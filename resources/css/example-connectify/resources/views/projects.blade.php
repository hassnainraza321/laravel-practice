@extends('index')
@section('title', Helper::getSiteTitle('Projects'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4 rounded-4">
                <div class="card-body px-4 py-2">
                    <div class="mt-2 d-flex flex-wrap align-items-center justify-content-between">
                        <h3 class="text-danger">Welcome {{ auth()->check() ? auth()->user()->first_name : '' }} !</h3>
                        <button type="button" class="btn btn-soft-dark" onclick="launchWhatsAppSignup()"><i class="ri-add-circle-line align-middle me-1"></i> Login with Facebook</button>
                    </div>
                    <p class="text-muted">Sign in to continue to WA Connectify.</p>
                </div>
            </div>
        </div>
    </div>
    @if(!$projects->isEmpty())
    <div class="row mt-2">
        <h3 class="text-muted">Recent Projects</h3>
        @foreach($projects as $project)
            <div class="col-md-6 col-lg-4">
                <div class="card mt-4 rounded-4">
                    <div class="card-body p-3">
                        <div class="mt-2">
                            <h3 class="text-danger text-center">{{ !empty($project->business_name) ? $project->business_name : '' }}</h3>
                            <div class="p-3">
                                <div class="d-flex justify-content-between">
                                    <label>Status</label>
                                    <p class="{{ !empty($project->status) && $project->status == 1 ? 'text-primary' : 'text-danger' }}">{{ !empty($project->status) && $project->status == 1 ? 'Verified' : 'Not Verified' }}</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <label>Number</label>
                                    <p>{{ !empty($project->phone_number) ? $project->phone_number : '' }}</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <label>Created At</label>
                                    <p>{{ \Carbon\Carbon::parse($project->created_at)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <a href="{{ route('home', ['ref_id' => $project->reference_id]) }}" class="btn btn-soft-dark w-50 d-block mx-auto">View</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>
@endsection 

@section('meta')
<meta name="class-to-open" content="projects">
@endsection

@section('js')
<script>
    var phone_number_id = whatsapp_business_account_id = code = '';

    window.fbAsyncInit = function() {
        FB.init({
            appId            : {{ Helper::getOption('meta_app_id') }},
            autoLogAppEvents : true,
            xfbml            : true,
            cookie           : true,
            version          : 'v20.0'
        });
    };

    const sessionInfoListener = (event) => {
        if (event.origin == null) {
            return;
        }
      
        // Make sure the data is coming from facebook.com
        if (!event.origin.endsWith("facebook.com")) {
            return;
        }

        console.log(event);
      
        try {
            const data = JSON.parse(event.data);
            
            if (data.type === 'WA_EMBEDDED_SIGNUP') {
          
                if (data.event === 'FINISH') {
                    phone_number_id = data.data.phone_number_id;
                    whatsapp_business_account_id = data.data.waba_id;
                    console.log("Phone number ID ", phone_number_id, " WhatsApp business account ID ", whatsapp_business_account_id);
                }
                else if (data.event === 'ERROR') {
                    const {error_message} = data.data;
                    console.error("error ", error_message);
                }
                else {
                    const{current_step} = data.data;
                    console.warn("Cancel at ", current_step);
                }
            }
        } catch {
            // Don’t parse info that’s not a JSON
            console.log('Non JSON Response', event.data);
        }
    };

    window.addEventListener('message', sessionInfoListener);
</script>
<script>
    function launchWhatsAppSignup() {

        FB.login(function (response) {

            if (response.authResponse)
            {
                code = response.authResponse.code;
                saveOnboardingResponse();
            }
            else
            {
                console.log('User cancelled login or did not fully authorize.');
            }
        },
        {
            config_id: {{ Helper::getOption('meta_config_id') }},
            response_type: 'code',     
            override_default_response_type: true,
            extras: {
                "feature": "whatsapp_embedded_signup",
                "sessionInfoVersion": 3
            }
        });
    }

    function saveOnboardingResponse() 
    {
        $.ajax({
            url: "{{ route('project') }}",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type: 'POST',
            data: {code: code, phone_number_id: phone_number_id, whatsapp_business_account_id: whatsapp_business_account_id},
            success: function(res) {
                if (res.success) {
                    alert('Onboarding successfully done.');
                } else {
                    alert(res.message);
                }
            }
        });
    }
</script>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
@endsection