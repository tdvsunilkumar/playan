@extends('layouts.admin')

@section('page-title')
    {{__('User Account')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Components') }}</li>
    <li class="breadcrumb-item">{{ __('Users') }}</li>
    <li class="breadcrumb-item active">{{ __('Account') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Create User Account')}}" class="btn btn-sm btn-primary add-btn">
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
                                    <table id="userAccountTable" class="display dataTable table w-100 table-striped" aria-describedby="permissionInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('NAME') }}</th>
                                                <th>{{ __('USERNAME') }}</th>
                                                <th>{{ __('ROLE') }}</th>
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
    @include('components.users.accounts.create')
    @include('components.users.accounts.dashboardPermission')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
<style>
    #user-account-modal .modal-body {
        overflow-y: scroll;
        max-height: 80vh;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/component-user-account.js?v='.filemtime(getcwd().'/js/datatables/component-user-account.js').'') }}"></script>
<script src="{{ asset('js/forms/component-user-account.js?v='.filemtime(getcwd().'/js/forms/component-user-account.js').'') }}"></script>
@endpush