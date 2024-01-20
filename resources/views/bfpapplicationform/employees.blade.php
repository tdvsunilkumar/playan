<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />

@extends('layouts.admin')
@section('page-title')
    
    {{__('Employees')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
<style>
   .page-header h4, .page-header .h4 {
        margin-bottom: 0;
        margin-right: 8px;
        padding-right: 8px;
        font-weight: 500;
        font-size: 25px;
    }
    .accordion-button{
        margin-bottom: 12px;
    }
    .form-group{
        margin-bottom: unset;
    }
    .form-group label {
        font-weight: 600;
        font-size: 12px;
    }
    .form-control, .custom-select{
        padding-left: 5px;
        font-size: 12px;
    }
    .pt10{
        padding-top:10px;
    }
    .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #8080802e;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .choices__inner {
        min-height: 35px;
        padding:5px ;
        padding-left:5px;
    }
    .field-requirement-details-status label{margin-top: 7px;}
    #flush-collapsetwo{
/*        padding-bottom: 80px;*/
    }
    /*.select3-container{
        z-index: 9999999 !important;
    }*/

    .bootstrap-select > .dropdown-toggle.btn-light, .bootstrap-select > .dropdown-toggle.btn-secondary, .bootstrap-select > .dropdown-toggle.btn-default {
            border-color: #ced4da !important;
            box-shadow: none;
            background: #ffffff !important;
            color: #293240;
            padding: 0px;
            padding-left: 5px;
    }
    .bootstrap-select.btn-group .dropdown-toggle .filter-option {
        display: inline-block;
        overflow: hidden;
        width: 100%;
        text-align: left;
        font-size: 12px;
    }
    .dropdown-toggle::after {
        display: inline-block;
        margin-left: 0.255em;
        vertical-align: 0.255em;
        content: "";
        border-top: 0.3em solid;
        border-right: 0px; 
         border-bottom: 0px; 
         border-left: 0px; 
    }
    .bootstrap-select.open li.selected a {
        background-color: #536ea4;
        font-size: 12px;
    }
    .bootstrap-select.btn-group .dropdown-menu li a span.text {
        display: inline-block;
        font-size: 12px;
    }
    .bootstrap-select.btn-group .dropdown-menu li {
        position: relative;
        font-size: 12px;
    }
 </style>
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Employees')}}</li>
@endsection
@section('action-btn')
    
@endsection



@section('content') 
             {{ Form::open(array('url' => 'fire-protection/bfpapplicationform/employees')) }}
            @csrf
               
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

                    <h4 class="text-header" style="padding: 5px;font-size: 18px;background: #20b7cc;color: #fff;">Basic Information</h4>
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
                            <div class="form-group m-form__group required">
                                {{ Form::label('gender', 'Gender', ['class' => '']) }}
                                {{
                                    Form::select('gender', $gender, $value = '', ['id' => 'gender', 'class' => 'form-control selectpicker','data-live-search'=>'true', 'data-placeholder' => 'select a gender'])
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
                   <br>
                    <h4 class="text-header" style="padding: 5px;font-size: 18px;background: #20b7cc;color: #fff;">Additional Information</h4>
                    <br>
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
                        <div class="col-sm-8" style="margin-bottom: 5px;">
                            <div class="form-group m-form__group required">
                                {{ Form::label('barangay_id', 'Barangay, Municipality, Province, Region', ['class' => 'fs-6 fw-bold']) }}
                                {{
                                    Form::select('barangay_id', $arrgetBrgyCode, $value = '', ['id' => 'barangay_id', 'class' => 'form-control  selectpicker','data-live-search'=>'true', 'data-placeholder' => 'select a barangay...'])
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
                    <br>
                    <h4 class="text-header" style="padding: 5px;font-size: 18px;background: #20b7cc;color: #fff;">Other Information</h4>
                    <br>
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
                                    Form::select('acctg_department_id', $gender, $value = '', ['id' => 'acctg_department_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a department'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('acctg_department_division_id', 'Division', ['class' => '']) }}
                                {{
                                    Form::select('acctg_department_division_id', $gender, $value = '', ['id' => 'acctg_department_division_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a division'])
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
                                    Form::select('hr_designation_id', $gender, $value = '', ['id' => 'hr_designation_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a designation'])
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
                                    Form::select('is_dept_restricted', $gender, $value = '', ['id' => 'is_dept_restricted', 'class' => 'form-control selectpicker','data-live-search'=>'true', 'data-placeholder' => 'select a department restriction'])
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
@endsection
<script src="http://localhost/playan/js/select2.min.js"></script>