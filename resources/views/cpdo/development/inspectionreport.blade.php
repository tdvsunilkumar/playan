{{ Form::open(array('url' => 'cpdodevelopmentapp/inspectionreport','class'=>'formDtls','id'=>'storeJobService','enctype'=>'multipart/form-data')) }}

{{ Form::hidden('insid',$inspectiondata->id, array('id' => 'insid')) }}
{{ Form::hidden('tfoc_id',$inspectiondata->tfoc_id, array('id' => 'tfoc_id')) }}
{{ Form::hidden('transid',$orderpayment->id, array('id' => 'transid')) }}
{{ Form::hidden('caf_id',$inspectiondata->caf_id, array('id' => 'caf_id')) }}

@php
    $disabled = ($issurcharge>0)?'':'disabled-field';
     $disabled = ($inspectiondata->cir_isapprove == 1)? 'disabled-field':'';
@endphp

<style type="text/css">
    .form-group {
        margin-bottom: 0rem;
    }
    .accordion-button::after {
        background-image: url(); 
        background-repeat: no-repeat;
        background-size: 1.25rem;
        transition: transform 0.2s ease-in-out;
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
    .field-requirement-details-status label{padding-top:10px;}
    .permitclick{  cursor:pointer;color:skyblue; }
    .orpayment > .row{ padding:10px; }
    .btn{padding: 0.575rem 0.5rem;}
    .accordion-button::after {
    background-image: url();
    }
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {{ Form::label('cafid', __('Owner Developer'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('caf_id') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('cafid',$arrOwners,$inspectiondata->cafid, array('class' => 'form-control ','id'=>'cafid','disabled'=>true)) }}
                                            </div>
                                            <span class="validate-err" id="err_caf_id"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {{ Form::label('nameofproject', __('Name of Project'),['class'=>'form-label']) }}<span class="text-danger">*</span> 
                                            <span class="validate-err">{{ $errors->first('client_id') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('nameofproject',$inspectiondata->nameofproject, array('class' => 'form-control ','id'=>'nameofproject','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_nameofproject"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('cir_date', __('Date of Inspection'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cir_date') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::date('cir_date',$inspectiondata->cir_date, array('class' => 'form-control field-disabled','id'=>'cir_date','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_caf_control_no"></span>
                                        </div>
                                    </div> 
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('locationproject', __('Location of Project'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('locationproject') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('locationproject',$arrgetBrgyCode,$inspectiondata->locationproject, array('class' => 'form-control  disabled-field','id'=>'locationproject')) }}
                                            </div>
                                            <span class="validate-err" id="err_locationproject"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('cir_zoning_class', __('Site Zoning Classification'),['class'=>'form-label']) }}<span class="text-danger">*</span> 
                                            <span class="validate-err">{{ $errors->first('cir_zoning_class') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('cir_zoning_class',$inspectiondata->cir_zoning_class, array('class' => 'form-control','id'=>'cir_zoning_class','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cir_zoning_class"></span>
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
                        <div class="accordion"   style="padding-top: 10px;">  
                            <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                            <h6 class="sub-title accordiantitle">{{__("A. PROJECT SITE")}}</h6>
                                        </button>
                                    </h6>
                                    <div  class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                                        <div class="basicinfodiv">
                                            <!--  <a data-toggle="modal" href="javascript:void(0)" id="loadAddserviceForm" class="btn btn-primary" type="add">Add Service</a> -->
                                            <!--------------- Land Apraisal Listing Start Here------------------>
                                            <div class="row"> 
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {{ Form::label('cir_use_res', __('A. 1 Existing Land Use Residential'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('cir_use_res') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('cir_use_res',$inspectiondata->cir_north, array('class' => 'form-control field-disabled','id'=>'cir_use_res','required'=>'required')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_cir_use_res"></span>
                                                    </div>
                                                </div>
                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('cit_id', __('A. 2 Terrain'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('cit_id') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::select('cit_id',$arrterrin,$inspectiondata->cit_id, array('class' => 'form-control field-disabled','id'=>'cit_id','required'=>'required')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_cit_id"></span>
                                                    </div>
                                                </div>
                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('citother', __('Others'),['class'=>'form-label']) }}
                                                        <span class="validate-err">{{ $errors->first('citother') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('citother',$inspectiondata->cir_north, array('class' => 'form-control disabled-field','id'=>'citother')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_tfoc_id"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                      <div class="col-sm-6">
                         <div class="accordion"   style="padding-top: 10px;">  
                            <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingfive">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                                            <h6 class="sub-title accordiantitle">{{__("A. VICINITY")}}</h6>
                                        </button>
                                    </h6>
                                    <div  class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                                        <div class="basicinfodiv">
                                            <div class="row">
                                                  <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('cir_north', __('North'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('cir_north') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('cir_north',$inspectiondata->cir_north, array('class' => 'form-control field-disabled','id'=>'cir_north','required'=>'required')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_cir_north"></span>
                                                    </div>
                                                </div>
                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('cir_south', __('South'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('cir_south') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('cir_south',$inspectiondata->cir_south, array('class' => 'form-control field-disabled','id'=>'cir_south','required'=>'required')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_cir_south"></span>
                                                    </div>
                                                </div>
                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('cir_east', __('East'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('cir_east') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('cir_east',$inspectiondata->cir_east, array('class' => 'form-control field-disabled','id'=>'cir_east','required'=>'required')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_cir_east"></span>
                                                    </div>
                                                </div>
                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('cir_west', __('West'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('cir_west') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('cir_west',$inspectiondata->cir_west, array('class' => 'form-control field-disabled','id'=>'cir_west','required'=>'required')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_cir_west"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                        <div class="form-group">
                                                            {{ Form::label('long', __('LONG'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                            <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-icon-user">
                                                                    {{ Form::text('cir_long_we_degree',$inspectiondata->cir_long_we_degree, array('class' => 'form-control','id'=>'cir_long_we_degree','required'=>'required')) }}
                                                                </div>
                                                            <span class="validate-err" id="err_cir_long_we_degree"></span>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-icon-user">
                                                                    {{ Form::text('cir_long_we_minutes',$inspectiondata->cir_long_we_minutes, array('class' => 'form-control','id'=>'cir_long_we_minutes','required'=>'required')) }}
                                                                </div>
                                                            <span class="validate-err" id="err_cir_long_we_minutes"></span>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-icon-user">
                                                                    {{ Form::text('cir_long_we_seconds',$inspectiondata->cir_long_we_seconds, array('class' => 'form-control','id'=>'cir_long_we_seconds','required'=>'required')) }}
                                                                </div>
                                                            <span class="validate-err" id="err_cir_long_we_seconds"></span>
                                                            </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="col-md-6">
                                                        <div class="form-group">
                                                            {{ Form::label('lat', __('LAT'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                            <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-icon-user">
                                                                    {{ Form::text('cir_lat_ns_degree',$inspectiondata->cir_lat_ns_degree, array('class' => 'form-control','id'=>'cir_lat_ns_degree','required'=>'required')) }}
                                                                </div>
                                                            <span class="validate-err" id="err_cir_lat_ns_degree"></span>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-icon-user">
                                                                    {{ Form::text('cir_lat_ns_minutes',$inspectiondata->cir_lat_ns_minutes, array('class' => 'form-control','id'=>'cir_lat_ns_minutes','required'=>'required')) }}
                                                                </div>
                                                            <span class="validate-err" id="err_cir_lat_ns_minutes"></span>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-icon-user">
                                                                    {{ Form::text('cir_lat_ns_seconds',$inspectiondata->cir_lat_ns_seconds, array('class' => 'form-control','id'=>'cir_lat_ns_seconds','required'=>'required')) }}
                                                                </div>
                                                            <span class="validate-err" id="err_cir_lat_ns_seconds"></span>
                                                            </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                 <div class="col-md-6 hide">
                                                    <div class="form-group">
                                                        {{ Form::label('cir_long', __('Long'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('cir_long') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('cir_long',$inspectiondata->cir_long, array('class' => 'form-control field-disabled','id'=>'cir_long')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_cir_long"></span>
                                                    </div>
                                                </div>
                                                 <div class="col-md-6 hide">
                                                    <div class="form-group">
                                                        {{ Form::label('cir_lat', __('Lat'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('cir_lat') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('cir_lat',$inspectiondata->cir_water_supply, array('class' => 'form-control field-disabled','id'=>'cir_lat')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_cir_lat"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {{ Form::label('cir_decs', __('B. 3 Description Of Access road/ Road right of way'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('cir_decs') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('cir_decs',$inspectiondata->cir_decs, array('class' => 'form-control field-disabled','id'=>'cir_decs','required'=>'required')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_cir_decs"></span>
                                                    </div>
                                                </div>
                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('cir_water_supply', __('Water Supply'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('cir_water_supply') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('cir_water_supply',$inspectiondata->cir_water_supply, array('class' => 'form-control field-disabled','id'=>'cir_water_supply','required'=>'required')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_cir_water_supply"></span>
                                                    </div>
                                                </div>
                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('cir_power_supply', __('Power Supply'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('cir_power_supply') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('cir_power_supply',$inspectiondata->cir_power_supply, array('class' => 'form-control field-disabled','id'=>'cir_power_supply','required'=>'required')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_cir_power_supply"></span>
                                                    </div>
                                                </div>
                                                  <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('cir_drainage', __('Drainage'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('cir_drainage') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('cir_drainage',$inspectiondata->cir_drainage, array('class' => 'form-control field-disabled','id'=>'cir_drainage','required'=>'required')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_cir_drainage"></span>
                                                    </div>
                                                </div>
                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('cir_other', __('Other(Specify)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                        <span class="validate-err">{{ $errors->first('cir_other') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('cir_other',$inspectiondata->cir_other, array('class' => 'form-control field-disabled','id'=>'cir_other','required'=>'required')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_cir_other"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>
                      <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('cir_remark', __('Remarks'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                            <span class="validate-err">{{ $errors->first('cir_remark') }}</span>
                            <div class="form-icon-user">
                                {{ Form::text('cir_remark',$inspectiondata->cir_remark, array('class' => 'form-control field-disabled','id'=>'cir_remark','required'=>'required')) }}
                            </div>
                            <span class="validate-err" id="err_cir_remark"></span>
                        </div>
                    </div>
                     <div class="col-md-3" id="cirapprovediv">
                        <div class="form-group">
                            {{ Form::label('cir_approved_by', __('Prepared BY'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                            <span class="validate-err">{{ $errors->first('cir_approved_by') }}</span>
                            <div class="form-icon-user">
                                {{ Form::select('cir_approved_by',$hremployees,$inspectiondata->cir_approved_by, array('class' => 'form-control  ','id'=>'cir_approved_by','required'=>'required')) }}
                            </div>
                            <span class="validate-err" id="err_cir_approved_by"></span>
                        </div>
                    </div> <div class="col-md-2" id="cirnoteddiv">
                        <div class="form-group">
                            {{ Form::label('cir_noted_by', __('Noted By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                            <span class="validate-err">{{ $errors->first('cir_noted_by') }}</span>
                            <div class="form-icon-user">
                                {{ Form::select('cir_noted_by',$hremployees,$inspectiondata->cir_noted_by, array('class' => 'form-control  ','id'=>'cir_noted_by','required'=>'required')) }}
                            </div>
                            <span class="validate-err" id="err_cir_noted_by"></span>
                        </div>
                    </div>
                    <div class="col-md-1" style="padding-top: 34px;">
                      @if(($inspectiondata->id)>0)  
                         <div class="form-check form-check-inline form-group col-md-3">
                        @if($approvalid == $loginusedid)  
                            {{ Form::checkbox('cir_isapprove', '1', ($inspectiondata->cir_isapprove =='1')?true:false, array('id'=>'cash','class'=>'form-check-input code')) }}
                            {{ Form::label('cir_isapprove', __('Approve'),['class'=>'form-label']) }}
                         @else
                            @if(isset($inspectiondata->cir_isapprove) == 1)
                                {{ Form::checkbox('cir_isapprove', '1', ($inspectiondata->cir_isapprove =='1')?true:false, array('id'=>'cash','disabled'=>'true','class'=>'form-check-input code')) }}
								{{ Form::label('cir_isapprove', __('Approve'),['class'=>'form-label']) }}
                            @endif
                        @endif
                        </div>
                      @endif  
                     </div>
                     <div class="row" id="orderofpdiv">
                     <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('amountdue', __('Amount Due'),['class'=>'form-label']) }}
                            <span class="validate-err">{{ $errors->first('amountdue') }}</span>
                            <div class="form-icon-user">
                                {{ Form::text('amountdue',$inspectiondata->caf_amount, array('class' => 'form-control disabled-field','id'=>'amountdue')) }}
                            </div>
                            <span class="validate-err" id="err_amountdue"></span>
                        </div>
                    </div>
                     <div class="col-md-3" style="padding-right: 0px;">
                        <div class="form-group">
                            {{ Form::label('ornumber', __('O.R.Number'),['class'=>'form-label']) }}
                            <span class="validate-err">{{ $errors->first('ornumber') }}</span>
                            <div class="form-icon-user">
                                {{ Form::text('ornumber',$inspectiondata->or_no, array('class' => 'form-control disabled-field','id'=>'ornumber')) }}
                            </div>
                            <span class="validate-err" id="err_ornumber"></span>
                        </div>
                    </div> 
                    <div class="col-md-3" style="padding-right: 0px;
    padding-left: 25px;">
                        <div class="form-group">
                            {{ Form::label('orissueddate', __('Date Issued'),['class'=>'form-label']) }}
                            <span class="validate-err">{{ $errors->first('orissueddate') }}</span>
                            <div class="form-icon-user">
                                {{ Form::date('orissueddate',$inspectiondata->orissueddate, array('class' => 'form-control disabled-field','id'=>'orissueddate')) }}
                            </div>
                            <span class="validate-err" id="err_orissueddate"></span>
                        </div>
                    </div>
                     <div class="col-md-3" style="padding-right: 0px;
    padding-left: 25px;">
                        <div class="form-group">
                            {{ Form::label('cit_penalty', __('Penalty'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                            <span class="validate-err">{{ $errors->first('cir_penalty') }}</span>
                            <div class="form-icon-user {{$disabled}}">
                                {{ Form::select('cir_penalty',$penaltyarray,$inspectiondata->cir_penalty, array('class' => 'form-control '.$disabled,'id'=>'cir_penalty','required'=>'required')) }}
                            </div>
                            <span class="validate-err" id="err_cit_id"></span>
                        </div>
                    </div>
                </div>
                @if($inspectiondata->id > 0)
                <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExampleUploads" style="padding-top: 10px">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading3">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Uploads")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                
                                
                                <div id="flush-collapse3" class="accordion-collapse collapse" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExampleUploads">
                                
                                    <div class="basicinfodiv">
                                        <div class="row">
                                            <div class="col-lg-10 col-md-10 col-sm-10">
                                                <div class="form-group">
                                                    {{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('file','documentname','',array('class'=>'form-control','id'=>'documentname'))}}  
                                                    </div>
                                                    <span class="validate-err" id="err_document"></span>
                                                </div>
                                            </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2" style="padding-top: 26px;">
                                                    <button type="button" style="float: right;" class="btn btn-primary" id="uploadAttachmentbtn">Upload File</button>
                                                </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:-10px;"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Document Title</th>
                                                                <th>Attachment</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <thead id="DocumentDtls">
                                                             <?php echo $inspectiondata->document_details?>
                                                            @if(empty($inspectiondata->document_details))
                                                            <tr>
                                                                <td colspan="3"><i>No results found.</i></td>
                                                            </tr>
                                                            @endif 
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                @endif 
                <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample3">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-headingGeo">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseGeo" aria-expanded="false" aria-controls="flush-collapseGeo">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Geo Tagging Location Map")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                
                                
                                <div id="flush-collapseGeo" class="accordion-collapse collapse" aria-labelledby="flush-headingGeo" data-bs-parent="#accordionFlushExample3">
                                
                                    <div class="basicinfodiv">
                                        <div class="row">
                        <div class="col-md-12" style="    margin-top: -20px;">
                          <div class="row field-requirement-details-status">
                            <div class="col-lg-1 col-md-1 col-sm-1" style="text-align: center;">
                                {{Form::label('id',__('NO'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                {{Form::label('link',__('Link Description'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                {{Form::label('link',__('Link Remark'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1" style="padding-left: 20px;">
                                <span class="btn_addmore_geolocation btn btn-primary" id="btn_addmore_geolocation" style="color:white;"><i class="ti-plus"></i></span>
                            </div>
                        </div>
                         <span class="geolocationDetails activity-details" id="geolocationDetails">
                             @php $i=1; @endphp
                            @foreach($arrLocations as $key=>$val)
                            <div class="removedocumentsdata row pt10" style="padding-top:5px;">
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                  <div class="form-group"><div class="form-icon-user">
                                    <p class="serialnoclass" style="text-align:center;">{{$i}}</p>
                                    {{ Form::hidden('geoid[]',$val->id, array('id' => 'fileid')) }}
                                    </div>
                                  </div>
                                 </div>
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <div class="form-group">
                                       {{ Form::text('linkdesc[]',$val->cig_location_description, array('id' => 'linkdesc','class'=>"form-control")) }}
                                   </div>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <div class="form-group">
                                       {{ Form::text('remark[]',$val->cig_remarks, array('id' => 'remark','class'=>"form-control")) }}
                                   </div>
                                </div>
                                @if($i>=0)
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                         <div class="form-group">
                                        <a class="btn btn-primary" href="{{$val->cig_location_description}}" target="_blank" style="padding: 5px 8px;"><i class="ti-world"></i></a>
                                         <button type="button" class="btn btn-danger btn_cancel_locations"  value="{{$val->id}}" style="padding: 5px 8px;"><i class="ti-trash"></i></button>
                                       </div>
                                 </div>
                                @endif
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
    <div class="modal-footer">
        @php 
			$disablebtn =""; 
			if(isset($inspectiondata->cir_isapprove) == 1){

				$disablebtn ="disabled-field"; 
			} 
		@endphp
         @if(($inspectiondata->id)>0)
		  @if($inspectiondata->cir_isapprove ==1)
             <a href="{{ url('/cpdodevelopmentapp/printinspection?id=').''.$inspectiondata->id}}" title="Print Development Permit Inspection" style="background: #20b7cc;padding: 10px;color: #fff;" data-title="Print Development Permit Inspection" target="_blank" class="mx-3 btn btn-sm digital-sign-btn" id="{{$inspectiondata->id}}" >
                            <i class="ti-printer text-white"></i> Print
          </a>
		  @endif
        @endif
        @if(($inspectiondata->id)>0)
          @if($inspectiondata->cir_isapprove ==1)
         <button type="button" style="float: right;" class="btn  btn-primary" id="btnOrderofPayment">Order of Payment</button>
          @endif
        @endif
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
       
          @if(($inspectiondata->csd_id) != 9)
        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
            <i class="fa fa-save icon"></i>
        <input type="submit" name="submit" id="submit" value="{{ ($inspectiondata->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary " style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
       </div>
        @endif
   
    </div>

</div>    
{{Form::close()}}
<div class="modal fade" id="orderofpaymentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
           <div class="modal-header">
                <h4 class="modal-title">Order of Payment</h4>
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
                                    {{ Form::label('NameofApplicant', __('Name of Applicant'),['class'=>'form-label']) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                               <div class="form-group">
                                    
                                    <div class="form-icon-user">
                                        {{ Form::select('applicantname',$arrOwners,$inspectiondata->cafid, array('class' => 'form-control disabled-field','id'=>'applicantname','readonly'=>'true')) }}
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
                                        {{ Form::text('telephoneno',$inspectiondata->client_telephone, array('class' => 'form-control disabled-field','id'=>'telephoneno')) }}
                                    </div>
                                    <span class="validate-err" id="err_telephoneno"></span>
                                </div>
                            </div>
                       </div>
                       <div class="row">
                            <div class="col-md-2">
                               <div class="form-group">
                                    {{ Form::label('zoningfee', __('Development Fee'),['class'=>'form-label']) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                               <div class="form-group">
                                    
                                    <div class="form-icon-user">
                                        {{ Form::text('zoningfee',$inspectiondata->orptotal, array('class' => 'form-control disabled-field','id'=>'zoningfee')) }}
                                    </div>
                                    <span class="validate-err" id="err_zoningfee"></span>
                                </div>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-2">
                               <div class="form-group">
                                    {{ Form::label('penalty', __('Penalty Fee'),['class'=>'form-label']) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                               <div class="form-group">
                                    
                                    <div class="form-icon-user">
                                        {{ Form::text('penalty',$inspectiondata->penaltyamount, array('class' => 'form-control disabled-field','id'=>'penalty')) }}
                                    </div>
                                    <span class="validate-err" id="err_zoningfee"></span>
                                </div>
                            </div>
                        </div>
                         <div class="row">   
                            <div class="col-md-7"></div>
                            <div class="col-md-2">
                               <div class="form-group">
                                    {{ Form::label('Total', __('Total'),['class'=>'form-label']) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                               <div class="form-group">
                                    
                                    <div class="form-icon-user">
                                        {{ Form::text('total',$inspectiondata->orptotal_amount, array('class' => 'form-control disabled-field','id'=>'total')) }}
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
<div id="hiddenlocationHtml" class="hide">
    <div class="removelocationdata row pt10" style="padding-top:5px;">
         <div class="col-lg-1 col-md-1 col-sm-1">
            <div class="form-group">
                    <div class="form-icon-user">
                        <input type="hidden" name="geoid[]">
                        <p class="serialnoclass" style="text-align:center;"></p>
                    </div>
            </div>
          </div>
         <div class="col-lg-5 col-md-5 col-sm-5">
                <div class="form-group">
                <div class="form-icon-user"><input required="required" class="form-control" name="linkdesc[]" type="text" value="">
                    </div>
               </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5">
                <div class="form-group">
                <div class="form-icon-user"><input required="required" class="form-control" name="remark[]" type="text" value="">
                    </div>
               </div>
            </div>
         <div class="col-lg-1 col-md-1 col-sm-1">
                     <div class="form-group">
                       <div class="form-icon-user"><button type="button" class="btn btn-danger btn_cancel_locations" style="padding: 5px 8px;"><i class="ti-trash"></i></button>
                       </div>
                   </div>
             </div>
    </div>
</div>
<script src="{{ asset('js/ajax_validation.js') }}"></script>  
<script src="{{ asset('js/Cpdo/add_developmentapp.js') }}"></script> 
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



