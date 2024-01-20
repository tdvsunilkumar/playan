@extends('layouts.admin')

@section('page-title')
    {{__('Approval Settings')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Components') }}</li>
    <li class="breadcrumb-item">{{ __('Approval Settings') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Create Approval Setting')}}" class="btn btn-sm btn-primary add-btn">
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
                                    <table id="approvalSettingTable" class="display dataTable table w-100 table-striped" aria-describedby="approvalSettingInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('GROUP') }}</th>
                                                <th>{{ __('MODULE') }}</th>
                                                <th>{{ __('SUB MODULE') }}</th>
                                                <th>{{ __('LEVELS') }}</th>
                                                <th class="sliced">{{ __('REMARKS') }}</th>
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
    @include('components.approval-settings.create')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
<style>
    #approval-setting-modal .modal-body {
        overflow-y: scroll;
        max-height: 80vh;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/component-approval-setting.js?v='.filemtime(getcwd().'/js/datatables/component-approval-setting.js').'') }}"></script>
<script src="{{ asset('js/forms/component-approval-setting.js?v='.filemtime(getcwd().'/js/forms/component-approval-setting.js').'') }}"></script>
@endpush