<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Data Kas
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <form action="{{ route('kas-masuk.update', $kas->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- 🔹 Tanggal Transaksi --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Tanggal Transaksi</label>
                        <input type="date" name="tanggal_transaksi"
                            value="{{ old('tanggal_transaksi', $kas->tanggal_transaksi) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm" required>
                    </div>

                    {{-- 🔹 Jenis (readonly) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Jenis</label>
                        <input type="text" name="jenis" value="{{ $kas->jenis }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100 cursor-not-allowed" readonly>
                    </div>

                    {{-- 🔹 Metode Pembayaran --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Metode Pembayaran</label>
                        <select name="metode_pembayaran"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-[#7AC943]" required>
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            <option value="cash" {{ old('metode_pembayaran', $kas->metode_pembayaran) == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="qris" {{ old('metode_pembayaran', $kas->metode_pembayaran) == 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="transfer" {{ old('metode_pembayaran', $kas->metode_pembayaran) == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        </select>
                    </div>


                    {{-- 🔹 Jumlah --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Jumlah</label>
                        <input type="number" id="jumlah" name="jumlah"
                            value="{{ old('jumlah', $kas->jumlah) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm" min="1" required>
                    </div>

                    {{-- 🔹 Harga Satuan --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Harga Satuan</label>
                        <input type="number" id="harga_satuan" name="harga_satuan" step="0.01"
                            value="{{ old('harga_satuan', $kas->harga_satuan) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm" min="0" required>
                    </div>

                    {{-- 🔹 Total (otomatis dihitung) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Total</label>
                        <input type="number" id="total" name="total" step="0.01"
                            value="{{ old('total', $kas->total) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100 cursor-not-allowed" readonly>
                    </div>

                    {{-- 🔹 Keterangan --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3"
                            class="w-full border-gray-300 rounded-lg shadow-sm">{{ old('keterangan', $kas->keterangan) }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('kas-masuk.index') }}"
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 mr-2">Batal</a>
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 🔹 Script untuk menghitung total otomatis --}}
    <script>
        const jumlahInput = document.getElementById('jumlah');
        const hargaInput = document.getElementById('harga_satuan');
        const totalInput = document.getElementById('total');

        function hitungTotal() {
            const jumlah = parseFloat(jumlahInput.value) || 0;
            const harga = parseFloat(hargaInput.value) || 0;
            totalInput.value = (jumlah * harga).toFixed(2);
        }

        jumlahInput.addEventListener('input', hitungTotal);
        hargaInput.addEventListener('input', hitungTotal);
    </script>
</x-app-layout>
