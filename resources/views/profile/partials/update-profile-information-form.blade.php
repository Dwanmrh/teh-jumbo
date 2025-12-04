<section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Kolom Kiri: Deskripsi --}}
    <header class="lg:col-span-1">
        <div class="flex items-center gap-3 mb-2">
            <span class="material-symbols-rounded text-brand-500 bg-brand-50 p-2 rounded-lg">id_card</span>
            <h2 class="text-lg font-bold text-stone-800">
                {{ __('Informasi Profil') }}
            </h2>
        </div>
        <p class="text-sm text-stone-500 leading-relaxed">
            {{ __('Perbarui detail informasi akun dan alamat email profil Anda.') }}
        </p>
    </header>

    {{-- Kolom Kanan: Form --}}
    <form method="post" action="{{ route('profile.update') }}" class="lg:col-span-2 space-y-5">
        @csrf
        @method('patch')

        {{-- Input Nama --}}
        <div>
            <label for="name" class="block text-sm font-semibold text-stone-700 mb-1">Nama Lengkap</label>
            <input id="name" name="name" type="text"
                class="w-full rounded-xl border-stone-200 bg-stone-50 text-stone-800 focus:bg-white focus:border-brand-500 focus:ring-brand-500 transition-colors shadow-sm py-2.5"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Input Email --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-stone-700 mb-1">Alamat Email</label>
            <input id="email" name="email" type="email"
                class="w-full rounded-xl border-stone-200 bg-stone-50 text-stone-800 focus:bg-white focus:border-brand-500 focus:ring-brand-500 transition-colors shadow-sm py-2.5"
                value="{{ old('email', $user->email) }}" required autocomplete="username">
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-amber-50 rounded-xl text-amber-800 text-sm flex items-start gap-2">
                    <span class="material-symbols-rounded text-base mt-0.5">warning</span>
                    <div>
                        <p>
                            {{ __('Alamat email Anda belum diverifikasi.') }}
                            <button form="send-verification" class="underline hover:text-amber-900 font-medium">
                                {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                            </button>
                        </p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                class="inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 text-white font-semibold rounded-xl shadow-lg shadow-brand-500/30 transition-all transform active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 text-sm tracking-wide">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-brand-600 font-medium flex items-center gap-1">
                    <span class="material-symbols-rounded text-lg">check_circle</span>
                    {{ __('Tersimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>
