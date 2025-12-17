<x-app-layout>
    {{-- Judul Halaman (untuk meta title) --}}
    <x-slot name="title">Dashboard</x-slot>

    {{-- EXTERNAL LIBS --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    <div class="space-y-6 sm:space-y-8">

        {{-- ===============================================
             0. ALERTS & QUICK ACTIONS
             =============================================== --}}

        {{-- Alert Stok Menipis --}}
        @if(isset($lowStockCount) && $lowStockCount > 0)
        <div class="bg-rose-50 border border-rose-100 rounded-2xl p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-2 animate-pulse ring-1 ring-rose-200 shadow-sm">
            <div class="p-2 bg-rose-100 text-rose-600 rounded-xl shrink-0">
                <span class="material-symbols-rounded">gpp_maybe</span>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-rose-800 text-sm">Perhatian: Stok Menipis!</h4>
                <p class="text-xs text-rose-600 mt-0.5">
                    Ada <b>{{ $lowStockCount }} barang</b> (seperti Cup atau Bahan Baku) yang jumlahnya tinggal sedikit (≤ 10). Segera cek gudang!
                </p>
            </div>
            <a href="{{ route('products.index') }}" class="w-full sm:w-auto text-center px-4 py-2 bg-rose-600 text-white text-xs font-bold rounded-xl hover:bg-rose-700 transition shadow-lg shadow-rose-200">
                Produk
            </a>
        </div>
        @endif

        {{-- ===============================================
             1. HEADER SECTION & FILTERS
             =============================================== --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">

            {{-- Greeting Area --}}
            <div class="relative z-10">
                @php
                    $h = date('H');
                    $greet = $h < 11 ? 'Selamat Pagi' : ($h < 15 ? 'Selamat Siang' : ($h < 18 ? 'Selamat Sore' : 'Selamat Malam'));
                @endphp

                <div class="flex flex-wrap items-center gap-3 mb-4">
                    {{-- Live Badge --}}
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-brand-100 shadow-sm">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                        </span>
                        <span class="text-[10px] font-bold text-stone-500 uppercase tracking-widest">Live Update</span>
                    </div>

                    {{-- Quick Action Buttons --}}
                    <div class="flex gap-2">
                        <a href="{{ route('kas-masuk.create') }}" class="flex items-center gap-1 px-3 py-1 bg-brand-600 text-white rounded-lg text-[10px] font-bold shadow hover:bg-brand-700 transition">
                            <span class="material-symbols-rounded text-sm">add</span> Pemasukan
                        </a>
                        <a href="{{ route('kas-keluar.create') }}" class="flex items-center gap-1 px-3 py-1 bg-white border border-stone-200 text-stone-600 rounded-lg text-[10px] font-bold hover:bg-stone-50 transition">
                            <span class="material-symbols-rounded text-sm">remove</span> Pengeluaran
                        </a>
                    </div>
                </div>

                <h1 class="text-3xl sm:text-4xl font-black text-stone-800 tracking-tight leading-[1.1]">
                    {{ $greet }}, <br class="hidden sm:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-brand-400">
                        {{ Str::limit(Auth::user()->name, 15) }}
                    </span> 👋
                </h1>
                <p class="text-stone-500 font-medium mt-2 text-sm sm:text-base max-w-lg leading-relaxed">
                    Ringkasan performa bisnis <span class="font-bold text-stone-700">Teh Solo De Jumbo</span> periode ini.
                </p>
            </div>

            {{-- Filter Area (Floating Style) --}}
            <form method="GET" action="{{ route('dashboard') }}" id="filterForm" class="flex flex-wrap sm:flex-nowrap gap-3 w-full lg:w-auto relative z-20">
                <input type="hidden" name="bulan" id="bulanInput" value="{{ request('bulan') }}">
                <input type="hidden" name="tahun" id="tahunInput" value="{{ request('tahun', now()->year) }}">

                {{-- Dropdown Bulan --}}
                <div x-data="{ open: false }" class="relative w-full sm:w-48 group">
                    <button type="button" @click="open = !open" @click.outside="open = false"
                        class="w-full h-[50px] px-5 rounded-full bg-white shadow-soft border border-stone-100 hover:border-brand-300 text-stone-700 text-sm font-bold flex items-center justify-between transition-all duration-300 active:scale-95 group-hover:shadow-lg group-hover:shadow-brand-500/5">
                        <span class="flex items-center gap-2.5">
                            <span class="material-symbols-rounded text-brand-500 text-[20px]">calendar_month</span>
                            <span class="truncate">{{ request('bulan') ? \Carbon\Carbon::create()->month((int)request('bulan'))->translatedFormat('F') : 'Semua Bulan' }}</span>
                        </span>
                        <span class="material-symbols-rounded text-stone-400 text-[20px] transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                    </button>

                    <div x-show="open" x-cloak x-transition.origin.top.right
                         class="absolute top-full right-0 mt-2 w-full min-w-[180px] bg-white border border-stone-100 rounded-2xl shadow-xl shadow-stone-200/50 max-h-64 overflow-y-auto p-1.5 flex flex-col gap-0.5 z-50">
                        <button type="button" onclick="setFilter('bulanInput', '')" class="text-left px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-stone-50 text-stone-600 transition">
                            Semua Bulan
                        </button>
                        @foreach(range(1, 12) as $m)
                            <button type="button" onclick="setFilter('bulanInput', '{{ $m }}')"
                                    class="text-left px-4 py-2.5 rounded-xl text-xs font-bold flex justify-between items-center transition {{ request('bulan') == $m ? 'bg-brand-50 text-brand-600' : 'text-stone-600 hover:bg-stone-50' }}">
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                @if(request('bulan') == $m) <span class="material-symbols-rounded text-base font-bold">check</span> @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Dropdown Tahun --}}
                <div x-data="{ open: false }" class="relative w-full sm:w-36 group">
                    <button type="button" @click="open = !open" @click.outside="open = false"
                        class="w-full h-[50px] px-5 rounded-full bg-white shadow-soft border border-stone-100 hover:border-brand-300 text-stone-700 text-sm font-bold flex items-center justify-between transition-all duration-300 active:scale-95 group-hover:shadow-lg group-hover:shadow-brand-500/5">
                        <span class="flex items-center gap-2.5">
                            <span class="material-symbols-rounded text-brand-500 text-[20px]">history</span>
                            <span>{{ request('tahun', now()->year) }}</span>
                        </span>
                        <span class="material-symbols-rounded text-stone-400 text-[20px] transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                    </button>

                    <div x-show="open" x-cloak x-transition.origin.top.right
                         class="absolute top-full right-0 mt-2 w-full bg-white border border-stone-100 rounded-2xl shadow-xl shadow-stone-200/50 p-1.5 flex flex-col gap-0.5 z-50">
                        @foreach(range(now()->year, 2023) as $y)
                            <button type="button" onclick="setFilter('tahunInput', '{{ $y }}')"
                                    class="text-left px-4 py-2.5 rounded-xl text-xs font-bold flex justify-between items-center transition {{ request('tahun', now()->year) == $y ? 'bg-brand-50 text-brand-600' : 'text-stone-600 hover:bg-stone-50' }}">
                                {{ $y }}
                                @if(request('tahun', now()->year) == $y) <span class="material-symbols-rounded text-base font-bold">check</span> @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>

        {{-- ===============================================
             2. HERO BENTO GRID
             =============================================== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">

            {{-- Card 1: Saldo (Dark Theme) --}}
            <div class="relative bg-stone-900 rounded-[2rem] p-6 sm:p-8 shadow-xl shadow-stone-900/10 overflow-hidden group hover:-translate-y-1 transition-all duration-500 flex flex-col justify-between h-full min-h-[220px]">
                {{-- Background Effects --}}
                <div class="absolute top-[-20%] right-[-10%] w-[80%] h-[80%] bg-brand-500 rounded-full blur-[90px] opacity-20 group-hover:opacity-30 transition-opacity duration-700"></div>
                <div class="absolute bottom-[-10%] left-[-10%] w-[60%] h-[60%] bg-stone-700 rounded-full blur-[70px] opacity-30"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2.5 bg-white/10 rounded-2xl backdrop-blur-md border border-white/5 shadow-inner">
                            <span class="material-symbols-rounded text-brand-400 text-[24px]">account_balance_wallet</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-widest text-stone-400">Total Sisa Saldo</span>
                            <span class="text-[10px] text-stone-500 font-medium">Realtime</span>
                        </div>
                    </div>
                    <h2 class="text-4xl sm:text-[42px] font-black tracking-tight text-white drop-shadow-sm leading-none">
                        <span class="text-2xl text-stone-500 font-bold mr-0.5 align-top relative top-1">Rp</span>{{ number_format($saldoRealSaatIni, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="relative z-10 mt-6 pt-5 border-t border-white/10 flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-stone-400 font-bold uppercase tracking-wider mb-1">Keuntungan Bersih (Periode Ini)</span>
                        <div class="flex items-center gap-2">
                             <span class="text-sm font-bold {{ $surplusPeriode >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $surplusPeriode >= 0 ? '+' : '-' }} Rp {{ number_format(abs($surplusPeriode), 0, ',', '.') }}
                            </span>
                            <div class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide bg-white/10 backdrop-blur-sm border border-white/5 text-stone-300">
                                {{ $surplusPeriode >= 0 ? 'Laba' : 'Rugi' }}
                            </div>
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-full {{ $surplusPeriode >= 0 ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' }} flex items-center justify-center border border-white/5">
                        <span class="material-symbols-rounded text-xl">{{ $surplusPeriode >= 0 ? 'trending_up' : 'trending_down' }}</span>
                    </div>
                </div>
            </div>

            {{-- Card 2: Pemasukan (White) --}}
            <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-soft border border-stone-100 group relative overflow-hidden hover:shadow-lg hover:shadow-emerald-500/5 hover:-translate-y-1 transition-all duration-300">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-50 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700 opacity-60"></div>

                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <p class="text-stone-400 text-xs font-bold uppercase tracking-wider">Pemasukan</p>
                            </div>
                            <h3 class="text-3xl lg:text-3xl xl:text-4xl font-black text-stone-800 tracking-tight">
                                {{ number_format($totalMasuk, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-50 to-white text-emerald-600 flex items-center justify-center border border-emerald-100 shadow-sm group-hover:rotate-12 transition-transform duration-500">
                            <span class="material-symbols-rounded text-[28px]">arrow_downward</span>
                        </div>
                    </div>

                    <div class="mt-8 space-y-2">
                        <div class="flex items-center justify-between text-xs font-bold text-stone-600 bg-stone-50 p-2.5 rounded-xl border border-stone-100">
                            <span class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Tunai</span>
                            <span>{{ number_format($pemasukanTunai, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-bold text-stone-600 bg-stone-50 p-2.5 rounded-xl border border-stone-100">
                            <span class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>Non-Tunai</span>
                            <span>{{ number_format($pemasukanNonTunai, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: Pengeluaran (White) --}}
            <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-soft border border-stone-100 group relative overflow-hidden hover:shadow-lg hover:shadow-rose-500/5 hover:-translate-y-1 transition-all duration-300">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-rose-50 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700 opacity-60"></div>

                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                <p class="text-stone-400 text-xs font-bold uppercase tracking-wider">Pengeluaran</p>
                            </div>
                            <h3 class="text-3xl lg:text-3xl xl:text-4xl font-black text-stone-800 tracking-tight">
                                {{ number_format($totalKeluar, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-50 to-white text-rose-600 flex items-center justify-center border border-rose-100 shadow-sm group-hover:-rotate-12 transition-transform duration-500">
                            <span class="material-symbols-rounded text-[28px]">arrow_upward</span>
                        </div>
                    </div>

                    <div class="mt-8">
                         <div class="flex items-center gap-3 bg-rose-50 text-rose-700 px-4 py-3 rounded-2xl text-xs font-bold border border-rose-100">
                            <div class="bg-white p-1 rounded-full shadow-sm text-rose-500">
                                <span class="material-symbols-rounded text-lg block">receipt_long</span>
                            </div>
                            <span>{{ $countKeluar }} Transaksi Dicatat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===============================================
             3. MAIN CONTENT SPLIT (Chart & Timeline)
             =============================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

            {{-- LEFT: Chart (Lebih Lebar) --}}
            <div class="lg:col-span-2 bg-white rounded-[2rem] p-6 sm:p-8 shadow-soft border border-stone-100 relative overflow-hidden">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                    <div>
                        <h3 class="font-extrabold text-stone-800 text-xl tracking-tight flex items-center gap-2">
                            <span class="material-symbols-rounded text-brand-500">bar_chart</span>
                            Arus Kas Bulanan
                        </h3>
                        <p class="text-xs text-stone-400 font-medium mt-1 pl-8">Grafik perbandingan Pemasukan & Pengeluaran</p>
                    </div>
                    {{-- Legend Pills --}}
                    <div class="flex gap-2 bg-stone-50 p-1.5 rounded-full border border-stone-100">
                        <div class="px-3 py-1.5 rounded-full bg-white shadow-sm flex items-center gap-2 border border-stone-50">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                            <span class="text-[10px] font-bold text-stone-600 uppercase">Masuk</span>
                        </div>
                        <div class="px-3 py-1.5 rounded-full bg-white shadow-sm flex items-center gap-2 border border-stone-50">
                            <span class="w-2 h-2 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.5)]"></span>
                            <span class="text-[10px] font-bold text-stone-600 uppercase">Keluar</span>
                        </div>
                    </div>
                </div>

                {{-- Canvas Wrap (Fixed Height agar tidak layout shift) --}}
                <div class="relative w-full h-[320px]">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>

            {{-- RIGHT: Recent Activity (Timeline Style) --}}
            <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-soft border border-stone-100 flex flex-col h-[500px] lg:h-auto overflow-hidden relative">
                {{-- Header --}}
                <div class="flex justify-between items-center mb-6 shrink-0 relative z-10">
                    <h3 class="font-extrabold text-stone-800 text-lg tracking-tight flex items-center gap-2">
                        <span class="material-symbols-rounded text-brand-500">history</span>
                        Aktivitas Baru
                    </h3>
                    <a href="{{ route('laporan.index') }}" class="w-9 h-9 rounded-full bg-stone-50 text-stone-400 hover:bg-brand-50 hover:text-brand-600 flex items-center justify-center transition-all hover:scale-105 active:scale-95 border border-stone-100 hover:border-brand-200">
                        <span class="material-symbols-rounded text-lg">arrow_forward</span>
                    </a>
                </div>

                {{-- Fade Overlay Bottom --}}
                <div class="absolute bottom-0 left-0 w-full h-16 bg-gradient-to-t from-white to-transparent z-10 pointer-events-none rounded-b-[2rem]"></div>

                {{-- Timeline List --}}
                <div class="flex-1 overflow-y-auto pr-1 pb-6 relative z-0 scrollbar-hide">
                    <div class="absolute left-[19px] top-2 bottom-2 w-[2px] bg-stone-100"></div>

                    @forelse($recentActivity as $item)
                        <div class="flex gap-4 group relative mb-6 last:mb-0">
                            {{-- Timeline Dot --}}
                            <div class="relative shrink-0 w-10 h-10 flex items-start justify-center pt-1 z-10">
                                <div class="w-3 h-3 rounded-full {{ $item->type == 'in' ? 'bg-emerald-400 ring-emerald-100' : 'bg-rose-400 ring-rose-100' }} ring-4 shadow-sm bg-white border-2 border-white transition-transform group-hover:scale-110"></div>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 pr-2">
                                <div class="bg-white rounded-2xl p-3.5 border border-stone-100 shadow-[0_2px_15px_-4px_rgba(0,0,0,0.03)] group-hover:border-brand-200 group-hover:shadow-md transition-all duration-300">
                                    <div class="flex justify-between items-start mb-1">
                                        <span class="text-[10px] font-bold text-stone-400 uppercase tracking-wider bg-stone-50 px-2 py-0.5 rounded-md border border-stone-100">
                                            {{ \Carbon\Carbon::parse($item->date)->translatedFormat('H:i') }}
                                        </span>
                                        <span class="text-xs font-black {{ $item->type == 'in' ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $item->type == 'in' ? '+' : '-' }}{{ number_format($item->total, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <p class="text-xs font-bold text-stone-700 leading-snug line-clamp-1 group-hover:text-brand-700 transition-colors">
                                        {{ $item->kategori }}
                                    </p>
                                    <p class="text-[10px] text-stone-400 mt-1 truncate">{{ Str::limit($item->keterangan, 30) }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-stone-300 gap-3 opacity-60">
                            <div class="p-4 bg-stone-50 rounded-full">
                                <span class="material-symbols-rounded text-4xl">history_toggle_off</span>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-widest">Belum ada aktivitas</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ===============================================
             4. BOTTOM PIE CHARTS (Compact)
             =============================================== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            {{-- Pie Masuk --}}
            <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-soft border border-stone-100 flex flex-col sm:flex-row items-center gap-6 sm:gap-8">
                <div class="relative w-36 h-36 shrink-0">
                    <canvas id="pieMasuk"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <span class="material-symbols-rounded text-emerald-50 text-4xl">savings</span>
                    </div>
                </div>
                <div class="flex-1 w-full min-w-0">
                    <h4 class="font-bold text-stone-800 mb-4 flex items-center gap-2 text-xs uppercase tracking-widest">
                        <span class="w-1.5 h-4 rounded-full bg-emerald-500"></span>
                        Sumber Pemasukan
                    </h4>
                    <div id="legendMasuk" class="space-y-2 max-h-40 overflow-y-auto pr-1 scrollbar-hide"></div>
                </div>
            </div>

            {{-- Pie Keluar --}}
            <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-soft border border-stone-100 flex flex-col sm:flex-row items-center gap-6 sm:gap-8">
                <div class="relative w-36 h-36 shrink-0">
                    <canvas id="pieKeluar"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <span class="material-symbols-rounded text-rose-50 text-4xl">outbound</span>
                    </div>
                </div>
                <div class="flex-1 w-full min-w-0">
                    <h4 class="font-bold text-stone-800 mb-4 flex items-center gap-2 text-xs uppercase tracking-widest">
                        <span class="w-1.5 h-4 rounded-full bg-rose-500"></span>
                        Alokasi Pengeluaran
                    </h4>
                    <div id="legendKeluar" class="space-y-2 max-h-40 overflow-y-auto pr-1 scrollbar-hide"></div>
                </div>
            </div>
        </div>

    </div>

    {{-- SCRIPTS (Chart Logic) --}}
    <script>
        function setFilter(id, val) {
            document.getElementById(id).value = val;
            document.getElementById('filterForm').submit();
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Setup Font & Colors Global (Match Tailwind Config)
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#a8a29e'; // stone-400
            Chart.defaults.scale.grid.color = '#f5f5f4'; // stone-100

            // --- DATA ---
            const labels = {!! json_encode($labelList) !!};
            const dMasuk = {!! json_encode($dataMasuk) !!};
            const dKeluar = {!! json_encode($dataKeluar) !!};
            const dSaldo = {!! json_encode($saldoKumulatif) !!};

            const formatCurrency = (v) => new Intl.NumberFormat('id-ID', {style:'currency', currency:'IDR', minimumFractionDigits:0}).format(v);
            const formatCompact = (v) => {
                if(v >= 1e6) return (v/1e6).toFixed(1) + 'jt';
                if(v >= 1e3) return (v/1e3).toFixed(0) + 'rb';
                return v;
            };

            // --- MAIN CHART ---
            const ctxMain = document.getElementById('mainChart').getContext('2d');
            let gradientSaldo = ctxMain.createLinearGradient(0, 0, 0, 300);
            gradientSaldo.addColorStop(0, 'rgba(68, 64, 60, 0.1)'); // stone-700 low opacity
            gradientSaldo.addColorStop(1, 'rgba(68, 64, 60, 0)');

            new Chart(ctxMain, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            type: 'line',
                            label: 'Saldo',
                            data: dSaldo,
                            borderColor: '#57534e', // stone-600
                            borderWidth: 2,
                            borderDash: [5, 5],
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#57534e',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.4,
                            fill: true,
                            backgroundColor: gradientSaldo,
                            order: 1,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Masuk',
                            data: dMasuk,
                            backgroundColor: '#10b981', // emerald-500
                            hoverBackgroundColor: '#059669',
                            borderRadius: 6,
                            borderSkipped: false,
                            barPercentage: 0.6,
                            categoryPercentage: 0.7,
                            order: 2
                        },
                        {
                            label: 'Keluar',
                            data: dKeluar,
                            backgroundColor: '#f43f5e', // rose-500
                            hoverBackgroundColor: '#e11d48',
                            borderRadius: 6,
                            borderSkipped: false,
                            barPercentage: 0.6,
                            categoryPercentage: 0.7,
                            order: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#ffffff',
                            titleColor: '#1c1917',
                            bodyColor: '#57534e',
                            borderColor: '#e7e5e4',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: true,
                            boxPadding: 6,
                            titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: 'bold' },
                            bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
                            callbacks: { label: (c) => ' ' + c.dataset.label + ': ' + formatCurrency(c.parsed.y) }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: {size: 10, weight: 600}, color: '#a8a29e' } },
                        y: {
                            border: { display: false },
                            grid: { borderDash: [4, 4], drawBorder: false },
                            ticks: { callback: formatCompact, font: {size: 10, weight: 600}, padding: 10, color: '#a8a29e' }
                        }
                    }
                }
            });

            // --- PIE CHART LOGIC ---
            const createPie = (canvasId, legendId, labels, data, colors) => {
                const total = data.reduce((a,b)=>Number(a)+Number(b),0);
                const container = document.getElementById(legendId);
                const ctx = document.getElementById(canvasId).getContext('2d');

                new Chart(ctx, {
                    type: 'doughnut',
                    data: { labels, datasets: [{ data, backgroundColor: colors, borderWidth: 0, hoverOffset: 5 }] },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '78%',
                        plugins: { legend: { display: false }, tooltip: { enabled: false } }
                    }
                });

                if(total === 0) {
                    container.innerHTML = '<div class="text-stone-300 text-[10px] text-center italic py-2">Belum ada data</div>';
                    return;
                }

                let html = '';
                labels.forEach((lbl, i) => {
                    if(data[i] > 0) {
                        const pct = ((data[i]/total)*100).toFixed(0)+'%';
                        html += `
                        <div class="flex items-center justify-between p-2 hover:bg-stone-50 rounded-xl transition cursor-default group border border-transparent hover:border-stone-100">
                            <div class="flex items-center gap-2.5 overflow-hidden min-w-0">
                                <span class="w-2 h-2 rounded-full shrink-0" style="background:${colors[i%colors.length]}"></span>
                                <span class="text-[11px] font-bold text-stone-500 group-hover:text-stone-800 truncate">${lbl}</span>
                            </div>
                            <div class="text-right shrink-0 ml-2">
                                <span class="block text-[11px] font-black text-stone-800">${formatCompact(data[i])}</span>
                                <span class="block text-[9px] text-stone-400 font-bold">${pct}</span>
                            </div>
                        </div>`;
                    }
                });
                container.innerHTML = html;
            };

            const cIn = ['#10b981', '#34d399', '#6ee7b7', '#a7f3d0', '#059669'];
            const cOut = ['#f43f5e', '#fb7185', '#fda4af', '#fecdd3', '#e11d48'];

            // Perhatikan pemanggilan variabel Blade di sini
            createPie('pieMasuk', 'legendMasuk', {!! json_encode($masukLabel) !!}, {!! json_encode($masukNominal) !!}, cIn);
            createPie('pieKeluar', 'legendKeluar', {!! json_encode($keluarLabel) !!}, {!! json_encode($keluarNominal) !!}, cOut);
        });
    </script>
</x-app-layout>
