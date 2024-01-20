{{Form::open(array('name'=>'forms','url'=>'rptproperty','method'=>'post','id'=>'propertyTaxDeclarationForm'))}}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('uc_code',$data->uc_code, array('id' => 'uc_code','class'=>'uc_code')) }}
{{ Form::hidden('update_code',$data->update_code, array('id' => 'uc_code','class'=>'uc_code')) }}
{{ Form::hidden('pk_id',$propertyKind, array('id' => 'pk_id','class'=>'pk_id')) }}
{{ Form::hidden('old_property_id',$oldpropertyid, array('id' => 'old_property_id','class'=>'old_property_id')) }}
{{ Form::hidden('created_against',$data->created_against, array('id' => 'created_against','class'=>'created_against')) }}
<style>
   .modal-body {
       position: relative;
       flex: 1 1 auto;
       padding: 1.25rem;
       width: 99.7%;
       /* padding-bottom: 5%; */
   }
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
   color: #fff;
   background: #20B7CC;
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
   .currency{
   position: relative;
   }

   table.dataTable {
  margin: 0 auto;
  width: 100%;
}
</style>
<div class="modal-body">
   <div class="row">
      <div class="col-lg-5 col-md-5 col-sm-5">
         <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3">
               <div class="form-group">
                  {{Form::label('rvy_revision_year_id',__("Revision Year"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                  <div class="form-icon-user">
                     {{Form::select('rvy_revision_year_id',$arrRevisionYears,$data->rvy_revision_year_id,array('class'=>'form-control rvy_revision_year_id','id'=>'rvy_revision_year_id','disabled'=>(isset($data->id) && $data->id != '')?true:true))}}
                     @if(isset($data->id) && $data->id != '')
                     <input type="hidden" name="rvy_revision_year_id" value="{{ $data->rvy_revision_year_id }}">
                     @endif
                     <input type="hidden" name="rvy_revision_year" value="{{ isset($data->rvy_revision_year)?$data->rvy_revision_year:''}}">
                     <input type="hidden" name="rvy_revision_code" value="{{ $data->rvy_revision_code }}">
                  </div>
                  <span class="validate-err" id="err_rvy_revision_year_id"></span>
               </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
               <div class="form-group">
                  {{Form::label('brgy_code_id',__("Barangay"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                  <div class="form-icon-user">
                     {{Form::select('brgy_code_id',$arrBarangay,($data->brgy_code_id != '')?$data->brgy_code_id:session()->get('landSelectedBrgy'),array('class'=>'form-control brgy_code_id ','id'=>'brgy_code_id','disabled'=>true))}}
                     <input type="hidden" name="brgy_code" value="">
                  </div>
                  <span class="validate-err" id="err_brgy_code_id"></span>
               </div>
            </div>
            @if(isset($data->id) && $data->id != '')
            <div class="col-lg-3 col-md-3 col-sm-3">
               <div class="form-group">
                  {{Form::label('brgy_code_id',__("TD No."),['class'=>'form-label'])}}<span class="text-danger">*</span>
                  <div class="form-icon-user">
                     @php
                        $rp_Td_No = str_pad($data->rp_td_no, 5, '0', STR_PAD_LEFT);
                     @endphp
                     {{Form::text('rp_Td_No',$rp_Td_No,array('class'=>'form-control','readonly'=>'readonly'))}}
                  </div>
                  <span class="validate-err" id="err_rp_td_no"></span>
               </div>
            </div>
            <input type="hidden" name="rp_tax_declaration_no" value="{{ $data->rp_tax_declaration_no}}"> 
            @endif
            <input type="hidden" name="rp_property_code" value="{{ $data->rp_property_code }}">
            <input type="hidden" name="rp_td_no" value="{{ $data->rp_td_no}}"> 
            <div class="col-lg-3 col-md-3 col-sm-3">
               <div class="form-group">
                  {{Form::label('rp_suffix',__("Suffix"),['class'=>'form-label'])}}
                  <div class="form-icon-user">
                     {{Form::text('rp_suffix',$data->rp_suffix,array('class'=>'form-control rp_suffix','placeholder' => 'Suffix','max'=>'5'))}}
                  </div>
                  <span class="validate-err" id="err_rp_suffix"></span>
               </div>
            </div>
         </div>
      </div>
      <!-- <div class="col-lg-8 col-md-8 col-sm-8"> -->
      <div class="col-lg-7 col-md-7 col-sm-7">
         <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2">
               <div class="form-group">
                  {{Form::label('loc_local_code_name',__("Locality"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                  <div class="form-icon-user">
                     {{Form::text('loc_local_code_name',(isset($data->loc_local_code_name))?$data->loc_local_code_name:'',array('class'=>'form-control loc_local_code_name','id'=>'loc_local_code_name','readonly'=>'readonly'))}}
                     <input type="hidden" name="loc_local_code" value="{{ $data->loc_local_code }}">
                  </div>
                  <span class="validate-err" id="err_loc_local_code"></span>
               </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
               <div class="form-group">
                  {{Form::label('dist_code_name',__("District"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                  <div class="form-icon-user">
                     {{Form::text('dist_code_name',(isset($data->dist_code_name))?$data->dist_code_name:'',array('class'=>'form-control dist_code','readonly'=>'readonly'))}}
                     <input type="hidden" name="dist_code" value="{{ $data->dist_code }}">
                  </div>
                  <span class="validate-err" id="err_dist_code"></span>
               </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
               <div class="form-group">
                  {{Form::label('brgy_code_and_desc',__("Barangay"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                  <div class="form-icon-user">
                     {{Form::text('brgy_code_and_desc',(isset($data->brgy_code_and_desc))?$data->brgy_code_and_desc:'',array('class'=>'form-control brgy_code_and_desc','readonly'=>'readonly'))}}
                  </div>
                  <span class="validate-err" id="err_brgy_code_and_desc"></span>
               </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
               <div class="form-group">
                  {{Form::label('rp_section_no',__("Section No."),['class'=>'form-label'])}}<span class="text-danger">*</span>
                  <div class="form-icon-user">
                     {{Form::text('rp_section_no',$data->rp_section_no,array('class'=>'form-control rp_section_no','placeholder'=>'Section No.'))}}
                  </div>
                  <span class="validate-err" id="err_rp_section_no"></span>
               </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
               <div class="form-group">
                  {{Form::label('rp_pin_no',__("PIN No."),['class'=>'form-label'])}}<span class="text-danger">*</span>
                  <div class="form-icon-user">
                     {{Form::text('rp_pin_no',$data->rp_pin_no,array('class'=>'form-control rp_pin_no','placeholder'=>'PIN No.'))}}
                  </div>
                  <span class="validate-err" id="err_rp_pin_no"></span>
               </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
               <div class="form-group">
                  {{Form::label('rp_pin_suffix',__("PIN Suffix"),['class'=>'form-label'])}}
                  <div class="form-icon-user">
                     {{Form::text('rp_pin_suffix',$data->rp_pin_suffix,array('class'=>'form-control rp_pin_suffix','placeholder'=>'PIN Suffix'))}}
                  </div>
                  <span class="validate-err" id="err_rp_pin_suffix"></span>
               </div>
            </div>
            <span class="validate-err" id="err_duplicatesFields"></span>
         </div>
         <span class="validate-err" id="err_rp_pin_no_test"></span>
      </div>
   </div>
   <div class="row pt10" >
      <!--------------- Owners Information Start Here---------------->
      <div class="col-lg-5 col-md-5 col-sm-5"  id="accordionFlushExampleOwner" style="height: 442px;">
         <div  class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingone">
                  <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseoneOwner" aria-expanded="false" aria-controls="flush-headingtwo">
                     <h6 class="sub-title accordiantitle">{{__("Owner's Information")}}</h6>
                  </button>
               </h6>
               <div id="flush-collapseoneOwner" class="accordion-collapse collapse show" aria-labelledby="flush-headingone" data-bs-parent="#accordionFlushExampleOwner">
                  <div class="basicinfodiv" style="padding: 12px;">
                     <div class="row">
                        <div class="col-lg-11 col-md-11 col-sm-11">
                           <div class="form-group profile_id_group">
                              {{Form::label('profile_id',__('Select Owner'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                              {{ Form::select('profile_id',[],'',array('class' =>'form-control profile_id main_profile_id','id'=>'profile','placeholder'=>'Select Name')) }}
                           </div>
                           <span class="validate-err" id="err_profile_id"></span>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1" style="margin-left: -15px;">
                           <div class="" style="margin-top: 22px;">
                              <!-- <a class="mx-3 btn btn-sm  align-items-center refeshbuttonselect2 ti-reload text-white" 
                                 name="stp_print"
                                 title="Refresh"></a> -->
                                  <a href="{{ url('real-property/property-owners') }}" target="_blank" title="{{__('New Profile User')}}" class="btn btn-sm btn-primary ti-plus"  style="margin-top: 8px;font size: 9px;">
                              </a>
                           </div>
                        </div>
                        <!-- <div class="col-lg-3 col-md-3 col-sm-3" style="margin-left:-7px;">
                           <div class="form-group" style="margin-top: 25px;"> -->
                              <!-- <a href="#"  data-size="xl" data-url="{{ url('/profileuser/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('New Profile User')}}" class="btn btn-sm btn-primary" style="margin-top: 8px;">
                                 <i class="ti ti-plus"></i></a> -->
                              <!-- <a href="{{ url('real-property/property-owners') }}" target="_blank" title="{{__('New Profile User')}}" class="btn btn-sm btn-primary"  style="margin-top: 8px;font size: 9px;    width: 127px;">
                              Change Owner</a> -->
                           <!-- </div>
                        </div> -->
                     </div>
                     <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
                           <div class="form-group">
                              {{Form::label('property_owner_address',__('Address'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                              <div class="form-icon-user">
                                 {{Form::text('property_owner_address',$data->property_owner_address,array('class'=>'form-control property_owner_address'))}}
                                 <input type="hidden" value="{{ $data->rpo_code }}" name="rpo_code">
                              </div>
                              <span class="validate-err" id="err_property_owner_address"></span>
                           </div>
                        </div>
                     </div>
                     
                     <!--   <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                           <div class="form-group">
                               {{Form::label('ba_p_address',__('Ass. Block No'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                               <div class="form-icon-user">
                                   {{Form::text('ba_p_address','',array('class'=>'form-control','rows'=>1))}}
                               </div>
                               <span class="validate-err" id="err_ba_p_address"></span>
                           </div>
                        </div>
                        </div> <br /> -->
                     <div class="row">
                        <button class="btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsethree" aria-expanded="false" aria-controls="flush-headingthree">
                           <h6 class="sub-title accordiantitle">Property Location</h6>
                        </button>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                           <div class="form-group">
                              {{Form::label('loc_local_code_name',__('Location'),['class'=>'form-label loc_local_code_name'])}}<span class="text-danger">*</span>
                              <div class="form-icon-user">
                                 {{Form::text('loc_local_code_name',(isset($data->loc_local_code_name))?$data->loc_local_code_name:'',array('class'=>'form-control','readonly','rows'=>1))}}
                              </div>
                              <span class="validate-err" id="err_loc_group_brgy_no"></span>
                           </div>
                        </div>
                        
                        <div class="col-lg-12 col-md-12 col-sm-12">
                           <div class="form-group">
                              {{Form::label('rp_location_number_n_street',__('Number & Street'),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                              <div class="form-icon-user">
                                 
                                 {{Form::textarea('rp_location_number_n_street',(isset($data->rp_location_number_n_street))?$data->rp_location_number_n_street:'',array('class'=>'form-control rp_location_number_n_street','rows'=>4))}}
                              </div>
                              <span class="validate-err" id="err_rp_location_number_n_street"></span>
                           </div>
                        </div>
                        <!-- <div class="col-lg-12 col-md-12 col-sm-12">
                           <div class="form-group">
                               {{Form::label('uc_code',__('Update Code'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                               <div class="form-icon-user">
                                   {{Form::select('uc_code',$arrUpdateCodes,$data->uc_code,array('class'=>'form-control','rows'=>1))}}
                               </div>
                               <span class="validate-err" id="err_uc_code"></span>
                           </div>
                           </div> -->
                        <!-- <div class="col-lg-12 col-md-12 col-sm-12">
                           <div class="form-group">
                               {{Form::label('pk_id',__('Property Kind Code'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                               <div class="form-icon-user">
                                   {{Form::select('pk_id',$arrPropKindCodes,$data->pk_id,array('class'=>'form-control pk_id','rows'=>1))}}
                               </div>
                               <span class="validate-err" id="err_pk_id"></span>
                           </div>
                           </div> -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
      <!--------------- Owners Information Start Here---------------->
      <!--------------- Business Information Start Here---------------->
      
      <div class="col-lg-7 col-md-7 col-sm-7" id="accordionFlushExample2" style="">
         <div class="accordion accordion-flush">
             <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingtwo">
                  <!-- <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                     <h6 class="sub-title accordiantitle">{{__('Business Information')}}</h6>
                     </button> -->
               </h6>
               
               <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2" style="height: ;">
                  <div class="basicinfodiv" style="padding: 12px;">

                     <div class="row">
                        <div class="col-lg-11 col-md-11 col-sm-11" style="padding-right: 0px;">
                           <div class="form-group property_administrator_id_group">
                              {{Form::label('property_administrator_id',__('Select Administrator'),['class'=>'form-label'])}}
                              {{ Form::select('property_administrator_id',[],$data->rp_administrator_code,array('class'=>'form-control property_administrator_id','id'=>'property_administrator_id','placeholder'=>'Select Name')) }}
                           </div>
                           <span class="validate-err" id="err_rp_administrator_code"></span>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1" style="">
                           <div class="" style="margin-top: 22px;">
                              <!-- <a class="mx-3 btn btn-sm  align-items-center refeshbuttonselect1 ti-reload text-white" 
                                 name="stp_print"
                                 title="Refresh"></a> -->
                                 <a href="{{ url('real-property/property-owners') }}" target="_blank" class="btn btn-sm btn-primary ti-plus" style="margin-top: 8px;">
                              </a>
                           </div>
                        </div>
                        <!-- <div class="col-lg-3 col-md-3 col-sm-3" style="margin-left:-20px;">
                           <div class="form-group" style="margin-top: 22px;">
                              <a href="{{ url('real-property/property-owners') }}" target="_blank" class="btn btn-sm btn-primary " style="margin-top: 8px;width: 195px;">
                              Change Administrator</a>
                           </div>
                        </div> -->
                     </div>
                     

                     <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                           <div class="form-group">
                              {{Form::label('rp_administrator_code',__('Administrator Address'),['class'=>'form-label'])}}
                              <div class="form-icon-user">
                                 {{Form::text('rp_administrator_code_address',$data->rp_administrator_code_address,array('class'=>'form-control','rows'=>1))}}
                                 <input type="hidden" name="rp_administrator_code" value="{{ $data->rp_administrator_code }}">
                              </div>
                              <span class="validate-err" id="err_rp_administrator_code"></span>
                           </div>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                           <div class="form-group">
                              {{Form::label('rp_oct_tct_cloa_no',__('OCT/TCT No'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                              <div class="form-icon-user">
                                 {{Form::text('rp_oct_tct_cloa_no',$data->rp_oct_tct_cloa_no,array('class'=>'form-control rp_oct_tct_cloa_no','rows'=>1))}}
                              </div>
                              <span class="validate-err" id="err_rp_oct_tct_cloa_no"></span>
                           </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                           <div class="form-group">
                              {{Form::label('rp_cadastral_lot_no',__("Cad'L Lot No."),['class'=>'form-label'])}}<span class="text-danger">*</span>
                              <div class="form-icon-user">
                                 {{Form::text('rp_cadastral_lot_no',$data->rp_cadastral_lot_no,array('class'=>'form-control rp_cadastral_lot_no','rows'=>1))}}
                              </div>
                              <span class="validate-err" id="err_rp_cadastral_lot_no"></span>
                           </div>
                        </div>
                     </div>
                     <div class="row" style="padding-top:0px;">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                           <div class="form-group">
                              {{Form::label('rp_bound_north',__('North'),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                              <div class="form-icon-user">
                                 {{Form::text('rp_bound_north',$data->rp_bound_north,array('class'=>'form-control rp_bound_north','rows'=>1))}}
                              </div>
                              <span class="validate-err" id="err_rp_bound_north"></span>
                           </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                           <div class="form-group">
                              {{Form::label('rp_bound_east',__('East'),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                              <div class="form-icon-user">
                                 {{Form::text('rp_bound_east',$data->rp_bound_east,array('class'=>'form-control rp_bound_east','rows'=>1))}}
                              </div>
                              <span class="validate-err" id="err_rp_bound_east"></span>
                           </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                           <div class="form-group">
                              {{Form::label('rp_bound_south',__('South'),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                              <div class="form-icon-user">
                                 {{Form::text('rp_bound_south',$data->rp_bound_south,array('class'=>'form-control rp_bound_south','rows'=>1))}}
                              </div>
                              <span class="validate-err" id="err_rp_bound_south"></span>
                           </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                           <div class="form-group">
                              {{Form::label('rp_bound_west',__('West'),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                              <div class="form-icon-user">
                                 {{Form::text('rp_bound_west',$data->rp_bound_west,array('class'=>'form-control rp_bound_west','rows'=>1))}}
                              </div>
                              <span class="validate-err" id="err_rp_bound_west"></span>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!--------------- Business Information End Here------------------>
   </div>
              
   <div class="row" >
      <!--------------- Taxable Items Start Here---------------->
      <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" style="padding-top:10px ;">
         <div  class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingfive">
                  <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefour" aria-expanded="false" aria-controls="flush-headingfive">
                     <h6 class="sub-title accordiantitle">{{__("Land Appraisal")}}</h6>
                  </button>
               </h6>
               <div id="flush-collapsefour" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5" style="    padding: 0px;">
                  <div class="basicinfodiv" style="">
                     <div class="row" style="">
                        <div class="col-sm-6" style="padding:0px;">
                           <!-- <a data-toggle="modal" href="javascript:void(0)" id="loadLandApprisalForm" class="btn btn-primary btnPopupOpen" type="add">Add</a> -->
                           <!-- <a data-toggle="modal" href="javascript:void(0)" id="loadLandApprisalForm" class="btn btn-primary" type="add">Add Land Appraisal</a> -->
                        </div>
                        <div class="col-sm-6" style="padding:0px;">
                           <div class="row">
                              <div class="col-sm-6" style="padding:0px;">
                                 <a data-toggle="modal" href="javascript:void(0)" data-propertyid="{{ $data->id }}" id="displaySwornStatementModal" class="btn btn-primary" >Sworn Statement of Property Owner</a>
                              </div>
                              <div class="col-sm-6" style="padding:0px;">
                                 <a data-toggle="modal" href="javascript:void(0)" data-propertyid="{{ $data->id }}" id="displayAnnotationSpecialPropertyStatusModal" class="btn btn-primary" >Annotation & Special Property Status</a>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!--------------- Land Apraisal Listing Start Here------------------>
                     <div id="landAppraisalListing" style="margin-top: -10px;"></div>
                     <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8">
                           <!-- <button type="button" class="btn btn-success" id="plantstreesadjustmentfactor">Plants/Trees and Value Adjustment Factors</button> -->
                           <button type="button" class="btn btn-success" id="plantstreesadjustmentfactornew" style="margin-left: 10px;">Plants/Trees and Value Adjustment Factors</button>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                           <div class="form-group row">
                              <label for="staticEmail" class="col-sm-5 col-form-label" style="text-align:end;">Total Market Value : </label>
                              <div class="col-sm-7" style="    padding-right: 20px;padding-left: 0px;">
                                 <div class="form-icon-user currency">
                                    <input type="text" readonly class="form-control decimalvalue" value="{{ (isset($totalMarketValue))?number_format((float)$totalMarketValue, 2, '.', ''):0.00 }}" id="landAppraisalTotalValueToDisplay" >
                                    <div class="currency-sign"><span>Php</span></div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <br />
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!--------------- Taxable Items End Here------------------>
   </div>
   <div class="row" >
      <!--------------- Taxable Items Start Here---------------->
      <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" style="padding-top: 10px;">
         <div  class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingfive">
                  <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                     <h6 class="sub-title accordiantitle">{{__("Assessment Summary")}}</h6>
                  </button>
               </h6>
               <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                  <div class="basicinfodiv" >
                     <div class="row" style="padding-top: 0;">
                        <div class="col-sm-6">
                        </div>
                     </div>
                     <!--------------- Land Apraisal Listing Start Here------------------>
                     <div id="assessementSummaryData"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!--------------- Taxable Items End Here------------------>
   </div>

   @if($data->id>0)
   <!---related building and machinary--->
   <div  class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingeight">
                  <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseeight" aria-expanded="true" aria-controls="flush-headingeight">
                     <h6 class="sub-title accordiantitle">{{__("Related Building & Machineries Information")}}</h6>
                  </button>
               </h6>
               <div id="flush-collapseeight" class="accordion-collapse collapse" aria-labelledby="flush-headingeight" data-bs-parent="#accordionFlushExample8">
                  <div class="basicinfodiv">
                    <div class="row" style="padding-top: 5px;">
                     </div>
                     <!--------------- Land Apraisal Listing Start Here------------------>
                     <div id="relatedBuildingSummaryData" style="">
                     <div class="row">
            <div class="col-xl-12" style="margin-top:-33px;" >
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="relatedBuildingsAndMachineries" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th >{{__('No.')}}</th>
                                        <th >{{__('TD. NO.')}}</th>
                                        <th >{{__('Taxpayer name')}}</th>
                                        <th >{{__('Kind')}}</th>
                                        <th >{{__('pin')}}</th>
                                        <th>{{__("Market Value")}}</th>
                                        <th>{{__("Assessment Level")}}</th>
                                        <th>{{__('Assessed Value')}}</th>
                                        <th>{{__('Status')}}</th>
                                        
                                    </tr>
                                </thead>
                               
                               
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div></div>
                   </div>
                 </div>
              </div>
           </div>
      <!---related building and machinary--->
 <!---geo location links start-->   
      <div  class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingseven">
                  <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseseeven" aria-expanded="false" aria-controls="flush-headingseven">
                     <h6 class="sub-title accordiantitle">
                     <i class="ti-menu-alt text-white fs-12"></i>
                      <span class="accordiantitle-icon">{{__("Tagging")}}</span></h6>
                  </button>
               </h6>
               <div id="flush-collapseseeven" class="accordion-collapse collapse" aria-labelledby="flush-headingseven" data-bs-parent="#accordionFlushExample7" style="    margin-top: -33px;">
                <div class="row">
                  <div class="col-md-12">
                  <span class="validate-err" id="err_locationlink"></span>
               </div>
                        <div class="col-md-12">
                          <div class="row field-requirement-details-status">
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                {{Form::label('id',__('NO'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                {{Form::label('link',__('Link Description'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                {{Form::label('link',__('Remarks'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                {{Form::label('action',__('Action'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1">
                            <span class="btn_addmore_geolocation btn" id="btn_addmore_geolocation" style="color:white;"><i class="ti-plus"></i></span>
                         </div>
                        </div>
                         <span class="geolocationDetails activity-details" id="geolocationDetails">
                            
                         </span>
                       </div>  
                    </div>
                    <div class="row">
                       <div class="col-md-12">
                          <a href="#"  data-size="xl" data-url="{{ url('/rptproperty/savegeolocationdata') }}" data-for="L"  id="savelocations" data-bs-toggle="tooltip" title="{{__('Search')}}" class="btn btn-sm btn-primary" style="margin: 5px;">
                                    Apply Changes</a>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        <!---geo location links end-->  
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample3">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading3">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Upload")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapse3" class="accordion-collapse collapse" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('file','documentname','',array('class'=>'form-control','id'=>'documentname'))}}  
                                                    </div>
                                                    <span class="validate-err" id="err_document"></span>
                                                </div>
                                             </div>
                                             <div class="col-lg-2 col-md-2 col-sm-2" style="padding-top: 24px;">
                                                 <button type="button" style="float: left;" class="btn btn-primary" id="uploadAttachmentbtn">Upload File</button>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Document Title</th>
                                                                <th>Attachment</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <thead id="DocumentDtls">
                                                            
                                                            @if(count($arrLocationdocs) =='0')
                                                            <tr>
                                                                <td colspan="3"><i>No results found.</i></td>
                                                            </tr>
                                                            @else
                                                            <tr>
                                                            
                                                            @foreach($arrLocationdocs as $key=>$val)
                                                            <td>{{$val->doc_link}}</td>
                                                            <td><a class="btn" href="{{asset('uploads/rpt/location/')}}{{$val->doc_link}}" target='_blank'><i class='ti-download'></i></a></td>
                                                            <td><button type="button" class="btn btn-danger btn_delete_documents" id="{{$val->id}}" value="{{$val->id}}"><i class="ti-trash"></i></button></td>
                                                            </tr>
                                                            
                                                            @endforeach
                                                            @endif 
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
       </div>
    @endif              
</div>

<div class="modal-footer">
   <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
   <a href="javascript:void(0)" data-propertyid="{{ $data->id}}" class="btn btn-primary collectapprovalformdata">Approval Form</a>
   <!-- <input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?__('Renew'):__('Create')}}" class="btn  btn-primary"> -->
   <button type="button" id="submittaxdeclarationform" class="btn  btn-primary" >{{ ($data->id)>0?__('Update'):__('Create')}}</button>
   <!-- <input type="submit" name="submit" id="submit"  value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
</div>
{{Form::close()}}
<div class="modal" id="addlandappraisalmodal" data-backdrop="static" style="z-index:9999999;">
   <div class="modal-dialog modal-lg modalDiv" >
      <div class="modal-content" id="landappraisalform">
      </div>
   </div>
</div>
<!-- <div class="modal" id="addPropertyOwnerModal" data-backdrop="static" style="z-index:9999999;">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel"></h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="body">
           </div>
       </div>
   </div>
   </div>  -->  


<div class="modal" id="landAppraisalAdjustmentFactorsmodal" data-backdrop="static" style="z-index:9999999;">
   <div class="modal-dialog modal-xl modalDiv" >
      <div class="modal-content" id="landAppraisalAdjustmentFactorsform">
      </div>
   </div>
</div>
<div class="modal" id="approvalformModal" data-backdrop="static" style="z-index:9999999;">
   <div class="modal-dialog modal-xl modalDiv" >
      <input type="hidden" name="cancelled_by_id" value="{{ $oldpropertyid }}">
      <div class="modal-content" id="approvalform">
      </div>
   </div>
</div>


<div class="modal" id="annotationSpecialPropertyStatusModal" data-backdrop="static" style="z-index:9999999;">
   <div class="modal-dialog modal-xl modalDiv" >
      <div class="modal-content" id="annotationSpecialPropertyStatusForm">
      </div>
   </div>
</div>
<div class="modal" id="swornStatementModal" data-backdrop="static" style="z-index:9999999;">
   <div class="modal-dialog modal-xl modalDiv" >
      <div class="modal-content" id="swornStatementForm">
      </div>
   </div>
</div>
<div class="modal" id="verifyPsw" tabindex="-1" role="dialog" style="z-index:9999999;">
        <div class="modal-dialog" role="document">
         {{Form::open(array('name'=>'forms','url'=>'rptproperty/verifypsw','method'=>'post','id'=>'verifyPswForm'))}}
            <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title">Verify Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               
                </button>
            </div>
           
            <div class="modal-body">
                <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                        <div class="form-icon-user">
                            {{ Form::password('verify_psw',array('class' => 'form-control','placeholder' => 'Input Password'))}}
                            <input type="hidden" name="verify_psw_id" value="{{ $data->id }}">
                        </div>
                        <span class="validate-err" id="err_verify_psw"></span>
                        
                    </div>
                </div>
            
            </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Verify</button>
                <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
            </div>
            
            </div>
            {{ Form::close()}}
        </div>
    </div>
<input type="hidden" name="dynamicid" value="3" id="dynamicid">

<div id="hiddenlocationHtml" class="hide">
    <div class="removelocationdata row pt10">
         <div class="col-lg-1 col-md-1 col-sm-1">
            <div class="form-group">
                    <div class="form-icon-user">
                        <input type="hidden" name="geoid[]">
                        <p class="serialnoclass" style="text-align:center;"></p>
                    </div>
            </div>
          </div>
         <div class="col-lg-5 col-md-5 col-sm-5">
                <div class="form-group">
                <div class="form-icon-user"><input required="required" class="form-control linkdesc newlink" name="linkdesc[]" type="text" value="">
                  <span class="validate-err linkdesc" ></span>
                    </div>
               </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="form-group">
                <div class="form-icon-user"><input required="required" class="form-control" name="remark[]" type="text" value="">
                    </div>

               </div>
            </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
                     <div class="form-group">
                       <span class="brousection"></span>
                       <div class="form-icon-user"><button type="button" class="btn btn-danger btn_cancel_locations"><i class="ti-trash"></i></button>
                       </div>
                   </div>
             </div>
    </div>
</div>

<script src="{{ asset('js/addRptProperty.js') }}?rand={{rand(0,99)}}"></script>
<script src="{{ asset('js/ajax_rptProperty.js') }}?rand={{rand(0,99)}}"></script>
<script type="text/javascript">
   setTimeout(function(){ 
      var id = "{{($data->rpo_code != '')?$data->rpo_code:''}}";

      if(id > 0){
      var text = "{{(isset($data->property_owner_details->full_name) && $data->property_owner_details != '')?$data->property_owner_details->full_name:'Select Property Owner'}}";
               $("#profile").select3("trigger", "select", {
    data: { id: id ,text:text}
});
            }
      var adminid = "{{($data->rp_administrator_code != '')?$data->rp_administrator_code:''}}";
      if(adminid > 0){
      var admintext = "{{(isset($data->property_admin_details->full_name) && $data->property_admin_details != '')?$data->property_admin_details->full_name:'Select Property Administrator'}}";
               $("#property_administrator_id").select3("trigger", "select", {
    data: { id: adminid ,text:admintext}
});
            }

}, 500);

</script>