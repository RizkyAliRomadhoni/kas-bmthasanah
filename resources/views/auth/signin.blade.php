<x-guest-layout>
    <!-- Import Font Profesional -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body, .main-content {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            margin: 0;
            padding: 0;
            background-color: #0f172a; /* Warna dasar gelap jika gambar gagal muat */
        }

        /* Hero Background */
        .auth-wrapper {
            background-image: url('../assets/img/bmt.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 20px;
        }

        /* Luxury Overlay */
        .auth-wrapper::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.6) 100%);
            z-index: 1;
        }

        /* Floating Modern Card */
        .auth-card {
            background: rgba(255, 255, 255, 0.98);
            width: 100%;
            max-width: 450px;
            padding: 50px 40px;
            border-radius: 30px;
            z-index: 10;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }

        /* Typography */
        .auth-title {
            font-weight: 800;
            font-size: 32px;
            color: #1e293b;
            letter-spacing: -1.5px;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            color: #64748b;
            font-size: 15px;
            margin-bottom: 35px;
        }

        /* Custom Form Input */
        .form-label {
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-left: 4px;
        }

        .form-control {
            border-radius: 14px !important;
            padding: 14px 18px !important;
            border: 2px solid #f1f5f9 !important;
            background-color: #f8fafc !important;
            font-size: 15px !important;
            transition: all 0.2s ease !important;
        }

        .form-control:focus {
            border-color: #059669 !important;
            background-color: #fff !important;
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1) !important;
        }

        /* Buttons */
        .btn-signin {
            background: #1e293b;
            color: white;
            padding: 14px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 16px;
            width: 100%;
            border: none;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 15px -3px rgba(30, 41, 59, 0.3);
        }

        .btn-signin:hover {
            background: #0f172a;
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(30, 41, 59, 0.4);
        }

        .btn-google {
            background: white;
            border: 2px solid #f1f5f9;
            padding: 12px;
            border-radius: 16px;
            font-weight: 600;
            color: #334155;
            width: 100%;
            margin-top: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }

        .btn-google:hover {
            background: #f8fafc;
            border-color: #e2e8f0;
        }

        /* Branding Footer */
        .branding-text {
            position: absolute;
            bottom: 40px;
            z-index: 10;
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
            font-size: 13px;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
            color: #cbd5e1;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 2px solid #f1f5f9;
        }

        .divider:not(:empty)::before { margin-right: 15px; }
        .divider:not(:empty)::after { margin-left: 15px; }

    </style>

    <main class="main-content">
        <div class="auth-wrapper">
            
            <div class="auth-card">
                <!-- Brand Identity -->
                <div class="text-center">
                    <div class="mb-4 d-inline-block">
                        <i class="fas fa-seedling fa-2x" style="color: #059669;"></i>
                    </div>
                    <h2 class="auth-title">Welcome Back</h2>
                    <p class="auth-subtitle">Sistem Keuangan BMT Hasanah</p>
                </div>

                <!-- Alert Section -->
                @if (session('status'))
                    <div class="alert alert-success text-xs border-radius-lg mb-4">{{ session('status') }}</div>
                @endif
                @error('message')
                    <div class="alert alert-danger text-white text-xs border-radius-lg mb-4">{{ $message }}</div>
                @enderror

                <!-- Login Form (Fungsi dipertahankan 100%) -->
                <form role="form" method="POST" action="sign-in">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" 
                            value="{{ old('email') ? old('email') : 'admin@corporateui.com' }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" 
                            value="{{ old('password') ? old('password') : 'secret' }}" required>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-4 mt-2">
                        <div class="form-check m-0 p-0 d-flex align-items-center">
                            <input class="form-check-input m-0" type="checkbox" id="remember" style="width: 18px; height: 18px; border-radius: 6px;">
                            <label class="ms-2 text-sm text-secondary font-weight-bold" for="remember" style="cursor: pointer;">Ingat saya</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="text-xs font-weight-bolder text-primary" style="text-decoration: none; color: #059669 !important;">Lupa Password?</a>
                    </div>

                    <button type="submit" class="btn-signin">Masuk ke Dashboard</button>

                    <div class="divider">Atau masuk dengan</div>

                    <button type="button" class="btn-google shadow-sm">
                        <img class="w-5 me-3" src="../assets/img/logos/google-logo.svg" alt="google">
                        <span style="font-size: 14px;">Google Account</span>
                    </button>
                </form>

                <div class="text-center mt-5">
                    <p class="text-sm text-secondary mb-0">
                        Belum punya akun? 
                        <a href="{{ route('sign-up') }}" class="font-weight-bold" style="color: #059669; text-decoration: none;">Daftar Sekarang</a>
                    </p>
                </div>
            </div>

            <!-- Footer Branding -->
            <div class="branding-text">
                <p class="mb-1">© 2025 BMT Hasanah Management System</p>
                <p class="text-xxs opacity-5">Dikembangkan oleh Tim Magang Umpo TI 2025</p>
            </div>
        </div>
    </main>
</x-guest-layout>