<x-guest-layout>

    {{-- Header Section --}}
    <div class="mb-6">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-brand-50 text-brand-600 mb-4 ring-4 ring-brand-50/50">
            {{-- SVG Icon: Lock Reset --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/><path d="M12 15v2"/><path d="M12 17a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/><path d="M3 11V9a7 7 0 0 1 14 0v2"/></svg>
        </div>
        <h2 class="text-2xl font-extrabold text-stone-900 mb-2">Lupa Kata Sandi?</h2>
        <p class="text-stone-500 text-sm leading-relaxed">
            Jangan khawatir. Masukkan email yang terdaftar, dan kami akan meneruskan permintaan Anda ke Pusat.
        </p>
    </div>

    {{-- IMPORTANT NOTICE --}}
    <div class="mb-6 p-4 rounded-xl bg-orange-50 border border-orange-100 flex items-start gap-3">
        {{-- SVG Icon: Info --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-600 mt-0.5 shrink-0"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        <div class="text-xs text-orange-800 leading-relaxed">
            <span class="font-bold block mb-1 text-orange-900">Penting:</span>
            Permintaan reset password akan <strong>dikirim ke Admin Pusat</strong> untuk verifikasi (ACC). Password baru akan dikirimkan manual setelah disetujui.
        </div>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        {{-- Email Field --}}
        <div class="space-y-1.5">
            <label for="email" class="text-xs font-bold text-stone-500 uppercase tracking-widest ml-1">Email Terdaftar</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-stone-400 group-focus-within:text-brand-600 transition-colors">
                    {{-- SVG Icon: At Sign (@) --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/></svg>
                </div>
                {{-- Padding left diset ke pl-12 supaya teks tidak menabrak ikon --}}
                <input id="email" class="block w-full pl-12 pr-4 py-3.5 rounded-xl border border-stone-200 bg-stone-50 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 text-stone-800 text-sm lg:text-base font-semibold transition-all placeholder-stone-400 outline-none"
                       type="email" name="email" :value="old('email')" required autofocus placeholder="email@tsjumbo.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="ml-1 text-xs font-semibold text-rose-500" />
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="w-full py-3.5 rounded-xl text-white font-bold text-sm uppercase tracking-widest bg-gradient-to-r from-stone-800 to-stone-900 hover:from-brand-600 hover:to-orange-500 shadow-lg shadow-stone-900/20 hover:shadow-brand-500/30 transition-all transform hover:-translate-y-0.5 active:scale-95 duration-300">
            {{ __('Ajukan Reset Password') }}
        </button>
    </form>

    {{-- Back to Login --}}
    <div class="mt-8 text-center border-t border-dashed border-stone-200 pt-6">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-bold text-stone-500 hover:text-brand-600 transition-colors group">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-1 transition-transform duration-300"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Kembali ke Halaman Login
        </a>
    </div>

</x-guest-layout>
