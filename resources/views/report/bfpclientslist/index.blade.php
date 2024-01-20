@extends('layouts.admin')
@section('page-title')
    {{ $title }}: Client's List
@endsection

@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{ $title }}: Client's List</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="ti-filter"></i></a>
       <a href="{{ url('export-bfp-client-lists') }}" class="btn btn-sm btn-primary" title="Bfp Client List" id="btn_download_spreadsheet">
            <i class="ti-files"></i>
        </a>
    </div>
@endsection
@section('content')
<link href="{{ asset('assets/js/yearpicker.css')}}" rel="stylesheet">
<style type="text/css">
    .yearpicker-container {
    position: fixed;
    color: var(--text-color);
    width: 280px;
    border: 1px solid var(--border-color);
    border-radius: 3px;
    font-size: 1rem;
    box-shadow: 1px 1px 8px 0px rgba(0, 0, 0, 0.2);
    background-color: var(--background-color);
    z-index: 10;
    margin-top: 0.2rem;
}

</style>
{{ Form::hidden('bbendo_id',$bbendo_id, array('id' => 'bbendo_id')) }}
{{ Form::hidden('pageTitle',$title, array('id' => 'pageTitle')) }}
    <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card" >
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-end" >
                            <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                                <div class="btn-box">
                                    <?php
                                        $currentYear = date('Y');
                                    ?>
                                   <b> Year</b>{{ Form::text('search_year', $currentYear, array('class' => 'yearpicker form-control','id'=>'search_year')) }}
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1 pdr-20">
                                 <div class="btn-box">
                                @php
                                    $fromDate = date('Y-m-d', strtotime(date('Y-m-d').' -1 months'));
                                @endphp
                                {{Form::label('fromdate',__('From date'), ['class' => 'fs-6 fw-bold']) }}
                                      {{ Form::date('fromdate',$fromDate, array('class' => 'form-control','placeholder'=>'From','id'=>'fromdate')) }}
                              </div>
                           </div>
                           <div class="col-lg-1 col-md-1 col-sm-1 pdr-20">
                               <div class="btn-box">
                                {{Form::label('todate',__('To date'), ['class' => 'fs-6 fw-bold']) }}
                                      {{ Form::date('todate',date("Y-m-d"), array('class' => 'form-control','placeholder'=>'To date','id'=>'todate')) }}
                               </div>
                           </div>

                           
						   <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                                <div class="btn-box" >
                                {{ Form::label('barangay', 'Barangay', ['class' => 'fs-6 fw-bold']) }}
                                {{
                                    Form::select('barangayid', [], $value = '', ['id' => 'barangayid', 'class' => 'form-control','data-placeholder' => 'Please select'])
                                }}                                 
                                </div>
                            </div>

                           <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                                <div class="btn-box" >
                                {{ Form::label('Application', 'Occupancy Type', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::select('application_status',$arrOcupancy,'', array('class' => 'form-control','placeholder'=>'Please Select','id'=>'application_status')) }}
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('Search', 'Status', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::select('status',array(''=>'Please Select','1'=>'New','2' =>'Renew'),'', array('class' => 'form-control','id'=>'status')) }}
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                                <div class="btn-box">
                                    {{ Form::label('Search', 'Search Here...', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
                                </div>
                            </div>
                             <div class="col-auto float-end ms-1" style="padding-top: 19px;">
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
                                <th>{{__('BINBAN')}}</th>
                                <th>{{__('MONTH')}}</th>
                                <th>{{__('IO NUMBER')}}</th>
								<th>{{__('ESTABLISMENT NAME')}}</th>
                                <th>{{__('FIRST NAME')}}</th>
                                <th>{{__('LAST NAME')}}</th>
                                <th>{{__('FSIC NUMBER')}}</th>
                                <th>{{__('DATE INSPECTED')}}</th>
                                <th>{{__('STATUS')}}</th>
                                <th>{{__('DATE ISSUED')}}</th>
                                <th>{{__('VALIDITY')}}</th>
                                <th>{{__('LOCATION')}}</th>
                                <th>{{__('CONTACT NUMBER')}}</th>
                                <th>{{__('OCCUPANCY')}}</th>
                                <th>{{__('FSI')}}</th>
                                <th>{{__('AMOUNT')}}</th>
                                <th>{{__('O.R. NUMBER')}}</th>
                                <th>{{__('DATE PAID')}}</th>
                                <th>{{__('REMARKS')}}</th>
                                <th>{{__('PRINTED')}}</th>
                            </tr>
                            </thead>
                           </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(session()->has('BFP_PRINT_CASHIER_ID'))
         <iframe id="openPrintDialog" src="@php echo url('/fire-protection/cashiering/printReceipt'.Session::get('BFP_PRINT_CASHIER_ID')) @endphp" class="hide" width="100%" height="800px"></iframe>
        @php  Session::forget('BFP_PRINT_CASHIER_ID'); @endphp
    @endif

    <script src="{{ asset('assets/js/yearpicker.js') }}"></script>
    <script src="{{ asset('js/report/bfpclientlist.js') }}?rand={{ rand(000,999) }}"></script>
@endsection
