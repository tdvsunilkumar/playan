<div class="modal form fade" id="account-payable-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="accountPayableModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'for-approvals/account-payables', 'class'=>'formDtls needs-validation', 'name' => 'accountPayableForm')) }}
            @csrf
                <div class="modal-header bg-accent p-0 p-4">
                    <h5 class="modal-title full-width c-white" id="accountPayableModal">
                        Manage Account Payable
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group disabled">
                                {{ Form::label('trans_no', 'Transaction No', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'trans_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'trans_no',
                                        'class' => 'require form-control form-control-solid strong'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('trans_type', 'Transaction Type', ['class' => '']) }}
                                {{
                                    Form::select('trans_type', $trans_types, $value = '', ['id' => 'trans_type', 'class' => 'require form-control select3', 'data-placeholder' => 'select a tranation type'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('vat_type', 'VAT Type', ['class' => '']) }}
                                {{
                                    Form::select('vat_type', $vat_types, $value = '', ['id' => 'vat_type', 'class' => 'form-control select3', 'data-placeholder' => 'select a vat type'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group disabled">
                                {{ Form::label('due_date', 'Due Date', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'due_date', $value = '', 
                                    $attributes = array(
                                        'id' => 'due_date',
                                        'class' => 'require form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('ewt_id', 'EWT', ['class' => '']) }}
                                {{
                                    Form::select('ewt_id', $ewts, $value = '', ['id' => 'ewt_id', 'class' => 'form-control select3', 'data-placeholder' => 'select an ewt'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('evat_id', 'EVAT', ['class' => '']) }}
                                {{
                                    Form::select('evat_id', $evats, $value = '', ['id' => 'evat_id', 'class' => 'form-control select3 disable', 'data-placeholder' => 'select an evat', 'disabled' => 'disabled'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group disabled">
                                {{ Form::label('items', 'Items', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'items', $value = '', 
                                    $attributes = array(
                                        'id' => 'items',
                                        'class' => 'require form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('gl_account_id', 'GL Account', ['class' => '']) }}
                                {{
                                    Form::select('gl_account_id', $gl_accounts, $value = '', ['id' => 'gl_account_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a gl account'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group disabled">
                                {{ Form::label('quantity', 'Quantity', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'quantity', $value = '', 
                                    $attributes = array(
                                        'id' => 'quantity',
                                        'class' => 'require form-control form-control-solid numeric-doubles'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('uom_id', 'Unit Of Measurement', ['class' => '']) }}
                                {{
                                    Form::select('uom_id', $uoms, $value = '', ['id' => 'uom_id', 'class' => 'require form-control select3', 'data-placeholder' => 'select a uom'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group disabled">
                                {{ Form::label('amount', 'Amount', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'amount', $value = '', 
                                    $attributes = array(
                                        'id' => 'amount',
                                        'class' => 'require form-control form-control-solid numeric-doubles'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group disabled">
                                {{ Form::label('total_amount', 'Total Amount', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'total_amount', $value = '', 
                                    $attributes = array(
                                        'id' => 'total_amount',
                                        'class' => 'require form-control form-control-solid strong text-danger numeric-doubles disable',
                                        'disabled' => 'disabled'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group mb-1">
                                {{ Form::label('remarks', 'Remarks', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::textarea($name = 'remarks', $value = '', 
                                    $attributes = array(
                                        'id' => 'remarks',
                                        'class' => 'form-control form-control-solid',
                                        'rows' => 3
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
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
</div>