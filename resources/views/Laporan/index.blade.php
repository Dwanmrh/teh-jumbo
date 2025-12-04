<x-app-layout>
    {{-- Scripts untuk Auto Submit Filter --}}
    <script>
        function submitFilter() {
            document.getElementById('filterForm').submit();
        }
    </script>

    <div class="space-y-6 sm:space-y-8 relative">

        {{-- 1. HEADER & ACTIONS --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 md:gap-6 relative z-10">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight leading-tight">
                    Laporan Keuangan
                </h1>
                <p class="text-stone-500 text-sm mt-1.5 leading-relaxed max-w-lg">
                    Rekapitulasi arus kas, pemasukan, dan pengeluaran secara terperinci.
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('laporan.export.pdf', request()->all()) }}" target="_blank"
                   class="flex-1 sm:flex-none justify-center group flex items-center gap-2 bg-white border border-stone-200 text-stone-600 px-4 sm:px-5 py-2.5 rounded-xl hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 transition-all shadow-sm hover:shadow-md active:scale-95">
                    <span class="material-symbols-rounded text-[20px] sm:text-[22px] text-rose-500 group-hover:text-rose-600">picture_as_pdf</span>
                    <span class="text-sm font-bold">PDF</span>
                </a>
                <a href="{{ route('laporan.export.excel', request()->all()) }}" target="_blank"
                   class="flex-1 sm:flex-none justify-center group flex items-center gap-2 bg-white border border-stone-200 text-stone-600 px-4 sm:px-5 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 transition-all shadow-sm hover:shadow-md active:scale-95">
                    <span class="material-symbols-rounded text-[20px] sm:text-[22px] text-emerald-500 group-hover:text-emerald-600">table_view</span>
                    <span class="text-sm font-bold">Excel</span>
                </a>
            </div>
        </div>

        {{-- 2. FILTER BAR (STICKY & RESPONSIVE) --}}
        {{-- UPDATE: top position disesuaikan agar tidak tertutup nav bar desktop yang floating --}}
        <div class="sticky top-[20px] sm:top-[160px] z-30 transition-all duration-300">
            <div class="bg-white/80 backdrop-blur-xl p-2 rounded-2xl shadow-soft border border-white/50 ring-1 ring-black/5">
                <form method="GET" action="{{ route('laporan.index') }}" id="filterForm" class="flex flex-col sm:flex-row gap-2">

                    {{-- Icon Filter Visual (Desktop Only) --}}
                    <div class="hidden sm:flex items-center justify-center px-4 bg-stone-100/50 rounded-xl border border-stone-200/50 text-stone-400">
                        <span class="material-symbols-rounded">filter_list</span>
                    </div>

                    {{-- DROPDOWN BULAN --}}
                    <div x-data="{
                            open: false,
                            selected: '{{ $selectedBulan ?? '' }}',
                            label: '{{ $selectedBulan ? \Carbon\Carbon::create()->month((int)$selectedBulan)->translatedFormat('F') : 'Semua Bulan' }}'
                        }"
                        class="relative flex-1 group">

                        <input type="hidden" name="bulan" :value="selected">

                        <button @click="open = !open" @click.outside="open = false" type="button"
                            class="w-full pl-10 sm:pl-11 pr-4 sm:pr-10 py-2.5 bg-stone-50 border border-stone-200 text-stone-700 text-sm font-bold rounded-xl hover:bg-white focus:ring-2 focus:ring-brand-500/20 transition-all flex items-center justify-between shadow-sm group-hover:border-brand-300">
                            <span class="material-symbols-rounded absolute left-3 text-stone-400 text-[20px] group-hover:text-brand-500 transition-colors">calendar_month</span>
                            <span x-text="label" class="truncate"></span>
                            <span class="material-symbols-rounded absolute right-3 text-stone-400 text-[20px] transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak
                             class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-stone-100 z-50 max-h-60 overflow-y-auto p-1.5 custom-scrollbar ring-1 ring-black/5">
                            <button type="button" @click="selected = ''; $nextTick(() => submitFilter())"
                                class="w-full text-left px-3 py-2 rounded-lg text-sm font-bold text-stone-600 hover:bg-stone-50 flex justify-between items-center transition-colors"
                                :class="selected == '' ? 'bg-brand-50 text-brand-600' : ''">
                                <span>Semua Bulan</span>
                                <span x-show="selected == ''" class="material-symbols-rounded text-[18px]">check</span>
                            </button>
                            <div class="h-px bg-stone-100 my-1"></div>
                            @for ($i = 1; $i <= 12; $i++)
                                @php $namaBulan = \Carbon\Carbon::create()->month($i)->translatedFormat('F'); @endphp
                                <button type="button" @click="selected = '{{ $i }}'; $nextTick(() => submitFilter())"
                                    class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-stone-600 hover:bg-stone-50 flex justify-between items-center transition-colors"
                                    :class="selected == '{{ $i }}' ? 'bg-stone-100 text-brand-700 font-bold' : ''">
                                    <span>{{ $namaBulan }}</span>
                                    <span x-show="selected == '{{ $i }}'" class="material-symbols-rounded text-[18px]">check</span>
                                </button>
                            @endfor
                        </div>
                    </div>

                    {{-- DROPDOWN TAHUN --}}
                    @php
                        $currentYear = date('Y');
                        $manualYears = range($currentYear - 2, $currentYear + 1);
                        $dbYears = isset($listTahun) ? (array)$listTahun : [];
                        $allYears = array_unique(array_merge($dbYears, $manualYears));
                        rsort($allYears);
                    @endphp

                    <div x-data="{
                            open: false,
                            selected: '{{ $selectedTahun ?? '' }}',
                            label: '{{ $selectedTahun ? $selectedTahun : 'Semua Tahun' }}'
                        }"
                        class="relative flex-1 group">

                        <input type="hidden" name="tahun" :value="selected">

                        <button @click="open = !open" @click.outside="open = false" type="button"
                            class="w-full pl-10 sm:pl-11 pr-4 sm:pr-10 py-2.5 bg-stone-50 border border-stone-200 text-stone-700 text-sm font-bold rounded-xl hover:bg-white focus:ring-2 focus:ring-brand-500/20 transition-all flex items-center justify-between shadow-sm group-hover:border-brand-300">
                            <span class="material-symbols-rounded absolute left-3 text-stone-400 text-[20px] group-hover:text-brand-500 transition-colors">today</span>
                            <span x-text="label" class="truncate"></span>
                            <span class="material-symbols-rounded absolute right-3 text-stone-400 text-[20px] transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak
                             class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-stone-100 z-50 max-h-60 overflow-y-auto p-1.5 custom-scrollbar ring-1 ring-black/5">
                            <button type="button" @click="selected = ''; $nextTick(() => submitFilter())"
                                class="w-full text-left px-3 py-2 rounded-lg text-sm font-bold text-stone-600 hover:bg-stone-50 flex justify-between items-center transition-colors"
                                :class="selected == '' ? 'bg-brand-50 text-brand-600' : ''">
                                <span>Semua Tahun</span>
                                <span x-show="selected == ''" class="material-symbols-rounded text-[18px]">check</span>
                            </button>
                            <div class="h-px bg-stone-100 my-1"></div>
                            @foreach($allYears as $th)
                                <button type="button" @click="selected = '{{ $th }}'; $nextTick(() => submitFilter())"
                                    class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-stone-600 hover:bg-stone-50 flex justify-between items-center transition-colors"
                                    :class="selected == '{{ $th }}' ? 'bg-stone-100 text-brand-700 font-bold' : ''">
                                    <span>{{ $th }}</span>
                                    <span x-show="selected == '{{ $th }}'" class="material-symbols-rounded text-[18px]">check</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Reset Button --}}
                    @if(request('bulan') || (request('tahun') && request('tahun') != date('Y')))
                        <a href="{{ route('laporan.index') }}" class="flex items-center justify-center px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100 hover:border-rose-200 rounded-xl transition-all shadow-sm" title="Reset Filter">
                            <span class="material-symbols-rounded text-[20px]">restart_alt</span>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- 3. STATS GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 relative z-0">

            {{-- Card 1: Saldo Awal --}}
            <div class="bg-white p-5 sm:p-6 rounded-[1.5rem] shadow-soft border border-stone-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-stone-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="p-2 bg-stone-100 rounded-xl text-stone-500 group-hover:bg-stone-200 transition-colors">
                            <span class="material-symbols-rounded text-[20px]">account_balance_wallet</span>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Saldo Awal</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-stone-700 font-mono tracking-tight truncate">
                        <span class="text-base sm:text-lg text-stone-400 mr-0.5">Rp</span>{{ number_format($saldoAwal, 0, ',', '.') }}
                    </div>
                    <p class="text-[11px] text-stone-400 mt-2 font-medium">Bawaan periode lalu</p>
                </div>
            </div>

            {{-- Card 2: Pemasukan --}}
            <div class="bg-white p-5 sm:p-6 rounded-[1.5rem] shadow-soft border border-emerald-100/50 relative overflow-hidden group hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-500/5 transition-all duration-300">
                <div class="absolute right-0 top-0 p-4 opacity-0 group-hover:opacity-10 transition-opacity transform translate-x-2 -translate-y-2 group-hover:translate-x-0 group-hover:translate-y-0">
                    <span class="material-symbols-rounded text-8xl text-emerald-600">trending_up</span>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="p-2 bg-emerald-50 rounded-xl text-emerald-600 group-hover:bg-emerald-100 transition-colors">
                            <span class="material-symbols-rounded text-[20px]">arrow_downward</span>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600/70">Pemasukan</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-emerald-600 font-mono tracking-tight truncate">
                        <span class="text-base sm:text-lg text-emerald-400 mr-0.5">Rp</span>{{ number_format($totalMasuk, 0, ',', '.') }}
                    </div>
                    <div class="w-full bg-stone-100 h-1 mt-3 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full" style="width: 100%"></div>
                    </div>
                </div>
            </div>

            {{-- Card 3: Pengeluaran --}}
            <div class="bg-white p-5 sm:p-6 rounded-[1.5rem] shadow-soft border border-rose-100/50 relative overflow-hidden group hover:border-rose-200 hover:shadow-lg hover:shadow-rose-500/5 transition-all duration-300">
                <div class="absolute right-0 top-0 p-4 opacity-0 group-hover:opacity-10 transition-opacity transform translate-x-2 -translate-y-2 group-hover:translate-x-0 group-hover:translate-y-0">
                    <span class="material-symbols-rounded text-8xl text-rose-600">trending_down</span>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="p-2 bg-rose-50 rounded-xl text-rose-600 group-hover:bg-rose-100 transition-colors">
                            <span class="material-symbols-rounded text-[20px]">arrow_upward</span>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600/70">Pengeluaran</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-rose-600 font-mono tracking-tight truncate">
                        <span class="text-base sm:text-lg text-rose-400 mr-0.5">Rp</span>{{ number_format($totalKeluar, 0, ',', '.') }}
                    </div>
                     {{-- Simple bar chart visualization --}}
                     @php
                        $maxVal = max($totalMasuk, $totalKeluar, 1);
                        $percentKeluar = ($totalKeluar / $maxVal) * 100;
                     @endphp
                    <div class="w-full bg-stone-100 h-1 mt-3 rounded-full overflow-hidden">
                        <div class="bg-rose-500 h-full rounded-full" style="width: {{ $percentKeluar }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Card 4: Saldo Akhir (Dark Theme) --}}
            <div class="bg-stone-900 p-5 sm:p-6 rounded-[1.5rem] shadow-xl shadow-stone-300 relative overflow-hidden text-white group">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-brand-500 rounded-full blur-[60px] opacity-30 group-hover:opacity-50 transition-opacity duration-500"></div>
                <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-24 h-24 bg-blue-500 rounded-full blur-[50px] opacity-20 group-hover:opacity-40 transition-opacity duration-500"></div>

                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Saldo Akhir</span>
                            <span class="bg-white/10 text-white text-[10px] font-bold px-2 py-0.5 rounded border border-white/10 backdrop-blur-sm">
                                {{ $saldoAkhir >= 0 ? 'SURPLUS' : 'DEFISIT' }}
                            </span>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black text-white font-mono tracking-tight truncate">
                            <span class="text-lg text-stone-500 mr-1">Rp</span>{{ number_format($saldoAkhir, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-white/10 text-[10px] text-stone-400 flex items-center gap-2">
                        <span class="material-symbols-rounded text-[14px] text-brand-500">verified</span>
                        Data terupdate real-time.
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. DESKTOP TABLE VIEW --}}
        <div class="hidden md:block bg-white rounded-[2rem] shadow-soft border border-stone-200 overflow-hidden ring-1 ring-black/5">
            {{-- Table Header --}}
            <div class="px-8 py-6 border-b border-stone-100 flex justify-between items-center bg-stone-50/30 backdrop-blur-sm">
                <div class="flex items-center gap-4">
                    <div class="bg-brand-100 text-brand-600 p-2.5 rounded-xl">
                        <span class="material-symbols-rounded">receipt_long</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-stone-800">Mutasi Rekening</h3>
                        <p class="text-xs text-stone-500 font-medium">Riwayat transaksi masuk dan keluar</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-stone-400 uppercase bg-stone-50/80 border-b border-stone-100 font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4 pl-8">Tanggal</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-center">Kategori</th>
                            <th class="px-6 py-4 text-right text-emerald-600">Masuk</th>
                            <th class="px-6 py-4 text-right text-rose-600">Keluar</th>
                            <th class="px-6 py-4 text-right pr-8">Saldo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        {{-- Row Saldo Awal --}}
                        @if($saldoAwal != 0)
                        <tr class="bg-stone-50/50">
                            <td class="px-6 py-4 pl-8 text-stone-400 font-mono text-xs">-</td>
                            <td class="px-6 py-4 font-bold text-stone-600 italic">Saldo Awal Periode</td>
                            <td class="px-6 py-4 text-center">-</td>
                            <td class="px-6 py-4 text-right text-stone-300">-</td>
                            <td class="px-6 py-4 text-right text-stone-300">-</td>
                            <td class="px-6 py-4 text-right font-bold text-stone-700 font-mono pr-8">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</td>
                        </tr>
                        @endif

                        {{-- Data Rows --}}
                        @forelse($laporan as $item)
                        <tr class="hover:bg-stone-50 transition-colors group">
                            <td class="px-6 py-4 pl-8 whitespace-nowrap">
                                <div class="font-bold text-stone-700">{{ $item['tanggal']->format('d M Y') }}</div>
                                <div class="text-[10px] text-stone-400 font-mono mt-0.5 bg-stone-100 inline-block px-1.5 rounded">{{ $item['kode'] }}</div>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <div class="font-bold text-stone-800 line-clamp-1 group-hover:text-brand-600 transition-colors">{{ $item['keterangan'] }}</div>
                                @if($item['penerima'] != '-')
                                <div class="text-[11px] text-stone-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-rounded text-[12px]">person</span> {{ $item['penerima'] }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide border {{ $item['type'] == 'masuk' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">
                                    {{ $item['kategori'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-emerald-600 font-bold whitespace-nowrap bg-emerald-50/10 group-hover:bg-emerald-50/30 transition-colors">
                                {{ $item['masuk'] > 0 ? number_format($item['masuk'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-rose-600 font-bold whitespace-nowrap bg-rose-50/10 group-hover:bg-rose-50/30 transition-colors">
                                {{ $item['keluar'] > 0 ? number_format($item['keluar'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-stone-800 font-bold pr-8">
                                Rp {{ number_format($item['saldo'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center text-stone-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-stone-100 rounded-full flex items-center justify-center mb-4">
                                        <span class="material-symbols-rounded text-3xl opacity-50">inbox</span>
                                    </div>
                                    <p class="font-bold text-stone-500">Tidak ada transaksi ditemukan</p>
                                    <p class="text-xs mt-1">Coba ubah filter periode atau tambahkan transaksi baru.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 5. MOBILE LIST VIEW (Cards Stack) --}}
        <div class="md:hidden flex flex-col gap-3">

            {{-- Header List Mobile --}}
            <div class="flex items-center gap-2 px-1 mb-1">
                <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Riwayat Transaksi</span>
                <div class="h-px bg-stone-200 flex-1"></div>
            </div>

            @if($saldoAwal != 0)
            <div class="bg-stone-100/60 backdrop-blur-sm p-4 rounded-2xl border border-stone-200 flex justify-between items-center shadow-sm">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-rounded text-stone-400 text-lg">history</span>
                    <span class="text-xs font-bold text-stone-500 uppercase">Saldo Awal</span>
                </div>
                <span class="font-mono font-bold text-stone-700">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</span>
            </div>
            @endif

            @forelse($laporan as $item)
            <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-4 relative overflow-hidden active:scale-[0.98] transition-transform duration-200">
                {{-- Decorative Side Bar --}}
                <div class="absolute left-0 top-0 bottom-0 w-1 {{ $item['type'] == 'masuk' ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>

                <div class="flex justify-between items-start mb-2 pl-2">
                    <div class="flex-1 mr-2">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-bold text-white px-1.5 py-0.5 rounded shadow-sm {{ $item['type'] == 'masuk' ? 'bg-emerald-500 shadow-emerald-200' : 'bg-rose-500 shadow-rose-200' }}">
                                {{ $item['tanggal']->format('d M') }}
                            </span>
                            <span class="text-[10px] text-stone-400 font-mono tracking-tight">{{ $item['kode'] }}</span>
                        </div>
                        <div class="font-bold text-stone-800 text-sm leading-snug line-clamp-2">{{ $item['keterangan'] }}</div>
                        @if($item['penerima'] != '-')
                        <div class="text-[10px] text-stone-400 flex items-center gap-1 mt-1 truncate">
                            <span class="material-symbols-rounded text-[12px]">person</span> {{ $item['penerima'] }}
                        </div>
                        @endif
                    </div>

                    <div class="text-right">
                        @if($item['masuk'] > 0)
                            <div class="text-emerald-600 font-black text-base font-mono bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100 shadow-sm">
                                +{{ number_format($item['masuk'] / 1000, 0) }}k
                            </div>
                        @else
                            <div class="text-rose-600 font-black text-base font-mono bg-rose-50 px-2 py-1 rounded-lg border border-rose-100 shadow-sm">
                                -{{ number_format($item['keluar'] / 1000, 0) }}k
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex justify-between items-center pt-3 border-t border-stone-100 mt-2 pl-2">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border tracking-wide {{ $item['type'] == 'masuk' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">
                        {{ $item['kategori'] }}
                    </span>
                    <div class="flex items-center gap-1.5">
                        <span class="text-[10px] text-stone-400 font-medium">Saldo:</span>
                        <div class="text-right text-sm font-mono font-bold text-stone-700">
                            Rp {{ number_format($item['saldo'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 px-6 bg-white rounded-3xl border border-dashed border-stone-200">
                <div class="w-12 h-12 bg-stone-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-rounded text-stone-300 text-2xl">event_busy</span>
                </div>
                <p class="text-stone-400 text-sm">Belum ada data transaksi untuk periode ini.</p>
            </div>
            @endforelse

            {{-- Spacer for bottom nav mobile --}}
            <div class="h-20"></div>
        </div>

    </div>
</x-app-layout>
