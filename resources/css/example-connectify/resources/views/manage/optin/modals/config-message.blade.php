<div id="config-message-modal" class="modal fade main-scope-to-close" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content rounded-5">
            <form class="ajax-form-submit" action="#" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-body p-4">
                    <div class="row">
                        <h3>Configure Message</h3>
                        <p>Send template message from one of your pre approved templates. You can also opt to send regular message to active users.</p>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12 form-checkbox-dark">
                                    <div class="mb-3">
                                        <input type="radio" name="template_type" class="form-check-input" value="pre-approved-message">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Pre-approved template message
                                        </label>
                                    </div>
                                    <div class="mb-3">
                                        <input type="radio" name="template_type" class="form-check-input" value="regular-message" checked>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Regular Message
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-none pre-approved-message">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        @php $index = 'template_id'; @endphp
                                        <label for="{{ $index }}" class="form-label">
                                            Template Name
                                        </label>
                                        <p>Please choose a WhatsApp template message from your approved list</p>
                                        @php
                                            $templates = DB::table('templates')->where('project_id', Helper::getProjectId(request('ref_id')))->where('id', '!=', 0)->get();
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
                                    <div class="content"></div>
                                </div>
                            </div>
                            <div class="regular-message">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="field-3" class="form-label">{{ __('Message Type TEXT') }} <span class="text-danger">*</span></label>
                                            <p>You can select from existing category or can go with new.</p>
                                            <select id="category-select" class="form-control mt-2 " id="" name="">
                                                <option value="TEXT">TEXT</option>
                                                <option value="IMAGE">IMAGE</option>
                                                <option value="FILE">FILE</option>
                                                <option value="VIDEO">VIDEO</option>
                                                <option value="AUDIO">AUDIO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="">
                                            <label for="customer_journey" class="form-label">{{ __('Message') }}</label>
                                            <p>Your message can be upto 4096 characters long.</p>
                                            <textarea rows="3" class="form-control" name="template_message">Setup a response message for opt-out user keywords</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row d-md-flex justify-content-center align-items-center">
                                <div class="col-md-8">
                                    <div class="card shadow-md mt-3">
                                        <span>
                                            <img src="{{ asset('images/whatsapp-icon.png') }}" alt="whatsapp icon" class="preview-whatsapp-icon">
                                        </span>
                                        <div class="card-body p-2">
                                            <div class="p-0 m-0">
                                                <img src="{{ asset('images/no-cat.svg') }}" class="mx-auto d-block">
                                            </div>
                                            <div class="preview mt-3">
                                                <p>Setup a response message for opt-out user keywords</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark btn-ajax-show-processing">
                        <span class="processing-show d-none spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>
                        <i class="default-show mdi mdi-content-save-move me-1"></i>
                        <span class="processing-show d-none">{{ __('Saving') }}...</span>
                        <span class="default-show">{{ __('Save Configuration') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>