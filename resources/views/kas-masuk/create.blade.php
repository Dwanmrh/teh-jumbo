<x-app-layout>
    {{-- Libraries --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Container Utama --}}
    <div class="min-h-screen bg-stone-50/50 pb-24 font-sans"
         x-data="{
             qty: 1,
             harga: '',
             total: 0,
             metode: 'Tunai',
             kategori: 'Penjualan Tunai', {{-- DEFAULT LANGSUNG KE PENJUALAN TUNAI --}}
             calculate() {
                 this.total = (this.qty || 0) * (this.harga || 0);
             },
             setHarga(amount) {
                 this.harga = amount;
                 this.calculate();
             },
             formatRupiah(number) {
                 return new Intl.NumberFormat('id-ID').format(number);
             }
         }">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8">

            {{-- 1. HEADER SECTION --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <a href="{{ route('kas-masuk.index') }}" class="w-8 h-8 flex items-center justify-center bg-white rounded-full border border-stone-200 text-stone-400 hover:text-emerald-600 hover:border-emerald-200 transition-colors shadow-sm">
                            <span class="material-symbols-rounded text-lg">arrow_back</span>
                        </a>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100 uppercase tracking-wider">Formulir Baru</span>
                    </div>
                    <h1 class="text-2xl md:text-4xl font-black text-stone-800 tracking-tight">Catat Pemasukan</h1>
                    <p class="text-stone-500 text-sm mt-1 max-w-lg leading-relaxed">Masukkan detail transaksi penjualan Teh Jumbo / pemasukan lainnya.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('kas-masuk.store') }}">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                    {{-- 2. KOLOM KIRI (Input Utama) --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- Card Input Nominal --}}
                        <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-stone-200 relative overflow-hidden group">
                            {{-- Decorative Background --}}
                            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-bl-[4rem] -mr-4 -mt-4 transition-all group-hover:bg-emerald-100/50"></div>

                            <div class="relative z-10">
                                <h3 class="font-bold text-stone-700 text-lg mb-6 flex items-center gap-2">
                                    <span class="material-symbols-rounded text-emerald-500">attach_money</span> Rincian Nominal
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                    {{-- Input Qty --}}
                                    <div class="md:col-span-3 space-y-2">
                                        <label class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">Qty</label>
                                        <div class="relative">
                                            <input type="number" name="jumlah" x-model="qty" @input="calculate()" min="1" required
                                                class="w-full bg-stone-50 border-stone-200 rounded-2xl text-center font-bold text-stone-700 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all py-3.5 text-lg"
                                                placeholder="1">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-300 text-xs font-bold">Pcs</span>
                                        </div>
                                    </div>

                                    {{-- Input Harga Satuan --}}
                                    <div class="md:col-span-9 space-y-2">
                                        <label class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">Harga Satuan</label>

                                        {{-- PRESET HARGA (FITUR BARU UNTUK TEH JUMBO) --}}
                                        <div class="flex gap-2 overflow-x-auto pb-2 no-scrollbar">
                                            <button type="button" @click="setHarga(3000)" class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-all hover:scale-105 active:scale-95" :class="harga == 3000 ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-stone-50 text-stone-500 border-stone-200 hover:bg-emerald-50'">3K (Kecil)</button>
                                            <button type="button" @click="setHarga(5000)" class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-all hover:scale-105 active:scale-95" :class="harga == 5000 ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-stone-50 text-stone-500 border-stone-200 hover:bg-emerald-50'">5K (Jumbo)</button>
                                            <button type="button" @click="setHarga(8000)" class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-all hover:scale-105 active:scale-95" :class="harga == 8000 ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-stone-50 text-stone-500 border-stone-200 hover:bg-emerald-50'">7K (Susu)</button>
                                            <button type="button" @click="setHarga(10000)" class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-all hover:scale-105 active:scale-95" :class="harga == 10000 ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-stone-50 text-stone-500 border-stone-200 hover:bg-emerald-50'">10K (Spesial)</button>
                                        </div>

                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 font-bold">Rp</span>
                                            <input type="number" name="harga_satuan" x-model="harga" @input="calculate()" min="0" required
                                                class="w-full bg-stone-50 border-stone-200 rounded-2xl text-right font-bold text-stone-700 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all py-3.5 pr-4 pl-10 text-lg placeholder:text-stone-300"
                                                placeholder="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Detail Transaksi --}}
                        <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-stone-200">
                            <h3 class="font-bold text-stone-700 text-lg mb-6 flex items-center gap-2">
                                <span class="material-symbols-rounded text-stone-400">receipt_long</span> Detail Transaksi
                            </h3>

                            <div class="space-y-5">
                                {{-- Tanggal & Kategori --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">Tanggal</label>
                                        <input type="date" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" required
                                            class="w-full bg-stone-50 border-stone-200 rounded-2xl text-stone-700 font-medium focus:ring-emerald-500/20 focus:border-emerald-500 transition-all py-3 px-4 text-sm">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">Kategori</label>
                                        <div class="relative">
                                            <select name="kategori" x-model="kategori"
                                                class="w-full bg-stone-50 border-stone-200 rounded-2xl text-stone-700 font-medium focus:ring-emerald-500/20 focus:border-emerald-500 transition-all py-3 pl-4 pr-10 text-sm appearance-none">
                                                <option value="Penjualan Tunai">Penjualan Tunai (Booth)</option>
                                                <option value="Event Besar">Event Besar (Jumat Berkah)</option>
                                                <option value="Titipan Mitra">Titipan Mitra (Snack/Gorengan)</option>
                                                <option value="Suntikan Modal">Suntikan Modal (Owner)</option>
                                                <option value="Pendapatan Lain">Pendapatan Lain</option>
                                            </select>
                                            <span class="material-symbols-rounded absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none">expand_more</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Metode Pembayaran (Visual Select) --}}
                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">Metode Pembayaran</label>
                                    <input type="hidden" name="metode_pembayaran" x-model="metode">
                                    <div class="grid grid-cols-3 gap-3">
                                        <template x-for="m in ['Tunai', 'Transfer', 'QRIS']">
                                            <button type="button" @click="metode = m"
                                                :class="metode === m ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/30 ring-2 ring-emerald-600 ring-offset-2' : 'bg-stone-50 text-stone-500 hover:bg-stone-100 border border-stone-200'"
                                                class="py-3 px-2 rounded-xl text-xs md:text-sm font-bold transition-all flex flex-col items-center gap-1.5 md:flex-row md:justify-center">
                                                <span class="material-symbols-rounded text-lg" x-text="m === 'Tunai' ? 'payments' : (m === 'Transfer' ? 'account_balance' : 'qr_code_scanner')"></span>
                                                <span x-text="m"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                {{-- Keterangan --}}
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <label class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">Keterangan / Varian Rasa</label>
                                        <span class="text-[10px] bg-stone-100 text-stone-400 px-2 py-0.5 rounded-full font-bold">Opsional</span>
                                    </div>
                                    <textarea name="keterangan" rows="2"
                                        class="w-full bg-stone-50 border-stone-200 rounded-2xl text-stone-700 font-medium focus:ring-emerald-500/20 focus:border-emerald-500 transition-all py-3 px-4 text-sm leading-relaxed placeholder:text-stone-300"
                                        placeholder="Kosongkan jika buru-buru..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. KOLOM KANAN (Ringkasan Live) --}}
                    <div class="lg:col-span-1">
                        <div class="sticky top-6 space-y-6">

                            {{-- Card Total --}}
                            <div class="bg-gradient-to-br from-stone-800 to-stone-900 rounded-[2rem] p-6 text-white shadow-xl shadow-stone-900/20 relative overflow-hidden">
                                {{-- Background Patterns --}}
                                <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full blur-2xl -mr-10 -mt-10"></div>
                                <div class="absolute bottom-0 left-0 w-32 h-32 bg-emerald-500/20 rounded-full blur-2xl -ml-6 -mb-6"></div>

                                <div class="relative z-10">
                                    <span class="text-stone-400 text-xs font-bold uppercase tracking-widest">Total Estimasi</span>
                                    <div class="mt-2 mb-1 flex items-start">
                                        <span class="text-emerald-400 text-xl font-medium mt-1 mr-1">Rp</span>
                                        <h2 class="text-4xl md:text-5xl font-black tracking-tight" x-text="formatRupiah(total)">0</h2>
                                    </div>
                                    <div class="h-px bg-white/10 my-4"></div>
                                    <div class="flex justify-between items-center text-sm text-stone-300 font-mono">
                                        <span>Items</span>
                                        <span x-text="qty + ' x ' + formatRupiah(harga)"></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="grid grid-cols-1 gap-3">
                                <button type="submit"
                                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 group">
                                    <span class="material-symbols-rounded group-hover:rotate-12 transition-transform">save</span>
                                    Simpan Pemasukan
                                </button>
                                <a href="{{ route('kas-masuk.index') }}"
                                    class="w-full bg-white hover:bg-stone-50 text-stone-500 font-bold py-4 rounded-2xl border border-stone-200 transition-all flex items-center justify-center gap-2">
                                    Batal
                                </a>
                            </div>

                            {{-- Helper Info --}}
                            <div class="bg-blue-50/50 rounded-2xl p-4 border border-blue-100 flex gap-3">
                                <span class="material-symbols-rounded text-blue-400 text-xl shrink-0">info</span>
                                <p class="text-xs text-blue-600/80 leading-relaxed font-medium">Pastikan varian rasa dicatat di keterangan untuk keperluan stok opname.</p>
                            </div>

                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>
