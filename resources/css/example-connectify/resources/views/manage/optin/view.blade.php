@extends('index')
@section('title', Helper::getSiteTitle('Optout Management'))

@section('content')
<div class="row mb-2 mt-2">
    <div class="col-xl-10 offset-xl-1">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Optout Management</li>
                </ol>
            </div>
            <label class="page-title">Optout Management</label>
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
                                <p>Setup keywords that user can type to Opt-in & Opt-out from messaging campaign</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <h3 class="mt-3">API Campaign Opt-out</h3>
                                <div class="col-sm-9">
                                    <p>Enable this if you don't wish to send api campaign to opted-out contacts</p>
                                </div>
                                <div class="col-md-3 form-check form-switch form-checkbox-dark form-switch-md" dir="ltr">
                                    <input type="checkbox" class="form-check-input float-end" value="1" name="" id="" checked>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h3 class="mt-3">Opt-out Keywords</h3>
                                    <p>The user will have to type exactly one of these messages on which they should be automatically opted-out</p>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <table class="table nowrap w-100 border-0">
                                                    <tbody class="repeater optout-keyword-repeater">
                                                        <tr class="node">
                                                            <td>
                                                                <input type="text" class="form-control mt-1 field_optout_keyword" name="optout_keyword[]" value="">
                                                            </td>
                                                            <td class="text-right border-0">
                                                                <button type="button" class="btn btn-sm btn-light insert-repeater" data-repeaterclass="optout-keyword-repeater"><i class="ri-add-line fs-14"></i></button>
                                                                <button type="button" class="btn btn-sm btn-soft-danger delete-repeater-node"><i class="ri-delete-bin-5-line"></i> </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-dark mb-3">Save Settings</button>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-6">
                                            <h3 class="mt-3">Opt-out Response</h3>
                                        </div>
                                        <div class="col-6 form-check form-switch form-checkbox-dark form-switch-md d-flex justify-content-end align-items-center">
                                            <input type="checkbox" class="form-check-input me-2" value="1" name="" id="" checked>
                                            <button class="btn btn btn-soft-dark" data-bs-toggle="modal" data-bs-target="#config-message-modal">Configure</button>
                                        </div>
                                        <p>Setup a response message for opt-out user keywords</p>
                                    </div>
                                    <div class="row d-md-flex justify-content-center">
                                        <div class="col-sm-8">
                                            <div class="card shadow-md mt-3">
                                                <span>
                                                    <img src="{{ asset('images/whatsapp-icon.png') }}" alt="whatsapp icon" class="preview-whatsapp-icon">
                                                </span>
                                                <div class="card-body p-2">
                                                    <div class="preview">
                                                        <p class="mt-2">You have been opted-out of your future communications</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-center">Auto response is disabled</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h3 class="mt-3">Opt-in Keywords</h3>
                                    <p>The user will have to type exactly one of these messages on which they should be automatically opted-in</p>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <table class="table nowrap w-100 border-0">
                                                    <tbody class="repeater optin-keyword-repeater">
                                                        <tr class="node">
                                                            <td>
                                                                <input type="text" class="form-control mt-1 field_optin_keyword" name="optin_keyword[]" value="">
                                                            </td>
                                                            <td class="text-right border-0">
                                                                <button type="button" class="btn btn-sm btn-light insert-repeater" data-repeaterclass="optin-keyword-repeater"><i class="ri-add-line fs-14"></i></button>
                                                                <button type="button" class="btn btn-sm btn-soft-danger delete-repeater-node"><i class="ri-delete-bin-5-line"></i> </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-dark mb-3">Save Settings</button>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-6">
                                            <h3 class="mt-3">Opt-in Response</h3>
                                        </div>
                                        <div class="col-6 form-check form-switch form-checkbox-dark form-switch-md d-flex justify-content-end align-items-center">
                                            <input type="checkbox" class="form-check-input me-2" value="1" name="" id="" checked>
                                            <button class="btn btn btn-soft-dark" data-bs-toggle="modal" data-bs-target="#config-message-modal">Configure</button>
                                        </div>
                                        <p>Setup a response message for opt-in user keywords</p>
                                    </div>
                                    <div class="row d-md-flex justify-content-center">
                                        <div class="col-sm-8">
                                            <div class="card shadow-md mt-3">
                                                <span>
                                                    <img src="{{ asset('images/whatsapp-icon.png') }}" alt="whatsapp icon" class="preview-whatsapp-icon">
                                                </span>
                                                <div class="card-body p-2">
                                                    <div class="preview">
                                                        <p class="mt-2">Thanks, You have been opted-in of your future communications</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-center">Auto response is disabled</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@include('manage.optin.modals.config-message')
@endsection

@section('meta')
<meta name="class-to-open" content="optout-management">
@endsection