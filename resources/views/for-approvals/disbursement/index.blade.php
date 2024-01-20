@extends('layouts.admin')

@section('page-title')
    {{__('Disbursement')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('For Approval') }}</li>
    <li class="breadcrumb-item active">{{ __('Disbursement') }}</li>
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
                                    <table id="paymentsTable" class="display dataTable table w-100 table-striped" aria-describedby="paymentsInfo">
                                        <thead>
                                            <tr>
                                                <th><div class="form-check"><input class="form-check-input" type="checkbox" value="all"></div></th>
                                                <th>{{ __('VOUCHER') }}</th>
                                                <th class="sliced">{{ __('GL ACCOUNT') }}</th>
                                                <th>{{ __('TYPE') }}</th>
                                                <th class="sliced">{{ __('CHEQUE DETAILS') }}</th>
                                                <th class="sliced">{{ __('BANK DETAILS') }}</th>
                                                <th>{{ __('TOTAL AMOUNT') }}</th>
                                                <th>{{ __('APPROVED/DISAPPROVED BY') }}</th>
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
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="indexToast" data-bs-autohide="true" class="toast hide bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body">
            Hello, world! This is a toast message.
            </div>
        </div>
    </div>
    <button id="approved-btn" class="btn btn-lg bg-success align-items-center btn-circle me-2" title="approve this"><i class="ti-thumb-up text-white"></i></button>
    <button id="disapproved-btn" class="btn btn-lg bg-danger align-items-center btn-circle" title="disapprove this"><i class="ti-thumb-down text-white"></i></button>
    @include('for-approvals.disbursement.disapprove')
    @include('for-approvals.disbursement.create')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/for-approvals-account-disbursment.js?v='.filemtime(getcwd().'/js/datatables/for-approvals-account-disbursment.js').'') }}"></script>
@endpush