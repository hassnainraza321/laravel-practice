@extends('dashboard.index')
@section('title', Helper::getSiteTitle('Send Notifications'))

@section('css-lib')
    
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Send Notifications</li>
                    </ol>
                </div>
                <h4 class="page-title">Send Notifications</h4>
            </div>
        </div>
    </div>
	
    <div class="row">
        <div class="col-sm-12 d-flex justify-content-center align-items-center">
            <div class="card">
                <div class="card-body">
                    <button onclick="startFCM()"
                        class="btn btn-danger btn-flat">Allow notification
                    </button>
                    <div class="card mt-3">
                        <div class="card-body">
                            @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                            @endif
                            <form action="{{ route('send.web-notification') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Message Title</label>
                                    <input type="text" class="form-control" name="title">
                                </div>
                                <div class="form-group">
                                    <label>Message Body</label>
                                    <textarea class="form-control" name="body"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success btn-block">Send Notification</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div> 
        </div> 
    </div>

@endsection
@section('js-lib')
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
    <script>
        var firebaseConfig = {
            apiKey: "AIzaSyAKYI0iLvoZtCLkw4CPntSoqoAHdRokTYI",
            authDomain: "magazines-1fe64.firebaseapp.com",
            projectId: "magazines-1fe64",
            storageBucket: "magazines-1fe64.appspot.com",
            messagingSenderId: "235819585706",
            appId: "1:235819585706:web:2e51c8f1caeb1b624dc0d3",
            measurementId: "G-JWZP5WNP45"
        };
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();
        function startFCM() {
            messaging
                .requestPermission()
                .then(function () {
                    return messaging.getToken()
                })
                .then(function (response) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ route("store.token", 'allow') }}',
                        type: 'POST',
                        data: {
                            token: response
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            alert('Token stored.');
                        },
                        error: function (error) {
                            alert(error);
                        },
                    });
                }).catch(function (error) {
                    alert(error);
                });
        }
        messaging.onMessage(function (payload) {
            const title = payload.notification.title;
            const options = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(title, options);
        });
    </script>
@endsection
