<x-guest-layout>
    <!-- Font & Icon -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-dark: #0f172a;
            --emerald-pro: #10b981;
            --teal-deep: #065f46;
            --amber-gold: #f59e0b;
            --soft-mint: #f0fdf4;
        }

        body, html {
            margin: 0; padding: 0; height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }

        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* --- SISI KIRI: VISUAL BRANDING --- */
        .side-visual {
            flex: 1.3;
            position: relative;
            background-image: url('../assets/img/bmt.jpg');
            background-size: cover;
            background-position: center;
            display: none; /* Sembunyikan di Mobile */
        }

        @media (min-width: 992px) {
            .side-visual { display: block; }
        }

        .visual-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(6, 95, 70, 0.8) 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 60px;
        }

        /* Animasi Floating Ikon */
        .brand-icon {
            width: 60px; height: 60px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* --- SISI KANAN: FORM INTERAKTIF --- */
        .side-form {
            flex: 1;
            background: linear-gradient(180deg, var(--soft-mint) 0%, #ffffff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            animation: fadeInBlur 1s ease-out;
        }

        @keyframes fadeInBlur {
            from { opacity: 0; filter: blur(10px); transform: scale(0.95); }
            to { opacity: 1; filter: blur(0); transform: scale(1); }
        }

        .form-label {
            font-size: 13px; font-weight: 700; color: var(--teal-deep);
            margin-bottom: 8px; display: block; letter-spacing: 0.5px;
        }

        .input-group-custom {
            position: relative; margin-bottom: 20px;
        }

        .input-custom {
            width: 100%;
            padding: 14px 16px 14px 45px;
            border-radius: 16px;
            border: 2px solid #e2e8f0;
            background-color: rgba(255, 255, 255, 0.6);
            font-size: 15px; font-weight: 500;
            transition: all 0.3s ease;
        }

        .input-group-custom i {
            position: absolute; left: 16px; top: 50%;
            transform: translateY(-50%); color: #94a3b8;
            transition: color 0.3s;
        }

        .input-custom:focus {
            outline: none; border-color: var(--emerald-pro);
            background-color: #fff;
            box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.15);
        }

        .input-custom:focus + i { color: var(--emerald-pro); }

        /* Tombol Gradiasi Interaktif */
        .btn-gradient {
            width: 100%; padding: 16px;
            background: linear-gradient(135deg, var(--emerald-pro) 0%, var(--teal-deep) 100%);
            color: white; border: none; border-radius: 16px;
            font-weight: 700; font-size: 16px; cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 10px 20px -5px rgba(6, 95, 70, 0.4);
        }

        .btn-gradient:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 20px 30px -10px rgba(6, 95, 70, 0.5);
            filter: brightness(1.1);
        }

        .btn-google {
            width: 100%; padding: 14px; background: white;
            border: 2px solid #e2e8f0; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; color: var(--primary-dark);
            transition: 0.3s;
        }

        .btn-google:hover { background: #f8fafc; border-color: #cbd5e1; }

        .divider {
            text-align: center; margin: 30px 0; color: #94a3b8;
            font-size: 12px; font-weight: 600; text-transform: uppercase;
            display: flex; align-items: center;
        }

        .divider::before, .divider::after {
            content: ""; flex: 1; height: 1px; background: #e2e8f0;
        }
        .divider span { padding: 0 15px; }

        .signup-section {
            background: var(--primary-dark);
            padding: 15px; border-radius: 20px;
            text-align: center; margin-top: 40px;
        }
    </style>

    <main class="main-content">
        <div class="login-wrapper">
            
            <!-- SEKSI KIRI: VISUAL (DIBUAT LEBIH ELEGAN) -->
            <div class="side-visual">
                <div class="visual-overlay">
                    <div class="brand-icon">
                        <i class="fas fa-leaf fa-2x text-white"></i>
                    </div>
                    
                    <div class="content-text">
                        <h1 class="text-white font-weight-bolder display-4 mb-3" style="line-height: 1.1;">
                            Masa Depan <br> <span style="color: var(--emerald-pro);">Farm Management</span>
                        </h1>
                        <p class="text-white opacity-8 text-lg" style="max-width: 450px;">
                            Pantau laporan keuangan, aset BMT Hasanah, dan perkembangan ternak secara real-time dengan teknologi cerdas.
                        </p>
                    </div>

                    <div class="footer-note">
                        <p class="text-white text-xs opacity-5 mb-0">© 2026 Hasanah Farm Digital Ecosystem.</p>
                    </div>
                </div>
            </div>

            <!-- SEKSI KANAN: FORM (WARNA INTERAKTIF) -->
            <div class="side-form">
                <div class="login-card">
                    <div class="mb-5">
                        <h2 class="fw-bold text-dark mb-2">Welcome Back!</h2>
                        <p class="text-secondary">Silakan masuk ke akun Anda untuk melanjutkan.</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success text-sm py-3 px-4 border-0 mb-4" style="background: var(--soft-mint); color: var(--teal-deep); border-radius: 12px;">
                            <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
                        </div>
                    @endif

                    <form role="form" method="POST" action="sign-in">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <div class="input-group-custom">
                                <input type="email" name="email" class="input-custom" 
                                    placeholder="your@email.com" value="{{ old('email') }}" required autofocus>
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <div class="input-group-custom">
                                <input type="password" name="password" class="input-custom" 
                                    placeholder="••••••••" required>
                                <i class="fas fa-lock"></i>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check p-0 m-0 d-flex align-items-center">
                                <input class="form-check-input m-0" type="checkbox" id="remember" style="width: 20px; height: 20px; border-radius: 6px; cursor: pointer;">
                                <label class="ms-2 text-sm fw-600 text-secondary" for="remember" style="cursor: pointer;">Ingat saya</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-sm fw-bold" style="color: var(--emerald-pro); text-decoration: none;">Lupa sandi?</a>
                        </div>

                        <button type="submit" class="btn-gradient">
                            LOGIN KE DASHBOARD <i class="fas fa-arrow-right ms-2"></i>
                        </button>

                        <div class="divider">
                            <span>Atau masuk dengan</span>
                        </div>

                        <button type="button" class="btn-google">
                            <img src="../assets/img/logos/google-logo.svg" alt="google" style="width: 20px; margin-right: 12px;">
                            Akun Google
                        </button>
                    </form>

                    <div class="signup-section shadow-sm">
                        <p class="text-white text-sm mb-0">
                            Belum punya akses? 
                            <a href="{{ route('sign-up') }}" class="fw-bold ms-1" style="color: var(--emerald-pro); text-decoration: none;">
                                Daftar Akun Baru
                            </a>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </main>
</x-guest-layout>