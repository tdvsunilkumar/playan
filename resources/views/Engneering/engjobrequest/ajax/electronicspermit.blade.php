{{Form::open(array('name'=>'forms','url'=>'jobrequest/storeelectrronicpermit','method'=>'post','id'=>'storeelectrronicpermit'))}}
 {{ Form::hidden('jobrequest_id',(isset($JobRequest->id))?$JobRequest->id:NULL, array('id' => 'jobrequest_id')) }}
 {{ Form::hidden('application_id',(isset($electronicsappdata->id))?$electronicsappdata->id:NULL, array('id' => 'application_id')) }}
 {{ Form::hidden('session_id',(isset($sessionId))?$sessionId:'', array('id' => 'session_id')) }}
 {{ Form::hidden('p_code',(isset($pcode))?$pcode:'', array('id' => 'p_code')) }}
 <style type="text/css">
     .row{ padding: 0px 10px; }
 </style>
<div class="modal-header">
                    <h4 class="modal-title">Electronic Permit Application</h4>
                        <a class="close closeElectronicModal" mid="" type="edit" data-dismiss="modal" aria-hidden="true"style="cursor:pointer;">X</a>
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
                                            {{ Form::label('mum_no', __('Municipal'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('mum_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('mum_no',$GetMuncipalities,$electronicsappdata->mum_no, array('class' => 'form-control mum_no disabled-field','id'=>'ebpa_mun_no','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_mum_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('eeta_application_no', __('Application No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('eeta_application_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('eeta_application_no',$electronicsappdata->eeta_application_no, array('class' => 'form-control disabled-field eeta_application_no','id'=>'eeta_application_no','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_eeta_application_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group" id="permitnodiv">
                                            {{ Form::label('ebpa_permit_no', __('Permit No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_permit_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('ebpa_permit_no',$arrPermitno,$electronicsappdata->ebpa_permit_no, array('class' => 'form-control ebpa_permit_no','id'=>'ebpa_permit_no')) }}
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
                                                {{ Form::text('ebpa_owner_last_name',$electronicsappdata->rpo_custom_last_name, array('class' => 'form-control ebpa_owner_last_name','id'=>'ebpa_owner_last_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_last_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_first_name', __('First Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ownerfirstname') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_first_name',$electronicsappdata->rpo_first_name, array('class' => 'form-control ebpa_owner_first_name','id'=>'ebpa_owner_first_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_first_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_mid_name', __('Middle Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ebpa_owner_mid_name') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_mid_name',$electronicsappdata->rpo_middle_name, array('class' => 'form-control ebpa_owner_mid_name','id'=>'ebpa_owner_mid_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_mid_name"></span>
                                        </div>
                                    </div>
                                      <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_suffix_name', __('Suffix'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('suffix') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_suffix_name',$electronicsappdata->suffix, array('class' => 'form-control suffix','id'=>'suffix')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_suffix_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_tax_acct_no', __('Tax Acct. No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_tax_acct_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('eeta_tax_acct_no',$electronicsappdata->eeta_tax_acct_no, array('class' => 'form-control ebpa_tax_acct_no','id'=>'ebpa_tax_acct_no')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_tax_acct_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('eeta_form_of_own', __('Form of Ownership'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('eeta_form_of_own') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('eeta_form_of_own',$electronicsappdata->eeta_form_of_own, array('class' => 'form-control eeta_form_of_own','id'=>'eeta_form_of_own')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_form_of_own"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('eeta_economic_act', __('Main Economic Activity / Kind Business'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('eeta_economic_act') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('eeta_economic_act',$electronicsappdata->eeta_economic_act, array('class' => 'form-control eeta_economic_act','id'=>'eeta_economic_act')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_economic_act"></span>
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
                                            <span class="validate-err">{{ $errors->first('brgy_code') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('appbrgy_code','', array('class' => 'form-control disabled-field brgy_code','id'=>'appbrgy_code')) }}
                                            </div>
                                            <span class="validate-err" id="err_brgy_code"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('eeta_location', __('City / Municipal of'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('eeta_location') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('eeta_location',$electronicsappdata->eeta_location, array('class' => 'form-control eeta_location','id'=>'eeta_location')) }}
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
                                        <h6 class="sub-title accordiantitle">{{__("Location of Construction")}}</h6>
                                    </button>
                                </h6>
                                 <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('tdno', __('Tax Dec. No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('tdno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('taxdecno',$electronicsappdata->taxdecno, array('class' => 'form-control  tdno','id'=>'tdno')) }}
                                            </div>
                                            <span class="validate-err" id="err_tdno"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('totno', __('TCT NO.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('totno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('totno',$electronicsappdata->totno, array('class' => 'form-control  totno','id'=>'totno')) }}
                                            </div>
                                            <span class="validate-err" id="err_Street"></span>
                                        </div>
                                    </div>
                                     
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('lotno', __('LOT NO.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('lotno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('lotno',$electronicsappdata->lotno, array('class' => 'form-control lotno','id'=>'lotno')) }}
                                            </div>
                                            <span class="validate-err" id="err_Street"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('blkno', __('BLK NO.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('blkno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('blkno',$electronicsappdata->blkno, array('class' => 'form-control blkno','id'=>'blkno')) }}
                                            </div>
                                            <span class="validate-err" id="err_Street"></span>
                                        </div>
                                    </div>
                                     
                                </div>
                                <div class="row">
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('Street', __('Street'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('Street') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('Street',$electronicsappdata->Street, array('class' => 'form-control  Street','id'=>'Street')) }}
                                            </div>
                                            <span class="validate-err" id="err_Street"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('locappbrgy_code', __('Barangay'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('locappbrgy_code') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('locappbrgy_code',$electronicsappdata->locbarangay, array('class' => 'form-control disabled-field brgy_code','id'=>'locappbrgy_code')) }}
                                            </div>
                                            <span class="validate-err" id="err_brgy_code"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('loceeta_location', __('City / Municipal of'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('loceeta_location') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('loceeta_location',$electronicsappdata->locmunicipality, array('class' => 'form-control disabled-field','id'=>'loceeta_location')) }}
                                            </div>
                                            <span class="validate-err" id="err_eeta_location"></span>
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
                                            <div class="form-group" id="ebs_id_group">
                                                {{ Form::label('ebs_id', __('Scope of Work'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebs_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('ebs_id',$arrbuildingScope,$electronicsappdata->ebs_id, array('class' => 'form-control','id'=>'ebs_id')) }}
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
                                    </div>
                                </div>
                            </div>
                            <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                            <h6 class="sub-title accordiantitle">{{__("Nature of installation works / Equipments System")}} &nbsp;<span class="text-danger">*</span></h6>
                                        </button>
                                    </h6>
                                     <div class="row">
                                        <div class="col-sm-12">
                                          <div class="row">
                                           @foreach($electronicequipmentarray as  $key => $val)
                                            <div class="col-md-4">
                                                 @php  $idsofeest_id = explode(',',$electronicsappdata->eest_id);
                                                         @endphp
                                                   <div class="form-check form-check-inline form-group col-md-12">
                                                    {{ Form::checkbox('eest_id[]', $key,(in_array($key,$idsofeest_id))?true:false, array('id'=>'eest_id'.$key,'class'=>'form-check-input code')) }}
                                                    {{ Form::label('eest_id'.$key, __($val),['class'=>'form-label']) }}
                                                </div>
                                            </div>  
                                          @endforeach
                                         </div>
                                         <span class="validate-err" id="err_eest_id"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                            <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                                <a href="#" data-dismiss="modal" class="btn closeElectronicModal" mid=""  type="edit">Close</a>
                                <a  class="btn btn-primary nextpageModal" id="nextpage">Next</a> 
                                <!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
                            </div>
                          </div> 
                      </div>
                  </div>
                    <div id="page2" style="display:none;">
                          <div class="row">
                             <div class="col-sm-6">
                               <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive" style="padding-right:0px;">
                                            <!-- <h6 class="sub-title accordiantitle">{{__("DESIGN PROFESSIONAL, PLANS AND SPECIFICATION")}}</h6> -->
                                            <div class="row" style="width: 100%;padding-right: 0px;padding-left: 0px;">
                                        <div class="col-md-11">
                                            <h6 class="sub-title accordiantitle " style="padding-top: 12px;">{{__("DESIGN PROFESSIONAL, PLANS AND SPECIFICATION")}}</h6>
                                        </div>
                                        <div class="col-md-1" >
                                            <a href="{{ url('/eng/consultantexternal') }}" target="_blank">
                                             <span class="btn_electricalrevision btn btn-primary hide" id="btn_electricalrevision2" style="color:white;float: right;padding: 5px;"><i class="ti-plus"></i></span></a>
                                         </div>
                                        </div>
                                        </button>
                                    </h6>
                                     <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('eeta_sign_category', __('Consultant Category'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eeta_sign_category') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eeta_sign_category',array(''=>'Please Select','1'=>'Employee','2'=>'External Consultant'),$electronicsappdata->eeta_sign_category, array('class' => 'form-control numeric-only','id'=>'eeta_sign_category')) }}
                                                <span class="validate-err" id="err_eeta_sign_category"></span>
                                              </div>
                                           </div>
                                       </div>
                                         <div class="col-md-8">
                                            <div class="form-group">
                                                {{ Form::label('eeta_sign_consultant_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eeta_sign_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eeta_sign_consultant_id',$signdropdown,$electronicsappdata->eeta_sign_consultant_id, array('class' => 'form-control','id'=>'eeta_sign_consultant_id')) }}
                                                <span class="validate-err" id="err_eeta_sign_consultant_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('address', __('Address'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('validity') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('signaddress',$electronicsappdata->signaddress, array('class' => 'form-control','id'=>'signaddress')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_sign_prc_reg_no', __('PRC NO.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_prc_reg_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('signprcno',$electronicsappdata->signprcno, array('class' => 'form-control numeric-only','id'=>'signprcno')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('validity', __('Validity'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('validity') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('signvalidity',$electronicsappdata->signvalidity, array('class' => 'form-control','id'=>'signvalidity')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                                    <div class="row">
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_sign_ptr_no', __('PTR No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('signptrno',$electronicsappdata->signptrno, array('class' => 'form-control','id'=>'signebfd_sign_ptr_no')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('signdateissued', __('Date Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('signdateissued',$electronicsappdata->signdateissued, array('class' => 'form-control','id'=>'signdateissued')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                                    <div class="row">
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('signplaceissued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('signplaceissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('signplaceissued',$electronicsappdata->signplaceissued, array('class' => 'form-control','id'=>'signplaceissued')) }}
                                                <span class="validate-err" id="err_tin"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('signtin', __('TIN'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('signtin',$electronicsappdata->signtin, array('class' => 'form-control ','id'=>'signtin')) }}
                                                <span class="validate-err" id="err_tin"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                            </div>
                         </div>
                         <div class="col-sm-6">
                               <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive" style="padding-right:0px;">
                                            <!-- <h6 class="sub-title accordiantitle">{{__("Supervisor / In Charge of Electronics Works")}}</h6> -->
                                             <div class="row" style="width: 100%;padding-right: 0px;padding-left: 0px;">
                                        <div class="col-md-11">
                                            <h6 class="sub-title accordiantitle " style="padding-top: 12px;">{{__("Supervisor / In Charge of Electronics Works")}}</h6>
                                        </div>
                                        <div class="col-md-1" >
                                            <a href="{{ url('/eng/consultantexternal') }}" target="_blank">
                                             <span class="btn_electricalrevision btn btn-primary hide" id="btn_electricalrevision" style="color:white;float: right;padding: 5px;"><i class="ti-plus"></i></span></a>
                                         </div>
                                        </div>
                                        </button>
                                    </h6>
                                     <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('eeta_incharge_category', __('Consultant Category'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eeta_incharge_category') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eeta_incharge_category',array(''=>'Please Select','1'=>'Employee','2'=>'External Consultant'),$electronicsappdata->eeta_incharge_category, array('class' => 'form-control numeric-only','id'=>'eeta_incharge_category')) }}
                                                <span class="validate-err" id="err_eeta_incharge_category"></span>
                                              </div>
                                           </div>
                                       </div>
                                         <div class="col-md-8">
                                            <div class="form-group">
                                                {{ Form::label('eeta_incharge_consultant_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eeta_incharge_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eeta_incharge_consultant_id',$inchargedropdown,$electronicsappdata->eeta_incharge_consultant_id, array('class' => 'form-control','id'=>'eeta_incharge_consultant_id')) }}
                                                <span class="validate-err" id="err_eeta_incharge_consultant_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('address', __('Address'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('validity') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('inchargenaddress',$electronicsappdata->inchargenaddress, array('class' => 'form-control','id'=>'inchargenaddress')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_sign_prc_reg_no', __('PRC NO.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_prc_reg_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('inchargeprcregno',$electronicsappdata->inchargeprcregno, array('class' => 'form-control','id'=>'inchargeprcregno')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('validity', __('Validity'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('validity') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('inchargevalidity',$electronicsappdata->inchargevalidity, array('class' => 'form-control','id'=>'inchargevalidity')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                                    <div class="row">
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_sign_ptr_no', __('PTR No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('inchargeptrno',$electronicsappdata->inchargeptrno, array('class' => 'form-control','id'=>'inchargeebfd_sign_ptr_no')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('inchargedateissued',$electronicsappdata->inchargedateissued, array('class' => 'form-control','id'=>'inchargedateissued')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                                    <div class="row">
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('placeissued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('placeissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('inchargeplaceissued',$electronicsappdata->inchargeplaceissued, array('class' => 'form-control','id'=>'inchargeplaceissued')) }}
                                                <span class="validate-err" id="err_tin"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('tin', __('TIN'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('inchargetin',$electronicsappdata->inchargetin, array('class' => 'form-control','id'=>'inchargetin')) }}
                                                <span class="validate-err" id="err_tin"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                            </div>
                         </div>
                        </div>
                         <div class="row">
                             <div class="col-sm-6">
                              <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                            <h6 class="sub-title accordiantitle">{{__("BUILDING OWNER")}}</h6>
                                        </button>
                                    </h6>
                                     <div class="row">
                                         <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('eeta_applicant_consultant_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eeta_applicant_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eeta_applicant_consultant_id',$arrlotOwner,$electronicsappdata->eeta_applicant_consultant_id, array('class' => 'form-control','id'=>'eeta_applicant_consultant_id')) }}
                                                <span class="validate-err" id="err_eeta_applicant_consultant_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('address', __('Address'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('validity') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('applicantaddress',$electronicsappdata->applicantaddress, array('class' => 'form-control','id'=>'applicantaddress')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ctcno', __('CTC NO.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ctcno') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('applicant_comtaxcert',$electronicsappdata->applicant_comtaxcert, array('class' => 'form-control','id'=>'applicant_comtaxcert')) }}
                                                <span class="validate-err" id="err_ctcno"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('dateissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('applicant_date_issued',$electronicsappdata->applicant_date_issued, array('class' => 'form-control','id'=>'applicant_date_issued')) }}
                                                <span class="validate-err" id="err_dateissued"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('placeissued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('placeissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('applicant_place_issued',$electronicsappdata->applicant_place_issued, array('class' => 'form-control','id'=>'applicant_place_issued')) }}
                                                <span class="validate-err" id="err_placeissued"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                            </div>
                         </div>
                         <div class="col-sm-6">
                               <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                            <h6 class="sub-title accordiantitle">{{__("WITH MY CONSENT LOT OWNER")}}</h6>
                                        </button>
                                    </h6>
                                     <div class="row">
                                         <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('eeta_owner_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eeta_owner_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eeta_owner_id',$arrlotOwner,$electronicsappdata->eeta_owner_id, array('class' => 'form-control','id'=>'eeta_owner_id')) }}
                                                <span class="validate-err" id="err_eeta_owner_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('address', __('Address'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('validity') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('owneraddress',$electronicsappdata->owneraddress, array('class' => 'form-control','id'=>'owneraddress')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ctcno', __('CTC NO.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ctcno') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('owner_comtaxcert',$electronicsappdata->owner_comtaxcert, array('class' => 'form-control','id'=>'owner_comtaxcert')) }}
                                                <span class="validate-err" id="err_ctcno"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('dateissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('owner_date_issued',$electronicsappdata->owner_date_issued, array('class' => 'form-control','id'=>'owner_date_issued')) }}
                                                <span class="validate-err" id="err_dateissued"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('placeissued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('placeissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ownerplaceissued',$electronicsappdata->ownerplaceissued, array('class' => 'form-control','id'=>'ownerplaceissued')) }}
                                                <span class="validate-err" id="err_placeissued"></span>
                                              </div>
                                           </div>
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
                                            {{ Form::label('eeta_building_official', __('Building Official Full Name'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('eeta_building_official') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('eeta_building_official',$buildofficial,$electronicsappdata->eeta_building_official, array('class' => 'form-control','id'=>'eeta_building_official','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_eeta_building_official"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        <div class="modal-footer">
                        <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                        <a href="#" data-dismiss="modal" class="btn closeElectronicModal" mid=""  type="edit">Close</a>
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
                        $("#eeta_sign_category").select3({ dropdownAutoWidth: false });
                        $("#eeta_incharge_category").select3({ dropdownAutoWidth: false });
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