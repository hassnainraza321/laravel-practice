@extends('index')
@section('title', Helper::getSiteTitle('Profile'))

@section('content')
<form action="{{ URL::current() }}" method="post" enctype="multipart/form-data" class="ajax-form-submit mt-3">
    <div class="row">
        @include('includes.show-message', ['extra_class' => 'col-xl-10 offset-xl-1 mb-2'])
        <div class="col-xl-10 offset-xl-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body ">
                            {{-- <div class="d-flex justify-content-center mt-2">
                                @if (Auth::user()->image != null)
                                    <img src="{{ asset('storage/'. Auth::user()->image) }}" alt="user-img" class="rounded-circle" style="max-height: 180px; width: 180px;">
                                @else
                                    <span class="avatar-bg p-2 rounded-circle text-uppercase d-flex justify-content-center align-items-center h1">{{ substr(auth()->user()->first_name, 0, 1);  }}{{ substr(auth()->user()->last_name, 0, 1);}}</span>
                                @endif
                            </div> --}}
                            <div class="m-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            @php $index = 'first_name'; @endphp
                                            <label for="{{ $index }}" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="{{ $index }}" name="{{ $index }}" placeholder="Enter your first name" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            @php $index = 'last_name'; @endphp
                                            <label for="{{ $index }}" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="{{ $index }}" name="{{ $index }}" placeholder="Enter your last name" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <div class="upload-img-section mb-3">
                                            <input type="file" id="image" name="image" class="dropify image" data-height="150" data-default-file="{{ isset($data) && !empty($data) ? asset('storage/'.$data->image) : '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            @php $index = 'email'; @endphp
                                            <label class="form-label" for="{{ $index }}">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" placeholder="Enter your email address">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            @php $index = 'phone'; @endphp
                                            <label class="form-label" for="{{ $index }}">Whatsapp Number </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" placeholder="Enter your whatsapp number">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            @php $index = 'last_password'; @endphp
                                            <label for="{{ $index }}" class="form-label">Last Password</label>
                                            <div class="input-group input-group-merge">
                                                <input type="password" id="{{ $index }}" name="{{ $index }}" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" placeholder="Enter your last password">
                                                <div class="input-group-text" data-password="false">
                                                    <span class="password-eye"></span>
                                                </div>
                                                <div class="feedback d-none w-100 d-block"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            @php $index = 'password'; @endphp
                                            <label for="{{ $index }}" class="form-label">New Password</label>
                                            <div class="input-group input-group-merge">
                                                <input type="password" id="{{ $index }}" name="{{ $index }}" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" placeholder="Enter your new password">
                                                <div class="input-group-text" data-password="false">
                                                    <span class="password-eye"></span>
                                                </div>
                                                <div class="feedback d-none w-100 d-block"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="auth-btn">
                                    <button class="btn btn-dark btn-ajax-show-processing mt-2 mw-100" type="submit">
                                        <i class="ri-login-circle-fill me-1"></i>
                                        <span class="spinner-border spinner-border-sm processing-show d-none me-1" role="status" aria-hidden="true"></span>
                                        <span class="processing-show d-none">Saving ...</span>
                                        <span class="default-show">Save</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('css-lib')
    <link href="{{ asset('vendor/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js-lib')
    <script src="{{ asset('vendor/dropify/js/dropify.min.js') }}"></script>
    <script>
        $('.dropify').dropify();
    </script>
@endsection