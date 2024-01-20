@extends('layouts.admin')

@section('page-title')
    {{__('Statement of Receipts Sources')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Reports') }}</li>
    <li class="breadcrumb-item">{{ __('Finance') }}</li>
    <li class="breadcrumb-item">{{ __('Budget Expense') }}</li>
@endsection

@section('action-btn')
    <div class="float-end">
        <a href="javascript:;" data-actions="print" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{__('download pdf')}}" class="btn btn-sm btn-danger">
            <i class="la la-file-pdf-o"></i>
        </a>
        <a href="javascript:;" data-actions="print" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{__('download excel')}}" class="ms-1 btn btn-sm btn-green">
            <i class="la la-file-excel-o"></i>
        </a>
        <a href="javascript:;" data-actions="print" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{__('print this')}}" class="ms-1 btn btn-sm btn-blue">
            <i class="ti-printer"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div id="report-ledger-card" class="card noflow" style="min-height: 400px">
                <div class="card-body">
                    <img src="{{ url('/assets/images/logo.png') }}"/>
                    <h6 class="text-center m-0 mt-3 mb-1 fs-6">Republic of the Philippines</h6>
                    <h6 class="text-center m-0 mb-1 fs-6">Province of Nueva Ecija</h6>
                    <h6 class="text-center m-0 mb-1 fs-6">City of Palayan</h6>
                    <h1 class="text-center m-0 mt-4 fs-1 fw-bold">Budget Expense</h1>
                    <h6 class="text-center m-0 fs-5 mb-5">As of <u>{{ date('d-M-Y', strtotime(Request::get('date_from'))) }}</u> to <u>{{ date('d-M-Y', strtotime(Request::get('date_to'))) }}</u></h6>

                    
                    <div class="row mt-5">
                        <div class="col-sm-12" style="overflow:auto">
                            <table class="table" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <th rowspan='2'>Responsibility</br>Center</th>
                                        <th rowspan='2'>Office</th>
                                        <th rowspan='2'>Account</th>
                                        <th rowspan='2'>Original Budget</th>
                                        <th rowspan='2'>Re-alignment</th>
                                        <th rowspan='2'>Final Budget</th>
                                        <th rowspan='2'>Savings</th>
                                        <th rowspan='2'>Available</th>
                                        <th colspan="{{count($months)}}">Expenditure</th>
                                        <th rowspan='2'>Total Expense</th>
                                        <th rowspan='2'>Balance</th>
                                    </tr>
                                    <tr>
                                        @foreach($months as $month)
                                            <th>{!!nl2br($month)!!}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($rows))
                                        @foreach ($rows as $row)
                                            <tr>
                                                <td class="text-center">{{$row->code}}</td>
                                                <td class="text-center">{{$row->name}}</td>
                                                <td class="text-center">{{$row->gl_name}}</td>
                                                <td class="text-end fw-bold"> {{ $row->annual_budget }}</td>
                                                <td class="text-end">{{$row->alignment}}</td>
                                                <td class="text-end">{{$row->final_budget}}</td>
                                                <td class="text-center"></td>
                                                <td class="text-justify"></td>
                                                @foreach($months as $month)
                                                    <td class="text-center"></td>
                                                @endforeach
                                                <td class="text-center">{{$row->amount_used}}</td>
                                                <td class="text-center"></td>
                                            </tr>
                                            
                                        @endforeach
                                        
                                    @else
                                        <tr>
                                            <td colspan="{{10 + count($months)}}" class="text-center">there are no records found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-sm-3 offset-sm-9">
                            <p class="fs-5 fw-bold">Certified Correct By:</p>
                            <p class="fs-5 mt-3 text-center pb-2 mb-0 border-bottom">Rogelmar Denopol</p>
                            <p class="fs-5 text-center pt-2">Project Manager</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
<style>
    #report-ledger-card img {
        position: absolute; 
        left: 25px; 
        top: 25px; 
        width: 120px;
    }
    #report-ledger-card p span.fw-bold {
        float: left;
        width: 140px;
    }
    #report-ledger-card table {
        border-top: 1px solid #333;
        border-right: 1px solid #333;
    }
    #report-ledger-card table th {
        background: #eaeaea;
        color: #333;
        border-left: 1px solid #333;
        border-bottom: 1px solid #333;
        text-align: center;
    }
    #report-ledger-card table td {
        border-left: 1px solid #333;
        border-bottom: 1px solid #333;
        text-align: left;
        padding: .75rem 1.5rem;
    }
    #report-ledger-card table td:nth-child(4) {
        min-width: 200px !important;
        max-width: 200px !important;
        word-wrap: break-word;
        white-space: normal;
    }
    .border-bottom {
        border-bottom: 1px solid #333;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/forms/report-acctg-ledger.js?v='.filemtime(getcwd().'/js/forms/report-acctg-ledger.js').'') }}"></script>
@endpush