<div id="tag-modal-{{ isset($data) && !empty($data) ? 'edit' : 'add' }}" class="modal fade main-scope-to-close" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-5">
            <div class="modal-header">
                <h3 class="modal-title">{{ isset($data) && !empty($data) ? 'Update Tag' : 'Add Tag' }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="ajax-form-submit" action="{{ isset($data) && !empty($data) ? route('tags.add', $data->id) : route('tags.add') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        @php $index = 'title'; @endphp
                                        <label class="form-label" for="{{ $index }}">{{ __('Tag Name') }} <span class="text-danger">*</span></label>
                                        <p>
                                            Pick something that describes your contact.
                                        </p>
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
                                        @php
                                            $index = 'tag_category';

                                            $tag_categories = DB::table('tag_categories')
                                                            ->where('project_id', Helper::getProjectId())
                                                            ->orderBy('tag_categories.name', 'asc')
                                                            ->get();

                                            $category = null;

                                            if (isset($data->tag_category_id) && !empty($data->tag_category_id)) 
                                            {
                                                $category = DB::table('tag_categories')->where('id', $data->tag_category_id)->value('name');
                                            }

                                        @endphp
                                        <label for="name" class="form-label">Category</label>
                                        <p>
                                            You can select from existing category or can go with new.
                                        </p>
                                        <div class="form-group">
                                            <input list="categories" id="{{ $index }}" name="{{ $index }}" class="form-control {{ $index }}" value="{{ $category }}" placeholder="Type or select a category" autocomplete="off">
                                            <datalist id="categories">
                                                @if(!$tag_categories->isEmpty())
                                                    @foreach($tag_categories as $category)
                                                        <option value="{{ $category->name }}"></option>
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
                                    <div class="row mb-3 d-md-flex align-items-center">
                                        <div class="col-md-10">
                                            <label for="customer_journey" class="form-label">{{ __('Customer Journey') }}</label>
                                            <p class="mb-0">Enable to track this tag in your customers' journey</p>
                                        </div>
                                        <div class="col-md-2 form-check form-switch form-switch-md form-checkbox-dark" dir="ltr">
                                            <input type="checkbox" class="form-check-input float-end" value="1" {{ isset($data) && $data->customer_journey == 1 ? 'checked' : '' }} name="customer_journey" id="customer_journey">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row mb-3 d-md-flex align-items-center">
                                        <div class="col-md-10">
                                            <label for="first_message" class="form-label">{{ __('First Message') }}</label>
                                            <p class="mb-0">Allows auto tagging if users' first message matches</p>
                                        </div>
                                        <div class="col-md-2 form-check form-switch form-switch-md form-checkbox-dark" dir="ltr">
                                            <input type="checkbox" class="form-check-input float-end" value="1" {{ isset($data) && $data->first_message == 1 ? 'checked' : '' }} name="first_message" id="first_message">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (isset($data))
                                @php
                                    $first_messages = DB::table('tag_messages')->where('tag_id', $data->id)->get();
                                @endphp
                            @endif
                            <div class="row first_message_value {{ !isset($first_messages) ? 'd-none' : '' }}">
                                <div class="col-md-12">
                                    <div class="row">
                                        <table class="table nowrap w-100 border-0 mb-0">
                                            <tbody class="repeater first-message-repeater">
                                                @if (isset($first_messages) && !$first_messages->isEmpty())
                                                    @foreach ($first_messages as $first_message)
                                                        <tr class="node">
                                                            <td>
                                                                <input type="text" class="form-control field_first_message" name="first_message_value[]" value="{{ $first_message->first_message }}">
                                                            </td>
                                                            <td class="text-right border-0">
                                                                <button type="button" class="btn btn-sm btn-light first-message-insert-repeater" data-repeaterclass="first-message-repeater"><i class="ri-add-line fs-14"></i></button>
                                                                <button type="button" class="btn btn-sm btn-soft-danger delete-repeater-node"><i class="ri-delete-bin-5-line"></i> </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr class="node">
                                                        <td>
                                                            <input type="text" class="form-control field_first_message" name="first_message_value[]" value="">
                                                        </td>
                                                        <td class="text-right border-0">
                                                            <button type="button" class="btn btn-sm btn-light first-message-insert-repeater" data-repeaterclass="first-message-repeater"><i class="ri-add-line fs-14"></i></button>
                                                            <button type="button" class="btn btn-sm btn-soft-danger delete-repeater-node"><i class="ri-delete-bin-5-line"></i> </button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
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