<div class="modal form-inner fade" id="ppmp-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="ppmpLabel" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{ Form::open(array('url' => 'general-services/project-procurement-management-plan', 'class' => '', 'name' => 'ppmpForm')) }}
            @csrf
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="bacRFQLabel">Manage Project Procurement Management Plan</h5>
            </div>
            <div class="modal-body p-4 pt-3 pb-1">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('fund_code_id', 'FUND CODE', ['class' => '']) }}
                            {{
                                Form::select('fund_code_id', $fund_codes, $value = '', ['id' => 'fund_code_id', 'class' => 'form-control select3 fs-3', 'data-placeholder' => 'select a fund code'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('budget_year', 'YEAR', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'budget_year', $value = '', 
                                $attributes = array(
                                    'id' => 'budget_year',
                                    'class' => 'form-control form-control-solid',
                                    'placeholder' => 'select a year',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group m-form__group required">
                            {{ Form::label('department_id', 'DEPARTMENT', ['class' => '']) }}
                            {{
                                Form::select('department_id', $departments, $value = '', ['id' => 'department_id', 'class' => 'form-control select3 fs-3', 'data-placeholder' => 'select a department'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group m-form__group required">
                            {{ Form::label('remarks', 'REMARKS', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::textarea($name = 'remarks', $value = '', 
                                $attributes = array(
                                    'id' => 'remarks',
                                    'class' => 'form-control form-control-solid',
                                    'rows' => 4
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