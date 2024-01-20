<div class="tab-pane fade show active" id="request-details" role="tabpanel" aria-labelledby="request-details-tab">
    {{ Form::open(array('url' => 'general-services/departmental-requisitions', 'class'=>'formDtls', 'name' => 'requisitionForm')) }}
    @csrf
    <h4 class="text-header">Request Information</h4>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('control_no', 'Control No', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::text($name = 'control_no', $value = '', 
                    $attributes = array(
                        'id' => 'control_no',
                        'class' => 'form-control form-control-solid strong',
                        'disabled' => 'disabled'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('requested_date', 'Request Date', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::date($name = 'requested_date', $value = '', 
                    $attributes = array(
                        'id' => 'requested_date',
                        'class' => 'form-control form-control-solid'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('request_type_id', 'Request Type', ['class' => '']) }}
                {{
                    Form::select('request_type_id', $request_types, $value = '', ['id' => 'request_type_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a request type'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('fund_code_id', 'Fund Code', ['class' => '']) }}
                {{
                    Form::select('fund_code_id', $fund_codes, $value = '', ['id' => 'fund_code_idx', 'class' => 'form-control select3', 'data-placeholder' => 'select a fund code'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('department_id', 'Department', ['class' => '']) }}
                {{
                    Form::select('department_id', $departments, $value = '', ['id' => 'department_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a department'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('division_id', 'Division', ['class' => '']) }}
                {{
                    Form::select('division_id', $divisions, $value = '', ['id' => 'division_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a division'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group m-form__group required">
                {{ Form::label('employee_id', 'Requestor', ['class' => '']) }}
                {{
                    Form::select('employee_id', $employees, $value = '', ['id' => 'employee_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a requestor'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group m-form__group">
                {{ Form::label('designation_id', 'Designation', ['class' => '']) }}
                {{
                    Form::select('designation_id', $designations, $value = '', ['id' => 'designation_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a designation', 'disabled' => 'disabled'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group m-form__group required">
                {{ Form::label('budget_category_id', 'Category', ['class' => '']) }}
                {{
                    Form::select('budget_category_id', $categories, $value = '', ['id' => 'budget_category_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a category'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group m-form__group">
                {{ Form::label('remarks', 'Remarls', ['class' => 'fs-6 fw-bold']) }}
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
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="modalToast" data-bs-autohide="true" class="toast hide bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body">
            Hello, world! This is a toast message.
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>