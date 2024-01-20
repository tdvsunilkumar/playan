{{Form::open(array('name'=>'forms','url'=>'rptbuilding/loadpreviousowner','method'=>'post','id'=>'propertyPreviousOwnerForm'))}}
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
    padding-bottom: 5%;
    padding: 12px;
}
   #addPreviousOwnerForBuildingModal .modal-xll {
      max-width: 100% !important;
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
   /*.pt10{
      padding-top:10px;
   }*/
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
.row {
    padding-top: 0px;
}
</style>
<div class="modal-body">
   <div class="row" style="padding-top: 0px;">
      <div class="col-lg-8 col-md-8 col-sm-8">
           <div class="row" style="padding-top: 0px;">
      <div class="col-lg-5 col-md-5 col-sm-5">
         <div class="row" style="padding-top: 0px;">
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
                     {{Form::select('brgy_code_id',$arrBarangay,($data->brgy_code_id != '')?$data->brgy_code_id:session()->get('buildingSelectedBrgy'),array('class'=>'form-control ','id'=>'brgy_code_id','onmousedown'=>'return false;','readonly'))}}
                     <input type="hidden" name="brgy_code" value="">
                  </div>
                  <span class="validate-err" id="err_brgy_code_id"></span>
               </div>
            </div>
            @if(isset($data->id) && $data->id != 0)
            <div class="col-lg-3 col-md-3 col-sm-3">
               <div class="form-group">
                  {{Form::label('brgy_code_id',__("TD No."),['class'=>'form-label'])}}<span class="text-danger">*</span>
                  <div class="form-icon-user">
                     @php
                        $rp_Td_No = str_pad($data->rp_td_no, 5, '0', STR_PAD_LEFT);
                     @endphp
                     {{Form::text('rp_td_no',$rp_Td_No,array('class'=>'form-control rp_td_no','readonly'=>'readonly'))}}
                  </div>
                  <span class="validate-err" id="err_rp_td_no"></span>
               </div>
            </div>
            @endif
            <input type="hidden" name="rp_property_code" value="{{ $data->rp_property_code }}">
            <input type="hidden" name="rp_tax_declaration_no" value="{{ $data->rp_tax_declaration_no}}"> 
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
         <div class="row" style="padding-top: 0px;">
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
                  {{Form::label('rp_pin_suffix',__("PIN Suffix"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                  <div class="form-icon-user">
                     {{Form::text('rp_pin_suffix',$data->rp_pin_suffix,array('class'=>'form-control rp_pin_suffix','placeholder'=>'PIN Suffix','readonly'=>true))}}
                  </div>
                  <span class="validate-err" id="err_rp_pin_suffix"></span>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="row pt10" >
      <!--------------- Owners Information Start Here---------------->
     <div class="col-lg-5 col-md-5 col-sm-5"  id="accordionFlushExampleOwner1">
         <div  class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingone-owner">
                  <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone-owner1" aria-expanded="false" aria-controls="flush-collapseone-owner1">
                     <h6 class="sub-title accordiantitle">{{__("Owner Information")}}</h6>
                  </button>
               </h6>
               <div id="flush-collapseone-owner1" class="accordion-collapse collapse show" aria-labelledby="flush-headingone-owner" data-bs-parent="#accordionFlushExampleOwner1" style="height: ;">
                  <div class="basicinfodiv" style="margin-top:-20px;">
                     
                            <div class="row" style="padding-top: 0px;">
                                <div class="col-lg-11 col-md-11 col-sm-11">
                                    <div class="form-group" id="owner">
                                       {{Form::label('profile_id',__('Owner'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                       {{ Form::select('profile_id',[],$data->rpo_code, array('class' => 'form-control select3','id'=>'profile_id_pre','placeholder'=>'Select Name')) }}
                                    </div>
                                    <span class="validate-err" id="err_profile_id"></span>
                                 </div>
                                 <div class="col-lg-1 col-md-1 col-sm-1">
                                    <div class="" style="    margin-top: 22px;
                                        background: none;
                                        border: none;
                                        margin-left: -18px;
                                    ">
                                       <!-- <a class="mx-3 btn btn-sm  align-items-center refeshbuttonselect2 ti-reload text-white" 
                                          name="stp_print"
                                          tiCCT Notle="Refresh"></a> -->
                                          <a href="{{ url('/rptpropertyowner')}}" target="_blank" data-url="{{ url('/rptpropertyowner/store') }}" title="{{__('New Profile User')}}" class="btn btn-sm btn-primary ti-plus" style="margin-top: 8px;font size: 9px;"></a>
                                    </div>
                                 </div>
                                <!-- <div class="col-lg-4 col-md-2 col-sm-2">
                                    <div class="form-group" style="margin-top: 25px;">
                                       <a href="#"  data-size="xl" data-url="{{ url('/rptpropertyowner/store') }}" data-bs-toggle="tooltip" title="{{__('New Profile User')}}" data-toggle="modal" href="#addPropertyOwnerModal" class="btn btn-sm btn-primary addNewPropertyOwner" style="margin-top: 8px;font-size: 13px;width: 143px;">
                                       Change Owner</a>
                                    </div>
                                 </div>-->
                                 <!-- <div class="col-lg-3 col-md-3 col-sm-3" style="margin-left:-7px;">
									 <div class="form-group" style="margin-top: 25px;">
										<a href="{{ url('/rptpropertyowner')}}" target="_blank" data-url="{{ url('/rptpropertyowner/store') }}" title="{{__('New Profile User')}}" class="btn btn-sm btn-primary " style="margin-top: 8px;font size: 9px;    width: 127px;">
										Change Owner</a>
									 </div>
								  </div> -->
                                 <div class="col-lg-11 col-md-11 col-sm-11" style="padding-top:4px;">
                                 <div class="form-group">
                                    {{Form::label('property_administrator_id',__('Administrator'),['class'=>'form-label'])}}
                                    {{ Form::select('property_administrator_id',[],$data->rp_administrator_code,array('class'=>'form-control property_administrator_id','id'=>'property_administrator_id_pre','placeholder'=>'Select Name')) }}
                                 </div>
                                 <span class="validate-err" id="err_rp_administrator_code"></span>
                              </div>

                              <div class="col-lg-1 col-md-1 col-sm-1" style="padding-top:4px;">
                                 <div class="" style="    margin-top: 22px;
                                        background: none;
                                        border: none;
                                        margin-left: -18px;
                                    ">
                                    <!-- <a class="mx-3 btn btn-sm  align-items-center refeshbuttonselect ti-reload text-white" 
                                       name="stp_print"
                                       title="Refresh"></a> -->
                                       <a href="{{ url('/rptpropertyowner')}}" target="_blank" data-url="{{ url('/rptpropertyowner/store') }}" title="{{__('New Profile User')}}" class="btn btn-sm btn-primary ti-plus" style="margin-top: 8px;font size: 9px;"></a>
                                 </div>
                              </div>
                             <!--  <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group" style="margin-top: 25px;">
                                    <a href="#"  data-size="xl" data-url="{{ url('/rptpropertyowner/store') }}"  data-bs-toggle="tooltip" title="{{__('New Profile User')}}" class="btn btn-sm btn-primary addNewPropertyOwner" style="margin-top: 8px;font-size: 13px;">
                                    Change Administrator</a>
                                 </div>
								 <div class="form-group" style="margin-top: 25px;">
									<a href="{{ url('/rptpropertyowner')}}" target="_blank" data-url="{{ url('/rptpropertyowner/store') }}" title="{{__('New Profile User')}}" class="btn btn-sm btn-primary " style="margin-top: 8px;font size: 9px;width:127px;">
									Change Administrator</a>
								 </div>
                              </div> -->
                              <div class="col-lg-12 col-md-12 col-sm-12">
                                 
                              
                        </div>
                     </div>
                  </div>
               </div>
            </div>   
         </div>
      </div>
      <div class="col-lg-7 col-md-7 col-sm-7" id="accordionFlushExample2">
         <div class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingtwo">
                  <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                     <h6 class="sub-title accordiantitle">{{__('')}}</h6>
                     </button>
               </h6>
               <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2" style="margin-top: -25px;">
                  <div class="basicinfodiv">
                     <div class="row" style="padding-top: 0px;">
                        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-top: 5px;">
                           <div class="form-group">
                              {{Form::label('property_owner_address',__('Address'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                              <div class="form-icon-user">
                                 {{Form::text('property_owner_address',$data->property_owner_address,array('class'=>'form-control property_owner_address'))}}
                                 <input type="hidden" value="{{ $data->rpo_code }}" name="rpo_code">
                              </div>
                              <span class="validate-err" id="err_property_owner_address"></span>
                           </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8"  style="padding-top: 5px;">
                           <div class="form-group">
                              {{Form::label('rp_administrator_code',__('Administrator Address'),['class'=>'form-label'])}}
                              <div class="form-icon-user">
                                 {{Form::text('rp_administrator_code_address',$data->rp_administrator_code_address,array('class'=>'form-control','rows'=>1))}}
                                 <input type="hidden" name="rp_administrator_code" value="{{ $data->rp_administrator_code }}">
                              </div>
                              <span class="validate-err" id="err_rp_administrator_code"></span>
                           </div>
                        </div>
                       <div class="col-lg-4 col-md-4 col-sm-4"  style="padding-top: 5px;">
                           <div class="form-group">
                              {{Form::label('rp_location_number_n_street',__('Number & Street'),['class'=>'form-label'])}}
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
                     </div>
       <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExampleOwner">
         <div  class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingone-owner">
                  <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone-owner" aria-expanded="false" aria-controls="flush-collapseone-owner">
                     <h6 class="sub-title accordiantitle">{{__("Land Reference")}}</h6>
                  </button>
               </h6>
               <div id="flush-collapseone-owner" class="accordion-collapse collapse show" aria-labelledby="flush-headingone-owner" data-bs-parent="#accordionFlushExampleOwner">
                  <div class="basicinfodiv" style="margin-top: -20px;">
                     <div class="row" style="font-weight: 600;">
                       
                        <div class="col-lg-4 col-md-4 col-sm-4">
                           <div class="row" style="padding-top: 0px;">
                              <div class="col-lg-12 col-md-12 col-sm-12" id="tax">
                                 <div class="form-group" >
                                    {{Form::label('rp_td_no_lref',__('Land Tax Declaration Reference'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <input type="hidden" value="{{$data->rp_code_lref}}">
                                    <div class="form-icon-user">
                                      
                                       {{ Form::select('rp_code_lref',[],$data->rp_code_lref, array('class' => 'form-control rp_code_lref','id'=>'rp_code_lref_pre','placeholder'=>'Please Select Tax Declaration No.')) }}
                                       <input type="hidden" name="rp_td_no_lref" id="rp_td_no_lref" value="{{ (isset($data->rp_td_no_lref))?$data->rp_td_no_lref:'' }}">
                                       <input type="hidden" name="rp_suffix_lref" id="rp_suffix_lref" value="{{ (isset($data->rp_suffix_lref))?$data->rp_suffix_lref:'' }}">
                                       <input type="hidden" name="rp_oct_tct_cloa_no_lref" id="rp_oct_tct_cloa_no_lref" value="{{ (isset($data->rp_oct_tct_cloa_no_lref))?$data->rp_oct_tct_cloa_no_lref:'' }}">
                                    </div>
                                    <span class="validate-err" id="err_rp_td_no_lref"></span>
                                 </div>
                              </div>
                             <!--<div class="col-lg-2 col-md-2 col-sm-2">
                                 <div class="form-group" style="margin-top: 25px;">
                                    <a href="#"  data-url="{{ url('/rptbuilding/searchland') }}"  title="{{__('Search For Land')}}" data-toggle="modal" class="btn btn-sm btn-primary searchlandDetails" style="margin-top: 8px;">
                                    Search</a>
                                 </div>
                              </div> -->
                           </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2"style="text-align: left;padding-top: 28px;">
                           <div class="select-group">
                              <div class="form-icon-user">
                               {{ Form::checkbox('checkowner','1', ('')?true:false, array('id'=>'checkowner','class'=>'form-check-input myCheckbox','style'=>'margin-top:9px')) }} {{Form::label('',__('Taxpayer Reference'),['class'=>'form-label','style'=>'padding-top: 7px;'])}}
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                           <div class="form-group">
                              {{Form::label('landowner',__('Land Owner'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                              <div class="form-icon-user">
                                 {{Form::text('land_owner',(isset($data->land_owner))?$data->land_owner:'',array('class'=>'form-control land_owner','readonly'=>'readonly','id'=>'land_owner'))}}
                              </div>
                              <span class="validate-err" id="err_rpo_code_lref"></span>
                           </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                           <div class="form-group">
                              {{Form::label('landowner',__('PIN'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                              <div class="form-icon-user">
                                 {{Form::text('rp_pin_no_lref','',array('class'=>'form-control land_owner','readonly'=>'readonly','id'=>'rp_pin'))}}
                                 <input type="hidden" name="rpo_code_lref" id="rpo_code_lref" value="{{ (isset($data->rpo_code_lref))?$data->rpo_code_lref:'' }}">
                              </div>
                              <span class="validate-err" id="err_rpo_code_lref"></span>
                           </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4">
                           <div class="form-group">
                              {{Form::label('location',__('Location'),['class'=>'form-label rp_bulding_permit_no'])}}<span class="text-danger">*</span>
                              <div class="form-icon-user">
                                 {{Form::text('land_location',(isset($data->land_location))?$data->land_location:'',array('class'=>'form-control land_location','readonly'=>'readonly','id'=>'land_location'))}}
                              </div>
                              <span class="validate-err" id="err_land_location"></span>
                           </div>
                        </div>
                        
                        <div class="col-lg-2 col-md-2 col-sm-2">
                           <div class="form-group">
                              {{Form::label('rp_cadastral_lot_no_lref',__('Cadl Lot No'),['class'=>'form-label cadlotno'])}}<span class="text-danger">*</span>
                              <div class="form-icon-user">
                                 {{Form::text('rp_cadastral_lot_no_lref',(isset($data->rp_cadastral_lot_no_lref))?$data->rp_cadastral_lot_no_lref:'',array('class'=>'form-control','id'=>'rp_cadastral_lot_no_lref','readonly'=>'readonly','id'=>'rp_cadastral_lot_no_lref'))}}
                              </div>
                              <span class="validate-err" id="err_rp_cadastral_lot_no_lref"></span>
                           </div>
                        </div>
                              
                              <div class="col-lg-2 col-md-2 col-sm-2">
                                 <div class="form-group">
                                    {{Form::label('rp_total_land_area',__('Land Area'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                       {{Form::text('rp_total_land_area',(isset($data->rp_total_land_area))?$data->rp_total_land_area:'',array('class'=>'form-control rp_total_land_area','readonly'=>'readonly','id'=>'rp_total_land_area'))}}
                                    </div>
                                    <span class="validate-err" id="err_rp_total_land_area"></span>
                                 </div>
                              </div>
                              <div class="col-lg-2 col-md-2 col-sm-2">
                                 <div class="form-group">
                                    {{Form::label('rp_section_no_lref',__('Section No.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                       {{Form::text('rp_section_no_lref','',array('class'=>'form-control rp_total_land_area','readonly'=>'readonly','id'=>'sectionNo'))}}
                                    </div>
                                    <span class="validate-err" id="err_rp_total_land_area"></span>
                                 </div>
                              </div>
                              <div class="col-lg-2 col-md-2 col-sm-2">
                                 <div class="form-group">
                                    {{Form::label('asslot',__('Ass. Lot No.'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                       {{Form::text('','',array('class'=>'form-control asslot','readonly'=>'readonly','id'=>'asslot'))}}
                                    </div>
                                    <span class="validate-err" id="err_rp_total_land_area"></span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <br>
                  </div>
               </div>
            </div>
        
      <!--------------- Owners Information Start Here---------------->
  <div class="row" >
      <!--------------- Taxable Items Start Here---------------->
      <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" style="padding-top: 0px;margin-top: -15px;">
         <div  class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingfive">
                  <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                     <h6 class="sub-title accordiantitle">{{__("Building Classification")}}</h6>
                  </button>
               </h6>
               <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                  <div class="basicinfodiv" style="margin-top: -35px;">
                     <div class="row" style="">
                        
                        <div class="col-sm-6" style="padding-left:0px;">
                           <div class="row" style="padding-top: 0px;">
                             
                           </div>
                        </div>
                     </div>
                     <div class="row" style="padding-top: 0;margin-top: -12px;">
                        <div class="row" style="    padding-top: 30px;">
                           <div class="col-lg-6 col-md-6 col-sm-6">
                              <div class="row" style="padding-top: 0px;">
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                       {{Form::label('bk_building_kind_code',__('Kind OF Building'),['class'=>'form-label loc_group_brgy_no'])}}<span class="text-danger">*</span>
                                       <div class="form-icon-user">
                                          {{ Form::select('bk_building_kind_code',$buildingKinds,(isset($data->bk_building_kind_code))?$data->bk_building_kind_code:'', array('class' => 'form-control  bk_building_kind_code','id'=>'bk_building_kind_code','placeholder'=>'Select Building Kind')) }}
                                       </div>
                                       <span class="validate-err" id="err_bk_building_kind_code"></span>
                                    </div>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                       {{Form::label('pc_class_code',__('Building Class'),['class'=>'form-label loc_group_brgy_no'])}}<span class="text-danger">*</span>
                                       <div class="form-icon-user">
                                          {{ Form::select('pc_class_code',$arrPropertyClasses,(isset($data->pc_class_code))?$data->pc_class_code:'', array('class' => 'form-control pc_class_code','id'=>'profile','placeholder'=>'Select Building Class')) }}
                                       </div>
                                       <span class="validate-err" id="err_pc_class_code"></span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-6 col-md-6 col-sm-6">
                              <div class="row" style="padding-top: 0px;">
                                 <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                       {{Form::label('buildingtype',__('Actual Use'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                       <div class="form-icon-user">
                                          {{ Form::text('buildingtype','', array('class' => 'form-control buildingtype','id'=>'profile','readonly'=>true)) }}
                                       </div>
                                       <span class="validate-err" id="err_buildingtype"></span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row" style="padding-top: 0px;">
                           <div class="col-lg-6 col-md-6 col-sm-6">
                              <div class="row" >
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                 {{Form::label('rp_building_name',__('Building Name'),['class'=>'form-label loc_group_brgy_no'])}}
                                 <div class="form-icon-user">
                                    {{Form::text('rp_building_name',(isset($data->rp_building_name))?$data->rp_building_name:'',array('class'=>'form-control rp_building_name','id'=>'rp_building_name'))}}
                                 </div>
                                 <span class="validate-err" id="err_rp_building_name"></span>
                              </div>
                                 </div>
                                 <div class="col-sm-3">
                                    <div class="form-group">
                                       {{Form::label('rp_building_age',__('Age'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                       {{Form::number('rp_building_age',(isset($data->rp_building_age))?$data->rp_building_age:'',array('class'=>'form-control rp_building_age','readonly'=>true))}}
                                    </div>
                                    <span class="validate-err" id="err_rp_building_age"></span>
                                 </div>
                                 <div class="col-sm-3">
                                    <div class="form-group">
                                       {{Form::label('rp_building_no_of_storey',__('Storey'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                       {{Form::number('rp_building_no_of_storey',(isset($data->rp_building_no_of_storey))?$data->rp_building_no_of_storey:'',array('class'=>'form-control rp_building_no_of_storey','readonly'=>true))}}
                                    </div>
                                    <span class="validate-err" id="err_rp_building_no_of_storey"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-6 col-md-6 col-sm-6">
                              <div class="row" >
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                       {{Form::label('rp_constructed_month',__('Constructed'),['class'=>'form-label loc_group_brgy_no'])}}<span class="text-danger">*</span>
                                       <div class="form-icon-user">
                                          {{Form::month('rp_constructed_month',(isset($data->rp_constructed_month) && isset($data->rp_constructed_year))?date("Y-m",strtotime($data->rp_constructed_year.'-'.$data->rp_constructed_month)):'',array('class'=>'form-control rp_constructed_month','id'=>'constructedmonth'))}}
                                       </div>
                                       <span class="validate-err" id="err_rp_constructed_month"></span>
                                    </div>
                                 </div>
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                       {{Form::label('rp_occupied_month',__('Occupied'),['class'=>'form-label loc_group_brgy_no'])}}<span class="text-danger">*</span>
                                       {{Form::month('rp_occupied_month',(isset($data->rp_occupied_month) && isset($data->rp_occupied_year))?date("Y-m",strtotime($data->rp_occupied_year.'-'.$data->rp_occupied_month)):'',array('class'=>'form-control rp_occupied_month','id'=>'rp_occupied_month'))}}
                                    </div>
                                    <span class="validate-err" id="err_rp_occupied_month"></span>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row" style="padding-top: 0px;">
                           <div class="col-lg-4 col-md-4 col-sm-4">
                              <div class="form-group">
                                       {{Form::label('rp_bulding_permit_no',__('Permit No.'),['class'=>'form-label'])}}
                                       {{Form::hidden('rp_bulding_permit_no',(isset($data->rp_bulding_permit_no))?$data->rp_bulding_permit_no:'',array('class'=>'form-control rp_bulding_permit_no'))}}

                                       {{Form::select('permit_id',[],(isset($data->permit_id))?$data->permit_id:'',array('class'=>'form-control rp_bulding_permit_no','id' => 'permit_id_pre'))}}
                                    </div>
                                    <span class="validate-err" id="err_rp_bulding_permit_no"></span>
                           </div>
                           <div class="col-lg-2 col-md-2 col-sm-2">
                              
                              <div class="form-icon-user" style="margin-top:27px;">
                               {{ Form::checkbox('is_manual_permit','1', ($data->is_manual_permit)?true:false, array('id'=>'manual_entry','class'=>'form-check-input manual_entry','style'=>'margin-top:9px')) }} {{Form::label('',__('Manual Entry'),['class'=>'form-label','style'=>'padding-top: 7px;'])}}
                              </div>
                          
                                    <span class="validate-err" id="err_rp_bulding_permit_no"></span>
                           </div>
                           <div class="col-lg-6 col-md-6 col-sm-6">
                              <div class="row" style="padding-top: 0px;">
                                 <div class="col-sm-6">
                                    <div class="form-group" id="year">
                                       {{Form::label('rp_building_completed_year',__('Year Completed'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                       <div class="form-icon-user">{{Form::text('rp_building_completed_year',(isset($data->rp_building_completed_year))?$data->rp_building_completed_year:'',array('class'=>'form-control rp_building_completed_year','autocomplete'=>'off'))}}
                                       </div>
                                       <span class="validate-err" id="err_rp_building_completed_year"></span>
                                    </div>
                                 </div>
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                       {{Form::label('rp_building_completed_percent',__(' Completed[%]'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                       <div class="form-icon-user">
                                          {{Form::number('rp_building_completed_percent',(isset($data->rp_building_completed_percent))?$data->rp_building_completed_percent:'',array('class'=>'form-control rp_building_completed_percent'))}}
                                       </div>
                                       <span class="validate-err" id="err_rp_building_completed_percent"></span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row" style="padding-top: 0px;">
                           <div class="col-lg-3 col-md-3 col-sm-3">
                              <div class="form-group">
                                 {{Form::label('rp_building_cct_no',__('CCT No'),['class'=>'form-label loc_group_brgy_no'])}}
                                 <div class="form-icon-user">
                                    {{Form::text('rp_building_cct_no',(isset($data->rp_building_cct_no))?$data->rp_building_cct_no:'',array('class'=>'form-control rp_building_cct_no','id'=>'rp_building_cct_no'))}}
                                 </div>
                                 <span class="validate-err" id="err_rp_building_cct_no"></span>
                              </div>
                           </div>
                           <div class="col-lg-3 col-md-3 col-sm-3">
                              <div class="form-group">
                                 {{Form::label('rp_building_unit_no',__('Unit No'),['class'=>'form-label rp_building_unit_no'])}}
                                 <div class="form-icon-user">
                                    {{Form::text('rp_building_unit_no',(isset($data->rp_building_unit_no))?$data->rp_building_unit_no:'',array('class'=>'form-control rp_building_unit_no','id'=>'rp_building_unit_no'))}}
                                    <input type="hidden" name="rp_depreciation_rate" class="rp_depreciation_rate" value="{{(isset($data->rp_depreciation_rate))?$data->rp_depreciation_rate:0}}">
                                    
                                 </div>
                                 <span class="validate-err" id="err_rp_building_unit_no"></span>
                              </div>
                           </div>
                           <div class="col-lg-3 col-md-3 col-sm-3">
                              <div class="row" style="padding-top: 0px;">
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                       {{Form::label('rp_building_gf_area',__('Area Of Ground Floor'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                       <div class="form-icon-user">{{Form::text('rp_building_gf_area',(isset($data->rp_building_gf_area))?$data->rp_building_gf_area:'',array('class'=>'form-control rp_building_gf_area','readonly' => true))}}
                                       </div>
                                       <span class="validate-err" id="err_rp_building_gf_area"></span>
                                    </div>
                                 </div>
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                       {{Form::label('rp_building_total_area',__('Total Area'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                       <div class="form-icon-user">
                                          {{Form::text('rp_building_total_area',(isset($data->rp_building_total_area))?$data->rp_building_total_area:'',array('class'=>'form-control rp_building_total_area decimalvalue','readonly' => true))}}
                                       </div>
                                       <span class="validate-err" id="err_rp_building_total_area"></span>
                                    </div>
                                 </div>

                              </div>
                           </div>
                           <div class="col-lg-3 col-md-3 col-sm-3">
                                 <div class="form-group">
                                    {{Form::label('total_assessed_value',__('Total Assessed Value'),['class'=>'form-label total_assessed_value'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                       {{Form::text('total_assessed_value',(isset($data->total_assessed_value))?$data->total_assessed_value:'',array('class'=>'form-control total_assessed_value','id'=>'total_assessed_value'))}}
                                       <input type="hidden" name="rp_depreciation_rate" class="rp_depreciation_rate" value="">
                                       
                                    </div>
                                    <span class="validate-err" id="err_total_assessed_value"></span>
                                 </div>
                           </div>
                           
                           
                           <div class="col-lg-12 col-md-12 col-sm-12">
                              <div class="row" style="padding-top: 0px;">
                            <div class="col-lg-3 col-md-3 col-sm-3">
                           <a data-toggle="modal" href="javascript:void(0)" id="loadStructuralCharacter" class="btn btn-primary" type="add" style="margin-top: 8px;width:100%;">Structural Characteristics</a>
                               </div>
                                 <div class="col-lg-3 col-md-3 col-sm-3">
                                    <a data-toggle="modal" data-propertyid="{{ $data->id }}" href="javascript:void(0)" id="displayFloorValueModal" class="btn btn-primary" type="add" style="margin-top: 8px; width:100%;">Building Floor Value & Description </a>
                                 </div>
                                 <div class="col-lg-3 col-md-3 col-sm-3">
                                    <a data-toggle="modal" data-propertyid="{{ $data->id }}" href="javascript:void(0)" id="displayFloorValueDepreciationModal" class="btn btn-primary" style="margin-top: 8px;width:100%;" type="add">Building Value Depreciation</a>
                                    <span class="validate-err" id="selectAtLeastOneSummary"></span>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <br />
                     </div>

                     <div class="row" >
                        <!--------------- Taxable Items Start Here---------------->
                        
                        <!--------------- Taxable Items End Here------------------>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample6" style="padding-top: 0;">
                           <div  class="accordion accordion-flush">
                              <div class="accordion-item">
                                 <h6 class="accordion-header" id="flush-headingfive1">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive1" aria-expanded="false" aria-controls="flush-headingfive1">
                                       <h6 class="sub-title accordiantitle">{{__("Assessment Summary")}}</h6>
                                    </button>
                                 </h6>
                                 <div id="flush-collapsefive1" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive1" data-bs-parent="#accordionFlushExample6">
                                    <div class="basicinfodiv">
                                       <div class="row" style="padding-top: 10px;">
                                          <div class="col-sm-6">
                                          </div>
                                       </div>
                                       <!--------------- Land Apraisal Listing Start Here------------------>
                                       <div id="assessementSummaryData" style="    margin-top: -40px;"></div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
         <!--------------- Taxable Items End Here------------------>
      </div>
   </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4">
         <div class="row" style="padding-top: 0px;">

         </div>
         <div class="row" style="padding-top: 0px;">
            <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:75px;">
               <div  class="accordion accordion-flush">
                  <div class="accordion-item">
                     <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseoneOwner" aria-expanded="false" aria-controls="flush-headingtwo">
                           <h6 class="sub-title accordiantitle">{{__("New Tax Declaration Reference")}}</h6>
                        </button>
                     </h6>
                     <div id="flush-collapseoneOwner" class="accordion-collapse collapse show" aria-labelledby="flush-headingone" data-bs-parent="#accordionFlushExampleOwner">
                        <div class="basicinfodiv" style="margin-top: -20px;">
                           <div class="row" style="padding-top: 0px;">
                              <div class="col-lg-12 col-md-12 col-sm-12">
                                 <div class="form-group">
                                    {{Form::label('rp_app_cancel_by_td_id',__('New Tax Declaration No.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    {{ Form::select('rp_app_cancel_by_td_id',$allTds,(isset($approvelFormData->rp_app_cancel_by_td_id))?$approvelFormData->rp_app_cancel_by_td_id:'',array('class' =>'form-control rp_app_cancel_by_td_id','id'=>'rp_app_cancel_by_td_id_pre')) }}
                                 </div>
                                 <span class="validate-err" id="err_rp_app_cancel_by_td_id"></span>
                              </div>

                           </div>
                           <div class="row" style="padding-top: 0px;">
                              <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;padding-top: 5px;">
                                 <div class="form-group">
                                    {{Form::label('',__('Property Index No.'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                       {{Form::text('','',array('class'=>'form-control prop_index_no','readonly' => true))}}
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="row" style="padding-top: 0px;">
                              <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
                                 <div class="form-group">
                                    {{Form::label('',__('Taxpayer Name'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                       {{Form::text('','',array('class'=>'form-control tax_payer_name','readonly' => true))}}
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row" style="padding-top: 0px;">
                              <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
                                 <div class="form-group">
                                    {{Form::label('',__('Address'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                       {{Form::text('','',array('class'=>'form-control tax_payer_address','readonly' => true))}}
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="row" style="padding-top: 0px;">
                              <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;margin-top: -20px;">
                                 <div class="card">
                                    <div class="card-body table-border-style">
                                       <div class="table-responsive" id="previousownerlandappraisaldetails">

                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="row" style="padding-top: 0px;">
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

                           <div class="row" style="padding-top: 0px;">
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

         <div class="row" style="padding-top: 0px;">
            <div class="col-lg-12 col-md-12 col-sm-12" style="">
               <div  class="accordion accordion-flush">
                  <div class="accordion-item">
                     <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseoneOwner" aria-expanded="false" aria-controls="flush-headingtwo">
                           <h6 class="sub-title accordiantitle">{{__("Approval")}}</h6>
                        </button>
                     </h6>
                     <div id="flush-collapseoneOwner" class="accordion-collapse collapse show" aria-labelledby="flush-headingone" data-bs-parent="#accordionFlushExampleOwner">
                        <div class="basicinfodiv" style="margin-top: -20px;">
                           <div class="row" style="padding-top: 0px;">
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
                                       {{Form::text('rp_app_effective_year',(isset($data->rp_app_effective_year))?$data->rp_app_effective_year:'',array('class'=>'form-control rp_app_effective_year','id'=>'rp_app_effective_year_pre','autocomplete' => 'off'))}}
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

                           <div class="row" style="padding-top: 0px;">
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
<div class="modal" id="addFloorValueFormmodal" data-backdrop="static" style="z-index:9999999;">
   <div class="modal-dialog modal-lg modalDiv" >
      <div class="modal-content" id="floorValueForm">
      </div>
   </div>
</div>
<div class="modal" id="addPropertyOwnerModal" data-backdrop="static" style="z-index:9999999 !important;">
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
</div>
<div class="modal" id="addStructuralCharacterModal" data-backdrop="static" style="z-index:9999999 !important;">
   <div class="modal-dialog modal-lg modalDiv" >
      <div class="modal-content" id="structuralCharacterForm">
      </div>
   </div>
</div>
<div class="modal" id="floorValueModal" data-backdrop="static" style="z-index:9999999;">
   <div class="modal-dialog modal-xxll modalDiv" >
      <div class="modal-content" id="floorValueform">
      </div>
   </div>
</div>
<div class="modal" id="floorValueDepreciationModal" data-backdrop="static" style="z-index:9999999;">
   <div class="modal-dialog" >
      <div class="modal-content" id="floorValueDepreciationform">
      </div>
   </div>
</div>
<div class="modal" id="addlandappraisalmodal" data-backdrop="static" style="z-index:9999999;">
   <div class="modal-dialog modal-lg modalDiv" >
      <div class="modal-content" id="landappraisalform">
      </div>
   </div>
</div>
<script src="{{ asset('js/addPreviousOwnerBuilding.js') }}?rand={{rand(0,99)}}"></script>

<script src="{{ asset('js/ajax_rptBuilding.js') }}?rand={{ rand(000,999) }}"></script>
<script type="text/javascript">
   var effectiveYear = '{{ (isset($data->rp_app_effective_year))?$data->rp_app_effective_year:''}}';
   $('#rp_app_effective_year_pre').yearpicker({year:effectiveYear});
   setTimeout(function(){ 
      var id = "{{($data->rpo_code != '')?$data->rpo_code:''}}";
      if(id > 0){
      var text = "{{(isset($data->property_owner_details->full_name) && $data->property_owner_details != '')?$data->property_owner_details->full_name:'Select Property Owner'}}";
               $("#profile_id_pre").select3("trigger", "select", {
    data: { id: id ,text:text}
}); }
      var adminid = "{{($data->rp_administrator_code != '')?$data->rp_administrator_code:''}}";
      if(adminid > 0){
      var admintext = "{{(isset($data->property_admin_details->full_name) && $data->property_admin_details != '')?$data->property_admin_details->full_name:'Select Property Administrator'}}";
               $("#property_administrator_id_pre").select3("trigger", "select", {
    data: { id: adminid ,text:admintext}
}); }
      var taxdecid = "{{($data->rp_code_lref != '')?$data->rp_code_lref:''}}";
      if(taxdecid > 0){
         var admintext = "{{(isset($data->buildingReffernceLand->rp_tax_declaration_no) && $data->buildingReffernceLand != '')?$data->buildingReffernceLand->rp_tax_declaration_no:'Select Tax Declaration No.'}}";
               $("#rp_code_lref_pre").select3("trigger", "select", {
    data: { id: taxdecid ,text:admintext}
});
      }
      var permitid = "{{($data->permit_id != '')?$data->permit_id:''}}";
      if(permitid > 0){
         var permittext = "{{(isset($data->rp_bulding_permit_no) && $data->rp_bulding_permit_no != '')?$data->rp_bulding_permit_no:'Select Building Permit'}}";
               $("#permit_id_pre").select3("trigger", "select", {
    data: { id: permitid ,text:permittext}
});
      }
}, 500);
</script>