@extends('index')
@section('title', Helper::getSiteTitle('Contacts'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Contacts</li>
                </ol>
            </div>
            <h4 class="page-title">Contacts</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body no-lt-rt-pad">
                @if($is_found)
                    {!! Helper::getDatatables(['Name', 'Whatsapp Number', 'Tags', 'Source', 'Created At', 'Action']) !!}
                @else
                    <div class="text-center mt-2 mb-5">
                        <img src="{{ asset('images/no-cat.svg') }}">
                        <h4>Add contacts to start broadcast.</h4>
                        <p>Use collections to start broadcast or whatsapp marketing.</p>
                        <a data-bs-toggle="modal" data-bs-target="#contact-modal-add" class="btn btn-sm btn-custom me-1">Create contact</a>
                        <button type="button" class="btn btn-sm btn-light me-1" data-bs-toggle="modal" data-bs-target="#contacts-import">Import</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@include('contact.modals.import-contacts')
@include('contact.modals.add')
@endsection

@section('meta')
<meta name="class-to-open" content="contacts">
<meta name="remove-url" content="{{ route('contacts.remove') }}">
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
                                        <a href="{{ route('contacts.export') }}" class="btn btn-sm btn-light me-1">Export</a>
                                        <button type="button" class="btn btn-sm btn-light me-1" data-bs-toggle="modal" data-bs-target="#contacts-import">Import</button>
                                        <a data-bs-toggle="modal" data-bs-target="#contact-modal-add" class="btn btn-sm btn-custom me-1">Create Contact</a>
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-light dropdown-toggle sorting-filter-btn" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ri-arrow-up-down-line"></i>
                                            </button>
                                            <div class="dropdown-menu sorting-filter-menu" aria-labelledby="dropdownMenuButton">
                                                <div class="dropdown-item">
                                                    <p class="mb-0">Sort By</p>
                                                </div>
                                                <div class="dropdown-item">
                                                    <div class="form-check form-checkbox-dark">
                                                        <input type="radio" id="customRadio1" name="sort_by" value="name-asc" class="form-check-input">
                                                        <label class="form-check-label" for="customRadio1">Name (A to Z)</label>
                                                    </div>
                                                </div>
                                                <div class="dropdown-item">
                                                    <div class="form-check form-checkbox-dark">
                                                        <input type="radio" id="customRadio2" name="sort_by" value="name-desc" class="form-check-input">
                                                        <label class="form-check-label" for="customRadio2">Name (Z to A)</label>
                                                    </div>
                                                </div>
                                                <div class="dropdown-item">
                                                    <div class="form-check form-checkbox-dark">
                                                        <input type="radio" id="customRadio3" name="sort_by" value="created_at-desc" class="form-check-input">
                                                        <label class="form-check-label" for="customRadio3">Created (New to Old)</label>
                                                    </div>
                                                </div>
                                                <div class="dropdown-item">
                                                    <div class="form-check form-checkbox-dark">
                                                        <input type="radio" id="customRadio4" name="sort_by" value="created_at-asc" class="form-check-input">
                                                        <label class="form-check-label" for="customRadio4">Created (Old to New)</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
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
                        { className: "text-left", "targets": [ 4 ], "orderable": "false" },
                        { className: "text-center", "targets": [ 5 ], "orderable": "false" },
                        { className: "text-center actions-width no-rowurl-redirect", "targets": [ 6 ], "orderable": "false" },
                    ],
                    "columns":[
                        { "data": "index_data", "name":"index_data" },
                        { "data": "name", "name":"filter_index" },
                        { "data": "whatsapp_number", "name":"filter_index" },
                        { "data": "title", "name":"filter_index" },
                        { "data": "source", "name":"filter_index" },
                        { "data": "created_at", "name":"filter_index" },
                        { "data": "actions", "name":"filter_index" },
                    ]
                });
    }
</script>
@endsection