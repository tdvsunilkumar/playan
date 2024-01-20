@extends('layouts.admin')

@section('page-title')
    {{__('Fixed Assets')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Accounting') }}</li>
    <li class="breadcrumb-item">{{ __('Fixed Assets') }}
    </li>
@endsection

@if ($permission->create > 0)
    @section('action-btn')
        <div class="float-end">
            <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Create Fixed Asset')}}" class="btn btn-sm btn-primary add-btn">
                <i class="ti-plus"></i>
            </a>
        </div>
    @endsection
@endif

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card table-card">
                <div class="card-body">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="fixedAssetTable" class="display dataTable table w-100 table-striped" aria-describedby="fixedAssetInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('FA NO.') }}</th>
                                                <th>{{ __('PAR NO.') }}</th>
                                                <th>{{ __('CATEGORY') }}</th>
                                                <th>{{ __('TYPE') }}</th>
                                                <th class="sliced">{{ __('GL ACCOUNT') }}</th>
                                                <th class="sliced">{{ __('ITEM') }}</th>
                                                <th>{{ __('UNIT COST') }}</th>
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
    @include('accounting.fixed-assets.create')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/acctg-fixed-asset.js?v='.filemtime(getcwd().'/js/datatables/acctg-fixed-asset.js').'') }}"></script>
<script src="{{ asset('js/forms/acctg-fixed-asset.js?v='.filemtime(getcwd().'/js/forms/acctg-fixed-asset.js').'') }}"></script>
@endpush