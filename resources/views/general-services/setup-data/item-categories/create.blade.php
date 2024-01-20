<div class="modal form fade" id="item-category-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="itemCategoryModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'general-services/setup-data/item-categories', 'class'=>'formDtls needs-validation', 'name' => 'itemCategoryForm')) }}
            @csrf
                <div class="modal-header bg-accent">
                    <h5 class="modal-title full-width c-white" id="disignationModal">
                        Manage Item Category
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
                                {{ Form::label('gl_account_id', 'GL Account', ['class' => '']) }}
                                {{
                                    Form::select('gl_account_id', $gl_accounts, $value = '', ['id' => 'gl_account_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a gl account'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('code', 'Code', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'code', $value = '', 
                                    $attributes = array(
                                        'id' => 'code',
                                        'class' => 'form-control form-control-solid text-uppercase'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group required">
                                {{ Form::label('description', 'Description', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'description', $value = '', 
                                    $attributes = array(
                                        'id' => 'description',
                                        'class' => 'form-control form-control-solid',
                                    )) 
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
                                        'rows' => 2
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>

                    <div class="fv-row row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group mb-0">
                                <div class="form-check">
                                    {{ Form::checkbox('is_health_safety', '1', false, ['class' => 'form-check-input']) }}
                                    {{ Form::label('is_health_safety', 'Health & Safety', ['class' => 'form-check-label fs-6 fw-bold']) }}
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