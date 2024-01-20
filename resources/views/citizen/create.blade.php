<div class="modal form fade" id="citizen-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="citizenModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'accounting/fund-codes', 'class'=>'formDtls', 'name' => 'citizenForm')) }}
            @csrf
                <div class="modal-header bg-accent">
                    <h5 class="modal-title full-width c-white" id="citizenModal">
                        Add Citizen
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="fv-row row">
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('lname', 'LName', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'LName', $value = '', 
                                    $attributes = array(
                                        'id' => 'LName',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('fname', 'FName', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'FName', $value = '', 
                                    $attributes = array(
                                        'id' => 'FName',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('mname', 'MName', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'MName', $value = '', 
                                    $attributes = array(
                                        'id' => 'MName',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('suffix', 'Suffix', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'Suffix', $value = '', 
                                    $attributes = array(
                                        'id' => 'Suffix',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required mb-0">
                                {{ Form::label('house_no', 'HouseNo', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'HouseNo', $value = '', 
                                    $attributes = array(
                                        'id' => 'HouseNo',
                                        'class' => 'form-control form-control-solid',
                                        'rows' => 3
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required mb-0">
                                {{ Form::label('st_name', 'StName', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'StName', $value = '', 
                                    $attributes = array(
                                        'id' => 'StName',
                                        'class' => 'form-control form-control-solid',
                                        'rows' => 3
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required mb-0">
                                {{ Form::label('subdivision', 'Subdivision', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'Subdivision', $value = '', 
                                    $attributes = array(
                                        'id' => 'Subdivision',
                                        'class' => 'form-control form-control-solid',
                                        'rows' => 3
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required mb-0">
                                {{ Form::label('barangay', 'Barangay', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'HouseNo', $value = '', 
                                    $attributes = array(
                                        'id' => 'HouseNo',
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