<section>
    <header>
        <h3 class="text-lg font-medium text-gray-900" style="font-family: 'Outfit', sans-serif; color:#2F362C;">
            {{ __('Ubah Password') }}
        </h3>

        <p class="mt-1 text-sm text-gray-600" style="font-family: 'Outfit', sans-serif;">
            {{ __('Pastikan password Anda aman dan mudah diingat.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700">Password Lama</label>
            <input id="current_password" name="current_password" type="password" class="mt-1 block w-full"
                autocomplete="current-password"
                style="border:1px solid #ccc; border-radius:5px; padding:8px 10px; font-family:'Outfit',sans-serif;">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
            <input id="password" name="password" type="password" class="mt-1 block w-full"
                autocomplete="new-password"
                style="border:1px solid #ccc; border-radius:5px; padding:8px 10px; font-family:'Outfit',sans-serif;">
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full"
                autocomplete="new-password"
                style="border:1px solid #ccc; border-radius:5px; padding:8px 10px; font-family:'Outfit',sans-serif;">
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-main" style="background-color:#7AC943; color:white; border:none; padding:8px 14px; border-radius:5px; font-family:'Outfit',sans-serif;">
                Simpan
            </button>
        </div>
    </form>
</section>
