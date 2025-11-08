<x-app-layout>
    {{-- Library wajib --}}
    <script defer src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="py-8 bg-[#f7f7f7] min-h-screen font-[Outfit]" x-data>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-medium text-[#2F362C]">Kas Keluar</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola transaksi pengeluaran</p>
                </div>

                <button id="openModalBtn"
                    class="hidden md:inline bg-[#FF5252] hover:bg-[#D60000] text-white px-4 py-2 rounded-md font-medium transition">
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
                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FF5252]" />
                    <input type="hidden" name="filter_harga" value="{{ request('filter_harga') }}">
                </div>

                <div class="flex gap-2 flex-wrap items-center">
                    {{-- FILTER HARGA --}}
                    <div class="relative">
                        <button type="button" id="hargaToggle"
                            class="border border-gray-300 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                            💰
                            @if(request('filter_harga') == '0-10000')
                                <span>Rp 0 - 10.000</span>
                            @elseif(request('filter_harga') == '11000-100000')
                                <span>Rp 11.000 - 100.000</span>
                            @elseif(request('filter_harga') == '100001-999999999')
                                <span>> Rp 100.000</span>
                            @else
                                <span class="text-gray-500">Semua</span>
                            @endif
                        </button>

                        <div id="hargaDropdown"
                            class="hidden absolute left-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg z-10 p-3">
                            <label class="block text-sm font-medium mb-2">Rentang Nominal:</label>
                            <select name="filter_harga" id="hargaSelect"
                                class="w-full border border-gray-300 rounded-lg px-2 py-1 mb-3">
                                <option value="">Semua</option>
                                <option value="0-10000" {{ request('filter_harga')=='0-10000' ? 'selected' : '' }}>Rp 0 - Rp 10.000</option>
                                <option value="11000-100000" {{ request('filter_harga')=='11000-100000' ? 'selected' : '' }}>Rp 11.000 - Rp 100.000</option>
                                <option value="100001-999999999" {{ request('filter_harga')=='100001-999999999' ? 'selected' : '' }}> > Rp 100.000</option>
                            </select>
                            <button type="submit" class="bg-[#FF5252] hover:bg-[#D60000] text-white w-full py-2 rounded-lg">Terapkan</button>
                        </div>
                    </div>

                    {{-- FILTER WAKTU --}}
                    <div class="relative">
                        <button type="button" id="filterToggle"
                            class="border border-gray-300 px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                            📅
                            @if(request('filter_waktu') == 'custom' && request('start_date') && request('end_date'))
                                <span>
                                    {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} -
                                    {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                                </span>
                            @elseif(request('filter_waktu'))
                                <span>{{ ucfirst(str_replace('-', ' ', request('filter_waktu'))) }}</span>
                            @else
                                <span class="text-gray-500">Semua</span>
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

                            <button type="submit"
                                class="bg-[#FF5252] hover:bg-[#D60000] text-white w-full py-2 rounded-lg mb-2">Terapkan</button>
                        </div>
                    </div>

                    {{-- RESET --}}
                    <a href="{{ route('kas-keluar.index') }}"
                        class="border border-gray-300 px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                        🔄
                    </a>
                </div>
            </form>

            {{-- CARD TOTAL --}}
            <div class="bg-gradient-to-r from-[#FF6A6A] to-[#D60000] text-white p-6 rounded-xl shadow-md mb-6">
                <p class="text-sm opacity-90">Total Kas Keluar</p>
                <p class="text-3xl font-bold">Rp {{ number_format($kasKeluar->sum('nominal'), 0, ',', '.') }}</p>
                <p class="mt-2 text-sm opacity-90">{{ $kasKeluar->count() }} transaksi tercatat</p>
            </div>

            {{-- TABEL DATA --}}
            <div class="bg-white rounded-xl shadow-md p-5 overflow-x-auto">
                <table class="w-full border-collapse text-center text-sm min-w-[800px]">
                    <thead class="bg-gray-100 text-[#2F362C] font-medium">
                        <tr>
                            <th class="p-3">Kode Kas</th>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Penerima</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Nominal</th>
                            <th class="p-3">Metode</th>
                            <th class="p-3">Deskripsi</th>
                            <th class="p-3">Bukti</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kasKeluar as $item)
                            <tr class="border-b hover:bg-[#f9f9f9]">
                                <td>{{ $item->kode_kas }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td>{{ $item->penerima }}</td>
                                <td>{{ $item->kategori }}</td>
                                <td class="font-semibold text-red-700">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                <td>{{ $item->metode_pembayaran }}</td>
                                <td>{{ $item->deskripsi ?? '-' }}</td>
                                <td>
                                    @if($item->bukti_pembayaran)
                                        <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}" target="_blank"
                                            class="text-blue-500 underline">Lihat</a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex justify-center gap-3">
                                        <a href="{{ route('kas-keluar.edit', $item->id) }}"
                                            class="text-blue-500 hover:text-blue-700 transition transform hover:scale-110">✎</a>
                                        <form method="POST" action="{{ route('kas-keluar.destroy', $item->id) }}" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="text-red-500 hover:text-red-700 transition transform hover:scale-110 delete-btn"
                                                data-nama="{{ $item->kode_kas }}">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="py-3 text-gray-500">Belum ada data kas keluar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- FLOATING BUTTON MOBILE --}}
            <button id="openModalBtnMobile"
                class="fixed bottom-6 right-6 bg-[#FF5252] hover:bg-[#D60000] text-white w-14 h-14 flex items-center justify-center rounded-full shadow-lg text-3xl z-30 md:hidden">
                +
            </button>

            {{-- MODAL TAMBAH --}}
            <div id="createModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-[1000]">
                <div class="bg-white rounded-xl shadow-lg w-[90%] md:w-[400px] p-6 relative">
                    <h3 class="text-lg font-semibold mb-4 text-[#2F362C]">Tambah Kas Keluar</h3>

                    <form method="POST" action="{{ route('kas-keluar.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium">Tanggal Transaksi</label>
                            <input type="date" name="tanggal" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium">Penerima</label>
                            <input type="text" name="penerima" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium">Kategori</label>
                            <select name="kategori" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                                <option value="" disabled selected>Pilih kategori</option>
                                <option value="Pembelian">Pembelian</option>
                                <option value="Gaji">Gaji</option>
                                <option value="Operasional">Operasional</option>
                                <option value="Lainnya">Lain-lain</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium">Nominal</label>
                            <input type="number" name="nominal" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium">Metode Pembayaran</label>
                            <div class="flex gap-2">
                                <button type="button" class="metode-btn flex-1 py-2 rounded-lg border border-gray-300 bg-gray-100 text-gray-700 font-medium" data-value="Tunai">Tunai</button>
                                <button type="button" class="metode-btn flex-1 py-2 rounded-lg border border-gray-300 bg-gray-100 text-gray-700 font-medium" data-value="QRIS">QRIS</button>
                                <button type="button" class="metode-btn flex-1 py-2 rounded-lg border border-gray-300 bg-gray-100 text-gray-700 font-medium" data-value="Transfer">Transfer</button>
                            </div>
                            <input type="hidden" name="metode_pembayaran" id="metode_pembayaran" required>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium">Deskripsi</label>
                            <textarea name="deskripsi" class="w-full border border-gray-300 rounded-md px-3 py-2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium">Upload Bukti Pembayaran</label>
                            <input type="file" name="bukti_pembayaran" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" id="closeModalBtn"
                                class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Batal</button>
                            <button type="submit"
                                class="bg-[#FF5252] hover:bg-[#D60000] text-white px-4 py-2 rounded-md">Simpan</button>
                        </div>
                    </form>

                    <button id="closeModalIcon" class="absolute top-2 right-3 text-gray-500 hover:text-gray-800">✕</button>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        // Modal
        const modal = document.getElementById("createModal");
        const openBtn = document.getElementById("openModalBtn");
        const openBtnMobile = document.getElementById("openModalBtnMobile");
        const closeBtn = document.getElementById("closeModalBtn");
        const closeIcon = document.getElementById("closeModalIcon");
        const metodeBtns = document.querySelectorAll(".metode-btn");
        const metodeInput = document.getElementById("metode_pembayaran");

        const openModal = () => { modal.classList.remove("hidden"); modal.classList.add("flex"); document.body.style.overflow = "hidden"; };
        const closeModal = () => { modal.classList.add("hidden"); modal.classList.remove("flex"); document.body.style.overflow = ""; };

        openBtn?.addEventListener("click", openModal);
        openBtnMobile?.addEventListener("click", openModal);
        closeBtn?.addEventListener("click", closeModal);
        closeIcon?.addEventListener("click", closeModal);
        modal.addEventListener("click", (e) => { if (e.target === modal) closeModal(); });

        metodeBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                metodeBtns.forEach(b => {
                    b.classList.remove("bg-[#FF5252]", "text-white");
                    b.classList.add("bg-gray-100", "text-gray-700");
                });
                btn.classList.add("bg-[#FF5252]", "text-white");
                btn.classList.remove("bg-gray-100", "text-gray-700");
                metodeInput.value = btn.dataset.value;
            });
        });

        // Delete Alert
        document.querySelectorAll(".delete-btn").forEach(btn => {
            btn.addEventListener("click", () => {
                const form = btn.closest(".delete-form");
                const nama = btn.dataset.nama;
                Swal.fire({
                    title: "Yakin ingin hapus?",
                    html: `<p>Data <strong>${nama}</strong> akan dihapus permanen.</p>`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, Hapus",
                    cancelButtonText: "Batal"
                }).then(result => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Filter dropdown
        const hargaToggle = document.getElementById('hargaToggle');
        const hargaDropdown = document.getElementById('hargaDropdown');
        const filterToggle = document.getElementById('filterToggle');
        const filterDropdown = document.getElementById('filterDropdown');
        const tanggalSelect = document.getElementById('tanggalSelect');
        const customDateRange = document.getElementById('customDateRange');

        hargaToggle?.addEventListener('click', e => {
            e.stopPropagation();
            hargaDropdown.classList.toggle('hidden');
            filterDropdown.classList.add('hidden');
        });

        filterToggle?.addEventListener('click', e => {
            e.stopPropagation();
            filterDropdown.classList.toggle('hidden');
            hargaDropdown.classList.add('hidden');
        });

        tanggalSelect?.addEventListener('change', () => {
            customDateRange.classList.toggle('hidden', tanggalSelect.value !== 'custom');
        });

        document.addEventListener('click', e => {
            if (!hargaDropdown.contains(e.target) && !hargaToggle.contains(e.target))
                hargaDropdown.classList.add('hidden');
            if (!filterDropdown.contains(e.target) && !filterToggle.contains(e.target))
                filterDropdown.classList.add('hidden');
        });
    });
    </script>
</x-app-layout>
