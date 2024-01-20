{{Form::open(array('name'=>'forms','url'=>'rptproperty/loadpreviousowner','method'=>'post','id'=>'propertyPreviousOwnerForm'))}}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('uc_code',$data->uc_code, array('id' => 'uc_code','class'=>'uc_code')) }}
{{ Form::hidden('update_code',$data->update_code, array('id' => 'uc_code','class'=>'uc_code')) }}
{{ Form::hidden('pk_id',$propertyKind, array('id' => 'pk_id','class'=>'pk_id')) }}
{{ Form::hidden('old_property_id',$oldpropertyid, array('id' => 'old_property_id','class'=>'old_property_id')) }}
{{ Form::hidden('created_against',(isset($data->created_against) && $data->created_against != '')?$data->created_against:$oldpropertyid, array('id' => 'created_against','class'=>'created_against')) }}
<style>
   .modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 1.25rem;
    /* padding-bottom: 5%; */
}
   #addPreviousOwnerForLandModal .modal-xll {
/*      max-width: 100% !important;*/
display: contents;
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
}.swal2-container {
  z-index: 99999999 !important;
}

table.dataTable {
   margin: 0 auto;
   width: 100%;
}
</style>
<link href="{{ asset('assets/js/yearpicker.css')}}" rel="stylesheet">
<div class="modal-body">
   <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
         <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-5">
               <div class="row">
                  <div class="col-lg-3 col-md-3 col-sm-3">
                     <div class="form-group">
                        {{Form::label('rvy_revision_year_id',__("Revision Year"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                           {{Form::select('rvy_revision_year_id',$arrRevisionYears,$data->rvy_revision_year_id,array('class'=>'form-control rvy_revision_year_id','id'=>'rvy_revision_year_id'))}}
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
            <div class="col-lg-5 col-md-5 col-sm-5"  id="accordionFlushExampleOwner">
               <div  class="accordion accordion-flush">
                  <div class="accordion-item">
                     <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseoneOwner" aria-expanded="false" aria-controls="flush-headingtwo">
                           <h6 class="sub-title accordiantitle">{{__("Owner's Information")}}</h6>
                        </button>
                     </h6>
                     <div id="flush-collapseoneOwner" class="accordion-collapse collapse show" aria-labelledby="flush-headingone" data-bs-parent="#accordionFlushExampleOwner">
                        <div class="basicinfodiv">
                           <div class="row">
                              <div class="col-lg-11 col-md-11 col-sm-11" style="width: 92%;">
                                 <div class="form-group profile_id_group ">
                                    {{Form::label('profile_id',__('Select Owner'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    {{ Form::select('profile_id',[],$data->rpo_code,array('class' =>'form-control profile_id pre_profile_id','id'=>'profile_pre','placeholder'=>'Select Name')) }}
                                 </div>
                                 <span class="validate-err" id="err_profile_id"></span>
                              </div>
                              <div class="col-lg-1 col-md-1 col-sm-1" style="margin-left: -10px;width: 8%;">
                                 <div class="action-btn bg-info" style="margin-top: 33px;background: none;">
                                    <!-- <a class="mx-3 btn btn-sm  align-items-center refeshbuttonselect2 ti-reload text-white" 
                                    name="stp_print"
                                    title="Refresh"></a> -->
                                    <a href="{{ url('/rptpropertyowner')}}" target="_blank" data-url="{{ url('/rptpropertyowner/store') }}" title="{{__('New Profile User')}}" class="btn btn-sm btn-primary ti-plus" style="margin-top: 1px;font size: 9px;    width: 127px;">
                                    </a>
                                 </div>
                              </div>
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


                           <div class="row">
                              <button class="btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsethree" aria-expanded="false" aria-controls="flush-headingthree">
                                 <h6 class="sub-title accordiantitle">Property Location</h6>
                              </button>
                              <div class="col-lg-6 col-md-6 col-sm-6">
                                 <div class="form-group">
                                    {{Form::label('loc_group_brgy_no',__('Location'),['class'=>'form-label loc_group_brgy_no'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                       {{Form::text('loc_group_brgy_no',(isset($data->loc_group_brgy_no))?$data->loc_group_brgy_no:'',array('class'=>'form-control','rows'=>1))}}
                                    </div>
                                    <span class="validate-err" id="err_loc_group_brgy_no"></span>
                                 </div>
                              </div>

                              <div class="col-lg-6 col-md-6 col-sm-6">
                                 <div class="form-group">
                                    {{Form::label('rp_location_number_n_street',__('Number & Street'),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                                    <div class="form-icon-user">

                                       {{Form::textarea('rp_location_number_n_street',(isset($data->rp_location_number_n_street))?$data->rp_location_number_n_street:'',array('class'=>'form-control rp_location_number_n_street','rows'=>1))}}
                                    </div>
                                    <span class="validate-err" id="err_rp_location_number_n_street"></span>
                                 </div>
                              </div>

                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <!--------------- Owners Information Start Here---------------->
            <!--------------- Business Information Start Here---------------->

            <div class="col-lg-7 col-md-7 col-sm-7" id="accordionFlushExample2">
               <div class="accordion accordion-flush">
                  <div class="accordion-item" style="height:95%;">
                     <h6 class="accordion-header" id="flush-headingtwo">

                     </h6>

                     <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2" style="height: ;">
                        <div class="basicinfodiv">

                           <div class="row">
                              <div class="col-lg-11 col-md-11 col-sm-11" style="width: 94%;">
                                 <div class="form-group property_administrator_id_group">
                                    {{Form::label('property_administrator_id',__('Select Administrator'),['class'=>'form-label'])}}
                                    {{ Form::select('property_administrator_id',$profile,$data->rp_administrator_code,array('class'=>'form-control property_administrator_id','id'=>'property_administrator_id_pre','placeholder'=>'Select Name')) }}
                                 </div>
                                 <span class="validate-err" id="err_rp_administrator_code"></span>
                              </div>
                              <div class="col-lg-1 col-md-4 col-sm-4" style="margin-left:-10px;text-align: end;    margin-left: -10px;width: 5%;">
                                 <div class="action-btn bg-info" style="margin-top: 30px;background: none;">
                                   
                                    <a href="{{ url('/rptpropertyowner')}}" target="_blank"  data-url="{{ url('/rptpropertyowner/store') }}" class="btn btn-sm btn-primary ti-plus"  style="margin-top: 1px;width: 195px;">
                                    </a>
                                 </div>
                              </div>
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
                              <div class="col-lg-6 col-md-6 col-sm-6">
                                 <div class="form-group">
                                    {{Form::label('rp_cadastral_lot_no',__("Cad'L Lot No."),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                       {{Form::text('rp_cadastral_lot_no',$data->rp_cadastral_lot_no,array('class'=>'form-control rp_cadastral_lot_no','rows'=>1))}}
                                    </div>
                                    <span class="validate-err" id="err_rp_cadastral_lot_no"></span>
                                 </div>
                              </div>
                           
                              <div class="col-lg-6 col-md-6 col-sm-6">
                                 <div class="form-group">
                                    {{Form::label('rp_oct_tct_cloa_no',__('OCT/TCT No'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                       {{Form::text('rp_oct_tct_cloa_no',$data->rp_oct_tct_cloa_no,array('class'=>'form-control rp_oct_tct_cloa_no','rows'=>1))}}
                                    </div>
                                    <span class="validate-err" id="err_rp_oct_tct_cloa_no"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row" style="padding-top:0px;">
                              <div class="col-lg-3 col-md-3 col-sm-3">
                                 <div class="form-group">
                                    {{Form::label('rp_bound_north',__('North'),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                                    <div class="form-icon-user">
                                       {{Form::text('rp_bound_north',$data->rp_bound_north,array('class'=>'form-control rp_bound_north','rows'=>1))}}
                                    </div>
                                    <span class="validate-err" id="err_rp_bound_north"></span>
                                 </div>
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-3">
                                 <div class="form-group">
                                    {{Form::label('rp_bound_east',__('East'),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                                    <div class="form-icon-user">
                                       {{Form::text('rp_bound_east',$data->rp_bound_east,array('class'=>'form-control rp_bound_east','rows'=>1))}}
                                    </div>
                                    <span class="validate-err" id="err_rp_bound_east"></span>
                                 </div>
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-3">
                                 <div class="form-group">
                                    {{Form::label('rp_bound_south',__('South'),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                                    <div class="form-icon-user">
                                       {{Form::text('rp_bound_south',$data->rp_bound_south,array('class'=>'form-control rp_bound_south','rows'=>1))}}
                                    </div>
                                    <span class="validate-err" id="err_rp_bound_south"></span>
                                 </div>
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-3">
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
            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" style="padding-top: ;margin-top: -10px;">
               <div  class="accordion accordion-flush">
                  <div class="accordion-item">
                     <h6 class="accordion-header" id="flush-headingfive">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefour" aria-expanded="false" aria-controls="flush-headingfive">
                           <h6 class="sub-title accordiantitle">{{__("Land Appraisal")}}</h6>
                        </button>
                     </h6>
                     <div id="flush-collapsefour" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5" style="padding: 0px;margin-top: -30px;">
                        <div class="basicinfodiv">
                          
                           <div id="landAppraisalListing"></div>
                           <div class="row">
                              <div class="col-lg-8 col-md-8 col-sm-8">

                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-4">
                                 <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-5 col-form-label">Total Market Value : </label>
                                    <div class="col-sm-7">
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
         </div>
         <div class="row" >
            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" style="padding-top: 10px;">
               <div  class="accordion accordion-flush">
                  <div class="accordion-item">
                     <h6 class="accordion-header" id="flush-headingfive">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                           <h6 class="sub-title accordiantitle">{{__("Assessment Summary")}}</h6>
                        </button>
                     </h6>
                     <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5" style="">
                        <div class="basicinfodiv">
                           
                           <div id="assessementSummaryData"></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4">
         <div class="row">

         </div>
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12" style="">
               <div  class="accordion accordion-flush">
                  <div class="accordion-item">
                     <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseoneOwner" aria-expanded="false" aria-controls="flush-headingtwo">
                           <h6 class="sub-title accordiantitle">{{__("New Tax Declaration Reference")}}</h6>
                        </button>
                     </h6>
                     <div id="flush-collapseoneOwner" class="accordion-collapse collapse show" aria-labelledby="flush-headingone" data-bs-parent="#accordionFlushExampleOwner">
                        <div class="basicinfodiv">
                           <div class="row">
                              <div class="col-lg-6 col-md-6 col-sm-6">
                                 <div class="form-group">
                                    {{Form::label('rp_app_cancel_by_td_id',__('New Tax Declaration No.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    {{ Form::select('rp_app_cancel_by_td_id',$allTds,(isset($approvelFormData->rp_app_cancel_by_td_id))?$approvelFormData->rp_app_cancel_by_td_id:'',array('class' =>'form-control rp_app_cancel_by_td_id','id'=>'rp_app_cancel_by_td_id_pre')) }}
                                 </div>
                                 <span class="validate-err" id="err_rp_app_cancel_by_td_id"></span>
                              </div>

                           
                              <div class="col-lg-6 col-md-6 col-sm-6" style="padding-bottom: 10px;">
                                 <div class="form-group">
                                    {{Form::label('',__('Property Index No.'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                       {{Form::text('','',array('class'=>'form-control prop_index_no','readonly' => true))}}
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
                                 <div class="form-group">
                                    {{Form::label('',__('Taxpayer Name'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                       {{Form::text('','',array('class'=>'form-control tax_payer_name','readonly' => true))}}
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
                                 <div class="form-group">
                                    {{Form::label('',__('Address'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                       {{Form::text('','',array('class'=>'form-control tax_payer_address','readonly' => true))}}
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;margin-top: -13px;">
                                 <div class="card">
                                    <div class="card-body table-border-style">
                                       <div class="table-responsive" id="previousownerlandappraisaldetails">

                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="row">
                              <div class="col-lg-4 col-md-4 col-sm-4" style="padding-bottom: 10px;">
                                 <div class="form-group">
                                    {{Form::label('',__('Taxability'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                       {{Form::text('','',array('class'=>'form-control taxability','readonly' => true))}}
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-4" style="padding-bottom: 10px;">
                                 <div class="form-group">
                                    {{Form::label('',__('Effectivity'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                       {{Form::text('','',array('class'=>'form-control effectivity','readonly' => true))}}
                                       
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-4" style="padding-bottom: 10px;">
                                 <div class="form-group">
                                    {{Form::label('',__('Quarter'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                       {{Form::text('','',array('class'=>'form-control quarter','readonly' => true))}}
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="row">
                              <div class="col-lg-8 col-md-8 col-sm-8" style="padding-bottom: 10px;">
                                 <div class="form-group">
                                    {{Form::label('',__('Approved By'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                       {{Form::text('','',array('class'=>'form-control approved_by','readonly' => true))}}
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-4" style="padding-bottom: 10px;">
                                 <div class="form-group">
                                    {{Form::label('',__('Date'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                       {{Form::text('','',array('class'=>'form-control date','readonly' => true))}}
                                    </div>
                                 </div>
                              </div>

                           </div>

                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12" style="">
               <div  class="accordion accordion-flush">
                  <div class="accordion-item">
                     <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseoneOwner" aria-expanded="false" aria-controls="flush-headingtwo">
                           <h6 class="sub-title accordiantitle">{{__("Approval")}}</h6>
                        </button>
                     </h6>
                     <div id="flush-collapseoneOwner" class="accordion-collapse collapse show" aria-labelledby="flush-headingone" data-bs-parent="#accordionFlushExampleOwner">
                        <div class="basicinfodiv">
                           <div class="row">
                              <div class="col-lg-4 col-md-4 col-sm-4" style="padding-bottom: 10px;">
                                 <div class="form-group">
                                    {{Form::label('rp_app_taxability',__('Taxability'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                       {{Form::select('rp_app_taxability',[1=>'Taxable',0=>'Exempt'],(isset($data->rp_app_taxability))?$data->rp_app_taxability:'',array('class'=>'form-control rp_app_taxability','id'=>'rp_app_taxability_pre'))}}

                                    </div>
                                    <span class="validate-err" id="err_rp_app_taxability"></span>
                                 </div>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-4" style="padding-bottom: 10px;">
                                 <div class="form-group">
                                    {{Form::label('rp_app_effective_year',__('Effectivity'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                       <!-- {{Form::text('rp_app_effective_year','',array('class'=>'form-control rp_app_effective_year','id'=>'rp_app_effective_year_pre'))}} -->
                                       {{ Form::text('rp_app_effective_year',(isset( $data->rp_app_effective_year))? $data->rp_app_effective_year:'', array('class' => 'yearpicker form-control','id'=>'year')) }}
                                    </div>
                                    <span class="validate-err" id="err_rp_app_effective_year"></span>
                                 </div>
                              </div>
                              
                              <div class="col-lg-4 col-md-4 col-sm-4" style="padding-bottom: 10px;">
                                 <div class="form-group">
                                    {{Form::label('rp_app_effective_quarter',__('Quarter'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                       {{Form::select('rp_app_effective_quarter',['1'=>'1st','2'=>'2nd','3'=>'3rd','4'=>'4th'],(isset($data->rp_app_effective_quarter))?$data->rp_app_effective_quarter:'',array('class'=>'form-control rp_app_effective_quarter','rows'=>1,'id'=>'rp_app_effective_quarter_pre'))}}

                                    </div>
                                    <span class="validate-err" id="err_rp_app_effective_quarter"></span>
                                 </div>
                              </div>
                           </div>

                           <div class="row">
                              <div class="col-lg-8 col-md-8 col-sm-8" style="padding-bottom: 10px;">
                                 <div class="form-group">
                                    {{Form::label('rp_app_approved_by',__('Approved By'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                       {{Form::select('rp_app_approved_by',$appraisers,(isset($approvelFormData->rp_app_approved_by))?$approvelFormData->rp_app_approved_by:'',array('class'=>'form-control rp_app_approved_by','id'=>'rp_app_approved_by_pre','placeholder'=>'Select Name'))}}

                                    </div>
                                    <span class="validate-err" id="err_rp_app_approved_by"></span>
                                 </div>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-4" style="padding-bottom: 10px;">
                                 <div class="form-group">
                                    {{Form::label('rp_app_posting_date',__('Date'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                       {{Form::date('rp_app_posting_date',(isset($data->rp_app_posting_date))?$data->rp_app_posting_date:date("Y-m-d"),array('class'=>'form-control rp_app_posting_date'))}}
                                    </div>
                                    <span class="validate-err" id="err_rp_app_posting_date"></span>
                                 </div>
                              </div>

                           </div>

                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>


      </div>
   </div>

</div>

<div class="modal-footer">
   <input type="button" value="{{__('Cancel')}}" class="btn btn-light eventOnCloseModal" data-bs-dismiss="modal">
   <button type="button" id="submittaxdeclarationformForPreviousOwner" class="btn  btn-primary" >{{ ($data->id)>0?__('Update'):__('Create')}}</button>
</div>
{{Form::close()}}
<div class="modal" id="addlandappraisalmodalForPreOwner" data-backdrop="static" style="z-index:9999999;">
   <div class="modal-dialog modal-lg modalDiv" >
      <div class="modal-content" id="landappraisalformForPreOwner">
      </div>
   </div>
</div>

<!-- <div class="modal" id="addPropertyOwnerModal" data-backdrop="static" style="z-index:9999999 !important;">
   <div class="modal-dialog " >
      <div class="modal-content" >
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="body">
         </div>
      </div>
   </div>
</div> -->

<input type="hidden" name="dynamicid" value="3" id="dynamicid">
<script src="{{ asset('js/addPreviousOwnerLand.js') }}?rand={{rand(0,99)}}"></script>
<script src="{{ asset('js/ajax_rptProperty.js') }}?rand={{rand(0,99)}}"></script>
<script type="text/javascript">
   var yearpickerInput = $('input[name="rp_app_effective_year"]').val();
      $('.yearpicker').yearpicker();
      $('.yearpicker').val(yearpickerInput).trigger('change');
   // var effectiveYear = '{{ (isset($data->rp_app_effective_year))?$data->rp_app_effective_year:''}}';
   // $('#rp_app_effective_year_pre').val(effectiveYear);
   setTimeout(function(){ 
      var id = "{{($data->rpo_code != '')?$data->rpo_code:''}}";
      var text = "{{(isset($data->property_owner_details->full_name) && $data->property_owner_details != '')?$data->property_owner_details->full_name:'Select Property Owner'}}";
              $('#addPreviousOwnerForLandModal').find("#profile_pre").select3("trigger", "select", {
    data: { id: id ,text:text}
});
      var adminid = "{{($data->rp_administrator_code != '')?$data->rp_administrator_code:''}}";
      var admintext = "{{(isset($data->property_admin_details->full_name) && $data->property_owner_details != '')?$data->property_admin_details->full_name:'Select Property Administrator'}}";
               $("#property_administrator_id_pre").select3("trigger", "select", {
    data: { id: adminid ,text:admintext}
});
}, 500);
</script>
<!-- <script src="{{ asset('assets/js/yearpicker.js') }}"></script> -->