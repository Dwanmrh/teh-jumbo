<x-app-layout>
    {{-- Fonts & Chart --}}
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-8 bg-[#f7f7f7] font-[Outfit] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="flex flex-wrap justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-[#2F362C]">Dashboard Keuangan</h2>
                <a href="{{ route('laporan.keuangan') }}"
                   class="bg-[#F5C04C] text-[#2F362C] font-medium px-4 py-2 rounded-md hover:bg-[#E0AC3B] transition">
                   Detail Laporan
                </a>
            </div>

            {{-- FILTER --}}
            <form method="GET" action="{{ route('dashboard') }}"
                class="bg-white p-4 rounded-md shadow-md flex flex-wrap items-center gap-3 mb-6">
                <label for="filter-type" class="font-medium text-[#2F362C]">Filter</label>

                <div class="relative">
                    <select id="filter-type" name="filter_type" onchange="toggleFilterInput()"
                        class="appearance-none bg-[#F5C04C] text-[#2F362C] font-medium px-4 py-2 pr-10 rounded-md border-none outline-none cursor-pointer">
                        <option value="">Pilih Jangka Waktu</option>
                        <option value="harian" {{ $filterType == 'harian' ? 'selected' : '' }}>Harian</option>
                        <option value="bulanan" {{ $filterType == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="tahunan" {{ $filterType == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                    <svg class="w-4 h-4 text-[#2F362C] absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                {{-- Input Harian --}}
                <input type="date" name="tanggal" id="filter-harian"
                    value="{{ $tanggal ?? '' }}"
                    class="{{ $filterType == 'harian' ? '' : 'hidden' }} bg-[#F5C04C] px-3 py-2 rounded-md text-[#2F362C]" />

                {{-- Input Bulanan --}}
                <input type="month" name="bulan" id="filter-bulanan"
                    value="{{ $bulan ?? '' }}"
                    class="{{ $filterType == 'bulanan' ? '' : 'hidden' }} bg-[#F5C04C] px-3 py-2 rounded-md text-[#2F362C]" />

                {{-- Input Tahunan --}}
                <input type="number" name="tahun" id="filter-tahunan" min="2000" max="{{ date('Y') }}"
                    value="{{ $tahun ?? date('Y') }}"
                    class="{{ $filterType == 'tahunan' ? '' : 'hidden' }} bg-[#F5C04C] px-3 py-2 rounded-md text-[#2F362C]" />

                <button type="submit"
                    class="bg-[#7AC943] text-white px-4 py-2 rounded-md hover:bg-[#6AB13B] transition">
                    Terapkan
                </button>

                <a href="{{ route('dashboard') }}"
                   class="bg-[#E74C3C] text-white px-4 py-2 rounded-md font-medium hover:bg-[#C0392B] transition">
                    Reset
                </a>
            </form>

            {{-- CARD SUMMARY --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <div class="bg-white shadow-md rounded-xl p-5 border-l-4 border-[#7AC943] hover:scale-[1.02] transition">
                    <h3 class="text-sm font-medium text-[#2F362C] mb-1">Total Kas Masuk</h3>
                    <p class="text-xl font-bold text-[#2F362C]">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white shadow-md rounded-xl p-5 border-l-4 border-[#E74C3C] hover:scale-[1.02] transition">
                    <h3 class="text-sm font-medium text-[#2F362C] mb-1">Total Kas Keluar</h3>
                    <p class="text-xl font-bold text-[#2F362C]">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white shadow-md rounded-xl p-5 border-l-4 border-[#F5C04C] hover:scale-[1.02] transition">
                    <h3 class="text-sm font-medium text-[#2F362C] mb-1">Selisih / Neraca</h3>
                    <p class="text-xl font-bold text-[#2F362C]">Rp {{ number_format($selisihKas, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white shadow-md rounded-xl p-5 border-l-4 border-[#8E44AD] hover:scale-[1.02] transition">
                    <h3 class="text-sm font-medium text-[#2F362C] mb-1">Saldo Akhir</h3>
                    <p class="text-xl font-bold text-[#2F362C]">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- GRAFIK --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-[#B6D96C] text-[#2F362C] font-semibold p-3 flex justify-between items-center">
                    <span>Grafik Keuangan</span>
                    <span class="text-sm text-[#2F362C]/70">
                        @if($filterType === 'harian')
                            {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                        @elseif($filterType === 'bulanan')
                            {{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}
                        @else
                            Tahun {{ $tahun }}
                        @endif
                    </span>
                </div>
                <div class="p-5">
                    <canvas id="chartKeuangan" class="w-full max-h-[400px]"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
        const ctx = document.getElementById('chartKeuangan').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($bulanList) !!},
                datasets: [
                    { label: 'Kas Masuk', data: {!! json_encode($dataMasuk) !!}, backgroundColor: '#7AC943' },
                    { label: 'Kas Keluar', data: {!! json_encode($dataKeluar) !!}, backgroundColor: '#E74C3C' },
                    { label: 'Selisih', data: {!! json_encode($dataSelisih) !!}, backgroundColor: '#F5C04C' },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } } },
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: Rp ${ctx.raw.toLocaleString('id-ID')}` } }
                }
            }
        });

        function toggleFilterInput() {
            const type = document.getElementById('filter-type').value;
            document.getElementById('filter-harian').classList.add('hidden');
            document.getElementById('filter-bulanan').classList.add('hidden');
            document.getElementById('filter-tahunan').classList.add('hidden');
            if (type === 'harian') document.getElementById('filter-harian').classList.remove('hidden');
            if (type === 'bulanan') document.getElementById('filter-bulanan').classList.remove('hidden');
            if (type === 'tahunan') document.getElementById('filter-tahunan').classList.remove('hidden');
        }
        document.addEventListener('DOMContentLoaded', toggleFilterInput);
    </script>
</x-app-layout>
