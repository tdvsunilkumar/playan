@extends('layouts.admin')

@section('page-title')
    {{__('Item Canvass')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Reports') }}</li>
    <li class="breadcrumb-item">{{ __('General Services') }}</li>
    <li class="breadcrumb-item">{{ __('Item Canvass') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @if ($permission['download'] > 0)
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Download Canvass Sheet')}}" class="btn btn-sm bg-print text-white add-btn">
            <i class="ti-files"></i>
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
                                    <table id="itemCanvassTable" class="display dataTable table w-100 table-striped" aria-describedby="itemCanvassInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th class="sliced">{{ __('SUPPLIER') }}</th>
                                                <th class="sliced">{{ __('BRANCH') }}</th>
                                                <th class="sliced">{{ __('GL ACCOUNT') }}</th>
                                                <th class="sliced">{{ __('ITEMS') }}</th>
                                                <th>{{ __('BRAND/MODEL') }}</th>
                                                <th>{{ __('QUANTITY') }}</th>
                                                <th>{{ __('UNIT COST') }}</th>
                                                <th>{{ __('TOTAL COST') }}</th>
                                                <th>{{ __('LAST MODIFIED') }}</th>
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
    @include('components.menus.groups.create')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/report-item-canvass.js?v='.filemtime(getcwd().'/js/datatables/report-item-canvass.js').'') }}"></script>
@endpush