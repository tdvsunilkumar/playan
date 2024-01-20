@extends('layouts.admin')

@section('page-title')
    {{__('Waste Materials')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('General Services') }}</li>
    <li class="breadcrumb-item">{{ __('Waste Materials') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @if ($permission['download'] > 0)
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Download Waste Materials')}}" class="btn btn-sm bg-print text-white add-btn">
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
                                    <table id="wasteMaterialTable" class="display dataTable table w-100 table-striped" aria-describedby="wasteMaterialInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('#') }}</th>
                                                <th>{{ __('QUANTITY') }}</th>
                                                <th>{{ __('UOM') }}</th>
                                                <th class="sliced">{{ __('ITEM DESCRIPTION') }}</th>
                                                <th class="sliced">{{ __('SUPPLIER') }}</th>
                                                <th>{{ __('PO DETAILS') }}</th>
                                                <th>{{ __('OR NO') }}</th>
                                                <th>{{ __('AMOUNT') }}</th>
                                                <th>{{ __('TOTAL AMOUNT') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="9" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td valign="top" colspan="7"></td>
                                                <td valign="top" colspan="1" class="text-end unit-cost fw-bold"></td>
                                                <td valign="top" colspan="1" class="text-end total-cost text-danger fw-bold"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/gso-waste-material.js?v='.filemtime(getcwd().'/js/datatables/gso-waste-material.js').'') }}"></script>
<script src="{{ asset('js/forms/gso-waste-material.js?v='.filemtime(getcwd().'/js/forms/gso-waste-material.js').'') }}"></script>
@endpush