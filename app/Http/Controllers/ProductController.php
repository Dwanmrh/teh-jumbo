<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Outlet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Mulai Query Builder
        $query = Product::query();

        // --- LOGIKA FILTER OUTLET ---

        // 1. Jika User adalah KASIR (Punya outlet_id & bukan admin)
        // Maka HANYA tampilkan produk dari outlet tersebut.
        if ($user->role !== 'admin' && $user->outlet_id) {
            $query->where('outlet_id', $user->outlet_id);
        }
        // 2. Jika User adalah ADMIN
        // Cek apakah Admin sedang memfilter outlet tertentu via Dropdown
        elseif ($user->role === 'admin') {
            if ($request->has('outlet_filter') && $request->outlet_filter != '') {
                $query->where('outlet_id', $request->outlet_filter);
            }
            // Jika tidak ada filter, otomatis tampilkan semua (default)
        }

        // Eksekusi Query
        $products = $query->latest()->get();

        // Ambil data outlet untuk dropdown filter (Hanya Admin yang butuh)
        $outlets = Outlet::all();

        return view('products.index', [
            'products' => $products,
            // Statistik dihitung dari $products yang SUDAH TERSARING
            'totalProduk' => $products->count(),
            'totalStok' => $products->sum('stok'),
            'nilaiStok' => $products->sum(function ($p) {
                return $p->harga * $p->stok;
            }),
            'stokRendah' => $products->where('stok', '<=', 10)->count(),
            'outlets' => $outlets,
            // Kirim ID filter yang sedang aktif agar dropdown tidak reset
            'currentOutletFilter' => $request->outlet_filter,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input
        $val = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string',
            'ukuran' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'modal' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // Ubah validasi outlet_id menjadi array outlet_ids
            'outlet_ids' => 'nullable|array',
            'outlet_ids.*' => 'exists:outlets,id',
        ]);

        // 2. Handle Upload Foto (Sekali saja)
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('produk', 'public');
        }

        // 3. Tentukan Outlet Tujuan
        // Jika Admin & memilih outlet, gunakan array dari form
        // Jika Kasir/Owner Outlet, gunakan outlet_id mereka sendiri (dibungkus array)
        $targetOutlets = [];

        if ($user->role === 'admin' && !empty($request->outlet_ids)) {
            $targetOutlets = $request->outlet_ids;
        } elseif ($user->outlet_id) {
            $targetOutlets = [$user->outlet_id];
        } else {
            // Fallback jika tidak punya outlet sama sekali
             $targetOutlets = [null];
        }

        // 4. Looping Simpan ke Database (Multi Outlet)
        foreach ($targetOutlets as $oid) {
            Product::create([
                'user_id' => $user->id,
                'outlet_id' => $oid, // ID Outlet berbeda tiap loop
                'nama' => $request->nama,
                'kategori' => $request->kategori,
                'ukuran' => $request->ukuran,
                'harga' => $request->harga,
                'modal' => $request->modal,
                'stok' => $request->stok,
                'foto' => $fotoPath, // Path foto yang sama
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Menu berhasil ditambahkan ke outlet yang dipilih!');
    }

    public function update(Request $request, Product $product)
    {
        $user = Auth::user();

        // Cek Otorisasi
        if ($product->outlet_id) {
            // Jika user bukan admin DAN outlet produk beda dengan outlet user -> tolak
            if ($user->role !== 'admin' && $product->outlet_id !== $user->outlet_id) {
                abort(403, 'Unauthorized Outlet Access');
            }
        } else {
            if ($product->user_id !== $user->id && $user->role !== 'admin') {
                abort(403);
            }
        }

        // 1. Validasi Input
        $val = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string',
            'ukuran' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'modal' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'outlet_ids' => 'nullable|array', // Terima array
            'outlet_ids.*' => 'exists:outlets,id',
        ]);

        // 2. Handle Foto Baru
        // Kita simpan path foto baru di variabel terpisah dulu
        $newFotoPath = $product->foto;

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($product->foto && Storage::disk('public')->exists($product->foto)) {
                Storage::disk('public')->delete($product->foto);
            }
            // Upload baru
            $newFotoPath = $request->file('foto')->store('produk', 'public');
        }

        // 3. Update Produk Saat Ini (Utama)
        $product->update([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'ukuran' => $request->ukuran,
            'harga' => $request->harga,
            'modal' => $request->modal,
            'stok' => $request->stok,
            'foto' => $newFotoPath,
        ]);

        // 4. DUPLIKASI ke Outlet Lain (Fitur Spesial Admin)
        if ($user->role === 'admin' && !empty($request->outlet_ids)) {
            // Filter: Jangan duplikasi ke outlet tempat produk ini berada sekarang
            $outletsToClone = array_diff($request->outlet_ids, [$product->outlet_id]);

            foreach ($outletsToClone as $oid) {
                Product::create([
                    'user_id' => $user->id,
                    'outlet_id' => $oid,
                    'nama' => $request->nama,
                    'kategori' => $request->kategori,
                    'ukuran' => $request->ukuran,
                    'harga' => $request->harga,
                    'modal' => $request->modal,
                    'stok' => $request->stok,
                    'foto' => $newFotoPath, // Share foto path
                ]);
            }

            if(count($outletsToClone) > 0) {
                 return redirect()->route('products.index')->with('success', 'Menu diperbarui & diduplikasi ke outlet lain!');
            }
        }

        return redirect()->route('products.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $user = Auth::user();

        // Otorisasi Hapus
        if ($user->role !== 'admin') {
            if ($product->outlet_id) {
                if ($product->outlet_id !== $user->outlet_id) abort(403);
            } else {
                if ($product->user_id !== $user->id) abort(403);
            }
        }

        // Hapus foto fisik
        if ($product->foto && Storage::disk('public')->exists($product->foto)) {
            Storage::disk('public')->delete($product->foto);
        }

        $product->delete();
        return back()->with('success', 'Menu dihapus.');
    }
}
