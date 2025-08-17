@extends('index')
@section('title', Helper::getSiteTitle('Create Template'))

@section('content')
<div class="row mb-2 mt-2">
    <div class="col-xl-10 offset-xl-1">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Create Template</li>
                </ol>
            </div>
            <h4 class="page-title">Create Template</h4>
        </div>
    </div>
</div>
<form action="{{ isset($data) && !empty($data) ? route('template.create', $data->id) : route('template.create') }}" method="post" enctype="multipart/form-data" class="ajax-form-submit">
    <div class="row">
        @include('includes.show-message', ['extra_class' => 'col-xl-10 offset-xl-1 mb-2'])
        <div class="col-xl-10 offset-xl-1">
            <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <div class="my-3">
                                @php 
                                    $index = 'category_id';
                                    $categories = DB::table('template_categories')->where('id', '!=', 0)->orderBy('id', 'asc')->get(); 
                                @endphp
                                <label class="form-label" for="{{ $index }}">Template Category </label>
                                <p>Your template should fall under one of these categories.</p>
                                <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }} {{ $index }}" id="{{ $index }}" name="{{ $index }}">
                                    @if(!$categories->isEmpty())
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ Helper::getInputValue($index, $data) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has($index))
                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                @endif
                            </div>
                            <div class="mb-3">
                                @php $index = 'name'; @endphp
                                <label class="form-label" for="{{ $index }}">Template Name </label>
                                <p>
                                    Name can only be in lowercase alphanumeric characters and underscores. Special characters and white-space are not allowed e.g. - app_verification_code
                                </p>
                                <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                @if ($errors->has($index))
                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                @endif
                            </div>
                            <div class="mb-3">
                                @php 
                                    $index = 'type_id';
                                    $types = DB::table('template_types')->where('id', '!=', 0)->orderBy('id', 'asc')->get(); 
                                @endphp
                                <label class="form-label" for="{{ $index }}">Template Type </label>
                                <p>Your template type should fall under one of these categories.</p>
                                <select class="form-select {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}">
                                    <option value="">Select message type</option>
                                    @if(!$types->isEmpty())
                                        @foreach($types as $type)
                                            {{-- @if ($type->id !== 2 && $type->id !== 3 && $type->id !== 4) --}}
                                                <option value="{{ $type->id }}" {{ Helper::getInputValue($index, $data) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                           {{--  @endif --}}
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has($index))
                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                @endif
                            </div>
                            <div class="mb-3">
                                @php 
                                    $index = 'template_language_id';
                                    $countries = DB::table('countries')->where('is_active', 1)->orderBy('name', 'asc')->select('id', 'languages')->get()->unique('languages'); 
                                @endphp
                                <label for="{{ $index }}" class="form-label">Template Language</label>
                                <p>
                                    You will need to specify the language in which message template is submitted.
                                </p>
                                <select class="form-select select2 {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}">
                                    @if(!$countries->isEmpty())
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ Helper::getInputValue($index, $data) == $country->id ? 'selected' : ($country->languages == 'English' ? 'selected' : '') }}>{{ $country->languages }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if($errors->has($index))
                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                @endif
                            </div>
                            <div class="mb-3 header_text d-none">
                                @php $index = 'header_text'; @endphp
                                <label class="form-label" for="{{ $index }}">Template Header Text (Optional) </label>
                                <p>
                                    Header text is optional and only upto 60 characters are allowed.
                                </p>
                                <input type="text" class="form-control {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" placeholder="Enter header text here">
                                @if ($errors->has($index))
                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                @endif
                            </div>
                            <div class="mb-3 content">
                                @php $index = 'content'; @endphp
                                <label class="form-label" for="{{ $index }}">Template Format </label>
                                <p>
                                    Use text formatting - *bold* , _italic_ & ~strikethrough~
                                    Your message content. Upto 1024 characters are allowed.
                                    e.g. - Hello @{{1}}, your code will expire in @{{2}} mins.
                                </p>
                                <textarea id="{{ $index }}" class="form-control {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" rows="5" placeholder="Enter text here.">{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}</textarea>
                                <input type="hidden" id="placeholders" name="placeholders">
                                <div id="error-message" class="text-danger d-none"></div>
                                @if ($errors->has($index))
                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                @endif
                            </div>
                            @if (!empty($data->sample_value))
                                @foreach (json_decode($data->sample_value) as $key => $value)
                                    <div class="mb-3 sample_value_repeater" id="sample_value_${value}">
                                        <label>Sample Values for {<span>{{ '{' . $value . '}' }}</span>}</label>

                                        @if ($key == 0) 
                                            <p>
                                                Specify sample values for your parameters. These values can be changed at the time of sending.
                                                e.g. - {{1}}: Mohit, {{2}}: 5.
                                            </p>
                                        @endif

                                        <input type="text" class="form-control sample_value_input" name="sample_value[]" value="{{ $value }}" placeholder="Sample value">
                                    </div>
                                @endforeach
                            @else
                                <div class="mb-3 sample_value d-none">
                                    @php $index = 'sample_value'; @endphp
                                    <label for="{{ $index }}" class="form-label">Sample Values</label>
                                    <p>
                                        Specify sample values for your parameters. These values can be changed at the time of sending.
                                        e.g. - @{{1}}: Mohit, @{{2}}: 5.
                                    </p>
                                    <input type="text" id="{{ $index }}" class="form-control {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}[]" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" placeholder="Sample value">
                                    @if ($errors->has($index))
                                        <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                    @endif
                                </div>
                            @endif
                            <div class="mb-3 carousel d-none">
                                @php $index = 'carousel_media_type'; @endphp
                                <label for="{{ $index }}" class="form-label">Carousel Media Type</label>
                                <p>
                                    Your carousel template type should fall under one of these categories.
                                </p>
                                <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}">
                                    <option value="IMAGE" {{ Helper::getInputValue($index, $data) == 'IMAGE' ? 'selected' : '' }}>IMAGE</option>
                                    <option value="VIDEO" {{ Helper::getInputValue($index, $data) == 'VIDEO' ? 'selected' : '' }}>VIDEO</option>
                                </select>
                                @if ($errors->has($index))
                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                @endif
                            </div>
                            <div class="mb-3 carousel d-none">
                                @php $index = 'card_body_text'; @endphp
                                <label for="{{ $index }}" class="form-label">Card 1 Body</label>
                                <p>
                                    Your message content. Upto 160 characters are allowed.
                                </p>
                                <input type="text" id="{{ $index }}" class="form-control {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" placeholder="Enter card 1 body text here">
                                @if ($errors->has($index))
                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                @endif
                            </div>
                            <div class="mb-3 footer_text">
                                @php $index = 'footer_text'; @endphp
                                <label for="{{ $index }}" class="form-label">Template Footer(Optional)</label>
                                <p>
                                    Your message content. Upto 60 characters are allowed.
                                </p>
                                <input type="text" id="{{ $index }}" class="form-control {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" placeholder="Enter footer text here">
                                @if ($errors->has($index))
                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                @endif
                            </div>
                            <div class="mb-4 limited_time_offer d-none">
                                @php $index = 'limited_time_offer'; @endphp
                                <label for="{{ $index }}" class="form-label">Limited Time Offer Text</label>
                                <p>
                                    Your limited time offer message content. Upto 16 characters are allowed.
                                </p>
                                <input type="text" id="{{ $index }}" class="form-control {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" placeholder="Enter your limited time offer text in here...">
                                @if ($errors->has($index))
                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                @endif
                                <div class="mt-3">
                                    @php $index = 'offer_expires'; @endphp
                                    <label for="{{ $index }}" class="form-label">Offer Expires</label>
                                    <input type="checkbox" id="{{ $index }}" name="{{ $index }}" value="1" class="form-check-input ms-5">
                                </div>
                            </div>
                            <div class="mb-3 expiration_warning d-none">
                                @php $index = 'expiration_warning'; @endphp
                                <label for="{{ $index }}" class="form-label">Expiration Warning (Optional)</label>
                                <p>
                                    The time should be between 1 to 90 minutes.
                                </p>
                                <input type="number" min="1" max="90" id="{{ $index }}" class="form-control {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" placeholder="Enter time in minutes...">
                                @if ($errors->has($index))
                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                @endif
                                <div class="mt-3 d-flex">
                                    @php $index = 'security_disclaimer'; @endphp
                                    <div class="form-check form-checkbox-dark">
                                        <input type="checkbox" id="{{ $index }}" name="{{ $index }}" value="1" class="form-check-input me-3" {{ Helper::getInputValue($index, $data) ? 'checked' : '' }}>
                                    </div>
                                    <label for="{{ $index }}" class="form-label">Add Security Disclaimer</label>
                                </div>
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
                                        <div class="p-0 m-0 preview-default-image">
                                            <img src="{{ asset('images/no-cat.svg') }}" class="mx-auto d-block">
                                        </div>
                                        <div class="upload-template-media d-none">
                                            @php $index = 'template_media'; @endphp
                                            <input type="file" id="{{ $index }}" name="{{ $index }}" class="dropify {{ $index }}" data-height="150" data-default-file="{{ isset($data) && !empty($data) ? asset('storage/'.$data->template_media) : '' }}">
                                            <div class="feedback d-none"></div>
                                        </div>
                                        <div class="preview px-3"></div>
                                        @if (!isset($data))
                                            <button type="button" class="btn btn-light w-100 copy-button d-none mb-1"><i class="ri-file-copy-line"></i> <span></span></button>
                                        @endif
                                        @php
                                            $call_to_actions = isset($data) ? DB::table('template_call_to_actions')->where('template_id', $data->id)->get() : collect();
                                        @endphp
                                        @if (!$call_to_actions->isEmpty())
                                            @foreach ($call_to_actions as $index => $call_to_action)
                                                @if (!empty($call_to_action->type) && $call_to_action->type == 'Coupon Code')
                                                    <button type="button" class="btn btn-light w-100 copy-button my-1"><i class="ri-file-copy-line"></i> <span>{{  $call_to_action->button_title }}</span></button>
                                                @endif

                                                @if (!empty($call_to_action->type) && $call_to_action->type == 'Phone Number')
                                                    <a class="btn btn-light w-100 phone-button my-1" target="_blank" data-row="{{ $index }}" href="tel:{{ $call_to_action->button_value }}"><i class="ri-phone-line"></i> <span>{{ $call_to_action->button_title }}</span></a>
                                                @endif

                                                @if (!empty($call_to_action->type) && $call_to_action->type == 'URL')
                                                    <a class="btn btn-light w-100 url-button my-1" target="_blank" data-row="{{ $index }}" href="{{ $call_to_action->button_value }}"><i class="ri-external-link-line"></i> <span>{{ $call_to_action->button_title }}</span></a>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <p>
                                    Disclaimer: This is just a graphical representation of the message that will be delivered. Actual message will consist of media selected and may appear different.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mt-3 mb-3">Interactive Actions</h4>
                            <p class="mb-3">
                                In addition to your message, you can send actions with your message.<br>
                                Maximum 25 characters are allowed in CTA button title & Quick Replies.
                            </p>
                            <div class="row mb-3 px-2">
                                <div class="col-6 col-md-3 form-check form-checkbox-dark">
                                    <input type="radio" name="action" class="form-check-input action-none" value="none">
                                    <label class="form-check-label" for="manual-assign">
                                        <p class="mb-0">None</p>
                                    </label>
                                </div>
                                <div class="col-6 col-md-3 form-check form-checkbox-dark">
                                    <input type="radio" name="action" class="form-check-input action-call" value="call to action">
                                    <label class="form-check-label" for="manual-assign">
                                        <p class="mb-0">Call to Actions</p>
                                    </label>
                                </div>
                                <div class="col-6 col-md-3 form-check form-checkbox-dark">
                                    <input type="radio" name="action" class="form-check-input action-quick" value="quick replies">
                                    <label class="form-check-label" for="manual-assign">
                                        <p class="mb-0">Quick Replies</p>
                                    </label>
                                </div>
                                <div class="col-6 col-md-3 form-check form-checkbox-dark">
                                    <input type="radio" name="action" class="form-check-input all-actions" checked value="all">
                                    <label class="form-check-label" for="manual-assign">
                                        <p class="mb-0">All</p>
                                    </label>
                                </div>
                            </div>
                            @php
                                $template_call_to_actions = collect();

                                if(!empty($data))
                                {
                                    $template_call_to_actions = DB::table('template_call_to_actions')->where('template_id', $data->id)->whereNot('type', 'Quick Reply')->whereNot('type', 'Coupon Code')->orderBy('id', 'asc')->get();
                                }
                            @endphp
                            <div class="row call-to-action-section {{ $template_call_to_actions->isEmpty() ? 'd-none' : '' }}">
                                
                                <table class="table nowrap w-100 border-0 mb-0">
                                    <tbody class="repeater call-to-action-repeater">
                                        @if(!$template_call_to_actions->isEmpty())
                                            @foreach($template_call_to_actions as $call_action)
                                                <tr class="node">
                                                    <td>
                                                        Call to Actions :
                                                    </td>
                                                    <td>
                                                        <select class="form-select" name="type[]">
                                                            <option value="">Select action type</option>
                                                            <option value="Phone Number" {{ !empty($call_action->type) && $call_action->type == 'Phone Number' ? 'selected' : '' }}>Phone Number</option>
                                                            <option value="URL" {{ !empty($call_action->type) && $call_action->type == 'URL' ? 'selected' : '' }}>URL</option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="text" name="button_title[]" class="form-control" value="{{ $call_action->button_title }}">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="text" name="button_value[]" class="form-control" value="{{ $call_action->button_value }}">
                                                        <input type="hidden" name="call_to_action_id[]" value="{{ $call_action->id }}">
                                                    </td>
                                                    <td class="text-right border-0">
                                                        <button type="button" class="btn btn-sm btn-soft-danger mb-1 me-1 delete-repeater-node"><i class="ri-delete-bin-5-line"></i> </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4">
                                                <button type="button" class="btn btn-light call-to-action-insert-repeater" data-repeaterclass="call-to-action-repeater"><i class="ri-add-line fs-18 me-1 lh-1"></i> Call To Action (2)</button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            @php
                                $template_quick_reply = collect();

                                if(!empty($data))
                                {
                                    $template_quick_reply = DB::table('template_call_to_actions')->where('template_id', $data->id)->where('type', 'Quick Reply')->orderBy('id', 'asc')->get();
                                }
                            @endphp
                            <div class="quick-reply-section {{ $template_quick_reply->isEmpty() ? 'd-none' : '' }}">
                                <table class="table nowrap w-50 border-0 mb-0">
                                    <tbody class="repeater quick-reply-repeater">
                                        @if(!$template_quick_reply->isEmpty())
                                            @foreach($template_quick_reply as $call_action)
                                                <tr class="node">
                                                    <td>
                                                        Quick Reply :
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="text" name="quick_reply[]" class="form-control item-bill-rate" value="{{ $call_action->button_title }}">
                                                        <input type="hidden" name="quick_reply_id[]" value="{{ $call_action->id }}">
                                                    </td>
                                                    <td class="text-right border-0">
                                                        <button type="button" class="btn btn-sm btn-soft-danger mb-1 me-1 delete-repeater-node"><i class="ri-delete-bin-5-line"></i> </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4">
                                                <button type="button" class="btn btn-light quick-reply-insert-repeater" data-repeaterclass="quick-reply-repeater"><i class="ri-add-line fs-18 me-1 lh-1"></i> Quick Reply (10)</button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="mb-3 copy-code-section">
                                @php
                                    $template_coupon_code = null;

                                    if(!empty($data))
                                    {
                                        $template_coupon_code = DB::table('template_call_to_actions')->where('template_id', $data->id)->where('type', 'Coupon Code')->orderBy('id', 'asc')->get();
                                    }
                                @endphp
                                <table class="table nowrap w-50 border-0">
                                    <tbody class="repeater copy-code-repeater">
                                        @if(!empty($template_coupon_code) && !$template_coupon_code->isEmpty())
                                            @foreach($template_coupon_code as $call_action)
                                                <tr class="node">
                                                    <td>
                                                        Copy Code :
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="text" name="coupon_code[]" class="form-control copy-code-input" value="{{ $call_action->button_title }}">
                                                        <input type="hidden" name="coupon_code_id[]" value="{{ $call_action->id }}">
                                                    </td>
                                                    <td class="text-right border-0">
                                                        <button type="button" class="btn btn-sm btn-soft-danger mb-1 me-1 delete-repeater-node"><i class="ri-delete-bin-5-line"></i> </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mb-3 actions-button-section">
                                <div class="col-md-3 col-lg-3">
                                    <button type="button" class="btn btn-light mx-auto d-block mb-2 quick-reply-insert-repeater" data-repeaterclass="quick-reply-repeater"><i class="ri-add-line fs-18 me-1 lh-1"></i> Quick Replies (10)</button>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <button type="button" class="btn btn-light mx-auto d-block mb-2 call-to-action-insert-repeater" data-repeaterclass="call-to-action-repeater" data-type="url"><i class="ri-add-line fs-18 me-1 lh-1"></i> URL (2)</button>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <button type="button" class="btn btn-light mx-auto d-block mb-2 call-to-action-insert-repeater" data-repeaterclass="call-to-action-repeater" data-type="phone"><i class="ri-add-line fs-18 me-1 lh-1"></i> Phone Number (1)</button>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <button type="button" class="btn btn-light mx-auto d-block mb-2 copy-code-insert-repeater" disabled data-repeaterclass="copy-code-repeater"><i class="ri-add-line fs-18 me-1 lh-1"></i> Copy Code (1)</button>
                                </div>
                                </div>
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
                        <span class="default-show">Save template</span>
                    </button>
                </div>
                <div class="col-6">
                    @if(!empty($data))
                        <a href="javascript:void(0);" class="btn btn-danger float-right remove-item-button" data-id="{{ $data->id }}">Delete template</a>
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
<meta name="remove-url" content="{{ route('template.remove') }}">
<meta name="class-to-open" content="templates">
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