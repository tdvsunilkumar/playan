<div class="modal form-inner fade" id="supplier-contact-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="supplierContactLabel" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            {{ Form::open(array('url' => 'general-services/setup-data/suppliers/contact-persons', 'class' => '', 'name' => 'contactPersonForm')) }}
            @csrf
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="supplierContactLabel">Manage Contact Person</h5>
            </div>
            <div class="modal-body p-0 p-4 pb-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            {{ Form::label('contact_person', 'Contact Person', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'contact_person', $value = '', 
                                $attributes = array(
                                    'id' => 'contact_person',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            {{ Form::label('telephone_no', 'Telephone No', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'telephone_no', $value = '', 
                                $attributes = array(
                                    'id' => 'telephone_no',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            {{ Form::label('mobile_no', 'Mobile No', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'mobile_no', $value = '', 
                                $attributes = array(
                                    'id' => 'mobile_no',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            {{ Form::label('email_address', 'Email Address', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'email_address', $value = '', 
                                $attributes = array(
                                    'id' => 'email_address',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-end">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn submit-btn btn-primary">Save Changes</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>