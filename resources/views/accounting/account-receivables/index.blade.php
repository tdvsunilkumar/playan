@extends('layouts.admin')

@section('page-title')
    {{__('Account Receivables')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Accounting') }}</li>
    <li class="breadcrumb-item">{{ __('Account Receivables') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @if ($permission->download > 0)
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Download Receivables')}}" class="btn btn-sm bg-print text-white add-btn">
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
                                    <table id="accountReceivableTable" class="display dataTable table w-100 table-striped" aria-describedby="accountReceivableInfo">
                                        <thead>
                                            <tr>
                                                <th><div class="form-check"><input class="form-check-input" type="checkbox" value="all"></div></th>
                                                <th class="sliced">{{ __('FUND CODE') }}</th>
                                                <th class="sliced">{{ __('GL ACCOUNT') }}</th>
                                                <th class="sliced">{{ __('ITEM DESCRIPTION') }}</th>
                                                <th>{{ __('AMOUNT DUE') }}</th>
                                                <th>{{ __('AMOUNT PAID') }}</th>
                                                <th>{{ __('REMAINING BALANCE') }}</th>
                                                <th>{{ __('DUE DATE') }}</th>
                                                <th>{{ __('STATUS') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="14" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td valign="top" colspan="3"></td>
                                                <td valign="top" colspan="1" class="text-end total-amount-due fw-bold"></td>
                                                <td valign="top" colspan="1" class="text-end total-amount-paid fw-bold"></td>
                                                <td valign="top" colspan="1" class="text-end total-amount-balance text-danger fw-bold"></td>
                                                <td valign="top" colspan="2"></td>
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
<script src="{{ asset('js/datatables/acctg-account-receivable.js?v='.filemtime(getcwd().'/js/datatables/acctg-account-receivable.js').'') }}"></script>
<script src="{{ asset('js/forms/acctg-account-receivable.js?v='.filemtime(getcwd().'/js/forms/acctg-account-receivable.js').'') }}"></script>
@endpush