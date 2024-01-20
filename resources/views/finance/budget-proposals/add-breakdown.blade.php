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
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('gl_account_id', 'GL Account', ['class' => '']) }}
                            {{
                                Form::select('gl_account_id', $gl_accounts, $value = '', ['id' => 'gl_account_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a gl account'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group mb-0">
                            {{ Form::label('is_ppmp', 'Is PPMP?', ['class' => 'fs-6 fw-bold']) }}
                            <br/>
                            <div class="form-check form-check-inline mt-2">
                                <input id="is_ppmp_yes" class="form-check-input" name="is_ppmp" type="radio" value="1">
                                {{ Form::label('is_ppmp', 'Yes', ['class' => 'form-check-label']) }}
                            </div>
                            <div class="form-check form-check-inline mt-2">
                                <input id="is_ppmp_no" class="form-check-input" name="is_ppmp" type="radio" value="0" checked="checked">
                                {{ Form::label('is_ppmp', 'No', ['class' => 'form-check-label']) }}
                            </div>
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
                            {{ Form::label('budget_category_id', 'Budget Category', ['class' => '']) }}
                            {{
                                Form::select('budget_category_id', $categories, $value = '', ['id' => 'budget_category_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a category'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
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
                    <div class="col-sm-6">
                        <div class="form-group m-form__group hidden">
                            {{ Form::label('balance', 'Balance', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'balance', $value = '', 
                                $attributes = array(
                                    'id' => 'balance',
                                    'class' => 'form-control form-control-solid numeric-double',
                                    'disabled' => 'disabled'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group hidden">
                            {{ Form::label('alignment', 'Alignment', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'alignment', $value = '', 
                                $attributes = array(
                                    'id' => 'alignment',
                                    'class' => 'form-control form-control-solid'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group hidden">
                            {{ Form::label('final_budget', 'Final Budget', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'final_budget', $value = '', 
                                $attributes = array(
                                    'id' => 'final_budget',
                                    'class' => 'form-control form-control-solid numeric-double',
                                    'disabled' => 'disabled'
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