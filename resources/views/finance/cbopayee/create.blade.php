<div class="modal form fade" id="submajor-account-group-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="submajorAccountGroupModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'finance/payee', 'class'=>'formDtls', 'name' => 'submajorAccountGroupForm')) }}
            @csrf
                <div class="modal-header bg-accent">
                    <h5 class="modal-title full-width c-white" id="">
                        Manage Payee[Creditors]
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
                        <div class="col-md-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('paye_type', 'Payee Type', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::select('paye_type',array('' =>'Select Payee Type','1' =>'1-Employee|Officer','2' =>'2-Supplier','3' =>'3-Other Entity'), $paye_type, array('class' => 'form-control select','id'=>'paye_type')) }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>

                           
                        <div class="col-sm-8" style="display : none;" id="hid_div_emp">
                            <div class="form-group m-form__group required" id="req_emp">
                                {{ Form::label('hr_employee_id', 'Employee', ['class' => '']) }}
                                {{
                                    Form::select('hr_employee_id', $hr_employee_id, $value = '', ['id' => 'hr_employee_id', 'class' => 'form-control select3', 'data-placeholder' => 'select an Employee'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-8" style="display : none;" id="hid_div_sup">
                            <div class="form-group m-form__group required"  id="req_sup">
                                {{ Form::label('scp_id', 'Supplier', ['class' => '']) }}
                                {{
                                    Form::select('scp_id', $scp_id, $value = '', ['id' => 'scp_id', 'class' => 'form-control select3', 'data-placeholder' => 'select an supplier name'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                     
                    <div class="fv-row row">
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('paye_name', 'Payee Name', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'paye_name', $value = '', 
                                    $attributes = array(
                                        'id' => 'paye_name',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('paye_address_lotno', 'House/Lot No.', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'paye_address_lotno', $value = '', 
                                    $attributes = array(
                                        'id' => 'paye_address_lotno',
                                        'class' => 'form-control form-control-solid',
                                       
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('paye_address_street', 'Street Name', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'paye_address_street', $value = '', 
                                    $attributes = array(
                                        'id' => 'paye_address_street',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('paye_address_subdivision', 'Subdivision', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'paye_address_subdivision', $value = '', 
                                    $attributes = array(
                                        'id' => 'paye_address_subdivision',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group m-form__group required">
                                {{ Form::label('brgy_code', 'Barangay, Municipality, Province, Region', ['class' => 'fs-6 fw-bold']) }}
                                {{
                                    Form::select('brgy_code', $barangays, $value = '', ['id' => 'brgy_code', 'class' => 'form-control select3', 'data-placeholder' => 'select a barangay...'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('paye_telephone_no', 'Telephone No.', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::number($name = 'paye_telephone_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'paye_telephone_no',
                                        'class' => 'form-control',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('paye_mobile_no', 'Mobile No.', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::number($name = 'paye_mobile_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'paye_mobile_no',
                                        'class' => 'form-control'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('paye_email_address', 'Email Address', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::email($name = 'paye_email_address', $value = '', 
                                    $attributes = array(
                                        'id' => 'paye_email_address',
                                        'class' => 'form-control form-control-solid',
                                       
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                       
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('paye_fax_no', 'FAX No.', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'paye_fax_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'paye_fax_no',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('paye_tin_no', 'TIN No.', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'paye_tin_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'paye_tin_no',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('paye_remarks', 'Remarks', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::textarea($name = 'paye_remarks', $value = '', 
                                    $attributes = array(
                                        'id' => 'paye_remarks',
                                        'class' => 'form-control form-control-solid',
                                        'rows' => 5
                                    )) 
                                }}
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