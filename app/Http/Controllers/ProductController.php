<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Ambil produk milik user yang sedang login saja
        $products = Product::where('user_id', $userId)->latest()->get();

        return view('products.index', [
            'products' => $products,
            'totalProduk' => $products->count(),
            'totalStok' => $products->sum('stok'),
            // Menghitung potensi omset (Harga Jual * Stok)
            'nilaiStok' => $products->sum(function($p) {
                return $p->harga * $p->stok;
            }),
            // Menghitung stok rendah (di bawah atau sama dengan 10)
            'stokRendah' => $products->where('stok', '<=', 10)->count(),
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $val = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string',
            'ukuran' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'modal' => 'required|numeric|min:0', // Validasi Modal
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Tambahkan User ID
        $val['user_id'] = Auth::id();

        // 3. Handle Upload Foto
        if ($request->hasFile('foto')) {
            $val['foto'] = $request->file('foto')->store('produk', 'public');
        }

        // 4. Simpan ke Database
        Product::create($val);

        return redirect()->route('products.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function update(Request $request, Product $product)
    {
        // Pastikan hanya pemilik yang bisa update
        if ($product->user_id !== Auth::id()) {
            abort(403);
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
        ]);

        // 2. Handle Ganti Foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada (agar storage tidak penuh)
            if ($product->foto && Storage::disk('public')->exists($product->foto)) {
                Storage::disk('public')->delete($product->foto);
            }
            // Simpan foto baru
            $val['foto'] = $request->file('foto')->store('produk', 'public');
        }

        // 3. Update Database
        $product->update($val);

        return redirect()->route('products.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        // Hapus foto fisik saat data dihapus
        if ($product->foto && Storage::disk('public')->exists($product->foto)) {
            Storage::disk('public')->delete($product->foto);
        }

        $product->delete();
        return back()->with('success', 'Menu dihapus.');
    }
}
