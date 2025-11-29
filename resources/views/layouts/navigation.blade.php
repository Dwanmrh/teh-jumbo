<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

    <!-- ===== NAVBAR ATAS (DESKTOP) ===== -->
    <div class="bg-white border-b px-4 py-3 flex justify-between items-center fixed top-0 left-0 w-full z-50">
        <div class="flex items-center space-x-3">

            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                <img src="{{ asset('assets/images/logo_teh.png') }}" class="h-8 w-8">
                <span class="text-lg font-semibold text-[#1E1E1E]">Teh Solo de Jumbo Fibonacci</span>
            </a>
        </div>

        <!-- User -->
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="px-3 py-2 rounded-md hover:bg-white flex items-center gap-2">
                    <span>{{ Auth::user()->name }}</span>
                    <span class="material-symbols-outlined text-sm">expand_more</span>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="text-red-600">Log Out</x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>

    <!-- Spacer -->
    <div class="h-[64px]"></div>


    <!-- ===== NAVIGATION FOOTER (MOBILE) ===== -->
    <div class="sm:hidden fixed bottom-0 left-0 w-full z-50">
        <div class="w-full bg-white border-t px-3 py-2 flex justify-between items-center">
            
            <a href="{{ route('dashboard') }}"
               class="flex flex-col items-center gap-1 flex-1 py-1
               {{ request()->routeIs('dashboard') ? 'text-black font-semibold' : 'text-gray-600' }}">
                <span class="material-symbols-outlined text-xl">grid_view</span>
                <span class="text-[11px]">Dashboard</span>
            </a>

            <a href="{{ route('pos.index') }}"
               class="flex flex-col items-center gap-1 flex-1 py-1
               {{ request()->routeIs('pos.index') ? 'text-black font-semibold' : 'text-gray-600' }}">
                <span class="material-symbols-outlined">shopping_cart</span>
                <span class="text-[11px]">POS</span>
            </a>

            <a href="{{ route('products.index') }}"
               class="flex flex-col items-center gap-1 flex-1 py-1
               {{ request()->routeIs('products.index') ? 'text-black font-semibold' : 'text-gray-600' }}">
                <span class="material-symbols-outlined text-xl">inventory_2</span>
                <span class="text-[11px]">Produk</span>
            </a>

            <a href="{{ route('kas-masuk.index') }}"
               class="flex flex-col items-center gap-1 flex-1 py-1
               {{ request()->routeIs('kas-masuk.index') ? 'text-black font-semibold' : 'text-gray-600' }}">
                <span class="material-symbols-outlined text-xl">trending_up</span>
                <span class="text-[11px]">Kas Masuk</span>
            </a>

            <a href="{{ route('kas-keluar.index') }}"
               class="flex flex-col items-center gap-1 flex-1 py-1
               {{ request()->routeIs('kas-keluar.index') ? 'text-black font-semibold' : 'text-gray-600' }}">
                <span class="material-symbols-outlined text-xl">trending_down</span>
                <span class="text-[11px]">Kas Keluar</span>
            </a>

            <a href="{{ route('laporan.index') }}"
               class="flex flex-col items-center gap-1 flex-1 py-1
               {{ request()->routeIs('laporan.index') ? 'text-black font-semibold' : 'text-gray-600' }}">
                <span class="material-symbols-outlined text-xl">description</span>
                <span class="text-[11px]">Laporan</span>
            </a>

        </div>
    </div>


    <!-- ===== NAVIGATION (DESKTOP Tengah) ===== -->
    <div class="hidden sm:flex w-full justify-center mt-5">
        <div class="w-[92%] px-3 py-2 rounded-2xl flex justify-evenly items-center" style="background-color: #eeeeee;">
            
            <a href="{{ route('dashboard') }}"
               class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl flex-1
               {{ request()->routeIs('dashboard') ? 'bg-white shadow-md' : 'hover:bg-white/60' }}">
                <span class="material-symbols-outlined text-lg">grid_view</span>
                <span class="text-xs font-medium">Dashboard</span>
            </a>

            <a href="{{ route('pos.index') }}"
               class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl flex-1
               {{ request()->routeIs('pos.index') ? 'bg-white shadow-md' : 'hover:bg-white/60' }}">
                <span class="material-symbols-outlined text-lg">shopping_cart</span>
                <span class="text-xs font-medium">POS</span>
            </a>
            

            <a href="{{ route('products.index') }}"
               class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl flex-1
               {{ request()->routeIs('products.index') ? 'bg-white shadow-md' : 'hover:bg-white/60' }}">
                <span class="material-symbols-outlined text-lg">inventory_2</span>
                <span class="text-xs font-medium">Produk</span>
            </a>

            <a href="{{ route('kas-masuk.index') }}"
               class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl flex-1
               {{ request()->routeIs('kas-masuk.index') ? 'bg-white shadow-md' : 'hover:bg-white/60' }}">
                <span class="material-symbols-outlined text-lg">trending_up</span>
                <span class="text-xs font-medium">Kas Masuk</span>
            </a>

            <a href="{{ route('kas-keluar.index') }}"
               class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl flex-1
               {{ request()->routeIs('kas-keluar.index') ? 'bg-white shadow-md' : 'hover:bg-white/60' }}">
                <span class="material-symbols-outlined text-lg">trending_down</span>
                <span class="text-xs font-medium">Kas Keluar</span>
            </a>

            <a href="{{ route('laporan.index') }}"
               class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl flex-1
               {{ request()->routeIs('laporan.index') ? 'bg-white shadow-md' : 'hover:bg-white/60' }}">
                <span class="material-symbols-outlined text-lg">description</span>
                <span class="text-xs font-medium">Laporan</span>
            </a>

        </div>
    </div>

</nav>
