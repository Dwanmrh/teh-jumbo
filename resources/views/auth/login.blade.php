<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Teh Solo de Jumbo Fibonacci</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Tailwind & Alpine.js --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    colors: {
                        brand: { 500: '#F5A623', 600: '#F38C00', 700: '#D67600', dark: '#0A2E57' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-stone-50 flex items-center justify-center min-h-screen p-4 sm:p-6">

    <div class="w-full max-w-4xl bg-white rounded-3xl shadow-xl shadow-stone-200/50 overflow-hidden flex flex-col md:flex-row min-h-[550px]">

        {{-- VISUAL SECTION (Kiri di Desktop) --}}
        <div class="w-full md:w-5/12 h-40 md:h-auto bg-gradient-to-br from-brand-500 to-brand-600 relative overflow-hidden flex flex-col justify-center items-center text-center p-6 shrink-0">
            {{-- Decorative Blobs --}}
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-brand-700/20 rounded-full blur-3xl"></div>

            <div class="relative z-10 flex flex-col items-center">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg mb-3 md:mb-6 border border-white/30">
                    <img src="{{ asset('assets/images/logo-teh.png') }}" alt="Logo" class="w-10 h-10 md:w-12 md:h-12 object-contain drop-shadow-md">
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-1">Selamat Datang!</h2>
                <p class="text-white/90 text-xs md:text-sm leading-relaxed max-w-[200px] hidden md:block">
                    Nikmati aroma dan rasa otentik Teh Solo.
                </p>
            </div>
        </div>

        {{-- FORM SECTION (Kanan di Desktop) --}}
        <div class="w-full md:w-7/12 p-8 sm:p-12 flex flex-col justify-center bg-white relative">
            {{-- Icon Daun Dekoratif --}}
            <div class="absolute top-4 right-6 opacity-5 rotate-12">
                <i class="fa-solid fa-leaf text-8xl text-brand-500"></i>
            </div>

            <div class="max-w-xs sm:max-w-sm mx-auto w-full">
                <div class="mb-8 text-center md:text-left">
                    <h3 class="text-2xl md:text-3xl font-bold text-stone-800">Login Akun</h3>
                    <p class="text-sm text-stone-500 mt-2">Silakan masuk untuk melanjutkan.</p>
                </div>

                @if (session('status'))
                    <div class="mb-6 p-3 rounded-2xl bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100 flex items-center gap-2">
                        <i class="fa-solid fa-check-circle"></i> {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1 pl-3">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-5 py-3 rounded-full bg-stone-50 border border-stone-200 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 outline-none transition-all text-sm font-medium placeholder-stone-400"
                            placeholder="contoh@gmail.com" required autofocus>
                        @error('email') <p class="text-red-500 text-xs mt-1 pl-3">{{ $message }}</p> @enderror
                    </div>

                    <div x-data="{ show: false }">
                        <div class="flex justify-between items-center mb-1 pl-3 pr-1">
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 hover:underline">Lupa Password?</a>
                            @endif
                        </div>

                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password"
                                class="w-full pl-5 pr-12 py-3 rounded-full bg-stone-50 border border-stone-200 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 outline-none transition-all text-sm font-medium placeholder-stone-400"
                                placeholder="••••••••" required>

                            <button type="button" @click="show = !show"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 hover:text-brand-600 transition-colors focus:outline-none">
                                <i class="fa-regular" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1 pl-3">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3.5 rounded-full text-white font-bold text-sm uppercase tracking-wider bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50 transition-all transform hover:-translate-y-0.5">
                            Login
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center text-sm text-stone-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-brand-600 font-bold hover:text-brand-700 hover:underline transition-all">Register disini</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
