<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' : '' }}{{ config('app.name', 'Teh Solo De Jumbo') }}</title>
    <link rel="icon" href="{{ asset('assets/images/logo-teh.png') }}" type="image/png">

    {{-- Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Icons: Material Symbols Rounded --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    {{-- Tailwind & Alpine --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#FFF7ED', 100: '#FFEDD5', 200: '#FED7AA', 300: '#FDBA74',
                            400: '#FB923C', 500: '#F97316', 600: '#EA580C', 700: '#C2410C',
                            800: '#9A3412', 900: '#7C2D12', 950: '#431407',
                        },
                        stone: { 850: '#1C1917' }
                    },
                    boxShadow: {
                        'glass': '0 4px 30px rgba(0, 0, 0, 0.1)',
                        'glow': '0 0 20px rgba(249, 115, 22, 0.3)',
                    },
                    backgroundImage: {
                        'noise': "url(\"data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.03'/%3E%3C/svg%3E\")",
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { -webkit-tap-highlight-color: transparent; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d6d3d1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a29e; }

        /* PERBAIKAN ICON: Kunci ukuran dan cegah teks "add" melebar */
        .material-symbols-rounded {
            font-family: 'Material Symbols Rounded';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;  /* Ukuran default */
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;

            /* Fitur Font */
            font-variation-settings: 'FILL' 0, 'wght' 500, 'GRAD' 0, 'opsz' 24;
            transition: font-variation-settings 0.3s ease;

            /* PENTING: Mencegah tombol loncat ukuran saat loading */
            width: 1em;
            height: 1em;
            overflow: hidden;
            vertical-align: middle;
        }

        .material-symbols-rounded.filled {
            font-variation-settings: 'FILL' 1, 'wght' 500, 'GRAD' 0, 'opsz' 24;
        }

        .pb-safe { padding-bottom: env(safe-area-inset-bottom, 20px); }
    </style>
</head>

<body class="font-sans antialiased bg-stone-50 text-stone-800 h-full flex flex-col selection:bg-brand-500 selection:text-white overflow-x-hidden relative">

    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-noise mix-blend-multiply"></div>
        <div class="hidden md:block absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-brand-200/40 rounded-full blur-[100px] opacity-60 mix-blend-multiply"></div>
        <div class="hidden md:block absolute bottom-[-10%] left-[-5%] w-[400px] h-[400px] bg-amber-100/50 rounded-full blur-[100px] opacity-60 mix-blend-multiply"></div>
        <div class="md:hidden absolute top-0 left-0 w-full h-[300px] bg-gradient-to-b from-brand-50/80 to-transparent opacity-60"></div>
    </div>

    <div class="relative z-10 flex-1 flex flex-col min-h-screen">

        @include('layouts.navigation')

        {{-- PAGE HEADING (Glass Sticky) --}}
        @if (isset($header))
            <header class="sticky top-[70px] md:top-[140px] z-30 transition-all duration-300 px-4 sm:px-6 lg:px-8 mt-20 md:mt-10">
                <div class="max-w-7xl mx-auto bg-white/70 backdrop-blur-xl border border-white/40 shadow-sm rounded-2xl py-4 px-6 flex items-center justify-between">
                    {{ $header }}
                </div>
            </header>
        @endif

        {{-- MAIN CONTENT --}}
        <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-[140px] md:pb-20 animate-fade-in pt-20 md:pt-12">
            {{ $slot }}
        </main>

        <footer class="hidden md:block py-8 text-center mt-auto border-t border-stone-200/50 bg-white/30 backdrop-blur-sm">
            <div class="flex flex-col items-center gap-3">
                <div class="flex items-center gap-2 opacity-60 hover:opacity-100 transition-opacity">
                    <img src="{{ asset('assets/images/logo-teh.png') }}" class="h-5 w-auto grayscale hover:grayscale-0 transition-all duration-300" alt="Logo">
                    <span class="text-[10px] font-bold tracking-[0.2em] text-stone-500 uppercase">Teh Solo De Jumbo System</span>
                </div>
                <p class="text-[10px] text-stone-400 font-medium">&copy; {{ date('Y') }} Created by RRSZ IT UMY.</p>
            </div>
        </footer>
    </div>

    @stack('modals')
    @stack('scripts')
</body>
</html>
