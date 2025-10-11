<x-app-layout>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f7f7f7;
        }

        .card-custom {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.15);
            padding: 24px;
            border-left: 5px solid #7AC943; /* warna hijau khas dashboard */
        }

        h3 {
            color: #2F362C;
            font-weight: 600;
            margin-bottom: 16px;
        }

        label {
            color: #2F362C;
            font-weight: 500;
        }

        input, select {
            font-family: 'Outfit', sans-serif;
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 8px 10px;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #F5C04C;
            box-shadow: 0 0 0 2px rgba(245, 192, 76, 0.3);
        }

        .btn-main {
            background-color: #7AC943;
            color: white;
            font-weight: 600;
            border: none;
            padding: 8px 14px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .btn-main:hover {
            background-color: #6ab53b;
        }

        .btn-danger {
            background-color: #E74C3C;
            color: white;
            font-weight: 600;
            border: none;
            padding: 8px 14px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .section-title {
            background-color: #F5C04C;
            color: #2F362C;
            padding: 8px 14px;
            border-radius: 6px 6px 0 0;
            font-weight: 600;
        }

        .dark\:bg-gray-800 {
            background-color: #fff !important;
        }

        .dark\:text-gray-200 {
            color: #2F362C !important;
        }
    </style>

    <div class="py-12" style="background-color: #f7f7f7;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Informasi Profil --}}
            <div class="card-custom">
                <div class="section-title">Informasi Akun</div>
                <div class="mt-4">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Ubah Password --}}
            <div class="card-custom">
                <div class="section-title">Ubah Password</div>
                <div class="mt-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Hapus Akun --}}
            <div class="card-custom">
                <div class="section-title" style="background-color:#E74C3C; color:white;">Hapus Akun</div>
                <div class="mt-4">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
