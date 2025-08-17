@extends('dashboard.index')
@section('title', Helper::getSiteTitle('Packages'))

@section('css-lib')
    <link href="{{ asset('assets/libs/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Packages</li>
                    </ol>
                </div>
                <h4 class="page-title">Add Packages</h4>
            </div>
        </div>
    </div>
    @php $user = Auth::user(); @endphp
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body ">
                    <div class="d-flex justify-content-center">
                        @if (Auth::user()->image != null)
                            <img src="{{ asset('storage/'. Auth::user()->image) }}" alt="user-img" class="rounded-circle" style="max-height: 180px; width: 180px;">
                        @else
                            <span class="text-white bg-primary p-2 rounded-circle text-uppercase d-flex justify-content-center align-items-center h1" style="height: 180px; width: 180px;">{{ substr(Auth::user()->username, 0, 1); }}</span>
                        @endif
                        {{-- <img src="{{ asset('assets/images/users/user-1.jpg') }}" class="rounded-circle"> --}}
                    </div>
                    <form id="user_form" action="{{ route('profile') }}" method="post" class="p-4" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input class="form-control" type="text" id="name" name="name" placeholder="Enter your name" @if(isset($user->name)) value="{{ $user->name }}" @endif>
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Userame</label>
                                    <input class="form-control" type="text" id="username" name="username" placeholder="Enter your username" @if(isset($user->username)) value="{{ $user->username }}" @endif>
                                    <span class="text-danger">
                                        @error('username')
                                        {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <div class="w-50">
                                    <input type="file" name="image" data-plugins="dropify" data-height="150" @if(isset($user->image)) data-default-file="{{ asset('storage/'.$user->image) }}" @endif>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input class="form-control" type="email" id="email" name="email" placeholder="Enter your email" @if(isset($user->email)) value="{{ $user->email }}" @endif>
                            <span class="text-danger">
                                @error('email')
                                {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="last_password" class="form-label">Last Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="last_password" name="last_password" class="form-control" placeholder="Enter your last password">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 ">
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your new password">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center d-flex justify-content-center">
                            <button id="submit_btn" data-form_id="user_form" class="btn btn-primary waves-effect waves-light w-25" type="submit"> Save </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js-lib')
    <script src="{{ asset('assets/libs/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
@endsection
