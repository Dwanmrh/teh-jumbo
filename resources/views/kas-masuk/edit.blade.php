<x-app-layout>
    {{-- Libraries --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="min-h-screen bg-stone-50/50 pb-24 font-sans"
         x-data="{
             qty: {{ old('jumlah', $kasMasuk->jumlah) }},
             harga: {{ old('harga_satuan', $kasMasuk->harga_satuan) }},
             total: 0,
             metode: '{{ old('metode_pembayaran', $kasMasuk->metode_pembayaran) }}',
             init() { this.calculate(); },
             calculate() { this.total = (this.qty || 0) * (this.harga || 0); },
             formatRupiah(number) { return new Intl.NumberFormat('id-ID').format(number); }
         }"
         x-init="init()">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8">

            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <a href="{{ route('kas-masuk.index') }}" class="w-8 h-8 flex items-center justify-center bg-white rounded-full border border-stone-200 text-stone-400 hover:text-amber-600 hover:border-amber-200 transition-colors shadow-sm">
                            <span class="material-symbols-rounded text-lg">arrow_back</span>
                        </a>
                        <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-md border border-amber-100 uppercase tracking-wider">Mode Edit</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl md:text-4xl font-black text-stone-800 tracking-tight">Edit Pemasukan</h1>
                        <span class="text-lg md:text-xl font-medium text-stone-400 select-all">#{{ $kasMasuk->kode_kas ?? $kasMasuk->id }}</span>
                    </div>
                    <p class="text-stone-500 text-sm mt-1 max-w-lg leading-relaxed">Perbarui detail transaksi jika terjadi kesalahan input.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('kas-masuk.update', $kasMasuk->id) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                    {{-- KOLOM KIRI --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- Card Nominal --}}
                        <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-stone-200 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-50 rounded-bl-[4rem] -mr-4 -mt-4 transition-all group-hover:bg-amber-100/50"></div>
                            <div class="relative z-10">
                                <h3 class="font-bold text-stone-700 text-lg mb-6 flex items-center gap-2">
                                    <span class="material-symbols-rounded text-amber-500">edit_note</span> Koreksi Nominal
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                    <div class="md:col-span-3 space-y-2">
                                        <label class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">Qty</label>
                                        <div class="relative">
                                            <input type="number" name="jumlah" x-model="qty" @input="calculate()" min="1" required
                                                class="w-full bg-stone-50 border-stone-200 rounded-2xl text-center font-bold text-stone-700 focus:ring-amber-500/20 focus:border-amber-500 transition-all py-3.5 text-lg"
                                                placeholder="1">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-300 text-xs font-bold">Pcs</span>
                                        </div>
                                    </div>
                                    <div class="md:col-span-9 space-y-2">
                                        <label class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">Harga Satuan</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 font-bold">Rp</span>
                                            <input type="number" name="harga_satuan" x-model="harga" @input="calculate()" min="0" required
                                                class="w-full bg-stone-50 border-stone-200 rounded-2xl text-right font-bold text-stone-700 focus:ring-amber-500/20 focus:border-amber-500 transition-all py-3.5 pr-4 pl-10 text-lg placeholder:text-stone-300"
                                                placeholder="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Detail --}}
                        <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-stone-200">
                            <h3 class="font-bold text-stone-700 text-lg mb-6 flex items-center gap-2">
                                <span class="material-symbols-rounded text-stone-400">receipt_long</span> Detail Transaksi
                            </h3>

                            <div class="space-y-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">Tanggal</label>
                                        <input type="date" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', $kasMasuk->tanggal_transaksi ? \Carbon\Carbon::parse($kasMasuk->tanggal_transaksi)->format('Y-m-d') : date('Y-m-d')) }}" required
                                            class="w-full bg-stone-50 border-stone-200 rounded-2xl text-stone-700 font-medium focus:ring-amber-500/20 focus:border-amber-500 transition-all py-3 px-4 text-sm">
                                    </div>

                                    {{-- COMBOBOX MODERN KATEGORI (EDIT MODE) --}}
                                    <div class="space-y-2"
                                         x-data="{
                                             open: false,
                                             search: '{{ old('kategori', $kasMasuk->kategori) }}',
                                             options: ['Titipan Mitra', 'Suntikan Modal', 'Pendapatan Lain', 'Event Besar', 'Penjualan Tunai (Manual)', 'Dana Talangan', 'Uang Kembalian'],
                                             get filteredOptions() {
                                                 if (this.search === '') return this.options;
                                                 return this.options.filter(option => option.toLowerCase().includes(this.search.toLowerCase()));
                                             },
                                             select(val) {
                                                 this.search = val;
                                                 this.open = false;
                                             }
                                         }">
                                        <label class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">Kategori</label>
                                        <div class="relative" @click.outside="open = false">
                                            {{-- Input Field --}}
                                            <input type="text" name="kategori" x-model="search"
                                                @focus="open = true" @input="open = true"
                                                class="w-full bg-stone-50 border-stone-200 rounded-2xl text-stone-700 font-bold focus:ring-amber-500/20 focus:border-amber-500 transition-all py-3 pl-4 pr-10 text-sm"
                                                placeholder="Ketik atau pilih..." autocomplete="off">

                                            {{-- Icon Chevron --}}
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none transition-transform duration-300"
                                                  :class="open ? 'rotate-180' : ''">
                                                <span class="material-symbols-rounded">expand_more</span>
                                            </span>

                                            {{-- Dropdown List --}}
                                            <div x-show="open" x-transition.opacity.duration.200ms
                                                 class="absolute z-50 mt-1 w-full bg-white rounded-2xl shadow-xl border border-stone-100 max-h-60 overflow-y-auto custom-scrollbar p-1.5">

                                                <template x-for="option in filteredOptions" :key="option">
                                                    <button type="button" @click="select(option)"
                                                        class="w-full text-left px-3 py-2.5 rounded-xl text-sm font-medium text-stone-600 hover:bg-amber-50 hover:text-amber-700 transition-colors flex items-center justify-between group">
                                                        <span x-text="option"></span>
                                                        <span class="material-symbols-rounded text-amber-500 opacity-0 group-hover:opacity-100 text-lg">check</span>
                                                    </button>
                                                </template>

                                                {{-- Opsi Baru --}}
                                                <div x-show="filteredOptions.length === 0 && search.length > 0" class="px-3 py-2.5 text-sm text-stone-400 italic flex items-center gap-2">
                                                    <span class="material-symbols-rounded text-amber-500">add_circle</span>
                                                    <span>Gunakan kategori baru: "<span x-text="search" class="font-bold text-stone-600"></span>"</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- END COMBOBOX --}}
                                </div>

                                {{-- Metode Pembayaran --}}
                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">Metode Pembayaran</label>
                                    <input type="hidden" name="metode_pembayaran" x-model="metode">
                                    <div class="grid grid-cols-3 gap-3">
                                        <template x-for="m in ['Tunai', 'Transfer', 'QRIS']">
                                            <button type="button" @click="metode = m"
                                                :class="metode === m ? 'bg-amber-600 text-white shadow-lg shadow-amber-500/30 ring-2 ring-amber-600 ring-offset-2' : 'bg-stone-50 text-stone-500 hover:bg-stone-100 border border-stone-200'"
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
                                        <label class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">Keterangan / Catatan</label>
                                        <span class="text-[10px] bg-stone-100 text-stone-400 px-2 py-0.5 rounded-full font-bold">Opsional</span>
                                    </div>
                                    <textarea name="keterangan" rows="2"
                                        class="w-full bg-stone-50 border-stone-200 rounded-2xl text-stone-700 font-medium focus:ring-amber-500/20 focus:border-amber-500 transition-all py-3 px-4 text-sm leading-relaxed placeholder:text-stone-300"
                                        placeholder="Keterangan transaksi...">{{ old('keterangan', $kasMasuk->keterangan) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN (Summary Edit) --}}
                    <div class="lg:col-span-1">
                        <div class="sticky top-6 space-y-6">
                            <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-[2rem] p-6 text-white shadow-xl shadow-orange-500/20 relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-40 h-40 bg-white/20 rounded-full blur-2xl -mr-10 -mt-10"></div>
                                <div class="absolute bottom-0 left-0 w-32 h-32 bg-black/10 rounded-full blur-2xl -ml-6 -mb-6"></div>
                                <div class="relative z-10">
                                    <span class="text-orange-100 text-xs font-bold uppercase tracking-widest">Total Baru</span>
                                    <div class="mt-2 mb-1 flex flex-col sm:flex-row sm:items-start gap-1">
                                        <span class="text-white/90 text-xl font-medium mt-1 mr-1">Rp</span>

                                        {{-- PERBAIKAN: Dynamic Font Size --}}
                                        <h2 class="font-black tracking-tight leading-none transition-all duration-300 break-all"
                                            :class="formatRupiah(total).length > 10 ? 'text-2xl md:text-4xl' : 'text-3xl md:text-5xl'"
                                            x-text="formatRupiah(total)">
                                            0
                                        </h2>
                                    </div>
                                    <div class="h-px bg-white/20 my-4"></div>
                                    <div class="flex justify-between items-center text-sm text-orange-50 font-mono">
                                        <span>Items</span>
                                        <span class="truncate max-w-[150px]" x-text="qty + ' x ' + formatRupiah(harga)"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-3">
                                <button type="submit"
                                    class="w-full bg-stone-800 hover:bg-stone-900 text-white font-bold py-4 rounded-2xl shadow-lg shadow-stone-800/20 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 group">
                                    <span class="material-symbols-rounded group-hover:rotate-12 transition-transform">save_as</span>
                                    Simpan Perubahan
                                </button>
                                <a href="{{ route('kas-masuk.index') }}"
                                    class="w-full bg-white hover:bg-stone-50 text-stone-500 font-bold py-4 rounded-2xl border border-stone-200 transition-all flex items-center justify-center gap-2">
                                    Batal
                                </a>
                            </div>

                            <div class="bg-orange-50/50 rounded-2xl p-4 border border-orange-100 flex gap-3">
                                <span class="material-symbols-rounded text-orange-400 text-xl shrink-0">warning</span>
                                <p class="text-xs text-orange-600/80 leading-relaxed font-medium">Perubahan ini akan memperbarui saldo kas secara otomatis.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>
