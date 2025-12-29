<x-app-layout>
    <x-slot name="title">Manajemen Outlet</x-slot>

    {{-- Load Font: Plus Jakarta Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- MAIN CONTAINER --}}
    <div class="flex flex-col space-y-8">

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 animate-[fadeIn_0.5s_ease-out]">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-stone-900 tracking-tight leading-tight">
                    Manajemen <span class="text-brand-600">Outlet</span>
                </h1>
                <p class="text-stone-500 mt-2 font-medium text-sm md:text-base max-w-xl">
                    Kelola data cabang, lokasi, dan kontak outlet Anda dalam satu tampilan terpusat.
                </p>
            </div>

            {{-- ACTION BUTTON COMPONENT --}}
            <x-action-button
                onclick="openCreateModal()"
                label="Tambah Outlet"
                icon="add"
                id="floatingAddBtn"
            />
        </div>

        {{-- Alert Messages (Flash Session) --}}
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-100 flex items-center gap-4 text-emerald-800 shadow-sm backdrop-blur-sm animate-[slideIn_0.4s_ease-out]">
                <div class="bg-emerald-100 p-2 rounded-xl text-emerald-600">
                    <span class="material-symbols-rounded">check_circle</span>
                </div>
                <div>
                    <p class="font-bold text-sm">Berhasil!</p>
                    <p class="text-sm opacity-90">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50/80 border border-rose-100 flex items-center gap-4 text-rose-800 shadow-sm backdrop-blur-sm animate-[slideIn_0.4s_ease-out]">
                <div class="bg-rose-100 p-2 rounded-xl text-rose-600">
                    <span class="material-symbols-rounded">error</span>
                </div>
                <div>
                    <p class="font-bold text-sm">Terjadi Kesalahan:</p>
                    <ul class="list-disc ml-4 text-sm mt-1 opacity-90">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- CONTENT AREA --}}
        <div class="bg-white border border-stone-100 rounded-[2.5rem] shadow-xl shadow-stone-200/50 overflow-hidden relative">

            {{-- Decorative Top Gradient --}}
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-brand-500 via-orange-500 to-yellow-500"></div>

            {{-- A. DESKTOP TABLE VIEW --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-stone-50/80 text-stone-500 text-xs font-bold uppercase tracking-wider border-b border-stone-100">
                        <tr>
                            <th class="px-8 py-5 w-16 text-center">
                                <input type="checkbox" id="selectAll"
                                    class="w-5 h-5 text-brand-600 bg-white border-stone-300 rounded-[6px] focus:ring-brand-500 focus:ring-offset-0 transition-all cursor-pointer">
                            </th>
                            <th class="px-6 py-5">Identitas Outlet</th>
                            <th class="px-6 py-5">Lokasi & Alamat</th>
                            <th class="px-6 py-5">Kontak</th>
                            <th class="px-8 py-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @forelse($outlets as $outlet)
                            <tr class="hover:bg-brand-50/30 transition-colors duration-300 group">
                                <td class="px-8 py-5 text-center">
                                    <input type="checkbox" name="selected_outlets[]" value="{{ $outlet->id }}"
                                        class="item-checkbox w-5 h-5 text-brand-600 bg-white border-stone-300 rounded-[6px] focus:ring-brand-500 focus:ring-offset-0 transition-all cursor-pointer">
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-brand-100 to-orange-100 border border-white shadow-md flex items-center justify-center text-brand-700 font-extrabold text-lg">
                                            {{ substr($outlet->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-stone-900 text-base group-hover:text-brand-700 transition-colors">{{ $outlet->name }}</div>
                                            <div class="text-[10px] font-bold text-stone-400 uppercase tracking-wider mt-0.5">
                                                ID: #{{ str_pad($outlet->id, 3, '0', STR_PAD_LEFT) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 max-w-xs">
                                    <div class="flex items-start gap-2">
                                        <span class="material-symbols-rounded text-[18px] text-stone-300 mt-0.5 group-hover:text-brand-400 transition-colors">location_on</span>
                                        <p class="text-sm text-stone-600 font-medium leading-relaxed line-clamp-2" title="{{ $outlet->address }}">
                                            {{ $outlet->address }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    @if($outlet->phone)
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-white border border-stone-200 text-stone-600 shadow-sm group-hover:border-brand-200 transition-colors">
                                            <span class="material-symbols-rounded text-[14px] text-brand-500">call</span>
                                            {{ $outlet->phone }}
                                        </span>
                                    @else
                                        <span class="text-stone-300 italic text-sm font-medium">-- Kosong --</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                                        <button onclick="openEditModal({{ $outlet->id }}, '{{ $outlet->name }}', '{{ $outlet->phone }}', `{{ $outlet->address }}`)"
                                            class="h-9 w-9 rounded-xl bg-white border border-stone-200 text-stone-400 hover:text-brand-600 hover:border-brand-200 hover:bg-brand-50 flex items-center justify-center transition-all shadow-sm" title="Edit">
                                            <span class="material-symbols-rounded text-[20px]">edit</span>
                                        </button>
                                        <form action="{{ route('outlets.destroy', $outlet->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="deleteItem(event)"
                                                class="h-9 w-9 rounded-xl bg-white border border-stone-200 text-stone-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 flex items-center justify-center transition-all shadow-sm" title="Hapus">
                                                <span class="material-symbols-rounded text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-50">
                                        <div class="bg-stone-100 p-4 rounded-full mb-3">
                                            <span class="material-symbols-rounded text-[48px] text-stone-400">store_off</span>
                                        </div>
                                        <p class="font-bold text-stone-500">Belum ada data outlet.</p>
                                        <p class="text-xs text-stone-400 mt-1">Silakan tambahkan outlet baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- B. MOBILE CARD VIEW --}}
            <div class="md:hidden bg-stone-50/50 p-4 space-y-4">
                {{-- Select All Mobile Helper --}}
                <div class="flex items-center justify-between px-2 mb-2">
                    <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Daftar Outlet</span>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="selectAllMobile" class="w-4 h-4 text-brand-600 rounded focus:ring-brand-500 border-stone-300">
                        <label for="selectAllMobile" class="text-xs font-bold text-stone-500">Pilih Semua</label>
                    </div>
                </div>

                @forelse($outlets as $outlet)
                <div class="bg-white p-5 rounded-3xl shadow-[0_2px_20px_rgba(0,0,0,0.04)] border border-stone-100 relative overflow-hidden group">
                    {{-- ID Badge --}}
                    <div class="absolute top-0 left-0 px-4 py-1.5 rounded-br-2xl text-[10px] font-bold uppercase tracking-wider bg-brand-50 text-brand-600 border-r border-b border-brand-100">
                        #{{ str_pad($outlet->id, 3, '0', STR_PAD_LEFT) }}
                    </div>

                    {{-- Checkbox --}}
                    <div class="absolute top-5 right-5">
                         <input type="checkbox" name="selected_outlets[]" value="{{ $outlet->id }}"
                            class="item-checkbox w-5 h-5 text-brand-600 bg-white border-stone-300 rounded-[6px] focus:ring-brand-500">
                    </div>

                    <div class="mt-8 flex items-center gap-4">
                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-brand-50 to-orange-50 flex items-center justify-center text-brand-600 font-extrabold text-xl shadow-inner">
                            {{ substr($outlet->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-stone-900 truncate text-lg leading-tight">{{ $outlet->name }}</h3>
                            @if($outlet->phone)
                            <div class="mt-1 flex items-center gap-1.5">
                                <span class="material-symbols-rounded text-[14px] text-brand-500">call</span>
                                <span class="text-xs font-semibold text-stone-600 font-mono">{{ $outlet->phone }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-stone-50 rounded-xl border border-stone-100 flex gap-2">
                        <span class="material-symbols-rounded text-[16px] text-stone-400 mt-0.5 shrink-0">location_on</span>
                        <p class="text-xs text-stone-600 leading-relaxed font-medium">{{ $outlet->address }}</p>
                    </div>

                    {{-- Actions --}}
                    <div class="grid grid-cols-2 gap-3 mt-5 pt-4 border-t border-stone-100 dashed">
                        <button onclick="openEditModal({{ $outlet->id }}, '{{ $outlet->name }}', '{{ $outlet->phone }}', `{{ $outlet->address }}`)"
                            class="py-2.5 rounded-xl bg-stone-50 text-stone-600 text-xs font-bold hover:bg-brand-50 hover:text-brand-600 transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-rounded text-[16px]">edit</span> Edit
                        </button>
                        <form action="{{ route('outlets.destroy', $outlet->id) }}" method="POST" class="w-full">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="deleteItem(event)"
                                class="w-full py-2.5 rounded-xl bg-stone-50 text-stone-600 text-xs font-bold hover:bg-rose-50 hover:text-rose-600 transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-rounded text-[16px]">delete</span> Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 px-4">
                    <p class="text-stone-400 italic">Belum ada data outlet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ================= FLOATING BULK DELETE BAR ================= --}}
    @if(Auth::user()->role === 'admin')
        <div id="bulkDeleteContainer"
            class="fixed bottom-24 md:bottom-8 left-0 right-0 z-40 px-4 flex justify-center transition-all duration-500 transform translate-y-40 opacity-0 invisible">
            <div class="bg-stone-900/90 backdrop-blur-md text-white p-2 pl-5 pr-2 rounded-full shadow-2xl flex items-center justify-between border border-stone-700 w-full max-w-sm ring-1 ring-white/10">
                <div class="flex items-center gap-3">
                    <div class="bg-brand-600 rounded-full p-1 flex items-center justify-center animate-pulse">
                        <span class="material-symbols-rounded text-white text-[16px]">check</span>
                    </div>
                    <span class="text-sm font-medium tracking-wide">
                        <span id="selectedCount" class="font-bold text-brand-400">0</span> terpilih
                    </span>
                </div>
                <button onclick="submitBulkDelete()" class="bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold px-5 py-3 rounded-full transition-all active:scale-95 shadow-lg shadow-rose-900/40 flex items-center gap-2 group">
                    <span>Hapus</span>
                    <span class="material-symbols-rounded text-[16px] group-hover:rotate-12 transition-transform">delete</span>
                </button>
            </div>
            <form id="bulkDeleteForm" action="{{ route('outlets.bulk_destroy') }}" method="POST" class="hidden">
                @csrf @method('DELETE')
                <div id="bulkDeleteInputs"></div>
            </form>
        </div>
    @endif

    {{-- ================= MODAL CREATE ================= --}}
    <div id="createModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-stone-900/40 backdrop-blur-sm transition-opacity duration-300" onclick="closeCreateModal()"></div>
        <div class="relative w-full max-w-lg bg-white rounded-[2rem] shadow-2xl transform scale-95 opacity-0 transition-all duration-300 max-h-[90vh] overflow-y-auto no-scrollbar" id="createModalContent">
            <div class="sticky top-0 bg-white/90 backdrop-blur-md px-8 py-6 border-b border-stone-100 flex justify-between items-center z-10">
                <div>
                    <h2 class="text-2xl font-extrabold text-stone-900 tracking-tight">Tambah Outlet</h2>
                    <p class="text-xs text-stone-400 font-medium">Isi detail informasi cabang baru</p>
                </div>
                <button onclick="closeCreateModal()" class="h-10 w-10 rounded-full bg-stone-50 hover:bg-stone-100 text-stone-400 hover:text-rose-500 transition-colors flex items-center justify-center">
                    <span class="material-symbols-rounded font-bold">close</span>
                </button>
            </div>
            <div class="p-8">
                <form action="{{ route('outlets.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2 ml-1">Nama Outlet</label>
                        <div class="relative group">
                            <span class="absolute left-4 top-3.5 text-stone-400 material-symbols-rounded text-[20px] group-focus-within:text-brand-500 transition-colors">store</span>
                            <input type="text" name="name" required placeholder="Cth: Cabang Pasar Gede"
                                class="w-full pl-12 pr-4 py-3.5 bg-stone-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-brand-500 focus:ring-0 transition-all font-medium text-stone-800 placeholder-stone-400">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2 ml-1">Kontak Telepon</label>
                        <div class="relative group">
                            <span class="absolute left-4 top-3.5 text-stone-400 material-symbols-rounded text-[20px] group-focus-within:text-brand-500 transition-colors">call</span>
                            <input type="text" name="phone" placeholder="08..."
                                class="w-full pl-12 pr-4 py-3.5 bg-stone-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-brand-500 focus:ring-0 transition-all font-medium font-mono text-stone-800 placeholder-stone-400">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2 ml-1">Lokasi Lengkap</label>
                        <div class="relative group">
                            <span class="absolute left-4 top-3.5 text-stone-400 material-symbols-rounded text-[20px] group-focus-within:text-brand-500 transition-colors">map</span>
                            <textarea name="address" rows="3" required placeholder="Jl. Brawijaya No. 45..."
                                class="w-full pl-12 pr-4 py-3.5 bg-stone-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-brand-500 focus:ring-0 transition-all font-medium text-stone-800 placeholder-stone-400 resize-none"></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-stone-100">
                        <button type="button" onclick="closeCreateModal()" class="px-6 py-3 rounded-xl font-bold text-stone-500 hover:bg-stone-50 transition-colors">Batal</button>
                        <button type="submit" class="px-8 py-3 bg-stone-900 hover:bg-black text-white rounded-xl font-bold shadow-lg shadow-stone-900/20 active:scale-95 transition-all">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ================= MODAL EDIT ================= --}}
    <div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-stone-900/40 backdrop-blur-sm transition-opacity duration-300" onclick="closeEditModal()"></div>
        <div class="relative w-full max-w-lg bg-white rounded-[2rem] shadow-2xl transform scale-95 opacity-0 transition-all duration-300 max-h-[90vh] overflow-y-auto no-scrollbar" id="editModalContent">
            <div class="sticky top-0 bg-white/90 backdrop-blur-md px-8 py-6 border-b border-stone-100 flex justify-between items-center z-10">
                <div>
                    <h2 class="text-2xl font-extrabold text-stone-900 tracking-tight">Edit Outlet</h2>
                    <p class="text-xs text-stone-400 font-medium">Perbarui informasi cabang</p>
                </div>
                <button onclick="closeEditModal()" class="h-10 w-10 rounded-full bg-stone-50 hover:bg-stone-100 text-stone-400 hover:text-brand-500 transition-colors flex items-center justify-center">
                    <span class="material-symbols-rounded font-bold">close</span>
                </button>
            </div>
            <div class="p-8">
                <form id="editForm" method="POST" class="space-y-6">
                    @csrf @method('PUT')
                    <input type="hidden" name="id" id="editId">
                    <div>
                        <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2 ml-1">Nama Outlet</label>
                        <div class="relative group">
                            <span class="absolute left-4 top-3.5 text-stone-400 material-symbols-rounded text-[20px] group-focus-within:text-brand-500 transition-colors">store</span>
                            <input type="text" name="name" id="editName" required
                                class="w-full pl-12 pr-4 py-3.5 bg-stone-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-brand-500 focus:ring-0 transition-all font-medium text-stone-800">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2 ml-1">Kontak Telepon</label>
                        <div class="relative group">
                            <span class="absolute left-4 top-3.5 text-stone-400 material-symbols-rounded text-[20px] group-focus-within:text-brand-500 transition-colors">call</span>
                            <input type="text" name="phone" id="editPhone"
                                class="w-full pl-12 pr-4 py-3.5 bg-stone-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-brand-500 focus:ring-0 transition-all font-medium font-mono text-stone-800">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2 ml-1">Lokasi Lengkap</label>
                        <div class="relative group">
                            <span class="absolute left-4 top-3.5 text-stone-400 material-symbols-rounded text-[20px] group-focus-within:text-brand-500 transition-colors">map</span>
                            <textarea name="address" id="editAddress" rows="3" required
                                class="w-full pl-12 pr-4 py-3.5 bg-stone-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-brand-500 focus:ring-0 transition-all font-medium text-stone-800 resize-none"></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-stone-100">
                        <button type="button" onclick="closeEditModal()" class="px-6 py-3 rounded-xl font-bold text-stone-500 hover:bg-stone-50 transition-colors">Batal</button>
                        <button type="submit" class="px-8 py-3 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-bold shadow-lg shadow-brand-500/30 active:scale-95 transition-all">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT LOGIC --}}
    <script>
        // --- MODAL UTILITIES ---
        function toggleModal(modalID, contentID, show) {
            const modal = document.getElementById(modalID);
            const content = document.getElementById(contentID);

            if (show) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                content.scrollTop = 0;
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 300);
            }
        }

        // CREATE
        function openCreateModal() { toggleModal('createModal', 'createModalContent', true); }
        function closeCreateModal() { toggleModal('createModal', 'createModalContent', false); }

        // EDIT
        function openEditModal(id, name, phone, address) {
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editPhone').value = phone;
            document.getElementById('editAddress').value = address;
            // Set action URL secara dinamis untuk update
            document.getElementById('editForm').action = '/outlets/' + id;
            toggleModal('editModal', 'editModalContent', true);
        }
        function closeEditModal() { toggleModal('editModal', 'editModalContent', false); }

        // --- BULK DELETE LOGIC ---
        const selectAll = document.getElementById('selectAll');
        const selectAllMobile = document.getElementById('selectAllMobile');
        const bulkContainer = document.getElementById('bulkDeleteContainer');
        const selectedCountSpan = document.getElementById('selectedCount');
        const fab = document.getElementById('floatingAddBtn');

        function getSelectedUniqueIds() {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            const uniqueIds = new Set();
            checkedBoxes.forEach(box => uniqueIds.add(box.value));
            return Array.from(uniqueIds);
        }

        function updateBulkState() {
            const uniqueIds = getSelectedUniqueIds();
            if(selectedCountSpan) selectedCountSpan.innerText = uniqueIds.length;

            if(uniqueIds.length > 0) {
                if(bulkContainer) bulkContainer.classList.remove('translate-y-40', 'opacity-0', 'invisible');
                if(fab) fab.classList.add('translate-y-40', 'opacity-0', 'invisible');
            } else {
                if(bulkContainer) bulkContainer.classList.add('translate-y-40', 'opacity-0', 'invisible');
                if(fab) fab.classList.remove('translate-y-40', 'opacity-0', 'invisible');
            }

            const totalItems = document.querySelectorAll('.item-checkbox').length;
            // Jika ada duplicate checkbox (desktop vs mobile), hitung unique itemnya
            const uniqueCheckboxes = new Set();
            document.querySelectorAll('.item-checkbox').forEach(cb => uniqueCheckboxes.add(cb.value));

            const allChecked = uniqueIds.length === uniqueCheckboxes.size && uniqueCheckboxes.size > 0;

            if(selectAll) selectAll.checked = allChecked;
            if(selectAllMobile) selectAllMobile.checked = allChecked;
        }

        function toggleAll(isChecked) {
            document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = isChecked);
            updateBulkState();
        }

        if(selectAll) selectAll.addEventListener('change', (e) => toggleAll(e.target.checked));
        if(selectAllMobile) selectAllMobile.addEventListener('change', (e) => toggleAll(e.target.checked));

        document.addEventListener('change', function(e) {
            if(e.target.classList.contains('item-checkbox')) {
                const itemId = e.target.value;
                const isChecked = e.target.checked;
                // Sync desktop/mobile checkbox
                document.querySelectorAll(`.item-checkbox[value="${itemId}"]`).forEach(cb => {
                    cb.checked = isChecked;
                });
                updateBulkState();
            }
        });

        function submitBulkDelete() {
            const uniqueIds = getSelectedUniqueIds();
            if(uniqueIds.length === 0) return;

            Swal.fire({
                title: 'Hapus ' + uniqueIds.length + ' Outlet?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#78716c',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    const container = document.getElementById('bulkDeleteInputs');
                    container.innerHTML = '';
                    uniqueIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = id;
                        container.appendChild(input);
                    });
                    document.getElementById('bulkDeleteForm').submit();
                }
            });
        }

        // --- DELETE CONFIRMATION (SINGLE) ---
        function deleteItem(event) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: 'Hapus Outlet Ini?',
                text: "Data kasir terkait mungkin akan terdampak!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#78716c',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        // --- AUTO HIDE NOTIFICATION ---
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.animate-\\[slideIn_0\\.4s_ease-out\\]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.remove('animate-[slideIn_0.4s_ease-out]');
                    alert.classList.add('transition-all', 'duration-500', 'opacity-0', '-translate-y-4');
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 3000);
            });
        });
    </script>
</x-app-layout>
