
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/dropzone/dropzone.css?v='.filemtime(getcwd().'/assets/vendors/dropzone/dropzone.css').'') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/selectpicker/bootstrap-select.min.css?v='.filemtime(getcwd().'/assets/vendors/selectpicker/bootstrap-select.min.css').'') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/growl/jquery.growl.css?v='.filemtime(getcwd().'/assets/vendors/growl/jquery.growl.css').'') }}"/>
<style>
    .accordion-button:not(.collapsed) {
        background-color: #47bbd2;
        color: #fff;
    }
    .accordion-button {
        background-color: #47bbd2;
        color: #fff;
        padding: 5px;
    }
    #other-info-tbl tr{
        background: transparent;
    }
    #other-info-tbl td{
        border-color: black;
        border-style: solid;
        padding: 0 20px;
    }
    #other-info-tbl .pb-3 label{
        width: 210px; 
    }
    #departmental_access + .select3-container{
        display: table;
    }
</style>
<div class="container-fluid card pt-4">
{{ Form::open(array('url' => 'human-resource/employees/store2', 'class'=>'formDtls', 'name' => 'employeeForm')) }}
    <div id="1-form" class="step-contain show" data-step="1">
        <div class="fv-row row hidden">
            <div class="col-sm-12">
                {{ Form::label('id', 'ID', ['class' => 'required fs-6 fw-bold mb-2']) }}
                {{ 
                    Form::text($name = 'id', $data->id, 
                    $attributes = array(
                        'id' => 'id',
                        'class' => 'form-control form-control-solid',
                        'disabled' => 'disabled'
                    )) 
                }}
            </div>
            <div class="validate-err"></div>
        </div>
        <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseBasic" aria-expanded="true" aria-controls="collapseBasic">Personal Information</h4>
        <div class="collapse show mt-2" id="collapseBasic">
            <div class="fv-row row">
                <div class="col-sm-4">
                    <div class="form-group m-form__group required">
                        {{ Form::label('firstname', 'Firstname', ['class' => 'fs-6 fw-bold']) }}<span class="text-danger">*</span>
                        {{ 
                            Form::text($name = 'firstname', $data->firstname, 
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
                            Form::text($name = 'middlename', $data->middlename, 
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
                        {{ Form::label('lastname', 'Lastname', ['class' => 'fs-6 fw-bold']) }}<span class="text-danger">*</span>
                        {{ 
                            Form::text($name = 'lastname', $data->lastname, 
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
                            Form::text($name = 'suffix', $data->suffix, 
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
                            Form::text($name = 'title', $data->title, 
                            $attributes = array(
                                'id' => 'title',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('birthdate', 'Date of Birth', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::date($name = 'birthdate', $data->birthdate, 
                            $attributes = array(
                                'id' => 'birthdate',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('hr_emp_birth_place', 'Place of Birth', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'hr_emp_birth_place', $data->hr_emp_birth_place, 
                            $attributes = array(
                                'id' => 'hr_emp_birth_place',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group required" id="parent_gender">
                        {{ Form::label('gender', 'Gender', ['class' => '']) }}
                        {{
                            Form::select('gender', $gender, $data->gender, ['id' => 'gender', 'class' => 'form-control select3', 'data-placeholder' => 'select a gender'])
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group required" id="parent_civil_status">
                        {{ Form::label('hr_emp_civil_status', 'Civil Status', ['class' => '']) }}
                        {{
                            Form::select('hr_emp_civil_status', $civil_status, $data->hr_emp_civil_status, ['id' => 'hr_emp_civil_status', 'class' => 'form-control select3', 'data-placeholder' => 'select a Civil Status'])
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group m-form__group required" id="citizenship-group">
                        {{ Form::label('hr_emp_citizenship', 'Citizenship', ['class' => 'fs-6 fw-bold']) }}
                        <div class="form-inline">
                        {{ 
                            Form::radio('hr_emp_citizenship', 
                            0,
                            false, 
                            $attributes = array(
                            'id' => 'hr_emp_citizenship_filo',
                            'class' => 'form-check-input ',
                            $data->hr_emp_citizenship === 0 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('hr_emp_citizenship_filo', 'Filipino', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::radio('hr_emp_citizenship', 
                            1, 
                            false,
                            $attributes = array(
                            'id' => 'hr_emp_citizenship_dual',
                            'class' => 'form-check-input ',
                            $data->hr_emp_citizenship === 1 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('hr_emp_citizenship_dual', 'Dual Citizenship', ['class' => 'fs-6 fw-bold mx-2']) }}
                        </div>
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="form-group m-form__group required" id="if-dual-group">
                        {{ Form::label('hr_emp_if_dual', 'If dual citizenship, please indicate the details', ['class' => 'fs-6 fw-bold']) }}
                        <div class="form-inline">
                        {{ 
                            Form::radio('hr_emp_if_dual', 
                            0,
                            false, 
                            $attributes = array(
                            'id' => 'hr_emp_if_dual_birth',
                            'class' => 'form-check-input',
                            $data->hr_emp_citizenship === 1 ? '' : 'disabled',
                            $data->hr_emp_if_dual === 0 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('hr_emp_if_dual_birth', 'By Birth', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::radio('hr_emp_if_dual', 
                            1, 
                            false,
                            $attributes = array(
                            'id' => 'hr_emp_if_dual_naturalization',
                            'class' => 'form-check-input',
                            $data->hr_emp_citizenship === 1 ? '' : 'disabled',
                            $data->hr_emp_if_dual === 1 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('hr_emp_if_dual_naturalization', 'By Naturalization', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ Form::label('hr_emp_if_dual_country', 'Please indicate country: ', ['class' => 'fs-6 fw-bold mx-3']) }}
                        {{ 
                            Form::text($name = 'hr_emp_if_dual_country', $data->hr_emp_if_dual_country, 
                            $attributes = array(
                                'id' => 'hr_emp_if_dual_country',
                                'class' => 'form-control form-control-solid',
                                'style' => 'width:300px',
                                $data->hr_emp_citizenship === 1 ? '' : 'readonly',
                            )) 
                        }}
                        </div>
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('hr_emp_height', 'Height', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'hr_emp_height', $data->hr_emp_height, 
                            $attributes = array(
                                'id' => 'hr_emp_height',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('hr_emp_weight', 'Weight', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'hr_emp_weight', $data->hr_emp_weight, 
                            $attributes = array(
                                'id' => 'hr_emp_weight',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('hr_emp_blood_type', 'Blood Type', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'hr_emp_blood_type', $data->hr_emp_blood_type, 
                            $attributes = array(
                                'id' => 'hr_emp_blood_type',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('mobile_no', 'Mobile No.', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'mobile_no', $data->mobile_no, 
                            $attributes = array(
                                'id' => 'mobile_no',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('telephone_no', 'Telephone No.', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'telephone_no', $data->telephone_no, 
                            $attributes = array(
                                'id' => 'telephone_no',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('fax_no', 'Fax No.', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'fax_no', $data->fax_no, 
                            $attributes = array(
                                'id' => 'fax_no',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('email_address', 'Email Address', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'email_address', $data->email_address, 
                            $attributes = array(
                                'id' => 'email_address',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('hr_emp_gsis_no', 'GSIS No.', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'hr_emp_gsis_no', $data->hr_emp_gsis_no, 
                            $attributes = array(
                                'id' => 'hr_emp_gsis_no',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('pag_ibig_no', 'Pag-ibig No.', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'pag_ibig_no', $data->pag_ibig_no, 
                            $attributes = array(
                                'id' => 'pag_ibig_no',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('philhealth_no', 'Philhealth No.', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'philhealth_no', $data->philhealth_no, 
                            $attributes = array(
                                'id' => 'philhealth_no',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('sss_no', 'SSS No.', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'sss_no', $data->sss_no, 
                            $attributes = array(
                                'id' => 'sss_no',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('tin_no', 'TIN No.', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'tin_no', $data->tin_no, 
                            $attributes = array(
                                'id' => 'tin_no',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('hr_emp_agency_emp_no', 'Agency Employee No.', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'hr_emp_agency_emp_no', $data->hr_emp_agency_emp_no, 
                            $attributes = array(
                                'id' => 'hr_emp_agency_emp_no',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                
            </div>
        </div>

        <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseAdditional" aria-expanded="true" aria-controls="collapseAdditional">Residential Address</h4>
        <div class="collapse show mt-2" id="collapseAdditional">
            <div class="fv-row row">
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('c_house_lot_no', 'Blk / Lot No.', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'c_house_lot_no', $data->c_house_lot_no, 
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
                            Form::text($name = 'c_street_name', $data->c_street_name, 
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
                            Form::text($name = 'c_subdivision', $data->c_subdivision, 
                            $attributes = array(
                                'id' => 'c_subdivision',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
            </div>
            <div class="fv-row row brgy_group">
                <div class="col-sm-4">
                    <div class="form-group m-form__group required " id="contain_barangay_id">
                        {{ Form::label('barangay_id', 'Barangay', ['class' => 'fs-6 fw-bold']) }}
                        {{
                            Form::select('barangay_id', 
                            $barangays, 
                            $data->barangay_id, 
                            [
                                'id' => 'barangay_id', 
                                'class' => 'form-control ajax-select get-barangay', 
                                'data-url' => 'getBarngayNameList',
                                'data-placeholder' => 'select a barangay...'
                            ])
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('municipality', 'City / Municipality', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'municipality', '', 
                            $attributes = array(
                                'id' => 'municipality',
                                'class' => 'form-control form-control-solid select_mun_desc',
                                'disabled'
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('province', 'Province', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'province', '', 
                            $attributes = array(
                                'id' => 'province',
                                'class' => 'form-control form-control-solid select_prov_desc',
                                'disabled'
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('c_zip', 'Zip Code', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'c_zip', $data->c_zip, 
                            $attributes = array(
                                'id' => 'c_zip',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
            </div>
            
        </div>

        <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapsePermanent" aria-expanded="true" aria-controls="collapsePermanent">Permanent Address</h4>
        <div class="collapse show mt-2" id="collapsePermanent">
            <div class="fv-row row">
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ 
                            Form::checkbox('hr_emp_is_same_permanent', 
                            1, 
                            false,
                            $attributes = array(
                            'id' => 'hr_emp_is_same_permanent',
                            'class' => 'form-check-input',
                            $data->hr_emp_is_same_permanent === 1 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('hr_emp_is_same_permanent', 'Click if same as residential address', ['class' => 'fs-6 fw-bold']) }}
                    </div>
                </div>
            </div>
            <div class="fv-row row">
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('hr_emp_house_lot_no_permanent', 'Blk / Lot No.', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'hr_emp_house_lot_no_permanent', $data->hr_emp_house_lot_no_permanent, 
                            $attributes = array(
                                'id' => 'hr_emp_house_lot_no_permanent',
                                'class' => 'form-control form-control-solid',
                                'data-from' => 'c_house_lot_no',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('hr_emp_street_name_permanent', 'Street Name', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'hr_emp_street_name_permanent', $data->hr_emp_street_name_permanent, 
                            $attributes = array(
                                'id' => 'hr_emp_street_name_permanent',
                                'class' => 'form-control form-control-solid',
                                'data-from' => 'c_street_name',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('hr_emp_subdivision_permanent', 'Subdivision / Village Name', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'hr_emp_subdivision_permanent', $data->hr_emp_subdivision_permanent, 
                            $attributes = array(
                                'id' => 'hr_emp_subdivision_permanent',
                                'class' => 'form-control form-control-solid',
                                'data-from' => 'c_subdivision',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
            </div>
            <div class="fv-row row brgy_group">
                <div class="col-sm-4">
                    <div class="form-group m-form__group required" id="contain_hr_emp_brgy_code_permanent">
                        {{ Form::label('hr_emp_brgy_code_permanent', 'Barangay', ['class' => 'fs-6 fw-bold']) }}
                        {{
                            Form::select('hr_emp_brgy_code_permanent', $barangays_perm, $data->hr_emp_brgy_code_permanent, 
                            [
                                'id' => 'hr_emp_brgy_code_permanent', 
                                'class' => 'form-control ajax-select get-barangay', 
                                'data-url' => 'getBarngayNameList',
                                'data-placeholder' => 'select a barangay...',
                                'data-from' => 'barangay_id',
                                ])
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('municipality_perm', 'City / Municipality', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'municipality_perm', '', 
                            $attributes = array(
                                'id' => 'municipality_perm',
                                'class' => 'form-control form-control-solid select_mun_desc disabled',
                                'disabled'
                            )) 
                        }}
                        {{ 
                            Form::hidden($name = 'hr_emp_city_code_permanent', $data->hr_emp_city_code_permanent, 
                            $attributes = array(
                                'id' => 'hr_emp_city_code_permanent',
                                'class' => 'form-control form-control-solid select_mun_no',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('province', 'Province', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'province', $value = '', 
                            $attributes = array(
                                'id' => 'province',
                                'class' => 'form-control form-control-solid select_prov_desc disabled',
                                'disabled'
                            )) 
                        }}
                        {{ 
                            Form::hidden($name = 'hr_emp_province_code_permanent', $data->hr_emp_province_code_permanent, 
                            $attributes = array(
                                'id' => 'hr_emp_province_code_permanent',
                                'class' => 'form-control form-control-solid select_prov_no',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('hr_emp_zip_code_permanent', 'Zip Code', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'hr_emp_zip_code_permanent', $data->hr_emp_zip_code_permanent, 
                            $attributes = array(
                                'id' => 'hr_emp_zip_code_permanent',
                                'class' => 'form-control form-control-solid ' ,
                                'data-from' => 'c_zip',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
            </div>
            
        </div>

        <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseFamily" aria-expanded="true" aria-controls="collapseFamily">Family Background</h4>
        <div class="collapse show mt-2" id="collapseFamily">
            <div class="fv-row row">
                <div class="col-sm-12">
                    <h5>SPOUSE INFORMATION</h5>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('hrefb_spouse_first_name', 'First Name', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'family[hrefb_spouse_first_name]', $emp->getData('hrefb_spouse_first_name','family'), 
                            $attributes = array(
                                'id' => 'hrefb_spouse_first_name',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('hrefb_spouse_middle_name', 'Middle Name', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'family[hrefb_spouse_middle_name]', $emp->getData('hrefb_spouse_middle_name','family'), 
                            $attributes = array(
                                'id' => 'hrefb_spouse_middle_name',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('hrefb_spouse_last_name', 'Last Name', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'family[hrefb_spouse_last_name]', $emp->getData('hrefb_spouse_last_name','family'), 
                            $attributes = array(
                                'id' => 'hrefb_spouse_last_name',
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
                        {{ Form::label('hrefb_spouse_suffix', 'Suffix (Jr., Sr., II, III) ', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'family[hrefb_spouse_suffix]', $emp->getData('hrefb_spouse_suffix','family'), 
                            $attributes = array(
                                'id' => 'hrefb_spouse_suffix',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('hrefb_spouse_occupation', 'Occupation', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'family[hrefb_spouse_occupation]', $emp->getData('hrefb_spouse_occupation','family'), 
                            $attributes = array(
                                'id' => 'hrefb_spouse_occupation',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('hrefb_spouse_employee_business', 'Employee / Business Name', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'family[hrefb_spouse_employee_business]', $emp->getData('hrefb_spouse_employee_business','family'), 
                            $attributes = array(
                                'id' => 'hrefb_spouse_employee_business',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('hrefb_spouse_employee_business_address', 'Business Address', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'family[hrefb_spouse_employee_business_address]', $emp->getData('hrefb_spouse_employee_business_address','family'), 
                            $attributes = array(
                                'id' => 'hrefb_spouse_employee_business_address',
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
                        {{ Form::label('hrefb_spouse_mobile_no', 'Mobile No.', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'family[hrefb_spouse_mobile_no]', $emp->getData('hrefb_spouse_mobile_no','family'), 
                            $attributes = array(
                                'id' => 'hrefb_spouse_mobile_no',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('hrefb_spouse_telephone_no', 'Telephone No.', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'family[hrefb_spouse_telephone_no]', $emp->getData('hrefb_spouse_telephone_no','family'), 
                            $attributes = array(
                                'id' => 'hrefb_spouse_telephone_no',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
            </div>
            <div class="fv-row row">
                <div class="col-sm-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Surname</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Suffix(Jr., Sr., II, III)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>FATHER'S NAME</td>
                                <td>
                                {{ 
                                    Form::text($name = 'family[hrefb_father_last_name]', $emp->getData('hrefb_father_last_name','family'), 
                                    $attributes = array(
                                        'id' => 'hrefb_father_last_name',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'family[hrefb_father_first_name]', $emp->getData('hrefb_father_first_name','family'), 
                                        $attributes = array(
                                            'id' => 'hrefb_father_first_name',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'family[hrefb_father_middle_name]', $emp->getData('hrefb_father_middle_name','family'), 
                                        $attributes = array(
                                            'id' => 'hrefb_father_middle_name',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'family[hrefb_father_suffix]', $emp->getData('hrefb_father_suffix','family'), 
                                        $attributes = array(
                                            'id' => 'hrefb_father_suffix',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <td>MOTHER'S NAME</td>
                                <td>
                                    {{ 
                                        Form::text($name = 'family[hrefb_mother_last_name]', $emp->getData('hrefb_mother_last_name','family'), 
                                        $attributes = array(
                                            'id' => 'hrefb_mother_last_name',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'family[hrefb_mother_first_name]', $emp->getData('hrefb_mother_first_name','family'), 
                                        $attributes = array(
                                            'id' => 'hrefb_mother_first_name',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'family[hrefb_mother_middle_name]', $emp->getData('hrefb_mother_middle_name','family'), 
                                        $attributes = array(
                                            'id' => 'hrefb_mother_middle_name',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'family[hrefb_mother_suffix]', $emp->getData('hrefb_mother_suffix','family'), 
                                        $attributes = array(
                                            'id' => 'hrefb_mother_suffix',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-12 mt-3">
                    <h5>CHILDREN'S NAME

                    <div class="float-end">
                        <a class="btn btn-primary btn-sm mb-3 add-row" data-add="child" data-id="0" data-bs-toggle="tooltip" title="{{__('Add Children')}}">
                            <i class="ti-plus"></i>
                        </a>
                    </div>
                    </h5>
                    
                </div>
                <div class="col-sm-12 mb-3">
                    <table class="table" id="child-tbl">
                        <thead>
                            <tr>
                                <th>Surname</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Suffix(Jr., Sr., II, III)</th>
                                <th>Date of Birth</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="child-list">
                            @foreach($emp->children as $child)
                                <tr class="old-row" id="child-{{$child->id}}">
                                    <td>
                                        {{ 
                                            Form::text($name = 'Children['.$child->id.'][hrec_last_name]', 
                                            $emp->getData('hrec_last_name','child',$child->id), 
                                            $attributes = array(
                                                'id' => 'child_ln_'.$child->id.'',
                                                'class' => 'form-control form-control-solid',
                                            )) 
                                        }}
                                    </td>
                                    <td>
                                        {{ 
                                            Form::text($name = 'Children['.$child->id.'][hrec_first_name]', $emp->getData('hrec_first_name','child',$child->id), 
                                            $attributes = array(
                                                'id' => 'child_fn_'.$child->id.'',
                                                'class' => 'form-control form-control-solid',
                                            )) 
                                        }}
                                    </td>
                                    <td>
                                        {{ 
                                            Form::text($name = 'Children['.$child->id.'][hrec_middle_name]', $emp->getData('hrec_middle_name','child',$child->id), 
                                            $attributes = array(
                                                'id' => 'child_mn_'.$child->id.'',
                                                'class' => 'form-control form-control-solid',
                                            )) 
                                        }}
                                    </td>
                                    <td>
                                        {{ 
                                            Form::text($name = 'Children['.$child->id.'][hrec_suffix]', $emp->getData('hrec_suffix','child',$child->id), 
                                            $attributes = array(
                                                'id' => 'child_suffix_'.$child->id.'',
                                                'class' => 'form-control form-control-solid',
                                            )) 
                                        }}
                                    </td>
                                    <td>
                                        {{ 
                                            Form::date($name = 'Children['.$child->id.'][hrec_date_of_birth]', $emp->getData('hrec_date_of_birth','child',$child->id), 
                                            $attributes = array(
                                                'id' => 'child_bday_'.$child->id.'',
                                                'class' => 'form-control form-control-solid',
                                            )) 
                                        }}
                                    </td>
                                    <td>
                                        
                                        <span>
                                            <a class="btn btn-sm btn-danger remove-row" data-bs-toggle="tooltip" data-type="child" data-id="{{$child->id}}" title="Remove require">
                                                <i class="ti-trash text-white"></i>
                                            </a>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <input type="button" value="{{__('Next')}}" class="btn btn-primary next-btn">
        </div>
    </div>
    <div id="2-form" class="step-contain" data-step="2">
        <!-- Educational Background -->
        <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseEduc" aria-expanded="true" aria-controls="collapseEduc">EDUCATIONAL BACKGROUND</h4>
        <div class="collapse show mt-2" id="collapseEduc">
            <div class="col-sm-12 mb-3">
                <table class="table" id="educ-tbl">
                    <thead>
                        <tr>
                            <th rowspan="2">Level</th>
                            <th rowspan="2">Name of School</th>
                            <th rowspan="2">Basic Education / Degree / </br>Course (write in full)</th>
                            <th colspan="2" class="text-center">PERIOD OF ATTENDANCE</th>
                            <th rowspan="2">Highest Level / </br>Units Earned</br>(if not graduate)</th>
                            <th rowspan="2">Year</br>Graduated</th>
                            <th rowspan="2">Scholarship / Academic</br>Honors Received</th>
                        </tr>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                        </tr>
                    </thead>
                    <tbody id="educ-list">
                        @foreach($educ_background as $id => $title)
                        <tr>
                            <td>
                                {{$title}}
                            </td>
                            <td>
                                {{ 
                                    Form::text($name = 'educ['.$id.'][hree_school]', $emp->getData('hree_school','educ',$id), 
                                    $attributes = array(
                                        'id' => 'educ_service',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                            </td>
                            <td>
                                {{ 
                                    Form::text($name = 'educ['.$id.'][hree_degree]', $emp->getData('hree_degree','educ',$id), 
                                    $attributes = array(
                                        'id' => 'educ_service',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                            </td>
                            <td>
                                {{ 
                                    Form::date($name = 'educ['.$id.'][hree_period_from]', $emp->getData('hree_period_from','educ',$id), 
                                    $attributes = array(
                                        'id' => 'educ_service',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                            </td>
                            <td>
                                {{ 
                                    Form::date($name = 'educ['.$id.'][hree_period_to]', $emp->getData('hree_period_to','educ',$id), 
                                    $attributes = array(
                                        'id' => 'educ_service',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                            </td>
                            <td>
                                {{ 
                                    Form::text($name = 'educ['.$id.'][hree_units]', $emp->getData('hree_units','educ',$id), 
                                    $attributes = array(
                                        'id' => 'educ_service',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                            </td>
                            
                            <td>
                                {{ 
                                    Form::selectYear($name = 'educ['.$id.'][hree_year_grad]', date('Y'), 1900, $emp->getData('hree_year_grad','educ',$id),
                                    $attributes = array(
                                        'id' => 'educ_service'.$id,
                                        'class' => 'form-control select3',
                                    )) 
                                }}
                            </td>
                            <td>
                                {{ 
                                    Form::text($name = 'educ['.$id.'][hree_scholarship]', $emp->getData('hree_scholarship','educ',$id), 
                                    $attributes = array(
                                        'id' => 'educ_service',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Civil Service Eligibility -->
        <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseCivil" aria-expanded="true" aria-controls="collapseCivil">CIVIL SERVICE ELIGIBILITY</h4>
        <div class="collapse show mt-2" id="collapseCivil">
            <div class="col-sm-12 mb-3">
                <table class="table" id="civil-tbl">
                    <thead>
                        <tr>
                            <th rowspan="2">Career Service / </br>RA 1080 (Board/Bar) </br>Under Special Laws / </br>CES / CSEE Barangay Eligibility / </br>Driver's Licence</th>
                            <th rowspan="2">Rating </br>(if applicable)</th>
                            <th rowspan="2">Date of Examination / </br>Conferment</th>
                            <th rowspan="2">Place of Examination / </br>Conferment</th>
                            <th colspan="2" class="text-center">Licence </br>(if applicable)</th>
                            <th rowspan="2" width="5%">
                                <a class="btn btn-primary  add-row" data-id="0" data-bs-toggle="tooltip" data-add="civil" title="{{__('Add Service')}}">
                                    <i class="ti-plus"></i>
                                </a>
                            </th>
                        </tr>
                        <tr>
                            <th>Number</th>
                            <th>Date of Validity</th>
                        </tr>
                    </thead>
                    <tbody id="civil-list">
                        @foreach($emp->civil as $civil)
                            <tr class="new-row" id="civil-{{$civil->id}}">
                                <td>
                                    {{ 
                                        Form::text($name = 'civil['.$civil->id.'][hrecse_service]', $emp->getData('hrecse_service','civil',$civil->id), 
                                        $attributes = array(
                                            'id' => 'civil_service_'.$civil->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'civil['.$civil->id.'][hrecse_rating]', $emp->getData('hrecse_rating','civil',$civil->id), 
                                        $attributes = array(
                                            'id' => 'civil_rating_'.$civil->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::date($name = 'civil['.$civil->id.'][hrecse_date_of_exam]', $emp->getData('hrecse_date_of_exam','civil',$civil->id), 
                                        $attributes = array(
                                            'id' => 'civil_date_exam_'.$civil->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'civil['.$civil->id.'][hrecse_place_of_exam]', $emp->getData('hrecse_place_of_exam','civil',$civil->id), 
                                        $attributes = array(
                                            'id' => 'civil_place_exam_'.$civil->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'civil['.$civil->id.'][hrecse_number]', $emp->getData('hrecse_number','civil',$civil->id), 
                                        $attributes = array(
                                            'id' => 'civil_number_'.$civil->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::date($name = 'civil['.$civil->id.'][hrecse_valid_date]', $emp->getData('hrecse_valid_date','civil',$civil->id), 
                                        $attributes = array(
                                            'id' => 'civil_valid_date_'.$civil->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    <span>
                                        <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" data-type="civil" data-id="{{$civil->id}}" title="Remove require">
                                            <i class="ti-trash text-white"></i>
                                        </a>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Work Experiences -->
        <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseWork" aria-expanded="true" aria-controls="collapseWork">WORK EXPERIENCES <small>(include private employment. Start from your recent work)</small> </h4>
        <div class="collapse show mt-2" id="collapseWork">
            <div class="col-sm-12 mb-3">
                <table class="table" id="work-tbl">
                    <thead>
                        <tr>
                            <th colspan="2">Inclusive Dates</br>(mm/dd/yyyy)</th>
                            <th rowspan="2">Position Title</br>(Write in full/ </br>Do not abbreviete)</th>
                            <th rowspan="2">Department / Agency / Office / Company</br>(Write in full/Do not abbreviete)</th>
                            <th rowspan="2">Monthly</br>Salary</th>
                            <th rowspan="2" class="text-center">Salary / Job / </br>Pay Grade (if applicable) </br>& Step (Format '00-0')/</br>Increment</th>
                            <th rowspan="2">Status of</br>Appointment</th>
                            <th rowspan="2" width="5%">Gov't</br>Service</br>(Y/N)</th>
                            <th rowspan="2" width="5%">
                                <a class="btn btn-primary  add-row" data-id="0" data-bs-toggle="tooltip" data-add="work" title="{{__('Add Service')}}">
                                    <i class="ti-plus"></i>
                                </a>
                            </th>
                        </tr>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                        </tr>
                    </thead>
                    <tbody id="work-list">
                        @foreach($emp->work as $work)
                            <tr class="old-row" id="work-{{$work->id}}">
                                <td>
                                    {{ 
                                        Form::date($name = 'work['.$work->id.'][hrewe_inclusive_from]', $emp->getData('hrewe_inclusive_from','work',$work->id), 
                                        $attributes = array(
                                            'id' => 'work_inc_from_'.$work->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::date($name = 'work['.$work->id.'][hrewe_inclusive_to]', $emp->getData('hrewe_inclusive_to','work',$work->id), 
                                        $attributes = array(
                                            'id' => 'work_inc_to_'.$work->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'work['.$work->id.'][hrewe_position_title]', $emp->getData('hrewe_position_title','work',$work->id), 
                                        $attributes = array(
                                            'id' => 'work_position_'.$work->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'work['.$work->id.'][hrewe_company]', $emp->getData('hrewe_company','work',$work->id), 
                                        $attributes = array(
                                            'id' => 'work_company_'.$work->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'work['.$work->id.'][hrewe_monthly_salary]', $emp->getData('hrewe_monthly_salary','work',$work->id), 
                                        $attributes = array(
                                            'id' => 'work_salary_'.$work->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'work['.$work->id.'][hrewe_salary_grade]', $emp->getData('hrewe_salary_grade','work',$work->id), 
                                        $attributes = array(
                                            'id' => 'work_grade_'.$work->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'work['.$work->id.'][hrewe_appointment_status]', $emp->getData('hrewe_appointment_status','work',$work->id), 
                                        $attributes = array(
                                            'id' => 'work_status_'.$work->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                    Form::select('work['.$work->id.'][hrewe_gov_service]', 
                                    [ 
                                        1 => 'Yes',
                                        0 => 'No',
                                    ], 
                                    $emp->getData('hrewe_gov_service','work',$work->id), 
                                    [
                                        'id' => 'work_gov_service_'.$work->id.'', 
                                        'class' => 'form-control select3', 
                                        'data-placeholder' => 'Yes or No'
                                    ])
                                    }}
                                </td>
                                <td>
                                    <span>
                                        <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" data-type="work" data-id="{{$work->id}}" title="Remove require">
                                            <i class="ti-trash text-white"></i>
                                        </a>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Voluntary Work -->
        <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseVolunteer" aria-expanded="true" aria-controls="collapseVolunteer">VOLUNTARY WORK OR INVOLVEMENT IN CIVIC / NON GOVERNMENT / PEOPLE / VOLUNTARY ORGANIZATIONS</h4>
        <div class="collapse show mt-2" id="collapseVolunteer">
            <div class="col-sm-12 mb-3">
                <table class="table" id="voluntary-tbl">
                    <thead>
                        <tr>
                            <th rowspan="2">Name of Organization</th>
                            <th rowspan="2">Address of Organization</th>
                            <th colspan="2">Inclusive Dates</br>(mm/dd/yyyy)</th>
                            <th rowspan="2">Number of Hours</th>
                            <th rowspan="2">Position / Nature of Work</th>
                            <th rowspan="2" width="5%">
                                <a class="btn btn-primary  add-row" data-id="0" data-bs-toggle="tooltip" data-add="voluntary" title="{{__('Add Service')}}">
                                    <i class="ti-plus"></i>
                                </a>
                            </th>
                        </tr>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                        </tr>
                    </thead>
                    <tbody id="voluntary-list">
                        @foreach($emp->voluntary as $voluntary)
                            <tr class="old-row" id="voluntary-{{$voluntary->id}}">
                                <td>
                                    {{ 
                                        Form::text($name = 'voluntary['.$voluntary->id.'][hrevw_org_name]', $emp->getData('hrevw_org_name','voluntary',$voluntary->id), 
                                        $attributes = array(
                                            'id' => 'voluntary_name_'.$voluntary->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'voluntary['.$voluntary->id.'][hrevw_org_address]', $emp->getData('hrevw_org_address','voluntary',$voluntary->id), 
                                        $attributes = array(
                                            'id' => 'voluntary_add_'.$voluntary->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::date($name = 'voluntary['.$voluntary->id.'][hrevw_inclusive_from]', $emp->getData('hrevw_inclusive_from','voluntary',$voluntary->id), 
                                        $attributes = array(
                                            'id' => 'voluntary_from_'.$voluntary->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::date($name = 'voluntary['.$voluntary->id.'][hrevw_inclusive_to]', $emp->getData('hrevw_inclusive_to','voluntary',$voluntary->id), 
                                        $attributes = array(
                                            'id' => 'voluntary_to_'.$voluntary->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'voluntary['.$voluntary->id.'][hrevw_hours]', $emp->getData('hrevw_hours','voluntary',$voluntary->id), 
                                        $attributes = array(
                                            'id' => 'voluntary_hrs_'.$voluntary->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'voluntary['.$voluntary->id.'][hrevw_position]', $emp->getData('hrevw_position','voluntary',$voluntary->id), 
                                        $attributes = array(
                                            'id' => 'voluntary_position_'.$voluntary->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    <span>
                                        <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" data-type="voluntary" data-id="{{$voluntary->id}}" title="Remove require">
                                            <i class="ti-trash text-white"></i>
                                        </a>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Training -->
        <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseTraining" aria-expanded="true" aria-controls="collapseTraining">LEARNING AND DEVELOPMENT (L&D) INTERVENTIONS / TRAINING PROGRAMS ATTENDED</h4>
        <div class="collapse show mt-2" id="collapseTraining">
            <div class="col-sm-12 mb-3">
                <table class="table" id="training-tbl">
                    <thead>
                        <tr>
                            <th rowspan="2">Name of Organization</th>
                            <th colspan="2">Inclusive Dates</br>(mm/dd/yyyy)</th>
                            <th rowspan="2">Type of I.D</br>(Managerial / Supervisory</br>Technical / Etc.)</th>
                            <th rowspan="2">Conducted / Sponsored By</br>(Write in full)</th>
                            <th rowspan="2"  width="5%">
                                <a class="btn btn-primary  add-row" data-id="0" data-bs-toggle="tooltip" data-add="training" title="{{__('Add Service')}}">
                                    <i class="ti-plus"></i>
                                </a>
                            </th>
                        </tr>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                        </tr>
                    </thead>
                    <tbody id="training-list">
                        @foreach($emp->training as $training)
                            <tr class="old-row" id="training-{{$training->id}}">
                                <td>
                                    {{ 
                                        Form::text($name = 'training['.$training->id.'][hretp_org_name]', $value = $emp->getData('hretp_org_name','training',$training->id), 
                                        $attributes = array(
                                            'id' => 'training_name_'.$training->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::date($name = 'training['.$training->id.'][hretp_inclusive_from]', $emp->getData('hretp_inclusive_from','training',$training->id), 
                                        $attributes = array(
                                            'id' => 'training_from_'.$training->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::date($name = 'training['.$training->id.'][hretp_inclusive_to]', $emp->getData('hretp_inclusive_to','training',$training->id), 
                                        $attributes = array(
                                            'id' => 'training_to_'.$training->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'training['.$training->id.'][hretp_id_type]', $emp->getData('hretp_id_type','training',$training->id), 
                                        $attributes = array(
                                            'id' => 'training_idtype_'.$training->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'training['.$training->id.'][hretp_sponsored_by]', $emp->getData('hretp_sponsored_by','training',$training->id), 
                                        $attributes = array(
                                            'id' => 'training_sponsore_'.$training->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    <span>
                                        <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" data-type="training" data-id="{{$training->id}}" title="Remove require">
                                            <i class="ti-trash text-white"></i>
                                        </a>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Skills -->
        <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseSkills" aria-expanded="true" aria-controls="collapseSkills">SPECIAL SKILLS AND HOBBIES</h4>
        <div class="collapse show mt-2" id="collapseSkills">
            <div class="col-sm-12 mb-3">
                <table class="table" id="skills-tbl">
                    <thead>
                        <tr>
                            <th rowspan="2">DESCRIPTION</th>
                            <th rowspan="2" width="5%">
                                <a class="btn btn-primary  add-row" data-id="0" data-bs-toggle="tooltip" data-add="skills" title="{{__('Add Service')}}">
                                    <i class="ti-plus"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="skills-list">
                        @foreach($emp->skills as $skill)
                            <tr class="new-row" id="skills-{{$skill->id}}">
                                <td>
                                    {{ 
                                        Form::text($name = 'skills['.$skill->id.'][hreh_description]', $emp->getData('hreh_description','skill',$skill->id), 
                                        $attributes = array(
                                            'id' => 'skills_desc_'.$skill->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    <span>
                                        <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" data-type="skill" data-id="{{$skill->id}}" title="Remove require">
                                            <i class="ti-trash text-white"></i>
                                        </a>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RECOGNITION -->
        <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseRecognition" aria-expanded="true" aria-controls="collapseRecognition">NON ACADEMIC DISTINCTIONS / RECOGNITION</h4>
        <div class="collapse show mt-2" id="collapseRecognition">
            <div class="col-sm-12 mb-3">
                <table class="table" id="recognition-tbl">
                    <thead>
                        <tr>
                            <th rowspan="2">DESCRIPTION</th>
                            <th rowspan="2" width="5%">
                                <a class="btn btn-primary  add-row" data-id="0" data-bs-toggle="tooltip" data-add="recognition" title="{{__('Add Service')}}">
                                    <i class="ti-plus"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="recognition-list">
                        @foreach($emp->recognition as $recognition)
                            <tr class="old-row" id="recognitions-{{$recognition->id}}">
                                <td>
                                    {{ 
                                        Form::text($name = 'recognitions['.$recognition->id.'][hrer_description]', $emp->getData('hrer_description','recognition',$recognition->id), 
                                        $attributes = array(
                                            'id' => 'recognition_desc_'.$recognition->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    <span>
                                        <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" data-type="recognition" data-id="{{$recognition->id}}" title="Remove require">
                                            <i class="ti-trash text-white"></i>
                                        </a>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ORGANIZATION -->
        <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOrgs" aria-expanded="true" aria-controls="collapseOrgs">MEMBERSHIP IN ASSOCATION / ORGANIZATION</h4>
        <div class="collapse show mt-2" id="collapseOrgs">
            <div class="col-sm-12 mb-3">
                <table class="table" id="orgs-tbl">
                    <thead>
                        <tr>
                            <th rowspan="2">DESCRIPTION</th>
                            <th rowspan="2" width="5%">
                                <a class="btn btn-primary  add-row" data-id="0" data-bs-toggle="tooltip" data-add="orgs" title="{{__('Add Service')}}">
                                    <i class="ti-plus"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="orgs-list">
                        @foreach($emp->orgs as $org)
                            <tr class="old-row" id="orgs-{{$org->id}}">
                                <td>
                                    {{ 
                                        Form::text($name = 'orgs['.$org->id.'][hreo_description]', $org->hreo_description, 
                                        $attributes = array(
                                            'id' => 'org_desc_'.$org->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    <span>
                                        <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" data-type="org" data-id="{{$org->id}}" title="Remove require">
                                            <i class="ti-trash text-white"></i>
                                        </a>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal-footer">
            <input type="button" value="{{__('Back')}}" class="btn  btn-light back-btn">
            <input type="button" value="{{__('Next')}}" class="btn btn-primary next-btn">
        </div>
    </div>
    <div id="3-form" class="step-contain" data-step="3">
        <table class="mb-4" id="other-info-tbl">
            
            <tr>
                <td class="pt-3" style="border-width: 1 1 0 1;">
                Related by consanguinity or affinity to the appointing or recommending authority, or to the chief of bureau or office or to the person who immediate supervision over you in the office, bureau or Department where you will be appointed
                </td>
                <td width="30%" style="border-width: 1 1 0 1;">
                </td>
            </tr>
            <tr>
                <td style="border-width: 0 1 0 1;">
                    <ol type="A">
                        <li>within the third degree?</li>
                    </ol>
                </td>
                <td style="border-width: 0 1 0 1;">
                    <div class="form-inline">
                        {{ 
                            Form::radio('other[1][hreo_yes_no]', 
                            1, 
                            false,
                            $attributes = array(
                            'id' => 'third_degree_yes',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',1) === 1 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('third_degree_yes', 'Yes', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::radio('other[1][hreo_yes_no]', 
                            0,
                            false, 
                            $attributes = array(
                            'id' => 'third_degree_no',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',1) === 0 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('third_degree_no', 'No', ['class' => 'fs-6 fw-bold mx-2']) }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-width: 0 1 0 1;">
                    <ol type="A" start="2">
                        <li>within the fourth degree?</li>
                    </ol>
                </td>
                <td style="border-width: 0 1 0 1;">
                    <div class="form-inline">
                        {{ 
                            Form::radio('other[2][hreo_yes_no]', 
                            1, 
                            false,
                            $attributes = array(
                            'id' => 'fourth_degree_yes',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',2) === 1 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('fourth_degree_yes', 'Yes', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::radio('other[2][hreo_yes_no]', 
                            0,
                            false, 
                            $attributes = array(
                            'id' => 'fourth_degree_no',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',2) === 0 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('fourth_degree_no', 'No', ['class' => 'fs-6 fw-bold mx-2']) }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-width: 0 1 1 1;">
                </td>
                <td style="border-width: 0 1 1 1;">
                    <div class="form-inline pb-3">
                        
                        {{ Form::label('fourth_degree_yes', 'If Yes, give details', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::text($name = 'other[2][hreo_details]', $emp->getData('hreo_details','other',2), 
                            $attributes = array(
                                'id' => 'other_details_2',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                    </div>
                </td>
            </tr>
            
            <tr>
                <td class="pt-4" style="border-width: 1 1 0 1;">
                    Found guilty of any administrative offense?
                </td>
                <td style="border-width: 1 1 0 1;">
                    <div class="form-inline">
                        {{ 
                            Form::radio('other[3][hreo_yes_no]', 
                            1, 
                            false,
                            $attributes = array(
                            'id' => 'guilty_yes_3',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',3) === 1 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_yes_3', 'Yes', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::radio('other[3][hreo_yes_no]', 
                            0,
                            false, 
                            $attributes = array(
                            'id' => 'guilty_no_3',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',3) === 0 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_no_3', 'No', ['class' => 'fs-6 fw-bold mx-2']) }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-width: 0 1 1 1;">
                </td>
                <td style="border-width: 0 1 1 1;">
                    <div class="form-inline pb-3">
                        
                        {{ Form::label('guilty_details_3', 'If Yes, give details', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::text($name = 'other[3][hreo_details]', $emp->getData('hreo_details','other',3), 
                            $attributes = array(
                                'id' => 'guilty_details_3',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                    </div>
                </td>
            </tr>

            <tr>
                <td class="pt-4" style="border-width: 1 1 0 1;">
                    Criminally charged before any court?
                </td>
                <td style="border-width: 1 1 0 1;">
                    <div class="form-inline">
                        {{ 
                            Form::radio('other[4][hreo_yes_no]', 
                            1, 
                            false,
                            $attributes = array(
                            'id' => 'guilty_yes_4',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',4) === 1 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_yes_4', 'Yes', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::radio('other[4][hreo_yes_no]', 
                            0,
                            false, 
                            $attributes = array(
                            'id' => 'guilty_no_4',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',4) === 0 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_no_4', 'No', ['class' => 'fs-6 fw-bold mx-2']) }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-width: 0 1 0 1;">
                </td>
                <td style="border-width: 0 1 0 1;">
                    <div class="form-inline pb-3">
                        <!-- get back -->
                        @php
                            $details = explode('$_$',$emp->getData('hreo_details','other',4));
                            if(!is_array($details)){
                                $details = [];
                            }
                        @endphp
                        {{ Form::label('guilty_details_4', 'If Yes, give details', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::text($name = 'other[4][hreo_details][1]', isset($details[0]) ? $details[0] : '', 
                            $attributes = array(
                                'id' => 'guilty_details_4',
                                'class' => 'form-control form-control-solid',
                                'width' => '200px'
                            )) 
                        }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-width: 0 1 0 1;">
                </td>
                <td style="border-width: 0 1 0 1;">
                    <div class="form-inline pb-3">
                        {{ Form::label('guilty_details_4', 'Date filed', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::date($name = 'other[4][hreo_details][2]', isset($details[1]) ? $details[1] : '', 
                            $attributes = array(
                                'id' => 'guilty_details_42',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-width: 0 1 1 1;">
                </td>
                <td style="border-width: 0 1 1 1;">
                    <div class="form-inline pb-3">
                        {{ Form::label('guilty_details_4', 'Status of Cases', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::text($name = 'other[4][hreo_details][3]', isset($details[2]) ? $details[2] : '', 
                            $attributes = array(
                                'id' => 'guilty_details_43',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                    </div>
                </td>
            </tr>

            <tr>
                <td class="pt-4" style="border-width: 1 1 0 1;">
                Convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?
                </td>
                <td style="border-width: 1 1 0 1;">
                    <div class="form-inline">
                        {{ 
                            Form::radio('other[5][hreo_yes_no]', 
                            1, 
                            false,
                            $attributes = array(
                            'id' => 'guilty_yes_5',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',5) === 1 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_yes_5', 'Yes', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::radio('other[5][hreo_yes_no]', 
                            0,
                            false, 
                            $attributes = array(
                            'id' => 'guilty_no_5',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',5) === 0 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_no_5', 'No', ['class' => 'fs-6 fw-bold mx-2']) }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-width: 0 1 1 1;">
                </td>
                <td style="border-width: 0 1 1 1;">
                    <div class="form-inline pb-3">
                        
                        {{ Form::label('guilty_details_5', 'If Yes, give details', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::text($name = 'other[5][hreo_details]', $emp->getData('hreo_details','other',5), 
                            $attributes = array(
                                'id' => 'guilty_details_5',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                    </div>
                </td>
            </tr>

            <tr>
                <td class="pt-4" style="border-width: 1 1 0 1;">
                Separated from the service in any of the following modes : resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?
                </td>
                <td style="border-width: 1 1 0 1;">
                    <div class="form-inline">
                        {{ 
                            Form::radio('other[6][hreo_yes_no]', 
                            1, 
                            false,
                            $attributes = array(
                            'id' => 'guilty_yes_6',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',6) === 1 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_yes_6', 'Yes', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::radio('other[6][hreo_yes_no]', 
                            0,
                            false, 
                            $attributes = array(
                            'id' => 'guilty_no_6',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',6) === 0 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_no_6', 'No', ['class' => 'fs-6 fw-bold mx-2']) }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-width: 0 1 1 1;">
                </td>
                <td style="border-width: 0 1 1 1;">
                    <div class="form-inline pb-3">
                        
                        {{ Form::label('guilty_details_6', 'If Yes, give details', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::text($name = 'other[6][hreo_details]', $emp->getData('hreo_details','other',6), 
                            $attributes = array(
                                'id' => 'guilty_details_6',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                    </div>
                </td>
            </tr>
            
            <tr>
                <td class="pt-4" style="border-width: 1 1 0 1;">
                Candidate in a national or local election held within the last year (except Barangay Election)?
                </td>
                <td style="border-width: 1 1 0 1;">
                    <div class="form-inline">
                        {{ 
                            Form::radio('other[7][hreo_yes_no]', 
                            1, 
                            false,
                            $attributes = array(
                            'id' => 'guilty_yes_7',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',7) === 1 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_yes_7', 'Yes', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::radio('other[7][hreo_yes_no]', 
                            0,
                            false, 
                            $attributes = array(
                            'id' => 'guilty_no_7',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',7) === 0 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_no_7', 'No', ['class' => 'fs-6 fw-bold mx-2']) }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-width: 0 1 1 1;">
                </td>
                <td style="border-width: 0 1 1 1;">
                    <div class="form-inline pb-3">
                        
                        {{ Form::label('guilty_details_7', 'If Yes, give details', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::text($name = 'other[7][hreo_details]', $emp->getData('hreo_details','other',7), 
                            $attributes = array(
                                'id' => 'guilty_details_7',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                    </div>
                </td>
            </tr>

            <tr>
                <td class="pt-4" style="border-width: 1 1 0 1;">
                Resigned from the government service during the three(3) month period before the last election to promote/ actively campaign for a national or local candidate?
                </td>
                <td style="border-width: 1 1 0 1;">
                    <div class="form-inline">
                        {{ 
                            Form::radio('other[8][hreo_yes_no]', 
                            1, 
                            false,
                            $attributes = array(
                            'id' => 'guilty_yes_8',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',8) === 1 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_yes_8', 'Yes', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::radio('other[8][hreo_yes_no]', 
                            0,
                            false, 
                            $attributes = array(
                            'id' => 'guilty_no_8',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',8) === 0 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_no_8', 'No', ['class' => 'fs-6 fw-bold mx-2']) }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-width: 0 1 1 1;">
                </td>
                <td style="border-width: 0 1 1 1;">
                    <div class="form-inline pb-3">
                        
                        {{ Form::label('guilty_details_8', 'If Yes, give details', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::text($name = 'other[8][hreo_details]', $emp->getData('hreo_details','other',8), 
                            $attributes = array(
                                'id' => 'guilty_details_8',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                    </div>
                </td>
            </tr>

            <tr>
                <td class="pt-4" style="border-width: 1 1 0 1;">
                Acquired the status of an immigrant or permanent resident of another country?
                </td>
                <td style="border-width: 1 1 0 1;">
                    <div class="form-inline">
                        {{ 
                            Form::radio('other[9][hreo_yes_no]', 
                            1, 
                            false,
                            $attributes = array(
                            'id' => 'guilty_yes_9',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',9) === 1 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_yes_9', 'Yes', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::radio('other[9][hreo_yes_no]', 
                            0,
                            false, 
                            $attributes = array(
                            'id' => 'guilty_no_9',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',9) === 0 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_no_9', 'No', ['class' => 'fs-6 fw-bold mx-2']) }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-width: 0 1 1 1;">
                </td>
                <td style="border-width: 0 1 1 1;">
                    <div class="form-inline pb-3">
                        
                        {{ Form::label('guilty_details_9', 'If Yes, give details (country)', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::text($name = 'other[9][hreo_details]', $emp->getData('hreo_details','other',9), 
                            $attributes = array(
                                'id' => 'guilty_details_9',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                    </div>
                </td>
            </tr>

            <tr>
                <td class="pt-4" style="border-width: 1 1 0 1;">
                Member of any indigenous group?
                </td>
                <td style="border-width: 1 1 0 1;">
                    <div class="form-inline">
                        {{ 
                            Form::radio('other[10][hreo_yes_no]', 
                            1, 
                            false,
                            $attributes = array(
                            'id' => 'guilty_yes_10',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',10) === 1 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_yes_10', 'Yes', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::radio('other[10][hreo_yes_no]', 
                            0,
                            false, 
                            $attributes = array(
                            'id' => 'guilty_no_10',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',10) === 0 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_no_10', 'No', ['class' => 'fs-6 fw-bold mx-2']) }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-width: 0 1 1 1;">
                </td>
                <td style="border-width: 0 1 1 1;">
                    <div class="form-inline pb-3">
                        
                        {{ Form::label('guilty_details_10', 'If Yes, please specify', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::text($name = 'other[10][hreo_details]', $emp->getData('hreo_details','other',10), 
                            $attributes = array(
                                'id' => 'guilty_details_10',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                    </div>
                </td>
            </tr>

            <tr>
                <td class="pt-4" style="border-width: 1 1 0 1;">
                Person with disability?
                </td>
                <td style="border-width: 1 1 0 1;">
                    <div class="form-inline">
                        {{ 
                            Form::radio('other[11][hreo_yes_no]', 
                            1, 
                            false,
                            $attributes = array(
                            'id' => 'guilty_yes_11',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',11) === 1 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_yes_11', 'Yes', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::radio('other[11][hreo_yes_no]', 
                            0,
                            false, 
                            $attributes = array(
                            'id' => 'guilty_no_11',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',11) === 0 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_no_11', 'No', ['class' => 'fs-6 fw-bold mx-2']) }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-width: 0 1 1 1;">
                </td>
                <td style="border-width: 0 1 1 1;">
                    <div class="form-inline pb-3">
                        
                        {{ Form::label('guilty_details_11', 'If Yes, ID No.', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::text($name = 'other[11][hreo_details]', $emp->getData('hreo_details','other',11), 
                            $attributes = array(
                                'id' => 'guilty_details_11',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                    </div>
                </td>
            </tr>

            <tr>
                <td class="pt-4" style="border-width: 1 1 0 1;">
                Solo Parent?
                </td>
                <td style="border-width: 1 1 0 1;">
                    <div class="form-inline">
                        {{ 
                            Form::radio('other[12][hreo_yes_no]', 
                            1, 
                            false,
                            $attributes = array(
                            'id' => 'guilty_yes_12',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',12) === 1 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_yes_12', 'Yes', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::radio('other[12][hreo_yes_no]', 
                            0,
                            false, 
                            $attributes = array(
                            'id' => 'guilty_no_12',
                            'class' => 'form-check-input ',
                            $emp->getData('hreo_yes_no','other',12) === 0 ? 'checked' : ''
                            )) 
                        }}
                        {{ Form::label('guilty_no_12', 'No', ['class' => 'fs-6 fw-bold mx-2']) }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-width: 0 1 1 1;">
                </td>
                <td style="border-width: 0 1 1 1;">
                    <div class="form-inline pb-3">
                        
                        {{ Form::label('guilty_details_12', 'If Yes, ID No.', ['class' => 'fs-6 fw-bold mx-2']) }}
                        {{ 
                            Form::text($name = 'other[12][hreo_details]', $emp->getData('hreo_details','other',12), 
                            $attributes = array(
                                'id' => 'guilty_details_12',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                    </div>
                </td>
            </tr>
        </table>
        <!-- REFERENCES  -->
        <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseRef" aria-expanded="true" aria-controls="collapseRef">REFERENCES <small>(Person not related by consanguinity or affinity to applicant / appointee)</small></h4>
        <div class="collapse show mt-2" id="collapseRef">
            <div class="col-sm-12 mb-3">
                <table class="table" id="reference-tbl">
                    <thead>
                        <tr>
                            <th >Name</th>
                            <th >Address</th>
                            <th >Contact No.</th>
                            <th  width="5%">
                                <a class="btn btn-primary  add-row" data-id="0" data-bs-toggle="tooltip" data-add="reference" title="{{__('Add Service')}}">
                                    <i class="ti-plus"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="reference-list">
                        @foreach($emp->reference as $reference)
                            <tr class="new-row" id="reference-{{$reference->id}}">
                                <td>
                                    {{ 
                                        Form::text($name = 'reference['.$reference->id.'][hreo_name]', $emp->getData('hreo_name','reference',$reference->id), 
                                        $attributes = array(
                                            'id' => 'reference_name_'.$reference->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'reference['.$reference->id.'][hreo_address]', $emp->getData('hreo_address','reference',$reference->id), 
                                        $attributes = array(
                                            'id' => 'reference_add_'.$reference->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    {{ 
                                        Form::text($name = 'reference['.$reference->id.'][hreo_contact_no]', $emp->getData('hreo_contact_no','reference',$reference->id), 
                                        $attributes = array(
                                            'id' => 'reference_contact_'.$reference->id.'',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                </td>
                                <td>
                                    <span>
                                        <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" data-type="reference" data-id="{{$reference->id}}" title="Remove require">
                                            <i class="ti-trash text-white"></i>
                                        </a>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <input type="button" value="{{__('Back')}}" class="btn  btn-light back-btn">
            <input type="button" value="{{__('Next')}}" class="btn btn-primary next-btn">
        </div>
    </div>
    <div id="4-form" class="step-contain" data-step="4">
        
        <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseAppointment" aria-expanded="true" aria-controls="collapseAppointment">Appointment</h4>
        <div class="collapse show mt-2" id="collapseAppointment">
            <div class="row">
                <div class="col-sm-4 ">
                    <div class="form-group m-form__group">
                        {{ Form::label('hra_date_hired', __('Date Hired'),['class'=>'']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{ Form::date(
                                'appoint[hra_date_hired]',
                                $emp->getData('hra_date_hired','appoint'), 
                                array(
                                    'class' => 'form-control',
                                    'id'=>'hra_date_hired'
                                    )) }}
                        </div>
                        <span class="validate-err" id="err_hra_date_hired"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('hres_id', __('Employment Status'),['class'=>'']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{ Form::select('appoint[hres_id]',
                                $emp_status,
                                $emp->getData('hres_id','appoint'), 
                                array(
                                    'class' => 'form-control select3',
                                    'id'=>'hres_id'
                                )) }}
                        </div>
                        <span class="validate-err" id="err_hres_id"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('hras_id', __('Appointment Status'),['class'=>'']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{ Form::select('appoint[hras_id]',
                                $emp_appointment_status,
                                $emp->getData('hras_id','appoint'), 
                                array(
                                    'class' => 'form-control select3',
                                    'id'=>'hras_id'
                                )) }}
                        </div>
                        <span class="validate-err" id="err_hras_id"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('hrpt_id', __('Payment Term'),['class'=>'']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{ Form::select('appoint[hrpt_id]',
                                $emp_pay_term,
                                $emp->getData('hrpt_id','appoint'), 
                                array(
                                    'class' => 'form-control select3',
                                    'id'=>'hrpt_id'
                                )) }}
                        </div>
                        <span class="validate-err" id="err_hrpt_id"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('hrol_id', __('Occupational Level'),['class'=>'']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{ Form::select('appoint[hrol_id]',
                                $emp_occupation_lvl,
                                $emp->getData('hrol_id','appoint'), 
                                array(
                                    'class' => 'form-control select3',
                                    'id'=>'hrol_id'
                                )) }}
                        </div>
                        <span class="validate-err" id="err_hrol_id"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('hrsg_id', __('Salary Grade'),['class'=>'']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{ Form::select('appoint[hrsg_id]',
                                $emp_salary_grade,
                                $emp->getData('hrsg_id','appoint'), 
                                array(
                                    'class' => 'form-control select3 select_salary',
                                    'id'=>'hrsg_id'
                                )) }}
                        </div>
                        <span class="validate-err" id="err_hrsg_id"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('hrsgs_id', __('Step'),['class'=>'']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{ Form::select('appoint[hrsgs_id]',
                                $emp_salary_step,
                                $emp->getData('hrsgs_id','appoint'), 
                                array(
                                    'class' => 'form-control select3 select_salary',
                                    'id'=>'hrsgs_id'
                                )) }}
                        </div>
                        <span class="validate-err" id="err_hrsgs_id"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('hra_monthly_rate', __('Monthly Rate'),['class'=>'']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{ Form::text('appoint[hra_monthly_rate]',
                                $emp->getData('hra_monthly_rate','appoint'), 
                                array(
                                    'class' => 'form-control ',
                                    'id'=>'hra_monthly_rate',
                                    'readonly'
                                )) }}
                        </div>
                        <span class="validate-err" id="err_hra_monthly_rate"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group m-form__group">
                        {{ Form::label('hra_annual_rate', __('Annual Rate'),['class'=>'']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{ Form::text('appoint[hra_annual_rate]',
                                $emp->getData('hra_annual_rate','appoint'), 
                                array(
                                    'class' => 'form-control',
                                    'id'=>'hra_annual_rate',
                                    'readonly'
                                )) }}
                        </div>
                        <span class="validate-err" id="err_hra_annual_rate"></span>
                    </div>
                </div>
            </div>
        </div>
        <h4 class="text-header accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOther" aria-expanded="true" aria-controls="collapseOther">Other Information</h4>
        <div class="collapse show mt-2" id="collapseOther">
            <div class="fv-row row">
                <div class="col-sm-6">
                    <div class="form-group m-form__group required">
                        {{ Form::label('acctg_department_id', 'Department', ['class' => '']) }}
                        {{
                            Form::select('acctg_department_id', $departments, $data->acctg_department_id, ['id' => 'acctg_department_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a department'])
                        }}
                        <span class="m-form__help text-danger" id="err_acctg_department_id"></span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group m-form__group required" id="contain_acctg_department_division_id">
                        {{ Form::label('acctg_department_division_id', 'Division', ['class' => '']) }}
                        {{
                            Form::select('acctg_department_division_id', $divisions, $data->acctg_department_division_id, ['id' => 'acctg_department_division_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a division'])
                        }}
                        <span class="m-form__help text-danger" id="err_acctg_department_division_id"></span>
                    </div>
                </div>
            </div>
            <div class="fv-row row">
                <div class="col-sm-6">
                    <div class="form-group m-form__group required">
                        {{ Form::label('hr_designation_id', 'Designation', ['class' => '']) }}
                        {{
                            Form::select('hr_designation_id', $designations, $data->hr_designation_id, ['id' => 'hr_designation_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a designation'])
                        }}
                        <span class="m-form__help text-danger" id="err_hr_designation_id"></span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group m-form__group required">
                        {{ Form::label('identification_no', 'ID No', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text($name = 'identification_no', $data->identification_no, 
                            $attributes = array(
                                'id' => 'identification_no',
                                'class' => 'form-control form-control-solid',
                            )) 
                        }}
                        <span class="m-form__help text-danger" id="err_identification_no"></span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group m-form__group required">
                        {{ Form::label('is_dept_restricted', 'Department Restriction', ['class' => '']) }}
                        {{
                            Form::select('is_dept_restricted', $restrictions, $data->is_dept_restricted, ['id' => 'is_dept_restricted', 'class' => 'form-control select3', 'data-placeholder' => 'select a department restriction'])
                        }}
                        <span class="m-form__help text-danger" id="err_is_dept_restricted"></span>
                    </div>
                </div>
            </div>
            <div class="fv-row row">
                <div class="col-sm-12">
                    <div class="form-group m-form__group">
                        
                        {{ Form::label('departmental_access', 'Departmental Access', ['class' => '']) }}
                        {{
                            Form::select(
                                'departmental_access[]', 
                                $access, 
                                $emp->department_access->pluck('department_id')->toArray(), 
                                [
                                    'id' => 'departmental_access', 
                                    'class' => 'form-control select3', 
                                    'multiple' => 'multiple', 
                                    'data-placeholder' => 'select an access',
                                    ($data->is_dept_restricted == 0) ? '' : 'disabled'
                                ])
                        }}
                        <span class="m-form__help text-danger" id="err_departmental_access"></span>
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
        <div class="modal-footer">
            <input type="button" value="{{__('Back')}}" class="btn  btn-light back-btn">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
        </div>
    </div>
{{ Form::close() }}
</div>
<script src="{{ asset('js/partials/select-ajax.js?v='.filemtime(getcwd().'/js/partials/select-ajax.js').'') }}"></script>
<script src="{{ asset('js/partials/barangay-ajax.js?v='.filemtime(getcwd().'/js/partials/barangay-ajax.js').'') }}"></script>
<script src="{{ asset('js/partials/forms_validation.js?v='.filemtime(getcwd().'/js/partials/forms_validation.js').'') }}"></script>
<script src="{{ asset('js/partials/step-form.js?v='.filemtime(getcwd().'/js/partials/step-form.js').'') }}"></script>
<script src="{{ asset('js/HR/add_employee.js?v='.filemtime(getcwd().'/js/HR/add_employee.js').'') }}"></script>
<script src="{{ asset('assets/vendors/selectpicker/bootstrap-select.min.js?v='.filemtime(getcwd().'/assets/vendors/selectpicker/bootstrap-select.min.js').'') }}"></script>
<script src="{{ asset('assets/vendors/dropzone/dropzone.js?v='.filemtime(getcwd().'/assets/vendors/dropzone/dropzone.js').'') }}"></script>
<table>
    <tbody class="hidden" id="child-row">
        <tr class="new-row" id="child-changeid">
            <td>
                {{ 
                    Form::text($name = 'Children[changeid][hrec_last_name]', $value = '', 
                    $attributes = array(
                        'id' => 'child_ln_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'Children[changeid][hrec_first_name]', $value = '', 
                    $attributes = array(
                        'id' => 'child_fn_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'Children[changeid][hrec_middle_name]', $value = '', 
                    $attributes = array(
                        'id' => 'child_mn_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'Children[changeid][hrec_suffix]', $value = '', 
                    $attributes = array(
                        'id' => 'child_suffix_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::date($name = 'Children[changeid][hrec_date_of_birth]', $value = '', 
                    $attributes = array(
                        'id' => 'child_bday_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                
                <span>
                    <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" title="Remove require">
                        <i class="ti-trash text-white"></i>
                    </a>
                </span>
            </td>
        </tr>
    </tbody>
</table>
<table>
    <tbody class="hidden" id="civil-row">
        <tr class="new-row" id="civil-changeid">
            <td>
                {{ 
                    Form::text($name = 'civil[changeid][hrecse_service]', $value = '', 
                    $attributes = array(
                        'id' => 'civil_service_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'civil[changeid][hrecse_rating]', $value = '', 
                    $attributes = array(
                        'id' => 'civil_rating_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::date($name = 'civil[changeid][hrecse_date_of_exam]', $value = '', 
                    $attributes = array(
                        'id' => 'civil_date_exam_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'civil[changeid][hrecse_place_of_exam]', $value = '', 
                    $attributes = array(
                        'id' => 'civil_place_exam_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'civil[changeid][hrecse_number]', $value = '', 
                    $attributes = array(
                        'id' => 'civil_number_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::date($name = 'civil[changeid][hrecse_valid_date]', $value = '', 
                    $attributes = array(
                        'id' => 'civil_valid_date_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                <span>
                    <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" title="Remove require">
                        <i class="ti-trash text-white"></i>
                    </a>
                </span>
            </td>
        </tr>
    </tbody>
</table>
<table>
    <tbody class="hidden" id="work-row">
        <tr class="new-row" id="work-changeid">
            <td>
                {{ 
                    Form::date($name = 'work[changeid][hrewe_inclusive_from]', $value = '', 
                    $attributes = array(
                        'id' => 'work_inc_from_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::date($name = 'work[changeid][hrewe_inclusive_to]', $value = '', 
                    $attributes = array(
                        'id' => 'work_inc_to_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'work[changeid][hrewe_position_title]', $value = '', 
                    $attributes = array(
                        'id' => 'work_position_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'work[changeid][hrewe_company]', $value = '', 
                    $attributes = array(
                        'id' => 'work_company_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'work[changeid][hrewe_monthly_salary]', $value = '', 
                    $attributes = array(
                        'id' => 'work_salary_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'work[changeid][hrewe_salary_grade]', $value = '', 
                    $attributes = array(
                        'id' => 'work_grade_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'work[changeid][hrewe_appointment_status]', $value = '', 
                    $attributes = array(
                        'id' => 'work_status_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                Form::select('work[changeid][hrewe_gov_service]', 
                [ 
                    1 => 'Yes',
                    0 => 'No',
                ], 
                $value = '', 
                [
                    'id' => 'work_gov_service_changeid', 
                    'class' => 'form-control select3', 
                    'data-placeholder' => 'Yes or No'
                ])
                }}
            </td>
            <td>
                <span>
                    <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" title="Remove require">
                        <i class="ti-trash text-white"></i>
                    </a>
                </span>
            </td>
        </tr>
    </tbody>
</table>
<table>
    <tbody class="hidden" id="voluntary-row">
        <tr class="new-row" id="voluntary-changeid">
            <td>
                {{ 
                    Form::text($name = 'voluntary[changeid][hrevw_org_name]', $value = '', 
                    $attributes = array(
                        'id' => 'voluntary_name_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'voluntary[changeid][hrevw_org_address]', $value = '', 
                    $attributes = array(
                        'id' => 'voluntary_add_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::date($name = 'voluntary[changeid][hrevw_inclusive_from]', $value = '', 
                    $attributes = array(
                        'id' => 'voluntary_from_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::date($name = 'voluntary[changeid][hrevw_inclusive_to]', $value = '', 
                    $attributes = array(
                        'id' => 'voluntary_to_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'voluntary[changeid][hrevw_hours]', $value = '', 
                    $attributes = array(
                        'id' => 'voluntary_hrs_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'voluntary[changeid][hrevw_position]', $value = '', 
                    $attributes = array(
                        'id' => 'voluntary_position_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                <span>
                    <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" title="Remove require">
                        <i class="ti-trash text-white"></i>
                    </a>
                </span>
            </td>
        </tr>
    </tbody>
</table>
<table>
    <tbody class="hidden" id="training-row">
        <tr class="new-row" id="training-changeid">
            <td>
                {{ 
                    Form::text($name = 'training[changeid][hretp_org_name]', $value = '', 
                    $attributes = array(
                        'id' => 'training_name_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::date($name = 'training[changeid][hretp_inclusive_from]', $value = '', 
                    $attributes = array(
                        'id' => 'training_from_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::date($name = 'training[changeid][hretp_inclusive_to]', $value = '', 
                    $attributes = array(
                        'id' => 'training_to_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'training[changeid][hretp_id_type]', $value = '', 
                    $attributes = array(
                        'id' => 'training_idtype_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'training[changeid][hretp_sponsored_by]', $value = '', 
                    $attributes = array(
                        'id' => 'training_sponsore_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                <span>
                    <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" title="Remove require">
                        <i class="ti-trash text-white"></i>
                    </a>
                </span>
            </td>
        </tr>
    </tbody>
</table>
<table>
    <tbody class="hidden" id="skills-row">
        <tr class="new-row" id="skills-changeid">
            <td>
                {{ 
                    Form::text($name = 'skills[changeid][hreh_description]', $value = '', 
                    $attributes = array(
                        'id' => 'skills_desc_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                <span>
                    <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" title="Remove require">
                        <i class="ti-trash text-white"></i>
                    </a>
                </span>
            </td>
        </tr>
    </tbody>
</table>
<table>
    <tbody class="hidden" id="recognition-row">
        <tr class="new-row" id="recognition-changeid">
            <td>
                {{ 
                    Form::text($name = 'recognition[changeid][hrer_description]', $value = '', 
                    $attributes = array(
                        'id' => 'recognition_desc_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                <span>
                    <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" title="Remove require">
                        <i class="ti-trash text-white"></i>
                    </a>
                </span>
            </td>
        </tr>
    </tbody>
</table>
<table>
    <tbody class="hidden" id="orgs-row">
        <tr class="new-row" id="orgs-changeid">
            <td>
                {{ 
                    Form::text($name = 'orgs[changeid][hreo_description]', $value = '', 
                    $attributes = array(
                        'id' => 'orgs_desc_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                <span>
                    <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" title="Remove require">
                        <i class="ti-trash text-white"></i>
                    </a>
                </span>
            </td>
        </tr>
    </tbody>
</table>
<table>
    <tbody class="hidden" id="reference-row">
        <tr class="new-row" id="reference-changeid">
            <td>
                {{ 
                    Form::text($name = 'reference[changeid][hreo_name]', $value = '', 
                    $attributes = array(
                        'id' => 'reference_name_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'reference[changeid][hreo_address]', $value = '', 
                    $attributes = array(
                        'id' => 'reference_add_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                {{ 
                    Form::text($name = 'reference[changeid][hreo_contact_no]', $value = '', 
                    $attributes = array(
                        'id' => 'reference_contact_changeid',
                        'class' => 'form-control form-control-solid',
                    )) 
                }}
            </td>
            <td>
                <span>
                    <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" title="Remove require">
                        <i class="ti-trash text-white"></i>
                    </a>
                </span>
            </td>
        </tr>
    </tbody>
</table>

