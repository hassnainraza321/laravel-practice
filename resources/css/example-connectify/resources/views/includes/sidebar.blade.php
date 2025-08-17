<div class="leftside-menu">
    <a href="javascript:void(0)" class="logo logo-light">
        <span class="logo-lg text-center">
            <img src="{{ asset('images/logo.png?v=' . Helper::$images_asset_version) }}" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('images/logo-sm.png?v=' . Helper::$images_asset_version) }}" alt="small logo">
        </span>
    </a>
    <a href="javascript:void(0)" class="logo logo-dark">
        <span class="logo-lg text-center">
            <img src="{{ asset('images/logo.png?v=' . Helper::$images_asset_version) }}" alt="dark logo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('images/logo.png?v=' . Helper::$images_asset_version) }}" alt="small logo">
        </span>
    </a>
    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <ul class="side-nav">
            <li class="side-nav-item dashboard">
                <a href="{{ route('dashboard') }}" class="side-nav-link">
                    <i class="ri-dashboard-3-line"></i>
                    <span> Dashboard </span>
                </a>
            </li>
            <li class="side-nav-item projects">
                <a href="{{ route('project') }}" class="side-nav-link">
                    <i class="ri-bar-chart-line"></i>
                    <span> Projects </span>
                </a>
            </li>
            <li class="side-nav-item contacts">
                <a href="{{ route('contacts') }}" class="side-nav-link">
                    <i class="ri-contacts-line"></i>
                    <span> Contacts </span>
                </a>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#catalog" aria-expanded="false" aria-controls="catalog" class="side-nav-link">
                    <i class="ri-stack-line"></i>
                    <span> Manage </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse catalog" id="catalog">
                    <ul class="side-nav-second-level">
                        <li class="templates">
                            <a href="{{ route('template.message') }}">Templates</a>
                        </li>
                        {{-- <li class="optout-management">
                            <a href="{{ route('optout') }}">Optout Management</a>
                        </li> --}}
                        <li class="chat-settings">
                            <a href="{{ route('chat.settings') }}">Live Chat Settings</a>
                        </li>
                        <li class="user-attributes">
                            <a href="{{ route('user.attributes') }}">User Attributes</a>
                        </li>
                        <li class="tags">
                            <a href="{{ route('tags') }}">Tags</a>
                        </li>
                        <li class="canned-messages">
                            <a href="{{ route('canned.message') }}">Canned Message</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="side-nav-item campaigns">
                <a href="{{ route('campaigns') }}" class="side-nav-link">
                    <i class="ri-send-plane-line"></i>
                    <span> Campaigns </span>
                </a>
            </li>
            <li class="side-nav-item settings">
                <a href="{{ route('logout') }}" class="side-nav-link">
                    <i class="ri-logout-box-line"></i>
                    <span> Logout </span>
                </a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
</div>