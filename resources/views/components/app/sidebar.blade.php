{{-- resources/views/layouts/sidebar.blade.php --}}
<div x-data="{ open: false }">

    {{-- BACKDROP UNTUK MOBILE --}}
    <div
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 bg-black/40 z-40 md:hidden"
        @click="open = false">
    </div>

    {{-- SIDEBAR --}}
    <aside
        id="sidenav-main"
        :class="open ? 'translate-x-0' : '-translate-x-full'"
        class="sidenav navbar navbar-vertical navbar-expand-xs border-0 bg-slate-900
               fixed-start fixed top-0 left-0 h-full w-64 z-50
               transition-transform duration-300 md:translate-x-0">

        {{-- HEADER SIDEBAR --}}
        <div class="sidenav-header flex justify-between items-center px-4 py-4">
            <a class="navbar-brand text-white font-bold text-lg" href="#">
                BMT HASANAH
            </a>

            {{-- CLOSE BUTTON (MOBILE) --}}
            <button @click="open = false" class="text-white text-xl md:hidden">
                ✕
            </button>
        </div>

        {{-- MENU --}}
        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse">
            <ul class="navbar-nav px-2">

                {{-- DASHBOARD --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center text-white {{ is_current_route('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-gauge text-lg me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- FARM --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center text-white {{ is_current_route('farm.index') ? 'active' : '' }}"
                       href="{{ route('farm.index') }}">
                        <i class="fa-solid fa-cow text-lg me-2"></i>
                        <span>BMT HASANAH Farm</span>
                    </a>
                </li>

                {{-- DATA KAMBING --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center text-white {{ is_current_route('kambing.index') ? 'active' : '' }}"
                       href="{{ route('kambing.index') }}">
                        <i class="fa-solid fa-otter text-lg me-2"></i>
                        <span>Data Kambing</span>
                    </a>
                </li>

                {{-- KAS --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center text-white {{ is_current_route('kas.index') ? 'active' : '' }}"
                       href="{{ route('kas.index') }}">
                        <i class="fa-solid fa-wallet text-lg me-2"></i>
                        <span>Laporan & Input Kas</span>
                    </a>
                </li>

                {{-- NERACA --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center text-white {{ is_current_route('neraca.index') ? 'active' : '' }}"
                       href="{{ route('neraca.index') }}">
                        <i class="fa-solid fa-file-invoice text-lg me-2"></i>
                        <span>Laporan Neraca</span>
                    </a>
                </li>

                {{-- ASET --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center text-white {{ is_current_route('aset.index') ? 'active' : '' }}"
                       href="{{ route('aset.index') }}">
                        <i class="fa-solid fa-box text-lg me-2"></i>
                        <span>Data Aset</span>
                    </a>
                </li>

                {{-- LABEL --}}
                <li class="nav-item mt-3 px-3 text-white-50 small">User Pages</li>

                {{-- USER PROFILE --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center text-white {{ is_current_route('users.profile') ? 'active' : '' }}"
                       href="{{ route('users.profile') }}">
                        <i class="fa-solid fa-user text-lg me-2"></i>
                        <span>User Profile</span>
                    </a>
                </li>

                {{-- USER ACTIVITY --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center text-white {{ is_current_route('users-management') ? 'active' : '' }}"
                       href="{{ route('users-management') }}">
                        <i class="fa-solid fa-users-gear text-lg me-2"></i>
                        <span>User Activity</span>
                    </a>
                </li>

                {{-- LABEL REGISTER --}}
                <li class="nav-item mt-3 px-3 text-white-50 small">Register</li>

                {{-- SIGN IN --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center text-white {{ is_current_route('signin') ? 'active' : '' }}"
                       href="{{ route('signin') }}">
                        <i class="fa-solid fa-right-to-bracket text-lg me-2"></i>
                        <span>Sign In</span>
                    </a>
                </li>

                {{-- SIGN UP --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center text-white {{ is_current_route('signup') ? 'active' : '' }}"
                       href="{{ route('signup') }}">
                        <i class="fa-solid fa-user-plus text-lg me-2"></i>
                        <span>Sign Up</span>
                    </a>
                </li>

                {{-- LOGOUT --}}
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="nav-link d-flex align-items-center text-white w-100 text-start">
                            <i class="fa-solid fa-door-open text-lg me-2"></i>
                            Logout
                        </button>
                    </form>
                </li>

            </ul>
        </div>
    </aside>

    {{-- TOGGLE BUTTON (MOBILE) --}}
    <button @click="open = true"
            class="fixed top-4 left-4 z-50 md:hidden text-white bg-slate-900/70 px-3 py-2 rounded-lg backdrop-blur">
        <i class="fa-solid fa-bars text-xl"></i>
    </button>

</div>
