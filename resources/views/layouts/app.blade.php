<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Icons Sidebar --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Style Fix -->
    <style>
        body {
            background-color: #f9fafb !important;
            font-family: 'Outfit', sans-serif;
        }

        /* ===== Dashboard Card Styling ===== */
        .dashboard-card {
            background-color: #ffffff !important;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .dashboard-card.green-border { border-left: 4px solid #8BC34A; }
        .dashboard-card.red-border { border-left: 4px solid #E57373; }
        .dashboard-card.blue-border { border-left: 4px solid #4FC3F7; }
        .dashboard-card.purple-border { border-left: 4px solid #BA68C8; }

        /* ===== Section Background ===== */
        .section-bg {
            background-color: #f0f4f8;
            border-radius: 10px;
            padding: 16px;
        }
    </style>
</head>

<body class="antialiased" x-data="{ sidebarOpen: false }" @sidebar-toggle.window="sidebarOpen = $event.detail">

    <div class="min-h-screen flex flex-col transition-all duration-300 ease-in-out">

        {{-- Navbar & Sidebar --}}
        @include('layouts.navigation')

        {{-- Konten utama --}}
        <div
            id="mainContent"
            class="flex-1 transition-all duration-300 ease-in-out px-4 sm:px-6 lg:px-8"
            :class="sidebarOpen ? 'ml-64' : 'ml-0'">

            @isset($header)
                <header class="bg-[#FAFAFA] border-b border-gray-200 mb-4 rounded-md mt-2">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="pt-2 pb-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
