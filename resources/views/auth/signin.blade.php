<x-guest-layout>
    <!-- Font & Icon -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-dark: #0f172a;
            --emerald-pro: #10b981;
            --teal-deep: #065f46;
            --soft-mint: #f0fdf4;
        }

        body, html {
            margin: 0; padding: 0; height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
        }

        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* --- SISI KIRI: VISUAL BRANDING (TETAP) --- */
        .side-visual {
            flex: 1.3;
            position: relative;
            background-image: url('../assets/img/bmt.jpg');
            background-size: cover;
            background-position: center;
            display: none;
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

        /* --- SISI KANAN: BACKGROUND INTERAKTIF (BARU) --- */
        .side-form {
            flex: 1;
            position: relative;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            overflow: hidden; /* Penting agar blob tidak keluar */
        }

        /* Background Dinamis (Lingkaran Cahaya Samur) */
        .bg-blobs {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
            background: #f8fafc;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            z-index: 1;
            animation: move 20s infinite alternate;
        }

        .blob-1 { width: 400px; height: 400px; background: #dcfce7; top: -100px; right: -100px; }
        .blob-2 { width: 350px; height: 350px; background: #f0fdf4; bottom: -50px; left: -100px; animation-delay: -5s; }
        .blob-3 { width: 300px; height: 300px; background: #ccfbf1; top: 40%; left: 30%; animation-delay: -10s; }

        @keyframes move {
            from { transform: translate(0, 0) rotate(0deg); }
            to { transform: translate(50px, 100px) rotate(90deg); }
        }

        /* Login Card dibuat Glassmorphism agar background terlihat tembus */
        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            position: relative;
            z-index: 10;
            animation: fadeInBlur 0.8s ease-out;
        }

        @keyframes fadeInBlur {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Form Elements */
        .form-label { font-size: 13px; font-weight: 700; color: var(--primary-dark); margin-bottom: 8px; display: block; }

        .input-group-custom { position: relative; margin-bottom: 20px; }
        .input-custom {
            width: 100%; padding: 14px 16px 14px 45px; border-radius: 16px;
            border: 2px solid #e2e8f0; background: white; font-size: 15px;
            transition: all 0.3s ease;
        }

        .input-group-custom i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .input-custom:focus { outline: none; border-color: var(--emerald-pro); box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
        .input-custom:focus + i { color: var(--emerald-pro); }

        .btn-gradient {
            width: 100%; padding: 16px;
            background: linear-gradient(135deg, var(--emerald-pro) 0%, var(--teal-deep) 100%);
            color: white; border: none; border-radius: 16px; font-weight: 700; font-size: 16px;
            cursor: pointer; transition: 0.3s;
            box-shadow: 0 10px 20px -5px rgba(6, 95, 70, 0.4);
        }

        .btn-gradient:hover { transform: translateY(-2px); filter: brightness(1.1); }

        .btn-google {
            width: 100%; padding: 14px; background: white; border: 2px solid #e2e8f0;
            border-radius: 16px; display: flex; align-items: center; justify-content: center;
            font-weight: 600; color: var(--primary-dark); transition: 0.2s;
        }

        .divider { text-align: center; margin: 25px 0; color: #94a3b8; font-size: 12px; display: flex; align-items: center; }
        .divider::before, .divider::after { content: ""; flex: 1; height: 1px; background: #e2e8f0; }
        .divider span { padding: 0 15px; }

        .signup-link { text-align: center; margin-top: 30px; font-size: 14px; color: #64748b; }
        .signup-link a { color: var(--emerald-pro); font-weight: 700; text-decoration: none; }
    </style>

    <main class="main-content">
        <div class="login-wrapper">
            
            <!-- SEKSI KIRI: VISUAL BRANDING -->
            <div class="side-visual">
                <div class="visual-overlay text-start">
                    <div>
                        <i class="fas fa-leaf fa-3x text-white mb-4"></i>
                        <h1 class="text-white font-weight-bolder display-4 mb-3" style="line-height: 1.1;">
                            Manajemen <br> <span style="color: var(--emerald-pro);">Farm Profesional</span>
                        </h1>
                        <p class="text-white opacity-8 text-lg" style="max-width: 450px;">
                            Satu platform untuk semua kebutuhan BMT Hasanah. Kelola keuangan, stok, dan aset dengan akurasi tinggi.
                        </p>
                    </div>
                    <p class="text-white text-xs opacity-5 mb-0">© 2026 Hasanah Farm Management.</p>
                </div>
            </div>

            <!-- SEKSI KANAN: FORM DENGAN BACKGROUND INTERAKTIF -->
            <div class="side-form">
                <!-- Elemen Background Interaktif -->
                <div class="bg-blobs">
                    <div class="blob blob-1"></div>
                    <div class="blob blob-2"></div>
                    <div class="blob blob-3"></div>
                </div>

                <!-- Card Login -->
                <div class="login-card shadow-lg">
                    <div class="mb-5 text-start">
                        <h2 class="fw-bold text-dark mb-1">Masuk</h2>
                        <p class="text-secondary text-sm">Gunakan email dan password terdaftar.</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success text-xs py-2 px-3 mb-4" style="background: var(--soft-mint); border-radius: 8px;">{{ session('status') }}</div>
                    @endif

                    <form role="form" method="POST" action="sign-in">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <div class="input-group-custom">
                                <input type="email" name="email" class="input-custom" 
                                    placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
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

                        <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                            <div class="form-check p-0 m-0 d-flex align-items-center">
                                <input class="form-check-input m-0" type="checkbox" id="remember" style="width: 18px; height: 18px; cursor: pointer;">
                                <label class="ms-2 text-xs font-weight-bold text-secondary" for="remember" style="cursor: pointer;">Ingat saya</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-xs font-weight-bold" style="color: var(--emerald-pro); text-decoration: none;">Lupa sandi?</a>
                        </div>

                        <button type="submit" class="btn-gradient">
                            LOGIN SEKARANG
                        </button>

                        <div class="divider">
                            <span>Atau gunakan</span>
                        </div>

                        <button type="button" class="btn-google">
                            <img src="../assets/img/logos/google-logo.svg" alt="google" style="width: 18px; margin-right: 12px;">
                            Akun Google
                        </button>
                    </form>

                    <div class="signup-link">
                        Belum memiliki akses? <a href="{{ route('sign-up') }}">Hubungi Admin</a>
                    </div>
                </div>
            </div>

        </div>
    </main>
</x-guest-layout>