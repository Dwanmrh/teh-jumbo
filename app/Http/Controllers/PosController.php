<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\KasMasuk;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PosController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cek apakah user punya outlet (hanya untuk keperluan data sesi/struk, bukan pembatasan produk)
        $outletId = $user->outlet_id;

        if (!$outletId) {
            return redirect()->route('dashboard')->with('error', 'Anda belum terdaftar di Outlet manapun (Diperlukan untuk pencatatan laporan).');
        }

        // --- UPDATE: MENGAMBIL SEMUA PRODUK (GLOBAL) ---
        // Menghapus ->where('outlet_id', $outletId) agar semua produk muncul
        $products = Product::orderBy('stok', 'desc')
            ->orderBy('nama', 'asc')
            ->get();

        return view('pos.index', compact('products'));
    }

    public function checkout(Request $r)
    {
        // 1. Validasi Awal Keranjang
        if (!$r->cart_json) {
            return redirect()->route('pos.index')->with('error', 'Keranjang belanja kosong!');
        }

        $cart = json_decode($r->cart_json, true);

        if (empty($cart) || !is_array($cart)) {
            return redirect()->route('pos.index')->with('error', 'Data keranjang tidak valid.');
        }

        // Variabel penampung
        $total = 0;
        $jumlahItem = 0;
        $itemsForStruk = [];

        // Ambil input user & sanitasi
        $metode = $r->metode_pembayaran ?? 'Tunai';
        $namaPelanggan = $r->nama_pelanggan ?? 'Pelanggan Umum';
        $tipePesanan = $r->tipe_pesanan ?? 'Dine-in';

        // Bersihkan input bayar dari karakter non-angka
        $bayarInput = preg_replace('/\D/', '', $r->bayar);
        $bayar = intval($bayarInput);

        try {
            DB::beginTransaction();

            // 2. Loop Keranjang
            foreach ($cart as $id => $item) {
                // Lock row product
                $product = Product::lockForUpdate()->find($id);

                // --- UPDATE: HAPUS VALIDASI OUTLET ---
                // Kita izinkan kasir menjual produk manapun, meskipun outlet_id produk berbeda
                if (!$product) {
                    throw new \Exception("Produk dengan ID {$id} tidak ditemukan.");
                }

                // Validasi Stok Server-Side
                if ($product->stok < $item['qty']) {
                    throw new \Exception("Stok '{$product->nama}' tidak mencukupi. Sisa stok: {$product->stok}");
                }

                // Kalkulasi Subtotal
                $hargaSatuan = intval($product->harga);
                $qty = intval($item['qty']);
                $subtotal = $hargaSatuan * $qty;

                $total += $subtotal;
                $jumlahItem += $qty;

                // Kurangi Stok Real-time (Global Stock)
                $product->decrement('stok', $qty);

                // Siapkan data detail untuk disimpan
                $itemsForStruk[] = [
                    'id' => $product->id,
                    'name' => $product->nama,
                    'qty' => $qty,
                    'price' => $hargaSatuan,
                    'subtotal' => $subtotal,
                    'ukuran' => $product->ukuran
                ];
            }

            // 3. Validasi & Kalkulasi Pembayaran
            $kembalian = 0;

            if ($metode === 'Tunai') {
                if ($bayar < $total) {
                    throw new \Exception("Uang pembayaran kurang! Total: Rp " . number_format($total, 0, ',', '.') . ", Dibayar: Rp " . number_format($bayar, 0, ',', '.'));
                }
                $kembalian = $bayar - $total;
            } else {
                $bayar = $total;
                $kembalian = 0;
            }

            // 4. Generate Kode Transaksi
            $today = Carbon::now()->format('ymd');
            $userOutletId = Auth::user()->outlet_id; // Laporan tetap masuk ke Outlet User yang login

            $countToday = KasMasuk::whereDate('created_at', Carbon::today())
                ->where('outlet_id', $userOutletId)
                ->count() + 1;

            $kodeKas = 'POS-' . $userOutletId . '-' . $today . '-' . str_pad($countToday, 3, '0', STR_PAD_LEFT);

            // 5. Tentukan Kategori Kas
            $kategoriKas = ($metode === 'Tunai') ? 'Penjualan Tunai' : 'Penjualan Non-Tunai';

            // 6. Simpan ke Database KasMasuk
            $kas = KasMasuk::create([
                'kode_kas' => $kodeKas,
                'tanggal_transaksi' => Carbon::now(),
                'keterangan' => "POS - {$namaPelanggan} ({$tipePesanan})",
                'jumlah' => $jumlahItem,
                'harga_satuan' => 0,
                'total' => $total,
                'payment_method' => $metode,
                'kategori' => $kategoriKas,
                'user_id' => Auth::id(),
                'outlet_id' => $userOutletId, // Transaksi tercatat di outlet Kasir, meskipun barangnya global
                'kembalian' => $kembalian,
                'detail_items' => $itemsForStruk,
            ]);

            DB::commit();

            $user = Auth::user();
            $alamatOutlet = $user->outlet ? $user->outlet->name : 'Teh Solo Pusat';

            // 7. Siapkan Data Session untuk Cetak Struk
            $printData = [
                'store_name' => 'Teh Solo De Jumbo',
                'address'    => $alamatOutlet,
                'no_ref' => $kas->kode_kas,
                'tanggal' => Carbon::parse($kas->tanggal_transaksi)->format('d/m/Y H:i'),
                'items' => $itemsForStruk,
                'total' => $total,
                'bayar' => $bayar,
                'kembali' => $kembalian,
                'nama_pelanggan' => $namaPelanggan,
                'tipe_pesanan' => $tipePesanan,
                'metode' => $metode,
                'kasir' => Auth::user()->name ?? 'Kasir',
            ];

            return redirect()->route('pos.index')
                ->with('success', 'Transaksi Berhasil!')
                ->with('print_data', $printData);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pos.index')
                ->with('error', 'Transaksi Gagal: ' . $e->getMessage());
        }
    }
}
