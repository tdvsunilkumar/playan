<div class="modal form fade" id="petty-cash-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="pettyCashModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'treasury/petty-cash/disbursement', 'class'=>'formDtls needs-validation', 'name' => 'pettyCashForm')) }}
            @csrf
                <div class="modal-header bg-accent pt-4 pb-4">
                    <h5 class="modal-title full-width c-white" id="pettyCashModal">
                        Manage Disbursement
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('voucher_id', 'Voucher No', ['class' => '']) }}
                                {{
                                    Form::select('voucher_id', $vouchers, $value = '', ['id' => 'voucher_id', 'class' => 'form-control select3 fs-3', 'data-placeholder' => 'select a voucher'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('payee_id', 'Payee', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'payee_id', $value = '', 
                                    $attributes = array(
                                        'id' => 'payee_id',
                                        'class' => 'form-control form-control-solid',
                                        'disabled' => 'disabled'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group">
                                {{ Form::label('department_id', 'Department', ['class' => '']) }}
                                {{
                                    Form::select('department_id', $departments, $value = '', ['id' => 'department_id', 'class' => 'form-control select3 fs-3', 'data-placeholder' => 'select a department'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group mb-0">
                                {{ Form::label('particulars', 'Particulars', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::textarea($name = 'particulars', $value = '', 
                                    $attributes = array(
                                        'id' => 'particulars',
                                        'class' => 'form-control form-control-solid',
                                        'rows' => 3
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="total-amount" class="text-secondary w-100 text-end fs-5 fw-bold">TOTAL AMOUNT: &nbsp; <span class="text-danger">0.00</span></label>
                            <h4 class="text-header mt-2 mb-4">Disbursement Details</h4>
                            <div class="table-responsive table-button">
                                <table id="pettyCashLineTable" class="display dataTable table w-100 table-striped" aria-describedby="pettyCashLineInfo" style="margin-top: 1.5rem !important; margin-bottom: 1.5rem !important">
                                    <thead>
                                        <tr>
                                            <th>{{ __('ID') }}</th>
                                            <th>{{ __('ALOB NO') }}</th>
                                            <th>{{ __('AMOUNT') }}</th>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="modalToast" data-bs-autohide="true" class="toast hide bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body text-white">
            Hello, world! This is a toast message.
            </div>
        </div>
    </div>
</div>