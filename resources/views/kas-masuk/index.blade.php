<x-app-layout>
    <x-slot name="title">Kas Masuk</x-slot>

    {{-- =====================================================================
         LIBRARIES & META
         ===================================================================== --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- =====================================================================
         MAIN CONTAINER
         ===================================================================== --}}
    <div class="min-h-screen pb-32 sm:pb-20" x-data="{ showDetail: false, selectedItem: {} }">

        {{-- 1. HEADER & ACTIONS --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-6 animate-[fadeIn_0.3s_ease-out]">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    <p class="text-xs font-bold tracking-widest text-emerald-600 uppercase">Keuangan</p>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-stone-900 tracking-tight leading-tight">
                    Kas <span class="text-emerald-500">Masuk</span>
                </h1>
                <p class="text-stone-500 text-sm mt-2 max-w-lg leading-relaxed font-medium">
                    Pantau arus kas masuk secara realtime. Data yang ditampilkan adalah akumulasi dari semua transaksi.
                </p>
            </div>

            @if(Auth::user()->role === 'admin')
                <div class="flex gap-3 w-full md:w-auto">
                    <x-action-button
                        href="{{ route('kas-masuk.create') }}"
                        label="Catat Pemasukan"
                        icon="add"
                        id="floatingAddBtn"
                        class="z-30"
                    />
                </div>
            @endif
        </div>

        {{-- 2. STATS CARD --}}
        @php
            if(Auth::user()->role === 'admin') {
                $totalKasGlobal = \App\Models\KasMasuk::sum('total');
                $jumlahTransaksiGlobal = \App\Models\KasMasuk::count();
            } else {
                $totalKasGlobal = \App\Models\KasMasuk::where('user_id', Auth::id())->sum('total');
                $jumlahTransaksiGlobal = \App\Models\KasMasuk::where('user_id', Auth::id())->count();
            }
        @endphp

        <div class="mb-8 animate-[fadeIn_0.4s_ease-out]">
            <div class="relative w-full bg-gradient-to-br from-emerald-500 to-teal-700 rounded-[2.5rem] p-6 md:p-10 shadow-2xl shadow-emerald-900/10 overflow-hidden group border border-emerald-400/20">
                <div class="absolute top-0 right-0 -mt-16 -mr-16 w-64 h-64 bg-emerald-300/20 rounded-full blur-[80px] group-hover:bg-emerald-300/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-56 h-56 bg-teal-900/20 rounded-full blur-[80px]"></div>
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] mix-blend-overlay"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="bg-white/20 backdrop-blur-md border border-white/20 text-white px-3 py-1 rounded-full text-[10px] md:text-xs font-extrabold uppercase tracking-wider shadow-sm">
                                Total Akumulasi (Global)
                            </span>
                        </div>
                        <h2 class="text-4xl md:text-6xl font-black text-white tracking-tighter drop-shadow-sm flex items-start gap-1">
                            <span class="text-emerald-100/80 text-lg md:text-3xl font-bold mt-1.5 md:mt-3">Rp</span>
                            {{ number_format($totalKasGlobal, 0, ',', '.') }}
                        </h2>
                        <div class="mt-4 flex items-center gap-3">
                            <div class="flex -space-x-2">
                                <div class="w-8 h-8 rounded-full bg-emerald-200 border-2 border-emerald-600 flex items-center justify-center text-[10px] font-bold text-emerald-800">
                                    <span class="material-symbols-rounded text-base">receipt</span>
                                </div>
                            </div>
                            <p class="text-emerald-50 font-medium text-sm md:text-base">
                                <span class="font-bold text-white">{{ $jumlahTransaksiGlobal }}</span> Transaksi tersimpan
                            </p>
                        </div>
                    </div>
                    <div class="hidden md:flex h-20 w-20 bg-white/10 rounded-3xl backdrop-blur-md border border-white/20 shadow-inner items-center justify-center transform group-hover:scale-110 transition-transform duration-500">
                        <span class="material-symbols-rounded text-5xl text-white drop-shadow-md">trending_up</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. FILTER & SEARCH BAR (Sticky z-40) --}}
        <form method="GET" action="{{ route('kas-masuk.index') }}" id="filterForm"
              class="sticky top-[80px] md:top-[170px] z-40 mb-8 animate-[fadeIn_0.5s_ease-out]">

            <div class="bg-white/80 backdrop-blur-xl p-3 sm:p-4 rounded-[2rem] shadow-soft border border-stone-200/60 ring-1 ring-stone-900/5 transition-all duration-300">
                <div class="flex flex-col md:flex-row gap-3">

                    {{-- Search Input --}}
                    <div class="relative flex-1 w-full group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 bg-stone-100 rounded-full p-1 transition-colors group-focus-within:bg-emerald-50">
                            <span class="material-symbols-rounded text-stone-400 text-lg group-focus-within:text-emerald-600">search</span>
                        </div>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                               placeholder="Cari kode, nominal, atau keterangan..."
                               class="w-full pl-12 pr-5 rounded-[1.5rem] border-0 bg-stone-50 focus:bg-white focus:ring-2 focus:ring-emerald-500/30 text-stone-700 text-sm font-semibold placeholder:text-stone-400 transition-all shadow-inner h-[52px]">
                    </div>

                    {{-- Filter Group --}}
                    {{-- Grid Layout untuk Mobile & Desktop --}}
                    <div class="grid grid-cols-2 md:grid-cols-[auto_auto_auto_auto_52px] gap-2 md:gap-3 w-full md:w-auto">

                        {{-- A. Filter Sumber --}}
                        <div class="relative h-[52px]" x-data="{ open: false, hasValue: '{{ request('filter_sumber') }}' }">
                            <input type="hidden" name="filter_sumber" id="sumberSelect" value="{{ request('filter_sumber') }}">
                            <button type="button" @click="open = !open"
                                    class="w-full md:w-auto min-w-0 md:min-w-[140px] h-full px-3 md:px-5 rounded-[1.5rem] bg-white border border-stone-200 hover:border-emerald-300 hover:bg-emerald-50/50 text-stone-600 hover:text-emerald-700 text-xs md:text-sm font-bold flex items-center justify-between gap-2 transition-all shadow-sm group">
                                <div class="flex items-center gap-2 overflow-hidden">
                                    <span class="material-symbols-rounded text-lg text-emerald-500 group-hover:scale-110 transition-transform shrink-0">store</span>
                                    <span class="truncate" x-text="hasValue === 'pos' ? 'POS' : (hasValue === 'manual' ? 'Manual' : 'Sumber')">Sumber</span>
                                </div>
                                <span class="material-symbols-rounded text-stone-400 text-lg shrink-0 hidden sm:block">expand_more</span>
                            </button>
                            {{-- Dropdown --}}
                            <div x-cloak x-show="open" @click.outside="open = false"
                                 class="absolute left-0 top-full mt-2 w-48 bg-white border border-stone-100 rounded-2xl shadow-xl z-50 p-1.5 flex flex-col gap-1 ring-1 ring-black/5">
                                <button type="button" onclick="setFilter('sumberSelect', '')" @click="open = false; hasValue = ''" class="text-left px-3 py-2.5 rounded-xl text-xs font-bold text-stone-600 hover:bg-stone-50 transition-colors">Semua Sumber</button>
                                <div class="h-px bg-stone-100 mx-2"></div>
                                <button type="button" onclick="setFilter('sumberSelect', 'pos')" @click="open = false; hasValue = 'pos'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 transition-colors">Transaksi POS</button>
                                <button type="button" onclick="setFilter('sumberSelect', 'manual')" @click="open = false; hasValue = 'manual'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 transition-colors">Input Manual</button>
                            </div>
                        </div>

                        {{-- B. Filter Nominal --}}
                        <div class="relative h-[52px]" x-data="{ open: false, hasValue: '{{ request('filter_harga') }}' }">
                            <input type="hidden" name="filter_harga" id="hargaSelect" value="{{ request('filter_harga') }}">
                            <button type="button" @click="open = !open"
                                    class="w-full md:w-auto min-w-0 md:min-w-[140px] h-full px-3 md:px-5 rounded-[1.5rem] bg-white border border-stone-200 hover:border-emerald-300 hover:bg-emerald-50/50 text-stone-600 hover:text-emerald-700 text-xs md:text-sm font-bold flex items-center justify-between gap-2 transition-all shadow-sm group">
                                <div class="flex items-center gap-2 overflow-hidden">
                                    <span class="material-symbols-rounded text-lg text-emerald-500 group-hover:scale-110 transition-transform shrink-0">attach_money</span>
                                    <span class="truncate" x-text="hasValue ? 'Filter' : 'Nominal'">Nominal</span>
                                </div>
                                <span class="material-symbols-rounded text-stone-400 text-lg shrink-0 hidden sm:block">expand_more</span>
                            </button>
                            {{-- Dropdown --}}
                            <div x-cloak x-show="open" @click.outside="open = false"
                                 class="absolute left-0 top-full mt-2 w-48 bg-white border border-stone-100 rounded-2xl shadow-xl z-50 p-1.5 flex flex-col gap-1 ring-1 ring-black/5">
                                <button type="button" onclick="setFilter('hargaSelect', '')" @click="open = false; hasValue = ''" class="text-left px-3 py-2.5 rounded-xl text-xs font-bold text-stone-600 hover:bg-stone-50 transition-colors">Semua Nominal</button>
                                <div class="h-px bg-stone-100 mx-2"></div>
                                <button type="button" onclick="setFilter('hargaSelect', '0-50000')" @click="open = false; hasValue = '0-50000'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 transition-colors">0 - 50rb</button>
                                <button type="button" onclick="setFilter('hargaSelect', '51000-500000')" @click="open = false; hasValue = '51000-500000'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 transition-colors">51rb - 500rb</button>
                                <button type="button" onclick="setFilter('hargaSelect', '500001-999999999')" @click="open = false; hasValue = '500001-999999999'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 transition-colors">> 500rb</button>
                            </div>
                        </div>

                        {{-- C. Filter Waktu --}}
                        <div class="relative h-[52px]" x-data="{ open: false, isCustom: '{{ request('filter_waktu') }}' == 'custom', hasValue: '{{ request('filter_waktu') }}' }">
                            <input type="hidden" name="filter_waktu" id="tanggalSelect" value="{{ request('filter_waktu') }}">
                            <button type="button" @click="open = !open"
                                    class="w-full md:w-auto min-w-0 md:min-w-[140px] h-full px-3 md:px-5 rounded-[1.5rem] bg-white border border-stone-200 hover:border-emerald-300 hover:bg-emerald-50/50 text-stone-600 hover:text-emerald-700 text-xs md:text-sm font-bold flex items-center justify-between gap-2 transition-all shadow-sm group">
                                <div class="flex items-center gap-2 overflow-hidden">
                                    <span class="material-symbols-rounded text-lg text-emerald-500 group-hover:scale-110 transition-transform shrink-0">calendar_today</span>
                                    <span class="truncate" x-text="hasValue ? 'Filter' : 'Waktu'">Waktu</span>
                                </div>
                                <span class="material-symbols-rounded text-stone-400 text-lg shrink-0 hidden sm:block">expand_more</span>
                            </button>
                            {{-- Dropdown --}}
                            <div x-cloak x-show="open" @click.outside="open = false"
                                 class="absolute right-0 md:left-0 md:right-auto top-full mt-2 w-64 bg-white border border-stone-100 rounded-2xl shadow-xl z-50 p-1.5 flex flex-col gap-1 ring-1 ring-black/5">
                                <button type="button" onclick="setFilter('tanggalSelect', '', false)" @click="open = false; isCustom = false; hasValue = ''" class="text-left px-3 py-2.5 rounded-xl text-xs font-bold text-stone-600 hover:bg-stone-50 transition-colors">Semua Waktu</button>
                                <div class="h-px bg-stone-100 mx-2"></div>
                                <button type="button" onclick="setFilter('tanggalSelect', 'hari-ini', false)" @click="open = false; isCustom = false; hasValue = 'hari-ini'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 transition-colors">Hari Ini</button>
                                <button type="button" onclick="setFilter('tanggalSelect', 'minggu-ini', false)" @click="open = false; isCustom = false; hasValue = 'minggu-ini'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 transition-colors">Minggu Ini</button>
                                <button type="button" onclick="setFilter('tanggalSelect', 'bulan-ini', false)" @click="open = false; isCustom = false; hasValue = 'bulan-ini'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 transition-colors">Bulan Ini</button>
                                <button type="button" onclick="setFilter('tanggalSelect', 'custom', true)" @click="isCustom = true; hasValue = 'custom'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 transition-colors flex justify-between items-center group">
                                    <span>Pilih Tanggal</span>
                                    <span class="material-symbols-rounded text-sm group-hover:text-emerald-600">date_range</span>
                                </button>
                                <div x-show="isCustom" x-transition class="p-2 bg-stone-50 rounded-xl mt-1 border border-stone-100 space-y-2">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="text-[10px] font-bold text-stone-400 pl-1">Dari</label>
                                            <input type="date" name="start_date" id="startDateInput" value="{{ request('start_date') }}" onchange="applyAllFilters()" class="w-full bg-white border-stone-200 rounded-lg text-[10px] py-1.5 px-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-bold text-stone-400 pl-1">Sampai</label>
                                            <input type="date" name="end_date" id="endDateInput" value="{{ request('end_date') }}" onchange="applyAllFilters()" class="w-full bg-white border-stone-200 rounded-lg text-[10px] py-1.5 px-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- D. Filter Sortir --}}
                        <div class="relative h-[52px]" x-data="{ open: false, hasValue: '{{ request('sort') }}' }">
                             <input type="hidden" name="sort" id="sortSelect" value="{{ request('sort') }}">
                             <button type="button" @click="open = !open"
                                     class="w-full md:w-auto min-w-0 md:min-w-[140px] h-full px-3 md:px-5 rounded-[1.5rem] bg-white border border-stone-200 hover:border-emerald-300 hover:bg-emerald-50/50 text-stone-600 hover:text-emerald-700 text-xs md:text-sm font-bold flex items-center justify-between gap-2 transition-all shadow-sm group">
                                 <div class="flex items-center gap-2 overflow-hidden">
                                     <span class="material-symbols-rounded text-lg text-emerald-500 group-hover:scale-110 transition-transform shrink-0">sort</span>
                                     <span class="truncate" x-text="hasValue ? 'Filter' : 'Urutkan'">Urutkan</span>
                                 </div>
                                 <span class="material-symbols-rounded text-stone-400 text-lg shrink-0 hidden sm:block">expand_more</span>
                             </button>
                             <div x-cloak x-show="open" @click.outside="open = false"
                                  class="absolute right-0 top-full mt-2 w-48 bg-white border border-stone-100 rounded-2xl shadow-xl z-50 p-1.5 flex flex-col gap-1 ring-1 ring-black/5">
                                 <button type="submit" onclick="document.getElementById('sortSelect').value='terbaru'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 transition-colors">Terbaru</button>
                                 <button type="submit" onclick="document.getElementById('sortSelect').value='az'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 transition-colors">Abjad A-Z</button>
                                 <button type="submit" onclick="document.getElementById('sortSelect').value='za'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 transition-colors">Abjad Z-A</button>
                                 <button type="submit" onclick="document.getElementById('sortSelect').value='max'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 transition-colors">Harga Tertinggi</button>
                                 <button type="submit" onclick="document.getElementById('sortSelect').value='min'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 transition-colors">Harga Terendah</button>
                             </div>
                        </div>

                        {{-- Reset Button --}}
                        <a href="{{ route('kas-masuk.index') }}"
                           class="col-span-2 md:col-span-1 w-full md:w-[52px] h-[52px] flex items-center justify-center rounded-[1.5rem] border border-stone-200 text-stone-400 hover:text-emerald-500 hover:border-emerald-200 hover:bg-emerald-50 transition-all bg-white shadow-sm shrink-0" title="Reset Filter">
                            <span class="material-symbols-rounded text-xl">restart_alt</span>
                        </a>

                    </div>
                </div>
            </div>
        </form>

        {{-- 4. TABLE VIEW (Desktop) --}}
        <div id="kasDataContainer" class="hidden md:block bg-white rounded-[2.5rem] shadow-soft border border-stone-100 overflow-hidden animate-[fadeIn_0.6s_ease-out]">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-stone-50 border-b border-stone-100">
                        <tr>
                            <th class="p-6 text-xs font-extrabold text-stone-400 uppercase tracking-widest w-10">
                                <input type="checkbox" id="selectAll" class="w-4 h-4 text-emerald-600 bg-white border-stone-300 rounded-md focus:ring-emerald-500 transition-all cursor-pointer">
                            </th>
                            <th class="p-6 text-xs font-extrabold text-stone-400 uppercase tracking-widest">Transaksi</th>
                            <th class="p-6 text-xs font-extrabold text-stone-400 uppercase tracking-widest">Keterangan</th>
                            <th class="p-6 text-xs font-extrabold text-stone-400 uppercase tracking-widest text-center">Kategori</th>
                            <th class="p-6 text-xs font-extrabold text-stone-400 uppercase tracking-widest text-right">Nominal</th>
                            <th class="p-6 text-xs font-extrabold text-stone-400 uppercase tracking-widest text-center">Metode</th>
                            <th class="p-6 text-xs font-extrabold text-stone-400 uppercase tracking-widest text-center">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @forelse ($kasMasuk as $item)
                            @php
                                // LOGIC PENENTUAN SUMBER (POS vs MANUAL)
                                $cat = strtolower(trim($item->kategori ?? ''));
                                // Jika kategori mengandung kata 'penjualan' -> POS
                                $isPos = str_contains($cat, 'penjualan');
                                $sourceType = $isPos ? 'pos' : 'manual';
                            @endphp

                            <tr class="hover:bg-emerald-50/30 transition-colors group filter-item"
                                data-keterangan="{{ strtolower($item->keterangan) }}"
                                data-nominal="{{ $item->total }}"
                                data-tanggal="{{ $item->tanggal_transaksi }}"
                                data-kategori="{{ strtolower($item->kategori) }}"
                                data-metode="{{ strtolower($item->metode_pembayaran) }}"
                                data-kode="{{ strtolower($item->kode_kas) }}"
                                data-source="{{ $sourceType }}">

                                <td class="p-6 align-top">
                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="user-checkbox w-4 h-4 text-emerald-600 bg-stone-100 border-stone-300 rounded-md focus:ring-emerald-500 transition-all cursor-pointer">
                                </td>
                                <td class="p-6 align-top">
                                    <div class="font-bold text-stone-800 whitespace-nowrap group-hover:text-emerald-700 transition-colors">{{ $item->kode_kas }}</div>
                                    <div class="text-xs text-stone-400 font-bold mt-1 flex items-center gap-1.5">
                                        <span class="material-symbols-rounded text-[14px]">event</span>
                                        {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="p-6 align-top">
                                    <div class="font-medium text-stone-600 line-clamp-2 max-w-[250px] leading-relaxed">{{ $item->keterangan }}</div>
                                    @if($item->jumlah > 1 && $item->harga_satuan > 0)
                                        <div class="inline-flex items-center gap-1 mt-2 px-2 py-0.5 rounded-md bg-stone-100 border border-stone-200 text-[10px] text-stone-500 font-mono">
                                            <span>{{ $item->jumlah }}</span>
                                            <span class="text-stone-300">x</span>
                                            <span>{{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="p-6 align-top text-center">
                                    @php
                                        $kategoriLower = strtolower($item->kategori);
                                        $badgeClass = match (true) {
                                            str_contains($kategoriLower, 'penjualan') => 'bg-emerald-100 text-emerald-800 border-emerald-200 ring-emerald-500/20',
                                            str_contains($kategoriLower, 'event') => 'bg-blue-100 text-blue-800 border-blue-200 ring-blue-500/20',
                                            str_contains($kategoriLower, 'mitra') || str_contains($kategoriLower, 'titipan') => 'bg-orange-100 text-orange-800 border-orange-200 ring-orange-500/20',
                                            str_contains($kategoriLower, 'modal') => 'bg-purple-100 text-purple-800 border-purple-200 ring-purple-500/20',
                                            default => 'bg-stone-100 text-stone-600 border-stone-200 ring-stone-500/20',
                                        };
                                    @endphp
                                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border ring-2 ring-opacity-50 {{ $badgeClass }}">
                                        {{ $item->kategori }}
                                    </span>
                                </td>
                                <td class="p-6 align-top text-right whitespace-nowrap">
                                    <span class="font-black text-emerald-600 text-base tracking-tight">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                </td>
                                <td class="p-6 align-top text-center">
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-stone-200 bg-stone-50">
                                        <span class="text-[10px] font-bold text-stone-600 capitalize tracking-wide">{{ $item->metode_pembayaran }}</span>
                                    </div>
                                </td>
                                <td class="p-6 align-top text-center">
                                    @if(Auth::user()->role === 'admin')
                                        <div class="flex items-center justify-center gap-2 opacity-50 group-hover:opacity-100 transition-all">
                                            <a href="{{ route('kas-masuk.edit', $item->id) }}" class="p-2 rounded-xl text-stone-400 hover:bg-amber-50 hover:text-amber-600 transition-all border border-transparent hover:border-amber-100" title="Edit">
                                                <span class="material-symbols-rounded text-xl">edit_square</span>
                                            </a>
                                            <button type="button"
                                                    class="p-2 rounded-xl text-stone-400 hover:bg-rose-50 hover:text-rose-600 transition-all border border-transparent hover:border-rose-100"
                                                    title="Hapus"
                                                    onclick="confirmDelete('{{ $item->id }}', '{{ $item->keterangan }}', '{{ route('kas-masuk.destroy', $item->id) }}')">
                                                <span class="material-symbols-rounded text-xl">delete</span>
                                            </button>
                                            <form id="deleteForm-{{ $item->id }}" action="{{ route('kas-masuk.destroy', $item->id) }}" method="POST" class="hidden">
                                                @csrf @method('DELETE')
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-stone-300 font-bold">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr id="noDataRow">
                                <td colspan="7" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-stone-50 p-6 rounded-full mb-4">
                                            <span class="material-symbols-rounded text-5xl text-stone-300">receipt_long</span>
                                        </div>
                                        <p class="text-stone-800 font-bold text-lg">Belum ada data pemasukan.</p>
                                        <p class="text-stone-400 text-sm mt-1">Silakan tambah data baru untuk memulai.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination Links --}}
            <div class="p-4 border-t border-stone-100">
                {{ $kasMasuk->withQueryString()->links() }}
            </div>
        </div>

        {{-- 5. MOBILE LIST VIEW --}}
        <div id="kasDataContainerMobile" class="md:hidden space-y-3 pb-safe animate-[fadeIn_0.6s_ease-out]">
            @if(Auth::user()->role === 'admin' && count($kasMasuk) > 0)
                <div class="flex items-center justify-between px-1 mb-2">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" id="selectAllMobile" class="peer sr-only">
                            <div class="w-5 h-5 border-2 border-stone-300 rounded-md peer-checked:bg-emerald-500 peer-checked:border-emerald-500 transition-all"></div>
                            <span class="material-symbols-rounded absolute top-0 left-0 text-[20px] text-white opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none">check</span>
                        </div>
                        <span class="text-xs font-bold text-stone-500 group-active:text-stone-800 uppercase tracking-wide">Pilih Semua</span>
                    </label>
                    <span class="text-[10px] text-stone-400 font-medium italic">{{ count($kasMasuk) }} Data</span>
                </div>
            @endif

            @forelse ($kasMasuk as $item)
                @php
                    $cat = strtolower(trim($item->kategori ?? ''));
                    $isPos = str_contains($cat, 'penjualan');
                    $sourceType = $isPos ? 'pos' : 'manual';
                @endphp

                <div @click="showDetail = true; selectedItem = JSON.parse('{{ json_encode([
                        'id' => $item->id,
                        'kode_kas' => $item->kode_kas,
                        'tanggal' => \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d M Y'),
                        'keterangan' => $item->keterangan ?? '-',
                        'kategori' => $item->kategori,
                        'jumlah' => $item->jumlah,
                        'harga_satuan' => number_format($item->harga_satuan, 0, ',', '.'),
                        'nominal' => number_format($item->total, 0, ',', '.'),
                        'metode_pembayaran' => $item->metode_pembayaran,
                        'edit_url' => route('kas-masuk.edit', $item->id),
                        'delete_url' => route('kas-masuk.destroy', $item->id),
                    ]) }}')"
                    class="bg-white rounded-[1.5rem] p-4 shadow-soft border border-stone-100 active:scale-[0.98] active:bg-stone-50 transition-all cursor-pointer relative overflow-hidden filter-item-mobile group"
                    data-keterangan="{{ strtolower($item->keterangan) }}"
                    data-nominal="{{ $item->total }}"
                    data-tanggal="{{ $item->tanggal_transaksi }}"
                    data-kategori="{{ strtolower($item->kategori) }}"
                    data-source="{{ $sourceType }}">

                    @php
                        $kategoriLower = strtolower($item->kategori);
                        $accentColor = match (true) {
                            str_contains($kategoriLower, 'penjualan') => 'bg-emerald-500',
                            str_contains($kategoriLower, 'event') => 'bg-blue-500',
                            str_contains($kategoriLower, 'mitra') => 'bg-orange-500',
                            default => 'bg-stone-400',
                        };
                    @endphp

                    {{-- Checkbox Mobile --}}
                    @if(Auth::user()->role === 'admin')
                        <div class="absolute top-0 right-0 p-4 z-10" @click.stop>
                            <label class="relative cursor-pointer flex items-center justify-center p-2 -m-2">
                                <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="user-checkbox peer sr-only">
                                <div class="w-6 h-6 bg-stone-100 border-2 border-stone-300 rounded-md peer-checked:bg-emerald-500 peer-checked:border-emerald-500 transition-all shadow-sm"></div>
                                <span class="material-symbols-rounded absolute text-[18px] text-white opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 font-bold">check</span>
                            </label>
                        </div>
                    @endif

                    <div class="absolute top-4 bottom-4 left-0 w-1 rounded-r-full {{ $accentColor }}"></div>

                    <div class="pl-3 pr-8">
                        <div class="flex items-center gap-2 mb-2">
                             <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wide border bg-stone-50 border-stone-200 text-stone-500">
                                {{ $item->kategori }}
                            </span>
                            <span class="text-[10px] text-stone-400 font-bold tracking-wide">{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d M') }}</span>
                        </div>
                        <h3 class="font-bold text-stone-800 text-sm line-clamp-2 leading-snug mb-2">{{ $item->keterangan }}</h3>
                        <div class="flex justify-between items-end">
                             <div class="flex flex-col">
                                <span class="text-[10px] text-stone-400 font-medium">Nominal</span>
                                <p class="font-black text-emerald-600 text-lg tracking-tight -mt-0.5">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                             </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 px-6 bg-white rounded-[2rem] border border-stone-100 border-dashed" id="noDataMobile">
                    <div class="bg-stone-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-rounded text-3xl text-stone-300">search_off</span>
                    </div>
                    <h3 class="text-stone-800 font-bold">Data tidak ditemukan</h3>
                </div>
            @endforelse
            {{-- Pagination Links Mobile --}}
            <div class="mt-4">
                {{ $kasMasuk->withQueryString()->links() }}
            </div>
        </div>

        {{-- 6. MODAL DETAIL MOBILE --}}
        <div x-show="showDetail" class="fixed inset-0 z-[110] md:hidden flex flex-col justify-end" style="display: none;">
            <div class="absolute inset-0 bg-stone-900/40 backdrop-blur-[2px]" @click="showDetail = false"></div>
            <div x-show="showDetail" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 class="relative bg-[#FAFAF9] rounded-t-[2.5rem] shadow-xl max-h-[85vh] flex flex-col overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-8 bg-transparent flex justify-center pt-3 z-20 pointer-events-none">
                    <div class="w-12 h-1.5 bg-stone-300/50 rounded-full"></div>
                </div>
                <div class="px-6 pt-8 pb-4 bg-white rounded-b-[2.5rem] shadow-sm z-10 relative">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider border shadow-sm bg-stone-100 text-stone-600 border-stone-200" x-text="selectedItem.kategori"></span>
                        <button @click="showDetail = false" class="w-8 h-8 rounded-full bg-stone-100 flex items-center justify-center text-stone-500">
                            <span class="material-symbols-rounded">close</span>
                        </button>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1">Total Pemasukan</p>
                        <h3 class="text-4xl font-black text-stone-800 tracking-tighter" x-text="'Rp ' + selectedItem.nominal"></h3>
                        <div class="inline-flex items-center gap-1 mt-2 px-3 py-1 rounded-full bg-stone-100 border border-stone-200">
                             <span class="text-[10px] font-mono font-bold text-stone-500" x-text="selectedItem.kode_kas"></span>
                        </div>
                    </div>
                </div>
                <div class="overflow-y-auto p-6 space-y-4 pb-safe">
                    <div class="bg-white p-5 rounded-3xl border border-stone-200/60 shadow-sm space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-[10px] font-bold text-stone-400 uppercase tracking-wide block mb-1">Tanggal</span>
                                <span class="font-bold text-stone-700 text-sm flex items-center gap-1.5">
                                    <span class="material-symbols-rounded text-base text-stone-400">event</span>
                                    <span x-text="selectedItem.tanggal"></span>
                                </span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-stone-400 uppercase tracking-wide block mb-1">Metode</span>
                                <span class="font-bold text-stone-700 text-sm capitalize flex items-center gap-1.5">
                                    <span class="material-symbols-rounded text-base text-stone-400">payments</span>
                                    <span x-text="selectedItem.metode_pembayaran"></span>
                                </span>
                            </div>
                        </div>
                        <div class="h-px bg-stone-100"></div>
                        <div>
                            <span class="text-[10px] font-bold text-stone-400 uppercase tracking-wide block mb-1">Keterangan</span>
                            <p class="font-medium text-stone-800 text-sm leading-relaxed" x-text="selectedItem.keterangan || '-'"></p>
                        </div>
                    </div>
                    @if(Auth::user()->role === 'admin')
                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <a :href="selectedItem.edit_url" class="bg-white border border-stone-200 text-stone-700 font-bold py-3.5 rounded-2xl flex items-center justify-center gap-2 hover:bg-stone-50 transition-all text-sm shadow-sm">
                                <span class="material-symbols-rounded text-lg">edit</span> Edit
                            </a>
                            <button @click="confirmDelete(selectedItem.id, selectedItem.keterangan, selectedItem.delete_url)"
                                    class="bg-rose-600 text-white font-bold py-3.5 rounded-2xl flex items-center justify-center gap-2 shadow-lg hover:bg-rose-700 transition-all text-sm">
                                <span class="material-symbols-rounded text-lg">delete</span> Hapus
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- 7. FLOATING ACTION & BULK DELETE --}}
        @if(Auth::user()->role === 'admin')
            <div id="bulkDeleteContainer" class="fixed bottom-[100px] md:bottom-10 left-1/2 -translate-x-1/2 z-[100] w-full max-w-sm px-4 transition-all duration-500 transform translate-y-40 opacity-0 invisible">
                <div class="bg-stone-900/90 text-white p-2 pl-5 pr-2 rounded-full shadow-2xl flex items-center justify-between border border-white/10 backdrop-blur-xl">
                    <div class="flex items-center gap-3">
                        <div class="bg-emerald-500 rounded-full p-1">
                            <span class="material-symbols-rounded text-stone-900 text-sm font-bold">check</span>
                        </div>
                        <span class="text-sm font-medium">
                            <span id="selectedCount" class="font-bold text-white text-lg">0</span> Terpilih
                        </span>
                    </div>
                    <button onclick="submitBulkDelete()" class="bg-rose-600 hover:bg-rose-500 text-white text-sm font-bold px-6 py-2.5 rounded-full transition-all active:scale-95 shadow-lg shadow-rose-900/30 flex items-center gap-2">
                         Hapus
                    </button>
                </div>
                <form id="bulkDeleteForm" action="{{ route('kas-masuk.bulk_destroy') }}" method="POST" class="hidden">
                    @csrf @method('DELETE')
                    <div id="bulkDeleteInputs"></div>
                </form>
            </div>
            <form id="singleDeleteForm" method="POST" class="hidden">@csrf @method('DELETE')</form>
        @endif

    </div>

    {{-- =====================================================================
         SCRIPTS
         ===================================================================== --}}
    <script>
        // ===========================
        // FILTER & SEARCH LOGIC (Client Side)
        // ===========================
        function setFilter(inputId, value, isCustom = false) {
            const input = document.getElementById(inputId);
            if (input) {
                input.value = value;
                // Jika bukan custom date (butuh input manual), langsung terapkan
                if (!isCustom) {
                    applyAllFilters();
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const sumberSelect = document.getElementById('sumberSelect');
            const hargaSelect = document.getElementById('hargaSelect');
            const tanggalSelect = document.getElementById('tanggalSelect');
            const startDateInput = document.getElementById('startDateInput');
            const endDateInput = document.getElementById('endDateInput');

            // Debounce Search
            let searchTimeout;
            searchInput?.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    applyAllFilters();
                }, 300);
            });

            window.applyAllFilters = function () {
                const query = searchInput.value.toLowerCase();
                const sumberFilter = sumberSelect.value;
                const hargaFilter = hargaSelect.value;
                const waktuFilter = tanggalSelect.value;
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;

                const rows = document.querySelectorAll('.filter-item, .filter-item-mobile');
                let visibleCount = 0;
                let visibleCountMobile = 0;

                rows.forEach(row => {
                    // Ambil Data Attributes
                    const keterangan = row.dataset.keterangan || '';
                    const kode = row.dataset.kode || '';
                    const kategori = row.dataset.kategori || '';
                    const nominal = parseInt(row.dataset.nominal) || 0;
                    const tanggal = row.dataset.tanggal;
                    const source = row.dataset.source || ''; // pos / manual

                    // Logic Matching
                    const matchesSearch = !query ||
                        keterangan.includes(query) ||
                        kode.includes(query) ||
                        kategori.includes(query) ||
                        nominal.toString().includes(query);

                    const matchesSumber = !sumberFilter || source === sumberFilter;
                    const matchesHarga = checkHargaFilter(nominal, hargaFilter);
                    const matchesWaktu = checkWaktuFilter(tanggal, waktuFilter, startDate, endDate);

                    if (matchesSearch && matchesSumber && matchesHarga && matchesWaktu) {
                        row.style.display = '';
                        if (row.classList.contains('filter-item')) visibleCount++;
                        if (row.classList.contains('filter-item-mobile')) visibleCountMobile++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Tampilkan pesan "Tidak ada data" jika hasil 0
                const noDataRow = document.getElementById('noDataRow');
                if (noDataRow) noDataRow.style.display = visibleCount === 0 ? '' : 'none';

                const noDataMobile = document.getElementById('noDataMobile');
                if (noDataMobile) {
                      noDataMobile.style.display = visibleCountMobile === 0 ? '' : 'none';
                }
            }

            function checkHargaFilter(nominal, filter) {
                if (!filter) return true;
                const [minStr, maxStr] = filter.split('-');
                const min = parseInt(minStr) || 0;
                const max = parseInt(maxStr) || Infinity;
                return nominal >= min && nominal <= max;
            }

            function checkWaktuFilter(dateStr, filter, start, end) {
                if (!filter) return true;
                const dateToCheck = new Date(dateStr);
                const today = new Date();
                dateToCheck.setHours(0, 0, 0, 0);
                today.setHours(0, 0, 0, 0);

                switch (filter) {
                    case 'hari-ini': return dateToCheck.getTime() === today.getTime();
                    case 'minggu-ini': return isSameWeek(today, dateToCheck);
                    case 'bulan-ini': return dateToCheck.getMonth() === today.getMonth() && dateToCheck.getFullYear() === today.getFullYear();
                    case 'custom':
                        if (!start || !end) return true;
                        const startDate = new Date(start);
                        const endDate = new Date(end);
                        startDate.setHours(0, 0, 0, 0);
                        endDate.setHours(0, 0, 0, 0);
                        return dateToCheck >= startDate && dateToCheck <= endDate;
                    default: return true;
                }
            }

            function isSameWeek(d1, d2) {
                const onejan = new Date(d1.getFullYear(), 0, 1);
                const week1 = Math.ceil((((d1.getTime() - onejan.getTime()) / 86400000) + onejan.getDay() + 1) / 7);
                const week2 = Math.ceil((((d2.getTime() - onejan.getTime()) / 86400000) + onejan.getDay() + 1) / 7);
                return week1 === week2 && d1.getFullYear() === d2.getFullYear();
            }


            // --- LOGIC CHECKBOX & BULK DELETE (Sama seperti sebelumnya) ---
            const selectAllCheckbox = document.getElementById('selectAll');
            const selectAllMobile = document.getElementById('selectAllMobile');
            const bulkDeleteContainer = document.getElementById('bulkDeleteContainer');
            const selectedCountSpan = document.getElementById('selectedCount');
            const floatingAddBtn = document.getElementById('floatingAddBtn');

            function updateBulkDeleteState() {
                const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                const count = new Set(Array.from(checkedBoxes).map(cb => cb.value)).size;

                if (selectedCountSpan) selectedCountSpan.innerText = count;

                if (bulkDeleteContainer) {
                    if (count > 0) {
                        bulkDeleteContainer.classList.remove('translate-y-40', 'opacity-0', 'invisible');
                        if(floatingAddBtn) floatingAddBtn.classList.add('translate-y-40', 'opacity-0');
                    } else {
                        bulkDeleteContainer.classList.add('translate-y-40', 'opacity-0', 'invisible');
                        if(floatingAddBtn) floatingAddBtn.classList.remove('translate-y-40', 'opacity-0');
                    }
                }
            }

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function () {
                    document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = this.checked);
                    if(selectAllMobile) selectAllMobile.checked = this.checked;
                    updateBulkDeleteState();
                });
            }

            if (selectAllMobile) {
                selectAllMobile.addEventListener('change', function () {
                    document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = this.checked);
                    if(selectAllCheckbox) selectAllCheckbox.checked = this.checked;
                    updateBulkDeleteState();
                });
            }

            document.querySelectorAll('.user-checkbox').forEach(cb => {
                cb.addEventListener('change', updateBulkDeleteState);
            });

            // --- SWEETALERT (DELETE) ---
            window.submitBulkDelete = function () {
                const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                const uniqueIds = new Set(Array.from(checkedBoxes).map(cb => cb.value));

                if (uniqueIds.size === 0) return;

                Swal.fire({
                    title: 'Hapus ' + uniqueIds.size + ' Data?',
                    text: "Data akan dihapus permanen dan tidak bisa dikembalikan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#f5f5f4',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-[2rem] font-sans p-6',
                        confirmButton: 'rounded-xl px-6 py-3',
                        cancelButton: 'rounded-xl px-6 py-3 text-stone-600'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('bulkDeleteForm');
                        const inputs = document.getElementById('bulkDeleteInputs');
                        inputs.innerHTML = '';
                        uniqueIds.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = id;
                            inputs.appendChild(input);
                        });
                        form.submit();
                    }
                });
            }

            window.confirmDelete = function (id, deskripsi, url) {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    html: `Hapus data <span class="text-rose-600 font-bold">"${deskripsi}"</span>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#f5f5f4',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-[2rem] font-sans p-6',
                        confirmButton: 'rounded-xl px-6 py-3',
                        cancelButton: 'rounded-xl px-6 py-3 text-stone-600'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('singleDeleteForm');
                        if (form) {
                            form.action = url;
                            form.submit();
                        } else {
                            // Fallback jika form tunggal tidak ditemukan, gunakan deleteForm per item
                             document.getElementById('deleteForm-' + id).submit();
                        }
                    }
                });
            }

            // Initial Filter Apply
            applyAllFilters();
        });
    </script>

    <style>
        .user-checkbox:checked {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
        }
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
