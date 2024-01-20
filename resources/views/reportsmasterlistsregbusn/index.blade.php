@extends('layouts.admin')
@section('page-title')
    {{__('Registered Business')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Registered Business')}}</li>
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
                                <a href="{{ url('export-registered-business') }}" class="btn btn-sm btn-primary" id="btn_download_spreadsheet" title="Download Spreadsheet">
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
                                <th>{{__('Business Name')}}</th>
                                <th>{{__('Type of Business')}}</th>
                                <th>{{__('Registration No.')}}</th>
                                <th>{{__('Registered Business Name in Business Permit')}}</th>
                                <th>{{__('Permit No.')}}</th>
                                <th>{{__('Type of Application')}}</th>
                                <th>{{__('Date Applied')}}</th>
                                <th>{{__('Date Issued')}}</th>
                                <th>{{__("Owner's Name")}}</th>
                                <th>{{__('Business Address')}}</th>
                                <th>{{__('Line of Business')}}</th>
                                <th>{{__('Size of Business (by no. of Employee)')}}</th>
                                <th>{{__('Size of Business (by Capital Investment/Gross Income)')}}</th>
								<th>{{__('Contact No. of Owner')}}</th>
                                <th>{{__('Email Address')}}</th>
                            </tr>
                            </thead>
                           </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('js/ReportsMasterlistsBusnReg.js') }}"></script>
@endsection
