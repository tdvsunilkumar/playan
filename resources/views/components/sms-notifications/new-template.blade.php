<div class="modal form fade" id="template-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="smsTemplateModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'components/sms-notifications/templates', 'class'=>'formDtls needs-validation', 'name' => 'smsTemplateForm')) }}
            @csrf
                <div class="modal-header bg-accent pb-4 pt-4">
                    <h5 class="modal-title full-width c-white" id="smsTemplateModal">
                        Manage Template
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body p-0 p-4">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('group_id', 'Group', ['class' => '']) }}
                                {{
                                    Form::select('group_id', $groups, $value = '', ['id' => 'group_id', 'class' => 'require form-control select3', 'data-placeholder' => 'select a group'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('module_id', 'Module', ['class' => '']) }}
                                {{
                                    Form::select('module_id', $modules, $value = '', ['id' => 'module_id', 'class' => 'require form-control select3', 'data-placeholder' => 'select a module'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('sub_module_id', 'Sub Module', ['class' => '']) }}
                                {{
                                    Form::select('sub_module_id', $sub_modules, $value = '', ['id' => 'sub_module_id', 'class' => 'require form-control select3', 'data-placeholder' => 'select a sub module'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('type_id', 'Type', ['class' => '']) }}
                                {{
                                    Form::select('type_id', $types, $value = '', ['id' => 'type_id', 'class' => 'require form-control select3', 'data-placeholder' => 'select a type'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('application', 'Application', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'application', $value = '', 
                                    $attributes = array(
                                        'id' => 'application',
                                        'class' => 'require form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('action_id', 'Action', ['class' => '']) }}
                                {{
                                    Form::select('action_id', $actions, $value = '', ['id' => 'action_id', 'class' => 'require form-control select3', 'data-placeholder' => 'select an action'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group mb-0 required">
                                {{ Form::label('template', 'Template', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::textarea($name = 'template', $value = '', 
                                    $attributes = array(
                                        'id' => 'template',
                                        'class' => 'form-control form-control-solid',
                                        'rows' => 3
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {{ Form::label('codex', 'Codex', ['class' => '']) }}
                            <div class="form-group m-form__group template-layer">
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