@extends('layouts.admin')

@section('page-title')
    {{__('Issuance Requestor')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('General Services') }}</li>
    <li class="breadcrumb-item">{{ __('Issuance') }}</li>
    <li class="breadcrumb-item">{{ __('Requestor') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" title="{{__('New')}}" class="btn btn-sm btn-primary add-btn">
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
                                    <table id="issuanceRequestTable" class="display dataTable table w-100 table-striped" aria-describedby="groupMenuInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Control No.') }}</th>
                                                <th>{{ __('Issuance Date') }}</th>
                                                <th>{{ __('Item Name') }}</th>
                                                <th class="sliced">{{ __('Item Desc') }}</th>
                                                <th>{{ __('UOM') }}</th>
                                                <th class="sliced">{{ __('Qty') }}</th>
                                                <th>{{ __('Command') }}</th>
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
    @include('general-services.issuance.requestor.create')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/gso-issuance-requestor.js?v='.filemtime(getcwd().'/js/datatables/gso-issuance-requestor.js').'') }}"></script>
<script src="{{ asset('js/forms/gso-issuance-requestor.js?v='.filemtime(getcwd().'/js/forms/gso-issuance-requestor.js').'') }}"></script>
@endpush