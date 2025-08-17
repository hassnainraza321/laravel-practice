@extends('index')
@section('title', Helper::getSiteTitle('Chat Settings'))

@section('content')
<div class="row mb-2 mt-2">
    <div class="col-xl-10 offset-xl-1">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Chat Settings</li>
                </ol>
            </div>
            <label class="page-title">Chat Settings</label>
        </div>
    </div>
</div>
<div class="row">
    @include('includes.show-message', ['extra_class' => 'col-xl-10 offset-xl-1 mb-2'])
    <div class="col-xl-10 offset-xl-1">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h3 class="mt-3">Quick Guide</h3>
                            <p>You can personalize the Auto-Resolving feature for users who have been inactive for over 24 hours.</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h3 class="mt-3">Auto Resolve Chats</h3>
                            <div class="col-9">
                                <p>Disable auto resolve intervened chats.</p>
                            </div>
                            <div class="col-3 form-check form-checkbox-dark form-switch form-switch-md" dir="ltr">
                                <input type="checkbox" class="form-check-input float-end" value="1" name="auto_resolve_chat" id="auto_resolve_chat" {{ !empty($chat_setting) && $chat_setting->auto_resolve_chat == 1 ? 'checked' : '' }} data-url="{{ route('chat.settings') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="row mt-3">
                                    <div class="col-7 d-flex justify-content-between align-items-center mb-3">
                                        <h3>Welcome Message</h3>
                                        <div class="form-check form-checkbox-dark form-switch form-switch-md ">
                                            <input type="checkbox" class="form-check-input" value="1" name="welcome_message" id="welcome_message" {{ !empty($chat_setting) && $chat_setting->welcome_message == 1 ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <a class="btn btn btn-soft-dark float-end config-welcome-btn fetch-dynamic-modal" data-url="{{ route('get.chat.configuration', 'Welcome Message') }}">Configure</a>
                                    </div>
                                    <p>Set up an automated response for a user's initial inquiry during business hours.</p>
                                </div>
                                <div class="row d-md-flex justify-content-center">
                                    <div class="col-sm-8">
                                        <div class="card shadow-md mt-3">
                                            <span>
                                                <img src="{{ asset('images/whatsapp-icon.png') }}" alt="whatsapp icon" class="preview-whatsapp-icon">
                                            </span>
                                            @php
                                                $wel_msg_data = DB::table('live_chat_configurations')->leftJoin('live_chat_settings', 'live_chat_settings.id', '=', 'live_chat_configurations.live_chat_setting_id')->leftJoin('templates', 'templates.id', '=', 'live_chat_configurations.template_id')->where('live_chat_settings.project_id', Helper::getProjectId())->where('live_chat_configurations.chat_type', 'Welcome Message')->select('live_chat_configurations.template_message', 'templates.*')->first();
                                            @endphp
                                            <div class="card-body p-2">
                                                <div class="preview">
                                                    @if (!empty($wel_msg_data))
                                                        @if (!empty($wel_msg_data->template_message))
                                                            <div class="mt-2">{!! nl2br(e($wel_msg_data->template_message)) !!}</div>
                                                        @else
                                                            <p class="mt-2">{{ $wel_msg_data->header_text }}</p>
                                                            <p>{{ $wel_msg_data->content }}</p>
                                                            <p>{{ $wel_msg_data->footer_text }}</p>
                                                        @endif
                                                    @else
                                                        <p class="mt-2 welcome-msg">Hello! We appreciate you reaching out. A team member will contact you shortly.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-center">Your auto response is disabled</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 border-start">
                                <div class="row mt-3">
                                    <div class="col-7 d-flex justify-content-between align-items-center mb-3">
                                        <h3>Off Hours Message</h3>
                                        <div class="form-check form-checkbox-dark form-switch form-switch-md ">
                                            <input type="checkbox" class="form-check-input" value="1" name="off_hours_message" id="off_hours_message" {{ !empty($chat_setting) && $chat_setting->off_hours_message == 1 ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <a class="btn btn btn-soft-dark float-end off-hour-btn fetch-dynamic-modal" data-url="{{ route('get.chat.configuration', 'Off Hours Message') }}">Configure</a>
                                    </div>
                                    <p>Set up an automated response for a user's initial inquiry during business hours.</p>
                                </div>
                                <div class="row d-md-flex justify-content-center">
                                    <div class="col-sm-8">
                                        <div class="card shadow-md mt-3">
                                            <span>
                                                <img src="{{ asset('images/whatsapp-icon.png') }}" alt="whatsapp icon" class="preview-whatsapp-icon">
                                            </span>
                                            @php
                                                $off_hour_data = DB::table('live_chat_configurations')->leftJoin('live_chat_settings', 'live_chat_settings.id', '=', 'live_chat_configurations.live_chat_setting_id')->leftJoin('templates', 'templates.id', '=', 'live_chat_configurations.template_id')->where('live_chat_settings.project_id', Helper::getProjectId())->where('live_chat_configurations.chat_type', 'Off Hours Message')->select('live_chat_configurations.template_message', 'templates.*')->first();
                                            @endphp
                                            <div class="card-body p-2">
                                                <div class="preview">
                                                    @if (!empty($off_hour_data))
                                                        @if (!empty($off_hour_data->template_message))
                                                            <div class="mt-2">{!! nl2br(e($off_hour_data->template_message)) !!}</div>
                                                        @else
                                                            <p class="mt-2">{{ $off_hour_data->header_text }}</p>
                                                            <p>{{ $off_hour_data->content }}</p>
                                                            <p>{{ $off_hour_data->footer_text }}</p>
                                                        @endif
                                                    @else
                                                        <p class="mt-2 off-hour-msg">Hello! Thanks for reaching out. Our team is currently unavailable but will be back tomorrow at 9 AM.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-center">Your auto response is disabled</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form class="ajax-form-submit" action="{{ route('chat.working.hours') }}" method="post">
                            @csrf
                            <div class="row px-3">
                                <h3 class="my-3">Working Hours</h3>
                                <p>Configure day-wise working hours for automated replies</p>
                                <div class="col-12 form-checkbox-dark">
                                    <div class="row align-items-center mb-3">
                                        @php $index = 'timezone'; @endphp
                                        <div class="col-3 col-md-4 col-lg-2">
                                            <p class="mb-0">Timezone</p>
                                        </div>
                                        <div class="col-9 col-md-8 col-lg-6">
                                            <select id="{{ $index }}" class="form-select select2 {{ $index }}" name="{{ $index }}">
                                                <option value="Asia/Karachi">Asia/Karachi (+05:00) 🇵🇰 Pakistan</option>
                                                <option value="Asia/Dubai">Asia/Dubai (+04:00) 🇦🇪 UAE</option>
                                                <option value="Asia/Calcutta">Asia/Kolkata (+05:30) 🇮🇳 India</option>
                                                <option value="Asia/Shanghai">Asia/Shanghai (+08:00) 🇨🇳 China</option>
                                                <option value="Asia/Tokyo">Asia/Tokyo (+09:00) 🇯🇵 Japan</option>
                                                <option value="Asia/Jakarta">Asia/Jakarta (+07:00) 🇮🇩 Indonesia</option>
                                                <option value="Asia/Singapore">Asia/Singapore (+08:00) 🇸🇬 Singapore</option>
                                                <option value="Asia/Seoul">Asia/Seoul (+09:00) 🇰🇷 South Korea</option>
                                                <option value="Asia/Manila">Asia/Manila (+08:00) 🇵🇭 Philippines</option>
                                                <option value="Asia/Kuala_Lumpur">Asia/Kuala_Lumpur (+08:00) 🇲🇾 Malaysia</option>
                                                <option value="Europe/London">Europe/London (+00:00) 🇬🇧 UK</option>
                                                <option value="Europe/Dublin">Europe/Dublin (+00:00) 🇮🇪 Ireland</option>
                                                <option value="Europe/Berlin">Europe/Berlin (+01:00) 🇩🇪 Germany</option>
                                                <option value="Europe/Paris">Europe/Paris (+01:00) 🇫🇷 France</option>
                                                <option value="Europe/Amsterdam">Europe/Amsterdam (+01:00) 🇳🇱 Netherlands</option>
                                                <option value="Europe/Madrid">Europe/Madrid (+01:00) 🇪🇸 Spain</option>
                                                <option value="Europe/Stockholm">Europe/Stockholm (+01:00) 🇸🇪 Sweden</option>
                                                <option value="Europe/Zurich">Europe/Zurich (+01:00) 🇨🇭 Switzerland</option>
                                                <option value="Europe/Moscow">Europe/Moscow (+03:00) 🇷🇺 Russia</option>
                                                <option value="America/New_York">America/New_York (-05:00) 🇺🇸 USA (Eastern)</option>
                                                <option value="America/Chicago">America/Chicago (-06:00) 🇺🇸 USA (Central)</option>
                                                <option value="America/Denver">America/Denver (-07:00) 🇺🇸 USA (Mountain)</option>
                                                <option value="America/Los_Angeles">America/Los_Angeles (-08:00) 🇺🇸 USA (Pacific)</option>
                                                <option value="America/Toronto">America/Toronto (-05:00) 🇨🇦 Canada</option>
                                                <option value="America/Vancouver">America/Vancouver (-08:00) 🇨🇦 Canada</option>
                                                <option value="America/Mexico_City">America/Mexico_City (-06:00) 🇲🇽 Mexico</option>
                                                <option value="America/Sao_Paulo">America/Sao_Paulo (-03:00) 🇧🇷 Brazil</option>
                                                <option value="America/Buenos_Aires">America/Buenos_Aires (-03:00) 🇦🇷 Argentina</option>
                                                <option value="Africa/Lagos">Africa/Lagos (+01:00) 🇳🇬 Nigeria</option>
                                                <option value="Africa/Johannesburg">Africa/Johannesburg (+02:00) 🇿🇦 South Africa</option>
                                                <option value="Africa/Cairo">Africa/Cairo (+02:00) 🇪🇬 Egypt</option>
                                                <option value="Africa/Nairobi">Africa/Nairobi (+03:00) 🇰🇪 Kenya</option>
                                                <option value="Australia/Sydney">Australia/Sydney (+11:00) 🇦🇺 Australia</option>
                                                <option value="Australia/Melbourne">Australia/Melbourne (+11:00) 🇦🇺 Australia</option>
                                                <option value="Australia/Brisbane">Australia/Brisbane (+10:00) 🇦🇺 Australia</option>
                                                <option value="Pacific/Auckland">Pacific/Auckland (+13:00) 🇳🇿 New Zealand</option>
                                            </select>
                                            <input type="hidden" id="time_zone" value="{{ $chat_working_hours->first()->timezone ?? '' }}" hidden>
                                        </div>
                                    </div>
                                    @if (!$chat_working_hours->isEmpty())
                                        @foreach ($chat_working_hours as $working_hour)
                                            <div class="row mb-2 align-items-center">
                                                @php $index = strtolower($working_hour->day); @endphp
                                                <div class="col-2">
                                                    <p class="mb-0">{{ $working_hour->day }}</p>
                                                </div>
                                                <div class="col-2">
                                                    <div class="col-md-3 form-check form-switch form-switch-md" dir="ltr">
                                                        <input type="checkbox" class="form-check-input {{ $index }}" value="1" name="status_{{ $index }}" id="" {{ $working_hour->status == 1 ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-8 col-lg-4 {{ $index }}-timing my-2 my-md-0">
                                                    <p class="text-center mb-0">Closed</p>
                                                    <div class="d-none d-flex justify-content-center align-items-center">
                                                        <input type="time" name="start_time_{{ $index }}" class="form-control" value="{{ $working_hour->start_time }}">
                                                        <p class="px-3 mb-0">to</p>
                                                        <input type="time" name="end_time_{{ $index }}" class="form-control" value="{{ $working_hour->end_time }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <button type="submit" class="btn btn-dark btn-ajax-show-processing my-3">
                                        <span class="processing-show d-none spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>
                                        <i class="default-show mdi mdi-content-save-move me-1"></i>
                                        <span class="processing-show d-none">{{ __('Saving') }}...</span>
                                        <span class="default-show">{{ __('Save Configuration') }}</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="row mt-3">
                                    <div class="col-7 d-flex justify-content-between align-items-center mb-1">
                                        <h3>Birthday Message</h3>
                                        <div class="form-check form-checkbox-dark form-switch form-switch-md ">
                                            <input type="checkbox" class="form-check-input" value="1" name="birthday_message" id="birthday_message" {{ !empty($chat_setting) && $chat_setting->birthday_message == 1 ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <p>
                                        Set up an automated birthday message for users to be sent during business hours.
                                    </p>
                                    <div class="mb-3">
                                        <label for="field-3" class="form-label">{{ __('Select Time') }} </label>
                                        <p>Select Campaign time to send.</p>
                                        <select id="category-select" class="form-control mt-2 w-75" id="" name="">
                                            <option value="12 AM">12 AM</option>
                                            <option value="1 AM">1 AM</option>
                                            <option value="2 AM">2 AM</option>
                                            <option value="3 AM">3 AM</option>
                                            <option value="4 AM">4 AM</option>
                                            <option value="5 AM">5 AM</option>
                                            <option value="6 AM">6 AM</option>
                                            <option value="7 AM">7 AM</option>
                                            <option value="8 AM">8 AM</option>
                                            <option value="9 AM">9 AM</option>
                                            <option value="10 AM">10 AM</option>
                                            <option value="11 AM">11 AM</option>
                                            <option value="12 PM">12 PM</option>
                                            <option value="1 PM">1 PM</option>
                                            <option value="2 PM">2 PM</option>
                                            <option value="3 PM">3 PM</option>
                                            <option value="4 PM">4 PM</option>
                                            <option value="5 PM">5 PM</option>
                                            <option value="6 PM">6 PM</option>
                                            <option value="7 PM">7 PM</option>
                                            <option value="8 PM">8 PM</option>
                                            <option value="9 PM">9 PM</option>
                                            <option value="10 PM">10 PM</option>
                                            <option value="11 PM">11 PM</option>
                                        </select>
                                    </div>
                                    <div class="col-7 d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label">Skip Opted-out users</label>
                                        <div class="form-check form-checkbox-dark form-switch form-switch-md ">
                                            <input type="checkbox" class="form-check-input" value="1" name="" id="">
                                        </div>
                                    </div>
                                    <p>
                                        Activate this option to exclude opted-out contacts from receiving birthday campaigns.
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 border-start">
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <a class="btn btn btn-soft-dark float-end birthday-message-btn fetch-dynamic-modal" data-url="{{ route('get.chat.configuration', 'Birthday Message') }}">Configure</a>
                                    </div>
                                </div>
                                @php
                                    $birthday_data = DB::table('live_chat_configurations')->leftJoin('live_chat_settings', 'live_chat_settings.id', '=', 'live_chat_configurations.live_chat_setting_id')->leftJoin('templates', 'templates.id', '=', 'live_chat_configurations.template_id')->where('live_chat_settings.project_id', Helper::getProjectId())->where('live_chat_configurations.chat_type', 'Birthday Message')->select('live_chat_configurations.template_message', 'templates.*')->first();
                                @endphp
                                <div class="row d-md-flex justify-content-center">
                                    <div class="col-sm-8">
                                        <div class="card shadow-md mt-3">
                                            <span>
                                                <img src="{{ asset('images/whatsapp-icon.png') }}" alt="whatsapp icon" class="preview-whatsapp-icon">
                                            </span>
                                            <div class="card-body p-2">
                                                <div class="p-0 m-0">
                                                    <img src="{{ asset('images/no-cat.svg') }}" class="mx-auto d-block">
                                                </div>
                                                <div class="preview">
                                                    @if (!empty($birthday_data))
                                                        @if (!empty($birthday_data->template_message))
                                                            <div class="mt-2">{!! nl2br(e($birthday_data->template_message)) !!}</div>
                                                        @else
                                                            <p class="mt-2">{{ $birthday_data->header_text }}</p>
                                                            <p>{{ $birthday_data->content }}</p>
                                                            <p>{{ $birthday_data->footer_text }}</p>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-center">Your auto response is disabled</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('manage.chat-settings.modals.config-message')
@endsection

@section('meta')
<meta name="class-to-open" content="chat-settings">
@endsection
