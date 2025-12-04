<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasMasuk;
use App\Models\KasKeluar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getLaporanData($request);
        return view('laporan.index', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getLaporanData($request);
        $pdf = Pdf::loadView('laporan.pdf', $data);
        $pdf->setPaper('a4', 'portrait');
        $filename = 'Laporan_Keuangan_' . date('d_m_Y_His') . '.pdf';
        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getLaporanData($request);
        $filename = 'Laporan_Keuangan_' . date('d_m_Y_His') . '.xlsx';
        return Excel::download(new LaporanExport(
            $data['laporan'],
            $data['saldoAwal'],
            $data['totalMasuk'],
            $data['totalKeluar'],
            $data['saldoAkhir']
        ), $filename);
    }

    private function getLaporanData(Request $request)
    {
        $userId = Auth::id();

        // --- 1. LOGIKA FILTER CERDAS ---
        $bulan = $request->filled('bulan') ? (int)$request->bulan : null;
        $tahun = $request->filled('tahun') ? (int)$request->tahun : null;

        // Jika halaman dibuka pertama kali (tanpa filter), default ke Tahun Ini
        if (!$bulan && !$tahun) {
            $tahun = (int)date('Y');
        }

        // Jika user pilih Bulan tapi Kosongkan Tahun, otomatis set Tahun Ini
        if ($bulan && empty($tahun)) {
            $tahun = (int)date('Y');
        }

        // --- 2. AMBIL LIST TAHUN (Untuk Dropdown) ---
        $tahunMasuk = DB::table('kas_masuk')->where('user_id', $userId)->selectRaw('YEAR(tanggal_transaksi) as year')->pluck('year');
        $tahunKeluar = DB::table('kas_keluar')->where('user_id', $userId)->selectRaw('YEAR(tanggal) as year')->pluck('year');

        // FIX ERROR COLLECTION: Gabung, Unik, Sort, dan paksa jadi ARRAY PHP BIASA
        $listTahun = $tahunMasuk->merge($tahunKeluar)
                        ->unique()
                        ->sortDesc()
                        ->values()
                        ->toArray(); // <-- PENTING: Mengubah Collection jadi Array

        // --- 3. QUERY DATA ---
        $queryMasuk = KasMasuk::where('user_id', $userId);
        $queryKeluar = KasKeluar::where('user_id', $userId);
        $saldoAwal = 0;

        // Logic Saldo Awal & Filter
        if ($tahun) {
            // Tentukan tanggal batas bawah
            $startMonth = $bulan ? $bulan : 1;
            $startDate = Carbon::createFromDate($tahun, $startMonth, 1)->startOfMonth();

            $prevMasuk = KasMasuk::where('user_id', $userId)->where('tanggal_transaksi', '<', $startDate)->sum('total');
            $prevKeluar = KasKeluar::where('user_id', $userId)->where('tanggal', '<', $startDate)->sum('nominal');
            $saldoAwal = $prevMasuk - $prevKeluar;

            // Apply Filter ke Query Utama
            $queryMasuk->whereYear('tanggal_transaksi', $tahun);
            $queryKeluar->whereYear('tanggal', $tahun);

            if ($bulan) {
                $queryMasuk->whereMonth('tanggal_transaksi', $bulan);
                $queryKeluar->whereMonth('tanggal', $bulan);
            }
        }

        $kasMasuk = $queryMasuk->orderBy('tanggal_transaksi', 'asc')->get();
        $kasKeluar = $queryKeluar->orderBy('tanggal', 'asc')->get();

        // --- 4. DATA PROCESSING ---
        $totalMasuk = $kasMasuk->sum('total');
        $totalKeluar = $kasKeluar->sum('nominal');
        $saldoAkhir = $saldoAwal + $totalMasuk - $totalKeluar;

        // Gabungkan
        $merged = collect();
        foreach ($kasMasuk as $m) {
            $merged->push([
                'tanggal' => Carbon::parse($m->tanggal_transaksi),
                'type' => 'masuk',
                'keterangan' => $m->keterangan,
                'kategori' => $m->kategori ?? 'Umum',
                'kode' => $m->kode_kas,
                'masuk' => $m->total,
                'keluar' => 0,
                'penerima' => '-'
            ]);
        }
        foreach ($kasKeluar as $k) {
            $merged->push([
                'tanggal' => Carbon::parse($k->tanggal),
                'type' => 'keluar',
                'keterangan' => $k->deskripsi ?? $k->kategori,
                'kategori' => $k->kategori ?? 'Umum',
                'kode' => $k->kode_kas,
                'masuk' => 0,
                'keluar' => $k->nominal,
                'penerima' => $k->penerima
            ]);
        }

        // Sorting & Running Balance
        $laporan = $merged->sortBy(function ($item) {
            return $item['tanggal']->timestamp;
        })->values();

        $runningBalance = $saldoAwal;
        $laporan = $laporan->map(function ($item) use (&$runningBalance) {
            $runningBalance = $runningBalance + $item['masuk'] - $item['keluar'];
            $item['saldo'] = $runningBalance;
            return $item;
        });

        return [
            'laporan' => $laporan,
            'listTahun' => $listTahun, // Ini sekarang sudah pasti Array
            'saldoAwal' => $saldoAwal,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'saldoAkhir' => $saldoAkhir,
            'selectedBulan' => $bulan,
            'selectedTahun' => $tahun,
        ];
    }
}
