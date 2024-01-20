<div class="modal form fade" id="bank-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="bankModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'accounting/setup-data/banks', 'class'=>'formDtls needs-validation', 'name' => 'bankForm')) }}
            @csrf
                <div class="modal-header bg-accent">
                    <h5 class="modal-title full-width c-white" id="bankModal">
                        Manage Expanded Vatable Tax
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('bank_account_no', 'Bank Account No.', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'bank_account_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'bank_account_no',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('bank_name', 'Bank Name', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'bank_name', $value = '', 
                                    $attributes = array(
                                        'id' => 'bank_name',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group mb-0 required">
                                {{ Form::label('bank_account_name', 'Bank Account Name', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::textarea($name = 'bank_account_name', $value = '', 
                                    $attributes = array(
                                        'id' => 'bank_account_name',
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
                    <button type="button" class="btn submit-btn btn-primary"><i class="la la-save"></i> Save Changes</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>