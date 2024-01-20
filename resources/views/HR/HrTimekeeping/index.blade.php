@extends('layouts.admin')
@section('page-title')
    {{__('Process Timekeeping')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Process Timekeeping')}}</li>
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
    <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class="mt-2" id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                    
                            <div class="d-flex align-items-center justify-content-end" >
                                
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                    <div class="btn-box">
                                        {{ Form::label('cut_off_period', 'Cut off Period', ['class' => 'fs-6 fw-bold']) }}
                                        {{ Form::select('cut_off_period',$cut_off_period,$current_cutoff->id, array('class' => 'form-control','id'=>'cut_off_period')) }}                                
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="btn-box">
                                        {{ Form::checkbox($name = 'hrtk_is_processed', $value = '1', $checked = "", $attributes = array(
                                                'id' => 'hrtk_is_processed',
                                                'class' => 'form-check-input hrtk_is_processed'
                                            )) }}
                                        {{ Form::label('cut_off_period', 'Processed', ['class' => 'fs-6 fw-bold']) }}
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                    <div class="btn-box">
                                        {{ Form::label('hrtk_department_id', 'Department', ['class' => 'fs-6 fw-bold']) }}
                                        {{ Form::select('hrtk_department_id',$department,'', array('class' => 'form-control','id'=>'hrtk_department_id')) }}                                
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                    <div class="btn-box">
                                        {{ Form::label('hrtk_division_id', 'Division', ['class' => 'fs-6 fw-bold']) }}
                                        {{ Form::select('hrtk_division_id',$division,'', array('class' => 'form-control','id'=>'hrtk_division_id')) }}                                
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                    <div class="btn-box">
                                        {{ Form::label('hrtk_emp_id', 'Employee', ['class' => 'fs-6 fw-bold']) }}
                                        {{ Form::select('hrtk_emp_id',$employee,'', array('class' => 'form-control','id'=>'hrtk_emp_id')) }}                                
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
                                <th>{{__('Total Hours')}}</th>
                                <th>{{__('Total AUT')}}</th>
                                <th>{{__('Total Overtime')}}</th>
                                <th>{{__('Total Leave')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                          
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <!-- <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal"> -->
                        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <input type="button" name="submit" value="Process" class="btn btn-primary" id="process" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/HR/HrTimekeeping.js') }}"></script>
@endsection


