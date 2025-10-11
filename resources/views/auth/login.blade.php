<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Teh Solo de Jumbo Fibonacci</title>
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

        .login-card {
            background: #fff;
            width: 360px;
            border-radius: 6px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            padding: 30px;
            position: relative;
        }

        .top-bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 26px;
            background: #f7f8fa;
            border-bottom: 1px solid #e0e0e0;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 8px;
            color: #333;
        }

        .top-bar a {
            color: #333;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .logo {
            display: block;
            margin: 40px auto 10px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }

        h2 {
            text-align: center;
            font-weight: 400;
            font-size: 14px;
            margin-bottom: 20px;
            color: #222;
        }

        h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: inherit;
            font-size: 14px;
            background: #fafafa;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .login-btn {
            width: 100%;
            background-color: #F38C00;
            color: #fff;
            border: none;
            padding: 10px 0;
            font-size: 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 8px;
        }

        .login-btn:hover {
            background-color: #F5A623;
        }

        .links {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-top: 12px;
        }

        .links a {
            color: #0A2E57;
            text-decoration: none;
            font-weight: 500;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            font-size: 13px;
            text-align: center;
            margin-top: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .error svg {
            width: 16px;
            height: 16px;
            fill: red;
        }

        @media (max-width: 400px) {
            .login-card {
                width: 90%;
            }
        }
    </style>
</head>
<body>

    <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Teh Solo de Jumbo Fibonacci</title>
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

        .login-card {
            background: #fff;
            width: 360px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            padding: 40px 30px 30px;
            position: relative;
            text-align: center; /* ✅ Semua teks dalam kartu jadi rata tengah */
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
            margin-bottom: 25px;
            letter-spacing: 0.5px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
            text-align: left;
        }

        input[type="email"], input[type="password"] {
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

        .login-btn {
            width: 100%;
            background-color: #F38C00;
            color: #fff;
            border: none;
            padding: 10px 0;
            font-size: 15px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
            font-weight: 600;
        }

        .login-btn:hover {
            background-color: #F5A623;
        }

        .links {
            display: flex;
            justify-content: space-between;
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

        .error {
            color: red;
            font-size: 13px;
            text-align: center;
            margin-top: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .error svg {
            width: 16px;
            height: 16px;
            fill: red;
        }

        @media (max-width: 400px) {
            .login-card {
                width: 90%;
            }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <img src="{{ asset('assets/images/logo_teh.png') }}" alt="Logo Teh Solo" class="logo">
        <h2>Teh Solo de Jumbo Fibonacci</h2>
        <h3>Login</h3>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="username@gmail.com" required autofocus>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Password" required>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="login-btn">Login</button>

            <div class="links">
                <a href="{{ route('register') }}">Register</a>
                <a href="{{ route('password.request') }}">Forgot Password?</a>
            </div>

            @if (session('error'))
                <div class="error">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M11.001 10h2v5h-2zm0 7h2v2h-2z"/>
                        <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10
                        10 10 10-4.486 10-10S17.514 2
                        12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8
                        8-8 8 3.589 8 8-3.589 8-8 8z"/>
                    </svg>
                    Gagal login, pastikan username dan password
                </div>
            @endif
        </form>
    </div>

</body>
</html>

</body>
</html>
