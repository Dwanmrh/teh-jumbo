<x-guest-layout>
    {{-- Card Container (Reverse flex di Desktop agar Visual di Kanan) --}}
    <div class="w-full max-w-[1000px] bg-white rounded-3xl shadow-2xl shadow-stone-200/50 overflow-hidden flex flex-col md:flex-row-reverse min-h-[600px] border border-stone-100">

        {{-- BAGIAN KANAN (Visual & Branding) --}}
        <div class="w-full md:w-5/12 bg-stone-900 relative overflow-hidden flex flex-col justify-center items-center text-center p-8 md:p-12 shrink-0">
            {{-- Background Image/Gradient --}}
            <div class="absolute inset-0 bg-gradient-to-br from-stone-800 to-stone-900 opacity-90 z-0"></div>
            {{-- Aksen Orange --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-brand-500/20 rounded-full blur-3xl z-0"></div>

            <div class="relative z-10">
                <div class="w-20 h-20 md:w-24 md:h-24 bg-white/5 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg border border-white/10 -rotate-3">
                    <img src="{{ asset('assets/images/logo-teh.png') }}" alt="Logo" class="w-12 h-12 md:w-16 md:h-16 object-contain rotate-3">
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-2 tracking-tight">Mulai Sekarang</h2>
                <p class="text-stone-300 text-sm md:text-base leading-relaxed max-w-[250px] mx-auto">
                    Bergabung dengan sistem manajemen Teh Solo. Cepat, Mudah, dan Aman.
                </p>
            </div>
        </div>

        {{-- BAGIAN KIRI (Form Register) --}}
        <div class="w-full md:w-7/12 p-6 sm:p-10 md:p-14 flex flex-col justify-center bg-white">

            <div class="max-w-md mx-auto w-full space-y-6">
                <div>
                    <h3 class="text-2xl font-bold text-stone-800">Buat Akun Baru</h3>
                    <p class="text-stone-500 text-sm mt-1">Lengkapi data diri Anda di bawah ini.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div class="space-y-1">
                        <label for="name" class="text-xs font-bold text-stone-600 uppercase tracking-wider pl-1">Nama Lengkap</label>
                        <div class="relative group">
                            <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 group-focus-within:text-stone-800 transition-colors">person</span>
                            <input id="name" class="block w-full pl-11 pr-4 py-3.5 rounded-xl border-stone-200 bg-stone-50 focus:bg-white focus:border-stone-800 focus:ring-stone-800 text-sm font-medium transition-all placeholder-stone-400"
                                type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama Lengkap" />
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div class="space-y-1">
                        <label for="email" class="text-xs font-bold text-stone-600 uppercase tracking-wider pl-1">Email</label>
                        <div class="relative group">
                            <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 group-focus-within:text-stone-800 transition-colors">mail</span>
                            <input id="email" class="block w-full pl-11 pr-4 py-3.5 rounded-xl border-stone-200 bg-stone-50 focus:bg-white focus:border-stone-800 focus:ring-stone-800 text-sm font-medium transition-all placeholder-stone-400"
                                type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="alamat@email.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-data="{ show: false }">
                        <div class="space-y-1">
                            <label for="password" class="text-xs font-bold text-stone-600 uppercase tracking-wider pl-1">Password</label>
                            <div class="relative">
                                <input id="password" class="block w-full px-4 py-3.5 rounded-xl border-stone-200 bg-stone-50 focus:bg-white focus:border-stone-800 focus:ring-stone-800 text-sm font-medium transition-all placeholder-stone-400"
                                    :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password" placeholder="••••••••" />
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label for="password_confirmation" class="text-xs font-bold text-stone-600 uppercase tracking-wider pl-1">Konfirmasi</label>
                            <div class="relative">
                                <input id="password_confirmation" class="block w-full px-4 py-3.5 rounded-xl border-stone-200 bg-stone-50 focus:bg-white focus:border-stone-800 focus:ring-stone-800 text-sm font-medium transition-all placeholder-stone-400"
                                    :type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />

                                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-800 transition-colors focus:outline-none sm:hidden">
                                    <span class="material-symbols-rounded" x-text="show ? 'visibility_off' : 'visibility'"></span>
                                </button>
                            </div>
                        </div>
                        {{-- Toggle Show Password Desktop (Di Luar Grid) --}}
                         <div class="sm:col-span-2 text-right">
                             <button type="button" @click="show = !show" class="text-xs font-bold text-stone-400 hover:text-stone-600 hidden sm:inline-block">
                                <span x-text="show ? 'Sembunyikan Password' : 'Lihat Password'"></span>
                            </button>
                         </div>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />


                    <button type="submit" class="w-full py-4 rounded-xl text-white font-bold text-sm uppercase tracking-widest bg-stone-800 hover:bg-stone-900 shadow-xl shadow-stone-400/20 hover:shadow-stone-500/30 transition-all transform hover:-translate-y-0.5 active:scale-95">
                        {{ __('Daftar Sekarang') }}
                    </button>

                </form>

                <div class="pt-2 text-center text-sm text-stone-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-bold text-brand-600 hover:text-brand-700 transition-colors">Login disini</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
