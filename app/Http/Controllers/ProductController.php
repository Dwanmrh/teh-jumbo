<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return view('products.index', [
            'totalProduk' => Product::count(),
            'totalStok'   => Product::sum('stok'),
            'nilaiStok'   => Product::sum(\DB::raw('harga * stok')),
            'stokRendah'  => Product::where('stok', '<=', 5)->count(),
            'products'    => $products,
        ]);
    }

    public function store(Request $r)
    {
        $data = $r->all();

        if ($r->hasFile('foto')) {
            $data['foto'] = $r->file('foto')->store('produk', 'public');
        }

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

