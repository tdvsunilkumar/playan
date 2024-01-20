<div class="modal form fade" id="add-payment-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="addPaymentLabel" tabindex="-1">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
            {{ Form::open(array('url' => 'accounting/journal-entries/payables/payments', 'class'=>'formDtls needs-validation', 'name' => 'paymentsForm')) }}
            @csrf
                <div class="modal-header bg-accent p-0 p-4">
                    <h5 class="modal-title full-width c-white" id="paymentsModal">
                        Manage Payment
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('disburse_type_id', 'Payment for', ['class' => 'required fs-6 fw-bold']) }}
                                {{
                                    Form::select('disburse_type_id', $disburse_type, $value = '', ['id' => 'disburse_type_id', 'class' => 'form-control select3 fs-3', 'data-placeholder' => 'select a payment'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('disburse_no', 'Disbursement No', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'disburse_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'disburse_no',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('payment_type_id', 'Payment Type', ['class' => '']) }}
                                {{
                                    Form::select('payment_type_id', $payment_types, $value = '', ['id' => 'payment_type_id', 'class' => 'form-control select3 fs-3', 'data-placeholder' => 'select a payment type'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('sl_account_id', 'GL Account', ['class' => '']) }}
                                <select name="sl_account_id" id="sl_account_id" class="form-control select3 fs-3" data-placeholder= "select a gl account">
                                    <option value=""></option>
                                    @foreach ($sl_accounts as $sl_account)
                                        <optgroup label="{{ $sl_account->text }}">
                                            @foreach ($sl_account->children as $child)
                                                @if ($child->hidden > 0)
                                                    <option value="{{ $child->id }}">
                                                        ({{ $child->gl_code }}) {{ $child->text }}
                                                    </option>
                                                @else
                                                    <option value="{{ $child->id }}">
                                                        ({{ $child->code }}) {{ $child->text }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('payment_date', 'Payment Date', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'payment_date', $value = '', 
                                    $attributes = array(
                                        'id' => 'payment_date',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('amount', 'Amount Pay', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'amount', $value = '', 
                                    $attributes = array(
                                        'id' => 'amount',
                                        'class' => 'form-control form-control-solid numeric-double'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('cheque_date', 'Cheque Date', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'cheque_date', $value = '', 
                                    $attributes = array(
                                        'id' => 'cheque_date',
                                        'class' => 'form-control form-control-solid disable',
                                        'disabled' => 'disabled'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('cheque_no', 'Cheque No', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'cheque_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'cheque_no',
                                        'class' => 'form-control form-control-solid disable',
                                        'disabled' => 'disabled'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group">
                                {{ Form::label('bank_name', 'Bank Name', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'bank_name', $value = '', 
                                    $attributes = array(
                                        'id' => 'bank_name',
                                        'class' => 'form-control form-control-solid disable',
                                        'disabled' => 'disabled'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('bank_account_no', 'Bank Account No', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'bank_account_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'bank_account_no',
                                        'class' => 'form-control form-control-solid disable',
                                        'disabled' => 'disabled'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('bank_account_name', 'Bank Account Name', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'bank_account_name', $value = '', 
                                    $attributes = array(
                                        'id' => 'bank_account_name',
                                        'class' => 'form-control form-control-solid disable',
                                        'disabled' => 'disabled'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group required">
                                <label for="exampleInputEmail1">
                                    File Browser
                                </label>
                                <div></div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFile" name="attachment" accept="application/pdf, image/*">
                                    <label class="custom-file-label" for="customFile">
                                        Choose file
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group mb-1">
                                {{ Form::label('reference_no', 'Reference No', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::textarea($name = 'reference_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'reference_no',
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
                    <button type="button" class="btn submit-btn btn-primary"><i class="la la-save align-middle"></i> Save Changes</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>