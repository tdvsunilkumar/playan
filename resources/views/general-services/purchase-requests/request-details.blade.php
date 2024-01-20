<div class="tab-pane fade" id="request-details" role="tabpanel" aria-labelledby="request-details-tab">
    {{ Form::open(array('url' => 'general-services/departmental-requisitions', 'class'=>'formDtls', 'name' => 'requisitionForm')) }}
    @csrf
    <h4 class="text-header">Request Information</h4>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('control_no', 'Control No.', ['class' => 'fs-6 fw-bold']) }}
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
                        'class' => 'form-control form-control-solid',
                        'disabled' => 'disabled'
                    )) 
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
                    Form::select('division_id', $divisions, $value = '', ['id' => 'division_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a division', 'disabled' => 'disabled'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('employee_id', 'Requestor', ['class' => '']) }}
                {{
                    Form::select('employee_id', $employees, $value = '', ['id' => 'employee_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a requestor', 'disabled' => 'disabled'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('designation_id', 'Designation', ['class' => '']) }}
                {{
                    Form::select('designation_id', $designations, $value = '', ['id' => 'designation_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a designation', 'disabled' => 'disabled'])
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
                    Form::select('request_type_id', $request_types, $value = '', ['id' => 'request_type_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a request type', 'disabled' => 'disabled'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('purchase_type_id', 'Purchase Type', ['class' => '']) }}
                {{
                    Form::select('purchase_type_id', $purchase_types, $value = '', ['id' => 'purchase_type_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a purchase type'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group m-form__group ">
                {{ Form::label('remarks', 'Remarks', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::textarea($name = 'remarks', $value = '', 
                    $attributes = array(
                        'id' => 'remarks',
                        'class' => 'form-control form-control-solid',
                        'rows' => 3,
                        'disabled' => 'disabled'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>