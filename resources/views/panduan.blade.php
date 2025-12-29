<x-app-layout>
    {{-- HEADER SLOT (Agar muncul di Sticky Glass Bar atas) --}}
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div>
                <h2 class="font-bold text-xl text-stone-800 leading-tight flex items-center gap-2">
                    <span class="material-symbols-rounded text-brand-500">menu_book</span>
                    Panduan Aplikasi
                </h2>
                <p class="text-xs text-stone-500 font-medium mt-0.5">Pusat Bantuan & Dokumentasi</p>
            </div>
            <div class="hidden sm:flex items-center gap-1 text-xs font-bold text-stone-400 bg-stone-100 px-3 py-1.5 rounded-full">
                <span>Teh Solo</span>
                <span class="material-symbols-rounded text-[14px]">chevron_right</span>
                <span class="text-brand-600">Jumbo</span>
            </div>
        </div>
    </x-slot>

    {{-- MAIN CONTENT --}}
    <div class="py-6 space-y-8">

        {{-- Intro Banner (Opsional, pemanis visual) --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-stone-800 to-stone-900 shadow-lg shadow-stone-900/10 p-6 sm:p-8 text-white">
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-xl sm:text-2xl font-extrabold tracking-tight">Butuh Bantuan?</h3>
                    <p class="text-stone-300 text-sm mt-1 max-w-lg leading-relaxed">
                        Berikut adalah kumpulan cara penggunaan sistem kasir dan administrasi Teh Solo De Jumbo. Klik pada topik untuk melihat detailnya.
                    </p>
                </div>
                <div class="p-3 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/10">
                    <span class="material-symbols-rounded text-4xl text-brand-400">support_agent</span>
                </div>
            </div>
            {{-- Decorative Blobs --}}
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-brand-500 rounded-full mix-blend-overlay filter blur-3xl opacity-20"></div>
            <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 bg-cyan-500 rounded-full mix-blend-overlay filter blur-3xl opacity-20"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 items-start">

            {{-- KOLOM KIRI: PANDUAN KASIR (UMUM) --}}
            <div class="space-y-6">
                <div class="flex items-center gap-3 px-2">
                    <div class="h-8 w-1 rounded-full bg-brand-500"></div>
                    <h3 class="text-lg font-bold text-stone-800">Operasional Kasir</h3>
                </div>

                <div class="bg-white rounded-3xl shadow-[0_2px_20px_rgba(0,0,0,0.04)] border border-stone-100 overflow-hidden">
                    <div class="divide-y divide-stone-100">

                        {{-- Accordion Item 1 --}}
                        <div x-data="{ open: false }" class="group transition-colors duration-300" :class="open ? 'bg-brand-50/30' : 'bg-white'">
                            <button @click="open = !open" class="w-full px-6 py-5 text-left flex justify-between items-center transition-all duration-200 hover:bg-stone-50/50">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-brand-600 bg-brand-100/50 group-hover:bg-brand-100 transition-colors">
                                        <span class="material-symbols-rounded text-[20px]">shopping_cart_checkout</span>
                                    </span>
                                    <span class="font-bold text-stone-700 group-hover:text-stone-900 text-sm sm:text-base">Transaksi Penjualan</span>
                                </div>
                                <span class="material-symbols-rounded text-stone-400 transition-transform duration-300" :class="open ? 'rotate-180 text-brand-500' : ''">expand_more</span>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="px-6 pb-6 pl-[4.25rem]">
                                <ol class="list-decimal list-outside text-sm text-stone-600 space-y-2 marker:text-brand-500 marker:font-bold">
                                    <li>Buka menu <b>Kasir</b> pada navigasi.</li>
                                    <li>Pilih produk yang dipesan pelanggan (klik gambar produk).</li>
                                    <li>Atur jumlah (Qty) menggunakan tombol <b>+</b> atau <b>-</b>.</li>
                                    <li>Tekan tombol <b>Checkout</b> di bagian bawah layar.</li>
                                    <li>Masukkan nominal uang yang diterima, sistem akan menghitung kembalian otomatis.</li>
                                    <li>Klik <b>Selesai</b> untuk memproses transaksi.</li>
                                </ol>
                            </div>
                        </div>

                        {{-- Accordion Item 2 --}}
                        <div x-data="{ open: false }" class="group transition-colors duration-300" :class="open ? 'bg-brand-50/30' : 'bg-white'">
                            <button @click="open = !open" class="w-full px-6 py-5 text-left flex justify-between items-center transition-all duration-200 hover:bg-stone-50/50">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-rose-600 bg-rose-100/50 group-hover:bg-rose-100 transition-colors">
                                        <span class="material-symbols-rounded text-[20px]">payments</span>
                                    </span>
                                    <span class="font-bold text-stone-700 group-hover:text-stone-900 text-sm sm:text-base">Mencatat Pengeluaran (Kas Keluar)</span>
                                </div>
                                <span class="material-symbols-rounded text-stone-400 transition-transform duration-300" :class="open ? 'rotate-180 text-brand-500' : ''">expand_more</span>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="px-6 pb-6 pl-[4.25rem]">
                                <p class="text-sm text-stone-600 mb-3 leading-relaxed">
                                    Gunakan fitur ini jika Anda menggunakan uang laci kasir untuk belanja operasional (Contoh: Beli Es Batu, Plastik, Gula).
                                </p>
                                <ul class="space-y-2 text-sm text-stone-600">
                                    <li class="flex items-start gap-2">
                                        <span class="material-symbols-rounded text-stone-400 text-[18px] mt-0.5">check_circle</span>
                                        <span>Buka menu <b>Keluar</b>.</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="material-symbols-rounded text-stone-400 text-[18px] mt-0.5">check_circle</span>
                                        <span>Klik tombol <b>Tambah Data</b>.</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="material-symbols-rounded text-stone-400 text-[18px] mt-0.5">check_circle</span>
                                        <span>Isi Nominal dan Keterangan (Wajib jelas).</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="material-symbols-rounded text-stone-400 text-[18px] mt-0.5">check_circle</span>
                                        <span>Simpan. Saldo akan otomatis terpotong.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- Accordion Item 3 (Kas Masuk Tambahan) --}}
                        <div x-data="{ open: false }" class="group transition-colors duration-300" :class="open ? 'bg-brand-50/30' : 'bg-white'">
                            <button @click="open = !open" class="w-full px-6 py-5 text-left flex justify-between items-center transition-all duration-200 hover:bg-stone-50/50">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-teal-600 bg-teal-100/50 group-hover:bg-teal-100 transition-colors">
                                        <span class="material-symbols-rounded text-[20px]">savings</span>
                                    </span>
                                    <span class="font-bold text-stone-700 group-hover:text-stone-900 text-sm sm:text-base">Input Kas Masuk (Modal)</span>
                                </div>
                                <span class="material-symbols-rounded text-stone-400 transition-transform duration-300" :class="open ? 'rotate-180 text-brand-500' : ''">expand_more</span>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="px-6 pb-6 pl-[4.25rem]">
                                <p class="text-sm text-stone-600 leading-relaxed">
                                    Menu <b>Masuk</b> digunakan jika Owner menambahkan uang modal receh ke laci kasir di awal shift, atau ada pemasukan lain di luar penjualan teh.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: PANDUAN OWNER (ADMIN ONLY) --}}
            @if(auth()->user()->role === 'admin')
            <div class="space-y-6">
                <div class="flex items-center gap-3 px-2">
                    <div class="h-8 w-1 rounded-full bg-stone-800"></div>
                    <h3 class="text-lg font-bold text-stone-800">Panel Pemilik (Owner)</h3>
                </div>

                <div class="bg-white rounded-3xl shadow-[0_2px_20px_rgba(0,0,0,0.04)] border border-stone-100 overflow-hidden">
                    <div class="divide-y divide-stone-100">

                        {{-- Admin Item 1 --}}
                        <div x-data="{ open: false }" class="group transition-colors duration-300" :class="open ? 'bg-stone-50' : 'bg-white'">
                            <button @click="open = !open" class="w-full px-6 py-5 text-left flex justify-between items-center transition-all duration-200 hover:bg-stone-50/50">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-stone-700 bg-stone-200 group-hover:bg-stone-300 transition-colors">
                                        <span class="material-symbols-rounded text-[20px]">inventory_2</span>
                                    </span>
                                    <span class="font-bold text-stone-700 group-hover:text-stone-900 text-sm sm:text-base">Manajemen Produk</span>
                                </div>
                                <span class="material-symbols-rounded text-stone-400 transition-transform duration-300" :class="open ? 'rotate-180 text-stone-800' : ''">expand_more</span>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="px-6 pb-6 pl-[4.25rem]">
                                <p class="text-sm text-stone-600 mb-2">Akses melalui menu <b>Produk</b> (di menu Lainnya).</p>
                                <ul class="space-y-2 text-sm text-stone-600 list-disc list-outside ml-4 marker:text-stone-400">
                                    <li><b>Tambah Baru:</b> Klik tombol "+ Tambah Produk".</li>
                                    <li><b>Edit Harga/Nama:</b> Klik ikon pensil pada daftar produk.</li>
                                    <li><b>Hapus:</b> Klik ikon sampah (hati-hati, data terhapus permanen).</li>
                                </ul>
                            </div>
                        </div>

                        {{-- Admin Item 2 --}}
                        <div x-data="{ open: false }" class="group transition-colors duration-300" :class="open ? 'bg-stone-50' : 'bg-white'">
                            <button @click="open = !open" class="w-full px-6 py-5 text-left flex justify-between items-center transition-all duration-200 hover:bg-stone-50/50">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-stone-700 bg-stone-200 group-hover:bg-stone-300 transition-colors">
                                        <span class="material-symbols-rounded text-[20px]">description</span>
                                    </span>
                                    <span class="font-bold text-stone-700 group-hover:text-stone-900 text-sm sm:text-base">Laporan & Export</span>
                                </div>
                                <span class="material-symbols-rounded text-stone-400 transition-transform duration-300" :class="open ? 'rotate-180 text-stone-800' : ''">expand_more</span>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="px-6 pb-6 pl-[4.25rem]">
                                <p class="text-sm text-stone-600 leading-relaxed mb-3">
                                    Menu <b>Laporan</b> menyajikan ringkasan pemasukan bersih (Omset - Pengeluaran).
                                </p>
                                <div class="flex gap-2">
                                    <span class="px-2 py-1 bg-red-50 text-red-600 border border-red-100 rounded text-xs font-bold flex items-center gap-1">
                                        <span class="material-symbols-rounded text-[14px]">picture_as_pdf</span> PDF
                                    </span>
                                    <span class="px-2 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded text-xs font-bold flex items-center gap-1">
                                        <span class="material-symbols-rounded text-[14px]">table_view</span> Excel
                                    </span>
                                </div>
                                <p class="text-xs text-stone-400 mt-2 italic">Gunakan tombol di pojok kanan atas halaman Laporan untuk mengunduh.</p>
                            </div>
                        </div>

                         {{-- Admin Item 3 --}}
                         <div x-data="{ open: false }" class="group transition-colors duration-300" :class="open ? 'bg-stone-50' : 'bg-white'">
                            <button @click="open = !open" class="w-full px-6 py-5 text-left flex justify-between items-center transition-all duration-200 hover:bg-stone-50/50">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-stone-700 bg-stone-200 group-hover:bg-stone-300 transition-colors">
                                        <span class="material-symbols-rounded text-[20px]">group</span>
                                    </span>
                                    <span class="font-bold text-stone-700 group-hover:text-stone-900 text-sm sm:text-base">Manajemen User</span>
                                </div>
                                <span class="material-symbols-rounded text-stone-400 transition-transform duration-300" :class="open ? 'rotate-180 text-stone-800' : ''">expand_more</span>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="px-6 pb-6 pl-[4.25rem]">
                                <p class="text-sm text-stone-600 leading-relaxed">
                                    Anda dapat menambah akun karyawan baru atau menghapus karyawan yang sudah tidak bekerja melalui menu <b>User</b>. Password default karyawan baru biasanya diatur saat pembuatan.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Footer Contact Small --}}
        <div class="text-center pt-8 pb-4">
            <p class="text-xs text-stone-400 font-medium">Sistem Informasi Teh Solo De Jumbo v1.0</p>
            <p class="text-[10px] text-stone-300 mt-1">Dikembangkan oleh Tim IT</p>
        </div>

    </div>
</x-app-layout>
