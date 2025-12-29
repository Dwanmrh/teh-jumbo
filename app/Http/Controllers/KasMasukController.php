<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasMasuk;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KasMasukController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = KasMasuk::query();

        // Filter Role
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // 1. Filter Search
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('kode_kas', 'like', "%{$searchTerm}%")
                    ->orWhere('payment_method', 'like', "%{$searchTerm}%")
                    ->orWhere('keterangan', 'like', "%{$searchTerm}%")
                    ->orWhere('kategori', 'like', "%{$searchTerm}%");
            });
        }

        // 2. Filter Waktu
        $now = Carbon::now('Asia/Jakarta');
        if ($request->filled('filter_waktu')) {
            switch ($request->input('filter_waktu')) {
                case 'hari-ini':
                    $query->whereDate('tanggal_transaksi', $now->toDateString());
                    break;
                case 'minggu-ini':
                    $query->whereBetween('tanggal_transaksi', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]);
                    break;
                case 'bulan-ini':
                    $query->whereMonth('tanggal_transaksi', $now->month)
                        ->whereYear('tanggal_transaksi', $now->year);
                    break;
                case 'custom':
                    if ($request->filled('start_date') && $request->filled('end_date')) {
                        $query->whereBetween('tanggal_transaksi', [
                            Carbon::parse($request->input('start_date')),
                            Carbon::parse($request->input('end_date'))
                        ]);
                    }
                    break;
            }
        }

        // 3. Filter Harga
        if ($request->filled('filter_harga')) {
            $range = explode('-', $request->input('filter_harga'));
            if (count($range) === 2) {
                $query->whereBetween('total', [$range[0], $range[1]]);
            }
        }

        // 4. Filter Sumber (POS vs Manual) - TAMBAHAN BARU
        if ($request->filled('filter_sumber')) {
            $sumber = $request->input('filter_sumber');
            if ($sumber === 'pos') {
                $query->where('kategori', 'like', '%penjualan%');
            } elseif ($sumber === 'manual') {
                $query->where('kategori', 'not like', '%penjualan%');
            }
        }

        // 5. Sorting (Server Side) - TAMBAHAN BARU
        $sort = $request->input('sort', 'terbaru'); // Default terbaru
        switch ($sort) {
            case 'az':
                $query->orderBy('keterangan', 'asc');
                break;
            case 'za':
                $query->orderBy('keterangan', 'desc');
                break;
            case 'max':
                $query->orderBy('total', 'desc');
                break;
            case 'min':
                $query->orderBy('total', 'asc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('tanggal_transaksi', 'desc')->orderBy('created_at', 'desc');
                break;
        }

        // Paginate dan Append Query String (agar filter tidak hilang saat ganti halaman)
        $kasMasuk = $query->paginate(10)->withQueryString();

        return view('kas-masuk.index', compact('kasMasuk'));
    }

    public function create()
    {
        return view('kas-masuk.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // 2. Kalkulasi Total (Gunakan input())
            $total = $request->input('jumlah') * $request->input('harga_satuan');

            // 3. Generate Nomor Kas
            $tglFormat = date('Ymd', strtotime($request->input('tanggal_transaksi')));
            $prefix = 'KM-' . $tglFormat . '-';

            $last = KasMasuk::where('kode_kas', 'like', $prefix . '%')
                ->orderBy('kode_kas', 'desc')
                ->first();

            $nextNumber = 1;
            if ($last) {
                $parts = explode('-', $last->kode_kas);
                $lastNumber = end($parts);
                $nextNumber = intval($lastNumber) + 1;
            }

            $kodeKas = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // 4. Siapkan Data
            $saveData = [
                'user_id' => Auth::id(),
                'kode_kas' => $kodeKas,
                'tanggal_transaksi' => $request->input('tanggal_transaksi'),
                'kategori' => $request->input('kategori'),
                'keterangan' => $request->input('keterangan') ?? '-',
                'payment_method' => $request->input('metode_pembayaran'),
                'total' => $total,
                'jumlah' => $request->input('jumlah'),
                'harga_satuan' => $request->input('harga_satuan'),
            ];

            // 5. Simpan
            KasMasuk::create($saveData);

            DB::commit();
            return redirect()->route('kas-masuk.index')->with('success', 'Data kas masuk berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $kasMasuk = KasMasuk::findOrFail($id);
        } else {
            $kasMasuk = KasMasuk::where('user_id', $user->id)->findOrFail($id);
        }

        return view('kas-masuk.edit', compact('kasMasuk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            if ($user->role === 'admin') {
                $kasMasuk = KasMasuk::findOrFail($id);
            } else {
                $kasMasuk = KasMasuk::where('user_id', $user->id)->findOrFail($id);
            }

            $total = $request->input('jumlah') * $request->input('harga_satuan');

            // Update Data
            $kasMasuk->update([
                'tanggal_transaksi' => $request->input('tanggal_transaksi'),
                'kategori' => $request->input('kategori'),
                'keterangan' => $request->input('keterangan') ?? '-',
                'payment_method' => $request->input('metode_pembayaran'),
                'total' => $total,
                'jumlah' => $request->input('jumlah'),
                'harga_satuan' => $request->input('harga_satuan'),
            ]);

            DB::commit();

            return redirect()->route('kas-masuk.index')->with('success', 'Data berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = Auth::user();
            if ($user->role === 'admin') {
                $kasMasuk = KasMasuk::findOrFail($id);
            } else {
                $kasMasuk = KasMasuk::where('user_id', $user->id)->findOrFail($id);
            }
            $kasMasuk->delete();

            return redirect()->route('kas-masuk.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('kas-masuk.index')->with('error', 'Gagal menghapus data.');
        }
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:kas_masuk,id',
        ]);

        try {
            $ids = $request->input('ids'); // PERBAIKAN: Gunakan input()
            $user = Auth::user();

            if ($user->role === 'admin') {
                KasMasuk::whereIn('id', $ids)->delete();
            } else {
                KasMasuk::where('user_id', $user->id)->whereIn('id', $ids)->delete();
            }

            return redirect()->route('kas-masuk.index')->with('success', 'Data terpilih berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('kas-masuk.index')->with('error', 'Gagal menghapus data terpilih.');
        }
    }
}
