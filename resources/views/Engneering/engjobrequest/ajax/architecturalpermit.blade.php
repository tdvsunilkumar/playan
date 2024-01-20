{{Form::open(array('name'=>'forms','url'=>'jobrequest/storearchitecturalpermit','method'=>'post','id'=>'storearchitecturalpermit'))}}
 {{ Form::hidden('jobrequest_id',(isset($JobRequest->id))?$JobRequest->id:NULL, array('id' => 'jobrequest_id')) }}
 {{ Form::hidden('application_id',(isset($architecturalappdata->id))?$architecturalappdata->id:NULL, array('id' => 'architecturalappdata')) }}
 {{ Form::hidden('session_id',(isset($sessionId))?$sessionId:'', array('id' => 'session_id')) }}
 {{ Form::hidden('p_code',(isset($pcode))?$pcode:'', array('id' => 'p_code')) }}
 <style type="text/css">
     .row{ padding: 0px 10px; }
 </style>
<div class="modal-header">
                    <h4 class="modal-title">Architectural Permit Application</h4>
                        <a class="close closeArchitecturalModal" mid="" type="edit" data-dismiss="modal" aria-hidden="true" style="cursor:pointer;">X</a>
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
                                                {{ Form::select('mum_no',$GetMuncipalities,$architecturalappdata->mum_no, array('class' => 'form-control mum_no disabled-field','id'=>'ebpa_mun_no','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_mum_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('eea_application_no', __('Application No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('eea_application_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('eea_application_no',$architecturalappdata->eea_application_no, array('class' => 'form-control disabled-field eea_application_no','id'=>'eea_application_no','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_eea_application_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group" id="permitnodiv">
                                            {{ Form::label('ebpa_permit_no', __('Building Permit No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_permit_no') }}</span>
                                            <div class="form-icon-user">
                                            {{ Form::select('ebpa_permit_no',$arrPermitno,$architecturalappdata->ebpa_permit_no, array('class' => 'form-control ebpa_permit_no','id'=>'ebpa_permit_no')) }}
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
                                        <h6 class="sub-title accordiantitle">{{__("Business Permit Details")}}</h6>
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
                                            <span class="validate-err">{{ $errors->first('p_code') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_last_name',$architecturalappdata->rpo_custom_last_name, array('class' => 'form-control p_code','id'=>'ebpa_owner_last_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_p_code"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_first_name', __('First Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ownerfirstname') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_first_name',$architecturalappdata->rpo_first_name, array('class' => 'form-control ebpa_owner_first_name','id'=>'ebpa_owner_first_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_first_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_mid_name', __('Middle Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('ebpa_owner_mid_name') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_mid_name',$architecturalappdata->rpo_middle_name, array('class' => 'form-control ebpa_owner_mid_name','id'=>'ebpa_owner_mid_name','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_mid_name"></span>
                                        </div>
                                    </div>
                                      <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_owner_suffix_name', __('Suffix'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('suffix') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_owner_suffix_name',$architecturalappdata->suffix, array('class' => 'form-control suffix','id'=>'suffix')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_owner_suffix_name"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('eea_tax_acct_no', __('Tax Acct. No.'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('eea_tax_acct_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('eea_tax_acct_no',$architecturalappdata->eea_tax_acct_no, array('class' => 'form-control eea_tax_acct_no','id'=>'eea_tax_acct_no')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_tax_acct_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('eea_form_of_own', __('Form Of Ownership'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('eea_form_of_own') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('eea_form_of_own',$architecturalappdata->eea_form_of_own, array('class' => 'form-control eea_form_of_own','id'=>'eea_form_of_own')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_form_of_own"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('eea_economic_act', __('Main Economic Activity/Kind Business'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('eea_economic_act') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('eea_economic_act',$architecturalappdata->eea_economic_act, array('class' => 'form-control eea_economic_act','id'=>'eea_economic_act')) }}
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
                                            {{ Form::label('eea_location', __('City / Municipal of'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('eea_location') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('eea_location',$architecturalappdata->eea_location, array('class' => 'form-control eea_location','id'=>'eea_location')) }}
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
                                                <h6 class="sub-title accordiantitle">{{__("Location of Construction")}}</h6>
                                            </button>
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    {{ Form::label('tdno', __('Tax Dec No.'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('tdno') }}</span>
                                                    <div class="form-icon-user">
                                                        {{ Form::text('taxdecno',$architecturalappdata->taxdecno, array('class' => 'form-control  tdno','id'=>'tdno')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_tdno"></span>
                                                </div>
                                            </div> 
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    {{ Form::label('totno', __('TCT NO.'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('totno') }}</span>
                                                    <div class="form-icon-user">
                                                        {{ Form::text('totno',$architecturalappdata->totno, array('class' => 'form-control  totno','id'=>'totno')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_Street"></span>
                                                </div>
                                            </div>
                                             
                                             <div class="col-md-2">
                                                <div class="form-group">
                                                    {{ Form::label('lotno', __('LOT NO.'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('lotno') }}</span>
                                                    <div class="form-icon-user">
                                                        {{ Form::text('lotno',$architecturalappdata->lotno, array('class' => 'form-control lotno','id'=>'lotno')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_Street"></span>
                                                </div>
                                            </div>
                                             <div class="col-md-2">
                                                <div class="form-group">
                                                    {{ Form::label('blkno', __('BLK NO.'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('blkno') }}</span>
                                                    <div class="form-icon-user">
                                                        {{ Form::text('blkno',$architecturalappdata->blkno, array('class' => 'form-control  blkno','id'=>'blkno')) }}
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
                                                        {{ Form::text('Street',$architecturalappdata->Street, array('class' => 'form-control  Street','id'=>'Street')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_Street"></span>
                                                </div>
                                            </div>
                                             <div class="col-md-4">
                                                <div class="form-group">
                                                    {{ Form::label('locappbrgy_code', __('Barangay'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('locappbrgy_code') }}</span>
                                                    <div class="form-icon-user">
                                                        {{ Form::text('locappbrgy_code',$architecturalappdata->locbarangay, array('class' => 'form-control disabled-field locappbrgy_code','id'=>'locappbrgy_code')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_brgy_code"></span>
                                                </div>
                                            </div>
                                             <div class="col-md-4">
                                                <div class="form-group">
                                                    {{ Form::label('loceea_location', __('City / Municipal of'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                    <span class="validate-err">{{ $errors->first('eea_location') }}</span>
                                                    <div class="form-icon-user">
                                                        {{ Form::text('loceea_location',$architecturalappdata->locmunicipality, array('class' => 'form-control disabled-field','id'=>'loceea_location')) }}
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('ebs_id', __('Scope of Work'),['class'=>'form-label bold']) }}
                                            <span class="validate-err">{{ $errors->first('ebs_id') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('ebs_id',$arrbuildingScope,$architecturalappdata->ebs_id, array('class' => 'form-control','id'=>'ebs_id')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebs_id"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-8">
                                        <div class="form-group">
                                            {{ Form::label('ebpa_scope_reearks', __('Other Remarks'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('ebpa_scope_reearks') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('ebpa_scope_reearks','', array('class' => 'form-control ','id'=>'ebpa_scope_reearks')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpa_scope_reearks"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-headingfive">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                        <h6 class="sub-title accordiantitle capitalize-me">{{__("USER OR CHARACTER OF OCCUPANCY")}}</h6>
                                    </button>
                                </h6>
                                 <div class="row">
                                    <div class="col-sm-12" style="padding-top: 10px;">
                                     {{ Form::label('label', __('ARCHITECTURAL FACILITIES AND OTHER FEATURES PURSUANT TO BATAS PAMBANSA BLANG 344, RECURRING CERTAIN BUILDING, INSTITUTIONS, ESTABLISHMENTS AND PUBLIC AND UTILITIES TO INSTALL FACILITIES AND OTHER DEVICES'),['class'=>'form-label bold']) }}
                                      <div class="row">
                                       @foreach($architecturefeaturetypearray as  $key => $val)
                                        <div class="col-md-3">
                                            @php  $idsofeeetid = explode(',',$architecturalappdata->eeft_id);
                                                     @endphp
                                               <div class="form-check form-check-inline form-group col-md-12">
                                                {{ Form::checkbox('eeft_id[]', $key,(in_array($key,$idsofeeetid))?true:false, array('id'=>'eeft_id'.$key,'class'=>'form-check-input code')) }}
                                                {{ Form::label('eeft_id'.$key, __($val),['class'=>'form-label']) }}
                                            </div>
                                        </div> 
                                      @endforeach
                                      <span class="validate-err" id="err_eeft_id"></span> 
                                     </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                     {{ Form::label('label', __('PERCENTAGE OF SITE OCCUPANCY'),['class'=>'form-label bold']) }}
                                      <div class="row">
                                           <div class="col-md-8">
                                                <div class="form-group">
                                                    {{ Form::label('percentageofbuilding', __('Percentage OF Building Footprint'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div c+lass="form-group">
                                                    <div class="form-icon-user">
                                                        {{ Form::text('eaa_footprint',$architecturalappdata->eaa_footprint, array('class' => 'form-control ','id'=>'eaa_footprint')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_eaa_footprint"></span>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="row">
                                           <div class="col-md-8">
                                                <div class="form-group">
                                                    {{ Form::label('eaa_impervious_area', __('Percentage OF Impervious Surface Area'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{ Form::text('eaa_impervious_area',$architecturalappdata->eaa_impervious_area, array('class' => 'form-control ','id'=>'eaa_impervious_area')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_eaa_impervious_area"></span>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="row">
                                           <div class="col-md-8">
                                                <div class="form-group">
                                                    {{ Form::label('eaa_unpaved_area', __('Percentage OF Unpaved Surface Area'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{ Form::text('eaa_unpaved_area',$architecturalappdata->eaa_unpaved_area, array('class' => 'form-control ','id'=>'eaa_unpaved_area')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_eaa_unpaved_area"></span>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="row">
                                           <div class="col-md-8">
                                                <div class="form-group">
                                                    {{ Form::label('eaa_others_percentage', __('OTHERS (Specify)'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{ Form::text('eaa_others_percentage',$architecturalappdata->eaa_others_percentage, array('class' => 'form-control ','id'=>'eaa_others_percentage')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_eaa_others_percentage"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                     {{ Form::label('label', __('CONFORMANCE TO FIRE CODE OF THE PHILIPPINES (P.D 1185)'),['class'=>'form-label bold']) }}
                                      <div class="row">
                                       @foreach($confirmancefirearray as  $key => $val)
                                        <div class="col-md-3">
                                            @php  $idsoectfc_id = explode(',',$architecturalappdata->ectfc_id);
                                                     @endphp
                                               <div class="form-check form-check-inline form-group col-md-12">
                                                {{ Form::checkbox('ectfc_id[]', $key,(in_array($key,$idsoectfc_id))?true:false, array('id'=>'ectfc_id','class'=>'form-check-input code')) }}
                                                {{ Form::label('ectfc_id'.$key, __($val),['class'=>'form-label']) }}
                                            </div>
                                        </div>  
                                      @endforeach
                                      <span class="validate-err" id="err_ectfc_id"></span>
                                     </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                            <a href="#" data-dismiss="modal" class="btn closeArchitecturalModal" mid=""  type="edit">Close</a>
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
                                            <!-- <h6 class="sub-title accordiantitle capitalize-me">{{__("DESIGN PROFESSIONAL, PLANS AND SPECIFICATION")}}</h6> -->
                                            
                                            <div class="row" style="width: 100%;padding-right: 0px;padding-left: 0px;">
                                        <div class="col-md-11">
                                            <h6 class="sub-title accordiantitle " style="padding-top: 12px;">{{__("DESIGN PROFESSIONAL, PLANS AND SPECIFICATION")}}</h6>
                                        </div>
                                        <div class="col-md-1" >
                                            <a href="{{ url('/eng/consultantexternal') }}" target="_blank">
                                             <span class="btn_electricalrevision btn btn-primary hide" id="btn_electricalrevision2" style="color:white;float: right;padding: 5px;"><i class="ti-plus"></i></span>
                                            </a>
                                         </div>
                                        </div>
                                        </button>
                                    </h6>
                                     <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('eea_sign_category', __('Consultant Category'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eea_sign_category') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eea_sign_category',array(''=>'Please Select','1'=>'Employee','2'=>'External Consultant'),$architecturalappdata->eea_sign_category, array('class' => 'form-control numeric-only','id'=>'eea_sign_category')) }}
                                                <span class="validate-err" id="err_eea_sign_category"></span>
                                              </div>
                                           </div>
                                       </div>
                                         <div class="col-md-8">
                                            <div class="form-group">
                                                {{ Form::label('eea_sign_consultant_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eea_sign_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eea_sign_consultant_id',$signdropdown,$architecturalappdata->eea_sign_consultant_id, array('class' => 'form-control','id'=>'eea_sign_consultant_id')) }}
                                                <span class="validate-err" id="err_eea_sign_consultant_id"></span>
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
                                                    {{ Form::text('signaddress',$architecturalappdata->signaddress, array('class' => 'form-control','id'=>'signaddress')) }}
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
                                                    {{ Form::text('signprcno',$architecturalappdata->signprcno, array('class' => 'form-control','id'=>'signprcno')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('validity', __('Validity'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('validity') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('signvalidity',$architecturalappdata->signvalidity, array('class' => 'form-control','id'=>'signvalidity')) }}
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
                                                    {{ Form::text('signptrno',$architecturalappdata->signptrno, array('class' => 'form-control numeric-only','id'=>'signebfd_sign_ptr_no')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('signdateissued',$architecturalappdata->signdateissued, array('class' => 'form-control','id'=>'signdateissued')) }}
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
                                                    {{ Form::text('signplaceissued',$architecturalappdata->signplaceissued, array('class' => 'form-control','id'=>'signplaceissued')) }}
                                                <span class="validate-err" id="err_tin"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('tin', __('TIN.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('signtin',$architecturalappdata->signtin, array('class' => 'form-control numeric-only','id'=>'signtin')) }}
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
                                            <!-- <h6 class="sub-title accordiantitle capitalize-me">{{__("SUPERVISIOR IN CHARGE OF ARCHITECTURAL WORK")}}</h6> -->
                                            <div class="row" style="width: 100%;padding-right: 0px;padding-left: 0px;">
                                            <div class="col-md-11">
                                            <h6 class="sub-title accordiantitle " style="padding-top: 12px;">{{__("SUPERVISIOR IN CHARGE OF ARCHITECTURAL WORK")}}</h6>
                                        </div>
                                        <div class="col-md-1" >
                                            <a href="{{ url('/eng/consultantexternal') }}" target="_blank">
                                             <span class="btn_electricalrevision btn btn-primary hide" id="btn_electricalrevision" style="color:white;float: right;padding: 5px;"><i class="ti-plus"></i></span>
                                             </a>
                                         </div>
                                        </div>
                                        </button>
                                    </h6>
                                     <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('eea_incharge_category', __('Consultant Category'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eea_incharge_category') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eea_incharge_category',array(''=>'Please Select','1'=>'Employee','2'=>'External Consultant'),$architecturalappdata->eea_incharge_category, array('class' => 'form-control numeric-only','id'=>'eea_incharge_category')) }}
                                                <span class="validate-err" id="err_eea_incharge_category"></span>
                                              </div>
                                           </div>
                                       </div>
                                         <div class="col-md-8">
                                            <div class="form-group">
                                                {{ Form::label('eea_incharge_consultant_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eea_incharge_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eea_incharge_consultant_id',$inchargedropdown,$architecturalappdata->eea_incharge_consultant_id, array('class' => 'form-control','id'=>'eea_incharge_consultant_id')) }}
                                                <span class="validate-err" id="err_eea_incharge_consultant_id"></span>
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
                                                    {{ Form::text('inchargenaddress',$architecturalappdata->inchargenaddress, array('class' => 'form-control','id'=>'inchargenaddress')) }}
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
                                                    {{ Form::text('inchargeprcregno',$architecturalappdata->inchargeprcregno, array('class' => 'form-control numeric-only','id'=>'inchargeprcregno')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('validity', __('Validity'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('validity') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('inchargevalidity',$architecturalappdata->inchargevalidity, array('class' => 'form-control','id'=>'inchargevalidity')) }}
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
                                                    {{ Form::text('inchargeptrno',$architecturalappdata->inchargeptrno, array('class' => 'form-control numeric-only','id'=>'inchargeebfd_sign_ptr_no')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::date('inchargedateissued',$architecturalappdata->inchargedateissued, array('class' => 'form-control','id'=>'inchargedateissued')) }}
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
                                                    {{ Form::text('inchargeplaceissued',$architecturalappdata->inchargeplaceissued, array('class' => 'form-control','id'=>'inchargeplaceissued')) }}
                                                <span class="validate-err" id="err_tin"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('tin', __('TIN.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('inchargetin',$architecturalappdata->inchargetin, array('class' => 'form-control','id'=>'inchargetin')) }}
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
                                            <h6 class="sub-title accordiantitle capitalize-me">{{__("BUILDING OWNER")}}</h6>
                                        </button>
                                    </h6>
                                     <div class="row">
                                         <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('eea_applicant_consultant_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eea_applicant_consultant_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eea_applicant_consultant_id',$arrlotOwner,$architecturalappdata->eea_applicant_consultant_id, array('class' => 'form-control','id'=>'eea_applicant_consultant_id')) }}
                                                <span class="validate-err" id="err_eea_applicant_consultant_id"></span>
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
                                                    {{ Form::text('applicantaddress',$architecturalappdata->applicantaddress, array('class' => 'form-control','id'=>'applicantaddress')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ctcno', __('CTC NO.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ctcno') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('applicant_comtaxcert',$architecturalappdata->applicant_comtaxcert, array('class' => 'form-control','id'=>'applicant_comtaxcert')) }}
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
                                                    {{ Form::date('applicant_date_issued',$architecturalappdata->applicant_date_issued, array('class' => 'form-control','id'=>'applicant_date_issued')) }}
                                                <span class="validate-err" id="err_dateissued"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('placeissued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('placeissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('applicant_place_issued',$architecturalappdata->applicant_place_issued, array('class' => 'form-control','id'=>'applicant_place_issued')) }}
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
                                                {{ Form::label('eea_owner_id', __('Name'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('eea_owner_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('eea_owner_id',$arrlotOwner,$architecturalappdata->eea_owner_id, array('class' => 'form-control','id'=>'eea_owner_id')) }}
                                                <span class="validate-err" id="err_eea_owner_id"></span>
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
                                                    {{ Form::text('owneraddress',$architecturalappdata->owneraddress, array('class' => 'form-control','id'=>'owneraddress')) }}
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                              </div>
                                           </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('ctcno', __('CTC NO.'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('ctcno') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ownerctcno',$architecturalappdata->ownerctcno, array('class' => 'form-control','id'=>'ctcoctno')) }}
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
                                                    {{ Form::date('owner_date_issued',$architecturalappdata->owner_date_issued, array('class' => 'form-control numeric-only','id'=>'owner_date_issued')) }}
                                                <span class="validate-err" id="err_dateissued"></span>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('placeissued', __('Place Issued'),['class'=>'form-label bold']) }}
                                                <span class="validate-err">{{ $errors->first('placeissued') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('ownerplaceissued',$architecturalappdata->ownerplaceissued, array('class' => 'form-control','id'=>'ownerplaceissued')) }}
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
                                        <h6 class="sub-title accordiantitle capitalize-me">{{__("Building Official")}}</h6>
                                    </button>
                                </h6>
                                 <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('eea_building_official', __('Building Official Full Name'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('eea_building_official') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('eea_building_official',$buildofficial,$architecturalappdata->eea_building_official, array('class' => 'form-control','id'=>'eea_building_official')) }}
                                            </div>
                                            <span class="validate-err" id="err_eea_building_official"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        <div class="modal-footer">
                        <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                        <a href="#" data-dismiss="modal" class="btn closeArchitecturalModal" mid=""  type="edit">Close</a>
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