<div class="modal form fade" id="fund-code-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="fundCodeModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'accounting/fund-codes', 'class'=>'formDtls', 'name' => 'fundcodeForm')) }}
            @csrf
                <div class="modal-header bg-accent">
                    <h5 class="modal-title full-width c-white" id="fundCodeModal">
                        Manage Fund Code
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
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group required mb-0">
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
                    <button type="button" class="btn submit-btn btn-primary">Save changes</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>