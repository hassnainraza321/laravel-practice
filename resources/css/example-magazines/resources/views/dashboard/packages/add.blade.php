@extends('dashboard.index')
@section('title', Helper::getSiteTitle('Packages'))

@section('css-lib')
    <link href="{{ asset('assets/libs/bootstrap-table/bootstrap-table.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Packages</li>
                    </ol>
                </div>
                <h4 class="page-title">Add Packages</h4>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="header-title">PACKAGES</h4>
                            <p class="sub-header">
                                You have create {{ count($packages) }} Packages.
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
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Article limit</th>
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
                <form class="item_form Item_form" action="{{ route('packages.add') }}" method="post">
                    @csrf
                    
                    <div class="modal-header">
                        <h4 class="modal-title">Add Packages</h4>
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
                                    <input type="text" class="form-control" id="{{ $index }}" name="{{ $index }}" placeholder="Add package name ...">
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
                                    <textarea class="form-control" id="{{ $index }}" name="{{ $index }}" placeholder="Add description here ...."> </textarea>
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
                                <input type="number" class="form-control" id="{{ $index }}" name="{{ $index }}" placeholder="Add package amount ...">
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
                                    <input type="text" class="form-control" id="{{ $index }}" name="{{ $index }}" placeholder="Add article limit ...">
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
                
                { "data": "description", "name":"description" },
                { "data": "amount", "name":"amount" },
                { "data": "article_limit", "name":"article_limit" },
                { "data": "action", "name":"action" },
            ]
        });
   
     })
</script>
@endsection
