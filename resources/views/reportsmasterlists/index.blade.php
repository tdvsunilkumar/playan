@extends('layouts.admin')
@section('page-title')
    {{__('Business Establishments')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Business Establishments')}}</li>
@endsection
@section('action-btn')
@endsection
@section('content')
<!-- Include jQuery library -->


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
                                <th>{{__('Business Id-No.')}}</th>
                                <th>{{__('Permit No.')}}</th>
                                <th>{{__('Business Name')}}</th>
								<th>{{__('First Name')}}</th>
								<th>{{__('Middle Name')}}</th>
								<th>{{__('Last Name')}}</th>
								<th>{{__('Extension Name')}}</th>
								<th>{{__('Gender')}}</th>
								<th>{{__('Location of Business')}}</th>
								<th>{{__('Address of Owner')}}</th>
								<th>{{__('Application Date')}}</th>
								<th>{{__('Type of Application')}}</th>
								<th>{{__('Capital Investment')}}</th>
								<th>{{__('Gross Sales')}}</th>
								<th>{{__('Mode of Payment')}}</th>
								<th>{{__('Type of Business')}}</th>
								<th>{{__('Surcharge')}}</th>
								<th>{{__('Interest')}}</th>
								<th>{{__('Total Amount Paid')}}</th>
								<th>{{__('O.r. Number')}}</th>
								<th>{{__('O.r. Date')}}</th>
								<th>{{__('TIN')}}</th>
								<th>{{__('Registration No.')}}</th>
								<th>{{__('No. of Male')}}</th>
								<th>{{__('No. of Female')}}</th>
								<th>{{__('Total Employees')}}</th>
								<th>{{__('Contact No.')}}</th>
								<th>{{__('Email Address')}}</th>
								<th>{{__('Nature of Business')}}</th>
								<th>{{__('Remarks')}}</th>
								<th>{{__('Plate No.')}}</th>
								<th>{{__('Application Method')}}</th>
								<th>{{__('Date Issued')}}</th>
								<th>{{__('Business Area')}}</th>
								<th>{{__('Floor Area')}}</th>
                            </tr>
                            </thead>
                           </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('js/ReportsMasterlists.js') }}"></script>
@endsection
