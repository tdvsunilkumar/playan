@extends('layouts.admin')

@section('page-title')
    {{ ucwords($segment).__(' - Journal Entry (Voucher)')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Treasury') }}</li>
    <li class="breadcrumb-item">{{ __('Journal Entry (Voucher)') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
            <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i></a>
            <a href="#" data-size="lg" data-url="{{ url('/treasury/journal-entries/check-disbursement/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" id="addCitizen" title="{{__('Add Cash Receipt')}}" class="btn btn-sm btn-primary" data-controls-modal="your_div_id" data-backdrop="static" data-keyboard="false">
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
                                    <table id="Jq_datatablelist" class="display dataTable table w-100 table-striped" aria-describedby="voucherInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('DATE') }}</th>
                                                <th>{{ __('VOUCHER NO') }}</th>
                                                <th class="sliced">{{ __('PARTICULARS') }}</th>
                                                <th>{{ __('TRANSACTION TYPE') }}</th>
                                                <th>{{ __('CREDIT') }}</th>
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
@endsection

@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/datatables/treasury-check-disbursment.js?v='.filemtime(getcwd().'/js/datatables/treasury-check-disbursment.js').'') }}"></script>
@endpush