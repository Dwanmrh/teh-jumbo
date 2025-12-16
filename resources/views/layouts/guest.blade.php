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
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { '0%': { opacity: '0', transform: 'translateY(30px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        zoomSlow: { '0%': { transform: 'scale(1)' }, '100%': { transform: 'scale(1.1)' } },
                        float: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-10px)' } }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        /* Glass effect for mobile header */
        .glass-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="font-sans antialiased text-stone-900 bg-stone-50 lg:bg-white h-screen overflow-hidden selection:bg-brand-500 selection:text-white">

    <div class="h-screen w-full flex flex-col lg:flex-row overflow-hidden relative">

        {{-- MOBILE & TABLET BACKGROUND (Header Image) --}}
        {{-- Hanya muncul di mobile/tablet (lg:hidden). Ini memberikan kesan "App" yang modern. --}}
        <div class="absolute inset-x-0 top-0 h-[45vh] lg:hidden z-0 overflow-hidden">
            <img src="{{ asset('assets/images/teh-jumbo.jpg') }}" class="w-full h-full object-cover opacity-90" alt="Background">
            <div class="absolute inset-0 bg-gradient-to-b from-stone-900/60 via-stone-900/20 to-transparent"></div>

            {{-- Mobile Branding Overlay --}}
            <div class="absolute top-0 left-0 w-full p-6 flex justify-between items-start animate-fade-in">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('assets/images/logo-teh.png') }}" alt="Logo" class="w-10 h-10 object-contain drop-shadow-md">
                    <div>
                        <h1 class="text-white font-extrabold text-lg leading-none tracking-tight">Teh Solo</h1>
                        <p class="text-brand-300 text-xs font-bold uppercase tracking-wider">De Jumbo</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- LEFT COLUMN: Form Area --}}
        {{-- Kita ubah layoutnya. Di mobile dia akan menjadi "Sheet" yang menumpuk di atas gambar. --}}
        <div class="w-full lg:w-[38.2%] h-full flex flex-col relative z-10
                    mt-[30vh] lg:mt-0
                    bg-white
                    rounded-t-[2.5rem] lg:rounded-none
                    shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.2)] lg:shadow-[10px_0_40px_-10px_rgba(0,0,0,0.05)]
                    overflow-hidden transition-all duration-500 ease-out">

            {{-- Scrollable Container --}}
            <div class="w-full h-full overflow-y-auto no-scrollbar flex flex-col">

                {{-- Decorative Handle Bar (Mobile Only) --}}
                <div class="lg:hidden w-full flex justify-center pt-4 pb-1 shrink-0">
                    <div class="w-12 h-1.5 bg-stone-200 rounded-full"></div>
                </div>

                {{-- Main Content Wrapper --}}
                <div class="flex-1 flex flex-col justify-center px-6 sm:px-12 md:px-20 lg:px-12 xl:px-16 py-6 lg:py-8 w-full max-w-[550px] mx-auto shrink-0">

                    {{-- Desktop Header (Logo) --}}
                    <div class="hidden lg:flex items-center gap-3 mb-10 animate-fade-in">
                        <img src="{{ asset('assets/images/logo-teh.png') }}" class="w-12 h-12 object-contain hover:rotate-12 transition-transform duration-500" alt="Logo">
                        <div class="flex flex-col justify-center">
                            <span class="text-xl font-bold tracking-tight text-stone-900 leading-none">Teh Manis Solo</span>
                            <span class="text-sm font-bold text-brand-600 leading-tight">De Jumbo</span>
                        </div>
                    </div>

                    {{-- Content Slot --}}
                    <div class="animate-slide-up" style="animation-delay: 0.1s;">
                        {{ $slot }}
                    </div>
                </div>

                {{-- Footer --}}
                <footer class="mt-auto w-full px-6 sm:px-12 md:px-20 lg:px-16 pb-6 pt-4 animate-fade-in shrink-0 bg-white">
                    <div class="w-full max-w-[550px] mx-auto">
                        <div class="w-full h-px bg-stone-100 mb-6 lg:mb-6"></div>
                        <div class="flex flex-col-reverse md:flex-row items-center justify-between gap-4 md:gap-0">
                            <div class="text-center md:text-left">
                                <p class="text-[10px] sm:text-xs text-stone-400 font-medium">
                                    &copy; {{ date('Y') }} Teh Solo De Jumbo.
                                </p>
                            </div>
                            <div class="flex items-center gap-4 sm:gap-6">
                                <a href="#" class="text-xs font-medium text-stone-500 hover:text-brand-600 transition-colors">Bantuan</a>
                                <a href="#" class="text-xs font-medium text-stone-500 hover:text-brand-600 transition-colors">Privasi</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        {{-- RIGHT COLUMN: Visual Branding (Desktop Only) --}}
        {{-- Tetap sama seperti sebelumnya, hanya muncul di Layar Besar (lg) --}}
        <div class="hidden lg:flex flex-1 relative bg-stone-900 h-full overflow-hidden">
            <div class="absolute inset-0">
                <img src="{{ asset('assets/images/teh-jumbo.jpg') }}" class="w-full h-full object-cover animate-zoom-slow opacity-90" alt="Es Teh Jumbo">
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-stone-900 via-stone-900/40 to-transparent opacity-90"></div>
            <div class="absolute inset-0 bg-brand-900/20 mix-blend-overlay"></div>

            <div class="relative z-20 w-full h-full flex flex-col justify-end p-16 xl:p-24 pb-20">
                <div class="max-w-2xl space-y-6 animate-slide-up" style="animation-delay: 0.3s;">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-brand-50 text-[10px] font-bold uppercase tracking-widest shadow-lg">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-leaf-500 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-leaf-500"></span>
                        </span>
                        Mitra Usaha Terpercaya
                    </div>
                    <h2 class="text-4xl xl:text-5xl font-extrabold text-white leading-[1.1] tracking-tight drop-shadow-lg">
                        Segarnya Rasa, <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-brand-200 to-white">
                            Cerdas Kelola Bisnis.
                        </span>
                    </h2>
                    <p class="text-base xl:text-lg text-stone-200 font-light leading-relaxed max-w-lg drop-shadow-md">
                        Sistem manajemen stok dan penjualan real-time untuk outlet modern.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
