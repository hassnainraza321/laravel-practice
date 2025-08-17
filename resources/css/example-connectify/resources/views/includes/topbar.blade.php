<div class="navbar-custom">
    <div class="topbar container-fluid">
        <div class="d-flex align-items-center gap-1">
            <div class="logo-topbar">
                <a href="{{ route('dashboard') }}" class="logo-light">
                    <span class="logo-lg">
                        <img src="{{ asset('images/logo-white.png?v=' . Helper::$images_asset_version) }}" alt="{{ Helper::getSiteTitle() }} logo">
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('images/logo.png?v=' . Helper::$images_asset_version) }}" alt="{{ Helper::getSiteTitle() }} logo">
                    </span>
                </a>
                <a href="{{ route('dashboard') }}" class="logo-dark">
                    <span class="logo-lg">
                        <img src="{{ asset('images/logo.png?v=' . Helper::$images_asset_version) }}" alt="{{ Helper::getSiteTitle() }} logo">
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('images/logo-sm.png?v=' . Helper::$images_asset_version) }}" alt="{{ Helper::getSiteTitle() }} logo">
                    </span>
                </a>
            </div> 
            <button class="button-toggle-menu">
                <i class="ri-menu-line"></i>
            </button>
            <button class="navbar-toggle" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <div class="lines">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
            <div class="app-search d-none d-lg-block">
                <p class="topbar-title">{{ Helper::getSiteTitle() }} Admin Dashboard</p>
            </div>
        </div>
        <ul class="topbar-menu d-flex align-items-center gap-3">
            <li class="d-sm-inline-block">
                <a class="btn btn-sm btn-light" href="{{ route('project') }}" target="_blank">
                    <i class="ri-store-2-line"></i>
                    <span class="ms-1">View Projects</span>
                </a>
            </li>
            <li class="dropdown">
                <a class="nav-link dropdown-toggle arrow-none nav-user" data-bs-toggle="dropdown" href="#" role="button"
                    aria-haspopup="false" aria-expanded="false">
                    <span class="account-user-avatar">
                        @if (!empty((auth()->user()->image)))
                            <img src="{{ asset('storage/'. auth()->user()->image) }}" class="img-fluid rounded-circle avatar-image" alt="" />
                        @else
                            {!! Helper::generateAvatarSVG(auth()->user()->first_name . ' ' . auth()->user()->last_name) !!}
                        @endif
                    </span>
                    <span class="d-lg-block">
                        <h5 class="my-0 fw-normal">{{ auth()->user()->first_name }} <i class="ri-arrow-down-s-line d-none d-sm-inline-block align-middle"></i></h5>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>
                    <a href="{{ route('profile') }}" class="dropdown-item">
                        <i class="ri-account-circle-line fs-18 align-middle me-1"></i>
                        <span>My Account</span>
                    </a>
                    <a href="{{ route('logout') }}" class="dropdown-item">
                        <i class="ri-logout-box-line fs-18 align-middle me-1"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</div>