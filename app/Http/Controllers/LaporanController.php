<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasMasuk;
use App\Models\KasKeluar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;


class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $userId = \Illuminate\Support\Facades\Auth::id();

        // Ambil list tahun
        $listTahun = collect([
            ...DB::table('kas_masuk')
                ->where('user_id', $userId)
                ->select(DB::raw('YEAR(tanggal_transaksi) as tahun'))
                ->pluck('tahun')
                ->toArray(),

            ...DB::table('kas_keluar')
                ->where('user_id', $userId)
                ->select(DB::raw('YEAR(tanggal) as tahun'))
                ->pluck('tahun')
                ->toArray(),
        ])->unique()->sortDesc()->values();

        // --- QUERY KAS MASUK ---
        $kasMasuk = DB::table('kas_masuk')
            ->where('user_id', $userId)
            ->when($request->tahun, fn($q) => $q->whereYear('tanggal_transaksi', $request->tahun))
            ->when($request->bulan, fn($q) => $q->whereMonth('tanggal_transaksi', $request->bulan))
            ->get();

        // --- QUERY KAS KELUAR ---
        $kasKeluar = DB::table('kas_keluar')
            ->where('user_id', $userId)
            ->when($request->tahun, fn($q) => $q->whereYear('tanggal', $request->tahun))
            ->when($request->bulan, fn($q) => $q->whereMonth('tanggal', $request->bulan))
            ->get();

        // Hitung total
        $totalMasuk = $kasMasuk->sum('total');
        $totalKeluar = $kasKeluar->sum('nominal');
        $selisihKas = $totalMasuk - $totalKeluar;

        // Gabungan laporan
        $laporan = $kasMasuk->map(function ($m) {
            return [
                'tanggal' => $m->tanggal_transaksi,
                'keterangan' => $m->keterangan,
                'kategori' => $m->kategori ?? '-',
                'metode_pembayaran' => $m->metode_pembayaran ?? '-',
                'kas_masuk' => $m->total,
                'kas_keluar' => 0,
                'saldo' => $m->total,
            ];
        })->merge(
                $kasKeluar->map(function ($k) {
                    return [
                        'tanggal' => $k->tanggal,
                        'deskripsi' => $k->deskripsi,
                        'kategori' => $k->kategori ?? '-',
                        'metode_pembayaran' => $k->metode_pembayaran ?? '-',
                        'penerima' => $k->penerima ?? '-',
                        'kas_masuk' => 0,
                        'kas_keluar' => $k->nominal,
                        'saldo' => -$k->nominal,
                    ];
                })
            )->sortByDesc('tanggal')->values();

        // KIRIM SEMUA DATA KE VIEW
        return view('laporan.index', compact(
            'kasMasuk',
            'kasKeluar',
            'totalMasuk',
            'totalKeluar',
            'selisihKas',
            'laporan',
            'listTahun'
        ));
    }

    public function exportPdf(Request $request)
    {
        // ambil data yang sama persis seperti di index
        $data = $this->getFilteredLaporan($request);

        $pdf = PDF::loadView('laporan.pdf', $data)->setPaper('A4', 'portrait');
        return $pdf->download('laporan-keuangan.pdf');
    }

    public function exportExcel(Request $request)
    {
        $filterType = $request->get('filter_type');
        $filterValue = $request->get('filter_value');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $userId = \Illuminate\Support\Facades\Auth::id();

        $kasMasukQuery = KasMasuk::where('user_id', $userId);
        $kasKeluarQuery = KasKeluar::where('user_id', $userId);

        // --- FILTER ---
        if ($filterType === 'harian' && $filterValue) {
            $kasMasukQuery->whereDate('tanggal_transaksi', $filterValue);
            $kasKeluarQuery->whereDate('tanggal', $filterValue);
        } elseif ($filterType === 'bulanan' && $filterValue) {
            $month = Carbon::parse($filterValue)->month;
            $year = Carbon::parse($filterValue)->year;
            $kasMasukQuery->whereMonth('tanggal_transaksi', $month)->whereYear('tanggal_transaksi', $year);
            $kasKeluarQuery->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
        } elseif ($filterType === 'rentang' && $startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            $kasMasukQuery->whereBetween('tanggal_transaksi', [$start, $end]);
            $kasKeluarQuery->whereBetween('tanggal', [$start, $end]);
        }

        $kasMasuk = $kasMasukQuery->orderBy('tanggal_transaksi', 'desc')->get();
        $kasKeluar = $kasKeluarQuery->orderBy('tanggal', 'desc')->get();

        $totalMasuk = $kasMasuk->sum('total');
        $totalKeluar = $kasKeluar->sum('nominal');
        $selisihKas = $totalMasuk - $totalKeluar;

        // --- GABUNG LAPORAN ---
        $laporan = collect();

        foreach ($kasMasuk as $m) {
            $laporan->push([
                'tanggal' => $m->tanggal_transaksi,
                'keterangan' => $m->keterangan ?? '-',
                'kategori' => $m->kategori ?? '-',
                'metode_pembayaran' => $m->metode_pembayaran ?? '-',
                'kas_masuk' => $m->total,
                'kas_keluar' => 0,
            ]);
        }

        foreach ($kasKeluar as $k) {
            $laporan->push([
                'tanggal' => $k->tanggal,
                'deskripsi' => $k->deskripsi ?? '-',
                'kategori' => $k->kategori ?? '-',
                'metode_pembayaran' => $k->metode_pembayaran ?? '-',
                'penerima' => $k->penerima ?? '-',
                'kas_masuk' => 0,
                'kas_keluar' => $k->nominal,
            ]);
        }

        $laporan = $laporan->sortByDesc('tanggal')->values();

        $saldo = 0;
        $laporan = $laporan->map(function ($item) use (&$saldo) {
            $saldo += ($item['kas_masuk'] - $item['kas_keluar']);
            $item['saldo'] = $saldo;
            return $item;
        });

        // --- DOWNLOAD EXCEL ---
        return Excel::download(
            new LaporanExport($laporan, $kasMasuk, $kasKeluar, $totalMasuk, $totalKeluar, $selisihKas),
            'laporan-keuangan.xlsx'
        );
    }


    private function getFilteredLaporan(Request $request)
    {
        $tahun = $request->get('tahun');
        $bulan = $request->get('bulan');
        $userId = \Illuminate\Support\Facades\Auth::id();

        $kasMasukQuery = KasMasuk::where('user_id', $userId);
        $kasKeluarQuery = KasKeluar::where('user_id', $userId);

        // FILTER TAHUN
        if ($tahun) {
            $kasMasukQuery->whereYear('tanggal_transaksi', $tahun);
            $kasKeluarQuery->whereYear('tanggal', $tahun);
        }

        // FILTER BULAN
        if ($bulan) {
            $kasMasukQuery->whereMonth('tanggal_transaksi', $bulan);
            $kasKeluarQuery->whereMonth('tanggal', $bulan);
        }

        // Ambil data
        $kasMasuk = $kasMasukQuery->orderBy('tanggal_transaksi', 'desc')->get();
        $kasKeluar = $kasKeluarQuery->orderBy('tanggal', 'desc')->get();

        // Hitung total
        $totalMasuk = $kasMasuk->sum('total');
        $totalKeluar = $kasKeluar->sum('nominal');
        $selisihKas = $totalMasuk - $totalKeluar;

        // Gabungkan laporan
        $laporan = collect();

        foreach ($kasMasuk as $m) {
            $laporan->push([
                'tanggal' => $m->tanggal_transaksi,
                'keterangan' => $m->keterangan ?? '-',
                'kategori' => $m->kategori ?? '-',
                'metode_pembayaran' => $m->metode_pembayaran ?? '-',
                'kas_masuk' => $m->total,
                'kas_keluar' => 0,
            ]);
        }

        foreach ($kasKeluar as $k) {
            $laporan->push([
                'tanggal' => $k->tanggal,
                'deskripsi' => $k->deskripsi ?? '-',
                'kategori' => $k->kategori ?? '-',
                'metode_pembayaran' => $k->metode_pembayaran ?? '-',
                'penerima' => $k->penerima ?? '-',
                'kas_masuk' => 0,
                'kas_keluar' => $k->nominal,
            ]);
        }

        // URUT TANGGAL TERBARU DULU
        $laporan = $laporan->sortByDesc('tanggal')->values();

        // Hitung saldo berjalan
        $saldo = 0;
        $laporan = $laporan->map(function ($item) use (&$saldo) {
            $saldo += ($item['kas_masuk'] - $item['kas_keluar']);
            $item['saldo'] = $saldo;
            return $item;
        });

        return [
            'laporan' => $laporan,
            'kasMasuk' => $kasMasuk,
            'kasKeluar' => $kasKeluar,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'selisihKas' => $selisihKas,
        ];
    }


}
