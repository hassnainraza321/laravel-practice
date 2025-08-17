<div id="con-close-modal" class="modal fade edit_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="item_form Item_form" action="{{ route('packages.edit', $id) }}" method="post">
                @csrf
                
                <div class="modal-header">
                    <h4 class="modal-title">Edit Packages</h4>
                    <button type="button" class="btn-close close-modal-button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="alert alert-success ajax_response_success d-none"></div>
                <div class="alert alert-danger ajax_response_error d-none"></div>

                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                @php $index = 'name' @endphp
                                <label for="{{ $index }}" class="form-label">Name :</label>
                                <input type="text" class="form-control" id="{{ $index }}" name="{{ $index }}" @if(isset($package->name)) value="{{ $package->name }}" @endif placeholder="Add package name ...">
                                <span class="text-danger _name">
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                @php $index = 'description' @endphp
                                <label for="{{ $index }}" class="form-label">Description :</label>
                                <textarea class="form-control" id="{{ $index }}" name="{{ $index }}" placeholder="Add description here ....">@if(isset($package->description)) {{ $package->description }} @endif</textarea>
                                <span class="text-danger description">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            @php $index = 'amount' @endphp
                            <label for="{{ $index }}" class="form-label">Amount :</label>
                            <input type="number" class="form-control" id="{{ $index }}" name="{{ $index }}" @if(isset($package->amount)) value="{{ $package->amount }}" @endif placeholder="Add package amount ...">
                            <span class="text-danger amount">
                            </span>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                @php $index = 'article_limit' @endphp
                                <label for="{{ $index }}" class="form-label">Article Limit :</label>
                                <input type="text" class="form-control" id="{{ $index }}" name="{{ $index }}" @if(isset($package->article_limit)) value="{{ $package->article_limit }}" @endif placeholder="Add article limit ...">
                                <span class="text-danger article_limit">
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