<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @yield('meta')
        <title>@yield('title')</title>
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

        <!-- Plugins css -->
        <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />

        @yield('css-lib')

        <!-- Bootstrap css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App css -->
        <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style"/>
        <!-- icons -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Head js -->
        <script src="{{ asset('assets/js/head.js') }}"></script>

        <link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/libs/datatables.net-select-bs5/css//select.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />

        <link href="{{ asset('assets/css/style.css?v=' . Helper::$css_asset_version) }}" rel="stylesheet" type="text/css" />

    </head>

    <!-- body start -->
    <body  data-layout-mode="detached" data-theme="light" data-topbar-color="dark" data-menu-position="fixed" data-leftbar-color="light" data-leftbar-size='default' data-sidebar-user='true'>

        <!-- Begin page -->
        <div id="wrapper">
            @include('dashboard.includes.topbar')
            @include('dashboard.includes.sidebar')

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">
                        @if(session('success'))
                        <div id="success_alert_modal" data-success-session="{{ (session('success')) }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content modal-filled bg-success">
                                    <div class="modal-body p-4">
                                        <div class="text-center">
                                            <i class="dripicons-checkmark h1 text-white"></i>
                                            <h4 class="mt-2 text-white">Well Done!</h4>
                                            <p class="mt-3 text-white">{{ (session('success')) }}</p>
                                            <button type="button" class="btn btn-light my-2" onclick="return $('#success_alert_modal').modal('hide');">Continue</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(session('error'))
                        <div id="error_alert_modal" data-error-session="{{ (session('error')) }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content modal-filled bg-danger">
                                    <div class="modal-body p-4">
                                        <div class="text-center">
                                            <i class="dripicons-wrong h1 text-white"></i>
                                            <h4 class="mt-2 text-white">Oh snap!</h4>
                                            <p class="mt-3 text-white">{{ session('error') }}</p>
                                            <button type="button" class="btn btn-light my-2" onclick="return $('#error_alert_modal').modal('hide');">Continue</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div id="alert_modal" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content modal-filled bg-danger">
                                    <div class="modal-body p-4">
                                        <div class="text-center">
                                            <i class="dripicons-wrong h1 text-white"></i>
                                            <h4 class="mt-2 text-white">Error !</h4>
                                            <p class="mt-3 text-white">Are you sure you want to delete !</p>
                                            <button type="button" class="btn btn-light my-2 dismiss_this">Cancel</button>
                                            <button type="button" class="btn btn-light my-2 delete_this">Continue</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @yield('content')

                    </div>

                </div>

            </div>

        </div>

        @include('dashboard.includes.rightbar')

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>

        <!-- Vendor js -->
        <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

        <!-- Plugins js-->
        <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
        <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

        <script src="{{ asset('assets/libs/selectize/js/standalone/selectize.min.js') }}"></script>

        <!-- Dashboar 1 init js-->
        <script src="{{ asset('assets/js/pages/dashboard-1.init.js') }}"></script>

        <!-- App js-->
        <script src="{{ asset('assets/js/app.min.js') }}"></script>

        <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>

        <script src="{{ asset('assets/js/base.js?v=' . Helper::$js_asset_version) }}"></script>

        @yield('js-lib')

    </body>
</html>