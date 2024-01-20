@extends('layouts.admin')

@section('page-title')
    {{ 'Debit Memo'.__(' - Journal Entry (Voucher)')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Accounting') }}</li>
    <li class="breadcrumb-item">{{ __('Journal Entry (Voucher)') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @if ($accounting_permission['create'] > 0)
        <!-- <a href="{{ url('accounting/journal-entries/debit-memo/add') }}" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Create Voucher')}}" class="btn btn-sm btn-primary add-btn">
            <i class="ti-plus"></i>
        </a> -->
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
                                    <table id="voucherTable" class="display dataTable table w-100 table-striped" aria-describedby="voucherInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('VOUCHER NO.') }}</th>
                                                <th class="sliced">{{ __('PAYEE') }}</th>
                                                <th>{{ __('TOTAL GROSS') }}</th>
                                                <th>{{ __('TOTAL DEDUCTIONS') }}</th>
                                                <th>{{ __('TOTAL DISBURSEMENT') }}</th>
                                                <th>{{ __('LAST MODIFIED') }}</th>
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
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/growl/jquery.growl.css?v='.filemtime(getcwd().'/assets/vendors/growl/jquery.growl.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/acctg-account-voucher.js?v='.filemtime(getcwd().'/js/datatables/acctg-account-voucher.js').'') }}"></script>
<script src="{{ asset('js/forms/acctg-account-voucher.js?v='.filemtime(getcwd().'/js/forms/acctg-account-voucher.js').'') }}"></script>
@endpush