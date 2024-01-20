@extends('layouts.admin')
<style type="text/css">
    .datefield{padding-top: 26px;}
    .swal2-container{
        z-index:9999999 !important;
    }
    .select3-container{
        z-index: 9999999 !important;
    }

</style>

@section('page-title')
    {{__('Tax Clearance')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Tax Clearance')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        
        <!-- <a href="#"  data-url="{{ route('taxclearance.showform') }}" title="{{__('New Tax Clearance')}}" class="btn btn-sm btn-primary addNewProperty" {{ (session()->get('taxClearanceSelectedBrgy') == '')?'hidden':'' }}>
            <i class="ti-plus"></i>
        </a> -->
         <a href="#"  data-url="{{ route('taxclearance.showform') }}" title="{{__('New Tax Clearance')}}" class="btn btn-sm btn-primary addNewProperty">
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
                         <div class="d-flex align-items-center  justify-content-end">
                          <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12 col-12">
                                
                         </div>   
                       <!--  <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20" id="barangaydiv">
                                <div class="form-group">
                                    {{Form::label('barangay',__("Barangay"),['class'=>'form-label'])}}
                                    <div class="btn-box">
                                    {{ Form::select('barangy_filter',$arrBarangay,session()->get('billingSelectedBrgy'), array('class' => 'form-control','id'=>'rptPropertySearchByBarangy','placeholder'=>'Select Barangay')) }}
                                    </div>
                                </div>
                            </div> -->
                            
                            <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                              <div class="form-group">
									{{Form::label('fromdate',__('From date'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                      {{ Form::date('fromdate', now()->format('Y-m-d'), array('class' => 'form-control','placeholder'=>'From','id'=>'fromdate')) }}
                                </div>
                              </div>
                           </div>
                           <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                              <div class="form-group">
									  {{Form::label('todate',__('To date'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                      {{ Form::date('todate', now()->format('Y-m-d'), array('class' => 'form-control','placeholder'=>'To date','id'=>'todate')) }}
                                </div>
                              </div>
                           </div>
                            <!-- <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12 col-12">
                                
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12" id="revisionyeardiv">
                                <div class="form-group">
                                    <div class="btn-box">
                                    {{ Form::select('revision_year',$revisionYears,(session()->has('billingSelectedRevsionYear'))?session()->get('billingSelectedRevsionYear'):$activeRevisionYear, array('class' => 'form-control','id'=>'rptPropertySearchByRevisionYear')) }}
                                    </div>
                                </div>
                            </div> -->
                            
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20">
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
                                    <th>{{__('Control No.')}}</th>
                                    <th>{{__('Declared Owner')}}</th>
                                    <th>{{__('Tin No.')}}</th>
                                    <th>{{__('Requested By')}}</th>
                                    <th>{{__('Purpose')}}</th>
                                    <th>{{__('O.R. Number')}}</th>
                                    <th>{{__('O.R. Amount')}}</th>
                                    <th>{{__("T.D. Count")}}</th>
                                    <th>{{__("Date")}}</th>
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
    
    <script src="{{ asset('js/taxclearance/rptTaxClearanceNew.js') }}?rand={{rand(0,999)}}"></script>
@endsection

