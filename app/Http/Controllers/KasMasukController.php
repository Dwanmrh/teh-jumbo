<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasMasuk;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasMasukController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $query = KasMasuk::query();
        } else {
            $query = KasMasuk::where('user_id', $user->id);
        }
        $now = Carbon::now('Asia/Jakarta');

        // 1. Filter Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_kas', 'like', "%{$request->search}%")
                    // PERBAIKAN: Sesuaikan nama kolom database (payment_method)
                    ->orWhere('payment_method', 'like', "%{$request->search}%")
                    ->orWhere('keterangan', 'like', "%{$request->search}%");
            });
        }

        // 2. Filter Waktu (Tetap sama)
        if ($request->filter_waktu) {
            switch ($request->filter_waktu) {
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
                    if ($request->start_date && $request->end_date) {
                        $query->whereBetween('tanggal_transaksi', [
                            Carbon::parse($request->start_date),
                            Carbon::parse($request->end_date)
                        ]);
                    }
                    break;
            }
        }

        // 3. Filter Harga (Tetap sama)
        if ($request->filter_harga) {
            $range = explode('-', $request->filter_harga);
            if (count($range) === 2) {
                $query->whereBetween('total', [$range[0], $range[1]]);
            }
        }

        $kasMasuk = $query
            ->orderByRaw("CASE
                WHEN keterangan LIKE '%POS%' THEN 0
                ELSE 1
            END")
            ->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kas-masuk.index', compact('kasMasuk'));
    }

    public function create()
    {
        return view('kas-masuk.create');
    }

    public function store(Request $request)
    {
        // Validasi menggunakan nama input dari FORM ('metode_pembayaran')
        $validated = $request->validate([
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Format tanggal (YYYYMMDD)
            $tglFormat = date('Ymd', strtotime($request->tanggal_transaksi));

            // Generate Nomor Kas Manual
            $last = KasMasuk::whereDate('tanggal_transaksi', $request->tanggal_transaksi)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($last) {
                // Ambil 3 digit terakhir, handle jika kode bukan format standar
                preg_match('/-(\d{3})$/', $last->kode_kas, $matches);
                $lastNumber = isset($matches[1]) ? intval($matches[1]) : 0;
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $kodeKas = 'KM-' . $tglFormat . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // --- PERBAIKAN DATA SEBELUM SIMPAN ---
            // 1. Mapping 'metode_pembayaran' (Input) ke 'payment_method' (Database)
            $validated['payment_method'] = $validated['metode_pembayaran'];
            unset($validated['metode_pembayaran']); // Hapus key lama agar tidak error

            // 2. Set Data Lainnya
            $validated['total'] = $validated['jumlah'] * $validated['harga_satuan'];
            $validated['user_id'] = Auth::id();
            $validated['keterangan'] = $validated['keterangan'] ?? '-';
            $validated['kode_kas'] = $kodeKas;

            // Simpan
            KasMasuk::create($validated);

            DB::commit();
            return redirect()->route('kas-masuk.index')->with('success', 'Data kas masuk berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (Auth::user()->role === 'admin') {
            $kasMasuk = KasMasuk::findOrFail($id);
        } else {
            $kasMasuk = KasMasuk::where('user_id', Auth::id())->findOrFail($id);
        }

        // Inject atribut 'metode_pembayaran' virtual agar form edit terbaca
        // Karena di database namanya 'payment_method', tapi form pakai 'metode_pembayaran'
        $kasMasuk->metode_pembayaran = $kasMasuk->payment_method;

        return view('kas-masuk.edit', compact('kasMasuk'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            if (Auth::user()->role === 'admin') {
                $kasMasuk = KasMasuk::findOrFail($id);
            } else {
                $kasMasuk = KasMasuk::where('user_id', Auth::id())->findOrFail($id);
            }

            // --- PERBAIKAN MAPPING UPDATE ---
            $validated['payment_method'] = $validated['metode_pembayaran'];
            unset($validated['metode_pembayaran']);

            $validated['total'] = $validated['jumlah'] * $validated['harga_satuan'];
            $validated['keterangan'] = $validated['keterangan'] ?? '-';

            $kasMasuk->update($validated);

            DB::commit();

            return redirect()->route('kas-masuk.index')
                ->with('success', 'Data berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            if (Auth::user()->role === 'admin') {
                $kasMasuk = KasMasuk::findOrFail($id);
            } else {
                $kasMasuk = KasMasuk::where('user_id', Auth::id())->findOrFail($id);
            }
            $kasMasuk->delete();

            return redirect()->route('kas-masuk.index')
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('kas-masuk.index')
                ->with('error', 'Gagal menghapus data.');
        }
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:kas_masuk,id',
        ]);

        try {
            if (Auth::user()->role === 'admin') {
                KasMasuk::whereIn('id', $request->ids)->delete();
            } else {
                KasMasuk::where('user_id', Auth::id())->whereIn('id', $request->ids)->delete();
            }

            return redirect()->route('kas-masuk.index')->with('success', 'Data terpilih berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('kas-masuk.index')->with('error', 'Gagal menghapus data terpilih.');
        }
    }
}
