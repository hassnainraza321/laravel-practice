<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title')</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
	@yield('css-lib')
</head>
<body>
	<div class="mx-5">
		@include('layout.header')
		@yield('content')
		@include('layout.footer')
	</div>
	<div class="dynamic-page-modals"></div>
	
	<script type="text/javascript" src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
	@yield('js-lib')
	<script type="text/javascript" src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/base.js') }}"></script>
	@yield('js')
</body>
</html>