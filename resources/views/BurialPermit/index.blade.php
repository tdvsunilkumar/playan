@extends('layouts.admin')
@section('page-title')
    {{__('Burial Permit: Cashering')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Burial Permit')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="ti-filter"></i></a>
        <a href="#" data-size="xxll" data-url="{{url('/cashier/burial-permit/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Burial Permit Cashiering')}}" class="btn btn-sm btn-primary">
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
                        <div class="d-flex align-items-center justify-content-end" >
                            <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                              <div class="form-group">
                                @php
                                    $fromDate = date('Y-m-d', strtotime(date('Y-m-d').' -1 months'));
                                @endphp
                                {{Form::label('fromdate',__('From date'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                      {{ Form::date('fromdate',$fromDate, array('class' => 'form-control','placeholder'=>'From','id'=>'fromdate')) }}
                                </div>
                              </div>
                           </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                              <div class="form-group">
                                {{Form::label('todate',__('To date'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                      {{ Form::date('todate',date("Y-m-d"), array('class' => 'form-control','placeholder'=>'To date','id'=>'todate')) }}
                                </div>
                              </div>
                           </div>
                           <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                              <div class="form-group">
                                {{Form::label('todate',__('Status'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                   {{ Form::select('status', ['3' => 'Select Status', '1' => 'Active', '0' => 'Cancelled'], '1', ['class' => 'form-control spp_type', 'id' => 'status']) }}
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
                                    <th>{{__('Year')}}</th>
                                    <th>{{__('Payee Type')}}</th>
                                    <th>{{__('Taxpayer Name')}}</th>
                                    <th>{{__('Address')}}</th>
                                    <th>{{__('Full Name[Expired]')}}</th>
                                    <th>{{__('O.R. NO.')}}</th>
                                    <th>{{__('Amount')}}</th>
                                    <th>{{__('Date')}}</th>
                                    <th>{{__('Payment terms')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Cashier')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(session()->has('BURIAL_PRINT_CASHIER_ID'))
         <iframe id="openPrintDialog" src="@php echo url('/cashier/burial-permit/printReceipt?id='.Session::get('BURIAL_PRINT_CASHIER_ID')) @endphp" class="hide" width="100%" height="800px"></iframe>
        @php  Session::forget('BURIAL_PRINT_CASHIER_ID'); @endphp
    @endif
    <script src="{{ asset('js/burialpermitcashering.js') }}?rand={{ rand(000,999) }}"></script>
@endsection
