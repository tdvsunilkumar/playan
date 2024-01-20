@extends('layouts.admin')

@section('page-title')
    {{__('Budget Proposal')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Finance') }}</li>
    <li class="breadcrumb-item active">{{ __('Budget Proposal') }}</li>
@endsection
@section('action-btn')
    @if ($can_create > 0)
    <div class="float-end">
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Create Budget Proposal')}}" class="btn btn-sm btn-primary add-btn">
            <i class="ti-plus"></i>
        </a>
    </div>
    @endif
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
                                    <table id="budgetTable" class="display dataTable table w-100 table-striped" aria-describedby="budgetInfo">
                                        <thead>
                                            <tr>
                                                <th><div class="form-check"><input class="form-check-input" type="checkbox" value="all"></div></th>
                                                <th>{{ __('BUDGET YEAR') }}</th>
                                                <th class="sliced">{{ __('DEPARTMENT') }}</th>
                                                <!-- <th class="sliced">{{ __('DIVISION') }}</th> -->
                                                <th class="sliced">{{ __('FUND CODE') }}</th>
                                                <th>{{ __('TOTAL BUDGET') }}</th>
                                                <th>{{ __('TOTAL USED') }}</th>
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
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="indexToast" data-bs-autohide="true" class="toast hide bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body">
            Hello, world! This is a toast message.
            </div>
        </div>
    </div>
    <button id="copy-budget-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="copy budget" class="btn btn-lg btn-blue align-items-center btn-circle me-2" title="approve this">
        <i class="flaticon-layers text-white"></i>
    </button>
    @include('finance.budget-proposals.create')
    @include('finance.budget-proposals.copy')
    @include('finance.budget-proposals.add-breakdown')
    @include('finance.budget-proposals.add-breakdown2')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datepicker/bootstrap-datepicker.css?v='.filemtime(getcwd().'/assets/vendors/datepicker/bootstrap-datepicker.css').'') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
<style>
    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        float: right;
        margin-left: 1rem;
        margin-bottom: 1.5rem;
    }
    #datatable-2 table th:nth-child(1) {
        width: 25px !important;
    }
    #copy-budget-btn { 
        position: fixed;
        bottom: -60px;
        right: 20px;
        transition: .15s all ease-in-out;
    }
    #copy-budget-btn.active {
        bottom: 20px;
        transition: .3s all ease-in-out;
    }
    .mb-0 {
        margin-bottom: 0 !important;
    }
    .modal .form-select-sm {
        padding-top: 0.4rem !important;
        padding-bottom: 0.4rem !important;
    }
    #breakdownTable tr.active {
        background: #fffcbe;
    }
    #breakdownTable td {
        border: none !important;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datepicker/bootstrap-datepicker.js?v='.filemtime(getcwd().'/assets/vendors/datepicker/bootstrap-datepicker.js').'') }}"></script>
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/cbo-budget.js?v='.filemtime(getcwd().'/js/datatables/cbo-budget.js').'') }}"></script>
<script src="{{ asset('js/forms/cbo-budget.js?v='.filemtime(getcwd().'/js/forms/cbo-budget.js').'') }}"></script>
@endpush