<x-app-layout>
    <main class="main-content mt-0">
        <div class="page-header min-vh-100" style="background-image: url('../assets/img/bmt.jpg'); background-size: cover; background-position: center; position: relative;">
            <!-- Dark Overlay untuk kontras yang mewah -->
            <span class="mask" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(5, 44, 36, 0.8) 100%); position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></span>
            
            <div class="container z-index-1">
                <div class="row justify-content-center">
                    <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-auto">
                        
                        <!-- Box Login -->
                        <div class="card card-plain bg-white shadow-2xl mt-5" style="border-radius: 24px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                            
                            <!-- Header dengan Logo Minimalis -->
                            <div class="card-header pb-0 text-center bg-transparent pt-5">
                                <div class="icon-box mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px; background: #10B981; border-radius: 16px; transform: rotate(-5deg); box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);">
                                    <i class="fas fa-leaf text-white fa-lg" style="transform: rotate(5deg);"></i>
                                </div>
                                <h3 class="font-weight-bolder text-dark mb-1" style="font-family: 'Inter', sans-serif; letter-spacing: -1px;">Hasanah Farm</h3>
                                <p class="text-secondary text-sm mb-0">Sistem Manajemen Keuangan Terpadu</p>
                            </div>

                            <div class="card-body p-4 pt-4">
                                <!-- Form tetap mempertahankan atribut aslinya -->
                                <form role="form" method="POST" action="{{ route('login') }}" class="text-start">
                                    @csrf
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label text-xs font-weight-bold text-uppercase text-secondary ms-1">Email Address</label>
                                        <div class="input-container">
                                            <input type="email" name="email" class="form-control custom-input" placeholder="name@company.com" required autofocus>
                                        </div>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="form-label text-xs font-weight-bold text-uppercase text-secondary ms-1">Password</label>
                                        <div class="input-container">
                                            <input type="password" name="password" class="form-control custom-input" placeholder="••••••••" required>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between mt-3 mb-4">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                                            <label class="form-check-label text-xs text-secondary mb-0" for="rememberMe" style="cursor:pointer;">Ingat saya</label>
                                        </div>
                                        <a href="{{ route('password.request') }}" class="text-xs font-weight-bold" style="color: #10B981; text-decoration: none;">Lupa Password?</a>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-slate w-100 py-3 mb-3">
                                            Sign In to Dashboard
                                        </button>
                                        
                                        <div class="divider my-4">
                                            <span class="text-xxs text-secondary text-uppercase font-weight-bold bg-white px-3">Atau</span>
                                        </div>

                                        <button type="button" class="btn btn-google w-100 d-flex align-items-center justify-content-center py-2">
                                            <img src="../assets/img/logos/google-logo.svg" alt="google" class="w-5 me-2">
                                            <span class="text-dark font-weight-bold text-xs uppercase">Google Account</span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="card-footer text-center pt-0 px-4 pb-4">
                                <p class="mb-0 text-sm text-secondary">
                                    Belum memiliki akses? 
                                    <a href="{{ route('register') }}" class="font-weight-bold" style="color: #10B981; text-decoration: none;">Hubungi Admin</a>
                                </p>
                            </div>
                        </div>

                        <!-- Footer luar kartu -->
                        <div class="text-center mt-4">
                            <p class="text-white text-xxs opacity-6">
                                © 2026 Hasanah Farm Management. Build with precision.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- CSS khusus untuk tampilan elegan -->
    <style>
        /* Import font Inter jika belum ada */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        .custom-input {
            border-radius: 12px !important;
            padding: 12px 16px !important;
            border: 1px solid #E2E8F0 !important;
            background-color: #F8FAFC !important;
            font-size: 14px !important;
            transition: all 0.2s ease;
        }

        .custom-input:focus {
            border-color: #10B981 !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1) !important;
            outline: none;
        }

        .btn-slate {
            background-color: #0F172A;
            color: #ffffff;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
        }

        .btn-slate:hover {
            background-color: #1e293b;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.3);
        }

        .btn-google {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .btn-google:hover {
            background: #F8FAFC;
            border-color: #CBD5E1;
            transform: translateY(-1px);
        }

        .divider {
            position: relative;
            text-align: center;
            border-bottom: 1px solid #F1F5F9;
            line-height: 0.1em;
        }

        .divider span {
            background: #fff;
        }

        .shadow-2xl {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
        }
    </style>
</x-app-layout>