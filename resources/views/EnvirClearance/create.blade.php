{{ Form::open(array('url' => 'environmental-clearance','class'=>'formDtls','id'=>'environmentalclearance')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
	{{ Form::hidden('created_by',$data->created_by, array('id' => 'id')) }}
    {{ Form::hidden('busn_id',$busn_id, array('id' => 'busn_id')) }}
    {{ Form::hidden('bend_id',$bend_id, array('id' => 'bend_id')) }}
	{{ Form::hidden('ebac_app_code','ENRO', array('id' => 'ebac_app_code')) }}
	{{ Form::hidden('ebac_app_no',$data->ebac_app_no, array('id' => 'id')) }}
	@if($data->id > 0)
	@php
        $dclass = ($data->ebac_status==2 || $data->ebac_status==3)?'disabled-status':'';
    @endphp
	@endif
	<style>
        .modal-body {
            position: relative;
            flex: 1 1 auto;
            padding: 1.25rem;
            padding-bottom: 5%;
            width: 100%;
            overflow: hidden;
        }
        .modal.show .modal-dialog {
            transform: none;
            padding-top: 40px;
        }
        .modal-content {
        	position: relative;
        	margin-right: 50%;
        	display: flex;
        	flex-direction: column;
        	width: 100%;
        	pointer-events: auto;
        	background-color: #ffffff;
        	background-clip: padding-box;
        	border: 1px solid rgba(0, 0, 0, 0.2);
        	border-radius: 15px;
        	outline: 0;
        	width: 700px;
        	position: absolute;
        	float: left;
        	margin-left: 55%;
        	margin-top: 63%;
        	transform: translate(-50%, -50%);
        }
</style>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="accordion"   style="padding-top: 10px;">  
                    <div  class="accordion accordion-flush">
                        <div class="accordion-item">
                        <div  class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                            <div class="basicinfodiv">
                            <div class="row">    
                                 <div class="col-md-8">
                                    <div class="form-group">
                                        {{ Form::label('businessname', __('Business Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('tfoc_id') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::text('businessname',$arrbploBusiness->busn_name, array('class' => 'form-control ','id'=>'businessname','required'=>'required','readonly'=>'readonly')) }}
                                        </div>
                                        <span class="validate-err" id="err_businessname"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('ebac_app_year', __('Year Applied'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('ebac_app_year') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::text('ebac_app_year',$data->ebac_app_year, array('class' => 'form-control','id'=>'ebac_app_year','required'=>'required','readonly'=>'readonly')) }}
                                        </div>
                                        <span class="validate-err" id="err_ebac_app_year"></span>
                                    </div>
                                </div>
                             </div>
                             <div class="row"> 
								 <div class="col-md-12">
									<div class="form-group">
										{{ Form::label('completeaddress', __('Business Address'),['class'=>'form-label']) }}<span class="text-danger">*</span>
										<span class="validate-err">{{ $errors->first('completeaddress') }}</span>
										<div class="form-icon-user">
											{{ Form::text('completeaddress',$complete_address, array('class' => 'form-control ','id'=>'completeaddress','required'=>'required','readonly'=>'readonly')) }}
										</div>
										<span class="validate-err" id="err_completeaddress"></span>
									</div>
								</div>
                            </div>
                            <div class="row"> 
								 <div class="col-md-12">
									<div class="form-group">
										{{ Form::label('taxpaername', __('Taxpayer Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
										<span class="validate-err">{{ $errors->first('client_id') }}</span>
										<div class="form-icon-user">
										@if($arrbploBusiness->suffix)
											{{ Form::text('taxpaername',$arrbploBusiness->rpo_first_name." ".$arrbploBusiness->rpo_middle_name." ".$arrbploBusiness->rpo_custom_last_name.", ".$arrbploBusiness->suffix, array('class' => 'form-control','id'=>'taxpaername','required'=>'required','readonly'=>'readonly')) }} 
										@else
											{{ Form::text('taxpaername',$arrbploBusiness->rpo_first_name." ".$arrbploBusiness->rpo_middle_name." ".$arrbploBusiness->rpo_custom_last_name, array('class' => 'form-control','id'=>'taxpaername','required'=>'required','readonly'=>'readonly')) }} 
										@endif
										  
										</div>
										<span class="validate-err" id="err_client_id"></span>
									</div>
								</div>
                           </div>
                          <div class="row"> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('ebac_date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('ebac_date') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('ebac_date',$data->ebac_date, array('class' => 'form-control','id'=>'ebac_date','required'=>'required','readonly'=>'readonly')) }}
                                    </div>
                                    <span class="validate-err" id="err_ebir_date"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('ebac_issuance_date', __('Issuance Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('ebac_issuance_date') }}</span>
                                    <div class="form-icon-user">
										@if($data->id > 0)
                                        {{ Form::date('ebac_issuance_date',$data->ebac_issuance_date, array('class' => 'form-control','id'=>'ebac_issuance_date','required'=>'required')) }}
										@else
										{{ Form::date('ebac_issuance_date',date('Y-m-d'), array('class' => 'form-control','id'=>'ebac_issuance_date','required'=>'required')) }}
										@endif
									</div>
                                    <span class="validate-err" id="err_ebir_date"></span>
                                </div>
                            </div>
							<div class="col-md-4">
								<div class="form-group">
									{{ Form::label('business_id_no', __('Business ID No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
									<span class="validate-err">{{ $errors->first('business_id_no') }}</span>
									<div class="form-icon-user">
										{{ Form::text('business_id_no',$arrbploBusiness->busns_id_no, array('class' => 'form-control','id'=>'business_id_no','required'=>'required','readonly'=>'readonly')) }}
									</div>
									<span class="validate-err" id="err_ebir_date"></span>
								</div>
							  </div>
                           </div>
						   <div class="row">
								<div class="col-xl-12">
									<div class="card">
										<div class="card-body table-border-style">
											<div class="table-responsive">
												<table class="table" id="Jq_busn_plan">
													<thead>
														<tr>
															<th>{{__('No.')}}</th>
															<th>{{__('Code')}}</th>
															<th>{{__("Line of Business")}}</th>
														</tr>
													</thead>
													<tbody>
														@php $i=1;  @endphp  
														@foreach($busn_plan as $key=>$val)
														@php
														$wrap_desc = wordwrap($val->subclass_description, 100, "<br />\n");
														$desc="<div class='showLess'>".$wrap_desc."</div>";
														@endphp
														<tr>
															<td>{{$i}}</td>
															<td>{{$val->subclass_code}}</td>
															<td>{{$val->subclass_description}}</td>
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
                           <div class="row">    
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::label('ebac_remarks', __('Comment|Recommendation'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('ebac_remarks') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::text('ebac_remarks',$data->ebac_remarks, array('class' => 'form-control','id'=>'ebac_remarks','required'=>'required')) }}
                                        </div>
                                        <span class="validate-err" id="err_pend_remarks"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if($data->ebac_remarks)
                                @if($data->ebac_approved_by_status == 0 && $ebacapproved_user_id == $current_user_id)
                               <!--  <div class="col-md-8">
                                    <div class="form-group" id="ebac_approved_byparrent">
                                        {{ Form::label('ebac_approved_by', __('Approved By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('ebac_approved_by') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('ebac_approved_by',$hremployees,$data->ebac_approved_by, array('class' => 'form-control select3','id'=>'ebac_approved_by','required'=>'required')) }}
                                        </div>
                                        <span class="validate-err" id="err_pebir_inspected_by"></span>
                                    </div>
                                </div> -->
                                 <div class="col-md-8">
                                    <div class="form-group" style="pointer-events: none;">
                                        {{ Form::label('ebac_approved_by', __('Approved By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('ebac_approved_by') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('ebac_approved_by',$hremployees,$data->ebac_approved_by, array('class' => 'form-control','id'=>'ebac_approved_by','required'=>'required','readonly')) }}
                                        </div>
                                        <span class="validate-err" id="err_ebac_approved_by"></span>
                                    </div>
                                </div>
                                @elseif($data->ebac_approved_by_status == 1)
								<div class="col-md-8">
                                    <div class="form-group" id="ebac_approved_byparrent">
                                        {{ Form::label('ebac_approved_by', __('Approved By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('ebac_approved_by') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('ebac_approved_by',$hremployees,$data->ebac_approved_by, array('class' => 'form-control','id'=>'ebac_approved_by','required'=>'required','readonly')) }}
                                        </div>
                                        <span class="validate-err" id="err_ebac_approved_by"></span>
                                    </div>
                                </div>
                                @else
                                 <div class="col-md-8">
                                    <div class="form-group" id="ebac_approved_byparrent">
                                        {{ Form::label('ebac_approved_by', __('Approved By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('ebac_approved_by') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('ebac_approved_by',$hremployees,$data->ebac_approved_by, array('class' => 'form-control select3','id'=>'ebac_approved_by','required'=>'required')) }}
                                        </div>
                                        <span class="validate-err" id="err_ebac_approved_by"></span>
                                    </div>
                                </div>
                                @endif
                                @else
                                 <div class="col-md-8">
                                    <div class="form-group" id="ebac_approved_byparrent">
                                        {{ Form::label('ebac_approved_by', __('Approved By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('ebac_approved_by') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('ebac_approved_by',$hremployees,$data->ebac_approved_by, array('class' => 'form-control select3','id'=>'ebac_approved_by','required'=>'required')) }}
                                        </div>
                                        <span class="validate-err" id="err_ebac_approved_by"></span>
                                    </div>
                                </div>
                                @endif  
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('ebac_approver_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('ebac_approver_position') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::text('ebac_approver_position',$data->ebac_approver_position, array('class' => 'form-control','id'=>'ebac_approver_position','required'=>'required')) }}
                                        </div>
                                        <span class="validate-err" id="err_ebir_inspector_position"></span>
                                    </div>
                                </div>
                             </div>
                        </div>
                        @if($data->id > 0 && $current_user_id == $ebacapproved_user_id && $data->ebac_approved_by_status == 0)
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::checkbox('ebac_approved_by_status', '1', ($data->ebac_approved_by_status =='1')?true:false, array('id'=>'ebac_approved_by_status','class'=>'form-check-input code')) }}
                                    {{ Form::label('ebac_approved_by_status', __('Approved: Confirmation'),['class'=>'form-label','style'=>'color:red']) }}
                                </div>
                            </div>
                        </div>
						@elseif($data->ebac_approved_by_status == 1 && $data->id > 0)
						<div class="row">
                            <div class="col-md-4" style="pointer-events: none;">
                                <div class="form-group">
                                    {{ Form::checkbox('ebac_approved_by_status', '1', ($data->ebac_approved_by_status =='1')?true:false, array('id'=>'ebac_approved_by_status','class'=>'form-check-input code')) }}
                                    {{ Form::label('ebac_approved_by_status', __('Approved: Confirmation'),['class'=>'form-label','style'=>'color:red']) }}
                                </div>
                            </div>
                        </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
			@if($data->id > 0)
			<div class="col-md-12">
                <!--- Start Status--->
                <div class="row" >
                    <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample3">  
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
								@if($data->id > 0)
								<div id="flush-collapse3" class="accordion-collapse collapse" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
								@else
								<div id="flush-collapse3" class="accordion-collapse collapse show" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
								@endif
                                    <div class="basicinfodiv">
                                        <div class="row">
										  <div class="row">
												<div class="col-lg-8 col-md-8 col-sm-8">
													<div class="form-group">
														{{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
														<div class="form-icon-user">
															{{ Form::input('file','document_name','',array('class'=>'form-control $dclass'))}}  
														</div>
														<span class="validate-err" id="err_document"></span>
													</div>
												</div>
												
												<div class="col-lg-4 col-md-4 col-sm-4 mt-4">
													<button type="button" style="float: right;" class="btn btn-primary {{$dclass}}" id="uploadAttachmentInspection">Upload File</button>
												</div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Document Title</th>
                                                                <th>Action</th>
                                                               <!-- <th>Action</th> -->
                                                            </tr>
                                                        </thead>
                                                        <thead id="DocumentDtls">
														   <?php echo $data->ebac_document ?>
                                                            @if(empty($data->ebac_document))
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
                </div>
                <!--- End Status --->
            </div> 
			@endif
			@if(($data->id)>0 && $data->ebac_approved_by_status == 1)
			<div class="modal-footer" style="float:left;padding-bottom: 100px;padding-top: 38px;">
			 <a  type="button" href="{{ url('/envirclearance/EnvirClearance?id=').''.$data->id }}" target="_blank" style="float: left;" class="btn btn-primary btnPrintclearance mt-2 digital-sign-btn" id="{{$data->id}}"><i class="ti-printer text-white" ></i> Print</a>
			</div>
			@endif
            <div class="modal-footer" style="float:right;padding-bottom: 100px;padding-top: 50px;">
                <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" style="margin-right: 16px" data-bs-dismiss="modal">
                <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
					<i class="fa fa-save icon"></i>
					<input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?_('Update'):_('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
				</div> 
            </div>
			
        </div>
    </div>
    
{{Form::close()}}
<!-- <script src="{{ asset('js/ajax_validation.js') }}"></script>   -->
<script src="{{ asset('js/add_EnvirClearance.js')}}?rand={{ rand(000,999) }}"></script> 
<script>
$(document).ready(function () {
    $('form').submit(function(e) {
        e.preventDefault();
        $(".validate-err").html('');
        var myForm = $(this);
        myForm.find("input[name='submit']").unbind("click");
        // var myform = $('form');
        var disabled = myForm.find(':input:disabled').removeAttr('disabled');
        var data = myForm.serialize().split("&");
        disabled.attr('disabled','disabled');
        var obj={};
        for(var key in data){
            obj[decodeURIComponent(data[key].split("=")[0])] = decodeURIComponent(data[key].split("=")[1]);
        }
        $.ajax({
            url :$(this).attr("action")+'/formValidation', // json datasource
            type: "POST", 
            data: obj,
            dataType: 'json',
            success: function(html){
                if(html.ESTATUS){
                    $("#err_"+html.field_name).html(html.error)
                    $("#"+html.field_name).focus();
                    $("#main_error_msg").html(html.error)

                }else{
					setConfirmAlert(e); 
                }
            }
        })
    });
	function setConfirmAlert(e){
		$("#environmentalclearance input[name='submit']").unbind("click");
		const swalWithBootstrapButtons = Swal.mixin({
			customClass: {
				confirmButton: 'btn btn-success',
				cancelButton: 'btn btn-danger'
			},
			buttonsStyling: false
		})
		swalWithBootstrapButtons.fire({
			title: 'Are you sure?',
			text: "This will save the current changes.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes',
			cancelButtonText: 'No',
			reverseButtons: true
		}).then((result) => {
			if(result.isConfirmed){
				$('#environmentalclearance').unbind('submit');
				$("#environmentalclearance input[name='submit']").trigger("click");
				$("#environmentalclearance input[name='submit']").attr("type","button");
			}
		});
   }
	
});
</script>
  
 
           




