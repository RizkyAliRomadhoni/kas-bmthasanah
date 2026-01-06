<x-app-layout>
    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100" style="background-color: #f8fafc;">
                <div class="container-fluid p-0">
                    <div class="row g-0">
                        <!-- Form Section (Kiri) -->
                        <div class="col-lg-5 col-md-12 d-flex flex-column justify-content-center min-vh-100 bg-white shadow-lg z-index-2 px-5">
                            <div class="mx-auto" style="max-width: 400px; width: 100%;">
                                <div class="mb-5 text-center text-lg-start">
                                    <h2 class="font-weight-bolder text-dark mb-2" style="letter-spacing: -1px; font-family: 'Plus Jakarta Sans', sans-serif;">
                                        Selamat Datang Kembali
                                    </h2>
                                    <p class="text-secondary font-weight-normal">Silakan masukkan akun Anda untuk melanjutkan ke dashboard.</p>
                                </div>

                                <div class="card card-plain shadow-none border-0">
                                    <div class="card-body p-0">
                                        <form role="form" class="text-start">
                                            <div class="mb-4">
                                                <label class="form-label font-weight-bold text-xs text-uppercase text-secondary">Email Address</label>
                                                <div class="input-group border rounded-lg p-1 shadow-sm transition-all shadow-hover">
                                                    <input type="email" class="form-control border-0 px-3" placeholder="name@company.com" aria-label="Email">
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3 text-start">
                                                <label class="form-label font-weight-bold text-xs text-uppercase text-secondary">Password</label>
                                                <div class="input-group border rounded-lg p-1 shadow-sm transition-all shadow-hover">
                                                    <input type="password" class="form-control border-0 px-3" placeholder="••••••••" aria-label="Password">
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center mb-4">
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" id="rememberMe">
                                                    <label class="form-check-label text-sm text-secondary ms-2 mb-0" for="rememberMe">Ingat saya</label>
                                                </div>
                                                <a href="javascript:;" class="text-xs font-weight-bold text-primary ms-auto">Lupa Password?</a>
                                            </div>

                                            <div class="text-center">
                                                <button type="button" class="btn btn-dark w-100 py-3 rounded-lg mb-3 shadow-dark border-0" style="background: #1e293b;">
                                                    Masuk ke Sistem
                                                </button>
                                                
                                                <div class="position-relative my-4">
                                                    <hr class="text-secondary opacity-2">
                                                    <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-xs text-secondary">Atau masuk dengan</span>
                                                </div>

                                                <button type="button" class="btn btn-outline-secondary w-100 py-2 rounded-lg d-flex align-items-center justify-content-center transition-all shadow-sm">
                                                    <img class="w-5 me-2" src="../assets/img/logos/google-logo.svg" alt="google-logo" />
                                                    <span class="text-dark font-weight-bold">Google</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center pt-4 px-0">
                                        <p class="text-sm text-secondary">
                                            Belum punya akun? 
                                            <a href="javascript:;" class="text-primary font-weight-bold">Daftar Sekarang</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Image Section (Kanan) -->
                        <div class="col-lg-7 d-none d-lg-block">
                            <div class="min-vh-100 position-relative" 
                                 style="background-image: url('../assets/img/bmt.jpg'); background-size: cover; background-position: center;">
                                
                                <!-- Overlay Gradien agar lebih elegan -->
                                <div class="position-absolute top-0 start-0 w-100 h-100" 
                                     style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.4) 0%, rgba(15, 23, 42, 0.8) 100%);">
                                </div>

                                <!-- Glassmorphism Card on Image -->
                                <div class="position-absolute bottom-5 start-5 end-5">
                                    <div class="p-5 border border-white border-opacity-10 shadow-2xl rounded-4" 
                                         style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);">
                                        <h3 class="text-white font-weight-bold mb-3">Sistem Keuangan Terpadu Hasanah Farm</h3>
                                        <p class="text-white text-lg opacity-8 mb-0">"Efisiensi dalam genggaman, akurasi dalam setiap laporan. Masa depan peternakan modern dimulai dari sini."</p>
                                        <div class="mt-4 d-flex align-items-center">
                                            <div class="avatar-group d-flex">
                                                <span class="text-white text-xs font-weight-bold opacity-6 italic">© 2024 Hasanah Farm Management System</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Tambahan CSS untuk efek transisi dan tombol -->
    <style>
        .rounded-lg { border-radius: 10px !important; }
        .shadow-dark { box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); }
        .transition-all { transition: all 0.3s ease; }
        .shadow-hover:focus-within {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
        }
        .italic { font-style: italic; }
    </style>
</x-app-layout>