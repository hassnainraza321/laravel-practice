@extends('index')
@section('title', Helper::getSiteTitle('Canned Message'))

@section('content')
<div class="row mb-2 mt-2">
    <div class="col-xl-10 offset-xl-1">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Canned Message</li>
                </ol>
            </div>
            <h4 class="page-title">Canned Message</h4>
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
                                @php $index = 'name'; @endphp
                                <label for="{{ $index }}" class="form-label">Name</label>
                                <p>
                                    Pick a name which describes your message.
                                </p>
                                <input type="text" id="{{ $index }}" class="form-control field_name {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" placeholder="Enter name">
                            </div>
                            <div class="mb-3">
                                @php 
                                    $index = 'message_type';
                                    $types = ['TEXT', 'IMAGE', 'FILE', 'VIDEO', 'AUDIO']; 
                                @endphp
                                <label for="{{ $index }}" class="form-label">Message Type</label>
                                <p>
                                    Select one fo the message types to proceed
                                </p>
                                <select id="{{ $index }}" class="form-select field_{{ $index }}" name="{{ $index }}">
                                    @foreach($types as $type)
                                        <option value="{{ $type }}" {{ Helper::getInputValue('type', $data) == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                @php $index = 'text'; @endphp
                                <label for="{{ $index }}" class="form-label">Text</label>
                                <p>
                                    Use text formatting - *bold* & _italic_
                                    Text can be upto 4096 characters long
                                    Personalize messages with - $FirstName, $Name, $MobileNumber, $LastName & custom attributes.
                                    Customize messages with dynamic parameters e.g. - Your verification code is {{1}}.
                                </p>
                                <textarea rows="4" id="text" name="{{ $index }}" class="form-control">{{ Helper::getInputValue($index, $data) }}</textarea>
                            </div>
                            <div class="mb-3 media_url d-none">
                                @php $index = 'media_url'; @endphp
                                <div class="img_label">
                                    <label for="{{ $index }}" class="form-label">Image</label>
                                    <p>
                                        Share public URL. Size < 5MB, Accepted formats - .png or .jpeg
                                    </p>
                                </div>
                                <div class="doc_label d-none">
                                    <label for="{{ $index }}" class="form-label">Document</label>
                                    <p>
                                        Share public URL. Size < 100MB, Accepted formats - .pdf, .DOCX & .XLSX
                                    </p>
                                </div>
                                <div class="video_label d-none">
                                    <label for="{{ $index }}" class="form-label">Video</label>
                                    <p>
                                        Share public URL. Size < 16MB, Accepted formats - .mp4
                                    </p>
                                </div>
                                <div class="audio_label d-none">
                                    <label for="{{ $index }}" class="form-label">Audio</label>
                                    <p>
                                        Share public URL. Size < 16MB, Accepted formats - .mp3
                                    </p>
                                </div>
                                <input type="url" id="{{ $index }}" class="form-control field_media_url {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" value="{{ isset($data) && !empty($data->media_url) ? asset($data->media_url) : '' }}" placeholder="Enter public name">
                                <p class="mt-3 text-center">OR</p>
                                @php $index = 'media_file'; @endphp
                                <input type="file" id="{{ $index }}" class="form-control field_media_url {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" placeholder="Enter public name">
                            </div>
                            <div class="mb-3 file_name d-none">
                                @php $index = 'file_name'; @endphp
                                <label for="{{ $index }}" class="form-label">File Name</label>
                                <p>
                                    Display name of media file, visible on download.
                                </p>
                                <input type="text" id="{{ $index }}" class="form-control field_file_name {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" placeholder="Enter file name">
                            </div>
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
                                        @if (isset($data) && !empty($data->media_url))
                                            <input type="file" class="dropify" data-height="150" data-default-file="{{ isset($data) && !empty($data) ? asset('storage/'.$data->media_url) : '' }}" disabled>
                                        @else
                                            <div class="p-0 m-0">
                                                <img src="{{ asset('images/no-cat.svg') }}" class="mx-auto d-block">
                                            </div>
                                        @endif
                                        <div class="preview p-2">
                                            @if (isset($data) && !empty($data->text))
                                                <div>{!! nl2br(e($data->text)) !!}</div>
                                            @endif
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
                        <span class="processing-show d-none">Saving...</span>
                        <span class="default-show">Save</span>
                    </button>
                </div>
                <div class="col-6">
                    @if(!empty($data))
                        <a href="javascript:void(0);" class="btn btn-danger float-right remove-item-button" data-id="{{ $data->id }}">Delete message</a>
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
<meta name="remove-url" content="{{ route('canned.message.remove') }}">
<meta name="class-to-open" content="canned-messages">
@endsection

@section('css-lib')
    <link href="{{ asset('vendor/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js-lib')
    <script src="{{ asset('vendor/dropify/js/dropify.min.js') }}"></script>
    <script>
        $('.dropify').dropify({
            disabled: true
        });
    </script>
@endsection