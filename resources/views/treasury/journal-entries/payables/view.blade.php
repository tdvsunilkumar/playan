@extends('layouts.admin')

@section('page-title')
    {{ ucwords($segment).__(' - Journal Entry (Voucher)')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Accounting') }}</li>
    <li class="breadcrumb-item">{{ __('Journal Entry (Voucher)') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="{{ url('treasury/journal-entries/'.$segment) }}" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Back to Manage Voucher')}}" class="btn btn-sm btn-primary add-btn">
            <i class="ti-angle-double-left"></i> Back
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div id="voucher-card" class="card table-card">
                <div class="card-header">
                    <h5 class="w-100">Journal Entry Voucher's Details 
                        <div class="form-check form-switch float-end">
                            <input class="form-check-input" type="checkbox" name="is_replenish" id="is_replenish" disabled="disabled">
                            <label class="fs-6 form-check-label" for="is_replenish">replenish JEV?</label>
                        </div>
                    </h5>
                </div>
                <div class="card-body">
                    {{ Form::open(array('url' => 'accounting/journal-entries/payables', 'class'=>'formDtls needs-validation', 'name' => 'voucherForm')) }}
                    @csrf
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('payee_id', 'PAYEE', ['class' => '']) }}
                                {{
                                    Form::select('payee_id', $payees, $value = '', ['id' => 'payee_id', 'class' => 'form-control select3 fs-3', 'data-placeholder' => 'select a payee'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('fund_code_id', 'FUND CODE', ['class' => '']) }}
                                {{
                                    Form::select('fund_code_id', $fund_codes, $value = '', ['id' => 'fund_code_id', 'class' => 'form-control select3 fs-3', 'data-placeholder' => 'select a fund code'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('voucher_no', 'JEV NO', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'voucher_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'voucher_no',
                                        'class' => 'form-control form-control-solid form-control-lg fs-3',
                                        'disabled' => 'disabled'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group mb-2">
                                {{ Form::label('remarks', 'REMARKS', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::textarea($name = 'remarks', $value = '', 
                                    $attributes = array(
                                        'id' => 'remarks',
                                        'class' => 'form-control form-control-solid fs-4',
                                        'rows' => 4,
                                        'disabled' => 'disabled'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div id="payable-card" class="card table-card">
                <div class="card-header">
                    <h5 class="d-flex">
                        <div class="me-auto">Payable's Details</div>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="payablesTable" class="display dataTable table w-100 table-striped" aria-describedby="payablesInfo">
                                        <thead>
                                            <tr>
                                                <th><div class="form-check"><input class="form-check-input" type="checkbox" value="all"></div></th>
                                                <th class="sliced">{{ __('GL ACCOUNT') }}</th>
                                                <th>{{ __('VAT TYPE') }}</th>
                                                <th>{{ __('EWT') }}</th>
                                                <th>{{ __('EVAT') }}</th>
                                                <th class="sliced">{{ __('ITEMS') }}</th>
                                                <th>{{ __('QUANTITY') }}</th>
                                                <th>{{ __('UOM') }}</th>
                                                <th>{{ __('AMOUNT') }}</th>
                                                <th>{{ __('TOTAL AMOUNT') }}</th>
                                                <th>{{ __('STATUS') }}</th>
                                                <th>{{ __('ACTIONS') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="14" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="hidden">
                                                <th colspan="10" class="text-end">0.00</th>
                                                <th colspan="2" class="text-start">SUB TOTAL</th>
                                            </tr>
                                            <tr>
                                                <th colspan="10" class="text-end pt-0 pb-0 fs-6 total-payables border-0">0.00</th>
                                                <th colspan="2" class="text-start pt-0 pb-0 fs-6 border-0">TOTAL PAYABLES</th>
                                            </tr>
                                            <tr>
                                                <th colspan="10" class="text-end pt-0 pb-0 fs-6 total-ewt border-0">0.00</th>
                                                <th colspan="2" class="text-start pt-0 pb-0 fs-6 border-0">EWT AMOUNT</th>
                                            </tr>
                                            <tr>
                                                <th colspan="10" class="text-end pt-0 pb-0 fs-6 total-evat border-0">0.00</th>
                                                <th colspan="2" class="text-start pt-0 pb-0 fs-6 border-0">EVAT AMOUNT</th>
                                            </tr>
                                            <tr>
                                                <th colspan="10" class="text-end fs-5 pt-0 pb-0 text-danger total-amount">0.00</th>
                                                <th colspan="2" class="text-start fs-5 pt-0 pb-0 ">TOTAL AMOUNT</th>
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

    <div class="row">
        <div class="col-xl-12">
            <div id="payment-card" class="card table-card">
                <div class="card-header">
                    <h5 class="d-flex">
                        <div class="me-auto">Payment's Details</div>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="paymentsTable" class="display dataTable table w-100 table-striped" aria-describedby="paymentsInfo">
                                        <thead>
                                            <tr>
                                                <th><div class="form-check"><input class="form-check-input" type="checkbox" value="all"></div></th>
                                                <th class="sliced">{{ __('GL ACCOUNT') }}</th>
                                                <th>{{ __('CATEGORY (TYPE)') }}</th>
                                                <!-- <th>{{ __('TYPE') }}</th> -->
                                                <th class="sliced">{{ __('CHEQUE DETAILS') }}</th>
                                                <th class="sliced">{{ __('BANK NAME') }}</th>
                                                <th class="sliced">{{ __('ACCOUNT DETAILS') }}</th>
                                                <th>{{ __('PAYMENT DATE') }}</th>
                                                <th>{{ __('TOTAL AMOUNT') }}</th>
                                                <th>{{ __('STATUS') }}</th>
                                                <th>{{ __('ACTIONS') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="10" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="hidden">
                                                <th colspan="8" class="text-end">0.00</th>
                                                <th colspan="2" class="text-start">SUB TOTAL</th>
                                            </tr>
                                            <tr>
                                                <th colspan="8" class="text-end fs-5 text-danger total-amount">0.00</th>
                                                <th colspan="2" class="text-start fs-5">TOTAL AMOUNT</th>
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
    @include('treasury.journal-entries.payables.add-payables')
    @include('treasury.journal-entries.payables.add-payments')
    @include('treasury.journal-entries.payables.add-bank-payments')  
    @include('treasury.journal-entries.payables.add-date')
    @include('treasury.journal-entries.payables.edit-payables')
    @include('treasury.journal-entries.payables.disapprove-payable')
    @include('treasury.journal-entries.payables.disapprove-payment')
    @include('treasury.journal-entries.payables.disapprove-document')
    @if ($accounting_permission['download'] > 0)  
    @include('treasury.journal-entries.payables.fab-menu')
    @endif
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/treasury-account-voucher.js?v='.filemtime(getcwd().'/js/datatables/treasury-account-voucher.js').'') }}"></script>
<script src="{{ asset('js/forms/treasury-account-voucher.js?v='.filemtime(getcwd().'/js/forms/treasury-account-voucher.js').'') }}"></script>
@endpush