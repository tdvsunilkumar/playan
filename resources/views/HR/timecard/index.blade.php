@extends('layouts.admin')
@section('page-title')
    {{__('Employee Time Record')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Employee Time Record')}}</li>
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
                        {{ Form::open(array('url' => 'hr-timecard/refresh','class'=>'formDtls needs-validation', 'name' => 'medicalUtilizationForm', 'method' => 'POST')) }}
                            @csrf
                            <div class="d-flex align-items-center justify-content-start" >
                                <div class="col-lg-3 col-md-3 col-sm-3 pdr-20">
                                    <div class="form-group">
                                        {{Form::label('todate',__('Department'),array('class'=>'form-label')) }}
                                        <div class="form-icon-user">
                                            {{ Form::select('departmentnew',$arrDepaertments,'', array('class' => 'form-control ','id'=>'departmentnew','required'=>'required')) }}
                                            
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-lg-3 col-md-3 col-sm-3 pdr-20">
                                    <div class="form-group">
                                        {{Form::label('division',__('Division'),array('class'=>'form-label')) }}
                                        <div class="form-icon-user">
                                            {{ Form::select('division',$arrDivisions,'', array('class' => 'form-control ','id'=>'division','required'=>'required')) }}
                                        </div>
                                    </div>
                                </div> -->
                                <div class="col-md-3">
                                        <div class="form-group">
                                            {{ Form::label('hr_employeesid', __('Employee'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('reason') }}</span>
                                                <div class="form-icon-user">
                                                    {{ 
                                                        Form::select('hr_employeesid', 
                                                            [],
                                                            '', 
                                                            $attributes = array(
                                                            'id' => 'hr_employeesid',
                                                            'data-url' => 'hr-appointment/getEmployees',
                                                            'data-placeholder' => 'Search Employee',
                                                            'data-contain' => 'multiCollapseExample1',
                                                            'class' => 'form-control ajax-select',
                                                        )) 
                                                    }}
                                                </div>
                                            <span class="validate-err" id="err_hr_employeesid"></span>
                                        </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-start" >
                                <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                                    <div class="form-group">
                                        @php
                                            $fromDate = date('Y-m-d', strtotime(date('Y-m-d').' -1 months'));
                                        @endphp
                                        {{Form::label('fromdate',__('From date'),array('class'=>'form-label')) }}
                                        <div class="form-icon-user">
                                            {{ Form::date('fromdate',$fromDate, array('class' => 'form-control','placeholder'=>'From','id'=>'fromdate')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                                    <div class="form-group">
                                        {{Form::label('todate',__('To date'),array('class'=>'form-label')) }}
                                        <div class="form-icon-user">
                                            {{ Form::date('todate',date("Y-m-d"), array('class' => 'form-control','placeholder'=>'To date','id'=>'todate')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto float-end ms-2" style="padding-top: 7px;">
                                    <a href="#" class="btn btn-sm btn-primary mr-3" id="btn_search">
                                        <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                    </a>
                                    <button type="su" id="reload-btn" class="btn align-middle justify-content-center ti-reload btn-info"> Refresh</button>
                                    <!-- <a href="#" class="btn btn-sm btn-danger" id="btn_clear">
                                        <span class="btn-inner--icon"><i class="ti-trash "></i></span>
                                    </a> -->
                                </div>
                            </div>
                        {{ Form::close() }}
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
                        <table class="table" id="Jq_datatablelist" data-url="hr-timecard/getList">
                            <thead>
                            <tr>
                                <th>{{__('SR No')}}</th>
                                <th>{{__('User Id')}}</th>
                                <th>{{__('Employee Name')}}</th>
                                <th>{{__('Department')}}</th>
                                <th>{{__('Division')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Schedule')}}</th>
                                <th>{{__('In time')}}</th>
                                <th>{{__('Out Time')}}</th>
                                <th>{{__('Late')}}</th>
                                <th>{{__('Undertime')}}</th>
                                <!-- <th>{{__('Overtime (calculation)')}}</th> -->
                                <th>{{__('Holiday')}}</th>
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
    <script src="{{ asset('js/HR/timecard.js') }}"></script>
<script src="{{ asset('js/SocialWelfare/select-ajax.js?v='.filemtime(getcwd().'/js/SocialWelfare/select-ajax.js').'') }}"></script>
@endsection


