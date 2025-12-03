<x-app-layout>
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="relative w-full pb-20">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-stone-800 tracking-tight leading-tight">
                    Inventaris <span class="text-brand-600">Menu</span>
                </h1>
                <p class="text-stone-500 text-sm sm:text-base mt-1 font-medium">
                    Kelola varian teh, ukuran cup, dan harga modal (HPP).
                </p>
            </div>

            {{-- Button Desktop --}}
            <button onclick="openModal()"
                class="hidden sm:flex group relative overflow-hidden bg-stone-900 text-white pl-5 pr-6 py-3 rounded-2xl items-center gap-2 transition-all duration-300 shadow-xl hover:-translate-y-1 hover:shadow-2xl hover:bg-black">
                <span class="material-symbols-rounded bg-white/20 rounded-full p-0.5 text-sm group-hover:rotate-90 transition-transform">add</span>
                <span class="font-bold text-sm tracking-wide">Tambah Menu</span>
            </button>
        </div>

        {{-- STATS GRID --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-5 rounded-3xl border border-stone-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="text-stone-400 text-[10px] font-bold uppercase tracking-wider mb-1">Total Varian</div>
                <div class="text-2xl font-black text-stone-800">{{ $totalProduk }} <span class="text-xs text-stone-400 font-medium">Menu</span></div>
            </div>
            <div class="bg-white p-5 rounded-3xl border border-stone-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="text-stone-400 text-[10px] font-bold uppercase tracking-wider mb-1">Total Stok (Cup)</div>
                <div class="text-2xl font-black text-emerald-600">{{ $totalStok }} <span class="text-xs text-stone-400 font-medium">Pcs</span></div>
            </div>
            <div class="bg-white p-5 rounded-3xl border border-stone-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="text-stone-400 text-[10px] font-bold uppercase tracking-wider mb-1">Potensi Omset</div>
                <div class="text-2xl font-black text-blue-600 truncate">Rp {{ number_format($nilaiStok, 0, ',', '.') }}</div>
            </div>
            <div class="{{ $stokRendah > 0 ? 'bg-orange-50 border-orange-100' : 'bg-white border-stone-100' }} p-5 rounded-3xl border shadow-sm transition-colors">
                <div class="{{ $stokRendah > 0 ? 'text-orange-600' : 'text-stone-400' }} text-[10px] font-bold uppercase tracking-wider mb-1">Restock Cup</div>
                <div class="text-2xl font-black {{ $stokRendah > 0 ? 'text-orange-700' : 'text-stone-800' }}">{{ $stokRendah }} <span class="text-xs font-medium">Item</span></div>
            </div>
        </div>

        {{-- PRODUCTS GRID --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $produk)
                <div class="group bg-white rounded-[2rem] border border-stone-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 flex flex-col overflow-hidden relative">

                    {{-- Image & Badges (MODIFIED: ASPECT RATIO 4:3 / pt-[75%]) --}}
                    <div class="relative w-full pt-[75%] bg-stone-100 overflow-hidden">
                        <img src="{{ $produk->foto ? asset('storage/'.$produk->foto) : asset('assets/images/teh-jumbo.jpg') }}"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                             alt="{{ $produk->nama }}"
                             onerror="this.src='https://placehold.co/400x300/f5f5f4/a8a29e?text=No+Image'">

                        <div class="absolute top-4 right-4 z-10">
                            @php
                                $sizeColor = match($produk->ukuran) {
                                    'Jumbo' => 'bg-purple-600 text-white shadow-purple-200',
                                    'Sedang' => 'bg-blue-500 text-white shadow-blue-200',
                                    'Kecil' => 'bg-stone-500 text-white shadow-stone-200',
                                    default => 'bg-stone-800 text-white'
                                };
                            @endphp
                            @if($produk->ukuran && $produk->ukuran != '-')
                                <span class="px-3 py-1.5 text-[10px] font-black uppercase tracking-wider {{ $sizeColor }} rounded-xl shadow-lg border-2 border-white">
                                    {{ $produk->ukuran }}
                                </span>
                            @endif
                        </div>

                        <div class="absolute top-4 left-4 z-10">
                            <span class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider bg-white/90 backdrop-blur-md text-stone-800 rounded-xl shadow-sm border border-white/50">
                                {{ ucfirst($produk->kategori) }}
                            </span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-6 flex flex-col flex-1">
                        <div class="mb-5">
                            <h3 class="text-lg font-extrabold text-stone-800 leading-tight mb-1 line-clamp-1" title="{{ $produk->nama }}">{{ $produk->nama }}</h3>
                            <div class="flex justify-between items-end mt-2">
                                <div class="text-stone-900 font-black text-xl">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                                @if($produk->modal > 0)
                                    {{-- Menampilkan estimasi profit per cup --}}
                                    <div class="text-[10px] text-emerald-700 font-bold bg-emerald-100/50 px-2.5 py-1 rounded-lg border border-emerald-100" title="Estimasi Profit per Cup">
                                        +{{ number_format($produk->harga - $produk->modal, 0, ',', '.') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-auto">
                            <div class="flex justify-between items-end mb-1.5">
                                <span class="text-[10px] font-bold uppercase text-stone-400 tracking-wide">Stok Cup</span>
                                <span class="text-xs font-bold {{ $produk->stok <= 10 ? 'text-orange-600' : 'text-stone-600' }}">
                                    {{ $produk->stok }} Pcs
                                </span>
                            </div>
                            <div class="w-full bg-stone-100 h-2.5 rounded-full overflow-hidden border border-stone-50">
                                <div class="h-full rounded-full {{ $produk->stok <= 10 ? 'bg-orange-500' : 'bg-stone-800' }} transition-all duration-500"
                                     style="width: {{ min(($produk->stok / 50) * 100, 100) }}%"></div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="grid grid-cols-2 gap-3 mt-6 pt-5 border-t border-dashed border-stone-100">
                            <button class="btnEdit flex items-center justify-center gap-2 py-3 rounded-2xl text-xs font-bold text-stone-600 bg-stone-50 hover:bg-stone-100 hover:text-stone-900 transition-colors"
                                data-id="{{ $produk->id }}"
                                data-nama="{{ $produk->nama }}"
                                data-kategori="{{ $produk->kategori }}"
                                data-ukuran="{{ $produk->ukuran ?? '-' }}"
                                data-harga="{{ $produk->harga }}"
                                data-modal="{{ $produk->modal ?? 0 }}"
                                data-stok="{{ $produk->stok }}">
                                <span class="material-symbols-rounded text-base">edit</span> Edit
                            </button>
                            <button class="delete-product-btn flex items-center justify-center gap-2 py-3 rounded-2xl text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 hover:text-rose-700 transition-colors"
                                data-id="{{ $produk->id }}"
                                data-nama="{{ $produk->nama }}">
                                <span class="material-symbols-rounded text-base">delete</span> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-20 text-center border-2 border-dashed border-stone-200 rounded-[2.5rem] bg-stone-50/50">
                    <span class="material-symbols-rounded text-5xl text-stone-300 mb-4">local_cafe</span>
                    <h3 class="font-bold text-xl text-stone-800">Belum Ada Menu</h3>
                    <p class="text-stone-400 mt-1">Mulai dengan menambahkan menu baru.</p>
                </div>
            @endforelse
        </div>

        {{-- FAB Mobile --}}
        <button onclick="openModal()" class="sm:hidden fixed bottom-24 right-5 z-[50] w-14 h-14 bg-stone-900 text-white rounded-full shadow-2xl flex items-center justify-center active:scale-90 transition-transform">
            <span class="material-symbols-rounded text-2xl">add</span>
        </button>

    </div>

    {{-- MODAL TAMBAH PRODUCT --}}
    <div id="modalAddProduct" class="fixed inset-0 z-[70] hidden items-center justify-center w-full h-full">
        <div class="absolute inset-0 bg-stone-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalAddBackdrop"></div>

        <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl mx-4 overflow-hidden transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[90vh]" id="modalAddContent">

            {{-- Header --}}
            <div class="px-8 py-6 border-b border-stone-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <div>
                    <h2 class="text-xl font-extrabold text-stone-900 tracking-tight">Menu Baru</h2>
                    <p class="text-xs text-stone-500 font-medium">Isi detail varian teh.</p>
                </div>
                <button onclick="closeModal()" class="w-10 h-10 rounded-full bg-stone-50 border border-stone-200 flex items-center justify-center text-stone-400 hover:bg-stone-100 hover:text-stone-600 transition-colors">
                    <span class="material-symbols-rounded text-xl">close</span>
                </button>
            </div>

            {{-- Form Content --}}
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6 overflow-y-auto custom-scrollbar" onsubmit="cleanCurrencyInputs(this)">
                @csrf

                {{-- Nama Menu --}}
                <div>
                    <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2">Nama Menu</label>
                    <input type="text" name="nama" required
                        class="w-full bg-stone-50 border border-stone-200 rounded-2xl px-5 py-4 font-bold text-stone-800 placeholder:text-stone-300 placeholder:font-normal focus:outline-none focus:ring-2 focus:ring-stone-800 focus:bg-white transition-all"
                        placeholder="Contoh: Teh Jumbo Original">
                </div>

                {{-- Grid: Kategori & Ukuran (Modern Combobox) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                    {{-- Kategori: Combobox --}}
                    <div x-data="combobox({ items: ['Original', 'Varian Rasa', 'Extra Topping'] })" class="relative">
                        <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2">Kategori</label>
                        <div class="relative">
                            <input type="text" name="kategori" x-model="value" x-ref="input"
                                @focus="open = true" @click.outside="open = false" @keydown.escape="open = false"
                                class="w-full bg-stone-50 border border-stone-200 rounded-2xl px-5 py-4 font-bold text-stone-800 placeholder:text-stone-300 focus:outline-none focus:ring-2 focus:ring-stone-800 focus:bg-white transition-all"
                                placeholder="Pilih / Ketik..." autocomplete="off" required>

                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none material-symbols-rounded text-xl transition-transform duration-300"
                                  :class="open ? 'rotate-180' : ''">expand_more</span>

                            <div x-show="open" x-transition.opacity.duration.200ms style="display: none;"
                                 class="absolute z-50 w-full mt-2 bg-white border border-stone-100 rounded-2xl shadow-xl max-h-48 overflow-y-auto custom-scrollbar">
                                <template x-for="item in filteredItems()" :key="item">
                                    <div @click="select(item)"
                                         class="px-5 py-3 hover:bg-stone-50 cursor-pointer font-medium text-stone-600 hover:text-stone-900 transition-colors border-b border-stone-50 last:border-0">
                                        <span x-text="item"></span>
                                    </div>
                                </template>
                                <div x-show="filteredItems().length === 0" class="px-5 py-3 text-stone-400 text-xs italic">
                                    Tekan enter untuk custom.
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Ukuran: Combobox --}}
                    <div x-data="combobox({ items: ['Jumbo', 'Sedang', 'Kecil', 'Tanpa Cup'] })" class="relative">
                        <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2">Ukuran Cup</label>
                        <div class="relative">
                            <input type="text" name="ukuran" x-model="value" x-ref="input"
                                @focus="open = true" @click.outside="open = false"
                                class="w-full bg-stone-50 border border-stone-200 rounded-2xl px-5 py-4 font-bold text-stone-800 placeholder:text-stone-300 focus:outline-none focus:ring-2 focus:ring-stone-800 focus:bg-white transition-all"
                                placeholder="Pilih / Ketik..." autocomplete="off">

                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none material-symbols-rounded text-xl transition-transform duration-300"
                                  :class="open ? 'rotate-180' : ''">expand_more</span>

                            <div x-show="open" x-transition.opacity.duration.200ms style="display: none;"
                                 class="absolute z-50 w-full mt-2 bg-white border border-stone-100 rounded-2xl shadow-xl max-h-48 overflow-y-auto custom-scrollbar">
                                <template x-for="item in filteredItems()" :key="item">
                                    <div @click="select(item)"
                                         class="px-5 py-3 hover:bg-stone-50 cursor-pointer font-medium text-stone-600 hover:text-stone-900 transition-colors border-b border-stone-50 last:border-0">
                                        <span x-text="item"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section Harga (Format Ribuan) --}}
                <div class="bg-stone-50 p-5 rounded-[1.5rem] border border-stone-100">
                    <label class="block text-[10px] font-extrabold text-stone-400 uppercase tracking-wider mb-4 border-b border-stone-200 pb-2">Penetapan Harga</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-stone-500 mb-1.5">Harga Jual</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-stone-400 font-bold text-xs">Rp</span>
                                <input type="text" inputmode="numeric" name="harga" required
                                    class="currency-input w-full bg-white border border-stone-200 rounded-xl pl-10 pr-4 py-3 font-black text-lg text-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-800 transition-all placeholder:text-stone-300"
                                    placeholder="0" oninput="formatCurrency(this)">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-stone-500 mb-1.5">Modal (HPP)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-stone-400 font-bold text-xs">Rp</span>
                                <input type="text" inputmode="numeric" name="modal" required
                                    class="currency-input w-full bg-white border border-stone-200 rounded-xl pl-10 pr-4 py-3 font-black text-lg text-stone-600 focus:outline-none focus:ring-2 focus:ring-stone-800 transition-all placeholder:text-stone-300"
                                    placeholder="0" oninput="formatCurrency(this)">
                            </div>
                        </div>
                    </div>
                    <p class="text-[9px] text-stone-400 mt-3 leading-relaxed">
                        *Estimasi biaya bahan (Cup + Teh + Gula + Es) per 1 gelas.
                    </p>
                </div>

                {{-- Stok --}}
                <div>
                    <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2">Stok Cup / Wadah</label>
                    <div class="flex items-center gap-3">
                        <input type="number" name="stok" required
                            class="w-full bg-stone-50 border border-stone-200 rounded-2xl px-5 py-4 font-bold text-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-800 focus:bg-white transition-all"
                            placeholder="0">
                        <span class="font-bold text-stone-400">Pcs</span>
                    </div>
                    <p class="text-[10px] text-orange-600 mt-2 font-medium">
                        *Hitung berdasarkan jumlah fisik gelas plastik yang tersedia.
                    </p>
                </div>

                {{-- File Upload --}}
                <div>
                    <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2">Foto Produk (Opsional)</label>
                    <input type="file" name="foto" class="block w-full text-sm text-stone-500 file:mr-4 file:py-3 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200 transition-all cursor-pointer">
                </div>

                {{-- Footer Button --}}
                <div class="pt-2 pb-2">
                    <button class="w-full bg-stone-950 text-white font-bold text-base py-4 rounded-2xl hover:bg-black hover:scale-[1.01] active:scale-[0.98] transition-all shadow-xl shadow-stone-200">
                        Simpan Menu
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT PRODUCT --}}
    <div id="modalEditProduct" class="fixed inset-0 z-[70] hidden items-center justify-center w-full h-full">
        <div class="absolute inset-0 bg-stone-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalEditBackdrop"></div>

        <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl mx-4 overflow-hidden transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[90vh]" id="modalEditContent">

            {{-- Header --}}
            <div class="px-8 py-6 border-b border-stone-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <div>
                    <h2 class="text-xl font-extrabold text-stone-900 tracking-tight">Edit Menu</h2>
                    <p class="text-xs text-stone-500 font-medium">Perbarui detail menu.</p>
                </div>
                <button onclick="closeModalEdit()" class="w-10 h-10 rounded-full bg-stone-50 border border-stone-200 flex items-center justify-center text-stone-400 hover:bg-stone-100 hover:text-stone-600 transition-colors">
                    <span class="material-symbols-rounded text-xl">close</span>
                </button>
            </div>

            {{-- Form Content --}}
            <form id="formEditProduct" method="POST" enctype="multipart/form-data" class="p-8 space-y-6 overflow-y-auto custom-scrollbar" onsubmit="cleanCurrencyInputs(this)">
                @csrf @method('PUT')

                {{-- Nama Menu --}}
                <div>
                    <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2">Nama Menu</label>
                    <input type="text" id="editNama" name="nama" required
                        class="w-full bg-stone-50 border border-stone-200 rounded-2xl px-5 py-4 font-bold text-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-800 focus:bg-white transition-all">
                </div>

                {{-- Grid: Kategori & Ukuran (Edit - Modern Combobox) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                    {{-- Kategori Edit --}}
                    <div id="wrapperEditKategori" x-data="combobox({ items: ['Original', 'Varian Rasa', 'Extra Topping'] })" class="relative">
                        <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2">Kategori</label>
                        <div class="relative">
                            {{-- Input Text ID dipindah kesini --}}
                            <input type="text" id="editKategori" name="kategori" x-model="value" x-ref="input"
                                @focus="open = true" @click.outside="open = false"
                                class="w-full bg-stone-50 border border-stone-200 rounded-2xl px-5 py-4 font-bold text-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-800 focus:bg-white transition-all"
                                placeholder="Pilih / Ketik..." required autocomplete="off">

                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none material-symbols-rounded text-xl" :class="open ? 'rotate-180' : ''">expand_more</span>

                            <div x-show="open" x-transition style="display: none;" class="absolute z-50 w-full mt-2 bg-white border border-stone-100 rounded-2xl shadow-xl max-h-48 overflow-y-auto custom-scrollbar">
                                <template x-for="item in filteredItems()" :key="item">
                                    <div @click="select(item)" class="px-5 py-3 hover:bg-stone-50 cursor-pointer font-medium text-stone-600 hover:text-stone-900 border-b border-stone-50">
                                        <span x-text="item"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Ukuran Edit --}}
                    <div id="wrapperEditUkuran" x-data="combobox({ items: ['Jumbo', 'Sedang', 'Kecil', 'Tanpa Cup'] })" class="relative">
                        <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2">Ukuran Cup</label>
                        <div class="relative">
                            {{-- Input Text ID dipindah kesini --}}
                            <input type="text" id="editUkuran" name="ukuran" x-model="value" x-ref="input"
                                @focus="open = true" @click.outside="open = false"
                                class="w-full bg-stone-50 border border-stone-200 rounded-2xl px-5 py-4 font-bold text-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-800 focus:bg-white transition-all"
                                placeholder="Pilih / Ketik..." autocomplete="off">

                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none material-symbols-rounded text-xl" :class="open ? 'rotate-180' : ''">expand_more</span>

                            <div x-show="open" x-transition style="display: none;" class="absolute z-50 w-full mt-2 bg-white border border-stone-100 rounded-2xl shadow-xl max-h-48 overflow-y-auto custom-scrollbar">
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
                <div class="bg-stone-50 p-5 rounded-[1.5rem] border border-stone-100">
                    <label class="block text-[10px] font-extrabold text-stone-400 uppercase tracking-wider mb-4 border-b border-stone-200 pb-2">Penetapan Harga</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-stone-500 mb-1.5">Harga Jual</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-stone-400 font-bold text-xs">Rp</span>
                                <input type="text" inputmode="numeric" id="editHarga" name="harga" required
                                    class="currency-input w-full bg-white border border-stone-200 rounded-xl pl-10 pr-4 py-3 font-black text-lg text-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-800 transition-all"
                                    oninput="formatCurrency(this)">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-stone-500 mb-1.5">Modal (HPP)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-stone-400 font-bold text-xs">Rp</span>
                                <input type="text" inputmode="numeric" id="editModal" name="modal" required
                                    class="currency-input w-full bg-white border border-stone-200 rounded-xl pl-10 pr-4 py-3 font-black text-lg text-stone-600 focus:outline-none focus:ring-2 focus:ring-stone-800 transition-all"
                                    oninput="formatCurrency(this)">
                            </div>
                        </div>
                    </div>
                    <p class="text-[9px] text-stone-400 mt-3 leading-relaxed">
                        *Estimasi biaya bahan baku per 1 gelas.
                    </p>
                </div>

                {{-- Stok --}}
                <div>
                    <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2">Stok Cup / Wadah</label>
                    <div class="flex items-center gap-3">
                        <input type="number" id="editStok" name="stok" required
                            class="w-full bg-stone-50 border border-stone-200 rounded-2xl px-5 py-4 font-bold text-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-800 focus:bg-white transition-all">
                        <span class="font-bold text-stone-400">Pcs</span>
                    </div>
                </div>

                {{-- File Upload --}}
                <div>
                    <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-2">Ganti Foto (Opsional)</label>
                    <input type="file" name="foto" class="block w-full text-sm text-stone-500 file:mr-4 file:py-3 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200 transition-all cursor-pointer">
                </div>

                {{-- Footer Button --}}
                <div class="pt-2 pb-2">
                    <button class="w-full bg-stone-950 text-white font-bold text-base py-4 rounded-2xl hover:bg-black hover:scale-[1.01] active:scale-[0.98] transition-all shadow-xl shadow-stone-200">
                        Update Menu
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        // --- 0. ALPINE JS LOGIC (MODERN DROPDOWN) ---
        document.addEventListener('alpine:init', () => {
            Alpine.data('combobox', (config) => ({
                items: config.items,
                value: config.initial || '',
                open: false,
                // Filter logika untuk pencarian
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
                    // Sync x-model dengan value input asli
                    this.$watch('value', val => {
                        this.$refs.input.value = val;
                    });
                    // Listener khusus agar tombol EDIT JS bisa update Alpine State
                    this.$el.addEventListener('set-value', (e) => {
                        this.value = e.detail;
                    });
                }
            }));
        });

        // 1. FORMAT CURRENCY SCRIPT (Titik Ribuan)
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

        function openModal(){ toggleModal('modalAddProduct', 'modalAddContent', 'modalAddBackdrop', true); }
        function closeModal(){ toggleModal('modalAddProduct', 'modalAddContent', 'modalAddBackdrop', false); }
        function openModalEdit(){ toggleModal('modalEditProduct', 'modalEditContent', 'modalEditBackdrop', true); }
        function closeModalEdit(){ toggleModal('modalEditProduct', 'modalEditContent', 'modalEditBackdrop', false); }

        // 3. Edit Button Logic (UPDATED FOR COMBOBOX)
        document.querySelectorAll('.btnEdit').forEach(btn => {
            btn.addEventListener('click', function(){
                // Set Nama
                document.getElementById('editNama').value = this.dataset.nama;

                // --- UPDATE LOGIC COMBOBOX START ---
                // Set Kategori
                const katVal = this.dataset.kategori;
                const wrapperKategori = document.getElementById('wrapperEditKategori');
                wrapperKategori.dispatchEvent(new CustomEvent('set-value', { detail: katVal }));

                // Set Ukuran
                const ukVal = this.dataset.ukuran || '-';
                const wrapperUkuran = document.getElementById('wrapperEditUkuran');
                wrapperUkuran.dispatchEvent(new CustomEvent('set-value', { detail: ukVal }));
                // --- UPDATE LOGIC COMBOBOX END ---

                // Set Harga & Modal
                const hargaRaw = this.dataset.harga;
                const modalRaw = this.dataset.modal || 0;

                const editHargaInput = document.getElementById('editHarga');
                const editModalInput = document.getElementById('editModal');

                editHargaInput.value = hargaRaw;
                editModalInput.value = modalRaw;
                formatCurrency(editHargaInput);
                formatCurrency(editModalInput);

                document.getElementById('editStok').value = this.dataset.stok;
                document.getElementById('formEditProduct').action = "/products/" + this.dataset.id;

                openModalEdit();
            });
        });

        // 4. Delete Logic
        document.querySelectorAll('.delete-product-btn').forEach(btn => {
             btn.addEventListener('click', function() {
                let id = this.dataset.id;
                let nama = this.dataset.nama;
                Swal.fire({
                    title: 'Hapus Menu?',
                    text: `Anda akan menghapus ${nama}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1c1917',
                    cancelButtonColor: '#d6d3d1',
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
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #e5e5e5; border-radius: 20px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #d4d4d4; }
    </style>
</x-app-layout>
