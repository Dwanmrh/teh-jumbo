<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teh Solo</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            background-color: #fff;
        }

        /* Navbar */
        .navbar {
            background-color: #F9C960;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 40px;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-left img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
        }

        .navbar-left span {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .navbar-right a {
            text-decoration: none;
            color: #000;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .navbar-right svg {
            width: 18px;
            height: 18px;
        }

        /* Hero Section */
        .hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 80px 100px;
        }

        .hero-text {
            max-width: 600px;
        }

        .hero-text h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .hero-text p {
            font-size: 22px;
            color: #555;
            font-weight: 500;
        }

        /* Right Image */
        .hero-image {
            position: relative;
            width: 480px;
            height: 480px;
        }

        .orange-bg {
            background-color: #F38C00;
            width: 480px;
            height: 480px;
            border-radius: 60% 40% 60% 40%;
            position: absolute;
            top: 0;
            right: 0;
            z-index: 1;
        }

        .hero-image img {
            position: absolute;
            top: 100px;
            left: 90px;
            width: 300px;
            z-index: 2;
        }

        @media (max-width: 900px) {
            .hero {
                flex-direction: column;
                text-align: center;
                padding: 40px 20px;
            }

            .hero-image {
                margin-top: 40px;
            }

            .hero-image img {
                left: 50%;
                transform: translateX(-50%);
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-left">
            <img src="{{ asset('assets/images/logo_teh.png') }}" alt="Logo">
            <span>Teh Solo de Jumbo Fibonacci</span>
        </div>
        <div class="navbar-right">
            <a href="{{ route('login') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A4 4 0 017 17h10a4 4 0 011.879.804M12 12a4 4 0 100-8 4 4 0 000 8z" />
                </svg>
                Login
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-text">
            <h1>Teh Solo de Jumbo Fibonacci</h1>
            <p>Hilangkan haus anda dengan minuman produk kami!</p>
        </div>

        <div class="hero-image">
            <div class="orange-bg"></div>
            <img src="{{ asset('assets/images/tehJumbo.png') }}" alt="Teh Solo">
        </div>
    </section>

</body>
</html>
