@extends('layouts.admin')

@section('page-title')
    {{__('Product Line')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Administrative') }}</li>
    <li class="breadcrumb-item">{{ __('General Services') }}</li>
    <li class="breadcrumb-item">{{ __('Product Line') }}</li>
@endsection

@section('action-btn')
    @if ($can_create > 0)
        <div class="float-end">
            <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Create Product Line')}}" class="btn btn-sm btn-primary add-btn">
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
                                    <table id="productLineTable" class="display dataTable table w-100 table-striped" aria-describedby="productLineInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('CODE') }}</th>
                                                <th class="sliced">{{ __('DESCRIPTION') }}</th>
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
    @include('administrative.general-services.product-lines.create')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/gso-product-line.js?v='.filemtime(getcwd().'/js/datatables/gso-product-line.js').'') }}"></script>
<script src="{{ asset('js/forms/gso-product-line.js?v='.filemtime(getcwd().'/js/forms/gso-product-line.js').'') }}"></script>
@endpush