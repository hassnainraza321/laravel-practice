@extends('dashboard.index')
@section('title', Helper::getSiteTitle('Magazines'))

@section('css-lib')
    
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Magazines</li>
                    </ol>
                </div>
                <h4 class="page-title">Add Magazines</h4>
            </div>
        </div>
    </div>
	
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="header-title">MAGAZINES</h4>
                            <p class="sub-header">
                                You have create {{ count($magazines) }} magazines.
                            </p>
                        </div>
                        <div>
                            <button id="add_item" class="btn btn-primary" type="button">Add New</button>
                        </div>
                    </div>

                    <form class="d-flex flex-wrap align-items-center">
                        <div class="me-sm-3">
                            <input type="search" class="form-control search-in-datatables  input-sm" id="inputPassword2" placeholder="{{ __('Search') }}...">
                        </div>
                    </form>
                    <div class="card-box table-responsive add-border">
                        <table class="table datatable" style="width:100%">
                            <thead class="add-border-bottom table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </div> 
    </div>

    <div class="ajax_modal"></div>

    <div id="modal_popup" class="modal fade modal_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="item_form Item_form" action="{{ route('magazines') }}" method="post">
                    @csrf
                    
                    <div class="modal-header">
                        <h4 class="modal-title">Add Magazines</h4>
                        <button type="button" class="btn-close close-modal-button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="alert alert-success ajax_response_success d-none"></div>
                    <div class="alert alert-danger ajax_response_error d-none"></div>

                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    @php $index = 'name' @endphp
                                    <label for="{{ $index }}" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="{{ $index }}" name="{{ $index }}" placeholder="Add magazine name ...">
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
                                    <input type="text" class="form-control" id="{{ $index }}" name="{{ $index }}" placeholder="Add magazine slug ...">
                                    <span class="text-danger _slug">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="field-3" class="form-label">Description</label>
                                    <textarea class="form-control" name="description" placeholder="Add description here ...."> </textarea>
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

@endsection
@section('js-lib')
    <script>
     $(document).ready(function () {
         
        table = $('.datatable').DataTable({
            language:{paginate:{previous:"<i class='mdi mdi-chevron-left'>",next:"<i class='mdi mdi-chevron-right'>"}},
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                pagination.toggle(this.api().page.info().pages > 1);
            },
            "pageLength": 10,
            "scrollX": false,
            "ordering": false,
            "lengthChange": false,
            "searching": false,
            "responsive": false,
            "processing": true,
            "serverSide": true,
            "language": {
                "emptyTable": "{{ __('No result found') }}"
            },
            "ajax":
            {
                url: window.location.href,
                data: function ( d ) {

                    if ($(".search-in-datatables").length)
                    {
                        d.keyword = $(".search-in-datatables").val();
                    }

                },
                beforeSend: function(jqXHR) {},
                error: function(jqXHR, textStatus, errorThrown){},
                complete: function(jqXHR) {}
            },
            "columnDefs": [
                { className: "text-left", "targets": [ 0 ] },
                { className: "text-left", "targets": [ 1 ] },
                { className: "text-center", "targets": [ 2 ] },
                { className: "text-right", "targets": [ 3 ] },
                
            ],
            "columns":[
                { "data": "name", "name":"name" },
                { "data": "slug", "name":"slug" },
                { "data": "description", "name":"description" },
                { "data": "action", "name":"action" },
            ]
        });
   
     })
</script>
@endsection
