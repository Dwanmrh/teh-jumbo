<x-app-layout>
    <x-slot name="title">Kelola Produk</x-slot>

    {{-- =====================================================================
         LIBRARIES & META
         ===================================================================== --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="min-h-screen pb-32 sm:pb-20">

        {{-- 1. HEADER & ACTIONS --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 animate-[fadeIn_0.5s_ease-out]">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-stone-900 tracking-tight leading-tight">
                    Kelola <span class="text-stone-500">Produk</span>
                </h1>
                <p class="text-stone-500 mt-2 font-medium text-sm md:text-base max-w-xl">
                    Kelola varian teh, atur ukuran cup, dan pantau harga modal (HPP) untuk memaksimalkan profit.
                </p>
            </div>

            <div class="shrink-0 z-30">
                <x-action-button onclick="openModal()" label="Tambah Produk" icon="add" />
            </div>
        </div>

        {{-- 2. STATS GRID --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8 animate-[fadeIn_0.8s_ease-out]">
            {{-- Stat 1 --}}
            <div class="relative overflow-hidden bg-white p-5 md:p-6 rounded-[1.5rem] border border-stone-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 group">
                <div class="absolute -right-6 -top-6 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity rotate-12 pointer-events-none">
                    <span class="material-symbols-rounded text-[120px]">local_cafe</span>
                </div>
                <div class="text-stone-400 text-[11px] font-bold uppercase tracking-wider mb-2">Total Varian</div>
                <div class="flex items-baseline gap-1">
                    <div class="text-3xl sm:text-4xl font-black text-stone-800">{{ $totalProduk }}</div>
                    <span class="text-xs text-stone-400 font-semibold">Menu</span>
                </div>
            </div>

            {{-- Stat 2 --}}
            <div class="relative overflow-hidden bg-white p-5 md:p-6 rounded-[1.5rem] border border-stone-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 group">
                <div class="absolute -right-6 -top-6 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity rotate-12 pointer-events-none">
                    <span class="material-symbols-rounded text-[120px]">inventory_2</span>
                </div>
                <div class="text-stone-400 text-[11px] font-bold uppercase tracking-wider mb-2">Total Stok</div>
                <div class="flex items-baseline gap-1">
                    <div class="text-3xl sm:text-4xl font-black text-emerald-600">{{ $totalStok }}</div>
                    <span class="text-xs text-stone-400 font-semibold">Pcs</span>
                </div>
            </div>

            {{-- Stat 3 --}}
            <div class="relative overflow-hidden bg-white p-5 md:p-6 rounded-[1.5rem] border border-stone-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 group">
                <div class="absolute -right-6 -top-6 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity rotate-12 pointer-events-none">
                    <span class="material-symbols-rounded text-[120px]">monetization_on</span>
                </div>
                <div class="text-stone-400 text-[11px] font-bold uppercase tracking-wider mb-2">Potensi Omset</div>
                <div class="text-2xl sm:text-3xl font-black text-blue-600 truncate tracking-tight">
                    <span class="text-sm text-blue-400 align-top mr-0.5 font-bold">Rp</span>{{ number_format($nilaiStok, 0, ',', '.') }}
                </div>
            </div>

            {{-- Stat 4 --}}
            <div class="relative overflow-hidden {{ $stokRendah > 0 ? 'bg-orange-50 border-orange-100' : 'bg-white border-stone-100' }} p-5 md:p-6 rounded-[1.5rem] border shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 group">
                <div class="absolute -right-6 -top-6 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity rotate-12 pointer-events-none">
                    <span class="material-symbols-rounded text-[120px]">warning</span>
                </div>
                <div class="text-[11px] font-bold uppercase tracking-wider mb-2 {{ $stokRendah > 0 ? 'text-orange-600' : 'text-stone-400' }}">
                    Perlu Restock
                </div>
                <div class="flex items-baseline gap-1">
                    <div class="text-3xl sm:text-4xl font-black {{ $stokRendah > 0 ? 'text-orange-600' : 'text-stone-800' }}">
                        {{ $stokRendah }}
                    </div>
                    <span class="text-xs font-semibold {{ $stokRendah > 0 ? 'text-orange-400' : 'text-stone-400' }}">Item</span>
                </div>
            </div>
        </div>

        {{-- 3. FILTER & SEARCH BAR (Sticky Filter) --}}
        <div class="sticky top-[80px] md:top-[170px] z-40 mb-8 animate-[fadeIn_0.5s_ease-out]">
            <div class="bg-white/80 backdrop-blur-xl p-3 sm:p-4 rounded-[2rem] shadow-soft border border-stone-200/60 ring-1 ring-stone-900/5 transition-all duration-300">
                <div class="flex flex-col md:flex-row gap-3">

                    {{-- Search Input --}}
                    <div class="relative flex-1 w-full group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 bg-stone-100 rounded-full p-1 transition-colors group-focus-within:bg-stone-200">
                            <span class="material-symbols-rounded text-stone-400 text-lg group-focus-within:text-stone-600">search</span>
                        </div>
                        <input type="text" id="searchInput"
                               placeholder="Cari menu, kategori, atau harga..."
                               class="w-full pl-12 pr-5 rounded-[1.5rem] border-0 bg-stone-50 focus:bg-white focus:ring-2 focus:ring-stone-500/30 text-stone-700 text-sm font-semibold placeholder:text-stone-400 transition-all shadow-inner h-[52px]">
                    </div>

                    {{-- Filter Group --}}
                    <div class="grid grid-cols-2 md:grid-cols-[auto_auto_auto_52px] gap-2 md:gap-3 w-full md:w-auto">

                        {{-- A. Filter Outlet (Admin Only) --}}
                        @if(Auth::user()->role === 'admin')
                            <div class="relative h-[52px]" x-data="{ open: false, hasValue: '' }">
                                <input type="hidden" id="outletSelect" value="">
                                <button type="button" @click="open = !open"
                                        class="w-full md:w-auto min-w-0 md:min-w-[140px] h-full px-3 md:px-5 rounded-[1.5rem] bg-white border border-stone-200 hover:border-stone-400 hover:bg-stone-50 text-stone-600 hover:text-stone-800 text-xs md:text-sm font-bold flex items-center justify-between gap-2 transition-all shadow-sm group">
                                    <div class="flex items-center gap-2 overflow-hidden">
                                        <span class="material-symbols-rounded text-lg text-stone-500 group-hover:scale-110 transition-transform shrink-0">store</span>
                                        <span class="truncate" x-text="hasValue ? document.getElementById('opt-outlet-'+hasValue).innerText : 'Outlet'">Outlet</span>
                                    </div>
                                    <span class="material-symbols-rounded text-stone-400 text-lg shrink-0 hidden sm:block">expand_more</span>
                                </button>
                                {{-- Dropdown --}}
                                <div x-cloak x-show="open" @click.outside="open = false"
                                     class="absolute left-0 top-full mt-2 w-48 bg-white border border-stone-100 rounded-2xl shadow-xl z-50 p-1.5 flex flex-col gap-1 ring-1 ring-black/5">
                                    <button type="button" onclick="setFilter('outletSelect', '', false)" @click="open = false; hasValue = ''" class="text-left px-3 py-2.5 rounded-xl text-xs font-bold text-stone-600 hover:bg-stone-50 transition-colors">Semua Outlet</button>
                                    <div class="h-px bg-stone-100 mx-2"></div>
                                    @foreach($outlets as $outlet)
                                        <button type="button" id="opt-outlet-{{ $outlet->id }}" onclick="setFilter('outletSelect', '{{ $outlet->id }}', false)" @click="open = false; hasValue = '{{ $outlet->id }}'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-stone-50 hover:text-stone-900 transition-colors">{{ $outlet->name }}</button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- B. Filter Kategori --}}
                        <div class="relative h-[52px]" x-data="{ open: false, hasValue: '' }">
                            <input type="hidden" id="kategoriSelect" value="">
                            <button type="button" @click="open = !open"
                                    class="w-full md:w-auto min-w-0 md:min-w-[140px] h-full px-3 md:px-5 rounded-[1.5rem] bg-white border border-stone-200 hover:border-stone-400 hover:bg-stone-50 text-stone-600 hover:text-stone-800 text-xs md:text-sm font-bold flex items-center justify-between gap-2 transition-all shadow-sm group">
                                <div class="flex items-center gap-2 overflow-hidden">
                                    <span class="material-symbols-rounded text-lg text-stone-500 group-hover:scale-110 transition-transform shrink-0">category</span>
                                    <span class="truncate" x-text="hasValue ? hasValue : 'Kategori'">Kategori</span>
                                </div>
                                <span class="material-symbols-rounded text-stone-400 text-lg shrink-0 hidden sm:block">expand_more</span>
                            </button>
                            <div x-cloak x-show="open" @click.outside="open = false"
                                 class="absolute left-0 top-full mt-2 w-48 bg-white border border-stone-100 rounded-2xl shadow-xl z-50 p-1.5 flex flex-col gap-1 ring-1 ring-black/5">
                                <button type="button" onclick="setFilter('kategoriSelect', '')" @click="open = false; hasValue = ''" class="text-left px-3 py-2.5 rounded-xl text-xs font-bold text-stone-600 hover:bg-stone-50 transition-colors">Semua Kategori</button>
                                <div class="h-px bg-stone-100 mx-2"></div>
                                @php
                                    $kategoris = $products->pluck('kategori')->unique();
                                @endphp
                                @foreach($kategoris as $cat)
                                     <button type="button" onclick="setFilter('kategoriSelect', '{{ $cat }}')" @click="open = false; hasValue = '{{ $cat }}'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-stone-50 hover:text-stone-900 transition-colors capitalize">{{ $cat }}</button>
                                @endforeach
                            </div>
                        </div>

                        {{-- C. Filter Stok --}}
                        <div class="relative h-[52px]" x-data="{ open: false, hasValue: '' }">
                            <input type="hidden" id="stokSelect" value="">
                            <button type="button" @click="open = !open"
                                    class="w-full md:w-auto min-w-0 md:min-w-[140px] h-full px-3 md:px-5 rounded-[1.5rem] bg-white border border-stone-200 hover:border-stone-400 hover:bg-stone-50 text-stone-600 hover:text-stone-800 text-xs md:text-sm font-bold flex items-center justify-between gap-2 transition-all shadow-sm group">
                                <div class="flex items-center gap-2 overflow-hidden">
                                    <span class="material-symbols-rounded text-lg text-stone-500 group-hover:scale-110 transition-transform shrink-0">inventory</span>
                                    <span class="truncate" x-text="hasValue === 'low' ? 'Perlu Restock' : (hasValue === 'safe' ? 'Stok Aman' : 'Stok')">Stok</span>
                                </div>
                                <span class="material-symbols-rounded text-stone-400 text-lg shrink-0 hidden sm:block">expand_more</span>
                            </button>
                            <div x-cloak x-show="open" @click.outside="open = false"
                                 class="absolute right-0 md:left-0 top-full mt-2 w-48 bg-white border border-stone-100 rounded-2xl shadow-xl z-50 p-1.5 flex flex-col gap-1 ring-1 ring-black/5">
                                <button type="button" onclick="setFilter('stokSelect', '')" @click="open = false; hasValue = ''" class="text-left px-3 py-2.5 rounded-xl text-xs font-bold text-stone-600 hover:bg-stone-50 transition-colors">Semua Stok</button>
                                <div class="h-px bg-stone-100 mx-2"></div>
                                <button type="button" onclick="setFilter('stokSelect', 'low')" @click="open = false; hasValue = 'low'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-orange-50 hover:text-orange-700 transition-colors flex items-center justify-between">
                                    Perlu Restock <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                                </button>
                                <button type="button" onclick="setFilter('stokSelect', 'safe')" @click="open = false; hasValue = 'safe'" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-stone-50 hover:text-stone-900 transition-colors flex items-center justify-between">
                                    Stok Aman <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                </button>
                            </div>
                        </div>

                        {{-- Reset Button --}}
                        <button onclick="resetFilters()"
                           class="col-span-2 md:col-span-1 w-full md:w-[52px] h-[52px] flex items-center justify-center rounded-[1.5rem] border border-stone-200 text-stone-400 hover:text-stone-600 hover:border-stone-400 hover:bg-stone-50 transition-all bg-white shadow-sm shrink-0" title="Reset Filter">
                            <span class="material-symbols-rounded text-xl">restart_alt</span>
                        </button>

                    </div>
                </div>
            </div>
        </div>

        {{-- 4. PRODUCTS GRID --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 animate-[fadeIn_1s_ease-out]">
            @forelse($products as $produk)
                <div class="product-item group bg-white rounded-[1.75rem] border border-stone-100/80 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:-translate-y-1.5 transition-all duration-500 flex flex-col overflow-hidden relative"
                     data-nama="{{ strtolower($produk->nama) }}"
                     data-kategori="{{ strtolower($produk->kategori) }}"
                     data-harga="{{ $produk->harga }}"
                     data-stok="{{ $produk->stok }}"
                     data-outlet-id="{{ $produk->outlet_id }}">

                    {{-- Image & Badges --}}
                    <div class="relative w-full aspect-[4/3] bg-stone-100 overflow-hidden">
                        <img src="{{ $produk->foto ? asset('storage/' . $produk->foto) : asset('assets/images/teh-jumbo.jpg') }}"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                             alt="{{ $produk->nama }}"
                             onerror="this.src='https://placehold.co/400x300/f5f5f4/a8a29e?text=No+Image'">

                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        {{-- Floating Badges --}}
                        <div class="absolute top-4 right-4 z-10 flex flex-col items-end gap-2">
                            @php
                                $sizeConfig = match ($produk->ukuran) {
                                    'Jumbo' => ['bg' => 'bg-purple-500', 'shadow' => 'shadow-purple-500/30'],
                                    'Sedang' => ['bg' => 'bg-blue-500', 'shadow' => 'shadow-blue-500/30'],
                                    'Kecil' => ['bg' => 'bg-stone-500', 'shadow' => 'shadow-stone-500/30'],
                                    default => ['bg' => 'bg-stone-800', 'shadow' => 'shadow-stone-800/30']
                                };
                            @endphp
                            @if($produk->ukuran && $produk->ukuran != '-')
                                <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-white {{ $sizeConfig['bg'] }} rounded-full shadow-lg {{ $sizeConfig['shadow'] }} backdrop-blur-sm border border-white/20">
                                    {{ $produk->ukuran }}
                                </span>
                            @endif
                        </div>

                        <div class="absolute top-4 left-4 z-10">
                            <span class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider bg-white/90 backdrop-blur-md text-stone-800 rounded-lg shadow-sm border border-white/50">
                                {{ ucfirst($produk->kategori) }}
                            </span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-5 flex flex-col flex-1">
                        <div class="mb-5">
                            <h3 class="text-[1.05rem] font-bold text-stone-800 leading-snug mb-2 line-clamp-2 min-h-[3rem] group-hover:text-stone-600 transition-colors" title="{{ $produk->nama }}">
                                {{ $produk->nama }}
                            </h3>
                            <div class="flex justify-between items-end border-t border-stone-5 pt-3">
                                <div>
                                    <span class="text-[10px] text-stone-400 font-bold uppercase tracking-wide">Harga Jual</span>
                                    <div class="text-stone-900 font-black text-xl tracking-tight">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                                </div>
                                @if($produk->modal > 0)
                                    <div class="text-right">
                                        <div class="text-[10px] text-emerald-600 font-bold bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100 flex items-center gap-1">
                                            <span class="material-symbols-rounded text-[14px]">trending_up</span>
                                            +{{ number_format($produk->harga - $produk->modal, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-auto space-y-4">
                            {{-- Stock Indicator --}}
                            <div>
                                <div class="flex justify-between items-end mb-1.5">
                                    <span class="text-[10px] font-bold uppercase text-stone-400 tracking-wide">Stok Tersedia</span>
                                    <span class="text-xs font-bold {{ $produk->stok <= 10 ? 'text-orange-600' : 'text-stone-600' }}">
                                        {{ $produk->stok }} <span class="text-[10px] font-medium text-stone-400">Unit</span>
                                    </span>
                                </div>
                                <div class="w-full bg-stone-100 h-1.5 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $produk->stok <= 10 ? 'bg-orange-500' : 'bg-stone-800' }} transition-all duration-500 relative"
                                         style="width: {{ min(($produk->stok / 50) * 100, 100) }}%">
                                        <div class="absolute top-0 right-0 bottom-0 w-20 bg-gradient-to-r from-transparent to-white/30"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="grid grid-cols-2 gap-2">
                                <button class="btnEdit flex items-center justify-center gap-1.5 py-2.5 rounded-xl text-xs font-bold text-stone-600 bg-stone-50 border border-stone-100 hover:bg-stone-800 hover:text-white hover:border-stone-800 transition-all duration-300"
                                        data-id="{{ $produk->id }}"
                                        data-nama="{{ $produk->nama }}"
                                        data-kategori="{{ $produk->kategori }}"
                                        data-ukuran="{{ $produk->ukuran ?? '-' }}"
                                        data-harga="{{ $produk->harga }}"
                                        data-modal="{{ $produk->modal ?? 0 }}"
                                        data-outlet-id="{{ $produk->outlet_id }}"
                                        data-stok="{{ $produk->stok }}">
                                    <span class="material-symbols-rounded text-[18px]">edit</span> Edit
                                </button>
                                <button class="delete-product-btn flex items-center justify-center gap-1.5 py-2.5 rounded-xl text-xs font-bold text-rose-600 bg-rose-50 border border-rose-100 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all duration-300"
                                        data-id="{{ $produk->id }}"
                                        data-nama="{{ $produk->nama }}">
                                    <span class="material-symbols-rounded text-[18px]">delete</span> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State dihandle JS jika data 0 dari awal --}}
                <div class="col-span-full flex flex-col items-center justify-center py-24 text-center border-2 border-dashed border-stone-200 rounded-[2.5rem] bg-stone-50/50">
                    <div class="bg-white p-4 rounded-full shadow-sm mb-4">
                        <span class="material-symbols-rounded text-5xl text-stone-300">local_cafe</span>
                    </div>
                    <h3 class="font-bold text-xl text-stone-800">Belum Ada Menu</h3>
                    <p class="text-stone-400 mt-2 max-w-xs mx-auto text-sm leading-relaxed">Inventaris menu Anda masih kosong.</p>
                </div>
            @endforelse

            {{-- Empty State (Untuk Filter JS) --}}
            <div id="noDataMessage" class="col-span-full hidden flex-col items-center justify-center py-24 text-center border-2 border-dashed border-stone-200 rounded-[2.5rem] bg-stone-50/50">
                <div class="bg-white p-4 rounded-full shadow-sm mb-4">
                    <span class="material-symbols-rounded text-5xl text-stone-300">search_off</span>
                </div>
                <h3 class="font-bold text-xl text-stone-800">Tidak Ditemukan</h3>
                <p class="text-stone-400 mt-2 max-w-xs mx-auto text-sm leading-relaxed">Menu dengan filter tersebut tidak tersedia.</p>
                <button onclick="resetFilters()" class="mt-4 text-sm font-bold text-stone-600 hover:text-stone-900 underline">Reset Filter</button>
            </div>
        </div>

    </div>

    {{-- MODAL TAMBAH PRODUCT --}}
    <div id="modalAddProduct" class="fixed inset-0 z-[110] hidden items-center justify-center w-full h-full">
        <div class="absolute inset-0 bg-stone-900/40 backdrop-blur-[4px] transition-opacity opacity-0" id="modalAddBackdrop"></div>

        <div class="relative bg-white w-full max-w-lg rounded-[2rem] shadow-2xl mx-4 overflow-hidden transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[85vh]" id="modalAddContent">

            {{-- Header --}}
            <div class="px-8 py-6 border-b border-stone-100 flex justify-between items-center bg-white sticky top-0 z-20">
                <div>
                    <h2 class="text-xl font-extrabold text-stone-900 tracking-tight">Menu Baru</h2>
                    <p class="text-xs text-stone-500 font-medium mt-0.5">Lengkapi detail produk di bawah ini.</p>
                </div>
                <button onclick="closeModal()" class="w-9 h-9 rounded-full bg-stone-50 border border-stone-200 flex items-center justify-center text-stone-400 hover:bg-stone-200 hover:text-stone-700 transition-colors">
                    <span class="material-symbols-rounded text-xl">close</span>
                </button>
            </div>

            {{-- Form Content --}}
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6 overflow-y-auto custom-scrollbar" onsubmit="cleanCurrencyInputs(this)">
                @csrf

                {{-- Nama Menu --}}
                <div class="group">
                    <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2 group-focus-within:text-brand-600 transition-colors">Nama Menu</label>
                    <input type="text" name="nama" required class="w-full bg-stone-50 border-2 border-transparent focus:border-stone-800 rounded-xl px-4 py-3.5 font-bold text-stone-800 placeholder:text-stone-300 placeholder:font-normal focus:outline-none focus:bg-white transition-all" placeholder="Contoh: Teh Jumbo Original">
                </div>

                @if(Auth::user()->role === 'admin')
                    {{-- Outlet Selector for Admin (MULTI SELECT) --}}
                    <div class="group">
                        <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2 group-focus-within:text-brand-600 transition-colors">
                            Pilih Outlet (Multi Select)
                        </label>

                        @php
                            $outletListJS = $outlets->map(function($o){ return ['id' => $o->id, 'name' => $o->name]; })->values();
                        @endphp

                        <div x-data="multiSelect({ items: {{ $outletListJS }}, initial: [] })" class="relative">

                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="outlet_ids[]" :value="id">
                            </template>

                            <div @click="toggle" @click.outside="close"
                                 class="w-full bg-stone-50 border-2 focus:border-stone-800 rounded-xl px-4 py-3.5 min-h-[52px] cursor-pointer flex flex-wrap items-center gap-2 transition-all"
                                 :class="open ? 'border-stone-800 bg-white' : 'border-transparent'">

                                <span x-show="selected.length === 0" class="text-stone-300 font-normal">Pilih Outlet (Bisa lebih dari satu)...</span>

                                <template x-for="id in selected" :key="id">
                                    <div class="bg-stone-800 text-white text-[11px] font-bold px-2 py-1 rounded-lg flex items-center gap-1 shadow-sm">
                                        <span x-text="getName(id)"></span>
                                        <button type="button" @click.stop="remove(id)" class="hover:text-stone-300">
                                            <span class="material-symbols-rounded text-[14px]">close</span>
                                        </button>
                                    </div>
                                </template>

                                <span class="absolute right-4 top-1/2 -translate-y-1/2 material-symbols-rounded text-stone-400 pointer-events-none transition-transform duration-300"
                                      :class="open ? 'rotate-180 text-stone-800' : ''">expand_more</span>
                            </div>

                            <div x-show="open" x-transition.opacity.duration.200ms style="display: none;"
                                 class="absolute z-50 w-full mt-2 bg-white border border-stone-100 rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] max-h-48 overflow-y-auto custom-scrollbar p-2">
                                <template x-for="item in items" :key="item.id">
                                    <label class="flex items-center gap-3 px-3 py-2.5 hover:bg-stone-50 rounded-lg cursor-pointer group transition-colors">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" :value="item.id" x-model="selected"
                                                   class="peer appearance-none w-5 h-5 border-2 border-stone-300 rounded-md checked:bg-stone-900 checked:border-stone-900 transition-colors">
                                            <span class="material-symbols-rounded absolute inset-0 text-white text-[16px] pointer-events-none opacity-0 peer-checked:opacity-100 flex items-center justify-center">check</span>
                                        </div>
                                        <span class="font-bold text-sm text-stone-600 group-hover:text-stone-900" x-text="item.name"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                        <p class="text-[10px] text-stone-400 mt-1.5 ml-1">Produk akan ditambahkan ke semua outlet yang dipilih.</p>
                    </div>
                @endif

                {{-- Grid: Kategori & Ukuran --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Kategori --}}
                    <div x-data="combobox({ items: ['Original', 'Varian Rasa', 'Extra Topping'] })" class="relative group">
                        <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2 group-focus-within:text-brand-600 transition-colors">Kategori</label>
                        <div class="relative">
                            <input type="text" name="kategori" x-model="value" x-ref="input" @focus="open = true" @click.outside="open = false" @keydown.escape="open = false"
                                   class="w-full bg-stone-50 border-2 border-transparent focus:border-stone-800 rounded-xl px-4 py-3.5 font-bold text-stone-800 placeholder:text-stone-300 focus:outline-none focus:bg-white transition-all"
                                   placeholder="Pilih / Ketik..." autocomplete="off" required>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none material-symbols-rounded text-xl transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                            <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute z-50 w-full mt-2 bg-white border border-stone-100 rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] max-h-48 overflow-y-auto custom-scrollbar">
                                <template x-for="item in filteredItems()" :key="item">
                                    <div @click="select(item)" class="px-5 py-3 hover:bg-stone-50 cursor-pointer font-medium text-stone-600 hover:text-stone-900 transition-colors border-b border-stone-50 last:border-0">
                                        <span x-text="item"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Ukuran --}}
                    <div x-data="combobox({ items: ['Jumbo', 'Sedang', 'Kecil', 'Tanpa Cup'] })" class="relative group">
                        <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2 group-focus-within:text-brand-600 transition-colors">Ukuran Cup</label>
                        <div class="relative">
                            <input type="text" name="ukuran" x-model="value" x-ref="input" @focus="open = true" @click.outside="open = false"
                                   class="w-full bg-stone-50 border-2 border-transparent focus:border-stone-800 rounded-xl px-4 py-3.5 font-bold text-stone-800 placeholder:text-stone-300 focus:outline-none focus:bg-white transition-all"
                                   placeholder="Pilih / Ketik..." autocomplete="off">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none material-symbols-rounded text-xl transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                            <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute z-50 w-full mt-2 bg-white border border-stone-100 rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] max-h-48 overflow-y-auto custom-scrollbar">
                                <template x-for="item in filteredItems()" :key="item">
                                    <div @click="select(item)" class="px-5 py-3 hover:bg-stone-50 cursor-pointer font-medium text-stone-600 hover:text-stone-900 transition-colors border-b border-stone-50 last:border-0">
                                        <span x-text="item"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section Harga --}}
                <div class="bg-stone-50 p-6 rounded-2xl border border-stone-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-5">
                        <span class="material-symbols-rounded text-6xl">payments</span>
                    </div>
                    <label class="block text-[10px] font-extrabold text-stone-400 uppercase tracking-wider mb-4 border-b border-stone-200 pb-2 relative z-10">Penetapan Harga</label>
                    <div class="grid grid-cols-2 gap-4 relative z-10">
                        <div class="group">
                            <label class="block text-[10px] font-bold text-stone-500 mb-1.5 group-focus-within:text-brand-600">Harga Jual</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-stone-400 font-bold text-xs">Rp</span>
                                <input type="text" inputmode="numeric" name="harga" required class="currency-input w-full bg-white border-2 border-transparent focus:border-stone-800 rounded-xl pl-10 pr-4 py-3 font-black text-lg text-stone-800 focus:outline-none transition-all placeholder:text-stone-300 shadow-sm" placeholder="0" oninput="formatCurrency(this)">
                            </div>
                        </div>
                        <div class="group">
                            <label class="block text-[10px] font-bold text-stone-500 mb-1.5 group-focus-within:text-emerald-600">Modal (HPP)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-stone-400 font-bold text-xs">Rp</span>
                                <input type="text" inputmode="numeric" name="modal" required class="currency-input w-full bg-white border-2 border-transparent focus:border-stone-800 rounded-xl pl-10 pr-4 py-3 font-black text-lg text-stone-600 focus:outline-none transition-all placeholder:text-stone-300 shadow-sm" placeholder="0" oninput="formatCurrency(this)">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stok --}}
                <div class="group">
                    <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2 group-focus-within:text-brand-600 transition-colors">
                        Sisa Stok (Porsi / Cup)
                    </label>
                    <div class="flex items-center gap-3">
                        <div class="relative flex-1">
                            <input type="number" name="stok" required class="w-full bg-stone-50 border-2 border-transparent focus:border-stone-800 rounded-xl px-4 py-3.5 font-bold text-stone-800 focus:outline-none focus:bg-white transition-all" placeholder="0">
                        </div>
                        <span class="font-bold text-stone-400 text-xs uppercase bg-stone-50 px-3 py-4 rounded-xl border border-stone-100">Item</span>
                    </div>
                    <p class="text-[10px] text-stone-400 mt-2 font-medium leading-snug flex items-center gap-1">
                        <span class="material-symbols-rounded text-sm text-orange-400">info</span>
                        Isi sesuai jumlah fisik gelas atau perkiraan porsi wadah.
                    </p>
                </div>

                {{-- File Upload --}}
                <div>
                    <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2">Foto Produk (Opsional)</label>
                    <div class="relative">
                        <input type="file" name="foto" class="block w-full text-sm text-stone-500 file:mr-4 file:py-3 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-stone-800 file:text-white hover:file:bg-black transition-all cursor-pointer bg-stone-50 rounded-xl pr-4 border border-stone-100">
                    </div>
                </div>

                {{-- Footer Button --}}
                <div class="pt-4 pb-2">
                    <button class="w-full bg-stone-900 text-white font-bold text-base py-4 rounded-2xl hover:bg-black hover:scale-[1.01] active:scale-[0.98] transition-all duration-300 shadow-xl shadow-stone-900/10 flex items-center justify-center gap-2">
                        <span class="material-symbols-rounded">save</span> Simpan Menu
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT PRODUCT --}}
    <div id="modalEditProduct" class="fixed inset-0 z-[110] hidden items-center justify-center w-full h-full">
        <div class="absolute inset-0 bg-stone-900/40 backdrop-blur-[4px] transition-opacity opacity-0" id="modalEditBackdrop"></div>

        <div class="relative bg-white w-full max-w-lg rounded-[2rem] shadow-2xl mx-4 overflow-hidden transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[85vh]" id="modalEditContent">

            {{-- Header --}}
            <div class="px-8 py-6 border-b border-stone-100 flex justify-between items-center bg-white sticky top-0 z-20">
                <div>
                    <h2 class="text-xl font-extrabold text-stone-900 tracking-tight">Edit Menu</h2>
                    <p class="text-xs text-stone-500 font-medium mt-0.5">Perbarui informasi produk.</p>
                </div>
                <button onclick="closeModalEdit()" class="w-9 h-9 rounded-full bg-stone-50 border border-stone-200 flex items-center justify-center text-stone-400 hover:bg-stone-200 hover:text-stone-700 transition-colors">
                    <span class="material-symbols-rounded text-xl">close</span>
                </button>
            </div>

            {{-- Form Content --}}
            <form id="formEditProduct" method="POST" enctype="multipart/form-data" class="p-8 space-y-6 overflow-y-auto custom-scrollbar" onsubmit="cleanCurrencyInputs(this)">
                @csrf @method('PUT')

                {{-- Nama Menu --}}
                <div class="group">
                    <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2 group-focus-within:text-brand-600 transition-colors">Nama Menu</label>
                    <input type="text" id="editNama" name="nama" required class="w-full bg-stone-50 border-2 border-transparent focus:border-stone-800 rounded-xl px-4 py-3.5 font-bold text-stone-800 focus:outline-none focus:bg-white transition-all">
                </div>

                @if(Auth::user()->role === 'admin')
                    {{-- Outlet Selector for Admin (EDIT - Multi Select) --}}
                    <div class="group">
                        <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2 group-focus-within:text-brand-600 transition-colors">Outlet (Multi Select)</label>

                        <div id="wrapperEditOutlet" x-data="multiSelect({ items: {{ $outletListJS }}, initial: [] })" class="relative">

                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="outlet_ids[]" :value="id">
                            </template>

                            <div @click="toggle" @click.outside="close"
                                 class="w-full bg-stone-50 border-2 focus:border-stone-800 rounded-xl px-4 py-3.5 min-h-[52px] cursor-pointer flex flex-wrap items-center gap-2 transition-all"
                                 :class="open ? 'border-stone-800 bg-white' : 'border-transparent'">

                                <span x-show="selected.length === 0" class="text-stone-300 font-normal">Pilih Outlet...</span>

                                <template x-for="id in selected" :key="id">
                                    <div class="bg-stone-800 text-white text-[11px] font-bold px-2 py-1 rounded-lg flex items-center gap-1 shadow-sm">
                                        <span x-text="getName(id)"></span>
                                        <button type="button" @click.stop="remove(id)" class="hover:text-stone-300">
                                            <span class="material-symbols-rounded text-[14px]">close</span>
                                        </button>
                                    </div>
                                </template>
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 material-symbols-rounded text-stone-400 pointer-events-none" :class="open ? 'rotate-180 text-stone-800' : ''">expand_more</span>
                            </div>

                            <div x-show="open" x-transition.opacity.duration.200ms style="display: none;"
                                 class="absolute z-50 w-full mt-2 bg-white border border-stone-100 rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] max-h-48 overflow-y-auto custom-scrollbar p-2">
                                <template x-for="item in items" :key="item.id">
                                    <label class="flex items-center gap-3 px-3 py-2.5 hover:bg-stone-50 rounded-lg cursor-pointer group transition-colors">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" :value="item.id" x-model="selected"
                                                   class="peer appearance-none w-5 h-5 border-2 border-stone-300 rounded-md checked:bg-stone-900 checked:border-stone-900 transition-colors">
                                            <span class="material-symbols-rounded absolute inset-0 text-white text-[16px] pointer-events-none opacity-0 peer-checked:opacity-100 flex items-center justify-center">check</span>
                                        </div>
                                        <span class="font-bold text-sm text-stone-600 group-hover:text-stone-900" x-text="item.name"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Grid: Kategori & Ukuran (Edit) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Kategori --}}
                    <div id="wrapperEditKategori" x-data="combobox({ items: ['Original', 'Varian Rasa', 'Extra Topping'] })" class="relative group">
                        <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2 group-focus-within:text-brand-600 transition-colors">Kategori</label>
                        <div class="relative">
                            <input type="text" id="editKategori" name="kategori" x-model="value" x-ref="input" @focus="open = true" @click.outside="open = false"
                                   class="w-full bg-stone-50 border-2 border-transparent focus:border-stone-800 rounded-xl px-4 py-3.5 font-bold text-stone-800 focus:outline-none focus:bg-white transition-all" placeholder="Pilih / Ketik..." required autocomplete="off">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none material-symbols-rounded text-xl" :class="open ? 'rotate-180' : ''">expand_more</span>
                            <div x-show="open" x-transition style="display: none;" class="absolute z-50 w-full mt-2 bg-white border border-stone-100 rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] max-h-48 overflow-y-auto custom-scrollbar">
                                <template x-for="item in filteredItems()" :key="item">
                                    <div @click="select(item)" class="px-5 py-3 hover:bg-stone-50 cursor-pointer font-medium text-stone-600 hover:text-stone-900 border-b border-stone-50">
                                        <span x-text="item"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Ukuran --}}
                    <div id="wrapperEditUkuran" x-data="combobox({ items: ['Jumbo', 'Sedang', 'Kecil', 'Tanpa Cup'] })" class="relative group">
                        <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2 group-focus-within:text-brand-600 transition-colors">Ukuran Cup</label>
                        <div class="relative">
                            <input type="text" id="editUkuran" name="ukuran" x-model="value" x-ref="input" @focus="open = true" @click.outside="open = false"
                                   class="w-full bg-stone-50 border-2 border-transparent focus:border-stone-800 rounded-xl px-4 py-3.5 font-bold text-stone-800 focus:outline-none focus:bg-white transition-all" placeholder="Pilih / Ketik..." autocomplete="off">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none material-symbols-rounded text-xl" :class="open ? 'rotate-180' : ''">expand_more</span>
                            <div x-show="open" x-transition style="display: none;" class="absolute z-50 w-full mt-2 bg-white border border-stone-100 rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] max-h-48 overflow-y-auto custom-scrollbar">
                                <template x-for="item in filteredItems()" :key="item">
                                    <div @click="select(item)" class="px-5 py-3 hover:bg-stone-50 cursor-pointer font-medium text-stone-600 hover:text-stone-900 border-b border-stone-50">
                                        <span x-text="item"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section Harga --}}
                <div class="bg-stone-50 p-6 rounded-2xl border border-stone-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-5">
                        <span class="material-symbols-rounded text-6xl">price_change</span>
                    </div>
                    <label class="block text-[10px] font-extrabold text-stone-400 uppercase tracking-wider mb-4 border-b border-stone-200 pb-2 relative z-10">Penetapan Harga</label>
                    <div class="grid grid-cols-2 gap-4 relative z-10">
                        <div class="group">
                            <label class="block text-[10px] font-bold text-stone-500 mb-1.5 group-focus-within:text-brand-600">Harga Jual</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-stone-400 font-bold text-xs">Rp</span>
                                <input type="text" inputmode="numeric" id="editHarga" name="harga" required class="currency-input w-full bg-white border-2 border-transparent focus:border-stone-800 rounded-xl pl-10 pr-4 py-3 font-black text-lg text-stone-800 focus:outline-none transition-all shadow-sm" oninput="formatCurrency(this)">
                            </div>
                        </div>
                        <div class="group">
                            <label class="block text-[10px] font-bold text-stone-500 mb-1.5 group-focus-within:text-emerald-600">Modal (HPP)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-stone-400 font-bold text-xs">Rp</span>
                                <input type="text" inputmode="numeric" id="editModal" name="modal" required class="currency-input w-full bg-white border-2 border-transparent focus:border-stone-800 rounded-xl pl-10 pr-4 py-3 font-black text-lg text-stone-600 focus:outline-none transition-all shadow-sm" oninput="formatCurrency(this)">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stok (Edit) --}}
                <div class="group">
                    <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2 group-focus-within:text-brand-600 transition-colors">
                        Sisa Stok (Porsi / Cup)
                    </label>
                    <div class="flex items-center gap-3">
                        <div class="relative flex-1">
                            <input type="number" id="editStok" name="stok" required class="w-full bg-stone-50 border-2 border-transparent focus:border-stone-800 rounded-xl px-4 py-3.5 font-bold text-stone-800 focus:outline-none focus:bg-white transition-all">
                        </div>
                        <span class="font-bold text-stone-400 text-xs uppercase bg-stone-50 px-3 py-4 rounded-xl border border-stone-100">Item</span>
                    </div>
                </div>

                {{-- File Upload --}}
                <div>
                    <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2">Ganti Foto (Opsional)</label>
                    <input type="file" name="foto" class="block w-full text-sm text-stone-500 file:mr-4 file:py-3 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-stone-800 file:text-white hover:file:bg-black transition-all cursor-pointer bg-stone-50 rounded-xl pr-4 border border-stone-100">
                </div>

                {{-- Footer Button --}}
                <div class="pt-4 pb-2">
                    <button class="w-full bg-stone-950 text-white font-bold text-base py-4 rounded-2xl hover:bg-black hover:scale-[1.01] active:scale-[0.98] transition-all duration-300 shadow-xl shadow-stone-900/10 flex items-center justify-center gap-2">
                        <span class="material-symbols-rounded">update</span> Update Menu
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        // ===========================
        // FILTER & SEARCH LOGIC (Client Side)
        // ===========================
        function setFilter(inputId, value) {
            const input = document.getElementById(inputId);
            if (input) {
                input.value = value;
                applyAllFilters();
            }
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            setFilter('outletSelect', '');
            setFilter('kategoriSelect', '');
            setFilter('stokSelect', '');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');

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
                const outletFilter = document.getElementById('outletSelect') ? document.getElementById('outletSelect').value : '';
                const kategoriFilter = document.getElementById('kategoriSelect').value.toLowerCase();
                const stokFilter = document.getElementById('stokSelect').value;

                const items = document.querySelectorAll('.product-item');
                let visibleCount = 0;

                items.forEach(item => {
                    // Ambil Data Attributes
                    const nama = item.dataset.nama || '';
                    const kategori = item.dataset.kategori || '';
                    const harga = item.dataset.harga || '';
                    const stok = parseInt(item.dataset.stok) || 0;
                    const outletId = item.dataset.outletId || '';

                    // Logic Matching
                    const matchesSearch = !query ||
                        nama.includes(query) ||
                        kategori.includes(query) ||
                        harga.includes(query);

                    const matchesOutlet = !outletFilter || outletId === outletFilter;
                    const matchesKategori = !kategoriFilter || kategori === kategoriFilter;

                    let matchesStok = true;
                    if(stokFilter === 'low') matchesStok = stok <= 10;
                    if(stokFilter === 'safe') matchesStok = stok > 10;

                    if (matchesSearch && matchesOutlet && matchesKategori && matchesStok) {
                        item.style.display = ''; // Show
                        visibleCount++;
                    } else {
                        item.style.display = 'none'; // Hide
                    }
                });

                // Toggle Empty State
                const noDataMessage = document.getElementById('noDataMessage');
                if (noDataMessage) {
                    noDataMessage.style.display = visibleCount === 0 ? 'flex' : 'none';
                }
            }
        });

        // ===========================
        // ALPINE JS LOGIC
        // ===========================
        document.addEventListener('alpine:init', () => {
            // Logic untuk Combobox (Single Select)
            Alpine.data('combobox', (config) => ({
                items: config.items,
                value: config.initial || '',
                open: false,
                filteredItems() {
                    if (this.value === '') return this.items;
                    return this.items.filter(item =>
                        item.toLowerCase().includes(this.value.toLowerCase())
                    );
                },
                select(item) {
                    this.value = item;
                    this.open = false;
                },
                init() {
                    this.$watch('value', val => {
                        this.$refs.input.value = val;
                    });
                    this.$el.addEventListener('set-value', (e) => {
                        this.value = e.detail;
                    });
                }
            }));

            // Logic untuk Multi Select (Checkboxes)
            Alpine.data('multiSelect', (config) => ({
                items: config.items, // Array of Objects {id: 1, name: 'A'}
                selected: config.initial || [], // Array of IDs [1, 2]
                open: false,
                toggle() { this.open = !this.open; },
                close() { this.open = false; },
                getName(id) {
                    const found = this.items.find(i => i.id == id);
                    return found ? found.name : 'Unknown';
                },
                remove(id) {
                    this.selected = this.selected.filter(i => i != id);
                },
                init() {
                    // Listener untuk set value dari luar (misal tombol edit)
                    this.$el.addEventListener('set-value', (e) => {
                        const val = e.detail;
                        if(Array.isArray(val)) {
                            this.selected = val.map(v => parseInt(v));
                        } else if(val) {
                            this.selected = [parseInt(val)];
                        } else {
                            this.selected = [];
                        }
                    });
                }
            }));
        });

        // 1. FORMAT CURRENCY SCRIPT
        function formatCurrency(input) {
            let value = input.value.replace(/\D/g, '');
            if (value !== '') {
                value = new Intl.NumberFormat('id-ID').format(value);
            }
            input.value = value;
        }

        function cleanCurrencyInputs(form) {
            const currencyInputs = form.querySelectorAll('.currency-input');
            currencyInputs.forEach(input => {
                input.value = input.value.replace(/\./g, '');
            });
        }

        // 2. Modal Logic (Animasi)
        function toggleModal(modalId, contentId, backdropId, show) {
            const modal = document.getElementById(modalId);
            const content = document.getElementById(contentId);
            const backdrop = document.getElementById(backdropId);

            if (show) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    backdrop.classList.remove('opacity-0');
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 20);
            } else {
                backdrop.classList.add('opacity-0');
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 300);
            }
        }

        function openModal() { toggleModal('modalAddProduct', 'modalAddContent', 'modalAddBackdrop', true); }
        function closeModal() { toggleModal('modalAddProduct', 'modalAddContent', 'modalAddBackdrop', false); }
        function openModalEdit() { toggleModal('modalEditProduct', 'modalEditContent', 'modalEditBackdrop', true); }
        function closeModalEdit() { toggleModal('modalEditProduct', 'modalEditContent', 'modalEditBackdrop', false); }

        // 3. Edit Button Logic
        document.querySelectorAll('.btnEdit').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('editNama').value = this.dataset.nama;

                const katVal = this.dataset.kategori;
                const wrapperKategori = document.getElementById('wrapperEditKategori');
                wrapperKategori.dispatchEvent(new CustomEvent('set-value', { detail: katVal }));

                const ukVal = this.dataset.ukuran || '-';
                const wrapperUkuran = document.getElementById('wrapperEditUkuran');
                wrapperUkuran.dispatchEvent(new CustomEvent('set-value', { detail: ukVal }));

                const hargaRaw = this.dataset.harga;
                const modalRaw = this.dataset.modal || 0;

                const editHargaInput = document.getElementById('editHarga');
                const editModalInput = document.getElementById('editModal');

                editHargaInput.value = hargaRaw;
                editModalInput.value = modalRaw;
                formatCurrency(editHargaInput);
                formatCurrency(editModalInput);

                const wrapperEditOutlet = document.getElementById('wrapperEditOutlet');
                if (wrapperEditOutlet) {
                    const currentOutletId = this.dataset.outletId;
                    wrapperEditOutlet.dispatchEvent(new CustomEvent('set-value', { detail: currentOutletId ? [currentOutletId] : [] }));
                }

                document.getElementById('editStok').value = this.dataset.stok;
                document.getElementById('formEditProduct').action = "/products/" + this.dataset.id;

                openModalEdit();
            });
        });

        // 4. Delete Logic
        document.querySelectorAll('.delete-product-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                let id = this.dataset.id;
                let nama = this.dataset.nama;
                Swal.fire({
                    title: 'Hapus Menu?',
                    text: `Anda akan menghapus ${nama}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#f5f5f4',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-[2rem]',
                        confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                        cancelButton: 'rounded-xl px-6 py-2.5 font-bold text-stone-600'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = document.createElement("form");
                        form.action = "/products/" + id;
                        form.method = "POST";
                        form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #d6d3d1;
            border-radius: 20px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #a8a29e;
        }
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
