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

        .signup-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* --- SISI KIRI: VISUAL BRANDING (DENGAN GAMBAR KANTOR) --- */
        .side-visual {
            flex: 1.2;
            position: relative;
            background-image: url('../assets/img/kantorbmthas.jpg');
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

        .image-content {
            animation: slideInLeft 1s ease-out;
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* --- SISI KANAN: BACKGROUND INTERAKTIF & FORM --- */
        .side-form {
            flex: 1;
            position: relative;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            overflow: hidden;
        }

        /* Background Blobs */
        .bg-blobs {
            position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: 0; background: #f8fafc;
        }
        .blob { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.4; z-index: 1; animation: move 20s infinite alternate; }
        .blob-1 { width: 400px; height: 400px; background: #dcfce7; top: -100px; right: -100px; }
        .blob-2 { width: 350px; height: 350px; background: #f0fdf4; bottom: -50px; left: -100px; animation-delay: -5s; }

        @keyframes move {
            from { transform: translate(0, 0) rotate(0deg); }
            to { transform: translate(50px, 100px) rotate(90deg); }
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 40px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            position: relative;
            z-index: 10;
            animation: fadeInBlur 0.8s ease-out;
        }

        @keyframes fadeInBlur {
            from { opacity: 0; transform: translateY(20px); filter: blur(5px); }
            to { opacity: 1; transform: translateY(0); filter: blur(0); }
        }

        /* Form Elements */
        .form-label { font-size: 13px; font-weight: 700; color: var(--primary-dark); margin-bottom: 8px; display: block; }
        .input-group-custom { position: relative; margin-bottom: 15px; }
        .input-custom {
            width: 100%; padding: 12px 16px 12px 45px; border-radius: 16px;
            border: 2px solid #e2e8f0; background: white; font-size: 15px;
            transition: all 0.3s ease;
        }
        .input-group-custom i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .input-custom:focus { outline: none; border-color: var(--emerald-pro); box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
        .input-custom:focus + i { color: var(--emerald-pro); }

        .btn-gradient {
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, var(--emerald-pro) 0%, var(--teal-deep) 100%);
            color: white; border: none; border-radius: 16px; font-weight: 700; font-size: 16px;
            cursor: pointer; transition: 0.3s;
            box-shadow: 0 10px 20px -5px rgba(6, 95, 70, 0.4);
        }
        .btn-gradient:hover { transform: translateY(-2px); filter: brightness(1.1); }

        .divider { text-align: center; margin: 20px 0; color: #94a3b8; font-size: 12px; display: flex; align-items: center; }
        .divider::before, .divider::after { content: ""; flex: 1; height: 1px; background: #e2e8f0; }
        .divider span { padding: 0 15px; }

        .signin-link { text-align: center; margin-top: 25px; font-size: 14px; color: #64748b; }
        .signin-link a { color: var(--emerald-pro); font-weight: 700; text-decoration: none; }
    </style>

    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <x-guest.sidenav-guest />
            </div>
        </div>
    </div>

    <main class="main-content">
        <div class="signup-wrapper">
            
            <!-- SEKSI KIRI: VISUAL (KANTOR BMT) -->
            <div class="side-visual">
                <div class="visual-overlay">
                    <div class="image-content">
                        <div class="mb-4">
                            <i class="fas fa-landmark fa-3x text-white"></i>
                        </div>
                        <h1 class="text-white font-weight-bolder display-4 mb-3" style="line-height: 1.1;">
                            Bergabung dengan <br> <span style="color: var(--emerald-pro);">BMT Hasanah</span>
                        </h1>
                        <p class="text-white opacity-8 text-lg" style="max-width: 450px;">
                            Kelola aset dan keuangan dengan transparansi tinggi. Solusi cerdas untuk masa depan ekonomi umat.
                        </p>
                    </div>
                    <p class="text-white text-xs opacity-5 mb-0">© 2026 BMT Hasanah Jabung • Ponorogo.</p>
                </div>
            </div>

            <!-- SEKSI KANAN: FORM DENGAN BLOB BACKGROUND -->
            <div class="side-form">
                <div class="bg-blobs">
                    <div class="blob blob-1"></div>
                    <div class="blob blob-2"></div>
                </div>

                <div class="auth-card">
                    <div class="mb-4 text-start">
                        <h2 class="fw-bold text-dark mb-1">Sign Up</h2>
                        <p class="text-secondary text-sm">Daftarkan akun baru Anda di sini.</p>
                    </div>

                    <form role="form" method="POST" action="sign-up">
                        @csrf
                        
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <div class="input-group-custom">
                                <input type="text" name="name" id="name" class="input-custom" 
                                    placeholder="Nama Lengkap" value="{{ old('name') }}" required autofocus>
                                <i class="fas fa-user"></i>
                            </div>
                            @error('name') <p class="text-danger text-xxs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <div class="input-group-custom">
                                <input type="email" name="email" id="email" class="input-custom" 
                                    placeholder="nama@email.com" value="{{ old('email') }}" required>
                                <i class="fas fa-envelope"></i>
                            </div>
                            @error('email') <p class="text-danger text-xxs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <div class="input-group-custom">
                                <input type="password" name="password" id="password" class="input-custom" 
                                    placeholder="Minimal 8 karakter" required>
                                <i class="fas fa-lock"></i>
                            </div>
                            @error('password') <p class="text-danger text-xxs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-check p-0 m-0 d-flex align-items-center mb-4">
                            <input class="form-check-input m-0" type="checkbox" name="terms" id="terms" style="width: 18px; height: 18px; cursor: pointer;" required>
                            <label class="ms-2 text-xs text-secondary font-weight-bold" for="terms" style="cursor: pointer;">
                                Saya setuju dengan <a href="#" class="text-emerald-pro">Syarat & Ketentuan</a>
                            </label>
                        </div>

                        <button type="submit" class="btn-gradient">
                            DAFTAR SEKARANG
                        </button>

                        <div class="divider">
                            <span>Atau daftar dengan</span>
                        </div>

                        <button type="button" class="btn btn-outline-secondary w-100 py-2 border-radius-lg bg-white d-flex align-items-center justify-content-center border-2" style="border-radius: 16px;">
                            <img src="../assets/img/logos/google-logo.svg" alt="google" style="width: 18px; margin-right: 10px;">
                            <span class="text-xs font-weight-bold">Google Account</span>
                        </button>
                    </form>

                    <div class="signin-link">
                        Sudah punya akun? <a href="{{ route('sign-in') }}">Sign In</a>
                    </div>
                </div>
            </div>

        </div>
    </main>
</x-guest-layout>