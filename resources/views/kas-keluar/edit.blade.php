<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Kas Keluar
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">

                <form action="{{ route('kas-keluar.update', $kasKeluar->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- 🔹 Tanggal Transaksi --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Tanggal Transaksi</label>
                        <input type="date" name="tanggal"
                            value="{{ old('tanggal', $kasKeluar->tanggal) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm" required>
                    </div>

                    {{-- 🔹 Kategori --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Kategori</label>
                        <select name="kategori" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="belanja_bahan_baku" {{ $kasKeluar->kategori == 'belanja_bahan_baku' ? 'selected' : '' }}>Bahan Baku</option>
                            <option value="biaya_listrik" {{ $kasKeluar->kategori == 'biaya_listrik' ? 'selected' : '' }}>Biaya Listrik</option>
                            <option value="gaji_karyawan" {{ $kasKeluar->kategori == 'gaji_karyawan' ? 'selected' : '' }}>Gaji</option>
                            <option value="operasional_lainnya" {{ $kasKeluar->kategori == 'operasional_lainnya' ? 'selected' : '' }}>Operasional Lainnya</option>
                            <option value="lain_lain" {{ $kasKeluar->kategori == 'lain_lain' ? 'selected' : '' }}>Lain-lain</option>
                        </select>
                    </div>

                    {{-- 🔹 Metode Pembayaran --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Metode Pembayaran</label>
                        <select name="metode_pembayaran"
                            class="w-full border-gray-300 rounded-lg shadow-sm" required>
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            <option value="cash" {{ $kasKeluar->metode_pembayaran == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="debit" {{ $kasKeluar->metode_pembayaran == 'debit' ? 'selected' : '' }}>Debit</option>
                            <option value="transfer" {{ $kasKeluar->metode_pembayaran == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        </select>
                    </div>

                    {{-- 🔹 Penerima --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Penerima</label>
                        <input type="text" name="penerima"
                            value="{{ old('penerima', $kasKeluar->penerima) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm" required>
                    </div>

                    {{-- 🔹 Nominal --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Nominal</label>
                        <input type="number" name="nominal"
                            value="{{ old('nominal', $kasKeluar->nominal) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm" min="1" required>
                    </div>

                    {{-- 🔹 Bukti Pembayaran (show preview if exists) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Bukti Pembayaran (Opsional)</label>

                        @if ($kasKeluar->bukti_pembayaran)
                            <p class="mb-2 text-sm text-gray-600">Bukti saat ini:</p>
                            <a href="{{ asset('storage/' . $kasKeluar->bukti_pembayaran) }}" target="_blank" class="text-blue-600 underline">
                                Lihat bukti
                            </a>
                        @endif

                        <input type="file" name="bukti_pembayaran"
                            class="w-full border-gray-300 rounded-lg shadow-sm mt-2">
                    </div>

                    {{-- 🔹 Deskripsi --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full border-gray-300 rounded-lg shadow-sm">{{ old('deskripsi', $kasKeluar->deskripsi) }}</textarea>
                    </div>

                    {{-- 🔹 Buttons --}}
                    <div class="flex justify-end">
                        <a href="{{ route('kas-keluar.index') }}"
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 mr-2">Batal</a>
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
