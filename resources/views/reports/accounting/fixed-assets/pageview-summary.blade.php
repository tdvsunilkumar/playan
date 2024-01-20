@inject('controller', 'App\Http\Controllers\ReportAcctgFixedAssetController')
@extends('layouts.admin')

@section('page-title')
    {{__('Fixed Assets')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Reports') }}</li>
    <li class="breadcrumb-item">{{ __('Accounting') }}</li>
    <li class="breadcrumb-item">{{ __('Fixed Assets') }}</li>
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
            <div id="report-card" class="card noflow" style="min-height: 400px">
                <div class="card-body">
                    <img src="{{ url('/assets/images/logo.png') }}"/>
                    <h6 class="text-center m-0 mt-3 mb-1 fs-6">Republic of the Philippines</h6>
                    <h6 class="text-center m-0 mb-1 fs-6">Province of Nueva Ecija</h6>
                    <h6 class="text-center m-0 mb-1 fs-6">City of Palayan</h6>
                    <h1 class="text-center m-0 mt-4 fs-1 fw-bold">FIXED ASSET {{ strtoupper($categories[Request::get('category')]) }}</h1>
                    <h5 class="text-center m-0 fs-4">{{ $funds }}</h5>
                    <h6 class="text-center m-0 fs-5 mb-5">As of <u>{{ date('d-M-Y', strtotime(Request::get('date_from'))) }}</u> to <u>{{ date('d-M-Y', strtotime(Request::get('date_to'))) }}</u></h6>
                    @php 
                        $columnWidth = 100 / (count($fund_codes) + 2);
                    @endphp
                    <div class="row mt-5">
                        <div class="col-sm-12">
                            <table class="table" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <th rowspan="2" colspan="1" class="text-center" width="{{ ($columnWidth - 5) }}%" style="min-width: {{ ($columnWidth - 5) }}%; max-width: {{ ($columnWidth - 5) }}%;">Type of Asset</th>
                                        @if (!empty($fund_codes)) 
                                            @foreach ($fund_codes as $fund)
                                                @if ($fund->code != 301)
                                                <th rowspan="1" colspan="3" class="text-center" style="min-width: {{ ($columnWidth + 2.5) }}%; max-width: {{ ($columnWidth + 2.5) }}%;" width="{{ ($columnWidth + 2.5) }}%">{{ $fund->description }}</th>
                                                @endif
                                            @endforeach
                                        @endif
                                        <th rowspan="2" colspan="1" class="text-center" style="min-width: {{ ($columnWidth - 5) }}%; max-width: {{ ($columnWidth - 5) }}%;" width="{{ ($columnWidth - 5) }}%">Total Amount<br/>Book Value</th> 
                                    </tr>
                                    <tr>
                                        @if (!empty($fund_codes)) 
                                            @foreach ($fund_codes as $fund)
                                                @if ($fund->code != 301)
                                                <th style="min-width: {{ $columnWidth / 3 }}%; max-width: {{ $columnWidth / 3 }}%;">Acq. Cost</th>
                                                <th style="min-width: {{ $columnWidth / 3 }}%; max-width: {{ $columnWidth / 3 }}%;">Dep. Cost</th>
                                                <th style="min-width: {{ $columnWidth / 3 }}%; max-width: {{ $columnWidth / 3 }}%;">Book Value</th>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($rows))
                                        @php $total_cost = 0; $total_row = 0;                                         
                                            $arr = array(); $iteration = 0; 
                                            foreach ($fund_codes as $fund) {
                                                $arr[$iteration++] = 0;
                                                $arr[$iteration++] = 0;
                                                $arr[$iteration++] = 0;
                                            }                                      
                                        @endphp
                                        @foreach ($rows as $row)
                                        @php $total_row = 0; $iteration = 0; @endphp
                                        <tr>
                                            <td class="text-center">{{ $row->type }}</td>
                                            @if (!empty($fund_codes)) 
                                                @foreach ($fund_codes as $fund)
                                                    @if ($fund->code != 301)
                                                    @php 
                                                        $acquisition = $controller->get_acquisition_cost($row->id, $fund->id, Request::get('status'), Request::get('date_from'), Request::get('date_to'));
                                                        $depreciation = $controller->get_depreciation_cost($row->id, $fund->id, Request::get('status'), Request::get('date_from'), Request::get('date_to'));
                                                        $total = (floatval($acquisition) - floatval($depreciation));

                                                        $arr[$iteration++] += ($acquisition > 0) ? floatval($acquisition) : 0; 
                                                        $arr[$iteration++] += ($depreciation > 0) ? floatval($depreciation) : 0;
                                                        $arr[$iteration++] += ($total > 0) ? floatval($total) : 0;
                                                        
                                                        if ($total > 0) {
                                                            $total_row += floatval($total);
                                                            $total_cost += floatval($total);
                                                        }
                                                    @endphp
                                                    <td class="text-center">{{ $controller->money_format($acquisition) }}</td>
                                                    <td class="text-center">{{ $controller->money_format($depreciation) }}</td>
                                                    <td class="text-center">{{ $controller->money_format($total) }}</td>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <td class="text-center">{{ $controller->money_format($total_row) }}</td>
                                        </tr>
                                        @endforeach
                                        @php $iteration = 0; @endphp
                                        <tr>
                                            <td class="text-center fw-bold">Total</td>
                                            @if (!empty($fund_codes)) 
                                                @foreach ($fund_codes as $fund)
                                                @if ($fund->code != 301)
                                                <td class="text-center fw-bold">{{ $controller->money_format($arr[$iteration++]) }}</td>
                                                <td class="text-center fw-bold">{{ $controller->money_format($arr[$iteration++]) }}</td>
                                                <td class="text-center fw-bold">{{ $controller->money_format($arr[$iteration++]) }}</td>
                                                @endif
                                                @endforeach
                                            @endif
                                            <td class="text-center fw-bold">{{ $controller->money_format($total_cost) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="11" class="text-center">there are no records found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-5">
                        <div class="col-sm-3">
                            <p class="fs-5 fw-bold">Prepared By:</p>
                            <p class="fs-5 mt-3 text-center pb-2 mb-0 border-bottom">{{ ucwords($prepared->fullname) }}</p>
                            <p class="fs-5 text-center pt-2">{{ ucwords($prepared->designation) }}</p>
                        </div>
                        <div class="col-sm-3 offset-sm-6">
                            <p class="fs-5 fw-bold">Certified By:</p>
                            <p class="fs-5 mt-3 text-center pb-2 mb-0 border-bottom">{{ ucwords($certified->fullname) }}</p>
                            <p class="fs-5 text-center pt-2">{{ ucwords($certified->designation) }}</p>
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
    #report-card img {
        position: absolute; 
        left: 25px; 
        top: 25px; 
        width: 120px;
    }
    #report-card p span.fw-bold {
        float: left;
        width: 140px;
    }
    #report-card table {
        border-top: 1px solid #333;
        border-right: 1px solid #333;
        padding: 0 !important;
        table-layout: fixed;
    }
    #report-card table th {
        background: #eaeaea;
        color: #333;
        border-left: 1px solid #333;
        border-bottom: 1px solid #333;
        text-align: center;
        padding: 0.5rem 0rem !important;
    }
    #report-card table td {
        border-left: 1px solid #333;
        border-bottom: 1px solid #333;
        text-align: left;
        padding: 0.5rem !important;
    }
    #report-card table td:nth-child(4) {
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
<script src="{{ asset('js/forms/report-acctg-fixed-asset.js?v='.filemtime(getcwd().'/js/forms/report-acctg-fixed-asset.js').'') }}"></script>
@endpush