@extends('layouts.admin')
@section('page-title')
    {{__('My Timecard')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Timecard')}}</li>
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
                        <div class="d-flex align-items-center justify-content-end" >
                            
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
                                <a href="#" class="btn btn-sm btn-primary" id="btn_search">
                                    <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                </a>
                                <!-- <a href="#" class="btn btn-sm btn-danger" id="btn_clear">
                                    <span class="btn-inner--icon"><i class="ti-trash "></i></span>
                                </a> -->
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
                        <table class="table" id="Jq_datatablelist" data-url='hr-timecard/getList/{{Auth::user()->hr_employee->id}}'>
                            <thead>
                            <tr>
                            <th>{{__('SR No')}}</th>
                                <th>{{__('User Id')}}</th>
                                
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
    <script src="{{ asset('js/HR/emp_timecard.js') }}"></script>
@endsection


