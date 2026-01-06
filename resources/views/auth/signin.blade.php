<x-guest-layout>
    <!-- Font & Icon -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --primary: #0f172a;
            --emerald: #10b981;
            --mint-soft: #f0fdf4;
        }

        body, .main-content {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        /* --- Animasi Background --- */
        .bg-circle {
            position: absolute; width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, transparent 70%);
            border-radius: 50%; z-index: 0;
            animation: moveAround 15s infinite alternate;
        }

        @keyframes moveAround {
            from { transform: translate(-10%, -10%); }
            to { transform: translate(10%, 10%); }
        }

        /* --- Auth Card (Glassmorphism) --- */
        .auth-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 40px;
            padding: 60px 45px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
            z-index: 10;
            animation: slideUpFade 1s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(40px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .auth-logo {
            width: 70px; height: 70px;
            background: var(--primary);
            color: white; border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 25px;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
            animation: bounce 3s infinite ease-in-out;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        /* --- Form Elements --- */
        .form-label {
            font-size: 11px; font-weight: 800; color: #475569;
            text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 10px; display: block;
        }

        .input-pro {
            width: 100%; border-radius: 18px !important; padding: 16px 22px !important;
            border: 2px solid #e2e8f0 !important; background: rgba(255, 255, 255, 0.9) !important;
            font-size: 16px !important; font-weight: 600 !important; color: var(--primary);
            transition: all 0.3s ease !important;
        }

        .input-pro:focus {
            border-color: var(--emerald) !important;
            box-shadow: 0 0 0 5px rgba(16, 185, 129, 0.15) !important;
            transform: translateY(-2px);
            background: #fff !important;
        }

        /* --- Tombol Shimmer --- */
        .btn-shimmer {
            background: var(--primary);
            color: white; padding: 18px; border-radius: 18px;
            font-weight: 700; width: 100%; border: none;
            transition: all 0.4s ease; cursor: pointer;
            text-transform: uppercase; letter-spacing: 2px;
            position: relative; overflow: hidden;
            box-shadow: 0 10px 20px -5px rgba(15, 23, 42, 0.4);
        }

        .btn-shimmer:hover {
            background: #000; transform: translateY(-3px);
            box-shadow: 0 15px 30px -5px rgba(15, 23, 42, 0.5);
        }

        .btn-shimmer::after {
            content: ''; position: absolute; top: -50%; left: -60%;
            width: 20%; height: 200%; background: rgba(255,255,255,0.2);
            transform: rotate(30deg); transition: 0.5s;
        }

        .btn-shimmer:hover::after { left: 120%; }

        .footer-text { margin-top: 40px; color: #94a3b8; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
    </style>

    <div class="bg-circle" style="top: -10%; left: -10%;"></div>
    <div class="bg-circle" style="bottom: -10%; right: -10%; background: radial-gradient(circle, rgba(5, 150, 105, 0.2) 0%, transparent 70%);"></div>

    <main class="main-content">
        <div class="auth-card">
            <div class="auth-logo">
                <i class="fas fa-leaf fa-2x"></i>
            </div>
            
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark" style="letter-spacing: -1.5px; font-size: 32px;">BMT HASANAH</h2>
                <p class="text-secondary text-sm fw-medium">Farm Management Gateway</p>
            </div>

            <!-- Login Form (Action disesuaikan) -->
            <form role="form" method="POST" action="/login">
                @csrf
                <div class="mb-4">
                    <label class="form-label">Administrator ID</label>
                    <input type="text" name="username" class="form-control input-pro" 
                        placeholder="Enter Username" required autofocus autocomplete="off">
                </div>

                <div class="mb-4">
                    <label class="form-label">Access Password</label>
                    <input type="password" name="password" class="form-control input-pro" 
                        placeholder="••••••••" required>
                </div>

                <div class="d-flex align-items-center mb-4">
                    <input type="checkbox" id="remember" name="remember" style="width: 18px; height: 18px; accent-color: var(--emerald);">
                    <label class="ms-2 text-xs font-weight-bold text-secondary mb-0" for="remember" style="cursor:pointer;">Tetap Masuk</label>
                </div>

                <button type="submit" class="btn-shimmer">
                    Authenticate
                </button>
            </form>

            <div class="text-center footer-text">
                &copy; 2026 Powered by Magang Umpo TI
            </div>
        </div>
    </main>
</x-app-layout>