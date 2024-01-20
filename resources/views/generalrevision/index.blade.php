@extends('layouts.admin')
<style type="text/css">
    .datefield{padding-top: 26px;}
    .swal2-container{
        z-index:9999999 !important;
    }
    

</style>

@section('page-title')
    {{__('General Revision')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('General Revision')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        
        <a href="#"  data-url="{{ url('/generalrevision/store') }}" title="{{__('Revision Year')}}" class="btn btn-sm btn-primary addNewProperty" >
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
                        {{ Form::open(array('url' => '','id'=>"rptPropertySerachFilter")) }}
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12 col-12 pdr-20" >
                                <div class="btn-box">
                                    <button type="button" class="btn btn-primary reviseSelectedTds" data-action="revise">Revise</button>
                                </div>
                            </div>
                            
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20" >
                                <div class="btn-box">
                                    <button type="button" class="btn btn-primary reviseSelectedTds" data-action="rollback">Rollback</button>
                                </div>
                            </div>
                            <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12 col-12 pdr-20">
                                    <div class="btn-box">
                                         {{ Form::label('Search', 'From Year', ['class' => 'fs-6 fw-bold','style'=>"color:red;"]) }}
                                        <b>{{ ($oldRevisionYearDetails != null)?$oldRevisionYearDetails->rvy_revision_year.'-'.$oldRevisionYearDetails->rvy_revision_code:'No Revision Found'}}</b>
                                    </div>
                            </div>
                            <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12 col-12 pdr-20">
                                    <div class="btn-box">
                                        {{ Form::label('Search', 'To Year', ['class' => 'fs-6 fw-bold','style'=>"color:green;"]) }}
                                         <b>{{ ($activeRevisionYearDetails != null)?$activeRevisionYearDetails->rvy_revision_year.'-'.$activeRevisionYearDetails->rvy_revision_code:'No Active Revision'}}</b>
                                     <input type="hidden" name="rptPropertySearchByRevisionYear" id="rptPropertySearchByRevisionYear" value="{{($activeRevisionYearDetails != null)?$activeRevisionYearDetails->id:0}}">
                                    </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20">
                                    <div class="btn-box">
                                        {{ Form::label('Search', 'Barangay', ['class' => 'fs-6 fw-bold']) }}
                                        {{ Form::select('barangy_filter',$arrBarangay,session()->get('landSelectedBrgy'), array('class' => 'form-control','id'=>'rptPropertySearchByBarangy')) }}
                                    </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20">
                                    <div class="btn-box">
                                        {{ Form::label('Search', 'Kind', ['class' => 'fs-6 fw-bold']) }}
                                        {{ Form::select('kind_filter',$kinds,session()->get('landSelectedBrgy'), array('class' => 'form-control','id'=>'rptPropertySearchByKind')) }}
                                    </div>
                            </div>
                            <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('Search', 'Search Here...', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'rptPropertySearchByText')) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2"><br>
                                <a href="#" class="btn btn-sm btn-primary" id="btn_search">
                                    <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger" id="btn_clear">
                                    <span class="btn-inner--icon"><i class="ti-trash "></i></span>
                                </a>
                            </div>

                        </div>
                        {{Form::close()}}
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
                        {{ Form::open(array('url' => 'generalrevision/reviseorrollback','id'=>"subitFormForRevision")) }}
                        <input type="hidden" name="actionForSelectedTds">
                        <table class="table" id="Jq_datatablelist">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectALlrecords" name=""></th>
                                    <th>{{__('No.')}}</th>
                                    <th>{{__('TD. No.')}}</th>
                                    <th>{{__('Taxpayers Name')}}</th>
                                    <th>{{__('PIN')}}</th>
                                    <th>{{__('Market Value')}}</th>
                                    <th>{{__("Assessed Value")}}</th>
                                    <th>{{__('Status')}}</th>
                                    
                                    <th>{{__('Action')}}</th>
                                    <!-- <th>{{__('Other')}}</th> -->
                                </tr>
                            </thead>
                        </table>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <div class="modal" id="commonUpDateCodeIntermediateModal1" data-backdrop="static" style="z-index:9999999 !important;">
        <div class="modal-dialog " >
            <div class="modal-content" >
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="body">
            </div>
            </div>
        </div>
    </div> 
    <div class="modal" id="commonUpDateCodeIntermediateModal2" data-backdrop="static" style="z-index:9999999 !important;">
        <div class="modal-dialog " >
            <div class="modal-content" >
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="body">
            </div>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('js/rptGeneralRevisionNew.js') }}?rand={{rand(0,999)}}"></script>
@endsection

