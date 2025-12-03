<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Teh Solo de Jumbo Fibonacci</title>

    {{-- Fonts & Icons --}}
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

    {{-- Main Container: Max Width 5xl agar tidak terlalu lebar di monitor besar --}}
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl shadow-stone-200/50 overflow-hidden flex flex-col md:flex-row-reverse min-h-[600px]">

        {{-- VISUAL SECTION (Kanan di Desktop, Atas di Mobile) --}}
        {{-- Menggunakan h-48 di mobile agar tidak memakan seluruh layar HP --}}
        <div class="w-full md:w-5/12 h-48 md:h-auto bg-brand-dark relative overflow-hidden flex flex-col justify-center items-center text-center p-6 text-white shrink-0">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-600 to-brand-700 opacity-95 z-10"></div>
            {{-- Pattern Background --}}
            <div class="absolute inset-0 opacity-20 z-0" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>

            <div class="relative z-20 flex flex-col items-center">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur-md rounded-2xl rotate-3 flex items-center justify-center border border-white/20 mb-4 md:mb-6 shadow-lg">
                   <img src="{{ asset('assets/images/logo-teh.png') }}" alt="Logo" class="w-10 h-10 md:w-12 md:h-12 object-contain -rotate-3">
                </div>

                <h2 class="text-2xl md:text-3xl font-bold mb-2">Bergabunglah!</h2>
                <p class="text-white/80 text-xs md:text-sm leading-relaxed max-w-[250px] hidden md:block">
                    Mulai perjalanan rasa otentik Anda bersama Teh Solo Jumbo Fibonacci.
                </p>
            </div>
        </div>

        {{-- FORM SECTION (Kiri di Desktop, Bawah di Mobile) --}}
        <div class="w-full md:w-7/12 p-8 sm:p-12 flex flex-col justify-center bg-white">
            <div class="max-w-md mx-auto w-full">

                <div class="mb-8 text-center md:text-left">
                    <h3 class="text-2xl md:text-3xl font-bold text-stone-800">Buat Akun Baru</h3>
                    <p class="text-sm text-stone-500 mt-2">Lengkapi data diri Anda untuk mendaftar.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1 pl-3">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full px-5 py-3 rounded-full bg-stone-50 border border-stone-200 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 outline-none transition-all text-sm font-medium" placeholder="Nama Anda" required autofocus>
                        @error('name') <p class="text-red-500 text-xs mt-1 pl-3">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1 pl-3">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-5 py-3 rounded-full bg-stone-50 border border-stone-200 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 outline-none transition-all text-sm font-medium" placeholder="contoh@gmail.com" required>
                        @error('email') <p class="text-red-500 text-xs mt-1 pl-3">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- PASSWORD --}}
                        <div x-data="{ show: false }">
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1 pl-3">Password</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password"
                                    class="w-full pl-5 pr-10 py-3 rounded-full bg-stone-50 border border-stone-200 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 outline-none transition-all text-sm font-medium" placeholder="••••••••" required>
                                <button type="button" @click="show = !show"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 hover:text-brand-600 transition-colors focus:outline-none">
                                    <i class="fa-regular" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('password') <p class="text-red-500 text-xs mt-1 pl-3">{{ $message }}</p> @enderror
                        </div>

                        {{-- CONFIRMATION PASSWORD --}}
                        <div x-data="{ show: false }">
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1 pl-3">Konfirmasi</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation"
                                    class="w-full pl-5 pr-10 py-3 rounded-full bg-stone-50 border border-stone-200 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 outline-none transition-all text-sm font-medium" placeholder="••••••••" required>
                                <button type="button" @click="show = !show"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 hover:text-brand-600 transition-colors focus:outline-none">
                                    <i class="fa-regular" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3.5 rounded-full text-white font-bold text-sm uppercase tracking-wider bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50 transition-all transform hover:-translate-y-0.5">
                            Register
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center text-sm text-stone-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-brand-600 font-bold hover:text-brand-700 hover:underline transition-all">Login disini</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
