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
        $user = Auth::user();
        $outletId = $user->outlet_id;

        if ($outletId) {
            // Show products for the outlet (Shared)
            $products = Product::where('outlet_id', $outletId)->latest()->get();
        } else {
            // Fallback: If no outlet assigned, show by user_id
            $products = Product::where('user_id', $user->id)->latest()->get();
        }

        return view('products.index', [
            'products' => $products,
            'totalProduk' => $products->count(),
            'totalStok' => $products->sum('stok'),
            // Menghitung potensi omset (Harga Jual * Stok)
            'nilaiStok' => $products->sum(function ($p) {
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

        // 2. Tambahkan User ID & Outlet ID
        $val['user_id'] = Auth::id();
        $val['outlet_id'] = Auth::user()->outlet_id; // Assign to confirmed outlet

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
        // Pastikan hanya pemilik Outlet yang sama yang bisa update
        $user = Auth::user();
        if ($product->outlet_id) {
            if ($product->outlet_id !== $user->outlet_id)
                abort(403, 'Unauthorized Outlet Access');
        } else {
            // Legacy/Fallback check
            if ($product->user_id !== $user->id)
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
        $user = Auth::user();
        if ($product->outlet_id) {
            if ($product->outlet_id !== $user->outlet_id)
                abort(403, 'Unauthorized Outlet Access');
        } else {
            if ($product->user_id !== $user->id)
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
