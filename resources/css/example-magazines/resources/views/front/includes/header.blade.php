<header>
    <!-- Header Start -->
    <div class="header-area">
        <div class="main-header ">
            <div class="header-top black-bg d-none d-sm-block">
                <div class="container">
                    <div class="col-xl-12">
                        <div class="row d-flex justify-content-between align-items-center">
                            <div class="header-info-left">
                                <ul>     
                                    <li class="title"><span class="flaticon-energy"></span> trending-title</li>
                                    <li>Class property employ ancho red multi level mansion</li>
                                </ul>
                            </div>
                            <div class="header-info-right">
                                <ul class="header-date">
                                    <li><span class="flaticon-calendar"></span> +92 321 6261885</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-mid gray-bg">
                <div class="container">
                    <div class="row d-flex align-items-center">
                        <!-- Logo -->
                        <div class="col-xl-3 col-lg-3 col-md-3 d-none d-md-block">
                            <div class="logo">
                                <a href="index.html"><img src="{{ asset('front_assets/img/logo/logo.png') }}" alt=""></a>
                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9">
                            <div class="header-banner f-right ">
                                <img src="{{ asset('front_assets/img/gallery/header_card.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-bottom header-sticky d-flex" style="background: black !important;">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-8 col-lg-8 col-md-12 header-flex">
                            <!-- sticky -->
                            <div class="sticky-logo">
                                <a href="index.html"><img src="{{ asset('front_assets/img/logo/logo.png') }}" alt=""></a>
                            </div>
                            <!-- Main-menu -->
                            <div class="main-menu d-none d-md-block">
                                <nav>                  
                                    <ul id="navigation">
                                        <li><a href="{{ route('front') }}">Home</a></li>
                                        <li><a href="about.html">About</a></li>
                                        <li><a href="#">Magazines</a>
                                            <ul class="submenu">
                                                @php $magazines = DB::table('magazines')->get();@endphp
                                                @foreach ($magazines as $magazine)
                                                    <li><a href="{{ route('magazine.article', $magazine->slug) }}">{{ $magazine->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </li>
                                        <li><a href="{{ route('article.all') }}">Latest News</a></li>
                                        <li><a href="{{ route('contact') }}">Contact</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>             
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <div class="header-right f-right d-none d-lg-block">
                                <!-- Heder social -->
                                <ul class="header-social">    
                                    <li><a href="https://www.fb.com/sai4ull"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                    <li> <a href="#"><i class="fab fa-youtube"></i></a></li>
                                </ul>
                                <!-- Search Nav -->
                                <div class="nav-search search-switch">
                                    <i class="fa fa-search"></i>
                                </div>

                            </div>
                            
                        </div>
                        <!-- Mobile Menu -->
                        <div class="col-12">
                            <div class="mobile_menu d-block d-md-none"></div>
                        </div>

                    </div>
                </div>
                <div class="text-white d-flex justify-content-center align-items-center pe-5">
                    <div class="main-menu">
                        <nav>                  
                            <ul id="navigation">
                                <li><a href="#">Admin</a>
                                    <ul class="submenu">
                                        @if (!Auth::check())
                                            <li><a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                                            <li><a href="{{ route('register') }}"><i class="fas fa-plus"></i> Register</a></li>
                                        @else
                                            <li><a href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                                        @endif
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->
</header>