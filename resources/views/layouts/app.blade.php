<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @if (config('app.is_demo'))
        <title itemprop="name">Kelola Kas & Neraca Keuangan BMT HASANAH</title>
        <meta name="twitter:card" content="KasBMThasanah" />
        <meta name="twitter:site" content="@KasBMThasanah" />
        <meta name="twitter:creator" content="@KasBMThasanah" />
        <meta name="twitter:title" content="Kelola Kas & Neraca Keuangan BMT HASANAH" />
        <meta name="twitter:description" content="Web Pengelola Keuangan Dan Kontrol Farm BMT HASANAH Ponorogo" />
        <meta name="twitter:image" content="https://s3.amazonaws.com/creativetim_bucket/products/737/original/corporate-ui-dashboard-laravel.jpg" />
        <meta name="twitter:url" content="https://www.creative-tim.com/live/corporate-ui-dashboard-laravel" />
    @endif

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logobmt.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logobmt.png') }}">
    <title>Kelola Kas & Neraca Keuangan BMT HASANAH</title>

    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Noto+Sans:300,400,500,600,700,800|PT+Mono:300,400,500,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/349ee9c857.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/corporate-ui-dashboard.css?v=1.0.0') }}" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    @php
        $topSidenavArray = ['wallet', 'profile'];
        $topSidenavTransparent = ['signin', 'signup'];
        $topSidenavRTL = ['RTL'];
    @endphp

    @if (in_array(request()->route()->getName(), $topSidenavArray))
        <x-sidenav-top />
    @elseif(in_array(request()->route()->getName(), $topSidenavTransparent))
        {{-- Transparent Navbar --}}
    @elseif(in_array(request()->route()->getName(), $topSidenavRTL))
        {{-- RTL Layout --}}
    @else
        <x-app.sidebar />
    @endif

    {{ $slot }}

    <div class="fixed-plugin">
        <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
            <i class="fa fa-cog py-2"></i>
        </a>
        <div class="card shadow-lg ">
            <div class="card-header pb-0 pt-3">
                <div class="float-start">
                    <h5 class="mt-3 mb-0">Corporate UI Configurator</h5>
                    <p>See our dashboard options.</p>
                </div>
                <div class="float-end mt-4">
                    <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
                        <i class="fa fa-close"></i>
                    </button>
                </div>
            </div>
            <hr class="horizontal dark my-1">

            <div class="card-body pt-sm-3 pt-0">
                <h6 class="mb-0">Sidebar Colors</h6>
                <a href="javascript:void(0)" class="switch-trigger background-color">
                    <div class="badge-colors my-2 text-start">
                        <span class="badge filter bg-gradient-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
                    </div>
                </a>

                <div class="mt-3">
                    <h6 class="mb-0">Sidenav Type</h6>
                    <p class="text-sm">Choose between 2 different sidenav types.</p>
                </div>
                <div class="d-flex">
                    <button class="btn bg-gradient-primary w-100 px-3 mb-2 active" data-class="bg-slate-900" onclick="sidebarType(this)">Dark</button>
                    <button class="btn bg-gradient-primary w-100 px-3 mb-2 ms-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
                </div>
                <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>

                <div class="mt-3">
                    <h6 class="mb-0">Navbar Fixed</h6>
                </div>
                <div class="form-check form-switch ps-0">
                    <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
                </div>

                <hr class="horizontal dark my-sm-4">

                <a class="btn bg-gradient-dark w-100" target="_blank" href="https://www.creative-tim.com/product/corporate-ui-dashboard-laravel">Free Download</a>
                <a class="btn btn-outline-dark w-100" target="_blank" href="https://www.creative-tim.com/learning-lab/bootstrap/installation-guide/corporate-ui-dashboard">View documentation</a>

                <div class="w-100 text-center mt-3">
                    <a class="github-button" target="_blank" href="https://github.com/creativetimofficial/corporate-ui-dashboard-laravel"
                        data-icon="octicon-star" data-size="large" data-show-count="true">
                        Star
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/corporate-ui-dashboard.min.js?v=1.0.0') }}"></script>

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), { damping: '0.5' });
        }
    </script>
    @if (session('success'))
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 2000">
        <div id="liveToast" class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body fw-semibold">
                    ✅ {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

{{-- tambahkan script bootstrap jika belum ada --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (document.querySelector('.swiper-container')) {
            new Swiper(".swiper-container", {
                slidesPerView: 1,
                spaceBetween: 10,
                loop: true,
                autoplay: {
                    delay: 3500,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
            });
        }
    });
</script>
</html>
