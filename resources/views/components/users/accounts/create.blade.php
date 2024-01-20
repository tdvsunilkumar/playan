<div class="modal form fade" id="user-account-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="userAccountModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'components/users/accounts', 'class'=>'formDtls needs-validation', 'name' => 'userAccountForm')) }}
            @csrf
                <div class="modal-header bg-accent">
                    <h5 class="modal-title full-width c-white pt-2 pb-2" id="userAccountModal">
                        Manage User Account
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('employee_id', 'Employee', ['class' => '']) }}
                                {{
                                    Form::select('employee_id', $employees, $value = '', ['id' => 'employee_id', 'class' => 'form-control select3', 'data-placeholder' => 'select an employee'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('role_id', 'Role', ['class' => '']) }}
                                {{
                                    Form::select('role_id', $roles, $value = '', ['id' => 'role_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a role'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('name', 'Name', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'name', $value = '', 
                                    $attributes = array(
                                        'id' => 'name',
                                        'class' => 'form-control form-control-solid',
                                        'disabled' => 'disabled'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('email', 'Email', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::email($name = 'email', $value = '', 
                                    $attributes = array(
                                        'id' => 'email',
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
                                {{ Form::label('password', 'Password', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'password', $value = '', 
                                    $attributes = array(
                                        'id' => 'password',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('confirm_password', 'Confirm Password', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'confirm_password', $value = '', 
                                    $attributes = array(
                                        'id' => 'confirm_password',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div id="result-layer" class="hidden">
                        <h4 class="text-header">Role's Permissions</h4>
                        <div id="result">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn submit-btn btn-primary"><i class="la la-save"></i>Save Changes</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>