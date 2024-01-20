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
        <a href="{{ url('accounting/journal-entries/'.$segment) }}" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Back to Manage Voucher')}}" class="btn btn-sm btn-primary add-btn">
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
                            <input class="form-check-input" type="checkbox" name="is_replenish" id="is_replenish">
                            <label class="fs-6 form-check-label" for="is_replenish">replenish JEV?</label>
                        </div>
                    </h5>
                </div>
                <div class="card-body">
                    {{ Form::open(array('url' => 'accounting/journal-entries/'.$segment, 'class'=>'formDtls needs-validation', 'name' => 'voucherForm')) }}
                    @csrf
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('payee_id', 'PAYEE', ['class' => '']) }}
                                {{
                                    Form::select('payee_id', $payees, $value = '', ['id' => 'payee_id', 'class' => 'form-control select3 fs-3', 'data-placeholder' => 'select a payee'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
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
                            <div class="form-group m-form__group required mb-2">
                                {{ Form::label('remarks', 'REMARKS', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::textarea($name = 'remarks', $value = '', 
                                    $attributes = array(
                                        'id' => 'remarks',
                                        'class' => 'form-control form-control-solid fs-4',
                                        'rows' => 4
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

    {{-- employee table start
    <div class="row">
        <div class="col-xl-12">
            <div id="employee-card" class="card table-card">
                <div class="card-header">
                    <h5 class="d-flex">
                        <div class="me-auto">Employee's Details</div>
                        <!-- <div  style="margin-top: -10px; margin-bottom: -10px">
                            @if ($accounting_permission['update'] > 0)
                            <button id="send-employee-btn" class="btn btn-blue me-1" disabled>
                                <span class="d-flex justify-content-center align-items-center">
                                    <i class="la la-angle-double-right align-middle me-1"></i> SEND
                                </span>
                            </button>
                            @endif
                            @if ($accounting_permission['delete'] > 0)
                            <button id="del-employee-btn" class="btn btn-danger me-1" disabled>
                                <span class="d-flex justify-content-center align-items-center">
                                    <i class="ti-trash align-middle me-1"></i> REMOVE
                                </span>
                            </button>
                            @endif
                            @if ($accounting_permission['create'] > 0)
                            <button id="add-employee-btn" class="btn btn-info">
                                <span class="d-flex justify-content-center align-items-center">
                                    <i class="ti-plus align-middle me-1"></i> ADD
                                </span>
                            </button>
                            @endif
                        </div> -->
                    </h5>
                </div>
                <div class="card-body">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="empListTable" class="display dataTable table w-100 table-striped" aria-describedby="employeesInfo">
                                        <thead>
                                            <tr>
                                                <!-- <th><div class="form-check"><input class="form-check-input" type="checkbox" value="all"></div></th> -->
                                                <th class="sliced">{{ __('Employee Name') }}</th>
                                                <th>{{ __('Department') }}</th>
                                                <th>{{ __('Division') }}</th>
                                                <th>{{ __('Salary') }}</th>
                                                <!-- <th class="sliced">{{ __('ITEMS') }}</th> -->
                                                <!-- <th>{{ __('Status') }}</th>
                                                <th>{{ __('UOM') }}</th>
                                                <th>{{ __('AMOUNT') }}</th>
                                                <th>{{ __('TOTAL AMOUNT') }}</th> -->
                                                <th>{{ __('Deductions') }}</th>
                                                <th>{{ __('ACTIONS') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="8" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="hidden">
                                                <th colspan="4" class="text-end">0.00</th>
                                                <th colspan="2" class="text-start">SUB TOTAL</th>
                                            </tr>
                                            <tr>
                                                <th colspan="4" class="text-end pt-0 pb-0 fs-6 total-employees border-0">0.00</th>
                                                <th colspan="2" class="text-start pt-0 pb-0 fs-6 border-0">TOTAL EMPLOYEES</th>
                                            </tr>
                                            <tr>
                                                <th colspan="4" class="text-end pt-0 pb-0 fs-6 total-ewt border-0">0.00</th>
                                                <th colspan="2" class="text-start pt-0 pb-0 fs-6 border-0">TOTAL SALARY</th>
                                            </tr>
                                            <tr>
                                                <th colspan="4" class="text-end pt-0 pb-0 fs-6 total-evat border-0">0.00</th>
                                                <th colspan="2" class="text-start pt-0 pb-0 fs-6 border-0">TOTAL DEDUCTIONS</th>
                                            </tr>
                                            <tr>
                                                <th colspan="4" class="text-end fs-5 pt-0 pb-0 text-danger total-amount">0.00</th>
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
    employee table end--}}

    <div class="row">
        <div class="col-xl-12">
            <div id="payable-card" class="card table-card">
                <div class="card-header">
                    <h5 class="d-flex">
                        <div class="me-auto">Payable's Details</div>
                        <div  style="margin-top: -10px; margin-bottom: -10px">
                            @if ($accounting_permission['update'] > 0)
                            <button id="send-payable-btn" class="btn btn-blue me-1" disabled>
                                <span class="d-flex justify-content-center align-items-center">
                                    <i class="la la-angle-double-right align-middle me-1"></i> SEND
                                </span>
                            </button>
                            @endif
                            @if ($accounting_permission['delete'] > 0)
                            <button id="del-payable-btn" class="btn btn-danger me-1" disabled>
                                <span class="d-flex justify-content-center align-items-center">
                                    <i class="ti-trash align-middle me-1"></i> REMOVE
                                </span>
                            </button>
                            @endif
                            @if ($accounting_permission['create'] > 0)
                            <button id="add-payable-btn" class="btn btn-info">
                                <span class="d-flex justify-content-center align-items-center">
                                    <i class="ti-plus align-middle me-1"></i> ADD
                                </span>
                            </button>
                            @endif
                        </div>
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
                                                <th>{{ __('Amount') }}</th>
                                                <!-- <th>{{ __('Credit Amount') }}</th> -->
                                                <th>{{ __('Responsibility Center') }}</th>
                                                <th>{{ __('STATUS') }}</th>
                                                <th>{{ __('ACTIONS') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="7" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="hidden">
                                                <th colspan="5" class="text-end">0.00</th>
                                                <th colspan="1" class="text-start">SUB TOTAL</th>
                                            </tr>
                                            <tr>
                                                <th colspan="5" class="text-end pt-0 pb-0 fs-6 total-gross border-0" id="total-gross">0.00</th>
                                                <th colspan="1" class="text-start pt-0 pb-0 fs-6 border-0">GROSS SALARY</th>
                                            </tr>
                                            <tr>
                                                <th colspan="5" class="text-end fs-5 pt-0 pb-0  total-deductions" id="total-deductions">0.00</th>
                                                <th colspan="1" class="text-start fs-5 pt-0 pb-0 ">DEDUCTIONS</th>
                                            </tr>
                                            <tr>
                                                <th colspan="5" class="text-danger text-end pt-0 pb-0 fs-6 total-salaries border-0" id="total-salaries">0.00</th>
                                                <th colspan="1" class="text-danger text-start pt-0 pb-0 fs-6 border-0">TOTAL SALARY</th>
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
            <div id="deduction-card" class="card table-card">
                <div class="card-header">
                    <h5 class="d-flex">
                        <div class="me-auto">Deduction's Details</div>
                        <div  style="margin-top: -10px; margin-bottom: -10px">
                            @if ($accounting_permission['update'] > 0)                                 
                            <button id="send-deduction-btn" class="btn btn-blue me-1" disabled>
                                <span class="d-flex justify-content-center align-items-center">
                                    <i class="la la-angle-double-right align-middle me-1"></i> SEND
                                </span>
                            </button>
                            @endif
                            @if ($accounting_permission['delete'] > 0)  
                            <button id="del-deduction-btn" class="btn btn-danger me-1" disabled>
                                <span class="d-flex justify-content-center align-items-center">    
                                    <i class="ti-trash align-middle me-1"></i> REMOVE
                                </span>
                            </button>
                            @endif
                            @if ($accounting_permission['create'] > 0)  
                            <button id="add-deduction-btn" class="btn btn-info" disabled="disabled">
                                <span class="d-flex justify-content-center align-items-center">
                                    <i class="ti-plus align-middle me-1"></i> ADD
                                </span>
                            </button>
                            @endif
                        </div>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="deductionTable" class="display dataTable table w-100 table-striped" aria-describedby="deductionInfo">
                                        <thead>
                                            <tr>
                                                <th><div class="form-check"><input class="form-check-input" type="checkbox" value="all"></div></th>
                                                <th class="sliced">{{ __('GL ACCOUNT') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <!-- <th>{{ __('Credit Amount') }}</th> -->
                                                <th>{{ __('Responsibility Center') }}</th>
                                                <th>{{ __('STATUS') }}</th>
                                                <th>{{ __('ACTIONS') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="7" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="hidden">
                                                <th colspan="1" class="text-end">0.00</th>
                                                <th colspan="1" class="text-end pay-pagibig" id="total-pagibig">0.00</th>
                                                <th colspan="1" class="text-end pay-philhealth" id="total-philhealth">0.00</th>
                                                <th colspan="1" class="text-end pay-bir" id="total-bir">0.00</th>
                                                <th colspan="1" class="text-end pay-gsis" id="total-gsis">0.00</th>
                                                <th colspan="1" class="text-start">SUB TOTAL</th>
                                            </tr>
                                            <tr>
                                                <th colspan="5" class="text-end pt-0 pb-0 fs-6 total-pagibig border-0" >0.00</th>
                                                <th colspan="1" class="text-start pt-0 pb-0 fs-6 border-0">PAG-IBIG</th>
                                            </tr>
                                            <tr>
                                                <th colspan="5" class="text-end pt-0 pb-0 fs-6 total-philhealth border-0">0.00</th>
                                                <th colspan="1" class="text-start pt-0 pb-0 fs-6 border-0">PHILHEALTH</th>
                                            </tr>
                                            <tr>
                                                <th colspan="5" class="text-end pt-0 pb-0 fs-6 total-bir border-0">0.00</th>
                                                <th colspan="1" class="text-start pt-0 pb-0 fs-6 border-0">BIR</th>
                                            </tr>
                                            <tr>
                                                <th colspan="5" class="text-end pt-0 pb-0 fs-6 total-gsis border-0">0.00</th>
                                                <th colspan="1" class="text-start pt-0 pb-0 fs-6 border-0">GSIS</th>
                                            </tr>
                                            <tr>
                                                <th colspan="5" class="text-end fs-5 text-danger total-amount">0.00</th>
                                                <th colspan="1" class="text-start fs-5">TOTAL AMOUNT</th>
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
                        <div class="me-auto">Payment's  Details</div>
                        <div  style="margin-top: -10px; margin-bottom: -10px">
                            @if ($treasury_permission['update'] > 0)                                 
                            <button id="send-payment-btn" class="btn btn-blue me-1" disabled>
                                <span class="d-flex justify-content-center align-items-center">
                                    <i class="la la-angle-double-right align-middle me-1"></i> SEND
                                </span>
                            </button>
                            @endif
                            @if ($treasury_permission['delete'] > 0)  
                            <button id="del-payment-btn" class="btn btn-danger me-1" disabled>
                                <span class="d-flex justify-content-center align-items-center">    
                                    <i class="ti-trash align-middle me-1"></i> REMOVE
                                </span>
                            </button>
                            @endif
                            @if ($treasury_permission['create'] > 0)  
                            <button id="add-payment-btn" class="btn btn-info">
                                <span class="d-flex justify-content-center align-items-center">
                                    <i class="ti-plus align-middle me-1"></i> ADD
                                </span>
                            </button>
                            @endif
                        </div>
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
                                                <th>{{ __('CATEGORY') }}</th>
                                                <th>{{ __('TYPE') }}</th>
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
                                                <td valign="top" colspan="14" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="hidden">
                                                <th colspan="9" class="text-end">0.00</th>
                                                <th colspan="2" class="text-start">SUB TOTAL</th>
                                            </tr>
                                            <tr>
                                                <th colspan="9" class="text-end fs-5 text-danger total-amount">0.00</th>
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
    @include('accounting.journal-entries.payables.add-payables')
    @include('accounting.journal-entries.debit-memo.add-payments')
    @include('accounting.journal-entries.payables.add-date')
    @include('accounting.journal-entries.payables.edit-payables')
    @include('accounting.journal-entries.payables.disapprove-payable')
    @include('accounting.journal-entries.payables.disapprove-payment')
    @include('accounting.journal-entries.payables.disapprove-document')
    @include('common.secondModal')
    @if ($treasury_permission['download'] > 0 || $accounting_permission['download'] > 0)  
        @include('accounting.journal-entries.debit-memo.fab-menu')
    @endif
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/acctg-debit-memo.js?v='.filemtime(getcwd().'/js/datatables/acctg-debit-memo.js').'') }}"></script>
<script src="{{ asset('js/forms/acctg-debit-memo.js?v='.filemtime(getcwd().'/js/forms/acctg-debit-memo.js').'') }}"></script>

<script src="{{ asset('js/partials/second-modal.js?v='.filemtime(getcwd().'/js/partials/second-modal.js').'') }}"></script>
@endpush