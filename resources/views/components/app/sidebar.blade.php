{{-- FULLY RESPONSIVE SIDEBAR FIXED VERSION --}}
<style>
    /* Mobile sidebar behavior */
    @media (max-width: 1199px) {
        #sidenav-main {
            transform: translateX(-100%);
            transition: transform .3s ease-in-out;
            width: 260px !important;
            z-index: 9999;
        }
        #sidenav-main.open {
            transform: translateX(0);
        }
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 9998;
            display: none;
        }
        .sidebar-overlay.show {
            display: block;
        }
    }

    /* Desktop push content */
    @media (min-width: 1200px) {
        body {
            margin-left: 260px !important;
        }
    }
</style>

<div id="sidebar-overlay" class="sidebar-overlay"></div>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 bg-slate-900 fixed-start"
       id="sidenav-main">

    <div class="sidenav-header d-flex justify-content-between align-items-center px-3">
        {{-- MOBILE TOGGLER --}}
        <button id="sidebar-toggler"
            class="navbar-toggler d-xl-none d-block text-white"
            type="button">
            <i class="fas fa-bars"></i>
        </button>

        <a class="navbar-brand d-flex align-items-center m-0 text-white" href="#">
            <span class="font-weight-bold text-lg">BMT HASANAH</span>
        </a>
    </div>

    {{-- COLLAPSE WRAPPER --}}
    <div class="collapse navbar-collapse w-auto show" id="sidenav-collapse">
        <ul class="navbar-nav">

            <!-- DASHBOARD -->
            <li class="nav-item">
                <a class="nav-link {{ is_current_route('dashboard') ? 'active' : '' }}"
                   href="{{ route('dashboard') }}">
                    <div class="icon icon-shape icon-sm d-flex align-items-center justify-content-center">
                        {!! your_dashboard_icon_here !!}
                    </div>
                    <span class="nav-link-text ms-2">Dashboard</span>
                </a>
            </li>

            <!-- KAS -->
            <li class="nav-item">
                <a class="nav-link {{ is_current_route('kas.index') ? 'active' : '' }}"
                   href="{{ route('kas.index') }}">
                    <div class="icon icon-shape icon-sm d-flex align-items-center justify-content-center">
                        {!! your_kas_icon_here !!}
                    </div>
                    <span class="nav-link-text ms-2">Laporan & Input Kas</span>
                </a>
            </li>

            <!-- NERACA -->
            <li class="nav-item">
                <a class="nav-link {{ is_current_route('neraca.index') ? 'active' : '' }}"
                   href="{{ route('neraca.index') }}">
                    <div class="icon icon-shape icon-sm d-flex align-items-center justify-content-center">
                        {!! your_neraca_icon !!}
                    </div>
                    <span class="nav-link-text ms-2">Laporan Neraca</span>
                </a>
            </li>

            <!-- ASET -->
            <li class="nav-item">
                <a class="nav-link {{ is_current_route('aset.index') ? 'active' : '' }}"
                   href="{{ route('aset.index') }}">
                    <div class="icon icon-shape icon-sm d-flex align-items-center justify-content-center">
                        {!! your_aset_icon !!}
                    </div>
                    <span class="nav-link-text ms-2">Data Aset</span>
                </a>
            </li>

            <!-- FARM -->
            <li class="nav-item">
                <a class="nav-link {{ is_current_route('farm.index') ? 'active' : '' }}"
                   href="{{ route('farm.index') }}">
                    <div class="icon icon-shape icon-sm d-flex align-items-center justify-content-center">
                        {!! your_farm_icon !!}
                    </div>
                    <span class="nav-link-text ms-2">BMT HASANAH Farm</span>
                </a>
            </li>

            <!-- USER PAGES -->
            <li class="nav-item mt-3 px-3 text-white-50 small">User Pages</li>

            <li class="nav-item">
                <a class="nav-link {{ is_current_route('users.profile') ? 'active' : '' }}"
                   href="{{ route('users.profile') }}">
                    <span class="nav-link-text ms-2">User Profile</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ is_current_route('users-management') ? 'active' : '' }}"
                   href="{{ route('users-management') }}">
                    <span class="nav-link-text ms-2">User Activity</span>
                </a>
            </li>

            <!-- REGISTER + LOGIN -->
            <li class="nav-item mt-3 px-3 text-white-50 small">Register</li>

            <li class="nav-item">
                <a class="nav-link {{ is_current_route('signin') ? 'active' : '' }}"
                   href="{{ route('signin') }}">
                    <span class="nav-link-text ms-2">Sign In</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ is_current_route('signup') ? 'active' : '' }}"
                   href="{{ route('signup') }}">
                    <span class="nav-link-text ms-2">Sign Up</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidenav-main');
    const toggler = document.getElementById('sidebar-toggler');
    const overlay = document.getElementById('sidebar-overlay');

    toggler.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    });
});
</script>
