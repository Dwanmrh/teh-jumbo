<x-app-layout>
    {{-- Library wajib --}}
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <script defer src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="py-8 bg-[#f7f7f7] min-h-screen font-[Outfit]" x-data="{ showDetail: false, selectedItem: {}, showTambah: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

         <!-- MODAL TAMBAH KAS KELUAR -->
        <div 
            x-show="showTambah"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div @click.outside="showTambah = false"
                class="bg-white rounded-2xl shadow-lg w-full max-w-lg p-6 relative">
                
                <button @click="showTambah = false" 
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>

                <h2 class="text-xl font-semibold text-[#2F362C] mb-4">Tambah Kas Keluar</h2>

                <form method="POST" action="{{ route('kas-keluar.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-3">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Transaksi</label>
                            <input type="date" name="tanggal" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#7AC943]" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="kategori" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#7AC943]">
                                <option value="Pembelian">Pembelian</option>
                                <option value="Operasional">Operasional</option>
                                <option value="Gaji">Gaji</option>
                                <option value="Lain-lain">Lainnya</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Penerima</label>
                            <input type="text" name="penerima" placeholder="Masukkan penerima"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#7AC943]" />
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nominal</label>
                                <input type="number" name="nominal" min="1" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2"
                                    placeholder="Nominal">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                                <select name="metode_pembayaran"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#7AC943]">
                                    <option value="Qris">Qris</option>
                                    <option value="Transfer">Transfer</option>
                                    <option value="Cash">Cash</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Bukti Pembayaran (Opsional)</label>
                            <input type="file" name="bukti_pembayaran"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-red-600 file:text-white
                                hover:file:bg-[#E0AC3B]">
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2"
                                placeholder="Masukkan deskripsi"></textarea>
                        </div>

                        <button type="submit"
                            class="bg-red-600 hover:bg-red-600 text-white font-medium w-full py-2 rounded-lg transition mt-3">
                            Simpan
                        </button>

                    </div>
                </form>
            </div>
        </div>

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-medium text-[#2F362C]">Kas Keluar</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola transaksi pengeluaran</p>
                </div>
                
                <button type="button" 
                    @click="showTambah = true"
                    class="hidden md:inline bg-red-600 hover:bg-rose-600 text-white px-4 py-2 rounded-md font-medium transition">
                    + Tambah Kas Keluar
                </button>

            </div>


            {{-- FILTER --}}
            <form method="GET" action="{{ route('kas-keluar.index') }}"
                class="bg-white rounded-xl shadow-md p-4 mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3"
                id="searchForm">

                <div class="flex items-center gap-2 w-full md:w-auto flex-1">
                    <input type="text" name="search" id="searchInput"
                        value="{{ request('search') }}" placeholder="Cari transaksi..."
                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7AC943]" />

                    <input type="hidden" name="filter_harga" value="{{ request('filter_harga') }}">
                </div>

                <div class="flex gap-2 flex-wrap items-center">
                    {{-- FILTER HARGA --}}
                    <div class="relative">
                        <button type="button" id="hargaToggle"
                            class="border border-gray-300 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 flex items-center gap-1">
                            <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
                        </button>

                        <div id="hargaDropdown"
                            class="hidden absolute left-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg z-10 p-3">

                            <label class="block text-sm font-medium mb-2">Rentang Harga:</label>

                            <select name="filter_harga" id="hargaSelect"
                                onchange="this.form.submit()"
                                class="w-full border border-gray-300 rounded-lg px-2 py-1 mb-1">
                                <option value="">Semua</option>
                                <option value="0-10000" {{ request('filter_harga')=='0-10000' ? 'selected' : '' }}>
                                    Rp 0 - Rp 10.000
                                </option>
                                <option value="11000-100000" {{ request('filter_harga')=='11000-100000' ? 'selected' : '' }}>
                                    Rp 11.000 - Rp 100.000
                                </option>
                                <option value="100001-999999999" {{ request('filter_harga')=='100001-999999999' ? 'selected' : '' }}>
                                    > Rp 100.000
                                </option>
                            </select>

                        </div>

                    </div>
                    {{-- FILTER WAKTU --}}
                    <div class="relative">
                        <button type="button" id="filterToggle"
                            class="border border-gray-300 px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 flex items-center gap-2">

                            <i class="fa fa-calendar"></i>

                            @if(request('filter_waktu') == 'custom' && request('start_date') && request('end_date'))
                                <span>
                                    {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }}
                                    –
                                    {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                                </span>
                            @elseif(request('filter_waktu'))
                                <span>{{ ucfirst(str_replace('-', ' ', request('filter_waktu'))) }}</span>
                            @endif
                        </button>

                        <div id="filterDropdown"
                            class="hidden absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-10 p-4">

                            <label class="block text-sm font-medium mb-2">Rentang Tanggal:</label>

                            <select name="filter_waktu" id="tanggalSelect"
                                onchange="handleFilterWaktuChange(this)"
                                class="w-full border border-gray-300 rounded-lg px-2 py-1 mb-3">

                                <option value="">Sepanjang Waktu</option>
                                <option value="hari-ini" {{ request('filter_waktu')=='hari-ini' ? 'selected' : '' }}>Hari Ini</option>
                                <option value="kemarin" {{ request('filter_waktu')=='kemarin' ? 'selected' : '' }}>Kemarin</option>
                                <option value="minggu-ini" {{ request('filter_waktu')=='minggu-ini' ? 'selected' : '' }}>Minggu Ini</option>
                                <option value="bulan-ini" {{ request('filter_waktu')=='bulan-ini' ? 'selected' : '' }}>Bulan Ini</option>
                                <option value="bulan-lalu" {{ request('filter_waktu')=='bulan-lalu' ? 'selected' : '' }}>Bulan Lalu</option>
                                <option value="tahun-ini" {{ request('filter_waktu')=='tahun-ini' ? 'selected' : '' }}>Tahun Ini</option>
                                <option value="custom" {{ request('filter_waktu')=='custom' ? 'selected' : '' }}>Tanggal Kustom</option>
                            </select>

                            <!-- CUSTOM DATE RANGE -->
                            <div id="customDateRange" class="{{ request('filter_waktu')=='custom' ? '' : 'hidden' }}">

                                <label class="text-sm text-gray-700">Dari:</label>
                                <input type="date" name="start_date"
                                    onchange="this.form.submit()"
                                    value="{{ request('start_date') }}"
                                    class="w-full border border-gray-300 rounded-lg px-2 py-1 mb-2">

                                <label class="text-sm text-gray-700">Sampai:</label>
                                <input type="date" name="end_date"
                                    onchange="this.form.submit()"
                                    value="{{ request('end_date') }}"
                                    class="w-full border border-gray-300 rounded-lg px-2 py-1 mb-3">
                            </div>

                            <script>
                                function handleFilterWaktuChange(select) {
                                    const customDiv = document.getElementById('customDateRange');

                                    if (select.value === 'custom') {
                                        customDiv.classList.remove('hidden');
                                    } else {
                                        customDiv.classList.add('hidden');
                                        select.form.submit(); // langsung submit
                                    }
                                }
                            </script>

                        </div>
                    </div>


                        {{-- TOMBOL RESET FILTER --}}
                        <a href="{{ route('kas-keluar.index') }}"
                            class="border border-gray-300 px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                        </a>
                </div>
            </form>

            {{-- Script dropdown & search --}}
            <script>
                const hargaToggle = document.getElementById('hargaToggle');
                const hargaDropdown = document.getElementById('hargaDropdown');
                const filterToggle = document.getElementById('filterToggle');
                const filterDropdown = document.getElementById('filterDropdown');
                const tanggalSelect = document.getElementById('tanggalSelect');
                const customDateRange = document.getElementById('customDateRange');
                const searchInput = document.getElementById('searchInput');
                const searchForm = document.getElementById('searchForm');

                hargaToggle.addEventListener('click', e => {
                    e.stopPropagation();

                     filterDropdown.classList.add('hidden');


                    hargaDropdown.classList.toggle('hidden');
                });

                filterToggle.addEventListener('click', e => {
                    e.stopPropagation();

                     hargaDropdown.classList.add('hidden');

                    filterDropdown.classList.toggle('hidden');
                });

                tanggalSelect.addEventListener('change', () => {
                    customDateRange.classList.toggle('hidden', tanggalSelect.value !== 'custom');
                });

                document.addEventListener('click', e => {
                    if (!hargaDropdown.contains(e.target) && !hargaToggle.contains(e.target)) hargaDropdown.classList.add('hidden');
                    if (!filterDropdown.contains(e.target) && !filterToggle.contains(e.target)) filterDropdown.classList.add('hidden');
                });

                let searchTimeout;
                searchInput.addEventListener('input', () => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        searchForm.submit();
                    }, 400);
                });
            </script>

            @php
                $totalKas = $kasKeluar->sum('nominal');
                $jumlahTransaksi = $kasKeluar->count();
            @endphp

            @php
                function getKategoriColor($kategori) {
                    return match (strtolower($kategori)) {
                        'pembelian' => ['bg-red-100', 'text-red-600'],
                        'operasional' => ['bg-yellow-100', 'text-yellow-600'],
                        'gaji' => ['bg-green-100', 'text-green-600'],
                        'lain-lain' => ['bg-gray-100', 'text-gray-600'],
                        default => ['bg-gray-200', 'text-gray-700'],
                    };
                }
            @endphp

            {{-- CARD TOTAL --}}
            <div class="bg-gradient-to-r from-[#FF6A6A] to-[#D60000] text-white p-6 rounded-xl shadow-md mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm opacity-90">Total Kas Keluar</p>
                        <p class="text-3xl font-bold">Rp {{ number_format($totalKas, 0, ',', '.') }}</p>

                        <p class="mt-2 text-sm opacity-90">
                            {{ $jumlahTransaksi }} transaksi tercatat
                        </p>
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 scale-y-[-1]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" stroke-linecap="round" stroke-linejoin="round"/>
                        <polyline points="17 6 23 6 23 12" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>


            {{-- TABEL DESKTOP --}}
            <div id="kasDataContainer" class="hidden md:block bg-white p-5 rounded-xl shadow-md">
                <table class="w-full border-collapse text-center text-sm">
                    <thead class="bg-gray-100 text-[#2F362C] font-medium">
                        <tr>
                            <th class="p-3 border-b border-gray-200">Kode Kas</th>
                            <th class="p-3 border-b border-gray-200">Tanggal Transaksi</th>
                            <th class="p-3 border-b border-gray-200">Kategori</th>
                            <th class="p-3 border-b border-gray-200">Metode Pembayaran</th>
                            <th class="p-3 border-b border-gray-200">Penerima</th>
                            <th class="p-3 border-b border-gray-200">Nominal</th>
                            <th class="p-3 border-b border-gray-200">Deskripsi</th>
                            <th class="p-3 border-b border-gray-200">Bukti Pembayaran</th>
                            <th class="p-3 border-b border-gray-200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kasKeluar as $index => $item)
                            <tr class="border-b hover:bg-[#f9f9f9]">
                                <td class="p-3 border-b border-gray-200">{{ $item->kode_kas }}</td>
                                <td class="p-3 border-b border-gray-200">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td class="p-3 border-b border-gray-200 text-blue-600 font-medium">
                                    @php
                                    [$bgColor, $textColor] = getKategoriColor($item->kategori);
                                        @endphp
                                        <span class="px-2 py-1 text-xs rounded-md font-medium {{ $bgColor }} {{ $textColor }}">
                                            {{ $item->kategori }}
                                        </span>
                                </td>
                                <td class="p-3 border-b border-gray-200">{{ $item->metode_pembayaran }}</td>
                                <td class="p-3 border-b border-gray-200">{{ $item->penerima}}</td>
                                <td class="p-3 border-b border-gray-200 text-rose-600">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                <td class="p-3 border-b border-gray-200">{{ $item->deskripsi ?? '-' }}</td>
                                <td class="p-3 border-b border-gray-200">
                                    @if($item->bukti_pembayaran)
                                        <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}" target="_blank" class="text-blue-500 underline">Lihat</a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="p-3 border-b border-gray-200">
                                    <div class="flex justify-center gap-6">
                                        <a href="{{ route('kas-keluar.edit', $item->id) }}"
                                        class="text-blue-500 hover:text-blue-600 transition transform hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.125 19.588 3 21l1.412-4.125L16.862 3.487z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('kas-keluar.destroy', $item->id) }}" 
                                            method="POST" 
                                            id="deleteForm-{{ $item->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="text-red-500 hover:text-red-600 transition transform hover:scale-110 delete-btn"
                                                    data-id="{{ $item->id }}"
                                                    data-deskripsi="{{ $item->deskripsi ?? '' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h10" />
                                                </svg>
                                            </button>
                                        </form>

                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-gray-500 p-3">Belum ada data kas keluar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <script>
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const deskripsi = btn.dataset.deskripsi;
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                html: deskripsi ? `<p>Data dengan deskripsi <strong>${deskripsi}</strong> akan dihapus.</p>` : 'Data ini akan dihapus secara permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if(result.isConfirmed){
                    document.getElementById('deleteForm-' + id).submit();
                }
            });
        });
    });
</script>



            {{-- MOBILE LIST --}}
             <div id="kasDataContainerMobile" class="md:hidden space-y-4">
                @forelse ($kasKeluar as $item)
                    <div @click="showDetail = true; selectedItem = {{ json_encode($item) }}"
                        class="bg-white rounded-xl shadow-md p-4 border-l-4 border-rose-600 flex justify-between items-center cursor-pointer">
                        <div>
                            <p class="text-base font-semibold text-[#2F362C]">{{ $item->kategori }}</p>
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[#2F362C] text-sm font-bold">
                                Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 mt-4">Belum ada transaksi kas keluar.</p>
                @endforelse
            </div>

            {{-- FLOATING BUTTON --}}
            <button @click="showTambah = true"
                class="fixed bottom-6 right-6 bg-[#7AC943] hover:bg-[#68AD3A] text-white w-14 h-14 flex items-center justify-center rounded-full shadow-lg text-3xl z-30 md:hidden">
                +
            </button>


            <!-- DETAIL MOBILE (FULL FIX) -->
<div x-show="showDetail"
    x-transition
    class="fixed top-[64px] inset-x-0 bottom-0 bg-white z-30 md:hidden overflow-y-auto p-5">

    <!-- HEADER -->
    <header class="flex items-center mb-6">
        <button @click="showDetail = false" class="text-2xl text-gray-700 mr-4 font-bold">&larr;</button>
        <h1 class="text-xl font-semibold text-[#2F362C]">Rincian Transaksi</h1>
    </header>

    <!-- Kode Kas -->
    <div class="mb-4">
        <div class="rounded-lg bg-white border border-gray-200 p-4 shadow-sm">
            <div class="text-xs text-gray-500">Kode Kas</div>
            <div class="text-lg font-semibold text-[#2F362C]" x-text="selectedItem.kode_kas || '-'"></div>
        </div>
    </div>

    <!-- Total / Nominal (kotak kuning) -->
    <div class="mb-4">
        <div class="rounded-lg bg-[#FFF2CF] border border-[#F5D88C] p-5 shadow-sm text-center">
            <div class="text-sm text-gray-600">Total Kas Keluar</div>
            <div class="text-2xl font-bold text-[#2F362C]" x-text="selectedItem.nominal ? ('Rp ' + new Intl.NumberFormat('id-ID').format(selectedItem.nominal)) : 'Rp 0'"></div>
        </div>
    </div>

    <!-- Dua kolom: Tanggal | Metode Pembayaran -->
    <div class="grid grid-cols-2 gap-3 mb-3">
        <div class="rounded-lg bg-white border border-gray-200 p-3 shadow-sm">
            <div class="text-xs text-gray-500">Tanggal Transaksi</div>
            <div class="font-medium text-[#2F362C]" x-text="selectedItem.tanggal ? (new Date(selectedItem.tanggal).toLocaleDateString()) : '-'"></div>
        </div>

        <div class="rounded-lg bg-white border border-gray-200 p-3 shadow-sm">
            <div class="text-xs text-gray-500">Metode Pembayaran</div>
            <div class="font-medium text-[#2F362C]" x-text="selectedItem.metode_pembayaran || '-'"></div>
        </div>
    </div>

    <!-- Dua kolom: Penerima | Kategori -->
    <div class="grid grid-cols-2 gap-3 mb-3">
        <div class="rounded-lg bg-white border border-gray-200 p-3 shadow-sm">
            <div class="text-xs text-gray-500">Penerima</div>
            <div class="font-medium text-[#2F362C]" x-text="selectedItem.penerima || '-'"></div>
        </div>

        <div class="rounded-lg bg-white border border-gray-200 p-3 shadow-sm">
            <div class="text-xs text-gray-500">Kategori</div>
            <div class="font-medium text-[#2F362C]" x-text="selectedItem.kategori || '-'"></div>
        </div>
    </div>

    <!-- Deskripsi -->
    <div class="mb-3">
        <div class="rounded-lg bg-white border border-gray-200 p-3 shadow-sm min-h-[70px]">
            <div class="text-xs text-gray-500 mb-1">Deskripsi</div>
            <div class="text-sm text-[#2F362C]" x-text="selectedItem.deskripsi || '-'"></div>
        </div>
    </div>

    <!-- Bukti Pembayaran: thumbnail kecil -->
    <template x-if="selectedItem.bukti_pembayaran">
        <div class="mb-6">
            <div class="rounded-lg bg-white border border-gray-200 p-3 shadow-sm">
                <div class="text-xs text-gray-500 mb-2">Bukti Pembayaran</div>

                <div class="flex items-center gap-3">
                    <img 
                        :src="('/storage/' + selectedItem.bukti_pembayaran)" 
                        @click="window.open('/storage/' + selectedItem.bukti_pembayaran, '_blank')"
                        class="w-24 h-24 object-cover rounded-md cursor-pointer border"
                        alt="Bukti Pembayaran">

                    <div class="flex-1">
                        <div class="text-sm text-[#2F362C] break-words" x-text="selectedItem.bukti_pembayaran.split('/').pop()"></div>
                        <div class="mt-2">
                            <button type="button"
                                @click="window.open('/storage/' + selectedItem.bukti_pembayaran, '_blank')"
                                class="inline-flex items-center gap-2 px-3 py-2 bg-white border rounded-md shadow-sm text-sm text-blue-600 hover:bg-gray-50">
                                <i class="fa-solid fa-eye"></i> Lihat Bukti
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Tombol Edit & Hapus -->
    <div class="fixed bottom-6 right-4 flex flex-col gap-2 z-50">
        <!-- Tombol Edit -->
        <a :href="`/kas-keluar/${selectedItem.id}/edit`"
            class="w-12 h-12 bg-[#EABF59] hover:bg-[#D4AA4E] text-white rounded-full flex items-center justify-center shadow-md text-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.125 19.588 3 21l1.412-4.125L16.862 3.487z" />
            </svg>
        </a>

        <!-- Form Hapus -->
        <form x-ref="deleteForm" :action="`/kas-keluar/${selectedItem.id}`" method="POST">
            @csrf
            @method('DELETE')

            <button type="button"
                @click="
                    if(!selectedItem.id) return;
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        html: selectedItem.deskripsi
                            ? `<p>Data dengan deskripsi <strong>${selectedItem.deskripsi}</strong> akan dihapus secara permanen.</p>`
                            : 'Data ini akan dihapus secara permanen.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $refs.deleteForm.submit();
                        }
                    })
                "
                class="w-12 h-12 bg-[#F87171] hover:bg-[#E55353] text-white rounded-full flex items-center justify-center shadow-md text-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h10" />
                </svg>
            </button>
        </form>
    </div>
</div>

        </div>
    </div>
</x-app-layout>
