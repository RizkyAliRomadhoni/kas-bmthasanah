<x-guest-layout>
    <!-- Google Font: Plus Jakarta Sans (Font Standar Dashboard Mahal) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-emerald: #059669;
            --dark-emerald: #064e3b;
            --soft-mint: #f0fdf4;
            --slate-dark: #0f172a;
        }

        body, .main-content {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            margin: 0; padding: 0;
            /* Background Interaktif: Gradasi Lembut */
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }

        /* Dekorasi Lingkaran blur di background agar terlihat mewah */
        .bg-decoration {
            position: absolute;
            width: 500px; height: 500px;
            background: rgba(5, 150, 105, 0.1);
            filter: blur(100px);
            border-radius: 50%;
            z-index: 0;
        }

        /* Floating Card Mode */
        .auth-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            width: 100%;
            max-width: 440px;
            padding: 50px 40px;
            border-radius: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            z-index: 10;
            animation: cardAppear 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardAppear {
            from { opacity: 0; transform: scale(0.9) translateY(30px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .auth-title {
            font-weight: 800;
            font-size: 34px;
            color: var(--slate-dark);
            letter-spacing: -1.5px;
            margin-bottom: 10px;
        }

        /* Input Styling */
        .form-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--dark-emerald);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-left: 5px;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 25px;
        }

        .input-wrapper i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-emerald);
            transition: 0.3s;
            font-size: 18px;
        }

        .form-control {
            border-radius: 20px !important;
            padding: 16px 20px 16px 55px !important;
            border: 2px solid transparent !important;
            background-color: white !important;
            font-size: 15px !important;
            font-weight: 500;
            color: var(--slate-dark);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
            transition: all 0.3s ease !important;
        }

        .form-control:focus {
            border-color: var(--primary-emerald) !important;
            background-color: #fff !important;
            box-shadow: 0 10px 20px -5px rgba(5, 150, 105, 0.2) !important;
            transform: translateY(-2px);
        }

        .form-control:focus + i {
            color: var(--dark-emerald);
            transform: translateY(-50%) scale(1.1);
        }

        /* Tombol Premium */
        .btn-premium {
            background: linear-gradient(135deg, var(--primary-emerald) 0%, var(--dark-emerald) 100%);
            color: white;
            padding: 18px;
            border-radius: 22px;
            font-weight: 700;
            font-size: 16px;
            width: 100%;
            border: none;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-premium:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 20px 35px -10px rgba(5, 150, 105, 0.5);
            filter: brightness(1.1);
        }

        .btn-google {
            background: white;
            border: 1px solid #e2e8f0;
            padding: 14px;
            border-radius: 20px;
            font-weight: 600;
            color: var(--slate-dark);
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: 0.3s;
            margin-top: 20px;
        }

        .btn-google:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
        }

        .divider {
            text-align: center;
            margin: 35px 0 20px 0;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
        }

        .divider::before, .divider::after {
            content: ""; flex: 1; height: 1px; background: #e2e8f0;
        }
        .divider span { padding: 0 15px; }

        .link-emerald {
            color: var(--primary-emerald);
            text-decoration: none;
            font-weight: 700;
            transition: 0.2s;
        }

        .link-emerald:hover { color: var(--dark-emerald); text-decoration: underline; }

        .footer-text {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #64748b;
        }
    </style>

    <!-- Elemen Dekorasi (Lingkaran Blur) -->
    <div class="bg-decoration" style="top: -100px; left: -100px;"></div>
    <div class="bg-decoration" style="bottom: -100px; right: -100px; background: rgba(16, 185, 129, 0.1);"></div>

    <main class="main-content">
        <div class="auth-card">
            <div class="text-center mb-5">
                <div class="d-inline-flex p-3 rounded-pill mb-3" style="background: rgba(5, 150, 105, 0.1);">
                    <i class="fas fa-leaf text-success fa-xl"></i>
                </div>
                <h2 class="auth-title">Welcome Back!</h2>
                <p class="text-secondary" style="font-size: 15px;">Silakan masuk ke akun Anda untuk melanjutkan.</p>
            </div>

            <!-- Form Start -->
            <form role="form" method="POST" action="sign-in">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" id="email" class="form-control" 
                            placeholder="yourname@email.com" value="{{ old('email') }}" required autofocus>
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" class="form-control" 
                            placeholder="••••••••" required>
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check p-0 m-0 d-flex align-items-center">
                        <input class="form-check-input m-0" type="checkbox" id="remember" style="width: 18px; height: 18px; cursor: pointer; border-radius: 5px;">
                        <label class="ms-2 text-sm text-secondary font-weight-semibold" for="remember" style="cursor: pointer;">Ingat saya</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="link-emerald text-sm">Lupa sandi?</a>
                </div>

                <button type="submit" class="btn-premium">
                    LOGIN KE DASHBOARD <i class="fas fa-arrow-right"></i>
                </button>

                <div class="divider">
                    <span>ATAU MASUK DENGAN</span>
                </div>

                <button type="button" class="btn-google">
                    <img src="../assets/img/logos/google-logo.svg" alt="google" style="width: 20px;">
                    Akun Google
                </button>
            </form>

            <div class="footer-text">
                Belum punya akses? <a href="{{ route('sign-up') }}" class="link-emerald">Daftar Akun Baru</a>
            </div>
        </div>
    </main>

</x-guest-layout>