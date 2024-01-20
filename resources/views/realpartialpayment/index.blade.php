@extends('layouts.admin')
<style type="text/css">
    
    .datefield{padding-top: 26px;}
</style>
@section('page-title')
    {{__('Real Property: Partial Payments')}}
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
                             <div class="col-xl-2 col-lg-2 col-md-2" id="revisionyeardiv">
                                <div class="form-group">
                                    {{Form::label('revisionyear',__("Tax Declaration Details"),['class'=>'form-label'])}}
                                    <div class="btn-box">
                                    {{ Form::select('taxdeclairdetail',$arrTaxDeclaration,'', array('class' => 'form-control ','id'=>'taxdeclairdetail','placeholder'=>'Select Details')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
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
                                    <th>{{__('Total Amount')}}</th>
                                    <th>{{__('O.R. Period')}}</th>
                                    <th>{{__('O.R. NO')}}</th>
                                    <th>{{__('O.R. Date')}}</th>
                                    <th>{{__('O.R. Amount')}}</th>
                                    <th>{{__('Balance')}}</th>
                                    <th>{{__('Details')}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
      <div class="modal" id="viewdetails" class="hide" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-lg modalDiv" style="max-width: 800px;">
            <div class="modal-content" id="serviceform">
                <div class="modal-header">
                                <h4 class="modal-title">Tax Credit File <span id="orno"></span></h4>
                                <a class="close closeReqModal" mid="" type="edit" data-dismiss="modal" aria-hidden="true">X</a>
                            </div>
                            <div class="modal-body" id="viewdetails">
                                
                </div>
               </div>
        </div>
</div>
    <script src="{{ asset('js/cashierrealproperty/partialpayment.js') }}?rand={{rand(0,999)}}"></script>
@endsection

  
