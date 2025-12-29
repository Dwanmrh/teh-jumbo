<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>{{ config('app.name', 'Teh Solo De Jumbo') }}</title>
    <link rel="icon" href="{{ asset('assets/images/logo-teh.png') }}" type="image/png">

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

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
                        leaf: { 50: '#f0fdf4', 500: '#22c55e', 600: '#16a34a', 700: '#15803d' },
                        stone: {
                            50: '#fafaf9', 100: '#f5f5f4', 200: '#e7e5e4', 300: '#d6d3d1',
                            400: '#a8a29e', 500: '#78716c', 600: '#57534e', 700: '#44403c',
                            800: '#292524', 900: '#1c1917', 950: '#0c0a09'
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-out forwards',
                        'slide-up': 'slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'float': 'float 6s ease-in-out infinite',
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { '0%': { opacity: '0', transform: 'translateY(30px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        float: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-15px)' } },
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
    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(231, 229, 228, 0.6);
        }
        .text-glow {
            text-shadow: 0 0 40px rgba(249, 115, 22, 0.2);
        }
        html { scroll-behavior: smooth; }
        body { overflow-x: hidden; }
    </style>
</head>
<body class="font-sans antialiased text-stone-900 bg-stone-50 selection:bg-brand-500 selection:text-white">

    {{-- Background Blobs --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] right-[-10%] w-[350px] md:w-[600px] h-[350px] md:h-[600px] bg-brand-100/40 rounded-full blur-[80px] lg:blur-[100px] mix-blend-multiply animate-blob"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[350px] md:w-[600px] h-[350px] md:h-[600px] bg-stone-200/50 rounded-full blur-[80px] lg:blur-[100px] mix-blend-multiply animate-blob animation-delay-2000"></div>
    </div>

    {{-- Navbar --}}
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300 top-0">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20"> {{-- Tinggi Navbar Fixed --}}
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-3 group">
                    <img src="{{ asset('assets/images/logo-teh.png') }}" alt="Logo" class="w-10 h-10 object-contain group-hover:rotate-12 transition-transform duration-500">
                    <div class="flex flex-col leading-none">
                        <span class="font-extrabold text-lg text-stone-900 tracking-tight">Teh Solo</span>
                        <span class="text-[10px] font-bold text-brand-600 uppercase tracking-wider">De Jumbo</span>
                    </div>
                </a>

                {{-- Menu Kanan --}}
                <div class="flex items-center gap-6">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-bold text-sm text-stone-600 hover:text-brand-600 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-5 py-2.5 bg-stone-900 hover:bg-brand-600 text-white text-xs sm:text-sm font-bold rounded-full shadow-lg shadow-stone-900/10 hover:shadow-brand-500/30 transition-all transform hover:-translate-y-0.5">
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    {{-- Perbaikan Proporsi: Padding Top ditambah agar tidak nabrak navbar, Grid disesuaikan --}}
    <section class="relative z-10 w-full pt-32 pb-16 lg:pt-40 lg:pb-24 min-h-screen flex items-center">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                {{-- 1. Text Content (Left) --}}
                <div class="flex flex-col items-center lg:items-start text-center lg:text-left order-2 lg:order-1">
                    {{-- Badge --}}
                    <div class="animate-fade-in inline-flex items-center gap-2 px-4 py-2 mb-6 rounded-full bg-white/70 backdrop-blur-sm border border-stone-200 shadow-sm hover:border-brand-200 transition-colors cursor-default">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-leaf-500 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-leaf-500"></span>
                        </span>
                        <span class="text-[10px] sm:text-xs font-bold tracking-widest text-stone-600 uppercase">The Authentic Taste of Java</span>
                    </div>

                    {{-- Headline --}}
                    <h1 class="animate-slide-up text-4xl sm:text-5xl lg:text-7xl font-extrabold text-stone-900 mb-6 leading-[1.1] tracking-tight">
                        Segarnya <br class="hidden lg:block">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-500 to-brand-600 text-glow">
                            Teh Solo Asli
                        </span>
                    </h1>

                    <p class="animate-slide-up text-base sm:text-lg lg:text-xl text-stone-500 mb-10 leading-relaxed max-w-lg font-medium" style="animation-delay: 0.1s;">
                        Nikmati sensasi <span class="text-stone-900 font-bold">Ginastel</span> (Legit, Panas, Kental) dalam porsi jumbo.
                        Terbuat dari daun teh pilihan dan 100% gula murni.
                    </p>

                    <div class="animate-slide-up flex flex-col sm:flex-row gap-4 w-full sm:w-auto" style="animation-delay: 0.2s;">
                        <a href="{{ route('login') }}"
                           class="inline-flex justify-center items-center px-8 py-4 text-sm sm:text-base font-bold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-2xl hover:from-brand-600 hover:to-brand-700 shadow-xl shadow-brand-500/30 transition-all transform hover:-translate-y-1 w-full sm:w-auto">
                            <span class="material-symbols-rounded mr-2 text-xl">shopping_cart</span>
                            Beli Sekarang
                        </a>
                        <a href="#features"
                           class="inline-flex justify-center items-center px-8 py-4 text-sm sm:text-base font-bold text-stone-600 bg-white border border-stone-200 rounded-2xl hover:bg-stone-50 hover:border-stone-300 hover:text-stone-900 transition-all w-full sm:w-auto shadow-sm">
                            Tentang Kami
                        </a>
                    </div>
                </div>

                {{-- 2. Image Content (Right) --}}
                <div class="relative flex justify-center items-center order-1 lg:order-2 animate-fade-in" style="animation-delay: 0.3s;">
                    {{-- Glow effect back --}}
                    <div class="absolute z-0 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                         <div class="w-[280px] h-[280px] sm:w-[450px] sm:h-[450px] bg-gradient-to-tr from-brand-200/50 to-orange-100/50 rounded-full blur-3xl opacity-60 animate-pulse"></div>
                    </div>

                    {{-- Main Image Wrapper --}}
                    <div class="relative z-10 w-[240px] sm:w-[320px] lg:w-[450px]">
                        <img src="{{ asset('assets/images/teh-jumbo-polos.jpg') }}"
                             alt="Teh Jumbo Segar"
                             class="w-full h-auto object-contain drop-shadow-[0_20px_50px_rgba(0,0,0,0.2)] animate-float">

                        {{-- Floating Card 1 (Aroma) - Posisi diperbaiki agar tidak terpotong di Mobile --}}
                        <div class="absolute top-0 right-0 sm:top-4 sm:-right-4 lg:top-12 lg:-right-6 bg-white/90 backdrop-blur-md px-3 py-2 sm:px-4 sm:py-3 rounded-2xl shadow-[0_15px_30px_rgba(0,0,0,0.08)] border border-white/60 animate-float" style="animation-delay: 1.5s;">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-leaf-50 flex items-center justify-center text-leaf-600">
                                    <span class="material-symbols-rounded text-lg sm:text-xl">eco</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[9px] sm:text-[10px] text-stone-400 font-bold uppercase tracking-wider">Aroma</span>
                                    <span class="text-xs sm:text-sm font-extrabold text-stone-800 whitespace-nowrap">Melati Asli</span>
                                </div>
                            </div>
                        </div>

                        {{-- Floating Card 2 (Gula) - Posisi diperbaiki --}}
                        <div class="absolute bottom-6 left-0 sm:bottom-10 sm:-left-4 lg:bottom-16 lg:-left-6 bg-white/90 backdrop-blur-md px-3 py-2 sm:px-4 sm:py-3 rounded-2xl shadow-[0_15px_30px_rgba(0,0,0,0.08)] border border-white/60 animate-float" style="animation-delay: 2.5s;">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-brand-50 flex items-center justify-center text-brand-600">
                                    <span class="material-symbols-rounded text-lg sm:text-xl">verified</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[9px] sm:text-[10px] text-stone-400 font-bold uppercase tracking-wider">Gula</span>
                                    <span class="text-xs sm:text-sm font-extrabold text-stone-800 whitespace-nowrap">100% Murni</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section id="features" class="py-20 lg:py-28 bg-white relative z-10 border-t border-stone-100">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-brand-600 font-bold tracking-widest uppercase text-xs sm:text-sm mb-3 block">Keunggulan Kami</span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-stone-900 tracking-tight leading-tight">Kualitas dalam Setiap Tetes</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Item 1 --}}
                <div class="p-8 rounded-[2rem] bg-stone-50 border border-stone-100 hover:shadow-xl hover:shadow-brand-500/5 transition-all duration-300 group hover:-translate-y-2">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-6 group-hover:bg-brand-500 group-hover:text-white transition-colors duration-300 text-brand-500">
                        <span class="material-symbols-rounded text-3xl">water_drop</span>
                    </div>
                    <h3 class="text-xl font-bold text-stone-900 mb-3">Air Berkualitas</h3>
                    <p class="text-stone-500 leading-relaxed text-sm sm:text-base">Menggunakan air mineral pilihan yang dimasak dengan suhu sempurna 90°C untuk ekstraksi teh terbaik.</p>
                </div>

                {{-- Item 2 --}}
                <div class="p-8 rounded-[2rem] bg-stone-50 border border-stone-100 hover:shadow-xl hover:shadow-brand-500/5 transition-all duration-300 group hover:-translate-y-2">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-6 group-hover:bg-brand-500 group-hover:text-white transition-colors duration-300 text-brand-500">
                        <span class="material-symbols-rounded text-3xl">spa</span>
                    </div>
                    <h3 class="text-xl font-bold text-stone-900 mb-3">Daun Teh Pilihan</h3>
                    <p class="text-stone-500 leading-relaxed text-sm sm:text-base">Racikan rahasia dari 3 jenis daun teh hitam dan melati premium dari perkebunan terbaik Jawa Tengah.</p>
                </div>

                {{-- Item 3 --}}
                <div class="p-8 rounded-[2rem] bg-stone-50 border border-stone-100 hover:shadow-xl hover:shadow-brand-500/5 transition-all duration-300 group hover:-translate-y-2">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-6 group-hover:bg-brand-500 group-hover:text-white transition-colors duration-300 text-brand-500">
                        <span class="material-symbols-rounded text-3xl">local_cafe</span>
                    </div>
                    <h3 class="text-xl font-bold text-stone-900 mb-3">Racikan Ginastel</h3>
                    <p class="text-stone-500 leading-relaxed text-sm sm:text-base">Legit, Panas, dan Kental. Cita rasa otentik Solo yang menjaga warisan budaya ngeteh.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Dark CTA Section --}}
    <section class="py-24 lg:py-32 bg-stone-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-stone-900 via-stone-900 to-stone-800"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
        <div class="absolute inset-0 bg-brand-900/10 mix-blend-overlay"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white mb-6 tracking-tight">
                Siap Segarkan Harimu?
            </h2>
            <p class="text-base sm:text-lg text-stone-300 mb-10 max-w-2xl mx-auto font-light leading-relaxed">
                Bergabunglah dengan ribuan pelanggan yang telah menikmati kesegaran Teh Solo de Jumbo Fibonacci. Rasa yang tak pernah bohong.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}" class="px-10 py-4 bg-white text-stone-900 text-base font-bold rounded-2xl hover:bg-brand-50 transition-all transform hover:-translate-y-1 shadow-lg shadow-white/10">
                    Beli Sekarang
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-stone-50 border-t border-stone-200 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-center text-center">
                <div class="flex items-center gap-3 mb-8 opacity-70 grayscale hover:grayscale-0 transition-all duration-500">
                     <img src="{{ asset('assets/images/logo-teh.png') }}" alt="Logo" class="h-10 w-10 object-contain">
                     <span class="font-bold text-stone-700 text-xl">Teh Solo De Jumbo</span>
                </div>

                <div class="w-full h-px bg-stone-200 mb-8 max-w-xs"></div>

                <p class="text-stone-400 text-xs sm:text-sm">
                    © {{ date('Y') }} <span class="font-bold text-stone-600">Teh Solo de Jumbo Fibonacci</span>. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
