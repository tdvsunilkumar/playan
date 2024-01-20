<div class="modal form fade" id="department-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="fundCodeModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'accounting/departments', 'class'=>'formDtls', 'name' => 'departmentForm')) }}
            @csrf
                <div class="modal-header bg-accent">
                    <h5 class="modal-title full-width c-white" id="departmentModal">
                        Manage Department
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="fv-row row hidden">
                        <div class="col-sm-12">
                            {{ Form::label('id', 'ID', ['class' => 'required fs-6 fw-bold mb-2']) }}
                            {{ 
                                Form::text($name = 'id', $value = '', 
                                $attributes = array(
                                    'id' => 'id',
                                    'class' => 'form-control form-control-solid',
                                    'disabled' => 'disabled'
                                )) 
                            }}
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('code', 'Code', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'code', $value = '', 
                                    $attributes = array(
                                        'id' => 'code',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('financial_code', 'Financial Code', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'financial_code', $value = '', 
                                    $attributes = array(
                                        'id' => 'financial_code',
                                        'class' => 'form-control form-control-solid',
                                    ))
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('shortname', 'Short Name', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'shortname', $value = '', 
                                    $attributes = array(
                                        'id' => 'shortname',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('name', 'Name', ['class' => '']) }}
                                {{ 
                                    Form::text($name = 'name', $value = '', 
                                    $attributes = array(
                                        'id' => 'name',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('acctg_department_function_id', 'Functions', ['class' => '']) }}
                                {{
                                    Form::select('acctg_department_function_id', $functions, $value = '', ['id' => 'acctg_department_function_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a function'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group">
                                {{ Form::label('program', 'Program', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'program', $value = '', 
                                    $attributes = array(
                                        'id' => 'program',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('hr_employee_id', 'Head/OIC', ['class' => '']) }}
                                {{
                                    Form::select('hr_employee_id', $employees, $value = '', ['id' => 'hr_employee_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a head/oic'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('hr_designation_id', 'Position', ['class' => '']) }}
                                {{
                                    Form::select('hr_designation_id', $designations, $value = '', ['id' => 'hr_designation_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a designation'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group mb-0">
                                {{ Form::label('remarks', 'Remarks', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::textarea($name = 'remarks', $value = '', 
                                    $attributes = array(
                                        'id' => 'remarks',
                                        'class' => 'form-control form-control-solid',
                                        'rows' => '2'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row mt-2 divisionLayer hidden">
                        <div class="col-md-12">
                            <h4 class="text-header mb-3">Division Information</h4>
                            <div id="datatable-2" class="dataTables_wrapper">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="divisionTable" class="display dataTable table w-100 table-striped" aria-describedby="divisionInfo">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('ID') }}</th>
                                                        <th>{{ __('CODE') }}</th>
                                                        <th>{{ __('NAME') }}</th>
                                                        <th>{{ __('LAST MODIFIED') }}</th>
                                                        <th>{{ __('STATUS') }}</th>
                                                        <th>{{ __('Actions') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="odd">
                                                        <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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