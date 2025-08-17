<!-- use Carbon\Carbon; $lastMonthStart = \Carbon::now()->subMonth()->startOfMonth(); $lastMonthEnd = \Carbon::now()->subMonth()->endOfMonth(); ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd]) -->
<div class="navbar-custom">
    <div class="container-fluid">        
        <ul class="list-unstyled topnav-menu float-end mb-0">

            <li class="dropdown d-none d-lg-inline-block">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="#">
                    <i class="fe-maximize noti-icon"></i>
                </a>
            </li>

            @php  $last_month_start = date('Y-m-01 00:00:00', strtotime('-1 month'));
            $articles = DB::table('articles')->where('user_id', '!=', Auth::id())->where('status', 1)->orderBy('id', 'desc')->take(5)->get(); @endphp
            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="fe-bell noti-icon"></i>
                    <span class="badge bg-danger rounded-circle noti-icon-badge">{{ DB::table('articles')->where('user_id', '!=', Auth::id())->where('status', 1)->whereBetween('created_at', [$last_month_start, now()])->count(); }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-lg">

                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h5 class="m-0">
                            <span class="float-end">
                                <a href="" class="text-dark">
                                    <small>Clear All</small>
                                </a>
                            </span>Notification
                        </h5>
                    </div>

                    <div class="noti-scroll" data-simplebar>

                        @foreach ($articles as $key => $article)
                            <a href="{{ url('http://localhost:8000/article-detail/'. $article->slug) }}" class="dropdown-item notify-item {{ $key === 0 ? ' active' : '' }}">
                                @php $users = DB::table('users')->where('id', $article->user_id)->first(); @endphp
                                @if (Auth::user()->image != null)
                                    <div class="notify-icon">
                                        <img src="{{ asset('storage/'. $users->image) }}" class="img-fluid rounded-circle" alt="" />
                                    </div>
                                @else
                                    <div class="notify-icon">
                                        <span class="text-white bg-primary p-2 rounded-circle text-uppercase">{{ substr($users->username, 0, 1); }}</span>
                                    </div>
                                @endif
                                
                                <p class="notify-details">{{ $users->username }}</p>
                                <p class="text-muted mb-0 user-msg">
                                    <small>{{  substr($article->title, 0, 35) ?? ''}}</small>
                                </p>

                                <p class="d-flex justify-content-end p-0 m-0">{{ 'Posted on: ' . date('d-m-Y', strtotime($article->created_at)) }}</p>
                            </a>
                            
                        @endforeach

                    <!-- All-->
                    <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item notify-all">
                        View all
                        <i class="fe-arrow-right"></i>
                    </a>

                </div>
            </li>

            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    @if (Auth::user()->image != null)
                        <img src="{{ asset('storage/'. Auth::user()->image) }}" alt="user-img" title="Mat Helme" class="rounded-circle avatar-md">
                    @else
                        <span class="text-white bg-primary p-2 rounded-circle text-uppercase " >{{ substr(Auth::user()->username, 0, 1); }}</span>
                    @endif
                    <span class="pro-user-name ms-1">
                        {{ Auth::user()->username }} <i class="mdi mdi-chevron-down"></i> 
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    <!-- item-->
                    <a href="{{ route('profile') }}" class="dropdown-item notify-item">
                        <i class="fe-user"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    @if (is_null(Auth::user()->transaction_id))
                        <button type="button" data-url="{{ route('store.token', 'Allow') }}" class="allow_noti dropdown-item notify-item">
                            <i class="fe-bell"></i>
                            <span>Allow</span>
                        </button>
                    @else
                        <button type="button" data-url="{{ route('store.token', 'Block') }}" class="allow_noti dropdown-item notify-item">
                            <i class="fe-bell"></i>
                            <span>Block</span>
                        </button>
                    @endif

                    <!-- item-->
                    {{-- <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock"></i>
                        <span>Lock Screen</span>
                     </a>--}}

                    <div class="dropdown-divider"></div>

                    <!-- item-->
                    <a href="{{ route('logout') }}" class="dropdown-item notify-item">
                        <i class="fe-log-out"></i>
                        <span>Logout</span>
                    </a>

                </div>
            </li>

            <li class="dropdown notification-list">
                <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">
                    <i class="fe-settings noti-icon"></i>
                </a>
            </li>

        </ul>

        <!-- LOGO -->
        <div class="logo-box">
            <a href="index.html" class="logo logo-dark text-center">
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                    <!-- <span class="logo-lg-text-light">UBold</span> -->
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="20">
                    <!-- <span class="logo-lg-text-light">U</span> -->
                </span>
            </a>

            <a href="index.html" class="logo logo-light text-center">
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="20">
                </span>
            </a>
        </div>

        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
                <button class="button-menu-mobile waves-effect waves-light">
                    <i class="fe-menu"></i>
                </button>
            </li>

            <li>
                <!-- Mobile menu toggle (Horizontal Layout)-->
                <a class="navbar-toggle nav-link" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </li>   

            
        </ul>
        <div class="clearfix"></div>
    </div>
</div>
<!-- end Topbar -->
