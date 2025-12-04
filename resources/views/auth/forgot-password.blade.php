<x-guest-layout>
    <div class="w-full max-w-[420px] bg-white rounded-3xl shadow-xl shadow-stone-200/50 p-8 sm:p-10 border border-stone-100 relative">

        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-brand-50 rounded-2xl flex items-center justify-center text-brand-600 mx-auto mb-4 shadow-sm border border-brand-100">
                <span class="material-symbols-rounded text-3xl">key_off</span>
            </div>
            <h2 class="text-2xl font-bold text-stone-800">Lupa Password?</h2>
            <p class="text-sm text-stone-500 mt-2 leading-relaxed">
                {{ __('Masukkan email Anda, kami akan mengirimkan tautan untuk mengatur ulang password.') }}
            </p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div class="space-y-1">
                <label for="email" class="text-xs font-bold text-stone-600 uppercase tracking-wider pl-1">Email Terdaftar</label>
                <div class="relative group">
                    <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 group-focus-within:text-brand-500 transition-colors">mail</span>
                    <input id="email" class="block w-full pl-11 pr-4 py-3.5 rounded-xl border-stone-200 bg-stone-50 focus:bg-white focus:border-brand-500 focus:ring-brand-500 text-sm font-medium transition-all placeholder-stone-400"
                        type="email" name="email" :value="old('email')" required autofocus placeholder="alamat@email.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl text-white font-bold text-sm uppercase tracking-widest bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 shadow-lg shadow-brand-500/30 hover:shadow-brand-500/40 transition-all transform hover:-translate-y-0.5 active:scale-95">
                {{ __('Kirim Link Reset') }}
            </button>
        </form>

        <div class="mt-8 text-center border-t border-stone-100 pt-6">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-bold text-stone-500 hover:text-brand-600 transition-colors group">
                <span class="material-symbols-rounded text-lg group-hover:-translate-x-1 transition-transform">arrow_back</span>
                Kembali ke Login
            </a>
        </div>
    </div>
</x-guest-layout>
