{{Form::open(array('name'=>'forms','url'=>'jobrequest/storefencingpermit','method'=>'post','id'=>'storefencingpermit'))}}
 {{ Form::hidden('jobrequest_id',(isset($JobRequest->id))?$JobRequest->id:NULL, array('id' => 'jobrequest_id')) }}
 {{ Form::hidden('application_id',(isset($fencingappdata->id))?$fencingappdata->id:NULL, array('id' => 'fencingappdata')) }}
 {{ Form::hidden('session_id',(isset($sessionId))?$sessionId:'', array('id' => 'session_id')) }}
  {{ Form::hidden('p_code',(isset($pcode))?$pcode:'', array('id' => 'p_code')) }}
 <style type="text/css">
     .row{ padding: 0px 10px; }
 </style>
<div class="modal-header">
                    <h4 class="modal-title">Fencing Permit Application</h4>
                        <a class="close closeFencingModal" mid="" type="edit" data-dismiss="modal" aria-hidden="true" style="cursor:pointer;">X</a>
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
                                            {{ Form::label('mun_no', __('Municipality'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('mun_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('mun_no',$GetMuncipalities,$fencingappdata->mun_no, array('class' => 'form-control mun_no disabled-field','id'=>'ebpa_mun_no','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_mun_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('efa_application_no', __('Application No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('efa_application_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('efa_application_no',$fencingappdata->efa_application_no, array('class' => 'form-control disabled-field efa_application_no','id'=>'efa_application_no','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_efa_application_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group" id="permitnodiv">
                                            {{ Form::label('ebpa_permit_no', __('Building Permit No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_permit_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('ebpa_permit_no',$arrPermitno,$fencingappdata->ebpa_permit_no, array('class' => 'form-control ebpa_permit_no','id'=>'ebpa_permit_no')) }}
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
                                                {{ Form::text('ebpa_owner_last_name',$fencingappdata->rpo_custom_last_name, array('class' => 'form-control ebpa_owner_last_name','id'=>'ebpa_owner_last_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_last_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_first_name', __('First Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ownerfirstname') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_first_name',$fencingappdata->rpo_first_name, array('class' => 'form-control ebpa_owner_first_name','id'=>'ebpa_owner_first_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_first_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_mid_name', __('Middle Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ebpa_owner_mid_name') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_mid_name',$fencingappdata->rpo_middle_name, array('class' => 'form-control ebpa_owner_mid_name','id'=>'ebpa_owner_mid_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_mid_name"></span>
                                        </div>
                                    </div>
                                      <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_suffix_name', __('Suffix'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('suffix') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_suffix_name',$fencingappdata->suffix, array('class' => 'form-control suffix','id'=>'suffix')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_suffix_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_tax_acct_no', __('Tax Acct. No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_tax_acct_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('taxacctno',$fencingappdata->taxacctno, array('class' => 'form-control ebpa_tax_acct_no','id'=>'ebpa_tax_acct_no')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_tax_acct_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_form_of_own', __('Form of Ownership'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_form_of_own') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('efa_form_of_own',$fencingappdata->efa_form_of_own, array('class' => 'form-control ebpa_form_of_own','id'=>'ebpa_form_of_own')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_form_of_own"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_economic_act', __('Main Economic Activity/Kind Business'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_economic_act') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('maineconomy',$fencingappdata->maineconomy, array('class' => 'form-control ebpa_economic_act','id'=>'ebpa_economic_act')) }}
                                            </div>
                                            <span class="validate-err" id="err_maineconomy"></span>
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
                                                {{ Form::text('appbrgy_code','', array('class' => 'form-control disabled-field appbrgy_code','id'=>'appbrgy_code')) }}
                                            </div>
                                            <span class="validate-err" id="err_brgy_code"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_location', __('City/Municipality of'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ebpa_location') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_location',$fencingappdata->ebpa_location, array('class' => 'form-control ema_location','id'=>'ebpa_location')) }}
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
                                        <h6 class="sub-title accordiantitle">{{__("Location of Construction")}}</h6>
                                    </button>
                                </h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('tdno', __('Tax Dec No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('tdno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('taxdecno',$fencingappdata->taxdecno, array('class' => 'form-control select3 tdno','id'=>'tdno')) }}
                                            </div>
                                            <span class="validate-err" id="err_tdno"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('totno', __('TCT No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('totno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('totno',$fencingappdata->totno, array('class' => 'form-control select3 totno','id'=>'totno')) }}
                                            </div>
                                            <span class="validate-err" id="err_Street"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('lotno', __('LOT No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('lotno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('lotno',$fencingappdata->lotno, array('class' => 'form-control select3 lotno','id'=>'lotno')) }}
                                            </div>
                                            <span class="validate-err" id="err_Street"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('blkno', __('BLK No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('blkno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('blkno',$fencingappdata->blkno, array('class' => 'form-control select3 blkno','id'=>'blkno')) }}
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
                                                {{ Form::text('Street',$fencingappdata->Street, array('class' => 'form-control select3 Street','id'=>'Street')) }}
                                            </div>
                                            <span class="validate-err" id="err_Street"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('locappbrgy_code', __('Barangay'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('appbrgy_code') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('locappbrgy_code',$fencingappdata->locbarangay, array('class' => 'form-control disabled-field locappbrgy_code','id'=>'locappbrgy_code')) }}
                                            </div>
                                            <span class="validate-err" id="err_brgy_code"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('loceega_location', __('City/Municipality of'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('loceega_location') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('loceega_location',$fencingappdata->locmunicipality, array('class' => 'form-control disabled-field','id'=>'loceega_location')) }}
                                            </div>
                                            <span class="validate-err" id="err_eega_location"></span>
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
                                 <div class="row" id="ebs_id_group">
                                    <div class="col-md-4">
                                        <div class="form-group" >
                                            {{ Form::label('ebs_id', __('Scope of Work'),['class'=>'form-label bold']) }}
                                            <span class="validate-err">{{ $errors->first('ebs_id') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('ebs_id',$arrbuildingScope,$fencingappdata->ebs_id, array('class' => 'form-control','id'=>'ebs_id')) }}
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
                         <div class="row">
                             <div class="col-sm-6">
                               <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive" style="padding-right:0px;">
                                            <!-- <h6 class="sub-title accordiantitle capitalize-me">{{__("DESIGN PROFESSIONAL, PLANS AND SPECIFICATION")}}</h6> -->
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
                                                {{ Form::label('efa_sign_category', __('Consultant Category'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('efa_sign_category') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('efa_sign_category',array(''=>'Please Select','1'=>'Employee','2'=>'External Consultant'),$fencingappdata->efa_sign_category, array('class' => 'form-control numeric-only','id'=>'efa_sign_category')) }}
                                                <span class="validate-err" id="err_efa_sign_category"></span>
                                              </div>
                                           </div>
                                       </div>
                                         <div class="col-md-8">
                                            <div class="form-group">
                                                {{ Form::label('efa_sign_consultant_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('efa_sign_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('efa_sign_consultant_id',$signdropdown,$fencingappdata->efa_sign_consultant_id, array('class' => 'form-control','id'=>'efa_sign_consultant_id')) }}
                                                <span class="validate-err" id="err_efa_sign_consultant_id"></span>
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
                                                    {{ Form::text('signaddress',$fencingappdata->signaddress, array('class' => 'form-control','id'=>'signaddress')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_sign_prc_reg_no', __('PRC No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_prc_reg_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('signprcno',$fencingappdata->signprcno, array('class' => 'form-control numeric-only','id'=>'signprcno')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('validity', __('Validity'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('validity') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('signvalidity',$fencingappdata->signvalidity, array('class' => 'form-control','id'=>'signvalidity')) }}
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
                                                    {{ Form::text('signptrno',$fencingappdata->signptrno, array('class' => 'form-control ','id'=>'signebfd_sign_ptr_no')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('signdateissued',$fencingappdata->signdateissued, array('class' => 'form-control','id'=>'signdateissued')) }}
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
                                                    {{ Form::text('signplaceissued',$fencingappdata->signplaceissued, array('class' => 'form-control','id'=>'signplaceissued')) }}
                                                <span class="validate-err" id="err_tin"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('tin', __('TIN'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('signtin',$fencingappdata->signtin, array('class' => 'form-control numeric-only','id'=>'signtin')) }}
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
                                            <!-- <h6 class="sub-title accordiantitle capitalize-me" style="padding-top:10px;">{{__("FULL TIME INSPECTOR AND SUPERVISOR OF CONSTRUCTION WORK")}}</h6> -->
                                            
                                             <div class="row" style="width: 100%;padding-right: 0px;padding-left: 0px;">
                                        <div class="col-md-11">
                                            <h6 class="sub-title accordiantitle " style="padding-top: 12px;">{{__("FULL TIME INSPECTOR AND SUPERVISOR OF CONSTRUCTION WORK")}}</h6>
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
                                                {{ Form::label('efa_inspector_category', __('Consultant Category'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('efa_inspector_category') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('efa_inspector_category',array(''=>'Please Select','1'=>'Employee','2'=>'External Consultant'),$fencingappdata->efa_inspector_category, array('class' => 'form-control numeric-only','id'=>'efa_inspector_category')) }}
                                                <span class="validate-err" id="err_efa_inspector_category"></span>
                                              </div>
                                           </div>
                                       </div>
                                         <div class="col-md-8">
                                            <div class="form-group">
                                                {{ Form::label('efa_inspector_consultant_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('efa_inspector_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('efa_inspector_consultant_id',$inchargedropdown,$fencingappdata->efa_inspector_consultant_id, array('class' => 'form-control','id'=>'efa_inspector_consultant_id')) }}
                                                <span class="validate-err" id="err_efa_inspector_consultant_id"></span>
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
                                                    {{ Form::text('inchargenaddress',$fencingappdata->inchargenaddress, array('class' => 'form-control','id'=>'inchargenaddress')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ebfd_sign_prc_reg_no', __('PRC No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_prc_reg_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('inchargeprcregno',$fencingappdata->inchargeprcregno, array('class' => 'form-control','id'=>'inchargeprcregno')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('validity', __('Validity'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('validity') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('inchargevalidity',$fencingappdata->inchargevalidity, array('class' => 'form-control','id'=>'inchargevalidity')) }}
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
                                                    {{ Form::text('inchargeptrno',$fencingappdata->inchargeptrno, array('class' => 'form-control','id'=>'inchargeebfd_sign_ptr_no')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('inchargedateissued',$fencingappdata->inchargedateissued, array('class' => 'form-control','id'=>'inchargedateissued')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                   </div>
                                    <div class="row">
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('inchargeplaceissued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('placeissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('inchargeplaceissued',$fencingappdata->inchargeplaceissued, array('class' => 'form-control','id'=>'inchargeplaceissued')) }}
                                                <span class="validate-err" id="err_tin"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('tin', __('TIN'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('inchargetin',$fencingappdata->inchargetin, array('class' => 'form-control','id'=>'inchargetin')) }}
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
                                            <h6 class="sub-title accordiantitle">{{__("Applicant")}}</h6>
                                        </button>
                                    </h6>
                                     <div class="row">
                                         <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('efa_applicant_consultant_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('efa_applicant_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('efa_applicant_consultant_id',$arrlotOwner,$fencingappdata->efa_applicant_consultant_id, array('class' => 'form-control','id'=>'efa_applicant_consultant_id')) }}
                                                <span class="validate-err" id="err_efa_applicant_consultant_id"></span>
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
                                                    {{ Form::text('applicantaddress',$fencingappdata->applicantaddress, array('class' => 'form-control','id'=>'applicantaddress')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ctcno', __('C.T.C. No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ctcno') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('applicant_comtaxcert',$fencingappdata->applicant_comtaxcert, array('class' => 'form-control','id'=>'applicant_comtaxcert')) }}
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
                                                    {{ Form::date('applicant_date_issued',$fencingappdata->applicant_date_issued, array('class' => 'form-control ','id'=>'applicant_date_issued')) }}
                                                <span class="validate-err" id="err_dateissued"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('placeissued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('placeissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('applicant_place_issued',$fencingappdata->applicant_place_issued, array('class' => 'form-control','id'=>'applicant_place_issued')) }}
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
                                            <h6 class="sub-title accordiantitle capitalize-me">{{__("WITH MY CONSENT LOT OWNER")}}</h6>
                                        </button>
                                    </h6>
                                     <div class="row">
                                         <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('efa_owner_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('efa_owner_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('efa_owner_id',$arrlotOwner,$fencingappdata->efa_owner_id, array('class' => 'form-control','id'=>'efa_owner_id')) }}
                                                <span class="validate-err" id="err_efa_owner_id"></span>
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
                                                    {{ Form::text('owneraddress',$fencingappdata->owneraddress, array('class' => 'form-control','id'=>'owneraddress')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ctcno', __('C.T.C. No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ctcno') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('owner_comtaxcert',$fencingappdata->owner_comtaxcert, array('class' => 'form-control','id'=>'owner_comtaxcert')) }}
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
                                                    {{ Form::date('owner_date_issued',$fencingappdata->owner_date_issued, array('class' => 'form-control','id'=>'owner_date_issued')) }}
                                                <span class="validate-err" id="err_dateissued"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('placeissued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('placeissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ownerplaceissued',$fencingappdata->ownerplaceissued, array('class' => 'form-control','id'=>'ownerplaceissued')) }}
                                                <span class="validate-err" id="err_placeissued"></span>
                                              </div>
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
                                             <h6 class="sub-title accordiantitle capitalize-me">{{__("For Notary")}}</h6>
                                        </button>
                                    </h6>
                                </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('applicant', __('Applicant'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('validity') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('applicantnamenew',$fencingappdata->applicantnamenew, array('class' => 'form-control','id'=>'applicantname')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('address', __('Address'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ctcno') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('applicantaddressnew',$fencingappdata->applicantaddressnew, array('class' => 'form-control','id'=>'applicantaddressnew'))}}
                                                <span class="validate-err" id="err_ctcno"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('ctcno', __('C.T.C. No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('dateissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ctcnonew',$fencingappdata->ctcnonew, array('class' => 'form-control numeric-only','id'=>'appctcno')) }}
                                                <span class="validate-err" id="err_dateissued"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('dateissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('dateissuednew',$fencingappdata->dateissuednew, array('class' => 'form-control numeric-only','id'=>'applicantdateissued')) }}
                                                <span class="validate-err" id="err_dateissued"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('placeissued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('placeissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('placeissuednew',$fencingappdata->placeissuednew, array('class' => 'form-control','id'=>'applicantplaceissued')) }}
                                                <span class="validate-err" id="err_placeissued"></span>
                                              </div>
                                           </div>
                                       </div>
                            </div>
                             <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('applicant', __('Liancened Architect Or Civil Engineer'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('validity') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('liancnedapplicant',$fencingappdata->liancnedapplicant, array('class' => 'form-control','id'=>'aplicantname')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('address', __('Address'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ctcno') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('liancnedaddress',$fencingappdata->liancnedaddress, array('class' => 'form-control','id'=>'archaddress'))}}
                                                <span class="validate-err" id="err_ctcno"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('ctcno', __('C.T.C. No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('dateissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('liancnedctcno',$fencingappdata->liancnedctcno, array('class' => 'form-control numeric-only','id'=>'architectctcno')) }}
                                                <span class="validate-err" id="err_dateissued"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('dateissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('liancneddateissued',$fencingappdata->liancneddateissued, array('class' => 'form-control numeric-only','id'=>'archidateissued')) }}
                                                <span class="validate-err" id="err_dateissued"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('placeissued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('placeissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('liancnedplaceissued',$fencingappdata->liancnedplaceissued, array('class' => 'form-control','id'=>'archiplaceissued')) }}
                                                <span class="validate-err" id="err_placeissued"></span>
                                              </div>
                                           </div>
                                       </div>
                            </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                            <a href="#" data-dismiss="modal" class="btn closeFencingModal" mid=""  type="edit">Close</a>
                            <a  class="btn btn-primary nextpageModal" id="nextpage">Next</a> 
                            <!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
                        </div>
                       </div> 
                    <div id="page2" style="display:none;">
                           <div class="col-sm-12">
                            <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                        </button>
                                    </h6>
                                </div>
                                <div class="row">
                                     {{ Form::label('measurements', __('Measurements'),['class'=>'form-label bold']) }}
                                    <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('measurelength', __('Length Meters'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('measurelength') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('measurelength',$fencingappdata->measurelength, array('class' => 'form-control numeric-double','id'=>'measurelength','required'=>'required')) }}
                                                <span class="validate-err" id="err_measurelength"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('measureheight', __('Height Meters'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('measureheight') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('measureheight',$fencingappdata->measureheight, array('class' => 'form-control numeric-double','id'=>'measureheight')) }}
                                                <span class="validate-err" id="err_measureheight"></span>
                                              </div>
                                           </div>
                                       </div>
                                </div>
                                <div class="row">
                                   <div class="col-sm-12">
                                         {{ Form::label('typeoffencing', __('Type of Fencing'),['class'=>'form-label']) }}
                                      <div class="row">
                                       @foreach($arrtypeofFencing as  $key => $val)
                                        <div class="col-md-4">
                                               <div class="form-check form-check-inline form-group col-md-12">
                                                {{ Form::radio('typeoffencing', $key,( $fencingappdata->typeoffencing == $key)?true:false, array('id'=>'typeoffencing'.$key,'class'=>'form-check-input code')) }}
                                                {{ Form::label('typeoffencing'.$key, __($val),['class'=>'form-label']) }}
                                            </div>
                                        </div>  
                                      @endforeach
                                     </div>
                                    </div>
                                    <span class="validate-err" id="err_esit_id"></span>
                                </div>
                            </div>
                        </div>
                           
                          <div class="col-sm-12">
                               <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                            <h6 class="sub-title accordiantitle">{{__("Assessed Fees")}}</h6>
                                        </button>
                                    </h6>
                                     <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('amounr_due', __('AMOUNT DUE'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                       <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('assesssedby', __('ASSESSED BY'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('ornumber', __('O.R NUMBER'),['class'=>'form-label bold']) }}
                                        </div>
                                       </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                {{ Form::label('datepaid', __('DATE PAID'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                   </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                 {{ Form::label('landuse', __('Line and Grade'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                               <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('efa_linegrade_amount',$fencingappdata->efa_linegrade_amount, array('class' => 'form-control numeric-double','id'=>'efa_linegrade_amount')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="errefa_linegrade_amount"></span>
                                             </div>
                                           </div>
                                       </div>
                                       <div class="col-md-3">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::select('efa_linegrade_processed_by',$hremployees,$fencingappdata->efa_linegrade_processed_by, array('class' => 'form-control','id'=>'efa_linegrade_processed_by')) }}
                                                    <span class="validate-err" id="err_efa_linegrade_processed_by"></span>
                                                </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{ Form::text('efa_linegrade_or_no',$fencingappdata->efa_linegrade_or_no, array('class' => 'form-control disabled-field','id'=>'efa_linegrade_or_no')) }}
                                                    <span class="validate-err" id="err_efa_linegrade_or_no"></span>
                                                </div>
                                           </div>
                                       </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::date('efa_linegrade_date_paid',$fencingappdata->efa_linegrade_date_paid, array('class' => 'form-control disabled-field','id'=>'efa_linegrade_date_paid')) }}
                                                    <span class="validate-err" id="err_efa_linegrade_date_paid"></span>
                                                </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                 {{ Form::label('landuse', __('Fencing'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                               <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('efa_fencing_amount',$fencingappdata->efa_fencing_amount, array('class' => 'form-control numeric-double','id'=>'efa_fencing_amount')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_efa_fencing_amount"></span>
                                             </div>
                                           </div>
                                       </div>
                                       <div class="col-md-3">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::select('efa_fencing_processed_by',$hremployees,$fencingappdata->efa_fencing_processed_by, array('class' => 'form-control','id'=>'efa_fencing_processed_by')) }}
                                                    <span class="validate-err" id="err_efa_fencing_processed_by"></span>
                                                </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{ Form::text('efa_fencing_or_no',$fencingappdata->efa_fencing_or_no, array('class' => 'form-control disabled-field','id'=>'efa_fencing_or_no')) }}
                                                    <span class="validate-err" id="err_efa_fencing_or_no"></span>
                                                </div>
                                           </div>
                                       </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::date('efa_fencing_date_paid',$fencingappdata->efa_fencing_date_paid, array('class' => 'form-control disabled-field','id'=>'efa_fencing_date_paid')) }}
                                                    <span class="validate-err" id="err_efa_fencing_date_paid"></span>
                                                </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                 {{ Form::label('landuse', __('Electrical '),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                               <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('efa_electrical_amount',$fencingappdata->efa_electrical_amount, array('class' => 'form-control numeric-double','id'=>'efa_electrical_amount')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_es_id"></span>
                                             </div>
                                           </div>
                                       </div>
                                       <div class="col-md-3">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::select('efa_electrical_processed_by',$hremployees,$fencingappdata->efa_electrical_processed_by, array('class' => 'form-control ','id'=>'efa_electrical_processed_by')) }}
                                                    <span class="validate-err" id="err_efa_electrical_processed_by"></span>
                                                </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{ Form::text('efa_electrical_or_no',$fencingappdata->efa_electrical_or_no, array('class' => 'form-control disabled-field','id'=>'efa_electrical_or_no')) }}
                                                    <span class="validate-err" id="err_efa_electrical_or_no"></span>
                                                </div>
                                           </div>
                                       </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::date('efa_electrical_date_paid',$fencingappdata->efa_electrical_date_paid, array('class' => 'form-control disabled-field','id'=>'efa_electrical_date_paid')) }}
                                                    <span class="validate-err" id="err_efa_electrical_date_paid"></span>
                                                </div>
                                           </div>
                                       </div>
                                   </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                 {{ Form::label('others', __('Others'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                               <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('efa_others_amount',$fencingappdata->efa_others_amount, array('class' => 'form-control numeric-double','id'=>'efa_others_amount')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_efa_others_amount"></span>
                                             </div>
                                           </div>
                                       </div>
                                       <div class="col-md-3">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::select('efa_others_processed_by',$hremployees,$fencingappdata->efa_others_processed_by, array('class' => 'form-control ','id'=>'efa_others_processed_by')) }}
                                                    <span class="validate-err" id="err_efa_others_processed_by"></span>
                                                </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{ Form::text('efa_others_or_no',$fencingappdata->efa_others_or_no, array('class' => 'form-control disabled-field','id'=>'efa_others_or_no')) }}
                                                    <span class="validate-err" id="err_efa_others_or_no"></span>
                                                </div>
                                           </div>
                                       </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::date('efa_others_date_paid',$fencingappdata->efa_others_date_paid, array('class' => 'form-control disabled-field','id'=>'efa_others_date_paid')) }}
                                                    <span class="validate-err" id="err_efa_others_date_paid"></span>
                                                </div>
                                           </div>
                                       </div>
                                   </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                 {{ Form::label('total', __('Total'),['class'=>'form-label bold']) }}
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                            <div class="form-group">
                                               <div class="form-icon-user">
                                                    <div class="form-icon-user currency">
                                                    {{ Form::text('efa_total_amount',$fencingappdata->efa_total_amount, array('class' => 'form-control numeric-double','id'=>'efa_total_amount','required'=>'required','readonly')) }}
                                                    <div class="currency-sign"><span>Php</span></div>
                                                </div>
                                                <span class="validate-err" id="err_efa_total_amount"></span>
                                             </div>
                                           </div>
                                       </div>
                                       <div class="col-md-3">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::select('efa_total_processed_by',$hremployees,$fencingappdata->efa_total_processed_by, array('class' => 'form-control ','id'=>'efa_total_processed_by')) }}
                                                    <span class="validate-err" id="err_efa_total_processed_by"></span>
                                                </div>
                                           </div>
                                       </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{ Form::text('efa_total_or_no',$fencingappdata->efa_total_or_no, array('class' => 'form-control disabled-field','id'=>'efa_total_or_no')) }}
                                                    <span class="validate-err" id="err_efa_total_or_no"></span>
                                                </div>
                                           </div>
                                       </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                 <div class="form-icon-user">
                                                    {{ Form::date('efa_total_date_paid',$fencingappdata->efa_total_date_paid, array('class' => 'form-control ','id'=>'efa_total_date_paid')) }}
                                                    <span class="validate-err" id="err_efa_total_date_paid"></span>
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
                                            {{ Form::label('efa_building_official', __('Building Official Full Name'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('efa_building_official') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('efa_building_official',$buildofficial,$fencingappdata->efa_building_official, array('class' => 'form-control','id'=>'efa_building_official','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_efa_building_official"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        <div class="modal-footer">
                        <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                        <a href="#" data-dismiss="modal" class="btn closeFencingModal" mid=""  type="edit">Close</a>
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
                        $("#efa_sign_category").select3({ dropdownAutoWidth: false });
                        $("#efa_inspector_category").select3({ dropdownAutoWidth: false });
                        select3Ajax("efa_linegrade_processed_by", "FencingPermit", "getClientsBfpAjax");
                        select3Ajax("efa_fencing_processed_by", "FencingPermit", "getClientsBfpAjax");
                        select3Ajax("efa_electrical_processed_by", "FencingPermit", "getClientsBfpAjax");
                        select3Ajax("efa_others_processed_by", "FencingPermit", "getClientsBfpAjax");
                        select3Ajax("efa_total_processed_by", "FencingPermit", "getClientsBfpAjax");
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

