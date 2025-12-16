<x-app-layout>
    <x-slot name="title">Manajemen User</x-slot>

    {{-- Load Font: Plus Jakarta Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{--
        MAIN CONTAINER FIX:
        Menghapus 'max-w-7xl mx-auto px-4...' agar tidak double margin dengan layout utama.
        Menggunakan 'flex flex-col space-y-8' untuk jarak vertikal yang rapi.
    --}}
    <div class="flex flex-col space-y-8">

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 animate-[fadeIn_0.5s_ease-out]">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-stone-900 tracking-tight leading-tight">
                    Manajemen <span class="text-indigo-600">User</span>
                </h1>
                <p class="text-stone-500 mt-2 font-medium text-sm md:text-base max-w-xl">
                    Kelola akses, role, dan penempatan outlet staff Anda dalam satu tampilan terpusat.
                </p>
            </div>
            {{-- ACTION BUTTON (Desktop & Mobile) --}}
            <x-action-button
                onclick="openCreateModal()"
                label="Tambah User"
                icon="add"
                id="floatingAddBtn"
            />
        </div>

        {{-- Alert Messages --}}
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
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

            {{-- A. DESKTOP TABLE VIEW --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-stone-50/80 text-stone-500 text-xs font-bold uppercase tracking-wider border-b border-stone-100">
                        <tr>
                            <th class="px-8 py-5 w-16 text-center">
                                <input type="checkbox" id="selectAll"
                                    class="w-5 h-5 text-indigo-600 bg-white border-stone-300 rounded-[6px] focus:ring-indigo-500 focus:ring-offset-0 transition-all cursor-pointer">
                            </th>
                            <th class="px-6 py-5">User Profile</th>
                            <th class="px-6 py-5">Role Access</th>
                            <th class="px-6 py-5">Outlet</th>
                            <th class="px-8 py-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @forelse($users as $user)
                            <tr class="hover:bg-indigo-50/30 transition-colors duration-300 group">
                                <td class="px-8 py-5 text-center">
                                    <input type="checkbox" name="selected_users[]" value="{{ $user->id }}"
                                        class="user-checkbox w-5 h-5 text-indigo-600 bg-white border-stone-300 rounded-[6px] focus:ring-indigo-500 focus:ring-offset-0 transition-all cursor-pointer">
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-indigo-100 to-purple-100 border border-white shadow-md flex items-center justify-center text-indigo-700 font-extrabold text-lg">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-stone-900 text-base group-hover:text-indigo-700 transition-colors">{{ $user->name }}</div>
                                            <div class="text-xs text-stone-400 font-medium tracking-wide">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-stone-900 text-white shadow-md shadow-stone-900/10">
                                            <span class="material-symbols-rounded text-[14px]">admin_panel_settings</span> Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-white border border-stone-200 text-stone-600 shadow-sm">
                                            <span class="material-symbols-rounded text-[14px] text-indigo-500">point_of_sale</span> Kasir
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    @if($user->outlet)
                                        <div class="flex items-center gap-2 text-stone-600 font-semibold text-sm">
                                            <div class="p-1.5 bg-orange-100 text-orange-600 rounded-lg">
                                                <span class="material-symbols-rounded text-[16px]">store</span>
                                            </div>
                                            {{ $user->outlet->name }}
                                        </div>
                                    @else
                                        <span class="text-stone-300 italic text-sm font-medium">-- Unassigned --</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                                        <button onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}', '{{ optional($user->outlet)->id }}')"
                                            class="h-9 w-9 rounded-xl bg-white border border-stone-200 text-stone-400 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 flex items-center justify-center transition-all shadow-sm" title="Edit">
                                            <span class="material-symbols-rounded text-[20px]">edit</span>
                                        </button>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="deleteUser(event)"
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
                                            <span class="material-symbols-rounded text-[48px] text-stone-400">group_off</span>
                                        </div>
                                        <p class="font-bold text-stone-500">Belum ada data user.</p>
                                        <p class="text-xs text-stone-400 mt-1">Silakan tambahkan user baru.</p>
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
                    <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Daftar User</span>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="selectAllMobile" class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500 border-stone-300">
                        <label for="selectAllMobile" class="text-xs font-bold text-stone-500">Pilih Semua</label>
                    </div>
                </div>

                @forelse($users as $user)
                <div class="bg-white p-5 rounded-3xl shadow-[0_2px_20px_rgba(0,0,0,0.04)] border border-stone-100 relative overflow-hidden group">
                    {{-- Role Badge Absolute --}}
                    <div class="absolute top-0 left-0 px-4 py-1.5 rounded-br-2xl text-[10px] font-bold uppercase tracking-wider
                        {{ $user->role === 'admin' ? 'bg-stone-900 text-white' : 'bg-indigo-50 text-indigo-600' }}">
                        {{ $user->role }}
                    </div>

                    {{-- Checkbox Absolute --}}
                    <div class="absolute top-5 right-5">
                         <input type="checkbox" name="selected_users[]" value="{{ $user->id }}"
                            class="user-checkbox w-5 h-5 text-indigo-600 bg-white border-stone-300 rounded-[6px] focus:ring-indigo-500">
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center text-indigo-600 font-extrabold text-xl shadow-inner">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-stone-900 truncate text-lg leading-tight">{{ $user->name }}</h3>
                            <p class="text-xs text-stone-400 truncate mt-0.5">{{ $user->email }}</p>

                            {{-- Outlet Info --}}
                            <div class="mt-2 flex items-center gap-1.5">
                                <span class="material-symbols-rounded text-[14px] text-orange-500">store</span>
                                <span class="text-xs font-semibold text-stone-600 truncate">
                                    {{ $user->outlet ? $user->outlet->name : 'No Outlet' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="grid grid-cols-2 gap-3 mt-5 pt-4 border-t border-stone-100 dashed">
                        <button onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}', '{{ optional($user->outlet)->id }}')"
                            class="py-2.5 rounded-xl bg-stone-50 text-stone-600 text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600 transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-rounded text-[16px]">edit</span> Edit
                        </button>
                         <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="w-full">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="deleteUser(event)"
                                class="w-full py-2.5 rounded-xl bg-stone-50 text-stone-600 text-xs font-bold hover:bg-rose-50 hover:text-rose-600 transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-rounded text-[16px]">delete</span> Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 px-4">
                    <p class="text-stone-400 italic">Belum ada data user.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>


    {{-- ================= FLOATING ACTION & BULK DELETE ================= --}}

    @if(Auth::user()->role === 'admin')
        {{-- BULK DELETE BAR (Floating Island Style) --}}
        <div id="bulkDeleteContainer"
            class="fixed bottom-24 md:bottom-8 left-0 right-0 z-40 px-4 flex justify-center transition-all duration-500 transform translate-y-40 opacity-0 invisible">

            <div class="bg-stone-900/90 backdrop-blur-md text-white p-2 pl-5 pr-2 rounded-full shadow-2xl flex items-center justify-between border border-stone-700 w-full max-w-sm ring-1 ring-white/10">
                <div class="flex items-center gap-3">
                    <div class="bg-indigo-600 rounded-full p-1 flex items-center justify-center animate-pulse">
                        <span class="material-symbols-rounded text-white text-[16px]">check</span>
                    </div>
                    <span class="text-sm font-medium tracking-wide">
                        <span id="selectedCount" class="font-bold text-indigo-400">0</span> terpilih
                    </span>
                </div>
                <button onclick="submitBulkDelete()" class="bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold px-5 py-3 rounded-full transition-all active:scale-95 shadow-lg shadow-rose-900/40 flex items-center gap-2 group">
                    <span>Hapus</span>
                    <span class="material-symbols-rounded text-[16px] group-hover:rotate-12 transition-transform">delete</span>
                </button>
            </div>

            {{-- Hidden Form --}}
            <form id="bulkDeleteForm" action="{{ route('users.bulk_destroy') }}" method="POST" class="hidden">
                @csrf @method('DELETE')
                <div id="bulkDeleteInputs"></div>
            </form>
        </div>

    @endif


    {{-- ================= MODAL CREATE (Modern Design) ================= --}}
    <div id="createModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-stone-900/40 backdrop-blur-sm transition-opacity duration-300" onclick="closeCreateModal()"></div>

        <div class="relative w-full max-w-lg bg-white rounded-[2rem] shadow-2xl transform scale-95 opacity-0 transition-all duration-300 max-h-[90vh] overflow-y-auto no-scrollbar" id="createModalContent">

            {{-- Modal Header --}}
            <div class="sticky top-0 bg-white/90 backdrop-blur-md px-8 py-6 border-b border-stone-100 flex justify-between items-center z-10">
                <div>
                    <h2 class="text-2xl font-extrabold text-stone-900 tracking-tight">Tambah User</h2>
                    <p class="text-xs text-stone-400 font-medium">Isi detail akun staff baru</p>
                </div>
                <button onclick="closeCreateModal()" class="h-10 w-10 rounded-full bg-stone-50 hover:bg-stone-100 text-stone-400 hover:text-rose-500 transition-colors flex items-center justify-center">
                    <span class="material-symbols-rounded font-bold">close</span>
                </button>
            </div>

            <div class="p-8">
                <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Input Group: Identity --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2 ml-1">Nama Pegawai</label>
                            <div class="relative group">
                                <span class="absolute left-4 top-3.5 text-stone-400 material-symbols-rounded text-[20px] group-focus-within:text-indigo-500 transition-colors">id_card</span>
                                <input type="text" name="name" required placeholder="Cth: Pegawai Rangga"
                                    class="w-full pl-12 pr-4 py-3.5 bg-stone-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-0 transition-all font-medium text-stone-800 placeholder-stone-400">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2 ml-1">Alamat Email</label>
                            <div class="relative group">
                                <span class="absolute left-4 top-3.5 text-stone-400 material-symbols-rounded text-[20px] group-focus-within:text-indigo-500 transition-colors">mail</span>
                                <input type="email" name="email" required placeholder="rangga@tsjumbo.com"
                                    class="w-full pl-12 pr-4 py-3.5 bg-stone-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-0 transition-all font-medium text-stone-800 placeholder-stone-400">
                            </div>
                        </div>
                    </div>

                    {{-- Input Group: Security --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2 ml-1">Password</label>
                            <div class="relative">
                                {{-- INPUT PASSWORD + TOGGLE --}}
                                <input type="password" name="password" id="create_password" required
                                    class="w-full pl-4 pr-12 py-3.5 bg-stone-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-0 transition-all font-medium">
                                <button type="button" onclick="togglePasswordVisibility('create_password', 'icon_create_password')"
                                    class="absolute right-4 top-3.5 text-stone-400 hover:text-stone-600 focus:outline-none transition-colors">
                                    <span id="icon_create_password" class="material-symbols-rounded text-[20px]">visibility_off</span>
                                </button>
                            </div>
                            <div id="createStrengthBar" class="h-1 w-full bg-stone-100 rounded-full mt-2 overflow-hidden">
                                <div id="createStrengthFill" class="h-full bg-rose-500 w-0 transition-all duration-500 ease-out"></div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2 ml-1">Konfirmasi</label>
                            <div class="relative">
                                {{-- INPUT PASSWORD CONFIRMATION + TOGGLE --}}
                                <input type="password" name="password_confirmation" id="create_password_confirmation" required
                                    class="w-full pl-4 pr-12 py-3.5 bg-stone-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-0 transition-all font-medium">
                                <button type="button" onclick="togglePasswordVisibility('create_password_confirmation', 'icon_create_password_confirmation')"
                                    class="absolute right-4 top-3.5 text-stone-400 hover:text-stone-600 focus:outline-none transition-colors">
                                    <span id="icon_create_password_confirmation" class="material-symbols-rounded text-[20px]">visibility_off</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Input Group: Access --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                         <div>
                            <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2 ml-1">Role Access</label>
                            <div class="relative">
                                <select name="role" required class="w-full px-4 py-3.5 bg-stone-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-0 transition-all font-medium text-stone-800 appearance-none cursor-pointer">
                                    <option value="kasir">Kasir</option>
                                    <option value="admin">Admin</option>
                                </select>
                                <span class="material-symbols-rounded absolute right-4 top-3.5 text-stone-400 pointer-events-none">expand_more</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2 ml-1">Outlet</label>
                            <div class="relative">
                                <select name="outlet_id" class="w-full px-4 py-3.5 bg-stone-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-0 transition-all font-medium text-stone-800 appearance-none cursor-pointer">
                                    <option value="">-- Tidak Ada --</option>
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-rounded absolute right-4 top-3.5 text-stone-400 pointer-events-none">expand_more</span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-stone-100">
                        <button type="button" onclick="closeCreateModal()"
                            class="px-6 py-3 rounded-xl font-bold text-stone-500 hover:bg-stone-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-8 py-3 bg-stone-900 hover:bg-black text-white rounded-xl font-bold shadow-lg shadow-stone-900/20 active:scale-95 transition-all">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ================= MODAL EDIT (FIXED CSS CONFLICT) ================= --}}
    <div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-stone-900/40 backdrop-blur-sm transition-opacity duration-300" onclick="closeEditModal()"></div>

        <div class="relative w-full max-w-lg bg-white rounded-[2rem] shadow-2xl transform scale-95 opacity-0 transition-all duration-300 max-h-[90vh] overflow-y-auto no-scrollbar" id="editModalContent">

            <div class="sticky top-0 bg-white/90 backdrop-blur-md px-8 py-6 border-b border-stone-100 flex justify-between items-center z-10">
                <div>
                    <h2 class="text-2xl font-extrabold text-stone-900 tracking-tight">Edit Data User</h2>
                    <p class="text-xs text-stone-400 font-medium">Perbarui informasi akun</p>
                </div>
                <button onclick="closeEditModal()" class="h-10 w-10 rounded-full bg-stone-50 hover:bg-stone-100 text-stone-400 hover:text-indigo-500 transition-colors flex items-center justify-center">
                    <span class="material-symbols-rounded font-bold">close</span>
                </button>
            </div>

            <div class="p-8">
                <form id="editForm" method="POST" class="space-y-6">
                    @csrf @method('PUT')
                    <input type="hidden" name="id" id="editId">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2 ml-1">Nama Lengkap</label>
                            <input type="text" name="name" id="editName" required
                                class="w-full px-4 py-3.5 bg-stone-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-0 transition-all font-medium text-stone-800">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2 ml-1">Alamat Email</label>
                            <input type="email" name="email" id="editEmail" required
                                class="w-full px-4 py-3.5 bg-stone-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-0 transition-all font-medium text-stone-800">
                        </div>
                    </div>

                    {{-- Password Change Section --}}
                    <div class="bg-indigo-50/50 p-5 rounded-2xl border-2 border-indigo-100/50 border-dashed">
                        <p class="text-xs font-bold text-indigo-800 mb-3 flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm bg-indigo-100 p-1 rounded-md">lock</span>
                            Ubah Password (Opsional)
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="relative">
                                    {{-- EDIT PASSWORD + TOGGLE --}}
                                    <input type="password" name="password" id="edit_password" placeholder="Password Baru"
                                        class="w-full pl-3 pr-10 py-2.5 bg-white border border-stone-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-0 transition-all">
                                    <button type="button" onclick="togglePasswordVisibility('edit_password', 'icon_edit_password')"
                                        class="absolute right-3 top-2.5 text-stone-400 hover:text-stone-600 focus:outline-none transition-colors">
                                        <span id="icon_edit_password" class="material-symbols-rounded text-[18px]">visibility_off</span>
                                    </button>
                                </div>
                                <div id="editStrengthBar" class="h-1 w-full bg-stone-200 rounded-full mt-2 overflow-hidden">
                                    <div id="editStrengthFill" class="h-full bg-rose-500 w-0 transition-all duration-500 ease-out"></div>
                                </div>
                            </div>
                            <div>
                                <div class="relative">
                                    {{-- EDIT CONFIRMATION + TOGGLE --}}
                                    <input type="password" name="password_confirmation" id="edit_password_confirmation" placeholder="Konfirmasi"
                                        class="w-full pl-3 pr-10 py-2.5 bg-white border border-stone-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-0 transition-all">
                                    <button type="button" onclick="togglePasswordVisibility('edit_password_confirmation', 'icon_edit_password_confirmation')"
                                        class="absolute right-3 top-2.5 text-stone-400 hover:text-stone-600 focus:outline-none transition-colors">
                                        <span id="icon_edit_password_confirmation" class="material-symbols-rounded text-[18px]">visibility_off</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2 ml-1">Role</label>
                            <div class="relative">
                                <select name="role" id="editRole" required class="w-full px-4 py-3.5 bg-stone-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-0 transition-all font-medium text-stone-800 appearance-none cursor-pointer">
                                    <option value="admin">Admin</option>
                                    <option value="kasir">Kasir</option>
                                </select>
                                <span class="material-symbols-rounded absolute right-4 top-3.5 text-stone-400 pointer-events-none">expand_more</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2 ml-1">Outlet</label>
                            <div class="relative">
                                <select name="outlet_id" id="editOutlet" class="w-full px-4 py-3.5 bg-stone-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-0 transition-all font-medium text-stone-800 appearance-none cursor-pointer">
                                    <option value="">-- Tanpa Outlet --</option>
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-rounded absolute right-4 top-3.5 text-stone-400 pointer-events-none">expand_more</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-stone-100">
                        <button type="button" onclick="closeEditModal()"
                            class="px-6 py-3 rounded-xl font-bold text-stone-500 hover:bg-stone-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/30 active:scale-95 transition-all">
                            Simpan Perubahan
                        </button>
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
        function openEditModal(id, name, email, role, outlet_id) {
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = role;
            document.getElementById('editOutlet').value = outlet_id === 'null' ? '' : outlet_id;
            document.getElementById('editForm').action = '/users/' + id;
            toggleModal('editModal', 'editModalContent', true);
        }
        function closeEditModal() { toggleModal('editModal', 'editModalContent', false); }

        // --- PASSWORD METER ---
        function checkPasswordStrength(password, fillElement) {
            if (password.length === 0) { fillElement.style.width = "0"; return; }
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            let color = "#ef4444"; // red
            let width = "20%";
            switch (strength) {
                case 1: width = "20%"; color = "#ef4444"; break;
                case 2: width = "40%"; color = "#f97316"; break;
                case 3: width = "60%"; color = "#eab308"; break;
                case 4: width = "80%"; color = "#84cc16"; break;
                case 5: width = "100%"; color = "#22c55e"; break;
            }
            fillElement.style.width = width;
            fillElement.style.backgroundColor = color;
        }

        const createPass = document.getElementById("create_password");
        const createFill = document.getElementById("createStrengthFill");
        if(createPass && createFill) {
            ['input'].forEach(evt => createPass.addEventListener(evt, () => checkPasswordStrength(createPass.value, createFill)));
        }
        const editPass = document.getElementById("edit_password");
        const editFill = document.getElementById("editStrengthFill");
        if(editPass && editFill) {
            ['input'].forEach(evt => editPass.addEventListener(evt, () => checkPasswordStrength(editPass.value, editFill)));
        }

        // --- TOGGLE PASSWORD VISIBILITY (FUNGSI BARU) ---
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerText = 'visibility'; // Ikon mata terbuka
                icon.parentElement.classList.add('text-indigo-600');
            } else {
                input.type = 'password';
                icon.innerText = 'visibility_off'; // Ikon mata tertutup
                icon.parentElement.classList.remove('text-indigo-600');
            }
        }

        // --- BULK DELETE LOGIC ---
        const selectAll = document.getElementById('selectAll');
        const selectAllMobile = document.getElementById('selectAllMobile');
        const bulkContainer = document.getElementById('bulkDeleteContainer');
        const selectedCountSpan = document.getElementById('selectedCount');
        const fab = document.getElementById('floatingAddBtn');

        function getSelectedUniqueIds() {
            const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
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

            const allCheckboxes = document.querySelectorAll('.user-checkbox');
            const totalUniqueUsers = allCheckboxes.length > 0 ? allCheckboxes.length / 2 : 0;
            const allChecked = uniqueIds.length === totalUniqueUsers && totalUniqueUsers > 0;

            if(selectAll) selectAll.checked = allChecked;
            if(selectAllMobile) selectAllMobile.checked = allChecked;
        }

        function toggleAll(isChecked) {
            document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = isChecked);
            updateBulkState();
        }

        if(selectAll) selectAll.addEventListener('change', (e) => toggleAll(e.target.checked));
        if(selectAllMobile) selectAllMobile.addEventListener('change', (e) => toggleAll(e.target.checked));

        document.addEventListener('change', function(e) {
            if(e.target.classList.contains('user-checkbox')) {
                const userId = e.target.value;
                const isChecked = e.target.checked;
                document.querySelectorAll(`.user-checkbox[value="${userId}"]`).forEach(cb => {
                    cb.checked = isChecked;
                });
                updateBulkState();
            }
        });

        function submitBulkDelete() {
            const uniqueIds = getSelectedUniqueIds();
            if(uniqueIds.length === 0) return;

            Swal.fire({
                title: 'Hapus ' + uniqueIds.length + ' User?',
                text: "Aksi ini tidak dapat dibatalkan.",
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
        function deleteUser(event) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: 'Hapus User Ini?',
                text: "Data tidak dapat dikembalikan!",
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

        // Form Validation Listener
        const createForm = document.querySelector('form[action="{{ route('users.store') }}"]');
        if(createForm) {
            createForm.addEventListener('submit', function(e) {
                if(document.getElementById('create_password').value !== document.getElementById('create_password_confirmation').value) {
                    e.preventDefault();
                    Swal.fire({icon: 'error', title: 'Oops', text: 'Password tidak cocok!', customClass: {popup: 'rounded-2xl'}});
                }
            });
        }
        const editFormEl = document.getElementById('editForm');
        if(editFormEl) {
            editFormEl.addEventListener('submit', function(e) {
                const p = document.getElementById('edit_password').value;
                const c = document.getElementById('edit_password_confirmation').value;
                if(p && p !== c) {
                    e.preventDefault();
                    Swal.fire({icon: 'error', title: 'Oops', text: 'Password tidak cocok!', customClass: {popup: 'rounded-2xl'}});
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
