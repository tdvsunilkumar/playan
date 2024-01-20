<div class="tab-pane fade" id="alob-details2" role="tabpanel" aria-labelledby="request-details-tab">
{{ Form::open(array('url' => 'finance/budget-allocations', 'class' => 'formDtls', 'name' => 'alobForm2')) }}
@csrf
<h4 class="text-header">ALLOTMENT & OBLIGATION SLIP INFORMATION</h4>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group m-form__group">
            {{ Form::label('allob_requested_date', 'Request Date', ['class' => 'fs-6 fw-bold']) }}
            {{ 
                Form::date($name = 'allob_requested_date2', $value = '', 
                $attributes = array(
                    'class' => 'form-control form-control-solid',
                    'disabled' => 'disabled'
                )) 
            }}
            <span class="m-form__help text-danger"></span>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group m-form__group">
            {{ Form::label('budget_year', 'Budget Year', ['class' => '']) }}
            {{
                Form::select('budget_year2', $years, $value = '', ['id' => 'budget_year2', 'class' => 'form-control select3', 'data-placeholder' => 'select a year', 'disabled' => 'disabled'])
            }}
            <span class="m-form__help text-danger"></span>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group m-form__group">
            {{ Form::label('control_no', 'Control No.', ['class' => 'fs-6 fw-bold']) }}
            {{ 
                Form::text($name = 'control_no2', $value = '', 
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
            {{ Form::label('budget_no', 'Budget No.', ['class' => 'fs-6 fw-bold']) }}
            {{ 
                Form::text($name = 'budget_no2', $value = '', 
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
                Form::select('allob_department_id2', $departments, $value = '', ['id' => 'allob_department_id2', 'class' => 'form-control select3', 'data-placeholder' => 'select a department', 'disabled' => 'disabled'])
            }}
            <span class="m-form__help text-danger"></span>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group m-form__group">
            {{ Form::label('allob_division_id', 'Division', ['class' => '']) }}
            {{
                Form::select('allob_division_id2', $allob_divisions, $value = '', ['id' => 'allob_division_id2', 'class' => 'form-control select3', 'data-placeholder' => 'select a division', 'disabled' => 'disabled'])
            }}
            <span class="m-form__help text-danger"></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group m-form__group">
            {{ Form::label('employee_id2', 'Requestor', ['class' => '']) }}
            {{
                Form::select('employee_id2', $employees, $value = '', ['id' => 'employee_id2', 'class' => 'form-control select3', 'data-placeholder' => 'select a requestor', 'disabled' => 'disabled'])
            }}
            <span class="m-form__help text-danger"></span>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group m-form__group">
            {{ Form::label('designation_id2', 'Designation', ['class' => '']) }}
            {{
                Form::select('designation_id2', $designations, $value = '', ['id' => 'designation_id2', 'class' => 'form-control select3', 'data-placeholder' => 'select a designation', 'disabled' => 'disabled'])
            }}
            <span class="m-form__help text-danger"></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group m-form__group">
            {{ Form::label('fund_code_id', 'Fund Code', ['class' => '']) }}
            {{
                Form::select('fund_code_id2', $fund_codes, $value = '', ['id' => 'fund_code_id2', 'class' => 'form-control select3', 'data-placeholder' => 'select a fund code', 'disabled' => 'disabled'])
            }}
            <span class="m-form__help text-danger"></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group m-form__group">
            <label for="address" class="fs-6 fw-bold w-100">Address 
                <div class="form-check form-switch float-end">
                    <input class="form-check-input" type="checkbox" name="with_pr2" id="flexSwitchCheckDefault" disabled="disabled">
                    <label class="form-check-label" for="flexSwitchCheckDefault">with PR?</label>
                </div>
            </label>
            {{ 
                Form::textarea($name = 'address2', $value = '', 
                $attributes = array(
                    'id' => 'address2',
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
        <div class="form-group m-form__group">
            {{ Form::label('particulars2', 'Particulars', ['class' => 'fs-6 fw-bold']) }}
            {{ 
                Form::textarea($name = 'particulars2', $value = '', 
                $attributes = array(
                    'id' => 'particulars',
                    'class' => 'form-control form-control-solid',
                    'rows' => 3,
                    'disabled' => 'disabled'
                )) 
            }}
            <span class="m-form__help text-danger"></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 text-center">
        <button type="button" class="btn add-alob-line-btn2 btn-primary">Add Line</button>
    </div>
</div>
{{ Form::close() }}
</div>