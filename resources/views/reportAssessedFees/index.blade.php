@extends('layouts.admin')
@section('page-title')
    {{__('Assessed Fees')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Assessed Fees') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
    </div>
@endsection
@section('content')
    <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('from_date', 'From Date', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::date('from_date', $from_date, array('class' => 'form-control','id'=>'from_date')) }}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('to_date', 'To Date', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::date('to_date', $to_date, array('class' => 'form-control','id'=>'to_date')) }}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
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
                                <a class="btn btn-sm btn-primary" id="btn_download_spreadsheet" title="Download Spreadsheet">
                                        <span class="btn-inner-icon"><i class="ti-files"> </i></span>
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
                                <th>{{__('Permit No.')}}</th>
                                <th>{{__('BUSINESS ID-No.')}}</th>
                                <th>{{__('Name of Proprietor')}}</th>
                                <th>{{__('Surname')}}</th>
                                <th>{{__('First Name')}}</th>
                                <th>{{__('Name of Establishment')}}</th>
                                <th>{{__('Business Address')}}</th>
                                <th>{{__('Type of Application')}}</th>
                                <th>{{__('Line of Business')}}</th>
                                <th>{{__('Contact No.')}}</th>
                                <th>{{__('Type of Ownership')}}</th>
                                <th>{{__('No. of Employees')}}</th>
                                <th>{{__('Capital Investment')}}</th>
                                <th>{{__('Gross Essential')}}</th>
                                <th>{{__('Gross Non-Essential')}}</th>
                                <th>{{__('Business Tax Non-Essential')}}</th>
                                <th>{{__('Business Tax-Essential')}}</th>
                                <th>{{__('Sanitary Inspection Fee')}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="{{ asset('js/reportAssessedFees.js') }}"></script>
@endpush
