@extends('layouts.admin')

@section('page-title')
    {{__('Application')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Business Permit') }}</li>
    <li class="breadcrumb-item">{{ __('Application') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="lg" data-url="{{ url('/business-permit/application/bulkUpload') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Bulk Upload')}}" >
            <span class="btn-inner--icon"><i class="ti-import"></i>&nbsp;Bulk Upload</span>
        </a>
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip"  class="btn btn-sm btn-primary add-btn" title="{{__('Manage Application')}}">
            <i class="ti-plus"></i>
        </a>
    </div>





@endsection
 
@section('content')
    <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body" id="flt_parent_busn_office_barangay_id">
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('barangay', 'Barangay', ['class' => 'fs-6 fw-bold']) }}
                                {{
                                    Form::select('flt_busn_office_barangay', $barangay, $value = '', ['id' => 'flt_busn_office_barangay', 'class' => 'form-control select3', 'data-placeholder' => 'Please select'])
                                }}                                 
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                @php
                                    $fromDate = date('Y-m-d', strtotime(date('Y-m-d').' -1 months'));
                                @endphp
                                {{ Form::label('from_date', 'From Date', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::date('from_date', $fromDate, array('class' => 'form-control','id'=>'from_date')) }}
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('to_date', 'To Date', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::date('to_date', $to_date, array('class' => 'form-control','id'=>'to_date')) }}
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('flt_status', 'Status', ['class' => 'fs-6 fw-bold']) }}
                                {{
                                    Form::select('flt_status', $status, $value = '', ['id' => 'flt_status', 'class' => 'form-control', 'data-placeholder' => 'Please select'])
                                }}                                 
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('Search', 'Search Here...', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2"><br>
                                <a href="#" class="btn btn-sm btn-primary" id="btn_search">
                                    <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger" id="btn_clear">
                                    <span class="btn-inner--icon"><i class="ti-trash "></i></span>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="Jq_datatablelist">
                            <thead>
                            <tr>
                                <th>{{__('No.')}}</th>
                                <th>{{__('BUSINESS ID-No.')}}</th>
                                <th>{{__('Taxpayer')}}</th>
                                <th>{{__('Business Name')}}</th>
                                <th>{{__('Barangay')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Last payment')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Method')}}</th>
                                <th>{{__('Duration')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="indexToast" data-bs-autohide="true" class="toast hide bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body">
            Hello, world! This is a toast message.
            </div>
        </div>
    </div>
    @include('BploBusiness.create')

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('js/custom_new.js') }}"></script>
<script src="{{ asset('js/BploBusiness.js') }}"></script>
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/bplo-business.js?v='.filemtime(getcwd().'/js/datatables/bplo-business.js').'') }}"></script>
<script src="{{ asset('js/forms/bplo-business.js?v='.filemtime(getcwd().'/js/forms/bplo-business.js').'') }}"></script>
@endpush
