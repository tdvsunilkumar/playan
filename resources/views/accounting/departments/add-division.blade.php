<div class="modal form-inner fade" id="division-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="canvassLabel" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'accounting/departments/divisions', 'class'=>'formDtls', 'name' => 'divisionForm')) }}
            @csrf
                <div class="modal-header bg-accent pt-3 pb-3">
                    <h5 class="modal-title full-width c-white" id="subisidiaryLedgerModal">
                        Manage Division
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group mb-0 required">
                                {{ Form::label('code', 'Code', ['class' => 'fs-6 fw-bold']) }}
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
                            <div class="form-group m-form__group mb-0 required">
                                {{ Form::label('name', 'Name', ['class' => 'required fs-6 fw-bold']) }}
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