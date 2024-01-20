@extends('layouts.admin')

@section('page-title')
    {{ ucwords($segment).__(' - Journal Entry (Voucher)')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Treasury') }}</li>
    <li class="breadcrumb-item">{{ __('Journal Entry (Voucher)') }}</li>
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
                                                <th>{{ __('VOUCHER NO') }}</th>
                                                <th class="sliced">{{ __('PAYEE') }}</th>
                                                <!-- <th class="sliced">{{ __('REMARKS') }}</th> -->
                                                <th>{{ __('TOTAL PAYABLES') }}</th>
                                                <th>{{ __('TOTAL EWT') }}</th>
                                                <th>{{ __('TOTAL EVAT') }}</th>
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
<script src="{{ asset('js/datatables/treasury-account-voucher.js?v='.filemtime(getcwd().'/js/datatables/treasury-account-voucher.js').'') }}"></script>
<script src="{{ asset('js/forms/treasury-account-voucher.js?v='.filemtime(getcwd().'/js/forms/treasury-account-voucher.js').'') }}"></script>
@endpush