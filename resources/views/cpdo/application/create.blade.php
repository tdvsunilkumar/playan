{{ Form::open(array('url' => 'cpdoapplication','class'=>'formDtls','id'=>'storeJobService','enctype'=>'multipart/form-data')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('transid',$orderpayment->id, array('id' => 'transid')) }}
{{ Form::hidden('totalamount','', array('id' => 'totalamount')) }}
 @php  $clientclass =""; @endphp
 @if(($data->id)>0)
     @php  $readonlyclass ="disabled-field"; $select3class ="" ;@endphp
      @if($data->is_online == 1)
        @php  $clientclass ="disabled-field"; @endphp
      @endif  
    @else
     @php  $readonlyclass ="";  $select3class ="select3"; @endphp
 @endif

<style type="text/css">
    .modal-body {
        position: relative;
        flex: 1 1 auto;
        padding: 1.25rem;
        /* padding-bottom: 5%; */
    }
    .form-group {
        margin-bottom: 0rem;
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
    .btn{padding: 0.575rem 0.5rem;}
    .field-requirement-details-status label{padding-top:12px;}
    .nofile{width: 39px; text-align: center;}
    .accordion-button::after {
    background-image: url();
  }
  .excempted{padding-left: 156px}
  @media (min-width: 768px)
.col-md-1 {
    flex: 0 0 auto;
    width: 4%;
}
@media (min-width: 768px)
.col-md-5 {
    flex: 0 0 auto;
    width: 46%;
}
</style>
<div class="modal-body">
    <div class="row" style="    margin-top: -30px;">
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
                                    
                                    <div class="col-md-5" style="">
                                        <div class="form-group" id="clientdiv">
                                            {{ Form::label('clientid', __('Owner Name'),['class'=>'form-label']) }}<span class="text-danger">*</span> &nbsp;&nbsp;&nbsp;
                                            <span class="validate-err">{{ $errors->first('client_id') }}</span>
                                            <div class="form-icon-user {{$clientclass}}">
                                                {{ Form::select('client_id',$arrOwners,$data->client_id, array('class' => 'form-control','id'=>'clientidnew','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_client_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-1" style="margin-top: 30px;text-align: end;padding-left: 0px"><a target="_blank" href="{{ url('/engclients') }}" data-size="lg"  title="{{__('Manage Engineering Clients')}}" class="btn btn-sm btn-primary" style="    padding: 5px 8px;"><i class="ti-plus"></i></a></div>
                                     
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('caf_control_no', __('Control No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('caf_control_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('caf_control_no',$data->caf_control_no, array('class' => 'form-control disabled-field','id'=>'caf_control_no')) }}
                                            </div>
                                            <span class="validate-err" id="err_caf_control_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('caf_name_firm', __('Complete Address of firm'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('caf_name_firm') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('caf_name_firm',$data->caf_name_firm, array('class' => 'form-control '.$readonlyclass,'id'=>'caf_name_firm','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_caf_name_firm"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('caf_email', __('Email Address'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('caf_email') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('caf_email',$data->caf_email, array('class' => 'form-control '.$readonlyclass,'id'=>'caf_email')) }}
                                            </div>
                                            <span class="validate-err" id="err_caf_email"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-5" >
                                        <div class="form-group">
                                            {{ Form::label('caf_client_representative_id', __('Authorized Representative'),['class'=>'form-label']) }}
                                            &nbsp;&nbsp;&nbsp;
                                            <span class="validate-err">{{ $errors->first('caf_client_representative_id') }}</span>
                                            <div class="form-icon-user" id="ownernamediv">
                                                {{ Form::select('caf_client_representative_id',$arrOwners,$data->caf_client_representative_id, array('class' => 'form-control ','id'=>'caf_client_representative_id')) }}
                                            </div>
                                            <span class="validate-err" id="err_caf_client_representative_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-1" style="margin-top: 30px;text-align: end;padding-left: 0px;">
                                        <a target="_blank" href="{{ url('/engclients') }}" data-size="lg"  title="{{__('Manage Engineering Clients')}}" class="btn btn-sm btn-primary" style="padding:5px 8px;"><i class="ti-plus"></i></a>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('caf_date') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::date('caf_date',$data->caf_date, array('class' => 'form-control '.$readonlyclass,'id'=>'caf_date','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_caf_date"></span>
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('client_telephone', __('Telephone No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('client_telephone') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::number('client_telephone',$data->client_telephone, array('class' => 'form-control '.$readonlyclass,'id'=>'client_telephone','required'=>'required')) }}
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
                <div class="accordion"   style="padding-top: 0px;">  
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
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('tfoc_id', __('Service Type Fee'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('tfoc_id') }}</span>
                                                <div class="form-icon-user {{$readonlyclass}}">
                                                    {{ Form::select('tfoc_id',$getServices,$data->tfoc_id, array('class' => 'form-control select3 field-disabled','id'=>'tfoc_id','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_tfoc_id"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="cmiddiv">
                                            <div class="form-group">
                                                {{ Form::label('cm_id', __('Services Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('cm_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('cm_id',$apptype,$data->cm_id, array('class' => 'form-control','id'=>'cm_id','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="cm_id"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-6" id="cmiddiv">
                                            <div class="form-group">
                                                {{ Form::label('billmaterials', __('Bill Materials'),['class'=>'form-label']) }} <span class="text-danger">*</span>
                                                {{ Form::label('billmaterials', __('Exempted?'),['class'=>'form-label','style'=>'float: right;']) }}
                                                {{ Form::checkbox('caf_excempted', '1', ($data->caf_excempted =='1')?true:false, array('id'=>'caf_excempted','class'=>'form-check-input code','style'=>'float: right;margin-right: 6px;')) }}
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_amount',$data->caf_amount, array('class' => 'form-control','id'=>'caf_amount')) }}
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
       
            <div class="accordion"  id="applicationdiv" style="padding-top:0px;">  
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
                                        <div class="col-md-2">
                                            <div class="form-group" id="cna_id_group">
                                                {{ Form::label('cna_id', __('Nature of Applicant'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('cna_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('cna_id',$arrApptype,$data->cna_id, array('class' => 'form-control select3'.$readonlyclass,'id'=>'cna_id','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_cna_id"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('caf_others_nature_of_applicant', __('Other Nature of  Applicant'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('caf_others_nature_of_applicant') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_others_nature_of_applicant',$data->caf_others_nature_of_applicant, array('class' => 'form-control disabled-field','id'=>'caf_others_nature_of_applicant')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_others_nature_of_applicant"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('caf_purpose_application', __('Purpose of Application'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('caf_date') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_purpose_application',$data->caf_purpose_application, array('class' => 'form-control '.$readonlyclass,'id'=>'caf_purpose_application','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_purpose_application"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('caf_type_project', __('Type of Project'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('caf_type_project') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_type_project',$data->caf_type_project, array('class' => 'form-control '.$readonlyclass,'id'=>'caf_type_project','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_type_project"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('caf_brgy_id', __('Location of Project'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('caf_brgy_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('caf_brgy_id',$arrgetBrgyCode,$data->caf_brgy_id,array('class'=>'form-control select3','id'=>'caf_brgy_id')) }}
                                                </div>
                                                <span class="validate-err" id="err_caf_brgy_id"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('cpt_id', __('Project Tenure'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('cpt_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('cpt_id',$arrtenure,$data->cpt_id, array('class' => 'form-control select3','id'=>'cpt_id')) }}
                                                </div>
                                                <span class="validate-err" id="err_cpt_id"></span>
                                            </div>
                                        </div>
                                         <div class="col-md-3">
                                            <div class="form-group">
                                                {{ Form::label('cpt_others', __('Specify No. of Years'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('cpt_others') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::number('cpt_others',$data->cpt_others, array('class' => 'form-control disabled-field','id'=>'cpt_others')) }}
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
                                                {{ Form::label('caf_product_manufactured', __('Project Manufactured'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('caf_product_manufactured') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('caf_product_manufactured',$data->caf_product_manufactured, array('class' => 'form-control','id'=>'caf_product_manufactured')) }}
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
                                            <div class="form-group">
                                                {{ Form::label('caf_power_source', __('Power Source'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('caf_power_source') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('caf_power_source',$arrtenure,$data->caf_power_source, array('class' => 'form-control select3','id'=>'caf_power_source')) }}
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
        <div class="col-lg-5" >
            <div class="accordion"  id="accordionFlushExample5" style="padding-top: 10px;"> 
                <div  class="accordion accordion-flush">
                    <div class="accordion-item" >
                        <h6 class="accordion-header" id="flush-headingfive">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                <h6 class="sub-title accordiantitle">{{__("Requirements")}}</h6>
                            </button>

                        </h6>
                        <div class="row" style="width: 100%; margin: 0px;padding-bottom: 20px;">
                            <div class="row field-requirement-details-status">
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    {{Form::label('subclass_id',__('Requirements'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('taxable_item_name',__('Status'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    {{Form::label('taxable_item_qty',__('File'),['class'=>'form-label numeric'])}}
                                </div>
                               
                                <div class="col-lg-1 col-md-1 col-sm-1"> <span class="btn_addmore_requirements btn" id="btn_addmore_requirements" style="color:white;"><i class="ti-plus"></i></span></div>
                            </div>
                            <span class="requirementsDetails activity-details" id="requirementsDetails" >
                                @php $i=1; @endphp
                                @foreach($arrRequirements as $key=>$val)
                                <div class="removerequirementsdata row pt10">
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                        <div class="form-group" id="reqid_group"> <div class="form-icon-user">{{ Form::select('reqid[]',$requirement,$val->req_id,array('class' => 'form-control  reqid','required'=>'required','id'=>'reqid'.$i)) }}</div>
                                        {{ Form::hidden('cfid[]',$val->cfid, array('id' => 'cfid')) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                @if(empty($val->cf_name))
                                                <span>N/A</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                <input class="form-control" name="reqfile[]" type="file" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2" style="text-align:center;">
                                        <div class="form-group">
                                            <td> @if($val->cf_name)<a class="btn" href="{{asset('uploads/')}}/{{$val->cf_path}}/{{$val->cf_name}}" target='_blank'><i class='ti-download'></i></a>@else<a class="" herf="#"><i class='fas fa-ban nofile'></i></a>@endif  <button type="button" class="btn btn-danger btn_cancel_requirement" style="padding: 4px;" fileid="{{$val->cfid}}" value="{{$val->id}}"><i class="ti-trash"></i></button></td>
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
         <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        @if(($data->id)>0)
         <button id="btnPrintapplication" type="button" style="float: right;" value="{{ url('/cpdoapplication/printapplication?id=').''.$data->id }}"   class="btn btn-primary"><i class="ti-printer text-white"></i>&nbsp;Print</button>
        @endif
       
        @if(($data->id)>0)
          @if(($data->csd_id) != 9)
                <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?__('Update'):__('Save Payment')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
          @endif
        @else
        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?__('Update'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
        @endif    
        <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Save Payment')}}" class="btn  btn-primary"> -->
    </div>




{{Form::close()}}

<div id="hidenRequirementHtml" class="hide">
     <div class="row removerequirementsdata" style="padding: 5px 0px;">
         <div class="col-lg-5 col-md-5 col-sm-5">
            <div class="form-group" id="reqid_group">
                <div class="form-icon-user">{{ Form::select('reqid[]',$requirement,'',array('class' => 'form-control reqid','id'=>'reqid0')) }}</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
        </div>
         <div class="col-lg-3 col-md-3 col-sm-3">
            <div class="form-group"><input class="form-control" name="reqfile[]" type="file" value=""></div>
        </div>
         <div class="col-lg-2 col-md-2 col-sm-2" style="text-align:center;padding-left: 56px;"><div class="form-group"><button type="button" class="btn btn-danger btn_cancel_requirement" style="padding: 4px;"><i class="ti-trash"></i></button></div></div>
    </div>
</div>

<script src="{{ asset('js/Cpdo/ajax_validationapp.js') }}"></script>  
<script src="{{ asset('js/Cpdo/add_application.js') }}?rand={{ rand(0000,9999) }}"></script> 

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
    $("#cna_id").select3({dropdownAutoWidth : false,dropdownParent: $("#cna_id_group")});
    $("#croh_id").select3({dropdownAutoWidth : false,dropdownParent: $("#croh_id_group")});
});
    
</script>
