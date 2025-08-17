@extends('index')
@section('title', Helper::getSiteTitle('API Campaign'))

@section('content')
<div class="row mb-2 mt-2">
    <div class="col-xl-10 offset-xl-1">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">API Campaign</li>
                </ol>
            </div>
            <h4 class="page-title">API Campaign</h4>
        </div>
    </div>
</div>
<form action="{{ URL::current() }}" method="post" enctype="multipart/form-data" class="ajax-form-submit">
    <div class="row">
        @include('includes.show-message', ['extra_class' => 'col-xl-10 offset-xl-1 mb-2'])
        <div class="col-xl-10 offset-xl-1">
            <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body api-name-section">
                            <div class="my-3">
                                @php $index = 'campaign_name'; @endphp
                                <label for="{{ $index }}" class="form-label">Campaign Name</label>
                                <p>
                                    Pick something that describes your audience & goals.
                                </p>
                                <input type="text" id="{{ $index }}" class="form-control campaign_name {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" value="" placeholder="Enter name">
                                @if ($errors->has($index))
                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="{{ $index }}" class="form-label">Select Audience</label>
                                <p>
                                    Pick your target group audience to follow up with.
                                </p>
                                @php
                                    $contacts = DB::table('contacts')
                                            ->where('project_id', Helper::getProjectId(request('ref_id')))
                                            ->orderBy('name', 'asc')
                                            ->get();
                                @endphp
                                <select class="select2" id="campaign_contact" name="campaign_contact[]" multiple="multiple">
                                  @if (!$contacts->isEmpty())
                                      @foreach ($contacts as $contact)
                                          <option value="{{ $contact->id }}">{{ $contact->name . ' ('.$contact->whatsapp_number.')' }}</option>
                                      @endforeach
                                  @endif
                                </select>
                            </div>
                            <div class="mb-3">
                                @php $index = 'template_id'; @endphp
                                <label for="{{ $index }}" class="form-label">Template Name</label>
                                <p>
                                    Select one from your WhatsApp approved template messages
                                </p>
                                @php
                                    $templates = DB::table('templates')->where('project_id', Helper::getProjectId(request('ref_id')))->where('status', 'APPROVED')->where('id', '!=', 0)->get();
                                @endphp
                                <select id="{{ $index }}" class="form-select select2" name="{{ $index }}" data-url="{{ route('template.create') }}">
                                    <option value="">Search Template</option>
                                    @if (!$templates->isEmpty())
                                        @foreach ($templates as $template)
                                            <option value="{{ $template->id }}">{{ $template->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="{{ $index }}"></span>
                            </div>
                            <div class="content"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mt-3 mb-3">Template Preview</h4>
                            <p>Your template message preview. It will update as you fill in the values in the form.</p>
                            <div class="mb-3">
                                @php $index = 'preview'; @endphp
                                <div class="card preview-card">
                                    <span>
                                        <img src="{{ asset('images/whatsapp-icon.png') }}" alt="whatsapp icon" class="preview-whatsapp-icon">
                                    </span>
                                    <div class="card-body p-2">
                                        <div class="p-0 m-0">
                                            <img src="{{ asset('images/no-cat.svg') }}" class="mx-auto d-block">
                                        </div>
                                        <div class="preview">
                                            
                                        </div>
                                    </div>
                                </div>
                                <p>
                                    Disclaimer: This is just a graphical representation of the message that will be delivered. Actual message will consist of media selected and may appear different.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <button class="btn btn-custom btn-ajax-show-processing me-1" type="submit">
                        <span class="spinner-border spinner-border-sm processing-show d-none me-1" role="status" aria-hidden="true"></span>
                        <span class="processing-show d-none">Sending...</span>
                        <span class="default-show">Set Live</span>
                    </button>
                </div>
                <div class="col-6">
                    @if(!empty($data))
                        <a href="javascript:void(0);" class="btn btn-danger float-right remove-item-button" data-id="{{ $data->id }}">Delete campaign</a>
                    @else
                        <a href="" class="btn btn-light float-right">Discard</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('meta')
<meta name="remove-url" content="{{ route('campaigns.remove') }}">
<meta name="class-to-open" content="campaigns">
@endsection