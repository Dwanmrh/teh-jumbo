<x-app-layout>

    {{-- EXTERNAL SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    {{-- CUSTOM STYLES (Scoped) --}}
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background-color: #e7e5e4; border-radius: 20px; }
        .custom-scroll:hover::-webkit-scrollbar-thumb { background-color: #d6d3d1; }

        /* --- PERBAIKAN: Hapus panah default select browser & Tailwind Forms Plugin --- */
        .no-arrow {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: none !important; /* PENTING: Menghapus panah SVG dari Tailwind Forms */
            background-position: 0 0 !important;
        }
        /* Untuk Chrome/Safari/Edge yang lebih baru */
        .no-arrow::-webkit-calendar-picker-indicator {
            display: none !important;
            opacity: 0;
        }
        /* Untuk IE10+ */
        .no-arrow::-ms-expand {
            display: none;
        }
    </style>

    <div class="space-y-6 sm:space-y-8 animate-fade-in-up pb-24">

        {{-- 1. HEADER SECTION (Greeting & Filters) --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">

            {{-- Greeting Area --}}
            <div>
                @php
                    $hour = date('H');
                    if ($hour < 11) $greeting = 'Selamat Pagi';
                    elseif ($hour < 15) $greeting = 'Selamat Siang';
                    elseif ($hour < 18) $greeting = 'Selamat Sore';
                    else $greeting = 'Selamat Malam';
                @endphp
                <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-800 tracking-tight leading-tight">
                    {{ $greeting }}, <span class="text-brand-600">{{ Auth::user()->name }}</span>!
                </h1>
                <p class="text-stone-500 text-sm mt-1 font-medium">
                    Berikut ringkasan performa <span class="font-bold text-stone-700">Teh Solo de Jumbo</span>.
                </p>
            </div>

            {{-- Filter Area --}}
            <form method="GET" action="{{ route('dashboard') }}"
                  class="bg-white p-1.5 rounded-2xl shadow-sm border border-stone-200 flex flex-col sm:flex-row items-center gap-2 w-full lg:w-auto">

                {{-- Select Bulan --}}
                <div class="relative w-full sm:w-48">
                    {{-- PERBAIKAN: Menambahkan class 'no-arrow' di sini --}}
                    <select name="bulan" onchange="this.form.submit()"
                            class="no-arrow w-full appearance-none bg-stone-50 border-transparent text-stone-700 text-sm font-bold py-2.5 pl-4 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:bg-white transition cursor-pointer hover:bg-stone-100">
                        <option value="">Setahun Penuh</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                    <span class="material-symbols-rounded absolute right-3 top-2.5 text-stone-400 pointer-events-none text-xl">calendar_month</span>
                </div>

                {{-- Select Tahun --}}
                <div class="relative w-full sm:w-32">
                    {{-- PERBAIKAN: Menambahkan class 'no-arrow' di sini --}}
                    <select name="tahun" onchange="this.form.submit()"
                            class="no-arrow w-full appearance-none bg-stone-50 border-transparent text-stone-700 text-sm font-bold py-2.5 pl-4 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:bg-white transition cursor-pointer hover:bg-stone-100">
                        @foreach(range(now()->year, 2024) as $y)
                            <option value="{{ $y }}" {{ request('tahun', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-rounded absolute right-3 top-2.5 text-stone-400 pointer-events-none text-xl">expand_more</span>
                </div>
            </form>
        </div>

        {{-- 2. KEY METRICS (Stats Cards) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            {{-- Card: Pemasukan --}}
            <div class="group relative bg-white rounded-[1.5rem] p-6 shadow-soft border border-stone-100 overflow-hidden hover:border-emerald-200 transition-all duration-300">
                <div class="absolute top-0 right-0 p-5 opacity-50 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
                    <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center">
                        <span class="material-symbols-rounded text-2xl">arrow_downward</span>
                    </div>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold text-stone-400 uppercase tracking-widest mb-1">Pemasukan</p>
                    <h3 class="text-2xl lg:text-3xl font-extrabold text-stone-800 tracking-tight">
                        <span class="text-lg text-stone-400 font-medium mr-0.5">Rp</span>{{ number_format($totalMasuk, 0, ',', '.') }}
                    </h3>
                    <div class="mt-4 inline-flex items-center gap-2 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">
                         <span class="material-symbols-rounded text-emerald-600 text-sm font-bold">add</span>
                         <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wide">{{ $countMasuk }} Transaksi</span>
                    </div>
                </div>
                <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-emerald-50 rounded-full blur-2xl opacity-60"></div>
            </div>

            {{-- Card: Pengeluaran --}}
            <div class="group relative bg-white rounded-[1.5rem] p-6 shadow-soft border border-stone-100 overflow-hidden hover:border-rose-200 transition-all duration-300">
                <div class="absolute top-0 right-0 p-5 opacity-50 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
                    <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center">
                        <span class="material-symbols-rounded text-2xl">arrow_upward</span>
                    </div>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold text-stone-400 uppercase tracking-widest mb-1">Pengeluaran</p>
                    <h3 class="text-2xl lg:text-3xl font-extrabold text-stone-800 tracking-tight">
                        <span class="text-lg text-stone-400 font-medium mr-0.5">Rp</span>{{ number_format($totalKeluar, 0, ',', '.') }}
                    </h3>
                    <div class="mt-4 inline-flex items-center gap-2 bg-rose-50 px-2.5 py-1 rounded-full border border-rose-100">
                        <span class="material-symbols-rounded text-rose-600 text-sm font-bold">remove</span>
                        <span class="text-[10px] font-bold text-rose-700 uppercase tracking-wide">{{ $countKeluar }} Transaksi</span>
                   </div>
                </div>
                 <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-rose-50 rounded-full blur-2xl opacity-60"></div>
            </div>

            {{-- Card: Saldo (Dark Theme) --}}
            <div class="relative bg-stone-900 rounded-[1.5rem] p-6 shadow-xl shadow-stone-900/10 text-white overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-stone-800 to-black opacity-80"></div>
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-40 h-40 bg-brand-500/20 rounded-full blur-3xl group-hover:bg-brand-500/30 transition-colors duration-500"></div>

                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-stone-400 uppercase tracking-widest mb-1">Saldo Akhir</p>
                            <h3 class="text-3xl lg:text-4xl font-extrabold tracking-tight text-white">
                                <span class="text-lg text-stone-500 font-medium mr-0.5">Rp</span>{{ number_format($saldoAkhir, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="p-2 bg-white/10 backdrop-blur-md rounded-xl border border-white/5 shadow-inner">
                            <span class="material-symbols-rounded text-brand-400">account_balance_wallet</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-white/10">
                        <span class="text-[10px] text-stone-500 font-bold uppercase block mb-1">Status Periode</span>
                        @if($surplusPeriode >= 0)
                            <div class="text-emerald-400 font-bold text-sm flex items-center gap-1">
                                <span class="material-symbols-rounded text-base">trending_up</span>
                                Surplus Rp {{ number_format($surplusPeriode, 0, ',', '.') }}
                            </div>
                        @else
                            <div class="text-rose-400 font-bold text-sm flex items-center gap-1">
                                <span class="material-symbols-rounded text-base">trending_down</span>
                                Defisit Rp {{ number_format(abs($surplusPeriode), 0, ',', '.') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. MAIN ANALYTICS --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- Main Chart --}}
            <div class="lg:col-span-8 bg-white rounded-[1.5rem] shadow-soft border border-stone-100 p-6 flex flex-col">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                    <div>
                        <h3 class="font-bold text-stone-800 text-lg">Analisa Arus Kas</h3>
                        <p class="text-xs text-stone-400 font-medium mt-0.5">Grafik perbandingan & tren saldo</p>
                    </div>

                    {{-- Legend --}}
                    <div class="flex items-center gap-4 bg-stone-50 px-3 py-1.5 rounded-lg border border-stone-100 self-start sm:self-auto">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-stone-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Masuk
                        </div>
                        <div class="flex items-center gap-1.5 text-xs font-bold text-stone-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Keluar
                        </div>
                        <div class="items-center gap-1.5 text-xs font-bold text-stone-600 hidden sm:flex">
                            <span class="w-2.5 h-2.5 rounded-full bg-stone-800"></span> Saldo
                        </div>
                    </div>
                </div>

                <div class="relative w-full h-[320px] sm:h-[400px]">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="lg:col-span-4 flex flex-col gap-6">
                <div class="bg-white rounded-[1.5rem] shadow-soft border border-stone-100 p-6 flex flex-col h-full max-h-[500px] lg:max-h-full">
                    <div class="flex justify-between items-center mb-4 shrink-0">
                        <div>
                            <h3 class="font-bold text-stone-800 text-lg">Aktivitas Baru</h3>
                            <p class="text-xs text-stone-400 font-medium">Transaksi terakhir</p>
                        </div>
                        <a href="{{ route('laporan.index') }}" class="group flex items-center gap-1 text-[10px] font-bold text-brand-600 bg-brand-50 px-2.5 py-1.5 rounded-lg hover:bg-brand-100 transition">
                            SEMUA <span class="material-symbols-rounded text-xs group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                        </a>
                    </div>

                    <div class="flex-1 overflow-y-auto custom-scroll pr-1 space-y-3 -mr-2">
                        @forelse($recentActivity as $item)
                            <div class="flex items-center justify-between p-3 rounded-2xl border border-stone-50 hover:bg-stone-50 hover:border-stone-200 transition-all duration-200 group cursor-default">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 border
                                        {{ $item->type == 'in' ? 'bg-emerald-50 border-emerald-100 text-emerald-600' : 'bg-rose-50 border-rose-100 text-rose-600' }}">
                                        <span class="material-symbols-rounded text-xl">
                                            {{ $item->type == 'in' ? 'arrow_downward' : 'arrow_upward' }}
                                        </span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-stone-700 truncate group-hover:text-stone-900 transition-colors">
                                            {{ $item->kategori ?? 'Umum' }}
                                        </p>
                                        <p class="text-[10px] text-stone-400 font-medium flex items-center gap-1">
                                            {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M, H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right pl-2 shrink-0">
                                    <p class="text-xs sm:text-sm font-bold {{ $item->type == 'in' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $item->type == 'in' ? '+' : '-' }}{{ number_format($item->total, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-48 text-stone-300">
                                <span class="material-symbols-rounded text-4xl mb-2 opacity-50">receipt_long</span>
                                <span class="text-xs font-medium">Belum ada data transaksi</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. PIE CHARTS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Pie Masuk --}}
            <div class="bg-white rounded-[1.5rem] shadow-soft border border-stone-100 p-6">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-1 h-4 bg-emerald-500 rounded-full"></div>
                    <h4 class="font-bold text-stone-700 text-sm uppercase tracking-wider">Sumber Pemasukan</h4>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-8">
                    <div class="relative w-40 h-40 sm:w-48 sm:h-48 shrink-0">
                        <canvas id="pieMasuk"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <span class="material-symbols-rounded text-emerald-200 text-4xl opacity-50">savings</span>
                        </div>
                    </div>
                    <div id="legendMasuk" class="w-full text-xs space-y-2 max-h-48 overflow-y-auto custom-scroll pr-2"></div>
                </div>
            </div>

            {{-- Pie Keluar --}}
            <div class="bg-white rounded-[1.5rem] shadow-soft border border-stone-100 p-6">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-1 h-4 bg-rose-500 rounded-full"></div>
                    <h4 class="font-bold text-stone-700 text-sm uppercase tracking-wider">Pos Pengeluaran</h4>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-8">
                    <div class="relative w-40 h-40 sm:w-48 sm:h-48 shrink-0">
                        <canvas id="pieKeluar"></canvas>
                         <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <span class="material-symbols-rounded text-rose-200 text-4xl opacity-50">payments</span>
                        </div>
                    </div>
                    <div id="legendKeluar" class="w-full text-xs space-y-2 max-h-48 overflow-y-auto custom-scroll pr-2"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART SCRIPTS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Chart.defaults.font.family = "'Outfit', sans-serif";
            Chart.defaults.color = '#78716c';

            const labels = {!! json_encode($labelList) !!};
            const dMasuk = {!! json_encode($dataMasuk) !!};
            const dKeluar = {!! json_encode($dataKeluar) !!};
            const dSaldo = {!! json_encode($saldoKumulatif) !!};

            const formatCompact = (val) => {
                if(val >= 1000000) return (val/1000000).toFixed(1) + 'jt';
                if(val >= 1000) return (val/1000).toFixed(0) + 'rb';
                return val;
            };

            // Main Chart
            const ctxMain = document.getElementById('mainChart').getContext('2d');
            let gradMasuk = ctxMain.createLinearGradient(0, 0, 0, 400);
            gradMasuk.addColorStop(0, '#10b981'); gradMasuk.addColorStop(1, '#059669');
            let gradKeluar = ctxMain.createLinearGradient(0, 0, 0, 400);
            gradKeluar.addColorStop(0, '#f43f5e'); gradKeluar.addColorStop(1, '#e11d48');

            new Chart(ctxMain, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        { type: 'line', label: 'Saldo', data: dSaldo, borderColor: '#292524', borderWidth: 2, backgroundColor: '#292524', pointBackgroundColor: '#fff', pointBorderColor: '#292524', pointRadius: 4, tension: 0.4, yAxisID: 'y1', order: 1 },
                        { label: 'Masuk', data: dMasuk, backgroundColor: gradMasuk, borderRadius: 6, barPercentage: 0.5, categoryPercentage: 0.8, order: 2 },
                        { label: 'Keluar', data: dKeluar, backgroundColor: gradKeluar, borderRadius: 6, barPercentage: 0.5, categoryPercentage: 0.8, order: 3 }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: 'rgba(28, 25, 23, 0.9)', padding: 14, cornerRadius: 12, displayColors: true,
                            callbacks: { label: function(c) { return (c.dataset.label||'') + ': ' + new Intl.NumberFormat('id-ID', {style:'currency', currency:'IDR', minimumFractionDigits:0}).format(c.parsed.y); } }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: {size: 11}, maxRotation: 0, autoSkip: true, maxTicksLimit: 8 } },
                        y: { type: 'linear', display: true, position: 'left', grid: { borderDash: [4, 4], color: '#f5f5f4', drawBorder: false }, ticks: { callback: formatCompact, font: {size: 11} } },
                        y1: { display: false }
                    }
                }
            });

            // Pie Charts
            const createPie = (canvasId, labels, data, colors, legendId) => {
                const ctx = document.getElementById(canvasId).getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: { labels: labels, datasets: [{ data: data, backgroundColor: colors, borderWidth: 0, hoverOffset: 10 }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '80%', plugins: { legend: { display: false }, tooltip: { enabled: true } } }
                });
                const container = document.getElementById(legendId);
                let html = ''; let total = data.reduce((a,b)=>Number(a)+Number(b),0);
                if(total === 0) { container.innerHTML = '<div class="text-center py-4 text-stone-400 italic">Tidak ada data</div>'; return; }
                labels.forEach((lbl, i) => {
                    if(data[i] > 0){
                        let pct = ((data[i]/total)*100).toFixed(0)+'%';
                        let val = new Intl.NumberFormat('id-ID').format(data[i]);
                        html += `<div class="flex justify-between items-center p-2 rounded-lg hover:bg-stone-50 transition cursor-default group"><div class="flex items-center gap-3 overflow-hidden"><span class="w-3 h-3 rounded-full shrink-0 shadow-sm" style="background:${colors[i % colors.length]}"></span><div class="flex flex-col min-w-0"><span class="truncate text-xs font-bold text-stone-600 group-hover:text-stone-800">${lbl}</span><span class="text-[10px] text-stone-400">Rp ${val}</span></div></div><span class="font-bold text-stone-700 bg-stone-100 px-2 py-1 rounded text-[10px] min-w-[36px] text-center">${pct}</span></div>`;
                    }
                });
                container.innerHTML = html;
            };
            const colorsIn = ['#10b981', '#34d399', '#6ee7b7', '#059669', '#064e3b'];
            const colorsOut = ['#f43f5e', '#fb7185', '#fda4af', '#e11d48', '#881337'];
            createPie('pieMasuk', {!! json_encode($masukLabel) !!}, {!! json_encode($masukNominal) !!}, colorsIn, 'legendMasuk');
            createPie('pieKeluar', {!! json_encode($keluarLabel) !!}, {!! json_encode($keluarNominal) !!}, colorsOut, 'legendKeluar');
        });
    </script>
</x-app-layout>
