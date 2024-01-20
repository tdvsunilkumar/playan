<div class="modal form-sub-inner fade" id="current-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="canvassLabel" tabindex="-1">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'accounting/chart-of-accounts/general-ledgers/current', 'class'=>'formDtls', 'name' => 'currentForm')) }}
            @csrf
                <div class="modal-header bg-accent pt-3 pb-3">
                    <h5 class="modal-title full-width c-white" id="subisidiaryLedgerModal">
                        Manage Receivable / Contra
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body" style="padding-bottom: 1.75rem">
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
                            <div class="form-group m-form__group">
                                {{ Form::label('is_debit', 'Is Debit?', ['class' => 'fs-6 fw-bold']) }}
                                <br/>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" name="is_debit" type="radio" value="1">
                                    {{ Form::label('is_debit', 'Yes', ['class' => 'form-check-label']) }}
                                </div>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" name="is_debit" type="radio" value="0" checked="checked">
                                    {{ Form::label('is_debit', 'No', ['class' => 'form-check-label']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group required mb-0">
                                {{ Form::label('sl_account_id', 'GL/SL Account', ['class' => '']) }}
                                {{
                                    Form::select('sl_account_id', $sl_accounts, $value = '', ['id' => 'sl_account_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a gl/sl'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn submit-btn btn-primary">Save Changes</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>