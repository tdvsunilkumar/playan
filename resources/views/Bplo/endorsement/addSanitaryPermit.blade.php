{{ Form::open(array('url' => 'Endrosement/sanitaryPermit','enctype'=>'multipart/form-data','id'=>'sanitaryPermit')) }}
    {{ Form::hidden('busn_id',$busn_id, array('id' => 'busn_id')) }}
    {{ Form::hidden('bend_id',$end_id, array('id' => 'bend_id')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('has_app_no',$data->has_app_no, array('id' => 'has_app_no')) }}
    <style type="text/css">
        .modal-body {
            position: relative;
            flex: 1 1 auto;
            padding: 1.25rem;
            padding-bottom: 5%;
            overflow: hidden;
        }
        .accordion-button::after{background-image: url();}
         .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #8080802e;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .field-requirement-details-status label{padding-top:5px;}
    .modal-lg, .modal-xl {
        max-width: 975px !important;
    }
   
    </style>
    <link href="{{ asset('assets/js/yearpicker.css')}}" rel="stylesheet">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('bend_id', __('Business Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bend_id') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('busn_name', $busn_name, array('class' => 'form-control','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_bend_id"></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('has_app_year', __('Year'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('has_app_year') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('has_app_year', $data->has_app_year, array('class' => 'yearpicker form-control')) }}
                    </div>
                    <span class="validate-err" id="err_has_app_year"></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('has_app_no', __('Application No.'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('has_transaction_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('has_transaction_no', $data->has_transaction_no, array('class' => 'form-control','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_has_transaction_no"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('complete_address', __('Complete Address'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('complete_address') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('complete_address', str_replace(',', ', ', $complete_address), array('class' => 'form-control','id'=>"complete_address",'readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_complete_address"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('has_permit_no', __('Permit No.'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('has_permit_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('has_permit_no', $data->has_permit_no, array('class' => 'form-control','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_has_is_paid"></span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('owner', __('Taxpayer Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('owner') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('owner', $owner, array('class' => 'form-control','id'=>"owner",'readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_owner"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('has_type_of_establishment', __('Type of Establishment'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('has_type_of_establishment') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('has_type_of_establishment', $data->has_type_of_establishment, array('class' => 'form-control','id' => 'has_type_of_establishment','required'=>'required')) }}
                         <div id="suggestionsDiv"></div>
                    </div>
                    <span class="validate-err" id="err_has_type_of_establishment"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('has_issuance_date', __('Issuance Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('has_issuance_date') }}</span>
                    <div class="form-icon-user">
                         {{ Form::date('has_issuance_date', $data->has_issuance_date, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_has_issuance_date"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('has_expired_date', __('Expiration Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('has_expired_date') }}</span>
                    <div class="form-icon-user">
                         {{ Form::date('has_expired_date', $data->has_expired_date, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_has_expired_date"></span>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('has_remarks', __('Comments | Remarks'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('has_remarks') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('has_remarks',$data->has_remarks, array('class' => 'form-control')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('has_remarks', __('Total Employees'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('has_remarks') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('employeesTotal', $Sanitarytotal_employees, array('class' => 'form-control','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('has_remarks', __('Total Healthcards'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('has_remarks') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('Healthcards',$total_employeeHealthCard, array('class' => 'form-control','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table" id="Jq_busn_plan" style="border: 1px solid #ccc;">
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid #fff;">{{__('No.')}}</th>
                                            <th style="border: 1px solid #fff;">{{__('Code')}}</th>
                                            <th style="border: 1px solid #fff;">{{__("Line of Business")}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=0;  @endphp  
                                        @foreach($busn_plan as $key=>$val)
                                        @php
                                        $wrap_desc = wordwrap($val->subclass_description, 100, "<br />\n");
                                        $desc="<div class='showLess'>".$wrap_desc."</div>";
                                        @endphp
                                        <tr>
                                            <td style="border: 1px solid #ccc;">{{$i + 1}}</td>
                                            <td style="border: 1px solid #ccc;">{{$val->subclass_code}}</td>
                                            <td style="border: 1px solid #ccc;">{{$val->subclass_description}}</td>
                                        </tr>
                                        @php $i++; @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
                <div class="col-md-6">
                    <div class="form-group" id="has_recommending_approver_div">
                        {{ Form::label('has_recommending_approver', __('Recommending Approval'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('has_recommending_approver') }}</span>
                        <div class="form-icon-user">
                             @if($data->has_transaction_no)
                            {{ Form::select('has_recommending_approver',$employee,$data->has_recommending_approver, array('class' => 'form-control','id'=>'has_recommending_approver',($auth->id == $data->has_recommending_approver || $auth->id == $data->has_approver || $data->has_recommending_approver_status == 1)? 'disabled':'')) }}
                            @else
                            {{ Form::select('has_recommending_approver',$employee,$data->has_recommending_approver, array('class' => 'form-control','id'=>'has_recommending_approver',($auth->id == $data->has_recommending_approver || $auth->id == $data->has_approver || $data->has_recommending_approver_status == 1)? '':'')) }}
                            @endif
                        </div>
                        <span class="validate-err" id="err_has_recommending_approver"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('has_recommending_approver_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('has_recommending_approver_position') }}</span>
                        <div class="form-icon-user">
                            {{ Form::text('has_recommending_approver_position', $data->has_recommending_approver_position, array('class' => 'form-control','id'=>'has_recommending_approver_position',($auth->id == $data->has_recommending_approver || $auth->id == $data->has_approver || $data->has_recommending_approver_status == 1)? '':'')) }}
                        </div>
                        <span class="validate-err" id="err_has_recommending_approver_position"></span>
                    </div>
                </div>
                @if($data->id > 0 && $auth->id == $data->has_recommending_approver || $data->has_recommending_approver_status == 1)
                <div class="col-md-12">
                    <div class="form-group">
                        <span class="validate-err">{{ $errors->first('has_recommending_approver_status') }}</span>
                        <div class="form-icon-user">
                            {{ Form::checkbox('has_recommending_approver_status', '0', ($data->has_recommending_approver_status)?true:false, array('id'=>'has_recommending_approver_status','class'=>'form-check-input code', (isset($data->id) && $auth->id == $data->has_recommending_approver && $data->has_recommending_approver_status == 0) ? '' : 'disabled')) }}
                           <b style="color:red;">Approved: Confirmation</b>

                        </div>
                        <span class="validate-err" id="err_has_recommending_approver_status"></span>
                    </div>
                </div>
                @endif
                <div class="col-md-6">
                    <div class="form-group" id="has_approver_div">
                        {{ Form::label('has_approver', __('Approved By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('has_approver') }}</span>
                        <div class="form-icon-user">
                            @if($data->has_transaction_no)
                            {{ Form::select('has_approver',$employee,$data->has_approver, array('class' => 'form-control','id'=>'has_approver',($auth->id == $data->has_approver || $data->has_approver_status == 1)? 'disabled':'')) }}
                            @else
                            {{ Form::select('has_approver',$employee,$data->has_approver, array('class' => 'form-control','id'=>'has_approver',($auth->id == $data->has_recommending_approver || $auth->id == $data->has_approver || $data->has_approver_status == 1)? '':'')) }}
                            @endif
                        </div>
                        <span class="validate-err" id="err_has_approver"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('has_approver_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('has_approver_position') }}</span>
                        <div class="form-icon-user">
                            {{ Form::text('has_approver_position',  $data->has_approver_position, array('class' => 'form-control','id'=>'has_approver_position',($auth->id == $data->has_recommending_approver || $auth->id == $data->has_approver || $data->has_approver_status == 1) ? '' : '')) }}
                        </div>
                        <span class="validate-err" id="err_has_approver_position"></span>
                    </div>
                </div>
                @if($data->has_recommending_approver_status == 1)
                @if($data->id > 0 && $auth->id == $data->has_approver || $data->has_approver_status == 1)
                <div class="col-md-12">
                    <div class="form-group">
                        <span class="validate-err">{{ $errors->first('has_approver_status') }}</span>
                        <div class="form-icon-user">
                            {{ Form::checkbox('has_approver_status', '0', ($data->has_approver_status)?true:false, array('id'=>'has_approver_status','class'=>'form-check-input code', (isset($data->id) && $auth->id == $data->has_approver && $data->has_approver_status == 0) ? '' : 'disabled')) }}
                            <b style="color:red;">Approved: Confirmation</b>
                        </div>
                        <span class="validate-err" id="err_has_approver_status"></span>
                    </div>
                </div>
                @endif
                @else
                @endif
            
                @if($data->id > 0)
                    <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample3">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading3">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Documentary Requirements")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapse3" class="accordion-collapse collapse show" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group" id="end_requirement_id_div">
                                                    {{ Form::label('requirement_id', __('Requirements'),['class'=>'form-label']) }}
                                                    <span class="text-danger">*</span>
                                                    <div class="form-icon-user">
                                                        {{ Form::select('end_requirement_id',$requirements,'', array('class' => 'form-control','id'=>'end_requirement_id')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_end_requirement_id"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-5 col-md-5 col-sm-5">
                                                <div class="form-group">
                                                    {{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('file','document_name','',array('class'=>'form-control'))}}  
                                                    </div>
                                                    <span class="validate-err" id="err_document"></span>
                                                </div></div>
                                                <div class="col-lg-1 col-md-1 col-sm-1" style="padding-top: 26px;">
                                                    <button type="button" style="float: right;" class="btn btn-primary" id="uploadAttachment">Upload File</button>
                                                </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: -20px;"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Document Title</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <thead id="DocumentDtls">
                                                            <?php echo $arrDocumentDetailsHtml?>
                                                            @if(empty($arrDocumentDetailsHtml))
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
                    <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExampleUpload">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-headingUpload">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseUpload" aria-expanded="false" aria-controls="flush-collapseUpload">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Upload")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapseUpload" class="accordion-collapse collapse" aria-labelledby="flush-headingUpload" data-bs-parent="#accordionFlushExampleUpload">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                            <div class="col-lg-11 col-md-11 col-sm-11">
                                                <div class="form-group">
                                                    {{ Form::label('document_names', __('Document'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('file','document_names','',array('class'=>'form-control'))}}  
                                                    </div>
                                                    <span class="validate-err" id="err_documents"></span>
                                                </div>
                                            </div>
                                                <div class="col-lg-1 col-md-1 col-sm-1" style="    padding-top: 26px;">
                                                    <button type="button" style="float: right;" class="btn btn-primary" id="uploadAttachmentonly">Upload File</button>
                                                </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:-20px"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>File Name</th>                                                           
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <thead id="DocumentDtlsss">
                                                            <?php echo $data->arrDocumentDetailsHtml?>
                                                            @if(empty($data->arrDocumentDetailsHtml))
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
        <div class="row">
            <div class="col-md-3" style="padding-top:17px;">
                @if($data->has_approver_status == 1 && $data->has_recommending_approver_status == 1)
                    <a href="{{ url('/healthy-and-safety/app-sanitary/hoapphealthsanitaryprint?id=').''.$data->id }}" target="_blank" title="Print Application Form" style="background: #20b7cc;padding: 10px;color: #fff;" data-title="Print" class="mx-3 btn print btn-sm digital-sign-btn" id="{{$data->id}}">
                                <i class="ti-printer text-white"></i> Print
                            </a>
                @endif
            </div>
            <div class="col-md-9"> 
            @if(($data->id)>0)   
            <div class="modal-footer" style="">
            @else
            <div class="modal-footer" style="padding-top: 100px;">
            @endif
                    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                    <input type="submit" id="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Create')}}" class="btn  btn-primary">
                </div>
            </div>  
        </div>      
    </div>
</div> 
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('assets/js/yearpicker.js') }}"></script>
<script src="{{ asset('js/add_hoappsanitary.js') }}?rand={{ rand(000,999) }}">
    
</script>
<script>
$(document).ready(function() {
    var shouldSubmitForm = false;
    $('#submit').click(function (e) {
        if (!shouldSubmitForm) {
            var form = $('#sanitaryPermit');
            Swal.fire({
                title: "Are you sure?",
                html: '<span style="color: red;">This process cannot be UNDO.</span>',
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
                    $('#submit').click();
                } else {
                    console.log("Form submission canceled");
                }
            });

            e.preventDefault();
        }
    });
});
</script>


