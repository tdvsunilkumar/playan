@extends('layouts.admin')

@section('page-title')
    {{__('Sub-Major Group')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Accounting') }}</li>
    <li class="breadcrumb-item">{{ __('Chart of Accounts') }}</li>
    <li class="breadcrumb-item">{{ __('Sub-Major Group') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" title="{{__('Create Sub-Major Group')}}" class="btn btn-sm btn-primary add-btn">
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
                                    <table id="submajorAccountGroupTable" class="display dataTable table w-100 table-striped" aria-describedby="majorAccountGroupInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('ACCOUNT GROUP') }}</th>
                                                <th class="sliced">{{ __('MAJOR GROUP') }}</th>
                                                <th>{{ __('CODE') }}</th>
                                                <th class="sliced">{{ __('DESCRIPTION') }}</th>
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
    @include('accounting.chart-of-accounts.submajor-account-groups.create')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/acctg-account-group-submajor.js?v='.filemtime(getcwd().'/js/datatables/acctg-account-group-submajor.js').'') }}"></script>
<script src="{{ asset('js/forms/acctg-account-group-submajor.js?v='.filemtime(getcwd().'/js/forms/acctg-account-group-submajor.js').'') }}"></script>
@endpush