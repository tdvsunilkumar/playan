<div class="modal form-inner fade" id="journal-entry-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="accountPayableModal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'accounting/general-journals', 'class'=>'formDtls needs-validation', 'name' => 'journalEntryForm')) }}
            @csrf
                <div class="modal-header bg-accent p-0 p-4">
                    <h5 class="modal-title full-width c-white" id="journalEntryModal">
                        Manage Journal Entry
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body p-0 p-4 pb-xs-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group required">
                                {{ Form::label('gl_account_id', 'GL Account', ['class' => '']) }}
                                {{
                                    Form::select('gl_account_id', $gl_accounts, $value = '', ['id' => 'gl_account_id', 'class' => 'require form-control select3', 'data-placeholder' => 'select a gl account'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group mb-lg-1">
                                {{ Form::label('debit_amount', 'Debit Amount', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'debit_amount', $value = '', 
                                    $attributes = array(
                                        'id' => 'debit_amount',
                                        'class' => 'require form-control form-control-solid numeric-double'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group mb-lg-1">
                                {{ Form::label('credit_amount', 'Credit Amount', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'credit_amount', $value = '', 
                                    $attributes = array(
                                        'id' => 'credit_amount',
                                        'class' => 'require form-control form-control-solid numeric-double'
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