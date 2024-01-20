<div class="modal form fade" id="rental-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="rentalModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'economic-and-investment/rental-application', 'class'=>'formDtls needs-validation', 'name' => 'ecoRentalForm')) }}
            @csrf
                <div class="modal-header bg-accent pb-4 pt-4">
                    <h5 class="modal-title full-width c-white" id="rentalModal">
                        Manage Rental Application
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
                                        'class' => 'form-control form-control-solid disable',
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
                            <div class="form-group m-form__group">
                                {{ Form::label('business_employer_name', 'Business Name / Employer', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'business_employer_name', $value = '', 
                                    $attributes = array(
                                        'id' => 'business_employer_name',
                                        'class' => 'form-control form-control-solid'
                                    )) 
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
                                {{ Form::label('event_start', 'Event Start', ['class' => 'required fs-6 fw-bold']) }}
                                <input type="datetime-local" 
                                    id="event_start"
                                    name="event_start"
                                    class="form-control form-control-solid"
                                />
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('event_end', 'Event End', ['class' => 'required fs-6 fw-bold']) }}
                                <input type="datetime-local" 
                                    id="event_end"
                                    name="event_end"
                                    class="form-control form-control-solid"
                                />
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('service_id', 'Service Type', ['class' => '']) }}
                                {{
                                    Form::select('service_id', $services, $value = '', ['id' => 'service_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a service type'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('is_free', 'is Service Free?', ['class' => '']) }}
                                <br/>
                                <div class="form-check form-check-inline mt-1">
                                    <input class="form-check-input" type="radio" name="is_free" id="is_free_yes" value="1">
                                    <span class="form-check-label" for="is_free_yes">Yes</span>
                                </div>
                                <div class="form-check form-check-inline mt-1">
                                    <input class="form-check-input" type="radio" name="is_free" id="is_free_no" value="0">
                                    <span class="form-check-label" for="is_free_no">No</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('location_id', 'Reception Location', ['class' => '']) }}
                                {{
                                    Form::select('location_id', $locations, $value = '', ['id' => 'location_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a cemetery location'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('reception_id', 'Reception Name', ['class' => '']) }}
                                {{
                                    Form::select('reception_id', $receptions, $value = '', ['id' => 'reception_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a reception'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('reception_class_id', 'Classification', ['class' => '']) }}
                                {{
                                    Form::select('reception_class_id', $classifications, $value = '', ['id' => 'reception_class_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a reception class'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('reception_class_value', 'Value', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'reception_class_value', $value = '', 
                                    $attributes = array(
                                        'id' => 'reception_class_value',
                                        'class' => 'form-control form-control-solid numeric-only'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('total_amount', 'Service Fees', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'total_amount', $value = '', 
                                    $attributes = array(
                                        'id' => 'total_amount',
                                        'class' => 'form-control form-control-solid numeric-double disable',
                                        'disabled' => 'dissabled'
                                    )) 
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