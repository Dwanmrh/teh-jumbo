<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasMasuk;
use App\Models\KasKeluar;
use App\Models\Product;
use App\Models\User;
use App\Models\Outlet;
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
        return $pdf->download('Laporan_Lengkap_' . date('d_m_Y_His') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getLaporanData($request);

        return Excel::download(new LaporanExport(
            $data['laporan'],
            $data['saldoAwal'],
            $data['totalMasuk'],
            $data['totalKeluar'],
            $data['saldoAkhir'],
            $data['sisaUangFisik'],
            $data['selectedBulan'],
            $data['selectedTahun']
        ), 'Laporan_Keuangan_' . date('d_m_Y_His') . '.xlsx');
    }

    /**
     * --- LOGIKA UTAMA PENGAMBILAN DATA ---
     */
    private function getLaporanData(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';

        // 1. Filter Waktu (Bulan/Tahun atau Tanggal Spesifik)
        $date  = $request->filled('date') ? $request->date : null;
        $bulan = $request->filled('bulan') ? (int)$request->bulan : null;
        $tahun = $request->has('tahun') ? ($request->filled('tahun') ? (int)$request->tahun : null) : (int)date('Y');

        // Jika filter harian aktif, abaikan bulan/tahun
        if ($date) {
            $bulan = null;
            // Tahun tetap diambil dari tanggal untuk keperluan export/display jika perlu
            $tahun = Carbon::parse($date)->year;
        }

        // 2. Transaksi Raw (Filtered)
        $transactions = $this->getRawTransactions($user, $isAdmin, $tahun, $bulan, $date);

        // 3. Helper Perhitungan Saldo & Cash Flow
        // Pisahkan Cash (Tunai) dan Non-Cash (QRIS/Transfer)
        $masukCash = $transactions['masuk']->filter(function($i) {
            $method = strtolower($i->payment_method ?? 'tunai');
            return in_array($method, ['tunai', 'cash']);
        })->sum('total');

        $masukNonCash = $transactions['masuk']->filter(function($i) {
            $method = strtolower($i->payment_method ?? 'tunai');
            return !in_array($method, ['tunai', 'cash']);
        })->sum('total');

        // Asumsi: Kas Keluar (Belanja Harian) selalu pakai Uang Tunai Laci
        $totalKeluar = $transactions['keluar']->sum('nominal');

        // 4. Hitung Saldo Awal (Diset 0 untuk melihat pergerakan periode ini saja)
        $saldoAwalTotal = 0;

        // 5. Hitung Sisa Uang Fisik (Real Cash Flow Periode Ini)
        // Rumus: Uang Tunai Masuk - Uang Tunai Keluar
        $sisaUangFisik = $masukCash - $totalKeluar;

        // 6. Format Laporan Keuangan (Mutasi)
        $laporan = $this->formatAndCalculateRunningBalance($transactions['masuk'], $transactions['keluar'], $saldoAwalTotal, $isAdmin);

        // --- DATA TAMBAHAN ---
        $listTahun    = $this->getListTahun($user, $isAdmin);
        $productStats = $this->getProductStats($transactions['masuk'], $isAdmin, $user);
        $outletStats  = $isAdmin ? $this->getOutletStats($transactions['masuk']) : [];
        $userStats    = $isAdmin ? $this->getUserStats($transactions['masuk']) : [];

        return [
            // Data Keuangan Utama
            'laporan'       => $laporan,
            'listTahun'     => $listTahun,
            'selectedDate'  => $date,
            'selectedBulan' => $bulan,
            'selectedTahun' => $tahun,

            // Ringkasan Card
            'saldoAwal'         => 0,
            'totalMasuk'        => $transactions['masuk']->sum('total'), // Total Omzet (Cash + QRIS)
            'totalMasukCash'    => $masukCash,    // Khusus Cash
            'totalMasukNonCash' => $masukNonCash, // Khusus QRIS/Transfer
            'totalKeluar'       => $totalKeluar,
            'saldoAkhir'        => $laporan->last()['saldo'] ?? $saldoAwalTotal, // Netto Total
            'sisaUangFisik'     => $sisaUangFisik, // Estimasi Uang di Laci

            // Data Statistik
            'productStats'  => $productStats,
            'outletStats'   => $outletStats,
            'userStats'     => $userStats,
        ];
    }

    // --- HELPER FUNCTIONS ---

    private function getListTahun($user, $isAdmin)
    {
        $qMasuk = DB::table('kas_masuk')->selectRaw('YEAR(tanggal_transaksi) as year');
        $qKeluar = DB::table('kas_keluar')->selectRaw('YEAR(tanggal) as year');
        if (!$isAdmin) {
            $qMasuk->where('user_id', $user->id);
            $qKeluar->where('user_id', $user->id);
        }
        return $qMasuk->pluck('year')->merge($qKeluar->pluck('year'))->unique()->sortDesc()->values()->toArray();
    }

    private function getRawTransactions($user, $isAdmin, $tahun, $bulan, $date = null)
    {
        $search = request('search');

        $qMasuk = KasMasuk::query()->when($isAdmin, function($q) { $q->with('user'); });
        $qKeluar = KasKeluar::query()->when($isAdmin, function($q) { $q->with('user'); });

        if (!$isAdmin) {
            $qMasuk->where('user_id', $user->id);
            $qKeluar->where('user_id', $user->id);
        }

        // Filter Logic: Date Specific OR Month/Year
        if ($date) {
            $qMasuk->whereDate('tanggal_transaksi', $date);
            $qKeluar->whereDate('tanggal', $date);
        } elseif ($tahun) {
            $qMasuk->whereYear('tanggal_transaksi', $tahun);
            $qKeluar->whereYear('tanggal', $tahun);
            if ($bulan) {
                $qMasuk->whereMonth('tanggal_transaksi', $bulan);
                $qKeluar->whereMonth('tanggal', $bulan);
            }
        }

        if ($search) {
            $qMasuk->where(function($q) use ($search) {
                $q->where('keterangan', 'LIKE', "%{$search}%")
                ->orWhere('kode_kas', 'LIKE', "%{$search}%")
                ->orWhere('payment_method', 'LIKE', "%{$search}%")
                ->orWhere('total', 'LIKE', "%{$search}%");
            });

            $qKeluar->where(function($q) use ($search) {
                $q->where('deskripsi', 'LIKE', "%{$search}%")
                ->orWhere('kategori', 'LIKE', "%{$search}%")
                ->orWhere('kode_kas', 'LIKE', "%{$search}%")
                ->orWhere('nominal', 'LIKE', "%{$search}%");
            });
        }

        return [
            'masuk'  => $qMasuk->orderBy('tanggal_transaksi', 'asc')->get(),
            'keluar' => $qKeluar->orderBy('tanggal', 'asc')->get()
        ];
    }

    private function formatAndCalculateRunningBalance($kasMasuk, $kasKeluar, $saldoAwal, $isAdmin)
    {
        $mappedMasuk = $kasMasuk->map(function ($item) use ($isAdmin) {
            return [
                'tanggal'    => Carbon::parse($item->tanggal_transaksi),
                'type'       => 'masuk',
                'keterangan' => $item->keterangan . (($isAdmin && $item->user) ? ' (' . $item->user->name . ')' : ''),
                'kategori'   => $item->kategori ?? 'Pemasukan',
                'payment'    => $item->payment_method ?? 'Tunai',
                'kode'       => $item->kode_kas,
                'masuk'      => (float) $item->total,
                'keluar'     => 0,
                'saldo'      => 0
            ];
        });

        $mappedKeluar = $kasKeluar->map(function ($item) use ($isAdmin) {
            return [
                'tanggal'    => Carbon::parse($item->tanggal),
                'type'       => 'keluar',
                'keterangan' => ($item->deskripsi ?? $item->kategori) . (($isAdmin && $item->user) ? ' (' . $item->user->name . ')' : ''),
                'kategori'   => $item->kategori ?? 'Pengeluaran',
                'payment'    => 'Tunai', // Asumsi pengeluaran kasir selalu tunai
                'kode'       => $item->kode_kas,
                'masuk'      => 0,
                'keluar'     => (float) $item->nominal,
                'saldo'      => 0
            ];
        });

        $sorted = $mappedMasuk->concat($mappedKeluar)->sortBy(function ($item) {
            return $item['tanggal']->timestamp;
        })->values();

        $runningBalance = $saldoAwal;
        return $sorted->map(function ($item) use (&$runningBalance) {
            $runningBalance = $runningBalance + $item['masuk'] - $item['keluar'];
            $item['saldo'] = $runningBalance;
            return $item;
        });
    }

    private function getProductStats($kasMasuk, $isAdmin, $user)
    {
        $query = Product::query();
        if (!$isAdmin) $query->where('user_id', $user->id);

        $allProducts = $query->get();
        $inventory = [
            'total_sku'   => $allProducts->count(),
            'total_stok'  => $allProducts->sum('stok'),
            'asset_value' => $allProducts->sum(fn($p) => $p->stok * $p->modal),
            'low_stock'   => $allProducts->where('stok', '<=', 10)->count()
        ];

        $productSales = [];
        foreach ($kasMasuk as $trx) {
            if (!empty($trx->detail_items)) {
                $items = is_string($trx->detail_items) ? json_decode($trx->detail_items, true) : $trx->detail_items;
                if (is_array($items)) {
                    foreach ($items as $item) {
                        $name = $item['name'] ?? 'Unknown';
                        if (!isset($productSales[$name])) $productSales[$name] = ['qty' => 0, 'total' => 0];
                        $productSales[$name]['qty'] += intval($item['qty'] ?? 0);
                        $productSales[$name]['total'] += intval($item['subtotal'] ?? 0);
                    }
                }
            }
        }
        uasort($productSales, fn($a, $b) => $b['qty'] <=> $a['qty']);

        return [
            'inventory'   => $inventory,
            'top_products'=> array_slice($productSales, 0, 5)
        ];
    }

    private function getOutletStats($kasMasuk)
    {
        $outlets = Outlet::with('users')->get();
        return $outlets->map(function($outlet) use ($kasMasuk) {
            $userIds = $outlet->users->pluck('id')->toArray();
            return [
                'name'        => $outlet->name,
                'staff_count' => $outlet->users->count(),
                'omzet'       => $kasMasuk->whereIn('user_id', $userIds)->sum('total'),
                'trx_count'   => $kasMasuk->whereIn('user_id', $userIds)->count()
            ];
        })->sortByDesc('omzet')->values();
    }

    private function getUserStats($kasMasuk)
    {
        $users = User::with('outlet')->get();
        return $users->map(function($u) use ($kasMasuk) {
            $userTrans = $kasMasuk->where('user_id', $u->id);
            return [
                'name'      => $u->name,
                'outlet'    => $u->outlet ? $u->outlet->name : '-',
                'trx_count' => $userTrans->count(),
                'omzet'     => $userTrans->sum('total'),
            ];
        })->sortByDesc('omzet')->values();
    }
}
