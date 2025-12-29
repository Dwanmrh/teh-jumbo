<x-guest-layout>

    {{-- Header Section --}}
    <div class="mb-8 text-center lg:text-left animate-slide-up">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-brand-50 text-brand-600 mb-5 ring-4 ring-brand-50/50 shadow-sm transform hover:scale-105 transition-transform duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/><path d="M12 15v2"/><path d="M12 17a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/><path d="M3 11V9a7 7 0 0 1 14 0v2"/></svg>
        </div>
        <h2 class="text-3xl font-extrabold text-stone-900 mb-3 tracking-tight">Lupa Kata Sandi?</h2>
        <p class="text-stone-500 text-sm md:text-base leading-relaxed font-medium">
            Masukkan email terdaftar Anda. Kami akan meneruskan permintaan ini ke Admin Pusat.
        </p>
    </div>

    {{-- IMPORTANT NOTICE --}}
    <div class="mb-8 p-4 sm:p-5 rounded-2xl bg-orange-50 border border-orange-100 flex items-start gap-4 animate-slide-up shadow-sm" style="animation-delay: 0.1s;">
        <div class="p-2 bg-orange-100 rounded-lg shrink-0 text-orange-600 mt-0.5">
             <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        </div>
        <div class="text-xs md:text-sm text-orange-800 leading-relaxed">
            <span class="font-bold block mb-1 text-orange-900 uppercase tracking-wide">Penting:</span>
            Password baru tidak dikirim otomatis. Admin akan memverifikasi dan mengirim kredensial baru secara manual.
        </div>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-6 bg-leaf-50 border border-leaf-200 text-leaf-700 rounded-2xl p-4 text-sm font-medium flex items-center gap-3 animate-fade-in shadow-sm ring-1 ring-leaf-100" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6 animate-slide-up" style="animation-delay: 0.2s;">
        @csrf

        {{-- Email Field --}}
        <div class="space-y-2">
            <label for="email" class="text-xs font-bold text-stone-500 uppercase tracking-widest ml-1">Email Terdaftar</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-stone-400 group-focus-within:text-brand-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/></svg>
                </div>

                <input id="email"
                       class="block w-full pl-11 pr-4 py-3.5 rounded-2xl border border-stone-200 bg-stone-50 text-stone-800 text-base font-semibold placeholder-stone-400 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all duration-200 outline-none shadow-sm group-hover:bg-white group-hover:border-stone-300"
                       type="email" name="email" :value="old('email')" required autofocus placeholder="pegawai@tsjumbo.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="ml-1 text-xs font-bold text-rose-500" />
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="w-full group relative flex justify-center py-4 px-4 border border-transparent text-base font-bold rounded-2xl text-white bg-stone-900 hover:bg-stone-800 shadow-[0_10px_20px_-10px_rgba(0,0,0,0.3)] hover:shadow-2xl hover:shadow-brand-500/20 hover:-translate-y-0.5 transition-all duration-300 overflow-hidden ring-1 ring-white/10">
            <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-brand-600 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <span class="relative flex items-center gap-2">
                {{ __('Ajukan Reset Password') }}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                   <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </span>
        </button>
    </form>

    {{-- Back to Login --}}
    <div class="mt-8 text-center border-t border-dashed border-stone-200 pt-6 animate-slide-up" style="animation-delay: 0.3s;">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-bold text-stone-500 hover:text-brand-600 transition-colors group px-4 py-2 rounded-xl hover:bg-stone-50">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-1 transition-transform duration-300"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Kembali ke Login
        </a>
    </div>

</x-guest-layout>
