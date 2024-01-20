@extends('layouts.admin')

@section('page-title')
    {{__('General Ledger Account')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Accounting') }}</li>
    <li class="breadcrumb-item">{{ __('Chart of Accounts') }}</li>
    <li class="breadcrumb-item">{{ __('General Ledger') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Create General Ledger Account')}}" class="btn btn-sm btn-primary add-btn">
            <i class="ti-plus"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card table-card">
                <div class="card-body">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="generalLedgerAccountTable" class="display dataTable table w-100 table-striped" aria-describedby="glAccountInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('ACCOUNT') }}</th>
                                                <th class="sliced">{{ __('MAJOR') }}</th>
                                                <th class="sliced">{{ __('SUB-MAJOR') }}</th>
                                                <th>{{ __('CODE') }}</th>
                                                <th class="sliced">{{ __('DESCRIPTION') }}</th>
                                                <th class="sliced">{{ __('NORMAL BALANCE') }}</th>
                                                <th>{{ __('WITH S/L') }}</th>
                                                <th>{{ __('LAST MODIFIED') }}</th>
                                                <th>{{ __('STATUS') }}</th>
                                                <th>{{ __('ACTIONS') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('accounting.chart-of-accounts.general-ledgers.create')
    @include('accounting.chart-of-accounts.general-ledgers.add-sl')
    @include('accounting.chart-of-accounts.general-ledgers.add-current')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/acctg-account-general-ledger.js?v='.filemtime(getcwd().'/js/datatables/acctg-account-general-ledger.js').'') }}"></script>
<script src="{{ asset('js/forms/acctg-account-general-ledger.js?v='.filemtime(getcwd().'/js/datatables/acctg-account-general-ledger.js').'') }}"></script>
@endpush