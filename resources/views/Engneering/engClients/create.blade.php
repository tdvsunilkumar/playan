{{ Form::open(array('url' => 'engclients','id'=>'storePropertyOwnerForm')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  
   
<style>
    .modal-xll {
        max-width: 1330px !important;
    }
    .accordion-button{
        margin-bottom: 12px;
    }
    .form-group{
        margin-bottom: unset;
    }
    .form-group label {
        font-weight: 600;
        font-size: 12px;
    }
    .form-control, .custom-select{
        padding-left: 5px;
        font-size: 12px;
    }
    .pt10{
        padding-top:10px;
    }
    .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #8080802e;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .choices__inner {
        min-height: 35px;
        padding:5px ;
        padding-left:5px;
    }
    .field-requirement-details-status label{margin-top: 7px;}
    #flush-collapsetwo{
/*        padding-bottom: 80px;*/
    }
    .select3-container{
        z-index: 999999 !important;
    }

 </style>



   
    <div class="modal-body">
        
        <div class="row pt10" >
            <!--------------- Owners Information Start Here---------------->
            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingone">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle">{{__("Taxpayer Information")}}</h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                            <div class="basicinfodiv">
                                <div class="row">
                                  
                                    @if(empty($data->id))
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('owner name',__('Taxpayer Name'),['class'=>'form-label'])}}
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <div class="form-group" id="clientsregistered_group">
                                            {{ Form::select('clientsregistered',$arrgetClients,'', array('class' => 'form-control select3','id'=>'clientsregistered')) }}
                                        </div>
                                    </div>
                                    @endif 
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            {{Form::label('rpo_custom_last_name',__('Last Name / Corporation/Organization Name/Couples Name/Other Name'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::textarea('rpo_custom_last_name',$data->rpo_custom_last_name,array('class'=>'form-control','required'=>'required','rows'=>'2'))}}
                                            </div>
                                            <span class="validate-err" id="err_rpo_custom_last_name"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('rpo_first_name',__('First Name'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('rpo_first_name',$data->rpo_first_name,array('class'=>'form-control'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_first_name"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('rpo_middle_name',__('Middle Name'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('rpo_middle_name',$data->rpo_middle_name,array('class'=>'form-control'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_middle_name"></span>
                                        </div>
                                    </div>

                                     <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('suffix',__('Suffix(Jr, Sr, II, III)'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('suffix',$data->suffix,array('class'=>'form-control'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_middle_name"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('rpo_address_house_lot_no',__('House/Lot No.'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::textarea('rpo_address_house_lot_no',$data->rpo_address_house_lot_no,array('class'=>'form-control','rows'=>1))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_address"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('rpo_address_street_name',__('Street Name'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::textarea('rpo_address_street_name',$data->rpo_address_street_name,array('class'=>'form-control','rows'=>1))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_address"></span>
                                        </div>
                                    </div>
                                     <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('rpo_address_subdivision',__('Subdivision'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::textarea('rpo_address_subdivision',$data->rpo_address_subdivision,array('class'=>'form-control','rows'=>1))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_address"></span>
                                        </div>
                                    </div>

                                    <!--  <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">    

                                             {{Form::label('p_code',__('Owner Name'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                <div class="form-icon-user">
                                                {{ Form::select('p_code',$arrgetBrgyCode,$data->p_code, array('class' => 'form-control select3','id'=>'p_code','required'=>'required')) }}
                                                </div>
                                            <span class="validate-err" id="err_bussiness_application_code"></span>
                                        </div>
                                    </div> -->
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            {{Form::label('p_barangay_id_no',__('Barangay, Municipality, Province, Region'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('p_barangay_id_no',$arrgetBrgyCode,$data->p_barangay_id_no,array('class'=>'form-control','id'=>'p_barangay_id_no','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_address"></span>
                                        </div>
                                    </div>
                           <!--  <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                     {{Form::label('brgy_code',__('Barangay Id'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        
                                         {{Form::text('p_barangay_id_no',$data->p_barangay_id_no,array('class'=>'form-control','id'=>'p_barangay_id_no'))}}
                                    </div>
                                    <span class="validate-err" id="err_bussiness_application_desc"></span>
                                </div>
                            </div> -->

                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('p_telephone_no',__('Telephone No.'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('p_telephone_no',$data->p_telephone_no,array('class'=>'form-control phonenumber','id'=>'p_telephone_no','placeholder' => ''))}}
                                    </div>
                                    <span class="validate-err" id="err_p_telephone_no"></span>
                                </div>
                            </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('p_mobile_no',__('Mobile No.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                        {{Form::text('p_mobile_no',$data->p_mobile_no,array('class'=>'form-control phonenumber','id'=>'p_mobile_no','placeholder' => ''))}}
                                    </div>
                                    <span class="validate-err" id="err_p_mobile_no"></span>
                                </div>
                            </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('p_fax_no',__('Fax No.'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('p_fax_no',$data->p_fax_no,array('class'=>'form-control','id'=>'p_fax_no'))}}
                                    </div>
                                    <span class="validate-err" id="err_brgy_name"></span>
                                </div>
                            </div>
                            
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('p_email_address',__('Email Address'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                        {{Form::email('p_email_address',$data->p_email_address,array('class'=>'form-control','id'=>'p_email_address'))}}
                                    </div>
                                    <span class="validate-err" id="err_p_email_address"></span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('p_tin_no',__('Tin No.'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('p_tin_no',$data->p_tin_no,array('class'=>'form-control','id'=>'p_tin_no'))}}
                                    </div>
                                    <span class="validate-err" id="err_p_tin_no"></span>
                                </div>
                            </div> 
                             <div class="col-md-4" id="country_div">
                               <div class="form-group">
                                    {{ Form::label('country', __('Nationality'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        
                                    <div class="form-icon-user">
                                        {{ Form::select('country',$arrgetCountries,$data->country, array('class' => 'form-control','id'=>'country','required'=>'required')) }}
                                        
                                    </div>
                                    <span class="validate-err" id="err_country"></span>
                                </div>
                            </div> 
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('gender',__('Gender'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                         {{ Form::select('gender',array('1' =>'Male','0' =>'Female'), $data->gender, array('class' => 'form-control spp_type','id'=>'gender','required'=>'required')) }}
                                    </div>
                                    <span class="validate-err" id="err_gender"></span>
                                </div>
                            </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('dateofbirth',__('Date Of Birth'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::date('dateofbirth',$data->dateofbirth,array('class'=>'form-control','id'=>'dateofbirth'))}}
                                    </div>
                                    <span class="validate-err" id="err_dateofbirth"></span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('icr_no',__('ICR No. (if an alien)'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('icr_no',$data->icr_no,array('class'=>'form-control','id'=>'icr_no'))}}
                                    </div>
                                    <span class="validate-err" id="err_icr_no"></span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('civil_status',__('Civil Status'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                         {{ Form::select('civil_status',array('0' =>'Single','1' =>'Separated','2'=>'Cohabitation (Live-in)','3'=>'Married','4'=>'Widower','5'=>'Divorce'), $data->civil_status, array('class' => 'form-control spp_type','id'=>'civil_status','required'=>'required')) }}
                                    </div>
                                    <span class="validate-err" id="err_civil_status"></span>
                                </div>
                            </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('height',__('Height'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('height',$data->height,array('class'=>'form-control','id'=>'height'))}}
                                    </div>
                                    <span class="validate-err" id="err_height"></span>
                                </div>
                            </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('weight',__('Weight'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('weight',$data->weight,array('class'=>'form-control','id'=>'weight'))}}
                                    </div>
                                    <span class="validate-err" id="err_weight"></span>
                                </div>
                            </div>
                             <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    {{Form::label('birth_place',__('Birth Place'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('birth_place',$data->birth_place,array('class'=>'form-control','id'=>'birth_place'))}}
                                    </div>
                                    <span class="validate-err" id="err_birth_place"></span>
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
            <!-- <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item"> -->
                       <!--  <h6 class="accordion-header" id="flush-headingone">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle">{{__("Property Owner Information")}}</h6>
                            </button>
                        </h6> -->
                      <!--   <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                            <div class="basicinfodiv">
                                <div class="row">
                                   

                                   
                                    
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            
            <!--------------- Owners Information Start Here---------------->

            <!--------------- ATTACHED DOCUMENTARY REQUIREMENTS Start Here---------------->
         
            
        </div>
       
             
                 

               
           </div>
         </div>
        </div>
        <!--------------- Business Details Listing End Here------------------>

          

              <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" id="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
            </div>

        </div>
    </div>
</div>
 {{Form::close()}}
 <script src="{{ asset('js/ajax_validation.js') }}"></script> 
<script src="{{ asset('js/add_engclients.js') }}?rand={{ rand(000,999) }}"></script>

<script type="text/javascript">
$(document).ready(function () {
    select3Ajax("clientsregistered","clientsregistered_group","getBploTaxpayersAutoSearchList");
    var shouldSubmitForm = false;

        $('#submit').click(function (e) {
            var form = $('#storePropertyOwnerForm');
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

        function checkIfFieldsFilled() {
            var form = $('#storePropertyOwnerForm');
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
	
	$("#uploadAttachmentonly").click(function(){
		uploadAttachmentonly();
	});
	$(".deleteAttachment").click(function(){
		deleteAttachment($(this));
	})
	
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
	   url : DIR+'engclients-uploadDocument',
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
				   url :DIR+'engclients-deleteAttachment', // json datasource
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



