@extends('layouts.admin')
@if(session()->has('REMOTE_SYNC_APPFORMIDENGINEERING'))
  {{ Form::hidden('currentupdatedappid',Session::get('REMOTE_SYNC_APPFORMIDENGINEERING'), array('id' => 'currentupdatedappid')) }}
@endif
  @php  Session::forget('REMOTE_SYNC_APPFORMIDENGINEERING'); @endphp
@section('page-title')
    {{__('Engineering Job Requests')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
   <div>
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Engineering Job Requests')}}</li></div>
@endsection
@section('action-btn')
    <div class="float-end">
         <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
             <a href="#" data-size="xxll" data-url="{{ url('/engjobrequest/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Engineering Job Request')}}" class="btn btn-sm btn-primary modal-dialog-scrollable">
            <i class="ti-plus"></i>
        </a>
    </div>
@endsection

@section('content')
   <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="#" accept-charset="UTF-8" id="product_service">
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20" id="barangaydiv">
                                <div class="btn-box">
                                {{ Form::label('barangay', 'Barangay', ['class' => 'fs-6 fw-bold']) }}
                                {{
                                    Form::select('flt_busn_office_barangay', $barangay, $value = '', ['id' => 'flt_busn_office_barangay', 'class' => 'form-control'])
                                }}                                 
                                </div>
                            </div>
                             <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('service', 'Services', ['class' => 'fs-6 fw-bold']) }}
                                {{
                                    Form::select('service', $services, $value = '', ['id' => 'service', 'class' => 'form-control','Placeholder'=>'Please Select'])
                                }}                                 
                                </div>
                            </div>
                            <div class="col-xl-1 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                @php
                                    $fromDate = date('Y-m-d', strtotime(date('Y-m-d').' -1 months'));
                                @endphp
                                {{ Form::label('from_date', 'From Date', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::date('from_date', $from_date, array('class' => 'form-control','id'=>'from_date')) }}
                                </div>
                            </div>
                            <div class="col-xl-1 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('to_date', 'To Date', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::date('to_date', $to_date, array('class' => 'form-control','id'=>'to_date')) }}
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('method', 'Method', ['class' => 'fs-6 fw-bold']) }}
                                {{
                                    Form::select('method', $methods, $value = '', ['id' => 'method', 'class' => 'form-control'])
                                }}                                 
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('status', 'Status', ['class' => 'fs-6 fw-bold']) }}
                                {{
                                    Form::select('flt_Status', [''=>'Select Status','1' => 'Active','0' => 'Inactive'], $value = '', ['id' => 'flt_Status', 'class' => 'form-control'])
                                }}                                 
                                </div>
                            </div>
                            <div class="col-xl-1 col-lg-3 col-md-6 col-sm-12 col-12" style="padding-right:10px;">
                                <div class="btn-box">
                                    {{ Form::label('search', 'Search Here', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-1" style="padding-top:20px;">
                                <a href="#" class="btn btn-sm btn-primary" id="btn_search">
                                    <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger" id="btn_clear">
                                    <span class="btn-inner--icon"><i class="ti-trash"></i></span>
                                </a>
                            </div>

                        </div>
                        </form>
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
                                <th>{{__('Job Req. no.')}}</th>
                                <th>{{__('Applicant Name')}}</th>
                                <th>{{__('Barangay')}}</th>
                                <th>{{__('Services')}}</th>
                                <th>{{__('Generated')}}</th>
                                <th>{{__('App No.')}}</th>
                                <th>{{__('Permit No.')}}</th>
                                <th>{{__('TOP No.')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('O.R. No.')}}</th>
                                <th>{{__('O.R. date')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Method')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                          
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <script src="{{ asset('js/Engneering/engjobrequestno.js') }}"></script>
@endsection
