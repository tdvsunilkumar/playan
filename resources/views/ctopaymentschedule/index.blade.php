@extends('layouts.admin')
@section('page-title')
    {{__('Payment Deadline(Penalty & Discount)')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Payment Deadline(Penalty & Discount)')}}</li>

@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i></a>
            <a href="#" data-size="lg" data-url="{{ url('/ctopaymentschedule/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Payment Schedule')}}" class="btn btn-sm btn-primary addNewPaymentScedule" >
            <i class="ti-plus"></i>
        </a>
        
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
/*    margin-top: 0.2rem;*/
}
</style>
<div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">

                    <div class="card-body">
                         {{ Form::open(array('url' => '','id'=>"rptPropertySerachFilter")) }}
                        <div class="d-flex align-items-center justify-content-end">
                            
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20" >
                               
                                    <div class="btn-box">
                                        
                                        <?php
                                            $currentYear = date('Y');
                                        ?>
                                         {{ Form::label('Search', 'Year.', ['class' => 'fs-6 fw-bold']) }}

                                          {{ Form::text('revision_year',$currentYear, array('class' => 'form-control yearpicker ','maxlength'=>'4','required'=>'required','id'=>'rptPropertySearchByRevisionYear')) }}
                                  <!--   {{ Form::select('revision_year',$collectionYears,(session()->has('paymentScheduleYear'))?session()->get('paymentScheduleYear'):date("Y"), array('class' => 'form-control yearpicker','id'=>'rptPropertySearchByRevisionYear','placeholder' => 'Select Year')) }} -->

                                         <!--  {{ Form::text('revision_year',(session()->has('paymentScheduleYear'))?session()->get('paymentScheduleYear'):date("Y"), array('class' => 'form-control yearpicker','maxlength'=>'4','required'=>'required','id'=>'rptPropertySearchByRevisionYear')) }} -->
                                    <!-- {{ Form::select('revision_year',$collectionYears,(session()->has('paymentScheduleYear'))?session()->get('paymentScheduleYear'):date("Y"), array('class' => 'form-control','id'=>'rptPropertySearchByRevisionYear','placeholder' => 'Select Year')) }} -->

                                    </div>
                                
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('Search', 'Search Here...', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'rptPropertySearchByText')) }}
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
                                <th>{{__('YEAR')}}</th>
                                <th>{{__('Schedule')}}</th>
                                <th>{{__('Start Date')}}</th>
                                <th>{{__('END DATE')}}</th>
                                <th>{{__('PENALTY DUE DATE')}}</th>
                                <th>{{__('DISCOUNT DUE DATE')}}</th>
                                <th>{{__('DISCOUNT[%]')}}</th>
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
    <script src="{{ asset('assets/js/yearpicker.js') }}"></script>
    <script src="{{ asset('js/ctopaymentschedule.js') }}?rand={{ rand(000,999) }}"></script>
@endsection
