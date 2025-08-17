@extends('index')
@section('title', Helper::getSiteTitle('User Attributes'))

@section('content')
<div class="row mb-2 mt-2">
    <div class="col-xl-10 offset-xl-1">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">User Attributes</li>
                </ol>
            </div>
            <label class="page-title">User Attributes</label>
        </div>
    </div>
</div>
<form action="{{ URL::current() }}" method="post" enctype="multipart/form-data" class="ajax-form-submit">
    <div class="row">
        @include('includes.show-message', ['extra_class' => 'col-xl-10 offset-xl-1 mb-2'])
        <div class="col-xl-10 offset-xl-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <h3 class="mt-3">Quick Guide</h3>
                                <p>
                                    Attributes store Dialogflow parameter values, and you can also assign them custom values from the contacts page. In addition to the default attributes ($Name, $MobileNumber, $LastName, $FirstName), you can create up to five user-defined attributes.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-10 my-3">
                                    <div class="row">
                                        <table class="table nowrap w-100 border-0">
                                            <thead class="bg-white">
                                                <tr>
                                                    <th class="border-0">Name*</th>
                                                    <th class="border-0">Action (optional)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="repeater user-attribute-repeater">
                                                @php
                                                    $user_attributes = DB::table('user_attributes')->where('project_id', Helper::getProjectId())->orderBy('id', 'asc')->get();
                                                @endphp
                                                @if(!$user_attributes->isEmpty())
                                                    @foreach($user_attributes as $user_attribute)
                                                        <tr class="node">
                                                            <td>
                                                                <input type="text" class="form-control attribute_name" name="attribute_name[]" value="{{ $user_attribute->name }}" placeholder="Enter attribute name">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control attribute_action" name="attribute_action[]" value="{{ $user_attribute->action }}" placeholder="Enter action name">
                                                            </td>
                                                            <td class="text-right border-0">
                                                                <button type="button" class="btn btn-sm btn-soft-danger delete-repeater-node"><i class="ri-delete-bin-5-line"></i> </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr class="node">
                                                        <td>
                                                            <input type="text" class="form-control attribute_name" name="attribute_name[]" value="" placeholder="Enter attribute name">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control attribute_action" name="attribute_action[]" value="" placeholder="Enter action name">
                                                        </td>
                                                        <td class="text-right border-0">
                                                            <button type="button" class="btn btn-sm btn-soft-danger delete-repeater-node"><i class="ri-delete-bin-5-line"></i> </button>
                                                        </td>
                                                    </tr>
                                                @endif
                                                
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-light insert-repeater" data-repeaterclass="user-attribute-repeater"><i class="ri-add-line fs-14"></i>Add More Attributes</button>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <button class="btn btn-dark btn-ajax-show-processing me-1 submit-attribute" type="submit">
                                        <span class="spinner-border spinner-border-sm processing-show d-none me-1" role="status" aria-hidden="true"></span>
                                        <span class="processing-show d-none">Saving...</span>
                                        <span class="default-show">Save Attributes</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('meta')
<meta name="class-to-open" content="user-attributes">
@endsection
