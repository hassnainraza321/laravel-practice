@extends('dashboard.index')
@section('title', Helper::getSiteTitle('Excel Sheet'))

@section('css-lib')
    
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Excel Sheet</li>
                    </ol>
                </div>
                <h4 class="page-title">Add Excel Sheet</h4>
            </div>
        </div>
    </div>
	
    <div class="row">
        <div class="col-sm-12 d-flex justify-content-center align-items-center">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('excel.sheets') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="excel_sheet">Sheet :</label>
                                <input type="file" name="excel_sheet" id="excel_sheet" class="form-control">
                                <span class="text-danger">
                                    @error('excel_sheet')
                                    {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button class="btn btn-primary btn-sm waves-effect waves-light">Submit</button>
                            <a href="{{ route('excel.sheets') }}" class="btn btn-primary btn-sm waves-effect waves-light">Export Excel</a>
                        </div>
                    </form>
                </div>
            </div> 
        </div> 
    </div>

@endsection
@section('js-lib')
   
@endsection
