<x-app-layout>
    <main class="main-content mt-0">
        <div class="page-header min-vh-100" style="background-image: url('../assets/img/bmt.jpg'); background-size: cover; background-position: center;">
            <!-- Soft Overlay agar gambar tidak terlalu kontras -->
            <span class="mask bg-gradient-dark opacity-6"></span>
            
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-auto">
                        
                        <!-- Floating Card -->
                        <div class="card card-plain mt-8" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-radius: 24px; border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
                            
                            <div class="card-header pb-0 text-center bg-transparent">
                                <div class="mb-3">
                                    <!-- Placeholder Logo (Ganti dengan logo farm Anda) -->
                                    <div class="mx-auto bg-emerald d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px; border-radius: 16px;">
                                        <i class="fas fa-leaf text-white fa-lg"></i>
                                    </div>
                                </div>
                                <h3 class="font-weight-black text-dark" style="letter-spacing: -0.5px;">Sign In</h3>
                                <p class="mb-0 text-sm text-secondary">Hasanah Farm Management System</p>
                            </div>

                            <div class="card-body pt-4">
                                <form role="form" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-xs font-weight-bold text-uppercase text-secondary">Email Address</label>
                                        <input type="email" name="email" class="form-control custom-input" placeholder="name@company.com" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-xs font-weight-bold text-uppercase text-secondary">Password</label>
                                        <input type="password" name="password" class="form-control custom-input" placeholder="••••••••" required>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                                            <label class="form-check-label text-xs text-secondary mb-0" for="rememberMe">Remember me</label>
                                        </div>
                                        <a href="{{ route('password.request') }}" class="text-xs font-weight-bold text-emerald">Forgot Password?</a>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-dark w-100 py-3 mb-3 border-radius-lg shadow-sm" style="background: #1e293b; border: none; font-size: 14px; letter-spacing: 0.5px;">
                                            Login to Dashboard
                                        </button>
                                        
                                        <p class="text-xs text-secondary mb-3">Atau masuk menggunakan</p>
                                        
                                        <button type="button" class="btn btn-outline-secondary w-100 py-2 border-radius-lg d-flex align-items-center justify-content-center bg-white shadow-sm border-light transition-all">
                                            <img src="../assets/img/logos/google-logo.svg" alt="google" class="w-5 me-2">
                                            <span class="text-dark font-weight-bold text-xs uppercase">Google Account</span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="card-footer text-center pt-0 px-lg-2 px-1 bg-transparent">
                                <p class="mb-4 text-xs mx-auto">
                                    Belum memiliki akses?
                                    <a href="{{ route('register') }}" class="text-emerald font-weight-bold">Hubungi Admin</a>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Footer Copyright -->
                        <div class="text-center mt-4">
                            <p class="text-white text-xxs opacity-8">
                                &copy; 2026 Hasanah Farm. All rights reserved.
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        .text-emerald { color: #10b981 !important; }
        .bg-emerald { background: #10b981 !important; }
        
        .custom-input {
            border-radius: 12px !important;
            padding: 12px 16px !important;
            border: 1px solid #e2e8f0 !important;
            transition: all 0.2s ease;
            font-size: 14px !important;
        }

        .custom-input:focus {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1) !important;
            background-color: #fff !important;
        }

        .transition-all { transition: all 0.3s ease; }
        .border-radius-lg { border-radius: 12px !important; }

        /* Style Google Button */
        .btn-outline-secondary:hover {
            background-color: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            transform: translateY(-1px);
        }

        .font-weight-black { font-weight: 800 !important; }
    </style>
</x-app-layout>