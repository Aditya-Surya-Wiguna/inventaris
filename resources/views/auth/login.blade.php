<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* ===============================
           BACKGROUND (FULL RESPONSIVE)
        =============================== */
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background: url("{{ asset('images/background.jpg') }}") center center / cover no-repeat fixed;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(10, 30, 30, 0.55);
            backdrop-filter: blur(6px);
            z-index: 0;
        }

        /* ===============================
           LOGIN CARD
        =============================== */
        .login-card {
            width: 380px;
            padding: 40px 30px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.9);
            text-align: center;
            color: #fff;
            position: relative;
            z-index: 1;
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===============================
           LOGO UIN (lebih besar + outline putih)
        =============================== */
        .login-card img {
            width: 180px;
            margin-bottom: 10px;
            /* filter: drop-shadow(0 0 3px #fff)
                    drop-shadow(0 0 6px rgba(255, 255, 255, 0.9))
                    drop-shadow(0 0 10px rgba(0, 0, 0, 0.6)); */
        }

        /* ===============================
           PEMBATAS (GARIS)
        =============================== */
        .divider {
            width: 80%;
            height: 1.5px;
            background: rgba(255, 255, 255, 0.7);
            margin: 20px auto 25px auto;
            border-radius: 5px;
        }

        /* ===============================
           TEXT & INPUTS
        =============================== */
        .login-card h4 {
            font-weight: 600;
            margin-bottom: 25px;
            color: #fff;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: #fff;
            border-radius: 30px;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.3);
        }

        /* ===============================
           BUTTON
        =============================== */
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #0e4e57, #13889a);
            color: #fff;
            border: none;
            border-radius: 30px;
            font-weight: 600;
            padding: 12px;
            transition: 0.3s;
        }

        .btn-login:hover {
            transform: scale(1.03);
            box-shadow: 0 0 15px rgba(14, 78, 87, 0.6);
        }

        /* ===============================
           FOOTER TEXT
        =============================== */
        .remember {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 10px;
        }

        .alert {
            border-radius: 10px;
            padding: 8px;
        }

        /* ===============================
           RESPONSIVE
        =============================== */
        @media (max-width: 480px) {
            .login-card {
                width: 90%;
                padding: 30px 20px;
            }
            .login-card img {
                width: 140px;
            }
            .divider {
                width: 90%;
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
        {{-- 🟢 LOGO UIN BESAR DENGAN OUTLINE PUTIH --}}
        <img src="{{ asset('images/logo-uin.png') }}" alt="Logo UIN Raden Fatah">

        {{-- 🔹 PEMBATAS GARIS --}}
        <div class=""></div>

        <h4>Login</h4>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <p class="remember">Hanya Untuk Login Admin</p>

            <button class="btn-login" type="submit">Login</button>
        </form>
    </div>
</body>
</html>
