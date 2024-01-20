@extends('layouts.admin')
@section('page-title')
    {{__('Assessment & Fees')}}
@endsection
@push('script-page')
@endpush
<div>
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Assessment & Fees')}}</li>
@endsection
</div>
@section('action-btn')
    <div class="float-end">

            <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i></a>
            <!-- <a href="#" data-size="xll" data-url="{{url('/fire-protection/cashiering/store?busn_id=1&end_id=1&year=2023') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Assessment & Fees')}}" class="btn btn-sm btn-primary">
                <i class="ti-plus"></i>
            </a> -->
        
    </div>
@endsection
@section('content')
<link href="{{ asset('assets/js/yearpicker.css')}}" rel="stylesheet">
<style type="text/css">
    .yearpicker-container {
    position: fixed;
    color: var(--text-color);
    width: 280px;
    border: 1px solid var(--border-color);
    border-radius: 3px;
    font-size: 1rem;
    box-shadow: 1px 1px 8px 0px rgba(0, 0, 0, 0.2);
    background-color: var(--background-color);
    z-index: 10;
    margin-top: 0.2rem;
}

</style>
     <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                    <?php
                                        $currentYear = date('Y');
                                    ?>
                                   <b> Year</b>{{ Form::text('search_year', $currentYear, array('class' => 'yearpicker form-control','id'=>'search_year')) }}
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                    <?php $end_status=config('constants.arrBusEndorsementStatus')?>
                                {{ Form::label('Search', 'Endorsement Status', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::select('endorsement_status', [''=> 'Please Select'] + $end_status, null, ['class' => 'form-control', 'id' => 'endorsement_status']) }}

                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('Search', 'Payment Status', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::select('payment_status', [''=>'Please Select','0' => 'Pending', '1' => 'Paid'], old('payment_status'), ['class' => 'form-control', 'id' => 'payment_status']) }}
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
                                <th>{{__('Business Id-No.')}}</th>
                                <th>{{__('Owner Name')}}</th>
                                <th>{{__('Business Name')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Application Status')}}</th>
                                <th>{{__('Endorsement Status')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Payment Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                           </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/yearpicker.js') }}"></script>
    <script src="{{ asset('js/Bplo/BfpAssessment.js') }}?rand={{ rand(000,999) }}"></script>
@endsection
