<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        $products = Product::where('user_id', $userId)->get();

        return view('products.index', [
            'totalProduk' => Product::where('user_id', $userId)->count(),
            'totalStok' => Product::where('user_id', $userId)->sum('stok'),
            'nilaiStok' => Product::where('user_id', $userId)->sum(\DB::raw('harga * stok')),
            'stokRendah' => Product::where('user_id', $userId)->where('stok', '<=', 5)->count(),
            'products' => $products,
        ]);
    }

    public function store(Request $r)
    {
        $data = $r->all();

        if ($r->hasFile('foto')) {
            $data['foto'] = $r->file('foto')->store('produk', 'public');
        }

        $data['user_id'] = \Illuminate\Support\Facades\Auth::id();
        Product::create($data);
        return redirect()->route('products.index');
    }


    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $r, Product $product)
    {
        $data = $r->all();

        if ($r->hasFile('foto')) {
            $data['foto'] = $r->file('foto')->store('produk', 'public');
        }

        $product->update($data);
        return redirect()->route('products.index');
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return back();
    }

    public function stockOpname(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        $selisih = $request->physical_stock - $product->stok;

        $product->update([
            'stok' => $request->physical_stock
        ]);

        // simpan riwayat jika mau
        // StockHistory::create([...]);

        return back()->with('success', 'Stock opname berhasil.');
    }

}
