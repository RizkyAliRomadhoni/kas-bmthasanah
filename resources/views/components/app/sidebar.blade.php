<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 bg-slate-900 fixed-start" id="sidenav-main">
    <div class="sidenav-header">
        <button class="navbar-toggler d-xl-none d-block ms-3 mt-3 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#sidenav-collapse" aria-controls="sidenav-collapse" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <a class="navbar-brand d-flex align-items-center m-0 text-white" href="#">
            <span class="font-weight-bold text-lg">BMT HASANAH</span>
        </a>
    </div>

    <!-- COLLAPSE WRAPPER UNTUK MOBILE -->
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse">
        <ul class="navbar-nav">

            <!-- DASHBOARD -->
            <li class="nav-item">
                <a class="nav-link {{ is_current_route('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <div class="icon icon-shape icon-sm d-flex align-items-center justify-content-center">
                        <svg width="30px" height="30px" viewBox="0 0 48 48"><g transform="translate(12,12)" fill="#fff"><path d="M0,1.7C0,0.77,0.77,0,1.7,0h20.6C23.23,0,24,0.77,24,1.7v3.43c0,0.95-0.77,1.72-1.71,1.72H1.7C0.77,6.86,0,6.09,0,5.14V1.7z"></path><path d="M0,12c0-0.95,0.77-1.71,1.7-1.71H12c0.95,0,1.71,0.76,1.71,1.71v10.29c0,0.95-0.76,1.71-1.71,1.71H1.7C0.77,24,0,23.24,0,22.29V12z"></path><path d="M17.14,10.29c-0.94,0-1.71,0.76-1.71,1.71v10.29c0,0.95,0.77,1.71,1.71,1.71h3.43c0.94,0,1.71-0.76,1.71-1.71V12c0-0.95-0.77-1.71-1.71-1.71h-3.43z"></path></g></svg>
                    </div>
                    <span class="nav-link-text ms-2">Dashboard</span>
                </a>
            </li>

            <!-- KAS -->
            <li class="nav-item">
                <a class="nav-link {{ is_current_route('kas.index') ? 'active' : '' }}" href="{{ route('kas.index') }}">
                    <div class="icon icon-shape icon-sm d-flex align-items-center justify-content-center">
                        <svg width="30" height="30" viewBox="0 0 48 48"><g transform="translate(12,12)" fill="#fff"><path d="M3.4,0C1.53,0,0,1.53,0,3.43v3.43c0,1.89,1.53,3.43,3.4,3.43h3.43C8.75,10.29,10.29,8.75,10.29,6.86V3.43C10.29,1.53,8.75,0,6.86,0H3.4z"></path><path d="M3.4,13.71C1.53,13.71,0,15.25,0,17.14v3.43c0,1.89,1.53,3.43,3.4,3.43h3.43C8.75,24,10.29,22.46,10.29,20.57v-3.43c0-1.89-1.54-3.43-3.43-3.43H3.4z"></path><path d="M13.71,3.43C13.71,1.53,15.25,0,17.14,0h3.43C22.46,0,24,1.53,24,3.43v3.43c0,1.89-1.54,3.43-3.43,3.43H17.14C15.25,10.29,13.71,8.75,13.71,6.86V3.43z"></path><path d="M13.71,17.14c0-1.89,1.54-3.43,3.43-3.43h3.43c1.89,0,3.43,1.54,3.43,3.43v3.43C24,22.46,22.46,24,20.57,24h-3.43c-1.89,0-3.43-1.54-3.43-3.43V17.14z"></path></g></svg>
                    </div>
                    <span class="nav-link-text ms-2">Laporan & Input Kas</span>
                </a>
            </li>

            <!-- NERACA -->
            <li class="nav-item">
                <a class="nav-link {{ is_current_route('neraca.index') ? 'active' : '' }}" href="{{ route('neraca.index') }}">
                    <div class="icon icon-shape icon-sm d-flex align-items-center justify-content-center">
                        <svg width="30" height="30" viewBox="0 0 48 48"><g transform="translate(12,15)" fill="#fff"><path d="M3,0C1.34,0,0,1.34,0,3v1.5h24V3c0-1.66-1.34-3-3-3H3z"></path><path d="M24,7.5H0V15c0,1.66,1.34,3,3,3h18c1.66,0,3-1.34,3-3V7.5zM3,13.5c0-0.83,0.67-1.5,1.5-1.5H6c0.83,0,1.5,0.67,1.5,1.5S6.83,15,6,15H4.5C3.67,15,3,14.33,3,13.5z"></path></g></svg>
                    </div>
                    <span class="nav-link-text ms-2">Laporan Neraca</span>
                </a>
            </li>

            <!-- ASET -->
            <li class="nav-item">
                <a class="nav-link {{ is_current_route('aset.index') ? 'active' : '' }}" href="{{ route('aset.index') }}">
                    <div class="icon icon-shape icon-sm d-flex align-items-center justify-content-center">
                        <svg width="30" height="30" viewBox="0 0 48 48"><g transform="translate(12,15)" fill="#fff"><path d="M5,3v18a1,1,0,0,0,1,1H20a1,1,0,0,0,1-1V3a1,1,0,0,0-1-1H6A1,1,0,0,0,5,3Zm2,1H19V20H7Zm2,2V8H17V6Zm0,4v2H17V10Zm0,4v2H17V14Z"></path></g></svg>
                    </div>
                    <span class="nav-link-text ms-2">Data Aset</span>
                </a>
            </li>

            <!-- FARM -->
            <li class="nav-item">
                <a class="nav-link {{ is_current_route('farm.index') ? 'active' : '' }}" href="{{ route('farm.index') }}">
                    <div class="icon icon-shape icon-sm d-flex align-items-center justify-content-center">
                        <svg width="30" height="30" viewBox="0 0 48 48"><g transform="translate(12,15)" fill="#fff"><path d="M12,3C6.48,3,2,6.69,2,11c0,1.87.8,3.6,2.14,4.96L2,20.5a1,1,0,0,0,1.52.86L7,18.5A11.1,11.1,0,0,0,12,19c5.52,0,10-3.69,10-8S17.52,3,12,3Z"></path></g></svg>
                    </div>
                    <span class="nav-link-text ms-2">BMT HASANAH Farm</span>
                </a>
            </li>

            <!-- USER PAGES LABEL -->
            <li class="nav-item mt-3 px-3 text-white-50 small">
                User Pages
            </li>

            <li class="nav-item">
                <a class="nav-link {{ is_current_route('users.profile') ? 'active' : '' }}" href="{{ route('users.profile') }}">
                    <span class="nav-link-text ms-2">User Profile</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ is_current_route('users-management') ? 'active' : '' }}" href="{{ route('users-management') }}">
                    <span class="nav-link-text ms-2">User Activity</span>
                </a>
            </li>

            <!-- REGISTER + LOGIN -->
            <li class="nav-item mt-3 px-3 text-white-50 small">
                Register
            </li>

            <li class="nav-item">
                <a class="nav-link {{ is_current_route('signin') ? 'active' : '' }}" href="{{ route('signin') }}">
                    <span class="nav-link-text ms-2">Sign In</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ is_current_route('signup') ? 'active' : '' }}" href="{{ route('signup') }}">
                    <span class="nav-link-text ms-2">Sign Up</span>
                </a>
            </li>

        </ul>
    </div>
</aside>
