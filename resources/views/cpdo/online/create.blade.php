{{ Form::open(array('url' => 'cpdoapplication','class'=>'formDtls','id'=>'storeJobService','enctype'=>'multipart/form-data')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('transid',$orderpayment->id, array('id' => 'transid')) }}
{{ Form::hidden('totalamount','', array('id' => 'totalamount')) }}

<style type="text/css">
     .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: #fff;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .btn{padding: 0.575rem 0.5rem;}
    .field-requirement-details-status label{padding-top:12px;}
    .nofile{width: 39px; text-align: center;}
    .accordion-button::after {
    background-image: url();
  }
  .excempted{padding-left: 156px}
</style>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-7">
            <div class="accordion"   style="padding-top: 10px;">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingfive">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive" >
                                <h6 class="sub-title accordiantitle">{{__("Application Information")}}</h6>
                            </button>
                        </h6>
                        <div  class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                            <div class="basicinfodiv">
                                <!--  <a data-toggle="modal" href="javascript:void(0)" id="loadAddserviceForm" class="btn btn-primary" type="add">Add Service</a> -->
                                <!--------------- Land Apraisal Listing Start Here------------------>
                                <div CLASS="row"> 
                                    <div class="col-md-9" >
                                        <div class="form-group">
                                            {{ Form::label('clientid', __('Owner Name'),['class'=>'form-label']) }}<span class="text-danger">*</span> &nbsp;&nbsp;&nbsp;
                                            <span class="validate-err">{{ $errors->first('client_id') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('client_id',$arrOwners,$data->client_id, array('class' => 'form-control disabled-field','id'=>'clientidnew','required'=>'required','readonly')) }}
                                            </div>
                                            <span class="validate-err" id="err_client_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {{ Form::label('caf_control_no', __('Control No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('caf_control_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('caf_control_no',$data->caf_control_no, array('class' => 'form-control disabled-field','id'=>'caf_control_no')) }}
                                            </div>
                                            <span class="validate-err" id="err_caf_control_no"></span>
                                        </div>
                                    </div>
                                    
                                    <!-- <div class="col-md-1" style="margin-top: 25px;"></div> -->
                                      <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('caf_name_firm', __('Complete Address of firm'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('caf_name_firm') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('caf_name_firm',$data->caf_name_firm, array('class' => 'form-control disabled-field','id'=>'caf_name_firm','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_caf_name_firm"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('caf_email', __('Email Address'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('caf_email') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('caf_email',$data->caf_email, array('class' => 'form-control disabled-field','id'=>'caf_email','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_caf_email"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('caf_client_representative_id', __('Authorized Representative'),['class'=>'form-label']) }}
                                            &nbsp;&nbsp;&nbsp;
                                            <span class="validate-err">{{ $errors->first('caf_client_representative_id') }}</span>
                                            <div class="form-icon-user disabled-field" id="ownernamediv">
                                                {{ Form::select('caf_client_representative_id',$arrOwners,$data->caf_client_representative_id, array('class' => 'form-control','id'=>'caf_client_representative_id','readonly')) }}
                                            </div>
                                            <span class="validate-err" id="err_caf_client_representative_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {{ Form::label('date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('caf_date') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::date('caf_date',$data->caf_date, array('class' => 'form-control disabled-field','id'=>'caf_date','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_caf_date"></span>
                                        </div>
                                    </div>
                                    
<!--                                     <div class="col-md-1" style="margin-top: 25px;">
                                       
                                    </div> -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {{ Form::label('client_telephone', __('Telephone No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('client_telephone') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::number('client_telephone',$data->client_telephone, array('class' => 'form-control disabled-field','id'=>'client_telephone','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_client_telephone"></span>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="accordion"   style="padding-top: 10px;">  
                    <div  class="accordion accordion-flush">
                        <div class="accordion-item">
                            <h6 class="accordion-header" id="flush-headingfive">
                                <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                    <h6 class="sub-title accordiantitle">{{__("Order Of Payment")}}</h6>
                                </button>
                            </h6>
                            <div  class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                                <div class="basicinfodiv">
                                    <!--  <a data-toggle="modal" href="javascript:void(0)" id="loadAddserviceForm" class="btn btn-primary" type="add">Add Service</a> -->
                                    <!--------------- Land Apraisal Listing Start Here------------------>
                                    <div CLASS="row"> 
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('tfoc_id', __('Service Type Fee'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('tfoc_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('tfoc_id',$getServices,$data->tfoc_id, array('class' => 'form-control select3 field-disabled','id'=>'tfoc_id','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4" id="cmiddiv">
                                            <div class="form-group">
                                                {{ Form::label('cm_id', __('Services Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('cm_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('cm_id',$apptype,$data->cm_id, array('class' => 'form-control','id'=>'cm_id','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="cm_id"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-4" id="cmiddiv">
                                            <div class="form-group">
                                                {{ Form::label('billmaterials', __('Bill Materials'),['class'=>'form-label']) }} <span class="text-danger">*</span>
                                                {{ Form::label('billmaterials', __('Exempted?'),['class'=>'form-label ','style'=>'float: right;']) }}
                                                {{ Form::checkbox('caf_excempted', '1', ($data->caf_excempted =='1')?true:false, array('id'=>'caf_excempted','class'=>'form-check-input code','style'=>'float: right;margin-right: 6px;')) }}
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_amount',$data->caf_amount, array('class' => 'form-control','id'=>'caf_amount','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="caf_amount"></span>
                                            </div>
                                        </div>
<!--                                         <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('caf_amount') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_amount',$data->caf_amount, array('class' => 'form-control numeric-double','id'=>'caf_amount','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_amount"></span>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
       
            <div class="accordion"   style="padding-top: 10px;">  
                    <div  class="accordion accordion-flush">
                        <div class="accordion-item">
                            <h6 class="accordion-header" id="flush-headingfive">
                                <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                    <h6 class="sub-title accordiantitle">{{__("Project Description")}}</h6>
                                </button>
                            </h6>
                            <div  class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                                <div class="basicinfodiv">
                                    <!--  <a data-toggle="modal" href="javascript:void(0)" id="loadAddserviceForm" class="btn btn-primary" type="add">Add Service</a> -->
                                    <!--------------- Land Apraisal Listing Start Here------------------>
                                    <div CLASS="row"> 
                                        <div class="col-md-3">
                                            <div class="form-group" id="cna_id_group">
                                                {{ Form::label('cna_id', __('Nature of Applicant'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('cna_id') }}</span>
                                                <div class="form-icon-user disabled-field">
                                                    {{ Form::select('cna_id',$arrApptype,$data->cna_id, array('class' => 'form-control ','id'=>'cna_id','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_cna_id"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('caf_others_nature_of_applicant', __('Other Nature of  Applicant'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('caf_others_nature_of_applicant') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_others_nature_of_applicant',$data->caf_others_nature_of_applicant, array('class' => 'form-control disabled-field','id'=>'caf_others_nature_of_applicant','readonly')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_others_nature_of_applicant"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('caf_purpose_application', __('Purpose of Application'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('caf_date') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_purpose_application',$data->caf_purpose_application, array('class' => 'form-control disabled-field','id'=>'caf_purpose_application','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_purpose_application"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('caf_type_project', __('Type of Project'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('caf_type_project') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_type_project',$data->caf_type_project, array('class' => 'form-control','id'=>'caf_type_project','required'=>'required','readonly')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_type_project"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('caf_complete_address', __('Location of Project'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('caf_complete_address') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_complete_address',$data->caf_complete_address, array('class' => 'form-control','id'=>'','readonly')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_complete_address"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-3">
                                            <div class="form-group" id="cpt_id_group">
                                                {{ Form::label('cpt_id', __('Project Tenure'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('cpt_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('cpt_id',$arrtenure,$data->cpt_id, array('class' => 'form-control','id'=>'cpt_id')) }}
                                                </div>
                                                <span class="validate-err" id="err_cpt_id"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('cpt_others', __('Specify No. of Years'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('cpt_others') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::number('cpt_others',$data->cpt_others, array('class' => 'form-control disabled-field','id'=>'cpt_others','readonly')) }}
                                                </div>
                                                <span class="validate-err" id="err_cpt_others"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('caf_site_area', __('Project Site Area (Sq. m.)'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('caf_site_area') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_site_area',$data->caf_site_area, array('class' => 'form-control','id'=>'caf_site_area')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_site_area"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-3">
                                            <div class="form-group" id="croh_id_group">
                                                {{ Form::label('croh_id', __('Right Over Land'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('croh_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('croh_id',$arrCpdoOverland,$data->croh_id, array('class' => 'form-control','id'=>'caf_address_house_lot_no','required'=>'required','id'=>'croh_id')) }}
                                                </div>
                                                <span class="validate-err" id="err_croh_id"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('caf_radius', __('1km Radius'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('caf_radius') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_radius',$data->caf_radius, array('class' => 'form-control','id'=>'caf_radius')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_radius"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('caf_use_project_site', __('Use of Project Site'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('caf_use_project_site') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_use_project_site',$data->caf_use_project_site, array('class' => 'form-control','id'=>'caf_use_project_site')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_use_project_site"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('caf_product_manufactured', __('Project manufactured'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('caf_product_manufactured') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_product_manufactured',$data->caf_product_manufactured, array('class' => 'form-control','id'=>'caf_product_manufactured','readonly')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_product_manufactured"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('caf_averg_product_output', __('Average Production Output'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('caf_averg_product_output') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_averg_product_output',$data->caf_averg_product_output, array('class' => 'form-control','id'=>'caf_averg_product_output')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_averg_product_output"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-3">
                                            <div class="form-group" id="caf_power_source_group">
                                                {{ Form::label('caf_power_source', __('Power Source'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('caf_power_source') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('caf_power_source',$arrtenure,$data->caf_power_source, array('class' => 'form-control','id'=>'caf_power_source')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_power_source"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('caf_power_daily_consump', __('Daily Consumption'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('caf_power_daily_consump') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_power_daily_consump',$data->caf_power_daily_consump, array('class' => 'form-control','id'=>'caf_power_daily_consump')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_power_daily_consump"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('caf_employment_current', __('Size Current'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('caf_employment_current') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_employment_current',$data->caf_employment_current, array('class' => 'form-control','id'=>'caf_employment_current')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_employment_current"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('caf_employment_project', __('Size Projected'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('caf_employment_project') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_employment_project',$data->caf_employment_project, array('class' => 'form-control','id'=>'caf_employment_project')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_employment_project"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('caf_remarks', __('Remarks'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('caf_remarks') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_remarks',$data->caf_remarks, array('class' => 'form-control','id'=>'caf_remarks')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_remarks"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="accordion"  id="accordionFlushExample5" style="padding-top: 10px;"> 
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingfive">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                <h6 class="sub-title accordiantitle">{{__("Requirements")}}</h6>
                            </button>

                        </h6>
                        <div class="row" style="width: 100%; margin: 0px;padding-bottom: 20px;">
                            <div class="row field-requirement-details-status">
                                <div class="col-lg-10 col-md-10 col-sm-10">
                                    {{Form::label('subclass_id',__('Requirements'),['class'=>'form-label'])}}
                                </div>
                                <!-- <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('taxable_item_name',__('Status'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('taxable_item_qty',__('File'),['class'=>'form-label numeric'])}}
                                </div> -->
                                <div class="col-lg-2 col-md-2 col-sm-2" style="text-align:end;">
                                    {{Form::label('capital_investment',__('Action'),['class'=>'form-label'])}}
                                </div>
                                <!-- <div class="col-lg-1 col-md-1 col-sm-1"> </div> -->
                            </div>
                            <span class="requirementsDetails activity-details" id="requirementsDetails">
                                @php $i=0; @endphp
                                @foreach($arrRequirements as $key=>$val)
                                <div class="removerequirementsdata row pt10">
                                    <div class="col-lg-11 col-md-11 col-sm-11">
                                        <div class="form-group"> <div class="form-icon-user">{{ Form::select('reqid[]',$requirement,$val->req_id,array('class' => 'form-control disabled-field reqid','required'=>'required','id'=>'reqid','readonly')) }}</div>
                                        {{ Form::hidden('cfid[]',$val->cfid, array('id' => 'cfid')) }}
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                @if(empty($val->cf_name))
                                                <span>N/A</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                <input class="form-control" name="reqfile[]" type="file" value="">
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group">
                                            <td> @if($val->cf_name)<a class="btn" href="{{config('constants.remoteserverurl')}}/{{$val->cf_path}}/{{$val->cf_name}}" target='_blank'><i class='ti-download'></i></a>@else<a class="" herf="#"><i class='fas fa-ban nofile'></i></a>@endif  </td>
                                        </div>
                                    </div>
                                    @php $i++; @endphp
                                </div>
                                @endforeach
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        @if(($data->id)>0)
        
        <input type="button" value="{{__('Close')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <button type="button" id="declinebtn" class="btn decline-btn btn-danger">Decline</button>
                        <button type="button" id="approvebtn" class="btn approve-btn bg-success btn-primary">Accept</button>
        @endif    
        <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Save Payment')}}" class="btn  btn-primary"> -->
    </div>


<div class="modal fade" id="orderofpaymentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
           <div class="modal-header">
                <h4 class="modal-title">Order Of Payment</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="container">
                <div class="modal-body">
                    <div class="">
                       <div class="row">
                             <div class="col-md-2">
                               <div class="form-group">
                                    {{ Form::label('Transaction', __('Trasaction No.'),['class'=>'form-label']) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                               <div class="form-group">
                                    
                                    <div class="form-icon-user">
                                        {{ Form::text('transaction_no',$orderpayment->transaction_no, array('class' => 'form-control disabled-field','id'=>'transaction_no')) }}
                                    </div>
                                    <span class="validate-err" id="err_transaction_no"></span>
                                </div>
                            </div>
                            <div class="col-md-2"></div>
                             <div class="col-md-2">
                               <div class="form-group">
                                    {{ Form::label('Date', __('Date'),['class'=>'form-label']) }}
                                </div>
                            </div>

                            <div class="col-md-3">
                               <div class="form-group">
                                    <div class="form-icon-user">
                                        {{ Form::date('transactiondate',$orderpayment->date, array('class' => 'form-control','id'=>'transactiondate')) }}
                                    </div>
                                    <span class="validate-err" id="err_transactiondate"></span>
                                </div>
                            </div>
                       </div>
                    </div>
                     <div class="row">
                             <div class="col-md-2">
                               <div class="form-group">
                                    {{ Form::label('NameofApplicant', __('Name Of Applicant'),['class'=>'form-label']) }}
                                </div>
                            </div>
                            <div class="col-md-3" style="pointer-events:none;">
                               <div class="form-group">
                                    
                                    <div class="form-icon-user">
                                        {{ Form::select('applicantname',$arrOwners,$data->client_id, array('class' => 'form-control','id'=>'applicantname','readonly'=>'true')) }}
                                    </div>
                                    <span class="validate-err" id="err_applicantname"></span>
                                </div>
                            </div>
                            <div class="col-md-2"></div>
                             <div class="col-md-2">
                               <div class="form-group">
                                    {{ Form::label('telephoneno', __('Telephone No.'),['class'=>'form-label']) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                               <div class="form-group">
                                    
                                    <div class="form-icon-user">
                                        {{ Form::text('telephoneno',$data->client_telephone, array('class' => 'form-control disabled-field','id'=>'telephoneno')) }}
                                    </div>
                                    <span class="validate-err" id="err_telephoneno"></span>
                                </div>
                            </div>
                       </div>
                       <div class="row">
                            <div class="col-md-2">
                               <div class="form-group">
                                    {{ Form::label('zoningfee', __('Zoning Clearance Fee'),['class'=>'form-label']) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                               <div class="form-group">
                                    
                                    <div class="form-icon-user">
                                        {{ Form::text('zoningfee',$data->caf_total_amount, array('class' => 'form-control disabled-field','id'=>'zoningfee')) }}
                                    </div>
                                    <span class="validate-err" id="err_zoningfee"></span>
                                </div>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-2">
                               <div class="form-group">
                                    {{ Form::label('Total', __('Total'),['class'=>'form-label']) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                               <div class="form-group">
                                    
                                    <div class="form-icon-user">
                                        {{ Form::text('total',$data->caf_total_amount, array('class' => 'form-control disabled-field','id'=>'total')) }}
                                    </div>
                                    <span class="validate-err" id="err_Total"></span>
                                </div>
                            </div>
                       </div>
                        <div class="row">
                             <div class="col-md-5">
                              
                            </div>
                            <div class="col-sm-2"></div>
                             
                       </div>
                    </div>
                </div>
                <div class="modal-footer"> 
                     @if(($orderpayment->transaction_no)>0)
                     <span  style="float: right;" class="btn  btn-primary" id="btnPrintOrderofPayment"><i class="ti-printer text-white"></i>&nbsp;Print</span>
                    @endif
                    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light closeOrderModal" data-bs-dismiss="modal">
                   <button class="btn btn-primary" id="saveorder"> <i class="la la-save"></i> Save Changes</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>  

{{Form::close()}}

<div id="hidenRequirementHtml" class="hide">
     <div class="row removerequirementsdata" style="padding: 5px 0px;">
         <div class="col-lg-5 col-md-5 col-sm-5">
            <div class="form-group">
                <div class="form-icon-user">{{ Form::select('reqid[]',$requirement,'',array('class' => 'form-control reqid','required'=>'required','id'=>'reqid')) }}</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
        </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group"><input class="form-control" name="reqfile[]" type="file" value=""></div>
        </div>
         <div class="col-lg-3 col-md-3 col-sm-3"><div class="form-group"><button type="button" class="btn btn-danger btn_cancel_requirement"><i class="ti-trash"></i></button></div></div>
    </div>
</div>

<script src="{{ asset('js/Cpdo/ajax_validationapp.js') }}"></script>  
<script src="{{ asset('js/Cpdo/add_application.js') }}?rand={{ rand(0000,9999) }}"></script> 
<script type="text/javascript">
    $("#cna_id").select3({dropdownAutoWidth : false,dropdownParent: $("#cna_id_group")});
    $("#croh_id").select3({dropdownAutoWidth : false,dropdownParent: $("#croh_id_group")});
    $("#cpt_id").select3({dropdownAutoWidth : false,dropdownParent: $("#cpt_id_group")});
    $("#caf_power_source").select3({dropdownAutoWidth : false,dropdownParent: $("#caf_power_source_group")});
</script>

