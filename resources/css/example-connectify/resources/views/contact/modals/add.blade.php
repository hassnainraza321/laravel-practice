<div id="contact-modal-{{ isset($data) && !empty($data) ? 'edit' : 'add' }}" class="modal fade main-scope-to-close" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-5">
            <div class="modal-header">
                <h3 class="modal-title">{{ isset($data) && !empty($data) ? 'Update Contact' : 'Add Contact' }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="ajax-form-submit" action="{{ isset($data) && !empty($data) ? route('contacts.add', $data->id) : route('contacts.add') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        @php $index = 'name'; @endphp
                                        <label class="form-label" for="{{ $index }}">{{ __('Name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" placeholder="Enter name">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        @php $index = 'whatsapp_number'; @endphp
                                        <label class="form-label" for="{{ $index }}">{{ __('Whatsapp Number') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" placeholder="Enter whatsapp number">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        @php
                                            $index = 'contact_tag';

                                            $tags = DB::table('tags')
                                                        ->where('project_id', Helper::getProjectId())
                                                        ->orderBy('tags.title', 'asc')
                                                        ->get();

                                            $tag = null;

                                            if (isset($data->tag_id) && !empty($data->tag_id)) 
                                            {
                                                $tag = DB::table('tags')->where('id', $data->tag_id)->value('title');
                                            }

                                        @endphp
                                        <label for="name" class="form-label">Tags</label>
                                        <div class="form-group">
                                            <input list="tags" id="{{ $index }}" name="{{ $index }}" class="form-control {{ $index }}" value="{{ $tag }}" placeholder="Type or select a tag" autocomplete="off">
                                            <datalist id="tags">
                                                @if(!$tags->isEmpty())
                                                    @foreach($tags as $tag)
                                                        <option value="{{ $tag->title }}"></option>
                                                    @endforeach
                                                @endif
                                            </datalist>
                                        </div>
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-0">
                                        @php $index = 'source'; @endphp
                                        <label class="form-label" for="{{ $index }}">{{ __('Source') }}</label>
                                        <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" placeholder="Enter source">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-light" data-bs-dismiss="modal">Cancel</a>
                    <button type="submit" class="btn btn-dark btn-ajax-show-processing">
                        <span class="processing-show d-none spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>
                        <i class="default-show mdi mdi-content-save-move me-1"></i>
                        <span class="processing-show d-none">{{ __('Saving') }}...</span>
                        <span class="default-show">{{ __('Submit') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>