@inject('controller', 'App\Http\Controllers\ReportAcctgLedgerController')
@extends('layouts.admin')

@section('page-title')
    {{__('Ledgers')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Reports') }}</li>
    <li class="breadcrumb-item">{{ __('Accounting') }}</li>
    <li class="breadcrumb-item">{{ __('Ledger') }}</li>
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
                    <h1 class="text-center m-0 mt-4 fs-1 fw-bold">{{ Request::get('ledger_type') == 'subsidiary-ledger' ? 'SUBSIDIARY' : 'GENERAL' }} LEDGER</h1>
                    <h5 class="text-center m-0 fs-4">{{ $funds }}</h5>
                    <h6 class="text-center m-0 fs-5 mb-5">As of <u>{{ date('d-M-Y', strtotime(Request::get('date_from'))) }}</u> to <u>{{ date('d-M-Y', strtotime(Request::get('date_to'))) }}</u></h6>

                    <div class="row mt-5">
                        <div class="col-md-9">
                            <p class="fs-5 mb-0 w-100">
                                <span class="fw-bold">Account's Title:</span> 
                                {{ $titles }}
                            </p>
                        </div>
                        <div class="col-md-3">
                            <p class="fs-5 mb-0 w-100">
                                <span class="fw-bold">Account Code:</span> 
                                {{ $codes }}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <p class="fs-5 w-100">
                                <span class="fw-bold">Name:</span> 
                                {{ $names }}
                            </p>
                        </div>
                        <div class="col-md-3">
                            <p class="fs-5 w-100">
                                <span class="fw-bold">Category:</span> 
                                {{ Request::get('category') ? Request::get('category') : '' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="row mt-5">
                        <div class="col-sm-12">
                            <table class="table" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <th>JEV No.</th>
                                        @if (Request::get('ledger_type') != 'subsidiary-ledger')
                                        <th>GL Account</th>
                                        @endif
                                        <th>Date</th>
                                        <th>Payee/Payer</th>
                                        <th>Particulars</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($rows))
                                        @php $totalAmt = 0; @endphp
                                        @foreach ($rows as $row)
                                            @if (strlen($row->jev_no) > 0)
                                            <tr>
                                                <td class="text-center"><span class="fw-bold">{{ $row->jev_no }}</span><br/>{{ $row->type }}</td>
                                                @if (Request::get('ledger_type') != 'subsidiary-ledger')
                                                <td class="text-center"><span class="fw-bold">{{ $row->account_code }}</span><br/>{{ $row->account_desc }}</td>
                                                @endif
                                                <td class="text-center">{{ $row->posted }}</td>
                                                <td class="text-center">{{ $row->payee }}</td>
                                                <td class="text-justify">{{ $row->particulars }}</td>
                                                <td class="text-end">{{ number_format($row->debit, 2) }}</td>
                                                <td class="text-end">{{ number_format($row->credit, 2) }}</td>
                                                <td class="text-end">{{ number_format($row->balance, 2) }}</td>
                                            </tr>
                                            @php $totalAmt += floatval($row->balance); @endphp
                                            @endif
                                        @endforeach
                                        <tr>
                                            <td class="text-end fw-bold" colspan="{{ (Request::get('ledger_type') != 'subsidiary-ledger') ? 7 : 6 }}">TOTAL AMOUNT</td>
                                            <td class="text-end fw-bold" colspan="1">{{ number_format($totalAmt, 2) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="{{ (Request::get('ledger_type') != 'subsidiary-ledger') ? 7 : 6 }}" class="text-center">there are no records found.</td>
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
    #report-ledger-card table td:nth-child(2),
    #report-ledger-card table td:nth-child(4) {
        min-width: 200px !important;
        max-width: 200px !important;
        word-wrap: break-word;
        white-space: normal;
    }
    #report-ledger-card table td:nth-child(5) {
        min-width: 250px !important;
        max-width: 250px !important;
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