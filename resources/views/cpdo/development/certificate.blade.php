{{ Form::open(array('url' => 'cpdodevelopmentapp/certification','class'=>'formDtls','id'=>'storeJobService','enctype'=>'multipart/form-data')) }}
{{ Form::hidden('certid',$certificatedata->id, array('certid' => 'id')) }}
{{ Form::hidden('caf_id',$certificatedata->caf_id, array('id' => 'caf_id')) }}
<style type="text/css">
    .accordion-button::after {
        background-image: url(); 
        background-repeat: no-repeat;
        background-size: 1.25rem;
        transition: transform 0.2s ease-in-out;
    }
    .select3-container {
    z-index: unset !important; 
   }
   .btndone{ background-color: #2a2a36 !important; }
</style>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="accordion"   style="padding-top: 10px;">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingfive">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive" >
                                <h6 class="sub-title accordiantitle">{{__("Application Details")}}</h6>
                            </button>
                        </h6>
                        <div  class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                            <div class="basicinfodiv">
                                <!--  <a data-toggle="modal" href="javascript:void(0)" id="loadAddserviceForm" class="btn btn-primary" type="add">Add Service</a> -->
                                <!--------------- Land Apraisal Listing Start Here------------------>
                                <div CLASS="row"> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('cc_applicant_no', __('Application No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cc_applicant_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('cc_applicant_no',$certificatedata->transaction_no, array('class' => 'form-control disabled-field','id'=>'cc_applicant_no','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cc_applicant_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cc_date') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::date('cc_date',$certificatedata->cc_date, array('class' => 'form-control','id'=>'cc_date','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cc_date"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('cc_falc_no', __('FALC NO.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cc_falc_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('cc_falc_no',$certificatedata->cc_falc_no, array('class' => 'form-control disabled-field','id'=>'cc_falc_no')) }}
                                            </div>
                                            <span class="validate-err" id="err_cc_falc_no"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('owner', __('Applicant Name Owner/Developer'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('owner') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('owner',$arrOwners,$certificatedata->cafid,array('class' => 'form-control disabled-field','id'=>'cc_applicant_no','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_owner"></span>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('address', __('Address'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cc_date') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('address',$certificatedata->locationproject, array('class' => 'form-control field-disabled','id'=>'address','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cc_date"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('telephoenumber', __('Telephone Number'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('telephoenumber') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('telephoenumber',$certificatedata->telephone, array('class' => 'form-control field-disabled','id'=>'telephoenumber','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_telephoenumber"></span>
                                        </div>
                                    </div>
                                   <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('cc_name_project', __('Name of Project'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cc_name_project') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('cc_name_project',$certificatedata->nameofproject, array('class' => 'form-control field-disabled','id'=>'cc_name_project','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cc_name_project"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('locationofproject', __('Location of Project'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('locationofproject') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('locationofproject',$certificatedata->locationofproject, array('class' => 'form-control disabled-field','id'=>'locationofproject','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cc_name_project"></span>
                                        </div>
                                    </div>
                                      <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('projecttelno', __('Telephone Number'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('projecttelno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('projecttelno',$certificatedata->projecttelno, array('class' => 'form-control','id'=>'projecttelno','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_projecttelno"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('developername', __('Developer Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('developername') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('developername',$certificatedata->developername, array('class' => 'form-control','id'=>'developername','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cc_name_project"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('developerlocation', __('Location of Developer'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('developerlocation') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('developerlocation',$certificatedata->developerlocation, array('class' => 'form-control','id'=>'developerlocation','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_developerlocation"></span>
                                        </div>
                                    </div>
                                      <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('developtelno', __('Telephone Number'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('developtelno') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('developtelno',$certificatedata->developtelno, array('class' => 'form-control','id'=>'developtelno','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_developtelno"></span>
                                        </div>
                                    </div>
                                     <!-- <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('cc_boc', __('Basic of Clearance'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cc_boc') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('cc_boc',$certificatedata->cc_boc, array('class' => 'form-control field-disabled','id'=>'cc_boc','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cc_boc"></span>
                                        </div>
                                    </div> -->
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('cc_area', __('Area'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cc_area') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('cc_area',$certificatedata->cc_area, array('class' => 'form-control field-disabled','id'=>'cc_area','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cc_area"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-6 hide">
                                        <div class="form-group">
                                            {{ Form::label('cc_location', __('Location'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cc_location') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('cc_location',$certificatedata->cc_location, array('class' => 'form-control field-disabled','id'=>'cc_location')) }}
                                            </div>
                                            <span class="validate-err" id="err_cc_location"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('cc_dominant', __('Dominant land Uses in vicinity of 1 km radius'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cc_dominant') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('cc_dominant',$certificatedata->cc_dominant, array('class' => 'form-control field-disabled','id'=>'cc_dominant','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cc_dominant"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('cc_project_class', __('Project Classification'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cc_project_class') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('cc_project_class',$certificatedata->cc_project_class, array('class' => 'form-control field-disabled','id'=>'cc_project_class','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cc_project_class"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('cc_rol', __('Right Over Land'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cc_rol') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('cc_rol',$arrCpdoOverland,$certificatedata->cc_rol, array('class' => 'form-control field-disabled','id'=>'cc_rol','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cc_rol"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-6 hide">
                                        <div class="form-group">
                                            {{ Form::label('cc_site_classification', __('Site Classification'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cc_site_classification') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('cc_site_classification',$certificatedata->cc_site_classification, array('class' => 'form-control field-disabled','id'=>'cc_site_classification')) }}
                                            </div>
                                            <span class="validate-err" id="err_cc_site_classification"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('cc_evaluation', __('Evaluation of facts'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cc_evaluation') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('cc_evaluation',$certificatedata->cc_evaluation, array('class' => 'form-control field-disabled','id'=>'cc_evaluation','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cc_evaluation"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('cc_decision', __('Decision'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cc_decision') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('cc_decision',$certificatedata->cc_decision, array('class' => 'form-control field-disabled','id'=>'cc_decision','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cc_decision"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   </div>
                </div>
        </div>
        <div class="col-sm-12">
                        <div class="accordion"   style="padding-top: 10px;">  
                            <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                            <h6 class="sub-title accordiantitle">{{__("Recommending Approval")}}</h6>
                                        </button>
                                    </h6>
                                    <div  class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                                        <div class="basicinfodiv">
                                            <!--  <a data-toggle="modal" href="javascript:void(0)" id="loadAddserviceForm" class="btn btn-primary" type="add">Add Service</a> -->
                                            <!--------------- Land Apraisal Listing Start Here------------------>
                                            <div class="row"> 
                                                <div class="col-md-6" id="prparedbydiv">
                                                    <div class="form-group" id="prparedbydiv">
                                                        {{ Form::label('prparedby', __('Prepered By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('prparedby') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::select('preparedby',$hremployees,$certificatedata->preparedby, array('class' => 'form-control','id'=>'prparedby')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_preparedby"></span>
                                                    </div>
                                                </div>
                                                 <div class="col-md-4">
                                                    <div class="form-group">
                                                        {{ Form::label('prparedbypostion', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('prparedbypostion') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('cir_created_position',$certificatedata->cir_created_position, array('class' => 'form-control','id'=>'prparedbypostion')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_prparedbypostion"></span>
                                                    </div>
                                                </div>
                                                 <div class="col-md-2"></div>
                                                 <div class="col-md-6" id="cc_recom_approvaldiv">
                                                    <div class="form-group">
                                                        {{ Form::label('cc_recom_approval', __('Recommended Approval'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('cc_recom_approval') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::select('cc_recom_approval',$hremployees,$certificatedata->cc_recom_approval, array('class' => 'form-control','id'=>'cc_recom_approval')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_cc_recom_approval"></span>
                                                    </div>
                                                </div>
                                               
                                                 <div class="col-md-4">
                                                    <div class="form-group">
                                                        {{ Form::label('cc_recom_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('cc_recom_position') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('cc_recom_approval_position',$certificatedata->cc_recom_approval_position, array('class' => 'form-control','id'=>'cc_recom_position')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_cc_recom_position"></span>
                                                    </div>
                                                </div>
                                               
                                                 <div class="col-md-2">
                                                   
                                                     <div class="form-check form-check-inline form-group col-md-3">
                                                     @if(($certificatedata->id)>0)   
                                                        @if($certificatedata->cc_recom_status == 1)
                                                        {{ Form::checkbox('cc_recom_status', '1', ($certificatedata->cc_recom_status =='1')?true:false, array('id'=>'cash','disabled'=>'true','class'=>'form-check-input code')) }}
                                                        {{ Form::label('cc_recom_status', __('Recommend'),['class'=>'form-label']) }}
                                                        @else
                                                           @if($recommendedid == $loginusedid)
                                                            {{ Form::checkbox('cc_recom_status', '1', ($certificatedata->cc_recom_status =='1')?true:false, array('id'=>'cash','class'=>'form-check-input code')) }}
                                                        {{ Form::label('cc_recom_status', __('Recommend'),['class'=>'form-label']) }}
                                                            @endif
                                                        @endif
                                                       @endif 
                                                    </div>
                                                   
                                                 </div>
                                                  <div class="col-md-6" id="cc_noteddiv">
                                                    <div class="form-group">
                                                        {{ Form::label('cc_noted', __('Noted By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('cc_noted') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::select('cc_noted',$hremployees,$certificatedata->cc_noted, array('class' => 'form-control ','id'=>'cc_noted')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_cc_noted"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {{ Form::label('notedpostion', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('notedpostion') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('cc_noted_position',$certificatedata->cc_noted_position, array('class' => 'form-control','id'=>'notedpostion')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_notedpostion"></span>
                                                    </div>
                                                </div>
                                                  <div class="col-md-2">
                                                   
                                                     <div class="form-check form-check-inline form-group col-md-3">
                                                      @if(($certificatedata->id)>0)    
                                                      @if($certificatedata->cc_recom_status == 1)  
                                                        @if($certificatedata->cc_notes_status == 1)
                                                        {{ Form::checkbox('cc_notes_status', '1', ($certificatedata->cc_notes_status =='1')?true:false, array('id'=>'cash','disabled'=>'true','class'=>'form-check-input code')) }}
                                                        {{ Form::label('cc_notes_status', __('Noted'),['class'=>'form-label']) }}
                                                        @else
                                                         @if($notedid == $loginusedid)
                                                            {{ Form::checkbox('cc_notes_status', '1', ($certificatedata->cc_notes_status =='1')?true:false, array('id'=>'cash','class'=>'form-check-input code')) }}
                                                        {{ Form::label('cc_notes_status', __('Noted'),['class'=>'form-label']) }}
                                                         @endif
                                                        @endif 
                                                      @endif 
                                                      @endif 
                                                    </div>
                                                 </div>
                                                 <div class="col-md-6" id="cc_approveddiv">
                                                    <div class="form-group">
                                                        {{ Form::label('cc_approved', __('Approval By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('cc_approved') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::select('cc_approved',$hremployees,$certificatedata->cc_approved, array('class' => 'form-control','id'=>'cc_approved')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_cc_approved"></span>
                                                    </div>
                                                </div>
                                                  <div class="col-md-4">
                                                    <div class="form-group">
                                                        {{ Form::label('approved_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('approved_position') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('cc_approved_position',$certificatedata->cc_approved_position, array('class' => 'form-control','id'=>'approved_position')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_approved_position"></span>
                                                    </div>
                                                </div>
                                                 <div class="col-md-2">
                                                     <div class="form-check form-check-inline form-group col-md-3">
                                                       @if(($certificatedata->id)>0)   
                                                       @if($certificatedata->cc_recom_status == 1)    
                                                        @if($certificatedata->cc_approval_status == 1)
                                                        {{ Form::checkbox('cc_approval_status', '1', ($certificatedata->cc_approval_status =='1')?true:false, array('id'=>'cash','disabled'=>'true','class'=>'form-check-input code')) }}
                                                        {{ Form::label('cc_approval_status', __('Approve'),['class'=>'form-label']) }}
                                                        @else
                                                         @if($approvalid == $loginusedid)
                                                            {{ Form::checkbox('cc_approval_status', '1', ($certificatedata->cc_approval_status =='1')?true:false, array('id'=>'cash','class'=>'form-check-input code')) }}
                                                          {{ Form::label('cc_approval_status', __('Approve'),['class'=>'form-label']) }}
                                                          @endif
                                                         @endif
                                                       @endif
                                                       @endif  
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
         @if(($certificatedata->id)>0 && $certificatedata->cc_approval_status =='1')
           @if($certificatedata->issued_certificate == 0)
         <!-- <button id="btnPrintCertificate" type="button" style="float: right;" value="{{ url('/cpdodevelopmentapp/printcertificate?id=').''.$certificatedata->id.'&cafid='.$certificatedata->caf_id }}"   class="btn btn-primary digital-sign-btn"><i class="ti-printer text-white"></i>&nbsp;Print</button> -->
         <a href="{{ url('/cpdodevelopmentapp/printcertificate?id=').''.$certificatedata->id.'&cafid='.$certificatedata->caf_id }}" title="Print Development Permit Certificate" style="background: #20b7cc;padding: 10px;color: #fff;" data-title="Print Development Permit Certificate" target="_blank" class="mx-3 btn btn-sm digital-sign-btn" id="{{$certificatedata->id}}" >
                            <i class="ti-printer text-white"></i> Print
          </a>
          @endif
        @endif
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
         <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
            <i class="fa fa-save icon"></i>
            <input type="submit" name="submit" id="submit" value="{{ ($certificatedata->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
        </div>
    
    </div>

</div>    
{{Form::close()}}

<script src="{{ asset('js/ajax_validation.js') }}"></script>  
<script src="{{ asset('js/Cpdo/add_developmentapp.js') }}?rand={{ rand(0000,9999) }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var shouldSubmitForm = false;

        $('#submit').click(function (e) {
            var form = $('#storeJobService');
            var areFieldsFilled = checkIfFieldsFilled();

            if (areFieldsFilled) {
                e.preventDefault(); // Prevent the default form submission

                Swal.fire({
                    title: "Are you sure?",
                    html: '<span style="color: red;">It will not change details after the confirmation?</span>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        shouldSubmitForm = true;
                        form.submit();
                    } else {
                        console.log("Form submission canceled");
                    }
                });
            }
        });

        function checkIfFieldsFilled() {
            var form = $('#storeJobService');
            var requiredFields = form.find('[required="required"]');
            var isValid = true;

            requiredFields.each(function () {
                var field = $(this);
                var fieldValue = field.val();

                if (fieldValue === '') {
                    isValid = false;
                    return false; // Exit the loop early if any field is empty
                }
            });

            if (!isValid) {
                // Swal.fire({
                //     title: "All required fields must be filled",
                //     icon: 'error',
                //     customClass: {
                //         confirmButton: 'btn btn-danger',
                //     },
                //     buttonsStyling: false
                // });
            }

            return isValid;
        }
    });
</script>




