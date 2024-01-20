{{Form::open(array('name'=>'forms','url'=>'jobrequest/storeelectricpermit','method'=>'post','id'=>'storeelecticpermit'))}}
 {{ Form::hidden('jobrequest_id',(isset($JobRequest->id))?$JobRequest->id:NULL, array('id' => 'jobrequest_id')) }}
 {{ Form::hidden('application_id',(isset($electricappdata->id))?$electricappdata->id:NULL, array('id' => 'electricappdata')) }}
 {{ Form::hidden('session_id',(isset($sessionId))?$sessionId:'', array('id' => 'session_id')) }}
  {{ Form::hidden('p_code',(isset($pcode))?$pcode:'', array('id' => 'p_code')) }}
 <style type="text/css">
     .row{ padding: 0px 10px; }
 </style>
<div class="modal-header">
                    <h4 class="modal-title">Electrical Permit Application</h4>
                        <a class="close closeElecticModal" mid="" type="edit" data-dismiss="modal" aria-hidden="true" style="cursor:pointer;">X</a>
                    </div>
                    <div class="container"></div>
                    <div class="modal-body" style="overflow-y: scroll; height: 800px;">
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
                                            {{ Form::label('mum_no', __('Municipality'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('mum_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('mum_no',$GetMuncipalities,$electricappdata->mum_no, array('class' => 'form-control mum_no disabled-field','id'=>'ebpa_mun_no','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_mum_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('eea_application_no', __('Application No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('eea_application_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('eea_application_no',$electricappdata->eea_application_no, array('class' => 'form-control disabled-field eea_application_no','id'=>'eea_application_no','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_eea_application_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group" id="permitnodiv">
                                            {{ Form::label('ebpa_permit_no', __('Building Permit No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_permit_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('ebpa_permit_no',$arrPermitno,$electricappdata->ebpa_permit_no, array('class' => 'form-control ebpa_permit_no','id'=>'ebpa_permit_no')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_permit_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('eea_application_date', __('Date of Application'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('eea_application_date') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::date('eea_application_date',$electricappdata->eea_application_date, array('class' => 'form-control eea_application_date','id'=>'eea_application_date')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_application_date"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('eea_issued_date', __('Date Issued'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('eea_issued_date') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::date('eea_issued_date',$electricappdata->eea_issued_date, array('class' => 'form-control eea_issued_date','id'=>'eea_issued_date')) }}
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
                                        <h6 class="sub-title accordiantitle">{{__("Building Permit Details")}}</h6>
                                    </button>
                                </h6>
                                 <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            {{ Form::label('permit_owner_name', __('Permit Owner Name'),['class'=>'form-label bold']) }}
                                            <span class="validate-err">{{ $errors->first('permit_owner_name') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('permit_owner_name','', array('class' => 'form-control disabled-field permit_owner_name','id'=>'permit_owner_name')) }}
                                            </div>
                                            <span class="validate-err" id="err_mum_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('job_re_reference', __('Job Request Reference'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('job_re_reference') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('job_re_reference','', array('class' => 'form-control disabled-field job_re_reference','id'=>'job_re_reference')) }}
                                            </div>
                                            <span class="validate-err" id="err_eda_application_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-8">
                                        <div class="form-group">
                                            {{ Form::label('owner_complete_address', __('Owner Complete Address'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('owner_complete_address') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('owner_complete_address','', array('class' => 'form-control disabled-field owner_complete_address','id'=>'owner_complete_address')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_permit_no"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('building_permit_location', __('Building Permit Location'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('building_permit_location') }}</span>
                                            <div class="form-icon-user">
                                             {{ Form::text('building_permit_location','', array('class' => 'form-control disabled-field building_permit_location','id'=>'building_permit_location')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_permit_no"></span>
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
                                                {{ Form::text('ebpa_owner_last_name',$electricappdata->rpo_custom_last_name, array('class' => 'form-control ebpa_owner_last_name','id'=>'ebpa_owner_last_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_last_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_first_name', __('First Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ownerfirstname') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_first_name',$electricappdata->rpo_first_name, array('class' => 'form-control ebpa_owner_first_name','id'=>'ebpa_owner_first_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_first_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_mid_name', __('Middle Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ebpa_owner_mid_name') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_mid_name',$electricappdata->rpo_middle_name, array('class' => 'form-control ebpa_owner_mid_name','id'=>'ebpa_owner_mid_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_mid_name"></span>
                                        </div>
                                    </div>
                                      <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_suffix_name', __('Suffix'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('suffix') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_suffix_name',$electricappdata->suffix, array('class' => 'form-control suffix','id'=>'suffix')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_suffix_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('taxacctno', __('Tax Acct. No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('taxacctno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('taxacctno',$electricappdata->taxacctno, array('class' => 'form-control taxacctno','id'=>'taxacctno')) }}
                                            </div>
                                            <span class="validate-err" id="err_taxacctno"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('formofowner', __('Form of Ownership'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('formofowner') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('formofowner',$electricappdata->formofowner, array('class' => 'form-control formofowner','id'=>'formofowner')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_form_of_own"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('kindbussiness', __('Main Economic Activity/Kind Business'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('kindbussiness') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('kindbussiness',$electricappdata->kindbussiness, array('class' => 'form-control kindbussiness','id'=>'kindbussiness')) }}
                                            </div>
                                            <span class="validate-err" id="err_kindbussiness"></span>
                                        </div>
                                    </div>
                                      <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_address_house_lot_no', __('House Lot No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_address_house_lot_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_address_house_lot_no','', array('class' => 'form-control ebpa_address_house_lot_no','id'=>'ebpa_address_house_lot_no')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_address_house_lot_no"></span>
                                        </div>
                                    </div>
                                      <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('', __('Street Name'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_address_street_name') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_address_street_name','', array('class' => 'form-control ebpa_address_street_name','id'=>'ebpa_address_street_name')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_address_street_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_address_subdivision', __('Subdivision'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_address_subdivision') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_address_subdivision','', array('class' => 'form-control ebpa_address_subdivision','id'=>'ebpa_address_subdivision')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_address_subdivision"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-8">
                                        <div class="form-group">
                                            {{ Form::label('appbrgy_code', __('Barangay, Municipality, Province, Region'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('appbrgy_code') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('appbrgy_code','', array('class' => 'form-control disabled-field appbrgy_code','id'=>'appbrgy_code')) }}
                                            </div>
                                            <span class="validate-err" id="err_brgy_code"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('eea_location', __('City / Municipality of'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('eea_location') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('eea_location',$electricappdata->eea_location, array('class' => 'form-control eea_location','id'=>'eea_location')) }}
                                            </div>
                                            <span class="validate-err" id="err_eea_location"></span>
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
                                    <div class="col-md-4" id="ebs_id_group">
                                        <div class="form-group" >
                                            {{ Form::label('ebs_id', __('Scope of Work'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ebs_id') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('ebs_id',$arrscopeofwork,$electricappdata->ebs_id, array('class' => 'form-control select3','id'=>'ebs_id')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebs_id"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-8">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_scope_remarks', __('Other Remarks'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_scope_remarks') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_scope_remarks','', array('class' => 'form-control ','id'=>'ebpa_scope_remarks')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_scope_remarks"></span>
                                        </div>
                                    </div>
                                      <div class="col-md-4" id="ebot_id_group">
                                        <div class="form-group" >
                                            {{ Form::label('ebot_id', __('Use or Type of Occupancy'),['class'=>'form-label bold']) }}
                                            <span class="validate-err">{{ $errors->first('ebot_id') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('ebot_id',$arrTypeofOccupancy,$electricappdata->ebot_id, array('class' => 'form-control select3','id'=>'ebot_id')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebot_id"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-8">
                                        <div class="form-group">
                                        {{ Form::label('otherOccupancy', __('Other Remarks(Occupancy)'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('other Occupancy') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('otheroccupancy','', array('class' => 'form-control ','id'=>'otherOccupancy')) }}
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
                                        <h6 class="sub-title accordiantitle">{{__("No. of Outlets and Equipment to be Installed")}}</h6>
                                    </button>
                                </h6>
                                 <div class="row">
                                    <div class="col-sm-6">
                                     {{ Form::label('label', __('No. of Outlets and Equipment to be Installed'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                      <div class="row">
                                       @foreach($electicequipmentarray as  $key => $val)
                                        <div class="col-md-6">
                                            @php  $idsofeeetid = explode(',',$electricappdata->eeet_id);
                                                     @endphp
                                               <div class="form-check form-check-inline form-group col-md-12">
                                                {{ Form::checkbox('eeet_id[]', $key,(in_array($key,$idsofeeetid))?true:false, array('id'=>'eeet_id'.$key,'class'=>'form-check-input code')) }}
                                                {{ Form::label('eeet_id'.$key, __($val),['class'=>'form-label']) }}
                                            </div>
                                        </div>  
                                      @endforeach
                                     </div>
                                      <span class="validate-err" id="err_eeet_id"></span>
                                    </div>
                                     <div class="col-sm-6">
                                        <div class="row">  
                                          <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('eea_date_of_construction', __('Date of Proposed Start of Construction'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('eea_date_of_construction') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('eea_date_of_construction',$electricappdata->eea_date_of_construction, array('class' => 'form-control eea_date_of_construction numeric-only','id'=>'eea_date_of_construction','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_eea_date_of_construction"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('eea_estimated_cost', __('Estimate Cost of Electrical Installation'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('eea_estimated_cost') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('eea_estimated_cost',$electricappdata->eea_estimated_cost, array('class' => 'form-control eea_estimated_cost','id'=>'eea_estimated_cost','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_eea_estimated_cost"></span>
                                            </div>
                                          </div>
                                      </div>
                                       <div class="row">  
                                       <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('eea_date_of_completion', __('Expected Date Start of Completion'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('eea_date_of_completion') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::date('eea_date_of_completion',$electricappdata->eea_date_of_completion, array('class' => 'form-control eea_date_of_completion numeric-only','id'=>'eea_date_of_completion')) }}
                                            </div>
                                            <span class="validate-err" id="err_eea_date_of_completion"></span>
                                        </div>
                                     </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('eea_prepared_by', __('Prepared By'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('eea_prepared_by') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('eea_prepared_by',$hremployees,$electricappdata->eea_prepared_by, array('class' => 'form-control eea_prepared_by','id'=>'eea_prepared_by')) }}
                                            </div>
                                            <span class="validate-err" id="err_eea_prepared_by"></span>
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
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive" style="padding-right:0px;">
                                            <!-- <h6 class="sub-title accordiantitle capitalize-me">{{__("ELECTRICAL ENGINEER/MASTER ELECTRICIAN")}}</h6> -->
                                            <div class="row" style="width: 100%;padding-right: 0px;padding-left: 0px;">
                                        <div class="col-md-11">
                                            <h6 class="sub-title accordiantitle " style="padding-top: 12px;">{{__("ELECTRICAL ENGINEER/MASTER ELECTRICIAN")}}</h6>
                                        </div>
                                        <div class="col-md-1" >
                                            <a href="{{ url('/eng/consultantexternal') }}" target="_blank">
                                             <span class="btn_electricalrevision btn btn-primary hide" id="btn_electricalrevision" style="color:white;float: right;padding: 5px;"><i class="ti-plus"></i></span></a>
                                         </div>
                                        </div>
                                        </button>
                                    </h6>
                                     <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('eea_sign_category', __('Consultant Category'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eea_sign_category') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eea_sign_category',array(''=>'Please Select','1'=>'Employee','2'=>'External Consultant'),$electricappdata->eea_sign_category, array('class' => 'form-control numeric-only','id'=>'eea_sign_category')) }}
                                                <span class="validate-err" id="err_eea_sign_category"></span>
                                              </div>
                                           </div>
                                       </div>
                                         <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('eea_sign_consultant_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eea_sign_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eea_sign_consultant_id',$signdropdown,$electricappdata->eea_sign_consultant_id, array('class' => 'form-control','id'=>'eea_sign_consultant_id')) }}
                                                <span class="validate-err" id="err_espa_sign_consultant_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('address', __('Address'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('validity') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('signaddress',$electricappdata->signaddress, array('class' => 'form-control','id'=>'signaddress')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                       
                                   </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('signdateissued',$electricappdata->signdateissued, array('class' => 'form-control','id'=>'signdateissued')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('placeissued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('placeissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('signplaceissued',$electricappdata->signplaceissued, array('class' => 'form-control','id'=>'signplaceissued')) }}
                                                <span class="validate-err" id="err_tin"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('tin', __('TIN'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('signtin',$electricappdata->signtin, array('class' => 'form-control','id'=>'signtin')) }}
                                                <span class="validate-err" id="err_tin"></span>
                                              </div>
                                           </div>
                                       </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('prcregno', __('PRC Reg. No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('signprcregno',$electricappdata->signprcregno, array('class' => 'form-control','id'=>'signprcregno')) }}
                                                <span class="validate-err" id="err_signprcregno"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_sign_ptr_no', __('PTR No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('signptrno',$electricappdata->signptrno, array('class' => 'form-control','id'=>'signebfd_sign_ptr_no')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
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
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive" style="padding-right:0px;">
                                            <!-- <h6 class="sub-title accordiantitle capitalize-me">{{__("ELECTRICAL ENGINEER/MASTER ELECTRICIAN")}}</h6> -->
                                            
                                            <div class="row" style="width: 100%;padding-right: 0px;padding-left: 0px;">
                                        <div class="col-md-11">
                                            <h6 class="sub-title accordiantitle " style="padding-top: 12px;">{{__("ELECTRICAL ENGINEER/MASTER ELECTRICIAN")}}</h6>
                                        </div>
                                        <div class="col-md-1" >
                                            <a href="{{ url('/eng/consultantexternal') }}" target="_blank">
                                             <span class="btn_electricalrevision btn btn-primary hide" id="btn_electricalrevision2" style="color:white;float: right;padding: 5px;"><i class="ti-plus"></i></span></a>
                                         </div>
                                        </div>
                                        </button>
                                    </h6>
                                     <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('eea_incharge_category', __('Consultant Category'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eea_incharge_category') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eea_incharge_category',array(''=>'Please Select','1'=>'Employee','2'=>'External Consultant'),$electricappdata->eea_incharge_category, array('class' => 'form-control numeric-only','id'=>'eea_incharge_category')) }}
                                                <span class="validate-err" id="err_eea_incharge_category"></span>
                                              </div>
                                           </div>
                                       </div>
                                         <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('eea_incharge_consultant_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eea_incharge_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eea_incharge_consultant_id',$inchargedropdown,$electricappdata->eea_incharge_consultant_id, array('class' => 'form-control','id'=>'eea_incharge_consultant_id')) }}
                                                <span class="validate-err" id="err_eea_incharge_consultant_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('address', __('Address'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('validity') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('inchargenaddress',$electricappdata->inchargenaddress, array('class' => 'form-control','id'=>'inchargenaddress')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                       
                                   </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('inchargedateissued',$electricappdata->inchargedateissued, array('class' => 'form-control','id'=>'inchargedateissued')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('placeissued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('placeissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('inchargeplaceissued',$electricappdata->inchargeplaceissued, array('class' => 'form-control','id'=>'inchargeplaceissued')) }}
                                                <span class="validate-err" id="err_tin"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('tin', __('TIN'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('inchargetin',$electricappdata->inchargetin, array('class' => 'form-control','id'=>'inchargetin')) }}
                                                <span class="validate-err" id="err_tin"></span>
                                              </div>
                                           </div>
                                       </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('prcregno', __('PRC Reg. No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('inchargeprcregno',$electricappdata->inchargeprcregno, array('class' => 'form-control','id'=>'inchargeprcregno')) }}
                                                <span class="validate-err" id="err_signprcregno"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_sign_ptr_no', __('PTR No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('inchargeptrno',$electricappdata->inchargeptrno, array('class' => 'form-control','id'=>'inchargeebfd_sign_ptr_no')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                            </div>
                         </div>

                       
                        <div class="modal-footer">
                        <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                            <a href="#" data-dismiss="modal" class="btn closeElecticModal" mid=""  type="edit">Close</a>
                            <a  class="btn btn-primary nextpageModal" id="nextpage">Next</a> 
                            <!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
                        </div>
                       </div> 
                    <div id="page2" style="display:none;">
                        <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                            <h6 class="sub-title accordiantitle">{{__("Applicant Information")}}</h6>
                                        </button>
                                    </h6>
                                     <div class="row">
                                       <div class="col-md-3">
                                           <div class="form-group">
                                                {{ Form::label('eea_applicant_consultant_id', __('Full Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eea_applicant_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eea_applicant_consultant_id',$arrlotOwner,$electricappdata->eea_applicant_consultant_id,array('class' => 'form-control numeric-only','id'=>'eea_applicant_consultant_id')) }}
                                                <span class="validate-err" id="err_espa_applicant_consultant_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                      <!--   <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('tan', __('TAN'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('tan') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('tan','', array('class' => 'form-control numeric-only','id'=>'tan','required'=>'required')) }}
                                                <span class="validate-err" id="err_eea_incharge_category"></span>
                                              </div>
                                           </div>
                                       </div> -->
                                         <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('certno.', __('Res. Cert. No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('certno') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('rescertno',$electricappdata->rescertno, array('class' => 'form-control','id'=>'applicant_comtaxcert')) }}
                                                <span class="validate-err" id="err_certno"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('dateissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('dateissued',$electricappdata->dateissued, array('class' => 'form-control','id'=>'applicant_date_issued')) }}
                                                <span class="validate-err" id="err_dateissued"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('placeissued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('placeissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('placeissued',$electricappdata->placeissued, array('class' => 'form-control','id'=>'applicant_place_issued')) }}
                                                <span class="validate-err" id="err_placeissued"></span>
                                              </div>
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('eea_owner_id', __('Full Name'),['class'=>'form-label bold']) }}
                                            <span class="validate-err">{{ $errors->first('eea_owner_id') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('eea_owner_id',$arrlotOwner,$electricappdata->eea_owner_id, array('class' => 'form-control eea_owner_id','id'=>'eea_owner_id')) }}
                                            </div>
                                            <span class="validate-err" id="err_eea_owner_id"></span>
                                        </div>
                                    </div>
                                      <div class="col-md-3">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_suffix_name', __('Suffix'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('suffix') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_suffix_name','', array('class' => 'form-control suffix','id'=>'ownersuffix')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_suffix_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-3">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_tax_acct_no', __('Tax Acct. No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_tax_acct_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ownertaxdcno',$electricappdata->ownertaxdcno, array('class' => 'form-control ownertaxdcno','id'=>'ownertaxdcno')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_tax_acct_no"></span>
                                        </div>
                                    </div>
                                     <div class="row">
                                        <div class="form-group">
                                            {{ Form::label('addresslabel', __('Address'),['class'=>'form-label']) }}
                                        </div>
                                    </div>
                                      <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_address_house_lot_no', __('House Lot No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_address_house_lot_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('owneraddress',$electricappdata->owneraddress, array('class' => 'form-control owneraddress','id'=>'ownerebpa_address_house_lot_no')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_address_house_lot_no"></span>
                                        </div>
                                    </div>
                                      <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('', __('Street Name'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_address_street_name') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ownerstreet',$electricappdata->ownerstreet, array('class' => 'form-control ebpa_address_street_name','id'=>'ownerebpa_address_street_name')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_address_street_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_address_subdivision', __('Subdivision'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_address_subdivision') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ownersubdivision',$electricappdata->ownersubdivision, array('class' => 'form-control ownersubdivision','id'=>'ownerebpa_address_subdivision')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_address_subdivision"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {{ Form::label('muncipality', __('City/Municipality of'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('muncipality') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ownermuncipality',$electricappdata->ownermuncipality, array('class' => 'form-control muncipality','id'=>'ownermuncipality')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_location"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {{ Form::label('telephoneno', __('Telephone No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('telephoneno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ownertelephoneno',$electricappdata->ownertelephoneno, array('class' => 'form-control telephoneno','id'=>'ownertelephoneno')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_location"></span>
                                        </div>
                                    </div>
                                </div>
                                  <div class="row">
                                    <div class="row">{{ Form::label('locconstruct', __('Location of Installation'),['class'=>'form-label']) }}</div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('tdno', __('Tax Dec. No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('tdno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('taxdecno',$electricappdata->taxdecno, array('class' => 'form-control  tdno','id'=>'tdno')) }}
                                            </div>
                                            <span class="validate-err" id="err_tdno"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('totno', __('TCT NO.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('totno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('totno',$electricappdata->totno, array('class' => 'form-control  totno','id'=>'totno')) }}
                                            </div>
                                            <span class="validate-err" id="err_Street"></span>
                                        </div>
                                    </div>
                                     
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('lotno', __('LOT NO.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('lotno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('lotno',$electricappdata->lotno, array('class' => 'form-control lotno','id'=>'lotno')) }}
                                            </div>
                                            <span class="validate-err" id="err_Street"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('blkno', __('BLK NO.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('blkno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('blkno',$electricappdata->blkno, array('class' => 'form-control blkno','id'=>'blkno')) }}
                                            </div>
                                            <span class="validate-err" id="err_Street"></span>
                                        </div>
                                    </div>
                                     
                                </div>
                                <div class="row">
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('streetname', __('Street'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('Street') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('streetname',$electricappdata->streetname, array('class' => 'form-control  streetname','id'=>'streetname')) }}
                                            </div>
                                            <span class="validate-err" id="err_streetname"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('locappbrgy_code', __('Barangay'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('locappbrgy_code') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('locappbrgy_code','', array('class' => 'form-control disabled-field brgy_code','id'=>'locappbrgy_code')) }}
                                            </div>
                                            <span class="validate-err" id="err_brgy_code"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('loceeta_location', __('City / Municipal of'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('loceeta_location') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('loceeta_location',$electricappdata->ownerespa_location, array('class' => 'form-control disabled-field','id'=>'loceeta_location')) }}
                                            </div>
                                            <span class="validate-err" id="err_eeta_location"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                         <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                        <div class="row" style="width: 100%;">
                                        <div class="col-md-8">
                                            <h6 class="sub-title accordiantitle" style="padding-top: 12px;">{{__("Assessed Fee")}}</h6>
                                        </div>
                                        <div class="col-md-4" >
                                             <span class="btn_electricalrevisionid btn btn-primary" id="btn_electricalrevisionid" style="color:white;float: right;padding: 5px;"><i class="ti-plus"></i></span>
                                         </div>
                                        </div>
                                                    </button>

                                    </h6>
                                     <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('eea_amount_due', __('Amount Due'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eea_amount_due') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('eea_amount_due',$electricappdata->eea_amount_due, array('class' => 'form-control numeric-only','id'=>'eea_amount_due')) }}
                                                <span class="validate-err" id="err_eea_amount_due"></span>
                                              </div>
                                           </div>
                                       </div>
                                         <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('eea_assessed_by.', __('Assessed By'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eea_assessed_by') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eea_assessed_by',$hremployees,$electricappdata->eea_assessed_by, array('class' => 'form-control','id'=>'eea_assessed_by')) }}
                                                <span class="validate-err" id="err_certno"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('eea_or_no', __('O.R. No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eea_or_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('eea_or_no',$electricappdata->eea_or_no, array('class' => 'form-control disabled-field','id'=>'eea_or_no')) }}
                                                <span class="validate-err" id="err_eea_or_no"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('eea_date_paid', __('Date Paid'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eea_date_paid') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('eea_date_paid',$electricappdata->eea_date_paid, array('class' => 'form-control disabled-field','id'=>'eea_date_paid')) }}
                                                <span class="validate-err" id="err_eea_date_paid"></span>
                                              </div>
                                           </div>
                                       </div>
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('eea_building_official', __('Building Official Full Name'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('eea_building_official') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('eea_building_official',$buildofficial,$electricappdata->eea_building_official, array('class' => 'form-control','id'=>'eea_building_official')) }}
                                            </div>
                                            <span class="validate-err" id="err_eea_building_official"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        <div class="modal-footer">
                        <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                        <a href="#" data-dismiss="modal" class="btn closeElecticModal" mid=""  type="edit">Close</a>
                         <a  class="btn btn-primary previouspageModal" id="previouspageModal">Previous</a> 
                        <button class="btn btn-primary" id="saveServiceDetails">Save Changes</button>
                        <!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
                        </div>
                    </div>
                  
                 {{Form::close()}}
                 <script type="text/javascript">
                     $(document).ready(function(){
                        $("#ebs_id").select3({ dropdownAutoWidth: false });
                        $("#ebot_id").select3({ dropdownAutoWidth: false });
                        $("#eea_sign_category").select3({ dropdownAutoWidth: false });
                        $("#eea_incharge_category").select3({ dropdownAutoWidth: false });
                     });
                 </script>
                 <script>
                    var elements = document.querySelectorAll('.accordiantitle');
                    elements.forEach(function(element) {
                        element.textContent = element.textContent.toLowerCase().replace(/\b./g, function(m) {
                            return m.toUpperCase();
                        });
                    });
                </script>