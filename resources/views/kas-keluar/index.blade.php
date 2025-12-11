<x-app-layout>
    {{-- Libraries --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Container Utama --}}
    <div class="min-h-screen bg-stone-50/50 pb-36"
         x-data="{ showDetail: false, selectedItem: {} }">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8">

            {{-- 1. HEADER SECTION --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-6 gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-stone-800 tracking-tight">Kas Keluar</h1>
                    <p class="text-stone-500 text-xs md:text-sm mt-1.5 max-w-lg leading-relaxed">Kelola dan pantau seluruh arus pengeluaran operasional.</p>
                </div>

                {{-- TOMBOL TAMBAH (Desktop) --}}
                <div class="flex gap-2">

                    <a href="{{ route('kas-keluar.create') }}"
                       class="hidden md:inline-flex group bg-rose-600 hover:bg-rose-700 text-white px-6 py-3 rounded-2xl items-center gap-2 transition-all shadow-lg shadow-rose-500/20 hover:shadow-rose-500/40 hover:-translate-y-1">
                        <span class="material-symbols-rounded bg-white/20 rounded-full p-1 text-sm group-hover:rotate-90 transition-transform">add</span>
                        <span class="font-bold text-sm tracking-wide">Catat Pengeluaran</span>
                    </a>
                </div>
            </div>

            {{-- 2. STATS CARD --}}
            @php
                $totalKas = $kasKeluar->sum('nominal');
                $jumlahTransaksi = $kasKeluar->count();
            @endphp
            <div class="mb-6">
                <div class="relative bg-gradient-to-br from-rose-600 to-rose-800 rounded-[2rem] p-6 md:p-10 shadow-2xl shadow-rose-900/20 overflow-hidden group">
                    {{-- Decorative Blobs --}}
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-48 h-48 md:w-64 md:h-64 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-700"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 md:w-40 md:h-40 bg-black/10 rounded-full blur-2xl"></div>

                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 md:gap-6">
                        <div>
                            <div class="flex items-center gap-2 mb-2 md:mb-3">
                                <span class="bg-rose-500/50 border border-rose-400/30 text-rose-50 px-2.5 py-0.5 rounded-full text-[10px] md:text-xs font-bold uppercase tracking-wider">Total Keluar</span>
                            </div>

                            {{-- Dynamic Font Size Logic --}}
                            <h2 class="font-black text-white tracking-tighter drop-shadow-sm flex items-start transition-all duration-300"
                                :class="'{{ strlen((string)$totalKas) }}' > 9 ? 'text-3xl md:text-5xl' : 'text-4xl md:text-6xl'">
                                <span class="text-rose-200 text-lg md:text-3xl font-bold mr-1 mt-1 md:mt-2">Rp</span>
                                {{ number_format($totalKas, 0, ',', '.') }}
                            </h2>

                            <p class="text-rose-100 mt-2 md:mt-3 font-medium flex items-center gap-2 text-xs md:text-base opacity-90">
                                <span class="flex h-1.5 w-1.5 md:h-2 md:w-2 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-200 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 md:h-2 md:w-2 bg-white"></span>
                                </span>
                                {{ $jumlahTransaksi }} Transaksi tercatat
                            </p>
                        </div>
                        <div class="hidden md:block bg-white/10 p-4 rounded-3xl backdrop-blur-sm border border-white/10 shadow-inner">
                            <span class="material-symbols-rounded text-5xl text-rose-50">trending_down</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. FILTER & SEARCH BAR --}}
            <form method="GET" action="{{ route('kas-keluar.index') }}" id="filterForm"
                  class="bg-white p-3 md:p-4 rounded-[1.5rem] md:rounded-3xl shadow-sm border border-stone-100 mb-6 sticky top-20 md:top-4 z-30 transition-all">

                <div class="flex flex-col md:flex-row gap-2 md:gap-3">
                    {{-- Search Input --}}
                    <div class="relative flex-1 w-full group">
                        <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 text-xl group-focus-within:text-rose-500 transition-colors">search</span>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                            placeholder="Cari penerima, deskripsi..."
                            class="w-full pl-10 pr-4 py-3 md:py-3.5 rounded-2xl border border-stone-200 bg-stone-50 focus:bg-white focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 text-sm font-medium transition-all placeholder:text-stone-400">
                    </div>

                    {{-- Filter Buttons --}}
                    <div class="grid grid-cols-5 gap-2 w-full md:w-auto">

                        {{-- Filter Harga --}}
                        <div class="relative col-span-2" x-data="{ open: false }" @click.outside="open = false">
                            <input type="hidden" name="filter_harga" id="hargaSelect" value="{{ request('filter_harga') }}">
                            <button type="button" @click="open = !open"
                                class="w-full h-full px-2 py-2.5 md:px-4 md:py-0 rounded-2xl border border-stone-200 bg-white hover:bg-stone-50 active:bg-stone-100 text-stone-600 text-xs md:text-sm font-bold flex items-center justify-center gap-1.5 transition-colors relative">
                                <span class="material-symbols-rounded text-base text-rose-500">attach_money</span>
                                <span class="truncate" x-text="
                                    $el.previousElementSibling.value == '0-50000' ? '0-50rb' :
                                    ($el.previousElementSibling.value == '51000-500000' ? '51rb-500rb' :
                                    ($el.previousElementSibling.value == '500001-999999999' ? '> 500rb' : 'Harga'))
                                ">Harga</span>
                                <div x-show="$el.previousElementSibling.value" style="display: none;" class="absolute top-1 right-1 w-2 h-2 bg-rose-500 rounded-full"></div>
                            </button>

                            <div x-show="open" style="display: none;"
                                 x-transition.opacity.duration.200ms
                                 class="absolute right-0 md:left-0 top-full mt-2 w-48 bg-white border border-stone-100 rounded-2xl shadow-xl z-50 p-1.5 flex flex-col gap-1">
                                <button type="button" onclick="setFilter('hargaSelect', '')" @click="open = false" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-rose-50 hover:text-rose-700 transition-colors">Semua</button>
                                <button type="button" onclick="setFilter('hargaSelect', '0-50000')" @click="open = false" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-rose-50 hover:text-rose-700 transition-colors">0 - 50rb</button>
                                <button type="button" onclick="setFilter('hargaSelect', '51000-500000')" @click="open = false" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-rose-50 hover:text-rose-700 transition-colors">51rb - 500rb</button>
                                <button type="button" onclick="setFilter('hargaSelect', '500001-999999999')" @click="open = false" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-rose-50 hover:text-rose-700 transition-colors">> 500rb</button>
                            </div>
                        </div>

                        {{-- Filter Waktu --}}
                        <div class="relative col-span-2" x-data="{ open: false, isCustom: '{{ request('filter_waktu') }}' == 'custom' }" @click.outside="open = false">
                            <input type="hidden" name="filter_waktu" id="tanggalSelect" value="{{ request('filter_waktu') }}">
                            <button type="button" @click="open = !open"
                                class="w-full h-full px-2 py-2.5 md:px-4 md:py-0 rounded-2xl border border-stone-200 bg-white hover:bg-stone-50 active:bg-stone-100 text-stone-600 text-xs md:text-sm font-bold flex items-center justify-center gap-1.5 transition-colors relative">
                                <span class="material-symbols-rounded text-base text-rose-500">calendar_today</span>
                                <span class="truncate" x-text="
                                    $el.previousElementSibling.value == 'hari-ini' ? 'Hari Ini' :
                                    ($el.previousElementSibling.value == 'minggu-ini' ? 'Minggu Ini' :
                                    ($el.previousElementSibling.value == 'bulan-ini' ? 'Bulan Ini' :
                                    ($el.previousElementSibling.value == 'custom' ? 'Custom' : 'Waktu')))
                                ">Waktu</span>
                                <div x-show="$el.previousElementSibling.value" style="display: none;" class="absolute top-1 right-1 w-2 h-2 bg-rose-500 rounded-full"></div>
                            </button>

                            <div x-show="open" style="display: none;"
                                 x-transition.opacity.duration.200ms
                                 class="absolute right-0 md:left-0 top-full mt-2 w-64 bg-white border border-stone-100 rounded-2xl shadow-xl z-50 p-1.5 flex flex-col gap-1">
                                <button type="button" onclick="setFilter('tanggalSelect', '', false)" @click="open = false; isCustom = false" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-rose-50 hover:text-rose-700 transition-colors">Semua</button>
                                <button type="button" onclick="setFilter('tanggalSelect', 'hari-ini', false)" @click="open = false; isCustom = false" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-rose-50 hover:text-rose-700 transition-colors">Hari Ini</button>
                                <button type="button" onclick="setFilter('tanggalSelect', 'minggu-ini', false)" @click="open = false; isCustom = false" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-rose-50 hover:text-rose-700 transition-colors">Minggu Ini</button>
                                <button type="button" onclick="setFilter('tanggalSelect', 'bulan-ini', false)" @click="open = false; isCustom = false" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-rose-50 hover:text-rose-700 transition-colors">Bulan Ini</button>

                                <div class="border-t border-stone-100 my-1"></div>
                                <button type="button" onclick="setFilter('tanggalSelect', 'custom', true)" @click="isCustom = true" class="text-left px-3 py-2 rounded-xl text-xs font-medium hover:bg-rose-50 hover:text-rose-700 transition-colors flex justify-between items-center">
                                    <span>Pilih Tanggal (Custom)</span>
                                    <span class="material-symbols-rounded text-sm" x-show="isCustom">check</span>
                                </button>

                                <div x-show="isCustom" class="p-2 bg-stone-50 rounded-xl mt-1 border border-stone-100 space-y-2">
                                    <input type="date" name="start_date" id="startDateInput" value="{{ request('start_date') }}" onchange="applyAllFilters()" class="w-full bg-white border-stone-200 rounded-lg text-xs py-1.5 px-2">
                                    <input type="date" name="end_date" id="endDateInput" value="{{ request('end_date') }}" onchange="applyAllFilters()" class="w-full bg-white border-stone-200 rounded-lg text-xs py-1.5 px-2">
                                </div>
                            </div>
                        </div>

                        {{-- Reset Button --}}
                        <a href="{{ route('kas-keluar.index') }}" class="col-span-1 h-full flex items-center justify-center rounded-2xl border border-stone-200 hover:bg-rose-50 hover:text-rose-600 text-stone-500 transition-colors bg-stone-50">
                            <span class="material-symbols-rounded text-lg">restart_alt</span>
                        </a>
                    </div>
                </div>
            </form>

            <div class="flex justify-start mb-4">
                <button id="bulkDeleteBtn" onclick="submitBulkDelete()"
                    class="hidden flex bg-rose-600 hover:bg-rose-700 text-white px-6 py-3 rounded-2xl items-center gap-2 transition-all shadow-lg shadow-rose-500/20 hover:shadow-rose-500/40 hover:-translate-y-1 opacity-50 cursor-not-allowed">
                    <span class="material-symbols-rounded bg-white/20 rounded-full p-1 text-sm">delete</span>
                    <span class="font-bold text-sm tracking-wide">Hapus Item (<span id="selectedCount">0</span>)</span>
                </button>
            </div>

            <form id="bulkDeleteForm" action="{{ route('kas-keluar.bulk_destroy') }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
                <div id="bulkDeleteInputs"></div>
            </form>

            {{-- 4. TABLE VIEW (Desktop) --}}
            <div id="kasDataContainer" class="hidden md:block bg-white rounded-[2rem] shadow-sm border border-stone-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-stone-50/50 border-b border-stone-100 text-stone-500">
                            <tr>
                                <th class="p-6 text-xs font-bold uppercase tracking-wider w-10">
                                    <input type="checkbox" id="selectAll" class="w-4 h-4 text-rose-600 bg-stone-100 border-stone-300 rounded focus:ring-rose-500">
                                </th>
                                <th class="p-6 text-xs font-bold uppercase tracking-wider">Info Transaksi</th>
                                <th class="p-6 text-xs font-bold uppercase tracking-wider">Keterangan</th>
                                <th class="p-6 text-xs font-bold uppercase tracking-wider text-center">Kategori</th>
                                <th class="p-6 text-xs font-bold uppercase tracking-wider text-right">Nominal</th>
                                <th class="p-6 text-xs font-bold uppercase tracking-wider text-center">Metode</th>
                                <th class="p-6 text-xs font-bold uppercase tracking-wider text-center">Bukti</th>
                                <th class="p-6 text-xs font-bold uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse ($kasKeluar as $item)
                                <tr class="hover:bg-stone-50 transition-colors group filter-item"
                                    data-penerima="{{ strtolower($item->penerima) }}"
                                    data-deskripsi="{{ strtolower($item->deskripsi ?? '') }}"
                                    data-nominal="{{ $item->nominal }}"
                                    data-tanggal="{{ $item->tanggal }}"
                                    data-kategori="{{ strtolower($item->kategori) }}"
                                    data-metode="{{ strtolower($item->metode_pembayaran) }}"
                                    data-kode="{{ strtolower($item->kode_kas) }}">

                                    <td class="p-6 align-top">
                                        <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="user-checkbox w-4 h-4 text-rose-600 bg-stone-100 border-stone-300 rounded focus:ring-rose-500">
                                    </td>
                                    <td class="p-6 align-top">
                                        <div class="font-bold text-stone-800 whitespace-nowrap">{{ $item->kode_kas }}</div>
                                        <div class="text-xs text-stone-400 font-medium mt-1 flex items-center gap-1">
                                            <span class="material-symbols-rounded text-[14px]">event</span>
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="p-6 align-top">
                                        <div class="font-bold text-stone-700">{{ $item->penerima }}</div>
                                        <div class="text-xs text-stone-500 mt-1 line-clamp-2 max-w-[250px] leading-relaxed">
                                            {{ $item->deskripsi ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="p-6 align-top text-center">
                                        @php
                                            $badgeClass = match (strtolower($item->kategori)) {
                                                'pembelian' => 'bg-amber-100 text-amber-800 border-amber-200',
                                                'operasional' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                'gaji' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                default => 'bg-stone-100 text-stone-600 border-stone-200',
                                            };
                                        @endphp
                                        <span class="inline-block px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide border {{ $badgeClass }}">
                                            {{ $item->kategori }}
                                        </span>
                                    </td>
                                    <td class="p-6 align-top text-right whitespace-nowrap">
                                        <span class="font-bold text-rose-600 text-base">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="p-6 align-top text-center">
                                        <span class="text-xs font-semibold text-stone-500 bg-stone-100 px-3 py-1.5 rounded-full border border-stone-200">
                                            {{ $item->metode_pembayaran }}
                                        </span>
                                    </td>
                                    <td class="p-6 align-top text-center">
                                        @if($item->bukti_pembayaran)
                                            <button onclick="window.open('{{ asset('storage/' . $item->bukti_pembayaran) }}', '_blank')"
                                                class="w-10 h-10 rounded-xl bg-white border border-stone-200 text-stone-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all flex items-center justify-center shadow-sm">
                                                <span class="material-symbols-rounded text-lg">image</span>
                                            </button>
                                        @else
                                            <span class="text-stone-300 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="p-6 align-top text-center">
                                        <div class="flex items-center justify-center gap-2 opacity-50 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('kas-keluar.edit', $item->id) }}" class="p-2 rounded-lg text-stone-500 hover:bg-rose-50 hover:text-rose-600 transition-colors">
                                                <span class="material-symbols-rounded">edit</span>
                                            </a>
                                            <button type="button" class="delete-btn p-2 rounded-lg text-stone-500 hover:bg-red-50 hover:text-red-600 transition-colors"
                                                data-id="{{ $item->id }}" data-deskripsi="{{ $item->deskripsi }}">
                                                <span class="material-symbols-rounded">delete</span>
                                            </button>
                                            <form action="{{ route('kas-keluar.destroy', $item->id) }}" method="POST" id="deleteForm-{{ $item->id }}" class="hidden">
                                                @csrf @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="noDataRow">
                                    <td colspan="8" class="py-20 text-center">
                                        <div class="flex flex-col items-center justify-center text-stone-300">
                                            <span class="material-symbols-rounded text-6xl mb-4 bg-stone-50 rounded-full p-6">receipt_long</span>
                                            <p class="text-stone-500 font-medium text-lg">Belum ada data pengeluaran.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 5. MOBILE LIST VIEW --}}
            <div id="kasDataContainerMobile" class="md:hidden space-y-3">
                @forelse ($kasKeluar as $item)
                    <div @click="showDetail = true; selectedItem = JSON.parse('{{ json_encode([
                            'id' => $item->id,
                            'kode_kas' => $item->kode_kas,
                            'tanggal' => \Carbon\Carbon::parse($item->tanggal)->format('d M Y'),
                            'kategori' => $item->kategori,
                            'penerima' => $item->penerima,
                            'nominal' => number_format($item->nominal, 0, ',', '.'),
                            'metode_pembayaran' => $item->metode_pembayaran,
                            'deskripsi' => $item->deskripsi ?? '-',
                            'bukti_url' => $item->bukti_pembayaran ? asset('storage/' . $item->bukti_pembayaran) : null,
                            'edit_url' => route('kas-keluar.edit', $item->id),
                            'delete_url' => route('kas-keluar.destroy', $item->id),
                        ]) }}')"
                        class="bg-white rounded-[1.25rem] p-4 shadow-[0_2px_8px_rgba(0,0,0,0.03)] border border-stone-100 active:scale-[0.98] transition-all cursor-pointer relative overflow-hidden filter-item-mobile group"
                        data-penerima="{{ strtolower($item->penerima) }}"
                        data-deskripsi="{{ strtolower($item->deskripsi ?? '') }}"
                        data-nominal="{{ $item->nominal }}"
                        data-tanggal="{{ $item->tanggal }}"
                        data-kategori="{{ strtolower($item->kategori) }}"
                        data-metode="{{ strtolower($item->metode_pembayaran) }}"
                        data-kode="{{ strtolower($item->kode_kas) }}">

                        {{-- Color Strip based on Category --}}
                        @php
                            $stripColor = match (strtolower($item->kategori)) {
                                'pembelian' => 'bg-amber-400',
                                'operasional' => 'bg-blue-400',
                                'gaji' => 'bg-purple-400',
                                default => 'bg-stone-300',
                            };
                        @endphp
                        <div class="absolute top-0 bottom-0 left-0 w-1 {{ $stripColor }}"></div>

                        <div class="pl-2.5">
                            <div class="flex justify-between items-start mb-2">
                                <div class="w-2/3">
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-stone-100 text-stone-500 uppercase tracking-wide">{{ $item->kategori }}</span>
                                        <span class="text-[9px] text-stone-400">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M') }}</span>
                                    </div>
                                    <h3 class="font-bold text-stone-800 text-sm line-clamp-1 leading-tight">{{ $item->penerima }}</h3>
                                </div>
                                <div class="text-right flex-1">
                                    <p class="font-extrabold text-rose-600 text-base">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-stone-50">
                                <p class="text-[10px] text-stone-500 line-clamp-1 italic w-3/4">"{{ $item->deskripsi ?? '-' }}"</p>
                                <span class="material-symbols-rounded text-stone-300 text-base">chevron_right</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12" id="noDataMobile">
                        <span class="material-symbols-rounded text-4xl text-stone-200 mb-2">money_off</span>
                        <p class="text-stone-400 text-xs font-medium">Tidak ada data.</p>
                    </div>
                @endforelse
            </div>

            {{-- 6. MODAL DETAIL MOBILE --}}
            <div x-show="showDetail"
                class="fixed inset-0 z-[100] md:hidden flex flex-col bg-white"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-full"
                x-transition:enter-end="translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-y-0"
                x-transition:leave-end="translate-y-full"
                style="display: none;">

                <div class="px-5 py-3 border-b border-stone-100 flex items-center justify-between bg-white sticky top-0 z-10">
                    <button @click="showDetail = false" class="w-8 h-8 rounded-full bg-stone-50 flex items-center justify-center text-stone-600 active:bg-stone-200">
                        <span class="material-symbols-rounded">arrow_back</span>
                    </button>
                    <h2 class="text-base font-bold text-stone-800">Rincian Pengeluaran</h2>
                    <div class="w-8"></div>
                </div>

                <div class="flex-1 overflow-y-auto p-5 bg-stone-50/50">
                    <div class="text-center mb-6 mt-2">
                        <span class="inline-block px-3 py-1 rounded-full bg-rose-100 text-rose-700 text-[10px] font-bold uppercase tracking-wider mb-2 shadow-sm border border-rose-200" x-text="selectedItem.kategori"></span>
                        <h3 class="text-3xl font-black text-stone-800 tracking-tight" x-text="'Rp ' + selectedItem.nominal"></h3>
                        <p class="text-stone-400 text-xs mt-1 font-mono" x-text="selectedItem.kode_kas"></p>
                    </div>

                    <div class="bg-white rounded-3xl p-5 shadow-sm border border-stone-100 space-y-4">
                        <div class="grid grid-cols-2 gap-3 pb-3 border-b border-stone-50">
                            <div>
                                <span class="text-[10px] text-stone-400 block mb-0.5">Tanggal</span>
                                <span class="font-bold text-stone-700 text-sm" x-text="selectedItem.tanggal"></span>
                            </div>
                            <div>
                                <span class="text-[10px] text-stone-400 block mb-0.5">Metode</span>
                                <span class="font-bold text-stone-700 text-sm" x-text="selectedItem.metode_pembayaran"></span>
                            </div>
                        </div>
                        <div>
                            <span class="text-[10px] text-stone-400 block mb-0.5">Penerima</span>
                            <span class="font-bold text-stone-800 text-base" x-text="selectedItem.penerima || '-'"></span>
                        </div>
                        <div>
                            <span class="text-[10px] text-stone-400 block mb-0.5">Deskripsi</span>
                            <p class="font-medium text-stone-600 leading-relaxed bg-stone-50 p-3 rounded-xl text-xs" x-text="selectedItem.deskripsi"></p>
                        </div>

                        <template x-if="selectedItem.bukti_url">
                            <div class="pt-2">
                                <a :href="selectedItem.bukti_url" target="_blank" class="flex items-center justify-center gap-2 p-3 bg-stone-800 rounded-xl text-xs font-bold text-white hover:bg-stone-900 transition-colors w-full shadow-lg shadow-stone-800/20">
                                    <span class="material-symbols-rounded text-base">image</span> Lihat Bukti Foto
                                </a>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="p-5 bg-white border-t border-stone-100 flex gap-3 shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
                    <a :href="selectedItem.edit_url" class="flex-1 bg-white border border-stone-200 text-stone-700 font-bold py-3 rounded-xl flex items-center justify-center gap-2 hover:bg-stone-50 active:scale-95 transition-all text-sm">
                        <span class="material-symbols-rounded text-lg">edit</span> Edit
                    </a>
                    <button @click="confirmDelete(selectedItem.id, selectedItem.deskripsi, selectedItem.delete_url)" class="flex-1 bg-rose-600 text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-rose-500/30 hover:bg-rose-700 active:scale-95 transition-all text-sm">
                        <span class="material-symbols-rounded text-lg">delete</span> Hapus
                    </button>
                </div>
            </div>

            {{-- 7. FLOATING BUTTON MOBILE --}}
            <a href="{{ route('kas-keluar.create') }}"
               class="fixed bottom-28 right-5 md:hidden w-14 h-14 bg-rose-600 text-white rounded-full shadow-xl shadow-rose-500/40 flex items-center justify-center z-40 active:scale-90 transition-transform border-2 border-white/20">
                <span class="material-symbols-rounded text-2xl">add</span>
            </a>

        </div>

        {{-- SCRIPTS --}}
        <script>
            function setFilter(inputId, value, isCustom = false) {
                const input = document.getElementById(inputId);
                if (input) {
                    input.value = value;
                    if(!isCustom) applyAllFilters();
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                const searchInput = document.getElementById('searchInput');
                const hargaSelect = document.getElementById('hargaSelect');
                const tanggalSelect = document.getElementById('tanggalSelect');
                const startDateInput = document.getElementById('startDateInput');
                const endDateInput = document.getElementById('endDateInput');

                let searchTimeout;
                searchInput?.addEventListener('input', () => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => applyAllFilters(), 300);
                });

                window.applyAllFilters = function() {
                    const query = searchInput.value.toLowerCase();
                    const hargaFilter = hargaSelect.value;
                    const waktuFilter = tanggalSelect.value;
                    const startDate = startDateInput.value;
                    const endDate = endDateInput.value;

                    const rows = document.querySelectorAll('.filter-item, .filter-item-mobile');
                    let visibleCount = 0;
                    let visibleCountMobile = 0;

                    rows.forEach(row => {
                        const penerima = row.dataset.penerima || '';
                        const deskripsi = row.dataset.deskripsi || '';
                        const nominal = parseInt(row.dataset.nominal) || 0;
                        const tanggal = row.dataset.tanggal;
                        const kode = row.dataset.kode || '';
                        const kategori = row.dataset.kategori || '';

                        const matchesSearch = !query || penerima.includes(query) || deskripsi.includes(query) || kode.includes(query) || kategori.includes(query) || nominal.toString().includes(query);
                        const matchesHarga = checkHargaFilter(nominal, hargaFilter);
                        const matchesWaktu = checkWaktuFilter(tanggal, waktuFilter, startDate, endDate);

                        if (matchesSearch && matchesHarga && matchesWaktu) {
                            row.style.display = '';
                            if(row.classList.contains('filter-item')) visibleCount++;
                            if(row.classList.contains('filter-item-mobile')) visibleCountMobile++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const noDataRow = document.getElementById('noDataRow');
                    if(noDataRow) noDataRow.style.display = visibleCount === 0 ? '' : 'none';

                    const noDataMobile = document.getElementById('noDataMobile');
                    if(noDataMobile && document.querySelectorAll('.filter-item-mobile').length > 0) {
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
                    dateToCheck.setHours(0,0,0,0);
                    today.setHours(0,0,0,0);

                    switch(filter) {
                        case 'hari-ini': return dateToCheck.getTime() === today.getTime();
                        case 'minggu-ini': return isSameWeek(today, dateToCheck);
                        case 'bulan-ini': return dateToCheck.getMonth() === today.getMonth() && dateToCheck.getFullYear() === today.getFullYear();
                        case 'custom':
                            if (!start || !end) return true;
                            const startDate = new Date(start);
                            const endDate = new Date(end);
                            startDate.setHours(0,0,0,0);
                            endDate.setHours(0,0,0,0);
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

                window.confirmDelete = function(id, deskripsi, url) {
                    Swal.fire({
                        title: 'Hapus Data?',
                        html: `Anda akan menghapus pengeluaran <strong>${deskripsi || 'ini'}</strong> secara permanen.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#78716c',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        customClass: { popup: 'rounded-3xl font-sans', confirmButton: 'rounded-xl px-6 py-2.5', cancelButton: 'rounded-xl px-6 py-2.5' }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.getElementById('deleteForm-' + id) || createDeleteForm(id, url);
                            form.submit();
                        }
                    });
                }

                function createDeleteForm(id, url) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
                    document.body.appendChild(form);
                    return form;
                }

                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.id;
                        const deskripsi = btn.dataset.deskripsi;
                        confirmDelete(id, deskripsi, null);
                    });
                });

                // ===========================
                // BULK DELETE LOGIC
                // ===========================
                const selectAllCheckbox = document.getElementById('selectAll');
                const userCheckboxes = document.querySelectorAll('.user-checkbox');
                const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
                const selectedCountSpan = document.getElementById('selectedCount');
                const bulkDeleteForm = document.getElementById('bulkDeleteForm');
                const bulkDeleteInputs = document.getElementById('bulkDeleteInputs');

                function updateBulkDeleteState() {
                    const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
                    const count = selectedCheckboxes.length;
                    if(selectedCountSpan) selectedCountSpan.textContent = count;

                    if (bulkDeleteBtn) {
                        if (count > 0) {
                            bulkDeleteBtn.disabled = false;
                            bulkDeleteBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'hidden');
                            bulkDeleteBtn.classList.add('flex'); // Ensure flex is added
                        } else {
                            bulkDeleteBtn.disabled = true;
                            bulkDeleteBtn.classList.add('opacity-50', 'cursor-not-allowed', 'hidden');
                            bulkDeleteBtn.classList.remove('flex');
                        }
                    }
                }

                if (selectAllCheckbox) {
                    selectAllCheckbox.addEventListener('change', function() {
                        userCheckboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                        updateBulkDeleteState();
                    });
                }

                userCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const allChecked = Array.from(userCheckboxes).every(c => c.checked);
                        if (selectAllCheckbox) selectAllCheckbox.checked = allChecked;
                        updateBulkDeleteState();
                    });
                });

                window.submitBulkDelete = function() {
                    const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
                    if (selectedCheckboxes.length === 0) return;

                    Swal.fire({
                        title: 'Hapus ' + selectedCheckboxes.length + ' Data?',
                        text: "Data yang dipilih akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#78716c',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-3xl font-sans',
                            confirmButton: 'rounded-xl px-6 py-2.5',
                            cancelButton: 'rounded-xl px-6 py-2.5'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            bulkDeleteInputs.innerHTML = ''; // Clear previous inputs
                            selectedCheckboxes.forEach(checkbox => {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'ids[]';
                                input.value = checkbox.value;
                                bulkDeleteInputs.appendChild(input);
                            });
                            bulkDeleteForm.submit();
                        }
                    });
                }

                applyAllFilters();
            });
        </script>
    </div>
</x-app-layout>
