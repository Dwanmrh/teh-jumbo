<x-app-layout>
    {{-- Alpine.js & SweetAlert2 --}}
    <script defer src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="py-8 bg-[#f7f7f7] min-h-screen font-[Outfit]" x-data="{ showDetail: false, selectedItem: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-medium text-[#2F362C]">Kas Keluar</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola transaksi pengeluaran</p>
            </div>
            
            <a href="{{ route('kas-keluar.create') }}"
            class="hidden md:inline bg-[#7AC943] hover:bg-[#68AD3A] text-white px-4 py-2 rounded-md font-medium transition">
                + Tambah Kas Keluar
            </a>
        </div>


            {{-- FILTER & SEARCH --}}
            <form method="GET" action="{{ route('kas-keluar.index') }}"
                class="bg-white rounded-xl shadow-md p-4 mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3"
                id="searchForm">

                <div class="flex items-center gap-2 w-full md:w-auto flex-1">
                    <input type="text" name="search" id="searchInput"
                        value="{{ request('search') }}" placeholder="Cari transaksi..."
                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7AC943]" />
                </div>
            </form>

             @php
                $totalKas = $kasKeluar->sum('nominal');
                $jumlahTransaksi = $kasKeluar->count();
            @endphp

            @php
    function getKategoriColor($kategori) {
        return match (strtolower($kategori)) {
            'pembelian' => ['bg-red-100', 'text-red-600'],
            'gaji' => ['bg-green-100', 'text-green-600'],
            'operasional' => ['bg-blue-100', 'text-blue-600'],
            'lainnya' => ['bg-gray-100', 'text-gray-600'],
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
                <th class="p-3 border-b border-gray-200">No</th>
                <th class="p-3 border-b border-gray-200">Tanggal</th>
                <th class="p-3 border-b border-gray-200">Kode Kas</th>
                <th class="p-3 border-b border-gray-200">Penerima</th>
                <th class="p-3 border-b border-gray-200">Kategori</th>
                <th class="p-3 border-b border-gray-200">Nominal</th>
                <th class="p-3 border-b border-gray-200">Metode</th>
                <th class="p-3 border-b border-gray-200">Deskripsi</th>
                <th class="p-3 border-b border-gray-200">Bukti</th>
                <th class="p-3 border-b border-gray-200">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kasKeluar as $index => $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-3 border-b border-gray-200">{{ $index + 1 }}</td>
                    <td class="p-3 border-b border-gray-200">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="p-3 border-b border-gray-200">{{ $item->kode_kas }}</td>
                    <td class="p-3 border-b border-gray-200">{{ $item->penerima }}</td>
                    <td class="p-3 border-b border-gray-200">
    @php
        [$bgColor, $textColor] = getKategoriColor($item->kategori);
    @endphp
    <span class="px-2 py-1 text-xs rounded-md font-medium {{ $bgColor }} {{ $textColor }}">
        {{ $item->kategori }}
    </span>
</td>


                    <td class="p-3 border-b border-gray-200 font-bold text-red-600">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    <td class="p-3 border-b border-gray-200">{{ $item->metode_pembayaran }}</td>
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
                                  <a href="{{ route('kas-masuk.edit', $item->id) }}"
                                        class="text-blue-500 hover:text-blue-600 transition transform hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.125 19.588 3 21l1.412-4.125L16.862 3.487z" />
                                            </svg>
                                    </a>
                            <form action="{{ route('kas-keluar.destroy', $item->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                            class="text-red-500 hover:text-red-600 transition transform hover:scale-110 delete-btn"
                                            data-nama="{{ $item->keterangan }}">
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



            {{-- LIST MOBILE --}}
            <div id="kasDataContainerMobile" class="md:hidden space-y-4">
                @forelse ($kasKeluar as $item)
                    <div @click="showDetail = true; selectedItem = {{ json_encode($item) }}"
                        class="bg-white rounded-xl shadow-md p-4 border-l-4 border-[#7AC943] flex justify-between items-center cursor-pointer">
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

            {{-- TOMBOL FLOATING MOBILE --}}
            <a href="{{ route('kas-keluar.create') }}"
               class="fixed bottom-6 right-6 bg-[#7AC943] hover:bg-[#68AD3A] text-white w-14 h-14 flex items-center justify-center rounded-full shadow-lg text-3xl z-30 md:hidden">
                +
            </a>

            <!-- DETAIL MOBILE (ganti blok lama dengan ini) -->
            <div x-show="showDetail"
                x-transition
                class="fixed top-[64px] inset-x-0 bottom-0 bg-white z-30 md:hidden overflow-y-auto p-5">
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

                <!-- Bukti Pembayaran: thumbnail kecil (klik buka full) -->
                <template x-if="selectedItem.bukti_pembayaran">
                    <div class="mb-6">
                        <div class="rounded-lg bg-white border border-gray-200 p-3 shadow-sm">
                            <div class="text-xs text-gray-500 mb-2">Bukti Pembayaran</div>

                            <div class="flex items-center gap-3">
                                <!-- Thumbnail -->
                                <template x-if="selectedItem.bukti_pembayaran">
                                    <img 
                                        :src="('/storage/' + selectedItem.bukti_pembayaran)" 
                                        @click="window.open('/storage/' + selectedItem.bukti_pembayaran, '_blank')"
                                        class="w-24 h-24 object-cover rounded-md cursor-pointer border"
                                        alt="Bukti Pembayaran">
                                </template>

                                <!-- Info + tombol lihat -->
                                <div class="flex-1">
                                    <div class="text-sm text-[#2F362C] break-words" x-text="selectedItem.bukti_pembayaran.split('/').pop()"></div>
                                    <div class="mt-2">
                                        <button type="button"
                                            @click="window.open('/storage/' + selectedItem.bukti_pembayaran, '_blank')"
                                            class="inline-flex items-center gap-2 px-3 py-2 bg-white border rounded-md shadow-sm text-sm text-blue-600 hover:bg-gray-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A2 2 0 0122 9.618V18a2 2 0 01-2 2H6a2 2 0 01-2-2V9.618a2 2 0 01.447-1.894L9 10m6 0v6" />
                                            </svg>
                                            Lihat Bukti
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Tombol Edit & Hapus (fixed) -->
                <div class="fixed bottom-6 right-4 flex flex-col gap-3 z-50">
                    <a :href="'/kas-keluar/' + selectedItem.id + '/edit'"
                    class="w-12 h-12 bg-[#EABF59] hover:bg-[#D4AA4E] text-white rounded-full flex items-center justify-center shadow-md text-lg">
                    ✏️
                    </a>

                    <button
                        type="button"
                        @click=" 
                            Swal.fire({
                                title: 'Yakin ingin menghapus?',
                                text: selectedItem.kode_kas || '',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#7AC943',
                                confirmButtonText: 'Ya, hapus!',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // buat form hapus dan submit
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = '/kas-keluar/' + selectedItem.id;

                                    const csrf = document.createElement('input');
                                    csrf.type = 'hidden';
                                    csrf.name = '_token';
                                    csrf.value = document.querySelector('meta[name=csrf-token]').content;
                                    form.appendChild(csrf);

                                    const method = document.createElement('input');
                                    method.type = 'hidden';
                                    method.name = '_method';
                                    method.value = 'DELETE';
                                    form.appendChild(method);

                                    document.body.appendChild(form);
                                    form.submit();
                                }
                            });
                        "
                        class="w-12 h-12 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-md text-lg">
                        🗑️
                    </button>
                </div>
            </div>


    {{-- DELETE ALERT --}}
    <script>
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const form = this.closest('.delete-form');
                const nama = this.dataset.nama;

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    html: `<p>Data <strong>${nama}</strong> akan dihapus secara permanen.</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#FF5252',
                    cancelButtonColor: '#7AC943',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
        @endif
    </script>
</x-app-layout>
