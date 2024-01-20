{{ Form::open(array('url' => 'health-safety-family-planning','class'=>'formDtls','id'=>'familyplanning')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<div class="modal-body">
	<div class="row pt10">
		<div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample">  
			<div class="accordion accordion-flush">
				<div class="accordion-item">
					<h6 class="accordion-header" id="flush-headingone">
						<button class="accordion-button  btn-primary" type="button">
							
						</button>
					</h6>
					<div id="flush-collapseone" class="accordion-collapse collapse show">
						<div class="basicinfodiv">
							<div class="row">
							  <div class="col-md-4">
								<div class="form-group">
									{{ Form::label('fam_ref_id', __('Application No.'),['class'=>'form-label']) }}
									<span class="validate-err">{{ $errors->first('fam_ref_id') }}</span>
									<div class="form-icon-user">
									 {{ Form::text('fam_ref_id',$data->fam_ref_id, array('id'=>'fam_ref_id','class' => 'form-control','readonly')) }}
									</div>
									<span class="validate-err" id="err_fam_ref_id"></span>
								</div>
							  </div>
							  <div class="col-md-4"></div>
							  <div class="col-md-4">
								<div class="form-group">
									{{ Form::label('fam_date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
									<span class="validate-err">{{ $errors->first('age') }}</span>
									<div class="form-icon-user">
									{{ Form::date('fam_date',$data->fam_date, array('id'=>'fam_date','class' => 'form-control')) }}
									</div>
									<span class="validate-err" id="err_fam_date"></span>
								</div>
							  </div>						  
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										{{ Form::label('house_lot_no', __('House / Lot No.'),['class'=>'form-label']) }}
										<span class="validate-err">{{ $errors->first('fam_ref_id') }}</span>
										<div class="form-icon-user">
										 {{ Form::text('house_lot_no',$data->house_lot_no, array('id'=>'house_lot_no','class' => 'form-control')) }}
										</div>
										<span class="validate-err" id="err_house_lot_no"></span>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										{{ Form::label('street_name', __('Street Name'),['class'=>'form-label']) }}
										<span class="validate-err">{{ $errors->first('street_name') }}</span>
										<div class="form-icon-user">
										 {{ Form::text('street_name',$data->street_name, array('id'=>'street_name','class' => 'form-control')) }}
										</div>
										<span class="validate-err" id="err_street_name"></span>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										{{ Form::label('subdivision', __('Subdivision'),['class'=>'form-label']) }}
										<span class="validate-err">{{ $errors->first('subdivision') }}</span>
										<div class="form-icon-user">
										 {{ Form::text('subdivision',$data->subdivision, array('id'=>'subdivision','class' => 'form-control')) }}
										</div>
										<span class="validate-err" id="err_subdivision"></span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group m-form__group required mb-0 select-contain" id="select-brgy">
										{{ Form::label('brgy_id', 'Barangay , Municipality, Province, Region', ['class' => 'required fs-6 fw-bold']) }}<span class="text-danger">*</span>
										{{
											Form::select('brgy_id', 
											$barangays, 
											$data->brgy_id, 
											[
												'id' => 'barangay_id', 
												'class' => 'form-control', 
												'data-placeholder' => 'select a barangay...',

											])
										}}
										
										<span class="validate-err" id="err_brgy_id">{{ $errors->first('brgy_id') }}</span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-12">
									<div class="">
										<div class="card-body">
											<div class="table-responsive">
												<table class="table" id="">
													<thead>
														<tr>
															<th>{{__('No.')}}</th>
															<th width="35%">{{__('Full Name')}}</th>
															<th>{{__('Gender')}}</th>
															<th>{{__('Age')}}</th>
															<th>
																<a href="{{ url('/health-safety-citizens?isopenAddform=1') }}" target="_blank" data-size="lg"  data-bs-toggle="tooltip" title="{{__('Add More')}}" class="btn btn-sm btn-primary addmoreslectcitize">
																	<i class="ti-plus white"></i>
																</a>
															</th>
														</tr>
													</thead>
													<tbody >
														@if($partners)
														@foreach($partners as $loop => $partner)
														<tr style="height: 100px;">
															<td>{{$loop->iteration}}.</td>
															<td style="width: 200px;" id="multiCollapseExample2">
																{{ Form::select('cit_id[]',$getcitizens,$partner->cit_id, array('class' =>'form-control','required'=>'required','id'=>'cit_id'.$loop->iteration)) }}
																{{ Form::hidden('fam_id[]', $partner->partner_id ) }}
															</td>
															<td>{{ Form::text('gender', '', array('id'=>'gender'.$loop->iteration,'class' => 'form-control','readonly')) }}</td>
															<td>{{ Form::text('age[]', '', array('id'=>'age'.$loop->iteration,'class' => 'form-control','required'=>'required','readonly')) }}</td>
														</tr>
														@endforeach
														@else
														<tr style="height: 100px;">
															<td>1.</td>
															<td style="width: 200px;" id="multiCollapseExample3">
																{{ Form::select('cit_id[]',$getcitizens,$data->cit_id, array('class' =>'form-control','id'=>'cit_id1')) }}
																<span class="validate-err" id="err_cit_id"></span>
															</td>
															<td>{{ Form::text('gender', '', array('id'=>'gender1','class' => 'form-control','readonly')) }}</td>
															<td>{{ Form::text('age[]', '', array('id'=>'age1','class' => 'form-control','readonly')) }}</td>														</tr>
														<tr>
															<td>2.</td>
															<td id="multiCollapseExample4">
																{{ Form::select('cit_id[]',$getcitizens,$data->cit_id, array('class' =>'form-control','id'=>'cit_id2')) }}
																<span class="validate-err" id="err_cit_id1"></span>
															</td>
															<td>{{ Form::text('gender', '', array('id'=>'gender2','class' => 'form-control','readonly')) }}</td>
															<td>{{ Form::text('age[]', '', array('id'=>'age2','class' => 'form-control','readonly')) }}</td>
														</tr>
														@endif
													</tbody>
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
	</div>
	<div class="modal-footer">
		<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
		<div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
			<i class="fa fa-save icon"></i>
			<input type="submit" name="submit" id="savechanges" value="Save Changes" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
		</div>
		<!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
	</div>
</div>
{{Form::close()}}
<script src="{{ asset('js/familyajax_validation.js') }}"></script>
<script src="{{ asset('js/SocialWelfare/add-citizen.js?v='.filemtime(getcwd().'/js/SocialWelfare/add-citizen.js').'') }}"></script>
<script src="{{ asset('js/SocialWelfare/select-ajax.js?v='.filemtime(getcwd().'/js/SocialWelfare/select-ajax.js').'') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
	$('#savechanges').click(function (e) {
		
		if($("#cit_id1 option:selected").val() > 0){
			$("#err_cit_id").css("display", "none");
		}else{
			$("#err_cit_id").css("display", "block");
		}
        checkIfFieldsFilled(); 
    });

	function checkIfFieldsFilled() {
		var form = $('#familyplanning');
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
			
		}

		return isValid;
	}

	$("#cit_id").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample2")});
	
	select3Ajax("cit_id1","multiCollapseExample3","getCitizenAjax");
	select3Ajax("cit_id2","multiCollapseExample4","getCitizenAjax");
	
	$("#commonModal").find('.body').css({overflow: 'unset'}) 
	
	$("#uploadAttachmentonly").click(function(){
		uploadAttachmentonly();
	});
	$(".deleteAttachment").click(function(){
		deleteAttachment($(this));
	})
     if($("#cit_id1 option:selected").val() > 0 ){
          var val = $("#cit_id1 option:selected").val();
          getcitizens1(val);
     }
	 if($("#cit_id2 option:selected").val() > 0 ){
          var val = $("#cit_id2 option:selected").val();
          getcitizens2(val);
     }
     $('#cit_id1').on('change', function() {
        var id =$(this).val();
        getcitizens1(id);
     });
	 $('#cit_id2').on('change', function() {
        var id =$(this).val();
        getcitizens2(id);
     });
	 $(".refeshbuttonselect1").click(function(){
 		 refreshCitizen1();
 	});
	$(".refeshbuttonselect2").click(function(){
 		 refreshCitizen2();
 	});
	
	function  getcitizens1(aglcode){
     var id =aglcode;
       $.ajax({
            url :DIR+'health-safety-family-planning/getCitizensName', // json datasource
            type: "POST", 
            data: {
                    "id": id, "_token": $("#_csrf_token").val(),
                },
            success: function(html){
			   var brgy_id = $('#barangay_id').find(":selected").val();
			   if(brgy_id ==undefined){
				   $("#house_lot_no").val('');
				   $("#street_name").val('');
				   $("#subdivision").val('');
				   $("#house_lot_no").val(html.cit_house_lot_no);
				   $("#street_name").val(html.cit_street_name);
				   $("#subdivision").val(html.cit_subdivision);
				   $("#barangay_id").append('<option value="'+ html.brgy_id +'" data-select3-id="select3-data-218-dts5">' + html.address + '</option>');
			   }
			   $("#age1").val('');
			   $("#gender1").val('');
               $("#age1").val(html.cit_age);
			   if(html.cit_gender == 0){
				$("#gender1").val('Male');
			   }else{
				$("#gender1").val('Female');   
			   }
            }
        })
   }
   function  getcitizens2(aglcode){
     var id =aglcode;
       $.ajax({
            url :DIR+'health-safety-family-planning/getCitizensName', // json datasource
            type: "POST", 
            data: {
                    "id": id, "_token": $("#_csrf_token").val(),
                },
            success: function(html){
				var brgy_id = $('#barangay_id').find(":selected").val();
			   if(brgy_id ==undefined){
				   $("#house_lot_no").val('');
				   $("#street_name").val('');
				   $("#subdivision").val('');
				   $("#house_lot_no").val(html.cit_house_lot_no);
				   $("#street_name").val(html.cit_street_name);
				   $("#subdivision").val(html.cit_subdivision);
				   $("#barangay_id").append('<option value="'+ html.brgy_id +'" data-select3-id="select3-data-218-dts5">' + html.address + '</option>');
			   }
			   $("#age2").val('');
			   $("#gender2").val('');
               $("#age2").val(html.cit_age);
			   
			   if(html.cit_gender == 0){
				$("#gender2").val('Male');
			   }else{
				$("#gender2").val('Female');   
			   }
            }
        })
   }
   function refreshCitizen1(){
	   $.ajax({
	 
			url :DIR+'health-safety-family-planning-getRefreshHelSaf', // json datasource
			type: "POST", 
			data: {
			  // "id": id, 
			  "_token": $("#_csrf_token").val(),
			},
			success: function(html){
			  if(html !=''){
			   $("#cit_id1").html(html);
				//$("#bba_code").html('<option>Please Select</option>');
			  }
			}
		})
   }
   function refreshCitizen2(){
	   $.ajax({
	 
			url :DIR+'health-safety-family-planning-getRefreshHelSaf', // json datasource
			type: "POST", 
			data: {
			  // "id": id, 
			  "_token": $("#_csrf_token").val(),
			},
			success: function(html){
			  if(html !=''){
			   $("#cit_id2").html(html);
				//$("#bba_code").html('<option>Please Select</option>');
			  }
			}
		})
   }
   function uploadAttachmentonly(){
		$(".validate-err").html("");
		if (typeof $('#ora_document')[0].files[0]== "undefined") {
			$("#err_documents").html("Please upload Document");
			return false;
		}
		var formData = new FormData();
		formData.append('file', $('#ora_document')[0].files[0]);
		formData.append('healthCertId', $("#id").val());
		showLoader();
		$.ajax({
		   url : DIR+'health-safety-family-planning-uploadDocument',
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
					$("#ora_document").val(null);
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
				   url :DIR+'health-safety-family-planning-deleteAttachment', // json datasource
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
});
</script>
  