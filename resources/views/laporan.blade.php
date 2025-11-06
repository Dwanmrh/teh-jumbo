<x-app-layout>
    {{-- Font Outfit --}}
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <div class="py-8 bg-[#f7f7f7] font-[Outfit]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Judul --}}
            <h2 class="text-2xl font-semibold text-[#2F362C] mb-6">Laporan Keuangan</h2>

            {{-- Filter --}}
            <div class="bg-white p-4 rounded-md shadow-md flex flex-wrap items-center gap-3 mb-6">
                <label for="filter-type" class="font-medium text-[#2F362C]">Filter</label>
                <select id="filter-type" onchange="toggleFilterInput()" class="bg-[#F5C04C] text-[#2F362C] font-medium px-3 py-2 rounded-md border-none outline-none">
                    <option value="">Pilih Jangka Waktu</option>
                    <option value="harian">Harian</option>
                    <option value="bulanan">Bulanan</option>
                </select>

                <input type="date" id="filter-harian" class="hidden bg-[#F5C04C] px-3 py-2 rounded-md text-[#2F362C]" />
                <input type="month" id="filter-bulanan" class="hidden bg-[#F5C04C] px-3 py-2 rounded-md text-[#2F362C]" />

                <button class="bg-[#7AC943] text-white px-4 py-2 rounded-md hover:bg-[#6AB13B]">Terapkan</button>
            </div>

            {{-- Ringkasan Keuangan --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <div class="bg-white shadow-md rounded-md p-5 border-l-4 border-[#7AC943]">
                    <h3 class="text-sm font-medium text-[#2F362C] mb-1">Total Kas Masuk</h3>
                    <p class="text-lg font-bold text-[#2F362C]">Rp 25.000.000</p>
                </div>
                <div class="bg-white shadow-md rounded-md p-5 border-l-4 border-[#E74C3C]">
                    <h3 class="text-sm font-medium text-[#2F362C] mb-1">Total Kas Keluar</h3>
                    <p class="text-lg font-bold text-[#2F362C]">Rp 12.000.000</p>
                </div>
                <div class="bg-white shadow-md rounded-md p-5 border-l-4 border-[#F5C04C]">
                    <h3 class="text-sm font-medium text-[#2F362C] mb-1">Total Transaksi</h3>
                    <p class="text-lg font-bold text-[#2F362C]">Rp 37.000.000</p>
                </div>
                <div class="bg-white shadow-md rounded-md p-5 border-l-4 border-[#8E44AD]">
                    <h3 class="text-sm font-medium text-[#2F362C] mb-1">Saldo Akhir</h3>
                    <p class="text-lg font-bold text-[#2F362C]">Rp 13.000.000</p>
                </div>
            </div>

            {{-- Tabel Laporan --}}
            <div class="bg-white rounded-md shadow-md p-4 overflow-x-auto">
                <table class="w-full border-collapse text-center text-sm">
                    <thead>
                        <tr class="bg-[#FFF2CF] text-[#2F362C] font-semibold">
                            <th class="border p-2">No</th>
                            <th class="border p-2">Tanggal</th>
                            <th class="border p-2">Keterangan</th>
                            <th class="border p-2">Kas Masuk</th>
                            <th class="border p-2">Kas Keluar</th>
                            <th class="border p-2">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 1; $i <= 5; $i++)
                            <tr class="even:bg-gray-50">
                                <td class="border p-2">{{ $i }}</td>
                                <td class="border p-2">2025-10-{{ 10 + $i }}</td>
                                <td class="border p-2">Penjualan Hari ke-{{ $i }}</td>
                                <td class="border p-2 text-green-600">Rp. {{ number_format(5000000 + $i * 100000, 0, ',', '.') }}</td>
                                <td class="border p-2 text-red-600">Rp. {{ number_format(2000000 + $i * 50000, 0, ',', '.') }}</td>
                                <td class="border p-2 font-medium text-[#2F362C]">Rp. {{ number_format(3000000 + $i * 50000, 0, ',', '.') }}</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            {{-- Tombol Export --}}
            <div class="mt-6 flex justify-end gap-3">
                <button class="bg-[#F5C04C] text-[#2F362C] px-4 py-2 rounded-md font-medium hover:bg-[#E0AC3B]">
                    Download PDF
                </button>
                <button class="bg-[#7AC943] text-white px-4 py-2 rounded-md font-medium hover:bg-[#6AB13B]">
                    Download Excel
                </button>
            </div>

        </div>
    </div>

    <script>
        function toggleFilterInput() {
            const type = document.getElementById('filter-type').value;
            document.getElementById('filter-harian').classList.toggle('hidden', type !== 'harian');
            document.getElementById('filter-bulanan').classList.toggle('hidden', type !== 'bulanan');
        }
    </script>
</x-app-layout>
