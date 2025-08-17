var table = '';
var typingTimer = '';
var doneTypingInterval = 500;

try
{
    Dropzone.autoDiscover = false;
}
catch(err){}

$(document).ready(function() {

    openNavigation();

    $(document).on('select2:open', () => {

        document.querySelector('.select2-search__field').focus();
    });

    if ($(".select2").length)
    {
        $(".select2").select2({ width: "100%", placeholder: $(".select2").data('placeholder'), });
    }

    if ($(".select2-tags").length)
    {
        $(".select2-tags").select2({ width: "100%", tags: true });
    }

    if ($(".datetime-datepicker").length)
    {
        $(".datetime-datepicker").flatpickr({ enableTime: !0, dateFormat: "Y-m-d H:i" });
    }

    if ($(".select2-ajax-request-load").length && $(".select2-ajax-request-load").data('url'))
    {
        var tag = false;

        if ($(".select2-ajax-request-load").data('tag') == 1)
        {
            tag = true;
        }

        $(".select2-ajax-request-load").select2({
            minimumInputLength: 2,
            tags: tag,
            ajax: {
                url: $(".select2-ajax-request-load").data('url'),
                dataType: 'json',
                delay: 500,
                type: "GET",
                quietMillis: 50,
                data: function (term) {
                    return {
                        term: term
                    };
                },
                processResults: function (data) {

                    return {
                        results: $.map(data.results, function(obj) {

                            return { id: obj.id, text: obj.name};
                        })
                    };
                }
            }
        });
    }

    if ($('#dz-upload-image').length)
    {
        $('#dz-upload-image').dropzone({
            url: $('#dz-upload-image').data("action"),
            paramName: 'dz_image',
            acceptedFiles: 'image/*',
            previewsContainer: $('#dz-upload-image').data("previewsContainer"),
            previewTemplate: $('#uploadPreviewTemplate').html(),
            uploadMultiple: false,
            addedfiles: function() {

                if($("#dz-upload-image").parents('form').find('.btn-show-processing').length)
                {
                    $("#dz-upload-image").parents('form').find('.btn-show-processing').addClass('disabled');
                    $("#dz-upload-image").parents('form').find('.btn-show-processing').prop('disabled', true);
                }

                if($("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').length)
                {
                    $("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').addClass('disabled');
                    $("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').prop('disabled', true);
                }
            },
            success: function(file, response) {
                
                $(file.previewElement).find('.dz-image-id').val(response.id);
                $(file.previewElement).find('.dz-remove-btn').attr('data-id', response.id);

                if($("#dz-upload-image").parents('form').find('.btn-show-processing').length)
                {
                    $("#dz-upload-image").parents('form').find('.btn-show-processing').removeClass('disabled');
                    $("#dz-upload-image").parents('form').find('.btn-show-processing').prop('disabled', false);
                }

                if($("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').length)
                {
                    $("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').removeClass('disabled');
                    $("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').prop('disabled', false);
                }
            },
            removedfile: function(file) {

                if($("#dz-upload-image").parents('form').find('.btn-show-processing').length)
                {
                    $("#dz-upload-image").parents('form').find('.btn-show-processing').removeClass('disabled');
                    $("#dz-upload-image").parents('form').find('.btn-show-processing').prop('disabled', false);
                }

                if($("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').length)
                {
                    $("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').removeClass('disabled');
                    $("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').prop('disabled', false);
                }

                var url = $(file.previewElement).find('.dz-remove-btn').data('url');
                var id = $(file.previewElement).find('.dz-remove-btn').data('id');
                
                if (url && id)
                {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        data: {id:id},
                        success: function(data) {}
                    });
                }

                file.previewElement.remove();

                return true;
            }
        });
    }

    if ($(".summernote").length)
    {
        $(".summernote").summernote({
            enterHtml: '<p></p>',
            height: 250,
            callbacks: {
                onInit: function (e) {
                    $(e.editor).find(".custom-control-description").addClass("custom-control-label").parent().removeAttr("for");
                },
                onPaste: function (e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    document.execCommand('insertText', false, bufferText);
                }
            },
        });
    }

    try { createTable(); } catch(err) {}

    $('body').on('change', '.select-all-checkbox', function (e) {

        if (this.checked)
        {
            if ($('.select-item-checkbox').length)
            {
                if ($(".bulk-order-status-change").length)
                {
                    $(".bulk-order-status-change").val('').change();
                    $(".bulk-order-status-change").removeClass('d-none');
                }

                $(".remove-all-button").removeClass('d-none');
                $(".select-item-checkbox").prop('checked', true);
            }
        }
        else
        {
            if ($(".bulk-order-status-change").length)
            {
                $(".bulk-order-status-change").val('').change();
                $(".bulk-order-status-change").addClass('d-none');
            }

            $(".remove-all-button").addClass('d-none');
            $(".select-item-checkbox").prop('checked', false);
        }
    });

    $('body').on('change', '.select-item-checkbox', function (e) {

        if ($('.select-item-checkbox:checked').length)
        {
            if ($(".bulk-order-status-change").length)
            {
                $(".bulk-order-status-change").val('').change();
                $(".bulk-order-status-change").removeClass('d-none');
            }

            $(".remove-all-button").removeClass('d-none');
        }
        else
        {
            if ($(".bulk-order-status-change").length)
            {
                $(".bulk-order-status-change").val('').change();
                $(".bulk-order-status-change").addClass('d-none');
            }

            $(".remove-all-button").addClass('d-none');
        }

        if (!$(".select-item-checkbox:not(:checked)").length)
        {
            $(".select-all-checkbox").prop('checked', true);
        }
        else
        {
            $(".select-all-checkbox").prop('checked', false);
        }
    });

    $('body').on('click', '.remove-all-button', function (e) {

        var ids = [];

        $(".select-item-checkbox:checked").each(function(){
            ids.push($(this).val());
        });

        ids = ids.join();

        var url = $(this).data('url') ? $(this).data('url') : $('meta[name="remove-url"]').attr('content');

        if (ids && url)
        {
            removeItemData($(this), ids, url);
        }
    });

    $('body').on('click', '.remove-item-button', function (e) {

        var id = $(this).data('id');
        var url = $('meta[name="remove-url"]').attr('content');

        if (id && url)
        {
            removeItemData($(this), id, url);
        }
    });

    $('body').on('click', '.remove-item-button-direct', function (e) {

        var id = $(this).data('id');
        var url = $(this).data('url');

        if (id && url)
        {
            removeItemData($(this), id, url);
        }
    });

    $("body").on('keyup paste', '.search-in-datatables', function () {
        
        clearTimeout(typingTimer);
        var _this = $(this);
        typingTimer = setTimeout(function() {
                            refreshTable();
                        }, doneTypingInterval);
    });

    $("body").on('change', '.sorting-filter-menu input', function () {
        
        refreshTable();
    });

    $('body').on('click', '.btn-show-processing', function (e) {

        buttonLoading($(this), 1, 0);
    });

	$('body').on('submit', '.ajax-form-submit', function (e) {

    	e.preventDefault();

    	var _this = $(this);
        var button = $(this).find(".btn-ajax-show-processing");

        clearValidationErrors(_this);
        buttonLoading(button);

    	var form_data = new FormData(this);
    	var url = $(this).attr('action');

    	$.ajax({
            xhr: function () {
                if ($('input[type="file"]').val())
                {
                    var xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener("progress", updateProgress, false);
                    return xhr;
                }
                else
                {
                    return new XMLHttpRequest();
                }
            },
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {

                if ($(".file-uploader").length)
                {
                    $(".file-uploader").addClass('d-none');
                }
                
                if (data.status < 1)
                {
                    buttonLoading(button, 0);

			        if (data.error_message)
                    {
                        if (_this.find(".display-messages").length)
                        {
                            _this.find(".display-messages").html('<div class="alert alert-danger" role="alert">'+ data.error_message +'</div>');
                            _this.find(".display-messages").slideDown('slow');
                        }
                    }
                    else if (data.swal_error_message) 
                    {
                        buttonLoading(button, 0);
                        
                        Swal.fire({
                            title: "Failed",
                            text: data.swal_error_message,
                            icon: "danger",
                            preConfirm:function(t) {
                                location.reload();
                            }
                        });
                    }
                    else if(data.message)
                    {
                        if (typeof data.message == "string") {
                            showToast(data.message, 'Error');
                        }
                        else
                        {
                            $.each(data.message, function (key, val) {

                                if (_this.find('.' + key).length)
                                {
                                    _this.find('.' + key).addClass('is-invalid');

                                    $.each(val, function (field_key, field_val) {
                                        var error_element = _this.find('.' + key).parent('div').parent('div').find('.feedback');

                                        if (error_element.length) 
                                        {
                                            error_element.html('<p class="text-danger">' + field_val + '</p>').removeClass('d-none');
                                        } 
                                        else 
                                        {
                                            _this.find('.' + key).after('<div class="invalid-feedback">' + field_val + '</div>');
                                        }
                                    });

                                    showToast(val, 'Error');
                                }
                            });
                        }
                    }
                }
                else if(data.status && data.status == 1)
                {
                	if (data.redirect) 
                    {
                        if (data.message) 
                        {
                            showToast(data.message, 'Success');

                            setTimeout(function() {
                                window.location.href = data.redirect;
                            }, 2000);
                        }
                        else
                        {
                            window.location.href = data.redirect; 
                        }  
                    }
                    else if (data.redirect_stop) 
                    {
                        if (!data.no_reset)
                        {
                            _this[0].reset();
                        }

                        buttonLoading(button, 0);

                        if (_this.parents('.modal').length)
                        {
                            modal_id = _this.parents('.modal').attr('id');
                            $('#' + modal_id).find('.btn-close').click();
                        }

                        try { refreshTable(); } catch(err) {}

                        window.location.href = data.redirect_stop;
                    }
                    else if (data.refresh) 
                    {
                        if (data.message) 
                        {
                            showToast(data.message, 'Success');

                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        }
                        else
                        {
                            location.reload();
                        }  
                    }
                    else if (data.swal_message) 
                    {
                        buttonLoading(button, 0);

                        if (!data.no_reset)
                        {
                            _this[0].reset();
                        }

                        if (_this.parents('.modal').length)
                        {
                            modal_id = _this.parents('.modal').attr('id');
                            $('#' + modal_id).find('.btn-close').click();
                        }

                        try { refreshTable(); } catch(err) {}
                        
                        if (data.swal_title)
                        {
                            var swal_title = data.swal_title;
                        }
                        else
                        {
                            var swal_title = 'Success';
                        }

                        Swal.fire({
                            title: swal_title,
                            text: data.swal_message,
                            icon: "success",
                            preConfirm:function(t) {
                                
                                try { 
                                    refreshTable();
                                } catch(err) {
                                    location.reload();
                                }
                            }
                        });
                    }
                    else
                    {
                    	if (!data.no_reset)
	                    {
	                        _this[0].reset();
	                    }

	                    if (_this.parents('.modal').length)
	                    {
	                    	modal_id = _this.parents('.modal').attr('id');
                            $('#' + modal_id).find('.btn-close').click();
	                    }

	                    clearValidationErrors(_this);

	                    try { refreshTable(); } catch(err) {}

	                    buttonLoading(button, 0);

                        if(data.message)
                        {
                            var c = { heading: "Action", text: data.message, position: "top-right", loaderBg: "#fff", icon: "success", bgColor: "#28a745", textColor: "#fff", hideAfter: 5000, stack: 1 };
                            $.toast(c);
                        }
                    }
                }
                else
                {
                    buttonLoading(button, 0);
                }
            }
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

    $('body').on('change keyup', 'input', function (e) {

        clearValidationErrors($(this).parent());
    });

    // $('body').on('click', '.insert-repeater', function (e) {

    //     var repeaterclass = $(this).data('repeater');

    //     if (repeaterclass)
    //     {
    //         var html = $("." + repeaterclass + " .node:first-child").clone();
    //         $("." + repeaterclass).append(html);
    //         $("." + repeaterclass + " .node:last-child").find('input').val('').change();
    //     }
    // });

    $('body').on('click', '.delete-repeater-node', function (e) {

        $(this).parents('.node').find('input').val('').change();
        $(this).parents('.node').find('select').prop('selectedIndex', 0);

        if ($(this).parents('.repeater').find('.node').length > 1)
        {
            $(this).parents('.node').remove();
        }
    });

    $('body').on('click', '.row-url-redirect td', function (e) {

        if (!$(e.currentTarget).hasClass('no-rowurl-redirect') && $(this).parent().data('rowurl'))
        {
            window.location.href = $(this).parent().data('rowurl');
        }
    });

    $('body').on('change', '.categories-conditions-type', function (e) {

        if ($(this).val() == 'manual')
        {
            $(".automated-conditions-type").addClass('d-none');
        }
        else
        {
            $(".automated-conditions-type").removeClass('d-none');
        }
    });

    $('body').on('click', '.dz-remove-btn', function (e) {

        var _this = $(this);
        var url = $(this).data('url');
        var id = $(this).data('id');
        var type = $(this).data('type');
        
        if (url && id)
        {
            $.ajax({
                url: url,
                type: 'GET',
                data: {id:id,type:type},
                success: function(data) {}
            });

            _this.parents('.dz-image-preview').remove();
        }
    });

    $('body').on('change', '.show-section', function (e) {

        if ($(this).data('changetype'))
        {
            if ($(".section-" + $(this).data('changetype')).length)
            {
                $(".section-" + $(this).data('changetype')).addClass('d-none');
            }

            if ($(this).hasClass('no-check'))
            {
                if ($(".section-" + $(this).data('changetype') + "-" + $(this).val()).length)
                {
                    $(".section-" + $(this).data('changetype') + "-" + $(this).val()).removeClass('d-none');
                }
            }
            else
            {
                if (this.checked)
                {
                    if ($(".section-" + $(this).data('changetype') + "-" + $(this).val()).length)
                    {
                        $(".section-" + $(this).data('changetype') + "-" + $(this).val()).removeClass('d-none');
                    }
                }
            }
        }
    });

    $('body').on('click', '.insert-repeater', function (e) {

        if ($('.node').hasClass('d-none')) 
        {
            $('.node').removeClass('d-none');
        }
        else
        {
            var repeaterclass = $(this).data('repeaterclass');

            if (repeaterclass)
            {
                var html = $("." + repeaterclass + " .node:first-child").clone();
                $("." + repeaterclass).append(html);
                $("." + repeaterclass + " .node:last-child").find('input').val('').change();
            }
        }
        
    });

    $('body').on('click', '.retry-campaign-insert-repeater', function (e) {

        var repeaterclass = $(this).data('repeaterclass');
        var count = $("." + repeaterclass + " .node").length + 1;

        if (repeaterclass)
        {
            var html = `<tr class="label-node">
                            <td class="border-0" colspan="2">Retry #${count}</td>
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
                        </tr>`;
            $("." + repeaterclass).append(html);
            $("." + repeaterclass + " .node:last-child").find('input').val('').change();
        }

        if ($("." + repeaterclass + " .node").length > 2) 
        {
            $(this).addClass('d-none');
        }
        else
        {
            $(this).removeClass('d-none');
        }
        
    });

    $('body').on('click', '.retry-campaign-delete-repeater-node', function (e) {

        $(this).parents('.node').find('input').val('').change();
        $(this).parents('.node').find('select').prop('selectedIndex', 0);

        if ($(this).parents('.repeater').find('.node').length > 1)
        {
            $(this).parents('.node').prev('.label-node').remove();
            $(this).parents('.node').remove();
        }

        $('.retry-campaign-insert-repeater').removeClass('d-none');
    });

    $('body').on('click', '.quick-reply-insert-repeater', function (e) {

        var repeaterclass = $(this).data('repeaterclass');
        $("." + repeaterclass + " .node-error").remove();

        if (repeaterclass)
        {
            var html = `<tr class="node">
                            <td>
                                Quick Reply :
                            </td>
                            <td class="text-center">
                                <input type="text" name="quick_reply[]" class="form-control">
                            </td>
                            <td class="text-right border-0">
                                <button type="button" class="btn btn-sm btn-soft-danger mb-1 me-1 delete-repeater-node"><i class="ri-delete-bin-5-line"></i> </button>
                            </td>
                        </tr>`;
            if ($("." + repeaterclass + " .node").length < 10) 
            {
                $("." + repeaterclass).append(html);
            }
            else
            {
                $("." + repeaterclass).append('<tr class="node-error"><td colspan="3" class="text-danger text-center mt-2">Only 10 Quick Reply allowed!</td></tr>');
            }
            
            $("." + repeaterclass + " .node:last-child").find('input').val('').change();
        }
        
    });

    $('body').on('click', '.copy-code-insert-repeater', function (e) {

        var repeaterclass = $(this).data('repeaterclass');
        $("." + repeaterclass + " .node-error").remove();

        if (repeaterclass)
        {
            var html = `<tr class="node">
                            <td>
                                Copy Code :
                            </td>
                            <td class="text-center">
                                <input type="text" name="coupon_code[]" class="form-control copy-code-input">
                            </td>
                            <td class="text-right border-0">
                                <button type="button" class="btn btn-sm btn-soft-danger mb-1 me-1 delete-repeater-node"><i class="ri-delete-bin-5-line"></i> </button>
                            </td>
                        </tr>`;

            if ($("." + repeaterclass + " .node").length < 1) 
            {
                $("." + repeaterclass).append(html);
            }
            else
            {
                $("." + repeaterclass).append('<tr class="node-error"><td colspan="3" class="text-danger text-center mt-2">Only 1 Copy Code allowed!</td></tr>');
            }

            $("." + repeaterclass + " .node:last-child").find('input').val('').change();
        }
        
    });

    $('body').on('input', '.copy-code-input', function () {
        var code = $(this).val();
        var form = $(this).closest('form');
        var button = form.find('.copy-button');

        if (button.hasClass('d-none')) 
        {
            button.removeClass('d-none').find('span').text(code);
        }
        else
        {
            button.find('span').text(code);
        }
    });
    
    $('body').on('click', '.copy-button', function () {
        copyCode($(this).text());
    });

    $('body').on('input', '.url-title, .url-value', function () {
        var row = $(this).closest('tr'); 
        var title = row.find('.url-title').val();
        var value = row.find('.url-value').val();
        var form = $(this).closest('form'); 
        var preview = form.find('.preview');
        
        var button = form.find('.url-button[data-row="' + row.index() + '"]');

        if (button.length == 0) 
        {
            preview.after('<a class="btn btn-light w-100 url-button mb-1" target="_blank" data-row="' + row.index() + '"><i class="ri-external-link-line"></i> <span>' + title + '</span></a>');
            button = form.find('.url-button[data-row="' + row.index() + '"]');
        }

        button.find('span').text(title);
        button.attr('href', value);
    });

    $('body').on('input', '.phone-title, .phone-value', function () {
        var row = $(this).closest('tr'); 
        var title = row.find('.phone-title').val();
        var value = row.find('.phone-value').val();
        var form = $(this).closest('form'); 
        var preview = form.find('.preview');
        
        var button = form.find('.phone-button[data-row="' + row.index() + '"]');

        if (button.length == 0) 
        {
            preview.after('<a class="btn btn-light w-100 phone-button mb-1" target="_blank" data-row="' + row.index() + '" href="tel:' + value + '"><i class="ri-phone-line"></i> <span>' + title + '</span></a>');
            button = form.find('.phone-button[data-row="' + row.index() + '"]');
        }

        button.find('span').text(title);
        button.attr('href', 'tel:' + value);
    });

    $('body').on('input', '.all-title, .all-value', function () {
        var row = $(this).closest('tr'); 
        var title = row.find('.all-title').val();
        var value = row.find('.all-value').val();
        var select = row.find('.form-select').val();
        var form = $(this).closest('form'); 
        var preview = form.find('.preview');
        
        if (select == 'Phone Number') 
        {
            var button = form.find('.phone-button[data-row="' + row.index() + '"]');
        }
        else
        {
            var button = form.find('.url-button[data-row="' + row.index() + '"]');
        }

        if (button.length == 0) 
        {
            if (select == 'Phone Number') 
            {
                preview.after('<a class="btn btn-light w-100 phone-button mb-1" target="_blank" data-row="' + row.index() + '" href="tel:' + value + '"><i class="ri-phone-line"></i> <span>' + title + '</span></a>');
                button = form.find('.phone-button[data-row="' + row.index() + '"]');
            }
            else
            {
                preview.after('<a class="btn btn-light w-100 url-button mb-1" target="_blank" data-row="' + row.index() + '"><i class="ri-external-link-line"></i> <span>' + title + '</span></a>');
                button = form.find('.url-button[data-row="' + row.index() + '"]');
            }

            
        }

        button.find('span').text(title);

        if (select == 'Phone Number') 
        {
            button.attr('href', 'tel:' + value);
        }
        else
        {
            button.attr('href', value);
        }
        
    });

    $('body').on('click', '.call-to-action-insert-repeater', function (e) {

        var repeaterclass = $(this).data('repeaterclass');
        var type = $(this).data('type');
        $("." + repeaterclass + " .node-error").remove();

        var url_selected = phone_selected = '';

        if (type == 'url') 
        {
            url_selected = 'selected';
        }
        else if (type == 'phone')
        {
            phone_selected = 'selected';
        }
        else
        {
            type = 'all';
        }

        if (repeaterclass)
        {
            var html = `<tr class="node ` + type +`">
                            <td>
                                Call to Actions :
                            </td>
                            <td>
                                <select class="form-select" name="type[]">
                                    <option value="">Select action type</option>`;

            if (type == 'phone') 
            {
                html += `<option value="Phone Number" `+ phone_selected +`>Phone Number</option>`;
            }
            else if (type == 'url')
            {
                html += `<option value="URL" `+ url_selected +`>URL</option>`;
            }
            else
            {
                html += `<option value="Phone Number" `+ phone_selected +`>Phone Number</option>
                <option value="URL" `+ url_selected +`>URL</option>`;
            }
                                    
                                   
            html += `</select>
                        </td>
                        <td class="text-center">
                            <input type="text" name="button_title[]" class="form-control `+ type +`-title">
                        </td>
                        <td class="text-center">
                            <input type="text" name="button_value[]" class="form-control `+ type +`-value">
                        </td>
                        <td class="text-right border-0">
                            <button type="button" class="btn btn-sm btn-soft-danger mb-1 me-1 delete-repeater-node"><i class="ri-delete-bin-5-line"></i> </button>
                        </td>
                    </tr>`;

            if (type == 'url') 
            {
                if ($("." + repeaterclass + " .url").length == 0 && $("." + repeaterclass).find('.phone').length == 0 && $("." + repeaterclass + " .node").length > 0) 
                {
                    $("." + repeaterclass + " .node").remove();
                }

                if ($("." + repeaterclass + " .url").length < 2) 
                {
                    $("." + repeaterclass).append(html);
                }
                else
                {
                    $("." + repeaterclass).append('<tr class="node-error"><td colspan="4" class="text-danger text-center mt-2">Only 2 URL allowed!</td></tr>');
                }
            }
            else if (type == 'phone')
            {
                if ($("." + repeaterclass + " .url").length == 0 && $("." + repeaterclass).find('.phone').length == 0 && $("." + repeaterclass + " .node").length > 0) 
                {
                    $("." + repeaterclass + " .node").remove();
                }

                if ($("." + repeaterclass + " .phone").length < 1) 
                {
                    $("." + repeaterclass).append(html);
                }
                else
                {
                    $("." + repeaterclass).append('<tr class="node-error"><td colspan="4" class="text-danger text-center mt-2">Only 1 Phone Number allowed!</td></tr>');
                }
            }
            else
            {
                if (type == 'all') 
                {
                    if ($("." + repeaterclass + " .node").length < 2) 
                    {
                        $("." + repeaterclass).append(html);
                    }
                    else
                    {
                        $("." + repeaterclass).append('<tr class="node-error"><td colspan="4" class="text-danger text-center mt-2">Only 2 Call To Actions allowed!</td></tr>');
                    }
                }
            }

            $("." + repeaterclass + " .node:last-child").find('input').val('').change();
        }
        
    });

    // $('body').on('click', '.delete-repeater-node', function (e) {

    //     $(this).parents('.node').find('input.form-control').val('');

    //     if ($(this).parents('.repeater').find('.node').length > 0)
    //     {
    //         $(this).parents('.node').remove();
    //     }
    // });

    $('body').on('change', 'input[type="radio"]', function (e) {

        if ($(this).val() == 'none') 
        {
            $('.call-to-action-section, .quick-reply-section, .copy-code-section, .actions-button-section').addClass('d-none');
        }

        if ($(this).val() == 'call to action') 
        {
            $('.call-to-action-section').removeClass('d-none');
            $('.actions-button-section').addClass('d-none');
            // .quick-reply-section, .copy-code-section, 

            if ($('.call-to-action-insert-repeater').hasClass('d-none')) 
            {
                $(".call-to-action-section .node").remove();
                $('.call-to-action-insert-repeater').removeClass('d-none');
            }
        }

        if ($(this).val() == 'quick replies') 
        {
            $('.quick-reply-section').removeClass('d-none');

            if ($('.quick-reply-insert-repeater').hasClass('d-none')) 
            {
                $('.quick-reply-insert-repeater').removeClass('d-none');
            }
            
            $('.actions-button-section').addClass('d-none');
            // .call-to-action-section, .copy-code-section, 
        }

        if ($(this).val() == 'all') 
        {
            $('.actions-button-section').removeClass('d-none');
            //$('.call-to-action-section, .copy-code-section, .quick-reply-section').addClass('d-none');
        }
    });

    $('body').on('click', '.quick-reply-insert-repeater', function (e) {

        var value = $(this).data('repeaterclass');

        if (value == 'quick-reply-repeater') 
        {
            $('.quick-reply-section').removeClass('d-none');
            // $('.call-to-action-section, .copy-code-section').addClass('d-none');

            if ($('input[type="radio"]:checked').val() == 'all')
            {
                $('.quick-reply-insert-repeater').filter(function () {
                    return $(this).data('repeaterclass') === 'quick-reply-repeater';
                }).first().addClass('d-none');
            }
        }
    });
    
    $('body').on('click', '.call-to-action-insert-repeater', function (e) {

        var value = $(this).data('repeaterclass');

        if (value == 'call-to-action-repeater') 
        {
            $('.call-to-action-section').removeClass('d-none');
            // $('.quick-reply-section, .copy-code-section').addClass('d-none');

            if ($('input[type="radio"]:checked').val() == 'all')
            {
                $('.call-to-action-insert-repeater').filter(function () {
                    return $(this).data('repeaterclass') === 'call-to-action-repeater';
                }).first().addClass('d-none');
            }
        }
    });   

    $('body').on('click', '.copy-code-insert-repeater', function (e) {

        var value = $(this).data('repeaterclass');

        if (value == 'copy-code-repeater') 
        {
            $('.copy-code-section').removeClass('d-none');
            // $('.quick-reply-section, .call-to-action-section').addClass('d-none');
        }
    });

    if ($('#category_id').val() == 1) 
    {
        $('#type_id option').each(function() 
        {
            if ($(this).val() == 4 || $(this).val() == 5 ) 
            {
                $(this).removeClass('d-none'); 
            } 
            else 
            {
                $(this).addClass('d-none');
            }
        });
    }

    if ($('#category_id').val() == 2) 
    {
        $('#type_id option').each(function() 
        {
            if ($(this).val() == 1 || $(this).val() == 2 ) 
            {
                $(this).removeClass('d-none'); 
            } 
            else 
            {
                $(this).addClass('d-none');
            }
        });
    }

    if ($('#category_id').val() == 3) 
    {
        $('#type_id option').each(function() 
        {
            if ($(this).val() == 1) 
            {
                $(this).removeClass('d-none'); 
                $(this).prop('selected', true);
            } 
        });

        $('.copy-code-insert-repeater').removeAttr('disabled');
        if ($('.copy-code-repeater').find('.node').length < 1) 
        {
            $('.copy-code-insert-repeater').click();
        }
        
        $('.sample_value, .expiration_warning').removeClass('d-none');
        $('.header_text, .footer_text, .content').addClass('d-none');
        $('.action-none, .action-call, .action-quick').attr('disabled', 'disabled');
        $('.quick-reply-insert-repeater, .call-to-action-insert-repeater, .call-to-action-insert-repeater, .copy-code-insert-repeater').attr('disabled', 'disabled');
    }
    else
    {
        $('.sample_value, .expiration_warning').addClass('d-none');
        $('.header_text, .footer_text, .content').removeClass('d-none');
        $('.action-none, .action-call, .action-quick').removeAttr('disabled');
        $('.quick-reply-insert-repeater, .call-to-action-insert-repeater, .call-to-action-insert-repeater').removeAttr('disabled');
    }

    $('body').on('change', '#category_id', function (e) {

        if ($(this).val() == 1) 
        {
            $('#type_id option').each(function() 
            {
                if ($(this).val() == 4 || $(this).val() == 5 ) 
                {
                    $(this).removeClass('d-none'); 
                } 
                else 
                {
                    $(this).addClass('d-none');
                }
            });
        }

        if ($(this).val() == 2) 
        {
            $('#type_id option').each(function() 
            {
                if ($(this).val() == 1 || $(this).val() == 2 ) 
                {
                    $(this).removeClass('d-none'); 
                } 
                else 
                {
                    $(this).addClass('d-none');
                }
            });
        }

        if ($(this).val() == 3) 
        {
            $('#type_id option').each(function() 
            {
                if ($(this).val() == 1) 
                {
                    $(this).removeClass('d-none'); 
                    $(this).prop('selected', true);
                } 
                else 
                {
                    $(this).addClass('d-none');
                }
            });

            $('.copy-code-insert-repeater').removeAttr('disabled');
            $('.copy-code-insert-repeater').click();
            $('.sample_value, .expiration_warning').removeClass('d-none');
            $('.header_text, .footer_text, .content').addClass('d-none');
            $('.action-none, .action-call, .action-quick').attr('disabled', 'disabled');
            $('.quick-reply-insert-repeater, .call-to-action-insert-repeater, .call-to-action-insert-repeater, .copy-code-insert-repeater').attr('disabled', 'disabled');
        }
        else
        {
            $('.sample_value, .expiration_warning').addClass('d-none');
            $('.header_text, .footer_text, .content').removeClass('d-none');
            $('.action-none, .action-call, .action-quick').removeAttr('disabled');
            $('.quick-reply-insert-repeater, .call-to-action-insert-repeater, .call-to-action-insert-repeater, .copy-code-insert-repeater').removeAttr('disabled');
        }

        if ($(this).val() == 2) 
        {
            $('.copy-code-insert-repeater').removeAttr('disabled');
        }
        else
        {
            $('.copy-code-insert-repeater').attr('disabled', 'disabled');
        }
    });

    if ($('#type_id').val() == 1) 
    {
        $('.header_text').removeClass('d-none');
    }
    else
    {
        $('.header_text').addClass('d-none');
    }

    $('body').on('change', '#type_id', function (e) {

        if ($(this).val() == 6) 
        {
            $('.carousel').removeClass('d-none');
            $('.header_text, .footer_text').addClass('d-none');
        }
        else
        {
            $('.carousel').addClass('d-none');
            $('.header_text, .footer_text').removeClass('d-none');
        }

        if ($(this).val() == 1) 
        {
            $('.header_text').removeClass('d-none');
        }
        else
        {
            $('.header_text').addClass('d-none');
        }

        if ($(this).val() == 7) 
        {
            $('.limited_time_offer').removeClass('d-none');

            if (!$('.header_text').hasClass('d-none')) 
            {
                $('.header_text').addClass('d-none');
            }

            if (!$('.footer_text').hasClass('d-none')) 
            {
                $('.footer_text').addClass('d-none');
            }
        }
        else
        {
            if (!$('.limited_time_offer').hasClass('d-none')) 
            {
                $('.limited_time_offer').addClass('d-none');
            }
        }

        if ($(this).val() == 2 || $(this).val() == 4 ) 
        {
            $('.preview-default-image').addClass('d-none');
            $('.upload-template-media').removeClass('d-none');  
        } 
        else 
        {
            $('.preview-default-image').removeClass('d-none');
            $('.upload-template-media').addClass('d-none'); 
        }
    });

    $('#header_text').on('input', function() 
    {
        var content_text = $('#content').val();
        var content = $(this).val(); 
        var footer_text = $('#footer_text').val();

        if (content_text.trim() !== '' || footer_text.trim() !== '' || content.trim() !== '') 
        {
            $('.preview').html(content.replace(/\n/g, '<br>') + '<br><br>' + content_text.replace(/\n/g, '<br>') + '<br><br>' + footer_text.replace(/\n/g, '<br>')); 
        }
    });

    
    $('#content').on('input', function() 
    {
        var form = $(this).closest('form');
        var header_text = $('#header_text').val();
        var content = $(this).val(); 
        var footer_text = $('#footer_text').val();
        
        if (header_text.trim() !== '' || content.trim() !== '' || footer_text.trim() !== '') 
        {
            $('.preview').html(header_text.replace(/\n/g, '<br>') + '<br><br>' + content.replace(/\n/g, '<br>') + '<br><br>' + footer_text.replace(/\n/g, '<br>'));
        }
    });

    $('#footer_text').on('input', function() 
    {
        var header_text = $('#header_text').val();
        var content = $('#content').val();
        var footer_text = $(this).val(); 

        if (header_text.trim() !== '' || content.trim() !== '' || footer_text.trim() !== '') 
        {
            $('.preview').html(header_text.replace(/\n/g, '<br>') + '<br><br>' + content.replace(/\n/g, '<br>') + '<br><br>' + footer_text.replace(/\n/g, '<br>'));
        }
    });

    $('#expiration_warning').on('input', function() 
    {
        if (this.value.length > 2) 
        {
            this.value = this.value.slice(0, 2);
        }
    });

    $('body').on('input', '#sample_value, .sample_value_input', function() 
    {
        if ($('.content').hasClass('d-none')) 
        {
            var expiration_warning = $('#expiration_warning').length ? $('#expiration_warning').val() : null;
            var sample_value = $(this).val();

            $('.preview').find('.prepended_sample').remove();

            if (expiration_warning && expiration_warning.length) 
            {
                $('.preview').html('<p class="prepended_sample">[' + sample_value + '] is your verification code.' + '</p><p class="prepended_expiration">' + 'This code expires in ' + expiration_warning + ' minute(s).</p>');
            }
            else
            {
                $('.preview').prepend('<p class="prepended_sample">[' + sample_value + '] is your verification code.</p>');
            } 
        }

    });

    $('#expiration_warning').on('input', function() 
    {
        var sample_value = $('#sample_value').length ? $('#sample_value').val() : null;
        var expiration_warning = $(this).val();
        var security_disclaimer = $('#security_disclaimer').is(':checked') ? ' For your security, do not share this code.' : '';

        $('.preview').find('.prepended_expiration').remove();

        if (sample_value && sample_value.length) 
        {
            $('.preview').html('<p>[' + sample_value + '] is your verification code.' + security_disclaimer + '</p><p class="prepended_expiration">' + 'This code expires in ' + expiration_warning + ' minute(s).</p>');
        }
        else
        {
            $('.preview').append('<p class="prepended_expiration">This code expires in ' + expiration_warning + ' minute(s).</p>');
        } 
    });

    $('#security_disclaimer').on('change', function () {
        let code_text = $('.preview p:first-child');
        let security_disclaimer = ' For your security, do not share this code.';
        
        if ($(this).is(':checked')) 
        {
            if (!code_text.text().includes(security_disclaimer.trim())) 
            {
                code_text.append(security_disclaimer);
            }
        } 
        else 
        {
            code_text.text(code_text.text().replace(security_disclaimer, ''));
        }
    });

    $('#header_text').trigger('input');
    $('#footer_text').trigger('input');
    $('#content').trigger('input');

    var currentPlaceholderIndex = 1;

    $('#content').on('input', function() 
    {
        var text = $(this).val();
        var regex = /\{\{\d+\}\}/g; 

        var matches = text.match(regex);
        var placeholderArray = [];

        if (matches) 
        {
            matches.forEach(function(match) 
            {
                var number = parseInt(match.replace(/[{}]/g, ''), 10);
                if (number > 9) return;
                if (!placeholderArray.includes(number)) 
                {
                    placeholderArray.push(number);
                }
            });

            for (var i = 1; i <= placeholderArray.length; i++) 
            {
                if (placeholderArray.includes(i)) continue;
                placeholderArray.push(i);
            }

            placeholderArray.sort((a, b) => a - b); 
            placeholderArray.forEach(function(num) 
            {
                text = text.replace(`{{${num}}}`, `{{${num}}}`); 
            });

            $('#placeholders').val(JSON.stringify(placeholderArray));

            $(this).val(text);
        } 
        else 
        {
            $('#placeholders').val('[]');
        }

        validateContent();
    });

    $('#content').on('keypress', function(e) 
    {
        var key = String.fromCharCode(e.which);

        if (key === '{') 
        {
            e.preventDefault();
            
            var currentValue = $(this).val();
            var newText = currentValue + '{{' + currentPlaceholderIndex + '}}';
            $(this).val(newText);
            currentPlaceholderIndex++;
            $('#placeholders').val(JSON.stringify(Array.from({ length: currentPlaceholderIndex - 1 }, (_, i) => i + 1))); 
        }

        validateContent();
    });

    let currentPlaceholders = [];

    $('#content').on('input', function() 
    {
        var placeholders = $('#placeholders').val();
        var newPlaceholders = placeholders.replace(/[\[\]]/g, '').split(',').filter(Boolean);
        
        newPlaceholders.forEach(function(value, index) 
        {
            if (!currentPlaceholders.includes(value)) 
            {
               var html = `
                            <div class="mb-3 sample_value_repeater" id="sample_value_${value}">
                                <label for="sample_value_${value}" class="form-label">Sample Values for {{${value}}}</label>`;

                        if (index == 0) 
                        {
                            html += `
                                <p>
                                    Specify sample values for your parameters. These values can be changed at the time of sending.
                                    e.g. - {{1}}: Mohit, {{2}}: 5.
                                </p>`;
                        }

                        html += `
                                <input type="text" class="form-control" name="sample_value[]" value="" placeholder="Sample value">
                            </div>`;


                
                if ($('.sample_value_repeater').length > 0) 
                {
                    $('.sample_value_repeater').last().after(html);
                }
                else
                {
                    $('.content').after(html);
                }
            }
        });

        currentPlaceholders.forEach(function(value) 
        {
            if (!newPlaceholders.includes(value)) 
            {
                $(`#sample_value_${value}`).remove();
            }
        });

        currentPlaceholders = newPlaceholders;
    });

    $('body').on('click', '.audience-attribute-insert-repeater', function (e) {

        var repeaterclass = $(this).data('repeaterclass');

        if (repeaterclass)
        {
            var html = $("." + repeaterclass + " .node:first-child").clone();
            $("." + repeaterclass).append(html);
            $("." + repeaterclass + " .node:last-child").find('input').val('').change();
        }
        
    });

    $('body').on('change', '#first_message', function (e) {

        if ($(this).prop('checked'))
        {
            $('.first_message_value').removeClass('d-none');
        }
        else
        {
            $('.first_message_value').addClass('d-none');
        }
    });

    $('body').on('click', '.first-message-insert-repeater', function (e) {
        e.preventDefault();

        var repeaterclass = $(this).data('repeaterclass');

        if (repeaterclass) 
        {
            var html = $("." + repeaterclass + " .node:first").clone();
            html.find('input').val('');
            $("." + repeaterclass).append(html);
        }

    });

    var currentStep = 0;

    $(".next-btn").on("click", function() {

        if (!$('.campaign-name-section').hasClass('d-none')) 
        {
            var campaign_name = $('.field_campaign_name').val();

            $('.error-message-text').text('');

            if (campaign_name === '') 
            {
                showToast('Campaign name is required!', 'Error');

                $('.field_campaign_name').after('<p class="error-message-text text-danger mt-2">Campaign name is required!</p>');
                return;
            }

            $('.campaign-name-section').addClass('d-none');
            $('.select-audience-section').removeClass('d-none');
            $('.prev-btn').removeClass('d-none');
        }
        else if (!$('.select-audience-section').hasClass('d-none'))
        {
            var campaign_audience = parseInt($('.campaign-audience').text().trim(), 10);
            var csv_file = $('.csv_file').val();
            

            if (campaign_audience <= 0) 
            {
                showToast('Contacts not found please add it first!', 'Error');
                return;
            }

            if (csv_file === '') 
            {
                showToast('CSV file field is required!', 'Error');
                return;
            }
            if ($('#csv_file').length > 0 && $('#csv_file')[0].files.length > 0) 
            {
                var file = $('#csv_file')[0].files[0];
                var validExtensions = ['txt', 'csv'];
                var fileExtension = file.name.split('.').pop().toLowerCase();

                if (!validExtensions.includes(fileExtension)) 
                {
                    showToast('Invalid file type. Please upload a valid .csv file.', 'Error');
                    this.value = '';
                    return;
                }
            }
            if ($('input[name="message_type"]:checked').val() == 1) 
            {
                if ($('.sample_value_repeater').length > 0) 
                {
                    $('.sample_value_repeater').remove();
                    $('.preview').html('');
                }
            }
            else if ($('input[name="message_type"]:checked').val() == 0)
            {
                if ($('.sample_value_repeater').length < 1) 
                {
                    $('.preview').html('');
                }
            }

            $('.select-audience-section').addClass('d-none');
            $('.create-message-section').removeClass('d-none');
        }
        else if (!$('.create-message-section').hasClass('d-none'))
        {
            var template_id = $('.field_template_id').val();
            var template_message = $('.field_template_message').val();
            var message_type = $('input[name="message_type"]:checked').val();

            $('.error-message-text').text('');

            if (message_type == 0) 
            {
                if (template_id === '') 
                {
                    showToast('Template field is required!', 'Error');

                    $('.field_template_id').next('.select2').after('<p class="error-message-text text-danger mt-2">Template field is required!</p>');
                    return;
                }

                if ($('.sample_value_repeater').length > 0 && $('.sample_value_input').val() == '') 
                {
                    showToast('Sample value is required!', 'Error');

                    $('.sample_value_input').after('<p class="error-message-text text-danger mt-2">Sample value is required!</p>');
                    return;
                }
            }
            else
            {
                if (template_message === '') 
                {
                    showToast('Template message field is required!', 'Error');

                    $('.field_template_message').after('<p class="error-message-text text-danger mt-2">Template message field is required!</p>');
                    return;
                }
            }

            $('.create-message-section').addClass('d-none');
            $('.test-campaign-section').removeClass('d-none');
        }
        else if (!$('.test-campaign-section').hasClass('d-none'))
        {
            var campaign_name = $('.field_campaign_name').val();

            $('.preview_campaign_name').text(campaign_name);

            $('.test-campaign-section').addClass('d-none');
            $('.preview-send-section').removeClass('d-none');
            $(this).addClass('d-none');
            $('.btn-ajax-show-processing').removeClass('d-none');

            getCampaignCheckout();
        }

        var allButtons = $('.custom-button');
        var totalSteps = allButtons.length - 1;

        if (currentStep < totalSteps) 
        {
            currentStep++;
        }

        var progressValue = $(allButtons[currentStep]).data("progress");

        $("#custom-progress-bar").css("width", progressValue + "%");
        $("#custom-progress-bar").attr("aria-valuenow", progressValue);

        $(".custom-button").removeClass("custom-button-active").addClass("custom-button-inactive");

        allButtons.each(function(index) {
            if (index <= currentStep) {
                $(this).removeClass("custom-button-inactive").addClass("custom-button-active");
            }
        });
        
    });

    $(document).on('input', 'textarea[name="template_message"]', function () {
        let text = $(this).val();
        var form = $(this).closest('form');
        form.find('.preview').html(text.replace(/\n/g, "<br>"));
    });

    if ($('input[name="message_type"]:checked').val() == 0) 
    {
        if (!$('.template-message').hasClass('d-none')) 
        {
            $('.template-message').addClass('d-none');
        }
    }
    else
    {
        if ($('.template-message').hasClass('d-none')) 
        {
            $('.template-message').removeClass('d-none');
        }
    }

    $('body').on('change', '[name="message_type"]', function (e) {

        if ($(this).val() == 0) 
        {
            $('.template-message').addClass('d-none');
            $('.template-id').removeClass('d-none');
        }
        else
        {
            $('.template-message').removeClass('d-none');
            $('.template-id').addClass('d-none');
        }
    });

    $(".prev-btn").on("click", function() {

        var allButtons = $('.custom-button');

        if (currentStep > 0) {
            currentStep--;
        }

        var progressValue = $(allButtons[currentStep]).data("progress");

        $("#custom-progress-bar").css("width", progressValue + "%");
        $("#custom-progress-bar").attr("aria-valuenow", progressValue);

        $(".custom-button").removeClass("custom-button-active").addClass("custom-button-inactive");

        allButtons.each(function(index) {
            if (index <= currentStep) {
                $(this).removeClass("custom-button-inactive").addClass("custom-button-active");
            }
        });

        if (!$('.select-audience-section').hasClass('d-none')) 
        {
            $('.select-audience-section').addClass('d-none');
            $('.campaign-name-section').removeClass('d-none');
            $(this).addClass('d-none');
        } 
        else if (!$('.create-message-section').hasClass('d-none')) 
        {
            $('.create-message-section').addClass('d-none');
            $('.select-audience-section').removeClass('d-none');
        }
        else if (!$('.test-campaign-section').hasClass('d-none')) 
        {
            $('.test-campaign-section').addClass('d-none');
            $('.create-message-section').removeClass('d-none');
        }
        else if (!$('.preview-send-section').hasClass('d-none')) 
        {
            $('.preview-send-section').addClass('d-none');
            $('.test-campaign-section').removeClass('d-none');
            $('.next-btn').removeClass('d-none');
            $('.btn-ajax-show-processing').addClass('d-none');
        }

    });

    $('.last_seen_in_24hr').on('click', function() {

        $('#last_seen_from').val(getDateBeforeDays(1));
        $('#last_seen_to').val(getTodayDate());
        $('#last_seen').val(0);

        if ($('.last_seen_clear_btn').hasClass('d-none')) 
        {
            $('.last_seen_clear_btn').removeClass('d-none');
        }
    });

    $('.last_seen_this_week').on('click', function() {

        $('#last_seen_from').val(getDateBeforeDays(7));
        $('#last_seen_to').val(getTodayDate());
        $('#last_seen').val(1);

        if ($('.last_seen_clear_btn').hasClass('d-none')) 
        {
            $('.last_seen_clear_btn').removeClass('d-none');
        }
    });

    $('.last_seen_this_month').on('click', function() {

        $('#last_seen_from').val(getDateBeforeDays(30));
        $('#last_seen_to').val(getTodayDate());
        $('#last_seen').val(2);

        if ($('.last_seen_clear_btn').hasClass('d-none')) 
        {
            $('.last_seen_clear_btn').removeClass('d-none');
        }
    });

    $('.last_seen_clear_btn').on('click', function() {
        $('#last_seen_from').val('');
        $('#last_seen_to').val('');
        $('#last_seen').val('');
    });

    $('.created_at_clear_btn').on('click', function() {
        $('#created_at_from').val('');
        $('#created_at_to').val('');
        $('#created_at').val('');
    });

    $('.created_at_in_24hr').on('click', function() {

        $('#created_at_from').val(getDateBeforeDays(1));
        $('#created_at_to').val(getTodayDate());
        $('#created_at').val(0);

        if ($('.created_at_clear_btn').hasClass('d-none')) 
        {
            $('.created_at_clear_btn').removeClass('d-none');
        }
    });

    $('.created_at_this_week').on('click', function() {

        $('#created_at_from').val(getDateBeforeDays(7));
        $('#created_at_to').val(getTodayDate());
        $('#created_at').val(1);

        if ($('.created_at_clear_btn').hasClass('d-none')) 
        {
            $('.created_at_clear_btn').removeClass('d-none');
        }
    });

    $('.created_at_this_month').on('click', function() {

        $('#created_at_from').val(getDateBeforeDays(30));
        $('#created_at_to').val(getTodayDate());
        $('#created_at').val(2);

        if ($('.created_at_clear_btn').hasClass('d-none')) 
        {
            $('.created_at_clear_btn').removeClass('d-none');
        }
    });

    $('body').on('click', '.created_at_in_24hr, .created_at_this_week, .created_at_this_month', function (e) {

        var created_at_from = $('#created_at_from').val();
        var created_at_to = $('#created_at_to').val();
        var url = $('.created_at_in_24hr').data('url');

        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            data: { 
                created_at_start_date: created_at_from, 
                created_at_end_date: created_at_to 
            },
            cache: false,
            success: function(data) {
                if (data.status < 1)
                {
                    showToast(data.message, 'Error');
                    $('.campaign-audience').text(data.campaign_audience);
                }

                if (data.status == 1) 
                {
                    showToast(data.message, 'Success');
                    $('.campaign-audience').text(data.campaign_audience);
                }
            }
        });

    });

    var campaign_audience = $('.campaign-audience').text();
    $('.get-audience-size').text(campaign_audience);

    $('body').on('change', '#template_id', function (e) {

        var _this = $(this);
        var id = $(this).val();
        var url = $(this).data('url');

        $.ajax({
            url: url+ '/' + id,
            type: 'GET',
            data: { 
                template_id: id, 
            },
            success: function(data) {

                if (data.status == 1) 
                {
                    const response = data.data;
                    var form = _this.closest('form');

                    const sampleValue = JSON.parse(response.sample_value || '[]'); // Parse JSON array
                    let html = '';

                    if (response.category_id === 3) 
                    {
                        if (sampleValue.length) {
                            form.find('.preview').html(
                                '<p class="prepended_sample">[' + sampleValue.join(', ') + '] is your verification code.</p><p>' +
                                'This code expires in ' + response.expiration_warning + ' minute(s).</p>'
                            );
                        } else {
                            form.find('.preview').html(
                                'This code expires in ' + response.expiration_warning + ' minute(s).'
                            );
                        }
                    }
                    else
                    {
                        if (response.header_text) 
                        {
                            form.find('.preview').html(
                                '<p>' + response.header_text + '</p>'
                            );
                        }

                        if (response.content) 
                        {
                            form.find('.preview').append(
                                '<p>' + response.content + '</p>'
                            );
                        }

                        if (response.footer_text) 
                        {
                            form.find('.preview').append(
                                '<p>' + response.footer_text + '</p>'
                            );
                        }
                            
                    }

                    $('.sample_value_repeater').remove();

                    sampleValue.forEach((value, index) => {
                        html += `
                            <div class="mb-3 sample_value_repeater" id="sample_value_${value}">
                                <label for="sample_value_${value}" class="form-label">Sample Values for {{${value}}}</label>`;

                        if (index === 0) 
                        {
                            html += `
                                <p>
                                    Specify sample values for your parameters. These values can be changed at the time of sending.
                                    e.g. - {{1}}: Mohit, {{2}}: 5.
                                </p>`;
                        }

                        html += `
                                <input type="text" class="form-control sample_value_input" name="sample_value[]" value="${value}" placeholder="Sample value">
                            </div>`;
                    });

                    if ($('.sample_value_repeater').length > 0) 
                    {
                        $('.sample_value_repeater').last().after(html);
                    } 
                    else 
                    {
                        $('.content').after(html);
                    }
                }
            }
        });
    });

    $(document).on('shown.bs.modal', '#config-message-modal-edit', function() {
        
        var valueToSelect = $(this).find("#tmp_id").val();
        var selectElement = $(this).find("#template_id");

        if (valueToSelect.trim() != '')
        {
            selectElement.val(valueToSelect).trigger("change");
        }
        
    });

    $('body').on('change', '#campaign_contact', function() {

        var selected_contacts = $(this).val();

        var selected_count = selected_contacts ? selected_contacts.length : 0;

        $('.campaign-audience').text(selected_count);
    });

    $(document).on('change', '#csv_file', function () {
        var file = this.files[0];

        if (!file) 
        {
            showToast('No file selected.', 'Error');
            return;
        }

        var validExtensions = ['csv'];
        var fileExtension = file.name.split('.').pop().toLowerCase();

        if (!validExtensions.includes(fileExtension)) 
        {
            showToast('Invalid file type. Please upload a valid .csv file.', 'Error');
            return;
        }

        var reader = new FileReader();

        reader.onload = function (e) {

            var fileContent = e.target.result;

            var rows = fileContent.split(/\r?\n/);

            var header = rows[0].split(',');

            var select = $('.map-to-attribute');
            select.empty();

            $('.audience-alert').remove();
            $('.csv-header').html('');

            header.forEach(function (col) {
                
                $('.csv-header').append('<div class="mb-3">' + col.trim() + '</div>');
                select.append(`<select class="form-select mb-1" name="mapped_attribute[]">
                                    <option value="user name">user name</option>
                                    <option value="phone number">phone number</option>
                                    <option value="tags">tags</option>
                                </select>`);
            });

            // select.append('<option value="' + col.trim() + '">' + col.trim() + '</option>');

            var rowCount = rows.filter(row => row.trim() !== '').length - 1;

            $('.get-audience-size').text(rowCount);

           $('.alert-message').append('<p class="alert alert-success audience-alert">' + rowCount + ' Contacts detected</p>');
        };

        reader.readAsText(file);
    });

    var default_code = $('.default_country_code').find('option:selected');
    var flag = default_code.data('flag');

    $('.default-country-flag').val(flag);

    $('body').on('change', '.default_country_code', function () {

        var default_code = $(this).find('option:selected');
        var flag = default_code.data('flag');

        $('.default-country-flag').val(flag);
    });
    
    $('.audience_clear_all_btn').on('click', function() {

        $('.select-audience-section').find('input, select').val('');
    });

    $('.b-csv-dropzone-input').on('input', function() {

        if ($(this).val().length) {
            $('.b-csv-dropzone').addClass('d-none');
        } else {
            $('.b-csv-dropzone').removeClass('d-none');
        }
    });

    if ($('[name="template_type"]:checked').val() == 1)
    {
        $('.pre-approved-message').removeClass('d-none');
        $('.regular-message').addClass('d-none');
    }
    else if ($('[name="template_type"]:checked').val() == 0)
    {
        $('.pre-approved-message').addClass('d-none');
        $('.regular-message').removeClass('d-none');
    }
    else
    {
        $('.pre-approved-message').addClass('d-none');
    }

    $('body').on('change', '[name="template_type"]', function (e) {

        if ($(this).val() == 1)
        {
            $('.pre-approved-message').removeClass('d-none');
            $('.regular-message').addClass('d-none');
        }
        else if ($(this).val() == 0)
        {
            $('.pre-approved-message').addClass('d-none');
            $('.regular-message').removeClass('d-none');
        }
        else
        {
            $('.pre-approved-message').addClass('d-none');
        }
    });

    $('body').on('change', '.mon', function (e) {

        if ($(this).prop('checked'))
        {
            $('.mon-timing').find('p').first().addClass('d-none');
            $('.mon-timing').find('div').removeClass('d-none');
        }
        else
        {
            $('.mon-timing').find('p').first().removeClass('d-none');
            $('.mon-timing').find('div').addClass('d-none');
        }
    });

    $('body').on('change', '.tue', function (e) {

        if ($(this).prop('checked'))
        {
            $('.tue-timing').find('p').first().addClass('d-none');
            $('.tue-timing').find('div').removeClass('d-none');
        }
        else
        {
            $('.tue-timing').find('p').first().removeClass('d-none');
            $('.tue-timing').find('div').addClass('d-none');
        }
    });

    $('body').on('change', '.wed', function (e) {

        if ($(this).prop('checked'))
        {
            $('.wed-timing').find('p').first().addClass('d-none');
            $('.wed-timing').find('div').removeClass('d-none');
        }
        else
        {
            $('.wed-timing').find('p').first().removeClass('d-none');
            $('.wed-timing').find('div').addClass('d-none');
        }
    });

    $('body').on('change', '.thu', function (e) {

        if ($(this).prop('checked'))
        {
            $('.thu-timing').find('p').first().addClass('d-none');
            $('.thu-timing').find('div').removeClass('d-none');
        }
        else
        {
            $('.thu-timing').find('p').first().removeClass('d-none');
            $('.thu-timing').find('div').addClass('d-none');
        }
    });

    $('body').on('change', '.fri', function (e) {

        if ($(this).prop('checked'))
        {
            $('.fri-timing').find('p').first().addClass('d-none');
            $('.fri-timing').find('div').removeClass('d-none');
        }
        else
        {
            $('.fri-timing').find('p').first().removeClass('d-none');
            $('.fri-timing').find('div').addClass('d-none');
        }
    });

    $('body').on('change', '.sat', function (e) {

        if ($(this).prop('checked'))
        {
            $('.sat-timing').find('p').first().addClass('d-none');
            $('.sat-timing').find('div').removeClass('d-none');
        }
        else
        {
            $('.sat-timing').find('p').first().removeClass('d-none');
            $('.sat-timing').find('div').addClass('d-none');
        }
    });

    $('body').on('change', '.sun', function (e) {

        if ($(this).prop('checked'))
        {
            $('.sun-timing').find('p').first().addClass('d-none');
            $('.sun-timing').find('div').removeClass('d-none');
        }
        else
        {
            $('.sun-timing').find('p').first().removeClass('d-none');
            $('.sun-timing').find('div').addClass('d-none');
        }
    });

    $('.mon').trigger('change');
    $('.tue').trigger('change');
    $('.wed').trigger('change');
    $('.thu').trigger('change');
    $('.fri').trigger('change');
    $('.sat').trigger('change');
    $('.sun').trigger('change');

    if ($('#message_type').val() != 'TEXT')
    {
        if ($('#message_type').val() == 'FILE') 
        {
            $('.doc_label').removeClass('d-none');
            $('.img_label').addClass('d-none');
            $('.video_label').addClass('d-none');
            $('.audio_label').addClass('d-none');
        }
        else if ($('#message_type').val() == 'VIDEO')
        {
            $('.doc_label').addClass('d-none');
            $('.img_label').addClass('d-none');
            $('.video_label').removeClass('d-none');
            $('.audio_label').addClass('d-none');    
        }
        else if ($('#message_type').val() == 'AUDIO')
        {
            $('.doc_label').addClass('d-none');
            $('.img_label').addClass('d-none');
            $('.video_label').addClass('d-none');
            $('.audio_label').removeClass('d-none');
        }
        else if ($('#message_type').val() == 'IMAGE')
        {
            $('.doc_label').addClass('d-none');
            $('.img_label').removeClass('d-none');
            $('.video_label').addClass('d-none');
            $('.audio_label').addClass('d-none');
        }
        else
        {
            $('.doc_label').addClass('d-none');
            $('.img_label').removeClass('d-none');
            $('.video_label').addClass('d-none');
            $('.audio_label').addClass('d-none');
        }

        $('.media_url').removeClass('d-none');
        $('.file_name').removeClass('d-none');
    }
    else
    {
        $('.media_url').addClass('d-none');
        $('.file_name').addClass('d-none');
    }
    
    $('body').on('change', '#message_type', function (e) {

        if ($(this).val() != 'TEXT')
        {
            if ($(this).val() == 'FILE') 
            {
                $('.doc_label').removeClass('d-none');
                $('.img_label').addClass('d-none');
                $('.video_label').addClass('d-none');
                $('.audio_label').addClass('d-none');
            }
            else if ($(this).val() == 'VIDEO')
            {
                $('.doc_label').addClass('d-none');
                $('.img_label').addClass('d-none');
                $('.video_label').removeClass('d-none');
                $('.audio_label').addClass('d-none');    
            }
            else if ($(this).val() == 'AUDIO')
            {
                $('.doc_label').addClass('d-none');
                $('.img_label').addClass('d-none');
                $('.video_label').addClass('d-none');
                $('.audio_label').removeClass('d-none');
            }
            else if ($(this).val() == 'IMAGE')
            {
                $('.doc_label').addClass('d-none');
                $('.img_label').removeClass('d-none');
                $('.video_label').addClass('d-none');
                $('.audio_label').addClass('d-none');
            }
            else
            {
                $('.doc_label').addClass('d-none');
                $('.img_label').removeClass('d-none');
                $('.video_label').addClass('d-none');
                $('.audio_label').addClass('d-none');
            }

            $('.media_url').removeClass('d-none');
            $('.file_name').removeClass('d-none');
        }
        else
        {
            $('.media_url').addClass('d-none');
            $('.file_name').addClass('d-none');
        }
    });

    $('body').on('click', '#copy-key', function () {
        
        const apiKey = $('#api-key');

        apiKey.select();

        document.execCommand('copy');

        if (!$('#copy-message').length) 
        { 
            apiKey.after('<p id="copy-message" class="text-danger">API key copied to clipboard!</p>');
        }

        setTimeout(function () 
        {
            $('#copy-message').fadeOut(500, function () 
            {
                $(this).remove();
            });

        }, 3000);

    });

    $('.country-code-item').on('click', function (e) {
        e.preventDefault();

        var selected_code = $(this).data('code');

        $('.country-code-btn').text(selected_code);

        $('#country_code').val(selected_code);
    });

    $('body').on('change', '#schedule_date_and_time', function (e) {

        if ($(this).prop('checked'))
        {
            $('.schedule_date_and_time_div').removeClass('d-none');
        }
        else
        {
            $('.schedule_date_and_time_div').addClass('d-none');
        }
    });

    $('body').on('change', '#retry_campaign', function (e) {

        if ($(this).prop('checked'))
        {
            $('.retry_campaign_section').removeClass('d-none');
        }
        else
        {
            $('.retry_campaign_section').addClass('d-none');
        }
    });

    $('body').on('click', '#test-campaign-btn', function (e) {

        var button = $(this);
        var template_id = $('#template_id').val();
        var template_message = $('.field_template_message').val();
        var message_type = $('input[name="message_type"]:checked').val();
        var test_campaign = $('#test_campaign').val();
        var whatsapp_number = $('#whatsapp_number').val();
        var country_code = $('#country_code').val();
        var url = $(this).data('url');

        if (template_id.trim() === '' && template_message.trim() === '') 
        {
            showToast('Template is required!', 'Error');
            setTimeout(function() { buttonLoading(button, 0); }, 2000);
            return;
        }

        if (test_campaign.trim() === '') 
        {
            showToast('Username is required!', 'Error');
            setTimeout(function() { buttonLoading(button, 0); }, 2000);
            return;
        }

        if (whatsapp_number.trim() === '') 
        {
            showToast('Whatsapp number is required!', 'Error');
            setTimeout(function() { buttonLoading(button, 0); }, 2000);
            return;
        }

        if (country_code.trim() === '') 
        {
            showToast('Country code number is required!', 'Error');
            setTimeout(function() { buttonLoading(button, 0); }, 2000);
            return;
        }

        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            data: { 
                template_id: template_id,
                template_message: template_message,
                message_type: message_type,
                test_campaign: test_campaign,
                whatsapp_number: whatsapp_number,
                country_code: country_code, 
            },
            success: function(data) {

                if (data.status < 1) 
                {
                    if (data.message) 
                    {
                        showToast(data.message, 'Error');
                    }

                    buttonLoading(button, 0);
                }

                if (data.status == 1) 
                {
                    if (data.message) 
                    {
                        showToast(data.message, 'Success');
                    }

                    buttonLoading(button, 0);
                }
            }
        });
    });

    $('body').on('click', '.submit-attribute', function (e) {

        var button = $(this);

        $('.attribute_name').each(function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();

            if ($(this).val().trim() === '') 
            {
                isValid = false;
                $(this).addClass('is-invalid');
                $(this).after('<div class="invalid-feedback">Attribute name is required</div>');

                e.preventDefault();
            } 
        });
    });

    $('body').on('click', '.broadcast-btn', function (e) {

        var button = $(this);

        if ($('#retry_campaign').is(":checked")) 
        {
            $('.field_retry_hour').each(function() {

                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();

                if ($(this).val().trim() === '') 
                {
                    isValid = false;
                    $(this).addClass('is-invalid');
                    $(this).after('<div class="invalid-feedback">Retry hour is required!</div>');

                    e.preventDefault();
                } 
            });

            $('.field_retry_minute').each(function() {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();

                if ($(this).val().trim() === '') 
                {
                    isValid = false;
                    $(this).addClass('is-invalid');
                    $(this).after('<div class="invalid-feedback">Retry minute is required!</div>');

                    e.preventDefault();
                } 
            });
        }

    });

    $("#schedule_date_and_time").change(validateScheduleDateTime);

    $("#schedule_date, #schedule_time, #campaign_timezone").on("input", validateScheduleDateTime);

    validateScheduleDateTime();

    $('body').on('click', '.config-welcome-btn', function (e) {

        var wel_text = $('.welcome-msg').text();
        $('.template_message').text(wel_text);
        $('.show-msg').text(wel_text);
        $('#chat_type').val('Welcome Message');
    });

    $('body').on('click', '.off-hour-btn', function (e) {

        var wel_text = $('.off-hour-msg').text();
        $('.template_message').text(wel_text);
        $('.show-msg').text(wel_text);
        $('#chat_type').val('Off Hours Message');
    });

    $('body').on('click', '.birthday-message-btn', function (e) {

        $('#chat_type').val('Birthday Message');
        $('.template_message').text('');
        $('#config-message-modal-add').find('.preview').html('');
    });

    $("#timezone").val($("#time_zone").val()).trigger('change');

    $('body').on('change', '#auto_resolve_chat, #welcome_message, #off_hours_message, #birthday_message', function (e) {

        var url = $('#auto_resolve_chat').data('url');
        var auto_resolve_chat = $('#auto_resolve_chat:checked').val(); 
        var welcome_message = $('#welcome_message:checked').val();
        var off_hours_message = $('#off_hours_message:checked').val();
        var birthday_message = $('#birthday_message:checked').val(); 

        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            data: { 
                auto_resolve_chat: auto_resolve_chat, 
                welcome_message: welcome_message,
                off_hours_message: off_hours_message, 
                birthday_message: birthday_message, 
            },
            cache: false,
            success: function(data) {

                if (data.status == 1) 
                {
                    showToast(data.message, 'Success');

                    if (data.refresh)
                    {
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                }
            }
        });

    });

    $('#text').on('input', function() 
    {
        var form = $(this).closest('form');
        var content = $(this).val(); 
        
        if (content.trim() !== '') 
        {
            $('.preview').html(content.replace(/\n/g, '<br>'));
        }
    });
    
});

function validateScheduleDateTime() {

    $('.broadcast-btn').attr("disabled", false);

    if ($('#schedule_date_and_time').is(":checked")) 
    {
        if ($('#schedule_date').val().trim() === '') 
        {
            showToast('Schedule date is required!', 'Error');
            $('.broadcast-btn').attr("disabled", true);
        }

        if ($('#schedule_time').val().trim() === '')
        {
            showToast('Schedule time is required!', 'Error');
            $('.broadcast-btn').attr("disabled", true);
        }

        if ($('#campaign_timezone').val().trim() === '')
        {
            showToast('Schedule timezone is required!', 'Error');
            $('.broadcast-btn').attr("disabled", true);
        }
    } 
}

function getTodayDate() 
{
    var today = new Date();
    return today.toISOString().split('T')[0];
}

function getDateBeforeDays(days) 
{
    const pastDate = new Date();
    pastDate.setDate(pastDate.getDate() - days);
    return pastDate.toISOString().split('T')[0];
}

function getDateAfterDays(days) 
{
    var today = new Date();
    today.setDate(today.getDate() + days);
    return today.toISOString().split('T')[0];
}

function validateContent() 
{
    var text = $('#content').val();
    var hasPlaceholders = /\{\{\d+\}\}/g.test(text);
    if (!hasPlaceholders) 
    {
        $('#error-message').removeClass('d-none');
        $('#error-message').text('The content must have placeholders like "Hello {{1}}, your code will expire in {{2}} mins.".').show();
    } 
    else 
    {
        $('#error-message').hide();
    }
}

function buttonLoading(button, start = 1, prop = 1)
{
	if (start == 1)
	{
		button.find('.processing-show').removeClass('d-none');
		button.find('.default-show').addClass('d-none');
		button.addClass('disabled');

        if (prop == 1)
        {
            button.prop('disabled', true);
        }
	}
	else
	{
		button.find('.processing-show').addClass('d-none');
		button.find('.default-show').removeClass('d-none');
		button.removeClass('disabled');

        if (prop == 1)
        {
            button.prop('disabled', false);
        }
	}
}

function clearValidationErrors(_this = null)
{
    if (_this)
    {
        _this.find('.is-invalid').removeClass('is-invalid');
        _this.find('.invalid-feedback').remove();
    }
    else
    {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    }
}

document.onkeypress=function(e){
    resetMessages();
}

function getCampaignCheckout() 
{
    var url = $('#checkout_url').val();
    var campaign_contact = $('#campaign_contact').val() || null;
    var campaign_tag = $('#campaign_tag').val() || null;
    var created_at_from = $('#created_at_from').val() || null;
    var created_at_to = $('#created_at_to').val() || null;
    var csv_file = null;
    if ($('#csv_file').length > 0 && $('#csv_file')[0].files.length > 0) 
    {
        csv_file = $('#csv_file')[0].files[0];
    }
    var formData = new FormData();
    if (campaign_contact) 
    {
        formData.append('campaign_contact', campaign_contact);
    }

    if (campaign_tag) 
    {
        formData.append('campaign_tag', campaign_tag);
    }

    if (created_at_from) 
    {
        formData.append('created_at_start_date', created_at_from);
    }

    if (created_at_to) 
    {
        formData.append('created_at_end_date', created_at_to);
    }

    if (csv_file) 
    {
        formData.append('csv_file', csv_file);
    }

    $.ajax({
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {

            var tbody = $('.checkout-body');
            tbody.find('.appended-data').remove();

            var total_amount = 0;

            if (data.status === 1 && data.data) {
                $.each(data.data, function(index, country) {
                    var row = `<tr class="appended-data">
                                    <td>`+ country.count +`</td>
                                    <td>`+ country.country_name +`(` + country.dialing_code +`)</td>
                                    <td>`+ country.country_code +`</td>
                                    <td colspan="2" class="text-center">$`+ country.price +`</td>
                                </tr>`;
                    tbody.append(row);

                    total_amount += country.price;
                });
            }

            $('.total-amount').text(total_amount);
        }
    });
}

function showToast(message, type) {
    let options = {
        heading: type === "Success" ? "Success" : "Error",
        text: message,
        position: "top-right",
        loaderBg: "#fff",
        icon: type === "Success" ? "success" : "error",
        bgColor: type === "Success" ? "#28a745" : "#ff4d4d",
        textColor: "#fff",
        hideAfter: 5000,
        stack: 1
    };
    $.toast(options);
}

function resetMessages()
{
	if ($(".display-messages").length)
	{
		$(".display-messages").slideUp('slow');
	}

	clearValidationErrors();
}

function updateProgress(oEvent)
{
    if (oEvent.lengthComputable && $(".file-uploader").length)
    {
        var percentComplete = oEvent.loaded / oEvent.total;
        percentComplete = parseInt(percentComplete * 100);
        $(".file-uploader").removeClass('d-none');
        $(".file-uploader .progress-bar").attr("aria-valuenow", percentComplete);
        $(".file-uploader .progress-bar").css("width", percentComplete + "%");
        $(".file-uploader .progress-bar").text(percentComplete + "%");
    }
}

function refreshTable() {

    try{
        if (table) 
        {
            if ($(".remove-all-button").length)
            {
                $(".remove-all-button").addClass('d-none');
            }
            
            if ($(".select-all-checkbox").length)
            {
                $(".select-all-checkbox").prop('checked', false);
            }

            table.ajax.reload();

            if ($('#filter-modal').length)
            {
                $('#filter-modal').modal('hide');
                $('.filter-button').show();
                $('.filter-button').next('.processing-button').hide();
            }
        }
    }
    catch(err) {}
}

function removeItemData(_this, id, url, redirect = '')
{
    var title = 'Are you sure?';
    var text = 'You will not be able to revert this!';
    var icon = 'warning';
    var confirm_button_color = '#3085d6';
    var cancel_button_color = '#d33';
    var confirm_button_text = 'Yes, Remove It!';

    if (_this.data('title'))
    {
        title = _this.data('title');
    }

    if (_this.data('text'))
    {
        text = _this.data('text');
    }

    if (_this.data('icon'))
    {
        icon = _this.data('icon');
    }

    if (_this.data('confirmbuttoncolor'))
    {
        confirm_button_color = _this.data('confirmbuttoncolor');
    }

    if (_this.data('cancelbuttoncolor'))
    {
        cancel_button_color = _this.data('cancelbuttoncolor');
    }

    if (_this.data('confirmbuttontext'))
    {
        confirm_button_text = _this.data('confirmbuttontext');
    }

    Swal.fire({
        title: title,
        text: text,
        icon:icon,
        showCancelButton:true,
        showLoaderOnConfirm:true,
        confirmButtonColor:confirm_button_color,
        cancelButtonColor:cancel_button_color,
        confirmButtonText: confirm_button_text,
        preConfirm:function(t){

            return fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: 'id=' + id,
                    }).then(function(t){
                        if(!t.ok)throw new Error(t.statusText);

                        if (redirect || !table)
                        {
                            location.reload();
                        }
                        else
                        {
                            refreshTable();
                        }
                        return t.json();
                    })
                    .then(data => {
                        if (data.status < 1 && data.message) 
                        {
                            showToast(data.message, 'Error');
                        }
                    })
                    .catch(function(t){
                        Swal.showValidationMessage("Request failed. Please refresh the page and try again")
                    });
        }
    });
}

function openNavigation()
{
    var class_name = $('meta[name="class-to-open"]').attr('content');

    if (class_name)
    {
        var _this = $('.side-nav .' + class_name);

        if (_this.length)
        {
            _this.addClass("menuitem-active");
            _this.find('a').addClass("active");

            if (_this.parent().parent().hasClass('collapse'))
            {
                _this.parent().parent().addClass('show');
                _this.parent().parent().prev().attr('aria-expanded', 'true')
            }
        }
    }
}

function makeCode(length, upper_case = '')
{
    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const charactersLength = characters.length;
    let counter = 0;
    
    while (counter < length) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
        counter += 1;
    }

    if(upper_case)
    {
        result = result.toUpperCase();
    }

    return result;
}
function copyCode(Key) {
    navigator.clipboard.writeText(Key).then(function () {
        showToast('Copied: ' + Key, 'Success');
    }).catch(function (err) {
        showToast('Copy failed!', 'Error');
    });
}