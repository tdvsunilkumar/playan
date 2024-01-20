@extends('layouts.admin')

@section('page-title')
    {{__('SMS Notifications (New SMS)')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('SMS Notifications') }}</li>
    <li class="breadcrumb-item">{{ __('New (SMS)') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="javascript:;" data-size="lg" class="btn btn-sm btn-primary send-btn p-2 ps-3 pe-3 ms-2">
            <i class="la la-send"></i> Send Message
        </a>
    </div>
    <div class="float-end">
        <a href="javascript:;" data-size="lg" class="btn btn-sm btn-blue send-later-btn p-2 ps-3 pe-3">
            <i class="la la-send"></i> Send Later
        </a>
    </div>
@endsection

@section('content')
    {{ Form::open(array('url' => 'components/sms-notifications/send', 'class'=>'formDtls needs-validation', 'name' => 'smsForm', 'method' => 'POST')) }}
    @csrf
    <div class="row">
        <div class="col-xl-8">
            <div id="voucher-card" class="card table-card">
                <div class="card-header">
                    <h5 class="w-100">SMS Notifications</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group mb-0">
                                {{ Form::label('messages', 'Send New Message', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::textarea($name = 'messages', $value = '', 
                                    $attributes = array(
                                        'id' => 'messages',
                                        'class' => 'form-control form-control-solid',
                                        'rows' => 5
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <span class="m-form__help m--font-metal text-length">
                                480
                            </span>
                        </div>
                        <div class="col-md-6 text-end">
                            <span class="m-form__help m--font-metal text-set">
                                1/3
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div id="voucher-card" class="card table-card">
                <div class="card-header">
                    <h5 class="w-100">Receipients</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs" role="tablist">                            
                                <li class="nav-item">
                                    <a class="nav-link active" id="list-users" data-bs-toggle="list" href="#users" role="tab" aria-controls="users">Users</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="list-employees" data-bs-toggle="list" href="#employees" role="tab" aria-controls="employees">Employees</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="list-taxpayers" data-bs-toggle="list" href="#taxpayers" role="tab" aria-controls="taxpayers">Taxpayers</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="list-citizens" data-bs-toggle="list" href="#citizens" role="tab" aria-controls="citizens">Citizens</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active show active" id="users" role="tabpanel">
                                    <div class="form-group m-form__group">
                                        <div class="input-group mt-3 mb-3">
                                            <input type="text" name="search-user" class="form-control" placeholder="search username's / user's number here" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                            <button class="btn btn-primary btn-search-user" type="button">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="m-b-10">
                                    [ <a href="javascript:;" value="user" class="select-all">SELECT ALL</a> ] [ <a href="javascript:;" value="user" class="deselect-all">DESELECT ALL</a> ]
                                    </div>
                                    <div class="m-scrollable" data-scrollbar-shown="true" data-scrollable="true" data-max-height="200">
                                        <div class="m-checkbox-list users-list">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="employees" role="tabpanel">
                                    <div class="form-group m-form__group">
                                        <div class="input-group mt-3 mb-3">
                                            <input type="text" name="search-employee" class="form-control" placeholder="search employee's name / employee's number here" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                            <button class="btn btn-primary btn-search-employee" type="button">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="m-b-10">
                                    [ <a href="javascript:;" value="employee" class="select-all">SELECT ALL</a> ] [ <a href="javascript:;" value="employee" class="deselect-all">DESELECT ALL</a> ]
                                    </div>
                                    <div class="m-scrollable" data-scrollbar-shown="true" data-scrollable="true" data-max-height="200">
                                        <div class="m-checkbox-list employees-list">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="taxpayers" role="tabpanel">
                                    <div class="form-group m-form__group">
                                        <div class="input-group mt-3 mb-3">
                                            <input type="text" name="search-taxpayer" class="form-control" placeholder="search taxpayer's name / taxpayer's number here" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                            <button class="btn btn-primary btn-search-taxpayer" type="button">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="m-b-10">
                                    [ <a href="javascript:;" value="taxpayer" class="select-all">SELECT ALL</a> ] [ <a href="javascript:;" value="taxpayer" class="deselect-all">DESELECT ALL</a> ]
                                    </div>
                                    <div class="m-scrollable" data-scrollbar-shown="true" data-scrollable="true" data-max-height="200">
                                        <div class="m-checkbox-list taxpayers-list">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="citizens" role="tabpanel">
                                    <div class="form-group m-form__group">
                                        <div class="input-group mt-3 mb-3">
                                            <input type="text" name="search-citizen" class="form-control" placeholder="search citizen's name / number here" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                            <button class="btn btn-primary btn-search-citizen" type="button">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="m-b-10">
                                    [ <a href="javascript:;" value="citizen" class="select-all">SELECT ALL</a> ] [ <a href="javascript:;" value="citizen" class="deselect-all">DESELECT ALL</a> ]
                                    </div>
                                    <div class="m-scrollable" data-scrollbar-shown="true" data-scrollable="true" data-max-height="200">
                                        <div class="m-checkbox-list citizens-list">
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
    {{ Form::close() }}
    @include('components.sms-notifications.send-later')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
<style>
    .input-group input {
        z-index: 1 !important;
        box-shadow: none !important;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/forms/sms-new.js?v='.filemtime(getcwd().'/js/forms/sms-new.js').'') }}"></script>
@endpush