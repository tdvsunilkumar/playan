@extends('layouts.admin')
@section('page-title')
    {{__('Employee Biometric Record')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Employee Biometric Record')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
         <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
             <!-- <a href="#" data-size="xll" data-url="{{ url('/hr-appointment/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Appointment')}}" class="btn btn-sm btn-primary modal-dialog-scrollable">
            <i class="ti-plus"></i>
        </a> -->
    </div>
@endsection

@section('content')
<div class="row hide" id="this_is_filter">
    <div class="col-sm-12">
        <div class="mt-2" id="multiCollapseExample1">
            <div class="card">
                <div class="card-body">
                   
                        <div class="d-flex align-items-center justify-content-end" >
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20" id="hrbr_department_id_div">
                                <div class="btn-box">
                                    {{ Form::label('hrbr_department_id', 'Department', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::select('hrbr_department_id',$department,'', array('class' => 'form-control','id'=>'hrbr_department_id')) }}                                
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                    {{ Form::label('hrbr_division_id', 'Division', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::select('hrbr_division_id',$division,'', array('class' => 'form-control','id'=>'hrbr_division_id')) }}                                
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                    {{ Form::label('from_date', 'From Date', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::date('from_date', '', array('class' => 'form-control','id'=>'from_date')) }}                                
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('to_date', 'To Date', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::date('to_date', '', array('class' => 'form-control','id'=>'to_date')) }}                                
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2"><br>
                                <a href="#" class="btn btn-sm btn-primary" id="btn_search">
                                    <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger" id="btn_clear">
                                    <span class="btn-inner--icon"><i class="ti-trash"></i></span>
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
                                <th>{{__('SR No')}}</th>
                                <th>{{__('User Id')}}</th>
                                <th>{{__('Employee Name')}}</th>
                                <th>{{__('Department')}}</th>
                                <th>{{__('Division')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Time')}}</th>
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
    <script src="{{ asset('js/HR/HrBiometricsRecord.js') }}"></script>
@endsection


