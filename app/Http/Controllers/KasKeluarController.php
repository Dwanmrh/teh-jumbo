<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasKeluar;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Carbon\CarbonInterface; // <--- 1. TAMBAHKAN INI

class KasKeluarController extends Controller
{
    /**
     * Tampilkan semua data kas keluar dengan filter & pencarian.
     */
    public function index(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role === 'admin') {
            $query = KasKeluar::query();
        } else {
            $query = KasKeluar::where('user_id', $user->id);
        }

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
                    // PERBAIKAN DI SINI: Gunakan CarbonInterface
                    $query->whereBetween('tanggal', [
                        $now->copy()->startOfWeek(CarbonInterface::MONDAY),
                        $now->copy()->endOfWeek(CarbonInterface::SUNDAY),
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
        $user = \Illuminate\Support\Facades\Auth::user();

        // Kasir must upload payment proof
        $buktiRule = $user->role === 'kasir' ? 'required|image|mimes:jpg,jpeg,png|max:2048' : 'nullable|image|mimes:jpg,jpeg,png|max:2048';

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string|max:255',
            'metode_pembayaran' => 'required|string|max:255',
            'penerima' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1',
            'bukti_pembayaran' => $buktiRule,
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

        $user = \Illuminate\Support\Facades\Auth::user();

        // Kasir must upload payment proof (but allow keeping existing one during update)
        $buktiRule = $user->role === 'kasir'
            ? 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            : 'nullable|image|mimes:jpg,jpeg,png|max:2048';

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string|max:255',
            'metode_pembayaran' => 'required|string|max:255',
            'penerima' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1',
            'bukti_pembayaran' => $buktiRule,
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

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:kas_keluar,id',
        ]);

        try {
            $ids = $request->ids;

            // Handle file deletion for selected records
            $records = KasKeluar::whereIn('id', $ids)->get();
            foreach ($records as $record) {
                // Check ownership if not admin (though route is currently admin only in web.php, let's be safe or consistent)
                // Actually web.php shows kas-keluar routes are shared/kasir access, but bulk destroy was put in admin group?
                // Wait, I put bulk destroy in admin group in web.php. 
                // If kasir needs it, I should move it. 
                // The user said "terapkan bulk actions untuk kas masuk dan kas keluar juga", didn't specify role.
                // Existing destroy allows kasir? destroy method doesn't check ownership explicitly but index does filter.
                // Let's assume admin for now as per my plan placement, or check ownership if I move it.
                // For now, just delete files.
                if ($record->bukti_pembayaran && Storage::disk('public')->exists($record->bukti_pembayaran)) {
                    Storage::disk('public')->delete($record->bukti_pembayaran);
                }
            }

            KasKeluar::whereIn('id', $ids)->delete();

            return redirect()->route('kas-keluar.index')->with('success', 'Data terpilih berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('kas-keluar.index')->with('error', 'Gagal menghapus data terpilih.');
        }
    }
}
