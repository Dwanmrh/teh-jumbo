<x-app-layout>
    <x-slot name="title">Laporan Keuangan</x-slot>

    {{-- Script Handle Search & Filter --}}
    <script>
        function setLaporanFilter(inputId, value) {
            const input = document.getElementById(inputId);
            if (input) input.value = value;
            showLoadingAndSubmit();
        }

        function setDateFilter(dateVal) {
            // Reset bulan/tahun jika pilih tanggal spesifik
            document.getElementById('bulanInput').value = '';
            document.getElementById('tahunInput').value = '';

            // Create hidden input for date if not exists or update it
            let dateInput = document.querySelector('input[name="date"]');
            if(!dateInput) {
                dateInput = document.createElement('input');
                dateInput.type = 'hidden';
                dateInput.name = 'date';
                document.getElementById('filterForm').appendChild(dateInput);
            }
            dateInput.value = dateVal;
            showLoadingAndSubmit();
        }

        function showLoadingAndSubmit() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
            document.getElementById('filterForm').submit();
        }

        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            let searchTimeout;
            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        showLoadingAndSubmit();
                    }, 800);
                });
            }
        });
    </script>

    {{-- Loading Overlay --}}
    <div id="loadingOverlay" class="hidden fixed inset-0 z-[150]">
        <div class="h-full w-full bg-white/60 backdrop-blur-sm flex items-center justify-center">
            <div class="flex flex-col items-center gap-3">
                <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-brand-500"></div>
                <span class="text-xs font-bold text-brand-600 animate-pulse">Memuat Data...</span>
            </div>
        </div>
    </div>

    <div class="space-y-6 sm:space-y-8 relative">

        {{-- 1. HEADER & EXPORT BUTTONS --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-800 tracking-tight">
                    Laporan Penjualan
                </h1>
                <p class="text-stone-500 text-sm mt-1 leading-relaxed max-w-xl font-medium">
                    Pantau performa outlet, arus kas, dan stok bahan secara <i>real-time</i>.
                </p>
            </div>

            <div class="flex gap-3 w-full md:w-auto">
                {{-- Tombol PDF --}}
                <a href="{{ route('laporan.export.pdf', request()->all()) }}" target="_blank"
                    class="flex-1 md:flex-none justify-center bg-white border border-stone-200 text-rose-600 px-5 py-3 rounded-2xl font-bold text-sm hover:bg-rose-50 hover:border-rose-100 hover:shadow-lg hover:shadow-rose-100/50 transition-all flex items-center gap-2 group active:scale-95">
                    <span class="material-symbols-rounded group-hover:scale-110 transition-transform">picture_as_pdf</span>
                    <span>PDF</span>
                </a>
                {{-- Tombol Excel --}}
                <a href="{{ route('laporan.export.excel', request()->all()) }}" target="_blank"
                    class="flex-1 md:flex-none justify-center bg-white border border-stone-200 text-emerald-600 px-5 py-3 rounded-2xl font-bold text-sm hover:bg-emerald-50 hover:border-emerald-100 hover:shadow-lg hover:shadow-emerald-100/50 transition-all flex items-center gap-2 group active:scale-95">
                    <span class="material-symbols-rounded group-hover:scale-110 transition-transform">table_view</span>
                    <span>Excel</span>
                </a>
            </div>
        </div>

        {{-- 2. FILTER & SEARCH BAR --}}
        <form method="GET" action="{{ route('laporan.index') }}" id="filterForm"
            class="bg-white p-2 sm:p-3 rounded-[24px] shadow-soft border border-stone-200 sticky top-[80px] md:top-[90px] z-30 transition-all ring-1 ring-stone-900/5">

            <div class="flex flex-col md:flex-row gap-2">
                {{-- A. SEARCH INPUT --}}
                <div class="relative flex-1 w-full group">
                    <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 group-focus-within:text-brand-500 transition-colors">search</span>
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                        placeholder="Cari transaksi atau nominal..."
                        class="w-full pl-11 pr-4 py-3 rounded-xl border-none bg-stone-50 focus:bg-white focus:ring-2 focus:ring-brand-200 text-stone-700 text-sm font-bold transition-all placeholder:text-stone-400">
                </div>

                <div class="grid grid-cols-2 md:flex gap-2 w-full md:w-auto items-center">
                    {{-- Quick Filter Presets (Hari Ini / Bulan Ini) --}}
                    <div class="hidden md:flex items-center gap-1 bg-stone-50 p-1 rounded-xl border border-stone-100 mr-1">
                        <button type="button" onclick="setDateFilter('{{ date('Y-m-d') }}')"
                           class="px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ request('date') == date('Y-m-d') ? 'bg-white shadow text-brand-600' : 'text-stone-500 hover:text-brand-600' }}">
                           Hari Ini
                        </button>
                        <button type="button" onclick="setLaporanFilter('bulanInput', '{{ date('m') }}')"
                           class="px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ !request('date') && (request('bulan') == date('m') || !$selectedBulan) ? 'bg-white shadow text-brand-600' : 'text-stone-500 hover:text-brand-600' }}">
                           Bulan Ini
                        </button>
                    </div>

                    {{-- B. FILTER BULAN --}}
                    <div class="relative col-span-1 md:w-40" x-data="{ open: false }" @click.outside="open = false">
                        <input type="hidden" name="bulan" id="bulanInput" value="{{ $selectedBulan }}">
                        <button type="button" @click="open = !open"
                            class="w-full h-full px-4 py-3 rounded-xl bg-stone-50 hover:bg-stone-100 text-stone-600 hover:text-stone-800 text-sm font-bold flex items-center justify-between gap-2 transition-colors relative text-left group">
                            <span class="truncate">
                                {{ $selectedBulan ? \Carbon\Carbon::create()->month($selectedBulan)->translatedFormat('F') : 'Semua Bln' }}
                            </span>
                            <span class="material-symbols-rounded text-stone-400 group-hover:text-stone-600 transition-colors">expand_more</span>
                        </button>
                        <div x-cloak x-show="open"
                            class="absolute top-full right-0 mt-2 w-[180px] bg-white border border-stone-100 rounded-2xl shadow-xl z-50 p-1.5 max-h-[300px] overflow-y-auto ring-1 ring-stone-900/5">
                            <button type="button" onclick="setLaporanFilter('bulanInput', '')" class="text-left w-full px-3 py-2.5 rounded-xl text-xs font-bold text-stone-500 hover:bg-brand-50 hover:text-brand-700 transition-colors">Semua Bulan</button>
                            @for ($i = 1; $i <= 12; $i++)
                                <button type="button" onclick="setLaporanFilter('bulanInput', '{{ $i }}')"
                                    class="text-left w-full px-3 py-2.5 rounded-xl text-xs font-bold hover:bg-brand-50 hover:text-brand-700 transition-colors {{ $selectedBulan == $i ? 'bg-brand-50 text-brand-700' : 'text-stone-600' }}">
                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                </button>
                            @endfor
                        </div>
                    </div>

                    {{-- C. FILTER TAHUN --}}
                    <div class="relative col-span-1 md:w-28" x-data="{ open: false }" @click.outside="open = false">
                        <input type="hidden" name="tahun" id="tahunInput" value="{{ $selectedTahun }}">
                        <button type="button" @click="open = !open"
                            class="w-full h-full px-4 py-3 rounded-xl bg-stone-50 hover:bg-stone-100 text-stone-600 hover:text-stone-800 text-sm font-bold flex items-center justify-between gap-2 transition-colors">
                            <span>{{ $selectedTahun ?? 'Thn' }}</span>
                            <span class="material-symbols-rounded text-stone-400">expand_more</span>
                        </button>
                        <div x-cloak x-show="open"
                             class="absolute top-full right-0 mt-2 w-32 bg-white border border-stone-100 rounded-2xl shadow-xl z-50 p-1.5 ring-1 ring-stone-900/5">
                            <button type="button" onclick="setLaporanFilter('tahunInput', '')" class="text-left w-full px-3 py-2.5 rounded-xl text-xs font-bold text-stone-500 hover:bg-brand-50 hover:text-brand-700">Semua</button>
                            @foreach(($listTahun ?? [date('Y')]) as $th)
                                <button type="button" onclick="setLaporanFilter('tahunInput', '{{ $th }}')"
                                    class="text-left w-full px-3 py-2.5 rounded-xl text-xs font-bold hover:bg-brand-50 hover:text-brand-700 transition-colors {{ $selectedTahun == $th ? 'bg-brand-50 text-brand-700' : 'text-stone-600' }}">
                                    {{ $th }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- D. TOMBOL RESET --}}
                    @if(request('bulan') || request('tahun') || request('search') || request('date'))
                        <a href="{{ route('laporan.index') }}"
                           class="col-span-2 md:col-auto h-11 md:h-auto md:aspect-square flex items-center justify-center rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 border border-rose-100 transition-colors"
                           title="Reset Filter">
                            <span class="material-symbols-rounded">restart_alt</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Badge Info jika Filter Harian Aktif --}}
            @if(request('date'))
            <div class="mt-2 text-xs text-stone-500 font-medium flex items-center gap-1 pl-4">
                <span class="material-symbols-rounded text-sm text-brand-500">event_available</span>
                Menampilkan data tanggal: <span class="font-bold text-stone-700">{{ \Carbon\Carbon::parse(request('date'))->translatedFormat('l, d F Y') }}</span>
            </div>
            @endif
        </form>

        {{-- 3. RINGKASAN KEUANGAN (Cards) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-6">

            {{-- B. Total Omzet (Pemasukan) --}}
            <div class="bg-gradient-to-br from-emerald-50/80 to-white p-5 rounded-[24px] border border-emerald-100/60 shadow-soft relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-400/10 rounded-full blur-2xl -mr-8 -mt-8"></div>
                <div class="flex items-center gap-2 mb-2 relative z-10">
                    <div class="p-1.5 bg-emerald-100 text-emerald-600 rounded-lg">
                        <span class="material-symbols-rounded text-[18px]">payments</span>
                    </div>
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wide">Total Omzet</span>
                </div>
                <div class="text-xl sm:text-2xl font-black text-stone-800 break-words mb-3 relative z-10">
                    Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                </div>
                <div class="space-y-1 pt-2 border-t border-emerald-100/50 relative z-10">
                    <div class="flex justify-between text-xs">
                        <span class="text-stone-500 font-medium">Tunai</span>
                        <span class="font-bold text-stone-700">Rp {{ number_format($totalMasukCash, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-stone-500 font-medium">Non-Tunai</span>
                        <span class="font-bold text-stone-700">Rp {{ number_format($totalMasukNonCash, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- C. Pengeluaran --}}
            <div class="bg-gradient-to-br from-rose-50/80 to-white p-5 rounded-[24px] border border-rose-100/60 shadow-soft relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-rose-400/10 rounded-full blur-2xl -mr-8 -mt-8"></div>
                <div class="flex items-center gap-2 mb-4 relative z-10">
                    <div class="p-1.5 bg-rose-100 text-rose-600 rounded-lg">
                        <span class="material-symbols-rounded text-[18px]">shopping_cart</span>
                    </div>
                    <span class="text-xs font-bold text-rose-700 uppercase tracking-wide">Pengeluaran</span>
                </div>
                <div class="text-xl sm:text-2xl font-black text-stone-800 break-words relative z-10">
                    Rp {{ number_format($totalKeluar, 0, ',', '.') }}
                </div>
                <p class="text-[10px] text-stone-400 mt-2 font-medium relative z-10">Belanja bahan & operasional (Tunai)</p>
            </div>

            {{-- D. Sisa Uang Fisik (REVISI: LOGIKA LACI KASIR) --}}
            <div class="bg-stone-900 p-5 rounded-[24px] shadow-xl shadow-stone-900/10 text-white relative overflow-hidden ring-1 ring-black/5">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-brand-500 rounded-full blur-[50px] opacity-30 pointer-events-none"></div>

                <div class="relative z-10 h-full flex flex-col justify-between gap-3">
                    <div class="flex justify-between items-start">
                        <div class="text-[10px] font-bold text-brand-400 uppercase tracking-widest mb-1 flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm">point_of_sale</span>
                            <span>UANG DI LACI</span>
                        </div>
                        {{-- Tooltip Info --}}
                        <div class="group relative">
                            <span class="material-symbols-rounded text-stone-600 text-sm cursor-help hover:text-stone-400">help</span>
                            <div class="absolute right-0 w-48 p-2 bg-stone-800 text-[10px] text-stone-300 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none border border-stone-700 z-50">
                                Estimasi uang fisik. Tidak termasuk QRIS/Transfer.
                            </div>
                        </div>
                    </div>

                    <div class="text-3xl font-black tracking-tight text-white break-words">
                        Rp {{ number_format($sisaUangFisik, 0, ',', '.') }}
                    </div>

                    <div class="flex flex-col gap-1 pt-3 border-t border-stone-800">
                        <div class="flex justify-between text-[10px] text-stone-400">
                           <span>Masuk (Tunai):</span>
                           <span class="text-emerald-400 font-bold">+Rp {{ number_format($totalMasukCash, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[10px] text-stone-400">
                           <span>Keluar (Tunai):</span>
                           <span class="text-rose-400 font-bold">-Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[10px] text-stone-400 mt-1">
                           <span>Info Saldo Akhir (All):</span>
                           <span class="text-stone-300 font-bold">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</span>
                        </div>
                   </div>
                </div>
            </div>
        </div>

        {{-- 4. ANALISA PRODUK & GUDANG --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Inventory --}}
            <div class="bg-white rounded-[24px] p-6 border border-stone-200 shadow-soft flex flex-col h-full">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-2xl">
                        <span class="material-symbols-rounded">inventory_2</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-stone-800 text-lg leading-tight">Stok Bahan</h3>
                        <p class="text-[11px] text-stone-400 font-medium">Monitoring ketersediaan gudang</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="p-4 bg-stone-50 rounded-2xl border border-stone-100 text-center">
                        <div class="text-2xl font-black text-stone-700">{{ $productStats['inventory']['total_sku'] }}</div>
                        <div class="text-[10px] text-stone-400 uppercase font-bold tracking-wide mt-1">Varian</div>
                    </div>
                    <div class="p-4 bg-stone-50 rounded-2xl border border-stone-100 text-center">
                        <div class="text-2xl font-black text-indigo-600">{{ $productStats['inventory']['total_stok'] }}</div>
                        <div class="text-[10px] text-stone-400 uppercase font-bold tracking-wide mt-1">Total Unit</div>
                    </div>
                </div>
                <div class="mt-auto">
                    @if($productStats['inventory']['low_stock'] > 0)
                        <div class="flex items-center gap-3 px-4 py-3 bg-rose-50 text-rose-700 rounded-2xl text-xs font-bold border border-rose-100">
                            <span class="material-symbols-rounded text-[18px]">warning</span>
                            <span>{{ $productStats['inventory']['low_stock'] }} Barang hampir habis (< 10)</span>
                        </div>
                    @else
                        <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 text-emerald-700 rounded-2xl text-xs font-bold border border-emerald-100">
                            <span class="material-symbols-rounded text-[18px]">check_circle</span>
                            <span>Stok aman terkendali</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Top Sales --}}
            <div class="lg:col-span-2 bg-white rounded-[24px] p-6 border border-stone-200 shadow-soft">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2.5 bg-brand-50 text-brand-600 rounded-2xl">
                        <span class="material-symbols-rounded">stars</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-stone-800 text-lg leading-tight">Menu Terlaris</h3>
                        <p class="text-[11px] text-stone-400 font-medium">Top 5 Produk periode ini</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @forelse($productStats['top_products'] as $name => $stat)
                        <div class="flex flex-col p-4 rounded-2xl border border-stone-100 bg-white hover:border-brand-200 hover:shadow-lg hover:shadow-brand-100/30 transition-all group">
                            <div class="flex justify-between mb-2">
                                <div class="w-8 h-8 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-xs group-hover:bg-brand-500 group-hover:text-white transition-colors">
                                    {{ substr($name, 0, 1) }}
                                </div>
                                <div class="text-right">
                                    <div class="text-[10px] font-bold text-stone-400 uppercase tracking-wide">Terjual</div>
                                    <div class="font-black text-lg text-stone-800">{{ $stat['qty'] }}</div>
                                </div>
                            </div>
                            <div class="font-bold text-stone-700 text-sm line-clamp-2 h-10 flex items-center">{{ $name }}</div>
                            <div class="mt-3 h-1.5 w-full bg-stone-100 rounded-full overflow-hidden">
                                <div class="h-full bg-brand-500 rounded-full group-hover:bg-brand-600 transition-colors" style="width: {{ rand(40, 95) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center border-2 border-dashed border-stone-100 rounded-2xl">
                            <div class="text-stone-300 mb-2"><span class="material-symbols-rounded text-4xl">shopping_bag</span></div>
                            <div class="text-stone-400 text-sm font-medium">Belum ada penjualan.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- 5. ADMIN SECTION (Outlet & Staff) --}}
        @if(Auth::user()->role === 'admin')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Outlet Performance --}}
                <div class="bg-white rounded-[24px] border border-stone-200 shadow-soft p-6">
                    <div class="flex items-center gap-3 mb-4 border-b border-stone-100 pb-4">
                        <div class="p-2 bg-purple-100 text-purple-600 rounded-xl"><span class="material-symbols-rounded">store</span></div>
                        <h3 class="font-bold text-stone-800 text-lg">Performa Cabang</h3>
                    </div>
                    <div class="space-y-3">
                        @forelse($outletStats as $outlet)
                            <div class="flex justify-between items-center p-3.5 bg-stone-50 rounded-2xl hover:bg-white hover:shadow-md border border-transparent hover:border-purple-100 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-white border border-stone-200 flex items-center justify-center text-stone-400 font-bold text-xs group-hover:border-purple-200 group-hover:text-purple-600">
                                        {{ substr($outlet['name'], 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-stone-800 text-sm">{{ $outlet['name'] }}</div>
                                        <div class="text-xs text-stone-500 font-medium">{{ $outlet['trx_count'] }} Transaksi</div>
                                    </div>
                                </div>
                                <div class="font-mono font-bold text-purple-700 bg-purple-50 px-3 py-1 rounded-lg">Rp {{ number_format($outlet['omzet'], 0, ',', '.') }}</div>
                            </div>
                        @empty
                            <div class="text-center text-stone-400 text-sm py-4">Tidak ada data cabang.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Staff Performance --}}
                <div class="bg-white rounded-[24px] border border-stone-200 shadow-soft p-6">
                    <div class="flex items-center gap-3 mb-4 border-b border-stone-100 pb-4">
                        <div class="p-2 bg-blue-100 text-blue-600 rounded-xl"><span class="material-symbols-rounded">badge</span></div>
                        <h3 class="font-bold text-stone-800 text-lg">Kinerja Pegawai</h3>
                    </div>
                    <div class="overflow-y-auto max-h-[350px] pr-2 custom-scrollbar">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-stone-400 uppercase font-bold sticky top-0 bg-white/95 backdrop-blur-sm z-10">
                                <tr>
                                    <th class="pb-3 pl-2">Nama</th>
                                    <th class="pb-3 text-right">Nota</th>
                                    <th class="pb-3 text-right">Omzet</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-50">
                                @forelse($userStats as $u)
                                    <tr class="group">
                                        <td class="py-3 pl-2">
                                            <div class="font-bold text-stone-700 group-hover:text-blue-600 transition-colors">{{ $u['name'] }}</div>
                                            <div class="text-[10px] text-stone-400 font-medium">{{ $u['outlet'] }}</div>
                                        </td>
                                        <td class="py-3 text-right">
                                            <span class="bg-stone-100 px-2 py-1 rounded-md text-xs font-bold text-stone-600">{{ $u['trx_count'] }}</span>
                                        </td>
                                        <td class="py-3 text-right font-mono text-stone-700 font-bold">Rp {{ number_format($u['omzet'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-6 text-stone-400">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- 6. TABEL MUTASI (Buku Kas) --}}
        <div class="bg-white rounded-[24px] border border-stone-200 shadow-soft overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-stone-100 bg-stone-50/50 flex items-center gap-3 backdrop-blur-sm">
                <div class="p-2 bg-stone-200 text-stone-600 rounded-xl"><span class="material-symbols-rounded">receipt_long</span></div>
                <div>
                    <h3 class="font-bold text-stone-800 text-lg leading-tight">Buku Kas Harian</h3>
                    <p class="text-[11px] text-stone-500 font-medium">Rincian uang masuk & keluar</p>
                </div>
            </div>

            {{-- DESKTOP TABLE --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-stone-50 text-stone-500 font-bold border-b border-stone-200 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 w-32">Tanggal</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-center">Metode</th>
                            <th class="px-6 py-4 text-right text-emerald-600 bg-emerald-50/30">Masuk (+)</th>
                            <th class="px-6 py-4 text-right text-rose-600 bg-rose-50/30">Keluar (-)</th>
                            <th class="px-6 py-4 text-right w-40">Saldo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($laporan as $item)
                        <tr class="hover:bg-stone-50 transition-colors group">
                            <td class="px-6 py-4 align-top">
                                <div class="font-bold text-stone-700">{{ $item['tanggal']->format('d M') }}</div>
                                <div class="text-[10px] text-stone-400 font-mono mt-0.5">{{ $item['kode'] }}</div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="text-stone-800 font-bold text-sm group-hover:text-brand-700 transition-colors">{{ $item['keterangan'] }}</div>
                                <div class="inline-flex mt-1.5 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wide border {{ $item['type'] == 'masuk' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">
                                    {{ $item['kategori'] }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center align-top">
                                @if($item['type'] == 'masuk')
                                    @php $method = strtolower($item['payment']); @endphp
                                    @if(in_array($method, ['tunai', 'cash']))
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-stone-100 text-stone-600 text-[10px] font-bold border border-stone-200">
                                            <span class="material-symbols-rounded text-[12px]">payments</span> Tunai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold border border-blue-100">
                                            <span class="material-symbols-rounded text-[12px]">qr_code</span> {{ $item['payment'] }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-stone-300 font-bold text-lg">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-emerald-600 bg-emerald-50/5 align-top">
                                {{ $item['masuk'] > 0 ? number_format($item['masuk'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-rose-600 bg-rose-50/5 align-top">
                                {{ $item['keluar'] > 0 ? number_format($item['keluar'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-stone-800 bg-stone-50/30 align-top">
                                Rp {{ number_format($item['saldo'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center border-dashed">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <span class="material-symbols-rounded text-4xl text-stone-300">receipt_long</span>
                                    <span class="text-stone-400 font-medium">Belum ada transaksi pada periode ini.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- MOBILE LIST VIEW --}}
            <div class="md:hidden divide-y divide-stone-100">
                @forelse($laporan as $item)
                <div class="p-4 relative hover:bg-stone-50 transition-colors">
                    <div class="flex gap-4">
                        {{-- Icon Indicator --}}
                        <div class="mt-0.5">
                            @php $method = strtolower($item['payment']); @endphp
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center border shadow-sm {{ $item['type'] == 'masuk' ? 'bg-emerald-50 border-emerald-100 text-emerald-600' : 'bg-rose-50 border-rose-100 text-rose-600' }}">
                                <span class="material-symbols-rounded text-[24px]">
                                    {{ $item['type'] == 'masuk' ? (in_array($method, ['tunai', 'cash']) ? 'payments' : 'qr_code') : 'shopping_cart' }}
                                </span>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-2">
                                <div>
                                    <div class="text-[10px] font-bold text-stone-400 uppercase tracking-wider mb-0.5">
                                        {{ $item['tanggal']->format('d M') }} &bull; {{ $item['tanggal']->format('H:i') }}
                                    </div>
                                    <div class="font-bold text-stone-800 text-sm leading-tight line-clamp-2">{{ $item['keterangan'] }}</div>
                                </div>
                                <div class="text-right whitespace-nowrap">
                                    @if($item['masuk'] > 0)
                                        <div class="text-emerald-600 font-black font-mono text-sm">+{{ number_format($item['masuk'], 0, ',', '.') }}</div>
                                    @else
                                        <div class="text-rose-600 font-black font-mono text-sm">-{{ number_format($item['keluar'], 0, ',', '.') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex justify-between items-end mt-2">
                                <div class="flex flex-wrap gap-1.5">
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold border bg-white border-stone-200 text-stone-500 uppercase">
                                        {{ $item['kategori'] }}
                                    </span>
                                    @if($item['type'] == 'masuk' && !in_array(strtolower($item['payment']), ['tunai', 'cash']))
                                         <span class="px-1.5 py-0.5 rounded text-[10px] font-bold border bg-blue-50 border-blue-100 text-blue-600 uppercase">
                                            {{ $item['payment'] }}
                                        </span>
                                    @endif
                                </div>
                                <div class="text-[10px] text-stone-500 font-medium">
                                    Saldo: <span class="font-mono text-stone-700 font-bold">Rp {{ number_format($item['saldo'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-10 text-center flex flex-col items-center justify-center gap-3">
                     <div class="w-16 h-16 bg-stone-50 rounded-full flex items-center justify-center text-stone-300">
                        <span class="material-symbols-rounded text-3xl">receipt_long</span>
                    </div>
                    <div class="text-stone-400 text-sm font-medium">Belum ada transaksi.</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
