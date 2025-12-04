<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\KasMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        // Ambil produk user, urutkan stok terbanyak dan nama abjad
        $products = Product::where('user_id', Auth::id())
            ->orderBy('stok', 'desc')
            ->orderBy('nama', 'asc')
            ->get();

        return view('pos.index', compact('products'));
    }

    public function checkout(Request $r)
    {
        // 1. Validasi Keranjang
        if (!$r->cart_json) {
            return redirect()->route('pos.index')->with('error', 'Keranjang kosong!');
        }

        $cart = json_decode($r->cart_json, true);

        $total = 0;
        $jumlahItem = 0;
        $itemsForStruk = []; // Array untuk struk & DB

        // Ambil Data Input
        $metode = $r->metode_pembayaran ?? 'Tunai';
        $namaPelanggan = $r->nama_pelanggan ?? 'Pelanggan Umum';
        $tipePesanan = $r->tipe_pesanan ?? 'Dine-in';

        try {
            DB::beginTransaction();

            // 2. Loop Keranjang & Validasi Stok
            foreach ($cart as $id => $item) {
                // Lock for update: Mencegah stok minus jika ada 2 transaksi bersamaan
                $product = Product::lockForUpdate()->find($id);

                // Validasi kepemilikan & ketersediaan produk
                if (!$product || $product->user_id !== Auth::id()) {
                    throw new \Exception("Produk '{$item['name']}' tidak valid.");
                }

                if ($product->stok < $item['qty']) {
                    throw new \Exception("Stok '{$item['name']}' tidak mencukupi (Sisa: {$product->stok}).");
                }

                // Hitung total
                $subtotal = intval($item['price']) * intval($item['qty']);
                $total += $subtotal;
                $jumlahItem += intval($item['qty']);

                // KURANGI STOK REAL-TIME
                $product->decrement('stok', intval($item['qty']));

                // Simpan detail item lengkap
                $itemsForStruk[] = [
                    'id' => $product->id,
                    'name' => $product->nama,
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                    'ukuran' => $product->ukuran
                ];
            }

            // 3. Generate Kode Transaksi (POS-YYMMDD-001)
            $today = Carbon::now()->format('ymd');
            $countToday = KasMasuk::whereDate('created_at', Carbon::today())
                            ->where('user_id', Auth::id())
                            ->count() + 1;
            $kodeKas = 'POS-' . $today . '-' . str_pad($countToday, 3, '0', STR_PAD_LEFT);

            // 4. Tentukan Kategori Kas Masuk
            $kategoriKas = match($metode) {
                'QRIS' => 'Penjualan Digital',
                'Transfer' => 'Penjualan Transfer',
                default => 'Penjualan Tunai'
            };

            // 5. Simpan ke Database Kas Masuk
            $kas = KasMasuk::create([
                'kode_kas'          => $kodeKas,
                'tanggal_transaksi' => Carbon::now(),
                'keterangan'        => "POS - " . $namaPelanggan . " (" . $tipePesanan . ")",
                'jumlah'            => $jumlahItem,
                'harga_satuan'      => 0, // 0 karena ini bundle items
                'total'             => $total,
                'metode_pembayaran' => $metode,
                'kategori'          => $kategoriKas,
                'user_id'           => Auth::id(),
                'kembalian'         => ($metode === 'Tunai') ? (intval($r->kembalian) ?? 0) : 0,
                'detail_items'      => $itemsForStruk, // Simpan array JSON ke DB
            ]);

            DB::commit();

            // 6. Siapkan Data Struk (Session Flash Data)
            $printData = [
                'store_name'    => 'TEH SOLO JUMBO', // Bisa diganti dinamis dari setting
                'address'       => 'Cabang Utama',   // Bisa diganti dinamis
                'no_ref'        => $kas->kode_kas,
                'tanggal'       => Carbon::parse($kas->tanggal_transaksi)->format('d/m/Y H:i'),
                'items'         => $itemsForStruk,
                'total'         => $total,
                'bayar'         => ($metode === 'Tunai') ? intval($r->bayar) : $total,
                'kembali'       => ($metode === 'Tunai') ? intval($r->kembalian) : 0,
                'nama_pelanggan'=> $namaPelanggan,
                'tipe_pesanan'  => $tipePesanan,
                'metode'        => $metode,
                'kasir'         => Auth::user()->name ?? 'Admin',
            ];

            return redirect()->route('pos.index')->with('print_data', $printData);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pos.index')->with('error', 'Transaksi Gagal: ' . $e->getMessage());
        }
    }
}
