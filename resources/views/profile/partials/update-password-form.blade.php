<section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Kolom Kiri --}}
    <header class="lg:col-span-1">
        <div class="flex items-center gap-3 mb-2">
            <span class="material-symbols-rounded text-brand-500 bg-brand-50 p-2 rounded-lg">lock</span>
            <h2 class="text-lg font-bold text-stone-800">
                {{ __('Ubah Password') }}
            </h2>
        </div>
        <p class="text-sm text-stone-500 leading-relaxed">
            {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    {{-- Kolom Kanan --}}
    <form method="post" action="{{ route('password.update') }}" class="lg:col-span-2 space-y-5">
        @csrf
        @method('put')

        {{-- Password Lama --}}
        <div>
            <label for="current_password" class="block text-sm font-semibold text-stone-700 mb-1">Password Saat Ini</label>
            <input id="current_password" name="current_password" type="password"
                class="w-full rounded-xl border-stone-200 bg-stone-50 text-stone-800 focus:bg-white focus:border-brand-500 focus:ring-brand-500 transition-colors shadow-sm py-2.5"
                autocomplete="current-password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        {{-- Password Baru --}}
        <div>
            <label for="update_password_password" class="block text-sm font-semibold text-stone-700 mb-1">Password Baru</label>
            <input id="update_password_password" name="password" type="password"
                class="w-full rounded-xl border-stone-200 bg-stone-50 text-stone-800 focus:bg-white focus:border-brand-500 focus:ring-brand-500 transition-colors shadow-sm py-2.5"
                autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        {{-- Konfirmasi Password --}}
        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-semibold text-stone-700 mb-1">Konfirmasi Password Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="w-full rounded-xl border-stone-200 bg-stone-50 text-stone-800 focus:bg-white focus:border-brand-500 focus:ring-brand-500 transition-colors shadow-sm py-2.5"
                autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Tombol --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                class="inline-flex items-center justify-center px-6 py-2.5 bg-stone-800 hover:bg-stone-900 text-white font-semibold rounded-xl shadow-lg shadow-stone-400/20 transition-all transform active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-stone-800 text-sm tracking-wide">
                {{ __('Perbarui Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-brand-600 font-medium flex items-center gap-1">
                    <span class="material-symbols-rounded text-lg">check_circle</span>
                    {{ __('Berhasil.') }}
                </p>
            @endif
        </div>
    </form>
</section>
