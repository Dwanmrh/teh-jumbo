<x-app-layout>
    <div class="py-8 bg-[#f7f7f7] min-h-screen font-[Outfit]">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded-xl shadow-md">

            <!-- Header -->
        <div class="flex items-center mb-6">
            <button type="button" onclick="window.history.back()"
                        class="text-base font-normal text-gray-700 hover:text-gray-900">
                    ←
                </button>
        <h2 class="flex-1 text-center text-2xl font-semibold text-[#2F362C]">
            Catat Kas Masuk
        </h2>
        <div class="w-6"></div> <!-- placeholder supaya judul tetap center -->
    </div>


            <form action="{{ route('kas-masuk.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1">Tanggal Transaksi</label>
                    <input type="date" name="tanggal_transaksi" class="w-full border border-gray-300 rounded-lg px-3 py-2"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1" for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="" disabled selected>Pilih kategori</option>
                        <option value="Penjualan">Penjualan</option>
                        <option value="Lain-lain">Lain-lain</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1">Jumlah</label>
                    <div class="flex items-center gap-2">
                        <button type="button" id="minus" class="px-3 py-1 bg-gray-200 rounded-lg text-lg font-semibold">-</button>
                        <input type="number" name="jumlah" id="jumlah" value="1"
                               class="w-full text-center border border-gray-300 rounded-lg px-3 py-2"
                               min="1" required>
                        <button type="button" id="plus" class="px-3 py-1 bg-gray-200 rounded-lg text-lg font-semibold">+</button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                    <div class="flex gap-2">
                        <button type="button" class="metode-btn flex-1 py-2 rounded-lg border border-gray-300 bg-gray-100 text-gray-700 font-medium" data-value="Tunai">Tunai</button>
                        <button type="button" class="metode-btn flex-1 py-2 rounded-lg border border-gray-300 bg-gray-100 text-gray-700 font-medium" data-value="Qris">QRIS</button>
                        <button type="button" class="metode-btn flex-1 py-2 rounded-lg border border-gray-300 bg-gray-100 text-gray-700 font-medium" data-value="Transfer">Transfer</button>
                    </div>
                    <input type="hidden" name="metode_pembayaran" id="metode_pembayaran" required>
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1">Harga Satuan</label>
                    <input type="number" name="harga_satuan" class="w-full border border-gray-300 rounded-lg px-3 py-2"
                           placeholder="Masukkan harga satuan" min="0" required>
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1">Total Harga</label>
                    <input type="number" name="total" id="total"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100"
                           placeholder="Total akan dihitung otomatis" readonly required>
                </div>

                <!-- Tombol Simpan full-width dan kuning -->
                <button type="submit"
                        class="w-full bg-[#F5C04C] hover:bg-[#E0AC3B] text-white px-4 py-3 rounded-md font-medium">
                    Simpan
                </button>
            </form>

        </div>
    </div>

    <script>
        // Jumlah + / -
        const jumlahInput = document.getElementById('jumlah');
        document.getElementById('minus').addEventListener('click', () => {
            let val = parseInt(jumlahInput.value) || 1;
            if(val > 1) jumlahInput.value = val - 1;
            hitungTotal();
        });
        document.getElementById('plus').addEventListener('click', () => {
            let val = parseInt(jumlahInput.value) || 1;
            jumlahInput.value = val + 1;
            hitungTotal();
        });

        // Metode Pembayaran toggle
        const metodeBtns = document.querySelectorAll('.metode-btn');
        const metodeInput = document.getElementById('metode_pembayaran');
        metodeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                metodeBtns.forEach(b => b.classList.remove('bg-[#7C5A5A]', 'text-white'));
                metodeBtns.forEach(b => b.classList.add('bg-gray-100', 'text-gray-700'));
                btn.classList.remove('bg-gray-100','text-gray-700');
                btn.classList.add('bg-[#7C5A5A]', 'text-white');
                metodeInput.value = btn.dataset.value;
            });
        });

        // Total harga otomatis
        const hargaInput = document.querySelector('input[name="harga_satuan"]');
        const totalInput = document.getElementById('total');

        function hitungTotal() {
            const jumlah = parseInt(jumlahInput.value) || 0;
            const harga = parseFloat(hargaInput.value) || 0;
            totalInput.value = jumlah * harga;
        }

        jumlahInput.addEventListener('input', hitungTotal);
        hargaInput.addEventListener('input', hitungTotal);
    </script>
</x-app-layout>
