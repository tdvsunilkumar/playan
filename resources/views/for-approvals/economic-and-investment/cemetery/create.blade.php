<div class="modal form fade" id="cemetery-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="cemeteryModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'economic-and-investment/cemetery-application', 'class'=>'formDtls needs-validation', 'name' => 'econCemeteryForm')) }}
            @csrf
                <div class="modal-header bg-accent pb-4 pt-4">
                    <h5 class="modal-title full-width c-white" id="cemeteryModal">
                        Manage Cemetery Application
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body p-0 p-4">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('transaction_no', 'Transaction No.', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'transaction_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'transaction_no',
                                        'class' => 'form-control form-control-solid',
                                        'disabled' => 'disabled'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('transaction_date', 'Transaction Date', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'transaction_date', $value = '', 
                                    $attributes = array(
                                        'id' => 'transaction_date',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('requestor_id', 'Requestor Name', ['class' => '']) }}
                                {{
                                    Form::select('requestor_id', $requestor, $value = '', ['id' => 'requestor_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a requestor'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('expired_id', 'Deceased Name', ['class' => '']) }}
                                {{
                                    Form::select('expired_id', $expired, $value = '', ['id' => 'expired_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a deceased person'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('full_address', 'Full Address', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'full_address', $value = '', 
                                    $attributes = array(
                                        'id' => 'full_address',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('contact_no', 'Contact No.', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'contact_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'contact_no',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('service_id', 'Service Type', ['class' => '']) }}
                                {{
                                    Form::select('service_id', $services, $value = 7, ['id' => 'service_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a service type'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('total_amount', 'Service Fees', ['class' => '']) }}
                                {{
                                    Form::select('total_amount', $services_fees, $value = '15000', ['id' => 'total_amount', 'class' => 'form-control select3', 'data-placeholder' => 'select a service fee'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('location_id', 'Cemetery Location', ['class' => '']) }}
                                {{
                                    Form::select('location_id', $locations, $value = '', ['id' => 'location_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a cemetery location'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('cemetery_id', 'Cemetery Name', ['class' => '']) }}
                                {{
                                    Form::select('cemetery_id', $cemeteries, $value = '', ['id' => 'cemetery_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a cemetery'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('cemetery_style_id', 'Cemetery Style', ['class' => '']) }}
                                {{
                                    Form::select('cemetery_style_id', $styles, $value = '', ['id' => 'cemetery_style_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a cemetery style'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('cemetery_lot_id', 'Cemetery Lot', ['class' => '']) }}
                                {{
                                    Form::select('cemetery_lot_id', $lots, $value = '', ['id' => 'cemetery_lot_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a cemetery lot'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>