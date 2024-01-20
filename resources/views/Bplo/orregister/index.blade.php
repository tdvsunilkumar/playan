@extends('layouts.admin')
@section('page-title')
    {{__('Official Receipts Register')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('OR Register')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">

            <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i></a>
            <a href="#" data-size="xll" data-url="{{ url('/ctoorregister/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage OR Register')}}" class="btn btn-sm btn-primary">
            <i class="ti-plus"></i>
        </a>
        
    </div>
@endsection

@section('content')
<style type="text/css">
    .swal2-html-container {
    z-index: 1;
    justify-content: center;
    margin: 1em 1.6em 0.3em;
    padding: 0;
    overflow: auto;
    color: inherit;
    font-size: 1.125em;
    font-weight: 400;
    line-height: normal;
    text-align: center;
    word-wrap: break-word;
    word-break: break-word;
    text-align: left;
    padding-left: 74px;
}
</style>
<div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-end" >
                            
                            <div class="col-lg-4 col-md-4 col-sm-4 pdr-20">
                               <div class="form-group">
                                {{Form::label('todate',__('Type'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                    {{ Form::select('type',$Ortypes,'', array('class' => 'form-control','id'=>'type')) }}
                                </div>
                              </div>
                           </div>
                           <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                              <div class="form-group">
                                {{Form::label('todate',__('Status'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                    {{ Form::select('status', ['3' => 'Select Status', '1' => 'Active', '2' => 'Completed', '0' => 'Cancelled'], '1', ['class' => 'form-control spp_type', 'id' => 'status']) }}
                                </div>
                              </div>
                           </div>
                           <div class="col-lg-3 col-md-3 col-sm-3 pdr-20">
                              <div class="form-group">
                                {{Form::label('Search',__('Search Here...'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                     {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
                                </div>
                              </div>
                           </div>

                            <div class="col-auto float-end ms-2" style="padding-top: 7px;">
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
                                <th>{{__('Or Type')}}</th>
                                <th>{{__('Or Series From')}}</th>
                                <th>{{__('Or Series To')}}</th>
                                <th>{{__('Tag no')}}</th>
                                <th>{{__('Registered By')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('STATUS')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                           </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script src="{{ asset('js/Bplo/orregister.js') }}?rand={{ rand(000,999) }}"></script>
@endsection
