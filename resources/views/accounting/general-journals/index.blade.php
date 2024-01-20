@extends('layouts.admin')

@section('page-title')
    {{__('General Journal')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Accounting') }}</li>
    <li class="breadcrumb-item">{{ __('General Journals') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @if ($permission['create'] > 0)
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Create General Journal')}}" class="btn btn-sm btn-primary add-btn">
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
                                    <table id="generalJournalTable" class="display dataTable table w-100 table-striped float-end" aria-describedby="accountPayableInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('TRANSACTION NO.') }}</th>
                                                <th>{{ __('TRANSACTION DATE') }}</th>
                                                <th>{{ __('FIXED ASSET NO.') }}</th>
                                                <th class="sliced">{{ __('PARTICULARS') }}</th>
                                                <th>{{ __('TOTAL DEBIT') }}</th>
                                                <th>{{ __('TOTAL CREDIT') }}</th>
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
    @include('accounting.general-journals.create')
    @include('accounting.general-journals.entry')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
<style>
    div.dataTables_wrapper div.dataTables_filter {
        float: right;
        margin-left: 1rem;
    }
    div.dataTables_wrapper div.dataTables_paginate {
        float: right;
        display: block;
        clear: both;
        margin-top: -25px !important;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/acctg-general-journal.js?v='.filemtime(getcwd().'/js/datatables/acctg-general-journal.js').'') }}"></script>
<script src="{{ asset('js/forms/acctg-general-journal.js?v='.filemtime(getcwd().'/js/forms/acctg-general-journal.js').'') }}"></script>
@endpush