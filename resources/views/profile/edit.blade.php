<x-app-layout>
    <div class="space-y-8">

        {{-- Header Halaman --}}
        <div class="relative overflow-hidden bg-white rounded-3xl p-6 sm:p-10 shadow-soft border border-stone-100">
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-stone-800 tracking-tight">
                        Pengaturan Akun
                    </h2>
                    <p class="mt-1 text-stone-500 text-sm sm:text-base">
                        Kelola informasi profil, keamanan, dan privasi akun Anda.
                    </p>
                </div>
                <div class="h-12 w-12 bg-brand-50 rounded-2xl flex items-center justify-center text-brand-600">
                    <span class="material-symbols-rounded text-3xl">manage_accounts</span>
                </div>
            </div>
            {{-- Dekorasi Latar Belakang --}}
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-brand-500/10 rounded-full blur-3xl"></div>
        </div>

        {{-- Grid Layout untuk Form --}}
        <div class="space-y-6 sm:space-y-8">

            {{-- 1. Update Profile Info --}}
            <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-soft border border-stone-100">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- 2. Update Password --}}
            <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-soft border border-stone-100">
                @include('profile.partials.update-password-form')
            </div>

            {{-- 3. Delete Account --}}
            <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-soft border border-red-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-red-500"></div>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
