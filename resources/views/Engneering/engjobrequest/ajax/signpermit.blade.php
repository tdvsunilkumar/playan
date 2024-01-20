{{Form::open(array('name'=>'forms','url'=>'jobrequest/storesigngpermit','method'=>'post','id'=>'storesigngpermit'))}}
 {{ Form::hidden('jobrequest_id',(isset($JobRequest->id))?$JobRequest->id:NULL, array('id' => 'jobrequest_id')) }}
 {{ Form::hidden('application_id',(isset($signappdata->id))?$signappdata->id:NULL, array('id' => 'signappdata')) }}
 {{ Form::hidden('session_id',(isset($sessionId))?$sessionId:'', array('id' => 'session_id')) }}
   {{ Form::hidden('p_code',(isset($pcode))?$pcode:'', array('id' => 'p_code')) }}
 <style type="text/css">
     .row{ padding: 0px 10px; }
 </style>
<div class="modal-header">
                    <h4 class="modal-title">Sign Permit Application</h4>
                        <a class="close closeSignModal" mid="" type="edit" data-dismiss="modal" aria-hidden="true" style="cursor:pointer;">X</a>
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
                                                {{ Form::select('mun_no',$GetMuncipalities,$signappdata->mun_no, array('class' => 'form-control mun_no disabled-field','id'=>'ebpa_mun_no','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_mum_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('esa_application_no', __('Application No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('esa_application_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('esa_application_no',$signappdata->esa_application_no, array('class' => 'form-control disabled-field esa_application_no','id'=>'esa_application_no','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_esa_application_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group" id="permitnodiv">
                                            {{ Form::label('ebpa_permit_no', __('Building Permit No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_permit_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('ebpa_permit_no',$arrPermitno,$signappdata->ebpa_permit_no, array('class' => 'form-control ebpa_permit_no','id'=>'ebpa_permit_no')) }}
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
                                                {{ Form::text('ebpa_owner_last_name',$signappdata->rpo_custom_last_name, array('class' => 'form-control ebpa_owner_last_name','id'=>'ebpa_owner_last_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_last_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_first_name', __('First Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ownerfirstname') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_first_name',$signappdata->rpo_first_name, array('class' => 'form-control ebpa_owner_first_name','id'=>'ebpa_owner_first_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_first_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_mid_name', __('Middle Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ebpa_owner_mid_name') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_mid_name',$signappdata->rpo_middle_name, array('class' => 'form-control ebpa_owner_mid_name','id'=>'ebpa_owner_mid_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_mid_name"></span>
                                        </div>
                                    </div>
                                      <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_suffix_name', __('Suffix'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('suffix') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_suffix_name',$signappdata->suffix, array('class' => 'form-control suffix','id'=>'suffix')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_suffix_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('taxaccno', __('Tax Acct. No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_tax_acct_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('taxaccno',$signappdata->taxaccno, array('class' => 'form-control taxaccno','id'=>'taxaccno')) }}
                                            </div>
                                            <span class="validate-err" id="taxaccno"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('esa_form_of_own', __('Form Of Ownership'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('esa_form_of_own') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('esa_form_of_own',$signappdata->esa_form_of_own, array('class' => 'form-control esa_form_of_own','id'=>'esa_form_of_own')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_form_of_own"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_economic_act', __('Main Economic Activity/Kind Business'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_economic_act') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('esa_economic_act',$signappdata->esa_economic_act, array('class' => 'form-control ebpa_economic_act','id'=>'ebpa_economic_act')) }}
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
                                            <span class="validate-err">{{ $errors->first('appbrgy_code') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('appbrgy_code','', array('class' => 'form-control disabled-field appbrgy_code','id'=>'appbrgy_code')) }}
                                            </div>
                                            <span class="validate-err" id="err_brgy_code"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_location', __('City / Municipality of'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_location') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_location',$signappdata->ebpa_location, array('class' => 'form-control ema_location','id'=>'ebpa_location')) }}
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
                                            {{ Form::label('taxdecno', __('Tax Dec. No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('tdno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('taxdecno',$signappdata->taxdecno, array('class' => 'form-control  tdno','id'=>'tdno')) }}
                                            </div>
                                            <span class="validate-err" id="err_tdno"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('totno', __('TCT No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('totno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('totno',$signappdata->totno, array('class' => 'form-control  totno','id'=>'totno')) }}
                                            </div>
                                            <span class="validate-err" id="err_Street"></span>
                                        </div>
                                    </div>
                                     
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('lotno', __('LOT No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('lotno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('lotno',$signappdata->lotno, array('class' => 'form-control  lotno','id'=>'lotno')) }}
                                            </div>
                                            <span class="validate-err" id="err_Street"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('blkno', __('BLK No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('blkno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('blkno',$signappdata->blkno, array('class' => 'form-control  blkno','id'=>'blkno')) }}
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
                                                {{ Form::text('Street',$signappdata->Street, array('class' => 'form-control  Street','id'=>'Street')) }}
                                            </div>
                                            <span class="validate-err" id="err_Street"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('locappbrgy_code', __('Barangay'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('locappbrgy_code') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('locappbrgy_code',$signappdata->locbarangay, array('class' => 'form-control disabled-field locappbrgy_code','id'=>'locappbrgy_code')) }}
                                            </div>
                                            <span class="validate-err" id="err_locappbrgy_code"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('locebpa_location', __('City / Municipality of'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('locebpa_location') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('locebpa_location',$signappdata->locmunicipality, array('class' => 'form-control disabled-field','id'=>'locebpa_location')) }}
                                            </div>
                                            <span class="validate-err" id="err_locebpa_location"></span>
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
                                 <div class="row" >
                                    <div class="col-md-4">
                                        <div class="form-group" id="ebs_id_group">
                                            {{ Form::label('ebs_id', __('Scope of Work'),['class'=>'form-label bold']) }}
                                            <span class="validate-err">{{ $errors->first('ebs_id') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('ebs_id',$arrbuildingScope,$signappdata->ebs_id, array('class' => 'form-control','id'=>'ebs_id')) }}
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
                                        <h6 class="sub-title accordiantitle">{{__("USE OR CHARACTER OF OCCUPANCY")}}</h6>
                                    </button>
                                </h6>
                                 <div class="row">
                                    <div class="col-sm-12">
                                         {{ Form::label('typeofdisplay', __('A. Type of Display'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                      <div class="row">
                                       @foreach($arrsigndisplaytype as  $key => $val)
                                        <div class="col-md-4">
                                               <div class="form-check form-check-inline form-group col-md-12">
                                                {{ Form::radio('esdt_id', $key,($signappdata->esdt_id == $key)?true:false, array('id'=>'esdt_id'.$key,'class'=>'form-check-input code')) }}
                                                {{ Form::label('esdt_id'.$key, __($val),['class'=>'form-label']) }}
                                            </div>
                                        </div>  
                                      @endforeach
                                     </div>
                                     <span class="validate-err" id="err_esdt_id"></span>
                                    </div>
                                    
                                </div>
                                 <div class="row">
                                   <div class="col-sm-12">
                                         {{ Form::label('typeofdisplay', __('B. Type of Installation'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                      <div class="row">
                                       @foreach($arrsignInstllationtype as  $key => $val)
                                        <div class="col-md-4">
                                               <div class="form-check form-check-inline form-group col-md-12">
                                                {{ Form::radio('esit_id', $key,($signappdata->esit_id == $key)?true:false, array('id'=>'esit_id'.$key,'class'=>'form-check-input code')) }}
                                                {{ Form::label('esit_id'.$key, __($val),['class'=>'form-label']) }}
                                            </div>
                                        </div>  
                                      @endforeach
                                     </div>
                                     <span class="validate-err" id="err_esit_id"></span>
                                    </div>
                                    
                                </div>
                                 <div class="row">
                                   <div class="col-sm-12">
                                         {{ Form::label('dispalysize', __('C. Display Size/Face'),['class'=>'form-label']) }}
                                      <div class="row">
                                        <div class="col-md-4">
                                               <div class="form-group">
                                                {{ Form::label('length', __('L(m)*'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('length') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('length',$signappdata->length, array('class' => 'form-control  length','id'=>'length')) }}
                                                </div>
                                                <span class="validate-err" id="err_length"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                               <div class="form-group">
                                                {{ Form::label('width', __('W(m)*'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('width') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('width',$signappdata->width, array('class' => 'form-control  width','id'=>'width')) }}
                                                </div>
                                                <span class="validate-err" id="err_width"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                               <div class="form-group">
                                                {{ Form::label('alllengthwidth', __('All(m)*'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('alllengthwidth') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('alllengthwidth',$signappdata->alllengthwidth, array('class' => 'form-control  alllengthwidth','id'=>'alllengthwidth')) }}
                                                </div>
                                                <span class="validate-err" id="err_alllengthwidth"></span>
                                            </div>
                                        </div>
                                     </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                  
                        <div class="modal-footer">
                        <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                            <a href="#" data-dismiss="modal" class="btn closeSignModal" mid=""  type="edit">Close</a>
                            <a  class="btn btn-primary nextpageModal" id="nextpage">Next</a> 
                            <!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
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
                                             <span class="btn_electricalrevision btn btn-primary hide" id="btn_electricalrevision" style="color:white;float: right;padding: 5px;"><i class="ti-plus"></i></span></a>
                                         </div>
                                        </div>
                                        </button>
                                    </h6>
                                     <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('esa_sign_category', __('Consultant Category'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('esa_sign_category') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('esa_sign_category',array(''=>'Please Select','1'=>'Employee','2'=>'External Consultant'),$signappdata->esa_sign_category, array('class' => 'form-control numeric-only','id'=>'esa_sign_category')) }}
                                                <span class="validate-err" id="err_esa_sign_category"></span>
                                              </div>
                                           </div>
                                       </div>
                                         <div class="col-md-8">
                                            <div class="form-group">
                                                {{ Form::label('esa_sign_consultant_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('esa_sign_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('esa_sign_consultant_id',$signdropdown,$signappdata->esa_sign_consultant_id, array('class' => 'form-control','id'=>'esa_sign_consultant_id')) }}
                                                <span class="validate-err" id="err_esa_sign_consultant_id"></span>
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
                                                    {{ Form::text('signaddress',$signappdata->signaddress, array('class' => 'form-control','id'=>'signaddress')) }}
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
                                                    {{ Form::text('signprcno',$signappdata->signprcno, array('class' => 'form-control numeric-only','id'=>'signprcno')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('validity', __('Validity'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('validity') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('signvalidity',$signappdata->signvalidity, array('class' => 'form-control','id'=>'signvalidity')) }}
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
                                                    {{ Form::text('signptrno',$signappdata->signptrno, array('class' => 'form-control ','id'=>'signebfd_sign_ptr_no')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('signdateissued',$signappdata->signdateissued, array('class' => 'form-control','id'=>'signdateissued')) }}
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
                                                    {{ Form::text('signplaceissued',$signappdata->signplaceissued, array('class' => 'form-control','id'=>'signplaceissued')) }}
                                                <span class="validate-err" id="err_tin"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('tin', __('TIN'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('signtin',$signappdata->signtin, array('class' => 'form-control ','id'=>'signtin')) }}
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
                                            <!-- <h6 class="sub-title accordiantitle" style="padding-top: 11px;">{{__("FULL TIME INSPECTOR AND SUPERVISOR OF CONSTRUCTION WORK")}}</h6> -->
                                            <div class="row" style="width: 100%;padding-right: 0px;padding-left: 0px;">
                                        <div class="col-md-11">
                                            <h6 class="sub-title accordiantitle " style="padding-top: 12px;">{{__("FULL TIME INSPECTOR AND SUPERVISOR OF CONSTRUCTION WORK")}}</h6>
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
                                                {{ Form::label('esa_incharge_category', __('Consultant Category'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('esa_incharge_category') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('esa_incharge_category',array(''=>'Please Select','1'=>'Employee','2'=>'External Consultant'),$signappdata->esa_incharge_category, array('class' => 'form-control ','id'=>'esa_incharge_category')) }}
                                                <span class="validate-err" id="err_esa_inspector_category"></span>
                                              </div>
                                           </div>
                                       </div>
                                         <div class="col-md-8">
                                            <div class="form-group">
                                                {{ Form::label('esa_incharge_consultant_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('esa_incharge_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('esa_incharge_consultant_id',$inchargedropdown,$signappdata->esa_incharge_consultant_id, array('class' => 'form-control esa_incharge_consultant_id','id'=>'esa_incharge_consultant_id')) }}
                                                <span class="validate-err" id="err_esa_incharge_consultant_id"></span>
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
                                                    {{ Form::text('inchargenaddress',$signappdata->inchargenaddress, array('class' => 'form-control','id'=>'inchargenaddress')) }}
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
                                                    {{ Form::text('inchargeprcregno',$signappdata->inchargeprcregno, array('class' => 'form-control','id'=>'inchargeprcregno')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('validity', __('Validity'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('validity') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('inchargevalidity',$signappdata->inchargevalidity, array('class' => 'form-control','id'=>'inchargevalidity')) }}
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
                                                    {{ Form::text('inchargeptrno',$signappdata->inchargeptrno, array('class' => 'form-control ','id'=>'inchargeebfd_sign_ptr_no')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('inchargedateissued', __('Date Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('inchargedateissued',$signappdata->inchargedateissued, array('class' => 'form-control','id'=>'inchargedateissued')) }}
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
                                                    {{ Form::text('inchargeplaceissued',$signappdata->inchargeplaceissued, array('class' => 'form-control','id'=>'inchargeplaceissued')) }}
                                                <span class="validate-err" id="err_tin"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('tin', __('TIN'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('inchargetin',$signappdata->inchargetin, array('class' => 'form-control ','id'=>'inchargetin')) }}
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
                                                {{ Form::label('esa_applicant_consultant_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('esa_applicant_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('esa_applicant_consultant_id',$arrlotOwner,$signappdata->esa_applicant_consultant_id, array('class' => 'form-control esa_applicant_consultant_id','id'=>'esa_applicant_consultant_id')) }}
                                                <span class="validate-err" id="err_esa_applicant_consultant_id"></span>
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
                                                    {{ Form::text('applicantaddress',$signappdata->applicantaddress, array('class' => 'form-control','id'=>'applicantaddress')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ctcno', __('CTC No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ctcno') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('applicant_comtaxcert',$signappdata->applicant_comtaxcert, array('class' => 'form-control','id'=>'applicant_comtaxcert')) }}
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
                                                    {{ Form::date('applicant_date_issued',$signappdata->applicant_date_issued, array('class' => 'form-control','id'=>'applicant_date_issued')) }}
                                                <span class="validate-err" id="err_dateissued"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('placeissued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('placeissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('applicant_place_issued',$signappdata->applicant_place_issued, array('class' => 'form-control','id'=>'applicant_place_issued')) }}
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
                                                {{ Form::label('esa_owner_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('esa_owner_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('esa_owner_id',$arrlotOwner,$signappdata->esa_owner_id, array('class' => 'form-control esa_owner_id','id'=>'esa_owner_id')) }}
                                                <span class="validate-err" id="err_esa_owner_id"></span>
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
                                                    {{ Form::text('owneraddress',$signappdata->owneraddress, array('class' => 'form-control','id'=>'owneraddress')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ctcno', __('CTC No.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ctcno') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('owner_comtaxcert',$signappdata->owner_comtaxcert, array('class' => 'form-control','id'=>'owner_comtaxcert')) }}
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
                                                    {{ Form::date('owner_date_issued',$signappdata->owner_date_issued, array('class' => 'form-control','id'=>'owner_date_issued')) }}
                                                <span class="validate-err" id="err_dateissued"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('placeissued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('placeissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ownerplaceissued',$signappdata->ownerplaceissued, array('class' => 'form-control','id'=>'ownerplaceissued')) }}
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
                                            {{ Form::label('esa_building_official', __('Building Official Full Name'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('esa_building_official') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('esa_building_official',$buildofficial,$signappdata->esa_building_official, array('class' => 'form-control','id'=>'esa_building_official','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_esa_building_official"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        <div class="modal-footer">
                        <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                        <a href="#" data-dismiss="modal" class="btn closeSignModal" mid=""  type="edit">Close</a>
                         <a  class="btn btn-primary previouspageModal" id="previouspageModal">Previous</a> 
                        <button class="btn btn-primary" id="saveServiceDetails">Save Changes</button>
                        <!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
                        </div>
                    </div>
                  
                 {{Form::close()}}
                 <script type="text/javascript">
                     $(document).ready(function(){
                       $("#ebs_id").select3({ dropdownAutoWidth: false });
                       $("#esa_sign_category").select3({ dropdownAutoWidth: false });
                       $("#esa_incharge_category").select3({ dropdownAutoWidth: false });
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