<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | Teh Solo de Jumbo Fibonacci</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    colors: {
                        brand: { 500: '#F5A623', 600: '#F38C00', 700: '#D67600', dark: '#0A2E57' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-stone-50 flex items-center justify-center min-h-screen p-4 relative overflow-hidden">

    {{-- Background Decoration --}}
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none -z-10">
        <div class="absolute top-[-10%] right-[-5%] w-[400px] h-[400px] bg-brand-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[400px] h-[400px] bg-brand-600/10 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-[420px] bg-white rounded-3xl shadow-xl shadow-stone-200/50 p-8 sm:p-10 text-center relative border border-stone-100">

        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-brand-500 to-brand-600 rounded-2xl rotate-3 flex items-center justify-center mx-auto mb-6 shadow-lg shadow-brand-500/30">
            <i class="fa-solid fa-key text-white text-2xl sm:text-3xl -rotate-3"></i>
        </div>

        <h2 class="text-2xl font-bold text-stone-800">Lupa Password?</h2>
        <p class="text-stone-500 text-sm mt-3 mb-8 leading-relaxed px-2">
            Jangan khawatir. Masukkan email Anda dan kami akan mengirimkan instruksi reset password.
        </p>

        @if (session('status'))
            <div class="mb-6 p-3 rounded-2xl bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100 flex items-center gap-2 justify-center">
                <i class="fa-solid fa-check-circle"></i> {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="text-left space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1 pl-3">Email Terdaftar</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-5 py-3 rounded-full bg-stone-50 border border-stone-200 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 outline-none transition-all text-sm font-medium placeholder-stone-400"
                    placeholder="nama@email.com" required autofocus>
                @error('email') <p class="text-red-500 text-xs mt-1 pl-3">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full py-3.5 rounded-full text-white font-bold text-sm uppercase tracking-wider bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50 transition-all transform hover:-translate-y-0.5">
                Kirim Link Reset
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-stone-100">
            <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-bold text-stone-400 hover:text-brand-600 transition-colors group">
                <i class="fa-solid fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Login
            </a>
        </div>
    </div>
</body>
</html>
