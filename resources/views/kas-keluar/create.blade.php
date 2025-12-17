<x-app-layout>
    <x-slot name="title">Create KK</x-slot>

    {{-- =====================================================================
    LIBRARIES & META
    ===================================================================== --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- =====================================================================
    MAIN CONTAINER
    ===================================================================== --}}
    <div class="flex flex-col space-y-8 font-sans" x-data="{
             nominal: '',
             displayNominal: '',
             metode: 'Tunai',
             kategori: 'Belanja Stok',
             kategoriOpen: false,
             options: ['Belanja Stok', 'Operasional Toko', 'Gaji Karyawan', 'Sewa Tempat', 'Listrik & Air', 'Maintenance', 'Lain-lain'],

             // Logic Image Preview
             imageUrl: null,
             fileName: null,

             // Logic Format Nominal (Single Input)
             formatRupiah(value) {
                 if(!value) return '';
                 return new Intl.NumberFormat('id-ID').format(value);
             },
             updateNominal(e) {
                 let raw = e.target.value.replace(/\D/g, '');
                 this.nominal = raw;
                 this.displayNominal = raw ? this.formatRupiah(raw) : '';
             },
             get totalLengthClass() {
                 let len = (this.displayNominal || '').length;
                 if (len <= 10) return 'text-4xl md:text-5xl';
                 if (len <= 14) return 'text-3xl md:text-4xl';
                 return 'text-2xl md:text-3xl';
             },

             // Logic Image
             fileChosen(event) {
                 let file = event.target.files[0];
                 if(file) {
                     this.fileName = file.name;
                     let reader = new FileReader();
                     reader.onload = (e) => this.imageUrl = e.target.result;
                     reader.readAsDataURL(file);
                 }
             },
             removeImage() {
                 this.imageUrl = null;
                 this.fileName = null;
                 document.getElementById('file-upload').value = '';
             },

             // Logic Kategori Search
             get filteredOptions() {
                 if (this.kategori === '') return this.options;
                 return this.options.filter(option => option.toLowerCase().includes(this.kategori.toLowerCase()));
             }
         }">

        {{-- 1. HEADER & BACK BUTTON --}}
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 animate-[fadeIn_0.3s_ease-out]">
            <div>
                {{-- Breadcrumb / Label --}}
                <div class="flex items-center gap-2 mb-2">
                    <a href="{{ route('kas-keluar.index') }}"
                        class="w-6 h-6 rounded-full bg-stone-200 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all group">
                        <span class="material-symbols-rounded text-base">arrow_back</span>
                    </a>
                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                    <p class="text-xs font-bold tracking-widest text-rose-600 uppercase">Input Pengeluaran</p>
                </div>

                <h1 class="text-3xl md:text-4xl font-extrabold text-stone-800 tracking-tight leading-tight">
                    Catat Pengeluaran
                </h1>
                <p class="text-stone-500 text-sm mt-2 max-w-lg leading-relaxed font-medium">
                    Input data pengeluaran operasional, belanja stok, atau pembayaran gaji karyawan.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('kas-keluar.store') }}" enctype="multipart/form-data"
            class="animate-[fadeIn_0.5s_ease-out]">
            @csrf

            {{-- Alert Session Error --}}
            @if(session('error'))
                <div
                    class="mb-6 bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-[1.5rem] flex items-center gap-3 shadow-sm animate-pulse">
                    <span class="material-symbols-rounded">error</span>
                    <span class="font-bold text-sm leading-relaxed">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div
                    class="mb-8 bg-rose-50 border border-rose-100 text-rose-600 px-6 py-4 rounded-[1.5rem] shadow-sm flex items-start gap-3">
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

                    {{-- Card 1: Nominal (INPUT MANUAL TUNGGAL) --}}
                    <div
                        class="bg-white rounded-[2.5rem] p-6 md:p-8 shadow-soft border border-stone-100 relative overflow-hidden group hover:border-rose-300/30 transition-all duration-500">
                        {{-- Background Texture --}}
                        <div
                            class="absolute top-0 right-0 w-40 h-40 bg-rose-50 rounded-bl-[5rem] -mr-10 -mt-10 transition-transform duration-700 group-hover:scale-110 opacity-50">
                        </div>

                        <div class="relative z-10">
                            <h3 class="font-bold text-stone-800 text-lg mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 border border-rose-200">
                                    <span class="material-symbols-rounded text-lg">money_off</span>
                                </span>
                                Nominal Keluar
                            </h3>

                            <div class="relative">
                                <span
                                    class="absolute left-6 top-1/2 -translate-y-1/2 text-stone-400 text-2xl md:text-3xl font-bold group-focus-within:text-rose-600 transition-colors">Rp</span>
                                {{-- Input Display (Format Rupiah) --}}
                                <input type="text" x-model="displayNominal" @input="updateNominal" placeholder="0"
                                    required
                                    class="w-full bg-stone-50 border-transparent focus:border-rose-500/50 rounded-[2rem] py-6 pl-16 md:pl-20 pr-6 text-3xl md:text-5xl font-black text-stone-800 focus:bg-white focus:ring-4 focus:ring-rose-500/10 transition-all outline-none placeholder-stone-200 tracking-tight shadow-inner">
                                {{-- Input Hidden (Nilai Asli) --}}
                                <input type="hidden" name="nominal" :value="nominal">
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Detail Transaksi --}}
                    <div class="bg-white rounded-[2.5rem] p-6 md:p-8 shadow-soft border border-stone-100">
                        <h3 class="font-bold text-stone-800 text-lg mb-6 flex items-center gap-2">
                            <span
                                class="w-8 h-8 rounded-full bg-stone-100 flex items-center justify-center text-stone-500 border border-stone-200">
                                <span class="material-symbols-rounded text-lg">receipt_long</span>
                            </span>
                            Detail Pengeluaran
                        </h3>

                        <div class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                {{-- Input Tanggal --}}
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-bold text-stone-400 uppercase tracking-widest ml-3">Tanggal</label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 material-symbols-rounded text-lg pointer-events-none">calendar_today</span>
                                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                                            class="w-full bg-stone-50 border-transparent focus:border-rose-500/50 rounded-[1.5rem] text-stone-700 font-bold focus:bg-white focus:ring-4 focus:ring-rose-500/10 transition-all py-3.5 pl-12 pr-4 text-sm shadow-inner cursor-pointer">
                                    </div>
                                </div>

                                {{-- Combobox Kategori --}}
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-bold text-stone-400 uppercase tracking-widest ml-3">Kategori</label>
                                    <div class="relative" @click.outside="kategoriOpen = false">
                                        <span
                                            class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 material-symbols-rounded text-lg pointer-events-none">category</span>
                                        <input type="text" name="kategori" x-model="kategori"
                                            @focus="kategoriOpen = true" @input="kategoriOpen = true"
                                            class="w-full bg-stone-50 border-transparent focus:border-rose-500/50 rounded-[1.5rem] text-stone-700 font-bold focus:bg-white focus:ring-4 focus:ring-rose-500/10 transition-all py-3.5 pl-12 pr-10 text-sm shadow-inner placeholder:text-stone-300"
                                            placeholder="Pilih atau ketik..." autocomplete="off">

                                        <span
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none transition-transform duration-300"
                                            :class="kategoriOpen ? 'rotate-180 text-rose-500' : ''">
                                            <span class="material-symbols-rounded">expand_more</span>
                                        </span>

                                        {{-- Dropdown Menu --}}
                                        <div x-show="kategoriOpen" x-transition.opacity.duration.200ms
                                            style="display: none;"
                                            class="absolute z-50 mt-2 w-full bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-stone-100 max-h-60 overflow-y-auto p-1.5 ring-1 ring-black/5 custom-scrollbar">
                                            <template x-for="option in filteredOptions" :key="option">
                                                <button type="button" @click="kategori = option; kategoriOpen = false"
                                                    class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold text-stone-600 hover:bg-rose-50 hover:text-rose-700 transition-colors flex items-center justify-between group">
                                                    <span x-text="option"></span>
                                                    <span
                                                        class="material-symbols-rounded text-rose-500 opacity-0 group-hover:opacity-100 text-base scale-75 group-hover:scale-100 transition-all">check</span>
                                                </button>
                                            </template>
                                            <div x-show="filteredOptions.length === 0 && kategori.length > 0"
                                                class="px-4 py-3 text-xs font-medium text-stone-400 italic bg-stone-50 rounded-xl border border-dashed border-stone-200 text-center m-1">
                                                Buat kategori baru: <br>
                                                <span x-text="kategori"
                                                    class="font-bold text-rose-600 not-italic"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Penerima --}}
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-bold text-stone-400 uppercase tracking-widest ml-3">Dibayarkan
                                    Kepada</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 material-symbols-rounded text-lg pointer-events-none">person</span>
                                    <input type="text" name="penerima" required
                                        placeholder="Contoh: Toko Plastik Jaya, PLN..."
                                        class="w-full bg-stone-50 border-transparent focus:border-rose-500/50 rounded-[1.5rem] text-stone-700 font-bold focus:bg-white focus:ring-4 focus:ring-rose-500/10 transition-all py-3.5 pl-12 pr-4 text-sm shadow-inner placeholder:text-stone-300 placeholder:font-normal">
                                </div>
                            </div>

                            {{-- Metode Pembayaran --}}
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-bold text-stone-400 uppercase tracking-widest ml-3">Sumber
                                    Dana</label>
                                <input type="hidden" name="metode_pembayaran" x-model="metode">
                                <div class="grid grid-cols-3 gap-3">
                                    @foreach(['Tunai' => 'wallet', 'Transfer' => 'account_balance', 'QRIS' => 'qr_code_scanner'] as $label => $icon)
                                        <button type="button" @click="metode = '{{ $label }}'"
                                            :class="metode === '{{ $label }}'
                                                        ? 'bg-stone-800 text-white shadow-xl shadow-stone-800/20 ring-2 ring-stone-800 ring-offset-2'
                                                        : 'bg-white text-stone-500 hover:bg-stone-50 border border-stone-200 hover:border-stone-300'"
                                            class="py-3 px-2 rounded-[1.2rem] text-xs md:text-sm font-bold transition-all flex flex-col items-center gap-2 md:flex-row md:justify-center relative overflow-hidden group active:scale-95">
                                            <span class="material-symbols-rounded text-lg"
                                                :class="metode === '{{ $label }}' ? 'text-rose-400' : 'text-stone-300 group-hover:text-stone-500'">{{ $icon }}</span>
                                            <span>{{ $label }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="space-y-2">
                                <div class="flex justify-between items-center ml-3">
                                    <label
                                        class="text-[10px] font-bold text-stone-400 uppercase tracking-widest">Keterangan
                                        Detail</label>
                                    <span
                                        class="text-[10px] bg-stone-100 text-stone-400 px-2 py-0.5 rounded-full font-bold">Opsional</span>
                                </div>
                                <textarea name="deskripsi" rows="3"
                                    class="w-full bg-stone-50 border-transparent focus:border-rose-500/50 rounded-[1.5rem] text-stone-700 font-medium focus:bg-white focus:ring-4 focus:ring-rose-500/10 transition-all py-3.5 px-5 text-sm leading-relaxed placeholder:text-stone-300 resize-none shadow-inner"
                                    placeholder="Rincian barang yang dibeli atau tujuan pengeluaran..."></textarea>
                            </div>

                            {{-- Upload Bukti --}}
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-stone-400 uppercase tracking-widest ml-3">
                                    Bukti Foto / Struk @if(Auth::user()->role !== 'admin') <span
                                    class="text-rose-500">*</span> @endif
                                </label>
                                <div class="relative group cursor-pointer">
                                    <input type="file" name="bukti_pembayaran" id="file-upload"
                                        class="absolute inset-0 w-full h-full opacity-0 z-20 cursor-pointer"
                                        @change="fileChosen">

                                    <div class="bg-stone-50 border-2 border-dashed border-stone-200 rounded-[1.5rem] p-4 flex items-center gap-4 transition-all group-hover:border-rose-400 group-hover:bg-rose-50/30"
                                        :class="imageUrl ? 'bg-white border-solid border-stone-200' : ''">

                                        {{-- Placeholder --}}
                                        <div class="flex items-center gap-4 w-full" x-show="!imageUrl">
                                            <div
                                                class="w-12 h-12 rounded-2xl bg-white border border-stone-200 flex items-center justify-center text-stone-300 group-hover:text-rose-500 group-hover:border-rose-200 transition-all shadow-sm">
                                                <span class="material-symbols-rounded text-2xl">add_a_photo</span>
                                            </div>
                                            <div>
                                                <p
                                                    class="text-sm font-bold text-stone-600 group-hover:text-rose-600 transition-colors">
                                                    Upload Bukti</p>
                                                <p class="text-[10px] text-stone-400">JPG, PNG, PDF (Max 2MB)</p>
                                            </div>
                                        </div>

                                        {{-- Preview --}}
                                        <div class="flex items-center gap-4 w-full" x-show="imageUrl"
                                            style="display: none;">
                                            <div class="relative w-12 h-12 shrink-0">
                                                <img :src="imageUrl"
                                                    class="w-full h-full rounded-2xl object-cover shadow-sm border border-stone-200">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-bold text-stone-700 truncate" x-text="fileName">
                                                </p>
                                                <p class="text-[10px] text-rose-500 font-bold">Klik untuk ganti</p>
                                            </div>
                                            <button type="button" @click.prevent="removeImage"
                                                class="z-30 p-2 rounded-full bg-white border border-stone-200 text-stone-400 hover:text-rose-500 hover:border-rose-200 transition-all shadow-sm">
                                                <span class="material-symbols-rounded text-lg block">delete</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN (SUMMARY STICKY) --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-[110px] space-y-4">

                        {{-- Total Card (Gradient Rose/Red) --}}
                        <div
                            class="bg-gradient-to-br from-rose-500 to-red-700 rounded-[2.5rem] p-6 text-white shadow-2xl shadow-rose-900/20 relative overflow-hidden border border-rose-400/20 group">
                            {{-- Background Effects --}}
                            <div
                                class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-rose-300/20 rounded-full blur-[50px] group-hover:bg-rose-300/30 transition-all duration-700">
                            </div>
                            <div
                                class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-red-900/20 rounded-full blur-[40px]">
                            </div>
                            <div
                                class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] mix-blend-overlay">
                            </div>

                            <div class="relative z-10 flex flex-col h-full justify-between min-h-[160px]">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span
                                            class="bg-white/20 backdrop-blur-md border border-white/20 text-white px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider shadow-sm">
                                            Estimasi Keluar
                                        </span>
                                    </div>

                                    <div class="mt-3 flex flex-col xl:flex-row xl:items-start gap-1">
                                        <span class="text-rose-200 text-lg font-bold mt-1 mr-1">Rp</span>
                                        <h2 class="font-black leading-none break-all tracking-tight"
                                            :class="totalLengthClass" x-text="displayNominal || '0'">0</h2>
                                    </div>
                                </div>

                                <div class="mt-6 pt-4 border-t border-white/10 flex justify-between items-center">
                                    <div class="flex flex-col max-w-[75%]">
                                        <span
                                            class="text-[10px] text-rose-100/70 font-bold uppercase tracking-wide">Metode
                                            Bayar</span>
                                        <span class="text-xs font-mono text-white truncate font-medium mt-0.5"
                                            x-text="metode"></span>
                                    </div>
                                    <div
                                        class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/20 shrink-0 shadow-inner">
                                        <span class="material-symbols-rounded text-white text-lg">payments</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                class="w-full group relative px-6 py-4 bg-stone-800 hover:bg-rose-600 text-white rounded-[1.5rem] flex items-center justify-center gap-3 transition-all duration-300 shadow-xl shadow-stone-200 hover:shadow-rose-500/30 hover:-translate-y-1 overflow-hidden">
                                <div
                                    class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]">
                                </div>
                                <span
                                    class="material-symbols-rounded bg-white/20 rounded-full p-0.5 group-hover:rotate-12 transition-transform">save</span>
                                <span class="font-bold tracking-wide">Simpan Data</span>
                            </button>

                            <a href="{{ route('kas-keluar.index') }}"
                                class="w-full bg-white hover:bg-rose-50 text-stone-500 hover:text-rose-600 font-bold py-4 rounded-[1.5rem] border border-stone-200 hover:border-rose-200 transition-all flex items-center justify-center gap-2 active:scale-95">
                                Batal
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>
</x-app-layout>