@extends('layouts.admin')
@section('page-title')
    {{__('Business Tax: Accounts Receivables')}}
@endsection
@push('script-page')
@endpush
<style type="text/css">
    .account-receivable th{
        text-align:center;border: 1px solid #fff;
    }
</style>
<div>
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Treasure')}}</li>
    <li class="breadcrumb-item">{{__('Accounts Receivables')}}</li>
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
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('search_busn_id', 'Business Details', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::select('search_busn_id',$arrBusiness,'', ['class' => 'form-control select3', 'id' => 'search_busn_id']) }}
                                </div>
                            </div>

                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('barngay_id', 'Barangay', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::select('barngay_id',$arrBarngay,'', ['class' => 'form-control select3', 'id' => 'barngay_id']) }}
                                </div>
                            </div>

                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('client_id', 'Taxpayer Name', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::select('client_id',array(""=>"Please Select"),'', ['class' => 'form-control select3', 'id' => 'client_id']) }}
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
                        <table class="table account-receivable" id="Jq_datatablelist">
                            <thead>
                                <tr >
                                    <th rowspan="2">No.</th>
                                    <th rowspan="2">Business Id-No.</th>
                                    <th rowspan="2">Taxpayer Name</th>
                                    <th rowspan="2">Email</th>
                                    <th rowspan="2">Business Name</th>
                                    <th rowspan="2">Location</th>

                                    <th colspan="3">Last Transaction</th>
                                    <th colspan="4">OutStanding</th>
                                    <th colspan="4">Delinquent</th>

                                    <th rowspan="2">{{__('Total')}}</th>
                                    <th rowspan="2">{{__('Action')}}</th>
                                </tr>
                                <tr>
                                    <th rowspan="1">{{__('O.R. No.')}}</th>
                                    <th rowspan="1">{{__('O.R. Amount')}}</th>
                                    <th rowspan="1">{{__('O.R. Date')}}</th>

                                    <th rowspan="1">{{__('Tax Due')}}</th>
                                    <th rowspan="1">{{__('Penalty')}}</th>
                                    <th rowspan="1">{{__('Interest')}}</th>
                                    <th rowspan="1">{{__('Total')}}</th>

                                    <th rowspan="1">{{__('Tax Due')}}</th>
                                    <th rowspan="1">{{__('Penalty')}}</th>
                                    <th rowspan="1">{{__('Interest')}}</th>
                                    <th rowspan="1">{{__('Total')}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('js/Bplo/DelinquencyOutstanding.js') }}"></script>
@endsection
