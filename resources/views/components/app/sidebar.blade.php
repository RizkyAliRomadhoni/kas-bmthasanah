<aside class="hidden lg:block sidenav navbar navbar-vertical navbar-expand-xs border-0 bg-slate-900 fixed-start"
       id="sidenav-main">

    {{-- SIDENAV HEADER --}}
    <div class="sidenav-header position-relative">

        {{-- LOGO --}}
        <a class="navbar-brand d-flex align-items-center m-0" href="#">
            <span class="font-weight-bold text-lg text-white">BMT HASANAH</span>
        </a>
    </div>

    {{-- NAV MENU --}}
    <ul class="navbar-nav">

        {{-- DASHBOARD --}}
        <li class="nav-item">
            <a class="nav-link {{ is_current_route('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}">
                <div class="icon icon-sm text-white d-flex align-items-center justify-content-center">
                    <!-- SVG -->
                </div>
                <span class="nav-link-text ms-2">Dashboard</span>
            </a>
        </li>

        {{-- LAPORAN & INPUT KAS --}}
        <li class="nav-item">
            <a class="nav-link {{ is_current_route('kas.index') ? 'active' : '' }}"
               href="{{ route('kas.index') }}">
                <div class="icon icon-sm text-white d-flex align-items-center justify-content-center">
                    <!-- SVG -->
                </div>
                <span class="nav-link-text ms-2">Laporan & Input Kas</span>
            </a>
        </li>

        {{-- NERACA --}}
        <li class="nav-item">
            <a class="nav-link {{ is_current_route('neraca.index') ? 'active' : '' }}"
               href="{{ route('neraca.index') }}">
                <div class="icon icon-sm text-white d-flex align-items-center justify-content-center">
                    <!-- SVG -->
                </div>
                <span class="nav-link-text ms-2">Laporan Neraca</span>
            </a>
        </li>

        {{-- DATA ASET --}}
        <li class="nav-item">
            <a class="nav-link {{ is_current_route('aset.index') ? 'active' : '' }}"
               href="{{ route('aset.index') }}">
                <div class="icon icon-sm text-white d-flex align-items-center justify-content-center">
                    <!-- SVG -->
                </div>
                <span class="nav-link-text ms-2">Data Aset</span>
            </a>
        </li>

        {{-- FARM --}}
        <li class="nav-item">
            <a class="nav-link {{ is_current_route('farm.index') ? 'active' : '' }}"
               href="{{ route('farm.index') }}">
                <div class="icon icon-sm text-white d-flex align-items-center justify-content-center">
                    <!-- SVG -->
                </div>
                <span class="nav-link-text ms-2">BMT Hasanah Farm</span>
            </a>
        </li>

        {{-- USER PAGES --}}
        <li class="nav-item mt-3">
            <div class="nav-link d-flex align-items-center text-white opacity-8">
                <!-- SVG -->
                <span class="ms-2">User Pages</span>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ is_current_route('users.profile') ? 'active' : '' }}"
               href="{{ route('users.profile') }}">
                <span class="nav-link-text ms-3">User Profile</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ is_current_route('users-management') ? 'active' : '' }}"
               href="{{ route('users-management') }}">
                <span class="nav-link-text ms-3">User Activity</span>
            </a>
        </li>

    </ul>

</aside>
