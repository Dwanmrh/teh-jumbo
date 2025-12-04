<section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Kolom Kiri --}}
    <header class="lg:col-span-1">
        <div class="flex items-center gap-3 mb-2">
            <span class="material-symbols-rounded text-red-500 bg-red-50 p-2 rounded-lg">warning</span>
            <h2 class="text-lg font-bold text-red-600">
                {{ __('Hapus Akun') }}
            </h2>
        </div>
        <p class="text-sm text-stone-500 leading-relaxed">
            {{ __('Setelah akun dihapus, semua data dan sumber daya akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.') }}
        </p>
    </header>

    {{-- Kolom Kanan --}}
    <div class="lg:col-span-2 space-y-5">
        <div class="p-4 bg-red-50 rounded-xl border border-red-100 text-red-800 text-sm">
            Apakah Anda yakin ingin menghapus akun ini?
        </div>

        <button x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center justify-center px-6 py-2.5 bg-white border-2 border-red-100 text-red-600 font-bold rounded-xl hover:bg-red-50 hover:border-red-200 transition-all transform active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-sm tracking-wide shadow-sm">
            {{ __('Hapus Akun Saya') }}
        </button>

        {{-- Modal Konfirmasi --}}
        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8">
                @csrf
                @method('delete')

                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                        <span class="material-symbols-rounded">priority_high</span>
                    </div>
                    <h2 class="text-xl font-bold text-stone-900">
                        {{ __('Konfirmasi Hapus Akun') }}
                    </h2>
                </div>

                <p class="mt-1 text-sm text-stone-500">
                    {{ __('Setelah akun dihapus, semua data akan hilang permanen. Silakan masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
                </p>

                <div class="mt-6">
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password"
                        class="w-full rounded-xl border-stone-200 bg-stone-50 focus:bg-white focus:border-red-500 focus:ring-red-500 transition-colors py-2.5"
                        placeholder="Masukkan Password Anda"
                    />
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close')"
                        class="px-4 py-2 bg-white border border-stone-200 text-stone-700 rounded-lg hover:bg-stone-50 font-medium transition text-sm">
                        {{ __('Batal') }}
                    </button>

                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-bold shadow-lg shadow-red-500/30 transition text-sm">
                        {{ __('Ya, Hapus Akun') }}
                    </button>
                </div>
            </form>
        </x-modal>
    </div>
</section>
