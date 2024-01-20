@inject('controller', 'App\Http\Controllers\ReportAcctgJournalController')
@extends('layouts.admin')

@section('page-title')
    {{__('Cash Receipt Journal')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Reports') }}</li>
    <li class="breadcrumb-item">{{ __('Accounting') }}</li>
    <li class="breadcrumb-item">{{ __('Cash Receipt Journal') }}</li>
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
                    <h1 class="text-center m-0 mt-4 fs-1 fw-bold">{{ strtoupper($categories[Request::get('category')]) }}</h1>
                    <h5 class="text-center m-0 fs-4">{{ $funds }}</h5>
                    <h6 class="text-center m-0 fs-5 mb-5">As of <u>{{ date('d-M-Y', strtotime(Request::get('date_from'))) }}</u> to <u>{{ date('d-M-Y', strtotime(Request::get('date_to'))) }}</u></h6>
                    <div class="row mt-5">
                        <div class="col-sm-12">
                            <table class="table" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <th rowspan="1" colspan="1" class="text-center">Date</th>
                                        <th rowspan="1" colspan="1"  class="text-center">JEV No.</th>
                                        <th rowspan="1" colspan="1"  class="text-center">Payee/Payer</th>
                                        <th rowspan="1" colspan="1"  class="text-center">Particulars</th>
                                        <th rowspan="1" colspan="1"  class="text-center">Account Title</th>
                                        <th rowspan="1" colspan="1"  class="text-center">Account Code</th>
                                        <th rowspan="1" colspan="1"  class="text-center">Debit</th>
                                        <th rowspan="1" colspan="1"  class="text-center">Credit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_debit = 0; $total_credit = 0; @endphp
                                    @if (!empty($rows)) 
                                        @foreach ($rows as $row)
                                            <tr>
                                                <td rowspan="{{ count($row->incomes) }}" colspan="1" class="text-center">{{ $row->date }}</td>
                                                <td rowspan="{{ count($row->incomes) }}" colspan="1"  class="text-center">{{ $row->jev_no }}</td>
                                                <td rowspan="{{ count($row->incomes) }}" colspan="1"  class="text-start">{{ $row->payee }}</td>
                                                <td rowspan="{{ count($row->incomes) }}" colspan="1"  class="text-justify">{{ $row->particulars }}</td>
                                                @php $i = 0; @endphp                                                
                                                @foreach ($row->incomes as $income)
                                                    @if (count($row->incomes) > 1 && $i > 0)
                                                    <tr>         
                                                    @endif                                                    
                                                        <td rowspan="1" colspan="1"  class="text-center">{{ $income->account_desc }}</td>
                                                        <td rowspan="1" colspan="1"  class="text-center">{{ $income->account_code }}</td>
                                                        <td rowspan="1" colspan="1"  class="text-end">{{ $controller->money_format($income->amount_deposited) }}</td>
                                                        <td rowspan="1" colspan="1"  class="text-end">{{ $controller->money_format($income->amount_collected) }}</td>
                                                        @php
                                                            $total_debit += $income->amount_deposited;
                                                            $total_credit += $income->amount_collected;
                                                        @endphp
                                                    @if (count($row->incomes) > 1 && $i > 0)
                                                    </tr>
                                                    @endif
                                                    @php $i++; @endphp
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8" class="text-center">there are no records found.</td>
                                        </tr>
                                    @endif
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-start">
                                                Total Amount
                                            </th>
                                            <th>
                                                {{$controller->money_format($total_debit)}}
                                            </th>
                                            <th>
                                                {{$controller->money_format($total_credit)}}
                                            </th>
                                        </tr>
                                    </tfoot>
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
        table-layout: fixed !important;
        width:100%;
    }

    #report-card table th {
        background: #eaeaea;
        color: #333;
        border-left: 1px solid #333;
        border-bottom: 1px solid #333;
        text-align: center;
        padding: 0.5rem !important;
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
    #report-card table td,
    #report-card table th {
        word-wrap: break-word;
        white-space: normal; 
    }
    .border-bottom {
        border-bottom: 1px solid #333;
    }
    .table tr:nth-child(even){
        background-color: inherit!important;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/forms/report-acctg-journal.js?v='.filemtime(getcwd().'/js/forms/report-acctg-journal.js').'') }}"></script>
@endpush