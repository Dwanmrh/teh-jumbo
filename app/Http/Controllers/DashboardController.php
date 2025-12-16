<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasMasuk;
use App\Models\KasKeluar;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Setup Filter Waktu
        $tahun = $request->tahun ?? now()->year;
        $bulan = $request->bulan; // null jika setahun penuh
        $viewMode = $bulan ? 'daily' : 'monthly';

        // 2. Base Query (Cloneable)
        $qMasuk = KasMasuk::query();
        $qKeluar = KasKeluar::query();

        // 3. Eksekusi Logika Statistik
        // Kita menggunakan clone agar query satu tidak mempengaruhi query lainnya
        $saldoAwal = $this->calculateSaldoAwal(clone $qMasuk, clone $qKeluar, $tahun, $bulan, $viewMode);
        $chartData = $this->generateChartData(clone $qMasuk, clone $qKeluar, $tahun, $bulan, $viewMode, $saldoAwal);
        $periodStats = $this->calculatePeriodStats(clone $qMasuk, clone $qKeluar, $tahun, $bulan, $viewMode);
        $globalStats = $this->calculateGlobalStats(clone $qMasuk, clone $qKeluar);
        $pieStats = $this->calculatePieStats(clone $qMasuk, clone $qKeluar, $tahun, $bulan, $viewMode);
        $recentActivity = $this->fetchRecentActivity(clone $qMasuk, clone $qKeluar);

        // 4. Hitung Low Stock Alert (Global Stock)
        $lowStockCount = Product::where('stok', '<=', 10)->count();

        // 5. Hitung Saldo Akhir Periode (Saldo Awal + Surplus/Defisit Periode Ini)
        $saldoAkhirPeriode = $saldoAwal + $periodStats['surplusPeriode'];

        return view('dashboard', array_merge(
            [
                'viewMode' => $viewMode,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'saldoAkhir' => $saldoAkhirPeriode,
                'lowStockCount' => $lowStockCount,
            ],
            $chartData,
            $periodStats,
            $globalStats,
            $pieStats,
            ['recentActivity' => $recentActivity]
        ));
    }

    // --- Private Calculation Methods ---

    private function calculateSaldoAwal(Builder $qMasuk, Builder $qKeluar, $tahun, $bulan, $viewMode)
    {
        $startDate = $viewMode === 'daily'
            ? Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()
            : Carbon::createFromDate($tahun, 1, 1)->startOfYear();

        $prevMasuk = $qMasuk->where('tanggal_transaksi', '<', $startDate)->sum('total');
        $prevKeluar = $qKeluar->where('tanggal', '<', $startDate)->sum('nominal');

        return $prevMasuk - $prevKeluar;
    }

    private function generateChartData(Builder $qMasuk, Builder $qKeluar, $tahun, $bulan, $viewMode, $saldoAwal)
    {
        $labelList = [];
        $dataMasuk = [];
        $dataKeluar = [];
        $saldoKumulatif = [];
        $runningSaldo = $saldoAwal;

        if ($viewMode === 'daily') {
            $daysInMonth = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;

            // Group by Date
            $rawMasuk = $qMasuk->whereMonth('tanggal_transaksi', $bulan)
                ->whereYear('tanggal_transaksi', $tahun)
                ->selectRaw('DATE(tanggal_transaksi) as date, sum(total) as total')
                ->groupBy('date')->pluck('total', 'date');

            $rawKeluar = $qKeluar->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->selectRaw('DATE(tanggal) as date, sum(nominal) as total')
                ->groupBy('date')->pluck('total', 'date');

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $dateKey = Carbon::createFromDate($tahun, $bulan, $d)->format('Y-m-d');
                $masuk = $rawMasuk[$dateKey] ?? 0;
                $keluar = $rawKeluar[$dateKey] ?? 0;

                $labelList[] = $d; // Label tanggal angka (1, 2, 3...)
                $dataMasuk[] = $masuk;
                $dataKeluar[] = $keluar;
                $runningSaldo += ($masuk - $keluar);
                $saldoKumulatif[] = $runningSaldo;
            }
        } else {
            // Group by Month
            $rawMasuk = $qMasuk->whereYear('tanggal_transaksi', $tahun)
                ->selectRaw('MONTH(tanggal_transaksi) as month, sum(total) as total')
                ->groupBy('month')->pluck('total', 'month');

            $rawKeluar = $qKeluar->whereYear('tanggal', $tahun)
                ->selectRaw('MONTH(tanggal) as month, sum(nominal) as total')
                ->groupBy('month')->pluck('total', 'month');

            for ($m = 1; $m <= 12; $m++) {
                $masuk = $rawMasuk[$m] ?? 0;
                $keluar = $rawKeluar[$m] ?? 0;

                $labelList[] = Carbon::create()->month($m)->translatedFormat('M'); // Jan, Feb...
                $dataMasuk[] = $masuk;
                $dataKeluar[] = $keluar;
                $runningSaldo += ($masuk - $keluar);
                $saldoKumulatif[] = $runningSaldo;
            }
        }

        return compact('labelList', 'dataMasuk', 'dataKeluar', 'saldoKumulatif');
    }

    private function calculatePeriodStats(Builder $qMasuk, Builder $qKeluar, $tahun, $bulan, $viewMode)
    {
        $startDate = $viewMode === 'daily'
            ? Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()
            : Carbon::createFromDate($tahun, 1, 1)->startOfYear();

        $endDate = $viewMode === 'daily'
            ? Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()
            : Carbon::createFromDate($tahun, 1, 1)->endOfYear();

        $qMasuk->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
        $qKeluar->whereBetween('tanggal', [$startDate, $endDate]);

        $totalMasuk = $qMasuk->sum('total');
        $countMasuk = $qMasuk->count();
        $totalKeluar = $qKeluar->sum('nominal');
        $countKeluar = $qKeluar->count();

        // Hitung Tunai vs Non-Tunai
        // Optimization: Ambil hanya kolom payment_method dan total untuk efisiensi memori
        $masukCollection = $qMasuk->select('payment_method', 'total')->get();

        $pemasukanTunai = $masukCollection->filter(function($item) {
            $m = strtolower($item->payment_method ?? 'tunai');
            return in_array($m, ['tunai', 'cash', 'cash (tunai)']);
        })->sum('total');

        $pemasukanNonTunai = $totalMasuk - $pemasukanTunai;

        return [
            'totalMasuk' => $totalMasuk,
            'countMasuk' => $countMasuk,
            'totalKeluar' => $totalKeluar,
            'countKeluar' => $countKeluar,
            'pemasukanTunai' => $pemasukanTunai,
            'pemasukanNonTunai' => $pemasukanNonTunai,
            'surplusPeriode' => $totalMasuk - $totalKeluar
        ];
    }

    private function calculateGlobalStats(Builder $qMasuk, Builder $qKeluar)
    {
        return [
            'saldoRealSaatIni' => $qMasuk->sum('total') - $qKeluar->sum('nominal')
        ];
    }

    private function calculatePieStats(Builder $qMasuk, Builder $qKeluar, $tahun, $bulan, $viewMode)
    {
        if ($viewMode === 'daily') {
            $qMasuk->whereMonth('tanggal_transaksi', $bulan)->whereYear('tanggal_transaksi', $tahun);
            $qKeluar->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        } else {
            $qMasuk->whereYear('tanggal_transaksi', $tahun);
            $qKeluar->whereYear('tanggal', $tahun);
        }

        // Gunakan IFNULL/COALESCE untuk handle kategori kosong
        $katMasuk = $qMasuk->selectRaw("COALESCE(kategori, 'Lainnya') as kat, sum(total) as sum")
            ->groupBy('kat')->pluck('sum', 'kat');

        $katKeluar = $qKeluar->selectRaw("COALESCE(kategori, 'Lainnya') as kat, sum(nominal) as sum")
            ->groupBy('kat')->pluck('sum', 'kat');

        return [
            'masukLabel' => $katMasuk->keys(),
            'masukNominal' => $katMasuk->values(),
            'keluarLabel' => $katKeluar->keys(),
            'keluarNominal' => $katKeluar->values(),
        ];
    }

    private function fetchRecentActivity(Builder $qMasuk, Builder $qKeluar)
    {
        // Limit diperbesar sedikit untuk buffer sebelum di-merge
        $latestMasuk = $qMasuk->latest('tanggal_transaksi')->limit(10)->get()
            ->map(fn($i) => (object)[
                'type' => 'in',
                'date' => $i->tanggal_transaksi,
                'total' => $i->total,
                'keterangan' => $i->keterangan ?? 'Pemasukan Kas',
                'kategori' => $i->kategori ?? 'Umum'
            ]);

        $latestKeluar = $qKeluar->latest('tanggal')->limit(10)->get()
            ->map(fn($i) => (object)[
                'type' => 'out',
                'date' => $i->tanggal,
                'total' => $i->nominal,
                'keterangan' => $i->deskripsi ?? 'Pengeluaran Kas',
                'kategori' => $i->kategori ?? 'Operasional'
            ]);

        // Merge, Sort by Date Descending, Take 7
        return $latestMasuk->merge($latestKeluar)->sortByDesc('date')->take(7);
    }
}
