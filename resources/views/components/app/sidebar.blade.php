<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 bg-slate-900 fixed-start"
       id="sidenav-main">

    {{-- SIDENAV HEADER --}}
    <div class="sidenav-header">
        {{-- Mobile Close Icon --}}
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-xl-none"
           id="iconSidenav"></i>

        {{-- LOGO --}}
        <a class="navbar-brand d-flex align-items-center m-0" href="#" target="_blank">
            <span class="font-weight-bold text-lg text-white">BMT HASANAH</span>
        </a>
    </div>

    {{-- NAV MENU --}}
    <ul class="navbar-nav">

        {{-- DASHBOARD --}}
        <li class="nav-item">
            <a class="nav-link {{ is_current_route('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}">
                <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
                    {{-- Dashboard Icon --}}
                    <svg width="30px" height="30px" viewBox="0 0 48 48" fill="none">
                        <g transform="translate(12,12)" fill="#ffffff">
                            <path d="M0 1.7C0 .77.77 0 1.7 0h20.6C23.23 0 24 .77 24 1.7v3.4c0 .95-.77 1.7-1.7 1.7H1.7C.77 6.8 0 6.1 0 5.14V1.7z"/>
                            <path d="M0 12c0-.95.77-1.7 1.7-1.7H12c.95 0 1.7.77 1.7 1.7v10.3c0 .95-.77 1.7-1.7 1.7H1.7C.77 24 0 23.23 0 22.3V12z"/>
                            <path d="M17.14 10.3c-.94 0-1.7.77-1.7 1.7v10.3c0 .95.76 1.7 1.7 1.7h3.43c.94 0 1.7-.75 1.7-1.7V12c0-.93-.76-1.7-1.7-1.7h-3.43z"/>
                        </g>
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Dashboard</span>
            </a>
        </li>

        {{-- LAPORAN & INPUT KAS --}}
        <li class="nav-item">
            <a class="nav-link {{ is_current_route('kas.index') ? 'active' : '' }}"
               href="{{ route('kas.index') }}">
                <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
                    {{-- Grid Icon --}}
                    <svg width="30px" height="30px" viewBox="0 0 48 48" fill="none">
                        <g transform="translate(12,12)" fill="#ffffff">
                            <path d="M3.4 0C1.53 0 0 1.53 0 3.4v3.4c0 1.87 1.53 3.4 3.4 3.4h3.46c1.87 0 3.4-1.53 3.4-3.4V3.4C10.3 1.53 8.77 0 6.86 0H3.4z"/>
                            <path d="M0 17.14v3.43C0 22.46 1.53 24 3.4 24h3.46c1.87 0 3.4-1.54 3.4-3.43v-3.43c0-1.9-1.53-3.43-3.4-3.43H3.4C1.53 13.7 0 15.25 0 17.14z"/>
                            <path d="M13.7 3.4c0-1.87 1.54-3.4 3.43-3.4h3.46C22.46 0 24 1.53 24 3.4v3.4c0 1.87-1.54 3.4-3.4 3.4h-3.46C15.25 10.2 13.7 8.7 13.7 6.8V3.4z"/>
                            <path d="M13.7 17.14v3.43c0 1.87 1.54 3.4 3.43 3.4h3.46c1.86 0 3.4-1.53 3.4-3.4v-3.43c0-1.9-1.54-3.43-3.4-3.43h-3.46c-1.89 0-3.43 1.53-3.43 3.43z"/>
                        </g>
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Laporan & Input Kas</span>
            </a>
        </li>

        {{-- LAPORAN NERACA --}}
        <li class="nav-item">
            <a class="nav-link {{ is_current_route('neraca.index') ? 'active' : '' }}"
               href="{{ route('neraca.index') }}">
                <div class="icon icon-shape icon-sm px-0 d-flex align-items-center justify-content-center">
                    {{-- Wallet Icon --}}
                    <svg width="30px" height="30px" viewBox="0 0 48 48" fill="none">
                        <g transform="translate(12,15)" fill="#ffffff">
                            <path d="M3 0h18c1.66 0 3 1.34 3 3v1.5H0V3c0-1.66 1.34-3 3-3z"/>
                            <path d="M0 7.5h24V15c0 1.66-1.34 3-3 3H3c-1.66 0-3-1.34-3-3V7.5z"/>
                        </g>
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Laporan Neraca</span>
            </a>
        </li>

        {{-- DATA ASET --}}
        <li class="nav-item">
            <a class="nav-link {{ is_current_route('aset.index') ? 'active' : '' }}"
               href="{{ route('aset.index') }}">
                <div class="icon icon-shape icon-sm px-0 d-flex align-items-center justify-content-center">
                    {{-- Document Icon --}}
                    <svg width="30px" height="30px" viewBox="0 0 48 48" fill="none">
                        <g transform="translate(12,12)" fill="#ffffff">
                            <path d="M5 3v18c0 .55.45 1 1 1h14c.55 0 1-.45 1-1V3c0-.55-.45-1-1-1H6c-.55 0-1 .45-1 1zm2 1h12v16H7V4zm2 2v2h8V6H9zm0 4v2h8v-2H9zm0 4v2h8v-2H9z"/>
                        </g>
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Data Aset</span>
            </a>
        </li>

        {{-- FARM --}}
        <li class="nav-item">
            <a class="nav-link {{ is_current_route('farm.index') ? 'active' : '' }}"
               href="{{ route('farm.index') }}">
                <div class="icon icon-shape icon-sm px-0 d-flex align-items-center justify-content-center">
                    {{-- Chat Bubble Icon --}}
                    <svg width="30px" height="30px" viewBox="0 0 48 48" fill="none">
                        <g transform="translate(12,15)" fill="#ffffff">
                            <path d="M12 3C6.47 3 2 6.69 2 11c0 1.87.8 3.6 2.14 4.96L2 20.5a1 1 0 0 0 1.52.86L7 18.5a11.1 11.1 0 0 0 5 1c5.52 0 10-3.69 10-8s-4.48-8-10-8z"/>
                        </g>
                    </svg>
                </div>
                <span class="nav-link-text ms-1">BMT Hasanah Farm</span>
            </a>
        </li>

        {{-- USER PAGES HEADER --}}
        <li class="nav-item mt-2">
            <div class="d-flex align-items-center nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" fill="#ffffff" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                          d="M18.7 19.1A9.7 9.7 0 0021.8 12a9.7 9.7 0 10-19.4 0 9.7 9.7 0 003.1 7.1A9.7 9.7 0 0012 21.8c2.6 0 5-.9 6.7-2.7zM12 15a7.5 7.5 0 015.9 2.8A8.2 8.2 0 0112 20.3a8.2 8.2 0 01-5.9-2.4A7.5 7.5 0 0112 15zm0-6a3.7 3.7 0 110 7.5 3.7 3.7 0 010-7.5z"/>
                </svg>
                <span class="font-weight-normal text-md ms-2 text-white">User Pages</span>
            </div>
        </li>

        {{-- USER PROFILE --}}
        <li class="nav-item">
            <a class="nav-link {{ is_current_route('users.profile') ? 'active' : '' }}"
               href="{{ route('users.profile') }}">
                <span class="nav-link-text ms-3">User Profile</span>
            </a>
        </li>

        {{-- USER ACTIVITY --}}
        <li class="nav-item">
            <a class="nav-link {{ is_current_route('users-management') ? 'active' : '' }}"
               href="{{ route('users-management') }}">
                <span class="nav-link-text ms-3">User Activity</span>
            </a>
        </li>

        {{-- REGISTER --}}
        <li class="nav-item">
            <a class="nav-link {{ is_current_route('signin') ? 'active' : '' }}"
               href="{{ route('signin') }}">
                <span class="nav-link-text ms-3">Sign In</span>
            </a>
        </li>

        {{-- SIGN UP --}}
        <li class="nav-item">
            <a class="nav-link {{ is_current_route('signup') ? 'active' : '' }}"
               href="{{ route('signup') }}">
                <span class="nav-link-text ms-3">Sign Up</span>
            </a>
        </li>

    </ul>
</aside>

{{-- MOBILE TOGGLE SCRIPT --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const iconSidenav = document.getElementById('iconSidenav');
        const sidenav = document.getElementById('sidenav-main');

        if (iconSidenav) {
            iconSidenav.addEventListener('click', function () {
                sidenav.classList.toggle('collapsed');
            });
        }
    });
</script>

{{-- EXTRA CSS --}}
<style>
    @media (max-width: 1199px) {
        #sidenav-main {
            transform: translateX(-100%);
            transition: all .3s ease;
        }
        #sidenav-main.collapsed {
            transform: translateX(0);
        }
    }
</style>
