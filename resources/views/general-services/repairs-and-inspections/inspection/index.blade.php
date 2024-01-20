@extends('layouts.admin')

@section('page-title')
    {{__('Inspection')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('General Services') }}</li>
    <li class="breadcrumb-item">{{ __('Repairs And Inspections') }}</li>
    <li class="breadcrumb-item">{{ __('Inspection') }}</li>
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
                                    <table id="repairTable" class="display dataTable table w-100 table-striped" aria-describedby="repairInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('REP NO.') }}</th>
                                                <th>{{ __('FA NO.') }}</th>
                                                <th class="sliced">{{ __('REQUESTED BY') }}</th>
                                                <th>{{ __('DATE REQUESTED') }}</th>
                                                <th class="sliced">{{ __('ISSUES') }}</th>
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
    @include('general-services.repairs-and-inspections.inspection.create')
    @include('general-services.repairs-and-inspections.inspection.add-item')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/gso-repair-inspection.js?v='.filemtime(getcwd().'/js/datatables/gso-repair-inspection.js').'') }}"></script>
<script src="{{ asset('js/forms/gso-repair-inspection.js?v='.filemtime(getcwd().'/js/forms/gso-repair-inspection.js').'') }}"></script>
@endpush