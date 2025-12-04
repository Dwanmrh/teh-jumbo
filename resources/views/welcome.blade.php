<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Teh Solo de Jumbo Fibonacci') }}</title>

    {{-- Fonts: Outfit --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    {{-- Tailwind & Config (Disamakan dengan App Layout) --}}
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
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'blob': 'blob 7s infinite',
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
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
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .bg-pattern {
            background-image: radial-gradient(#ea580c 0.5px, transparent 0.5px), radial-gradient(#ea580c 0.5px, #fafaf9 0.5px);
            background-size: 24px 24px;
            background-position: 0 0, 12px 12px;
            opacity: 0.05;
        }
        .text-glow {
            text-shadow: 0 0 20px rgba(249, 115, 22, 0.3);
        }
    </style>
</head>
<body class="bg-stone-50 font-sans text-stone-800 antialiased overflow-x-hidden selection:bg-brand-500 selection:text-white relative">

    {{-- Latar Belakang Dekoratif (Sama seperti Guest Layout) --}}
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-pattern"></div>
        {{-- Blob Kiri Atas --}}
        <div class="absolute top-[-10%] left-[-5%] w-[500px] h-[500px] bg-brand-200/20 rounded-full blur-[100px] animate-blob mix-blend-multiply"></div>
        {{-- Blob Kanan Bawah --}}
        <div class="absolute bottom-[-10%] right-[-5%] w-[500px] h-[500px] bg-brand-300/20 rounded-full blur-[100px] animate-blob animation-delay-2000 mix-blend-multiply"></div>
    </div>

    {{-- Navbar --}}
    <nav class="fixed w-full z-50 bg-white/70 backdrop-blur-md border-b border-white/50 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                {{-- Logo --}}
                <div class="flex items-center gap-3">
                    <div class="relative group cursor-pointer">
                        <div class="absolute inset-0 bg-brand-500 blur-lg opacity-20 group-hover:opacity-40 transition-opacity rounded-full"></div>
                        <img src="{{ asset('assets/images/logo-teh.png') }}" alt="Logo" class="relative w-10 h-10 sm:w-11 sm:h-11 object-contain group-hover:-rotate-6 transition-transform duration-300">
                    </div>
                    <div class="flex flex-col leading-tight">
                        <span class="font-bold text-xl text-stone-900 tracking-tight">Teh Solo</span>
                        <span class="text-[10px] font-bold text-brand-600 tracking-[0.2em] uppercase">Jumbo Fibonacci</span>
                    </div>
                </div>

                {{-- Menu Kanan --}}
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-stone-600 hover:text-brand-600 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-medium text-stone-500 hover:text-stone-900 px-4 py-2 hidden sm:block transition-colors">Masuk</a>
                        <a href="{{ route('register') }}"
                           class="px-5 py-2.5 bg-stone-900 hover:bg-stone-800 text-white text-sm font-bold rounded-full shadow-lg shadow-stone-900/20 transition-all transform hover:-translate-y-0.5 hover:shadow-xl">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-16 sm:pt-40 lg:pt-48 lg:pb-32 flex items-center min-h-[90vh] z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="grid lg:grid-cols-12 gap-12 lg:gap-8 items-center">

                {{-- 1. Konten Teks (Kiri) --}}
                <div class="lg:col-span-7 flex flex-col items-center lg:items-start text-center lg:text-left animate-fade-in-up">

                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 mb-6 text-xs font-bold tracking-widest text-brand-700 uppercase bg-white border border-brand-100 rounded-full shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                        The Authentic Taste of Java
                    </div>

                    {{-- Headline --}}
                    <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold text-stone-900 mb-6 leading-[1.1] tracking-tight">
                        Segarnya <br class="hidden lg:block">
                        <span class="relative whitespace-nowrap text-transparent bg-clip-text bg-gradient-to-r from-brand-500 to-brand-600 text-glow">
                            Teh Solo Asli
                            {{-- Garis bawah dekoratif --}}
                            <svg class="absolute w-full h-3 -bottom-1 left-0 text-brand-300 -z-10 opacity-60" viewBox="0 0 200 9" fill="none"><path d="M2.00025 6.99997C25.7501 5.51786 102.398 2.37896 197.995 2.05352" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
                        </span>
                    </h1>

                    <p class="text-lg text-stone-500 mb-10 leading-relaxed max-w-lg">
                        Nikmati racikan istimewa <span class="font-bold text-stone-800">Teh Solo de Jumbo Fibonacci</span>. Aroma melati yang khas, rasa sepat yang pas, dan manis gula asli.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                        <a href="{{ route('login') }}"
                           class="inline-flex justify-center items-center px-8 py-4 text-base font-bold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-full hover:from-brand-600 hover:to-brand-700 shadow-xl shadow-brand-500/30 transition-all transform hover:-translate-y-1 w-full sm:w-auto">
                            <span class="material-symbols-rounded mr-2">shopping_bag</span>
                            Pesan Sekarang
                        </a>
                        <a href="#"
                           class="inline-flex justify-center items-center px-8 py-4 text-base font-bold text-stone-600 bg-white border border-stone-200 rounded-full hover:bg-stone-50 hover:border-stone-300 hover:text-stone-900 transition-all w-full sm:w-auto shadow-sm">
                            Lihat Menu
                        </a>
                    </div>
                </div>

                {{-- 2. Konten Gambar (Kanan) --}}
                <div class="lg:col-span-5 relative flex justify-center items-center mt-10 lg:mt-0">

                    {{-- Blob Belakang Gambar --}}
                    <div class="absolute z-0 animate-float">
                        <div class="w-[300px] h-[300px] sm:w-[450px] sm:h-[450px] bg-gradient-to-tr from-brand-100 to-orange-50 rounded-full blur-2xl opacity-80"></div>
                    </div>

                    {{-- Gambar Produk --}}
                    <div class="relative z-10 transform transition-transform hover:scale-105 duration-500">
                        {{-- Placeholder jika gambar tidak ada, gunakan style ini --}}
                        <img src="{{ asset('assets/images/teh-jumbo-polos.jpg') }}"
                             alt="Teh Jumbo Segar"
                             class="w-[200px] sm:w-[300px] lg:w-[380px] object-contain drop-shadow-[0_20px_40px_rgba(249,115,22,0.25)]">

                        {{-- Floating Badge 1 --}}
                        <div class="absolute top-10 right-0 bg-white/90 backdrop-blur-sm px-4 py-2 rounded-2xl shadow-lg border border-white/50 animate-float" style="animation-delay: 1s;">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-rounded text-green-600">eco</span>
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-stone-400 font-bold uppercase">Aroma</span>
                                    <span class="text-xs font-bold text-stone-800">Melati Asli</span>
                                </div>
                            </div>
                        </div>

                        {{-- Floating Badge 2 --}}
                        <div class="absolute bottom-10 -left-4 bg-white/90 backdrop-blur-sm px-4 py-2 rounded-2xl shadow-lg border border-white/50 animate-float" style="animation-delay: 2s;">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-rounded text-brand-600">verified</span>
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-stone-400 font-bold uppercase">Gula</span>
                                    <span class="text-xs font-bold text-stone-800">100% Murni</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Features / USP Section (Tambahan agar halaman lebih berisi) --}}
    <section class="py-20 bg-white relative z-10 border-t border-stone-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-6 rounded-3xl bg-stone-50 border border-stone-100 hover:shadow-lg hover:shadow-brand-500/5 transition-all group">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-4 group-hover:bg-brand-500 group-hover:text-white transition-colors text-brand-500">
                        <span class="material-symbols-rounded text-3xl">water_drop</span>
                    </div>
                    <h3 class="text-lg font-bold text-stone-900 mb-2">Air Berkualitas</h3>
                    <p class="text-sm text-stone-500">Menggunakan air mineral pilihan yang dimasak dengan suhu sempurna untuk ekstraksi teh terbaik.</p>
                </div>
                 <div class="p-6 rounded-3xl bg-stone-50 border border-stone-100 hover:shadow-lg hover:shadow-brand-500/5 transition-all group">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-4 group-hover:bg-brand-500 group-hover:text-white transition-colors text-brand-500">
                        <span class="material-symbols-rounded text-3xl">spa</span>
                    </div>
                    <h3 class="text-lg font-bold text-stone-900 mb-2">Daun Teh Pilihan</h3>
                    <p class="text-sm text-stone-500">Campuran daun teh hitam dan melati premium dari perkebunan terbaik di Jawa Tengah.</p>
                </div>
                 <div class="p-6 rounded-3xl bg-stone-50 border border-stone-100 hover:shadow-lg hover:shadow-brand-500/5 transition-all group">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-4 group-hover:bg-brand-500 group-hover:text-white transition-colors text-brand-500">
                        <span class="material-symbols-rounded text-3xl">local_cafe</span>
                    </div>
                    <h3 class="text-lg font-bold text-stone-900 mb-2">Racikan Ginastel</h3>
                    <p class="text-sm text-stone-500">Legit, Panas, dan Kental. Cita rasa otentik Solo yang tidak bisa Anda temukan di tempat lain.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-stone-50 border-t border-stone-200 py-8 relative z-10">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex justify-center items-center gap-2 mb-4 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
                 <img src="{{ asset('assets/images/logo-teh.png') }}" alt="Logo" class="h-8 w-8">
                 <span class="font-bold text-stone-700">Teh Solo</span>
            </div>
            <p class="text-stone-400 text-xs sm:text-sm">© {{ date('Y') }} <span class="font-bold text-brand-600">Teh Solo de Jumbo Fibonacci</span>. Excellence in every cup.</p>
        </div>
    </footer>

</body>
</html>
