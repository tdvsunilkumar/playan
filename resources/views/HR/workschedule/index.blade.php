@extends('layouts.admin')
@section('page-title')
    {{__('Employee Work Schedule')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Employee Work Schedule')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
             <a href="#" data-size="xll" data-url="{{ url('/hr-work-schedule/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Work Schedule')}}" class="btn btn-sm btn-primary modal-dialog-scrollable">
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
                        <div class="row d-flex align-items-center justify-content-end">
                            
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    {{Form::label('select_date',__('Select Date'),array('class'=>'form-label')) }}
                                    <div class="form-icon-user">
                                    {{ Form::date('select_date',
                                    Carbon\Carbon::today()->toDateString(), 
                                    array(
                                        'class' => 'form-control',
                                        'id'=>'select_date',
                                        'required'=>'required'
                                        )
                                    ) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    {{Form::label('select_department',__('Select Department'),array('class'=>'form-label')) }}
                                    <div class="form-icon-user">
                                    
                                    {{ Form::select('select_department',
                                        $departments,
                                        '', 
                                        array('class' => 'form-control','id'=>'select_department')
                                    ) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    {{Form::label('search',__('Search'),array('class'=>'form-label')) }}
                                    <div class="form-icon-user">
                                        {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2">
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
                                <th>{{__('SR No')}}</th>
                                <th>{{__('Employee Name')}}</th>
                                <th>{{__('Schedule')}}</th>
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
    <script src="{{ asset('js/HR/workschedule.js?v='.filemtime(getcwd().'/js/HR/add_workschedule.js')) }}"></script>
@endsection


