@extends('layouts.admin')
@section('page-title')
    {{__('PSIC Group')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('PSIC Group')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
    <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="ti-filter"></i>
    </a>   
    <a href="#" data-size="lg" data-url="{{ url('/psicgroup/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage PSIC Group')}}" class="btn btn-sm btn-primary">
            <i class="ti-plus"></i>
        </a>
    </div>
@endsection


@section('content')
     <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card" >
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-end" >
                           
                           <div class="col-lg-4 col-md-4 col-sm-4 pdr-20">
                                <div class="btn-box" >
                                {{ Form::label('Section', 'Section', ['class' => 'fs-6 fw-bold']) }}
                                {{
                                    Form::select('Section', $arrsection, $value = '', ['id' => 'Section', 'class' => 'form-control ','data-placeholder' => 'Please select'])
                                }}                                 
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 pdr-20">
                                <div class="btn-box" >
                                {{ Form::label('Division', 'Division', ['class' => 'fs-6 fw-bold']) }}
                                {{
                                    Form::select('Division', $arrdivision, $value = '', ['id' => 'Division', 'class' => 'form-control','data-placeholder' => 'Please select'])
                                }}                                 
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 pdr-20">
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
                                <th>{{__('Group Code')}}</th>
                                <th>{{__('Section')}}</th>
                                <th>{{__('Division')}}</th>
                                <th>{{__('Group DESC')}}</th>
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
<script src="{{ asset('js/psicgroup.js') }}?rand={{ rand(000,999) }}"></script>
@endsection