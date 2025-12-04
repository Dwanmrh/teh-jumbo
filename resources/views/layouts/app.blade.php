<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Teh Solo Admin') }}</title>

    {{-- Fonts: Outfit (Modern Sans) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Icons: Material Symbols Rounded --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    {{-- Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Tailwind Config (Fallback / CDN Development) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#fff7ed', 100: '#ffedd5', 200: '#fed7aa',
                            300: '#fdba74', 400: '#fb923c', 500: '#f97316',
                            600: '#ea580c', 700: '#c2410c', 800: '#9a3412', 900: '#7c2d12'
                        }
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'glow': '0 4px 20px 0px rgba(249, 115, 22, 0.3)',
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Outfit', sans-serif; }

        /* Scrollbar Halus */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d6d3d1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a29e; }

        .material-symbols-rounded {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
    </style>
</head>

<body class="font-sans antialiased bg-stone-50 text-stone-800 selection:bg-brand-500 selection:text-white">

    <div class="min-h-screen flex flex-col relative">

        {{-- Include Navigation --}}
        @include('layouts.navigation')

        {{-- Main Content --}}
        {{-- UPDATE: pb-32 di mobile agar tidak tertutup nav bar bawah, sm:pb-10 di desktop --}}
        <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 pb-32 sm:pb-10">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        {{-- UPDATE: mb-24 di mobile --}}
        <footer class="py-6 text-center text-xs text-stone-400 mb-24 sm:mb-0">
            <div class="flex flex-col items-center gap-1">
                <p>&copy; {{ date('Y') }} <span class="font-bold text-brand-600">Teh Solo de Jumbo Fibonacci</span>.</p>
                <p class="opacity-70">Excellence in every cup.</p>
            </div>
        </footer>

    </div>

</body>
</html>
