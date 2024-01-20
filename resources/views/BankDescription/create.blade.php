{{ Form::open(array('url' => 'bankdescription','class'=>'formDtls')) }}
 <style>
.modal-content {
    position: absolute;
   float: left;
   margin-left: 50%;
   margin-top: 50%;
  transform: translate(-50%, -50%);
}
</style>   {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
	
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('bdesc_code', __('Bank Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bdesc_code') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('bdesc_code', $data->bdesc_code, array('class' => 'form-control','maxlength'=>'50','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bdesc_code"></span>
                </div>
            </div>    
        </div>
		<div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('bdesc_description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bdesc_description') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('bdesc_description', $data->bdesc_description, array('class' => 'form-control','maxlength'=>'100','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bdesc_description"></span>
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
											{{ Form::label('bdesc_sample_doc', __('Document'),['class'=>'form-label']) }}
											<div class="form-icon-user">
												{{ Form::input('file','bdesc_sample_doc','',array('class'=>'form-control'))}}  
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
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
			<div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<!-- <script src="{{ asset('js/ajax_validation.js') }}"></script> -->
<script src="{{ asset('js/ajax_common_save.js') }}"></script>
<script>
$(document).ready(function(){
	$("#uploadAttachmentonly").click(function(){
		uploadAttachmentonly();
	});
	$(".deleteAttachment").click(function(){
		deleteAttachment($(this));
	})
	
	
});
function uploadAttachmentonly(){
		$(".validate-err").html("");
		if (typeof $('#bdesc_sample_doc')[0].files[0]== "undefined") {
			$("#err_documents").html("Please upload Document");
			return false;
		}
		var formData = new FormData();
		formData.append('file', $('#bdesc_sample_doc')[0].files[0]);
		formData.append('healthCertId', $("#id").val());
		showLoader();
		$.ajax({
		   url : DIR+'bankdescription-uploadDocument',
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
					$("#bdesc_sample_doc").val(null);
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
				   url :DIR+'bankdescription-deleteAttachment', // json datasource
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
  