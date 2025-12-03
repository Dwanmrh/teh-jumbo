<x-app-layout>

    <style>
        /* Paksa hilangkan panah default browser */
        select {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: none !important;
            background-position: right 0.5rem center !important;
        }
        /* Fix khusus untuk browser IE/Edge lama */
        select::-ms-expand {
            display: none;
        }
    </style>

    {{-- Resources Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row justify-between items-end mb-6 sm:mb-8 gap-4 sm:gap-6">
        <div class="w-full md:w-auto">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-stone-800 tracking-tight leading-tight flex items-center gap-2">
                <span class="material-symbols-rounded text-brand-600">monitor_heart</span>
                Ringkasan Bisnis
            </h2>
            <p class="text-stone-500 text-xs sm:text-sm mt-2 flex items-center gap-2">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                Data keuangan terkini: {{ now()->format('d M Y') }}
            </p>
        </div>

        {{-- FILTER FORM (OPTIMIZED FOR MOBILE & DESKTOP) --}}
        <form method="GET" action="{{ route('dashboard') }}"
              class="w-full md:w-auto bg-white p-2 rounded-2xl shadow-soft border border-stone-100 flex flex-col sm:flex-row items-center gap-2 relative z-30">

            {{-- Filter Bulan --}}
            {{-- Mobile: Lebar Penuh (w-full). Desktop: Lebar tetap (sm:w-[180px]) --}}
            <div class="relative w-full sm:w-[180px] group">
                <select name="bulan" onchange="this.form.submit()"
                    class="w-full max-w-full appearance-none outline-none border-none text-sm focus:ring-0 rounded-xl bg-stone-50 hover:bg-stone-100 text-stone-700 font-bold cursor-pointer py-3 pl-4 pr-10 transition-colors truncate">
                    <option value="">Pilih Bulan</option>
                    @foreach(range(1, 12) as $bln)
                        @php
                            $val = now()->year .'-'. str_pad($bln, 2, '0', STR_PAD_LEFT);
                            $namaBulan = \Carbon\Carbon::create()->month($bln)->translatedFormat('F');
                        @endphp
                        <option value="{{ $val }}" {{ request('bulan') == $val ? 'selected' : '' }}>
                            {{ $namaBulan }}
                        </option>
                    @endforeach
                </select>
                {{-- Icon Absolut --}}
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-stone-400 group-hover:text-brand-500 transition-colors">
                    <span class="material-symbols-rounded text-xl">calendar_month</span>
                </div>
            </div>

            {{-- Separator (Hanya muncul di Desktop) --}}
            <div class="hidden sm:block w-px h-8 bg-stone-200 mx-1"></div>

            {{-- Filter Tahun --}}
            <div class="relative w-full sm:w-[140px] group">
                <select name="tahun" onchange="this.form.submit()"
                    class="w-full max-w-full appearance-none outline-none border-none text-sm focus:ring-0 rounded-xl bg-stone-50 hover:bg-stone-100 text-stone-700 font-bold cursor-pointer py-3 pl-4 pr-10 transition-colors truncate">
                    <option value="">Pilih Tahun</option>
                    @foreach(range(now()->year, now()->year - 5) as $t)
                        <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                {{-- Icon Absolut --}}
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-stone-400 group-hover:text-brand-500 transition-colors">
                    <span class="material-symbols-rounded text-xl">expand_more</span>
                </div>
            </div>

        </form>
    </div>

    {{-- STATS CARDS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">

        {{-- 1. Card Masuk --}}
        <div class="bg-white rounded-[1.5rem] sm:rounded-[2rem] p-5 sm:p-6 shadow-soft border border-stone-100 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2.5 sm:p-3 bg-emerald-50 rounded-2xl text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300 shadow-sm">
                    <span class="material-symbols-rounded text-xl sm:text-2xl">arrow_downward</span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] sm:text-xs font-bold text-stone-400 uppercase tracking-wider">Total Pemasukan</span>
                </div>
            </div>
            <h3 class="text-2xl lg:text-3xl font-bold text-stone-800 tracking-tight">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h3>

            <div class="mt-4 flex flex-col gap-1">
                <div class="flex items-center gap-2">
                    <span class="bg-emerald-100 text-emerald-700 text-[10px] sm:text-xs px-2.5 py-1 rounded-full font-bold">+{{ $countMasuk }} Transaksi</span>
                </div>
                <p class="text-[10px] sm:text-xs text-stone-400 mt-1">
                    Rata-rata: <span class="font-bold text-emerald-600">Rp {{ $countMasuk > 0 ? number_format($totalMasuk / $countMasuk, 0, ',', '.') : 0 }}</span>
                </p>
            </div>

            {{-- Decorative BG Icon --}}
            <span class="material-symbols-rounded absolute -right-6 -bottom-6 text-[100px] sm:text-[140px] text-emerald-500/5 rotate-12 group-hover:scale-110 transition-transform duration-500 pointer-events-none">savings</span>
        </div>

        {{-- 2. Card Keluar --}}
        <div class="bg-white rounded-[1.5rem] sm:rounded-[2rem] p-5 sm:p-6 shadow-soft border border-stone-100 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2.5 sm:p-3 bg-rose-50 rounded-2xl text-rose-600 group-hover:bg-rose-500 group-hover:text-white transition-colors duration-300 shadow-sm">
                    <span class="material-symbols-rounded text-xl sm:text-2xl">arrow_upward</span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] sm:text-xs font-bold text-stone-400 uppercase tracking-wider">Total Pengeluaran</span>
                </div>
            </div>
            <h3 class="text-2xl lg:text-3xl font-bold text-stone-800 tracking-tight">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h3>

            <div class="mt-4 flex flex-col gap-1">
                <div class="flex items-center gap-2">
                    <span class="bg-rose-100 text-rose-700 text-[10px] sm:text-xs px-2.5 py-1 rounded-full font-bold">{{ $countKeluar }} Transaksi</span>
                </div>
                <p class="text-[10px] sm:text-xs text-stone-400 mt-1">
                    Rata-rata: <span class="font-bold text-rose-600">Rp {{ $countKeluar > 0 ? number_format($totalKeluar / $countKeluar, 0, ',', '.') : 0 }}</span>
                </p>
            </div>

            <span class="material-symbols-rounded absolute -right-6 -bottom-6 text-[100px] sm:text-[140px] text-rose-500/5 rotate-12 group-hover:scale-110 transition-transform duration-500 pointer-events-none">payments</span>
        </div>

        {{-- 3. Card Saldo --}}
        @php
            $isSurplus = $saldoAkhir >= 0;
            $persentaseTerpakai = $totalMasuk > 0 ? ($totalKeluar / $totalMasuk) * 100 : 0;
        @endphp
        <div class="relative bg-gradient-to-br {{ $isSurplus ? 'from-brand-500 to-brand-600' : 'from-red-500 to-red-600' }} rounded-[1.5rem] sm:rounded-[2rem] p-5 sm:p-6 shadow-glow text-white overflow-hidden group hover:-translate-y-1 transition-all duration-300 md:col-span-1">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 20px 20px;"></div>

            <div class="relative z-10 flex flex-col h-full justify-between">
                <div class="flex justify-between items-start">
                    <div class="p-2.5 sm:p-3 bg-white/20 rounded-2xl backdrop-blur-sm border border-white/10">
                        <span class="material-symbols-rounded text-xl sm:text-2xl">account_balance_wallet</span>
                    </div>
                    <span class="bg-white/20 px-3 py-1 rounded-full text-[10px] sm:text-xs font-bold backdrop-blur-md border border-white/10 flex items-center gap-1">
                        <span class="material-symbols-rounded text-sm">{{ $isSurplus ? 'trending_up' : 'trending_down' }}</span>
                        {{ $isSurplus ? 'Surplus' : 'Defisit' }}
                    </span>
                </div>

                <div class="mt-6">
                    <p class="text-xs font-medium text-white/80 uppercase tracking-wider mb-1">Saldo Bersih</p>
                    <h3 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h3>

                    <div class="mt-4 flex items-center gap-2 text-[10px] sm:text-xs font-medium bg-black/10 w-fit px-3 py-1 rounded-lg backdrop-blur-sm">
                        <span class="material-symbols-rounded text-sm">pie_chart</span>
                        {{ number_format($persentaseTerpakai, 1) }}% dana terpakai
                    </div>
                </div>
            </div>

            <span class="material-symbols-rounded absolute -right-8 -bottom-12 text-[150px] sm:text-[180px] text-white opacity-10 rotate-[20deg] group-hover:rotate-[30deg] transition-transform duration-500 pointer-events-none">paid</span>
        </div>
    </div>

    {{-- CHARTS ROW 1: BAR CHART & PIE MASUK --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        {{-- Main Chart (Bar) --}}
        <div class="lg:col-span-2 bg-white p-5 sm:p-7 rounded-[1.5rem] sm:rounded-[2rem] shadow-soft border border-stone-100 flex flex-col">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
                <div>
                    <h3 class="font-bold text-stone-800 text-base sm:text-lg tracking-tight">Tren Arus Kas</h3>
                    <p class="text-xs text-stone-400 font-medium">Perbandingan Pemasukan & Pengeluaran</p>
                </div>
                {{-- Legend Custom --}}
                <div class="flex gap-2 sm:gap-4 text-[10px] sm:text-xs font-bold bg-stone-50 p-1.5 rounded-xl border border-stone-100">
                    <span class="flex items-center gap-1.5 px-2 py-1 rounded-lg bg-white shadow-sm border border-stone-100 text-emerald-600"><span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-emerald-500"></span> Masuk</span>
                    <span class="flex items-center gap-1.5 px-2 py-1 rounded-lg bg-white shadow-sm border border-stone-100 text-rose-600"><span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-rose-500"></span> Keluar</span>
                </div>
            </div>
            <div class="relative w-full h-64 sm:h-72 md:h-80">
                <canvas id="trenKas"></canvas>
            </div>
        </div>

        {{-- Pie Chart (Masuk) --}}
        <div class="bg-white p-5 sm:p-7 rounded-[1.5rem] sm:rounded-[2rem] shadow-soft border border-stone-100 flex flex-col">
            <div class="mb-4">
                <h3 class="font-bold text-stone-800 text-base sm:text-lg tracking-tight">Sumber Dana</h3>
                <p class="text-xs text-stone-400 font-medium">Kategori Pemasukan</p>
            </div>
            <div class="flex-1 flex items-center justify-center relative min-h-[220px]">
                <canvas id="pieMasuk"></canvas>
            </div>
            <div id="legendMasuk" class="mt-6 space-y-2 overflow-y-auto max-h-40 pr-2 custom-scrollbar text-xs sm:text-sm"></div>
        </div>
    </div>

    {{-- CHARTS ROW 2: LINE CHART & PIE KELUAR --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        {{-- Line Chart (Saldo) --}}
        <div class="lg:col-span-2 bg-white p-5 sm:p-7 rounded-[1.5rem] sm:rounded-[2rem] shadow-soft border border-stone-100 flex flex-col">
            <div class="mb-6">
                <h3 class="font-bold text-stone-800 text-base sm:text-lg tracking-tight">Pertumbuhan Saldo</h3>
                <p class="text-xs text-stone-400 font-medium">Akumulasi keuntungan seiring waktu</p>
            </div>
            <div class="relative w-full h-64 sm:h-72 md:h-80">
                <canvas id="saldoChart"></canvas>
            </div>
        </div>

        {{-- Pie Chart (Keluar) --}}
        <div class="bg-white p-5 sm:p-7 rounded-[1.5rem] sm:rounded-[2rem] shadow-soft border border-stone-100 flex flex-col">
            <div class="mb-4">
                <h3 class="font-bold text-stone-800 text-base sm:text-lg tracking-tight">Pos Pengeluaran</h3>
                <p class="text-xs text-stone-400 font-medium">Alokasi Biaya</p>
            </div>
            <div class="flex-1 flex items-center justify-center relative min-h-[220px]">
                <canvas id="pieKeluar"></canvas>
            </div>
            <div id="legendKeluar" class="mt-6 space-y-2 overflow-y-auto max-h-40 pr-2 custom-scrollbar text-xs sm:text-sm"></div>
        </div>
    </div>

    {{-- JAVASCRIPT Logic --}}
    <script>
        // Palette warna Modern
        const colorSetMasuk = ['#10B981', '#3B82F6', '#F59E0B', '#8B5CF6', '#EC4899', '#6366F1'];
        const colorSetKeluar = ['#F43F5E', '#F97316', '#EAB308', '#A855F7', '#14B8A6', '#64748B'];

        // --- DATA DARI CONTROLLER ---
        const labelList = {!! json_encode($labelList) !!};
        const dataMasuk = {!! json_encode($dataMasuk) !!};
        const dataKeluar = {!! json_encode($dataKeluar) !!};
        const saldoKumulatif = {!! json_encode($saldoKumulatif) !!};

        const masukLabel = {!! json_encode($kategoriMasukLabel) !!};
        const masukNominal = {!! json_encode($kategoriMasukNominal) !!};
        const keluarLabel = {!! json_encode($kategoriKeluarLabel) !!};
        const keluarNominal = {!! json_encode($kategoriKeluarNominal) !!};

        // --- Helper: Format Rupiah Singkat (1 Jt, 500 K) ---
        function formatCurrencyCompact(value) {
            if (value >= 1000000 || value <= -1000000) {
                return 'Rp ' + (value / 1000000).toFixed(1) + ' Jt';
            } else if (value >= 1000 || value <= -1000) {
                return 'Rp ' + (value / 1000).toFixed(0) + ' K';
            }
            return 'Rp ' + value;
        }

        // --- Helper: Generate Custom Legend ---
        function generateLegend(labels, data, colors, elementId) {
            let total = data.reduce((a, b) => Number(a) + Number(b), 0);
            let html = "";
            labels.forEach((label, i) => {
                let percent = total > 0 ? ((data[i] / total) * 100).toFixed(0) : 0;
                if(data[i] > 0) {
                    html += `
                    <div class="flex justify-between items-center text-xs bg-stone-50 p-2.5 rounded-xl border border-stone-100 hover:bg-stone-100 transition-colors cursor-default">
                        <div class="flex items-center gap-3">
                            <div class="w-2.5 h-2.5 rounded-full ring-2 ring-white shadow-sm" style="background:${colors[i % colors.length]}"></div>
                            <span class="text-stone-600 font-bold truncate max-w-[100px] sm:max-w-[120px] md:max-w-[140px]">${label}</span>
                        </div>
                        <span class="font-bold text-stone-800 bg-white px-1.5 py-0.5 rounded shadow-sm border border-stone-100">${percent}%</span>
                    </div>`;
                }
            });
            document.getElementById(elementId).innerHTML = html;
        }

        document.addEventListener('DOMContentLoaded', function () {
            Chart.register(ChartDataLabels);
            Chart.defaults.font.family = "'Outfit', sans-serif";
            Chart.defaults.color = '#78716c';

            // 1. BAR CHART (TREN MASUK & KELUAR)
            new Chart(document.getElementById('trenKas'), {
                type: 'bar',
                data: {
                    labels: labelList,
                    datasets: [
                        { label: 'Masuk', data: dataMasuk, backgroundColor: '#10B981', hoverBackgroundColor: '#059669', borderRadius: 6, barPercentage: 0.6, categoryPercentage: 0.7 },
                        { label: 'Keluar', data: dataKeluar, backgroundColor: '#F43F5E', hoverBackgroundColor: '#E11D48', borderRadius: 6, barPercentage: 0.6, categoryPercentage: 0.7 }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        datalabels: { display: false },
                        tooltip: {
                            backgroundColor: '#1c1917', titleFont: {size: 13}, bodyFont: {size: 12}, padding: 10, cornerRadius: 8, displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + Number(context.raw).toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            grid: { color: '#f5f5f4', borderDash: [6, 6] },
                            border: { display: false },
                            ticks: {
                                padding: 10, font: {size: 11},
                                callback: function(value) { return formatCurrencyCompact(value); }
                            }
                        },
                        x: { grid: { display: false }, ticks: { padding: 10, font: {size: 11} } }
                    }
                }
            });

            // 2. LINE CHART (SALDO)
            new Chart(document.getElementById('saldoChart'), {
                type: 'line',
                data: {
                    labels: labelList,
                    datasets: [{
                        label: 'Saldo', data: saldoKumulatif,
                        borderColor: '#f97316', borderWidth: 3,
                        pointBackgroundColor: '#fff', pointBorderColor: '#f97316',
                        pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6,
                        fill: true,
                        backgroundColor: (ctx) => {
                            const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 300);
                            gradient.addColorStop(0, 'rgba(249, 115, 22, 0.15)');
                            gradient.addColorStop(1, 'rgba(249, 115, 22, 0)');
                            return gradient;
                        },
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        datalabels: { display: false },
                        tooltip: {
                            backgroundColor: '#f97316', titleColor: '#fff', bodyColor: '#fff', padding: 10, cornerRadius: 8, displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return 'Saldo: Rp ' + Number(context.raw).toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            grid: { color: '#f5f5f4', borderDash: [6, 6] },
                            border: { display: false },
                            ticks: {
                                padding: 10,
                                callback: function(value) { return formatCurrencyCompact(value); }
                            }
                        },
                        x: { grid: { display: false }, ticks: { padding: 10 } }
                    }
                }
            });

            // 3. PIE CHARTS CONFIG (Doughnut Style)
            const pieOptions = {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, datalabels: { display: false }, tooltip: { backgroundColor: '#1c1917', padding: 12, cornerRadius: 8 } },
                cutout: '75%',
                layout: { padding: 10 },
                elements: { arc: { borderWidth: 0, hoverOffset: 10 } }
            };

            new Chart(document.getElementById('pieMasuk'), {
                type: 'doughnut',
                data: { labels: masukLabel, datasets: [{ data: masukNominal, backgroundColor: colorSetMasuk }] },
                options: pieOptions
            });
            generateLegend(masukLabel, masukNominal, colorSetMasuk, 'legendMasuk');

            new Chart(document.getElementById('pieKeluar'), {
                type: 'doughnut',
                data: { labels: keluarLabel, datasets: [{ data: keluarNominal, backgroundColor: colorSetKeluar }] },
                options: pieOptions
            });
            generateLegend(keluarLabel, keluarNominal, colorSetKeluar, 'legendKeluar');
        });
    </script>
</x-app-layout>
