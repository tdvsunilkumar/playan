{{ Form::open(array('url' => 'cpdodevelopmentapp','class'=>'formDtls','id'=>'storeJobService','enctype'=>'multipart/form-data')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('transid',$orderpayment->id, array('id' => 'transid')) }}
{{ Form::hidden('totalamount','', array('id' => 'totalamount')) }}
@php $readonlyclass = ($data->id > 0 )?'disabled-field':'';@endphp

<style type="text/css">
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
</style>
<div class="modal-body">
    <div class="row" style="margin-top: -25px;">
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
                            <div class="basicinfodiv" id="applicationdiv">
                                <!--  <a data-toggle="modal" href="javascript:void(0)" id="loadAddserviceForm" class="btn btn-primary" type="add">Add Service</a> -->
                                <!--------------- Land Apraisal Listing Start Here------------------>
                                <div CLASS="row"> 
                                    <div class="col-md-4" >
                                        <div class="form-group" id="ownernamediv">
                                            {{ Form::label('client_id', __('Client Name'),['class'=>'form-label']) }}<span class="text-danger">*</span> &nbsp;&nbsp;&nbsp;
                                            <span class="validate-err">{{ $errors->first('client_id') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('client_id',$arrOwners,$data->client_id, array('class' => 'form-control','id'=>'client_id','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_client_id"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('tfoc_idtype', __('Service Type Fee'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('tfoc_idtype') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('tfoc_idtype',$apptype,$data->tfoc_idtype, array('class' => 'form-control select3 '.$readonlyclass,'id'=>'tfoc_idtype','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_tfoc_idtype"></span>
                                            </div>
                                        </div>
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
                                   
                                    
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            {{ Form::label('cdp_address', __('Address of Firm'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cdp_address') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('cdp_address',$data->cdp_address, array('class' => 'form-control field-disabled','id'=>'cdp_address','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cdp_address"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('cdp_email_address', __('Email Address'),['class'=>'form-label']) }}
                                            <span class="validate-err">{{ $errors->first('cdp_email_address') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::email('cdp_email_address',$data->cdp_email_address, array('class' => 'form-control field-disabled','id'=>'cdp_email_address')) }}
                                            </div>
                                            <span class="validate-err" id="err_cdp_email_address"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('cdp_phone_no', __('Telephone No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('cdp_phone_no') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::number('cdp_phone_no',$data->cdp_phone_no, array('class' => 'form-control field-disabled','id'=>'cdp_phone_no','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_cdp_phone_no"></span>
                                        </div>
                                    </div>
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('nameofproject', __('Name of Project'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <span class="validate-err">{{ $errors->first('nameofproject') }}</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('nameofproject',$data->nameofproject, array('class' => 'form-control ','id'=>'nameofproject','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_nameofproject"></span>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('locationofproject', __('Location of Project'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <span class="validate-err">{{ $errors->first('locationofproject') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('locationofproject',$arrgetBrgyCode,$data->locationofproject,array('class'=>'form-control select3','id'=>'locationofproject','required'=>'required')) }}
                                                </div>
                                                <span class="validate-err" id="err_locationofproject"></span>
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
                        <h6 class="accordion-header" id="flush-headingten">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseten" aria-expanded="false" aria-controls="flush-headingfive" >
                                <h6 class="sub-title accordiantitle">{{__("Line Of Payments")}}</h6>
                            </button>
                        </h6>
                        <div  class="accordion-collapse collapse show" aria-labelledby="flush-headingten" data-bs-parent="#accordionFlushExample5">
                            <div class="basicinfodiv">
                                <div class="row" style="background: #20b7cc;color: #fff;padding-top: 5px;">
                                   <div class="col-lg-1 col-md-1 col-sm-1">#</div>
                                   <div class="col-lg-5 col-md-5 col-sm-5"><label for="tfoc_idnew" class="form-label">PAYMENT DESCRIPTION</label></div>
                                   <div class="col-lg-2 col-md-2 col-sm-2"><label for="tfoc_idnew" class="form-label">NUMBER</label></div>
                                   <div class="col-lg-2 col-md-2 col-sm-2"><label for="tfoc_idnew" class="form-label">TYPE</label></div>
                                   <div class="col-lg-2 col-md-2 col-sm-2"><label for="tfoc_idnew" class="form-label">AMOUNT</div>
                                </div>
                                <div  id="paymentlinediv">

                                </div>
                                <div class="row" style="padding-top: 5px;">
                                    <div class="col-sm-10">
                                        {{ Form::label('totalamount', __('Total Amount'),['class'=>'form-label']) }}
                                    </div>
                                    <div class="col-sm-2">
                                        {{ Form::text('cdp_total_amount',number_format((float)$data->cdp_total_amount , 2, '.', ''), array('class' => 'form-control disabled-field','id'=>'cdp_total_amount','required'=>'required')) }}
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
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    {{Form::label('subclass_id',__('Requirements'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('taxable_item_name',__('Status'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    {{Form::label('taxable_item_qty',__('File'),['class'=>'form-label numeric'])}}
                                </div>
                               
                                <div class="col-lg-2 col-md-2 col-sm-2" style="text-align:center;padding-right:35px;"> <span class="btn_addmore_requirements btn" id="btn_addmore_requirements" style="color:white;padding: 5px 8px;"><i class="ti-plus"></i></span></div>
                            </div>
                            <span class="requirementsDetails activity-details" id="requirementsDetails" >
                                @php $i=0; @endphp
                                @foreach($arrRequirements as $key=>$val)
                                <div class="removerequirementsdata row pt10">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group" id="reqid_group<?php echo$i?>"> <div class="form-icon-user">{{ Form::select('reqid[]',$requirement,$val->req_id,array('class' => 'form-control reqid','required'=>'required','id'=>'reqid'.$i)) }}</div>
                                        {{ Form::hidden('cfid[]',$val->cfid, array('id' => 'cfid')) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                @if(empty($val->cdprl_name))
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
                                    <div class="col-lg-2 col-md-2 col-sm-2" style="text-align:left;padding: 0px;">
                                        <div class="form-group">
                                            <td> @if($val->cdprl_name)<a class="btn" href="{{asset('uploads/')}}/{{$val->cdprl_path}}/{{$val->cdprl_name}}" target='_blank'><i class='ti-download'></i></a>@else<a class="" herf="#"><i class='fas fa-ban nofile'></i></a>@endif  <button type="button" class="btn btn-danger btn_cancel_requirement" fileid="{{$val->cfid}}" value="{{$val->id}}" style="    padding: 3px 8px;"><i class="ti-trash"></i></button></td>
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
        <!--  <button id="btnPrintapplication" type="button" style="float: right;" value="{{ url('/cpdoapplication/printapplication?id=').''.$data->id }}"   class="btn btn-primary"><i class="ti-printer text-white"></i>&nbsp;Print</button> -->
        @endif
       
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        @if(($data->id)>0)
          @if(($data->csd_id) != 9)
                <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?__('Update'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
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
</div>
{{Form::close()}}

<script src="{{ asset('js/Cpdo/ajax_validationapp.js') }}"></script>  
<script src="{{ asset('js/Cpdo/add_developmentapp.js') }}?rand={{ rand(0000,9999) }}"></script> 
<div id="hidenRequirementHtml" class="hide">
     <div class="row removerequirementsdata" style="padding: 5px 0px;">
         <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group" id="reqid_group">
                <div class="form-icon-user">{{ Form::select('reqid[]',$requirement,'',array('class' => 'form-control reqid','required'=>'required','id'=>'reqid0')) }}</div>
            </div>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1">
        </div>
         <div class="col-lg-3 col-md-3 col-sm-3">
            <div class="form-group"><input class="form-control" name="reqfile[]" type="file" value=""></div>
        </div>
         <div class="col-lg-2 col-md-2 col-sm-2" style="text-align:left;padding-left: 40px;"><div class="form-group"><button type="button" class="btn btn-danger btn_cancel_requirement" style="    padding: 3px 8px;"><i class="ti-trash"></i></button></div></div>
    </div>
</div>

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









