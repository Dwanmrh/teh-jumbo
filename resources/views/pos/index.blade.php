<x-app-layout>
    <x-slot name="title">Kasir</x-slot>

    {{-- Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Audio Feedback Assets --}}
    <audio id="beepSound" src="https://cdn.freesound.org/previews/546/546078_7862587-lq.mp3" preload="auto"></audio>
    <audio id="successSound" src="https://cdn.freesound.org/previews/772/772277_12520441-lq.mp3" preload="auto"></audio>

    {{-- WRAPPER UTAMA (Alpine.js Scope) --}}
    <div x-data="posSystem()" x-init="initSystem()" class="relative min-h-screen">

        {{-- ==========================================
             SECTION 1: HEADER & SEARCH TOOLS
             ========================================== --}}
        <div class="flex flex-col gap-6 mb-8 animate-[fadeIn_0.5s_ease-out]">
            {{-- Header Container --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                {{-- Title & Status --}}
                <div class="relative pl-1">
                    <h1 class="text-3xl sm:text-4xl font-black text-stone-800 tracking-tight leading-none">
                        Kasir <span class="text-brand-600">Utama</span>
                    </h1>

                    <div class="flex items-center gap-2 mt-2">
                        {{-- Indikator Status (Dot) --}}
                        <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>

                        <div class="flex flex-col">
                            {{-- LOGIKA TAMPILAN OUTLET (UPDATED DESIGN) --}}
                            @if(Auth::user()->role === 'admin')
                                {{-- Custom Dropdown Wrapper --}}
                                <div x-data="{ open: false }" class="relative z-50">

                                    {{-- 1. Trigger Button --}}
                                    <button @click="open = !open" @click.outside="open = false"
                                        class="group flex items-center gap-2 pl-3 pr-2 py-1.5 bg-white border border-stone-200 rounded-full shadow-sm hover:shadow-md hover:border-brand-500 transition-all duration-300">

                                        <div class="w-6 h-6 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                            <span class="material-symbols-rounded text-sm">storefront</span>
                                        </div>

                                        <div class="flex flex-col items-start mr-1">
                                            <span class="text-[9px] font-bold text-stone-400 uppercase tracking-wider leading-none mb-0.5">Lokasi</span>
                                            <span class="text-xs font-black text-stone-800 uppercase tracking-wide leading-none group-hover:text-brand-600 transition-colors">
                                                {{ request('outlet_id') ? $outlets->firstWhere('id', request('outlet_id'))->name : 'Semua Outlet' }}
                                            </span>
                                        </div>

                                        <div class="w-5 h-5 rounded-full bg-stone-100 flex items-center justify-center text-stone-400 group-hover:bg-brand-600 group-hover:text-white transition-all">
                                            <span class="material-symbols-rounded text-sm transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                                        </div>
                                    </button>

                                    {{-- 2. Dropdown Menu --}}
                                    <div x-show="open"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                        class="absolute left-0 top-full mt-2 w-64 bg-white rounded-2xl shadow-xl shadow-stone-200/50 border border-stone-100 overflow-hidden p-2 origin-top-left">

                                        <div class="flex flex-col gap-1 max-h-[300px] overflow-y-auto no-scrollbar">

                                            {{-- Opsi: Semua Outlet --}}
                                            <a href="{{ route('pos.index') }}"
                                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request('outlet_id') == '' ? 'bg-brand-50 text-brand-700' : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900' }}">
                                                <div class="w-8 h-8 rounded-lg {{ request('outlet_id') == '' ? 'bg-white text-brand-600 shadow-sm' : 'bg-stone-100 text-stone-400' }} flex items-center justify-center">
                                                    <span class="material-symbols-rounded text-lg">domain</span>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-xs font-black uppercase tracking-wide">Semua Outlet</p>
                                                    <p class="text-[10px] opacity-70 font-medium">Gabungan data stok</p>
                                                </div>
                                                @if(request('outlet_id') == '')
                                                    <span class="material-symbols-rounded text-brand-600 text-lg">check_circle</span>
                                                @endif
                                            </a>

                                            <div class="h-px bg-stone-100 my-1"></div>

                                            {{-- Loop Outlets --}}
                                            @foreach($outlets as $outlet)
                                                <a href="{{ route('pos.index', ['outlet_id' => $outlet->id]) }}"
                                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request('outlet_id') == $outlet->id ? 'bg-brand-50 text-brand-700' : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900' }}">

                                                    <div class="w-8 h-8 rounded-lg {{ request('outlet_id') == $outlet->id ? 'bg-white text-brand-600 shadow-sm' : 'bg-stone-100 text-stone-400' }} flex items-center justify-center">
                                                        <span class="material-symbols-rounded text-lg">store</span>
                                                    </div>

                                                    <div class="flex-1">
                                                        <p class="text-xs font-black uppercase tracking-wide">{{ $outlet->name }}</p>
                                                        <p class="text-[10px] opacity-70 font-medium truncate w-32">{{ $outlet->alamat ?? 'Cabang Terdaftar' }}</p>
                                                    </div>

                                                    @if(request('outlet_id') == $outlet->id)
                                                        <span class="material-symbols-rounded text-brand-600 text-lg">check_circle</span>
                                                    @endif
                                                </a>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Tampilan untuk Kasir Biasa (Non-Admin) --}}
                                <div class="flex items-center gap-2 pl-1 mt-1">
                                    <span class="material-symbols-rounded text-stone-400 text-sm">store</span>
                                    <p class="text-xs font-black text-stone-700 uppercase tracking-wider">
                                        {{ $currentOutletName }}
                                    </p>
                                </div>
                            @endif

                            <p class="text-[10px] font-medium text-stone-400 mt-1 pl-1">
                                {{ date('d M Y') }}
                            </p>
                        </div>

                    </div>
                </div>

                {{-- Search Bar --}}
                <div class="w-full md:max-w-sm relative group z-30">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-symbols-rounded text-stone-400 group-focus-within:text-brand-500 transition-colors">search</span>
                    </div>
                    <input type="text" x-model="search" x-ref="searchInput" autofocus placeholder="Cari menu (Nama/Kategori)..."
                        class="block w-full pl-11 pr-10 py-3.5 bg-white border border-stone-200 rounded-2xl text-sm font-bold text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 shadow-soft transition-all">

                    <button x-show="search.length > 0" @click="search = ''; $refs.searchInput.focus()" x-transition
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-stone-300 hover:text-stone-500 cursor-pointer">
                        <span class="material-symbols-rounded text-lg">cancel</span>
                    </button>
                </div>
            </div>

            {{-- Category Filter --}}
            <div class="w-full overflow-x-auto no-scrollbar mask-image-r">
                <div class="flex gap-2 sm:gap-3 pb-2 pl-1">
                    <button @click="setCategory('all')"
                        :class="activeCategory === 'all'
                            ? 'bg-stone-800 text-white shadow-lg shadow-stone-900/20 ring-2 ring-stone-800 scale-105'
                            : 'bg-white text-stone-500 border border-stone-200 hover:bg-stone-50 hover:border-stone-300'"
                        class="px-5 py-2.5 rounded-full text-xs font-extrabold uppercase tracking-wide whitespace-nowrap transition-all duration-300 active:scale-95 flex-shrink-0">
                        Semua Menu
                    </button>

                    @php
                        $styles = [
                            ['active' => 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30 border-transparent', 'hover' => 'hover:text-emerald-700 hover:bg-emerald-50 border-emerald-200'],
                            ['active' => 'bg-orange-500 text-white shadow-lg shadow-orange-500/30 border-transparent', 'hover' => 'hover:text-orange-700 hover:bg-orange-50 border-orange-200'],
                            ['active' => 'bg-blue-500 text-white shadow-lg shadow-blue-500/30 border-transparent', 'hover' => 'hover:text-blue-700 hover:bg-blue-50 border-blue-200'],
                            ['active' => 'bg-rose-500 text-white shadow-lg shadow-rose-500/30 border-transparent', 'hover' => 'hover:text-rose-700 hover:bg-rose-50 border-rose-200'],
                            ['active' => 'bg-purple-500 text-white shadow-lg shadow-purple-500/30 border-transparent', 'hover' => 'hover:text-purple-700 hover:bg-purple-50 border-purple-200'],
                        ];
                        $categories = $products->pluck('kategori')->filter()->unique()->values();
                    @endphp

                    @foreach($categories as $index => $cat)
                        @php $style = $styles[$index % count($styles)]; @endphp
                        <button @click="setCategory('{{ strtolower($cat) }}')"
                            :class="activeCategory === '{{ strtolower($cat) }}'
                                ? '{{ $style['active'] }} scale-105'
                                : 'bg-white text-stone-500 border {{ $style['hover'] }}'"
                            class="px-5 py-2.5 rounded-full text-xs font-extrabold uppercase tracking-wide whitespace-nowrap transition-all duration-300 active:scale-95 flex-shrink-0 border">
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ==========================================
             SECTION 2: MAIN GRID (PRODUCTS & CART)
             ========================================== --}}
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-start relative pb-20">

            {{-- LEFT: PRODUCT GRID --}}
            <div class="w-full lg:flex-1">
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 gap-4 sm:gap-5">
                    @forelse($products as $p)
                        @php $isHabis = $p->stok <= 0; @endphp

                        {{-- REVISI VIEW:
                             1. Hapus class 'grayscale' dan 'cursor-not-allowed'
                             2. Hapus pengecekan @if(!$isHabis) pada @click
                        --}}
                        <div x-show="filterProduct('{{ strtolower($p->nama) }}', '{{ strtolower($p->kategori) }}')"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-90"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="group relative bg-white rounded-[2rem] border border-stone-100 shadow-soft hover:shadow-glow hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden cursor-pointer h-full {{ $isHabis ? 'ring-2 ring-red-100' : '' }}"
                             @click="addToCart({{ $p->id }}, '{{ $p->nama }}', {{ $p->harga }}, {{ $p->stok }}, '{{ $p->ukuran }}')">

                            {{-- Image --}}
                            <div class="relative w-full pt-[90%] bg-stone-100 overflow-hidden">
                                <img src="{{ $p->foto ? asset('storage/'.$p->foto) : asset('assets/images/teh-jumbo.jpg') }}"
                                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                     alt="{{ $p->nama }}" loading="lazy"
                                     onerror="this.src='https://placehold.co/400x400/f5f5f4/a8a29e?text=No+Image'">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-40 group-hover:opacity-60 transition-opacity"></div>

                                <div class="absolute top-3 left-3 flex flex-col gap-1.5">
                                    @if($p->ukuran && $p->ukuran != '-')
                                        @php
                                            $sizeColor = match($p->ukuran) {
                                                'Jumbo' => 'bg-purple-500',
                                                'Sedang' => 'bg-blue-500',
                                                'Kecil' => 'bg-stone-500',
                                                default => 'bg-stone-700'
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider text-white rounded-md shadow-lg {{ $sizeColor }} border border-white/20">
                                            {{ $p->ukuran }}
                                        </span>
                                    @endif
                                </div>
                                <div class="absolute top-3 right-3">
                                    @php
                                        $stokBadgeColor = 'bg-black/40 text-white'; // Default Aman
                                        if($isHabis) {
                                            $stokBadgeColor = 'bg-rose-600 text-white'; // Habis
                                        } elseif($p->stok <= 10) {
                                            $stokBadgeColor = 'bg-orange-500 text-white animate-pulse'; // Sekarat
                                        }
                                    @endphp

                                    <span class="px-2 py-1 text-[9px] font-black uppercase tracking-wider rounded-lg shadow border border-white/20 backdrop-blur-md {{ $stokBadgeColor }}">
                                        {{ $isHabis ? 'HABIS' : 'Stok: ' . $p->stok }}
                                    </span>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-4 flex flex-col flex-1 relative">
                                <h3 class="text-xs sm:text-sm font-extrabold text-stone-800 leading-snug mb-3 line-clamp-2 group-hover:text-brand-600 transition-colors">
                                    {{ $p->nama }}
                                </h3>
                                <div class="mt-auto flex items-end justify-between gap-2">
                                    <div class="flex flex-col">
                                        <span class="text-[9px] font-bold text-stone-400 uppercase tracking-wide">Harga</span>
                                        <span class="text-stone-900 font-black text-sm sm:text-base">
                                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="w-9 h-9 rounded-full bg-stone-50 text-stone-800 border border-stone-100 flex items-center justify-center group-hover:bg-brand-500 group-hover:text-white group-hover:border-brand-400 transition-all shadow-sm active:scale-90 shrink-0">
                                        <span class="material-symbols-rounded text-xl">add</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 flex flex-col items-center justify-center text-stone-400 border-2 border-dashed border-stone-200 rounded-[2rem] bg-stone-50/50">
                            <span class="material-symbols-rounded text-6xl mb-4 text-stone-300">search_off</span>
                            <p class="text-sm font-bold">Produk tidak ditemukan</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- RIGHT: DESKTOP CART (Sticky) --}}
            <div class="hidden lg:block lg:w-[360px] xl:w-[380px] sticky top-[100px] h-[calc(100vh-140px)] shrink-0 transition-all duration-300">
                <div class="bg-white rounded-[2.5rem] shadow-soft border border-stone-100 flex flex-col h-full overflow-hidden relative ring-1 ring-stone-900/5">

                    <div class="px-6 py-5 border-b border-stone-100 bg-white/80 backdrop-blur-md flex justify-between items-center z-10">
                        <div>
                            <h2 class="font-black text-lg text-stone-800 flex items-center gap-2">
                                <span class="material-symbols-rounded text-brand-600 filled">shopping_cart</span>
                                Keranjang
                            </h2>
                            <p class="text-[10px] text-stone-400 font-bold uppercase tracking-wider mt-0.5">Order Hari Ini</p>
                        </div>
                        <button @click="clearCart()" x-show="Object.keys(cart).length > 0"
                            class="w-9 h-9 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-100 transition-colors active:scale-90" title="Hapus Semua">
                            <span class="material-symbols-rounded text-lg">delete</span>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-3 no-scrollbar bg-stone-50/50">
                        <template x-for="(item, id) in cart" :key="id">
                            <div class="bg-white p-4 rounded-[1.5rem] border border-stone-100 shadow-sm flex flex-col gap-3 relative group hover:shadow-md transition-all">
                                <div class="flex justify-between items-start">
                                    <div class="pr-6">
                                        <h4 class="font-bold text-stone-800 text-sm leading-tight mb-1" x-text="item.name"></h4>
                                        <span class="inline-block px-1.5 py-0.5 rounded-md bg-stone-100 text-[10px] font-bold text-stone-500 uppercase tracking-wide" x-text="item.size"></span>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-black text-stone-900 text-sm" x-text="formatRupiah(item.price * item.qty)"></p>
                                        <p class="text-[10px] text-stone-400 font-medium" x-text="'@' + formatRupiah(item.price)"></p>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center border-t border-dashed border-stone-100 pt-2">
                                    <div class="flex items-center gap-1 bg-stone-100/80 rounded-xl p-1">
                                        <button @click="updateQty(id, -1)" class="w-7 h-7 bg-white rounded-lg shadow-sm flex items-center justify-center text-stone-600 hover:text-stone-900 active:scale-90 transition font-bold text-xs">-</button>
                                        <span class="text-xs font-black w-8 text-center text-stone-800" x-text="item.qty"></span>
                                        <button @click="updateQty(id, 1)" class="w-7 h-7 bg-stone-800 text-white rounded-lg shadow-sm flex items-center justify-center hover:bg-brand-600 active:scale-90 transition font-bold text-xs">+</button>
                                    </div>
                                    <button @click="deleteItem(id)" class="text-stone-300 hover:text-rose-500 transition-colors flex items-center gap-1 text-[10px] font-bold uppercase tracking-wide px-2 py-1 rounded-lg hover:bg-rose-50">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </template>

                        <div x-show="Object.keys(cart).length === 0" class="h-full flex flex-col items-center justify-center text-stone-300 pb-10 opacity-60">
                            <span class="material-symbols-rounded text-6xl mb-2">production_quantity_limits</span>
                            <p class="text-xs font-bold text-center uppercase tracking-widest">Keranjang Kosong</p>
                        </div>
                    </div>

                    <div class="p-6 bg-white border-t border-stone-100 shadow-[0_-10px_40px_rgba(0,0,0,0.03)] z-20">
                        <div class="flex justify-between items-end mb-4">
                            <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Total Pembayaran</span>
                            <span class="text-2xl font-black text-brand-600 tracking-tight" x-text="formatRupiah(totalCart)"></span>
                        </div>
                        <button @click="openCheckoutModal()" :disabled="Object.keys(cart).length === 0"
                            class="w-full py-4 rounded-2xl font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-xl shadow-stone-200 active:scale-[0.98]"
                            :class="Object.keys(cart).length === 0 ? 'bg-stone-100 text-stone-300 cursor-not-allowed' : 'bg-stone-900 text-white hover:bg-black hover:shadow-stone-900/20'">
                            Lanjut Pembayaran <span class="material-symbols-rounded text-lg">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==========================================
             SECTION 3: MOBILE FLOATING BUTTON
             ========================================== --}}
        <div x-show="Object.keys(cart).length > 0"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-full opacity-0"
             class="lg:hidden fixed bottom-[88px] left-4 right-4 z-40">

             <div @click="mobileCartOpen = true"
                  class="bg-stone-900 text-white p-4 rounded-[2rem] shadow-2xl shadow-stone-900/30 flex justify-between items-center cursor-pointer active:scale-95 transition-transform border border-white/10 backdrop-blur-md relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
                <div class="flex flex-col relative z-10 pl-2">
                    <span class="text-[10px] text-stone-400 font-bold uppercase tracking-wider">Total</span>
                    <span class="font-black text-xl tracking-tight" x-text="formatRupiah(totalCart)"></span>
                </div>
                <div class="flex items-center gap-3 relative z-10">
                    <div class="flex items-center gap-1 bg-white/20 px-3 py-1.5 rounded-full text-[10px] font-bold backdrop-blur-sm">
                        <span x-text="Object.keys(cart).length"></span> Item
                    </div>
                    <div class="w-11 h-11 bg-brand-600 rounded-full flex items-center justify-center shadow-lg shadow-brand-500/50">
                        <span class="material-symbols-rounded text-xl">shopping_cart</span>
                    </div>
                </div>
             </div>
        </div>

        {{-- ==========================================
             SECTION 4: MOBILE CART DRAWER
             ========================================== --}}
        <template x-teleport="body">
            <div x-show="mobileCartOpen" x-cloak class="lg:hidden fixed inset-0 z-[150] flex items-end justify-center">
                {{-- Backdrop --}}
                <div class="absolute inset-0 bg-stone-900/60 backdrop-blur-sm transition-opacity"
                     @click="mobileCartOpen = false" x-show="mobileCartOpen"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

                {{-- Drawer Container --}}
                <div class="relative bg-stone-50 w-full rounded-t-[2.5rem] shadow-[0_-10px_40px_rgba(0,0,0,0.2)] h-[85vh] flex flex-col overflow-hidden"
                     x-show="mobileCartOpen"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">

                     {{-- Handle Bar --}}
                     <div class="absolute top-3 left-1/2 -translate-x-1/2 w-14 h-1.5 bg-stone-300 rounded-full"></div>

                     {{-- Header Drawer --}}
                     <div class="pt-8 pb-4 px-6 bg-white border-b border-stone-200 flex justify-between items-center shrink-0">
                        <div>
                            <h2 class="text-xl font-black text-stone-900">Rincian Pesanan</h2>
                            <p class="text-xs text-stone-500 font-medium">Periksa kembali pesanan anda</p>
                        </div>
                        <button @click="mobileCartOpen = false" class="w-12 h-12 bg-stone-100 rounded-full flex items-center justify-center text-stone-500 hover:bg-stone-200 transition active:scale-90">
                            <span class="material-symbols-rounded text-2xl">close</span>
                        </button>
                     </div>

                     {{-- List Item (Scrollable) --}}
                     <div class="flex-1 overflow-y-auto p-5 space-y-4 no-scrollbar">
                         <template x-for="(item, id) in cart" :key="id">
                             <div class="bg-white p-5 rounded-[1.75rem] shadow-sm border border-stone-100 flex justify-between items-start gap-3">
                                 <div class="flex-1">
                                     <h4 class="font-black text-stone-800 text-base mb-2 leading-tight" x-text="item.name"></h4>
                                     <div class="mb-4">
                                         <span class="inline-block px-2.5 py-1 rounded-lg bg-stone-100 text-[11px] font-bold text-stone-600 uppercase tracking-wide border border-stone-200" x-text="item.size"></span>
                                     </div>
                                     <div class="flex items-center gap-3">
                                         <button @click="updateQty(id, -1)"
                                            class="w-12 h-12 bg-stone-100 rounded-2xl text-stone-600 shadow-sm border border-stone-200 flex items-center justify-center active:scale-90 active:bg-stone-200 transition touch-manipulation">
                                             <span class="material-symbols-rounded text-xl font-bold">remove</span>
                                          </button>
                                          <span class="text-xl font-black w-10 text-center text-stone-800" x-text="item.qty"></span>
                                          <button @click="updateQty(id, 1)"
                                             class="w-12 h-12 bg-stone-900 text-white rounded-2xl shadow-lg shadow-stone-900/20 flex items-center justify-center active:scale-90 active:bg-black transition touch-manipulation">
                                              <span class="material-symbols-rounded text-xl font-bold">add</span>
                                           </button>
                                     </div>
                                 </div>
                                 <div class="text-right flex flex-col items-end justify-between h-full gap-4 pt-1">
                                     <div>
                                         <p class="font-black text-lg text-stone-900 tracking-tight" x-text="formatRupiah(item.price * item.qty)"></p>
                                         <p class="text-[11px] text-stone-400 font-medium" x-text="'@' + formatRupiah(item.price)"></p>
                                     </div>
                                     <button @click="deleteItem(id)"
                                         class="flex items-center gap-1.5 text-xs text-rose-600 font-bold uppercase tracking-wider bg-rose-50 px-4 py-2.5 rounded-xl border border-rose-100 hover:bg-rose-100 transition active:scale-95">
                                         <span class="material-symbols-rounded text-base">delete</span>
                                         Hapus
                                      </button>
                                 </div>
                             </div>
                          </template>
                          <div x-show="Object.keys(cart).length === 0" class="flex flex-col items-center justify-center py-20 text-stone-300 opacity-60">
                             <span class="material-symbols-rounded text-7xl mb-3">shopping_basket</span>
                             <p class="text-sm font-bold text-center uppercase tracking-widest">Keranjang Kosong</p>
                         </div>
                      </div>

                      {{-- Footer Total & Button --}}
                      <div class="p-6 bg-white border-t border-stone-200 pb-10 shrink-0 z-20 shadow-[0_-5px_20px_rgba(0,0,0,0.05)]">
                         <div class="flex justify-between items-end mb-4 px-1">
                             <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Total Tagihan</span>
                             <span class="text-2xl font-black text-brand-600 tracking-tight" x-text="formatRupiah(totalCart)"></span>
                         </div>
                         <button @click="openCheckoutModal()" class="w-full py-5 bg-stone-900 text-white rounded-[1.5rem] font-bold text-lg shadow-xl shadow-stone-900/20 active:scale-[0.98] transition-transform flex items-center justify-center gap-3">
                             <span>Bayar Sekarang</span>
                             <span class="material-symbols-rounded text-2xl">arrow_forward</span>
                         </button>
                      </div>
                </div>
            </div>
        </template>

        {{-- ==========================================
             SECTION 5: CHECKOUT MODAL
             ========================================== --}}
        <template x-teleport="body">
            <div x-show="checkoutModalOpen" x-cloak
                 class="fixed inset-0 z-[999] flex items-center justify-center px-4"
                 aria-labelledby="modal-title" role="dialog" aria-modal="true">

                {{-- Backdrop --}}
                <div class="absolute inset-0 bg-stone-900/80 backdrop-blur-md transition-opacity"
                     x-show="checkoutModalOpen"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     @click="checkoutModalOpen = false"></div>

                {{-- Modal Panel --}}
                <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]"
                     x-show="checkoutModalOpen"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="scale-95 opacity-0 translate-y-10" x-transition:enter-end="scale-100 opacity-100 translate-y-0"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="scale-100 opacity-100 translate-y-0" x-transition:leave-end="scale-95 opacity-0 translate-y-10">

                    {{-- Header Modal --}}
                    <div class="px-8 py-6 border-b border-stone-100 flex justify-between items-center bg-white sticky top-0 z-10">
                        <div>
                            <h2 class="text-2xl font-extrabold text-stone-900 tracking-tight">Checkout</h2>
                            <p class="text-xs text-stone-500 font-bold">Penyelesaian Transaksi</p>
                        </div>
                        <button @click="checkoutModalOpen = false" class="w-10 h-10 rounded-full bg-stone-50 border border-stone-200 flex items-center justify-center text-stone-400 hover:bg-stone-100 transition-colors">
                            <span class="material-symbols-rounded text-xl">close</span>
                        </button>
                    </div>

                    {{-- FORM CHECKOUT --}}
                    <form method="POST" action="{{ route('pos.checkout') }}" class="flex flex-col flex-1 overflow-hidden" @submit.prevent="submitCheckout($event)">
                        @csrf
                        <input type="hidden" name="cart_json" :value="JSON.stringify(cart)">
                        <input type="hidden" name="transaction_outlet_id" value="{{ $selectedOutletId }}">
                        <input type="hidden" name="total" :value="totalCart">
                        <input type="hidden" name="kembalian" :value="kembalian">
                        <input type="hidden" name="tipe_pesanan" :value="orderType">
                        <input type="hidden" name="metode_pembayaran" x-model="selectedPaymentMethod">
                        <input type="hidden" name="bayar" x-model="bayar">

                        <div class="p-8 overflow-y-auto space-y-6 no-scrollbar bg-stone-50/50">

                            {{-- Input Nama Pelanggan --}}
                            <div>
                                <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-wider mb-2">Nama Pelanggan (Opsional)</label>
                                <input type="text" name="nama_pelanggan" x-model="customerName" placeholder="Contoh: Mas Budi"
                                    class="w-full bg-white border border-stone-200 rounded-2xl px-5 py-4 font-bold text-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-800 transition-all placeholder:font-normal placeholder:text-stone-300">
                            </div>

                            {{-- Tipe Pesanan --}}
                            <div>
                                 <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-wider mb-2">Jenis Pesanan</label>
                                 <div class="bg-white p-1.5 rounded-[1.25rem] flex relative border border-stone-200 shadow-sm">
                                     <div class="absolute top-1.5 bottom-1.5 w-[calc(50%-6px)] bg-stone-900 rounded-2xl shadow-md transition-all duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)]"
                                          :class="orderType === 'Dine-in' ? 'left-1.5' : 'left-[calc(50%+3px)]'"></div>
                                     <button type="button" @click="orderType = 'Dine-in'" class="flex-1 relative z-10 py-3 text-xs font-bold text-center transition-colors uppercase tracking-wide" :class="orderType === 'Dine-in' ? 'text-white' : 'text-stone-400 hover:text-stone-600'">Minum di Tempat</button>
                                     <button type="button" @click="orderType = 'Take-away'" class="flex-1 relative z-10 py-3 text-xs font-bold text-center transition-colors uppercase tracking-wide" :class="orderType === 'Take-away' ? 'text-white' : 'text-stone-400 hover:text-stone-600'">Bungkus</button>
                                 </div>
                            </div>

                            <div class="border-t border-dashed border-stone-200"></div>

                            {{-- Total Display --}}
                            <div class="bg-white rounded-[2rem] p-6 border border-stone-200 text-center relative overflow-hidden shadow-sm ring-1 ring-stone-900/5">
                                <span class="text-[10px] font-black text-stone-400 uppercase tracking-widest">Total Tagihan</span>
                                <div class="text-4xl font-black text-stone-900 mt-1 mb-1 tracking-tight" x-text="formatRupiah(totalCart)"></div>
                            </div>

                            {{-- Metode Pembayaran --}}
                            <div>
                                <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-wider mb-3">Metode Pembayaran</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <template x-for="method in ['Tunai', 'Transfer', 'QRIS']">
                                        <div @click="setPaymentMethod(method)"
                                             :class="selectedPaymentMethod === method ? 'bg-stone-900 text-white shadow-lg shadow-stone-900/30 ring-2 ring-stone-900 scale-[1.02]' : 'bg-white border-stone-200 text-stone-500 hover:bg-stone-50 hover:border-stone-300'"
                                             class="cursor-pointer border rounded-2xl py-4 flex flex-col items-center justify-center gap-2 transition-all active:scale-95 text-center shadow-sm">
                                            <span class="material-symbols-rounded text-2xl"
                                                  x-text="method === 'Tunai' ? 'payments' : (method === 'Transfer' ? 'account_balance' : 'qr_code_scanner')"></span>
                                            <span class="text-[10px] font-bold uppercase tracking-wider" x-text="method"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- TAMPILAN KHUSUS QRIS --}}
                            <div x-show="selectedPaymentMethod === 'QRIS'" x-transition
                                 class="mt-4 bg-white border border-stone-200 rounded-[2rem] p-6 text-center shadow-sm relative overflow-hidden group">
                                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-red-500 to-gray-800"></div>
                                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-wider mb-4">Scan QRIS Untuk Membayar</p>
                                <div class="bg-white p-2 inline-block rounded-xl border border-stone-100 shadow-lg mx-auto relative">
                                    <img src="{{ asset('assets/images/qris.jpg') }}"
                                         alt="QRIS Teh Solo"
                                         class="w-56 h-auto object-contain mx-auto rounded-lg">
                                    <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-md border border-stone-100 shadow-sm">
                                        <span class="text-[10px] font-black text-stone-800">NMID: ID1025...</span>
                                    </div>
                                </div>
                                <div class="mt-6 flex flex-col items-center">
                                    <p class="text-xs text-stone-500 font-bold mb-1">Total yang harus dibayar</p>
                                    <div class="text-3xl font-black text-stone-900 tracking-tight bg-stone-100 px-4 py-2 rounded-xl border border-stone-200"
                                         x-text="formatRupiah(totalCart)"></div>
                                </div>
                                <div class="mt-5 p-3 bg-blue-50 text-blue-800 rounded-2xl text-xs flex items-start gap-2 text-left border border-blue-100">
                                    <span class="material-symbols-rounded text-lg shrink-0 mt-0.5">verified_user</span>
                                    <div>
                                        <span class="font-bold block mb-0.5">Konfirmasi Manual Diperlukan</span>
                                        <span>Pastikan notifikasi uang masuk sudah diterima di HP/Sistem sebelum menekan tombol "Proses Transaksi".</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Input Uang --}}
                            <div x-show="selectedPaymentMethod === 'Tunai'" x-transition>
                                <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-wider mb-2">Uang Diterima</label>
                                <div class="relative mb-3 group">
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-stone-400 font-black text-lg group-focus-within:text-stone-800 transition-colors">Rp</span>
                                    <input type="text" x-model="bayarDisplay" @input="updateBayar($event.target.value)" id="inputBayar"
                                        class="w-full bg-white border-2 border-stone-200 rounded-[1.5rem] pl-14 pr-6 py-4 font-black text-2xl text-stone-900 focus:outline-none focus:border-stone-900 focus:ring-0 transition-all placeholder:text-stone-300 placeholder:font-bold"
                                        placeholder="0">
                                </div>

                                <div class="grid grid-cols-4 gap-2">
                                    <button type="button" @click="setBayar(totalCart)" class="py-3 text-[10px] font-bold rounded-xl border border-stone-200 bg-stone-100 text-stone-600 hover:bg-stone-800 hover:text-white transition-all active:scale-95">Uang Pas</button>
                                    <button type="button" @click="setBayar(10000)" class="py-3 text-[10px] font-bold rounded-xl border border-stone-200 bg-white hover:bg-stone-100 transition-all active:scale-95">10K</button>
                                    <button type="button" @click="setBayar(20000)" class="py-3 text-[10px] font-bold rounded-xl border border-stone-200 bg-white hover:bg-stone-100 transition-all active:scale-95">20K</button>
                                    <button type="button" @click="setBayar(50000)" class="py-3 text-[10px] font-bold rounded-xl border border-stone-200 bg-white hover:bg-stone-100 transition-all active:scale-95">50K</button>
                                    <button type="button" @click="setBayar(100000)" class="col-span-4 py-3 text-[10px] font-bold rounded-xl border border-stone-200 bg-white hover:bg-stone-100 transition-all active:scale-95">100K</button>
                                </div>
                            </div>

                            <div x-show="selectedPaymentMethod === 'Tunai' && bayar >= totalCart"
                                 class="bg-emerald-50 border border-emerald-100 rounded-[2rem] p-6 flex flex-col items-center justify-center text-center animate-in fade-in slide-in-from-bottom-2">
                                <span class="text-emerald-800 font-bold text-xs uppercase tracking-widest mb-1">Kembalian</span>
                                <span class="text-emerald-600 font-black text-3xl tracking-tight" x-text="formatRupiah(kembalian)"></span>
                            </div>
                        </div>

                        <div class="p-6 border-t border-stone-100 bg-white shrink-0">
                            <button type="submit" :disabled="selectedPaymentMethod === 'Tunai' && bayar < totalCart"
                                class="w-full py-4 rounded-2xl font-bold text-base transition-all flex items-center justify-center gap-2 shadow-xl active:scale-[0.98]"
                                :class="(selectedPaymentMethod === 'Tunai' && bayar < totalCart)
                                    ? 'bg-stone-100 text-stone-300 cursor-not-allowed'
                                    : (selectedPaymentMethod === 'QRIS' ? 'bg-blue-600 text-white hover:bg-blue-700 shadow-blue-200' : 'bg-stone-900 text-white hover:bg-black hover:shadow-stone-900/20')">

                                <span x-text="selectedPaymentMethod === 'QRIS' ? 'Konfirmasi Pembayaran QRIS' : 'Proses Transaksi'"></span>
                                <span class="material-symbols-rounded">receipt_long</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

    </div> {{-- END WRAPPER UTAMA --}}

    {{-- ==========================================
         SECTION 6: ULTRA REALISTIC RECEIPT & PRINT LOGIC
         ========================================== --}}
    @if(session('print_data'))
        <div id="receipt-modal-wrapper"
             x-data="{ open: true }"
             x-show="open"
             class="fixed inset-0 z-[9999] flex items-center justify-center px-4"
             style="background-color: rgba(10, 10, 10, 0.9); backdrop-filter: blur(8px);">

            {{-- Wrapper Utama --}}
            <div class="w-full max-w-[450px] flex flex-col gap-6 animate-[slideUp_0.4s_ease-out] max-h-[95vh]">

                {{-- AREA STRUK KERTAS --}}
                <div class="relative w-full filter drop-shadow-[0_15px_50px_rgba(0,0,0,0.6)]">
                    {{-- 1. Hiasan Sobekan Atas --}}
                    <div class="h-4 w-full relative z-10"
                         style="background-color: #fcfcfc; mask-image: linear-gradient(to bottom, transparent, black), url('data:image/svg+xml;utf8,<svg width=\'12\' height=\'6\' viewBox=\'0 0 12 6\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M0 6L6 0L12 6H0Z\' fill=\'black\'/></svg>'); mask-size: 12px 6px; mask-repeat: repeat-x; mask-position: bottom; -webkit-mask-image: linear-gradient(to bottom, transparent, black), url('data:image/svg+xml;utf8,<svg width=\'12\' height=\'6\' viewBox=\'0 0 12 6\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M0 6L6 0L12 6H0Z\' fill=\'black\'/></svg>'); -webkit-mask-size: 12px 6px; -webkit-mask-repeat: repeat-x; -webkit-mask-position: bottom;">
                    </div>

                    {{-- 2. KONTAINER ISI --}}
                    <div class="bg-[#fcfcfc] px-8 pt-8 pb-4 w-full relative">
                        <div id="receiptArea" class="font-mono text-stone-900 text-base leading-relaxed relative">
                            <div class="no-print absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-56 h-56 border-[8px] border-stone-200/50 rounded-full flex items-center justify-center -rotate-12 pointer-events-none select-none z-0">
                                <span class="text-5xl font-black text-stone-200/50 uppercase tracking-widest">LUNAS</span>
                            </div>

                            <div class="relative z-10">
                                {{-- Header --}}
                                <div class="text-center mb-8">
                                    <div class="font-black text-2xl uppercase tracking-wider text-stone-900 mb-1">{{ session('print_data')['store_name'] }}</div>
                                    <div class="text-xs font-bold text-stone-500 uppercase px-4 leading-tight">{{ session('print_data')['address'] }}</div>
                                </div>

                                {{-- Meta Data --}}
                                <div class="border-b-2 border-dashed border-stone-300 pb-4 mb-4 text-xs font-bold text-stone-600 uppercase tracking-wide">
                                    <div class="flex justify-between mb-1"><span>Tanggal</span> <span class="text-stone-900">{{ session('print_data')['tanggal'] }}</span></div>
                                    <div class="flex justify-between mb-1"><span>No. Ref</span> <span class="text-stone-900">{{ session('print_data')['no_ref'] }}</span></div>
                                    <div class="flex justify-between mb-1"><span>Kasir</span> <span class="text-stone-900">{{ session('print_data')['kasir'] }}</span></div>
                                    <div class="flex justify-between"><span>Customer</span> <span class="text-stone-900">{{ session('print_data')['nama_pelanggan'] }}</span></div>
                                </div>

                                {{-- Items List --}}
                                <div class="pb-4 mb-4 border-b-2 border-dashed border-stone-300 min-h-[100px]">
                                    @foreach(session('print_data')['items'] as $item)
                                        <div class="mb-3 last:mb-0">
                                            <div class="font-black text-sm text-stone-800 uppercase leading-tight">{{ $item['name'] }} @if(isset($item['ukuran']) && $item['ukuran'] != '-')({{ $item['ukuran'] }})@endif</div>
                                            <div class="flex justify-between text-xs pl-2 text-stone-500 mt-1 font-medium">
                                                <span>{{ $item['qty'] }} x {{ number_format($item['price'], 0, ',', '.') }}</span>
                                                <span class="text-stone-900 font-bold">{{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Totals Section --}}
                                <div class="space-y-1 mb-8">
                                    <div class="flex justify-between text-2xl font-black text-stone-900 items-end">
                                        <span class="text-xs font-bold text-stone-500 mb-1">TOTAL</span>
                                        <span>{{ number_format(session('print_data')['total'], 0, ',', '.') }}</span>
                                    </div>

                                    {{-- Payment Details --}}
                                    <div class="pt-3 mt-3 border-t border-stone-200">
                                        @if(session('print_data')['metode'] == 'Tunai')
                                            <div class="flex justify-between text-xs font-bold text-stone-500">
                                                <span>TUNAI</span>
                                                <span>{{ number_format(session('print_data')['bayar'], 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between text-xs font-bold text-stone-500 mt-1.5">
                                                <span>KEMBALIAN</span>
                                                <span>{{ number_format(session('print_data')['kembali'], 0, ',', '.') }}</span>
                                            </div>
                                        @else
                                            <div class="flex justify-between items-center bg-stone-100 p-2.5 rounded-lg border border-stone-200/50">
                                                <span class="text-[11px] font-bold uppercase text-stone-500">Metode Bayar</span>
                                                <span class="text-xs font-black uppercase text-stone-800">{{ session('print_data')['metode'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Footer & Barcode Simulation --}}
                                <div class="text-center">
                                    <p class="font-bold text-[11px] text-stone-800 mb-4 uppercase">*** Terima Kasih ***</p>
                                    <div class="h-10 w-full max-w-[240px] mx-auto flex items-stretch justify-center gap-[1px] opacity-80 overflow-hidden mb-1">
                                        @for($i = 0; $i < 50; $i++)
                                            <div class="bg-stone-900 w-[{{ rand(1,4) }}px]"></div>
                                            <div class="bg-transparent w-[{{ rand(1,3) }}px]"></div>
                                        @endfor
                                    </div>
                                    <p class="text-[10px] font-mono text-stone-400 tracking-widest">{{ session('print_data')['no_ref'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Hiasan Sobekan Bawah --}}
                    <div class="h-4 w-full relative z-10"
                         style="background-color: #fcfcfc; mask-image: url('data:image/svg+xml;utf8,<svg width=\'12\' height=\'6\' viewBox=\'0 0 12 6\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M0 0L6 6L12 0H0Z\' fill=\'black\'/></svg>'); mask-size: 12px 6px; mask-repeat: repeat-x; mask-position: top; -webkit-mask-image: url('data:image/svg+xml;utf8,<svg width=\'12\' height=\'6\' viewBox=\'0 0 12 6\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M0 0L6 6L12 0H0Z\' fill=\'black\'/></svg>'); -webkit-mask-size: 12px 6px; -webkit-mask-repeat: repeat-x; -webkit-mask-position: top;">
                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="grid grid-cols-2 gap-4 shrink-0 px-2">
                    <button onclick="printReceipt()" class="col-span-1 bg-white text-stone-900 py-4 rounded-2xl font-black text-sm hover:bg-emerald-50 shadow-xl shadow-black/20 active:scale-95 transition-all flex items-center justify-center gap-2 group border border-white/10">
                        <span class="material-symbols-rounded group-hover:animate-bounce">print</span> CETAK
                    </button>
                    <a href="{{ request()->url() }}" class="col-span-1 bg-rose-600 text-white py-4 rounded-2xl font-bold text-sm hover:bg-rose-700 shadow-xl shadow-rose-900/30 active:scale-95 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-rounded">close</span> TUTUP
                    </a>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var audio = document.getElementById('successSound');
                if(audio) {
                    audio.currentTime = 0;
                    audio.play().catch(e => console.log("Audio autoplay prevented."));
                }
            });
        </script>
    @endif

    {{-- Script Print Thermal Optimization --}}
    <script>
        function printReceipt() {
            const content = document.getElementById('receiptArea').innerHTML;
            const iframe = document.createElement('iframe');
            iframe.style.position = 'absolute';
            iframe.style.width = '0px';
            iframe.style.height = '0px';
            iframe.style.border = 'none';
            document.body.appendChild(iframe);

            const doc = iframe.contentWindow.document;
            doc.open();
            doc.write('<html><head><title>Struk Transaksi</title>');
            doc.write('<style>');
            doc.write(`
                @page { margin: 0; size: auto; }
                body { font-family: 'Courier New', monospace; margin: 0; padding: 5px 2px; font-size: 12px; color: #000; width: 58mm; }
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .flex { display: flex; justify-content: space-between; align-items: center; }
                .font-black { font-weight: 900; }
                .font-bold { font-weight: bold; }
                .uppercase { text-transform: uppercase; }
                .mb-1 { margin-bottom: 4px; } .mb-3 { margin-bottom: 12px; } .mb-4 { margin-bottom: 16px; } .mb-8 { margin-bottom: 24px; }
                .mt-1 { margin-top: 4px; } .mt-1\\.5 { margin-top: 6px; }
                .pb-4 { padding-bottom: 16px; } .pl-2 { padding-left: 8px; } .pt-3 { padding-top: 12px; } .px-4 { padding-left: 16px; padding-right: 16px; }
                .text-xs { font-size: 10px; } .text-sm { font-size: 12px; } .text-base { font-size: 14px; } .text-xl { font-size: 18px; } .text-2xl { font-size: 22px; }
                .border-b-2 { border-bottom: 1px dashed #000; } .border-t { border-top: 1px dashed #000; }
                .text-stone-900, .text-stone-800, .text-stone-600, .text-stone-500 { color: #000; }
                .bg-stone-900, .bg-black { background-color: #000 !important; -webkit-print-color-adjust: exact; }
                .bg-transparent { background-color: transparent !important; }
                .h-10 { height: 40px; margin-top: 10px; display: flex; justify-content: center; overflow: hidden; }
                .bg-\\[\\#fcfcfc\\] { background: none; } .bg-stone-100 { background: none; border: 1px solid #000; }
                .absolute, .shadow-lg, .rounded-lg, .rounded-xl, .material-symbols-rounded { display: none; }
            `);
            doc.write('</style>');
            doc.write('</head><body>');
            doc.write('<div style="padding-bottom: 20px;">' + content + '</div>');
            doc.write('</body></html>');
            doc.close();

            iframe.contentWindow.focus();
            setTimeout(() => {
                iframe.contentWindow.print();
                setTimeout(() => { document.body.removeChild(iframe); }, 2000);
            }, 500);
        }
    </script>

    {{-- LOGIC JS (REVISI: HAPUS BLOKIR STOK) --}}
    <script>
        function posSystem() {
            return {
                search: '',
                activeCategory: 'all',
                cart: {},
                mobileCartOpen: false,
                checkoutModalOpen: false,
                customerName: '',
                orderType: 'Take-away',
                selectedPaymentMethod: 'Tunai',
                bayar: 0,
                bayarDisplay: '',

                initSystem() {
                    document.addEventListener('keydown', (e) => {
                        if (e.key === '/' && !this.checkoutModalOpen) {
                            e.preventDefault();
                            this.$refs.searchInput.focus();
                        }
                    });
                },

                playSound(type) {
                    const audio = document.getElementById(type === 'success' ? 'successSound' : 'beepSound');
                    if(audio) {
                        audio.currentTime = 0;
                        audio.play().catch(e => console.log('Audio blocked', e));
                    }
                },

                filterProduct(pName, pCat) {
                    const matchesSearch = pName.includes(this.search.toLowerCase()) || pCat.includes(this.search.toLowerCase());
                    const matchesCat = this.activeCategory === 'all' || pCat === this.activeCategory;
                    return matchesSearch && matchesCat;
                },
                setCategory(cat) { this.activeCategory = cat; },

                // REVISI: HAPUS VALIDASI MAXSTOCK
                addToCart(id, name, price, maxStock, size) {
                    this.playSound('beep');

                    if (this.cart[id]) {
                        // REVISI: Validasi maxStock dihapus agar bisa minus
                        this.cart[id].qty++;
                    } else {
                        // REVISI: Validasi stok kosong dihapus agar bisa minus
                        this.cart[id] = { name: name, price: price, qty: 1, maxStock: maxStock, size: size || '-' };
                    }
                },

                // REVISI: HAPUS VALIDASI MAXSTOCK
                updateQty(id, change) {
                    if (this.cart[id]) {
                        const newQty = this.cart[id].qty + change;
                        // Validasi batas atas dihapus
                        this.cart[id].qty = newQty;
                        if (this.cart[id].qty <= 0) delete this.cart[id];
                        else this.playSound('beep');
                    }
                },

                deleteItem(id) { delete this.cart[id]; },

                clearCart() {
                    Swal.fire({
                        title: 'Kosongkan?', text: "Semua item akan dihapus.", icon: 'warning', showCancelButton: true,
                        confirmButtonColor: '#1c1917', cancelButtonColor: '#f5f5f4', confirmButtonText: 'Ya', cancelButtonText: 'Batal',
                        customClass: { popup: 'rounded-[2rem] font-sans', confirmButton: 'rounded-xl', cancelButton: 'rounded-xl text-stone-600' }
                    }).then((result) => { if (result.isConfirmed) this.cart = {}; });
                },

                get totalCart() {
                    let total = 0;
                    for (const id in this.cart) { total += this.cart[id].price * this.cart[id].qty; }
                    return total;
                },

                get kembalian() {
                    if(this.selectedPaymentMethod !== 'Tunai') return 0;
                    return Math.max(0, this.bayar - this.totalCart);
                },

                openCheckoutModal() {
                    this.mobileCartOpen = false; this.checkoutModalOpen = true;
                    this.bayar = 0; this.bayarDisplay = ''; this.customerName = '';
                    this.orderType = 'Take-away';
                    this.selectedPaymentMethod = 'Tunai';
                    setTimeout(() => {
                        if(this.selectedPaymentMethod === 'Tunai' && document.getElementById('inputBayar')) {
                            document.getElementById('inputBayar').focus();
                        }
                    }, 300);
                },

                setPaymentMethod(method) {
                    this.selectedPaymentMethod = method;
                    if (method !== 'Tunai') {
                        this.bayar = this.totalCart;
                        this.bayarDisplay = this.formatRupiah(this.totalCart);
                    } else {
                        this.bayar = 0;
                        this.bayarDisplay = '';
                        setTimeout(() => document.getElementById('inputBayar').focus(), 100);
                    }
                },

                updateBayar(val) {
                    let number = val.replace(/[^0-9]/g, '');
                    this.bayar = parseInt(number) || 0;
                    this.bayarDisplay = number ? new Intl.NumberFormat('id-ID').format(number) : '';
                },

                setBayar(amount) {
                    this.bayar = amount;
                    this.bayarDisplay = new Intl.NumberFormat('id-ID').format(amount);
                },

                submitCheckout(e) {
                    if(this.selectedPaymentMethod === 'Tunai' && this.bayar < this.totalCart) {
                        this.playSound('beep');
                        this.showError('Pembayaran Kurang', 'Nominal uang tidak mencukupi.');
                        return;
                    }
                    if(Object.keys(this.cart).length === 0) {
                        this.showError('Error', 'Keranjang kosong');
                        return;
                    }
                    let btn = e.target.querySelector('button[type="submit"]');
                    if(btn) {
                        btn.innerHTML = '<span class="animate-spin material-symbols-rounded">progress_activity</span> Memproses...';
                        btn.disabled = true;
                        btn.classList.add('opacity-75', 'cursor-not-allowed');
                    }
                    e.target.submit();
                },

                formatRupiah(number) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number); },
                showToast(title) {
                    const Toast = Swal.mixin({ toast: true, position: 'bottom-center', showConfirmButton: false, timer: 1000, background: '#1c1917', color: '#fff', iconColor: '#ea580c', customClass: { popup: 'rounded-2xl mb-20 font-sans font-bold' } });
                    Toast.fire({ icon: 'success', title: title });
                },
                showError(title, text) {
                    Swal.fire({ icon: 'error', title: title, text: text, toast: true, position: 'top-center', showConfirmButton: false, timer: 2000, background: '#1c1917', color: '#fff', iconColor: '#f43f5e', customClass: { popup: 'rounded-2xl font-sans font-bold' } });
                }
            }
        }
    </script>

    <style>
        .mask-image-r{mask-image:linear-gradient(to right,black 90%,transparent 100%)}
        .no-scrollbar::-webkit-scrollbar{display:none}
        .no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button {-webkit-appearance: none; margin: 0;}
    </style>
</x-app-layout>
