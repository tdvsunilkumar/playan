@extends('layouts.admin')

@section('page-title')
    {{__('Departmental Requisition')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('General Services') }}</li>
    <li class="breadcrumb-item">{{ __('Departmental Requisition') }}</li>
@endsection

@section('action-btn')
    @if ($can_create > 0)
        <div class="float-end">
            <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Create Departmental Request')}}" class="btn btn-sm btn-primary add-btn">
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
                                    <table id="departmentalRequisitionTable" class="display dataTable table w-100 table-striped" aria-describedby="departmentalRequisition_info">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('CONTROL NO.') }}</th>
                                                <th>{{ __('REQUEST TYPE') }}</th>
                                                <th class="sliced">{{ __('DEPARTMENT') }}</th>
                                                <th>{{ __('REQUESTOR') }}</th>
                                                <!-- <th class="sliced">{{ __('REMARKS') }}</th> -->
                                                <th>{{ __('TOTAL AMOUNT') }}</th>
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
            <div class="toast-body text-white">
            Hello, world! This is a toast message.
            </div>
        </div>
    </div>
    @include('general-services.departmental-requisitions.disapprove')
    @include('general-services.departmental-requisitions.create')
    @include('general-services.departmental-requisitions.tracking')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/selectpicker/bootstrap-select.min.css?v='.filemtime(getcwd().'/assets/vendors/selectpicker/bootstrap-select.min.css').'') }}"/>
<style>
    #tracking-modal .modal-body {
        padding-top: 0px;
        padding-bottom: 0px;
    }
    #tracking-modal ul {
        margin-right: 0;
        padding: 10px 20px;
        margin-bottom: 0px;
    }
    #tracking-modal ul li {
        list-style: none;
        padding-top: 10px;
        padding-bottom: 10px;
    }
    #tracking-modal ul li:nth-of-type(2n+1) {
        background-color: rgba(81, 69, 157, 0.03);
    }
    #tracking-modal .w-10 {
        float: left;
        width: 20px !important;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('assets/vendors/selectpicker/bootstrap-select.min.js?v='.filemtime(getcwd().'/assets/vendors/selectpicker/bootstrap-select.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/gso-departmental-requisition.js?v='.filemtime(getcwd().'/js/datatables/gso-departmental-requisition.js').'') }}"></script>
<script src="{{ asset('js/forms/gso-departmental-requisition.js?v='.filemtime(getcwd().'/js/forms/gso-departmental-requisition.js').'') }}"></script>
@endpush