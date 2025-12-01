<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasKeluar;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class KasKeluarController extends Controller
{
    /**
     * Tampilkan semua data kas keluar dengan filter & pencarian.
     */
    public function index(Request $request)
    {
        $query = KasKeluar::where('user_id', \Illuminate\Support\Facades\Auth::id());

        // 🔍 Pencarian berdasarkan kategori, metode, penerima, atau deskripsi
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('kategori', 'like', "%{$request->search}%")
                    ->orWhere('metode_pembayaran', 'like', "%{$request->search}%")
                    ->orWhere('penerima', 'like', "%{$request->search}%")
                    ->orWhere('deskripsi', 'like', "%{$request->search}%");
            });
        }

        // 📅 Filter tanggal
        $tz = 'Asia/Jakarta';
        $now = Carbon::now($tz);

        if ($request->filter_waktu) {
            switch ($request->filter_waktu) {
                case 'hari-ini':
                    $query->whereDate('tanggal', $now->toDateString());
                    break;
                case 'kemarin':
                    $query->whereDate('tanggal', $now->copy()->subDay()->toDateString());
                    break;
                case 'minggu-ini':
                    $query->whereBetween('tanggal', [
                        $now->copy()->startOfWeek(Carbon::MONDAY),
                        $now->copy()->endOfWeek(Carbon::SUNDAY),
                    ]);
                    break;
                case 'bulan-ini':
                    $query->whereBetween('tanggal', [
                        $now->copy()->startOfMonth(),
                        $now->copy()->endOfMonth(),
                    ]);
                    break;
                case 'bulan-lalu':
                    $query->whereBetween('tanggal', [
                        $now->copy()->subMonthNoOverflow()->startOfMonth(),
                        $now->copy()->subMonthNoOverflow()->endOfMonth(),
                    ]);
                    break;
                case 'tahun-ini':
                    $query->whereBetween('tanggal', [
                        $now->copy()->startOfYear(),
                        $now->copy()->endOfYear(),
                    ]);
                    break;
                case 'custom':
                    if ($request->start_date && $request->end_date) {
                        $query->whereBetween('tanggal', [
                            Carbon::parse($request->start_date, $tz)->startOfDay(),
                            Carbon::parse($request->end_date, $tz)->endOfDay(),
                        ]);
                    }
                    break;
            }
        }

        if ($request->filter_harga) {
            $range = explode('-', $request->filter_harga);
            if (count($range) === 2) {
                $query->whereBetween('nominal', [$range[0], $range[1]]);
            }
        }

        $kasKeluar = $query->orderBy('tanggal', 'desc')->get();

        return view('kas-keluar.index', compact('kasKeluar'));
    }

    /**
     * Form tambah kas keluar.
     */
    public function create()
    {
        return view('kas-keluar.create');
    }

    /**
     * Simpan data kas keluar baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string|max:255',
            'metode_pembayaran' => 'required|string|max:255',
            'penerima' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            $validated['bukti_pembayaran'] = $path;
        }

        $validated['user_id'] = \Illuminate\Support\Facades\Auth::id();
        KasKeluar::create($validated);

        return redirect()->route('kas-keluar.index')->with('success', 'Data kas keluar berhasil ditambahkan.');
    }

    /**
     * Form edit kas keluar.
     */
    public function edit($id)
    {
        $kasKeluar = KasKeluar::findOrFail($id);
        return view('kas-keluar.edit', compact('kasKeluar'));
    }

    /**
     * Update data kas keluar.
     */
    public function update(Request $request, $id)
    {
        $kasKeluar = KasKeluar::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string|max:255',
            'metode_pembayaran' => 'required|string|max:255',
            'penerima' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        // 🔁 Hapus file lama jika ada file baru
        if ($request->hasFile('bukti_pembayaran')) {
            if ($kasKeluar->bukti_pembayaran && Storage::disk('public')->exists($kasKeluar->bukti_pembayaran)) {
                Storage::disk('public')->delete($kasKeluar->bukti_pembayaran);
            }
            $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            $validated['bukti_pembayaran'] = $path;
        }

        $kasKeluar->update($validated);

        return redirect()->route('kas-keluar.index')->with('success', 'Data kas keluar berhasil diperbarui.');
    }

    /**
     * Hapus data kas keluar.
     */
    public function destroy($id)
    {
        $kasKeluar = KasKeluar::findOrFail($id);

        if ($kasKeluar->bukti_pembayaran && Storage::disk('public')->exists($kasKeluar->bukti_pembayaran)) {
            Storage::disk('public')->delete($kasKeluar->bukti_pembayaran);
        }

        $kasKeluar->delete();

        return redirect()->route('kas-keluar.index')->with('success', 'Data kas keluar berhasil dihapus.');
    }
}
