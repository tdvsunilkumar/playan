@extends('layouts.admin')

@section('page-title')
    {{__('Account Payables')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Accounting') }}</li>
    <li class="breadcrumb-item">{{ __('Account Payables') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @if ($can_download > 0)
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Download Payables')}}" class="btn btn-sm bg-print text-white add-btn">
            <i class="ti-files"></i>
        </a>    
        @endif
        @if ($can_create > 0)
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Create Account Payables')}}" class="btn btn-sm btn-primary add-btn">
            <i class="ti-plus"></i>
        </a>
        @endif
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
                                    <table id="accountPayableTable" class="display dataTable table w-100 table-striped" aria-describedby="accountPayableInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('VOUCHER NO.') }}</th>
                                                <th>{{ __('TRANSACTION') }}</th>
                                                <th>{{ __('VAT') }}</th>
                                                <th class="sliced">{{ __('GL ACCOUNT') }}</th>
                                                <th class="sliced">{{ __('ITEMS') }}</th>
                                                <th>{{ __('QUANTITY') }}</th>
                                                <th>{{ __('UOM') }}</th>
                                                <th>{{ __('AMOUNT') }}</th>
                                                <th>{{ __('TOTAL AMOUNT') }}</th>
                                                <th>{{ __('DUE DATE') }}</th>
                                                <th>{{ __('STATUS') }}</th>
                                                <th>{{ __('ACTIONS') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="14" class="dataTables_empty">Loading...</td>
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
    @include('accounting.account-payables.create')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/acctg-account-payable.js?v='.filemtime(getcwd().'/js/datatables/acctg-account-payable.js').'') }}"></script>
<script src="{{ asset('js/forms/acctg-account-payable.js?v='.filemtime(getcwd().'/js/forms/acctg-account-payable.js').'') }}"></script>
@endpush