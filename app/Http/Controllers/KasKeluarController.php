<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasKeluar;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasKeluarController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $query = KasKeluar::query();
        } else {
            $query = KasKeluar::where('user_id', $user->id);
        }

        // 1. Filter Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('kategori', 'like', "%{$request->search}%")
                    // PERBAIKAN: Cari ke kolom payment_method
                    ->orWhere('payment_method', 'like', "%{$request->search}%")
                    ->orWhere('penerima', 'like', "%{$request->search}%")
                    ->orWhere('deskripsi', 'like', "%{$request->search}%")
                    ->orWhere('kode_kas', 'like', "%{$request->search}%");
            });
        }

        // 2. Filter Waktu
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

        // 3. Filter Harga
        if ($request->filter_harga) {
            $range = explode('-', $request->filter_harga);
            if (count($range) === 2) {
                $query->whereBetween('nominal', [$range[0], $range[1]]);
            }
        }

        $kasKeluar = $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->get();

        return view('kas-keluar.index', compact('kasKeluar'));
    }

    public function create()
    {
        return view('kas-keluar.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        // Staff/Kasir WAJIB upload bukti, Admin boleh kosong
        $buktiRule = $user->role === 'admin' ? 'nullable|image|mimes:jpg,jpeg,png|max:2048' : 'required|image|mimes:jpg,jpeg,png|max:2048';

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string|max:255',
            'metode_pembayaran' => 'required|string|max:255',
            'penerima' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1',
            'bukti_pembayaran' => $buktiRule,
            'deskripsi' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Handle File
            if ($request->hasFile('bukti_pembayaran')) {
                $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
                $validated['bukti_pembayaran'] = $path;
            }

            // PERBAIKAN: Mapping metode_pembayaran -> payment_method
            $validated['payment_method'] = $validated['metode_pembayaran'];
            unset($validated['metode_pembayaran']);

            $validated['user_id'] = $user->id;

            // Generate Kode Kas (KK-YYMM-XXX)
            $dateCode = date('ym', strtotime($request->tanggal));
            $last = KasKeluar::where('kode_kas', 'LIKE', 'KK-' . $dateCode . '-%')
                ->orderBy('kode_kas', 'desc')
                ->first();

            $num = 1;
            if ($last) {
                $lastNum = (int) substr($last->kode_kas, -3);
                $num = $lastNum + 1;
            }
            $validated['kode_kas'] = 'KK-' . $dateCode . '-' . str_pad($num, 3, '0', STR_PAD_LEFT);

            KasKeluar::create($validated);

            DB::commit();
            return redirect()->route('kas-keluar.index')->with('success', 'Data kas keluar berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $kasKeluar = KasKeluar::findOrFail($id);

        // Inject virtual attribute untuk form edit
        $kasKeluar->metode_pembayaran = $kasKeluar->payment_method;

        return view('kas-keluar.edit', compact('kasKeluar'));
    }

    public function update(Request $request, $id)
    {
        $kasKeluar = KasKeluar::findOrFail($id);
        $user = Auth::user();
        $buktiRule = 'nullable|image|mimes:jpg,jpeg,png|max:2048';

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string|max:255',
            'metode_pembayaran' => 'required|string|max:255',
            'penerima' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1',
            'bukti_pembayaran' => $buktiRule,
            'deskripsi' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('bukti_pembayaran')) {
                if ($kasKeluar->bukti_pembayaran && Storage::disk('public')->exists($kasKeluar->bukti_pembayaran)) {
                    Storage::disk('public')->delete($kasKeluar->bukti_pembayaran);
                }
                $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
                $validated['bukti_pembayaran'] = $path;
            }

            // PERBAIKAN: Mapping Update
            $validated['payment_method'] = $validated['metode_pembayaran'];
            unset($validated['metode_pembayaran']);

            $kasKeluar->update($validated);

            DB::commit();
            return redirect()->route('kas-keluar.index')->with('success', 'Data kas keluar berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

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
            $records = KasKeluar::whereIn('id', $ids)->get();

            foreach ($records as $record) {
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
