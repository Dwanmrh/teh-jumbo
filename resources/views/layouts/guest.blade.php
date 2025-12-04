<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Teh Solo Admin') }}</title>

    {{-- Fonts: Outfit --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    {{-- Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                        },
                        stone: {
                             50: '#fafaf9', 100: '#f5f5f4', 200: '#e7e5e4',
                             300: '#d6d3d1', 400: '#a8a29e', 500: '#78716c',
                             600: '#57534e', 700: '#44403c', 800: '#292524', 900: '#1c1917'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Outfit', sans-serif; }
        .bg-pattern {
            background-image: radial-gradient(#ea580c 0.5px, transparent 0.5px), radial-gradient(#ea580c 0.5px, #fafaf9 0.5px);
            background-size: 24px 24px;
            background-position: 0 0, 12px 12px;
            opacity: 0.1;
        }
    </style>
</head>
<body class="font-sans text-stone-900 antialiased bg-stone-50 min-h-screen flex flex-col justify-center items-center relative overflow-x-hidden py-6 sm:py-0">

    {{-- Background Dekoratif --}}
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-pattern"></div>
        {{-- Blob Kiri Atas --}}
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-brand-200/20 rounded-full blur-[100px]"></div>
        {{-- Blob Kanan Bawah --}}
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-brand-300/20 rounded-full blur-[100px]"></div>
    </div>

    {{-- Slot Content (Flexible Width) --}}
    <div class="w-full px-4 sm:px-6 relative z-10 flex justify-center">
        {{ $slot }}
    </div>

</body>
</html>
