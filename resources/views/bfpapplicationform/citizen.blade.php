<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />

@extends('layouts.admin')
@section('page-title')
    
    {{__('Citizen')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Citizen')}}</li>
@endsection
@section('action-btn')
    
@endsection



@section('content')
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

            {{ Form::open(array('url' => 'fire-protection/bfpapplicationform/citizen')) }}
            @csrf
               
                <div class="modal-body">
                    <div class="row pt10" >
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
@endsection
<script src="http://localhost/playan/js/select2.min.js"></script>