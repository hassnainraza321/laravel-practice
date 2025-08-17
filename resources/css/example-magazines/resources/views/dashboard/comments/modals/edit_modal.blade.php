<div id="con-close-modal" class="modal fade edit_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Comment</h4>
                    <button type="button" class="btn-close close-modal-button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form class="item_form" action="{{ route('comment.edit' , $id) }}" method="post">
                    @csrf

                    <div class="alert alert-success ajax_response_success d-none"></div>
                    <div class="alert alert-danger ajax_response_error d-none"></div>

                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    @php $index = 'name' @endphp
                                    <label for="{{ $index }}" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="{{ $index }}" name="{{ $index }}" @if(isset($comment->name)) value="{{ $comment->name }}" @endif >
                                    <span class="text-danger _name">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    @php $index = 'website' @endphp
                                    <label for="{{ $index }}" class="form-label">Website</label>
                                    <input type="text" class="form-control" id="{{ $index }}" name="{{ $index }}" @if(isset($comment->website)) value="{{ $comment->website }}" @endif >
                                    <span class="text-danger _website">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    @php $index = 'email' @endphp
                                    <label for="{{ $index }}" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="{{ $index }}" name="{{ $index }}" @if(isset($comment->website)) value="{{ $comment->email }}" @endif placeholder="Add email ...">
                                    <span class="text-danger _email">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="field-3" class="form-label">Comment</label>
                                    <textarea class="form-control" name="comment" placeholder="Add Comment here ....">@if(isset($comment->website)) {{ $comment->comment }} @endif</textarea>
                                    <span class="text-danger _comment">
                                    </span>
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