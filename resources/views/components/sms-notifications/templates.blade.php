@extends('layouts.admin')

@section('page-title')
    {{__('Templates')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Components') }}</li>
    <li class="breadcrumb-item">{{ __('SMS Notifications') }}</li>
    <li class="breadcrumb-item">{{ __('Templates') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Create Template')}}" class="btn btn-sm btn-primary add-btn">
            <i class="ti-plus"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card noflow table-card">
                <div class="card-body">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="templateTable" class="display dataTable table w-100 table-striped" aria-describedby="bankInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th class="sliced">{{ __('GROUPS') }}</th>
                                                <th>{{ __('APPLICATION') }}</th>
                                                <th>{{ __('TYPE') }}</th>
                                                <th class="sliced">{{ __('TEMPLATES') }}</th>
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
    @include('components.sms-notifications.new-template')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
<style>
#templateTable_filter {
    float: right !important;
}
#datatable-2 .dataTables_filter input[type="search"] {
    height: 37px !important;
    margin-bottom: 1.5rem;
}
.line-30 {
    line-height: 34px !important;
    margin-right: 5px;
}
.toolbar-2 #parent_groups_id select {
    width: 300px !important;
}
.dataTables_wrapper {
    margin-left: 5px !important;
}
.select3-container--default .select3-selection--single .select3-selection__arrow {
    height: 32px !important;
}
div:not(.modal) .select3-results__option {
    padding: 5px 5px !important;
    line-height: 20px !important;
}

</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/sms-template.js?v='.filemtime(getcwd().'/js/datatables/sms-template.js').'') }}"></script>
<script src="{{ asset('js/forms/sms-template.js?v='.filemtime(getcwd().'/js/forms/sms-template.js').'') }}"></script>
@endpush