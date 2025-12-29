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
    /**
     * Menampilkan Halaman POS
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // --- 1. LOGIKA AKSES & FILTER ---
        $query = Product::query();
        $selectedOutletId = null;

        if ($user->role !== 'admin') {
            // A. KASIR: Kunci ke outlet user
            if (!$user->outlet_id) {
                return redirect()->route('dashboard')->with('error', 'Akun tidak terhubung outlet.');
            }
            $query->where('outlet_id', $user->outlet_id);
            $selectedOutletId = $user->outlet_id;
        } else {
            // B. ADMIN: Cek filter dari Dropdown (PERBAIKAN: input())
            if ($request->has('outlet_id') && $request->input('outlet_id') != '') {
                $outletId = $request->input('outlet_id');
                $query->where('outlet_id', $outletId);
                $selectedOutletId = $outletId;
            }
        }

        // --- 2. AMBIL DATA PRODUK (SOFT STOCK MODE) ---
        $products = $query
            ->orderBy('stok', 'desc')
            ->orderBy('nama', 'asc')
            ->get();

        // --- 3. DATA PENDUKUNG ---
        $outlets = ($user->role === 'admin') ? Outlet::all() : [];

        // Nama Outlet untuk Header UI
        $currentOutletName = 'Semua Outlet';
        if ($selectedOutletId) {
            $outletObj = Outlet::find($selectedOutletId);
            $currentOutletName = $outletObj ? $outletObj->name : 'Unknown';
        } elseif ($user->role !== 'admin') {
             $currentOutletName = $user->outlet->name ?? '-';
        }

        return view('pos.index', compact('products', 'outlets', 'selectedOutletId', 'currentOutletName'));
    }

    /**
     * Memproses Checkout Transaksi
     */
    public function checkout(Request $request) // Gunakan $request agar konsisten
    {
        // 1. Validasi Awal Keranjang (PERBAIKAN: input())
        if (!$request->input('cart_json')) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong!');
        }

        $cart = json_decode($request->input('cart_json'), true);

        if (empty($cart) || !is_array($cart)) {
            return redirect()->back()->with('error', 'Data keranjang tidak valid.');
        }

        // Variabel penampung
        $total = 0;
        $jumlahItem = 0;
        $itemsForStruk = [];

        // Ambil input user (PERBAIKAN: input())
        $metode = $request->input('metode_pembayaran') ?? 'Tunai';
        $namaPelanggan = $request->input('nama_pelanggan') ?? 'Pelanggan Umum';
        $tipePesanan = $request->input('tipe_pesanan') ?? 'Dine-in';

        // --- 2. TENTUKAN OUTLET TRANSAKSI ---
        $user = Auth::user();
        $transactionOutletId = $user->outlet_id;

        if ($user->role === 'admin') {
            // Jika Admin, ambil ID dari hidden input form
            $transactionOutletId = $request->input('transaction_outlet_id');

            // Fallback: Jika Admin mode "Semua Outlet", ambil outlet ID dari produk pertama
            if(!$transactionOutletId) {
                $firstProductId = array_key_first($cart);
                $firstProduct = Product::find($firstProductId);
                $transactionOutletId = $firstProduct ? $firstProduct->outlet_id : 1;
            }
        }

        // Sanitasi input bayar (hapus titik/koma) (PERBAIKAN: input())
        $bayarInput = preg_replace('/\D/', '', $request->input('bayar'));
        $bayar = intval($bayarInput);

        try {
            DB::beginTransaction();

            // --- 3. LOOP KERANJANG & KURANGI STOK ---
            foreach ($cart as $id => $item) {
                $product = Product::find($id);

                if (!$product) {
                    throw new \Exception("Produk dengan ID {$id} tidak ditemukan.");
                }

                // Hitung Subtotal
                $hargaSatuan = intval($product->harga);
                $qty = intval($item['qty']);
                $subtotal = $hargaSatuan * $qty;

                $total += $subtotal;
                $jumlahItem += $qty;

                // Eksekusi Pengurangan Stok (Soft Stock)
                $product->decrement('stok', $qty);

                // Siapkan data detail untuk JSON/Struk
                $itemsForStruk[] = [
                    'id' => $product->id,
                    'name' => $product->nama,
                    'qty' => $qty,
                    'price' => $hargaSatuan,
                    'subtotal' => $subtotal,
                    'ukuran' => $product->ukuran
                ];
            }

            // --- 4. VALIDASI PEMBAYARAN ---
            $kembalian = 0;
            if ($metode === 'Tunai') {
                if ($bayar < $total) {
                    throw new \Exception("Uang pembayaran kurang! Total: " . number_format($total));
                }
                $kembalian = $bayar - $total;
            } else {
                // Non-tunai dianggap pas
                $bayar = $total;
                $kembalian = 0;
            }

            // --- 5. GENERATE KODE TRANSAKSI ---
            $today = Carbon::now()->format('ymd');

            // Hitung urutan transaksi hari ini di outlet tersebut
            $countToday = KasMasuk::whereDate('created_at', Carbon::today())
                ->where('outlet_id', $transactionOutletId)
                ->count() + 1;

            $kodeKas = 'POS-' . $transactionOutletId . '-' . $today . '-' . str_pad($countToday, 3, '0', STR_PAD_LEFT);

            // --- 6. SIMPAN KE KAS MASUK ---
            $kategoriKas = ($metode === 'Tunai') ? 'Penjualan Tunai' : 'Penjualan Non-Tunai';

            $kas = KasMasuk::create([
                'kode_kas' => $kodeKas,
                'tanggal_transaksi' => Carbon::now(),
                'keterangan' => "POS - {$namaPelanggan} ({$tipePesanan})",

                // PERBAIKAN DI SINI:
                // Jangan gunakan jumlah item, tapi anggap 1 Transaksi
                'jumlah' => 1,

                // Isi harga satuan dengan Total Belanja agar aman jika diedit/dihitung manual
                'harga_satuan' => $total,

                'total' => $total,

                'payment_method' => $metode,
                'kategori' => $kategoriKas,
                'user_id' => Auth::id(),
                'outlet_id' => $transactionOutletId,
                'kembalian' => $kembalian,
                'detail_items' => $itemsForStruk, // Detail item tetap aman tersimpan di sini (JSON)
            ]);

            DB::commit();

            // --- 7. PERSIAPAN CETAK STRUK ---
            $outletObj = Outlet::find($transactionOutletId);
            $namaOutletStruk = $outletObj ? $outletObj->name : 'Teh Solo Pusat';

            $printData = [
                'store_name' => 'Teh Solo De Jumbo',
                'address'    => $namaOutletStruk,
                'no_ref' => $kas->kode_kas,
                'tanggal' => Carbon::parse($kas->tanggal_transaksi)->format('d/m/Y H:i'),
                'items' => $itemsForStruk,
                'total' => $total,
                'bayar' => $bayar,
                'kembali' => $kembalian,
                'nama_pelanggan' => $namaPelanggan,
                'tipe_pesanan' => $tipePesanan,
                'metode' => $metode,
                'kasir' => $user->name,
            ];

            // Redirect Logic
            $redirectParams = [];
            if($user->role === 'admin' && $transactionOutletId) {
                $redirectParams['outlet_id'] = $transactionOutletId;
            }

            return redirect()->route('pos.index', $redirectParams)
                ->with('success', 'Transaksi Berhasil!')
                ->with('print_data', $printData);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Transaksi Gagal: ' . $e->getMessage());
        }
    }
}
