{{ Form::open(array('url' => 'medical-record')) }}
{{ Form::hidden('medical_rec_id',$data->medical_rec_id, array('id' => 'id')) }}
<style>
    .accordion-button::after{
        content: "+"!important;
        background-image: none;
    }
</style>
<div class="modal-body">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('med_rec_date', 
                    __('Date'),
                    ['class'=>'form-label']
                    ) 
                }}
                <div class="form-icon-user">
                    {{ Form::dateTimeLocal('med_rec_date',
                        $data->med_rec_date, 
                        array(
                            'class' => 'form-control',
                        )) 
                    }}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {{ Form::label('rec_card_num', 
                    __('Record No.'),
                    ['class'=>'form-label']
                    ) 
                }}
                <div class="form-icon-user">
                    {{ Form::text('rec_card_num',
                        $data->rec_card_num, 
                        array(
                            'class' => 'form-control',
                            'readonly'
                        )) 
                    }}
                    {{ Form::hidden('rec_card_id',
                        $data->rec_card_id)
                    }}
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                {{ Form::label('cit_id', __('Patient Name'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('patient',
                        ($data->patient)?$data->patient->cit_fullname:'', 
                        array(
                            'class' => 'form-control',
                            'readonly'
                        )) 
                    }}
                    {{
                        Form::hidden(
                            'cit_id',
                            ($data->patient)?$data->patient->id:'', 
                        )
                    }}
                    {{
                        Form::hidden(
                            'cit_age',
                            ($data->patient)?$data->patient->age:'', 
                        )
                    }}
                    {{
                        Form::hidden(
                            'cit_age_days',
                            ($data->patient)?$data->patient->cit_age_days:'', 
                        )
                    }}
                    {{
                        Form::hidden(
                            'cit_gender',
                            ($data->patient)?$data->patient->cit_gender:'', 
                        )
                    }}

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('hp_code', __('Attending Health Officer'),['class'=>'form-label']) }}<span style="color: red">*</span>
                <div class="form-icon-user" id="contain_hp_code">
                    {{ 
                        Form::select('hp_code', 
                            [],
                            $data->hp_code, 
                            $attributes = array(
                            'id' => 'hp_code',
                            'data-url' => 'citizens/selectEmployee',
                            'data-placeholder' => 'Search Officer',
                            'data-value' => isset($data->officer)?$data->officer->fullname:'',
                            'data-value_id' =>$data->hp_code,
                            'class' => 'form-control ajax-select ',
                            ($data->medical_rec_id && $data->rec_is_posted === 1)?'readonly':''
                            
                        )) 
                    }}
                </div>
                <span class="validate-err" id="err_hp_code"></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6" id="diagnosis-section">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" id="add-diagnosis" type="button" style="">
                            <h6 class="sub-title accordiantitle">Diagnosis </h6>
                        </button>
                    </h6>
                    
                    <div id="diagnosis-contain" class="accordion-collapse collapse show">
                        @if(!empty($data->diagnosis))
                        @foreach($data->diagnosis as $diagnose)
                        <div class="row diagnosis mt-3">
                            <div class="col-md-10" id="contain_diagnosis-{{$diagnose->id}}">
                            {{ 
                                Form::select('diagnosis['.$diagnose->id.'][disease]', 
                                    [],
                                    '', 
                                    $attributes = array(
                                    'id' => 'diagnosis-'.$diagnose->id,
                                    'data-url' => 'medical-record/selectDiagnosis',
                                    'data-placeholder' => 'Search Diagnosis',
                                    'data-value' => $diagnose->diag_name,
                                    'data-value_id' => $diagnose->disease_id,
                                    'class' => 'form-control diagnosis-select ajax-select select-service',
                                    ($data->medical_rec_id && $data->rec_is_posted === 1)?'readonly':''
                                )) 
                            }}
                            {{ Form::text('diagnosis['.$diagnose->id.'][specify]',
                                $diagnose->is_specified, 
                                array(
                                    'class' => 'form-control diagnosis-specify',
                                    ($diagnose->is_specified) ? '' : 'style'=>"display: none;",
                                ))
                            }}
                            </div>
                            <div class="col-md-2">
                                <button type="button" data-value="1" data-id="{{ $diagnose->id }}" 
                                    class="btn btn-sm btn-danger remove-diagnosis diag-delete-{{ $diagnose->id }} 
                                    {{ $diagnose->is_active == 1 ? '' : 'hide' }}">
                                    <i class="ti-trash text-white"></i>
                                </button>
                                <button type="button" data-value="0" data-id="{{ $diagnose->id }}"
                                    class="btn btn-sm btn-info remove-diagnosis diag-restore-{{ $diagnose->id }} 
                                    {{ $diagnose->is_active == 0 ? '' : 'hide' }}">
                                    <i class="ti-reload text-white"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="row new-row diagnosis mt-3">
                            <div class="col-md-10" id="contain_diagnosis-new">
                            {{ 
                                Form::select('diagnosis[new][disease]', 
                                    [],
                                    '', 
                                    $attributes = array(
                                    'id' => 'diagnosis-new',
                                    'data-url' => 'medical-record/selectDiagnosis',
                                    'data-placeholder' => 'Search Diagnosis',
                                    'data-value' => '',
                                    'data-value_id' =>'',
                                    'class' => 'form-control diagnosis-select ajax-select select-service',
                                    ($data->medical_rec_id && $data->rec_is_posted === 1)?'readonly':''
                                )) 
                            }}
                            <input class="form-control diagnosis-specify" name="diagnosis[new][specify]" type="text" value="">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-danger remove-row"><i class="ti-trash text-white"></i></button>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6" id="treatment-section">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" id="add-treatment" type="button" style="">
                        <h6 class="sub-title accordiantitle">Treatment - Management</h6>
                        </button>
                    </h6>
                    
                    <div id="treatment-contain" class="accordion-collapse collapse show">
                        @if(!empty($data->treatment))
                            @foreach($data->treatment as $treatment)
                            <div class="row mt-3">
                                <div class="col-md-5" >
                                {{ Form::text('treatment['.$treatment->id.'][treat_medication]',
                                    $treatment->treat_medication, 
                                    array(
                                        'class' => 'form-control',
                                        )
                                    ) 
                                }}
                                </div>
                                <div class="col-md-5">
                                {{ Form::text('treatment['.$treatment->id.'][treat_management]',
                                    $treatment->treat_management, 
                                    array(
                                        'class' => 'form-control',
                                        )
                                    ) 
                                }}
                                </div>
                                <div class="col-md-2 text-center">
                                    <button type="button" data-value="1" data-id="{{ $treatment->id }}" 
                                        class="btn btn-sm btn-danger remove-treatment treatment-delete-{{ $treatment->id }} 
                                        {{ $treatment->treat_is_active == 1 ? '' : 'hide' }}">
                                        <i class="ti-trash text-white"></i>
                                    </button>
                                    <button type="button" data-value="0" data-id="{{ $treatment->id }}"
                                        class="btn btn-sm btn-info remove-treatment treatment-restore-{{ $treatment->id }} 
                                        {{ $treatment->treat_is_active == 0 ? '' : 'hide' }}">
                                        <i class="ti-reload text-white"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="row new-row mt-3">
                                <div class="col-md-5" >
                                {{ Form::text('treatment[new][treat_medication]',
                                    '', 
                                    array(
                                        'class' => 'form-control',
                                        )
                                    ) 
                                }}
                                </div>
                                <div class="col-md-5">
                                {{ Form::text('treatment[new][treat_management]',
                                    '', 
                                    array(
                                        'class' => 'form-control',
                                        )
                                    ) 
                                }}
                                </div>
                                <div class="col-md-2 text-center">
                                    <button type="button" class="btn btn-sm btn-danger remove-row"><i class="ti-trash text-white"></i></button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('med_rec_nurse_note', __('Notes'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::textarea('med_rec_nurse_note',
                        $data->med_rec_nurse_note, 
                        array(
                            'class' => 'form-control',
                        )) 
                    }}
                </div>
            </div>
        </div>
    </div>
	@if($data->medical_rec_id > 0)
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
</div>
<div class="modal-footer">
   @if($data->medical_rec_id)
        <!-- <a  value="{{ ($data->medical_rec_id)>0?__('Print'):__('Print')}}" class="btn  btn-primary"><i class="ti-printer text-white"></i> Print</a> -->
   @endif
   <input type="button" value="{{__('Close')}}" class="btn  btn-light" data-bs-dismiss="modal">
   <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
      <i class="fa fa-save icon"></i>
      <input type="submit" name="submit" value="{{($data->medical_rec_id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
   </div>
</div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation_issuance.js') }}?rand={{ rand(000,999) }}"></script>
<script src="{{ asset('js/partials/citizen-ajax.js?v='.filemtime(getcwd().'/js/partials/citizen-ajax.js').'') }}"></script>
<script src="{{ asset('js/partials/select-ajax.js?v='.filemtime(getcwd().'/js/partials/select-ajax.js').'') }}"></script>
<script src="{{ asset('js/HealthandSafety/add_medicalrecord.js?v='.filemtime(getcwd().'/js/HealthandSafety/add_medicalrecord.js').'') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
    $("#commonModal").find('.body').css({overflow: 'unset'})
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
		   url : DIR+'medical-record/uploadDocument',
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
				   url :DIR+'medical-record/deleteAttachment', // json datasource
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
