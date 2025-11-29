<x-app-layout>
    {{-- Library wajib --}}
    <script defer src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="py-8 bg-[#f7f7f7] min-h-screen font-[Outfit]" x-data="{ showDetail: false, selectedItem: {}, showTambah: false }">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-9">

            {{-- ... (Modal Tambah Kas Masuk) ... --}}
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

                    <h2 class="text-xl font-semibold text-[#2F362C] mb-4">Tambah Kas Masuk</h2>

                    <form method="POST" action="{{ route('kas-masuk.store') }}">
                        @csrf
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Transaksi</label>
                                <input type="date" name="tanggal_transaksi" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#7AC943]" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                <input type="text" name="keterangan" placeholder="Contoh: Penjualan produk"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#7AC943]" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select name="kategori" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#7AC943]">
                                    <option value="Penjualan">Penjualan</option>
                                    <option value="Lain-lain">Lain-lain</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                    <input type="number" name="jumlah" required min="1"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#7AC943]" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Harga Satuan</label>
                                    <input type="number" name="harga_satuan" required min="0"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#7AC943]" />
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                                <select name="metode_pembayaran" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#7AC943]">
                                    <option value="Tunai">Tunai</option>
                                    <option value="Transfer">Transfer</option>
                                    <option value="QRIS">QRIS</option>
                                </select>
                            </div>

                            <button type="submit"
                                class="bg-green-600 hover:bg-green-600 text-white font-medium w-full py-2 rounded-lg transition mt-3">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-medium text-[#2F362C]">Kas Masuk</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola transaksi pemasukan</p>
                </div>
                
                <button type="button" 
                    @click="showTambah = true"
                    class="hidden md:inline bg-green-600 hover:bg-green-600 text-white px-4 py-2 rounded-md font-medium transition">
                    + Tambah Kas Masuk
                </button>

            </div>


            {{-- FILTER (Perubahan: Tambahkan ID 'filterForm' dan ubah action) --}}
            <form method="GET" action="{{ route('kas-masuk.index') }}"
                class="bg-white rounded-xl shadow-md p-4 mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3"
                id="filterForm"> {{-- Ubah ID agar tidak rancu dengan searchForm --}}

                <div class="flex items-center gap-2 w-full md:w-auto flex-1">
                    <input type="text" name="search" id="searchInput"
                        value="{{ request('search') }}" placeholder="Cari transaksi..."
                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7AC943]" />

                </div>

                <div class="flex gap-2 flex-wrap items-center">
                    {{-- FILTER HARGA (Tombol Terapkan di sini tetap submit form untuk filtering harga/waktu) --}}
                    {{-- ... (Filter Harga tidak diubah) ... --}}
                    <div class="relative">
                        <button type="button" id="hargaToggle"
                            class="border border-gray-300 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 flex items-center gap-1">
                            <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
                        </button>

                        <div id="hargaDropdown"
                            class="hidden absolute left-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg z-10 p-3">
                            <label class="block text-sm font-medium mb-2">Rentang Harga:</label>
                            <select name="filter_harga" id="hargaSelect"
                                class="w-full border border-gray-300 rounded-lg px-2 py-1 mb-3">
                                <option value="">Semua</option>
                                <option value="0-10000" {{ request('filter_harga')=='0-10000' ? 'selected' : '' }}>Rp 0 - Rp 10.000</option>
                                <option value="11000-100000" {{ request('filter_harga')=='11000-100000' ? 'selected' : '' }}>Rp 11.000 - Rp 100.000</option>
                                <option value="100001-999999999" {{ request('filter_harga')=='100001-999999999' ? 'selected' : '' }}>> Rp 100.000</option>
                            </select>
                            <button type="submit" class="bg-[#7AC943] hover:bg-[#68AD3A] text-white w-full py-2 rounded-lg">Terapkan</button>
                        </div>
                    </div>

                    {{-- FILTER WAKTU --}}
                    {{-- ... (Filter Waktu tidak diubah) ... --}}
                    <div class="relative">
                        <button type="button" id="filterToggle"
                            class="border border-gray-300 px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                            
                            <i class="fa fa-calendar" aria-hidden="true"></i>

                            @if(request('filter_waktu') == 'custom' && request('start_date') && request('end_date'))
                                <span>
                                    {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} - {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                                </span>
                            @elseif(request('filter_waktu'))
                                <span>{{ ucfirst(str_replace('-', ' ', request('filter_waktu'))) }}</span>
                            @endif
                        </button>


                        <div id="filterDropdown"
                            class="hidden absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-10 p-4">
                            <label class="block text-sm font-medium mb-2">Rentang Tanggal:</label>
                            <select name="filter_waktu" id="tanggalSelect"
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

                            <div id="customDateRange" class="{{ request('filter_waktu')=='custom' ? '' : 'hidden' }}">
                                <label class="text-sm text-gray-700">Dari:</label>
                                <input type="date" name="start_date"
                                    class="w-full border border-gray-300 rounded-lg px-2 py-1 mb-2"
                                    value="{{ request('start_date') }}">
                                <label class="text-sm text-gray-700">Sampai:</label>
                                <input type="date" name="end_date"
                                    class="w-full border border-gray-300 rounded-lg px-2 py-1 mb-3"
                                    value="{{ request('end_date') }}">
                            </div>

                            <button type="submit" class="bg-[#7AC943] hover:bg-[#68AD3A] text-white w-full py-2 rounded-lg mb-2">Terapkan</button>

                        </div>
                    </div>

                        {{-- TOMBOL RESET FILTER --}}
                        <a href="{{ route('kas-masuk.index') }}"
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
                const filterForm = document.getElementById('filterForm'); // Gunakan ID filterForm

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

                // --- PERUBAHAN UTAMA UNTUK SEARCH LOKAL (NON-RELOAD) ---
                searchInput.addEventListener('input', () => {
                    const query = searchInput.value.toLowerCase();
                    
                    // Filter untuk tabel desktop
                    const desktopRows = document.querySelectorAll('#kasDataContainer table tbody tr');
                    desktopRows.forEach(row => {
                        // Pastikan tidak menyertakan baris 'Belum ada data'
                        if (row.querySelector('td[colspan="10"]')) return; 

                        // Ambil teks dari kolom yang ingin dicari (Kode Kas, Keterangan, Jumlah, Total)
                        const kodeKas = row.children[0].textContent.toLowerCase();
                        const keterangan = row.children[2].textContent.toLowerCase();
                        const total = row.children[6].textContent.toLowerCase();

                        if (kodeKas.includes(query) || keterangan.includes(query) || total.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Filter untuk mobile list
                    const mobileItems = document.querySelectorAll('#kasDataContainerMobile > div');
                    mobileItems.forEach(item => {
                        // Ambil data yang tersimpan di atribut data-keterangan/data-total di AlpineJS
                        // Karena di mobile datanya tersimpan di x-data (yang di-JSON-encode), 
                        // kita akan mencari berdasarkan teks yang terlihat di list (keterangan dan total)
                        const visibleKeterangan = item.querySelector('.font-semibold').textContent.toLowerCase();
                        const visibleTotal = item.querySelector('.text-right .font-semibold').textContent.toLowerCase();

                        if (visibleKeterangan.includes(query) || visibleTotal.includes(query)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
                // --- AKHIR PERUBAHAN UTAMA ---
            </script>

            @php
                $totalKas = $kasMasuk->sum('total');
                $jumlahTransaksi = $kasMasuk->count();
            @endphp

            @php
                function getKategoriColor($kategori) {
                    return match (strtolower($kategori)) {
                        'penjualan' => ['bg-blue-100', 'text-blue-600'],
                        'lain-lain' => ['bg-gray-100', 'text-gray-600'],
                        default => ['bg-gray-200', 'text-gray-700'],
                    };
                }
            @endphp

            {{-- CARD TOTAL --}}
            {{-- ... (Card Total tidak diubah) ... --}}
            <div class="bg-gradient-to-r from-[#4CC66A] to-[#1F8A3A] text-white p-6 rounded-xl shadow-md mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm opacity-90">Total Kas Masuk</p>
                        <p class="text-3xl font-bold">Rp {{ number_format($totalKas, 0, ',', '.') }}</p>

                        <p class="mt-2 text-sm opacity-90">
                            {{ $jumlahTransaksi }} transaksi tercatat
                        </p>
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
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
                            <th class="p-3 border-b border-gray-200">Keterangan</th>
                            <th class="p-3 border-b border-gray-200">Kategori</th>
                            <th class="p-3 border-b border-gray-200">Jumlah</th>
                            <th class="p-3 border-b border-gray-200">Harga Satuan</th>
                            <th class="p-3 border-b border-gray-200">Total</th>
                            <th class="p-3 border-b border-gray-200">Metode Pembayaran</th>
                            <th class="p-3 border-b border-gray-200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kasMasuk as $index => $item)
                            <tr class="border-b hover:bg-[#f9f9f9]">
                                <td class="p-3 border-b border-gray-200">{{ $item->kode_kas }}</td>
                                <td class="p-3 border-b border-gray-200">{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d M Y') }}</td>
                                <td class="p-3 border-b border-gray-200">{{ $item->keterangan }}</td>
                                <td class="p-3 border-b border-gray-200 text-blue-600 font-medium">
                                    @php
                                    [$bgColor, $textColor] = getKategoriColor($item->kategori);
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-md font-medium {{ $bgColor }} {{ $textColor }}">
                                        {{ $item->kategori }}
                                    </span>
                                </td>
                                <td class="p-3 border-b border-gray-200">{{ $item->jumlah }}</td>
                                <td class="p-3 border-b border-gray-200">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                <td class="p-3 border-b border-gray-200 font-bold text-emerald-600">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                <td class="p-3 border-b border-gray-200">{{ $item->metode_pembayaran }}</td>
                                <td class="p-3 border-b border-gray-200">
                                    <div class="flex justify-center gap-6">
                                        <a href="{{ route('kas-masuk.edit', $item->id) }}"
                                        class="text-blue-500 hover:text-blue-600 transition transform hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.125 19.588 3 21l1.412-4.125L16.862 3.487z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('kas-masuk.destroy', $item->id) }}" 
                                            method="POST" 
                                            id="deleteForm-{{ $item->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                     class="text-red-500 hover:text-red-600 transition transform hover:scale-110 delete-btn"
                                                     data-id="{{ $item->id }}"
                                                     data-deskripsi="{{ $item->keterangan ?? '' }}"> {{-- Gunakan $item->keterangan --}}
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
                                <td colspan="10" class="text-center text-gray-500 p-3">Belum ada data kas masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- ... (Script SweetAlert Delete tidak diubah) ... --}}
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
            {{-- ... (Mobile List dan Detail Mobile tidak diubah) ... --}}
            <div id="kasDataContainerMobile" class="md:hidden space-y-4">
                @forelse ($kasMasuk as $item)
                    <div @click="showDetail = true; selectedItem = JSON.parse('{{ json_encode([
                        'id' => $item->id,
                        'kode_kas' => $item->kode_kas,
                        'tanggal_transaksi' => \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d M Y'),
                        'keterangan' => $item->keterangan ?? 'Tidak Ada Keterangan',
                        'kategori' => $item->kategori,
                        'jumlah' => $item->jumlah,
                        'harga_satuan' => number_format($item->harga_satuan, 0, ',', '.'),
                        'total' => number_format($item->total, 0, ',', '.'),
                        'metode_pembayaran' => $item->metode_pembayaran,
                        'edit_url' => route('kas-masuk.edit', $item->id),
                        'delete_url' => route('kas-masuk.destroy', $item->id),
                    ]) }}')"
                        class="bg-white rounded-xl shadow-md p-4 border-l-4 border-[#7AC943] flex justify-between items-center cursor-pointer">
                        <div>
                            <p class="text-base font-semibold text-[#2F362C]">{{ $item->keterangan }}</p>
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d M Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[#2F362C] font-semibold">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 mt-4">Belum ada transaksi kas masuk.</p>
                @endforelse
            </div>

            {{-- FLOATING BUTTON --}}
            {{-- ... (Floating Button dan Detail Mobile tidak diubah) ... --}}
            <button @click="showTambah = true"
                class="fixed bottom-6 right-6 bg-[#7AC943] hover:bg-[#68AD3A] text-white w-14 h-14 flex items-center justify-center rounded-full shadow-lg text-3xl z-30 md:hidden">
                +
            </button>


            {{-- DETAIL MOBILE --}}
            <div x-show="showDetail"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-full"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-full"
                class="fixed top-[64px] inset-x-0 bottom-0 bg-white z-30 md:hidden overflow-y-auto p-5">

                <header class="flex justify-start items-center mb-6">
                    <button @click="showDetail = false" class="text-2xl text-gray-700 mr-4 font-bold">&larr;</button>
                    <h1 class="text-xl font-semibold text-[#2F362C]">Rincian Transaksi</h1>
                </header>

                <div class="space-y-6">
                    <div class="p-5 bg-gray-50 border border-gray-200 rounded-xl shadow-sm">
                        <label class="text-sm text-gray-500 font-medium block mb-1">Kode Kas</label>
                        <p class="text-lg font-semibold text-[#2F362C]" x-text="selectedItem.kode_kas"></p>
                    </div>

                    <div class="p-5 bg-[#FFF2CF] border border-[#F5C04C] rounded-xl shadow-sm">
                        <label class="text-sm text-gray-700 font-medium block mb-1">Total Kas Masuk</label>
                        <p class="text-3xl font-extrabold text-[#2F362C]" x-text="'Rp ' + selectedItem.total"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="text-xs text-gray-500">Tanggal Transaksi</div>
                            <div class="font-medium text-[#2F362C] text-lg" x-text="selectedItem.tanggal_transaksi"></div>
                        </div>
                        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="text-xs text-gray-500">Metode Pembayaran</div>
                            <div class="font-medium text-[#2F362C] text-lg" x-text="selectedItem.metode_pembayaran"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="text-xs text-gray-500">Jumlah</div>
                            <div class="font-medium text-[#2F362C] text-lg" x-text="selectedItem.jumlah"></div>
                        </div>
                        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="text-xs text-gray-500">Harga Satuan</div>
                            <div class="font-medium text-[#2F362C] text-lg" x-text="'Rp ' + selectedItem.harga_satuan"></div>
                        </div>
                    </div>

                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm min-h-[100px]">
                        <label class="text-xs text-gray-500 block mb-1">Keterangan</label>
                        <p class="text-base text-[#2F362C] mt-1" x-text="selectedItem.keterangan || 'Tidak Ada Keterangan'"></p>
                    </div>
                </div>

                {{-- Tombol Edit & Hapus --}}
                <div class="fixed bottom-6 right-4 flex flex-col gap-2 z-50">
                    <a :href="selectedItem.edit_url"
                        class="w-12 h-12 bg-[#EABF59] hover:bg-[#D4AA4E] text-white rounded-full flex items-center justify-center shadow-md text-lg">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>

                    <button type="button" 
                        @click="
                            Swal.fire({
                                title: 'Yakin ingin menghapus?',
                                html: selectedItem.keterangan
                                    ? `<p>Data <strong>${selectedItem.keterangan}</strong> akan dihapus secara permanen.</p>`
                                    : 'Data akan dihapus secara permanen.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: 'Ya, Hapus',
                                cancelButtonText: 'Batal',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = selectedItem.delete_url;
                                    const csrfInput = document.createElement('input');
                                    csrfInput.type = 'hidden';
                                    csrfInput.name = '_token';
                                    csrfInput.value = document.querySelector('meta[name=csrf-token]').content;
                                    const methodInput = document.createElement('input');
                                    methodInput.type = 'hidden';
                                    methodInput.name = '_method';
                                    methodInput.value = 'DELETE';
                                    form.append(csrfInput, methodInput);
                                    document.body.appendChild(form);
                                    form.submit();
                                }
                            });
                        "
                        class="w-12 h-12 bg-gray-700 hover:bg-gray-800 text-white rounded-full flex items-center justify-center shadow-md text-lg">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        
    </div>

</x-app-layout>