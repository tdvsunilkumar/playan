{{ Form::open(array('url' => 'CtoPaymentOrType','class'=>'formDtls','id'=>'submitLandUnitValueForm')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<style type="text/css">
   .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .btn{padding: 0.575rem 0.5rem;}
    .field-requirement-details-status label{padding-top:5px;}
    .nofile{width: 39px; text-align: center;}
    .accordion-button::after {
    background-image: url();
  }
</style>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('ortype_name', __('Type Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ortype_name') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ortype_name', $data->ortype_name, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_ortype_name"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('or_short_name', __('Short Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('or_short_name') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('or_short_name', $data->or_short_name, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_or_short_name"></span>
                </div>
            </div>
		</div>
		<div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('ora_remarks', __('Remark'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('ora_remarks') }}</span>
                    <div class="form-icon-user">
                         {{ Form::textarea('ora_remarks', $data->ora_remarks, array('class' => 'form-control','rows' => '3')) }}
                    </div>
                </div>
            </div>    
        </div>
		<div class="row" style="width: 100%; margin: 0px;padding-bottom: 20px;">
			<div class="row field-requirement-details-status">
				<div class="col-lg-2 col-md-2 col-sm-2">
					{{Form::label('No',__('No.'),['class'=>'form-label','style'=>'color:#fff;padding-top: 12px;'])}}
				</div>
				<div class="col-lg-5 col-md-5 col-sm-7">
					{{Form::label('applicable',__('Applicable Cashier System'),['class'=>'form-label','style'=>'color:#fff;padding-top: 12px;'])}}
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2">
					{{Form::label('action',__('Action'),['class'=>'form-label','style'=>'color:#fff;padding-top: 12px;'])}}
					<span class="btn_addmore_requirements btn" id="btn_addmore_requirements" style="color:white;">
						<i class="ti-plus"></i>
					</span>
				</div>
			</div>
			<span class="requirementsDetails activity-details" id="requirementsDetails">
				@php $i=1; @endphp
				@foreach($data_details as $row)
				<div class="removerequirementsdata row pt10">
					<div id="hidenRequirementHtmls" class="row">
					
					  <div class="row removerequirementsdatas">
						<div class="col-md-2">{{$i}}</div>
						<div class="col-md-8">
							<div class="form-group">
								<div class="form-icon-user">
								{{ Form::hidden('id_details[]',$row->id, array('id' => 'id')) }}
									{{ Form::select('pcs_id[]',$arrDepaertments,$row->pcs_id,array('class' =>'form-control checksameget order','id'=>'reqid')) }}
								</div>
							</div>
						</div>
						
						<div class="col-md-2">
							<div class="form-group">
								<div class="action-btn bg-danger ms-2">
									<a href="#" class="mx-3 btn btn-sm deletetesh_buttons ti-trash text-white text-white" name="stp_print" data-id='{{$row->id}}'></a>
								</div>
							</div>
						</div>
					  </div>
					</div>
				</div>
				@php $i++; @endphp
				@endforeach
			</span>
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
														<th >Attachment</th>
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
		<div id="hidenRequirementHtml" class="row hide">
		  <div class="row removerequirementsdata">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="form-group">
					<div class="form-icon-user">
						{{ Form::select('pcs_id[]',$arrDepaertments,'',array('class' =>'form-control checksameget order','id'=>'reqid')) }}	
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<div class="action-btn bg-danger ms-2">
						<a href="#" class="mx-3 btn btn-sm deletetesh_button ti-trash text-white text-white" name="stp_print"></a>
					</div>
				</div>
			</div>
		  </div>
		</div>
		<div class="modal-footer">
			<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
			<div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
			<!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
		</div>
 </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_or_type_validation.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
	$('#savechanges').click(function (e) {
        e.preventDefault();
        $(".validate-err").html('');
        var data = $("form").serialize();
        var obj={};
        for(var key in data){
            obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
        }
        $.ajax({
            url :DIR+'CtoPaymentOrType/formValidation', // json datasource
            type: "POST", 
            data: $('#submitLandUnitValueForm').serialize(),
            dataType: 'json',
            success: function(html){
                if(html.ESTATUS){
                  
                    $("#err_"+html.field_name).html(html.error);
                    $("."+html.field_name).focus();
                }else{
                    var areFieldsFilled = checkIfFieldsFilled();
                    if (areFieldsFilled) {
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
                    $('#submitLandUnitValueForm').submit();
                    form.submit();
                    // location.reload();
                    } else {
                        console.log("Form submission canceled");
                    }
                });
                    
                    }
                }
            }
        })
     
   });
   function checkIfFieldsFilled() {
            var form = $('#submitLandUnitValueForm');
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
	$("#btn_addmore_requirements").click(function(){
		 addmoreRequirements();
 	});
 	$("#uploadAttachmentonly").click(function(){
		uploadAttachmentonly();
	});
	$(".deleteAttachment").click(function(){
		deleteAttachment($(this));
	})
	function addmoreRequirements(){
	 var prevLength = $("#requirementsDetails").find(".removerequirementsdata").length;
	 var html = $("#hidenRequirementHtml").html();
		$("#requirementsDetails").append(html);
		$(".deletetesh_button").click(function(){
			$(this).closest(".removerequirementsdata").remove();
		});
	}


   $(".deletetesh_buttons").click(function(){
	   var thisval = $(this);
		const swalWithBootstrapButtons = Swal.mixin({
			customClass: {
				confirmButton: 'btn btn-success',
				cancelButton: 'btn btn-danger'
			},
			buttonsStyling: false
		})
		swalWithBootstrapButtons.fire({
			title: 'Are you sure?',
			text: "This action can not be undone. Do you want to continue?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes',
			cancelButtonText: 'No',
			reverseButtons: true
		}).then((result) => {
			if(result.isConfirmed)
			{
				var id  = $(this).data("id");
				var filtervars = {id:id};
				$.ajax({
					type: "post",
					url: DIR+'ctopaymentortypedetails-delete',
					data: filtervars,
					dataType: "json",
					success: function(html){
						thisval.closest(".removerequirementsdatas").remove();
	
					}
				});
			}
		})
	});
	
	
});
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
		   url : DIR+'ortype-uploadDocument',
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
				   url :DIR+'ortype-deleteAttachment', // json datasource
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
  