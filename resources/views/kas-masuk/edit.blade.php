<x-app-layout>
    {{-- Library wajib --}}
    <script defer src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="py-8 bg-[#f7f7f7] min-h-screen font-[Outfit]">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="mb-6">
                <h2 class="text-2xl font-medium text-[#2F362C]">Edit Kas Masuk</h2>
                <p class="text-sm text-gray-500 mt-1">Perbarui data transaksi pemasukan</p>
            </div>

            {{-- FORM EDIT --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <form action="{{ route('kas-masuk.update', $kasMasuk->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- 🔹 Tanggal Transaksi --}}
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-[#2F362C]">Tanggal Transaksi</label>
                        <input type="date" name="tanggal_transaksi"
                            value="{{ old('tanggal_transaksi', $kasMasuk->tanggal_transaksi) }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                    </div>

                    {{-- 🔹 Keterangan --}}
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-[#2F362C]">Keterangan</label>
                        <textarea name="keterangan"
                            class="w-full border border-gray-300 rounded-md px-3 py-2">{{ old('keterangan', $kasMasuk->keterangan) }}</textarea>
                    </div>

                    {{-- 🔹 Kategori --}}
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-[#2F362C]">Kategori</label>
                        <select name="kategori"
                            class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                            <option value="Penjualan" {{ old('kategori', $kasMasuk->kategori) == 'Penjualan' ? 'selected' : '' }}>Penjualan</option>
                            <option value="Lain-lain" {{ old('kategori', $kasMasuk->kategori) == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                        </select>
                    </div>

                    {{-- 🔹 Jumlah & Harga Satuan --}}
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-sm font-medium text-[#2F362C]">Jumlah</label>
                            <input type="number" name="jumlah" id="jumlah" min="1"
                                value="{{ old('jumlah', $kasMasuk->jumlah) }}"
                                class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#2F362C]">Harga Satuan</label>
                            <input type="number" name="harga_satuan" id="harga_satuan" min="0"
                                value="{{ old('harga_satuan', $kasMasuk->harga_satuan) }}"
                                class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                        </div>
                    </div>

                    {{-- 🔹 Total Otomatis --}}
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-[#2F362C]">Total (Otomatis)</label>
                        <input type="number" name="total" id="total"
                            value="{{ old('total', $kasMasuk->total) }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                    </div>

                    {{-- 🔹 Metode Pembayaran --}}
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-[#2F362C]">Metode Pembayaran</label>
                        <div class="flex gap-2">
                            <button type="button"
                                class="metode-btn flex-1 py-2 rounded-lg border border-gray-300
                                font-medium transition
                                {{ old('metode_pembayaran', $kasMasuk->metode_pembayaran) == 'Tunai' ? 'bg-[#7AC943] text-white border-[#68AD3A]' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                                data-value="Tunai">Tunai</button>

                            <button type="button"
                                class="metode-btn flex-1 py-2 rounded-lg border border-gray-300
                                font-medium transition
                                {{ old('metode_pembayaran', $kasMasuk->metode_pembayaran) == 'QRIS' ? 'bg-[#7AC943] text-white border-[#68AD3A]' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                                data-value="QRIS">QRIS</button>

                            <button type="button"
                                class="metode-btn flex-1 py-2 rounded-lg border border-gray-300
                                font-medium transition
                                {{ old('metode_pembayaran', $kasMasuk->metode_pembayaran) == 'Transfer' ? 'bg-[#7AC943] text-white border-[#68AD3A]' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                                data-value="Transfer">Transfer</button>
                        </div>
                        <input type="hidden" name="metode_pembayaran" id="metode_pembayaran"
                            value="{{ old('metode_pembayaran', $kasMasuk->metode_pembayaran) }}" required>
                    </div>

                    {{-- 🔹 Tombol Aksi --}}
                    <div class="flex justify-end gap-2 mt-6">
                        <a href="{{ route('kas-masuk.index') }}"
                            class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">Batal</a>
                        <button type="submit"
                            class="bg-[#7AC943] hover:bg-[#68AD3A] text-white px-4 py-2 rounded-md">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT: Hitung Total + Pilih Metode Pembayaran --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const jumlahInput = document.getElementById("jumlah");
            const hargaInput = document.getElementById("harga_satuan");
            const totalInput = document.getElementById("total");
            const metodeBtns = document.querySelectorAll(".metode-btn");
            const metodeInput = document.getElementById("metode_pembayaran");

            // Hitung total otomatis
            const hitungTotal = () => {
                const jumlah = parseInt(jumlahInput.value) || 0;
                const harga = parseFloat(hargaInput.value) || 0;
                totalInput.value = jumlah * harga;
            };
            jumlahInput.addEventListener("input", hitungTotal);
            hargaInput.addEventListener("input", hitungTotal);

            // Pilih metode pembayaran
         metodeBtns.forEach(btn => {
            btn.addEventListener("click", () => {

                metodeBtns.forEach(b => {
                    b.classList.remove("bg-[#7AC943]", "text-white", "border-[#68AD3A]");
                    b.classList.add("bg-gray-100", "text-gray-700", "border-gray-300", "hover:bg-gray-200");
                });

                btn.classList.remove("bg-gray-100", "text-gray-700", "border-gray-300", "hover:bg-gray-200");
                btn.classList.add("bg-[#7AC943]", "text-white", "border-[#68AD3A]");

                metodeInput.value = btn.dataset.value;
            });
        });
        });
    </script>
</x-app-layout>
