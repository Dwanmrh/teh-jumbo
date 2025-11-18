<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasMasuk;
use App\Models\KasKeluar;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->filter_type;
        $tanggal = $request->tanggal;
        $bulanInput = $request->bulan; // format YYYY-MM
        $tahun = $request->tahun;

        // List bulan
        $bulanList = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        // Ambil list tahun unik
        $tahunList = DB::table('kas_masuk')
            ->selectRaw('YEAR(tanggal_transaksi) as tahun')
            ->union(
                DB::table('kas_keluar')->selectRaw('YEAR(tanggal) as tahun')
            )
            ->groupBy('tahun')
            ->orderBy('tahun','desc')
            ->pluck('tahun');

        // INIT
        $labelList = [];
        $dataMasuk = [];
        $dataKeluar = [];
        $dataSelisih = [];

        /*
        | 1. FILTER HARIAN
        */
        if ($filterType === 'harian' && $tanggal) {

            $totalMasuk = KasMasuk::whereDate('tanggal_transaksi', $tanggal)->sum('total');
            $countMasuk = KasMasuk::whereDate('tanggal_transaksi', $tanggal)->count();

            $totalKeluar = KasKeluar::whereDate('tanggal', $tanggal)->sum('nominal');
            $countKeluar = KasKeluar::whereDate('tanggal', $tanggal)->count();

            $labelList = [Carbon::parse($tanggal)->translatedFormat('d M Y')];
            $dataMasuk = [$totalMasuk];
            $dataKeluar = [$totalKeluar];
            $dataSelisih = [$totalMasuk - $totalKeluar];

            $selisihKas = $totalMasuk - $totalKeluar;
            $saldoAkhir = $selisihKas;
        }

        /*
        | 2. FILTER BULANAN (dengan atau tanpa tahun)
        */
        elseif ($filterType === 'bulanan' && $bulanInput) {

            $explode = explode('-', $bulanInput);
            $year = $explode[0];
            $month = $explode[1];

            if ($tahun) {
                // User pilih tahun → November 2025
                $filterYear = $tahun;
            } else {
                // User TIDAK pilih tahun → tampilkan semua tahun
                $filterYear = null;
            }

            // Perhitungan total bulan
            $qMasuk = KasMasuk::whereMonth('tanggal_transaksi', $month);
            $qKeluar = KasKeluar::whereMonth('tanggal', $month);

            if ($filterYear) {
                $qMasuk->whereYear('tanggal_transaksi', $filterYear);
                $qKeluar->whereYear('tanggal', $filterYear);

                $daysInMonth = Carbon::create($filterYear, $month)->daysInMonth;
            } else {
                // tanpa tahun → gunakan jumlah hari max (31)
                $daysInMonth = 31;
            }

            $totalMasuk = $qMasuk->sum('total');
            $countMasuk = $qMasuk->count();
            $totalKeluar = $qKeluar->sum('nominal');
            $countKeluar = $qKeluar->count();

            $selisihKas = $totalMasuk - $totalKeluar;
            $saldoAkhir = $selisihKas;

            // Grafik per-hari
            for ($d = 1; $d <= $daysInMonth; $d++) {

                $q1 = KasMasuk::whereDay('tanggal_transaksi', $d)
                    ->whereMonth('tanggal_transaksi', $month);

                $q2 = KasKeluar::whereDay('tanggal', $d)
                    ->whereMonth('tanggal', $month);

                if ($filterYear) {
                    $q1->whereYear('tanggal_transaksi', $filterYear);
                    $q2->whereYear('tanggal', $filterYear);
                }

                $masuk = $q1->sum('total');
                $keluar = $q2->sum('nominal');

                $labelList[] = $d;
                $dataMasuk[] = $masuk;
                $dataKeluar[] = $keluar;
                $dataSelisih[] = $masuk - $keluar;
            }
        }

        /*
        | 3. TAHUNAN DEFAULT (12 bulan)
        */
        else {

            $tahun = $tahun ?? now()->year;

            for ($m = 1; $m <= 12; $m++) {

                $masuk = KasMasuk::whereYear('tanggal_transaksi', $tahun)
                    ->whereMonth('tanggal_transaksi', $m)->sum('total');

                $keluar = KasKeluar::whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $m)->sum('nominal');

                $labelList[] = $bulanList[$m - 1];
                $dataMasuk[] = $masuk;
                $dataKeluar[] = $keluar;
                $dataSelisih[] = $masuk - $keluar;
            }

            $totalMasuk = array_sum($dataMasuk);
            $totalKeluar = array_sum($dataKeluar);
            $countMasuk = KasMasuk::whereYear('tanggal_transaksi', $tahun)->count();
            $countKeluar = KasKeluar::whereYear('tanggal', $tahun)->count();
            $selisihKas = $totalMasuk - $totalKeluar;
            $saldoAkhir = $selisihKas;
        }

        /*
        | PIE CHART FILTER IKUT FILTER YANG DIPILIH
        */
        $kategoriMasuk = KasMasuk::selectRaw('kategori, SUM(total) as total');
        $kategoriKeluar = KasKeluar::selectRaw('kategori, SUM(nominal) as total');

        if ($filterType === 'harian') {
            $kategoriMasuk->whereDate('tanggal_transaksi', $tanggal);
            $kategoriKeluar->whereDate('tanggal', $tanggal);
        }
        elseif ($filterType === 'bulanan') {

            $kategoriMasuk->whereMonth('tanggal_transaksi', $month);
            $kategoriKeluar->whereMonth('tanggal', $month);

            if ($tahun) {
                $kategoriMasuk->whereYear('tanggal_transaksi', $tahun);
                $kategoriKeluar->whereYear('tanggal', $tahun);
            }
        }
        else {
            $kategoriMasuk->whereYear('tanggal_transaksi', $tahun);
            $kategoriKeluar->whereYear('tanggal', $tahun);
        }

        $kategoriMasuk = $kategoriMasuk->groupBy('kategori')->pluck('total','kategori')->toArray();
        $kategoriKeluar = $kategoriKeluar->groupBy('kategori')->pluck('total','kategori')->toArray();

        // Pie Chart
        $kategoriMasukLabel = array_keys($kategoriMasuk);
        $kategoriMasukNominal = array_values($kategoriMasuk);
        $kategoriKeluarLabel = array_keys($kategoriKeluar);
        $kategoriKeluarNominal = array_values($kategoriKeluar);

        /*
        | SALDO KUMULATIF
        */
        $saldoKumulatif = [];
        $running = 0;

        for ($i = 0; $i < count($dataMasuk); $i++) {
            $in = $dataMasuk[$i] ?? 0;
            $out = $dataKeluar[$i] ?? 0;
            $running += ($in - $out);
            $saldoKumulatif[] = $running;
        }

        /*
        RETURN
        */
        return view('dashboard', compact(
            'filterType','tanggal','bulanInput','tahun','tahunList','labelList',
            'dataMasuk','dataKeluar','dataSelisih',
            'totalMasuk','totalKeluar','selisihKas','saldoAkhir',
            'countMasuk','countKeluar',
            'kategoriMasukLabel','kategoriMasukNominal',
            'kategoriKeluarLabel','kategoriKeluarNominal',
            'saldoKumulatif'
        ));
    }
}
