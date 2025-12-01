<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\KasMasuk;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
        return view('pos.index', compact('products'));
    }

    public function checkout(Request $r)
    {
        $cart = json_decode($r->cart_json, true);

        if (!is_array($cart)) {
            return back()->with('error', 'Data keranjang rusak');
        }

        $total = 0; // <-- INI WAJIB
        $totalQty = 0;
        $userId = \Illuminate\Support\Facades\Auth::id();

        foreach ($cart as $id => $item) {

            // skip item aneh
            if (!isset($item['price'], $item['qty'])) {
                continue;
            }

            // hitung total
            $subtotal = $item['price'] * $item['qty'];
            $total += $subtotal;
            $totalQty += $item['qty'];

            // update stok
            $p = Product::where('user_id', $userId)->where('id', $id)->first();
            if ($p) {
                $p->stok -= $item['qty'];
                $p->save();
            }
        }

        $hargaSatuan = $totalQty > 0 ? ($total / $totalQty) : 0;

        $namaProduk = [];

        foreach ($cart as $id => $item) {
            if (isset($item['name'])) {
                $namaProduk[] = $item['qty'] . 'x ' . $item['name'];
            }
        }

        $keterangan = 'Penjualan POS (' . implode(', ', $namaProduk) . ')';

        KasMasuk::create([
            'tanggal_transaksi' => now(),
            'kategori' => 'penjualan',
            'keterangan' => $keterangan,
            'metode_pembayaran' => $r->metode,
            'jumlah' => $totalQty,
            'harga_satuan' => $hargaSatuan,
            'total' => $total,
            'user_id' => $userId,
        ]);


        return redirect()->route('pos.index')->with('success', 'Transaksi selesai');
    }

}
