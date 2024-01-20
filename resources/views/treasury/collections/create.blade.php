<div class="modal form fade" id="collection-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="accountPayableModal">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'treasury/collections', 'class'=>'formDtls needs-validation', 'name' => 'collectionForm')) }}
            @csrf
                <div class="modal-header bg-accent p-0 p-4">
                    <h5 class="modal-title full-width c-white" id="collectionModal">
                        Manage Collections
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- LEFT COLUMN DETAILS START -->
                        <div class="col-md-5 border-right space-right">
                            <h4 class="text-header">Collection Information</h4>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('transaction_no', 'Transaction No.', ['class' => 'fs-6 fw-bold']) }}
                                        {{ 
                                            Form::text($name = 'transaction_no', $value = '', 
                                            $attributes = array(
                                                'class' => 'form-control form-control-solid strong disable',
                                                'disabled' => 'disabled'
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-form__group required">
                                        {{ Form::label('transaction_date', 'Transaction Date', ['class' => 'fs-6 fw-bold']) }}
                                        {{ 
                                            Form::date($name = 'transaction_date', $value = '', 
                                            $attributes = array(
                                                'class' => 'form-control form-control-solid'
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group m-form__group required">
                                        {{ Form::label('fund_code_id', 'Fund Code', ['class' => '']) }}
                                        {{
                                            Form::select('fund_code_id', $fund_codes, $value = '', ['id' => 'fund_code_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a fund code'])
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-form__group required">
                                        {{ Form::label('officer_id', 'Officer', ['class' => '']) }}
                                        {{
                                            Form::select('officer_id', $officers, $value = '', ['id' => 'officer_id', 'class' => 'form-control select3', 'data-placeholder' => 'select an officer'])
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table id="denomination" class="w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Denominations</th>
                                                    <th class="text-center">No. of Pieces</th>
                                                    <th class="text-center">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 space-left space-right">
                            <h4 class="text-header mb-3">Transaction Information</h4>
                            <div id="datatable-2" class="dataTables_wrapper">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="transTable" class="display dataTable table w-100 table-striped" aria-describedby="transInfo">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('DATE') }}</th>
                                                        <th>{{ __('OR NO.') }}</th>
                                                        <th class="sliced">{{ __('TAXPAYER NAME') }}</th>
                                                        <th>{{ __('FORM CODE') }}</th>
                                                        <th>{{ __('AMOUNT') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="odd">
                                                        <td valign="top" colspan="7" class="dataTables_empty">Loading...</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="text-header mb-3 mt-3">Official Receipt Information</h4>
                            <div id="datatable-2" class="dataTables_wrapper">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="receiptTable" class="display dataTable table w-100 table-striped" aria-describedby="receiptInfo">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Form No.') }}</th>
                                                        <th>{{ __('OR Dept.') }}</th>
                                                        <th>{{ __('FROM') }}</th>
                                                        <th>{{ __('TO') }}</th>
                                                        <th>{{ __('TOTAL AMOUNT') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="odd">
                                                        <td valign="top" colspan="7" class="dataTables_empty">Loading...</td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="4" class="text-end fs-5">{{ __('TOTAL AMOUNT') }}</th>
                                                        <th colspan="1" class="text-end text-danger fs-5 total-transaction">{{ __('0.00') }}</th>
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
                <div class="modal-footer d-flex">
                    <div class="col-sm-5 me-auto">
                        <h5 class="w-100 text-left">
                            TOTAL AMOUNT
                            <span class="float-end text-center text-danger col-sm-4 total-amount" style="margin-right: 3.5%;">0.00</span>
                        </h5>
                    </div>
                    <div class="pe-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn print-btn btn-blue ms-1 hidden"><i class="la la-print align-middle"></i> Print</button>
                        <button type="button" class="btn send-btn btn-blue ms-1"><i class="la la-send align-middle"></i> Send</button>
                        <button type="button" class="btn submit-btn btn-primary ms-1"><i class="la la-save align-middle"></i> Save Changes</button>
                    </div>
                </div>
                <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                    <div id="modalToast" data-bs-autohide="true" class="toast hide bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-body text-white">
                        Hello, world! This is a toast message.
                        </div>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>