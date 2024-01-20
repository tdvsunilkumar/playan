@extends('layouts.admin')
@section('page-title')
    {{__('BAC Designation')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('BAC Designation')}}</a></li>
@endsection
@section('action-btn')
    <div class="float-end">
         <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
             <a href="#" data-size="xll" data-url="{{ url('/bac-designations/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage BAC Designation')}}" class="btn btn-sm btn-primary modal-dialog-scrollable">
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
                               
								<div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12">
									<div class="btn-box">
                                        {{ Form::label('q', 'Search Here..', ['class' => 'fs-6 fw-bold']) }}
										{{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
									</div>
								</div>
								<div class="col-auto float-end ms-2" style="padding-top: 20px;">
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
                                <th>{{__('No')}}</th>
                                <th>{{__('Employee')}}</th>
                                <th>{{__('Department')}}</th>
                                <th>{{__('Application Name')}}</th>
                                <th>{{__('Position')}}</th>
                                <th>{{__('Last Modified')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div> 
</div>
    <script src="{{ asset('js/GsoBacDesignations.js') }}"></script>
@endsection


