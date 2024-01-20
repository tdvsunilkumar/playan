{{ Form::open(array('url' => 'rptcertificateofnolandholding/client','id'=>'storePropertyOwnerForm')) }}
   
   
  
   
<style>
    .modal-xll {
        max-width: 1330px !important;
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

 </style>



   
    <div class="modal-body">
        
        <div class="row pt10" >
            <!--------------- Owners Information Start Here---------------->
            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
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
                                                {{ Form::select('p_barangay_id_no',$arrgetBrgyCode,'',array('class'=>'form-control','id'=>'p_barangay_id_no')) }}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_address"></span>
                                        </div>
                                    </div>
                           

                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('p_telephone_no',__('Telephone No.'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('p_telephone_no','',array('class'=>'form-control phonenumber','id'=>'p_telephone_no'))}}
                                    </div>
                                    <span class="validate-err" id="err_brgy_name"></span>
                                </div>
                            </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('p_mobile_no',__('Mobile No.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                        {{Form::text('p_mobile_no','',array('class'=>'form-control phonenumber','id'=>'p_mobile_no','required'=>'required'))}}
                                    </div>
                                    <span class="validate-err" id="err_brgy_name"></span>
                                </div>
                            </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
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
                                        {{ Form::select('country',$arrgetCountries,'', array('class' => 'form-control select3','id'=>'country')) }}
                                        
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

          

              <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <input type="submit" id="submitClient" name="submitClient" value="Save Changes" class="btn  btn-primary">
        </div>
            </div>

        </div>
    </div>
</div>
 {{Form::close()}}
<!-- <script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('js/add_rptProperty.js') }}"></script> -->




