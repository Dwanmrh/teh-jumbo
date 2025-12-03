<x-app-layout>
    {{-- Libraries --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="min-h-screen bg-stone-50/50 pb-20 font-sans" x-data="imagePreview()">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-10">

            {{-- HEADER: Tombol Kembali & Judul --}}
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('kas-keluar.index') }}"
                   class="group w-12 h-12 rounded-[1rem] bg-white border border-stone-200 shadow-sm flex items-center justify-center text-stone-600 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all active:scale-95">
                    <span class="material-symbols-rounded group-hover:-translate-x-1 transition-transform">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-stone-800 tracking-tight">Catat Pengeluaran</h1>
                    <p class="text-stone-500 text-sm mt-1">Isi formulir di bawah untuk mencatat arus kas keluar baru.</p>
                </div>
            </div>

            {{-- FORM CARD --}}
            <form method="POST" action="{{ route('kas-keluar.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-stone-200/50 border border-stone-100 overflow-hidden relative">

                    {{-- Decorative Top Line --}}
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-rose-400 via-rose-500 to-rose-600"></div>

                    <div class="p-6 md:p-10 space-y-8">

                        {{-- SECTION 1: NOMINAL (Hero Input) --}}
                        <div class="relative">
                            <label class="block text-xs font-bold text-rose-600 uppercase tracking-widest mb-3 ml-1">Nominal Pengeluaran</label>
                            <div class="relative group">
                                <span class="absolute left-4 md:left-6 top-1/2 -translate-y-1/2 text-stone-300 text-2xl md:text-3xl font-bold group-focus-within:text-rose-500 transition-colors">Rp</span>
                                <input type="number" name="nominal" required min="1" placeholder="0" autofocus
                                    class="w-full bg-stone-50/50 border-2 border-stone-100 focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 rounded-[1.5rem] py-4 md:py-6 pl-14 md:pl-20 pr-6 text-3xl md:text-4xl font-black text-stone-800 placeholder-stone-300 transition-all outline-none">
                            </div>
                            @error('nominal') <p class="text-rose-500 text-xs mt-2 ml-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <hr class="border-stone-100">

                        {{-- SECTION 2: DETAIL TRANSAKSI --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">

                            {{-- Tanggal --}}
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-stone-500 uppercase tracking-wide ml-1">Tanggal Transaksi</label>
                                <div class="relative">
                                    <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}"
                                        class="w-full bg-white border border-stone-200 rounded-2xl px-4 py-3.5 text-stone-700 font-bold text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all shadow-sm">
                                </div>
                            </div>

                            {{-- Kategori --}}
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-stone-500 uppercase tracking-wide ml-1">Kategori</label>
                                <div class="relative">
                                    <select name="kategori" class="w-full bg-white border border-stone-200 rounded-2xl px-4 py-3.5 text-stone-700 font-bold text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all shadow-sm appearance-none cursor-pointer">
                                        <option value="Pembelian">Pembelian (Stok/Aset)</option>
                                        <option value="Operasional">Operasional (Listrik/Air)</option>
                                        <option value="Gaji">Gaji Karyawan</option>
                                        <option value="Lain-lain">Lain-lain</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-stone-400 bg-white pl-2">
                                        <span class="material-symbols-rounded">expand_more</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Penerima --}}
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-xs font-bold text-stone-500 uppercase tracking-wide ml-1">Dibayarkan Kepada</label>
                                <input type="text" name="penerima" placeholder="Nama Toko / Orang / Instansi" required
                                    class="w-full bg-white border border-stone-200 rounded-2xl px-5 py-3.5 text-stone-700 font-bold text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all shadow-sm placeholder:font-normal placeholder:text-stone-400">
                            </div>

                            {{-- Metode Pembayaran --}}
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-stone-500 uppercase tracking-wide ml-1">Metode Pembayaran</label>
                                <div class="relative">
                                    <select name="metode_pembayaran" class="w-full bg-white border border-stone-200 rounded-2xl px-4 py-3.5 text-stone-700 font-bold text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all shadow-sm appearance-none cursor-pointer">
                                        <option value="Tunai">Tunai / Cash</option>
                                        <option value="Transfer">Transfer Bank</option>
                                        <option value="QRIS">QRIS / E-Wallet</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-stone-400 bg-white pl-2">
                                        <span class="material-symbols-rounded">credit_card</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-xs font-bold text-stone-500 uppercase tracking-wide ml-1">Catatan / Deskripsi</label>
                                <textarea name="deskripsi" rows="3" placeholder="Keterangan tambahan untuk transaksi ini..."
                                    class="w-full bg-white border border-stone-200 rounded-2xl px-5 py-3.5 text-stone-700 text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all shadow-sm placeholder:text-stone-400 resize-none"></textarea>
                            </div>

                            {{-- Upload Bukti (Alpine JS Preview) --}}
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-xs font-bold text-stone-500 uppercase tracking-wide ml-1">Bukti Foto (Opsional)</label>

                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-stone-300 border-dashed rounded-[1.5rem] hover:bg-stone-50 hover:border-rose-300 transition-all group cursor-pointer relative bg-stone-50/30">

                                    {{-- Input File Hidden --}}
                                    <input type="file" name="bukti_pembayaran" id="file-upload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                           @change="fileChosen">

                                    <div class="space-y-1 text-center" x-show="!imageUrl">
                                        <div class="mx-auto h-12 w-12 text-stone-300 group-hover:text-rose-500 transition-colors">
                                            <span class="material-symbols-rounded text-5xl">add_a_photo</span>
                                        </div>
                                        <div class="flex text-sm text-stone-600 justify-center">
                                            <span class="relative cursor-pointer rounded-md font-bold text-rose-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-rose-500 focus-within:ring-offset-2">
                                                Upload file
                                            </span>
                                            <p class="pl-1">atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-stone-400">PNG, JPG, GIF up to 5MB</p>
                                    </div>

                                    {{-- Image Preview Container --}}
                                    <div x-show="imageUrl" class="relative w-full" style="display: none;">
                                        <img :src="imageUrl" class="max-h-64 rounded-xl mx-auto shadow-md object-contain bg-white">
                                        <button type="button" @click="removeImage" class="absolute top-2 right-2 bg-rose-600 text-white p-1.5 rounded-full shadow-lg hover:bg-rose-700 transition-colors z-20">
                                            <span class="material-symbols-rounded text-sm">close</span>
                                        </button>
                                        <p class="text-center text-xs text-stone-500 mt-2 font-medium" x-text="fileName"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Footer Actions --}}
                    <div class="bg-stone-50 p-6 md:px-10 md:py-8 border-t border-stone-100 flex flex-col-reverse md:flex-row justify-end gap-3 md:gap-4">
                        <a href="{{ route('kas-keluar.index') }}"
                           class="px-6 py-4 rounded-2xl border border-stone-200 text-stone-600 font-bold text-sm text-center hover:bg-white hover:text-stone-800 hover:shadow-md transition-all">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-8 py-4 rounded-2xl bg-gradient-to-r from-rose-600 to-rose-700 text-white font-bold text-sm shadow-lg shadow-rose-500/30 hover:shadow-rose-500/50 hover:to-rose-800 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-rounded">save</span>
                            Simpan Data
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script>
        function imagePreview() {
            return {
                imageUrl: null,
                fileName: null,
                fileChosen(event) {
                    this.fileToDataUrl(event, (src) => this.imageUrl = src);
                    this.fileName = event.target.files[0] ? event.target.files[0].name : null;
                },
                fileToDataUrl(event, callback) {
                    if (!event.target.files.length) return;
                    let file = event.target.files[0],
                        reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = (e) => callback(e.target.result);
                },
                removeImage() {
                    this.imageUrl = null;
                    this.fileName = null;
                    document.getElementById('file-upload').value = '';
                }
            }
        }
    </script>
</x-app-layout>
