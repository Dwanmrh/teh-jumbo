<section>
    <header>
        <h3 class="text-lg font-medium text-gray-900" style="font-family: 'Outfit', sans-serif; color:#2F362C;">
            {{ __('Informasi Profil') }}
        </h3>

        <p class="mt-1 text-sm text-gray-600" style="font-family: 'Outfit', sans-serif;">
            {{ __('Perbarui informasi akun Anda seperti nama dan email.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
            <input id="name" name="name" type="text" class="mt-1 block w-full"
                value="{{ old('name', $user->name) }}"
                required autofocus autocomplete="name"
                style="border:1px solid #ccc; border-radius:5px; padding:8px 10px; font-family:'Outfit',sans-serif;">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" class="mt-1 block w-full"
                value="{{ old('email', $user->email) }}"
                required autocomplete="username"
                style="border:1px solid #ccc; border-radius:5px; padding:8px 10px; font-family:'Outfit',sans-serif;">
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-main" style="background-color:#7AC943; color:white; border:none; padding:8px 14px; border-radius:5px; font-family:'Outfit',sans-serif;">
                Simpan
            </button>
        </div>
    </form>
</section>
