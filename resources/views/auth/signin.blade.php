<x-app-layout>
    <!-- Import Font Premium (Plus Jakarta Sans) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* Reset & Base */
        body, .main-content {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            background-color: #ffffff;
            margin: 0;
            overflow-x: hidden;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
        }

        /* --- Sisi Kiri: Visual & Branding --- */
        .side-visual {
            flex: 1;
            position: relative;
            display: none; /* Sembunyikan di Mobile */
            background-image: url('../assets/img/bmt.jpg');
            background-size: cover;
            background-position: center;
        }

        @media (min-width: 992px) {
            .side-visual { display: block; }
        }

        .visual-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(5, 44, 36, 0.2), rgba(5, 44, 36, 0.85));
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand-logo {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -1px;
            display: flex;
            align-items: center;
        }

        .brand-logo i {
            color: #10B981;
            margin-right: 10px;
        }

        .quote-box h1 {
            color: #fff;
            font-size: 42px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        /* --- Sisi Kanan: Form Login --- */
        .side-form {
            width: 100%;
            max-width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background-color: #fff;
        }

        @media (min-width: 992px) {
            .side-form { width: 450px; flex: none; }
        }

        .form-wrapper {
            width: 100%;
            max-width: 360px;
        }

        .form-header h2 {
            font-weight: 800;
            font-size: 32px;
            color: #0F172A;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .form-header p {
            color: #64748B;
            font-size: 15px;
            margin-bottom: 35px;
        }

        /* Minimalist Input */
        .custom-label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            display: block;
        }

        .custom-input {
            width: 100%;
            background-color: #F8FAFC !important;
            border: 1.5px solid #F1F5F9 !important;
            border-radius: 12px !important;
            padding: 14px 18px !important;
            font-size: 15px !important;
            transition: all 0.3s ease;
            color: #0F172A;
        }

        .custom-input:focus {
            background-color: #fff !important;
            border-color: #10B981 !important;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1) !important;
            outline: none;
        }

        .btn-login {
            background-color: #0F172A;
            color: white;
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            border: none;
            margin-top: 20px;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .btn-login:hover {
            background-color: #1e293b;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.15);
        }

        /* Google Button */
        .btn-google {
            width: 100%;
            background: #fff;
            border: 1.5px solid #F1F5F9;
            padding: 12px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #475569;
            font-size: 14px;
            margin-top: 15px;
            transition: 0.2s;
        }

        .btn-google:hover {
            background-color: #f8fafc;
            border-color: #E2E8F0;
        }

        .footer-text {
            margin-top: 40px;
            font-size: 14px;
            text-align: center;
            color: #64748B;
        }

        .footer-text a {
            color: #10B981;
            text-decoration: none;
            font-weight: 700;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 25px 0;
            color: #CBD5E1;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #F1F5F9;
        }

        .divider:not(:empty)::before { margin-right: 15px; }
        .divider:not(:empty)::after { margin-left: 15px; }
    </style>

    <div class="login-container">
        <!-- SEKSI KIRI: Visual -->
        <div class="side-visual">
            <div class="visual-overlay">
                <div class="brand-logo">
                    <i class="fas fa-leaf"></i> Hasanah Farm
                </div>
                
                <div class="quote-box">
                    <h1>Membangun Ekosistem <br> <span style="color: #10B981;">Farm Terpercaya.</span></h1>
                    <p class="text-white opacity-8 text-lg" style="max-width: 500px;">
                        Kelola data, neraca keuangan, dan perkembangan peternakan Anda dalam satu platform yang cerdas dan efisien.
                    </p>
                </div>

                <div class="footer-copyright">
                    <p class="text-white text-xs opacity-5 mb-0">© 2026 PT. Hasanah Farm Teknologi Berkelanjutan</p>
                </div>
            </div>
        </div>

        <!-- SEKSI KANAN: Form -->
        <div class="side-form">
            <div class="form-wrapper">
                <div class="form-header text-center text-lg-start">
                    <h2>Masuk</h2>
                    <p>Selamat datang! Masukkan detail akun Anda.</p>
                </div>

                <form role="form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="custom-label">Email Address</label>
                        <input type="email" name="email" class="custom-input" placeholder="contoh@mail.com" required>
                    </div>

                    <div class="mb-2">
                        <label class="custom-label">Password</label>
                        <input type="password" name="password" class="custom-input" placeholder="••••••••" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="form-check p-0 m-0 d-flex align-items-center">
                            <input class="form-check-input m-0" style="width: 18px; height: 18px; border-radius: 6px;" type="checkbox" id="rememberMe">
                            <label class="ms-2 text-xs font-weight-bold text-secondary" for="rememberMe" style="cursor: pointer;">Ingat saya</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="text-xs font-weight-bold" style="color: #10B981; text-decoration: none;">Lupa Password?</a>
                    </div>

                    <button type="submit" class="btn-login">Login ke Dashboard</button>

                    <div class="divider">Atau masuk dengan</div>

                    <button type="button" class="btn-google">
                        <img src="../assets/img/logos/google-logo.svg" alt="G" style="width: 18px; margin-right: 12px;">
                        Akun Google
                    </button>
                </form>

                <div class="footer-text">
                    Belum punya akses? <a href="#">Hubungi Admin</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>