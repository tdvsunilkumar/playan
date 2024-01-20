@extends('layouts.admin')

@section('page-title')
    {{__('Collections')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Treasury') }}</li>
    <li class="breadcrumb-item">{{ __('Collections') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @if ($permission['create'] > 0)
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Create Collection')}}" class="btn btn-sm btn-primary add-btn">
            <i class="ti-plus"></i>
        </a>
        @endif
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
                                    <table id="collectionTable" class="display dataTable table w-100 table-striped mb-0" aria-describedby="collectionInfo">
                                        <thead>
                                            <tr>
                                                <th class="sliced">{{ __('FUND CODE') }}</th>
                                                <th>{{ __('TRANSACTION NO.') }}</th>
                                                <th>{{ __('TRANSACTION DATE') }}</th>
                                                <th class="sliced">{{ __('OFFICER') }}</th>
                                                <th>{{ __('AMOUNT') }}</th>
                                                <th>{{ __('LAST MODIFIED') }}</th>
                                                <th>{{ __('STATUS') }}</th>
                                                <th>{{ __('ACTIONS') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="14" class="dataTables_empty">Loading...</td>
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
    @include('treasury.collections.create')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/cto-collection.js?v='.filemtime(getcwd().'/js/datatables/cto-collection.js').'') }}"></script>
<script src="{{ asset('js/forms/cto-collection.js?v='.filemtime(getcwd().'/js/forms/cto-collection.js').'') }}"></script>
@endpush