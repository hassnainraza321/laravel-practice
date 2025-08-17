@extends('index')
@section('title', 'Users')

@section('content')
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body no-lt-rt-pad">
                {!! Helper::getDatatables(['Name', 'Email', 'Created At', 'Action']) !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-lib')
<script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
@endsection

@section('css-lib')
<link href="{{ asset('vendor/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js')
<script type="text/javascript">

    function createTable()
    {
        if (!$('.datatable').length)
        {
            return;
        }

        table = $('.datatable').DataTable({
                    drawCallback: function() {
                        if($('.dt-search').find('.remove-all-button').length < 1)
                        {
                            $('.dt-search').prepend(`<button type="button" class="btn btn-sm btn-danger me-1 d-none remove-all-button"><i class="ri-delete-bin-5-line"></i></button>`);
                        }
                    },
                    "pageLength": 10,
                    "scrollX": false,
                    "ordering": false,
                    "lengthChange": true,
                    "searching": true,
                    "responsive": true,
                    "processing": true,
                    "serverSide": true,
                    "language": {
                        "emptyTable": "{{ __('No result found') }}",
                        "search": ''
                    },
                    "layout": {
                        topStart: {
                            search: {
                                placeholder: 'Search...'
                            }
                        },
                        topEnd: function () {
                            return `<div class="dt-btns">
                                        <a href="" class="btn btn-sm btn-light me-1">Export</a>
                                    <div>`;
                        }
                    },
                    "ajax":
                    {
                        url: window.location.href,
                        data: function ( d ) {

                            if ($(".sorting-filter-menu").length)
                            {
                                d.sort_by = $(".sorting-filter-menu input:checked").val();
                            }
                        },
                        beforeSend: function(jqXHR) {},
                        error: function(jqXHR, textStatus, errorThrown){},
                        complete: function(jqXHR) {}
                    },
                    createdRow: function (row, data, dataIndex) {
                        
                        if (data.row_url)
                        {
                            $(row).addClass('row-url-redirect');
                            $(row).attr('data-rowurl', data.row_url);
                        }
                    },
                    "columnDefs": [
                        { className: "index-colum-checkbx no-rowurl-redirect", "targets": [ 0 ], "orderable": "false" },
                        { className: "text-left", "targets": [ 1 ] },
                        { className: "text-center", "targets": [ 2 ], "orderable": "false" },
                        { className: "text-left no-rowurl-redirect", "targets": [ 3 ], "orderable": "false" },
                        { className: "text-end actions-width no-rowurl-redirect", "targets": [ 4 ], "orderable": "false" },
                    ],
                    "columns":[
                        { "data": "index_data", "name":"index_data" },
                        { "data": "name", "name":"filter_index" },
                        { "data": "email", "name":"filter_index" },
                        { "data": "created_at", "name":"filter_index" },
                        { "data": "actions", "name":"filter_index" },
                    ]
                });
    }
</script>
@endsection
