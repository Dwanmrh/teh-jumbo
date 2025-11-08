<x-app-layout>
    {{-- Library wajib --}}
    <script defer src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <div class="py-10 bg-[#f7f7f7] min-h-screen font-[Outfit]">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            {{-- HEADER --}}
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-[#2F362C]">Edit Kas Keluar</h2>
                <p class="text-gray-500 text-sm mt-1">Perbarui data transaksi pengeluaran</p>
            </div>

            {{-- CARD FORM --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <form action="{{ route('kas-keluar.update', $kasKeluar->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- TANGGAL TRANSAKSI --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Transaksi</label>
                        <input type="date" name="tanggal"
                            value="{{ old('tanggal', $kasKeluar->tanggal) }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-[#FF5252]" required>
                    </div>

                    {{-- PENERIMA --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penerima</label>
                        <input type="text" name="penerima"
                            value="{{ old('penerima', $kasKeluar->penerima) }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-[#FF5252]" required>
                    </div>

                    {{-- KATEGORI --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="kategori"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-[#FF5252]" required>
                            <option value="" disabled>-- Pilih Kategori --</option>
                            <option value="Pembelian" {{ $kasKeluar->kategori == 'Pembelian' ? 'selected' : '' }}>Pembelian</option>
                            <option value="Gaji" {{ $kasKeluar->kategori == 'Gaji' ? 'selected' : '' }}>Gaji</option>
                            <option value="Operasional" {{ $kasKeluar->kategori == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="Lainnya" {{ $kasKeluar->kategori == 'Lainnya' ? 'selected' : '' }}>Lain-lain</option>
                        </select>
                    </div>

                    {{-- NOMINAL --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nominal</label>
                        <input type="number" name="nominal" min="1"
                            value="{{ old('nominal', $kasKeluar->nominal) }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-[#FF5252]" required>
                    </div>

                    {{-- METODE PEMBAYARAN --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <div class="flex gap-2">
                            @php $metode = $kasKeluar->metode_pembayaran; @endphp
                            <button type="button"
                                class="metode-btn flex-1 py-2 rounded-lg border border-gray-300 font-medium
                                {{ $metode == 'Tunai' ? 'bg-[#FF5252] text-white' : 'bg-gray-100 text-gray-700' }}"
                                data-value="Tunai">Tunai</button>
                            <button type="button"
                                class="metode-btn flex-1 py-2 rounded-lg border border-gray-300 font-medium
                                {{ $metode == 'QRIS' ? 'bg-[#FF5252] text-white' : 'bg-gray-100 text-gray-700' }}"
                                data-value="QRIS">QRIS</button>
                            <button type="button"
                                class="metode-btn flex-1 py-2 rounded-lg border border-gray-300 font-medium
                                {{ $metode == 'Transfer' ? 'bg-[#FF5252] text-white' : 'bg-gray-100 text-gray-700' }}"
                                data-value="Transfer">Transfer</button>
                        </div>
                        <input type="hidden" name="metode_pembayaran" id="metode_pembayaran"
                            value="{{ old('metode_pembayaran', $kasKeluar->metode_pembayaran) }}" required>
                    </div>

                    {{-- DESKRIPSI --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-[#FF5252]"
                            placeholder="Tulis keterangan tambahan (opsional)...">{{ old('deskripsi', $kasKeluar->deskripsi) }}</textarea>
                    </div>

                    {{-- BUKTI PEMBAYARAN --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran (Opsional)</label>

                        @if ($kasKeluar->bukti_pembayaran)
                            <div class="mb-3">
                                <p class="text-sm text-gray-600 mb-1">Bukti saat ini:</p>
                                <img src="{{ asset('storage/' . $kasKeluar->bukti_pembayaran) }}"
                                    alt="Bukti" class="w-32 h-32 object-cover rounded-lg border cursor-pointer"
                                    onclick="window.open('{{ asset('storage/' . $kasKeluar->bukti_pembayaran) }}', '_blank')">
                            </div>
                        @endif

                        <input type="file" name="bukti_pembayaran"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-[#FF5252]">
                    </div>

                    {{-- BUTTONS --}}
                    <div class="flex justify-end gap-2 mt-6">
                        <a href="{{ route('kas-keluar.index') }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">Batal</a>
                        <button type="submit"
                            class="bg-[#FF5252] hover:bg-[#D60000] text-white px-4 py-2 rounded-md">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT METODE PEMBAYARAN --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const metodeBtns = document.querySelectorAll('.metode-btn');
            const metodeInput = document.getElementById('metode_pembayaran');

            metodeBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    metodeBtns.forEach(b => {
                        b.classList.remove('bg-[#FF5252]', 'text-white');
                        b.classList.add('bg-gray-100', 'text-gray-700');
                    });
                    btn.classList.add('bg-[#FF5252]', 'text-white');
                    btn.classList.remove('bg-gray-100', 'text-gray-700');
                    metodeInput.value = btn.dataset.value;
                });
            });
        });
    </script>
</x-app-layout>
