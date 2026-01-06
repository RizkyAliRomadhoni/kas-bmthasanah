<x-guest-layout>
    <style>
        /* Modern Typography */
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');

        :root {
            --primary-dark: #1e293b;
            --emerald-farm: #10b981;
            /* Warna background kiri (Mint ke Putih) */
            --bg-gradient-left: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
        }

        body, .main-content {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            background-color: #ffffff;
        }

        /* Styling Input agar "Pop Out" di background mint */
        .custom-input {
            border-radius: 12px !important;
            border: 2px solid #e2e8f0 !important;
            background-color: #ffffff !important;
            padding: 12px 15px !important;
            transition: all 0.3s ease;
        }

        .custom-input:focus {
            border-color: var(--emerald-farm) !important;
            box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.1) !important;
            transform: translateY(-2px);
        }

        .btn-emerald {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 12px;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .btn-emerald:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4);
            filter: brightness(1.1);
        }
    </style>

    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <x-guest.sidenav-guest />
            </div>
        </div>
    </div>

    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container-fluid p-0">
                    <div class="row g-0">
                        
                        <!-- SEKSI KIRI: FORM DENGAN BACKGROUND MINT INTERAKTIF -->
                        <div class="col-lg-5 col-md-12 d-flex flex-column justify-content-center min-vh-100 px-5" 
                             style="background: var(--bg-gradient-left); position: relative; overflow: hidden;">
                            
                            <!-- Dekorasi halus di background agar tidak sepi -->
                            <div style="position: absolute; top: -50px; left: -50px; width: 200px; height: 200px; background: rgba(16, 185, 129, 0.05); border-radius: 50%;"></div>

                            <div class="mx-auto" style="max-width: 420px; width: 100%; z-index: 2;">
                                <div class="text-center text-lg-start mb-4">
                                    <h2 class="font-weight-bolder text-dark mb-1" style="letter-spacing: -1px;">Welcome Back!</h2>
                                    <p class="text-secondary">Masuk ke sistem pengelolaan <b>Hasanah Farm</b>.</p>
                                </div>

                                <div class="card card-plain bg-transparent shadow-none border-0">
                                    <div class="card-body p-0">
                                        <!-- Form asli Anda (Fungsi tetap sama) -->
                                        <form role="form" class="text-start" method="POST" action="sign-in">
                                            @csrf
                                            
                                            <label class="text-xs font-weight-bold text-uppercase text-secondary ms-1">Email Address</label>
                                            <div class="mb-3">
                                                <input type="email" id="email" name="email" class="form-control custom-input"
                                                    placeholder="Enter your email"
                                                    value="{{ old('email') ? old('email') : 'admin@corporateui.com' }}" required autofocus>
                                            </div>

                                            <label class="text-xs font-weight-bold text-uppercase text-secondary ms-1">Password</label>
                                            <div class="mb-3">
                                                <input type="password" id="password" name="password"
                                                    value="{{ old('password') ? old('password') : 'secret' }}"
                                                    class="form-control custom-input" placeholder="Enter password" required>
                                            </div>

                                            <div class="d-flex align-items-center mb-4">
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" id="rememberMe" checked>
                                                    <label class="form-check-label text-xs text-secondary mb-0 ms-2" for="rememberMe">Remember me</label>
                                                </div>
                                                <a href="{{ route('password.request') }}" class="text-xs font-weight-bold text-primary ms-auto">Forgot password?</a>
                                            </div>

                                            <div class="text-center">
                                                <button type="submit" class="btn btn-emerald text-white w-100 py-3 shadow-lg mb-3">
                                                    SIGN IN
                                                </button>
                                                
                                                <div class="position-relative my-4">
                                                    <hr class="text-secondary opacity-2">
                                                    <span class="position-absolute top-50 start-50 translate-middle px-3 text-xxs text-secondary font-weight-bold" style="background: #f8fcf9;">ATAU</span>
                                                </div>

                                                <button type="button" class="btn btn-outline-secondary w-100 py-2 border-radius-lg bg-white d-flex align-items-center justify-content-center">
                                                    <img class="w-5 me-2" src="../assets/img/logos/google-logo.svg" alt="google">
                                                    <span class="text-xs font-weight-bold text-dark">Google Account</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <div class="card-footer text-center pt-4 px-0">
                                        <p class="text-sm text-secondary">
                                            Belum punya akun? 
                                            <a href="{{ route('sign-up') }}" class="text-success font-weight-bold">Sign Up Sekarang</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KANAN: TETAP GAMBAR SEPERTI AWAL -->
                        <div class="col-md-7 d-none d-lg-block">
                            <div class="min-vh-100 position-relative" 
                                 style="background-image: url('../assets/img/bmt.jpg'); background-size: cover; background-position: center;">
                                
                                <!-- Overlay mewah agar teks terbaca -->
                                <div class="position-absolute top-0 start-0 w-100 h-100" 
                                     style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.4) 0%, rgba(15, 23, 42, 0.7) 100%);">
                                </div>

                                <div class="position-absolute bottom-5 start-5 p-5 text-start" style="max-width: 600px;">
                                    <h2 class="text-white font-weight-bolder display-5 mb-2">Hasanah Farm</h2>
                                    <p class="text-white text-lg opacity-9">Website Pengelolaan Keuangan BMT Hasanah. Amanah, Profesional, dan Berkah.</p>
                                    <p class="text-white text-xs opacity-5 mt-5">Copyright © 2025 Magang Umpo TI 2025</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>
</x-guest-layout>