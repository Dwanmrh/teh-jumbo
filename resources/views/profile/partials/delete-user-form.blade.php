<section>
    <header>
        <h3 class="text-lg font-medium text-gray-900" style="font-family: 'Outfit', sans-serif; color:#E74C3C;">
            {{ __('Hapus Akun') }}
        </h3>

        <p class="mt-1 text-sm text-gray-600" style="font-family: 'Outfit', sans-serif;">
            {{ __('Setelah akun Anda dihapus, semua data akan dihapus secara permanen. Harap pastikan sebelum melanjutkan.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" class="mt-6 space-y-6">
        @csrf
        @method('delete')

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
            <input id="password" name="password" type="password" class="mt-1 block w-full"
                placeholder="Masukkan password Anda"
                required autocomplete="current-password"
                style="border:1px solid #ccc; border-radius:5px; padding:8px 10px; font-family:'Outfit',sans-serif;">
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-danger" style="background-color:#E74C3C; color:white; border:none; padding:8px 14px; border-radius:5px; font-family:'Outfit',sans-serif;">
                Hapus Akun
            </button>
        </div>
    </form>
</section>
