{{Form::open(array('name'=>'forms','url'=>'jobrequest/storebuildingpermit','method'=>'post','id'=>'storebuildingpermit'))}}
 {{ Form::hidden('jobrequest_id',(isset($JobRequest->id))?$JobRequest->id:NULL, array('id' => 'jobrequest_id')) }}
 {{ Form::hidden('application_id',(isset($appdata->id))?$appdata->id:NULL, array('id' => 'appdata')) }}
 {{ Form::hidden('assessedfeeid',(isset($EngAssessdata->id))?$EngAssessdata->id:NULL, array('id' => 'assessedfeeid')) }}
  {{ Form::hidden('session_id',(isset($sessionId))?$sessionId:'', array('id' => 'session_id')) }}
 {{ Form::hidden('p_code',(isset($pcode))?$pcode:'', array('id' => 'p_code')) }}
 {{ Form::hidden('ebost_id',(isset($appdata->ebost_id))?$appdata->ebost_id:'', array('id' => 'ebost_id')) }}
 <style type="text/css">
     .row{ padding: 0px 10px; }
     
 </style>
<div class="modal-header">
                    <h4 class="modal-title">Building Permit Application</h4>
                        <a class="close closeServiceModal" mid="" type="edit" data-dismiss="modal" aria-hidden="true" style="cursor:pointer;">X</a>
                    </div>
                    <div class="container"></div>
                    <div class="modal-body" style="overflow-y: scroll; height:800px;">
                      <div id="page1">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-headingfive">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                        <h6 class="sub-title accordiantitle">{{__("Application Information")}}</h6>
                                    </button>
                                </h6>
                                 <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_mun_no', __('Municipal'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ebpa_mun_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('ebpa_mun_no',$GetMuncipalities,$appdata->ebpa_mun_no, array('class' => 'form-control ebpa_mun_no disabled-field','id'=>'ebpa_mun_no','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_mun_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_application_no', __('Application No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ebpa_application_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_application_no',$appdata->ebpa_application_no, array('class' => 'form-control disabled-field ebpa_application_no','id'=>'ebpa_application_no','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_application_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_permit_no', __('Permit No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_permit_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_permit_no',$appdata->ebpa_permit_no, array('class' => 'form-control ebpa_permit_no disabled-field','id'=>'ebpa_permit_nob')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_permit_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('eba_id', __('Type of Application'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('eba_id') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('eba_id',$arrApptype,$appdata->eba_id, array('class' => 'form-control eba_id select3','id'=>'eba_id')) }}
                                            </div>
                                            <span class="validate-err" id="err_eba_id"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_application_date', __('Date of Application'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_application_date') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::date('ebpa_application_date',$appdata->ebpa_application_date, array('class' => 'form-control ebpa_application_date','id'=>'ebpa_application_date')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_application_date"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_issued_date', __('Date Issued'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_issued_date') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::date('ebpa_issued_date',$appdata->ebpa_issued_date, array('class' => 'form-control ebpa_issued_date','id'=>'ebpa_issued_date')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_issued_date"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-headingfive">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                        <h6 class="sub-title accordiantitle">{{__("Owner Information")}}</h6>
                                    </button>
                                </h6>
                                 <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_last_name', __('Last Name'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ownerlastname') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_last_name',$appdata->ebpa_owner_last_name, array('class' => 'form-control ebpa_owner_last_name','id'=>'ebpa_owner_last_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_last_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_first_name', __('First Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ownerfirstname') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_first_name',$appdata->ebpa_owner_first_name, array('class' => 'form-control ebpa_owner_first_name','id'=>'ebpa_owner_first_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_first_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_mid_name', __('Middle Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ebpa_owner_mid_name') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_mid_name',$appdata->ebpa_owner_mid_name, array('class' => 'form-control ebpa_owner_mid_name','id'=>'ebpa_owner_mid_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_mid_name"></span>
                                        </div>
                                    </div>
                                      <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_suffix_name', __('Suffix'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('suffix') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_suffix_name',$appdata->ebpa_owner_suffix_name, array('class' => 'form-control suffix','id'=>'suffix')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_suffix_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_tax_acct_no', __('Tax Acct. No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_tax_acct_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_tax_acct_no',$appdata->ebpa_tax_acct_no, array('class' => 'form-control ebpa_tax_acct_no','id'=>'ebpa_tax_acct_no')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_tax_acct_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_form_of_own', __('Form Of Ownership'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_form_of_own') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_form_of_own',$appdata->ebpa_form_of_own, array('class' => 'form-control ebpa_form_of_own','id'=>'ebpa_form_of_own')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_form_of_own"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_economic_act', __('Main Economic Activity/Kind Business'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ebpa_economic_act') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_economic_act',$appdata->ebpa_economic_act, array('class' => 'form-control ebpa_economic_act','id'=>'ebpa_economic_act')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_economic_act"></span>
                                        </div>
                                    </div>
                                      <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_address_house_lot_no', __('House Lot No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_address_house_lot_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_address_house_lot_no',$appdata->ebpa_address_house_lot_no, array('class' => 'form-control disabled-field ebpa_address_house_lot_no','id'=>'ebpa_address_house_lot_no')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_address_house_lot_no"></span>
                                        </div>
                                    </div>
                                      <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('', __('Street Name'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_address_street_name') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_address_street_name',$appdata->ebpa_address_street_name, array('class' => 'form-control disabled-field ebpa_address_street_name','id'=>'ebpa_address_street_name')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_address_street_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_address_subdivision', __('Subdivision'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_address_subdivision') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_address_subdivision',$appdata->ebpa_address_subdivision, array('class' => 'form-control disabled-field ebpa_address_subdivision','id'=>'ebpa_address_subdivision')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_address_subdivision"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-8">
                                        <div class="form-group">
                                            {{ Form::label('brgy_code', __('Barangay, Municipality, Province, Region'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('brgy_code') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('appbrgy_code',$appdata->brgy_code, array('class' => 'form-control disabled-field brgy_code','id'=>'appbrgy_code')) }}
                                            </div>
                                            <span class="validate-err" id="err_appbrgy_code"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_location', __('Location of Construction'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ebpa_location') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_location',$appdata->ebpa_location, array('class' => 'form-control ebpa_location disabled-field','id'=>'ebpa_location','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_location"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-headingfive">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                        <h6 class="sub-title accordiantitle">{{__("Scope of Work")}}</h6>
                                    </button>
                                </h6>
                                 <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebs_id', __('Scope of Work'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ebs_id') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('ebs_id',$arrbuildingScope,$appdata->ebs_id, array('class' => 'form-control','id'=>'ebs_id')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebs_id"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_scope_remarks', __('Other Remarks'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_scope_remarks') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_scope_remarks',$appdata->ebpa_scope_remarks, array('class' => 'form-control ','id'=>'ebpa_scope_remarks')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_scope_remarks"></span>
                                        </div>
                                    </div>
                                    <!--  <div class="col-md-2">
                                        
                                    </div> -->
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('no_of_units', __('Number of Units'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('no_of_units') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('no_of_units',$appdata->no_of_units, array('class' => 'form-control ','id'=>'no_of_units')) }}
                                            </div>
                                            <span class="validate-err" id="err_no_of_units"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-headingfive">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                        <h6 class="sub-title accordiantitle">{{__("Use / Type of Occupancy")}}</h6>
                                    </button>
                                </h6>
                                 <div class="row" style="padding-top: 5px;">
                                       @foreach($arrTypeofOccupancy as  $key => $val)
                                        <div class="col-md-4">
                                               <div class="form-check form-check-inline form-group col-md-12">
                                                {{ Form::radio('ebot_id', $key,($appdata->ebot_id == $key)?true:false, array('id'=>'ebot_id'.$key,'class'=>'form-check-input occupancyclass')) }}
                                                {{ Form::label('ebot_id'.$key, __($val),['class'=>'form-label']) }}
                                            </div>
                                        </div> 
                                        <div class="col-md-8">
                                         <span class="suboccupancytype" id="subtypeoccu{{$key}}"></span>
                                       </div>
                                      @endforeach
                                  </div>
                                  <div class="row">
                                       <div class="col-md-8">
                                                <div class="form-group">
                                                {{ Form::label('ebpa_occ_other_remarks', __('Other Remark'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebpa_occ_other_remarks') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebpa_occ_other_remarks',$appdata->ebpa_occ_other_remarks, array('class' => 'form-control disabled-field','id'=>'ebpa_occ_other_remarks')) }}
                                                </div>
                                                <span class="validate-err" id="err_ebpa_occ_other_remarks"></span>
                                            </div>
                                        </div>    
                                      <span class="validate-err" id="err_ebot_id"></span>
                                </div>
                            </div>
                        </div>
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-headingfive">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                        <h6 class="sub-title accordiantitle">{{__("Building Official")}}</h6>
                                    </button>
                                </h6>
                                 <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" id="bldgoffdiv">
                                            {{ Form::label('ebpa_bldg_official_name', __('Building Official Full Name'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ebpa_bldg_official_name') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('ebpa_bldg_official_name',$buildofficial,$appdata->ebpa_bldg_official_name, array('class' => 'form-control','id'=>'ebpa_bldg_official_id','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_bldg_official_name"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        <div class="modal-footer"  style="padding-bottom: 100px;">
                        <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                            <a href="#" data-dismiss="modal" class="btn closeServiceModal" mid=""  type="edit">Close</a>
                            <a  class="btn btn-primary nextpageModal" id="nextpage">Next</a> 
                            <!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
                        </div>
                       </div> 
                    <div id="page2" style="display:none;">
                        <div class="row">
                        <div class="col-sm-4">
                               <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                            <h6 class="sub-title accordiantitle">{{__("Total Estimate Cost")}}</h6>
                                        </button>
                                    </h6>
                                     <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_bldg_est_cost', __('Building'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_bldg_est_cost') }}</span>
                                                <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('ebfd_bldg_est_cost',$engfeesdata->ebfd_bldg_est_cost, array('class' => 'form-control numeric-double','id'=>'ebfd_bldg_est_cost')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_ebfd_bldg_est_cost"></span>
                                              </div>
                                           </div>
                                       </div>
                                         <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_elec_est_cost', __('Electrical'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_elec_est_cost') }}</span>
                                                <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('ebfd_elec_est_cost',$engfeesdata->ebfd_elec_est_cost, array('class' => 'form-control numeric-double','id'=>'ebfd_elec_est_cost')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_ebfd_elec_est_cost"></span>
                                            </div>
                                        </div>
                                     </div>
                                   </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_plum_est_cost', __('Plumbing'),['class'=>'form-label bold'])}}
                                                <span class="validate-err">{{ $errors->first('ebfd_plum_est_cost') }}</span>
                                                <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('ebfd_plum_est_cost',$engfeesdata->ebfd_plum_est_cost, array('class' => 'form-control numeric-double','id'=>'ebfd_plum_est_cost')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_ebfd_plum_est_cost"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_mech_est_cost', __('Mechanical'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_mech_est_cost') }}</span>
                                                <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('ebfd_mech_est_cost',$engfeesdata->ebfd_mech_est_cost, array('class' => 'form-control numeric-double','id'=>'ebfd_mech_est_cost')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_ebfd_mech_est_cost"></span>
                                            </div>
                                        </div>
                                     </div>
                                   </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_other_est_cost', __('Other'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_other_est_cost') }}</span>
                                                <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('ebfd_other_est_cost',$engfeesdata->ebfd_other_est_cost, array('class' => 'form-control numeric-double','id'=>'ebfd_other_est_cost')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_ebfd_other_est_cost"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_total_est_cost', __('Total Cost'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_total_est_cost') }}</span>
                                                <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('ebfd_total_est_cost',$engfeesdata->ebfd_total_est_cost, array('class' => 'form-control numeric-double','id'=>'ebfd_total_est_cost','readonly')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_ebfd_total_est_cost"></span>
                                            </div>
                                        </div>
                                     </div>
                                   </div>
                            </div>
                         </div>
                         </div>
                         <div class="col-sm-3">
                               <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                            <h6 class="sub-title accordiantitle">{{__("Cost of Equipment Installed")}}</h6>
                                        </button>
                                    </h6>
                                     <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_equip_cost_1', __('Cost 1'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_equip_cost_1') }}</span>
                                                <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('ebfd_equip_cost_1',$engfeesdata->ebfd_equip_cost_1, array('class' => 'form-control numeric-double','id'=>'ebfd_equip_cost_1')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_ebfd_equip_cost_1"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                         <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_equip_cost_2', __('Cost 2'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_equip_cost_2') }}</span>
                                                <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('ebfd_equip_cost_2',$engfeesdata->ebfd_equip_cost_2, array('class' => 'form-control numeric-double','id'=>'ebfd_equip_cost_2')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_ebfd_equip_cost_2"></span>
                                             </div>
                                        </div>
                                     </div>
                                   </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_equip_cost_3', __('Cost 3'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_equip_cost_3') }}</span>
                                                <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('ebfd_equip_cost_3',$engfeesdata->ebfd_equip_cost_3, array('class' => 'form-control numeric-double','id'=>'ebfd_equip_cost_3')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_ebfd_equip_cost_3"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                            </div>
                            </div>
                          </div>
                          <div class="col-sm-5">
                               <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                            <h6 class="sub-title accordiantitle">{{__("Details")}}</h6>
                                        </button>
                                    </h6>
                                     <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_no_of_storey', __('Number of Storeys'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('ebfd_no_of_storey') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebfd_no_of_storey',$engfeesdata->ebfd_no_of_storey, array('class' => 'form-control numeric-only','id'=>'ebfd_no_of_storey','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_ebfd_no_of_storey"></span>
                                              </div>
                                        </div>
                                          <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_construction_date', __('Proposal date of Construction'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('ebfd_construction_date') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('ebfd_construction_date',$engfeesdata->ebfd_construction_date, array('class' => 'form-control','id'=>'ebfd_construction_date','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_ebfd_construction_date"></span>
                                              </div>
                                        </div>
                                    </div>
                                     <div class="row">
                                         <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_floor_area', __('Total Floor Area'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('ebfd_floor_area') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebfd_floor_area',$engfeesdata->ebfd_floor_area, array('class' => 'form-control numeric-double','id'=>'ebfd_floor_area','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_ebfd_floor_area"></span>
                                            </div>
                                           </div>
                                           <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_completion_date', __('Expected date of Completion'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('ebfd_completion_date') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('ebfd_completion_date',$engfeesdata->ebfd_completion_date, array('class' => 'form-control','id'=>'ebfd_completion_date','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_ebfd_completion_date"></span>
                                              </div>
                                           </div>
                                    </div>
                                     <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_mats_const', __('Material of Construction'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('ebfd_mats_const') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebfd_mats_const',$engfeesdata->ebfd_mats_const, array('class' => 'form-control','id'=>'ebfd_mats_const','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_ebfd_mats_const"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                          </div>
                         </div>
                        <div class="col-sm-12">
                               <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                            <table style="width: 100%;"><tr><td>
                                                <h6 class="sub-title accordiantitle">{{__("Assessed Fees")}}</h6> 
                                            </td>
                                                <td style="text-align: end;">
                                                    <span class="btn_buildingrevision btn btn-primary" id="btn_buildingrevision" style="color:white;background: none;    border: none;"><i class="ti-plus"></i></span>
                                                </td></tr></table>
                                           
                                             
                                        </button>
                                    </h6>
                                     <div class="row" style="background: #20b7cc;margin: 7px 1px;color: #fff;">
                                        <div class="col-md-2" style="padding-top: 12px;color:#fff;">
                                            <div class="form-group">
                                                {{ Form::label('description', __('DESCRIPTION'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                       <div class="col-md-2" style="padding-top: 12px;color:#fff;">
                                            <div class="form-group">
                                                {{ Form::label('amounr_due', __('AMOUNT DUE'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                       <div class="col-md-4" style="padding-top: 12px;color:#fff;">
                                            <div class="form-group">
                                                {{ Form::label('assesssedby', __('ASSESSED BY'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                        <div class="col-md-2" style="padding-top: 12px;color:#fff;padding-left: 20px;">
                                            <div class="form-group">
                                                {{ Form::label('ornumber', __('O.R. NUMBER'),['class'=>'form-label bold']) }}
                                        </div>
                                       </div>
                                         <div class="col-md-2" style="padding-top: 12px;color:#fff;padding-left: 20px;">
                                            <div class="form-group">
                                                {{ Form::label('datepaid', __('DATE PAID'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                   </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                 {{ Form::label('landuse', __('Land Use / Zoning'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                               <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('ebaf_zoning_amount',$EngAssessdata->ebaf_zoning_amount, array('class' => 'form-control numeric-double','id'=>'ebaf_zoning_amount')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_ebaf_zoning_amount"></span>
                                             </div>
                                           </div>
                                       </div>
                                       <div class="col-md-4">
                                            <div class="form-group" id="ebaf_zoning_assessed_byparrent">
                                                 <div class="form-icon-user">
                                                    {{ Form::select('ebaf_zoning_assessed_by',$hremployees,$EngAssessdata->ebaf_zoning_assessed_by, array('class' => 'form-control select3','id'=>'ebaf_zoning_assessed_by')) }}
                                                    <span class="validate-err" id="err_ebaf_zoning_assessed_by"></span>
                                                </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebaf_zoning_or_no',$EngAssessdata->ebaf_zoning_or_no, array('class' => 'form-control numeric-only','id'=>'ebaf_zoning_or_no')) }}
                                                    <span class="validate-err" id="err_ebaf_zoning_or_no"></span>
                                                </div>
                                           </div>
                                       </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::date('ebaf_zoning_date_paid',$EngAssessdata->ebaf_zoning_date_paid, array('class' => 'form-control ','id'=>'ebaf_zoning_date_paid')) }}
                                                    <span class="validate-err" id="err_ebaf_zoning_date_paid"></span>
                                                </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                 {{ Form::label('landuse', __('Line and Grade'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                               <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('ebaf_linegrade_amount',$EngAssessdata->ebaf_linegrade_amount, array('class' => 'form-control numeric-double','id'=>'ebaf_linegrade_amount')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_ebaf_linegrade_amount"></span>
                                             </div>
                                           </div>
                                       </div>
                                       <div class="col-md-4">
                                            <div class="form-group">
                                                 <div class="form-icon-user" id="ebaf_linegrade_assessed_byparrent">
                                                    {{ Form::select('ebaf_linegrade_assessed_by',$hremployees,$EngAssessdata->ebaf_linegrade_assessed_by, array('class' => 'form-control ','id'=>'ebaf_linegrade_assessed_by')) }}
                                                    <span class="validate-err" id="err_ebaf_linegrade_assessed_by"></span>
                                                </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebaf_linegrade_or_no',$EngAssessdata->ebaf_linegrade_or_no, array('class' => 'form-control numeric-only','id'=>'ebaf_linegrade_or_no')) }}
                                                    <span class="validate-err" id="err_ebaf_linegrade_or_no"></span>
                                                </div>
                                           </div>
                                       </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::date('ebaf_linegrade_date_paid',$EngAssessdata->ebaf_linegrade_date_paid, array('class' => 'form-control ','id'=>'ebaf_linegrade_date_paid')) }}
                                                    <span class="validate-err" id="err_ebaf_linegrade_date_paid"></span>
                                                </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                 {{ Form::label('landuse', __('Building'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                               <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('ebaf_bldg_amount',$EngAssessdata->ebaf_bldg_amount, array('class' => 'form-control numeric-double','id'=>'ebaf_bldg_amount')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_ebaf_bldg_amount"></span>
                                             </div>
                                           </div>
                                       </div>
                                       <div class="col-md-4">
                                            <div class="form-group" id="ebaf_bldg_assessed_byparrent">
                                                 <div class="form-icon-user">
                                                    {{ Form::select('ebaf_bldg_assessed_by',$hremployees,$EngAssessdata->ebaf_bldg_assessed_by, array('class' => 'form-control ','id'=>'ebaf_bldg_assessed_by')) }}
                                                    <span class="validate-err" id="err_ebaf_bldg_assessed_by"></span>
                                                </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebaf_bldg_or_no',$EngAssessdata->ebaf_bldg_or_no, array('class' => 'form-control numeric-only','id'=>'ebaf_bldg_or_no')) }}
                                                    <span class="validate-err" id="err_ebaf_bldg_or_no"></span>
                                                </div>
                                           </div>
                                       </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::date('ebaf_bldg_date_paid',$EngAssessdata->ebaf_bldg_date_paid, array('class' => 'form-control ','id'=>'ebaf_bldg_date_paid')) }}
                                                    <span class="validate-err" id="err_ebaf_bldg_date_paid"></span>
                                                </div>
                                           </div>
                                       </div>
                                   </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                 {{ Form::label('landuse', __('Plumbing'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                               <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('ebaf_plum_amount',$EngAssessdata->ebaf_plum_amount, array('class' => 'form-control numeric-double','id'=>'ebaf_plum_amount')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_ebaf_plum_amount"></span>
                                             </div>
                                           </div>
                                       </div>
                                       <div class="col-md-4">
                                            <div class="form-group" id="ebaf_plum_assessed_byparrent">
                                                 <div class="form-icon-user">
                                                    {{ Form::select('ebaf_plum_assessed_by',$hremployees,$EngAssessdata->ebaf_plum_assessed_by, array('class' => 'form-control ','id'=>'ebaf_plum_assessed_by')) }}
                                                    <span class="validate-err" id="err_ebaf_plum_assessed_by"></span>
                                                </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebaf_plum_or_no',$EngAssessdata->ebaf_plum_or_no, array('class' => 'form-control numeric-only','id'=>'ebaf_plum_or_no')) }}
                                                    <span class="validate-err" id="err_ebaf_plum_or_no"></span>
                                                </div>
                                           </div>
                                       </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::date('ebaf_plum_date_paid',$EngAssessdata->ebaf_plum_date_paid, array('class' => 'form-control ','id'=>'ebaf_plum_date_paid')) }}
                                                    <span class="validate-err" id="err_ebaf_plum_date_paid"></span>
                                                </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                 {{ Form::label('electrical', __('Electrical'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                               <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('ebaf_elec_amount',$EngAssessdata->ebaf_elec_amount, array('class' => 'form-control numeric-double','id'=>'ebaf_elec_amount')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_ebaf_elec_amount"></span>
                                             </div>
                                           </div>
                                       </div>
                                       <div class="col-md-4">
                                            <div class="form-group">
                                                 <div class="form-icon-user" id="ebaf_elec_assessed_byparrent">
                                                    {{ Form::select('ebaf_elec_assessed_by',$hremployees,$EngAssessdata->ebaf_elec_assessed_by, array('class' => 'form-control ','id'=>'ebaf_elec_assessed_by')) }}
                                                    <span class="validate-err" id="err_ebaf_elec_assessed_by"></span>
                                                </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebaf_elec_or_no',$EngAssessdata->ebaf_elec_or_no, array('class' => 'form-control numeric-only','id'=>'ebaf_elec_or_no')) }}
                                                    <span class="validate-err" id="err_ebaf_elec_or_no"></span>
                                                </div>
                                           </div>
                                       </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::date('ebaf_elec_date_paid',$EngAssessdata->ebaf_elec_date_paid, array('class' => 'form-control ','id'=>'ebaf_elec_date_paid')) }}
                                                    <span class="validate-err" id="err_ebaf_elec_date_paid"></span>
                                                </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                 {{ Form::label('mechanical', __('Mechanical'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                               <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('ebaf_mech_amount',$EngAssessdata->ebaf_mech_amount, array('class' => 'form-control numeric-double','id'=>'ebaf_mech_amount')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_ebaf_mech_amount"></span>
                                             </div>
                                           </div>
                                       </div>
                                       <div class="col-md-4">
                                            <div class="form-group" id="ebaf_mech_assessed_byparrent">
                                                 <div class="form-icon-user">
                                                    {{ Form::select('ebaf_mech_assessed_by',$hremployees,$EngAssessdata->ebaf_mech_assessed_by, array('class' => 'form-control ','id'=>'ebaf_mech_assessed_by')) }}
                                                    <span class="validate-err" id="err_ebaf_mech_assessed_by"></span>
                                                </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebaf_mech_or_no',$EngAssessdata->ebaf_mech_or_no, array('class' => 'form-control numeric-only','id'=>'ebaf_mech_or_no')) }}
                                                    <span class="validate-err" id="err_ebaf_mech_or_no"></span>
                                                </div>
                                           </div>
                                       </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::date('ebaf_mech_date_paid',$EngAssessdata->ebaf_mech_date_paid, array('class' => 'form-control ','id'=>'ebaf_mech_date_paid')) }}
                                                    <span class="validate-err" id="err_ebaf_mech_date_paid"></span>
                                                </div>
                                           </div>
                                       </div>
                                   </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                 {{ Form::label('others', __('Others'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                               <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('ebaf_others_amount',$EngAssessdata->ebaf_others_amount, array('class' => 'form-control numeric-double','id'=>'ebaf_others_amount')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_ebaf_others_amount"></span>
                                             </div>
                                           </div>
                                       </div>
                                       <div class="col-md-4">
                                            <div class="form-group" id="ebaf_others_assessed_byparrent">
                                                 <div class="form-icon-user">
                                                    {{ Form::select('ebaf_others_assessed_by',$hremployees,$EngAssessdata->ebaf_others_assessed_by, array('class' => 'form-control ','id'=>'ebaf_others_assessed_by')) }}
                                                    <span class="validate-err" id="err_ebaf_others_assessed_by"></span>
                                                </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebaf_others_or_no',$EngAssessdata->ebaf_others_or_no, array('class' => 'form-control numeric-only','id'=>'ebaf_others_or_no')) }}
                                                    <span class="validate-err" id="err_ebaf_others_or_no"></span>
                                                </div>
                                           </div>
                                       </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::date('ebaf_others_date_paid',$EngAssessdata->ebaf_others_date_paid, array('class' => 'form-control ','id'=>'ebaf_others_date_paid')) }}
                                                    <span class="validate-err" id="err_ebaf_others_date_paid"></span>
                                                </div>
                                           </div>
                                       </div>
                                   </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                 {{ Form::label('total', __('Total'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                               <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('ebaf_total_amount',$EngAssessdata->ebaf_total_amount, array('class' => 'form-control numeric-double','id'=>'ebaf_total_amount','readonly')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_ebaf_total_amount"></span>
                                             </div>
                                           </div>
                                       </div>
                                       <div class="col-md-4">
                                            <div class="form-group" id="ebaf_total_assessed_byparrent">
                                                 <div class="form-icon-user">
                                                    {{ Form::select('ebaf_total_assessed_by',$hremployees,$EngAssessdata->ebaf_total_assessed_by, array('class' => 'form-control','id'=>'ebaf_total_assessed_by')) }}
                                                    <span class="validate-err" id="err_ebaf_total_assessed_by"></span>
                                                </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebaf_total_or_no',$EngAssessdata->ebaf_total_or_no, array('class' => 'form-control numeric-only','id'=>'ebaf_total_or_no')) }}
                                                    <span class="validate-err" id="err_ebaf_total_or_no"></span>
                                                </div>
                                           </div>
                                       </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::date('ebaf_total_date_paid',$EngAssessdata->ebaf_total_date_paid, array('class' => 'form-control ','id'=>'ebaf_total_date_paid')) }}
                                                    <span class="validate-err" id="err_ebaf_total_date_paid"></span>
                                                </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                            </div>
                         </div>
                        <div class="col-sm-12">
                               <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive" style="padding-right: 0px;">
                                            <!-- <h6 class="sub-title accordiantitle">{{__("Architecture / Civil Engineer Signed and Sealed Plans & Specifications")}}</h6> -->
                                            <div class="row" style="width: 100%;padding-right: 0px;padding-left: 0px;">
                                        <div class="col-md-8">
                                            <h6 class="sub-title accordiantitle " style="padding-top: 12px;">{{__("Architecture / Civil Engineer Signed and Sealed Plans & Specifications")}}</h6>
                                        </div>
                                        <div class="col-md-4" >
                                            <a href="{{ url('/eng/consultantexternal') }}" target="_blank">
                                             <span class="btn_electricalrevision btn btn-primary hide" id="btn_electricalrevision2" style="color:white;float: right;padding: 5px;"><i class="ti-plus"></i></span>
                                         </a>
                                         </div>
                                        </div>
                                        </button>
                                    </h6>
                                     <div class="row">
                                        <div class="col-md-2" id="ebfd_sign_category_group">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_sign_category', __('Consultant Category'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_category') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('ebfd_sign_category',array(''=>'Please Select','1'=>'Employee','2'=>'External Consultant'),$engfeesdata->ebfd_sign_category, array('class' => 'form-control numeric-only','id'=>'ebfd_sign_category')) }}
                                                <span class="validate-err" id="err_ebfd_sign_category"></span>
                                              </div>
                                           </div>
                                       </div>
                                       
                                        <div class="col-md-3">
                                            <div class="form-group" id="BuildingPermit">
                                                {{ Form::label('ebfd_sign_consultant_id', __('Full Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('ebfd_sign_consultant_id',$signdropdown,$engfeesdata->ebfd_sign_consultant_id, array('class' => 'form-control','id'=>'ebfd_sign_consultant_id')) }}
                                                    
                                                <span class="validate-err" id="err_ebfd_sign_consultant_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_sign_prc_reg_no', __('PRC Reg. No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_prc_reg_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebfd_sign_prc_reg_no',$engfeesdata->ebfd_sign_prc_reg_no, array('class' => 'form-control numeric-only','id'=>'signprcregno')) }}
                                                <span class="validate-err" id="err_ebfd_sign_prc_reg_no"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_sign_address_house_lot_no', __('Address'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_address_house_lot_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebfd_sign_address_house_lot_no',$engfeesdata->ebfd_sign_address_house_lot_no, array('class' => 'form-control','id'=>'signaddress')) }}
                                                <span class="validate-err" id="err_ebfd_sign_address_house_lot_no"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_sign_ptr_no', __('PTR No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebfd_sign_ptr_no',$engfeesdata->ebfd_sign_ptr_no, array('class' => 'form-control numeric-only','id'=>'signebfd_sign_ptr_no')) }}
                                                <span class="validate-err" id="err_ebfd_sign_ptr_no"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                            </div>
                         </div>
                           <div class="col-sm-12">
                               <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive" style="padding-right: 0px;">
                                            <div class="row" style="width: 100%;padding-right: 0px;padding-left: 0px;">
                                        <div class="col-md-8">
                                            <h6 class="sub-title accordiantitle " style="padding-top: 12px;">{{__("Architecture / Civil Engineer Incharge of Construction")}}</h6>
                                        </div>
                                        <div class="col-md-4" >
                                            <a href="{{ url('/eng/consultantexternal') }}" target="_blank">
                                             <span class="btn_electricalrevision btn btn-primary hide" id="btn_electricalrevision" style="color:white;float: right;padding: 5px;"><i class="ti-plus"></i></span></a>
                                         </div>
                                        </div>
                                        </button>
                                    </h6>
                                     <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_incharge_category', __('Consultant Category'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_incharge_category') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('ebfd_incharge_category',array(''=>'Please Select','1'=>'Employee','2'=>'External Consultant'),$engfeesdata->ebfd_incharge_category, array('class' => 'form-control numeric-only','id'=>'ebfd_incharge_category')) }}
                                                <span class="validate-err" id="err_ebfd_incharge_category"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-3">
                                            <div class="form-group" id="BuildingPermit">
                                                {{ Form::label('ebfd_incharge_consultant_id', __('Full Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_incharge_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('ebfd_incharge_consultant_id',$inchargedropdown,$engfeesdata->ebfd_incharge_consultant_id, array('class' => 'form-control','id'=>'ebfd_incharge_consultant_id')) }}
                                                <span class="validate-err" id="err_ebfd_incharge_consultant_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_incharge_prc_reg_no', __('PRC Reg. No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_incharge_prc_reg_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebfd_incharge_prc_reg_no',$engfeesdata->ebfd_incharge_prc_reg_no, array('class' => 'form-control numeric-only','id'=>'inchargeprcregno')) }}
                                                <span class="validate-err" id="err_ebfd_incharge_prc_reg_no"></span>
                                              </div>
                                           </div>
                                       </div>
                                      
                                       <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('inchargeaddress', __('Address'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('inchargeaddress') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebfd_incharge_address_house_lot_no',$engfeesdata->ebfd_incharge_address_house_lot_no, array('class' => 'form-control','id'=>'inchargenaddress')) }}
                                                <span class="validate-err" id="err_ebfd_incharge_address_house_lot_no"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_incharge_ptr_no', __('PTR No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_incharge_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebfd_incharge_ptr_no',$engfeesdata->ebfd_incharge_ptr_no, array('class' => 'form-control numeric-only','id'=>'inchargeebfd_sign_ptr_no')) }}
                                                <span class="validate-err" id="err_ebfd_incharge_ptr_no"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_incharge_ptr_date_issued', __('Date Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_incharge_ptr_date_issued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('ebfd_incharge_ptr_date_issued',$engfeesdata->ebfd_incharge_ptr_date_issued, array('class' => 'form-control','id'=>'inchargedateissued')) }}
                                                <span class="validate-err" id="err_ebfd_incharge_ptr_date_issued"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-5">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_incharge_ptr_place_issued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_incharge_ptr_place_issued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebfd_incharge_ptr_place_issued',$engfeesdata->ebfd_incharge_ptr_place_issued, array('class' => 'form-control','id'=>'inchargeplaceissued')) }}
                                                <span class="validate-err" id="err_ebfd_incharge_ptr_place_issued"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-5">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_incharge_tan', __('TIN'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_incharge_tan') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebfd_incharge_tan',$engfeesdata->ebfd_incharge_tan, array('class' => 'form-control numeric-only','id'=>'inchargetin')) }}
                                                <span class="validate-err" id="err_ebfd_incharge_tan"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                            </div>
                            </div>
                         </div>
                          <div class="col-sm-12">
                               <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                            <h6 class="sub-title accordiantitle">{{__("Applicant")}}</h6>
                                        </button>
                                    </h6>
                                     <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group" id="applicantparent">
                                                {{ Form::label('ebfd_applicant_consultant_id', __('Full Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_applicant_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('ebfd_applicant_consultant_id',$arrlotOwner,$engfeesdata->ebfd_applicant_consultant_id, array('class' => 'form-control ','id'=>'ebfd_applicant_consultant_id')) }}
                                                <span class="validate-err" id="err_ebfd_applicant_consultant_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_applicant_comtaxcert', __('Community Tax Certificate'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_applicant_comtaxcert') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebfd_applicant_comtaxcert',$engfeesdata->ebfd_applicant_comtaxcert, array('class' => 'form-control','id'=>'applicant_comtaxcert')) }}
                                                <span class="validate-err" id="err_ebfd_applicant_comtaxcert"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_applicant_date_issued', __('Date Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_applicant_date_issued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('ebfd_applicant_date_issued',$engfeesdata->ebfd_applicant_date_issued, array('class' => 'form-control','id'=>'applicant_date_issued')) }}
                                                <span class="validate-err" id="err_ebfd_applicant_date_issued"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_applicant_place_issued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_applicant_place_issued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebfd_applicant_place_issued',$engfeesdata->ebfd_applicant_place_issued, array('class' => 'form-control','id'=>'applicant_place_issued')) }}
                                                <span class="validate-err" id="err_ebfd_applicant_place_issued"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                            </div>
                            </div>
                         </div>
                         <div class="col-sm-12">
                               <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                            <h6 class="sub-title accordiantitle">{{__("Lot Owner Consent")}}</h6>
                                        </button>
                                    </h6>
                                     <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group" id="lotownerparent">
                                                {{ Form::label('ebfd_consent_id', __('Lot Owner Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_consent_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('ebfd_consent_id',$arrlotOwner,$engfeesdata->ebfd_consent_id, array('class' => 'form-control select3','id'=>'ebfd_consent_id')) }}
                                                <span class="validate-err" id="err_ebfd_consent_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('ebpa_address_house_lotno', __('Address'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebpa_address_house_lotno') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebpa_address_house_lotno',$engfeesdata->ebpa_address_house_lotno, array('class' => 'form-control','id'=>'owneraddress')) }}
                                                <span class="validate-err" id="err_ebpa_address_house_lotno"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('ebpa_address_house_lotno', __('TCT/OCT No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebpa_address_house_lotno') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebpa_address_house_lotno',$engfeesdata->ebpa_address_house_lotno, array('class' => 'form-control','id'=>'ctcoctno')) }}
                                                <span class="validate-err" id="err_ebpa_address_house_lotno"></span>
                                              </div>
                                           </div>
                                       </div>
                                       
                                       
                                       <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_consent_comtaxcert', __('Tax Certificate'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_consent_comtaxcert') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ebfd_consent_comtaxcert',$engfeesdata->ebfd_consent_comtaxcert, array('class' => 'form-control','id'=>'owner_comtaxcert')) }}
                                                <span class="validate-err" id="err_ebfd_consent_comtaxcert"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                            </div>
                            </div>
                         </div>
                        <div class="modal-footer" style="padding-bottom: 100px;">
                        <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                        <a href="#" data-dismiss="modal" class="btn closeServiceModal" mid=""  type="edit">Close</a>
                         <a  class="btn btn-primary previouspageModal" id="previouspageModal">Previous</a> 
                        <button class="btn btn-primary" id="saveServiceDetails">Save Changes</button>
                        <!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
                        </div>
                    </div>
                    </div>
   {{Form::close()}}
   <script type="text/javascript">
     $(document).ready(function(){
        $("#ebs_id").select3({ dropdownAutoWidth: false });
        $("#eba_id").select3({ dropdownAutoWidth: false });
        // $("#ebpa_bldg_official_name").select3({ dropdownAutoWidth: false });
        // $(".suboccupancydrop").select3({ dropdownAutoWidth: false });
     });
 </script>