<x-guest-layout>
    {{-- Card Container --}}
    <div class="w-full max-w-[1000px] bg-white rounded-3xl shadow-2xl shadow-stone-200/50 overflow-hidden flex flex-col md:flex-row min-h-[600px] border border-stone-100">

        {{-- BAGIAN KIRI (Visual & Branding) --}}
        <div class="w-full md:w-5/12 bg-gradient-to-br from-brand-600 to-brand-700 relative overflow-hidden flex flex-col justify-center items-center text-center p-8 md:p-12 shrink-0">
            {{-- Dekorasi Pattern Halus --}}
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-black/10 rounded-full blur-3xl"></div>

            {{-- Konten Branding --}}
            <div class="relative z-10">
                <div class="w-20 h-20 md:w-24 md:h-24 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg border border-white/20 rotate-3 hover:rotate-6 transition-transform duration-500">
                    <img src="{{ asset('assets/images/logo-teh.png') }}" alt="Logo" class="w-12 h-12 md:w-16 md:h-16 object-contain -rotate-3">
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-2 tracking-tight">Selamat Datang</h2>
                <p class="text-brand-100 text-sm md:text-base leading-relaxed max-w-[250px] mx-auto">
                    Masuk ke dashboard admin untuk mengelola Teh Solo de Jumbo Fibonacci.
                </p>
            </div>

            <div class="absolute bottom-6 text-brand-200 text-xs font-medium tracking-widest uppercase opacity-60">
                Excellence in every cup
            </div>
        </div>

        {{-- BAGIAN KANAN (Form Login) --}}
        <div class="w-full md:w-7/12 p-6 sm:p-10 md:p-14 flex flex-col justify-center bg-white relative">

            <div class="max-w-md mx-auto w-full space-y-8">
                <div>
                    <h3 class="text-2xl font-bold text-stone-800">Login Akun</h3>
                    <p class="text-stone-500 text-sm mt-1">Silakan masukkan kredensial Anda.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-1">
                        <label for="email" class="text-xs font-bold text-stone-600 uppercase tracking-wider pl-1">Email</label>
                        <div class="relative group">
                            <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 group-focus-within:text-brand-500 transition-colors">mail</span>
                            <input id="email" class="block w-full pl-11 pr-4 py-3.5 rounded-xl border-stone-200 bg-stone-50 focus:bg-white focus:border-brand-500 focus:ring-brand-500 text-sm font-medium transition-all placeholder-stone-400"
                                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="alamat@email.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div x-data="{ show: false }" class="space-y-1">
                        <div class="flex justify-between items-center pl-1">
                            <label for="password" class="text-xs font-bold text-stone-600 uppercase tracking-wider">Password</label>
                        </div>
                        <div class="relative group">
                            <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 group-focus-within:text-brand-500 transition-colors">lock</span>
                            <input id="password" class="block w-full pl-11 pr-12 py-3.5 rounded-xl border-stone-200 bg-stone-50 focus:bg-white focus:border-brand-500 focus:ring-brand-500 text-sm font-medium transition-all placeholder-stone-400"
                                :type="show ? 'text' : 'password'"
                                name="password" required autocomplete="current-password" placeholder="••••••••" />
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 hover:text-brand-600 transition-colors focus:outline-none">
                                <span class="material-symbols-rounded text-xl" x-text="show ? 'visibility_off' : 'visibility'"></span>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-stone-300 text-brand-600 shadow-sm focus:ring-brand-500" name="remember">
                            <span class="ms-2 text-sm text-stone-600">{{ __('Ingat Saya') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-sm font-bold text-brand-600 hover:text-brand-700 hover:underline" href="{{ route('password.request') }}">
                                {{ __('Lupa Password?') }}
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="w-full py-4 rounded-xl text-white font-bold text-sm uppercase tracking-widest bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 shadow-xl shadow-brand-500/30 hover:shadow-brand-500/40 transition-all transform hover:-translate-y-0.5 active:scale-95">
                        {{ __('Log in') }}
                    </button>

                </form>

                <div class="pt-2 text-center text-sm text-stone-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold text-stone-800 hover:text-brand-600 transition-colors">Daftar disini</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
