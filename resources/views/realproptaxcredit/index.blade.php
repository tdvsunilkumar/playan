@extends('layouts.admin')
<style type="text/css">
    
    .datefield{padding-top: 26px;}
</style>
@section('page-title')
    {{__('Real Property: Tax Credit File [Overpayment]')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Real Property Treasury')}}</li>
    
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
                                {{ Form::label('business', __('Barangay'),['class'=>'form-label']) }}<span class="text-danger">*</span>
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
                                    <th>{{__('T.D. No.')}}</th>
                                    <th>{{__('Taxpayer')}}</th>
                                    <th>{{__('Barangay')}}</th>
                                    <th>{{__('Property Type')}}</th>
                                    <th>{{__('Area')}}</th>
                                    <th>{{__('Assessed Value')}}</th>
                                    <th>{{__('TOP No.')}}</th>
                                    <th>{{__('O.R. Number')}}</th>
                                    <th>{{__('O.R. Amount')}}</th>
                                    <th>{{__('O.R. Date')}}</th>
                                    <th>{{__('Credit Amount')}}</th>
                                    <th>{{__('Account Description')}}</th>
                                    <th>{{__('Payment Type')}}</th>
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
                            <div class="modal-body" id="viewDetailsBody">
                                
                </div>
               </div>
        </div>
</div>
    <script src="{{ asset('js/cashierrealproperty/taxcredit.js') }}?rand={{rand(0,999)}}"></script>
@endsection

  

