<div class="modal form-inner fade" id="budget-breakdown-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="bugetLabel" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{ Form::open(array('url' => 'finance/budget-proposal/add-breakdown', 'class' => '', 'name' => 'breakdownForm')) }}
            @csrf
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="bacRFQLabel">Manage Breakdown</h5>
            </div>
            <div class="modal-body p-4 pt-3 pb-1">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group m-form__group required">
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
                        <div class="form-group m-form__group required">
                            {{ Form::label('quarterly_budget', 'Quarterly Budget', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'quarterly_budget', $value = '', 
                                $attributes = array(
                                    'id' => 'quarterly_budget',
                                    'class' => 'form-control form-control-solid numeric-double'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('annual_budget', 'Annual Budget', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'annual_budget', $value = '', 
                                $attributes = array(
                                    'id' => 'annual_budget',
                                    'class' => 'form-control form-control-solid numeric-double'
                                )) 
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