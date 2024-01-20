@extends('layouts.admin')

@section('page-title')
    {{__('Petty Cash - Replenishment')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('For Approvals') }}</li>
    <li class="breadcrumb-item">{{ __('Petty Cash') }}</li>
    <li class="breadcrumb-item">{{ __('Replenishment') }}</li>    
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
                                    <table id="replenishTable" class="display dataTable table w-100 table-striped" aria-describedby="replenishInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('CONTROL NO') }}</th>
                                                <th class="sliced">{{ __('DEPARTMENT') }}</th>
                                                <th class="sliced">{{ __('PARTICULARS') }}</th>
                                                <th>{{ __('TOTAL AMOUNT') }}</th>
                                                <th>{{ __('APPROVED/DISAPPROVED BY') }}</th>
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
    @include('for-approvals.petty-cash.replenishment.create')
    @include('for-approvals.petty-cash.replenishment.disapprove')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/for-approvals-petty-cash-replenishment.js?v='.filemtime(getcwd().'/js/datatables/for-approvals-petty-cash-replenishment.js').'') }}"></script>
@endpush