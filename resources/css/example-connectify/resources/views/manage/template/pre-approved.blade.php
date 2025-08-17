@extends('index')
@section('title', Helper::getSiteTitle('Pre-Approved Templates'))

@section('content')
<div class="row mb-2 mt-2">
    <div class="col-xl-10 offset-xl-1">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pre-Approved Templates</li>
                </ol>
            </div>
            <h4 class="page-title">Pre-Approved Templates</h4>
        </div>
    </div>
</div>
<div class="col-xl-10 offset-xl-1">
    @if(!$templates->isEmpty())
    <div class="row">
        @foreach($templates as $template)
            <div class="col-md-6 col-lg-3">
                <div class="card my-3">
                    <span>
                        <img src="{{ asset('images/whatsapp-icon.png') }}" alt="whatsapp icon" class="preview-whatsapp-icon">
                    </span>
                    <div class="card-body p-2">
                        <div class="p-0 m-0">
                            <img src="{{ asset('images/no-cat.svg') }}" class="mx-auto d-block w-50">
                        </div>
                        <div class="preview p-2">
                            <h4 class="text-center">{{ $template->name }}</h4>
                            <p>{{ $template->header_text }}</p>
                            <p>{{ $template->content }}</p>
                            <p>{{ $template->footer_text }}</p>
                        </div>
                        <div class="text-center">
                            @php
                                $call_to_actions = DB::table('template_call_to_actions')->where('template_id', $template->id)->get();
                            @endphp
                            @if (!$call_to_actions->isEmpty())
                                @foreach ($call_to_actions as $call_to_action)
                                    <button class="btn btn-soft-dark w-75">{{ $call_to_action->button_title }}</button>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('template.create', $template->reference_id) }}" class="btn btn-dark w-100">Preview and Submit <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @else
    <div class="card my-3">
        <div class="card-body"> 
            <h4 class="text-center">Templates Not Found!</h4>
        </div>
    </div>
    @endif
</div>
@endsection 

@section('meta')
<meta name="class-to-open" content="templates">
@endsection