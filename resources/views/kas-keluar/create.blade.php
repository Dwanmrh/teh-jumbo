<x-app-layout>
    <div class="py-8 bg-[#f7f7f7] min-h-screen font-[Outfit]">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded-xl shadow-md">

            <div class="flex items-center mb-6">
                <button type="button" onclick="window.history.back()"
                        class="text-base font-normal text-gray-700 hover:text-gray-900">
                    ←
                </button>

                <h2 class="flex-1 text-center text-2xl font-semibold text-[#2F362C]">
                    Catat Kas Keluar
                </h2>
                <div class="w-6"></div> </div>


            <form action="{{ route('kas-keluar.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1" for="tanggal">Tanggal Transaksi</label>
                    <input type="date" name="tanggal" id="tanggal" class="w-full border border-gray-300 rounded-lg px-3 py-2"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1" for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="" disabled selected>Pilih kategori</option>
                        <option value="Pembelian">Pembelian</option>
                        <option value="Gaji">Gaji</option>
                        <option value="Operasional">Operasional</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1" for="penerima">Penerima</label>
                    <input type="text" name="penerima" id="penerima" class="w-full border border-gray-300 rounded-lg px-3 py-2"
                           placeholder="Nama atau institusi penerima" required>
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                    <div class="flex gap-2">
                        <button type="button" class="metode-btn flex-1 py-2 rounded-lg border border-gray-300 bg-gray-100 text-gray-700 font-medium" data-value="cash">Tunai</button>
                        <button type="button" class="metode-btn flex-1 py-2 rounded-lg border border-gray-300 bg-gray-100 text-gray-700 font-medium" data-value="debit">Qris</button>
                        <button type="button" class="metode-btn flex-1 py-2 rounded-lg border border-gray-300 bg-gray-100 text-gray-700 font-medium" data-value="transfer">Transfer</button>
                    </div>
                    <input type="hidden" name="metode_pembayaran" id="metode_pembayaran" required>
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1" for="nominal">Nominal Pengeluaran</label>
                    <input type="number" name="nominal" id="nominal" class="w-full border border-gray-300 rounded-lg px-3 py-2"
                           placeholder="Masukkan jumlah pengeluaran" min="1" required>
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1" for="bukti_pembayaran">Bukti Pembayaran (Opsional)</label>
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="w-full border border-gray-300 rounded-lg px-3 py-2
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-[#F5C04C] file:text-white
                        hover:file:bg-[#E0AC3B]">
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1" for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2"
                              placeholder="Detail transaksi pengeluaran (Masukkan Deskripsi)"></textarea>
                </div>

                <button type="submit"
                        class="w-full bg-[#F5C04C] hover:bg-[#E0AC3B] text-white px-4 py-3 rounded-md font-medium">
                    Simpan
                </button>
            </form>

        </div>
    </div>

    <script>
        // Metode Pembayaran toggle
        const metodeBtns = document.querySelectorAll('.metode-btn');
        const metodeInput = document.getElementById('metode_pembayaran');
        metodeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Hapus styling aktif dari semua tombol
                metodeBtns.forEach(b => b.classList.remove('bg-[#7C5A5A]', 'text-white'));
                metodeBtns.forEach(b => b.classList.add('bg-gray-100', 'text-gray-700'));

                // Tambahkan styling aktif ke tombol yang diklik
                btn.classList.remove('bg-gray-100','text-gray-700');
                btn.classList.add('bg-[#7C5A5A]', 'text-white');

                // Update nilai hidden input
                metodeInput.value = btn.dataset.value;
            });
        });
    </script>
</x-app-layout>