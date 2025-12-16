<x-app-layout>
    <x-slot name="title">Edit KM</x-slot>

    {{-- =====================================================================
         LIBRARIES & META
         ===================================================================== --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{--
        MAIN CONTAINER FIX:
        1. Menghapus 'min-h-screen' dan 'max-w-6xl px-4...' bawaan lama.
        2. Mengganti dengan 'flex flex-col space-y-8' agar layout mengalir rapi sesuai parent.
    --}}
    <div class="flex flex-col space-y-8 font-sans"
         x-data="{
             qty: {{ old('jumlah', $kasMasuk->jumlah) }},
             harga: {{ old('harga_satuan', $kasMasuk->harga_satuan) }},
             total: 0,
             metode: '{{ old('metode_pembayaran', $kasMasuk->metode_pembayaran) }}',
             kategori: '{{ old('kategori', $kasMasuk->kategori) }}',
             kategoriOpen: false,
             options: ['Titipan Mitra', 'Suntikan Modal', 'Pendapatan Lain', 'Event Besar', 'Penjualan Tunai (Manual)', 'Dana Talangan', 'Uang Kembalian'],

             calculate() {
                 this.total = (parseInt(this.qty) || 0) * (parseInt(this.harga) || 0);
             },
             formatRupiah(number) {
                 return new Intl.NumberFormat('id-ID').format(number);
             },
             get totalLengthClass() {
                 let len = this.formatRupiah(this.total).length;
                 if (len <= 9) return 'text-4xl md:text-5xl';
                 if (len <= 12) return 'text-3xl md:text-4xl';
                 return 'text-2xl md:text-3xl';
             },
             init() {
                 this.calculate();
                 this.$watch('qty', () => this.calculate());
                 this.$watch('harga', () => this.calculate());
             },
             get filteredOptions() {
                 if (this.kategori === '') return this.options;
                 return this.options.filter(option => option.toLowerCase().includes(this.kategori.toLowerCase()));
             }
         }"
         x-init="init()">

        {{-- 1. HEADER & BACK BUTTON --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 animate-[fadeIn_0.3s_ease-out]">
            <div>
                {{-- Breadcrumb / Label --}}
                <div class="flex items-center gap-2 mb-2">
                    <a href="{{ route('kas-masuk.index') }}" class="w-6 h-6 rounded-full bg-stone-200 flex items-center justify-center hover:bg-amber-500 hover:text-white transition-all group">
                        <span class="material-symbols-rounded text-base">arrow_back</span>
                    </a>
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                    <p class="text-xs font-bold tracking-widest text-amber-600 uppercase">Edit Transaksi</p>
                </div>

                <div class="flex items-center gap-3">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-stone-800 tracking-tight leading-tight">
                        Edit Pemasukan
                    </h1>
                    <span class="bg-amber-100 text-amber-700 border border-amber-200 text-[10px] md:text-xs font-bold px-2 py-1 rounded-lg font-mono">
                        #{{ $kasMasuk->kode_kas ?? $kasMasuk->id }}
                    </span>
                </div>
                <p class="text-stone-500 text-sm mt-2 max-w-lg leading-relaxed font-medium">
                    Lakukan koreksi data transaksi jika terjadi kesalahan input sebelumnya.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('kas-masuk.update', $kasMasuk->id) }}" class="animate-[fadeIn_0.5s_ease-out]">
            @csrf
            @method('PUT')

            {{-- Alert Session Error --}}
            @if ($errors->any())
                <div class="mb-8 bg-rose-50 border border-rose-100 text-rose-600 px-6 py-4 rounded-[1.5rem] shadow-sm flex items-start gap-3">
                    <span class="material-symbols-rounded text-rose-500 mt-0.5">warning</span>
                    <div>
                        <h3 class="font-bold text-sm mb-1">Periksa Kembali Inputan</h3>
                        <ul class="list-disc list-inside text-xs space-y-0.5 opacity-90 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                {{-- KOLOM KIRI (INPUT FORM) --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Card 1: Nominal (Style Edit - Amber Theme) --}}
                    <div class="bg-white rounded-[2.5rem] p-6 md:p-8 shadow-soft border border-stone-100 relative overflow-hidden group hover:border-amber-300/30 transition-all duration-500">
                         {{-- Background Texture --}}
                         <div class="absolute top-0 right-0 w-40 h-40 bg-amber-50 rounded-bl-[5rem] -mr-10 -mt-10 transition-transform duration-700 group-hover:scale-110 opacity-50"></div>

                        <div class="relative z-10">
                            <h3 class="font-bold text-stone-800 text-lg mb-6 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 border border-amber-200">
                                    <span class="material-symbols-rounded text-lg">edit_note</span>
                                </span>
                                Koreksi Nominal
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                {{-- Input Qty --}}
                                <div class="md:col-span-3 space-y-2">
                                    <label class="text-[10px] font-bold text-stone-400 uppercase tracking-widest ml-3">Qty</label>
                                    <div class="relative group/input">
                                        <input type="number" name="jumlah" x-model="qty" min="1" required
                                            class="w-full bg-stone-50 border-transparent focus:border-amber-500/50 rounded-[1.5rem] text-center font-bold text-stone-800 focus:bg-white focus:ring-4 focus:ring-amber-500/10 transition-all py-3.5 text-lg shadow-inner placeholder:text-stone-300"
                                            placeholder="1">
                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 text-[10px] font-bold uppercase pointer-events-none group-focus-within/input:text-amber-500 transition-colors">Pcs</span>
                                    </div>
                                </div>

                                {{-- Input Harga Satuan --}}
                                <div class="md:col-span-9 space-y-2">
                                    <label class="text-[10px] font-bold text-stone-400 uppercase tracking-widest ml-3">Harga Satuan</label>
                                    <div class="relative group/input">
                                        <div class="absolute left-5 top-1/2 -translate-y-1/2 text-stone-400 font-bold group-focus-within/input:text-amber-600 transition-colors">Rp</div>
                                        <input type="number" name="harga_satuan" x-model="harga" min="0" required
                                            class="w-full bg-stone-50 border-transparent focus:border-amber-500/50 rounded-[1.5rem] text-right font-bold text-stone-800 focus:bg-white focus:ring-4 focus:ring-amber-500/10 transition-all py-3.5 pr-6 pl-12 text-lg placeholder:text-stone-300 shadow-inner"
                                            placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Detail Transaksi --}}
                    <div class="bg-white rounded-[2.5rem] p-6 md:p-8 shadow-soft border border-stone-100">
                        <h3 class="font-bold text-stone-800 text-lg mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-stone-100 flex items-center justify-center text-stone-500 border border-stone-200">
                                <span class="material-symbols-rounded text-lg">receipt_long</span>
                            </span>
                            Detail Transaksi
                        </h3>

                        <div class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                {{-- Input Tanggal --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-stone-400 uppercase tracking-widest ml-3">Tanggal</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 material-symbols-rounded text-lg pointer-events-none">calendar_today</span>
                                        <input type="date" name="tanggal_transaksi"
                                            value="{{ old('tanggal_transaksi', $kasMasuk->tanggal_transaksi ? \Carbon\Carbon::parse($kasMasuk->tanggal_transaksi)->format('Y-m-d') : date('Y-m-d')) }}" required
                                            class="w-full bg-stone-50 border-transparent focus:border-amber-500/50 rounded-[1.5rem] text-stone-700 font-bold focus:bg-white focus:ring-4 focus:ring-amber-500/10 transition-all py-3.5 pl-12 pr-4 text-sm shadow-inner cursor-pointer">
                                    </div>
                                </div>

                                {{-- Combobox Kategori --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-stone-400 uppercase tracking-widest ml-3">Kategori</label>
                                    <div class="relative" @click.outside="kategoriOpen = false">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 material-symbols-rounded text-lg pointer-events-none">category</span>
                                        <input type="text" name="kategori" x-model="kategori"
                                            @focus="kategoriOpen = true" @input="kategoriOpen = true"
                                            class="w-full bg-stone-50 border-transparent focus:border-amber-500/50 rounded-[1.5rem] text-stone-700 font-bold focus:bg-white focus:ring-4 focus:ring-amber-500/10 transition-all py-3.5 pl-12 pr-10 text-sm shadow-inner placeholder:text-stone-300"
                                            placeholder="Pilih atau ketik..." autocomplete="off">

                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none transition-transform duration-300"
                                            :class="kategoriOpen ? 'rotate-180 text-amber-500' : ''">
                                            <span class="material-symbols-rounded">expand_more</span>
                                        </span>

                                        {{-- Dropdown Menu --}}
                                        <div x-show="kategoriOpen" x-transition.opacity.duration.200ms style="display: none;"
                                            class="absolute z-50 mt-2 w-full bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-stone-100 max-h-60 overflow-y-auto p-1.5 ring-1 ring-black/5 custom-scrollbar">
                                            <template x-for="option in filteredOptions" :key="option">
                                                <button type="button" @click="kategori = option; kategoriOpen = false"
                                                    class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold text-stone-600 hover:bg-amber-50 hover:text-amber-700 transition-colors flex items-center justify-between group">
                                                    <span x-text="option"></span>
                                                    <span class="material-symbols-rounded text-amber-500 opacity-0 group-hover:opacity-100 text-base scale-75 group-hover:scale-100 transition-all">check</span>
                                                </button>
                                            </template>
                                            <div x-show="filteredOptions.length === 0 && kategori.length > 0"
                                                 class="px-4 py-3 text-xs font-medium text-stone-400 italic bg-stone-50 rounded-xl border border-dashed border-stone-200 text-center m-1">
                                                Kategori baru: <br>
                                                <span x-text="kategori" class="font-bold text-amber-600 not-italic"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Metode Pembayaran --}}
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-stone-400 uppercase tracking-widest ml-3">Metode Penerimaan</label>
                                <input type="hidden" name="metode_pembayaran" x-model="metode">
                                <div class="grid grid-cols-3 gap-3">
                                    @foreach(['Tunai' => 'payments', 'Transfer' => 'account_balance', 'QRIS' => 'qr_code_scanner'] as $label => $icon)
                                        <button type="button" @click="metode = '{{ $label }}'"
                                            :class="metode === '{{ $label }}'
                                                ? 'bg-stone-800 text-white shadow-xl shadow-stone-800/20 ring-2 ring-stone-800 ring-offset-2'
                                                : 'bg-white text-stone-500 hover:bg-stone-50 border border-stone-200 hover:border-stone-300'"
                                            class="py-3 px-2 rounded-[1.2rem] text-xs md:text-sm font-bold transition-all flex flex-col items-center gap-2 md:flex-row md:justify-center relative overflow-hidden group active:scale-95">
                                            <span class="material-symbols-rounded text-lg" :class="metode === '{{ $label }}' ? 'text-amber-400' : 'text-stone-300 group-hover:text-stone-500'">{{ $icon }}</span>
                                            <span>{{ $label }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Keterangan --}}
                            <div class="space-y-2">
                                <div class="flex justify-between items-center ml-3">
                                    <label class="text-[10px] font-bold text-stone-400 uppercase tracking-widest">Keterangan</label>
                                    <span class="text-[10px] bg-stone-100 text-stone-400 px-2 py-0.5 rounded-full font-bold">Opsional</span>
                                </div>
                                <textarea name="keterangan" rows="3"
                                    class="w-full bg-stone-50 border-transparent focus:border-amber-500/50 rounded-[1.5rem] text-stone-700 font-medium focus:bg-white focus:ring-4 focus:ring-amber-500/10 transition-all py-3.5 px-5 text-sm leading-relaxed placeholder:text-stone-300 resize-none shadow-inner"
                                    placeholder="Keterangan transaksi...">{{ old('keterangan', $kasMasuk->keterangan) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN (SUMMARY STICKY) --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-[110px] space-y-4">

                        {{-- Warning Banner --}}
                        <div class="bg-amber-50 border border-amber-100 p-4 rounded-3xl flex items-start gap-3 shadow-sm">
                            <div class="bg-amber-100 p-2 rounded-full text-amber-600 shrink-0">
                                <span class="material-symbols-rounded text-lg">priority_high</span>
                            </div>
                            <div class="text-xs text-stone-600 leading-relaxed font-medium pt-1">
                                <span class="font-bold text-stone-800 block mb-0.5">Perhatian</span>
                                Mengubah nominal akan mempengaruhi saldo kas akhir secara otomatis.
                            </div>
                        </div>

                        {{-- Total Card (Gradient Amber/Orange for Edit) --}}
                        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-[2.5rem] p-6 text-white shadow-2xl shadow-orange-600/30 relative overflow-hidden border border-white/10 group">
                            {{-- Background Effects --}}
                            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/20 rounded-full blur-[50px] group-hover:bg-white/30 transition-all duration-700"></div>
                            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-black/10 rounded-full blur-[40px]"></div>
                            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] mix-blend-overlay"></div>

                            <div class="relative z-10 flex flex-col h-full justify-between min-h-[160px]">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="bg-white/20 backdrop-blur-md border border-white/20 text-white px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider shadow-sm">
                                            Estimasi Baru
                                        </span>
                                    </div>

                                    <div class="mt-3 flex flex-col xl:flex-row xl:items-start gap-1">
                                        <span class="text-orange-100 text-lg font-bold mt-1 mr-1">Rp</span>
                                        <h2 class="font-black leading-none break-all tracking-tight"
                                            :class="totalLengthClass"
                                            x-text="formatRupiah(total)">0</h2>
                                    </div>
                                </div>

                                <div class="mt-6 pt-4 border-t border-white/20 flex justify-between items-center">
                                    <div class="flex flex-col max-w-[75%]">
                                        <span class="text-[10px] text-orange-100/70 font-bold uppercase tracking-wide">Rincian Item</span>
                                        <span class="text-xs font-mono text-white truncate font-medium mt-0.5" x-text="qty + ' x ' + (harga ? formatRupiah(harga) : '0')"></span>
                                    </div>
                                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-md border border-white/10 shrink-0 shadow-inner">
                                        <span class="material-symbols-rounded text-white text-lg">calculate</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                class="w-full group relative px-6 py-4 bg-stone-800 hover:bg-stone-900 text-white rounded-[1.5rem] flex items-center justify-center gap-3 transition-all duration-300 shadow-xl shadow-stone-200 hover:shadow-stone-500/30 hover:-translate-y-1 overflow-hidden">
                                <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
                                <span class="material-symbols-rounded bg-white/20 rounded-full p-0.5 group-hover:rotate-12 transition-transform">save_as</span>
                                <span class="font-bold tracking-wide">Simpan Perubahan</span>
                            </button>

                            <a href="{{ route('kas-masuk.index') }}"
                                class="w-full bg-white hover:bg-stone-50 text-stone-500 hover:text-stone-700 font-bold py-4 rounded-[1.5rem] border border-stone-200 hover:border-stone-300 transition-all flex items-center justify-center gap-2 active:scale-95">
                                Batal
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>
</x-app-layout>
