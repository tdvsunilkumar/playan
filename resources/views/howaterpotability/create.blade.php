{{ Form::open(array('url' => 'healthy-and-safety/water-potability', 'id'=>'waterpotability')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{!! Form::hidden('cashierd_id', $data->cashierd_id, array('class' => 'cashierd_id', 'id' => 'cashierd_id')) !!}
{!! Form::hidden('cashier_id', $data->cashier_id, array('class' => 'cashierd_id', 'id' => 'cashier_id')) !!}
<div class="modal-body">
   <div  class="accordion accordion-flush">
      <div class="accordion-item">
         <h6 class="accordion-header" id="flush-headingone1">
            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse1" aria-expanded="false" aria-controls="flush-collapse1">
               <h6 class="sub-title accordiantitle">
                  <i class="ti-menu-alt text-white fs-12"></i>
                  <span class="sub-title accordiantitle">{{__("Business Details")}}</span>
               </h6>
            </button>
         </h6>
         <div id="flush-collapse1" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
            <div class="basicinfodiv">
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        {{ Form::label('business_id', __('Business Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user" id="contain_business_id">
                           {{ Form::select('business_id', $busn_name, $data->business_id, array('class' => 'form-control select3','id' => 'business_id',)) }}
                        </div>
                        <span class="validate-err" id="err_business_id"></span>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        {{ Form::label('brgy_id', __('Location'),['class'=>'form-label']) }}
                        <div class="form-icon-user">
                           {{ Form::text('address','', array('class' => 'form-control','id' => 'address','required'=>'required', 'readonly'=>'readonly')) }}
						   {{ Form::hidden('brgy_id',$data->brgy_id, array('id' => 'brgy_id')) }}
                        </div>
                        <span class="validate-err" id="err_brgy_id"></span>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-3">
                     <div class="form-group">
                        {{ Form::label('certificate_no', __('Certificate No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                           {{ Form::text('certificate_no', $data->certificate_no, array('class' => 'form-control')) }}
                        </div>
                        <span class="validate-err" id="err_certificate_no"></span>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        {{ Form::label('date_issued', __('Issuance Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                           {{ Form::date('date_issued', $data->date_issued, array('class' => 'form-control select-citizen_id_cit_date_issued')) }}
                        </div>
                        <span class="validate-err" id="err_date_issued"></span>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        {{ Form::label('date_start', __('Start Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                           {{ Form::date('date_start', $data->date_start, array('class' => 'form-control')) }}
                        </div>
                        <span class="validate-err" id="err_date_start"></span>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        {{ Form::label('date_end', __('End Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                           {{ Form::date('date_end', $data->date_end, array('class' => 'form-control select-citizen_id_cit_date_end')) }}
                        </div>
                        <span class="validate-err" id="err_date_end"></span>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                     <div class="form-group" id="patient-group">
                        {{ Form::label('requestor_id', __('Requestor Name'),['class'=>'form-label']) }}
                        <span style="color: red">*</span>
                        <div class="form-icon-user" id='contain_requestor_id'>
                           {{ 
                              Form::select('requestor_id', 
                                    isset($citizens->cit_fullname) ? [$citizens->id=>$citizens->cit_fullname] : [],
                                    $data->requestor_id, 
                                    $attributes = array(
                                    'id' => 'requestor_id',
                                    'data-url' => 'citizens/getCitizens',
                                    'data-placeholder' => 'Search Citizen',
                                    'data-contain' => 'select-contain-citizen',
                                    'data-value' =>isset($citizens->cit_fullname) ? $citizens->cit_fullname : '',
                                    'data-value_id' =>isset($citizens->cit_fullname) ? $citizens->id : '',
                                    'class' => 'form-control ajax-select get-citizen select_id',
                                    ($data->requestor_id) ? 'readonly' : ''
                              )) 
                           }}
                        </div>
                        <span class="validate-err" id="err_requestor_id"></span>
                     </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div  class="accordion accordion-flush">
      <div class="accordion-item">
         <h6 class="accordion-header" id="flush-headingone2">
            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse2" aria-expanded="false" aria-controls="flush-collapse2">
               <h6 class="sub-title accordiantitle">
                  <i class="ti-menu-alt text-white fs-12"></i>
                  <span class="sub-title accordiantitle">{{__("Officers Information")}}</span>
               </h6>
            </button>
         </h6>
         <div id="flush-collapse2" class="accordion-collapse collapse show citizen_group" aria-labelledby="flush-headingone2">
            <div class="basicinfodiv">
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        {{ Form::label('inspected_by', __('Inspected By'),['class'=>'form-label']) }}
                        <span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('inspected_by') }}</span>
                        <div class="form-icon-user d-flex align-items-center">
                           <div class="flex-grow-1">
                              <div class="m-form__group required select-contain" id='contain_inspected_by'>
                                 {{ Form::select('inspected_by',
                                     $employee,
                                     $data->inspected_by, 
                                     array('class' => 'form-control ajax-select',
                                     'id'=>'inspected_by',
                                     'data-url' => 'citizens/selectEmployee',
                                    'data-contain'=>'contain_inspected_by')) }}
                                 <span class="validate-err"  id="err_inspected_by"></span>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        {{ Form::label('approved_by', __('Approved By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('approved_by') }}</span>
                        <div class="form-icon-user" id="contain_approved_by">
                           {{ Form::select('approved_by', 
                              $employee,
                              $data->approved_by,
                               array(
                                 'class' => 'form-control ajax-select',
                                 'id'=>'approved_by',
                                 'data-url' => 'citizens/selectEmployee',
                                 'data-contain'=>'contain_approved_by')) }}
                        </div>
                        <span class="validate-err" id="err_approved_by"></span>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        {{ Form::label('inspector_position', __('Inspector Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('inspector_position') }}</span>
                        <div class="form-icon-user">
                           {{ Form::text('inspector_position', $data->inspector_position, array('class' => 'form-control','id'=>"inspector_position")) }}
                        </div>
                        <span class="validate-err" id="err_inspector_position"></span>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        {{ Form::label('approver_position', __('Approver Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('approver_position') }}</span>
                        <div class="form-icon-user">
                           {{ Form::text('approver_position', $data->approver_position, array('class' => 'form-control','id'=>'approver_position')) }}
                        </div>
                        <span class="validate-err" id="err_approver_position"></span>
                     </div>
                  </div>
               </div>
			   <div class="row">
					<div class="col-md-6">
						@if($esignisinspected_by == \Auth::user()->id)
						   <div class="form-group" id="esign_is_approved">
							  {{ Form::label('inspector_is_approved', __('Approved by Inspector'),['class'=>'form-label']) }}
							  <span style="color: red">*</span> 
							  <span class="validate-err">{{ $errors->first('inspector_is_approved') }}</span>
							  <div class="form-icon-user">
								 {{ Form::checkbox('inspector_is_approved',1, $data->inspector_is_approved,['id' => 'inspector_is_approved']) }}
							  </div>
							  <span class="validate-err" id="err_inspector_is_approved"></span>
						   </div>
					   @endif
					</div>
					<div class="col-md-6">
						@if($esignisapproveds == \Auth::user()->id)
						   <div class="form-group" id="esign_is_approved">
							  {{ Form::label('is_approved', __('Approved Certificate'),['class'=>'form-label']) }}
							  <span style="color: red">*</span> 
							  <span class="validate-err">{{ $errors->first('is_approved') }}</span>
							  <div class="form-icon-user">
								 {{ Form::checkbox('is_approved',1, $data->is_approved,['id' => 'is_approved']) }}
							  </div>
							  <span class="validate-err" id="err_esign_is_approved"></span>
						   </div>
					   @endif
					</div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div  class="accordion accordion-flush">
      <div class="accordion-item">
         <h6 class="accordion-header" id="flush-headingone3">
            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3">
               <h6 class="sub-title accordiantitle">
                  <i class="ti-menu-alt text-white fs-12"></i>
                  <span class="sub-title accordiantitle">{{__("Payment Information")}}{{$data->is_free}}</span>
               </h6>
            </button>
         </h6>
         <div id="flush-collapse3" class="accordion-collapse collapse show citizen_group" aria-labelledby="flush-headingone3">
            <div class="basicinfodiv">
               <div class="row">
                  <div class="col-md-3">
                     <div class="form-group" id="or_no-group">
                        {{ Form::label('or_no_label', __('O.R. No.'),['class'=>'form-label']) }}
                        <span style="color: red;" class="or_no_star {{ $data->is_free !== 1 ? '' : 'hide' }}">
                        *
                        </span>
                        <span class="validate-err">
                        {{ $errors->first('or_no') }}
                        </span>
                        @php
                        $condition = ['class' => 'form-control or_no', 'id' => 'or_no'];
                        $required = [];
                        if($data->is_free !== 1){
                        // $required = ['required' => true];
                        } else {
                        $required = ['readonly'];
                        }
                        @endphp
                        <div class="form-icon-user" id="contain_or_no">
                           {!! Form::select('or_no', 
                           $select_or_nos,
                           ($data->is_free !== 1) ? $data->or_no : 'Free', 
                           array_merge($condition,$required)
                           ) !!}
                        </div>
                        <span class="validate-err" id="err_or_no"></span>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group" id="or-date-group">
                        {{ Form::label('or_date', __('O.R. Date'),['class'=>'form-label']) }}
                        <span style="color: red" class="or_no_star {{ $data->is_free !== 1 ? '' : 'hide' }}">*</span>
                        <span class="validate-err">{{ $errors->first('or_date') }}</span>
                        <div class="form-icon-user">
                           {!! Form::date('or_date',
                           $data->or_date, 
                           ['class' => 'form-control', 'id' => 'or_date', 'placeholder' => 'OR Date', 'readonly' => true]) !!}
                        </div>
                        <span class="validate-err" id="err_or_date"></span>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group" id="service-group">
                        {{ Form::label('or_amount', __('Amount'),['class'=>'form-label']) }}
                        <span style="color: red" class="or_no_star {{ $data->is_free !== 1 ? '' : 'hide' }}">*</span>
                        <span class="validate-err">{{ $errors->first('or_amount') }}</span>
                        <div class="form-icon-user">
                           {!! Form::text('or_amount',  
                           $data->or_amount,
                           [
                           'class' => 'form-control', 
                           'id' => 'or_amount', 
                           'placeholder' => 'Amount', 
                           'readonly' => true
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
                           {!! Form::checkbox('is_free',
                           1, $data->is_free,
                           ['id' => 'is_free']) !!}
                        </div>
                        <span class="validate-err" id="err_is_free"></span>
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
</div>
<div class="modal-footer">
<input type="button" value="{{__('Close')}}" class="btn  btn-light" data-bs-dismiss="modal">
   @if($data->id)
   <a href="{{route('howaterpotability.print',['id'=>$data->id])}}" target="_blank">
      <button class="btn btn-primary" type="button" >
      <i class="fa fa-print icon" ></i>
      {{__('Print')}}
      </button>
      </a>
   <!-- <button type="button" class='btn btn-info btn_open_labreq_modal' data-url="{{route('laboratoryreq.store',['requestor_id'=>$data->requestor_id])}}" data-title="Lab Request">Lab Request</button> -->
   @endif
  
   <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
      <i class="fa fa-save icon"></i>
      <input type="submit" id="savechanges" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
   </div>
</div>
</div>
{{Form::close()}}
<script src="{{ asset('js/waterajax_validation.js') }}"></script>
<script src="{{ asset('js/waterpotability.js') }}"></script>
<script src="{{ asset('js/partials/select-ajax.js?v='.filemtime(getcwd().'/js/partials/select-ajax.js').'') }}"></script>
<script src="{{ asset('js/partials/citizen-ajax.js?v='.filemtime(getcwd().'/js/partials/citizen-ajax.js').'') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
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
	   url : DIR+'healthy-and-safety/water-potability/uploadDocument',
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
			   url :DIR+'healthy-and-safety/water-potability/deleteAttachment', // json datasource
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
</script
