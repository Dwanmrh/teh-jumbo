@php
    $user = Auth::user();

    $adminMain = [
        ['route' => 'dashboard', 'icon' => 'grid_view', 'label' => 'Beranda'],
        ['route' => 'pos.index', 'icon' => 'point_of_sale', 'label' => 'Kasir'],
        ['route' => 'kas-masuk.index', 'icon' => 'trending_up', 'label' => 'Masuk'],
        ['route' => 'kas-keluar.index', 'icon' => 'trending_down', 'label' => 'Keluar'],
    ];
    $adminMore = [
        ['route' => 'products.index', 'icon' => 'inventory_2', 'label' => 'Produk'],
        ['route' => 'outlets.index', 'icon' => 'store', 'label' => 'Outlet'],
        ['route' => 'users.index', 'icon' => 'group', 'label' => 'User'],
        ['route' => 'laporan.index', 'icon' => 'description', 'label' => 'Laporan'],
    ];

    $staffMain = [
        ['route' => 'dashboard', 'icon' => 'grid_view', 'label' => 'Beranda'],
        ['route' => 'pos.index', 'icon' => 'point_of_sale', 'label' => 'Kasir'],
        ['route' => 'kas-masuk.index', 'icon' => 'trending_up', 'label' => 'Masuk'],
        ['route' => 'kas-keluar.index', 'icon' => 'trending_down', 'label' => 'Keluar'],
    ];
    $staffMore = [
         ['route' => 'products.index', 'icon' => 'inventory_2', 'label' => 'Cek Stok'],
    ];

    $mainLinks = ($user->role === 'admin') ? $adminMain : $staffMain;
    $moreLinks = ($user->role === 'admin') ? $adminMore : $staffMore;
    $allLinks  = array_merge($mainLinks, $moreLinks);
@endphp

{{-- TOP BAR (Identity) --}}
<nav class="fixed top-0 left-0 w-full z-50 bg-white/90 backdrop-blur-xl border-b border-stone-200/60 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-[64px] sm:h-[72px] flex justify-between items-center">
        {{-- Logo Brand --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group select-none">
                <img src="{{ asset('assets/images/logo-teh.png') }}" class="h-8 w-auto sm:h-9 object-contain transform group-hover:-rotate-12 transition-transform duration-500" alt="Logo">
                <div class="flex flex-col justify-center">
                    <span class="text-lg sm:text-xl font-extrabold text-stone-800 tracking-tight leading-none">Teh Solo <span class="text-brand-600">Jumbo</span></span>
                    <span class="text-[9px] sm:text-[10px] font-bold text-stone-400 tracking-[0.15em] uppercase mt-0.5 group-hover:text-brand-500 transition-colors">
                        {{ $user->role === 'admin' ? 'Owner Panel' : 'Staff Panel' }}
                    </span>
                </div>
            </a>
        </div>

        {{-- Profile --}}
        <div class="flex items-center">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center gap-2 py-1 pl-2 pr-1 sm:pr-2 rounded-full hover:bg-stone-100 border border-transparent hover:border-stone-200 transition-all duration-300 group">
                        <div class="hidden sm:block text-right leading-tight mr-1.5">
                            <div class="text-xs font-bold text-stone-800 group-hover:text-brand-700 transition-colors">{{ Str::limit($user->name, 15) }}</div>
                            <div class="text-[10px] text-stone-400 font-medium capitalize tracking-wide">{{ $user->role }}</div>
                        </div>
                        <div class="h-8 w-8 sm:h-9 sm:w-9 rounded-full bg-stone-900 text-white flex items-center justify-center text-xs font-bold border-[3px] border-stone-100 shadow-sm group-hover:scale-105 transition-transform">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <span class="material-symbols-rounded text-stone-400 text-xl sm:hidden">expand_more</span>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <div class="sm:hidden px-4 py-3 bg-stone-50 border-b border-stone-100">
                         <div class="font-bold text-stone-800 text-sm">{{ $user->name }}</div>
                         <div class="text-xs text-stone-500">{{ $user->email }}</div>
                    </div>
                    <div class="px-4 py-3 hidden sm:block bg-gradient-to-br from-brand-50/50 to-transparent border-b border-brand-100/30">
                        <p class="text-[10px] uppercase font-bold text-brand-600 tracking-wider">Akun Terverifikasi</p>
                        <p class="text-xs text-stone-600 font-medium truncate mt-0.5">{{ $user->email }}</p>
                    </div>
                    <div class="p-1.5 space-y-1">
                        <x-dropdown-link :href="route('profile.edit')" class="rounded-lg hover:bg-stone-50 hover:text-brand-600 flex items-center gap-2.5 text-xs font-semibold px-3 py-2">
                            <span class="material-symbols-rounded text-[18px]">person</span> Profil Saya
                        </x-dropdown-link>
                        <div class="h-px bg-stone-100 my-1 mx-2"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="rounded-lg text-rose-600 hover:bg-rose-50 hover:text-rose-700 flex items-center gap-2.5 text-xs font-semibold px-3 py-2">
                                <span class="material-symbols-rounded text-[18px]">logout</span> Keluar Sistem
                            </x-dropdown-link>
                        </form>
                    </div>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</nav>
<div class="h-[64px] sm:h-[72px]"></div>

{{-- DESKTOP NAV (Floating Pills) --}}
<div class="hidden md:block w-full sticky top-[64px] sm:top-[72px] z-40 pointer-events-none mb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pointer-events-auto mt-6">
        <div class="relative group mx-auto max-w-fit">
            <div class="bg-white/80 backdrop-blur-md px-2 py-2 rounded-full flex items-center shadow-soft border border-stone-200/80 ring-1 ring-stone-900/5 gap-1 overflow-x-auto no-scrollbar max-w-full">
                @foreach($allLinks as $link)
                    @php $isActive = request()->routeIs($link['route']) || request()->routeIs(explode('.', $link['route'])[0].'.*'); @endphp
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-2 px-4 lg:px-5 py-2 rounded-full transition-all duration-300 group relative select-none whitespace-nowrap
                              {{ $isActive
                                 ? 'bg-stone-800 text-white shadow-lg shadow-stone-800/20 scale-[1.02]'
                                 : 'text-stone-500 hover:text-brand-600 hover:bg-brand-50 hover:scale-[1.02]'
                              }}">
                        <span class="material-symbols-rounded text-[20px] {{ $isActive ? 'filled text-brand-400' : '' }}">{{ $link['icon'] }}</span>
                        <span class="text-sm font-bold tracking-wide">{{ $link['label'] }}</span>
                        @if(!$isActive)
                             <span class="absolute bottom-1.5 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-brand-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- MOBILE NAV (Tidak ada perubahan, kode tetap sama seperti yang lama) --}}
<div x-data="{ mobileMenuOpen: false }" class="md:hidden">
    <div x-show="mobileMenuOpen" x-transition.opacity @click="mobileMenuOpen = false" class="fixed inset-0 z-[90] bg-stone-900/40 backdrop-blur-[3px]"></div>
    <div class="fixed bottom-0 left-0 w-full z-[100] bg-white border-t border-stone-100 shadow-[0_-5px_30px_rgba(0,0,0,0.04)] pb-safe rounded-t-[20px]">
        <div class="flex justify-between items-end h-[64px] px-2 relative">
            <div class="flex-1 flex justify-around items-center h-full pl-2 pr-8">
                @foreach(array_slice($mainLinks, 0, 2) as $link)
                    @php $isActive = request()->routeIs($link['route']); @endphp
                    <a href="{{ route($link['route']) }}" class="flex flex-col items-center justify-center w-full h-full active:scale-90 transition-transform duration-200 group">
                        <div class="relative p-1 rounded-xl transition-all {{ $isActive ? '-translate-y-1' : '' }}">
                            <span class="material-symbols-rounded text-[26px] {{ $isActive ? 'filled text-brand-600' : 'text-stone-400 group-hover:text-stone-600' }}">{{ $link['icon'] }}</span>
                        </div>
                        <span class="text-[10px] font-bold {{ $isActive ? 'text-brand-700' : 'text-stone-400 group-hover:text-stone-600' }}">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </div>
            <div class="absolute left-1/2 -translate-x-1/2 -top-6 z-10">
                <div class="p-1.5 bg-[#FDFDFC] rounded-full shadow-[0_-2px_8px_rgba(0,0,0,0.02)]">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="w-14 h-14 rounded-full flex items-center justify-center text-white shadow-xl shadow-brand-500/30 transition-all duration-300 transform active:scale-90 ring-4 ring-[#FDFDFC]" :class="mobileMenuOpen ? 'bg-stone-800 rotate-45' : 'bg-gradient-to-br from-brand-500 to-brand-600 hover:brightness-110'">
                        <span class="material-symbols-rounded text-[32px] transition-transform duration-300" :class="mobileMenuOpen ? 'rotate-90' : ''" x-text="mobileMenuOpen ? 'add' : 'apps'"></span>
                    </button>
                </div>
            </div>
            <div class="flex-1 flex justify-around items-center h-full pl-8 pr-2">
                @foreach(array_slice($mainLinks, 2) as $link)
                    @php $isActive = request()->routeIs($link['route']); @endphp
                    <a href="{{ route($link['route']) }}" class="flex flex-col items-center justify-center w-full h-full active:scale-90 transition-transform duration-200 group">
                        <div class="relative p-1 rounded-xl transition-all {{ $isActive ? '-translate-y-1' : '' }}">
                            <span class="material-symbols-rounded text-[26px] {{ $isActive ? 'filled text-brand-600' : 'text-stone-400 group-hover:text-stone-600' }}">{{ $link['icon'] }}</span>
                        </div>
                        <span class="text-[10px] font-bold {{ $isActive ? 'text-brand-700' : 'text-stone-400 group-hover:text-stone-600' }}">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    <div x-cloak x-show="mobileMenuOpen"
         x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500"
         x-transition:enter-start="translate-y-[120%] opacity-50"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition cubic-bezier(0.16, 1, 0.3, 1) duration-300"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-[120%] opacity-0"
         @click.outside="mobileMenuOpen = false"
         class="fixed bottom-[100px] left-4 right-4 z-[95] bg-white rounded-3xl shadow-2xl shadow-stone-900/10 border border-stone-100 ring-1 ring-stone-900/5 overflow-hidden max-h-[60vh] flex flex-col">

        <div class="px-6 py-4 bg-stone-50 border-b border-stone-100 flex items-center justify-between shrink-0">
             <h3 class="text-sm font-extrabold text-stone-800 flex items-center gap-2 uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-brand-500"></span> Menu Lainnya
            </h3>
            <button @click="mobileMenuOpen = false" class="text-stone-400 hover:text-stone-800"><span class="material-symbols-rounded">close</span></button>
        </div>
        <div class="p-6 overflow-y-auto">
            <div class="grid grid-cols-4 gap-x-2 gap-y-6">
                @foreach($moreLinks as $link)
                    @php $isActive = request()->routeIs($link['route']); @endphp
                    <a href="{{ route($link['route']) }}" class="flex flex-col items-center gap-2 group active:scale-95 transition-transform">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all border shadow-sm {{ $isActive ? 'bg-stone-800 text-white border-stone-800 shadow-md ring-2 ring-brand-200' : 'bg-white border-stone-100 text-stone-500 group-hover:bg-brand-50 group-hover:border-brand-200 group-hover:text-brand-600' }}">
                            <span class="material-symbols-rounded text-[28px] {{ $isActive ? 'filled' : '' }}">{{ $link['icon'] }}</span>
                        </div>
                        <span class="text-[10px] font-bold text-center leading-tight text-stone-500 group-hover:text-stone-800 line-clamp-2">{{ $link['label'] }}</span>
                    </a>
                @endforeach
                 <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-2 group active:scale-95 transition-transform">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all border border-dashed border-stone-300 bg-stone-50 text-stone-400 group-hover:border-brand-400 group-hover:text-brand-600">
                        <span class="material-symbols-rounded text-[28px]">settings</span>
                    </div>
                    <span class="text-[10px] font-bold text-center leading-tight text-stone-500 group-hover:text-stone-800">Setting</span>
                </a>
            </div>
        </div>
    </div>
</div>
