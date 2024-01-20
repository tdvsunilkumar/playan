{{ Form::open(array('url' => 'online-development-permit','class'=>'formDtls','id'=>'onlineCpdoApp','enctype'=>'multipart/form-data')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('totalamount','', array('id' => 'totalamount')) }}
@php $readonlyclass = ($data->id > 0 )?'disabled-field':'';@endphp

<style type="text/css">
    .modal.show .modal-dialog {
        transform: none;
        width: 70%;
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('cdp_control_no', __('Control No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cdp_control_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('cdp_control_no',$data->cdp_control_no, array('class' => 'form-control disabled-field','id'=>'cdp_control_no')) }}
                                            </div>
                                            <span class="validate-err" id="err_caf_control_no"></span>
                                        </div>
                                    </div>
                                  
                                    <div class="col-md-4" >
                                        <div class="form-group">
                                            {{ Form::label('client_id', __('Client Name'),['class'=>'form-label']) }}<span class="text-danger">*</span> &nbsp;&nbsp;&nbsp;
                                            <span class="validate-err">{{ $errors->first('client_id') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('client_id',$data->full_name, array('class' => 'form-control disabled-field','id'=>'client_id')) }}
                                            </div>
                                            <span class="validate-err" id="err_client_id"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group"><span class="text-danger">*</span>
                                            {{ Form::label('cdp_address', __('Address Of Firm'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('cdp_address') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('cdp_address',$data->cdp_address, array('class' => 'form-control disabled-field','id'=>'cdp_address','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cdp_address"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('cdp_email_address', __('Email Address'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('cdp_email_address') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('cdp_email_address',$data->cdp_email_address, array('class' => 'form-control disabled-field','id'=>'cdp_email_address','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_caf_email"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('cdp_phone_no', __('Telephone No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cdp_phone_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::number('cdp_phone_no',$data->cdp_phone_no, array('class' => 'form-control disabled-field','id'=>'cdp_phone_no','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cdp_phone_no"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('nameofproject', __('Name of Project'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('nameofproject') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('nameofproject',$data->nameofproject, array('class' => 'form-control disabled-field','id'=>'nameofproject','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_nameofproject"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('locationofproject', __('Location of Project'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('locationofproject') }}</span>
                                                <div class="form-icon-user disabled-field">
                                                    {{ Form::select('locationofproject',$arrgetBrgyCode,$data->locationofproject,array('class'=>'form-control select3','id'=>'locationofproject','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_locationofproject"></span>
                                            </div>
                                    </div>
                                     <div class="col-md-8">
                                         <div class="form-group">
                                                {{ Form::label('cdf_remarks', __('Remarks'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('cdf_remarks') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::text('cdf_remarks',$data->cdf_remarks, array('class' => 'form-control','id'=>'cdf_remarks')) }}
                                                </div>
                                                <span class="validate-err" id="err_cdf_remarks"></span>
                                        </div>
                                   </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .form-group {
    margin-bottom: 0rem;
}
        </style>
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
                                <!-- <div class="col-lg-1 col-md-1 col-sm-1">
                                    {{Form::label('taxable_item_name',__('Status'),['class'=>'form-label'])}}
                                </div> -->
                                <!-- <div class="col-lg-3 col-md-3 col-sm-3">
                                    {{Form::label('taxable_item_qty',__('File'),['class'=>'form-label numeric'])}}
                                </div> -->
                               
                                <div class="col-lg-2 col-md-2 col-sm-2" style="text-align:end;padding-right:35px;">
                                 <!-- <span class="btn_addmore_requirements btn" id="btn_addmore_requirements" style="color:white;padding: 5px 8px;"><i class="ti-plus"></i></span> -->
                             </div>
                            </div>
                            <span class="requirementsDetails activity-details" id="requirementsDetails" >
                                @php $i=0; @endphp
                                @foreach($arrRequirements as $key=>$val)
                                <div class="removerequirementsdata row pt10">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <div class="form-group" id="reqid_group<?php echo$i?>"> <div class="form-icon-user">
                                            <!-- {{ Form::select('reqid[]',$requirement,$val->req_id,array('class' => 'form-control reqid','required'=>'required','id'=>'reqid'.$i)) }} -->
                                           {{$val->req_description}}
                                        </div>
                                        {{ Form::hidden('cfid[]',$val->cfid, array('id' => 'cfid')) }}
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                @if(empty($val->cdprl_name))
                                                <span>N/A</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div> -->
                                    <!-- <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                <input class="form-control" name="reqfile[]" type="file" value="">
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-lg-2 col-md-2 col-sm-2" style="text-align:end;padding-right:35px;">
                                        <div class="form-group">
                                            <td> @if($val->cdprl_name)<a class="btn" href="{{config('constants.remoteserverurl')}}/{{$val->cdprl_path}}/{{$val->cdprl_name}}" target='_blank'><i class='ti-download'></i></a>@else<a class="" herf="#"><i class='fas fa-ban nofile'></i></a>@endif 

                                             <!-- <button type="button" class="btn btn-danger btn_cancel_requirement" fileid="{{$val->cfid}}" value="{{$val->id}}" style="    padding: 3px 8px;"><i class="ti-trash"></i></button> -->
                                         </td>
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
            @if($data->is_approved == 0)
            <div class="button" style="background: #ff3a6e;padding-left: 8px;color: #fff;border-radius: 5px;">
                <input type="button" id="decline" value="{{ ($data->id)>0?__('Decline'):__('Decline')}}" class="btn btn-primary" style="background: #ff3a6e;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <div class="button" style="background: #6fd943;padding-left: 8px;color: #fff;border-radius: 5px;">
                <input type="button" id="approve" value="{{ ($data->id)>0?__('Accept'):__('Accept')}}" class="btn btn-primary" style="background: #6fd943;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            @endif
        <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Save Payment')}}" class="btn  btn-primary"> -->
    </div>


{{Form::close()}} 
<script src="{{ asset('js/Cpdo/online_add_developmentapp.js') }}?rand={{ rand(0000,9999) }}"></script> 





