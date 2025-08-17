@extends('dashboard.index')
@section('title', Helper::getSiteTitle('Articles'))

@section('css-lib')
    
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Articles</li>
                    </ol>
                </div>
                <h4 class="page-title">Articles</h4>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="header-title">ARTICLES</h4>
                            <p class="sub-header">
                                You have create {{ count($articles) }} articles.
                            </p>
                        </div>
                        <div>
                            <a id="add_item" href="{{ route('articles.add') }}" class="btn btn-primary" >Add New</a>
                        </div>
                    </div>
                    <form class="d-flex flex-wrap align-items-center">
                        <div class="me-sm-3">
                            <input type="search" class="form-control search-in-datatables  input-sm" id="inputPassword2" placeholder="{{ __('Search') }}...">
                        </div>
                        <div class="me-sm-3 ampull-right ml-3">
                            <select class="form-control filter-select  am-font-12" id="article-select">
                                <option value="" selected>{{ __('Status') }}</option>
                                <option value="1">Publish</option>
                                <option value="0">Draft</option>
                            </select>
                        </div>
                    </form>
                
                    <div class="card-box table-responsive add-border">
                        <table class="table datatable" style="width:100%">
                            <thead class="add-border-bottom table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Status</th>
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

                    if ($("#article-select").length)
                    {
                        d.status_id = $("#article-select").val();
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
                { "data": "title", "name":"title" },
                { "data": "slug", "name":"slug" },
                { "data": "status", "name":"status" },
                { "data": "action", "name":"action" },
            ]
        });
   
     })
</script>
@endsection
