@extends('dashboard.index')
@section('title', Helper::getSiteTitle('Users'))

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
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
                <h4 class="page-title">Users</h4>
            </div>
        </div>
    </div>
	
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="header-title">USERS</h4>
                            <p class="sub-header">
                                You have create {{ count($users) }} users.
                            </p>
                        </div>
                        
                    </div>

                    <form class="d-flex flex-wrap align-items-center">
                        <div class="me-sm-3">
                            <input type="search" class="form-control search-in-datatables  input-sm" id="inputPassword2" placeholder="{{ __('Search') }}...">
                        </div>
                        <div class="me-sm-3 ampull-right ml-3">
                            <select class="form-control filter-select  am-font-12" id="user-select">
                                <option value="" selected>{{ __('Status') }}</option>
                                <option value="0">Active</option>
                                <option value="1">Suspend</option>
                            </select>
                        </div>
                    </form>
                    <div class="card-box table-responsive add-border">
                        <table class="table datatable" style="width:100%">
                            <thead class="add-border-bottom table-light">
                                <tr>
                                    <th>Username</th>
                                    <th>Email</th>
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

                    if ($("#user-select").length)
                    {
                        d.status_id = $("#user-select").val();
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
                { "data": "username", "name":"username" },
                { "data": "email", "name":"email" },
                { "data": "account_status", "name":"account_status" },
                { "data": "action", "name":"action" },
            ]
        });
   
     })
</script>
@endsection
