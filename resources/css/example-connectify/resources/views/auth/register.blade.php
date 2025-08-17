@extends('auth.index')
@section('title', Helper::getSiteTitle('Login'))

@section('content')
<div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-8 col-lg-10">
                <div class="card overflow-hidden">
                    <div class="row g-0">
                        <div class="col-lg-12">
                            <div class="d-flex flex-column">
                                <div class="auth-brand px-5 py-4 text-center">
                                    <a href="{{ route('dashboard') }}" class="logo-light">
                                        <img src="{{ asset('images/logo-dark.png') }}" alt="logo">
                                    </a>
                                </div>
                                <div class="px-5 py-4 my-auto">
                                    <form action="{{ URL::current() }}" method="post" enctype="multipart/form-data" class="ajax-form-submit">
                                        @include('includes.show-message', ['extra_class' => 'mb-3'])
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                @php $index = 'first_name'; @endphp
                                                <label class="form-label" for="{{ $index }}">First Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" placeholder="Enter first name">
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                @php $index = 'last_name'; @endphp
                                                <label class="form-label" for="{{ $index }}">Last Name </label>
                                                <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" placeholder="Enter last name">
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                @php $index = 'email'; @endphp
                                                <label class="form-label" for="{{ $index }}">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" placeholder="Enter your email address">
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                @php $index = 'whatsapp_number'; @endphp
                                                <label class="form-label" for="{{ $index }}">Whatsapp Number <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" placeholder="Enter your whatsapp number">
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                @php $index = 'password'; @endphp
                                                <label class="form-label" for="{{ $index }}">Password <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" placeholder="Enter your password">
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                @php $index = 'password_confirmation'; @endphp
                                                <label class="form-label" for="{{ $index }}">Confirm Password <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" placeholder="Confirm your password">
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-0 d-flex flex-column align-items-center text-center auth-btn">
                                            <button class="btn btn-dark btn-ajax-show-processing w-50 mt-2" type="submit">
                                                <i class="ri-login-circle-fill me-1"></i>
                                                <span class="spinner-border spinner-border-sm processing-show d-none me-1" role="status" aria-hidden="true"></span>
                                                <span class="processing-show d-none">Loading ...</span>
                                                <span class="default-show">Sign up</span>
                                            </button>
                                            <div class="d-flex align-items-center w-50 my-3">
                                                <hr class="flex-grow-1">
                                                <span class="mx-2 fw-bold text-muted">OR</span>
                                                <hr class="flex-grow-1">
                                            </div>
                                            <a href="{{ route('social.redirect', 'google') }}" class="btn btn-soft-danger w-50 mb-2"><i class="bi-google me-1"></i> Continue with Google</a>
                                            <a href="{{ route('social.redirect', 'facebook') }}" class="btn btn-soft-primary w-50 mb-4"><i class="bi-facebook me-1"></i> Continue with Facebook</a>
                                            <div>
                                                <p>Already have account? <a href="{{ route('login') }}" class="text-primary">Log In</a></p>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection