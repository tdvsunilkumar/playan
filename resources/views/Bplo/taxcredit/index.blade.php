@extends('layouts.admin')
<style type="text/css">
    
    .datefield{padding-top: 26px;}
</style>
@section('page-title')
    {{__('Business Permit: Tax Credit File')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Tax Credit File')}}</li>
    
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                              <div class="form-group">
                                {{ Form::label('business', __('Business Details'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('business') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('businessids',$arrBusinessnames,'', array('class' => 'form-control select3 ','id'=>'businessids','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_agl_code"></span>
                              </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                              <div class="form-group">
                                {{Form::label('fromdate',__('From date'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                      {{ Form::date('fromdate', $startdate, array('class' => 'form-control','placeholder'=>'From','id'=>'fromdate')) }}
                                </div>
                              </div>
                           </div>
                           <div class="col-lg-2 col-md-2 col-sm-2">
                              <div class="form-group">
                                {{Form::label('todate',__('To date'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                      {{ Form::date('todate', $enddate, array('class' => 'form-control','placeholder'=>'To date','id'=>'todate')) }}
                                </div>
                              </div>
                           </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                              <div class="form-group">
                                {{Form::label('search',__('Search'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                      {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
                                </div>
                              </div>
                           </div>
                           <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="col-auto float-end ms-2" style="padding-top: 40px;">
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
                                    <th>{{__('Business ID-No.')}}</th>
                                    <th>{{__('Taxpayer')}}</th>
                                    <th>{{__('Business Name')}}</th>
                                    <th>{{__('O.R. Number')}}</th>
                                    <th>{{__('O.R. Amount')}}</th>
                                    <th>{{__('O.R. Date')}}</th>
                                    <th>{{__('Credit Amount')}}</th>
                                    <th>{{__('Account Description')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Details')}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <style>
    .modal.show .modal-dialog {
        transform: none;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 821px;
    pointer-events: auto;
    background-color: #ffffff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 15px;
    outline: 0;
    float: left;
    margin-left: 18%;
    margin-top: 50%;
    transform: translate(-6%, -50%);
}
    .col-md-1 {
        flex: 0 0 auto;
        width: 15.33333%;
    }
   
 </style>
      <div class="modal" id="viewdetails" class="hide" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-lg modalDiv" style="max-width: 800px;">
            <div class="modal-content" id="serviceform">
                <div class="modal-header">
                                <h4 class="modal-title">Tax Credit File <span id="orno"></span></h4>
                                <a class="close closeReqModal" mid="" type="edit" data-dismiss="modal" aria-hidden="true">X</a>
                            </div>
                            <div class="modal-body">
                                 <div class="row">
                                     <div class="col-md-4">
                                       <div class="form-group">
                                            {{ Form::label('orno', __('Reference O.R. No'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('reforno','', array('class' => 'form-control disabled-field','id'=>'reforno')) }}
                                            </div>
                                        </div>
                                    </div>

                                   <div class="col-md-4">
                                       <div class="form-group">
                                            {{ Form::label('oramount', __('O.R. Amount'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('oramount','', array('class' => 'form-control disabled-field','id'=>'oramount')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                            {{ Form::label('ordate', __('O.R. Date'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('ordate','', array('class' => 'form-control disabled-field','id'=>'ordate')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                     <div class="col-md-12">
                                       <div class="form-group">
                                            {{ Form::label('chartofaccount', __('Chart of Account'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('chartofaccount','', array('class' => 'form-control disabled-field','id'=>'chartofaccount')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                     <div class="col-md-12">
                                       <div class="form-group">
                                            {{ Form::label('cashier', __('Cashier'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('cashier','', array('class' => 'form-control disabled-field','id'=>'cashier')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="currentapplieddetail" id="currentapplieddetail">
                                  <div  class="accordion accordion-flush">
                        <div class="accordion-item">
                            <h6 class="accordion-header" id="flush-headingfive1">
                                <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="true" aria-controls="flush-headingfive">
                                    <h6 class="sub-title accordiantitle">{{__("Utilization Details")}}</h6>
                                </button>
                    </h6>
                            <div id="flush-collapsefive" class="collapse show" aria-labelledby="flush-headingfive1" style="padding:10px;">
                                <div class="row">
                                     <div class="col-md-4">
                                       <div class="form-group">
                                            {{ Form::label('currentorno', __('Applied Or No'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('reforno','', array('class' => 'form-control disabled-field','id'=>'currentorno')) }}
                                            </div>
                                        </div>
                                    </div>

                                   <div class="col-md-4">
                                       <div class="form-group">
                                            {{ Form::label('currentcreditamt', __('Credited Amount'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('oramount','', array('class' => 'form-control disabled-field','id'=>'currentcreditamt')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                            {{ Form::label('currentordate', __('O. R. Date'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('ordate','', array('class' => 'form-control disabled-field','id'=>'currentordate')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                     <div class="col-md-12">
                                       <div class="form-group">
                                            {{ Form::label('chartofaccount', __('Chart Of Account'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('chartofaccount','', array('class' => 'form-control disabled-field','id'=>'currentchartofaccount')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                     <div class="col-md-12">
                                       <div class="form-group">
                                            {{ Form::label('cashier', __('Cashier'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('cashier','', array('class' => 'form-control disabled-field','id'=>'currentcashier')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
               </div>
        </div>
</div>
    <script src="{{ asset('js/Bplo/taxcredit.js') }}"></script>
@endsection

  

