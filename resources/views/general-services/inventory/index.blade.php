@extends('layouts.admin')

@section('page-title')
    {{__('Inventory')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('General Services') }}</li>
    <li class="breadcrumb-item">{{ __('Inventory') }}</li>
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
                                    <table id="itemTable" class="display dataTable table w-100 table-striped" aria-describedby="itemInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th class="sliced">{{ __('GL ACCOUNT') }}</th>
                                                <th class="slcied">{{ __('TYPE') }}</th>
                                                <th class="sliced">{{ __('CATEGORY') }}</th>
                                                <th>{{ __('CODE') }}</th>
                                                <th class="sliced">{{ __('NAME') }}</th>
                                                <th>{{ __('QUANTITY') }}</th>
                                                <th>{{ __('UOM') }}</th>
                                                <th title="weighted & latest cost">{{ __('UNIT COST') }}</th>
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
    @include('general-services.inventory.create')
    @include('general-services.inventory.adjustment')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')>
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/gso-inventory.js?v='.filemtime(getcwd().'/js/datatables/gso-inventory.js').'') }}"></script>
<script src="{{ asset('js/forms/gso-inventory.js?v='.filemtime(getcwd().'/js/forms/gso-inventory.js').'') }}"></script>
@endpush
