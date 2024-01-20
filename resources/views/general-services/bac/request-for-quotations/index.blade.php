@extends('layouts.admin')

@section('page-title')
    {{__('Request For Quotation')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('General Services') }}</li>
    <li class="breadcrumb-item">{{ __('BAC') }}</li>
    <li class="breadcrumb-item">{{ __('Request For Quotation') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Create RFQ')}}" class="btn btn-sm btn-primary add-btn">
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
                                    <table id="rfqTable" class="display dataTable table w-100 table-striped" aria-describedby="rfqInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('CONTROL NO.') }}</th>
                                                <th class="sliced">{{ __('FUND CODE') }}</th>
                                                <th class="sliced">{{ __('PURCHASE TYPE') }}</th>
                                                <th class="sliced">{{ __('PROJECT NAME') }}</th>
                                                <th class="sliced">{{ __('AGENCIES') }}</th>
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
    @include('general-services.bac.request-for-quotations.create')
    @include('general-services.bac.request-for-quotations.add-purchase-request')
    @include('general-services.bac.request-for-quotations.add-supplier')
    @include('general-services.bac.request-for-quotations.canvass')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
<style>
    .dataTables_length {
        float: left;
    }
    .toolbar-1 button, .toolbar-2 button, .toolbar-3 button {
        padding: 2.5px 10px !important;
    }
    #preview-btn {
        margin-left: 5px;
        padding: 2.5px 10px; 
        padding: 3.75px 10px;
        color: #fff;
        border-radius: 0.25rem;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/bac-request-for-quotation.js?v='.filemtime(getcwd().'/js/datatables/bac-request-for-quotation.js').'') }}"></script>
<script src="{{ asset('js/forms/bac-request-for-quotation.js?v='.filemtime(getcwd().'/js/forms/bac-request-for-quotation.js').'') }}"></script>
@endpush