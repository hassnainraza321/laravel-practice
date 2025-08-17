<div id="con-close-modal" class="modal fade edit_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Magazines</h4>
                    <button type="button" class="btn-close close-modal-button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form class="item_form" action="{{ route('magazines.edit' , $magazine->id) }}" method="post">
                    @csrf

                    <div class="alert alert-success ajax_response_success d-none"></div>
                    <div class="alert alert-danger ajax_response_error d-none"></div>

                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    @php $index = 'name' @endphp
                                    <label for="{{ $index }}" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="{{ $index }}" name="{{ $index }}" @if(isset($magazine->name)) value="{{ $magazine->name }}" @endif placeholder="Add magazine name ...">
                                    <span class="text-danger _name">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    @php $index = 'slug' @endphp
                                    <label for="{{ $index }}" class="form-label">Slug</label>
                                    <input type="text" class="form-control" id="{{ $index }}" name="{{ $index }}" @if(isset($magazine->slug)) value="{{ $magazine->slug }}" @endif placeholder="Add magazine slug ...">
                                    <span class="text-danger _slug">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="field-3" class="form-label">Description</label>
                                    <textarea class="form-control" name="description" placeholder="Add description here ....">@if(isset($magazine->description)) {{ $magazine->description }} @endif </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect close-modal-button" data-bs-dismiss="modal">Close</button>
                        <button id="save_item" type="submit" class="btn btn-info waves-effect waves-light">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.modal -->