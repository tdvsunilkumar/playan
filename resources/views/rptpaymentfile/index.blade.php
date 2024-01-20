@extends('layouts.admin')
@section('page-title')
    {{__('Real Property:Payment File [Subsidiary Ledger]')}}
@endsection
@push('script-page')
@endpush
<div>
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Real Property Treasury')}}</li>
@endsection
</div>
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
                        <div class="d-flex align-items-center justify-content-end" >
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 pdr-20" >
                                <div class="btn-box">
                                    {{ Form::label('rp_td_no', 'Tax Declaration Details', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::select('barangy_filter',$arrBarangay,[], array('class' => 'form-control','id'=>'rptPropertySearchByTD')) }}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 pdr-20" >
                                <div class="btn-box">
                                    {{ Form::label('Search', 'Barangay', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::select('barangy_filter',$arrBarangay,'', array('class' => 'form-control','id'=>'rptPropertySearchByBarangy')) }}
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12 pdr-20" >
                                <div class="btn-box">
                                    {{ Form::label('Search', 'Kind', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::select('kind_filter',$kinds,'', array('class' => 'form-control','id'=>'rptPropertySearchByKind','placeholder' => 'Select Kind')) }}
                                </div>
                            </div>
                            
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('Search', 'Search Here...', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
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
                                    <th>{{__('TaxPayer')}}</th>
                                    <th>{{__('Email')}}</th>
                                    <th>{{__('Barangay')}}</th>
                                    <th>{{__('Property Type')}}</th>
                                    <th>{{__('Area')}}</th>
                                    <!-- <th>{{__('Last Paid Date')}}</th> -->
                                    <th>{{__('Assessed Value')}}</th>
                                    <th>{{__('Last O.R. No.')}}</th>
                                    <th>{{__('Last O.R. Amount')}}</th>
                                    <th>{{__('Last O.R. Date')}}</th>
                                    <th>{{__('Details')}}</th>
                                </tr>
                            </thead>
                           </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('js/rptpaymentfile/index.js') }}?rand={{ rand(0,999) }}"></script>
@endsection