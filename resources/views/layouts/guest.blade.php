<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Teh Solo De Jumbo') }}</title>
    <link rel="icon" href="{{ asset('assets/images/logo-teh.png') }}" type="image/png">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#fff7ed', 100: '#ffedd5', 200: '#fed7aa', 300: '#fdba74',
                            400: '#fb923c', 500: '#f97316', 600: '#ea580c', 700: '#c2410c',
                            800: '#9a3412', 900: '#7c2d12', 950: '#431407',
                        },
                        leaf: { 50: '#f0fdf4', 500: '#22c55e', 600: '#16a34a', 700: '#15803d' }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-out forwards',
                        'slide-up': 'slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'zoom-slow': 'zoomSlow 20s linear infinite alternate',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { '0%': { opacity: '0', transform: 'translateY(30px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        zoomSlow: { '0%': { transform: 'scale(1)' }, '100%': { transform: 'scale(1.1)' } },
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        /* Fix mobile height */
        .h-dvh-real { height: 100vh; height: 100dvh; }
    </style>
</head>
<body class="font-sans antialiased text-stone-900 bg-stone-900 h-dvh-real w-full overflow-hidden selection:bg-brand-500 selection:text-white">

    <div class="h-full w-full flex flex-col lg:flex-row relative">

        {{-- ========================================== --}}
        {{-- BACKGROUND (MOBILE & TABLET VIEW ONLY) --}}
        {{-- ========================================== --}}
        <div class="absolute inset-0 lg:hidden z-0 overflow-hidden">
            <img src="{{ asset('assets/images/teh-jumbo.jpg') }}" class="w-full h-full object-cover opacity-60" alt="Background">
            <div class="absolute inset-0 bg-gradient-to-b from-stone-900/30 via-stone-900/60 to-stone-900/90"></div>

            {{-- Mobile Header Logo --}}
            <div class="absolute top-0 left-0 w-full p-6 flex justify-between items-start z-10 animate-fade-in pointer-events-none">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/images/logo-teh.png') }}" alt="Logo" class="w-10 h-10 object-contain drop-shadow-md">
                    <div>
                        <h1 class="text-white font-extrabold text-xl leading-none tracking-tight shadow-black drop-shadow-sm">Teh Solo</h1>
                        <p class="text-brand-300 text-xs font-bold uppercase tracking-wider drop-shadow-sm">De Jumbo</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- LEFT COLUMN (FORM CONTAINER) --}}
        {{-- ========================================== --}}
        <div class="absolute inset-0 z-10
                    lg:static lg:z-auto lg:h-full lg:w-[45%] xl:w-[40%] lg:bg-white lg:border-r lg:border-stone-100
                    overflow-y-auto no-scrollbar">

            {{--
                FIX UTAMA DISINI:
                1. Hapus 'lg:justify-center'. Ini penyebab konten terpotong di atas saat zoom/layar pendek.
                2. Tambahkan 'lg:py-10' untuk memberikan jarak aman (safe area) di atas dan bawah saat mode desktop.
            --}}
            <div class="min-h-full w-full flex flex-col justify-end relative lg:py-2">

                {{-- Spacer Mobile --}}
                <div class="h-[20vh] shrink-0 lg:hidden md:hidden"></div>

                {{-- CONTENT CARD --}}
                {{--
                    FIX KEDUA:
                    Tambahkan 'lg:my-auto'.
                    Ini akan membuat kartu otomatis ke tengah (center) jika ada ruang sisa,
                    TAPI jika ruang sempit (konten panjang/zoom), dia akan tetap bisa discroll tanpa terpotong atasnya.
                --}}
                <div class="w-full bg-white relative
                            rounded-t-[2.5rem] shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.5)]
                            md:max-w-md md:mx-auto md:rounded-3xl md:shadow-2xl md:my-10
                            lg:shadow-none lg:rounded-none lg:w-full lg:max-w-none
                            lg:my-auto
                            transition-all duration-300">

                    {{-- Handle Bar (Mobile Only) --}}
                    <div class="lg:hidden md:hidden w-full flex justify-center pt-5 pb-2 shrink-0 sticky top-0 bg-white z-20 rounded-t-[2.5rem]">
                        <div class="w-12 h-1.5 bg-stone-200 rounded-full"></div>
                    </div>

                    {{-- INNER WRAPPER --}}
                    <div class="w-full px-6 sm:px-10 lg:px-12 xl:px-16 py-8 lg:py-10 mx-auto lg:max-w-[520px]">

                        {{-- Desktop Logo --}}
                        <div class="hidden lg:flex items-center gap-4 mb-10 animate-fade-in pl-1">
                            <img src="{{ asset('assets/images/logo-teh.png') }}" class="w-12 h-12 object-contain hover:rotate-12 transition-transform duration-500" alt="Logo">
                            <div class="flex flex-col justify-center">
                                <span class="text-xl font-bold tracking-tight text-stone-900 leading-none">Teh Solo</span>
                                <span class="text-xs font-bold text-brand-600 leading-tight tracking-wide">De Jumbo</span>
                            </div>
                        </div>

                        {{-- SLOT --}}
                        <div class="animate-slide-up" style="animation-delay: 0.1s;">
                            {{ $slot }}
                        </div>

                        {{-- FOOTER --}}
                        <div class="mt-10 pt-6 border-t border-stone-100 flex flex-col-reverse md:flex-row items-center justify-between gap-3">
                            <p class="text-[10px] sm:text-xs text-stone-400 font-medium text-center md:text-left">
                                &copy; {{ date('Y') }} Teh Solo De Jumbo.
                            </p>
                            <div class="flex items-center gap-4 opacity-0 lg:opacity-100 transition-opacity">
                                <a href="#" class="text-xs font-medium text-stone-400 hover:text-brand-600">Bantuan</a>
                                <a href="#" class="text-xs font-medium text-stone-400 hover:text-brand-600">Privasi</a>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Spacer Bawah Mobile --}}
                <div class="h-8 w-full lg:hidden md:hidden bg-white"></div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- RIGHT COLUMN (VISUAL IMAGE DESKTOP) --}}
        {{-- ========================================== --}}
        <div class="hidden lg:flex flex-1 relative bg-stone-900 h-full overflow-hidden">
            <div class="absolute inset-0">
                <img src="{{ asset('assets/images/teh-jumbo.jpg') }}" class="w-full h-full object-cover animate-zoom-slow opacity-90" alt="Es Teh Jumbo">
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-stone-900 via-stone-900/40 to-transparent opacity-90"></div>
            <div class="absolute inset-0 bg-brand-900/10 mix-blend-overlay"></div>

            <div class="relative z-20 w-full h-full flex flex-col justify-end p-16 xl:p-24 pb-20">
                <div class="max-w-2xl space-y-6 animate-slide-up" style="animation-delay: 0.3s;">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-brand-50 text-[11px] font-bold uppercase tracking-widest shadow-lg">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-leaf-500 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-leaf-500"></span>
                        </span>
                        Mitra Usaha Terpercaya
                    </div>
                    <h2 class="text-5xl xl:text-6xl font-extrabold text-white leading-[1.1] tracking-tight drop-shadow-xl">
                        Segarnya Rasa, <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-brand-200 to-white">
                            Cerdas Kelola Bisnis.
                        </span>
                    </h2>
                    <p class="text-lg text-stone-200 font-light leading-relaxed max-w-lg drop-shadow-md">
                        Platform manajemen outlet modern dengan integrasi stok real-time dan analisis penjualan pintar.
                    </p>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
