@extends('index')
@section('title', Helper::getSiteTitle('CSV Campaign'))

@section('content')
<div class="row mb-2 mt-2">
    <div class="col-xl-10 offset-xl-1">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">CSV Campaign</li>
                </ol>
            </div>
            <label class="page-title">CSV Campaign</label>
        </div>
    </div>
</div>
<form action="{{ URL::current() }}" method="post" enctype="multipart/form-data" class="ajax-form-submit">
    <div class="row">
        @include('includes.show-message', ['extra_class' => 'col-xl-10 offset-xl-1 mb-2'])
        <div class="col-xl-10 offset-xl-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row broadcast-page">
                                <div class="col-sm-3 my-1">
                                    <div class="d-flex align-items-center">
                                        <label>Quality Rating</label>
                                        <button type="button" class="tooltip-btn px-3" data-toggle="tooltip" data-placement="top" title="Your WhatsApp Business Account’s quality rating (as assessed by WhatsApp) is High, as the messages that you have been sending to your customers in the last 7 days have been of good quality. Know More">
                                          <i class="ri-information-fill"></i>
                                        </button>
                                        <label><span class="badge rounded-pill bg-success">High</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-5 my-1">
                                    <div class="d-flex align-items-center">
                                        <label>Template Messaging Tier</label>
                                        <button type="button" class="tooltip-btn px-3" data-toggle="tooltip" data-placement="top" title="Messaging limits determine how many unique users your business can send template messages on a daily basis. - Get to next tier">
                                          <i class="ri-information-fill"></i>
                                        </button>
                                        <label>Tier 1 (1K/24 Hours)</label>
                                    </div>
                                </div>
                                <div class="col-sm-4 my-1">
                                    <div class="d-flex align-items-center">
                                        <label>Remaining Quota</label>
                                        <button type="button" class="tooltip-btn px-3" data-toggle="tooltip" data-placement="top" title="Number of unique users you can send template messages.">
                                          <i class="ri-information-fill"></i>
                                        </button>
                                        <label>1000</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row broadcast-page">
                                <div class="col-sm-12 px-md-5 broadcast-form-progress">
                                    <div class="position-relative-custom">
                                        <div class="custom-progress">
                                            <div class="custom-progress-bar" id="custom-progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>

                                        <button type="button" class="custom-button custom-button-active top-0-custom start-0-custom" data-progress="0">1</button>
                                        <div class="custom-label start-0-custom">Campaign Name</div>

                                        <button type="button" class="custom-button custom-button-inactive top-0-custom start-25-custom" data-progress="25">2</button>
                                        <div class="custom-label start-25-custom">Upload CSV</div>

                                        <button type="button" class="custom-button custom-button-inactive top-0-custom start-50-custom" data-progress="50">3</button>
                                        <div class="custom-label start-50-custom">Create Message</div>

                                        <button type="button" class="custom-button custom-button-inactive top-0-custom start-75-custom" data-progress="75">4</button>
                                        <div class="custom-label start-75-custom">Test Campaign</div>

                                        <button type="button" class="custom-button custom-button-inactive top-0-custom start-100-custom" data-progress="100">5</button>
                                        <div class="custom-label start-100-custom minw-100">Preview & Send</div>
                                    </div>
                                </div>
                                <div class="col-sm-12 broadcast-form-section">
                                    <div class="row campaign-name-section">
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                @php $index = 'campaign_name'; @endphp
                                                <label for="{{ $index }}" class="form-label">Campaign Name</label>
                                                <p>
                                                    Pick something that describes your audience & goals.
                                                </p>
                                                <input type="text" id="{{ $index }}" class="form-control field_campaign_name {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" value="" placeholder="Enter name">
                                            </div>
                                            <div class="mb-3 form-checkbox-dark">
                                                @php $index = 'name'; @endphp
                                                <label for="{{ $index }}" class="form-label">Message Type</label>
                                                <p>
                                                    Send template message from one of your pre approved templates. {{ Helper::$allow_regular_message === 1 ? 'You can also opt to send regular message to active users.' : '' }}
                                                </p>
                                                <div class="row mb-2">
                                                    <div class="col-1">
                                                        <input type="radio" name="message_type" class="form-check-input action-none" value="0" checked>
                                                    </div>
                                                    <div class="col-11">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Pre-approved template message
                                                        </label>
                                                    </div>
                                                </div>
                                                @if (Helper::$allow_regular_message === 1)
                                                    <div class="row">
                                                        <div class="col-1">
                                                            <input type="radio" name="message_type" class="form-check-input action-none" value="1">
                                                        </div>
                                                        <div class="col-11">
                                                            <label class="form-check-label" for="flexRadioDefault1">
                                                                Regular Message
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row select-audience-section d-none">
                                        <div class="col-md-7 audience-input pe-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <h4 class="mb-3">CSV Headers</h4>
                                                    <div class="csv-header">
                                                        <label class="mb-3">PK</label>
                                                    </div>
                                                    <h4 class="mb-3">Options</h4>
                                                    <div class="d-flex align-items-center align-items-center mb-2">
                                                        <label>Default Country Code</label>
                                                        <button type="button" class="tooltip-btn px-3" data-toggle="tooltip" data-placement="top" title="Default value for contacts with missing Country code.">
                                                          <i class="ri-information-fill"></i>
                                                        </button>
                                                    </div>
                                                    <div class="d-flex align-items-center align-items-center mb-3">
                                                        <label for="replace-tags">Replace Tags</label>
                                                        <button type="button" class="tooltip-btn px-3" data-toggle="tooltip" data-placement="top" title="Enable if you want to replace the existing tags.">
                                                          <i class="ri-information-fill"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <h4 class="text-center mb-3">Map to Attribute</h4>
                                                    <div class="mb-4 map-to-attribute">
                                                        <select class="form-select" name="mapped_attribute[]">
                                                            <option value="user name">user name</option>
                                                            <option value="phone number">phone number</option>
                                                            <option value="tags">tags</option>
                                                        </select>
                                                    </div>
                                                    <div class="my-3">
                                                        @php
                                                            $index = 'default_country_code';
                                                            $countries = DB::table('countries')->where('is_active', 1)->orderBy('name', 'asc')->get();
                                                        @endphp
                                                        <select class="form-select select2 {{ $index }}" name="{{ $index }}">
                                                            @foreach ($countries as $country)
                                                                <option value="{{ $country->phone_code }}" {{ $country->flag == 'PK' ? 'selected' : '' }} data-flag="{{ $country->flag }}">
                                                                    {{ $country->flag }} {{ $country->phone_code }} {{ $country->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" name="default_country_flag" class="default-country-flag" value="">
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check form-switch form-checkbox-dark form-switch-md me-5" dir="ltr">
                                                            <input type="checkbox" class="form-check-input" value="1" name="replace_tag" id="replace-tags">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <label>Notes:</label>
                                                    <ul>
                                                        <li>Provide the header name from your file that matches the specified field. Leave it blank if the field is not available in the file.</li>
                                                        <li>If a contact number already exists, it will be skipped during the import process.</li>
                                                        <li>Tags in the CSV file should be separated using a pipe (|).</li>
                                                        <li>Contacts from countries not supported by WhatsApp will be automatically excluded.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="card h-100 shadow-lg">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <h4 class="mt-3">Upload Contacts File</h4>
                                                        </div>
                                                        <div class="col-6">
                                                            <a href="{{ asset('files/broadcast_csv.csv') }}" class="badge bg-dark float-end mt-3" download="">
                                                                <i class="ri-download-line"></i>  Sample File.
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <span class="badge bg-danger mb-3">File must be type CSV.</span>
                                                    <div class="alert-message"></div>
                                                    <input type="file" id="csv_file" name="csv_file" class="dropify csv_file" data-height="250" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row create-message-section d-none">
                                        <div class="col-md-7">
                                            <div class="mb-3 template-id">
                                                @php $index = 'template_id'; @endphp
                                                <label for="{{ $index }}" class="form-label">Template Name</label>
                                                <p>
                                                    Select one from your WhatsApp approved template messages
                                                </p>
                                                @php
                                                    $templates = DB::table('templates')->where('project_id', Helper::getProjectId(request('ref_id')))->where('status', 'APPROVED')->where('id', '!=', 0)->get();
                                                @endphp
                                                <select id="{{ $index }}" class="form-select select2 field_{{ $index }}" name="{{ $index }}" data-url="{{ route('template.create') }}">
                                                    <option value="">Search Template</option>
                                                    @if (!$templates->isEmpty())
                                                        @foreach ($templates as $template)
                                                            <option value="{{ $template->id }}">{{ $template->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            @if (Helper::$allow_regular_message === 1)
                                                <div class="mb-3 template-message d-none">
                                                    @php $index = 'template_message'; @endphp
                                                    <label for="{{ $index }}" class="form-label">Template Message</label>
                                                    <p>
                                                        Write regular template message.
                                                    </p>
                                                    <textarea rows="5" name="{{ $index }}" class="form-control field_{{ $index }}"></textarea>
                                                </div>
                                            @endif
                                            <div class="content"></div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="mb-3">
                                                <div class="card">
                                                    <span>
                                                        <img src="{{ asset('images/whatsapp-icon.png') }}" alt="whatsapp icon" class="preview-whatsapp-icon">
                                                    </span>
                                                    <div class="card-body p-2">
                                                        <div class="p-0 m-0">
                                                            <img src="{{ asset('images/no-cat.svg') }}" class="mx-auto d-block">
                                                        </div>
                                                        <div class="preview"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row test-campaign-section d-none">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label>Test Campaign</label>
                                                <div class="row">
                                                    <div class="col-md-4 mt-2">
                                                        @php $index = 'test_campaign'; @endphp
                                                        <input type="text" id="{{ $index }}" class="form-control field_{{ $index }}" name="{{ $index }}" value="" placeholder="Username">
                                                    </div>
                                                    <div class="col-md-4 mt-2">
                                                        <div class="input-group">
                                                            @php
                                                                $index = 'whatsapp_number';
                                                                $countries = DB::table('countries')->where('is_active', 1)->orderBy('name', 'asc')->get();
                                                            @endphp
                                                            <button class="btn btn-dark dropdown-toggle country-code-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                {{ $countries->where('flag', 'PK')->value('phone_code') }} 
                                                            </button>
                                                            <ul class="dropdown-menu country-code-dropdown">
                                                                @foreach ($countries as $country)
                                                                    <li>
                                                                        <a class="dropdown-item country-code-item {{ $country->flag == 'PK' ? 'active' : '' }}" href="#" data-code="{{ $country->phone_code }}">
                                                                            {{ $country->flag }} {{ $country->phone_code }} {{ $country->name }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                            <input type="hidden" id="country_code" name="country_code" value="{{ $countries->where('flag', 'PK')->value('phone_code') }}">
                                                            <input type="number" id="{{ $index }}" class="form-control field_{{ $index }}" name="{{ $index }}" value="" placeholder="Phone Number">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mt-2">
                                                        <button type="button" id="test-campaign-btn" class="btn btn-light btn-show-processing" data-url="{{ route('campaigns.test.request') }}">
                                                            <span class="processing-show d-none spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>
                                                            <span class="processing-show d-none">{{ __('Testing') }}...</span>
                                                            <span class="default-show">{{ __('Test') }}</span>
                                                            <i class="ri-send-plane-fill"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row preview-send-section d-none">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                @php $index = 'schedule_date_and_time'; @endphp
                                                <div class="row">
                                                    <div class="col-8">
                                                        <label for="{{ $index }}" class="form-label">Schedule Date and Time</label>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-check form-switch form-checkbox-dark form-switch-md ms-4" dir="ltr">
                                                            <input type="checkbox" class="form-check-input" value="1" name="{{ $index }}" id="{{ $index }}">
                                                        </div>
                                                    </div>
                                                    <p>Schedule campaign upto two month from today</p>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                @php $index = 'exclude_opted_out'; @endphp
                                                <div class="row">
                                                    <div class="col-8">
                                                        <label for="{{ $index }}" class="form-label">Exclude Opted-out data</label>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-check form-switch form-checkbox-dark form-switch-md ms-4" dir="ltr">
                                                            <input type="checkbox" class="form-check-input" value="1" name="{{ $index }}" id="{{ $index }}" checked>
                                                        </div>
                                                    </div>
                                                    <p>Skip users who have opted out from future campaign</p>
                                                </div>
                                            </div>
                                            <div class="mb-3 d-flex justify-content-between">
                                                <label>Campaign Name: </label>
                                                <p class="preview_campaign_name me-5"></p>
                                            </div>
                                            <div class="mb-3 d-flex justify-content-between">
                                                <label>Audience Size: </label>
                                                <p class="get-audience-size me-5 text-center"></p>
                                            </div>
                                            <div class="mb-3 d-flex justify-content-between">
                                                @php $index = 'retry_campaign'; @endphp
                                                <div>
                                                    <label for="{{ $index }}" class="form-label label">Retry Campaign</label>
                                                    <p>
                                                        Maximize delivery rates by configuring up to 3 automatic retries for unsuccessful messages.
                                                    </p>
                                                </div>
                                                <div class="form-check form-switch form-checkbox-dark form-switch-md me-5" dir="ltr">
                                                    <input type="checkbox" class="form-check-input" value="1" name="{{ $index }}" id="{{ $index }}">
                                                </div>
                                            </div>
                                            <div class="mb-3 retry_campaign_section d-none">
                                                <label for="{{ $index }}" class="form-label label">Retry Timeline Overview</label>
                                                <div class="alert alert-secondary" role="alert">
                                                    Allow up to three retry attempts, with delays determined by the time passed since the campaign was sent.
                                                </div>
                                                <div class="row mb-3">
                                                    <table class="table nowrap w-100 border-0">
                                                        <tbody class="repeater retry-campaign-repeater">
                                                            <tr class="label-node">
                                                                <td class="border-0" colspan="2">Retry #1</td>
                                                            </tr>
                                                            <tr class="node">
                                                                <td>
                                                                    <input type="number" class="form-control mt-1 field_retry_hour" name="retry_hour[]" placeholder="Hours">
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control mt-1 field_retry_minute" name="retry_minute[]" placeholder="Minutes">
                                                                </td>
                                                                <td class="text-right border-0">
                                                                    <button type="button" class="btn btn-sm btn-soft-danger retry-campaign-delete-repeater-node"><i class="ri-delete-bin-5-line"></i> </button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-light retry-campaign-insert-repeater" data-repeaterclass="retry-campaign-repeater"><i class="ri-add-line fs-14"></i>Add Retry</button>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="schedule_date_and_time_div d-none">
                                                <div class="mb-3">
                                                    @php $index = 'schedule_date'; @endphp
                                                    <label for="{{ $index }}" class="form-label">Select Date</label>
                                                    <p>
                                                        Select Campaign date to send.
                                                    </p>
                                                    <input type="date" id="{{ $index }}" class="form-control field_campaign_name {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" value="{{ now()->format('Y-m-d') }}">
                                                </div>
                                                <div class="mb-3">
                                                    @php $index = 'schedule_time'; @endphp
                                                    <label for="{{ $index }}" class="form-label">Select Time</label>
                                                    <p>
                                                        Select Campaign time to send.
                                                    </p>
                                                    <input type="time" id="{{ $index }}" class="form-control field_campaign_name {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" value="{{ now()->format('H:i') }}">
                                                </div>
                                                <div class="mb-3">
                                                    @php $index = 'campaign_timezone'; @endphp
                                                    <label for="{{ $index }}" class="form-label">Select Timezone</label>
                                                    <p>
                                                        Select timezone for Scheduled Campaign's date & time.
                                                    </p>
                                                    <select id="{{ $index }}" class="form-select select2 {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}">
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
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="card shadow-md">
                                                    <span>
                                                        <img src="{{ asset('images/whatsapp-icon.png') }}" alt="whatsapp icon" class="preview-whatsapp-icon">
                                                    </span>
                                                    <div class="card-body p-2">
                                                        <div class="p-0 m-0">
                                                            <img src="{{ asset('images/no-cat.svg') }}" class="mx-auto d-block">
                                                        </div>
                                                        <div class="preview"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-body shadow-lg table-responsive">
                                                    <input type="hidden" id="checkout_url" value="{{ route('campaigns.checkout') }}" hidden>
                                                    <table class="table w-100">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="3">Estimated Campaign Cost </th>
                                                                <th>Estimated Cost $<span class="total-amount"></span></th>
                                                                <th>Available WCC $0</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="checkout-body">
                                                            <tr>
                                                                <td>User Count</td>
                                                                <td>Country Name</td>
                                                                <td>ISO Code</td>
                                                                <td colspan="2" class="text-center">Price</td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="3" class="text-right fw-bold">Total Estimated Cost:</td>
                                                                <td colspan="2" class="text-center fw-bold">$<span class="total-amount"></span></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-light prev-btn d-none"><i class="ri-arrow-left-line"></i> Prev</button>
                                    <button type="button" class="btn btn-dark float-end next-btn">Next <i class="ri-arrow-right-line"></i></button>
                                    <button type="submit" class="btn btn-dark btn-ajax-show-processing d-none float-end broadcast-btn">
                                        <span class="processing-show d-none spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>
                                        <i class="default-show mdi mdi-content-save-move me-1"></i>
                                        <span class="processing-show d-none">{{ __('Sending') }}...</span>
                                        <span class="default-show">{{ __('Send Now') }}</span>
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

@section('meta')
<meta name="class-to-open" content="campaigns">
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