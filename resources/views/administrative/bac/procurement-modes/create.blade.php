<div class="modal form fade" id="procurement-mode-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="procurementModeModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'administrative/bac/procurement-modes', 'class'=>'formDtls needs-validation', 'name' => 'procurementModeForm')) }}
            @csrf
                <div class="modal-header bg-accent">
                    <h5 class="modal-title full-width c-white" id="procurementModeModal">
                        Manage Group Menu
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
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('description', 'Description', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'description', $value = '', 
                                    $attributes = array(
                                        'id' => 'description',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('minimum_amount', 'Minimum Amount', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'minimum_amount', $value = '', 
                                    $attributes = array(
                                        'id' => 'minimum_amount',
                                        'class' => 'form-control form-control-solid numeric-double'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('maximum_amount', 'Maximum Amount', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'maximum_amount', $value = '', 
                                    $attributes = array(
                                        'id' => 'maximum_amount',
                                        'class' => 'form-control form-control-solid numeric-double'
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
                    <button type="button" class="btn submit-btn btn-primary">Save Changes</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>