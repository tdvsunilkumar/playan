<div class="modal form fade" id="evat-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="evatModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'accounting/setup-data/expanded-vatable-taxes', 'class'=>'formDtls needs-validation', 'name' => 'evatForm')) }}
            @csrf
                <div class="modal-header bg-accent">
                    <h5 class="modal-title full-width c-white" id="evatModal">
                        Manage Expanded Vatable Tax
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('code', 'Code', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'code', $value = '', 
                                    $attributes = array(
                                        'id' => 'code',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('percentage', 'Percentage', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'percentage', $value = '', 
                                    $attributes = array(
                                        'id' => 'percentage',
                                        'class' => 'form-control form-control-solid numeric-double'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('name', 'Name', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'name', $value = '', 
                                    $attributes = array(
                                        'id' => 'name',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('gl_account_id', 'GL Code', ['class' => 'required fs-6 fw-bold']) }}
                                {{
                                    Form::select('gl_account_id', $gl_accounts, $value = '', ['id' => 'gl_account_id', 'class' => 'form-control select3 fs-3 require', 'data-placeholder' => 'select a GL'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group mb-0">
                                {{ Form::label('description', 'Description', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::textarea($name = 'description', $value = '', 
                                    $attributes = array(
                                        'id' => 'description',
                                        'class' => 'form-control form-control-solid',
                                        'rows' => 3
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn submit-btn btn-primary"><i class="la la-save"></i> Save Changes</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>