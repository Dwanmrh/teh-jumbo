<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasMasuk;
use App\Models\KasKeluar;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->filter_type;
        $tanggal = $request->tanggal;
        $bulan = $request->bulan;
        $tahun = $request->tahun ?? now()->year;

        // Untuk grafik per bulan
        $bulanList = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $tahunList = range(now()->year - 3, now()->year);
        $dataMasuk = [];
        $dataKeluar = [];
        $dataSelisih = [];

        // CASE 1: FILTER HARIAN
        if ($filterType === 'harian' && $tanggal) {
            $totalMasuk = KasMasuk::whereDate('tanggal_transaksi', $tanggal)->sum('total');
            $totalKeluar = KasKeluar::whereDate('tanggal', $tanggal)->sum('nominal');
            $selisihKas = $totalMasuk - $totalKeluar;
            $saldoAkhir = $selisihKas;

            // Hanya 1 titik data untuk grafik
            $dataMasuk = [$totalMasuk];
            $dataKeluar = [$totalKeluar];
            $dataSelisih = [$selisihKas];
            $bulanList = [Carbon::parse($tanggal)->translatedFormat('d M Y')];
        }

        // CASE 2: FILTER BULANAN
        elseif ($filterType === 'bulanan' && $bulan) {
            [$year, $month] = explode('-', $bulan);
            $totalMasuk = KasMasuk::whereYear('tanggal_transaksi', $year)
                ->whereMonth('tanggal_transaksi', $month)->sum('total');
            $totalKeluar = KasKeluar::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)->sum('nominal');
            $selisihKas = $totalMasuk - $totalKeluar;
            $saldoAkhir = $selisihKas;

            // Grafik harian dalam bulan itu
            $daysInMonth = Carbon::create($year, $month)->daysInMonth;
            $bulanList = range(1, $daysInMonth);
            foreach ($bulanList as $day) {
                $date = Carbon::create($year, $month, $day)->toDateString();
                $masuk = KasMasuk::whereDate('tanggal_transaksi', $date)->sum('total');
                $keluar = KasKeluar::whereDate('tanggal', $date)->sum('nominal');
                $dataMasuk[] = $masuk;
                $dataKeluar[] = $keluar;
                $dataSelisih[] = $masuk - $keluar;
            }
        }

        // CASE 3: FILTER TAHUNAN / DEFAULT
        else {
            for ($i = 1; $i <= 12; $i++) {
                $masuk = KasMasuk::whereYear('tanggal_transaksi', $tahun)
                    ->whereMonth('tanggal_transaksi', $i)->sum('total');
                $keluar = KasKeluar::whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $i)->sum('nominal');
                $dataMasuk[] = $masuk;
                $dataKeluar[] = $keluar;
                $dataSelisih[] = $masuk - $keluar;
            }

            $totalMasuk = array_sum($dataMasuk);
            $totalKeluar = array_sum($dataKeluar);
            $selisihKas = $totalMasuk - $totalKeluar;
            $saldoAkhir = $selisihKas;
        }

        return view('dashboard', compact(
            'filterType',
            'tanggal',
            'bulan',
            'tahun',
            'tahunList',
            'bulanList',
            'dataMasuk',
            'dataKeluar',
            'dataSelisih',
            'totalMasuk',
            'totalKeluar',
            'selisihKas',
            'saldoAkhir'
        ));
    }
}
