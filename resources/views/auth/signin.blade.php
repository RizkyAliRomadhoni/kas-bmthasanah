<x-guest-layout>
    <!-- Font & Icon -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #1e293b;
            --emerald: #10b981;
            --gold: #f59e0b;
        }

        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* --- SISI KIRI: IMAGE SECTION --- */
        .side-image {
            flex: 1.2;
            position: relative;
            background-image: url('../assets/img/bmt.jpg');
            background-size: cover;
            background-position: center;
            display: none; /* Sembunyikan di HP */
        }

        @media (min-width: 992px) {
            .side-image { display: block; }
        }

        .image-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(6, 78, 59, 0.8) 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 80px;
        }

        .image-content {
            animation: slideInLeft 1s ease-out;
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* --- SISI KANAN: FORM SECTION --- */
        .side-form {
            flex: 1;
            background-color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-card {
            width: 100%;
            max-width: 400px; /* Ukuran card dibuat ramping */
            background: #ffffff;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Input Styling */
        .form-group label {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
            display: block;
        }

        .input-custom {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-size: 15px;
            color: var(--primary);
            transition: 0.3s;
        }

        .input-custom:focus {
            outline: none;
            border-color: var(--emerald);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        /* Button Styling */
        .btn-main {
            width: 100%;
            padding: 14px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-main:hover {
            background-color: #0f172a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-google {
            width: 100%;
            padding: 12px;
            background: white;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            color: #475569;
            margin-top: 15px;
            transition: 0.2s;
        }

        .btn-google:hover { background-color: #f8fafc; }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .divider::before {
            content: ""; position: absolute; top: 50%; left: 0; width: 100%;
            height: 1px; background: #e2e8f0; z-index: 1;
        }

        .divider span {
            position: relative; z-index: 2; background: #fff; padding: 0 15px;
            font-size: 12px; color: #94a3b8; font-weight: 600;
        }

        .signup-link {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #64748b;
        }

        .signup-link a {
            color: var(--emerald);
            font-weight: 700;
            text-decoration: none;
            transition: 0.2s;
        }

        .signup-link a:hover { text-decoration: underline; }
    </style>

    <main class="main-content">
        <div class="login-wrapper">
            
            <!-- SISI KIRI (VISUAL) -->
            <div class="side-image">
                <div class="image-overlay text-start">
                    <div class="image-content">
                        <div class="mb-4">
                            <i class="fas fa-leaf fa-3x text-white"></i>
                        </div>
                        <h1 class="text-white font-weight-bolder display-4 mb-3">Hasanah Farm <br><span style="color: var(--emerald)">Management System.</span></h1>
                        <p class="text-white opacity-8 text-lg" style="max-width: 500px; line-height: 1.6;">
                            Kelola ekosistem peternakan Anda dengan presisi tinggi. Akurasi keuangan BMT Hasanah kini dalam satu dashboard terpadu.
                        </p>
                    </div>
                    <div class="position-absolute bottom-5">
                        <p class="text-white text-xs opacity-5 italic">© 2026 PT. Hasanah Farm Teknologi. All rights reserved.</p>
                    </div>
                </div>
            </div>

            <!-- SISI KANAN (FORM) -->
            <div class="side-form">
                <div class="login-card">
                    <div class="text-center mb-5">
                        <h3 class="fw-bold text-dark mb-2">Masuk ke Dashboard</h3>
                        <p class="text-secondary text-sm">Selamat datang kembali di sistem BMT.</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success text-xs py-2 px-3 mb-4" style="border-radius: 8px;">{{ session('status') }}</div>
                    @endif

                    <form role="form" method="POST" action="sign-in">
                        @csrf
                        <div class="form-group mb-4">
                            <label>Email Address</label>
                            <input type="email" name="email" class="input-custom" 
                                value="{{ old('email') ? old('email') : 'admin@corporateui.com' }}" required autofocus>
                        </div>

                        <div class="form-group mb-2">
                            <label>Password</label>
                            <input type="password" name="password" class="input-custom" 
                                value="{{ old('password') ? old('password') : 'secret' }}" required>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-4 mt-3">
                            <div class="form-check p-0 m-0 d-flex align-items-center">
                                <input class="form-check-input m-0" type="checkbox" id="remember" style="width: 18px; height: 18px;">
                                <label class="ms-2 text-xs font-weight-bold text-secondary" for="remember" style="cursor: pointer;">Ingat Saya</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-xs font-weight-bold" style="color: var(--emerald); text-decoration: none;">Lupa Password?</a>
                        </div>

                        <button type="submit" class="btn-main">LOGIN SEKARANG</button>

                        <div class="divider">
                            <span>Atau masuk dengan</span>
                        </div>

                        <button type="button" class="btn-google">
                            <img src="../assets/img/logos/google-logo.svg" alt="google" style="width: 18px; margin-right: 10px;">
                            Akun Google
                        </button>
                    </form>

                    <div class="signup-link">
                        Belum memiliki akses? <a href="{{ route('sign-up') }}">Daftar di sini</a>
                    </div>
                </div>
            </div>

        </div>
    </main>
</x-guest-layout>