<x-app-layout>
    {{-- Libraries --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="min-h-screen bg-stone-50/50 pb-24 font-sans" x-data="{
             nominal: '',
             displayNominal: '',
             metode: 'Tunai',
             // Logic Format Rupiah
             formatRupiah(value) {
                 if(!value) return '0';
                 return new Intl.NumberFormat('id-ID').format(value);
             },
             updateNominal(e) {
                 let raw = e.target.value.replace(/\D/g, '');
                 this.nominal = raw;
                 this.displayNominal = raw ? this.formatRupiah(raw) : '';
             },
             // Logic Image Preview
             imageUrl: null,
             fileName: null,
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
             }
         }">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8">

            {{-- 1. HEADER --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <a href="{{ route('kas-keluar.index') }}"
                            class="w-8 h-8 flex items-center justify-center bg-white rounded-full border border-stone-200 text-stone-400 hover:text-rose-600 hover:border-rose-200 transition-colors shadow-sm">
                            <span class="material-symbols-rounded text-lg">arrow_back</span>
                        </a>
                        <span
                            class="text-xs font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded-md border border-rose-100 uppercase tracking-wider">Pengeluaran
                            Baru</span>
                    </div>
                    <h1 class="text-2xl md:text-4xl font-black text-stone-800 tracking-tight">Catat Pengeluaran</h1>
                    <p class="text-stone-500 text-sm mt-1 max-w-lg leading-relaxed">Catat biaya operasional, belanja
                        stok, gaji, atau pengeluaran lainnya.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('kas-keluar.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                    {{-- 2. KOLOM KIRI (Input Utama) --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- Card Input Nominal --}}
                        <div
                            class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-stone-200 relative overflow-hidden group">
                            {{-- Decorative Background (Rose) --}}
                            <div
                                class="absolute top-0 right-0 w-32 h-32 bg-rose-50 rounded-bl-[4rem] -mr-4 -mt-4 transition-all group-hover:bg-rose-100/50">
                            </div>

                            <div class="relative z-10">
                                <h3 class="font-bold text-stone-700 text-lg mb-6 flex items-center gap-2">
                                    <span class="material-symbols-rounded text-rose-500">money_off</span> Nominal Keluar
                                </h3>

                                <div class="relative">
                                    <span
                                        class="absolute left-4 md:left-6 top-1/2 -translate-y-1/2 text-stone-400 text-2xl md:text-3xl font-bold group-focus-within:text-rose-500 transition-colors">Rp</span>

                                    {{-- Input Visual --}}
                                    <input type="text" x-model="displayNominal" @input="updateNominal" placeholder="0"
                                        required
                                        class="w-full bg-stone-50 border-2 border-stone-100 focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 rounded-[1.5rem] py-4 md:py-6 pl-14 md:pl-20 pr-6 text-3xl md:text-4xl font-black text-stone-800 placeholder-stone-300 transition-all outline-none">

                                    {{-- Input Hidden --}}
                                    <input type="hidden" name="nominal" :value="nominal">
                                </div>
                                @error('nominal') <p class="text-rose-500 text-xs mt-2 ml-1 font-bold">{{ $message }}
                                </p> @enderror
                            </div>
                        </div>

                        {{-- Card Detail Transaksi --}}
                        <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-stone-200">
                            <h3 class="font-bold text-stone-700 text-lg mb-6 flex items-center gap-2">
                                <span class="material-symbols-rounded text-stone-400">receipt_long</span> Detail
                                Transaksi
                            </h3>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    {{-- Tanggal --}}
                                    <div class="space-y-2">
                                        <label
                                            class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">Tanggal</label>
                                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                                            class="w-full bg-stone-50 border-stone-200 rounded-2xl text-stone-700 font-bold focus:ring-rose-500/20 focus:border-rose-500 transition-all py-3 px-4 text-sm">
                                    </div>

                                    {{-- COMBOBOX KATEGORI (Modern) --}}
                                    <div class="space-y-2" x-data="{
                                             open: false,
                                             search: 'Belanja Stok',
                                             options: ['Belanja Stok', 'Operasional Toko', 'Gaji Karyawan', 'Sewa Tempat', 'Perbaikan & Maintenance', 'Lain-lain'],
                                             get filteredOptions() {
                                                 if (this.search === '') return this.options;
                                                 return this.options.filter(option => option.toLowerCase().includes(this.search.toLowerCase()));
                                             },
                                             select(val) {
                                                 this.search = val;
                                                 this.open = false;
                                             }
                                         }">
                                        <label
                                            class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">Kategori
                                            (Pilih / Ketik)</label>
                                        <div class="relative" @click.outside="open = false">
                                            <input type="text" name="kategori" x-model="search" @focus="open = true"
                                                @input="open = true"
                                                class="w-full bg-stone-50 border-stone-200 rounded-2xl text-stone-700 font-bold focus:ring-rose-500/20 focus:border-rose-500 transition-all py-3 pl-4 pr-10 text-sm"
                                                placeholder="Cari kategori..." autocomplete="off">

                                            <span
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none transition-transform duration-300"
                                                :class="open ? 'rotate-180' : ''">
                                                <span class="material-symbols-rounded">expand_more</span>
                                            </span>

                                            <div x-show="open" x-transition.opacity.duration.200ms
                                                class="absolute z-50 mt-1 w-full bg-white rounded-2xl shadow-xl border border-stone-100 max-h-60 overflow-y-auto custom-scrollbar p-1.5">
                                                <template x-for="option in filteredOptions" :key="option">
                                                    <button type="button" @click="select(option)"
                                                        class="w-full text-left px-3 py-2.5 rounded-xl text-sm font-medium text-stone-600 hover:bg-rose-50 hover:text-rose-700 transition-colors flex items-center justify-between group">
                                                        <span x-text="option"></span>
                                                        <span
                                                            class="material-symbols-rounded text-rose-500 opacity-0 group-hover:opacity-100 text-lg">check</span>
                                                    </button>
                                                </template>
                                                {{-- Opsi Baru --}}
                                                <div x-show="filteredOptions.length === 0 && search.length > 0"
                                                    class="px-3 py-2.5 text-sm text-stone-400 italic flex items-center gap-2">
                                                    <span
                                                        class="material-symbols-rounded text-rose-500">add_circle</span>
                                                    <span>Buat kategori baru: "<span x-text="search"
                                                            class="font-bold text-stone-600"></span>"</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- END COMBOBOX --}}
                                </div>

                                {{-- Dibayarkan Kepada --}}
                                <div class="space-y-2">
                                    <label
                                        class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">Dibayarkan
                                        Kepada</label>
                                    <input type="text" name="penerima"
                                        placeholder="Contoh: Toko Plastik Jaya, PLN, Budi..." required
                                        class="w-full bg-stone-50 border-stone-200 rounded-2xl px-5 py-3.5 text-stone-700 font-bold text-sm focus:ring-rose-500/20 focus:border-rose-500 transition-all placeholder:font-normal placeholder:text-stone-400">
                                </div>

                                {{-- Metode Pembayaran --}}
                                <div class="space-y-2">
                                    <label
                                        class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">Sumber
                                        Dana</label>
                                    <input type="hidden" name="metode_pembayaran" x-model="metode">
                                    <div class="grid grid-cols-3 gap-3">
                                        <template x-for="m in ['Tunai', 'Transfer', 'QRIS']">
                                            <button type="button" @click="metode = m"
                                                :class="metode === m ? 'bg-rose-600 text-white shadow-lg shadow-rose-500/30 ring-2 ring-rose-600 ring-offset-2' : 'bg-stone-50 text-stone-500 hover:bg-stone-100 border border-stone-200'"
                                                class="py-3 px-2 rounded-xl text-xs md:text-sm font-bold transition-all flex flex-col items-center gap-1.5 md:flex-row md:justify-center">
                                                <span class="material-symbols-rounded text-lg"
                                                    x-text="m === 'Tunai' ? 'wallet' : (m === 'Transfer' ? 'account_balance' : 'qr_code')"></span>
                                                <span x-text="m"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                {{-- Deskripsi --}}
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <label
                                            class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">Keterangan
                                            Detail</label>
                                    </div>
                                    <textarea name="deskripsi" rows="2"
                                        class="w-full bg-stone-50 border-stone-200 rounded-2xl text-stone-700 font-medium focus:ring-rose-500/20 focus:border-rose-500 transition-all py-3 px-4 text-sm leading-relaxed placeholder:text-stone-300"
                                        placeholder="Rincian barang yang dibeli atau tujuan pengeluaran..."></textarea>
                                </div>

                                {{-- Upload Bukti (Modern Style) --}}
                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-stone-400 uppercase tracking-wider ml-1">
                                        Bukti Foto / Struk
                                        @if(Auth::user()->role === 'kasir')
                                            <span class="text-rose-500">*</span>
                                        @endif
                                    </label>
                                    <div class="relative group cursor-pointer">
                                        <input type="file" name="bukti_pembayaran" id="file-upload"
                                            class="absolute inset-0 w-full h-full opacity-0 z-20 cursor-pointer"
                                            @change="fileChosen">

                                        <div class="border-2 border-dashed border-stone-300 rounded-2xl p-4 flex items-center gap-4 transition-all group-hover:border-rose-400 group-hover:bg-rose-50/50"
                                            :class="imageUrl ? 'bg-stone-50' : 'bg-white'">

                                            {{-- Placeholder State --}}
                                            <div class="flex items-center gap-4 w-full" x-show="!imageUrl">
                                                <div
                                                    class="w-12 h-12 rounded-xl bg-stone-100 flex items-center justify-center text-stone-400 group-hover:text-rose-500 group-hover:bg-white transition-colors">
                                                    <span class="material-symbols-rounded text-2xl">add_a_photo</span>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-sm font-bold text-stone-600 group-hover:text-rose-600">
                                                        Upload Bukti</p>
                                                    <p class="text-[10px] text-stone-400">JPG, PNG, PDF (Max 2MB)</p>
                                                </div>
                                            </div>

                                            {{-- Preview State --}}
                                            <div class="flex items-center gap-4 w-full" x-show="imageUrl"
                                                style="display: none;">
                                                <img :src="imageUrl"
                                                    class="w-12 h-12 rounded-xl object-cover shadow-sm border border-stone-200">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-bold text-stone-700 truncate"
                                                        x-text="fileName"></p>
                                                    <p class="text-[10px] text-rose-500 font-bold">Klik untuk ganti</p>
                                                </div>
                                                <button type="button" @click.prevent="removeImage"
                                                    class="z-30 p-2 text-stone-400 hover:text-rose-500 transition-colors">
                                                    <span class="material-symbols-rounded">delete</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- 3. KOLOM KANAN (Summary Live) --}}
                    <div class="lg:col-span-1">
                        <div class="sticky top-6 space-y-6">

                            {{-- Card Total --}}
                            <div
                                class="bg-gradient-to-br from-rose-500 to-red-700 rounded-[2rem] p-6 text-white shadow-xl shadow-rose-500/20 relative overflow-hidden">
                                {{-- Background Patterns --}}
                                <div
                                    class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10">
                                </div>
                                <div
                                    class="absolute bottom-0 left-0 w-32 h-32 bg-black/10 rounded-full blur-2xl -ml-6 -mb-6">
                                </div>

                                <div class="relative z-10">
                                    <span class="text-rose-100 text-xs font-bold uppercase tracking-widest">Total
                                        Pengeluaran</span>
                                    <div class="mt-2 mb-2 flex flex-col sm:flex-row sm:items-start gap-1">
                                        <span class="text-rose-200 text-xl font-medium mt-1 mr-1">Rp</span>

                                        {{-- DYNAMIC FONT SIZE: Agar nominal besar tidak terpotong --}}
                                        <h2 class="font-black tracking-tight leading-none transition-all duration-300 break-all"
                                            :class="displayNominal.length > 10 ? 'text-2xl md:text-3xl' : 'text-3xl md:text-5xl'"
                                            x-text="displayNominal || '0'">
                                            0
                                        </h2>
                                    </div>

                                    <div class="h-px bg-white/20 my-4"></div>

                                    <div class="space-y-1">
                                        <div class="flex justify-between items-center text-sm text-rose-50">
                                            <span class="opacity-80">Sumber</span>
                                            <span class="font-bold" x-text="metode"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="grid grid-cols-1 gap-3">
                                <button type="submit" id="saveBtn"
                                    class="w-full bg-stone-800 hover:bg-stone-900 text-white font-bold py-4 rounded-2xl shadow-lg shadow-stone-800/20 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 group">
                                    <span
                                        class="material-symbols-rounded group-hover:rotate-12 transition-transform">save</span>
                                    Simpan Data
                                </button>

                                <a href="{{ route('kas-keluar.index') }}"
                                    class="w-full bg-white hover:bg-stone-50 text-stone-500 font-bold py-4 rounded-2xl border border-stone-200 transition-all flex items-center justify-center gap-2">
                                    Batal
                                </a>
                            </div>

                            {{-- Helper Info --}}
                            <div class="bg-rose-50 rounded-2xl p-4 border border-rose-100 flex gap-3">
                                <span class="material-symbols-rounded text-rose-400 text-xl shrink-0">info</span>
                                <p class="text-xs text-rose-600/80 leading-relaxed font-medium">
                                    @if(Auth::user()->role === 'kasir')
                                        <strong>Wajib:</strong> Upload bukti pembayaran untuk setiap transaksi.
                                    @else
                                        Pastikan bukti pembayaran jelas jika nominal pengeluaran di atas Rp 100.000.
                                    @endif
                                </p>
                            </div>

                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            const roleUser = "{{ Auth::user()->role }}";
            const fileUpload = document.getElementById('file-upload').files.length;

            if (roleUser === 'kasir' && fileUpload === 0) {
                e.preventDefault(); // Prevent submission
                Swal.fire({
                    icon: 'warning',
                    title: 'Bukti pembayarannya belum diupload',
                    text: 'Kasir wajib mengupload bukti pembayaran.',
                    confirmButtonColor: '#e11d48',
                    confirmButtonText: 'Mengerti'
                });
            }
        });
    </script>

</x-app-layout>