<x-app-layout>
    {{-- Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- WRAPPER UTAMA --}}
    <div x-data="posSystem()" x-init="initSystem()" class="relative min-h-screen bg-transparent">

        {{-- ==========================================
             SECTION 1: HEADER POS & SEARCH
             ========================================== --}}
        <div class="sticky top-[72px] sm:top-[160px] z-30 mb-6 sm:mb-8 transition-all duration-300">

            {{-- HEADER CARD --}}
            <div class="bg-white/90 backdrop-blur-xl p-5 sm:p-5 rounded-[2rem] shadow-soft border border-stone-200/60 ring-1 ring-black/5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 sm:gap-4">

                {{-- Identitas Halaman --}}
                <div class="flex items-center gap-4 w-full md:w-auto">
                    {{-- Icon Box --}}
                    <div class="w-12 h-12 sm:w-12 sm:h-12 rounded-2xl bg-stone-900 text-white flex items-center justify-center shadow-lg shadow-stone-900/20 shrink-0">
                        <span class="material-symbols-rounded text-2xl">point_of_sale</span>
                    </div>

                    {{-- Text --}}
                    <div>
                        <h1 class="text-xl font-black text-stone-800 tracking-tight leading-none">KASIR UTAMA</h1>
                        <p class="text-[10px] sm:text-[11px] font-bold text-stone-400 mt-1.5 uppercase tracking-wide flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                            <span>Sistem Aktif</span>
                            <span>&bull;</span>
                            <span>{{ date('d M Y') }}</span>
                        </p>
                    </div>
                </div>

                {{-- Search Bar --}}
                <div class="relative w-full md:max-w-md group mt-1 sm:mt-0">
                    <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 group-focus-within:text-brand-600 transition-colors text-xl">search</span>
                    <input type="text" x-model="search" placeholder="Cari menu atau kategori..."
                        class="w-full bg-stone-100/50 border border-stone-200 rounded-2xl pl-12 pr-10 py-3.5 sm:py-3 font-bold text-sm text-stone-800 placeholder:text-stone-400 focus:bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all shadow-inner">

                    {{-- Tombol Clear Search --}}
                    <button x-show="search.length > 0" @click="search = ''"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600 transition-colors">
                        <span class="material-symbols-rounded text-lg">cancel</span>
                    </button>
                </div>
            </div>

            {{-- Kategori Pills --}}
            <div class="mt-5 sm:mt-4 w-full overflow-x-auto no-scrollbar mask-image-r">
                <div class="flex gap-2.5 sm:gap-2 pb-2 pl-1">
                    {{-- Tombol SEMUA (Netral) --}}
                    <button @click="setCategory('all')"
                        :class="activeCategory === 'all'
                            ? 'bg-stone-800 text-white shadow-lg shadow-stone-900/20 ring-2 ring-stone-800 border-transparent'
                            : 'bg-white text-stone-500 border border-stone-200 hover:bg-stone-100'"
                        class="px-5 py-2.5 sm:py-2.5 rounded-xl text-xs font-extrabold uppercase tracking-wide whitespace-nowrap transition-all duration-300 active:scale-95 border">
                        Semua
                    </button>

                    @php
                        $styles = [
                            ['active' => 'bg-emerald-100 text-emerald-800 border-emerald-200 shadow-emerald-500/20', 'hover' => 'hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200'],
                            ['active' => 'bg-blue-100 text-blue-800 border-blue-200 shadow-blue-500/20',       'hover' => 'hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200'],
                            ['active' => 'bg-orange-100 text-orange-800 border-orange-200 shadow-orange-500/20', 'hover' => 'hover:bg-orange-50 hover:text-orange-700 hover:border-orange-200'],
                            ['active' => 'bg-purple-100 text-purple-800 border-purple-200 shadow-purple-500/20', 'hover' => 'hover:bg-purple-50 hover:text-purple-700 hover:border-purple-200'],
                            ['active' => 'bg-rose-100 text-rose-800 border-rose-200 shadow-rose-500/20',       'hover' => 'hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200'],
                        ];

                        $categories = $products->pluck('kategori')->filter()->unique()->values();
                    @endphp

                    @foreach($categories as $index => $cat)
                        @php
                            $style = $styles[$index % count($styles)];
                        @endphp

                        <button @click="setCategory('{{ strtolower($cat) }}')"
                            :class="activeCategory === '{{ strtolower($cat) }}'
                                ? '{{ $style['active'] }} ring-1 ring-black/5 scale-[1.02]'
                                : 'bg-white text-stone-500 border-stone-200 {{ $style['hover'] }}'"
                            class="px-5 py-2.5 sm:py-2.5 rounded-xl text-xs font-extrabold uppercase tracking-wide whitespace-nowrap transition-all duration-300 active:scale-95 border">
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ==========================================
             SECTION 2: GRID SYSTEM (PRODUCTS & CART)
             ========================================== --}}
        <div class="flex flex-col lg:flex-row gap-6 sm:gap-6 lg:gap-8 items-start relative">

            {{-- LEFT: PRODUCT GRID --}}
            <div class="w-full lg:flex-1">
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 gap-4 sm:gap-5 pb-32 sm:pb-0">
                    @forelse($products as $p)
                        @php $isHabis = $p->stok <= 0; @endphp

                        {{-- CARD PRODUK --}}
                        <div x-show="filterProduct('{{ strtolower($p->nama) }}', '{{ strtolower($p->kategori) }}')"
                             class="group relative bg-white rounded-[1.75rem] sm:rounded-[2rem] border border-stone-100 shadow-sm hover:shadow-glow hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden cursor-pointer {{ $isHabis ? 'opacity-50 grayscale cursor-not-allowed' : '' }}"
                             @if(!$isHabis) @click="addToCart({{ $p->id }}, '{{ $p->nama }}', {{ $p->harga }}, {{ $p->stok }}, '{{ $p->ukuran }}')" @endif>

                            {{-- Image Container --}}
                            <div class="relative w-full pt-[80%] bg-stone-100 overflow-hidden">
                                <img src="{{ $p->foto ? asset('storage/'.$p->foto) : asset('assets/images/teh-jumbo.jpg') }}"
                                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                     alt="{{ $p->nama }}" loading="lazy"
                                     onerror="this.src='https://placehold.co/400x300/f5f5f4/a8a29e?text=No+Image'">

                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>

                                {{-- Badge Ukuran --}}
                                <div class="absolute top-3 left-3 z-10 flex flex-col items-start gap-1">
                                    @php
                                        $sizeColor = match($p->ukuran) {
                                            'Jumbo' => 'bg-purple-600 shadow-purple-500/30',
                                            'Sedang' => 'bg-blue-500 shadow-blue-500/30',
                                            'Kecil' => 'bg-stone-500 shadow-stone-500/30',
                                            default => 'bg-stone-800'
                                        };
                                    @endphp
                                    @if($p->ukuran && $p->ukuran != '-')
                                        <span class="px-2 py-1 text-[9px] font-black uppercase tracking-wider {{ $sizeColor }} text-white rounded-md shadow-lg border border-white/20">
                                            {{ $p->ukuran }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Badge Stok --}}
                                <div class="absolute top-3 right-3 z-10">
                                    <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-wider rounded-lg shadow border border-white/20 backdrop-blur-md {{ $isHabis ? 'bg-rose-600 text-white' : 'bg-black/60 text-white' }}">
                                        {{ $isHabis ? 'HABIS' : 'Stok: ' . $p->stok }}
                                    </span>
                                </div>
                            </div>

                            {{-- Info Produk --}}
                            <div class="p-4 flex flex-col flex-1">
                                <h3 class="text-xs sm:text-sm font-extrabold text-stone-800 leading-snug mb-2 line-clamp-2 group-hover:text-brand-600 transition-colors">
                                    {{ $p->nama }}
                                </h3>

                                <div class="mt-auto flex items-center justify-between gap-2">
                                    <div class="flex flex-col">
                                        <span class="text-[9px] sm:text-[10px] font-bold text-stone-400 uppercase">Harga</span>
                                        <span class="text-stone-900 font-black text-sm sm:text-base">
                                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="w-8 h-8 rounded-full bg-stone-100 text-stone-800 flex items-center justify-center group-hover:bg-brand-600 group-hover:text-white transition-all shadow-sm active:scale-90 shrink-0">
                                        <span class="material-symbols-rounded text-xl">add</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 flex flex-col items-center justify-center text-stone-400 border-2 border-dashed border-stone-200 rounded-[2rem] bg-stone-50/50">
                            <span class="material-symbols-rounded text-6xl mb-4 text-stone-300">inventory_2</span>
                            <p class="text-sm font-bold">Produk tidak ditemukan</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- RIGHT: DESKTOP CART (Sticky Sidebar) --}}
            <div class="hidden lg:block lg:w-[320px] xl:w-[350px] sticky top-[160px] h-[calc(100vh-180px)] shrink-0">
                <div class="bg-white rounded-[2.5rem] shadow-soft border border-stone-100 flex flex-col h-full overflow-hidden relative ring-1 ring-black/5">

                    {{-- Cart Header --}}
                    <div class="px-6 py-5 border-b border-stone-100 bg-white flex justify-between items-center z-10">
                        <div>
                            <h2 class="font-black text-lg text-stone-800 flex items-center gap-2">
                                <span class="material-symbols-rounded text-brand-600">shopping_cart</span> Keranjang
                            </h2>
                            <p class="text-[10px] text-stone-400 font-bold uppercase tracking-wider mt-0.5">Transaksi Penjualan</p>
                        </div>
                        <button @click="clearCart()" x-show="Object.keys(cart).length > 0"
                            class="text-rose-500 hover:bg-rose-50 p-2 rounded-xl transition-all active:scale-90" title="Hapus Semua">
                            <span class="material-symbols-rounded text-xl">delete_sweep</span>
                        </button>
                    </div>

                    {{-- Cart Items --}}
                    <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar bg-stone-50/30">
                        <template x-for="(item, id) in cart" :key="id">
                            <div class="bg-white p-4 rounded-[1.25rem] border border-stone-100 shadow-sm flex flex-col gap-2 relative group hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start pr-6">
                                    <div>
                                        <h4 class="font-bold text-stone-800 text-sm leading-tight" x-text="item.name"></h4>
                                        <p class="text-[10px] text-stone-400 font-bold mt-1" x-text="item.size"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-black text-stone-900 text-sm" x-text="formatRupiah(item.price * item.qty)"></p>
                                        <p class="text-[10px] text-stone-400 font-medium" x-text="'@' + formatRupiah(item.price)"></p>
                                    </div>
                                </div>

                                {{-- Qty Control --}}
                                <div class="flex justify-between items-center mt-1">
                                    <div class="flex items-center gap-1 bg-stone-100 rounded-lg p-1">
                                        <button @click="updateQty(id, -1)" class="w-7 h-7 bg-white rounded-md shadow-sm flex items-center justify-center text-stone-600 hover:text-stone-900 active:scale-90 transition font-bold text-xs">-</button>
                                        <span class="text-xs font-black w-8 text-center" x-text="item.qty"></span>
                                        <button @click="updateQty(id, 1)" class="w-7 h-7 bg-stone-800 text-white rounded-md shadow-sm flex items-center justify-center hover:bg-brand-600 active:scale-90 transition font-bold text-xs">+</button>
                                    </div>

                                    {{-- Delete Item Button --}}
                                    <button @click="deleteItem(id)" class="absolute top-2 right-2 text-stone-300 hover:text-rose-500 transition-colors">
                                        <span class="material-symbols-rounded text-lg">close</span>
                                    </button>
                                </div>
                            </div>
                        </template>

                        {{-- Empty State --}}
                        <div x-show="Object.keys(cart).length === 0" class="h-full flex flex-col items-center justify-center text-stone-300 pb-10 opacity-60">
                            <span class="material-symbols-rounded text-6xl mb-2">add_shopping_cart</span>
                            <p class="text-xs font-bold text-center">Belum ada item</p>
                        </div>
                    </div>

                    {{-- Cart Footer --}}
                    <div class="p-6 bg-white border-t border-stone-100 shadow-[0_-10px_40px_rgba(0,0,0,0.03)] z-20">
                        <div class="flex justify-between items-end mb-4">
                            <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Total Tagihan</span>
                            <span class="text-2xl font-black text-stone-900 tracking-tight" x-text="formatRupiah(totalCart)"></span>
                        </div>
                        <button @click="openCheckoutModal()" :disabled="Object.keys(cart).length === 0"
                            class="w-full py-4 rounded-2xl font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-lg shadow-brand-500/20 active:scale-[0.98]"
                            :class="Object.keys(cart).length === 0 ? 'bg-stone-200 text-stone-400 cursor-not-allowed' : 'bg-stone-900 text-white hover:bg-black'">
                            Proses Pembayaran <span class="material-symbols-rounded text-lg">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==========================================
             SECTION 3: MOBILE FLOATING BUTTON
             ========================================== --}}
        <div x-show="Object.keys(cart).length > 0"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-full opacity-0"
             class="lg:hidden fixed bottom-28 left-4 right-4 z-40">

             <div @click="mobileCartOpen = true"
                  class="bg-stone-900 text-white p-4 rounded-[1.75rem] shadow-2xl shadow-stone-900/40 flex justify-between items-center cursor-pointer active:scale-95 transition-transform border border-white/10 backdrop-blur-md relative overflow-hidden">
                <div class="flex flex-col relative z-10">
                    <span class="text-[10px] text-stone-400 font-bold uppercase tracking-wider">Total</span>
                    <span class="font-black text-xl tracking-tight" x-text="formatRupiah(totalCart)"></span>
                </div>
                <div class="flex items-center gap-3 relative z-10">
                    <div class="flex items-center gap-1 bg-white/20 px-3 py-1 rounded-full text-[10px] font-bold">
                        <span x-text="Object.keys(cart).length"></span> Item
                    </div>
                    <div class="w-10 h-10 bg-brand-600 rounded-full flex items-center justify-center shadow-lg shadow-brand-500/50">
                        <span class="material-symbols-rounded text-xl">shopping_cart</span>
                    </div>
                </div>
             </div>
        </div>

        {{-- MOBILE CART DRAWER --}}
        <div x-show="mobileCartOpen" x-cloak class="lg:hidden fixed inset-0 z-[70] flex items-end justify-center">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
                 @click="mobileCartOpen = false" x-show="mobileCartOpen"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

            <div class="relative bg-stone-50 w-full rounded-t-[2.5rem] shadow-2xl h-[85vh] flex flex-col overflow-hidden"
                 x-show="mobileCartOpen"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">

                 <div class="p-6 bg-white border-b border-stone-200 flex justify-between items-center rounded-t-[2.5rem]">
                    <h2 class="text-xl font-black text-stone-900">Rincian Pesanan</h2>
                    <button @click="mobileCartOpen = false" class="w-10 h-10 bg-stone-100 rounded-full flex items-center justify-center text-stone-500 hover:bg-stone-200 transition">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                 </div>

                 <div class="flex-1 overflow-y-auto p-5 space-y-4">
                     <template x-for="(item, id) in cart" :key="id">
                         <div class="bg-white p-4 rounded-2xl shadow-sm border border-stone-100 flex justify-between items-center">
                             <div>
                                 <h4 class="font-bold text-stone-800 text-sm" x-text="item.name"></h4>
                                 <p class="text-[10px] text-stone-400 font-bold mb-2" x-text="item.size"></p>
                                 <div class="flex items-center gap-2">
                                     <button @click="updateQty(id, -1)" class="w-7 h-7 bg-stone-100 rounded text-stone-600 font-bold text-xs">-</button>
                                     <span class="text-xs font-black" x-text="item.qty"></span>
                                     <button @click="updateQty(id, 1)" class="w-7 h-7 bg-stone-800 text-white rounded font-bold text-xs">+</button>
                                 </div>
                             </div>
                             <div class="text-right">
                                 <p class="font-black text-base text-stone-900" x-text="formatRupiah(item.price * item.qty)"></p>
                                 <button @click="deleteItem(id)" class="text-[10px] text-rose-500 font-bold mt-1">Hapus</button>
                             </div>
                         </div>
                     </template>
                 </div>

                 <div class="p-6 bg-white border-t border-stone-200 pb-10">
                    <button @click="openCheckoutModal()" class="w-full py-4 bg-stone-900 text-white rounded-2xl font-bold text-base shadow-xl active:scale-95 transition-transform">
                        Bayar <span x-text="formatRupiah(totalCart)"></span>
                    </button>
                 </div>
            </div>
        </div>

        {{-- ==========================================
             SECTION 4: CHECKOUT MODAL
             ========================================== --}}
        <div x-show="checkoutModalOpen" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-stone-900/80 backdrop-blur-md transition-opacity"
                 x-show="checkoutModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="checkoutModalOpen = false"></div>

            <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]"
                 x-show="checkoutModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="scale-95 opacity-0 translate-y-10" x-transition:enter-end="scale-100 opacity-100 translate-y-0"
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
                    <input type="hidden" name="total" :value="totalCart">
                    <input type="hidden" name="kembalian" :value="kembalian">
                    <input type="hidden" name="metode_pembayaran" :value="selectedPaymentMethod">
                    <input type="hidden" name="tipe_pesanan" :value="orderType">
                    <input type="hidden" name="kategori_transaksi" value="Penjualan Tunai">

                    <div class="p-8 overflow-y-auto space-y-6 custom-scrollbar bg-stone-50/50">

                        {{-- Input Nama Pelanggan --}}
                        <div>
                            <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-wider mb-2">Nama Pelanggan (Opsional)</label>
                            <input type="text" name="nama_pelanggan" x-model="customerName" placeholder="Contoh: Mas Budi"
                                class="w-full bg-white border border-stone-200 rounded-2xl px-5 py-4 font-bold text-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-800 transition-all placeholder:font-normal">
                        </div>

                        {{-- Tipe Pesanan --}}
                        <div>
                             <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-wider mb-2">Jenis Pesanan</label>
                             <div class="bg-white p-1 rounded-2xl flex relative border border-stone-200">
                                 <div class="absolute top-1 bottom-1 w-[calc(50%-4px)] bg-stone-900 rounded-xl shadow-md transition-all duration-300"
                                      :class="orderType === 'Dine-in' ? 'left-1' : 'left-[calc(50%+2px)]'"></div>
                                 <button type="button" @click="orderType = 'Dine-in'" class="flex-1 relative z-10 py-3 text-xs font-bold text-center transition-colors" :class="orderType === 'Dine-in' ? 'text-white' : 'text-stone-500'">Makan di Tempat</button>
                                 <button type="button" @click="orderType = 'Take-away'" class="flex-1 relative z-10 py-3 text-xs font-bold text-center transition-colors" :class="orderType === 'Take-away' ? 'text-white' : 'text-stone-500'">Bungkus / Bawa</button>
                             </div>
                        </div>

                        <div class="border-t border-dashed border-stone-200"></div>

                        {{-- Total Display --}}
                        <div class="bg-white rounded-[2rem] p-6 border border-stone-200 text-center relative overflow-hidden shadow-sm">
                            <span class="text-[10px] font-black text-stone-400 uppercase tracking-widest">Total Tagihan</span>
                            <div class="text-4xl font-black text-stone-900 mt-1 mb-1 tracking-tight" x-text="formatRupiah(totalCart)"></div>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div>
                            <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-wider mb-3">Metode Pembayaran</label>
                            <div class="grid grid-cols-3 gap-3">
                                <template x-for="method in ['Tunai', 'Transfer', 'QRIS']">
                                    <div @click="setPaymentMethod(method)"
                                         :class="selectedPaymentMethod === method ? 'bg-stone-900 text-white shadow-lg shadow-stone-900/30 ring-2 ring-stone-900' : 'bg-white border-stone-200 text-stone-500 hover:bg-stone-50'"
                                         class="cursor-pointer border rounded-2xl py-4 flex flex-col items-center justify-center gap-2 transition-all active:scale-95 text-center">
                                        <span class="material-symbols-rounded text-xl"
                                              x-text="method === 'Tunai' ? 'payments' : (method === 'Transfer' ? 'account_balance' : 'qr_code_scanner')"></span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider" x-text="method"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Input Uang --}}
                        <div x-show="selectedPaymentMethod === 'Tunai'" x-transition>
                            <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-wider mb-2">Uang Diterima</label>
                            <div class="relative mb-3">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-stone-400 font-black text-lg">Rp</span>
                                <input type="text" x-model="bayarDisplay" @input="updateBayar($event.target.value)" id="inputBayar"
                                    class="w-full bg-white border-2 border-stone-200 rounded-[1.5rem] pl-14 pr-6 py-4 font-black text-2xl text-stone-900 focus:outline-none focus:border-stone-900 focus:ring-0 transition-all placeholder:text-stone-300"
                                    placeholder="0">
                            </div>

                            <div class="grid grid-cols-4 gap-2">
                                <button type="button" @click="setBayar(totalCart)" class="py-3 text-[10px] font-bold rounded-xl border border-stone-200 bg-stone-100 text-stone-600 hover:bg-stone-800 hover:text-white transition-all active:scale-95">PAS</button>
                                <button type="button" @click="setBayar(10000)" class="py-3 text-[10px] font-bold rounded-xl border border-stone-200 bg-white hover:bg-stone-100 transition-all active:scale-95">10K</button>
                                <button type="button" @click="setBayar(20000)" class="py-3 text-[10px] font-bold rounded-xl border border-stone-200 bg-white hover:bg-stone-100 transition-all active:scale-95">20K</button>
                                <button type="button" @click="setBayar(50000)" class="py-3 text-[10px] font-bold rounded-xl border border-stone-200 bg-white hover:bg-stone-100 transition-all active:scale-95">50K</button>
                                <button type="button" @click="setBayar(100000)" class="col-span-4 py-3 text-[10px] font-bold rounded-xl border border-stone-200 bg-white hover:bg-stone-100 transition-all active:scale-95">100K</button>
                            </div>
                        </div>

                        <div x-show="selectedPaymentMethod === 'Tunai' && bayar >= totalCart"
                             class="bg-brand-50 border border-brand-100 rounded-[2rem] p-6 flex flex-col items-center justify-center text-center animate-in fade-in slide-in-from-bottom-2">
                            <span class="text-brand-800 font-bold text-xs uppercase tracking-widest mb-1">Kembalian</span>
                            <span class="text-brand-600 font-black text-3xl tracking-tight" x-text="formatRupiah(kembalian)"></span>
                        </div>
                    </div>

                    <div class="p-6 border-t border-stone-100 bg-white shrink-0">
                        <button type="submit" :disabled="selectedPaymentMethod === 'Tunai' && bayar < totalCart"
                            class="w-full py-4 rounded-2xl font-bold text-base transition-all flex items-center justify-center gap-2 shadow-xl active:scale-[0.98]"
                            :class="(selectedPaymentMethod === 'Tunai' && bayar < totalCart) ? 'bg-stone-100 text-stone-300 cursor-not-allowed' : 'bg-stone-900 text-white hover:bg-black'">
                            <span>Bayar & Cetak Struk</span>
                            <span class="material-symbols-rounded">receipt_long</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- MODAL STRUK / RECEIPT (Otomatis Muncul Setelah Transaksi Sukses) --}}
    @if(session('print_data'))
        <div x-data="{ open: true }" x-show="open" class="fixed inset-0 z-[100] flex items-center justify-center px-4 bg-stone-900/90 backdrop-blur-sm p-4">
            <div class="bg-white w-full max-w-[350px] shadow-2xl relative flex flex-col max-h-[90vh]">

                {{-- AREA CETAK (Kertas Thermal 58mm style) --}}
                <div id="receiptArea" class="bg-white p-4 font-mono text-xs text-black overflow-y-auto custom-scrollbar">

                    {{-- Header Struk --}}
                    <div class="text-center mb-4">
                        <h2 class="font-black text-lg uppercase mb-1">{{ session('print_data')['store_name'] }}</h2>
                        <p class="text-[10px] text-stone-500">{{ session('print_data')['address'] }}</p>
                    </div>

                    {{-- Info Struk --}}
                    <div class="mb-3 pb-3 border-b border-dashed border-black">
                        <div class="flex justify-between">
                            <span>Tgl:</span>
                            <span>{{ session('print_data')['tanggal'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ref:</span>
                            <span>{{ session('print_data')['no_ref'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kasir:</span>
                            <span>{{ session('print_data')['kasir'] }}</span>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span>Plg:</span>
                            <span class="font-bold">{{ session('print_data')['nama_pelanggan'] }}</span>
                        </div>
                    </div>

                    {{-- List Item --}}
                    <div class="space-y-2 mb-3 pb-3 border-b border-dashed border-black">
                        @foreach(session('print_data')['items'] as $item)
                            <div>
                                <div class="font-bold">{{ $item['name'] }} <span class="text-[9px] font-normal">({{ $item['ukuran'] }})</span></div>
                                <div class="flex justify-between mt-0.5">
                                    <span>{{ $item['qty'] }} x {{ number_format($item['price'], 0, ',', '.') }}</span>
                                    <span class="font-bold">{{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Totalan --}}
                    <div class="space-y-1 mb-4">
                        <div class="flex justify-between text-sm font-black">
                            <span>TOTAL</span>
                            <span>{{ number_format(session('print_data')['total'], 0, ',', '.') }}</span>
                        </div>

                        @if(session('print_data')['metode'] == 'Tunai')
                            <div class="flex justify-between">
                                <span>Bayar (Tunai)</span>
                                <span>{{ number_format(session('print_data')['bayar'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Kembali</span>
                                <span>{{ number_format(session('print_data')['kembali'], 0, ',', '.') }}</span>
                            </div>
                        @else
                            <div class="flex justify-between italic">
                                <span>Bayar ({{ session('print_data')['metode'] }})</span>
                                <span>{{ number_format(session('print_data')['total'], 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="text-center pt-2 border-t border-dashed border-black">
                        <p class="font-bold mb-1">TERIMA KASIH</p>
                        <p class="text-[10px]">Silakan datang kembali</p>
                        <p class="text-[10px] mt-2 text-stone-400">Powered by TehSoloApp</p>
                    </div>
                </div>

                {{-- TOMBOL AKSI (Tidak ikut ter-print) --}}
                <div class="p-4 bg-stone-50 border-t border-stone-200 flex gap-3">
                    <button onclick="printReceipt()" class="flex-1 bg-stone-900 text-white py-3 rounded-xl font-bold text-sm hover:bg-black transition flex items-center justify-center gap-2 shadow-lg">
                        <span class="material-symbols-rounded text-lg">print</span> Cetak
                    </button>
                    <button @click="open = false" class="flex-1 bg-white border border-stone-300 text-stone-600 py-3 rounded-xl font-bold text-sm hover:bg-stone-100 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        {{-- Script Print --}}
        <script>
            function printReceipt() {
                const content = document.getElementById('receiptArea').innerHTML;
                const win = window.open('', '', 'height=700,width=400');
                win.document.write('<html><head><title>Struk Belanja</title>');
                // CSS Reset untuk Printer Thermal
                win.document.write('<style>');
                win.document.write('body { font-family: "Courier New", monospace; margin: 0; padding: 10px; font-size: 12px; color: #000; }');
                win.document.write('.text-center { text-align: center; }');
                win.document.write('.text-right { text-align: right; }');
                win.document.write('.flex { display: flex; justify-content: space-between; }');
                win.document.write('.font-bold { font-weight: bold; }');
                win.document.write('.uppercase { text-transform: uppercase; }');
                win.document.write('.mb-1 { margin-bottom: 4px; }');
                win.document.write('.mt-1 { margin-top: 4px; }');
                win.document.write('.border-b { border-bottom: 1px dashed #000; margin: 8px 0; padding-bottom: 8px; }');
                win.document.write('.border-t { border-top: 1px dashed #000; margin: 8px 0; padding-top: 8px; }');
                win.document.write('</style>');
                win.document.write('</head><body>');
                win.document.write(content);
                win.document.write('</body></html>');
                win.document.close();
                win.focus();

                // Tunggu sebentar biar CSS load (opsional tapi aman)
                setTimeout(() => {
                    win.print();
                    win.close();
                }, 500);
            }
        </script>
    @endif

    {{-- LOGIC JS --}}
    <script>
        function posSystem() {
            return {
                search: '',
                activeCategory: 'all',
                cart: {},
                mobileCartOpen: false,
                checkoutModalOpen: false,
                customerName: '',
                orderType: 'Dine-in',
                selectedPaymentMethod: 'Tunai',
                bayar: 0,
                bayarDisplay: '',

                initSystem() { },

                filterProduct(pName, pCat) {
                    const matchesSearch = pName.includes(this.search.toLowerCase()) || pCat.includes(this.search.toLowerCase());
                    const matchesCat = this.activeCategory === 'all' || pCat === this.activeCategory;
                    return matchesSearch && matchesCat;
                },
                setCategory(cat) { this.activeCategory = cat; },
                addToCart(id, name, price, maxStock, size) {
                    if (this.cart[id]) {
                        if(this.cart[id].qty >= maxStock) { this.showError('Stok Habis', 'Sisa stok: ' + maxStock); return; }
                        this.cart[id].qty++;
                    } else {
                        if(maxStock <= 0) { this.showError('Habis', 'Stok kosong.'); return; }
                        this.cart[id] = { name: name, price: price, qty: 1, maxStock: maxStock, size: size || '-' };
                    }
                    this.showToast('Ditambahkan');
                },
                updateQty(id, change) {
                    if (this.cart[id]) {
                        const newQty = this.cart[id].qty + change;
                        if(newQty > this.cart[id].maxStock) { this.showError('Max Stok', 'Batas stok tercapai'); return; }
                        this.cart[id].qty = newQty;
                        if (this.cart[id].qty <= 0) delete this.cart[id];
                    }
                },
                deleteItem(id) { delete this.cart[id]; },
                clearCart() {
                    Swal.fire({
                        title: 'Hapus Semua?', icon: 'warning', showCancelButton: true,
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
                    this.orderType = 'Dine-in'; this.selectedPaymentMethod = 'Tunai';
                    setTimeout(() => { if(this.selectedPaymentMethod === 'Tunai') document.getElementById('inputBayar').focus(); }, 300);
                },
                setPaymentMethod(method) {
                    this.selectedPaymentMethod = method;
                    if (method !== 'Tunai') { this.bayar = this.totalCart; } else { this.bayar = 0; this.bayarDisplay = ''; }
                },
                updateBayar(val) {
                    let number = val.replace(/\D/g, ''); this.bayar = parseInt(number) || 0;
                    this.bayarDisplay = number ? new Intl.NumberFormat('id-ID').format(number) : '';
                },
                setBayar(amount) { this.bayar = amount; this.bayarDisplay = new Intl.NumberFormat('id-ID').format(amount); },
                submitCheckout(e) {
                    if(this.selectedPaymentMethod === 'Tunai' && this.bayar < this.totalCart) {
                        this.showError('Kurang', 'Uang pembayaran kurang.'); return;
                    }
                    e.target.submit(); this.cart = {};
                },
                formatRupiah(number) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number); },
                showToast(title) {
                    const Toast = Swal.mixin({ toast: true, position: 'bottom-center', showConfirmButton: false, timer: 1000, background: '#1c1917', color: '#fff', iconColor: '#ea580c', customClass: { popup: 'rounded-2xl mb-32 font-sans font-bold' } });
                    Toast.fire({ icon: 'success', title: title });
                },
                showError(title, text) {
                    Swal.fire({ icon: 'error', title: title, text: text, toast: true, position: 'top-center', showConfirmButton: false, timer: 2000, background: '#1c1917', color: '#fff', iconColor: '#f43f5e', customClass: { popup: 'rounded-2xl font-sans' } });
                }
            }
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar{width:4px}
        .custom-scrollbar::-webkit-scrollbar-track{background:transparent}
        .custom-scrollbar::-webkit-scrollbar-thumb{background-color:#d6d3d1;border-radius:10px}
        .mask-image-r{mask-image:linear-gradient(to right,black 95%,transparent 100%)}
        .no-scrollbar::-webkit-scrollbar{display:none}
        .no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button {-webkit-appearance: none; margin: 0;}
    </style>
</x-app-layout>
