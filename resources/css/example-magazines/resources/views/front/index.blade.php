<!DOCTYPE html>
<html class="no-js" lang="zxx">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <title>@yield('title')</title>
        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="manifest" href="site.webmanifest" />
        <link rel="shortcut icon" type="image/x-icon" href="front_assets/img/favicon.ico" />
        <link rel="stylesheet" href="{{ asset('front_assets/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('front_assets/css/owl.carousel.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('front_assets/css/ticker-style.css') }}" />
        <link rel="stylesheet" href="{{ asset('front_assets/css/flaticon.css') }}" />
        <link rel="stylesheet" href="{{ asset('front_assets/css/slicknav.css') }}" />
        <link rel="stylesheet" href="{{ asset('front_assets/css/animate.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('front_assets/css/magnific-popup.css') }}" />
        <link rel="stylesheet" href="{{ asset('front_assets/css/fontawesome-all.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('front_assets/css/themify-icons.css') }}" />
        <link rel="stylesheet" href="{{ asset('front_assets/css/slick.css') }}" />
        <link rel="stylesheet" href="{{ asset('front_assets/css/nice-select.css') }}" />
        <link rel="stylesheet" href="{{ asset('front_assets/css/style.css') }}" />
        <link href="{{ asset('assets/css/style.css?v=' . Helper::$css_asset_version) }}" rel="stylesheet" type="text/css" />

        @yield('css-lib')
    </head>
    <body>
        <div id="preloader-active">
            <div class="preloader d-flex align-items-center justify-content-center">
                <div class="preloader-inner position-relative">
                    <div class="preloader-circle"></div>
                    <div class="preloader-img pere-text">
                        <img src="{{ asset('front_assets/img/logo/logo.png') }}" alt="" />
                    </div>
                </div>
            </div>
        </div>

        @include('front.includes.header')
        @yield('content')
        @include('front.includes.footer')

        <div class="search-model-box">
            <div class="d-flex align-items-center h-100 justify-content-center">
                <div class="search-close-btn">+</div>
                <form class="search-model-form" action="{{ route('search.keyword') }}" method="post">
                    @csrf
                    <input type="text" id="search-input" name="keyword" @if(isset($keyword)) value="{{ $keyword }}" @endif placeholder="Searching key....." />
                </form>
            </div>
        </div>
        <script src="{{ asset('./front_assets/js/vendor/modernizr-3.5.0.min.js') }}"></script>
        <script src="{{ asset('./front_assets/js/vendor/jquery-1.12.4.min.js') }}"></script>
        <script src="{{ asset('./front_assets/js/popper.min.js') }}"></script>
        <script src="{{ asset('./front_assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('./front_assets/js/jquery.slicknav.min.js') }}"></script>
        <script src="{{ asset('./front_assets/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('./front_assets/js/slick.min.js') }}"></script>
        <script src="{{ asset('./front_assets/js/gijgo.min.js') }}"></script>
        <script src="{{ asset('./front_assets/js/wow.min.js') }}"></script>
        <script src="{{ asset('./front_assets/js/animated.headline.js') }}"></script>
        <script src="{{ asset('./front_assets/js/jquery.magnific-popup.js') }}"></script>
        <script src="{{ asset('./front_assets/js/jquery.scrollUp.min.js') }}"></script>
        <script src="{{ asset('./front_assets/js/jquery.nice-select.min.js') }}"></script>
        <script src="{{ asset('./front_assets/js/jquery.sticky.js') }}"></script>
        <script src="{{ asset('./front_assets/js/contact.js') }}"></script>
        <script src="{{ asset('./front_assets/js/jquery.form.js') }}"></script>
        <script src="{{ asset('./front_assets/js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('./front_assets/js/mail-script.js') }}"></script>
        <script src="{{ asset('./front_assets/js/jquery.ajaxchimp.min.js') }}"></script>
        <script src="{{ asset('./front_assets/js/plugins.js') }}"></script>
        <script src="{{ asset('./front_assets/js/main.js') }}"></script>
        <script src="{{ asset('assets/js/base.js?v=' . Helper::$js_asset_version) }}"></script>
        @yield('js-lib')
    </body>
</html>
