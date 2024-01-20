{{ Form::open(array('url' => 'OccupationalPermit','id'=>'requestpermit')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<style type="text/css">
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
   div.modal-footer .icon{
   margin-right: 10px;
   }
</style>
<div class="modal-body" style="overflow-x: hidden;">
<div class="row">
   <div  class="accordion accordion-flush">
      <div class="accordion-item">
         <h6 class="accordion-header" id="flush-headingone">
            <button class="accordion-button collapsed btn-primary" type="button" style="">
               <h6 class="sub-title accordiantitle">{{__("Citizen Information")}}</h6>
            </button>
         </h6>
         <div id="flush-collapseone" class="accordion-collapse collapse show citizen_group" aria-labelledby="flush-headingone1">
            <div class="basicinfodiv row">
               <div class="col-sm-8">
                  <div class="form-group m-form__group required select-contain" id='contain_requestor_id'>
                     <div>
                        {{ Form::label('cit_last_name', 'Name of Requestor', ['class' =>'form-label required fs-6 fw-bold']) }}<span class="text-danger">*</span>
                     </div>
					 <div class="form-icon-user">
                     {{ 
                     Form::select('requestor_id', 
                     $getcitizens,
                     $data->requestor_id, 
                     $attributes = array(
                     'id' => 'requestor_id',
                     'data-url' => 'citizens/getCitizens',
                     'data-placeholder' => 'Search Citizen',
                     'data-contain' => 'select-contain-citizen-labreq',
                     'data-value' =>isset($data->requestor->cit_fullname) ? $data->requestor->cit_fullname : '',
                     'data-value_id' =>$data->requestor_id,
                     'class' => 'form-control ajax-select get-citizen select_id',
                     ($data->id && $data->is_posted === 1)?'readonly':'',
                     )) 
                     }}
					 </div>
                     <span class="validate-err"  id="err_requestor_id"></span>
                     
                  </div>
                  
               </div>
               
			  @if($data->id)
					   
			  @else
			  <div class="col-md-1" style="margin-top:6px;">
				  <div class="my-4">
					 <button type="button" data-size="xl" class='btn btn-sm btn-info btn_open_second_modal' data-url="{{route('citizen.store',['field'=>'requestor_id'])}}" data-title="Add Records">
						<i class="ti-plus"></i>
					 </button>
				  </div>
			  </div>
			  @endif
               
			   @if($data->id)
				 <div class="col-md-4" >
                  <div class="form-group">
                     {{ Form::label('request_date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                     <div class="form-icon-user">
                        {{ Form::date('request_date', $data->request_date, array('class' => 'form-control','required'=>'required')) }}
                     </div>
                     <span class="validate-err" id="err_lab_reg_date"></span>
                  </div>
               </div>   
			   @else
				 <div class="col-md-3" >
                  <div class="form-group">
                     {{ Form::label('request_date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                     <div class="form-icon-user">
                        {{ Form::date('request_date', $data->request_date, array('class' => 'form-control','required'=>'required')) }}
                     </div>
                     <span class="validate-err" id="err_lab_reg_date"></span>
                  </div>
               </div>  
			   @endif
              
            </div>
            <div class="row">
               <div class="col-md-8">
                  <div class="form-group">
                     {{ Form::label('complete_address', __('Complete Address'),['class'=>'form-label']) }}
                     <span class="validate-err">{{ $errors->first('complete_address') }}</span>
                     <div class="form-icon-user">
						{{ Form::hidden('brgy_id', isset($data->requestor)? $data->requestor->brgy_id:'', array('id' => 'brgy_id', 'class'=>'select_brgy_id')) }}
                  {{ Form::text('complete_address', isset($data->requestor)? $data->requestor->cit_full_address:'', array('class' => 'form-control select_cit_full_address','id'=>'complete_address','readonly'=>'true',($data->id && $data->lab_is_posted === 1)?'readonly':'')) }}
                     </div>
                     <span class="validate-err" id="err_complete_address"></span>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     {{ Form::label('control_no', __('Control Number'),['class'=>'form-label']) }}
                     <span class="validate-err">{{ $errors->first('control_no') }}</span>
                     <div class="form-icon-user">
                        {{ Form::text('control_no', $data->control_no, array('class' => 'form-control','id'=>"control_no",'readonly'=>'true')) }}
                     </div>
                     <span class="validate-err" id="err_gender"></span>
                  </div>
               </div>
             </div>
            </div>
         </div>
      </div>
   </div>
   <div  class="accordion accordion-flush citizen_group">
      <div class="accordion-item">
         <h6 class="accordion-header" id="flush-headingone">
            <button class="accordion-button collapsed btn-primary" type="button" style="">
               <h6 class="sub-title accordiantitle">{{__("Payor Information")}}</h6>
            </button>
         </h6>
         <div id="flush-collapseone" class="accordion-collapse collapse select-contain show" aria-labelledby="flush-headingone1">
            <div class="basicinfodiv">
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {{ Form::label('payor_id', __('Payor Name'),['class'=>'form-label']) }}
                        <div class="form-icon-user" id="contain_payor_id">
                        {{ 
                           Form::select('payor_id', 
                           $getcitizens,
                           $data->payor_id, 
                           $attributes = array(
                           'id' => 'payor_id',
                           'data-url' => 'citizens/getCitizens',
                           'data-placeholder' => 'Search Citizen',
                           'data-contain' => 'select-contain-citizen-labreq',
                           'data-value' =>isset($data->requestor->cit_fullname) ? $data->requestor->cit_fullname : '',
                           'data-value_id' =>$data->payor_id,
                           'class' => 'form-control ajax-select get-citizen select-requestor_id_id select_id',
                           ($data->id && $data->is_posted === 1)?'readonly':'',
                           )) 
                        }}
                        </div>
                        <span class="validate-err" id="err_top_transaction_no"></span>
                     </div>
                  </div>
                  
				 
				<div class="col-md-1" style="margin-top:6px;">
					 <div class="my-4" >
						<button type="button" data-size="xl" class='btn btn-sm btn-info btn_open_second_modal' data-url="{{route('citizen.store',['field'=>'payor_id'])}}" data-title="Add Records" {{($data->id && $data->is_posted === 1)?'disabled':''}}>
						   <i class="ti-plus"></i>
						</button>
					 </div>
				 </div>
				 
                  
                  <div class="col-md-7">
                     <div class="form-group">
                        {{ Form::label('payor_add', __('Payor Address'),['class'=>'form-label']) }}
                        <div class="form-icon-user">
                           {{ Form::text(
                           'payor_add', 
                           isset($data->payor) ? $data->payor->cit_full_address:'', 
                           array(
                           'class' => 'form-control select_cit_full_address select-requestor_id_cit_full_address',
                           'id'=>'payor_address',
                           'required'=>'required', 
                           'readonly',
                           )) 
                           }}
                        </div>
                        <span class="validate-err" id="err_payor_add"></span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div  class="accordion accordion-flush">
      <div class="accordion-item">
         <h6 class="accordion-header" id="flush-headingone">
            <button class="accordion-button collapsed btn-primary" type="button" style="">
               <h6 class="sub-title accordiantitle">{{__("Payment Information")}}</h6>
            </button>
         </h6>
         <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
            <div class="basicinfodiv">
               <div class="row">
                  <div class="col-md-3">
                     <div class="form-group">
                        {{ Form::label('top_transaction_no', __('Transaction No.'),['class'=>'form-label']) }}
                        <div class="form-icon-user">
                           {{ Form::text('top_transaction_no', 
                           $data->top_transaction_no, 
                           array(
                           'class' => 'form-control', 
                           'readonly'
                           )
                           ) }}
                        </div>
                        <span class="validate-err" id="err_top_transaction_no"></span>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        {{ Form::label('or_no', __('OR No.'),['class'=>'form-label']) }}
                        <div class="form-icon-user">
                           {{ Form::text('or_no', 
                           $data->or_no, 
                           array(
                           'class' => 'form-control',
                           'readonly'
                           )
                           ) }}
                        </div>
                        <span class="validate-err" id="err_lab_req_or"></span>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        {{ Form::label('or_date', __('OR Date'),['class'=>'form-label']) }}
                        <div class="form-icon-user">
                           {{ Form::text('or_date', 
                           $data->or_date, 
                           array(
                           'class' => 'form-control',
                           'readonly'
                           )
                           ) }}
                        </div>
                        <span class="validate-err" id="err_or_date"></span>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        {{ Form::label('request_amount', __('Total Amount'),['class'=>'form-label']) }}
                        <div class="form-icon-user">
                           {{ Form::text(
                           'request_amount', 
                           ($data->request_amount)?number_format($data->request_amount,2):'', 
                           array(
                           'class' => 'form-control fee',
                           'required'=>'required', 
						   'data-value'=>($data->request_amount)?number_format($data->request_amount,2):'',
                           'readonly',
                           ($data->id && $data->is_posted === 1)?'readonly':'')
                           ) 
                           }}
                        </div>
                        <span class="validate-err" id="err_lab_req_amount"></span>
                     </div>
                  </div>
                  <div class="col-md-1">
                     <div class="form-group">
                        {{ Form::label('is_free', __('Free'),['class'=>'form-label']) }}
                        <div class="form-icon-user">
                           {{ Form::checkbox('is_free', 
                           '1', 
                           ($data->is_free === 1)?true:false, 
                           array(
                           'id'=>'free',
                           'class'=>'form-check-input code',
                           ($data->id && $data->is_posted === 1)?'disabled':''
                           )) 
                           }}
                        </div>
                        <span class="validate-err" id="err_lab_req_or"></span>
                     </div>
                  </div>
				  
               </div>
            </div>
         </div>
      </div>
   </div>
   <div  class="accordion accordion-flush" id="lab-fees">
      <div class="accordion-item">
         <h6 class="accordion-header" id="flush-headingone">
            <button class="accordion-button collapsed btn-primary" type="button" style="">
               <h6 class="sub-title accordiantitle">{{__("Permit Information")}}</h6>
               <!-- <h6 style="margin-left: 750px;" id="btn_addmore_healthcert" class="btn btn-success" value="Add More">{{__("AddMore")}}</h6> -->
            </button>
         </h6>
         <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
            <div class="basicinfodiv">
               <div class="row">
                  <div class="row field-requirement-details-status" style="color: #fff; background: #20b7cc;">
                     <div class="col-md-1 text-center">
                        {{Form::label('code',__('No.'),['class'=>'form-label'])}}
                     </div>
                     <div class="col-md-3">
                        {{Form::label('code',__('Fees & Charge'),['class'=>'form-label'])}}
                     </div>
                     <div class="col-md-3">
                        {{Form::label('date',__('Service Name'),['class'=>'form-label numeric'])}}
                     </div>
                     <div class="col-md-1">
                        {{Form::label('date',__('Free'),['class'=>'form-label numeric'])}}
                     </div>
                     <div class="col-md-2">
                        {{Form::label('result',__('Fee'),['class'=>'form-label'])}}
                     </div>
                     <div class="col-md-2 text-center">
                        <button type="button" id="btn_addmore_healthcert" class="btn btn-info {{($data->id && $data->lab_is_posted === 1)?'disabled':''}}"  style="padding: 0.4rem 0.76rem !important;" {{($data->id && $data->is_posted == 1)?'disabled':''}}>
                           <i class="ti-plus text-white"></i>
                        </button>
                     </div>
                  </div>
                  <span class="Healthcerti nature-details" id="Healthcerti">
                     <span class="validate-err" id="err_fees"></span>
                     @php $i=0; $j=0;  @endphp  
                     @if(isset($data->fees))
                     @foreach($data->fees as $key=>$val)
                     @php $j=$j+1; @endphp
                     
                     <div class="row removenaturedata">
                        <div class="col-md-1">
                           <div class="form-group">
                              <div class="form-icon-user text-center">
                              {{$j}}
                              </div>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <div class="form-icon-user" id='contain_service_id{{$val->id}}'>
                              {{ 
                                 Form::select('fees['.$val->id.'][service_id]', 
                                 [],
                                 $data->service_id, 
                                 $attributes = array(
                                 'id' => 'service_id'.$val->id,
                                 'data-url' => 'getServices',
                                 'data-placeholder' => 'Search Service',
                                 'data-value' =>isset($val->desc) ? $val->desc->ho_service_name  : '',
                                 'data-value_id' =>$val->service_id,
                                 'class' => 'form-control ajax-select select-service',
                                 ($data->id && $data->is_posted === 1)?'readonly':''
                                 )) 
                                 }}
                                 <span class="validate-err" id="err_fees-{{$val->id}}-service_id"></span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <div class="form-icon-user">
                                 {{ Form::hidden('fees['.$val->id.'][id]',$val->id, array('id' => 'serviceid'.$val->id,'class' => 'healthreqid')) }}
                                 {{Form::text(
                                 'fees['.$val->id.'][service_name]',
                                 $val->desc->service_name,
                                 array(
                                 'class'=>'form-control select_service_name tet',
                                 'placeholder'=>'',
                                 'id'=>'service_name'.$val->id, 
                                 'readonly',
                                 ($data->id && $data->is_posted === 1)?'readonly':'')
                                 )
                                 }}
                                 
                              </div>
                           </div>
                        </div>
                        <div class="col-md-1">
                           <div class="form-group">
                              <div class="form-icon-user">
                                 {{ Form::checkbox('fees['.$val->id.'][hlf_is_free]',
                                 '1', 
                                 ($val->is_free === 1)?true:false,
                                 array(
                                 'class'=>'form-check-input code fee',
                                 'id'=>'is_service_free_'.$val->id, 
                                 (($data->is_free === 1) ? 'disabled':'' ),
                                 ($data->id && $data->is_posted === 1)?'disabled':''
                                 )) 
                                 }}
                              </div>
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="form-group">
                              <div class="form-icon-user">
                                 {{Form::text(
                                 'fees['.$val->id.'][fee]',
                                 ($val->permit_fee)?number_format($val->permit_fee,2):number_format($val->permit_fee,2),
                                 array(
                                 'class'=>'form-control select_ho_service_amount fee',
                                 'placeholder'=>'',
                                 'id'=>'fee'.$val->id, 
                                 'data-value' => number_format($val->desc->ho_service_amount,2),
                                 (($data->is_free === 1) ? 'readonly':'' ),
                                 ($data->id && $data->is_posted === 1)?'readonly':''),
                                 )
                                 }}
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-1">
                           <button class='btn btn-info btn_open_labreqform_modal'
                           style="padding: 0.4rem 1rem !important;"
                           type="button" 
                           data-url="{{url('healthy-and-safety/transfer-of-cadaver/store')}}?permit_id={{$data->id}}&service_id={{$val->service_id}}"
                           data-title="Civil Registrar Permits: {{$val->desc->ho_service_name}}" 
                           {{($data->id && $data->accept != 0 && $val->desc->service_name != 'Exhumation Permit')?'':'disabled'}}>Result</button>
                        </div>
						<div class="col-sm-1">
						
                           <button class='btn btn-danger btn_cancel_healthcert' style="padding: 0.4rem 1rem !important;"  type="button" {{($data->id && $data->is_posted === 1)?'disabled':''}}>
                              <i class="ti-trash text-white"></i>
                           </button>
                        </div>
                     </div>
                     @php $i++; @endphp
                     @endforeach
                     @endif
                  </span>
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
      <button class="btn btn-light" data-bs-dismiss="modal" type="button">{{__('Close')}}</button>
      @if($data->id)
      <a href="{{route('requestpermit.print',['id'=>$data->id])}}" target="_blank">
      <button class="btn btn-primary" type="button" >
      <i class="fa fa-print icon" ></i>
      {{__('Print')}}
      </button>
      </a>
      <button class="btn btn-primary" type="submit" value="submit" {{($data->id && $data->is_posted === 1)?'disabled':''}}>
      {{__('Submit')}}
      </button>
      @endif
	  @if(empty($data->id))
      <button class="btn btn-primary" type="submit" value="submit">
      {{__('Submit')}}
      </button>
      <input type="hidden" class="required-hide" name="submit" value="submit" {{($data->id && $data->is_posted === 1)?'disabled':''}}>
      @endif
      <input type="hidden" class="required-hide" name="submit" value="submit">
      <button class="btn btn-primary" id="savechanges" type="submit2" value="save">
      <i class="fa fa-save icon" ></i>
      {{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}
      </button>
   </div>
</div>
</div>
{{Form::close()}}
<input type="hidden" id="labrequest-url" value="{{url()->full()}}">
<script src="{{ asset('js/partials/select-ajax.js?v='.filemtime(getcwd().'/js/partials/select-ajax.js').'') }}"></script>
<script src="{{ asset('js/partials/citizen-ajax.js?v='.filemtime(getcwd().'/js/partials/citizen-ajax.js').'') }}"></script>
<script src="{{ asset('js/add_requestpermit.js?v='.filemtime(getcwd().'/js/add_requestpermit.js').'') }}"></script>
<script src="{{ asset('js/HealthandSafety/requestpermit.js?v='.filemtime(getcwd().'/js/HealthandSafety/requestpermit.js').'') }}"></script>
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script type="text/javascript">
$(document).ready(function (){
	$("#commonModal").find('.body').css({overflow: 'unset'});
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
	   url : DIR+'OccupationalPermit-uploadDocument',
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
			   url :DIR+'OccupationalPermit-deleteAttachment', // json datasource
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