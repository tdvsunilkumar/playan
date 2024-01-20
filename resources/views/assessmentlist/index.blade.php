@extends('layouts.admin')
<style type="text/css">
    .datefield{padding-top: 26px;}
    .swal2-container{
        z-index:9999999 !important;
    }
    .select3-container{
        /*z-index: 9999999 !important;*/
    }

</style>

@section('page-title')
    {{__('Assessment List [Tax Declaration]')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Assessment Lists')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        
        <!-- <a href="#"  data-url="{{ route('billing.showform') }}?cb_billing_mode=0" title="{{__('New Single Property Billing')}}" class="btn btn-sm btn-primary addNewProperty" {{ (session()->get('billingSelectedBrgy') == '')?'hidden':'' }}>
            <i class="ti-plus"></i>
        </a> -->
    </div>
@endsection

@section('content')
<div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('url' => '','id'=>"rptPropertySerachFilter")) }}
                        <div class="d-flex align-items-center  justify-content-end">
                          <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12 col-12">
                                
                         </div> 
                         <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20" id="barangaydiv">
                                <div class="form-group">
                                    {{Form::label('barangay',__("Status"),['class'=>'form-label'])}}
                                    <div class="btn-box">
                                    {{ Form::select('status_filtter',['1'=>'Active','0'=>'Cancelled'],1, array('class' => 'form-control','id'=>'rptPropertySearchBystatus')) }}
                                    </div>
                                </div>
                            </div>  
                        <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20" id="barangaydiv">
                                <div class="form-group">
                                    {{Form::label('barangay',__("Barangay"),['class'=>'form-label'])}}
                                    <div class="btn-box">
                                    {{ Form::select('barangy_filter',$arrBarangay,session()->get('billingSelectedBrgy'), array('class' => 'form-control','id'=>'rptPropertySearchByBarangy','placeholder'=>'Select Barangay')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20" id="revisionyeardiv">
                                <div class="form-group">
                                    {{Form::label('revisionyear',__("Tax Declaration Details"),['class'=>'form-label'])}}
                                    <div class="btn-box">
                                    {{ Form::select('taxdeclairdetail',$arrTaxDeclaration,(session()->has('billingSelectedRevsionYear'))?session()->get('billingSelectedRevsionYear'):$arrTaxDeclaration, array('class' => 'form-control','id'=>'taxdeclairdetail','placeholder'=>'Select Details')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                              <div class="form-group"> 
                              {{Form::label('revisionyear',__("Search Details"),['class'=>'form-label'])}} 
                                <div class="btn-box">
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'rptPropertySearchByText')) }}
                                </div>
                              </div>
                            </div>
                            <div class="col-auto float-end ms-2" style="margin-top:10px;">
                                <a href="#" class="btn btn-sm btn-primary" id="btn_search">
                                    <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger" id="btn_clear">
                                    <span class="btn-inner--icon"><i class="ti-trash"></i></span>
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
                        <table class="table" id="Jq_datatablelist">
                            <thead>
                                <tr>
                                    <th>{{__('No.')}}</th>
                                    <th>{{__('T.D. No.')}}</th>
                                    <th>{{__('Taxpayers Name')}}</th>
                                    <th>{{__('Barangay')}}</th>
                                    <th>{{__('Class')}}</th>
                                    <th>{{__('PIN')}}</th>
                                    <th>{{__("Survey No|CCT|unit no|Description")}}</th>
                                    <th>{{__('Market Value')}}</th>
                                    <th>{{__("Assessed Value")}}</th>
                                    <th>{{__("Update Code")}}</th>
                                    <th>{{__("Effectivity")}}</th>
                                    <th>{{__("Updated By")}}</th>
                                    <th>{{__("Date")}}</th>
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
    
    <script src="{{ asset('js/assessment/assessment.js') }}?rand={{rand(0,999)}}"></script>
@endsection

