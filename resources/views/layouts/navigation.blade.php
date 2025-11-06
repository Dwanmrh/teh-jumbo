<nav x-data="{ sidebarOpen: false }" class="font-[Outfit] relative">

    <!-- ===== Navbar Atas (Sticky) ===== -->
    <div class="bg-[#EABF59] px-4 py-3 flex justify-between items-center shadow-md fixed top-0 left-0 w-full z-50">
        <!-- Kiri: Logo + Nama + Tombol Burger -->
        <div class="flex items-center space-x-3">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                <img src="{{ asset('assets/images/logo_teh.png') }}" alt="Logo" class="h-8 w-8">
                <span class="text-lg font-semibold text-[#1E1E1E]">Teh Solo</span>
            </a>

            <!-- Tombol Burger -->
            <button
                @click="sidebarOpen = !sidebarOpen; $dispatch('sidebar-toggle', sidebarOpen)"
                class="ml-3 text-[#1E1E1E] focus:outline-none hover:bg-[#d4aa4e] p-2 rounded-md transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Kanan: Dropdown User -->
        <div>
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button
                        class="inline-flex items-center px-3 py-2 border-0 text-sm font-medium rounded-md text-[#1E1E1E] bg-transparent hover:bg-[#d4aa4e] transition ease-in-out duration-150">
                        <div>{{ Auth::user()->name }}</div>
                        <svg class="ml-1 h-4 w-4 text-[#1E1E1E]" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')" class="hover:bg-[#F1C661]/40">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-red-600 hover:bg-red-50">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>

    <!-- Spacer agar konten tidak tertutup navbar -->
    <div class="h-[64px]"></div>

    <!-- ===== Sidebar ===== -->
    <div x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="-translate-x-full opacity-0"
        @click.away="sidebarOpen = false; $dispatch('sidebar-toggle', false)"
        class="fixed left-0 top-[64px] w-64 bg-[#2F3E2F] text-white shadow-lg z-40 h-[calc(100vh-64px)] overflow-y-auto">

        <div class="mt-3 space-y-1 px-3 pb-4">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-[#9BCC50] transition {{ request()->routeIs('dashboard') ? 'bg-[#9BCC50] text-black' : '' }}">
                <i class="fa-solid fa-house"></i> Dashboard
            </a>

            <a href="{{ route('kas-masuk.index') }}"
                class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-[#9BCC50] transition {{ request()->routeIs('kas-masuk.index') ? 'bg-[#9BCC50] text-black' : '' }}">
                <i class="fa-solid fa-arrow-down"></i> Kas Masuk
            </a>

            <a href="{{ route('kas-keluar.index') }}"
                class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-[#9BCC50] transition {{ request()->routeIs('kas.keluar') ? 'bg-[#9BCC50] text-black' : '' }}">
                <i class="fa-solid fa-arrow-up"></i> Kas Keluar
            </a>

            {{-- <a href="{{ route('barang.index') }}"
                class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-[#9BCC50] transition {{ request()->routeIs('barang.index') ? 'bg-[#9BCC50] text-black' : '' }}">
                <i class="fa-solid fa-box"></i> Persediaan Barang
            </a> --}}

            <a href="{{ route('laporan.keuangan') }}"
                class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-[#9BCC50] transition {{ request()->routeIs('laporan.keuangan') ? 'bg-[#9BCC50] text-black' : '' }}">
                <i class="fa-solid fa-file-invoice"></i> Laporan Keuangan
            </a>
        </div>
    </div>
</nav>
