<x-guest-layout>
    {{-- Session Status --}}
    <x-auth-session-status class="mb-6 bg-leaf-50 border border-leaf-200 text-leaf-700 rounded-2xl p-4 text-xs sm:text-sm font-medium flex items-start sm:items-center gap-3 animate-fade-in shadow-sm ring-1 ring-leaf-100" :status="session('status')" />

    {{-- Welcome Header --}}
    <div class="mb-8 lg:mb-10 text-center lg:text-left animate-slide-up">
        {{-- Badge Mobile Only --}}
        <div class="lg:hidden inline-block mb-3 px-3 py-1 rounded-full bg-brand-50 text-brand-600 text-[10px] font-extrabold uppercase tracking-widest border border-brand-100">
            Form Login
        </div>

        <h2 class="text-3xl md:text-3xl lg:text-4xl font-extrabold text-stone-900 mb-2 tracking-tight leading-tight">
            Halo, Tim De Jumbo!
        </h2>
        <p class="text-stone-500 text-sm md:text-base leading-relaxed max-w-xs mx-auto lg:mx-0 font-medium">
            Masuk untuk kelola penjualan outlet <br class="hidden lg:block">
            <strong class="text-stone-800">Teh Solo De Jumbo</strong>.
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5 animate-slide-up" style="animation-delay: 0.1s;">
        @csrf

        {{-- Email Field --}}
        <div class="space-y-2">
            <label for="email" class="text-xs font-bold text-stone-500 uppercase tracking-widest ml-1">
                Email
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-stone-400 group-focus-within:text-brand-600 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                </div>
                {{-- Input: Padding disesuaikan agar proporsional --}}
                <input id="email"
                       class="block w-full pl-11 pr-4 py-3.5 rounded-2xl border border-stone-200 bg-stone-50 text-stone-800 text-sm md:text-base font-semibold placeholder-stone-400 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all duration-200 outline-none shadow-sm group-hover:bg-white group-hover:border-stone-300"
                       type="email"
                       name="email"
                       :value="old('email')"
                       required autofocus
                       placeholder="pegawai@tsjumbo.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="ml-1 text-xs font-bold text-rose-500" />
        </div>

        {{-- Password Field --}}
        <div class="space-y-2" x-data="{ show: false }">
            <div class="flex justify-between items-center ml-1">
                <label for="password" class="text-xs font-bold text-stone-500 uppercase tracking-widest">Kata Sandi</label>
            </div>

            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-stone-400 group-focus-within:text-brand-600 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>

                <input id="password"
                       class="block w-full pl-11 pr-12 py-3.5 rounded-2xl border border-stone-200 bg-stone-50 text-stone-800 text-sm md:text-base font-semibold placeholder-stone-400 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all duration-200 outline-none shadow-sm group-hover:bg-white group-hover:border-stone-300"
                       :type="show ? 'text' : 'password'"
                       name="password"
                       required autocomplete="current-password"
                       placeholder="••••••••" />

                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 pl-3 flex items-center text-stone-400 hover:text-stone-600 cursor-pointer transition-colors focus:outline-none rounded-r-2xl">
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg x-show="show" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7c.85 0 1.68-.08 2.49-.23"/><line x1="1" x2="23" y1="1" y2="23"/></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="ml-1 text-xs font-bold text-rose-500" />
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group select-none">
                <div class="relative flex items-center">
                    <input id="remember_me" type="checkbox" class="peer sr-only" name="remember">
                    <div class="w-5 h-5 border-2 border-stone-300 rounded-md bg-stone-50 peer-checked:bg-brand-600 peer-checked:border-brand-600 transition-all duration-200 shadow-sm peer-focus:ring-2 peer-focus:ring-brand-500/20"></div>
                    <svg class="absolute w-3.5 h-3.5 text-white left-[3px] opacity-0 peer-checked:opacity-100 transition-opacity duration-200 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="ml-2.5 text-xs sm:text-sm font-bold text-stone-500 group-hover:text-stone-700 transition-colors">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs sm:text-sm font-bold text-brand-600 hover:text-brand-700 transition-colors text-right" href="{{ route('password.request') }}">
                    Lupa sandi?
                </a>
            @endif
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="w-full group relative flex justify-center py-3.5 px-4 border border-transparent text-base font-bold rounded-2xl text-white bg-stone-900 hover:bg-stone-800 shadow-[0_10px_20px_-10px_rgba(0,0,0,0.3)] hover:shadow-2xl hover:shadow-brand-500/20 hover:-translate-y-0.5 transition-all duration-300 overflow-hidden ring-1 ring-white/10 mt-3">
            <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-brand-600 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <span class="relative flex items-center gap-2">
                Masuk Sekarang
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </span>
        </button>
    </form>

    {{-- Footer Help --}}
    <div class="mt-8 pt-6 border-t border-dashed border-stone-200 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <p class="text-[10px] sm:text-xs text-stone-400 font-bold uppercase tracking-wider mb-2 sm:mb-0">Kendala Login?</p>

            <div class="flex items-center gap-2">
                <a href="https://wa.me/081311220271" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-leaf-600 hover:text-leaf-700 hover:bg-leaf-50 border border-stone-200 hover:border-leaf-200 rounded-lg text-xs font-bold transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 group">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    WhatsApp
                </a>

                <a href="mailto:wafamahabbah@gmail.com" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-stone-600 hover:text-brand-600 hover:bg-stone-50 border border-stone-200 hover:border-brand-200 rounded-lg text-xs font-bold transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 group">
                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    Email
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
