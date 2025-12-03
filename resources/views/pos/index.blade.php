<x-app-layout>
    <div x-data="posSystem()" x-init="initSystem()" class="relative min-h-screen bg-stone-50/50">

        {{-- HEADER --}}
        <div class="sticky top-[64px] sm:top-[72px] z-30 bg-white/80 backdrop-blur-md border-b border-stone-200 pb-4 pt-4 sm:pt-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between gap-4 mb-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-800">Kasir Teh Solo</h1>
                        <p class="text-stone-500 text-xs font-medium">Klik menu untuk menambah pesanan.</p>
                    </div>
                    {{-- Search --}}
                    <div class="relative w-full md:w-80 group">
                        <span class="material-symbols-rounded absolute left-3 top-2.5 text-stone-400">search</span>
                        <input type="text" x-model="search" placeholder="Cari menu..."
                            class="block w-full pl-10 pr-4 py-2.5 rounded-xl bg-stone-100 border-none focus:ring-2 focus:ring-brand-500/50 transition-all">
                    </div>
                </div>

                {{-- Categories --}}
                <div class="flex gap-2 overflow-x-auto pb-2 no-scrollbar">
                    <button @click="setCategory('all')" :class="activeCategory === 'all' ? 'bg-stone-900 text-white' : 'bg-white text-stone-600 border border-stone-200'" class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all">Semua</button>
                    @foreach($products->pluck('kategori')->unique() as $cat)
                        <button @click="setCategory('{{ strtolower($cat) }}')" :class="activeCategory === '{{ strtolower($cat) }}' ? 'bg-brand-600 text-white' : 'bg-white text-stone-600 border border-stone-200'" class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all uppercase">{{ $cat }}</button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- CONTENT GRID --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col lg:flex-row gap-8 relative">

                {{-- LIST PRODUK --}}
                <div class="w-full lg:w-3/4 pb-32 lg:pb-0">
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                        @foreach($products as $p)
                            @php $isHabis = $p->stok <= 0; @endphp
                            <div x-show="filterProduct('{{ strtolower($p->nama) }}', '{{ strtolower($p->kategori) }}')"
                                 class="group relative bg-white rounded-2xl border border-stone-100 shadow-sm hover:border-brand-200 hover:shadow-lg transition-all overflow-hidden flex flex-col h-full {{ $isHabis ? 'opacity-50 pointer-events-none' : 'cursor-pointer' }}"
                                 @if(!$isHabis) @click="addToCart({{ $p->id }}, '{{ $p->nama }}', {{ $p->harga }}, {{ $p->stok }})" @endif>

                                <div class="relative aspect-[4/3] bg-stone-100 overflow-hidden">
                                    <img src="{{ $p->foto ? asset('storage/'.$p->foto) : asset('assets/images/default-product.jpg') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <div class="absolute top-2 left-2 px-2 py-1 rounded-lg text-[10px] font-bold backdrop-blur-md {{ $p->stok > 0 ? 'bg-white/90 text-stone-800' : 'bg-rose-500 text-white' }}">
                                        {{ $p->stok > 0 ? 'Stok: '.$p->stok : 'Habis' }}
                                    </div>
                                </div>
                                <div class="p-3 flex flex-col flex-1">
                                    <h3 class="font-bold text-stone-800 text-sm leading-tight line-clamp-2 mb-1">{{ $p->nama }}</h3>
                                    <div class="mt-auto pt-2 border-t border-dashed border-stone-100 font-extrabold text-brand-600 text-sm">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- CART DESKTOP --}}
                <div class="hidden lg:block w-1/4 sticky top-28 h-[calc(100vh-140px)]">
                    <div class="bg-white rounded-3xl shadow-lg border border-stone-100 flex flex-col h-full overflow-hidden">
                        <div class="p-4 border-b border-stone-100 flex justify-between items-center bg-stone-50">
                            <h2 class="font-bold text-stone-800">Keranjang</h2>
                            <button @click="clearCart()" x-show="Object.keys(cart).length > 0" class="text-xs text-rose-500 font-bold hover:underline">Reset</button>
                        </div>
                        <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-stone-50/30">
                            <template x-for="(item, id) in cart" :key="id">
                                <div class="bg-white p-3 rounded-xl border border-stone-100 shadow-sm flex flex-col gap-2">
                                    <div class="flex justify-between font-bold text-stone-800 text-sm">
                                        <span x-text="item.name"></span>
                                        <span x-text="formatRupiah(item.price * item.qty)"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-[10px] text-stone-400" x-text="'Stok sisa: ' + (item.maxStock - item.qty)"></span>
                                        <div class="flex items-center gap-2 bg-stone-100 rounded-lg p-1">
                                            <button @click="updateQty(id, -1)" class="w-5 h-5 bg-white rounded shadow-sm flex items-center justify-center font-bold text-xs">-</button>
                                            <span class="text-xs font-bold w-4 text-center" x-text="item.qty"></span>
                                            <button @click="updateQty(id, 1)" class="w-5 h-5 bg-brand-500 text-white rounded shadow-sm flex items-center justify-center font-bold text-xs">+</button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div x-show="Object.keys(cart).length === 0" class="h-full flex flex-col items-center justify-center text-stone-300">
                                <span class="material-symbols-rounded text-5xl mb-2">shopping_cart_off</span>
                                <p class="text-xs">Keranjang kosong</p>
                            </div>
                        </div>
                        <div class="p-4 bg-white border-t border-stone-100">
                            <div class="flex justify-between items-end mb-3">
                                <span class="text-sm font-medium text-stone-500">Total</span>
                                <span class="text-xl font-black text-stone-800" x-text="formatRupiah(totalCart)"></span>
                            </div>
                            <button @click="openCheckoutModal()" :disabled="Object.keys(cart).length === 0" class="w-full py-3 rounded-xl font-bold transition flex items-center justify-center gap-2" :class="Object.keys(cart).length === 0 ? 'bg-stone-200 text-stone-400' : 'bg-stone-900 text-white hover:bg-black shadow-lg'">
                                Bayar Sekarang
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- MOBILE FLOATING CART (Sama seperti sebelumnya) --}}
        <div x-show="Object.keys(cart).length > 0" class="lg:hidden fixed bottom-24 left-4 right-4 z-40">
             <div @click="mobileCartOpen = true" class="bg-stone-900 text-white p-4 rounded-2xl shadow-xl flex justify-between items-center cursor-pointer">
                <div><span class="text-xs text-stone-400 block">Total</span><span class="font-bold text-lg" x-text="formatRupiah(totalCart)"></span></div>
                <div class="flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg"><span x-text="Object.keys(cart).length + ' Item'"></span><span class="material-symbols-rounded">shopping_bag</span></div>
             </div>
        </div>

        {{-- CHECKOUT MODAL --}}
        <div x-show="checkoutModalOpen" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-stone-900/60 backdrop-blur-sm" @click="checkoutModalOpen = false"></div>
            <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-5 bg-stone-50 border-b border-stone-100 flex justify-between items-center">
                    <h2 class="text-lg font-extrabold text-stone-800">Pembayaran</h2>
                    <button @click="checkoutModalOpen = false" class="w-8 h-8 rounded-full bg-white border flex items-center justify-center text-stone-500">&times;</button>
                </div>

                <form method="POST" action="{{ route('pos.checkout') }}" class="flex flex-col flex-1 overflow-hidden" @submit.prevent="submitCheckout($event)">
                    @csrf
                    {{-- Hidden Inputs untuk Backend Controller --}}
                    <input type="hidden" name="cart_json" :value="JSON.stringify(cart)">
                    <input type="hidden" name="total" :value="totalCart">
                    <input type="hidden" name="kembalian" :value="kembalian">
                    {{-- Auto-set agar masuk ke Kas Masuk --}}
                    <input type="hidden" name="keterangan" value="Penjualan POS">
                    <input type="hidden" name="kategori" value="Penjualan Tunai">
                    <input type="hidden" name="penerima" value="Pelanggan Umum">

                    <div class="p-6 overflow-y-auto space-y-6">
                        <div class="bg-stone-50 rounded-2xl p-4 border border-stone-100">
                            <div class="flex justify-between mb-2"><span class="text-sm font-bold text-stone-500">Total Tagihan</span><span class="text-xl font-black text-stone-800" x-text="formatRupiah(totalCart)"></span></div>
                            <div class="h-px bg-stone-200 my-2"></div>
                             <template x-for="(item, id) in cart" :key="id">
                                <div class="flex justify-between text-xs text-stone-600 mb-1">
                                    <span x-text="item.qty + 'x ' + item.name"></span>
                                    <span x-text="formatRupiah(item.price * item.qty)"></span>
                                </div>
                             </template>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-500 uppercase mb-2">Uang Diterima</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 font-bold text-lg">Rp</span>
                                <input type="number" x-model="bayar" name="bayar" class="w-full pl-12 pr-4 py-4 rounded-xl border-2 border-stone-100 focus:border-brand-500 text-xl font-bold text-stone-800" placeholder="0">
                            </div>
                            <div class="grid grid-cols-4 gap-2 mt-3">
                                <button type="button" @click="bayar = totalCart" class="py-2 text-xs font-bold rounded-lg border hover:bg-brand-50 border-stone-200">Pas</button>
                                <button type="button" @click="bayar = 10000" class="py-2 text-xs font-bold rounded-lg border hover:bg-stone-50 border-stone-200">10K</button>
                                <button type="button" @click="bayar = 20000" class="py-2 text-xs font-bold rounded-lg border hover:bg-stone-50 border-stone-200">20K</button>
                                <button type="button" @click="bayar = 50000" class="py-2 text-xs font-bold rounded-lg border hover:bg-stone-50 border-stone-200">50K</button>
                            </div>
                        </div>

                        <div x-show="kembalian >= 0" class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex justify-between items-center">
                            <span class="text-emerald-800 font-bold text-sm">Kembalian</span>
                            <span class="text-emerald-700 font-black text-xl" x-text="formatRupiah(kembalian)"></span>
                        </div>
                    </div>

                    <div class="p-6 bg-stone-50 border-t border-stone-100">
                        <button type="submit" :disabled="bayar < totalCart" :class="bayar < totalCart ? 'bg-stone-300' : 'bg-brand-600 hover:bg-brand-700 shadow-lg'" class="w-full py-3.5 text-white rounded-xl font-bold transition flex items-center justify-center gap-2">
                            <span>Bayar & Cetak Struk</span>
                            <span class="material-symbols-rounded">print</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- ALPINE LOGIC --}}
    <script>
        function posSystem() {
            return {
                search: '',
                activeCategory: 'all',
                cart: {},
                mobileCartOpen: false,
                checkoutModalOpen: false,
                bayar: '',

                initSystem() {}, // Bisa load localStorage jika perlu

                filterProduct(pName, pCat) {
                    const matchesSearch = pName.includes(this.search.toLowerCase());
                    const matchesCat = this.activeCategory === 'all' || pCat === this.activeCategory;
                    return matchesSearch && matchesCat;
                },
                setCategory(cat) { this.activeCategory = cat; },

                addToCart(id, name, price, maxStock) {
                    if (this.cart[id]) {
                        if(this.cart[id].qty >= maxStock) {
                            Swal.fire({ icon: 'error', title: 'Stok Habis!', text: 'Sisa stok hanya ' + maxStock, toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });
                            return;
                        }
                        this.cart[id].qty++;
                    } else {
                        if(maxStock <= 0) return;
                        this.cart[id] = { name: name, price: price, qty: 1, maxStock: maxStock };
                    }
                },

                updateQty(id, change) {
                    if (this.cart[id]) {
                        const newQty = this.cart[id].qty + change;
                        if(newQty > this.cart[id].maxStock) {
                            Swal.fire({ icon: 'warning', title: 'Mencapai Batas Stok', toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });
                            return;
                        }
                        this.cart[id].qty = newQty;
                        if (this.cart[id].qty <= 0) delete this.cart[id];
                    }
                },

                clearCart() { if(confirm('Hapus semua pesanan?')) this.cart = {}; },

                get totalCart() {
                    let total = 0;
                    for (const id in this.cart) { total += this.cart[id].price * this.cart[id].qty; }
                    return total;
                },
                get kembalian() { return (parseInt(this.bayar) || 0) - this.totalCart; },

                openCheckoutModal() { this.mobileCartOpen = false; this.bayar = ''; this.checkoutModalOpen = true; },
                submitCheckout(e) { if (this.bayar >= this.totalCart) { e.target.submit(); this.cart = {}; } },
                formatRupiah(number) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number); }
            }
        }
    </script>

    {{-- SCRIPT CETAK STRUK --}}
    @if(session('print_data'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                printReceipt(@json(session('print_data')));
            });

            function printReceipt(data) {
                // Gunakan approach popup window yang standard
                let printWindow = window.open('', '', 'height=600,width=400');

                let itemRows = '';
                for (const [id, item] of Object.entries(data.items)) {
                    itemRows += `
                        <tr>
                            <td style="padding: 2px 0;">${item.name}<br><small>${item.qty} x ${parseInt(item.price).toLocaleString()}</small></td>
                            <td align="right" style="vertical-align: top;">${(item.price * item.qty).toLocaleString()}</td>
                        </tr>`;
                }

                printWindow.document.write(`
                    <html>
                    <body style="font-family: 'Courier New', monospace; font-size: 12px; margin: 0; padding: 10px;">
                        <div style="text-align: center; margin-bottom: 10px;">
                            <h3 style="margin:0;">TEH SOLO JUMBO</h3>
                            <p style="margin:0; font-size:10px;">${data.tanggal}</p>
                            <p style="margin:0; font-size:10px;">${data.no_ref}</p>
                        </div>
                        <hr style="border-top: 1px dashed #000;">
                        <table width="100%" style="border-collapse: collapse;">
                            ${itemRows}
                        </table>
                        <hr style="border-top: 1px dashed #000;">
                        <table width="100%">
                            <tr><td>Total</td><td align="right" style="font-weight:bold;">Rp ${data.total.toLocaleString()}</td></tr>
                            <tr><td>Bayar</td><td align="right">Rp ${parseInt(data.bayar).toLocaleString()}</td></tr>
                            <tr><td>Kembali</td><td align="right">Rp ${data.kembali.toLocaleString()}</td></tr>
                        </table>
                        <div style="text-align: center; margin-top: 20px;">
                            <p>Terima Kasih!</p>
                        </div>
                    </body>
                    </html>
                `);

                printWindow.document.close();
                printWindow.focus();
                setTimeout(() => { printWindow.print(); printWindow.close(); }, 500);
            }
        </script>
    @endif
</x-app-layout>
