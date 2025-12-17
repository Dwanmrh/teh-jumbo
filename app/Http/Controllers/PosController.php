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

        // REFACTOR: Logic sekarang berbasis Outlet
        $outletId = $user->outlet_id;

        if (!$outletId) {
            // Jika user tidak punya outlet (misal Admin belum assign diri sendiri),
            // Tampilkan error atau kosong.
            return redirect()->route('dashboard')->with('error', 'Anda belum terdaftar di Outlet manapun.');
        }

        // Mengambil produk berdasarkan Outlet ID
        $products = Product::where('outlet_id', $outletId)
            ->orderBy('stok', 'desc')
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

        // Bersihkan input bayar dari karakter non-angka (misal: "Rp 10.000" jadi "10000")
        $bayarInput = preg_replace('/\D/', '', $r->bayar);
        $bayar = intval($bayarInput);

        try {
            DB::beginTransaction();

            // 2. Loop Keranjang (Critical Process)
            foreach ($cart as $id => $item) {
                // Lock row product untuk mencegah race condition (transaksi bersamaan)
                $product = Product::lockForUpdate()->find($id);

                // REFACTOR: Validasi Berbasis Outlet
                $userOutletId = Auth::user()->outlet_id;

                // Validasi Produk Exist & Milik Outlet yang Sama
                if (!$product || $product->outlet_id != $userOutletId) {
                    throw new \Exception("Produk '{$product->nama}' tidak valid untuk Outlet ini.");
                }

                // Validasi Stok Server-Side
                if ($product->stok < $item['qty']) {
                    throw new \Exception("Stok '{$product->nama}' tidak mencukupi. Sisa stok: {$product->stok}");
                }

                // Kalkulasi Subtotal menggunakan HARGA DATABASE (Aman dari manipulasi)
                $hargaSatuan = intval($product->harga);
                $qty = intval($item['qty']);
                $subtotal = $hargaSatuan * $qty;

                $total += $subtotal;
                $jumlahItem += $qty;

                // Kurangi Stok Real-time
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
                // Jika QRIS/Transfer, anggap lunas sesuai total
                $bayar = $total;
                $kembalian = 0;
            }

            // 4. Generate Kode Transaksi (POS-OUTLET_ID-YYMMDD-XXX)
            $today = Carbon::now()->format('ymd');
            $userOutletId = Auth::user()->outlet_id;

            // Hitung urutan berdasarkan Outlet, bukan User
            $countToday = KasMasuk::whereDate('created_at', Carbon::today())
                ->where('outlet_id', $userOutletId)
                ->count() + 1;

            // Format: POS-{OUTLET}-{DATE}-{SEQ} (Contoh: POS-1-241217-001)
            $kodeKas = 'POS-' . $userOutletId . '-' . $today . '-' . str_pad($countToday, 3, '0', STR_PAD_LEFT);

            // 5. Tentukan Kategori Kas
            $kategoriKas = ($metode === 'Tunai') ? 'Penjualan Tunai' : 'Penjualan Non-Tunai';

            // 6. Simpan ke Database KasMasuk
            $kas = KasMasuk::create([
                'kode_kas' => $kodeKas,
                'tanggal_transaksi' => Carbon::now(),
                'keterangan' => "POS - {$namaPelanggan} ({$tipePesanan})",
                'jumlah' => $jumlahItem,
                'harga_satuan' => 0, // 0 karena bundle items
                'total' => $total,
                'payment_method' => $metode,
                'kategori' => $kategoriKas,
                'user_id' => Auth::id(), // Tetap simpan siapa yang input (Kasir/Admin)
                'outlet_id' => $userOutletId, // REFACTOR: Simpan Outlet ID
                'kembalian' => $kembalian,
                'detail_items' => $itemsForStruk, // Pastikan di Model KasMasuk ada cast: protected $casts = ['detail_items' => 'array'];
            ]);

            DB::commit();

            $user = Auth::user();

            if (!$user->outlet) {
                return redirect()->route('pos.index')
                    ->with('error', 'Outlet user tidak ditemukan.');
            }

            $alamatOutlet = $user->outlet->name;


            // 7. Siapkan Data Session untuk Cetak Struk
            $printData = [
                'store_name' => 'Teh Solo De Jumbo',
                'address'    => $alamatOutlet, // Bisa diambil dari setting database jika ada
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

            // Redirect dengan session flash data
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
