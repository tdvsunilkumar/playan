@extends('layouts.admin')

@section('page-title')
    {{__('Tracking')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Components') }}</li>
    <li class="breadcrumb-item">{{ __('SMS Notifcation') }}</li>
    <li class="breadcrumb-item">{{ __('Tracking') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card noflow">
                <div class="card-header">
                    <h5 class="w-100"><i class="la la-filter"></i> Filter</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('date_from', 'Date From', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'date_from', $value = '', 
                                    $attributes = array(
                                        'id' => 'date_from',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('date_to', 'Date To', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'date_to', $value = '', 
                                    $attributes = array(
                                        'id' => 'date_to',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('status', 'Status', ['class' => '']) }}
                                {{
                                    Form::select('status', $statuses, $value = 'all', ['id' => 'status', 'class' => 'form-control select3'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-sm-3 col-sm-6 text-center">
                            <button type="button" class="btn search-btn btn-info w-50 mt-2"><i class="la la-search align-middle"></i> SEARCH</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card table-card">
                <div class="card-header">
                    <div class="row">
                        <div class="col text-center">
                            <h4 id="sms-successful" class="fs-5 m-0"><i class="la la-check"></i> Success: <span class="text-secondary">0</span></h5>
                        </div>
                        <div class="col text-center">
                            <h4 id="sms-failed" class="fs-5 m-0"><i class="la la-close"></i> Failed: <span class="text-secondary">0</span></h5>
                        </div>
                        <div class="col text-center">
                            <h4 id="sms-delivered" class="fs-5 m-0"><i class="la la-envelope"></i> Delivered: <span class="text-secondary">0</span></h5>
                        </div>
                        <div class="col text-center">
                            <h4 id="sms-undelivered" class="fs-5 m-0"><i class="la la-exclamation"></i> Undelivered: <span class="text-secondary">0</span></h5>
                        </div>
                        <div class="col text-center">
                            <h4 id="sms-expired" class="fs-5 m-0"><i class="la la-exclamation-triangle"></i> Expired: <span class="text-secondary">0</span></h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="trackingTable" class="display dataTable table w-100 table-striped mb-0" aria-describedby="trackingInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('TRACKING ID') }}</th>
                                                <th class="sliced">{{ __('Messages') }}</th>
                                                <th>{{ __('No Of Contacts') }}</th>
                                                <th>{{ __('SENT AT') }}</th>
                                                <th>{{ __('Successful') }}</th>
                                                <th>{{ __('Failed') }}</th>
                                                <th>{{ __('Delivered') }}</th>
                                                <th>{{ __('Undelivered') }}</th>
                                                <th>{{ __('Expired') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
<style>
    .search-btn:hover {
        color: #fff;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/sms-tracking.js?v='.filemtime(getcwd().'/js/datatables/sms-tracking.js').'') }}"></script>
@endpush