@extends('layouts.admin')

@section('page-title')
    {{ ucwords(str_replace('-',' ',request()->segment(count(request()->segments())))) }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('General Services') }}</li>
    <li class="breadcrumb-item">{{ __('Obligation Request') }}</li>
    <li class="breadcrumb-item">{{ ucwords(str_replace('-',' ',request()->segment(count(request()->segments())))) }}</li>
@endsection
@section('action-btn')
    @php $restricted = ['procurement', 'replenishment']; @endphp
    @if (!in_array(strtolower(str_replace('-',' ',request()->segment(count(request()->segments())))), $restricted))
        @if(in_array("create", $permissions))
            <div class="float-end">
                <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Create Obligation Request')}}" class="btn btn-sm btn-primary add-btn">
                    <i class="ti-plus"></i>
                </a>
            </div>
        @endif
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
                                    <table id="obligationRequestTable" class="display dataTable table w-100 table-striped" aria-describedby="obligationRequest_info">
                                        <thead>
                                            <tr>
                                                <th>{{ __('CONTROL NO.') }}</th>
                                                <th>{{ __('PR REF NO.') }}</th>
                                                <th class="sliced">{{ __('DEPARTMENT') }}</th>
                                                <th class="sliced">{{ __('REQUESTOR') }}</th>
                                                <th class="sliced">{{ __('PARTICULARS') }}</th>
                                                <th>{{ __('TOTAL AMOUNT') }}</th>
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
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="indexToast" data-bs-autohide="true" class="toast hide bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body">
            Hello, world! This is a toast message.
            </div>
        </div>
    </div>
    @include('finance.obligation-requests.create')
    @include('finance.obligation-requests.view-alob')

    @if(request()->segment(count(request()->segments())) === 'payroll')
        @include('common.secondModal')
    @endif
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/finance-obligation-request.js?v='.filemtime(getcwd().'/js/datatables/finance-obligation-request.js').'') }}"></script>
<script src="{{ asset('js/forms/finance-obligation-request.js?v='.filemtime(getcwd().'/js/forms/finance-obligation-request.js').'') }}"></script>

@if(request()->segment(count(request()->segments())) === 'payroll')
<script src="{{ asset('js/partials/second-modal.js?v='.filemtime(getcwd().'/js/partials/second-modal.js').'') }}"></script>
@endif
@endpush