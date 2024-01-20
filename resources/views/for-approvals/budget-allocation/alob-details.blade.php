<div class="tab-pane fade show active" id="alob-details" role="tabpanel" aria-labelledby="request-details-tab">
    {{ Form::open(array('url' => 'finance/budget-allocations', 'class' => 'formDtls', 'name' => 'alobForm')) }}
    @csrf
    <h4 class="text-header">ALLOTMENT & OBLIGATION SLIP INFORMATION</h4>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('allob_requested_date', 'Request Date', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::date($name = 'allob_requested_date', $value = '', 
                    $attributes = array(
                        'class' => 'form-control form-control-solid',
                        'disabled' => 'disabled'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('budget_year', 'Budget Year', ['class' => '']) }}
                {{
                    Form::select('budget_year', $years, $value = '', ['id' => 'budget_year', 'class' => 'form-control select3', 'data-placeholder' => 'select a year'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('control_no', 'Control No', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::text($name = 'control_no', $value = '', 
                    $attributes = array(
                        'class' => 'form-control form-control-solid strong',
                        'disabled' => 'disabled'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('budget_no', 'Budget No', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::text($name = 'budget_no', $value = '', 
                    $attributes = array(
                        'class' => 'form-control form-control-solid strong',
                        'disabled' => 'disabled'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('allob_department_id', 'Department', ['class' => '']) }}
                {{
                    Form::select('allob_department_id', $departments, $value = '', ['id' => 'allob_department_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a department', 'disabled' => 'disabled'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('allob_division_id', 'Division', ['class' => '']) }}
                {{
                    Form::select('allob_division_id', $allob_divisions, $value = '', ['id' => 'allob_division_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a division', 'disabled' => 'disabled'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('payee_id', 'Payee', ['class' => '']) }}
                {{
                    Form::select('payee_id', $payees, $value = '', ['id' => 'payee_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a payee'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div> -->
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
                {{ Form::label('budget_category_id2', 'Category', ['class' => '']) }}
                {{
                    Form::select('budget_category_id2', $categories, $value = '', ['id' => 'budget_category_id2', 'class' => 'form-control select3', 'data-placeholder' => 'select a category', 'disabled' => 'disabled'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group m-form__group">
                {{ Form::label('address', 'Address', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::textarea($name = 'address', $value = '', 
                    $attributes = array(
                        'id' => 'address',
                        'class' => 'form-control form-control-solid',
                        'rows' => 1,
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
                {{ Form::label('particulars', 'Particulars', ['class' => 'fs-6 fw-bold']) }}
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
    <div class="row sss">
        <div class="col-sm-12 text-center">
            <button type="button" class="btn add-alob-line-btn btn-primary">Add Line</button>
        </div>
    </div>
    {{ Form::close() }}
</div>