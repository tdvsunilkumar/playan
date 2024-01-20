<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />

@extends('layouts.admin')
@section('page-title')
    
    {{__('Taxpayer')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')



    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Taxpayer')}}</li>
@endsection
@section('action-btn')
    
@endsection



@section('content')
{{ Form::open(array('url' => 'rptcertificateofnolandholding/client')) }}
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

   
    <div class="modal-body">
        
        <div class="row pt10" >
            <!--------------- Owners Information Start Here---------------->
            <div class="col-lg-8 col-md-8 col-sm-8"  id="accordionFlushExample">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingone">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle">{{__("Owner Information")}}</h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                            <div class="basicinfodiv">
                                <div class="row">
                                            
                                    <div class="col-lg-12 col-md-12 col-sm-12">

                                        <div class="form-group">
                                            {{Form::label('rpo_custom_last_name',__('Last Name / Corporation/Organization Name/Couples Name/Other Name'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::textarea('rpo_custom_last_name','',array('class'=>'form-control','required'=>'required','rows'=>'2'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_last_name"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('rpo_first_name',__('First Name'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('rpo_first_name','',array('class'=>'form-control'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_first_name"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('rpo_middle_name',__('Middle Name'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('rpo_middle_name','',array('class'=>'form-control'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_middle_name"></span>
                                        </div>
                                    </div>

                                     <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('suffix',__('Suffix(Jr, Sr, II, III)'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('suffix','',array('class'=>'form-control'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_middle_name"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('rpo_address_house_lot_no',__('House/Lot No'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::textarea('rpo_address_house_lot_no','',array('class'=>'form-control','rows'=>1))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_address"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('rpo_address_street_name',__('Street Name'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::textarea('rpo_address_street_name','',array('class'=>'form-control','rows'=>1))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_address"></span>
                                        </div>
                                    </div>
                                     <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('rpo_address_subdivision',__('Subdivision'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::textarea('rpo_address_subdivision','',array('class'=>'form-control','rows'=>1))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_address"></span>
                                        </div>
                                    </div>

                                    
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            {{Form::label('p_barangay_id_no',__('Barangay, Municipality, Province, Region'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('p_barangay_id_no',$arrgetBrgyCode,'',array('class' => 'form-control selectpicker','data-live-search'=>'true','id'=>'p_barangay_id_no')) }}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_address"></span>
                                        </div>
                                    </div>
                           

                            <div class="col-lg-4 col-md-4 col-sm-4" style="padding-top: 20px;">
                                <div class="form-group">
                                    {{Form::label('p_telephone_no',__('Telephone No.'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('p_telephone_no','',array('class'=>'form-control phonenumber','id'=>'p_telephone_no'))}}
                                    </div>
                                    <span class="validate-err" id="err_brgy_name"></span>
                                </div>
                            </div>
                             <div class="col-lg-4 col-md-4 col-sm-4" style="padding-top: 20px;">
                                <div class="form-group">
                                    {{Form::label('p_mobile_no',__('Mobile No.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                        {{Form::text('p_mobile_no','',array('class'=>'form-control phonenumber','id'=>'p_mobile_no','required'=>'required'))}}
                                    </div>
                                    <span class="validate-err" id="err_brgy_name"></span>
                                </div>
                            </div>
                             <div class="col-lg-4 col-md-4 col-sm-4" style="padding-top: 20px;">
                                <div class="form-group">
                                    {{Form::label('p_fax_no',__('Fax No.'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('p_fax_no','',array('class'=>'form-control','id'=>'p_fax_no'))}}
                                    </div>
                                    <span class="validate-err" id="err_brgy_name"></span>
                                </div>
                            </div>
                            
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('p_email_address',__('Email Address'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('p_email_address','',array('class'=>'form-control','id'=>'p_email_address'))}}
                                    </div>
                                    <span class="validate-err" id="err_brgy_name"></span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('p_tin_no',__('Tin No.'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('p_tin_no','',array('class'=>'form-control','id'=>'p_tin_no'))}}
                                    </div>
                                    <span class="validate-err" id="err_brgy_name"></span>
                                </div>
                            </div> 
                             <div class="col-md-4">
                               <div class="form-group">
                                    {{ Form::label('country', __('Country'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        
                                    <div class="form-icon-user">
                                       {{ Form::select('country',$arrgetCountries,'', array('class' => 'form-control selectpicker','id'=>'select-country','data-live-search'=>'true')) }}
                                        
                                    </div>
                                    <span class="validate-err" id="err_reg_no"></span>
                                </div>
                            </div> 
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('gender',__('Gender'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                         {{ Form::select('gender',array('1' =>'Male','0' =>'Female'),'', array('class' => 'form-control spp_type','id'=>'gender','required'=>'required')) }}
                                    </div>
                                    <span class="validate-err" id="err_brgy_name"></span>
                                </div>
                            </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('dateofbirth',__('Date Of Birth'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::date('dateofbirth','',array('class'=>'form-control','id'=>'dateofbirth'))}}
                                    </div>
                                    <span class="validate-err" id="err_brgy_name"></span>
                                </div>
                            </div>
                             </div>
                             <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <input type="submit" id="submitClient" name="submitClient" value="Save Changes" class="btn  btn-primary">
        </div>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item"> -->
                       <!--  <h6 class="accordion-header" id="flush-headingone">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle">{{__("Property Owner Information")}}</h6>
                            </button>
                        </h6> -->
                      <!--   <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                            <div class="basicinfodiv">
                                <div class="row">
                                   

                                   
                                    
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            
            <!--------------- Owners Information Start Here---------------->

            <!--------------- ATTACHED DOCUMENTARY REQUIREMENTS Start Here---------------->
         
            
        </div>
       
             
        

               
           </div>
         </div>
        </div>
        <!--------------- Business Details Listing End Here------------------>

          
          

           
            </div>

        </div>
    </div>
</div>
 {{Form::close()}}
 @endsection
 <script src="http://localhost/playan/js/select2.min.js"></script>
<!-- <script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('js/add_rptProperty.js') }}"></script> -->




