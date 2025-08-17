$(document).ready(function() {

	try { createTable(); } catch(err) {}

    $("body").on("click", ".btn-show-processing", function (e) {
        $(this).find(".processing-show").removeClass("d-none");

        $(this).find(".default-show").addClass("d-none");

        $(this).addClass("disabled");
    });

    $("body").on("submit", ".ajax-form-submit", function (e) {

        e.preventDefault();

        var _this = $(this);

        var button = $(this).find(".btn-ajax-show-processing");

        clearValidationErrors(_this);

        button.find(".processing-show").removeClass("d-none");

        button.find(".default-show").addClass("d-none");

        button.addClass("disabled");

        button.prop("disabled", true);

        var form_data = new FormData(this);

        var url = $(this).attr("action");

        $.ajax({
            xhr: function () {
                if ($('input[type="file"]').val()) {

                    var xhr = new XMLHttpRequest();

                    xhr.upload.addEventListener("progress", updateProgress, false);

                    return xhr;
                } else {
                    return new XMLHttpRequest();
                }
            },

            url: url,

            type: "POST",

            data: form_data,

            cache: false,

            contentType: false,

            processData: false,

            success: function (data) {

                if (data.status < 1) {
                    button.find(".processing-show").addClass("d-none");

                    button.find(".default-show").removeClass("d-none");

                    button.removeClass("disabled");

                    var errors = "";

                    if (data.error_message)
                    {
                        if (_this.find(".display-messages").length)
                        {
                            _this.find(".display-messages").html('<div class="alert alert-danger" role="alert">'+ data.error_message.email +'</div>');
                            _this.find(".display-messages").slideDown('slow');
                        }
                    }

                    if (data.message) {
                        $.each(data.message, function (key, val) {
                            if (_this.find(".field_" + key).length) {
                                _this.find(".field_" + key).addClass("is-invalid");

                                $.each(val, function (field_key, field_val) {
                                    _this.find(".field_" + key).after('<div class="invalid-feedback mb-2 text-left">' + field_val + "</div>");
                                });
                            }
                        });
                    }
                } else if (data.status && data.status == 1) {

                    if (data.message) {
                        _this.prepend('<p class="alert alert-success my-2">' + data.message + "</p>");
                    }

                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else if (data.refresh) {
                        location.reload();
                    } else {
                        if (!data.no_reset) {
                            _this[0].reset();
                        }

                        if (_this.parents(".modal").length) {
                            modal_id = _this.parents(".modal").attr("id");

                            $("#" + modal_id).modal("hide");
                        }

                        clearValidationErrors(_this);

                        try {
                            refreshTable();
                        } catch (err) {}

                        button.find(".processing-show").addClass("d-none");

                        button.find(".default-show").removeClass("d-none");

                        button.removeClass("disabled");
                    }
                } else {
                    button.find(".processing-show").addClass("d-none");

                    button.find(".default-show").removeClass("d-none");

                    button.removeClass("disabled");
                }

                button.prop("disabled", false);
            },
        });
    });

	$('body').on('click', '.fetch-dynamic-modal', function (e) {

        var _this = $(this);
        var url = $(this).data('url');
        
        if (url)
        {
            $.ajax({
                url: url,
                type: 'GET',
                data: {},
                success: function(data) {
                    if(data.status && data.status == 1 && data.modal)
                    {
                        if ($(".dynamic-page-modals").length)
                        {
                            $(".dynamic-page-modals").html(data.modal);

                            if ($(".dynamic-page-modals").find('.modal').length)
                            {
                                modal_id = $(".dynamic-page-modals").find('.modal').attr('id')

                                $('#' + modal_id).modal('toggle');
                                $('#' + modal_id).modal('show');
                            }
                        }
                    }
                }
            });
        }
    });
});

function clearValidationErrors(_this) {
    
    _this.find(".is-invalid").removeClass("is-invalid");

    _this.find(".invalid-feedback").remove();

    $(".error-message-text").remove();
}