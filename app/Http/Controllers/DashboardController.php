<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasMasuk;
use App\Models\KasKeluar;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // 1. TENTUKAN PERIODE WAKTU (FILTER)
        $bulanInput = $request->bulan; // Format: 01, 02, ... 12
        $tahunInput = $request->tahun ?? now()->year; // Default tahun sekarang

        // Mode Tampilan: 'daily' (jika bulan dipilih) atau 'monthly' (jika hanya tahun)
        $viewMode = $bulanInput ? 'daily' : 'monthly';

        // Siapkan Variable Data
        $labelList = [];
        $dataMasuk = [];
        $dataKeluar = [];
        $saldoKumulatif = [];

        // Query Dasar
        $qMasuk = KasMasuk::where('user_id', $userId);
        $qKeluar = KasKeluar::where('user_id', $userId);

        // 2. HITUNG SALDO AWAL (Uang sebelum periode yang dipilih)
        // Ini penting agar grafik saldo tidak mulai dari 0, tapi dari sisa uang bulan lalu
        $startPeriodDate = $viewMode === 'daily'
            ? Carbon::createFromDate($tahunInput, $bulanInput, 1)->startOfMonth()
            : Carbon::createFromDate($tahunInput, 1, 1)->startOfYear();

        $totalMasukLalu = (clone $qMasuk)->where('tanggal_transaksi', '<', $startPeriodDate)->sum('total');
        $totalKeluarLalu = (clone $qKeluar)->where('tanggal', '<', $startPeriodDate)->sum('nominal');
        $saldoAwal = $totalMasukLalu - $totalKeluarLalu;

        // 3. LOOPING DATA GRAFIK UTAMA
        $runningSaldo = $saldoAwal;

        if ($viewMode === 'daily') {
            // --- TAMPILAN HARIAN (DALAM 1 BULAN) ---
            $daysInMonth = Carbon::createFromDate($tahunInput, $bulanInput, 1)->daysInMonth;

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $date = Carbon::createFromDate($tahunInput, $bulanInput, $d)->format('Y-m-d');

                $masuk = (clone $qMasuk)->whereDate('tanggal_transaksi', $date)->sum('total');
                $keluar = (clone $qKeluar)->whereDate('tanggal', $date)->sum('nominal');

                $labelList[] = $d; // Label tanggal 1, 2, 3...
                $dataMasuk[] = $masuk;
                $dataKeluar[] = $keluar;

                $runningSaldo += ($masuk - $keluar);
                $saldoKumulatif[] = $runningSaldo;
            }

            // Filter Query untuk Cards Total (Hanya bulan ini)
            $qMasuk->whereMonth('tanggal_transaksi', $bulanInput)->whereYear('tanggal_transaksi', $tahunInput);
            $qKeluar->whereMonth('tanggal', $bulanInput)->whereYear('tanggal', $tahunInput);

        } else {
            // --- TAMPILAN BULANAN (DALAM 1 TAHUN) ---
            for ($m = 1; $m <= 12; $m++) {
                $masuk = (clone $qMasuk)->whereMonth('tanggal_transaksi', $m)->whereYear('tanggal_transaksi', $tahunInput)->sum('total');
                $keluar = (clone $qKeluar)->whereMonth('tanggal', $m)->whereYear('tanggal', $tahunInput)->sum('nominal');

                $labelList[] = Carbon::create()->month($m)->translatedFormat('M'); // Jan, Feb...
                $dataMasuk[] = $masuk;
                $dataKeluar[] = $keluar;

                $runningSaldo += ($masuk - $keluar);
                $saldoKumulatif[] = $runningSaldo;
            }

            // Filter Query untuk Cards Total (Setahun penuh)
            $qMasuk->whereYear('tanggal_transaksi', $tahunInput);
            $qKeluar->whereYear('tanggal', $tahunInput);
        }

        // 4. HITUNG TOTAL & KATEGORI (PIE CHART)
        $totalMasuk = $qMasuk->sum('total');
        $countMasuk = $qMasuk->count();
        $totalKeluar = $qKeluar->sum('nominal');
        $countKeluar = $qKeluar->count();
        $saldoAkhir = $saldoAwal + ($totalMasuk - $totalKeluar); // Saldo Real (Awal + Periode Ini)

        // Data Kategori (Group By)
        $katMasuk = (clone $qMasuk)->selectRaw('kategori, sum(total) as sum')->groupBy('kategori')->pluck('sum', 'kategori');
        $katKeluar = (clone $qKeluar)->selectRaw('kategori, sum(nominal) as sum')->groupBy('kategori')->pluck('sum', 'kategori');

        // 5. TRANSAKSI TERAKHIR (LATEST HISTORY)
        // Menggabungkan masuk & keluar untuk feed aktivitas (Optional tapi berguna)
        $latestMasuk = KasMasuk::where('user_id', $userId)->latest('tanggal_transaksi')->limit(5)->get()->map(function($item){
            $item->type = 'in'; $item->date = $item->tanggal_transaksi; return $item;
        });
        $latestKeluar = KasKeluar::where('user_id', $userId)->latest('tanggal')->limit(5)->get()->map(function($item){
            $item->type = 'out'; $item->date = $item->tanggal; $item->total = $item->nominal; return $item;
        });

        $recentActivity = $latestMasuk->merge($latestKeluar)->sortByDesc('date')->take(5);

        return view('dashboard', [
            'viewMode' => $viewMode,
            'tahun' => $tahunInput,
            'bulan' => $bulanInput,
            'labelList' => $labelList,
            'dataMasuk' => $dataMasuk,
            'dataKeluar' => $dataKeluar,
            'saldoKumulatif' => $saldoKumulatif,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'saldoAkhir' => $saldoAkhir, // Ini saldo akumulatif real
            'surplusPeriode' => $totalMasuk - $totalKeluar, // Ini profit/loss periode ini saja
            'countMasuk' => $countMasuk,
            'countKeluar' => $countKeluar,
            'masukLabel' => $katMasuk->keys(),
            'masukNominal' => $katMasuk->values(),
            'keluarLabel' => $katKeluar->keys(),
            'keluarNominal' => $katKeluar->values(),
            'recentActivity' => $recentActivity
        ]);
    }
}
