<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\KasMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::where('user_id', Auth::id())->get();
        return view('pos.index', compact('products'));
    }

    public function checkout(Request $r)
    {
        // 1. Decode Cart
        $cart = json_decode($r->cart_json, true);

        if (!is_array($cart) || empty($cart)) {
            return back()->with('error', 'Keranjang kosong atau data rusak.');
        }

        // 2. Persiapan Variabel
        $totalBayar = 0;
        $totalQty = 0;
        $userId = Auth::id();
        $namaProdukArr = [];

        // --- MULAI TRANSAKSI DATABASE (PENGAMAN) ---
        DB::beginTransaction();

        try {
            foreach ($cart as $id => $item) {
                // Skip jika data item tidak lengkap
                if (!isset($item['price'], $item['qty'])) continue;

                $qtyBeli = $item['qty'];

                // Ambil Produk dari DB (Lock for update agar tidak rebutan jika akses barengan)
                $product = Product::where('user_id', $userId)
                                  ->where('id', $id)
                                  ->lockForUpdate()
                                  ->first();

                if (!$product) {
                    throw new \Exception("Produk ID $id tidak ditemukan.");
                }

                // 3. Validasi Stok (Mencegah Stok Minus)
                if ($product->stok < $qtyBeli) {
                    throw new \Exception("Stok produk '{$product->nama}' tidak mencukupi. Sisa: {$product->stok}");
                }

                // 4. Update Stok & Hitung Total
                $product->stok -= $qtyBeli;
                $product->save();

                $subtotal = $item['price'] * $qtyBeli;
                $totalBayar += $subtotal;
                $totalQty += $qtyBeli;

                $namaProdukArr[] = "{$qtyBeli}x {$item['name']}";
            }

            // 5. Susun Keterangan Transaksi
            // Tips: Batasi panjang string agar tidak error di database jika belanjanya banyak sekali
            $listProduk = implode(', ', $namaProdukArr);
            $keterangan = 'POS: ' . substr($listProduk, 0, 200) . (strlen($listProduk) > 200 ? '...' : '');

            // 6. Simpan ke Kas Masuk
            // Catatan: Harga satuan rata-rata sebenarnya tidak relevan di akuntansi kas masuk gabungan,
            // tapi kita isi agar tidak error jika kolom itu required.
            $hargaSatuanRataRata = $totalQty > 0 ? ($totalBayar / $totalQty) : 0;

            $transaksi = KasMasuk::create([
                'tanggal_transaksi' => now(),
                'kategori'          => 'penjualan',
                'keterangan'        => $keterangan,
                'metode_pembayaran' => $r->metode, // Tunai, Transfer, QRIS
                'jumlah'            => $totalQty,  // Total Item
                'harga_satuan'      => $hargaSatuanRataRata,
                'total'             => $totalBayar, // Total Uang Masuk (PENTING)
                'user_id'           => $userId,
            ]);

            // Jika semua lancar, simpan permanen
            DB::commit();

            // 7. Redirect dengan Session untuk Cetak Struk
            // Kita kirim ID transaksi terakhir agar bisa dicetak di view
            return redirect()->route('pos.index')
                ->with('success', 'Transaksi berhasil!')
                ->with('last_transaction_id', $transaksi->id)
                ->with('print_data', [
                    'items' => $cart,
                    'total' => $totalBayar,
                    'bayar' => $r->bayar_input ?? $totalBayar, // Jika ada input uang bayar dari frontend
                    'kembali' => ($r->bayar_input ?? $totalBayar) - $totalBayar,
                    'tanggal' => now()->format('d-m-Y H:i')
                ]);

        } catch (\Exception $e) {
            // Jika ada error (stok habis, dll), batalkan semua perubahan DB
            DB::rollBack();
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }
}
