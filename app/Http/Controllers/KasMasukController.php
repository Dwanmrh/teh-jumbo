<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasMasuk;
use Carbon\Carbon;

class KasMasukController extends Controller
{
    /**
     * Menampilkan semua data kas masuk.
     */
   public function index(Request $request)
{
    $query = KasMasuk::query();
    $tz = 'Asia/Jakarta';
    $now = Carbon::now($tz);

    // 🔍 Filter search (tambahkan kode_kas)
    if ($request->search) {
        $query->where(function($q) use ($request) {
            $q->where('kode_kas', 'like', "%{$request->search}%")
              ->orWhere('metode_pembayaran', 'like', "%{$request->search}%")
              ->orWhere('keterangan', 'like', "%{$request->search}%");
        });
    }

    // Filter waktu
    if ($request->filter_waktu) {
        switch ($request->filter_waktu) {
            case 'hari-ini':
                $query->whereDate('tanggal_transaksi', $now->toDateString());
                break;
            case 'kemarin':
                $query->whereDate('tanggal_transaksi', $now->copy()->subDay()->toDateString());
                break;
            case 'minggu-ini':
                $query->whereBetween('tanggal_transaksi', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]);
                break;
            case 'bulan-ini':
                $query->whereMonth('tanggal_transaksi', $now->month);
                break;
            case 'bulan-lalu': // ➡️ Tambahan: bulan lalu
                $lastMonth = $now->copy()->subMonth();
                $query->whereBetween('tanggal_transaksi', [$lastMonth->copy()->startOfMonth(), $lastMonth->copy()->endOfMonth()]);
                break;
            case 'tahun-ini':
                $query->whereYear('tanggal_transaksi', $now->year);
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

    // Filter harga
    if ($request->filter_harga) {
        $range = explode('-', $request->filter_harga);
        if (count($range) === 2) {
            $query->whereBetween('total', [$range[0], $range[1]]);
        }
    }

    $kasMasuk = $query->orderBy('tanggal_transaksi', 'desc')->get();


    return view('kas-masuk.index', compact('kasMasuk'));
}


    public function create()
    {
        return view('kas-masuk.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|max:255',
        ]);

        $validated['total'] = $validated['jumlah'] * $validated['harga_satuan'];
        KasMasuk::create($validated);

        return redirect()->route('kas-masuk.index')->with('success', 'Kas masuk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kasMasuk = KasMasuk::findOrFail($id);
        return view('kas-masuk.edit', compact('kasMasuk'));
    }

    public function update(Request $request, $id)
    {
        $kasMasuk = KasMasuk::findOrFail($id);

        $validated = $request->validate([
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|max:255',
        ]);

        $validated['total'] = $validated['jumlah'] * $validated['harga_satuan'];
        $kasMasuk->update($validated);

        return redirect()->route('kas-masuk.index')->with('success', 'Kas masuk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kasMasuk = KasMasuk::findOrFail($id);
        $kasMasuk->delete();

        return redirect()->route('kas-masuk.index')->with('success', 'Kas masuk berhasil dihapus.');
    }
}
