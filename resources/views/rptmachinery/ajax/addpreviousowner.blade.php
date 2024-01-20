{{Form::open(array('name'=>'forms','url'=>'rptmachinery/loadpreviousowner','method'=>'post','id'=>'propertyPreviousOwnerForm'))}}
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
   #addPreviousOwnerForMachineryModal .modal-xll {
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
   <div class="row" style="padding-top: 0px;">
      <div class="col-lg-8 col-md-8 col-sm-8">
         <div class="row" style="padding-top: 0px;">
      <div class="col-lg-5 col-md-5 col-sm-5">
         <div class="row" style="padding-top: 0px;">
            <div class="col-lg-4 col-md-4 col-sm-4">
               <div class="form-group">
                  {{Form::label('rvy_revision_year_id',__("Revision Year"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                  <div class="form-icon-user">
                     {{Form::select('rvy_revision_year_id',$arrRevisionYears,$data->rvy_revision_year_id,array('class'=>'form-control rvy_revision_year_id','id'=>'rvy_revision_year_id','disabled'=>(isset($data->id) && $data->id != '')?true:false))}}
                     @if(isset($data->id) && $data->id != '')
                     <input type="hidden" name="rvy_revision_year_id" value="{{ $data->rvy_revision_year_id }}">
                     @endif
                     <input type="hidden" name="rvy_revision_year" value="{{ isset($data->rvy_revision_year)?$data->rvy_revision_year:''}}">
                     <input type="hidden" name="rvy_revision_code" value="{{ $data->rvy_revision_code }}">
                  </div>
                  <span class="validate-err" id="err_rvy_revision_year_id"></span>
               </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
               <div class="form-group">
                  {{Form::label('brgy_code_id',__("Barangay"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                  <div class="form-icon-user">
                     {{Form::select('brgy_code_id',$arrBarangay,($data->brgy_code_id != '')?$data->brgy_code_id:session()->get('machineSelectedBrgy'),array('class'=>'form-control brgy_code_id','id'=>'brgy_code_id','disabled'=>true))}}
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
                     {{Form::text('rp_pin_suffix',$data->rp_pin_suffix,array('class'=>'form-control rp_pin_suffix','placeholder'=>'PIN Suffix','readonly' => true))}}
                  </div>
                  <span class="validate-err" id="err_rp_pin_suffix"></span>
               </div>
            </div>
         </div>
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
                  <div class="basicinfodiv" style="    margin-top: -41px;">
                     <div class="row" style="padding-top: 0px;">
                        <div class="col-md-12">
                           <div class="row" style="padding-top: 0px;">
                              <div class="col-lg-11 col-md-11 col-sm-11">
                                 <div class="form-group">
                                    {{Form::label('profile_id',__('Owner'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    {{ Form::select('profile_id',[],$data->rpo_code, array('class' => 'form-control  profile_id','id'=>'profile_id_pre','placeholder'=>'Select Name')) }}
                                 </div>
                                 <span class="validate-err" id="err_profile_id"></span>
                              </div>
                              <div class="col-lg-1 col-md-1 col-sm-1" style="margin-left: -9px;">
                                 <div class="" style="margin-top: 22px;">
                                    <!-- <a class="mx-3 btn btn-sm  align-items-center refeshbuttonselect2 ti-reload text-white" 
                                       name="stp_print"
                                       title="Refresh"></a> -->
                                       <a href="{{ url('/rptpropertyowner')}}" target="_blank" data-url="{{ url('/rptpropertyowner/store') }}" title="{{__('New Profile User')}}" class="btn btn-sm btn-primary ti-plus" style="margin-top: 8px;font size: 9px;">
                                       </a>
                                 </div>
                              </div>
                             <!--  <div class="col-lg-4 col-md-2 col-sm-2">
                                 <div class="form-group" style="margin-top: 25px;">
                                 <a href="#"  data-size="xl" data-url="{{ url('/profileuser/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('New Profile User')}}" class="btn btn-sm btn-primary" style="margin-top: 8px;">
                                       <i class="ti-plus"></i></a>
                                    <a href="#"  data-size="xl" data-url="{{ url('/rptpropertyowner/store') }}" data-bs-toggle="tooltip" title="{{__('New Profile User')}}" data-toggle="modal" href="#addPropertyOwnerModal" class="btn btn-sm btn-primary addNewPropertyOwner" style="margin-top: 8px;width:150px;">
                                    Change Owner</a>
                                 </div>
                              </div> -->
							  <!-- <div class="col-lg-3 col-md-3 col-sm-3" style="margin-left:-7px;">
                                 <div class="form-group" style="margin-top: 25px;">
                                    <a href="{{ url('/rptpropertyowner')}}" target="_blank" data-url="{{ url('/rptpropertyowner/store') }}" title="{{__('New Profile User')}}" class="btn btn-sm btn-primary " style="margin-top: 8px;font size: 9px;    width: 127px;">
                                    Change Owner</a>
                                 </div>
                              </div> -->
                           </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                           <div class="row" style="padding-top: 0px;">
                              <div class="col-lg-11 col-md-11 col-sm-11">
                                 <div class="form-group">
                                    {{Form::label('property_administrator_id',__('Administrator'),['class'=>'form-label'])}}
                                    {{ Form::select('property_administrator_id',[],$data->rp_administrator_code,array('class'=>'form-control property_administrator_id','id'=>'property_administrator_id_pre','placeholder'=>'Select Name')) }}
                                 </div>
                                 <span class="validate-err" id="err_rp_administrator_code"></span>
                              </div>
                              <div class="col-lg-1 col-md-4 col-sm-4" style="margin-left: -9px;">
                                 <div class="" style="margin-top: 20px;">
                                    <!-- <a class="mx-3 btn btn-sm  align-items-center refeshbuttonselect ti-reload text-white" 
                                       name="stp_print"
                                       title="Refresh"></a> -->
                                       <a href="{{ url('/rptpropertyowner')}}" target="_blank" data-url="{{ url('/rptpropertyowner/store') }}" title="{{__('New Profile User')}}" class="btn btn-sm btn-primary ti-plus" style="margin-top: 8px;font size: 9px;">
                                       </a>
                                 </div>
                              </div>
                              <!-- <div class="col-lg-4 col-md-4 col-sm-4"> -->
                                 <!--<div class="form-group" style="margin-top: 25px;">
                                    <a href="#"  data-size="xl" data-url="{{ url('/rptpropertyowner/store') }}"  data-bs-toggle="tooltip" title="{{__('New Profile User')}}" class="btn btn-sm btn-primary addNewPropertyOwner" style="margin-top: 8px;">
                                    Change Administrator</a>
                                 </div>-->
								         <!-- <div class="form-group" style="margin-top: 25px;">
                                    <a href="{{ url('/rptpropertyowner')}}" target="_blank" data-url="{{ url('/rptpropertyowner/store') }}" title="{{__('New Profile User')}}" class="btn btn-sm btn-primary " style="margin-top: 8px;font size: 9px;    width: 127px;">
                                    Change Administrator</a>
                                 </div> -->
                              <!-- </div> -->
                           </div>
                           <br />
                        </div>
                        
                        <div class="col-lg-12 col-md-12 col-sm-12">
                           <div class="row" style="padding-top: 0px;">
                              <div class="col-lg-8 col-md-8 col-sm-8">
                                 <div class="form-group">
                                    {{Form::label('rp_code_bref',__('Building TD. No'),['class'=>'form-label rp_code_bref'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                       {{Form::select('rp_code_bref',[],(isset($data->rp_code_bref))?$data->rp_code_bref:'',array('class'=>'form-control searchForLandOrBuildingpre rp_code_bref','id'=>'B_pre'))}}
                                       <input type="hidden" name="rp_code_bref" class="rp_code_bref" value="{{$data->rp_code_bref}}">
                                       <input type="hidden" name="rp_section_no_bref" class="rp_section_no_bref" value="{{$data->rp_section_no_bref}}">
                                       <input type="hidden" name="rp_pin_no_bref" class="rp_pin_no_bref" value="{{$data->rp_pin_no_bref}}">
                                       <input type="hidden" name="rp_pin_suffix_bref" class="rp_pin_suffix_bref" value="{{$data->rp_pin_suffix_bref}}">
                                    </div>
                                    <span class="validate-err" id="err_rp_code_bref"></span>
                                 </div>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-4"style="text-align: center;padding-top: 28px;">
                                 <div class="select-group">
                                    <div class="form-icon-user">
                                     {{ Form::checkbox('checkowner','1', ('')?true:false, array('id'=>'myCheckboxBuilding','class'=>'form-check-input myCheckboxBuilding','style'=>'margin-top:9px')) }} {{Form::label('',__('Taxpayer Reference'),['class'=>'form-label','style'=>'padding-top: 7px;'])}}
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                           <div class="row" style="padding-top: 0px;">
                              <div class="col-lg-8 col-md-8 col-sm-8">
                                 <div class="form-group">
                                       {{Form::label('rp_code_lref',__('Land TD. No'),['class'=>'form-label rp_code_lref'])}}<span class="text-danger">*</span>
                                       <div class="form-icon-user">
                                          {{Form::select('rp_code_lref',[],(isset($data->rp_code_lref))?$data->rp_code_lref:'',array('class'=>'form-control searchForLandOrBuildingpre rp_code_lref','id'=>'L_pre'))}}
                                          <input type="hidden" name="rp_code_lref" class="rp_code_lref" value="{{$data->rp_code_lref}}">
                                          <input type="hidden" name="rp_section_no_lref" class="rp_section_no_lref" value="{{$data->rp_section_no_lref}}">
                                          <input type="hidden" name="rp_pin_no_lref" class="rp_pin_no_lref" value="{{$data->rp_pin_no_lref}}">
                                          <input type="hidden" name="rp_pin_suffix_lref" class="rp_pin_suffix_lref" value="{{$data->rp_pin_suffix_lref}}">
                                       </div>
                                       <span class="validate-err" id="err_rp_code_lref"></span>
                                    </div>
                                 </div>
                                <div class="col-lg-4 col-md-4 col-sm-4"style="text-align: center;padding-top: 28px;">
                                 <div class="select-group">
                                    <div class="form-icon-user">
                                     {{ Form::checkbox('checkLand','1', ('')?true:false, array('id'=>'checkowner','class'=>'form-check-input myCheckboxLand','style'=>'margin-top:9px')) }} {{Form::label('',__('Taxpayer Reference'),['class'=>'form-label','style'=>'padding-top: 7px;'])}}
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                           <div class="form-group">
                              {{Form::label('loc_local_name',__('Location'),['class'=>'form-label location'])}}<span class="text-danger">*</span>
                              <div class="form-icon-user">
                                 {{Form::text('loc_local_name',(isset($data->loc_local_name))?$data->loc_local_name:'',array('class'=>'form-control loc_local_name','rows'=>1,'readonly' => true))}}
                              </div>
                              <span class="validate-err" id="err_loc_local_name"></span>
                           </div>
                        </div>
                     </div>
                     <br>
                     <!--   <div class="row" style="padding-top: 0px;">
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
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!--------------- Owners Information Start Here---------------->
       <div class="col-lg-7 col-md-7 col-sm-7" id="accordionFlushExample2">
         <div class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingone">
                  <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseoneOwner" aria-expanded="false" aria-controls="flush-headingtwo">
                     <h6 class="sub-title accordiantitle">{{__("")}}</h6>
                     </button>
               </h6>
               <div id="flush-headingone" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                  <div class="basicinfodiv" style="margin-top: -20px;">
                     <div class="row" style="padding-top: 0px;">
                      <div class="col-lg-12 col-md-12 col-sm-12">
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
                       <div class="row" style="padding-top: 0px;">
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
                        <br>
                        <div class="col-lg-12 col-md-12 col-sm-12" style="    margin-top: -20px;">
                           <div class="row" style="padding-top: 0px;">
                              <div class="col-lg-8 col-md-8 col-sm-8">
                                 <div class="form-group">
                                 {{Form::label('building_owner',__('Building Owner'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                 <div class="form-icon-user">
                                    {{Form::text('building_owner',$data->building_owner,array('class'=>'form-control building_owner','readonly'=>'readonly'))}}
                                 </div>
                                 <span class="validate-err" id="err_building_owner"></span>
                              </div>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-4">
                                 <div class="form-group">
                                    {{Form::label('bpin',__('PIN'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                    {{Form::text('bpin',($buildRef != null)?$buildRef->rp_pin_declaration_no:'',array('class'=>'form-control disabled-field bpin'))}}
                                   </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row" style="padding-top: 0px;">
                        <div class="col-lg-8 col-md-8 col-sm-8">
                           <div class="form-group">
                              {{Form::label('land_owner',__('Land Owner'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                              <div class="form-icon-user">
                                 {{Form::text('land_owner',$data->land_owner,array('class'=>'form-control land_owner rpo_code_lref','readonly'=>'readonly'))}}
                                 <input type="hidden" name="rpo_code_lref" class="rpo_code_lref" value="{{$data->rpo_code_lref}}">
                              </div>
                              <span class="validate-err" id="err_rpo_code_lref"></span>
                           </div>
                          </div>
                          <div class="col-lg-4 col-md-4 col-sm-4">
                           <div class="form-group">
                                    {{Form::label('lpin',__('PIN'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                    {{Form::text('lpin',($landRef != null)?$landRef->rp_pin_declaration_no:'',array('class'=>'form-control disabled-field bpin'))}}
                                   </div>
                                 </div>
                          </div>

                        </div>
                        <div class="row" style="padding-top: 0px;">
                         <div class="col-lg-12 col-md-12 col-sm-12">
                           <div class="form-group">
                              {{Form::label('rp_location_number_n_street',__('NO./Street'),['class'=>'form-label'])}}
                              <div class="form-icon-user">
                                 {{Form::text('rp_location_number_n_street',$data->rp_location_number_n_street,array('class'=>'form-control rp_location_number_n_street'))}}
                              </div>
                              <span class="validate-err" id="err_rp_location_number_n_street"></span>
                           </div>
                          </div>
                        </div>
                        <br>
                  </div>
               </div>
            </div>
         </div>
      </div>

   </div>
   <div class="row" >
      <!--------------- Taxable Items Start Here---------------->
      <!--  <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" style="padding-top: 10px;">
         <div  class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingfive">
               </h6>
               <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                  <div class="basicinfodiv"> -->
                    
                        <div class="col-xl-12" style="    padding: 2px;margin-top: -20px;">
                           <div class="card" style="padding: 0px;">
                              <div class="card-body table-border-style" style="    padding-bottom: 0px;">
                                    <div class="table-responsive" id="machineAppraisalDescription" style="border: 1px solid #3ec9d6;border-top: none;"></div>
                                 </div>
                              </div>
                          
                        </div>
                     
                     <!--------------- Land Apraisal Listing End Here------------------><br />
               <!--   </div>
               </div>
            </div>
         </div>
      </div> -->
      <!--------------- Taxable Items End Here------------------>
   </div>
   <div class="row" >
      <!--------------- Taxable Items Start Here---------------->
      <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" style="margin-top: -10px;">
         <div  class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingfive">
                  <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                     <h6 class="sub-title accordiantitle">{{__("Machine Appraisal")}}</h6>
                  </button>
               </h6>
               <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                  <div class="basicinfodiv">
                     
                     <div id="landAppraisalListing" style="margin-top: -36px;"></div>
                     <!--------------- Machine Apraisal Listing End Here------------------>
                     <br />
                     <div id="AssessmentSummarylisting">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!--------------- Taxable Items End Here------------------>
   </div>
   <div class="row" >
      <!--------------- Taxable Items Start Here---------------->
      <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" style="margin-top: -10px;">
         <div  class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingfive">
                  <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                     <h6 class="sub-title accordiantitle">{{__("Assessment Summary")}}</h6>
                  </button>
               </h6>
               <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                  <div class="basicinfodiv" style="margin-top: 0px;">
                     <div class="row" style="padding-top: 10px;">
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
                        <div class="basicinfodiv">
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
                              <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
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
                              <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 10px;">
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
            <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:0;">
               <div  class="accordion accordion-flush">
                  <div class="accordion-item">
                     <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseoneOwner" aria-expanded="false" aria-controls="flush-headingtwo">
                           <h6 class="sub-title accordiantitle">{{__("Approval")}}</h6>
                        </button>
                     </h6>
                     <div id="flush-collapseoneOwner" class="accordion-collapse collapse show" aria-labelledby="flush-headingone" data-bs-parent="#accordionFlushExampleOwner">
                        <div class="basicinfodiv">
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
<div class="modal" id="addmachineappraisalmodalForPreOwner" data-backdrop="static" style="z-index:9999999;">
   <div class="modal-dialog modal-lg modalDiv" >
      <div class="modal-content" id="machineappraisalformForPreOwner">
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
<!-- <div class="modal" id="addmachineappraisalmodalForPreOwner" data-backdrop="static" style="z-index:9999999 !important;">
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
<script src="{{ asset('js/addPreviousOwnerMachinery.js') }}?rand={{rand(0,99)}}"></script>
<script src="{{ asset('js/ajax_rptMachinery.js') }}?rand={{ rand(000,999) }}"></script>
<script type="text/javascript">
   var effectiveYear = '{{ (isset($data->rp_app_effective_year))?$data->rp_app_effective_year:''}}';
   $('#rp_app_effective_year_pre').yearpicker({year:effectiveYear});
   setTimeout(function(){ 
      var propId = $('#addPreviousOwnerForMachineryModal').find('#id').val();
      
      var id = "{{($data->rpo_code != '')?$data->rpo_code:''}}";
      var text = "{{(isset($data->property_owner_details->full_name) && $data->property_owner_details != '')?$data->property_owner_details->full_name:'Select Property Owner'}}";
               $('#addPreviousOwnerForMachineryModal').find("#profile_id_pre").select3("trigger", "select", {
    data: { id: id ,text:text}
});
      var adminid = "{{($data->rp_administrator_code != '')?$data->rp_administrator_code:''}}";
      var admintext = "{{(isset($data->property_admin_details->full_name) &&  $data->property_admin_details != '')?$data->property_admin_details->full_name:'Select Property Administrator'}}";
               $('#addPreviousOwnerForMachineryModal').find("#property_administrator_id_pre").select3("trigger", "select", {
    data: { id: adminid ,text:admintext}
});
               var landdecid = "{{($data->rp_code_lref != '')?$data->rp_code_lref:''}}";
      if(landdecid > 0){
         var landtext = "{{(isset($data->machineReffernceLand->rp_tax_declaration_no) && $data->machineReffernceLand != '')?$data->machineReffernceLand->rp_tax_declaration_no:'Select Land Tax Declaration No.'}}";
               $('#addPreviousOwnerForMachineryModal').find("#L_pre").select3("trigger", "select", {
    data: { id: landdecid ,text:landtext}
});
      }

      var builddecid = "{{($data->rp_code_bref != '')?$data->rp_code_bref:''}}";
      if(builddecid > 0){
         var buildtext = "{{(isset($data->machineReffernceBuild->rp_tax_declaration_no) && $data->machineReffernceBuild != '')?$data->machineReffernceBuild->rp_tax_declaration_no:'Select Building Tax Declaration No.'}}";
               $('#addPreviousOwnerForMachineryModal').find("#B_pre").select3("trigger", "select", {
    data: { id: builddecid ,text:buildtext}
});
      $('#addPreviousOwnerForMachineryModal').find('#B_pre').select3({
      templateSelection: function (data, container) {
      $(data.element).attr('data-custom-attribute', 'pre_o');
      return data.text;
      }
      });
      }
            
}, 500);
</script>