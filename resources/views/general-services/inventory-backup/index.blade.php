@extends('layouts.admin')

@section('page-title')
    {{__('Inventory')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('General Services') }}</li>
    <li class="breadcrumb-item">{{ __('Inventory') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" title="{{__('New')}}" class="btn btn-sm btn-primary add-btn disabled" id="issue_add">
            Issue
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
                                    <table id="inventoryTable" class="display dataTable table w-100 table-striped" aria-describedby="groupMenuInfo">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" class="select" id="selectAll"></th>
                                                <th>{{ __('No.') }}</th>
                                                <th>{{ __('Category') }}</th>
                                                <th>{{ __('Item Code') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th class="sliced">{{ __('Qty') }}</th>
                                                <th>{{ __('Unit') }}</th>
                                                <th>{{ __('Action') }}</th>
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
    @include('general-services.inventory.item_issue')
    @include('general-services.inventory.create')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/gsoInventory.js?v='.filemtime(getcwd().'/js/datatables/gsoInventory.js').'') }}"></script>
<script src="{{ asset('js/forms/gsoInventory.js?v='.filemtime(getcwd().'/js/datatables/gsoInventory.js').'') }}"></script>
@endpush