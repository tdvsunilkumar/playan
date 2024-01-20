@extends('layouts.admin')

@section('page-title')
    {{__('Cemetery Application')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Economic & Investment') }}</li>
    <li class="breadcrumb-item">{{ __('Cemetery') }}</li>
    <li class="breadcrumb-item">{{ __('Application') }}</li>
@endsection
@section('action-btn')
    @if ($permission['create'] > 0)
        <div class="float-end">
            <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Create Cemetery Application')}}" class="btn btn-sm btn-primary add-btn">
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
                                    <table id="econCemeteryTable" class="display dataTable table w-100 table-striped" aria-describedby="econCemeteryInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('TRANSACTION') }}</th>
                                                <th>{{ __('REFERENCE NO') }}</th>
                                                <th class="sliced">{{ __('NAME') }}</th>
                                                <th class="sliced">{{ __('ADDRESS') }}</th>
                                                <th>{{ __('TOTAL AMOUNT') }}</th>
                                                <th>{{ __('REMAINING BALANCE') }}</th>
                                                <th>{{ __('OR NO.') }}</th>
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
    @include('economic-and-investment.cemetery.disapprove')
    @include('economic-and-investment.cemetery.create')
    @include('economic-and-investment.cemetery.summary')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
<style>
    div.dataTables_wrapper 
    div.dataTables_filter {
        margin-bottom: 1.5rem;
        margin-left: 1rem;
        float: right !important;
    }
    #econCemeteryPaymentTable {
        margin-top: 0 !important;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/eco-cemetery.js?v='.filemtime(getcwd().'/js/datatables/eco-cemetery.js').'') }}"></script>
<script src="{{ asset('js/forms/eco-cemetery.js?v='.filemtime(getcwd().'/js/forms/eco-cemetery.js').'') }}"></script>
@endpush