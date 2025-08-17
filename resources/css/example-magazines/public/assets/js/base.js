$(document).ready(function() {

    $(".loading-spinner").hide();
    $(".main-content").show();

    $('.modal').modal({
        backdrop: 'static',
        keyboard: false
    });

    var success_session = $('#success_alert_modal').data('success-session');
    var error_session = $('#error_alert_modal').data('error-session');

    if (success_session) {
        $('#success_alert_modal').modal('show');
    }

    if (error_session) {
        $('#error_alert_modal').modal('show');
    }

    $(document).on('click', '.show_alert', function() {

        var url = $(this).data('url');

        $('#alert_modal').modal('show');

        $('.dismiss_this').on('click', function () {

            $('#alert_modal').modal('hide');
        });

        $('.delete_this').on('click', function () {

            window.location.href = url;
        });

    });

    $('#add_item').on('click', function() {

        $('body').find('.ajax_modal').html('');
        $('body').find('.Item_form').find('input.form-control').val('');

        if (!$('.ajax_response_error').hasClass('d-none')) {
            $('.item_form').find('.ajax_response_error').addClass('d-none');
            $('.item_form').find('.ajax_response_success').addClass('d-none');
        }

        $('body').off('submit', '.item_form');

        $('.modal_popup').modal('show');

        $('body').on('submit', '.item_form', function(e) {

            e.preventDefault();

            button = this;

            addSpinner(button);

            var form_data = new FormData(this);
            
            var url = $(this).attr('action');

            setTimeout(function() {

                $.ajax({

                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: form_data,
                    cache: false,
                    contentType: false,
                    processData: false,

                    success: function (response) {
                        
                        if (response.success) {

                            $(button).find('.ajax_response_success').removeClass('d-none').text(response.success);
                            $('.modal_popup').modal('hide');
                            refreshTable();
                            removeSpinner(button);
                        }

                        if (response.Validator) {

                            $(button).find('._name').text(response.Validator.name);
                            $(button).find('._slug').text(response.Validator.slug);

                            $(button).find('.title').text(response.Validator.title);
                            $(button).find('.slug').text(response.Validator.slug);
                            $(button).find('.magazine_id').text(response.Validator.magazine_id);
                            $(button).find('.content').text(response.Validator.content);
                            $(button).find('.featured_image').text(response.Validator.featured_image);
                            $(button).find('.featured_video').text(response.Validator.featured_video);
                            $(button).find('.images').text(response.Validator.images);
                            $(button).find('.videos').text(response.Validator.videos);

                            $(button).find('.description').text(response.Validator.description);
                            $(button).find('.amount').text(response.Validator.amount);
                            $(button).find('.article_limit').text(response.Validator.article_limit);

                            $(button).find('._to').text(response.Validator.to);
                            $(button).find('._subject').text(response.Validator.subject);
                            $(button).find('._message').text(response.Validator.message);

                            removeSpinner(button);

                        }

                        if (response.error) {

                            $(button).find('.ajax_response_error').removeClass('d-none').text(response.error);

                            removeSpinner(button);
                        }
                        
                    },
                    
                });

            }, 1000);

        });
    });

$(document).on('click', '.edit_magazine', function() {

    if (!$('.ajax_response_error').hasClass('d-none')) {
        $('.item_form').find('.ajax_response_error').addClass('d-none');
        $('.item_form').find('.ajax_response_success').addClass('d-none');
    }

    $('body').off('submit', '.item_form');

        var url = $(this).data('url');

        $.ajax({

            type: 'GET',
            url: url,
            
            success: function(response) {
                
                if(response.modal){

                    $('body').find('.ajax_modal').html(response.modal);

                    $('.edit_popup').modal('show');

                }

            },
            error: function(error) {

                
                console.error("Error:", error);
                
            }
        });

        

        $('body').on('submit', '.item_form', function(e) {

            e.preventDefault();

            button = this;

            addSpinner(button);

            var form_data = new FormData(this);

            setTimeout(function() {

                $.ajax({

                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: form_data,
                    cache: false,
                    contentType: false,
                    processData: false,

                    success: function (response) {
                        
                        if (response.success) {

                            $(button).find('.ajax_response_success').removeClass('d-none').text(response.success);
                            $('.edit_popup').modal('hide');
                            refreshTable();
                            removeSpinner(button);
                        }

                        if (response.Validator) {

                            $(button).find('._name').text(response.Validator.name);
                            $(button).find('._slug').text(response.Validator.slug);

                            $(button).find('._name').text(response.Validator.name);
                            $(button).find('._email').text(response.Validator.email);
                            $(button).find('._comment').text(response.Validator.comment);

                            removeSpinner(button);

                        }

                        if (response.error) {

                            $(button).find('.ajax_response_error').removeClass('d-none').text(response.error);

                            removeSpinner(button);
                        }
                        
                    },
                    
                });

            }, 1000);

        });
    });


    $('body').on('submit', '.comment_form', function(e) {

        e.preventDefault();

        button = this;

        addSpinner(button);

        var form_data = new FormData(this);
        
        var url = $(this).attr('action');

        setTimeout(function() {

            $.ajax({

                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,

                success: function (response) {
                    
                    if (response.success) {

                        $('.comment_form').find('.ajax_response_success').removeClass('d-none').text(response.success);
                        $(button).find('input.form-control').val('');
                        removeSpinner(button);
                    }

                    if (response.Validator) {

                        $(button).find('._name').text(response.Validator.name);
                        $(button).find('._email').text(response.Validator.email);
                        $(button).find('._comment').text(response.Validator.comment);

                        
                        $(button).find('._subject').text(response.Validator.subject);
                        
                        $(button).find('._message').text(response.Validator.message);

                        
                        removeSpinner(button);

                    }

                    if (response.error) {

                        $('.comment_form').find('.ajax_response_error').removeClass('d-none').text(response.error);

                        removeSpinner(button);
                    }
                    
                },
                
            });

        }, 0);

    });


    $(document).on('click', '.close-modal-button', function() {
        $('body').off('submit', '.item_form');
        $('body').off('submit', '.comment_form');
        // location.reload(true); 
    });

    $("body").on('keyup', '.search-in-datatables', function () {
        
        refreshTable();
    });

    $("body").on('change', '.filter-select', function () {
        refreshTable();
    });

    function refreshTable() {

    try{
        if (table) 
        {

            table.ajax.reload();
            
        }
    }
    catch(err) {}
}

    function addSpinner(button) {

        var _Button = $(button).find('#save_item');


        if (!_Button.hasClass('d-none')) {
            
            _Button.addClass('d-none');

            _Button.after(`<button id="spinner_btn" class="btn btn-info waves-effect waves-light"> <span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>
                                                        Loading...
                                </button>`);
        }
      
    }

    function removeSpinner(button) {

        $(button).find('#spinner_btn').remove();
        $(button).find('#save_item').removeClass('d-none');
    }

    $('#submit_btn').on('click', function() {

        var form_id = $(this).data('form_id');

        $(this).addClass('d-none');

        if (form_id === 'auth_form') {
            $(this).after(`<button id="submit_spinner_btn" class="btn btn-primary "> <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Loading ...
                        </button>`);
        }
        else{
            $(this).after(`<button id="submit_spinner_btn" class="btn btn-info waves-effect waves-light mx-auto d-block w-25"> <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Loading ...
                        </button>`);
        }

        setTimeout(function() {

            $('#' + form_id).submit();
            $(this).find('#submit_spinner_btn').remove();
            $(this).removeClass('d-none');
        
        }, 3000);
    });

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
    function startFCM(route) {
        
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
                    url: route,
                    type: 'POST',
                    data: {
                        token: response
                    },
                    dataType: 'JSON',
                    success: function (response) {

                        if (response.Allow) {
                            alert(response.Allow);
                        }

                        if (response.Block) {
                            alert(response.Block);
                        }

                        if (response.error) {
                            alert(response.error);
                        }
                        
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
            content: payload.notification.content,
            icon: payload.notification.icon,
            image: payload.notification.image,
            link: payload.notification.link,
        };
        console.log(options);
        new Notification(title, options);
    });

   $('.allow_noti').on('click', function() {
        
        var route = $(this).data('url');
        startFCM(route);

   }); 

});