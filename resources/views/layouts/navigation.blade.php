{{-- 1. HEADER UTAMA (Logo & Profile) --}}
<nav class="fixed top-0 left-0 w-full z-[60] transition-all duration-300 bg-white/80 backdrop-blur-xl border-b border-stone-200/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-[64px] sm:h-[72px] flex justify-between items-center">

        {{-- Logo Kiri --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <div class="relative">
                    <div class="absolute inset-0 bg-brand-500 blur-lg opacity-20 group-hover:opacity-40 transition-opacity rounded-full"></div>
                    {{-- Logo size responsive --}}
                    <img src="{{ asset('assets/images/logo-teh.png') }}" class="relative h-8 w-8 sm:h-10 sm:w-10 object-contain group-hover:-rotate-6 transition-transform duration-300" alt="Logo">
                </div>
                <div class="flex flex-col -space-y-0.5">
                    <span class="text-lg sm:text-xl font-bold text-stone-900 tracking-tight">Teh Solo</span>
                    <span class="text-[9px] sm:text-[10px] font-bold text-brand-600 tracking-[0.2em] uppercase">Admin Panel</span>
                </div>
            </a>
        </div>

        {{-- User Menu Kanan --}}
        <div class="flex items-center gap-4">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center gap-2 sm:gap-3 pl-2 sm:pl-3 pr-1 py-1 rounded-full hover:bg-stone-100/80 transition duration-200 group focus:outline-none">
                        <div class="text-right hidden sm:block leading-tight">
                            <div class="text-sm font-bold text-stone-800 group-hover:text-brand-700 transition">{{ Auth::user()->name }}</div>
                            <div class="text-[10px] text-stone-500 font-medium">Administrator</div>
                        </div>
                        <div class="h-8 w-8 sm:h-9 sm:w-9 rounded-full bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white text-xs sm:text-sm font-bold shadow-md shadow-brand-500/20 ring-2 ring-white">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="material-symbols-rounded text-stone-400 group-hover:text-stone-600 transition text-lg sm:text-xl">expand_more</span>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-xs text-gray-500">Signed in as</p>
                        <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <x-dropdown-link :href="route('profile.edit')" class="hover:bg-brand-50 hover:text-brand-600">
                        <div class="flex items-center gap-2.5 py-1">
                            <span class="material-symbols-rounded text-[20px]">person</span> Profile
                        </div>
                    </x-dropdown-link>
                    <div class="border-t border-gray-100 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-rose-600 hover:bg-rose-50 hover:text-rose-700">
                            <div class="flex items-center gap-2.5 py-1">
                                <span class="material-symbols-rounded text-[20px]">logout</span> Log Out
                            </div>
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</nav>

{{-- SPACER --}}
<div class="h-[64px] sm:h-[72px]"></div>

@php
$navLinks = [
    ['route' => 'dashboard', 'icon' => 'grid_view', 'label' => 'Dashboard'],
    ['route' => 'pos.index', 'icon' => 'point_of_sale', 'label' => 'POS / Kasir'],
    ['route' => 'products.index', 'icon' => 'inventory_2', 'label' => 'Produk'],
    ['route' => 'kas-masuk.index', 'icon' => 'trending_up', 'label' => 'Kas Masuk'],
    ['route' => 'kas-keluar.index', 'icon' => 'trending_down', 'label' => 'Kas Keluar'],
    ['route' => 'laporan.index', 'icon' => 'description', 'label' => 'Laporan'],
];
@endphp

{{-- 2. MENU NAVIGASI (Desktop - Floating Dock Style) --}}
<div class="hidden sm:block w-full sticky top-[80px] z-40 pointer-events-none">
    <div class="max-w-fit mx-auto pointer-events-auto">
        <div class="bg-white/90 backdrop-blur-md px-2 py-1.5 rounded-2xl flex justify-center items-center shadow-soft border border-stone-200/60 ring-1 ring-black/5 gap-1 mt-6 transition-all hover:scale-[1.01]">
            @foreach($navLinks as $link)
                <a href="{{ route($link['route']) }}"
                   class="flex items-center gap-2 px-4 lg:px-5 py-2 lg:py-2.5 rounded-xl transition-all duration-300 group whitespace-nowrap relative overflow-hidden
                   {{ request()->routeIs($link['route']) ? 'bg-brand-600 text-white shadow-glow' : 'text-stone-500 hover:bg-stone-100 hover:text-stone-900' }}">
                    <span class="material-symbols-rounded text-[20px] relative z-10">{{ $link['icon'] }}</span>
                    <span class="text-xs font-bold tracking-wide relative z-10">{{ $link['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- 3. MENU NAVIGASI (Mobile - Floating Glass Dock) --}}
{{-- UPDATE: Menggunakan left-1/2 dan -translate-x-1/2 agar PRESISI di tengah (True Center) --}}
<div class="sm:hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4 transition-all duration-300">
    <div class="bg-white/90 backdrop-blur-xl border border-white/50 shadow-2xl shadow-stone-300/40 rounded-[2rem] px-2 py-3 flex justify-between items-center relative w-full">
        @foreach($navLinks as $link)
            @php $isActive = request()->routeIs($link['route']); @endphp
            <a href="{{ route($link['route']) }}"
               class="group relative flex flex-col items-center justify-center flex-1 transition-all duration-300
                      {{ $isActive ? '-translate-y-2' : '' }}">

                @if($isActive)
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-10 h-10 bg-brand-500/10 rounded-full blur-md"></div>
                @endif

                <div class="relative z-10 p-2 rounded-2xl transition-all duration-300
                            {{ $isActive ? 'bg-gradient-to-br from-brand-500 to-brand-600 text-white shadow-lg shadow-brand-500/40 scale-110' : 'text-stone-400 group-hover:text-brand-600 bg-transparent' }}">
                    <span class="material-symbols-rounded text-[24px] leading-none">
                        {{ $link['icon'] }}
                    </span>
                </div>
            </a>
        @endforeach
    </div>
</div>
