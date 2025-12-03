<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teh Solo de Jumbo Fibonacci</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    colors: {
                        brand: { 500: '#F5A623', 600: '#F38C00', 700: '#D67600', dark: '#0A2E57' }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-15px)' },
                        },
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white font-sans text-gray-800 antialiased overflow-x-hidden selection:bg-brand-500 selection:text-white">

    {{-- Navbar --}}
    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-lg border-b border-gray-100 shadow-sm transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/images/logo-teh.png') }}" alt="Logo" class="w-9 h-9 sm:w-11 sm:h-11 rounded-full shadow-sm">
                    <div class="flex flex-col leading-none">
                        <span class="font-bold text-lg sm:text-xl text-gray-900">Teh Solo</span>
                        <span class="text-[10px] sm:text-xs font-semibold text-brand-600 tracking-widest uppercase">Jumbo Fibonacci</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-brand-600">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-brand-600 px-3 hidden sm:block">Masuk</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-bold rounded-full shadow-lg shadow-brand-500/30 transition-all transform hover:-translate-y-0.5">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    {{-- UPDATE: pt-28 (mobile) vs pt-40 (desktop) agar tidak terlalu turun di HP --}}
    <section class="relative pt-28 pb-10 sm:pt-40 lg:pt-48 lg:pb-32 overflow-hidden flex items-center min-h-screen sm:min-h-[90vh]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative w-full h-full">

            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center h-full">

                {{-- 1. Konten Teks --}}
                <div class="text-center lg:text-left relative z-20 order-2 lg:order-1 flex flex-col items-center lg:items-start">

                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-2 px-3 py-1 mb-4 sm:mb-6 text-[10px] sm:text-xs font-bold tracking-widest text-brand-700 uppercase bg-orange-50 rounded-full border border-orange-100 shadow-sm">
                        <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-brand-500 animate-pulse"></span>
                        The Authentic Taste
                    </div>

                    {{-- Headline Responsive --}}
                    <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold tracking-tight text-gray-900 mb-4 sm:mb-6 leading-[1.15]">
                        Segarnya <br class="hidden lg:block">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-yellow-500 relative">
                            Teh Solo Asli
                            <svg class="absolute w-full h-2 sm:h-3 -bottom-1 left-0 text-brand-200 -z-10" viewBox="0 0 200 9" fill="none"><path d="M2.00025 6.99997C25.7501 5.51786 102.398 2.37896 197.995 2.05352" stroke="currentColor" stroke-width="3"/></svg>
                        </span>
                    </h1>

                    <p class="text-base sm:text-lg lg:text-xl text-gray-500 mb-8 sm:mb-10 leading-relaxed max-w-lg mx-auto lg:mx-0">
                        Racikan istimewa <span class="font-semibold text-brand-700">Teh Solo de Jumbo Fibonacci</span>. Aroma melati, rasa sepat yang pas, dan manis gula asli.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <a href="{{ route('login') }}" class="inline-flex justify-center items-center px-8 py-3.5 text-sm sm:text-base font-bold text-white bg-brand-600 rounded-full hover:bg-brand-700 shadow-xl shadow-brand-500/30 w-full sm:w-auto transition-transform hover:-translate-y-1">
                            Pesan Sekarang
                        </a>
                        <a href="#about" class="inline-flex justify-center items-center px-8 py-3.5 text-sm sm:text-base font-bold text-gray-700 bg-white border-2 border-gray-100 rounded-full hover:border-brand-200 hover:text-brand-600 w-full sm:w-auto transition-colors">
                            Lihat Menu
                        </a>
                    </div>
                </div>

                {{-- 2. Konten Gambar --}}
                <div class="relative order-1 lg:order-2 flex justify-center items-center pb-6 lg:pb-0">

                    {{-- Blob Shape Responsive --}}
                    <div class="relative z-0 animate-float">
                        <div class="w-[280px] h-[280px] sm:w-[400px] sm:h-[400px] lg:w-[500px] lg:h-[500px] bg-gradient-to-br from-orange-100 via-amber-200 to-orange-300 opacity-90 shadow-2xl shadow-orange-200/50"
                             style="border-radius: 45% 55% 70% 30% / 30% 30% 70% 70%;">
                        </div>
                    </div>

                    {{-- Product Image Responsive --}}
                    {{-- UPDATE: Ukuran gambar w-[180px] (mobile) naik ke w-[400px] (desktop) --}}
                    <img src="{{ asset('assets/images/tehJumbo.png') }}"
                         alt="Teh Jumbo Segar"
                         class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[180px] sm:w-[280px] lg:w-[400px] drop-shadow-2xl z-10 hover:scale-105 transition-transform duration-500 ease-out">

                    {{-- Label Floating --}}
                    <div class="absolute top-[15%] right-[10%] z-20 bg-white/90 backdrop-blur text-brand-700 text-[10px] sm:text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg rotate-12 animate-pulse">
                        100% Gula Asli
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-white border-t border-gray-100 py-6">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-400 text-xs sm:text-sm">© {{ date('Y') }} Teh Solo de Jumbo Fibonacci.</p>
        </div>
    </footer>

</body>
</html>
