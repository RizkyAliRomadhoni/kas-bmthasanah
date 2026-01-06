<x-guest-layout>
    <style>
        /* Modern UI Variable */
        :root {
            --primary-dark: #1e293b; /* Midnight Slate */
            --accent-color: #10b981; /* Emerald BMT */
            --bg-light: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
        }

        .custom-input:focus {
            border-color: var(--accent-color) !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
        }

        .btn-signin {
            background: var(--primary-dark);
            transition: all 0.3s ease;
            border: none;
        }

        .btn-signin:hover {
            background: #0f172a;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
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
                <div class="container">
                    <div class="row align-items-center">
                        <!-- KIRI: FORM LOGIN -->
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-auto">
                            <div class="card card-plain bg-white p-4 shadow-xl border-radius-lg">
                                <div class="card-header pb-0 text-start bg-transparent">
                                    <h3 class="font-weight-bolder text-dark mb-1" style="letter-spacing: -1px;">Selamat Datang</h3>
                                    <p class="mb-3 text-sm text-secondary">Silakan masuk untuk akses dashboard BMT Hasanah.</p>
                                    
                                    <!-- Hint Login (Dibuat lebih rapi dalam box) -->
                                    <div class="p-2 border-radius-sm mb-3" style="background-color: #f1f5f9; border-left: 4px solid var(--accent-color);">
                                        <p class="mb-0 text-xs text-dark"><strong>Demo Akun:</strong> admin@corporateui.com</p>
                                        <p class="mb-0 text-xs text-dark"><strong>Password:</strong> secret</p>
                                    </div>
                                </div>

                                <div class="text-center">
                                    @if (session('status'))
                                        <div class="mb-4 font-weight-bold text-sm text-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    @error('message')
                                        <div class="alert alert-danger text-white text-xs border-radius-md" role="alert">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="card-body pt-2">
                                    <form role="form" class="text-start" method="POST" action="sign-in">
                                        @csrf
                                        <label class="text-xs font-weight-bold text-secondary text-uppercase ms-1">Email Address</label>
                                        <div class="mb-3">
                                            <input type="email" id="email" name="email" class="form-control custom-input py-2"
                                                placeholder="Contoh: admin@mail.com"
                                                value="{{ old('email') ? old('email') : 'admin@corporateui.com' }}"
                                                required>
                                        </div>
                                        
                                        <label class="text-xs font-weight-bold text-secondary text-uppercase ms-1">Password</label>
                                        <div class="mb-3">
                                            <input type="password" id="password" name="password"
                                                value="{{ old('password') ? old('password') : 'secret' }}"
                                                class="form-control custom-input py-2" placeholder="••••••••"
                                                required>
                                        </div>

                                        <div class="d-flex align-items-center mb-3">
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" id="flexCheckDefault" checked>
                                                <label class="form-check-label text-xs text-secondary mb-0 ms-2" for="flexCheckDefault">
                                                    Ingat saya
                                                </label>
                                            </div>
                                            <a href="{{ route('password.request') }}" class="text-xs font-weight-bold text-primary ms-auto">Lupa Password?</a>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-signin text-white w-100 py-2 border-radius-md mt-2 shadow-sm">
                                                Sign In
                                            </button>
                                            <div class="position-relative my-4 text-center">
                                                <hr class="horizontal dark">
                                                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-xs text-secondary">Atau masuk dengan</span>
                                            </div>
                                            <button type="button" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center py-2 border-radius-md transition-all">
                                                <img class="w-5 me-2" src="../assets/img/logos/google-logo.svg" alt="google-logo" />
                                                <span class="text-xs font-weight-bold">Google Account</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="card-footer text-center pt-0 px-lg-2 px-1 bg-transparent">
                                    <p class="mb-0 text-xs mx-auto text-secondary">
                                        Belum punya akun?
                                        <a href="{{ route('sign-up') }}" class="text-dark font-weight-bold">Daftar di sini</a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- KANAN: GAMBAR & BRANDING -->
                        <div class="col-md-6">
                            <div class="position-absolute w-40 top-0 end-0 h-100 d-md-block d-none">
                                <div class="oblique-image position-absolute fixed-top ms-auto h-100 z-index-0 bg-cover ms-n8"
                                    style="background-image:url('../assets/img/bmt.jpg'); background-position: center;">
                                    
                                    <!-- Overlay Gradien -->
                                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.4) 0%, rgba(15, 23, 42, 0.8) 100%);"></div>

                                    <div class="mt-12 p-5 text-start border-radius-md position-absolute bottom-0 m-4 glass-card" style="max-width: 500px;">
                                        <h2 class="text-white font-weight-bolder mb-2">BMT Hasanah</h2>
                                        <p class="text-white text-lg opacity-9 mb-4">"Amanah, Profesional, dan Berkah. Website resmi pengelolaan keuangan terintegrasi."</p>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white p-1 border-radius-sm me-3">
                                                <i class="fas fa-shield-alt text-dark p-2"></i>
                                            </div>
                                            <h6 class="text-white text-sm mb-0">Magang Umpo TI 2025</h6>
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
</x-guest-layout>