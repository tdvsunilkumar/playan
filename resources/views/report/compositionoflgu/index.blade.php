@extends('layouts.admin')
<style type="text/css">
    .datefield{padding-top: 26px;}
</style>
@section('page-title')
    {{__('Composition of LGU Fees')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Composition of LGU Fees')}}</li>
    
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
    </div>
@endsection
@section('content')
 <style>
     .card:not(.table-card) .table tr td:first-child, .card:not(.table-card) .table tr th:first-child {
        padding-left: 25px;
        padding-top: 10px
        vertical-align: top;
    }
    table.dataTable td, table.dataTable th {
        -webkit-box-sizing: content-box;
        box-sizing: content-box;
        vertical-align: top;
        padding-top: 10px
</style>
    <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-11 col-md-11 col-sm-11">
                              <div class="form-group">
                                {{ Form::label('subclass', __('Select Line of Business'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('subclass') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('subclass',$arrDepaertments,'', array('class' => 'form-control ','id'=>'subclass','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_agl_code"></span>
                              </div>
                            </div>
                           <div class="col-lg-1 col-md-1 col-sm-1">
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
                                    <th>{{__('Description')}}</th>
                                    <th>{{__('Transaction')}}</th>
                                    <th>{{__('Type')}}</th>
                                    <th>{{__('Effectivity Date')}}</th>
                                    <th>{{__('Composition Type ')}}</th>
                                    <th>{{__('Amount')}}</th>
                                </tr>
                            </thead>
                           
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
      <div class="modal" id="viewdetails" class="hide" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-lg modalDiv" style="max-width: 1200px;">
            <div class="modal-content" id="serviceform">
                <div class="modal-header">
                                <h4 class="modal-title">Reference OR No:&nbsp;&nbsp;<span id="orno"></span></h4>
                                <a class="close closeReqModal" mid="" type="edit" data-dismiss="modal" aria-hidden="true">X</a>
                            </div>
                            <div class="modal-body">
                                <p>Taxpayers:&nbsp;&nbsp;<span id="taxpayername"></span></p>
                                <table class="table table-responsive" id="dynamicdetails">
                                </table>
                     </div>
            </div>
        </div>
</div>
    <script src="{{ asset('js/report/compositionoflgu.js') }}?rand={{rand(0,999)}}"></script>
@endsection

  

