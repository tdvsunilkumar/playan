<div class="modal form fade" id="employee-modal" tabindex="-1" role="dialog" aria-labelledby="employeeModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'human-resource/employees', 'class'=>'formDtls', 'name' => 'employeeForm')) }}
            @csrf
                <div class="modal-header bg-accent pt-4 pb-4">
                    <h5 class="modal-title full-width c-white" id="employeeModal">
                        Manage Employee
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
                    <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseBasic" aria-expanded="true" aria-controls="collapseBasic">Basic Information</h4>
                    <div class="collapse show mt-2" id="collapseBasic">
                        <div class="fv-row row">
                            <div class="col-sm-4">
                                <div class="form-group m-form__group required">
                                    {{ Form::label('firstname', 'Firstname', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'firstname', $value = '', 
                                        $attributes = array(
                                            'id' => 'firstname',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group m-form__group">
                                    {{ Form::label('middlename', 'Middlename', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'middlename', $value = '', 
                                        $attributes = array(
                                            'id' => 'middlename',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group m-form__group required">
                                    {{ Form::label('lastname', 'Lastname', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'lastname', $value = '', 
                                        $attributes = array(
                                            'id' => 'lastname',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="fv-row row">
                            <div class="col-sm-2">
                                <div class="form-group m-form__group">
                                    {{ Form::label('suffix', 'Suffix(Jr., Sr., II, III)', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'suffix', $value = '', 
                                        $attributes = array(
                                            'id' => 'suffix',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group m-form__group">
                                    {{ Form::label('title', 'Title', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'title', $value = '', 
                                        $attributes = array(
                                            'id' => 'title',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group m-form__group required" id="parent_gender">
                                    {{ Form::label('gender', 'Gender', ['class' => '']) }}
                                    {{
                                        Form::select('gender', $gender, $value = '', ['id' => 'gender', 'class' => 'form-control select3', 'data-placeholder' => 'select a gender'])
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group m-form__group">
                                    {{ Form::label('birthdate', 'Birthdate', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::date($name = 'birthdate', $value = '', 
                                        $attributes = array(
                                            'id' => 'birthdate',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseAdditional" aria-expanded="true" aria-controls="collapseAdditional">Additional Information</h4>
                    <div class="collapse show mt-2" id="collapseAdditional">
                        <div class="fv-row row">
                            <div class="col-sm-4">
                                <div class="form-group m-form__group">
                                    {{ Form::label('c_house_lot_no', 'Blk / Lot No.', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'c_house_lot_no', $value = '', 
                                        $attributes = array(
                                            'id' => 'c_house_lot_no',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group m-form__group">
                                    {{ Form::label('c_street_name', 'Street Name', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'c_street_name', $value = '', 
                                        $attributes = array(
                                            'id' => 'c_street_name',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group m-form__group">
                                    {{ Form::label('c_subdivision', 'Subdivision / Village Name', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'c_subdivision', $value = '', 
                                        $attributes = array(
                                            'id' => 'c_subdivision',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="fv-row row">
                            <div class="col-sm-8">
                                <div class="form-group m-form__group required" id="parent_barangay_id">
                                    {{ Form::label('barangay_id', 'Barangay, Municipality, Province, Region', ['class' => 'fs-6 fw-bold']) }}
                                    {{
                                        Form::select('barangay_id', $barangays, $value = '', ['id' => 'barangay_id', 'class' => 'form-control', 'data-placeholder' => 'select a barangay...'])
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group m-form__group">
                                    {{ Form::label('email_address', 'Email Address', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::email($name = 'email_address', $value = '', 
                                        $attributes = array(
                                            'id' => 'email_address',
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
                                    {{ Form::label('telephone_no', 'Telephone No.', ['class' => 'fs-6 fw-bold']) }}
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
                            <div class="col-sm-4">
                                <div class="form-group m-form__group required">
                                    {{ Form::label('mobile_no', 'Mobile No.', ['class' => 'fs-6 fw-bold']) }}
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
                            <div class="col-sm-4">
                                <div class="form-group m-form__group">
                                    {{ Form::label('fax_no', 'Fax No', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'fax_no', $value = '', 
                                        $attributes = array(
                                            'id' => 'fax_no',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOther" aria-expanded="true" aria-controls="collapseOther">Other Information</h4>
                    <div class="collapse show mt-2" id="collapseOther">
                        <div class="fv-row row">
                            <div class="col-sm-6">
                                <div class="form-group m-form__group">
                                    {{ Form::label('tin_no', 'TIN No.', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'tin_no', $value = '', 
                                        $attributes = array(
                                            'id' => 'tin_no',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group m-form__group">
                                    {{ Form::label('sss_no', 'SSS No.', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'sss_no', $value = '', 
                                        $attributes = array(
                                            'id' => 'sss_no',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="fv-row row">
                            <div class="col-sm-6">
                                <div class="form-group m-form__group">
                                    {{ Form::label('pag_ibig_no', 'Pag-Ibig No', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'pag_ibig_no', $value = '', 
                                        $attributes = array(
                                            'id' => 'pag_ibig_no',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group m-form__group">
                                    {{ Form::label('philhealth_no', 'PhilHealth No.', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'philhealth_no', $value = '', 
                                        $attributes = array(
                                            'id' => 'philhealth_no',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="fv-row row">
                            <div class="col-sm-6">
                                <div class="form-group m-form__group required">
                                    {{ Form::label('acctg_department_id', 'Department', ['class' => '']) }}
                                    {{
                                        Form::select('acctg_department_id', $departments, $value = '', ['id' => 'acctg_department_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a department'])
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group m-form__group required">
                                    {{ Form::label('acctg_department_division_id', 'Division', ['class' => '']) }}
                                    {{
                                        Form::select('acctg_department_division_id', $divisions, $value = '', ['id' => 'acctg_department_division_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a division'])
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="fv-row row">
                            <div class="col-sm-6">
                                <div class="form-group m-form__group required">
                                    {{ Form::label('hr_designation_id', 'Designation', ['class' => '']) }}
                                    {{
                                        Form::select('hr_designation_id', $designations, $value = '', ['id' => 'hr_designation_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a designation'])
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group m-form__group required">
                                    {{ Form::label('identification_no', 'ID No', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'identification_no', $value = '', 
                                        $attributes = array(
                                            'id' => 'identification_no',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group m-form__group required">
                                    {{ Form::label('is_dept_restricted', 'Department Restriction', ['class' => '']) }}
                                    {{
                                        Form::select('is_dept_restricted', $restrictions, $value = '', ['id' => 'is_dept_restricted', 'class' => 'form-control select3', 'data-placeholder' => 'select a department restriction'])
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="fv-row row">
                            <div class="col-sm-12">
                                <div class="form-group m-form__group">
                                    {{ Form::label('departmental_access', 'Departmental Access', ['class' => '']) }}
                                    {{
                                        Form::select('departmental_access[]', $access, $value = '', ['id' => 'departmental_access', 'class' => 'form-control select3', 'multiple' => 'multiple', 'data-placeholder' => 'select an access', 'disabled' => 'disabled'])
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row hidden upload-row">
                        <div class="col-sm-12">
                            <h4 class="text-header mb-0 accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseUpload" aria-expanded="false" aria-controls="collapseUpload">Upload Information</h4>
                        </div>
                        <div class="collapse mt-2" id="collapseUpload">
                            <div class="col-sm-12">
                                <div class="d-flex align-items-center">
                                    <div class="form-group m-form__group required w-100 me-2">
                                        <label for="exampleInputEmail1">
                                            File Browser
                                        </label>
                                        <div></div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="customFile" name="attachment" accept="application/pdf, image/*">
                                            <label class="custom-file-label" for="customFile">
                                                Choose file
                                            </label>
                                        </div>
                                    </div>
                                    <button type="button" id="hr-upload-btn" class="btn btn-sm ms-auto btn-primary text-center">Upload&nbsp;Now</button>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div id="datatable-2" class="dataTables_wrapper mt-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table id="hrUploadTable" class="display dataTable table w-100 table-striped" aria-describedby="hrUploadInfo">
                                                    <thead>
                                                        <tr>
                                                            <th class="sliced">{{ __('FILENAME') }}</th>
                                                            <th>{{ __('TYPE') }}</th>
                                                            <th>{{ __('SIZE') }}</th>
                                                            <th>{{ __('ACTIONS') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="odd">
                                                            <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn submit-btn btn-primary"><i class="la la-save"></i> Save Changes</button>
                </div>
            {{ Form::close() }}

            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div id="modalToast" data-bs-autohide="true" class="toast hide bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-body text-white">
                    Hello, world! This is a toast message.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
