<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>{{ config('app.name', 'Teh Solo De Jumbo Fibonacci') }}</title>
    <link rel="icon" href="{{ asset('assets/images/logo-teh.png') }}" type="image/png">

    {{-- Fonts: Plus Jakarta Sans (Sama dengan Guest Blade) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Icons --}}
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
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(231, 229, 228, 0.6);
        }
        .text-glow {
            text-shadow: 0 0 30px rgba(249, 115, 22, 0.2);
        }
        /* Custom selection color to match brand */
        ::selection {
            background-color: #f97316;
            color: white;
        }
    </style>
</head>
<body class="font-sans antialiased text-stone-900 bg-stone-50 overflow-x-hidden selection:bg-brand-500 selection:text-white">

    {{-- Background Blobs (Lebih halus agar senada dengan Guest) --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] right-[-5%] w-[600px] h-[600px] bg-brand-100/40 rounded-full blur-[120px] mix-blend-multiply animate-blob"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[500px] h-[500px] bg-stone-200/40 rounded-full blur-[100px] mix-blend-multiply animate-blob animation-delay-2000"></div>
    </div>

    {{-- Navbar --}}
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                {{-- Logo --}}
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/images/logo-teh.png') }}" alt="Logo" class="w-10 h-10 object-contain hover:rotate-12 transition-transform duration-500">
                    <div class="flex flex-col leading-none">
                        <span class="font-bold text-lg text-stone-900 tracking-tight">Teh Solo</span>
                        <span class="text-[11px] font-bold text-brand-600 uppercase tracking-wider">De Jumbo</span>
                    </div>
                </div>

                {{-- Menu Kanan --}}
                <div class="flex items-center gap-3 sm:gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-sm text-stone-600 hover:text-brand-600 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-5 py-2.5 bg-stone-900 hover:bg-stone-800 text-white text-sm font-bold rounded-full shadow-lg shadow-stone-900/20 transition-all transform hover:-translate-y-0.5 hover:shadow-xl">
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-16 lg:pt-48 lg:pb-32 min-h-[90vh] flex items-center z-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 w-full">
            <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                {{-- 1. Text Content (Left) --}}
                <div class="lg:col-span-7 flex flex-col items-center lg:items-start text-center lg:text-left">

                    {{-- Badge --}}
                    <div class="animate-fade-in inline-flex items-center gap-2 px-3 py-1.5 mb-8 rounded-full bg-white border border-stone-200 shadow-sm">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-leaf-500 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-leaf-500"></span>
                        </span>
                        <span class="text-[10px] font-bold tracking-widest text-stone-600 uppercase">The Authentic Taste of Java</span>
                    </div>

                    {{-- Headline --}}
                    <h1 class="animate-slide-up text-5xl sm:text-6xl lg:text-7xl font-extrabold text-stone-900 mb-6 leading-[1.1] tracking-tight">
                        Segarnya <br class="hidden lg:block">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-500 to-brand-600 text-glow">
                            Teh Solo Asli
                        </span>
                    </h1>

                    <p class="animate-slide-up text-lg text-stone-500 mb-10 leading-relaxed max-w-lg font-medium" style="animation-delay: 0.1s;">
                        Rasakan kenikmatan <span class="text-stone-900 font-bold">Ginastel</span> (Legit, Panas, Kental) dalam kemasan jumbo. Aroma melati khas, gula murni, tanpa pemanis buatan.
                    </p>

                    <div class="animate-slide-up flex flex-col sm:flex-row gap-4 w-full sm:w-auto" style="animation-delay: 0.2s;">
                        <a href="{{ route('login') }}"
                           class="inline-flex justify-center items-center px-8 py-4 text-base font-bold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-full hover:from-brand-600 hover:to-brand-700 shadow-xl shadow-brand-500/20 transition-all transform hover:-translate-y-1 w-full sm:w-auto">
                            <span class="material-symbols-rounded mr-2 text-[20px]">shopping_bag</span>
                            Pesan Sekarang
                        </a>
                        <a href="#features"
                           class="inline-flex justify-center items-center px-8 py-4 text-base font-bold text-stone-600 bg-white border border-stone-200 rounded-full hover:bg-stone-50 hover:border-stone-300 hover:text-stone-900 transition-all w-full sm:w-auto shadow-sm">
                            Tentang Kami
                        </a>
                    </div>
                </div>

                {{-- 2. Image Content (Right) --}}
                <div class="lg:col-span-5 relative flex justify-center items-center mt-10 lg:mt-0 animate-fade-in" style="animation-delay: 0.3s;">

                    {{-- Glow effect behind image --}}
                    <div class="absolute z-0">
                         <div class="w-[300px] h-[300px] sm:w-[450px] sm:h-[450px] bg-gradient-to-tr from-brand-200/50 to-orange-100/50 rounded-full blur-3xl opacity-60 animate-pulse"></div>
                    </div>

                    {{-- Main Image --}}
                    <div class="relative z-10 transform transition-transform hover:scale-105 duration-700 ease-out">
                        <img src="{{ asset('assets/images/teh-jumbo-polos.jpg') }}"
                             alt="Teh Jumbo Segar"
                             class="w-[240px] sm:w-[320px] lg:w-[400px] object-contain drop-shadow-[0_25px_50px_rgba(0,0,0,0.15)] animate-float">

                        {{-- Floating Card 1 (Top Right) --}}
                        <div class="absolute top-12 -right-4 lg:right-0 bg-white/80 backdrop-blur-md px-4 py-3 rounded-2xl shadow-lg border border-white/40 animate-float" style="animation-delay: 1.5s;">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-leaf-50 flex items-center justify-center text-leaf-600">
                                    <span class="material-symbols-rounded">eco</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-stone-400 font-bold uppercase tracking-wider">Aroma</span>
                                    <span class="text-sm font-bold text-stone-800">Melati Asli</span>
                                </div>
                            </div>
                        </div>

                        {{-- Floating Card 2 (Bottom Left) --}}
                        <div class="absolute bottom-16 -left-4 lg:left-0 bg-white/80 backdrop-blur-md px-4 py-3 rounded-2xl shadow-lg border border-white/40 animate-float" style="animation-delay: 2.5s;">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center text-brand-600">
                                    <span class="material-symbols-rounded">verified</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-stone-400 font-bold uppercase tracking-wider">Gula</span>
                                    <span class="text-sm font-bold text-stone-800">100% Murni</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section id="features" class="py-24 bg-white relative z-10 border-t border-stone-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-brand-600 font-bold tracking-wider uppercase text-xs mb-2 block">Keunggulan Kami</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-stone-900 tracking-tight">Kualitas dalam Setiap Tetes</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                {{-- Item 1 --}}
                <div class="p-8 rounded-[2rem] bg-stone-50 border border-stone-100 hover:shadow-xl hover:shadow-brand-500/5 transition-all duration-300 group hover:-translate-y-1">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-6 group-hover:bg-brand-500 group-hover:text-white transition-colors duration-300 text-brand-500">
                        <span class="material-symbols-rounded text-3xl">water_drop</span>
                    </div>
                    <h3 class="text-xl font-bold text-stone-900 mb-3">Air Berkualitas</h3>
                    <p class="text-stone-500 leading-relaxed text-sm">Menggunakan air mineral pilihan yang dimasak dengan suhu sempurna 90°C untuk ekstraksi teh terbaik.</p>
                </div>

                {{-- Item 2 --}}
                <div class="p-8 rounded-[2rem] bg-stone-50 border border-stone-100 hover:shadow-xl hover:shadow-brand-500/5 transition-all duration-300 group hover:-translate-y-1">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-6 group-hover:bg-brand-500 group-hover:text-white transition-colors duration-300 text-brand-500">
                        <span class="material-symbols-rounded text-3xl">spa</span>
                    </div>
                    <h3 class="text-xl font-bold text-stone-900 mb-3">Daun Teh Pilihan</h3>
                    <p class="text-stone-500 leading-relaxed text-sm">Racikan rahasia dari 3 jenis daun teh hitam dan melati premium dari perkebunan terbaik Jawa Tengah.</p>
                </div>

                {{-- Item 3 --}}
                <div class="p-8 rounded-[2rem] bg-stone-50 border border-stone-100 hover:shadow-xl hover:shadow-brand-500/5 transition-all duration-300 group hover:-translate-y-1">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-6 group-hover:bg-brand-500 group-hover:text-white transition-colors duration-300 text-brand-500">
                        <span class="material-symbols-rounded text-3xl">local_cafe</span>
                    </div>
                    <h3 class="text-xl font-bold text-stone-900 mb-3">Racikan Ginastel</h3>
                    <p class="text-stone-500 leading-relaxed text-sm">Legit, Panas, dan Kental. Cita rasa otentik Solo yang menjaga warisan budaya ngeteh.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Dark CTA Section (Menyelaraskan dengan Kolom Kanan Guest Blade) --}}
    <section class="py-24 bg-stone-900 relative overflow-hidden">
        {{-- Background Elements similar to Guest Right Column --}}
        <div class="absolute inset-0 bg-gradient-to-br from-stone-900 via-stone-900 to-stone-800"></div>
        <div class="absolute inset-0 bg-brand-900/10 mix-blend-overlay"></div>
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-brand-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-leaf-500/10 rounded-full blur-3xl"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6 tracking-tight">
                Siap Segarkan Harimu?
            </h2>
            <p class="text-lg text-stone-300 mb-10 max-w-2xl mx-auto font-light">
                Bergabunglah dengan ribuan pelanggan yang telah menikmati kesegaran Teh Solo de Jumbo Fibonacci.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}" class="px-8 py-4 bg-white text-stone-900 font-bold rounded-full hover:bg-brand-50 transition-all transform hover:-translate-y-1 shadow-lg shadow-white/10">
                    Pesan Sekarang
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-stone-50 border-t border-stone-200 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col items-center justify-center text-center">
                <div class="flex items-center gap-2 mb-6 opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
                     <img src="{{ asset('assets/images/logo-teh.png') }}" alt="Logo" class="h-8 w-8 object-contain">
                     <span class="font-bold text-stone-700 text-lg">Teh Solo</span>
                </div>

                <div class="flex gap-6 mb-8 text-sm font-medium text-stone-500">
                    <a href="#" class="hover:text-brand-600 transition-colors">Tentang</a>
                    <a href="#" class="hover:text-brand-600 transition-colors">Menu</a>
                    <a href="#" class="hover:text-brand-600 transition-colors">Lokasi</a>
                    <a href="#" class="hover:text-brand-600 transition-colors">Kontak</a>
                </div>

                <div class="w-full h-px bg-stone-200 mb-8 max-w-xs"></div>

                <p class="text-stone-400 text-xs">
                    © {{ date('Y') }} <span class="font-bold text-stone-600">Teh Solo de Jumbo Fibonacci</span>. <br class="sm:hidden"> All rights reserved.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
