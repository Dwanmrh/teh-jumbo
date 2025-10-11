<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | Teh Solo de Jumbo Fibonacci</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(180deg, #F5A623, #F38C00);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .forgot-card {
            background: #fff;
            width: 360px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            padding: 40px 30px 30px;
            text-align: center; /* ✅ Samakan gaya dengan login/register */
        }

        .logo {
            display: block;
            margin: 20px auto 10px;
            width: 70px;
            height: 70px;
            border-radius: 50%;
        }

        h2 {
            font-weight: 600;
            font-size: 16px;
            color: #222;
            margin: 6px 0 4px;
        }

        h3 {
            font-size: 22px;
            font-weight: 700;
            color: #F38C00;
            margin-bottom: 18px;
            letter-spacing: 0.5px;
        }

        p {
            font-size: 13px;
            color: #444;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-family: inherit;
            font-size: 14px;
            background: #fafafa;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .send-btn {
            width: 100%;
            background-color: #F38C00; /* ✅ Warna sama seperti login/register */
            color: #fff;
            border: none;
            padding: 10px 0;
            font-size: 15px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
            font-weight: 600;
        }

        .send-btn:hover {
            background-color: #F5A623;
        }

        .links {
            text-align: center;
            font-size: 13px;
            margin-top: 14px;
        }

        .links a {
            color: #0A2E57;
            text-decoration: none;
            font-weight: 500;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .status {
            color: green;
            font-size: 13px;
            margin-top: 10px;
            text-align: center;
        }

        .error {
            color: red;
            font-size: 13px;
            margin-top: 8px;
            text-align: center;
        }

        @media (max-width: 400px) {
            .forgot-card {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-card">
        <img src="{{ asset('assets/images/logo_teh.png') }}" alt="Logo Teh Solo" class="logo">
        <h2>Teh Solo de Jumbo Fibonacci</h2>
        <h3>Forgot Password</h3>
        <p>Masukkan alamat email Anda dan kami akan mengirimkan link untuk reset password.</p>

        <!-- Session Status -->
        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="send-btn">Kirim Link Reset Password</button>

            <div class="links">
                <a href="{{ route('login') }}">Kembali ke Login</a>
            </div>
        </form>
    </div>
</body>
</html>
