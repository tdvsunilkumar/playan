@extends('layouts.admin')

@section('page-title')
    {{__('Departmental Requisition')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('For Approval') }}</li>
    <li class="breadcrumb-item">{{ __('Departmental Requisition') }}</li>
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
                                    <table id="departmentalRequisitionTable" class="display dataTable table w-100 table-striped mt-0" style="margin-top: 0.5rem !important" aria-describedby="departmentalRequisition_info">
                                        <thead>
                                            <tr>
                                                <th>{{ __('CONTROL NO') }}</th>
                                                <th>{{ __('REQUEST TYPE') }}</th>
                                                <th class="sliced">{{ __('DEPARTMENT') }}</th>
                                                <th>{{ __('REQUESTOR') }}</th>
                                                <th class="sliced">{{ __('REMARKS') }}</th>
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
    @include('for-approvals.departmental-requisition.disapprove')
    @include('for-approvals.departmental-requisition.create')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/for-approvals-departmental-requisition.js?v='.filemtime(getcwd().'/js/datatables/for-approvals-departmental-requisition.js').'') }}"></script>
@endpush