{{ Form::open(array('url' => 'blood-sugar-test','class'=>'formDtls', 'id'=>'Bloodsugartest')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('lab_req_id',$data->lab_req_id, array('id' => 'lab_req_id')) }}
{{ Form::hidden('bs_lab_no',$data->bs_lab_no, array('id' => 'bs_lab_no')) }}
<div class="modal-body">
   <div class="row pt10">
      <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample">
         <div class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingone">
                  <button class="accordion-button  btn-primary" type="button">
                     <h6 class="sub-title accordiantitle">Laboratory Details</h6>
                  </button>
               </h6>
               <div id="flush-collapseone" class="accordion-collapse collapse show">
                  <div class="basicinfodiv">
                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              {{ Form::label('cit_id', __('Name'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('cit_id') }}</span>
                              <div class="form-icon-user">
                                 {{ 
                                 Form::text('cit_fullname', 
                                 $data->patient->cit_fullname, 
                                 array(
                                 'id'=>'cit_fullname',
                                 'class' => 'form-control',
                                 'required'=>'required',
                                 'readonly')
                                 ) 
                                 }}
                                 {{ 
                                 Form::hidden('cit_id', 
                                 $data->cit_id, 
                                 array(
                                 'id'=>'cit_id',
                                 'class' => 'form-control',
                                 'required'=>'required',
                                 'readonly')
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_cit_id"></span>
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="form-group">
                              {{ Form::label('bs_age_days', __('Age'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('bs_age_days') }}</span>
                              <div class="form-icon-user">
                                 {{ 
                                 Form::text('bs_age_days', 
                                 $data->patient->age_human, 
                                 array(
                                 'id'=>'bs_age_days',
                                 'class' => 'form-control',
                                 'required'=>'required',
                                 'readonly')
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_bs_age"></span>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              {{ Form::label('sex', __('Sex'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('sex') }}</span>
                              <div class="form-icon-user">
                                 {{ 
                                 Form::text('sex', 
                                 $data->patient->gender(), 
                                 array(
                                 'id'=>'sex',
                                 'class' => 'form-control',
                                 'readonly')
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_lab_req_id"></span>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              {{ Form::label('bs_date', __('Date'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('bs_date') }}</span>
                              <div class="form-icon-user">
                                 {{ Form::dateTimeLocal('bs_date',date('Y-m-d'), array('id'=>'bs_date','class' => 'form-control','required'=>'required')) }}
                              </div>
                              <span class="validate-err" id="err_bs_date"></span>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              {{ Form::label('lab_control_no', __('Laboratory Control No.'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('lab_control_no') }}</span>
                              <div class="form-icon-user">
                                 {{ Form::text('lab_control_no',$data->lab_control_no, array('id'=>'lab_control_no','class' => 'form-control','readonly')) }}
                              </div>
                              <span class="validate-err" id="err_lab_control_no"></span>
                           </div>
                        </div>
                        <div class="col-md-5">
                           <div class="form-group">
                              {{ Form::label('bs_or_num', __('O.R. Number'),['class'=>'form-label']) }}
                              <div class="form-icon-user">
                                 {{ 
                                 Form::text('bs_or_num', 
                                 $data->bs_or_num, 
                                 array(
                                 'id'=>'bs_or_num',
                                 'class' => 'form-control',
                                 'readonly')
                                 ) 
                                 }}
                              </div>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              {{ Form::label('bs_lab_num', __('Lab. No.'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('bs_lab_num') }}</span>
                              <div class="form-icon-user">
                                 {{ Form::text('bs_lab_num',$data->bs_lab_num, array('id'=>'bs_lab_num','class' => 'form-control','readonly')) }}
                              </div>
                              <span class="validate-err" id="err_bs_lab_num"></span>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingone">
                  <button class="accordion-button  btn-primary" type="button">
                     <h6 class="sub-title accordiantitle">Health Officer Details</h6>
                  </button>
               </h6>
               <div id="flush-collapseone" class="accordion-collapse collapse show">
                  <div class="basicinfodiv">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('med_tech_id', __('Medical Technologist'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                              <span class="validate-err">{{ $errors->first('med_tech_id') }}</span>
                              <div class="form-icon-user" id="contain_med_tech">
                                 {{ 
                                 Form::select('med_tech_id',
                                 $getphysician,
                                 ($data->id > 0 && isset($data->med_tech_id))?$data->med_tech_id:$last_user_data->med_tech_id, 
                                 array(
                                 'class' =>'form-control ajax-select',
                                 'id'=>'med_tech_id',
                                 'data-url' => 'citizens/selectEmployee',
                                 'data-contain'=>'contain_med_tech',
                                 ($data->id && $data->is_posted === 1)?'disabled':''
                                 )
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_med_tech_id"></span>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('health_officer_id', __('Health Officer'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                              <span class="validate-err">{{ $errors->first('health_officer_id') }}</span>
                              <div class="form-icon-user" id="contain_health_officer">
                                 {{ 
                                 Form::select('health_officer_id',
                                 $getphysician,
                                 ($data->id > 0 && isset($data->health_officer_id))?$data->health_officer_id:$last_user_data->health_officer_id,
                                 array(
                                 'class' =>'form-control ajax-select',
                                 'id'=>'health_officer_id',
                                 'data-url' => 'citizens/selectEmployee',
                                 'data-contain'=>'contain_health_officer',
                                 ($data->id && $data->is_posted === 1)?'disabled':''
                                 )
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_health_officer_id"></span>
                           </div>
                        </div>
                        <!-- <div class="col-md-4">
                           <div class="form-group">
                           {{ Form::label('hp_code', __('Requesting Physician'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                           <span class="validate-err">{{ $errors->first('hp_code') }}</span>
                           <div class="form-icon-user" id="hp_code_contain">
                           	{{ 
                           	Form::select('hp_code',
                           	$getphysician,
                           	$data->hp_code, 
                           	array(
                           	'class' =>'form-control select3',
                           	'id'=>'hp_code',
                           	'data-contain'=>'hp_code_contain',
                           	($data->id && $data->is_posted === 1)?'disabled':''
                           	)
                           	) 
                           	}}
                           </div>
                           <span class="validate-err" id="err_hp_code"></span>
                           </div>
                           </div> -->
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('med_tech_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                              <span class="validate-err">{{ $errors->first('med_tech_position') }}</span>
                              <div class="form-icon-user">
                                 {{ 
                                 Form::text('med_tech_position',
                                 ($data->id > 0 && isset($data->med_tech_position))?$data->med_tech_position:$last_user_data->med_tech_position,
                                 array(
                                 'id'=>'med_tech_position',
                                 'class' => 'form-control',
                                 'required'=>'required',
                                 ($data->id && $data->is_posted === 1)?'disabled':''
                                 )
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_med_tech_position"></span>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('health_officer_position', __('Position'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('health_officer_position') }}</span>
                              <div class="form-icon-user">
                                 {{ 
                                 Form::text('health_officer_position',
                                 ($data->id > 0 && isset($data->health_officer_position))?$data->health_officer_position:$last_user_data->health_officer_position,
                                 array(
                                 'id'=>'health_officer_position',
                                 'class' => 'form-control',
                                 'required'=>'required',
                                 ($data->id && $data->is_posted === 1)?'disabled':''
                                 )
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_health_officer_position"></span>
                           </div>
                        </div>                       
                        <div class="col-md-6">
						   @if($esignisapproveds == \Auth::user()->id)
                           <div class="form-group" id="esign_is_approved">
                              {{ Form::label('esign_is_approved', __('Approved by Medical Technologist'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('esign_is_approved') }}</span>
                              <div class="form-icon-user">
                                 {{ Form::checkbox('esign_is_approved',  
                                 1, $data->esign_is_approved,
                                 ['id' => 'esign_is_approved']) }}
                              </div>
                              <span class="validate-err" id="err_esign_is_approved"></span>
                           </div>
						    @endif
                        </div>
						 <div class="col-md-6">
							@if($officerisapproved == \Auth::user()->id)
                              <div class="form-group" id="officer_is_approved" style="margin-left:5px;">
                                 {{ Form::label('officer_is_approved', __('Approved by Health Officer'),['class'=>'form-label']) }}
                                 <span class="validate-err">{{ $errors->first('officer_is_approved') }}</span>
                                 <div class="form-icon-user">
                                    {{ Form::checkbox('officer_is_approved',  
                                    1, $data->officer_is_approved,
                                    ['id' => 'officer_is_approved']) }}
                                 </div>
                                 <span class="validate-err" id="err_officer_is_approved"></span>
                              </div>
							@endif
                         </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="row pt10">
      <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample">
         <div class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingone">
                  <button class="accordion-button collapsed btn-primary" type="button">
                     <h6 class="sub-title accordiantitle">Laboratory Result</h6>
                  </button>
               </h6>
               <div id="flush-collapseone" class="accordion-collapse collapse show">
                  <div class="basicinfodiv">
                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              {{ Form::label('bs_type', __('Test'),['class'=>'form-label']) }}
                           </div>
                        </div>
                        @if($data == '')
                           @foreach($FbsFields as $field)
                           <div class="col-md-4">
                              <div class="form-group">
                                 <span class="validate-err">{{ $errors->first('bs_type') }}</span>
                                 <div class="form-icon-user">
                                    {{ Form::text('bs_type_id',$field->ho_service_name, array('id'=>'bs_type','class' => 'form-control','readonly')) }}
                                    {{ Form::hidden('bs_type',$data->service->ho_service_name, array('id'=>'bs_type','class' => 'form-control','readonly')) }}
                                 </div>
                                 <span class="validate-err" id="err_bs_type"></span>
                              </div>
                           </div>
                           @endforeach
                        @else
                           <div class="col-md-4">
                              <div class="form-group">
                                 <span class="validate-err">{{ $errors->first('bs_type') }}</span>
                                 <div class="form-icon-user">
                                    {{ Form::text('bs_type_id',(isset($data->lab_request->ho_service_name))?$data->lab_request->ho_service_name:'', array('id'=>'bs_type','class' => 'form-control','readonly')) }}
                                 </div>
                                 <span class="validate-err" id="err_bs_type"></span>
                              </div>
                           </div>
                        @endif
                     </div>
                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              {{ Form::label('bs_specimen', __('Specimen'),['class'=>'form-label']) }}
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <span class="validate-err">{{ $errors->first('bs_specimen') }}</span>
                              <div class="form-icon-user">
                                 {{ Form::text('bs_specimen',$data->bs_specimen, array('id'=>'bs_specimen','class' => 'form-control',($data->id && $data->is_posted === 1)?'readonly':'')) }}
                              </div>
                              <span class="validate-err" id="err_bs_specimen"></span>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              {{ Form::label('bs_lot_no', __('BRAND/LOT#'),['class'=>'form-label']) }}
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <span class="validate-err">{{ $errors->first('bs_lot_no') }}</span>
                              <div class="form-icon-user">
                                 {{ Form::text('bs_lot_no',$data->bs_lot_no, array('id'=>'bs_lot_no','class' => 'form-control','maxlength'=>'150',($data->id && $data->is_posted === 1)?'readonly':'')) }}
                              </div>
                              <span class="validate-err" id="err_bs_lot_no"></span>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              {{ Form::label('bs_expiry', __('Expiry'),['class'=>'form-label']) }}
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <span class="validate-err">{{ $errors->first('bs_expiry') }}</span>
                              <div class="form-icon-user">
                                 {{ Form::date('bs_expiry',$data->bs_expiry, array('id'=>'bs_expiry','class' => 'form-control','maxlength'=>'150',($data->id && $data->is_posted === 1)?'readonly':'')) }}
                              </div>
                              <span class="validate-err" id="err_bs_expiry"></span>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              {{ Form::label('bs_normal_value', __('Normal Value'),['class'=>'form-label']) }}
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <span class="validate-err">{{ $errors->first('bs_normal_value') }}</span>
                              <div class="form-icon-user">
                                 {{ Form::text('bs_normal_value',$data->bs_normal_value, array('class' => 'form-control',($data->id && $data->is_posted === 1)?'disabled':'')) }}
                              </div>
                              <span class="validate-err" id="err_bs_expiry"></span>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-4">
                        <div class="form-group">
                           {{ Form::label('bs_result', __('Result'),['class'=>'form-label']) }}
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <span class="validate-err">{{ $errors->first('bs_result') }}</span>
                           <div class="form-icon-user">
                              {{ Form::text('bs_result',$data->bs_result, array('class' => 'form-control',($data->id && $data->is_posted === 1)?'disabled':'')) }}
                           </div>
                           <span class="validate-err" id="err_bs_result"></span>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-4">
                        <div class="form-group">
                           {{ Form::label('bs_remarks', __('Remarks'),['class'=>'form-label']) }}
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <span class="validate-err">{{ $errors->first('bs_remarks') }}</span>
                           <div class="form-icon-user">
                              {{ Form::text('bs_remarks',$data->bs_remarks, array('class' => 'form-control',($data->id && $data->is_posted === 1)?'readonly':'')) }}
                           </div>
                           <span class="validate-err" id="err_bs_remarks"></span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
	@if($data->id > 0)
	<div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample3">
	<div  class="accordion accordion-flush">
		<div class="accordion-item">
			<h6 class="accordion-header" id="flush-heading4">
				<button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse4" aria-expanded="false" aria-controls="flush-collapse4">
				<h6 class="sub-title accordiantitle">
					<i class="ti-menu-alt text-white fs-12"></i>
					<span class="accordiantitle-icon">{{__("Upload")}}
					</span>
				</h6>
				</button>
			</h6>
			<div id="flush-collapse4" class="accordion-collapse collapse" aria-labelledby="flush-heading4" data-bs-parent="#accordionFlushExample3">
				<div class="basicinfodiv">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6">
						<div class="form-group">
							{{ Form::label('ora_document', __('Document'),['class'=>'form-label']) }}
							<div class="form-icon-user">
							{{ Form::input('file','ora_document','',array('class'=>'form-control'))}}  
							</div>
							<span class="validate-err" id="err_documents"></span>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 mt-4">
						<button type="button" style="float: right;" class="btn btn-primary" id="uploadAttachmentonly">Upload File</button>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<br>
						<div class="table-responsive">
							<table class="table">
							<thead>
								<tr>
									<th>Attachment</th>
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
	<div class="modal-footer">
	<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
	@if($data->id)
	<a href="{{route('blood-sugar-test.print',['id'=>$data->id])}}" class="digital-sign-btn" target="_blank">
	<button class="btn btn-primary" type="button" >
	<i class="fa fa-print icon" ></i>
	{{__('Print')}}
	</button>
	</a>
	<button class="btn btn-primary" type="submit" value="submit" {{($data->id && $data->is_posted === 1)?'disabled':''}}>
	{{__('Submit')}}
	</button>
	@endif
	<button class="btn btn-primary" id="savechanges" type="submit2" value="save">
	<i class="fa fa-save icon" ></i>
	{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}
	</button>
	</div>
</div>
{{Form::close()}}
<script src="{{ asset('js/SocialWelfare/select-ajax.js?v='.filemtime(getcwd().'/js/SocialWelfare/select-ajax.js').'') }}"></script>
<script src="{{ asset('/js/HealthandSafety/LabForms.js?v='.filemtime(getcwd().'/js/HealthandSafety/LabForms.js').'') }}"></script>
<script type="text/javascript">
   $(document).ready(function () {
   
      var shouldSubmitForm = false;
      $('#savechanges').click(function (e) {
            var form = $('#Pregnancytest');
            var areFieldsFilled = checkIfFieldsFilled();

            if (areFieldsFilled) {
                e.preventDefault(); // Prevent the default form submission

                Swal.fire({
                    title: "Are you sure?",
                    html: '<span style="color: red;">This will save the current changes.</span>',
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

	var now = new Date();
	now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
	document.getElementById('bs_date').value = now.toISOString().slice(0,16);
   
   	var text_loader = "Loading...";
   	$('#med_tech_id').change(function (e) {
   		$('#med_tech_position').val(text_loader);
   		$.ajax({
   			type: "get",
   			url: DIR+"serology/designation/"+$(this).val(),
   			success: function (response) {
   				if(response.status == 200){
   					$('#med_tech_position').val(response.data.description);
   				}
   			},error(error){
   				$('#med_tech_position').val('');
   			}
   		});
   	});
   	$('#health_officer_id').change(function (e) { 
   		$('#health_officer_position').val(text_loader);
   		$.ajax({
   			type: "get",
   			url: DIR+"serology/designation/"+$(this).val(),
   			success: function (response) {
   				if(response.status == 200){
   					$('#health_officer_position').val(response.data.description);
   				}
   			},error(error){
   				$('#health_officer_position').val('');
   			}
   		});
   	});
	
   	$("#uploadAttachmentonly").click(function(){
      		uploadAttachmentonly();
      	});
      	$(".deleteAttachment").click(function(){
      		deleteAttachment($(this));
      	})
   });
   // function uploadAttachmentonly(){
   // 	$(".validate-err").html("");
   // 	if (typeof $('#ora_document')[0].files[0]== "undefined") {
   // 		$("#err_documents").html("Please upload Document");
   // 		return false;
   // 	}
   // 	var formData = new FormData();
   // 	formData.append('file', $('#ora_document')[0].files[0]);
   // 	formData.append('healthCertId', $("#id").val());
   // 	showLoader();
   // 	$.ajax({
   // 	   url : DIR+'pregnancy-test-uploadDocument',
   // 	   type : 'POST',
   // 	   data : formData,
   // 	   processData: false,  // tell jQuery not to process the data
   // 	   contentType: false,  // tell jQuery not to set contentType
   // 	   success : function(data) {
   // 			hideLoader();
   // 			var data = JSON.parse(data);
   // 			if(data.ESTATUS==1){
   // 				$("#err_end_requirement_id").html(data.message);
   // 			}else{
   // 				$("#end_requirement_id").val(0);
   // 				$("#ora_document").val(null);
   // 				if(data!=""){
   // 					$("#DocumentDtlsss").html(data.documentList);
   // 				}
   // 				Swal.fire({
   // 					 position: 'center',
   // 					 icon: 'success',
   // 					 title: 'Document uploaded successfully.',
   // 					 showConfirmButton: false,
   // 					 timer: 1500
   // 				})
   // 				$(".deleteEndrosment").unbind("click");
   // 				$(".deleteEndrosment").click(function(){
   // 					deleteEndrosment($(this));
   // 				})
   // 			}
   // 	   }
   // 	});
   // }
   // function deleteAttachment(thisval){
   // 	var healthCertid = thisval.attr('healthCertid');
   // 	var doc_id = thisval.attr('doc_id');
   // 	const swalWithBootstrapButtons = Swal.mixin({
   // 	   customClass: {
   // 		   confirmButton: 'btn btn-success',
   // 		   cancelButton: 'btn btn-danger'
   // 	   },
   // 	   buttonsStyling: false
   //    })
   //    swalWithBootstrapButtons.fire({
   // 	   text: "Are you sure?",
   // 	   icon: 'warning',
   // 	   showCancelButton: true,
   // 	   confirmButtonText: 'Yes',
   // 	   cancelButtonText: 'No',
   // 	   reverseButtons: true
   //    }).then((result) => {
   // 		if(result.isConfirmed){
   // 			showLoader();
   // 			$.ajax({
   // 			   url :DIR+'pregnancy-test-deleteAttachment', // json datasource
   // 			   type: "POST", 
   // 			   data: {
   // 					"healthCertid": healthCertid,
   // 					"doc_id": doc_id,  
   // 			   },
   // 			   success: function(html){
   // 				hideLoader();
   // 				thisval.closest("tr").remove();
   // 				   Swal.fire({
   // 					 position: 'center',
   // 					 icon: 'success',
   // 					 title: 'Update Successfully.',
   // 					 showConfirmButton: false,
   // 					 timer: 1500
   // 				   })
   // 			   }
   // 		   })
   // 	   }
   //    })
   // }
</script>
<script type="text/javascript">
   $(document).ready(function () {
   FormAjax()
   
   selectNormal('.select3');
    if($("#cit_id option:selected").val() > 0 ){
         var val = $("#cit_id option:selected").val();
         getcitizens(val);
    }
    $('#cit_id').on('change', function() {
       var id =$(this).val();
       getcitizens(id);
    });
   
   function  getcitizens(aglcode){
    var id =aglcode;
      $.ajax({
           url :DIR+'blood-sugar-test/getCitizensName', // json datasource
           type: "POST", 
           data: {
                   "id": id, "_token": $("#_csrf_token").val(),
               },
           success: function(html){
     $("#pt_age").val('');
     $("#sex").val('');
   //    $("#lab_req_id").val('');
   //    $("#lab_control_no").val('');
   //    $("#lab_req_id").val(html.lab_req_no);
   //    $("#lab_control_no").val(html.lab_control_no);
              $("#pt_age").val(html.cit_age);
     if(html.cit_gender == 0){
   $("#sex").val('Male');
     }else{
   $("#sex").val('Female');   
     }
           }
       })
   } 
   });
</script>