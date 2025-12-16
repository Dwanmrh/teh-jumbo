@props([
    'href' => null,     // Jika diisi link, jadi <a>. Jika tidak, jadi <button>
    'label' => 'Baru',  // Teks untuk desktop
    'icon' => 'add',    // Icon default
])

@php
    // ==========================================
    // 1. MOBILE CLASSES (FLOATING ACTION BUTTON)
    // ==========================================
    // PERBAIKAN:
    // 1. Menghapus 'ring-2 ring-white/50' (Ini penyebab border putih melayang)
    // 2. Menghapus 'border-stone-800' agar warna solid menyatu
    // 3. Menambah 'shadow-stone-900/30' agar bayangan lebih deep
    $mobileClasses = "md:hidden fixed bottom-28 right-5 z-30 w-14 h-14 bg-stone-900 text-white rounded-2xl shadow-xl shadow-stone-900/30 flex items-center justify-center active:scale-90 transition-transform duration-200";

    // ==========================================
    // 2. DESKTOP CLASSES (PILL BUTTON)
    // ==========================================
    // PERBAIKAN:
    // 1. Menghapus 'ring-1' untuk memastikan tidak ada garis halus
    // 2. Menghapus 'focus:ring-offset-2' jika ada (agar saat diklik tidak ada jarak putih)
    $desktopClasses = "hidden md:inline-flex group relative items-center justify-center gap-2 bg-stone-900 hover:bg-black text-white text-sm font-bold py-2.5 px-6 rounded-xl transition-all duration-300 shadow-lg shadow-stone-900/10 hover:shadow-stone-900/20 hover:-translate-y-0.5 overflow-hidden focus:outline-none focus:ring-2 focus:ring-stone-900 focus:ring-offset-0";
@endphp

{{-- LOGIKA: Jika ada 'href', render <a> tag. Jika tidak, render <button> tag --}}

@if($href)
    {{-- VERSION A: LINK (<a>) --}}
    <a href="{{ $href }}" {{ $attributes }}>

        {{-- TAMPILAN MOBILE --}}
        <div class="{{ $mobileClasses }}">
            <span class="material-symbols-rounded text-[28px] leading-none">{{ $icon }}</span>
        </div>

        {{-- TAMPILAN DESKTOP --}}
        <div class="{{ $desktopClasses }}">
            {{-- Efek Kilau Halus --}}
            <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>

            <span class="material-symbols-rounded text-[20px] group-hover:rotate-90 transition-transform duration-300">{{ $icon }}</span>
            <span class="relative tracking-wide">{{ $label }}</span>
        </div>
    </a>
@else
    {{-- VERSION B: BUTTON (<button>) --}}
    <button {{ $attributes }}>

        {{-- TAMPILAN MOBILE --}}
        <div class="{{ $mobileClasses }}">
             <span class="material-symbols-rounded text-[28px] leading-none">{{ $icon }}</span>
        </div>

        {{-- TAMPILAN DESKTOP --}}
        <div class="{{ $desktopClasses }}">
            {{-- Efek Kilau Halus --}}
             <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>

            <span class="material-symbols-rounded text-[20px] group-hover:rotate-90 transition-transform duration-300">{{ $icon }}</span>
            <span class="relative tracking-wide">{{ $label }}</span>
        </div>
    </button>
@endif
