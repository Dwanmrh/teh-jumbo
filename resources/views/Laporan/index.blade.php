<x-app-layout>
    {{-- Font Outfit --}}
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <div class="py-8 bg-[#f7f7f7] font-[Outfit] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- JUDUL --}}
            <h2 class="text-2xl font-semibold text-[#2F362C] mb-6">Laporan Keuangan</h2>

            {{-- FILTER --}}
            <form method="GET" action="{{ route('laporan.keuangan') }}"
                class="bg-white p-4 rounded-md shadow-md flex flex-wrap items-center gap-3 mb-6">

                <label for="filter-type" class="font-medium text-[#2F362C]">Filter</label>

                {{-- Dropdown Jangka Waktu --}}
                <div class="relative">
                    <select id="filter-type" name="filter_type" onchange="toggleFilterInput()"
                        class="appearance-none bg-[#F5C04C] text-[#2F362C] font-medium px-4 py-2 pr-10 rounded-md border-none outline-none cursor-pointer">
                        <option value="">Pilih Jangka Waktu</option>
                        <option value="harian" {{ $filterType=='harian' ? 'selected' : '' }}>Harian</option>
                        <option value="bulanan" {{ $filterType=='bulanan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="rentang" {{ $filterType=='rentang' ? 'selected' : '' }}>Rentang Tanggal</option>
                    </select>

                    {{-- Panah custom --}}
                    <svg class="w-4 h-4 text-[#2F362C] absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                {{-- Input Harian --}}
                <input type="date" name="filter_value" id="filter-harian"
                    value="{{ $filterType=='harian' ? $filterValue : '' }}"
                    class="{{ $filterType=='harian' ? '' : 'hidden' }} bg-[#F5C04C] px-3 py-2 rounded-md text-[#2F362C]" />

                {{-- Input Bulanan --}}
                <input type="month" name="filter_value" id="filter-bulanan"
                    value="{{ $filterType=='bulanan' ? $filterValue : '' }}"
                    class="{{ $filterType=='bulanan' ? '' : 'hidden' }} bg-[#F5C04C] px-3 py-2 rounded-md text-[#2F362C]" />

                {{-- Input Rentang --}}
                <div id="filter-rentang"
                    class="flex items-center gap-2 {{ $filterType=='rentang' ? '' : 'hidden' }}">
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="bg-[#F5C04C] px-3 py-2 rounded-md text-[#2F362C]" />
                    <span class="text-[#2F362C]">s.d</span>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="bg-[#F5C04C] px-3 py-2 rounded-md text-[#2F362C]" />
                </div>

                {{-- Tombol Terapkan --}}
                <button type="submit"
                    class="bg-[#7AC943] text-white px-4 py-2 rounded-md hover:bg-[#6AB13B] transition">
                    Terapkan
                </button>

                {{-- Tombol Reset --}}
                <a href="{{ route('laporan.keuangan') }}"
                    class="bg-[#E74C3C] text-white px-4 py-2 rounded-md font-medium hover:bg-[#C0392B] transition">
                    Reset
                </a>
            </form>

            {{-- RINGKASAN --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <div class="bg-white shadow-md rounded-md p-5 border-l-4 border-[#7AC943]">
                    <h3 class="text-sm font-medium text-[#2F362C] mb-1">Total Kas Masuk</h3>
                    <p class="text-lg font-bold text-[#2F362C]">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white shadow-md rounded-md p-5 border-l-4 border-[#E74C3C]">
                    <h3 class="text-sm font-medium text-[#2F362C] mb-1">Total Kas Keluar</h3>
                    <p class="text-lg font-bold text-[#2F362C]">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white shadow-md rounded-md p-5 border-l-4 border-[#F5C04C]">
                    <h3 class="text-sm font-medium text-[#2F362C] mb-1">Selisih / Neraca Kas</h3>
                    <p class="text-lg font-bold text-[#2F362C]">Rp {{ number_format($selisihKas, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white shadow-md rounded-md p-5 border-l-4 border-[#8E44AD]">
                    <h3 class="text-sm font-medium text-[#2F362C] mb-1">Saldo Akhir</h3>
                    <p class="text-lg font-bold text-[#2F362C]">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- TABEL LAPORAN --}}
            <div class="bg-white rounded-md shadow-md p-4 overflow-x-auto">
                <table class="w-full border-collapse text-center text-sm">
                    <thead>
                        <tr class="bg-[#FFF2CF] text-[#2F362C] font-semibold">
                            <th class="border p-2">No</th>
                            <th class="border p-2">Tanggal</th>
                            <th class="border p-2">Keterangan</th>
                            <th class="border p-2">Kategori</th>
                            <th class="border p-2">Metode</th>
                            <th class="border p-2">Kas Masuk</th>
                            <th class="border p-2">Kas Keluar</th>
                            <th class="border p-2">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporan as $index => $item)
                            <tr class="even:bg-gray-50">
                                <td class="border p-2">{{ $index + 1 }}</td>
                                <td class="border p-2">{{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}</td>
                                <td class="border p-2 text-left">{{ $item['keterangan'] }}</td>
                                <td class="border p-2">{{ $item['kategori'] ?? '-' }}</td>
                                <td class="border p-2">{{ $item['metode'] ?? '-' }}</td>
                                <td class="border p-2 text-green-600">
                                    @if($item['kas_masuk'] > 0)
                                        Rp {{ number_format($item['kas_masuk'], 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="border p-2 text-red-600">
                                    @if($item['kas_keluar'] > 0)
                                        Rp {{ number_format($item['kas_keluar'], 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="border p-2 font-medium text-[#2F362C]">
                                    Rp {{ number_format($item['saldo'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-3 text-gray-500">Belum ada data transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- TOMBOL EXPORT --}}
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('laporan.export.pdf', request()->all()) }}"
                class="bg-[#F5C04C] text-[#2F362C] px-4 py-2 rounded-md font-medium hover:bg-[#E0AC3B]">
                    Download PDF
                </a>
                <a href="{{ route('laporan.export.excel', request()->all()) }}"
                class="bg-[#7AC943] text-white px-4 py-2 rounded-md font-medium hover:bg-[#6AB13B]">
                    Download Excel
                </a>
            </div>

        </div>
    </div>

    {{-- SCRIPT FILTER --}}
    <script>
        function toggleFilterInput() {
            const type = document.getElementById('filter-type').value;
            document.getElementById('filter-harian').classList.add('hidden');
            document.getElementById('filter-bulanan').classList.add('hidden');
            document.getElementById('filter-rentang').classList.add('hidden');

            if (type === 'harian') {
                document.getElementById('filter-harian').classList.remove('hidden');
            } else if (type === 'bulanan') {
                document.getElementById('filter-bulanan').classList.remove('hidden');
            } else if (type === 'rentang') {
                document.getElementById('filter-rentang').classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>
