<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-8 bg-[#f7f7f7] font-[Outfit]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Judul --}}
            <h2 class="text-2xl font-semibold text-[#2F362C] mb-6">Dashboard</h2>

            {{-- Filter --}}
            <div class="bg-white p-3 rounded-md shadow-md flex items-center gap-3 max-w-sm mb-6">
                <label for="filter" class="font-medium text-[#2F362C]">Filter</label>
                <select id="filter" class="bg-[#F5C04C] rounded-md px-3 py-1.5 font-medium text-[#2F362C] border-none outline-none">
                    <option>Pilih Jangka Waktu</option>
                    <option>Harian</option>
                    <option>Bulanan</option>
                </select>
            </div>

            {{-- Cards --}}
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-8">
                <div class="bg-white shadow-md rounded-md p-5 border-l-4 border-[#F5C04C]">
                    <h3 class="text-sm font-medium text-[#2F362C] mb-1">Profit Kotor</h3>
                    <p class="text-lg font-bold">Rp 10.000.000</p>
                </div>
                <div class="bg-white shadow-md rounded-md p-5 border-l-4 border-[#E74C3C]">
                    <h3 class="text-sm font-medium text-[#2F362C] mb-1">Sewa Tempat</h3>
                    <p class="text-lg font-bold">Rp 4.500.000</p>
                </div>
                <div class="bg-white shadow-md rounded-md p-5 border-l-4 border-[#1ABC9C]">
                    <h3 class="text-sm font-medium text-[#2F362C] mb-1">Operasional</h3>
                    <p class="text-lg font-bold">Rp 4.500.000</p>
                </div>
                <div class="bg-white shadow-md rounded-md p-5 border-l-4 border-[#8E44AD]">
                    <h3 class="text-sm font-medium text-[#2F362C] mb-1">Karyawan</h3>
                    <p class="text-lg font-bold">Rp 5.500.000</p>
                </div>
                <div class="bg-white shadow-md rounded-md p-5 border-l-4 border-[#7AC943] sm:col-span-2 lg:col-span-3">
                    <h3 class="text-sm font-medium text-[#2F362C] mb-1">Profit Bersih</h3>
                    <p class="text-lg font-bold">Rp 15.000.000</p>
                </div>
            </div>

            {{-- Chart --}}
            <div class="bg-white rounded-md shadow-lg overflow-hidden">
                <div class="bg-[#B6D96C] text-[#2F362C] font-semibold p-3">Grafik Profit & Pengeluaran</div>
                <div class="p-4">
                    <canvas id="incomeChart" class="max-h-[400px] w-full"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('incomeChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
                datasets: [
                    { label: 'Profit Kotor', data: [10000000,12000000,11000000,14000000,13000000,12500000,15000000], backgroundColor: '#F5C04C' },
                    { label: 'Sewa Tempat', data: [4500000,4600000,4400000,4700000,4500000,4600000,4800000], backgroundColor: '#E74C3C' },
                    { label: 'Operasional', data: [4500000,4200000,4700000,4300000,4400000,4500000,4600000], backgroundColor: '#1ABC9C' },
                    { label: 'Karyawan', data: [5500000,5300000,5600000,5800000,5400000,5700000,5900000], backgroundColor: '#8E44AD' },
                    { label: 'Profit Bersih', data: [15000000,16000000,15500000,17000000,16500000,17200000,18000000], backgroundColor: '#7AC943' },
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
    </script>
</x-app-layout>
