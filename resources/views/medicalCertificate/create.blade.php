{{ Form::open(array('url' => 'medical-certificate/store')) }}
{{-- Form::hidden('cit_id', isset($citizens->cit_id) ? $citizens->cit_id : null, array('id' => 'cit_id')) --}}
{{-- Form::hidden('cit_age', isset($citizens->age) ? $citizens->age : null, array('id' => 'cit_age')) --}}
{!! Form::hidden('id', $selected['id'], array('class' => 'id', 'id' => 'id')) !!}
{!! Form::hidden('or_date', $selected['or_date'], array('class' => 'or_date', 'id' => 'or_date')) !!}
{!! Form::hidden('or_amount', $selected['amount'], array('class' => 'or_amount', 'id' => 'or_amount')) !!}
{!! Form::hidden('cashierd_id', $selected['cashierd_id'], array('class' => 'cashierd_id', 'id' => 'cashierd_id')) !!}
{!! Form::hidden('cashier_id', $selected['cashier_id'], array('class' => 'cashierd_id', 'id' => 'cashier_id')) !!}

{{-- {!! Form::hidden('licence_number', null, array('id' => 'licence_number')) !!}
{!! Form::hidden('position', null, array('id' => 'position')) !!} --}}

<div class="modal-body">
	<div class="row pt10">
		<div class="col-lg-6 col-md-16 col-sm-12" id="accordionFlushExample">  
			<div class="accordion accordion-flush">
				<div class="accordion-item">
					<h6 class="accordion-header" id="flush-headingone">
						<button class="accordion-button  btn-primary" type="button">
							Patient Information
						</button> 
					</h6>
					<div id="flush-collapseone" class="accordion-collapse collapse show citizen_group">
						<div class="basicinfodiv">
							<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="patient-group">
                                        {{ Form::label('cit_name', __('Patient Name'),['class'=>'form-label']) }}
                                        <span style="color: red">*</span>
                                        <span class="validate-err">{{ $errors->first('cit_name') }}</span>
                                        <div class="form-icon-user" id='select-contain-citizen'>
                                            {{ 
                                                Form::select('cit_id', 
                                                    [],
                                                    $citizens->cit_id, 
                                                    $attributes = array(
                                                    'id' => 'cit_id',
                                                    'data-url' => 'record-card/getCitizens',
                                                    'data-placeholder' => 'Search Citizen',
                                                    'data-contain' => 'select-contain-citizen',
                                                    'data-value' =>isset($citizens->cit_fullname) ? $citizens->cit_fullname : '',
                                                    'data-value_id' =>isset($citizens->cit_fullname) ? $citizens->id : '',
                                                    'class' => 'form-control ajax-select get-citizen select_id',
													($citizens->cit_id) ? 'readonly' : '',
                                                )) 
                                            }}
                                        </div>
                                        <span class="validate-err" id="err_cit_id"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::label('address_show', __('Address'),['class'=>'form-label']) }}
                                        <span class="validate-err">{{ $errors->first('address') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::text('address_show',  
                                                isset($citizens->cit_full_address) ? $citizens->cit_full_address: null, 
                                                ['class' => 'form-control select_cit_full_address', 'id' => 'address_show', 'placeholder' => 'Address', 'disabled' => true]) !!}
                                        </div>
                                        <span class="validate-err" id="err_address"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::label('cit_age', __('Age'),['class'=>'form-label']) }}
                                        <span style="color: red">*</span>
                                        <span class="validate-err">{{ $errors->first('cit_age') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::text('cit_age',  
                                                $selected['med_cert_cit_age'], 
                                                ['class' => 'form-control select_human_age', 'id' => 'cit_age', 'placeholder' => 'Age', 'readonly' => true]) !!}
                                        </div>
                                        <span class="validate-err" id="err_cit_age"></span>
                                    </div>
                                </div>
								<div class="col-md-12">
									<div class="form-group" id="patient-group">
										{{ Form::label('med_cert_date_label', __('Date'),['class'=>'form-label']) }}
										<span style="color: red">*</span>
										<span class="validate-err">{{ $errors->first('med_cert_date') }}</span>
										<div class="form-icon-user">
											{!! Form::date('med_cert_date', $selected['med_cert_date'], ['class' => 'form-control']) !!}
										</div>
										<span class="validate-err" id="err_med_cert_date"></span>
									</div>
								</div>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div class="col-lg-6 col-md-16 col-sm-12" id="accordionFlushExample">  
			<div class="accordion accordion-flush">
				<div class="accordion-item">
					<h6 class="accordion-header" id="flush-headingone">
						<button class="accordion-button  btn-primary" type="button">
							Officer Information
						</button>
					</h6>
					<div id="flush-collapseone" class="accordion-collapse collapse show">
						<div class="basicinfodiv">
							<div class="col-md-12">
                                <div class="form-group" id="officer-group">
                                    {{ Form::label('officer', __('Health Officer'),['class'=>'form-label']) }}
                                    <span style="color: red">*</span>
                                    <span class="validate-err">{{ $errors->first('officer') }}</span>
                                    <div class="form-icon-user">
                                        {!! Form::select('med_officer_id',
                                            $select_health_officer, 
                                            $selected['medical_officer'], ['class' => 'form-control officer', 'id' => 'officer']) !!}
                                    </div>
                                    <span class="validate-err" id="err_med_officer_id"></span>
                                </div>
                            </div>
							<div class="col-md-12">
								<div class="form-group" id="med_officer_approved_status_group">
									{{ Form::label('med_officer_approved_status', __('Approve Certificate'),['class'=>'form-label']) }}
									 <span style="color: red">*</span> 
									<span class="validate-err">{{ $errors->first('med_officer_approved_status') }}</span>
									<div class="form-icon-user">
										{!! Form::checkbox('med_officer_approved_status',  
												null, $selected['approved_status'],
												['id' => 'med_officer_approved_status']) !!}
									</div>
									<span class="validate-err" id="err_med_officer_approved_status"></span>
								</div>
							</div>
                            <div class="col-md-12">
                                <div class="form-group" id="licence_number_show-group">
                                    {{ Form::label('licence_number_show', __('License Number'),['class'=>'form-label']) }}
                                    <span style="color: red">*</span>
                                    <span class="validate-err">{{ $errors->first('licence_number_show') }}</span>
                                    <div class="form-icon-user">
                                        {!! Form::text('licence_number_show',  
                                            $selected['licence_number'], 
                                            ['class' => 'form-control', 'id' => 'licence_number_show', 'placeholder' => 'License Number', 'disabled' => true]) !!}
                                    </div>
                                    <span class="validate-err" id="err_licence_number_show"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group" id="postion_show-group">
                                    {{ Form::label('med_officer_position', __('Position'),['class'=>'form-label']) }}
                                    <span style="color: red">*</span>
                                    <span class="validate-err">{{ $errors->first('med_officer_position') }}</span>
                                    <div class="form-icon-user">
                                        {!! Form::text('med_officer_position',  
                                            $selected['position'], 
                                            ['class' => 'form-control position', 'id' => 'position', 'placeholder' => 'Positions']) !!}
                                    </div>
                                    <span class="validate-err" id="err_med_officer_position"></span>
                                </div>
                            </div>
							<div class="col-md-12">
                                <div class="form-group" id="cert-type">
                                    {{ Form::label('med_cert_type', __('Certificate Type'),['class'=>'form-label']) }}
                                    <span style="color: red">*</span>
                                    <span class="validate-err">{{ $errors->first('med_cert_type') }}</span>
                                    <div class="form-icon-user" id="med_cert_type_contain">
                                        {!! Form::select('med_cert_type',
                                            $cert_type,$selected['med_cert_type'],
                                            ['class' => 'form-control cert ', 'id' => 'med_cert_type']) !!}
                                    </div>
                                    <span class="validate-err" id="err_med_cert_id"></span>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 type-form" id="medicol-form">  
			<div class="accordion accordion-flush">
				<div class="accordion-item">
					<h6 class="accordion-header" id="flush-headingone">
						<button class="accordion-button  btn-primary" type="button">
							Medico Legal Information
						</button>
					</h6>
					<div id="flush-collapseone" class="accordion-collapse collapse show">
						<div class="basicinfodiv">
							<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::label('incedent_nature', __('Nature Of Incident'),['class'=>'form-label']) }}
                                        <div class="form-icon-user">
										{!! Form::text('incedent_nature',  
                                               $selected['incedent_nature'],
                                                [
                                                    'class' => 'form-control', 
                                                    'placeholder' => 'Nature Of Incedent'
                                                ]
                                            ) !!}
                                        </div>
                                        <span class="validate-err" id="err_or_no"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" id="p_barangay_id_group">
                                        {{ Form::label('incedent_place', __('Place Of Incident'),['class'=>'form-label']) }}
                                        <span style="color: red" class="or_no_star {{ $selected['is_free'] !== 1 ? '' : 'hide' }}">*</span>
                                        <span class="validate-err">{{ $errors->first('incedent_place') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::select('incedent_place',
                                                $getbarangay, $selected['incedent_place'],
                                                ['class' => 'form-control', 'placeholder' => 'please Select','id'=>'incedent_place']) !!}

                                        </div>
                                        <span class="validate-err" id="err_or_date"></span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group" id="service-group">
                                        {{ Form::label('incedent_datetime', __('Date Time of Incident'),['class'=>'form-label']) }}
                                        <span style="color: red" class="or_no_star {{ $selected['is_free'] !== 1 ? '' : 'hide' }}">*</span>
                                        <span class="validate-err">{{ $errors->first('incedent_datetime') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::dateTimeLocal('incedent_datetime',  
                                                $selected['incedent_datetime'],
                                                [
                                                    'class' => 'form-control', 
                                                    'placeholder' => 'Date Time of Incedent'
                                                ]
                                            ) !!}
                                        </div>
                                        <span class="validate-err" id="err_or_amount"></span>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 type-form" id="findings-form">  
			<div class="accordion accordion-flush">
				<div class="accordion-item">
					<h6 class="accordion-header" id="flush-headingone">
						<button class="accordion-button  btn-primary" type="button">
							Findings
						</button>
					</h6>
					<div id="flush-collapseone" class="accordion-collapse collapse show">
						<div class="basicinfodiv">
							<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" >
                                        {{ Form::label('med_cert_findings', __('Impression'),['class'=>'form-label']) }}
                                        <div class="form-icon-user">
										{!! Form::textarea('med_cert_findings',  
											$selected['med_cert_findings'],
                                                [
                                                    'class' => 'form-control', 
                                                ]
                                            ) !!}
                                        </div>
                                        <span class="validate-err" id="err_med_cert_findings"></span>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
        <div class="col-lg-12 col-md-16 col-sm-12" id="accordionFlushExample">  
			<div class="accordion accordion-flush">
				<div class="accordion-item">
					<h6 class="accordion-header" id="flush-headingone">
						<button class="accordion-button  btn-primary" type="button">
							Payment Information
						</button>
					</h6>
					<div id="flush-collapseone" class="accordion-collapse collapse show">
						<div class="basicinfodiv">
							<div class="row">
                                <div class="col-md-3">
                                    <div class="form-group" id="or_no-group">
                                        {{ Form::label('or_no_label', __('O.R. No.'),['class'=>'form-label']) }}
                                        <span style="color: red;" class="or_no_star {{ $selected['is_free'] !== 1 ? '' : 'hide' }}">
                                            *
                                        </span>
                                        <span class="validate-err">
                                            {{ $errors->first('or_no') }}
                                        </span>
                                        @php
                                            $condition = ['class' => 'form-control or_no', 'id' => 'or_no'];

                                        @endphp
                                        <div class="form-icon-user">
                                            {!! Form::select('or_no', 
                                                $select_or_nos,
                                                ($selected['is_free'] !== 1) ? $selected['or_no'] : 'Free', 
                                                array_merge($condition)) !!}
                                        </div>
                                        <span class="validate-err" id="err_or_no"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" id="or-date-group">
                                        {{ Form::label('or_date', __('O.R. Date'),['class'=>'form-label']) }}
                                        <span style="color: red" class="or_no_star {{ $selected['is_free'] !== 1 ? '' : 'hide' }}">*</span>
                                        <span class="validate-err">{{ $errors->first('or_date') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::date('or_date_show',
                                                $selected['or_date'], 
                                                ['class' => 'form-control', 'id' => 'or_date_show', 'placeholder' => 'OR Date', 'disabled' => true]) !!}
                                        </div>
                                        <span class="validate-err" id="err_or_date"></span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group" id="service-group">
                                        {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
                                        <span style="color: red" class="or_no_star {{ $selected['is_free'] !== 1 ? '' : 'hide' }}">*</span>
                                        <span class="validate-err">{{ $errors->first('amount') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::text('amount',  
                                                $selected['amount'],
                                                [
                                                    'class' => 'form-control', 
                                                    'id' => 'amount_show', 
                                                    'placeholder' => 'Amount', 
                                                    'disabled' => true
                                                ]
                                            ) !!}
                                        </div>
                                        <span class="validate-err" id="err_or_amount"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" id="service-group">
                                        {{ Form::label('Free', __('Free'),['class'=>'form-label']) }}
                                        {{-- <span style="color: red">*</span> --}}
                                        <span class="validate-err">{{ $errors->first('Free') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::checkbox('med_cert_is_free',
                                                null, $selected['is_free'],
                                                ['id' => 'med_cert_is_free']) !!}
                                        </div>
                                        <span class="validate-err" id="err_med_cert_is_free"></span>
                                    </div>
                                </div>
                            </div>

						</div>
					</div>
				</div>
			</div>
		</div>
		@if($selected['id'] > 0)
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
										{{ Form::label('doc_json', __('Document'),['class'=>'form-label']) }}
										<div class="form-icon-user">
											{{ Form::input('file','doc_json','',array('class'=>'form-control'))}}  
										</div>
										<span class="validate-err" id="err_documents"></span>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 mt-4">
									<button type="button" style="float: right;" class="btn btn-primary" id="uploadAttachmentonly">Upload File</button>
								</div>
							</div>
							<div class="row">	
								<div class="col-lg-12 col-md-12 col-sm-12"><br>
									<div class="table-responsive">
										<table class="table">
											<thead>
												<tr>
													<th>Attachment</th>
													<th>Action</th>
												</tr>
											</thead>
											<thead id="DocumentDtlsss">
												<?php echo $selected['arrDocumentDetailsHtml']?>
												@if(empty($selected['arrDocumentDetailsHtml']))
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
	</div> 
	<div class="modal-footer">
		<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        @if($selected['id'] != null)
            <!-- <a href="{{ $selected['approved_status'] == '1' ? route('medical.print',['id'=>$selected['id']]) : '#'}}" 
				target="{{ $selected['approved_status'] == '1' ? '_blank' : ''}}"> -->
				<a href="{{route('medical.print',['id'=>$selected['id']])}}" target="_blank">
                <input type="button" value="Print" class="btn print-btn btn-primary" >
            </a>
        @endif
        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
            <i class="fa fa-save icon"></i>
            <input type="submit" name="submit" value="Save Changes" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
        </div>
	</div>
</div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation_issuance.js') }}?rand={{ rand(000,999) }}"></script>
<script src="{{ asset('js/SocialWelfare/citizen-ajax.js?v='.filemtime(getcwd().'/js/SocialWelfare/citizen-ajax.js').'') }}"></script>
<script src="{{ asset('js/SocialWelfare/select-ajax.js?v='.filemtime(getcwd().'/js/SocialWelfare/select-ajax.js').'') }}"></script>
<script src="{{ asset('js/medical_certificate_create.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {

	$('.type-form').hide();
	@if($selected['med_cert_type'] === 2)
	$('#medicol-form').show();
	@else
	$('#findings-form').show();
	@endif
    select3Ajax("incedent_place","p_barangay_id_group","getBarngayMunList");
	// $("#commonModal").find('.body').css({overflow: 'unset'})
	
 	$("#uploadAttachmentonly").click(function(){
		uploadAttachmentonly();
	});
	$(".deleteAttachment").click(function(){
		deleteAttachment($(this));
	})	
});
	function uploadAttachmentonly(){
		$(".validate-err").html("");
		if (typeof $('#doc_json')[0].files[0]== "undefined") {
			$("#err_documents").html("Please upload Document");
			return false;
		}
		var formData = new FormData();
		formData.append('file', $('#doc_json')[0].files[0]);
		formData.append('healthCertId', $("#id").val());
		showLoader();
		$.ajax({
		   url : DIR+'medical-certificate-uploadDocument',
		   type : 'POST',
		   data : formData,
		   processData: false,  // tell jQuery not to process the data
		   contentType: false,  // tell jQuery not to set contentType
		   success : function(data) {
				hideLoader();
				var data = JSON.parse(data);
				if(data.ESTATUS==1){
					$("#err_end_requirement_id").html(data.message);
				}else{
					$("#end_requirement_id").val(0);
					$("#doc_json").val(null);
					if(data!=""){
						$("#DocumentDtlsss").html(data.documentList);
					}
					Swal.fire({
						 position: 'center',
						 icon: 'success',
						 title: 'Document uploaded successfully.',
						 showConfirmButton: false,
						 timer: 1500
					})
					$(".deleteEndrosment").unbind("click");
					$(".deleteEndrosment").click(function(){
						deleteEndrosment($(this));
					})
				}
		   }
		});
	}
	function deleteAttachment(thisval){
		var healthCertid = thisval.attr('healthCertid');
		var doc_id = thisval.attr('doc_id');
		const swalWithBootstrapButtons = Swal.mixin({
		   customClass: {
			   confirmButton: 'btn btn-success',
			   cancelButton: 'btn btn-danger'
		   },
		   buttonsStyling: false
	   })
	   swalWithBootstrapButtons.fire({
		   text: "Are you sure?",
		   icon: 'warning',
		   showCancelButton: true,
		   confirmButtonText: 'Yes',
		   cancelButtonText: 'No',
		   reverseButtons: true
	   }).then((result) => {
			if(result.isConfirmed){
				showLoader();
				$.ajax({
				   url :DIR+'medical-certificate-deleteAttachment', // json datasource
				   type: "POST", 
				   data: {
						"healthCertid": healthCertid,
						"doc_id": doc_id,  
				   },
				   success: function(html){
					hideLoader();
					thisval.closest("tr").remove();
					   Swal.fire({
						 position: 'center',
						 icon: 'success',
						 title: 'Update Successfully.',
						 showConfirmButton: false,
						 timer: 1500
					   })
				   }
			   })
		   }
	   })
	}
</script>