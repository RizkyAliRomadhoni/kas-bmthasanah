<x-guest-layout>
    <!-- Font Premium -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --primary-dark: #06121a;
            --farm-green: #10b981;
            --gold-accent: #fbbf24;
            --glass: rgba(255, 255, 255, 0.9);
        }

        body, .main-content {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            background-color: var(--primary-dark);
            overflow: hidden;
        }

        /* --- Animasi Background --- */
        .bg-animate {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url('../assets/img/bmt.jpg');
            background-size: cover;
            background-position: center;
            animation: zoomInfinite 20s infinite alternate;
            z-index: -1;
        }

        @keyframes zoomInfinite {
            from { transform: scale(1); }
            to { transform: scale(1.1); }
        }

        .overlay-premium {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at center, rgba(6, 18, 26, 0.7) 0%, rgba(6, 18, 26, 0.95) 100%);
            z-index: 0;
        }

        /* --- Card Animasi --- */
        .auth-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 35px;
            padding: 50px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            z-index: 10;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- Typography --- */
        .gradient-text {
            background: linear-gradient(135deg, #1e293b 0%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        /* --- Input Styling --- */
        .form-label {
            font-size: 11px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 8px;
            display: block;
        }

        .custom-input {
            background: rgba(241, 245, 249, 0.5) !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 16px !important;
            padding: 14px 20px !important;
            font-size: 15px !important;
            transition: all 0.3s ease !important;
        }

        .custom-input:focus {
            background: #fff !important;
            border-color: var(--farm-green) !important;
            box-shadow: 0 0 0 5px rgba(16, 185, 129, 0.15) !important;
            transform: scale(1.01);
        }

        /* --- Button "Mahal" --- */
        .btn-premium {
            position: relative;
            background: #1e293b;
            color: white;
            padding: 16px;
            border-radius: 18px;
            font-weight: 700;
            font-size: 15px;
            width: 100%;
            border: none;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 10px 20px -5px rgba(30, 41, 59, 0.4);
        }

        .btn-premium::before {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        .btn-premium:hover::before {
            left: 100%;
        }

        .btn-premium:hover {
            background: #0f172a;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -5px rgba(30, 41, 59, 0.6);
        }

        .btn-google {
            background: #fff;
            border: 1.5px solid #e2e8f0;
            padding: 12px;
            border-radius: 18px;
            font-weight: 600;
            color: #334155;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            font-size: 14px;
        }

        .btn-google:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 35px 0;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .divider::before, .divider::after {
            content: ''; flex: 1; border-bottom: 1px solid #e2e8f0;
        }

        .divider:not(:empty)::before { margin-right: 15px; }
        .divider:not(:empty)::after { margin-left: 15px; }

        .footer-brand {
            position: absolute;
            bottom: 30px;
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            letter-spacing: 1px;
            text-align: center;
            width: 100%;
            z-index: 5;
        }
    </style>

    <main class="main-content">
        <div class="position-relative min-vh-100 d-flex align-items-center justify-content-center px-3">
            <!-- Background & Overlay -->
            <div class="bg-animate"></div>
            <div class="overlay-premium"></div>
            
            <!-- Auth Card -->
            <div class="auth-card">
                <div class="text-center mb-5">
                    <div class="bg-gradient-success d-inline-flex p-3 rounded-pill shadow-lg mb-3" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-leaf text-white fa-lg"></i>
                    </div>
                    <h2 class="gradient-text mb-1" style="font-size: 34px; letter-spacing: -1.5px;">BMT Hasanah</h2>
                    <p class="text-secondary fw-medium" style="font-size: 14px;">Professional Farm Management System</p>
                </div>

                <!-- Notifikasi / Error -->
                @if (session('status'))
                    <div class="alert alert-success py-2 px-3 text-white border-radius-lg mb-4 text-xs" style="background:#10b981; border:none;">{{ session('status') }}</div>
                @endif
                @error('message')
                    <div class="alert alert-danger py-2 px-3 text-white border-radius-lg mb-4 text-xs" style="background:#ef4444; border:none;">{{ $message }}</div>
                @enderror

                <!-- Form Login -->
                <form role="form" method="POST" action="sign-in">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control custom-input" 
                            value="{{ old('email') ? old('email') : 'admin@corporateui.com' }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control custom-input" 
                            value="{{ old('password') ? old('password') : 'secret' }}" required>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-4 mt-3">
                        <div class="form-check m-0 p-0 d-flex align-items-center">
                            <input class="form-check-input m-0" type="checkbox" id="remember" style="width: 18px; height: 18px; cursor: pointer; border-radius: 6px;">
                            <label class="ms-2 text-sm text-slate-600 fw-bold" for="remember" style="cursor: pointer; color:#64748b;">Ingat saya</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="text-xs fw-bold" style="color: var(--farm-green); text-decoration: none;">Lupa sandi?</a>
                    </div>

                    <button type="submit" class="btn-premium">
                        MASUK KE DASHBOARD
                    </button>

                    <div class="divider">Atau gunakan</div>

                    <button type="button" class="btn-google shadow-sm">
                        <img class="w-5 me-3" src="../assets/img/logos/google-logo.svg" alt="google">
                        <span>Lanjutkan dengan Google</span>
                    </button>
                </form>

                <div class="text-center mt-5">
                    <p class="text-sm text-secondary mb-0">
                        Belum memiliki akses? 
                        <a href="{{ route('sign-up') }}" class="fw-bold" style="color: var(--farm-green); text-decoration: none;">Hubungi Admin</a>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer-brand">
                <p class="mb-0">© 2026 HASANAH FARM TECH • MAGANG UMPO TI</p>
            </div>
        </div>
    </main>
</x-guest-layout>