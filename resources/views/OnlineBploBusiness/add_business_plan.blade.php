<div class="modal form fade" id="business-plan-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="submajorAccountGroupModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'business-online-application', 'class'=>'formDtls', 'name' => 'addBusinessPlan')) }}
            @csrf
                <div class="modal-header bg-accent">
                    <h5 class="modal-title full-width c-white" id="">
                        Manage Payee[Creditors]
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body">  
                    <div class="fv-row row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group required">
                                {{ Form::label('subclass_id', 'Select PSIC Subclass', ['class' => '']) }}
                                {{
                                    Form::select('subclass_id', $psicSubclass, $value = '', ['id' => 'busn_office_main_barangay_id', 'class' => 'form-control select3', 'data-placeholder' => 'Please select'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('busp_no_units', 'Units', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::number($name = 'busp_no_units', $value = '', 
                                    $attributes = array(
                                        'id' => 'busp_no_units',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('busp_capital_investment', 'Capital Investment', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::number($name = 'busp_capital_investment', $value = '', 
                                    $attributes = array(
                                        'id' => 'busp_capital_investment',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('busp_essential', 'Essential', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::number($name = 'busp_essential', $value = '', 
                                    $attributes = array(
                                        'id' => 'busp_essential',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('busp_non_essential', 'Non-Essential', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::number($name = 'busp_non_essential', $value = '', 
                                    $attributes = array(
                                        'id' => 'busp_non_essential',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn submit-btn-add-busn-plan btn-primary">Save Changes</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>