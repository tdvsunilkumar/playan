@extends('layouts.admin')

@section('page-title')
    {{__('Procurement Mode')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Administrative') }}</li>
    <li class="breadcrumb-item">{{ __('BAC') }}</li>
    <li class="breadcrumb-item active">{{ __('Procurement Mode') }}</li>
@endsection
@section('action-btn')
    @if(in_array("create", $permissions))
        <div class="float-end">
            <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Create Procurement Mode')}}" class="btn btn-sm btn-primary add-btn">
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
                                    <table id="procurementModeTable" class="display dataTable table w-100 table-striped" aria-describedby="procurementModeInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('CODE') }}</th>
                                                <th class="sliced">{{ __('DESCRIPTION') }}</th>
                                                <th>{{ __('MINIMUM AMOUNT') }}</th>
                                                <th>{{ __('MAXIMUM AMOUNT') }}</th>
                                                <th>{{ __('REMARKS') }}</th>
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
    @include('administrative.bac.procurement-modes.create')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/admin-bac-procurement-mode.js?v='.filemtime(getcwd().'/js/datatables/admin-bac-procurement-mode.js').'') }}"></script>
<script src="{{ asset('js/forms/admin-bac-procurement-mode.js?v='.filemtime(getcwd().'/js/forms/admin-bac-procurement-mode.js').'') }}"></script>
@endpush