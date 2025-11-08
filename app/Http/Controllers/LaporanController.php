<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasMasuk;
use App\Models\KasKeluar;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter dari request
        $filterType = $request->get('filter_type'); // harian / bulanan / rentang
        $filterValue = $request->get('filter_value');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $kasMasukQuery = KasMasuk::query();
        $kasKeluarQuery = KasKeluar::query();

        // 🔹 Filter Harian
        if ($filterType === 'harian' && $filterValue) {
            $kasMasukQuery->whereDate('tanggal_transaksi', $filterValue);
            $kasKeluarQuery->whereDate('tanggal', $filterValue);
        }
        // 🔹 Filter Bulanan
        elseif ($filterType === 'bulanan' && $filterValue) {
            $month = Carbon::parse($filterValue)->month;
            $year = Carbon::parse($filterValue)->year;
            $kasMasukQuery->whereMonth('tanggal_transaksi', $month)->whereYear('tanggal_transaksi', $year);
            $kasKeluarQuery->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
        }
        // 🔹 Filter Rentang Tanggal (Custom Range)
        elseif ($filterType === 'rentang' && $startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            $kasMasukQuery->whereBetween('tanggal_transaksi', [$start, $end]);
            $kasKeluarQuery->whereBetween('tanggal', [$start, $end]);
        }

        // Ambil data dari database
        $kasMasuk = $kasMasukQuery->get();
        $kasKeluar = $kasKeluarQuery->get();

        // Hitung total
        $totalMasuk = $kasMasuk->sum('total');
        $totalKeluar = $kasKeluar->sum('nominal');
        $selisihKas = $totalMasuk - $totalKeluar;
        $saldoAkhir = $selisihKas; // saldo akhir sama dengan selisih kas

        // Gabungkan Kas Masuk & Kas Keluar
        $laporan = collect();

        foreach ($kasMasuk as $masuk) {
            $laporan->push([
                'tanggal' => $masuk->tanggal_transaksi,
                'keterangan' => $masuk->keterangan ?? '-',
                'kategori' => $masuk->kategori ?? '-',
                'metode' => $masuk->metode_pembayaran ?? '-',
                'kas_masuk' => $masuk->total,
                'kas_keluar' => 0,
            ]);
        }

        foreach ($kasKeluar as $keluar) {
            $laporan->push([
                'tanggal' => $keluar->tanggal,
                'keterangan' => $keluar->deskripsi ?? '-',
                'kategori' => $keluar->kategori ?? '-',
                'metode' => $keluar->metode_pembayaran ?? '-',
                'kas_masuk' => 0,
                'kas_keluar' => $keluar->nominal,
            ]);
        }

        // Urutkan dan hitung saldo berjalan
        $laporan = $laporan->sortBy('tanggal')->values();
        $saldo = 0;
        $laporan = $laporan->map(function ($item) use (&$saldo) {
            $saldo += ($item['kas_masuk'] - $item['kas_keluar']);
            $item['saldo'] = $saldo;
            return $item;
        });

        return view('laporan.index', compact(
            'laporan',
            'totalMasuk',
            'totalKeluar',
            'selisihKas',
            'saldoAkhir',
            'filterType',
            'filterValue',
            'startDate',
            'endDate'
        ));
    }

    // 🔹 EXPORT KE PDF
    public function exportPdf(Request $request)
    {
        // Ambil data yang sama seperti di index
        $laporanData = app(LaporanController::class)->index($request)->getData();

        $pdf = PDF::loadView('laporan.export', [
            'laporan' => $laporanData['laporan'],
            'totalMasuk' => $laporanData['totalMasuk'],
            'totalKeluar' => $laporanData['totalKeluar'],
            'selisihKas' => $laporanData['selisihKas'],
            'saldoAkhir' => $laporanData['saldoAkhir'],
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan_keuangan.pdf');
    }

    // 🔹 EXPORT KE EXCEL
    public function exportExcel(Request $request)
    {
        $laporanData = app(LaporanController::class)->index($request)->getData();

        $exportData = collect($laporanData['laporan'])->map(function ($item) {
            return [
                'Tanggal' => Carbon::parse($item['tanggal'])->format('d M Y'),
                'Keterangan' => $item['keterangan'],
                'Kategori' => $item['kategori'],
                'Metode' => $item['metode'],
                'Kas Masuk' => $item['kas_masuk'],
                'Kas Keluar' => $item['kas_keluar'],
                'Saldo' => $item['saldo'],
            ];
        });

        return Excel::download(new class($exportData) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            protected $data;
            public function __construct($data) { $this->data = $data; }
            public function collection() { return $this->data; }
            public function headings(): array { return array_keys($this->data->first() ?? []); }
        }, 'laporan_keuangan.xlsx');
    }
}
