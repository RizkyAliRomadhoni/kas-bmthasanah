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
            overflow: hidden;
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

        /* --- SISI KANAN: BACKGROUND INTERAKTIF --- */
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

        /* Elemen Interaktif (Animated Blobs) */
        .bg-blobs {
            position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: 0;
        }
        .blob {
            position: absolute; border-radius: 50%; filter: blur(70px); opacity: 0.5; z-index: 1;
            animation: move 20s infinite alternate;
        }
        .blob-1 { width: 400px; height: 400px; background: #dcfce7; top: -100px; right: -100px; }
        .blob-2 { width: 350px; height: 350px; background: #f0fdf4; bottom: -50px; left: -100px; animation-delay: -5s; }

        @keyframes move {
            from { transform: translate(0, 0); }
            to { transform: translate(40px, 80px); }
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.7);
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

        /* Form Styling */
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

        .divider { text-align: center; margin: 25px 0; color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase; display: flex; align-items: center; letter-spacing: 1px; }
        .divider::before, .divider::after { content: ""; flex: 1; height: 1px; background: #e2e8f0; }
        .divider span { padding: 0 15px; }

        .footer-lock { text-align: center; margin-top: 30px; font-size: 12px; color: #94a3b8; font-weight: 600; text-transform: uppercase; }
    </style>

    <main class="main-content">
        <div class="login-wrapper">
            
            <!-- SEKSI KIRI: VISUAL BRANDING -->
            <div class="side-visual">
                <div class="visual-overlay text-start">
                    <div>
                        <div class="d-inline-flex p-3 rounded-pill mb-4" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px);">
                            <i class="fas fa-leaf text-white fa-lg"></i>
                        </div>
                        <h1 class="text-white font-weight-bolder display-4 mb-3" style="line-height: 1.1;">
                            Hasanah Farm <br> <span style="color: var(--emerald-pro);">Management.</span>
                        </h1>
                        <p class="text-white opacity-8 text-lg" style="max-width: 450px;">
                            Sistem kendali terpadu untuk pengelolaan aset, neraca, dan keuangan BMT Hasanah Ponorogo.
                        </p>
                    </div>
                    <p class="text-white text-xs opacity-5 mb-0">© 2026 BMT HASANAH • Magang Umpo TI.</p>
                </div>
            </div>

            <!-- SEKSI KANAN: FORM LOGIN (BACKGROUND INTERAKTIF) -->
            <div class="side-form">
                <!-- Elemen Background Interaktif (Blobs) -->
                <div class="bg-blobs">
                    <div class="blob blob-1"></div>
                    <div class="blob blob-2"></div>
                </div>

                <div class="login-card">
                    <div class="mb-5 text-start">
                        <h2 class="fw-bold text-dark mb-1">Masuk</h2>
                        <p class="text-secondary text-sm">Gunakan akses administrator sistem.</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success text-xs py-2 px-3 mb-4" style="background: var(--soft-mint); border-radius: 8px;">{{ session('status') }}</div>
                    @endif

                    <!-- Form Login dengan Username -->
                    <form role="form" method="POST" action="sign-in">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <div class="input-group-custom">
                                <input type="text" name="username" class="input-custom" 
                                    placeholder="Input Username" value="{{ old('username') }}" required autofocus>
                                <i class="fas fa-user-shield"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <div class="input-group-custom">
                                <input type="password" name="password" class="input-custom" 
                                    placeholder="Input Password" required>
                                <i class="fas fa-key"></i>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                            <div class="form-check p-0 m-0 d-flex align-items-center">
                                <input class="form-check-input m-0" type="checkbox" id="remember" style="width: 18px; height: 18px; cursor: pointer;">
                                <label class="ms-2 text-xs font-weight-bold text-secondary" for="remember" style="cursor: pointer;">Ingat Saya</label>
                            </div>
                        </div>

                        <button type="submit" class="btn-gradient shadow-lg">
                            AUTHENTICATE SYSTEM
                        </button>

                        <div class="divider">
                            <span>BMT Hasanah Internal</span>
                        </div>

                        <div class="footer-lock">
                            <i class="fas fa-lock me-1"></i> Authorized Access Only
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <!-- Pastikan di Database Anda: 
         User: BMThasanah 
         Pass: webkelolakas 
    -->
</x-app-layout>