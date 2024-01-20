@extends('layouts.admin')
@section('page-title')
    {{__('User Logs')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('User Logs')}}</li>
@endsection
@section('action-btn')
<div class="float-end">
	<a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<i class="ti-filter"></i></a>
</div>
@endsection
@section('content')
<div class="row hide" id="this_is_filter" style="display: block;">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-end">
							<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 pdr-20">
                                <div class="btn-box" id="multiCollapseExample1">
                                    {{ Form::label('department', 'Department', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::select('department',$arrDepartment, '',array('class' => 'form-control','id'=>'department')) }}
                                </div>
                            </div>
							<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 pdr-20">
                                <div class="btn-box" id="multiCollapseExample2">
                                    {{ Form::label('log_type', 'Log type', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::select('log_type',$arrLogtype,'', array('class' => 'form-control','id'=>'log_type')) }}
                                </div>
                            </div>
							<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('from_date', 'From Date', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::date('from_date', $from_date, array('class' => 'form-control','id'=>'from_date')) }}
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('to_date', 'To Date', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::date('to_date', $to_date, array('class' => 'form-control','id'=>'to_date')) }}
                                </div>
                            </div>
							<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 pdr-20">
                                <div class="btn-box">
                                    {{ Form::label('Search', 'Search Here...', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2">
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
                                <th>{{__('Employee Name')}}</th>
								<th>{{__('Email Address')}}</th>
                                <th>{{__('Department')}}</th>
                                <th>{{__('Log Description')}}</th>
								<th>{{__('Location')}}</th>
                            </tr>
                            </thead>
                           </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('js/userloginlogs.js') }}"></script>
@endsection
