@extends('dashboard.index')
@section('title', Helper::getSiteTitle('Articles'))

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

                {{-- <button id="demo-delete-row" class="btn btn-danger btn-sm" disabled><i class="mdi mdi-close me-1"></i>Delete</button> --}}
                <table data-toggle="table" data-toolbar="#demo-delete-row" data-search="true"
                data-buttons-class="md btn-light" data-show-refresh="true" data-show-columns="true" data-sort-name="id" data-page-list="[5, 10, 20]"
                    data-page-size="5" data-pagination="true" data-show-pagination-switch="true" class="table-borderless text-center">
                    <thead class="table-light">
                        <tr>
                            <th data-field="state" data-checkbox="true"></th>
                            {{-- <th data-field="id" data-sortable="true" >Id</th> --}}
                            <th data-field="title" data-sortable="true">Title</th>
                            <th data-field="slug" data-sortable="true">Slug</th>
                            {{-- <th data-field="content" data-align="center" data-sortable="true">content</th> --}}
                            <th data-field="status" data-sortable="true">Status</th>
                            <th data-field="action" data-align="center" data-sortable="true">Action
                            </th>
                        </tr>
                    </thead>
                
                    <tbody>
                        @if (isset($articles) && !empty($articles))
                            @foreach ($articles as $article)
                                <tr>
                                    <td></td>
                                    {{-- <td>{{ $article->id }}</td> --}}
                                    <td>{{ $article->title }}</td>
                                    <td>{{ $article->slug }}</td>
                                    {{-- <td>{!! $article->content !!}</td> --}}
                                    <td><span class="badge {{ $article->status === 1 ? 'bg-success' : 'bg-danger' }}">{{ $article->status === 1 ? 'Publish' : 'Draft' }}</span></td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn border-0 p-1 m-0 bg-danger text-white rounded" data-bs-toggle="dropdown" data-bs-target="#dropdown">. . .</button>
                                            <div id="dropdown" class="dropdown-menu">
                                                <a href="{{ route('articles.edit', $article->id) }}" class="dropdown-item edit_magazine"><i data-feather="edit"></i> {{ __('Edit') }}</a>
                                                <a class="dropdown-item show_alert" data-url="{{ route('articles.remove', $article->id) }}"><i data-feather="trash-2"></i> {{ __('Delete') }}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                </div>
            </div> 
        </div> 
    </div>

@endsection
@section('js-lib')
    <script src="{{ asset('assets/libs/bootstrap-table/bootstrap-table.min.js') }}"></script>

    <script src="{{ asset('assets/js/pages/bootstrap-tables.init.js') }}"></script>
    
@endsection