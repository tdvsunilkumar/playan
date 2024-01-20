<div class="modal form-inner fade" id="approval-setting-modal" tabindex="-1" data-bs-keyboard="false" data-bs-backdrop="static" role="dialog" aria-labelledby="approvalSettingModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'components/approval-settings', 'class'=>'formDtls needs-validation', 'name' => 'approvalSettingForm')) }}
            @csrf
                <div class="modal-header bg-accent">
                    <h5 class="modal-title full-width c-white pt-4 pb-4" id="approvalSettingModal">
                        Manage Approval Setting
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body pb-0">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('module_id', 'Module', ['class' => '']) }}
                                {{
                                    Form::select('module_id', $modules, $value = '', ['id' => 'module_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a module'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('sub_module_id', 'Sub Module', ['class' => '']) }}
                                {{
                                    Form::select('sub_module_id', $sub_modules, $value = '', ['id' => 'sub_module_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a sub module'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('levels', 'Levels', ['class' => '']) }}
                                {{
                                    Form::select('levels', $levels, $value = '', ['id' => 'levels', 'class' => 'form-control select3', 'data-placeholder' => 'select a level'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group">
                                {{ Form::label('remarks', 'Remarks', ['class' => 'required fs-6 fw-bold']) }}
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
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                Primary Approver
                            </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="row">
                                        @foreach ($departmentx as $department)
                                        <div class="col-sm-6">
                                            <div class="form-group m-form__group">
                                                <label>{{ $department->name }}</label>
                                                {{
                                                    Form::select('1_'.$department->id.'[]', $users, $value = '', ['id' => '1_department_'.$department->id, 'class' => 'form-control select3', 'data-placeholder' => '', 'multiple' => 'multiple'])
                                                }}
                                                <span class="m-form__help text-danger"></span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                Secondary Approver
                            </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="row">
                                        @foreach ($departmentx as $department)
                                        <div class="col-sm-6">
                                            <div class="form-group m-form__group">
                                                <label>{{ $department->name }}</label>
                                                {{
                                                    Form::select('2_'.$department->id.'[]', $users, $value = '', ['id' => '2_department_'.$department->id, 'class' => 'form-control select3', 'data-placeholder' => '', 'multiple' => 'multiple'])
                                                }}
                                                <span class="m-form__help text-danger"></span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                Tertiary Approver
                            </button>
                            </h2>
                            <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="row">
                                        @foreach ($departmentx as $department)
                                        <div class="col-sm-6">
                                            <div class="form-group m-form__group">
                                                <label>{{ $department->name }}</label>
                                                {{
                                                    Form::select('3_'.$department->id.'[]', $users, $value = '', ['id' => '3_department_'.$department->id, 'class' => 'form-control select3', 'data-placeholder' => '', 'multiple' => 'multiple'])
                                                }}
                                                <span class="m-form__help text-danger"></span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                                Quaternary Approver
                            </button>
                            </h2>
                            <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body" style="padding: 0.5rem 1rem;">
                                    <div class="row">
                                        @foreach ($departmentx as $department)
                                        <div class="d-flex flex-column">
                                            <label>{{ $department->name }}</label>
                                            <div class="form-group m-form__group">
                                                {{
                                                    Form::select('4_'.$department->id.'[]', $users, $value = '', ['id' => '4_department_'.$department->id, 'class' => 'form-control select3', 'data-placeholder' => '', 'multiple' => 'multiple'])
                                                }}
                                                <span class="m-form__help text-danger"></span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    
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