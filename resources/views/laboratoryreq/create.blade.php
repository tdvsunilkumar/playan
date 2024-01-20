{{ Form::open(array('url' => 'laboratory-request','id'=>'labrequest')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('lab_req_year',$data->lab_req_year, array('id' =>'lab_req_year')) }}

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
   .field-requirement-details-status {
    color: white;
    background-color: #20B7CC;
}
</style>
<div class="modal-body">
<div class="row">
   <div  class="accordion accordion-flush">
      <div class="accordion-item">
         <h6 class="accordion-header" id="flush-headingone">
            <button class="accordion-button collapsed btn-primary" type="button" style="">
               <h6 class="sub-title accordiantitle">{{__("Patient Information")}}</h6>
            </button>
         </h6>
         <div id="flush-collapseone" class="accordion-collapse collapse show citizen_group" aria-labelledby="flush-headingone1">
            <div class="basicinfodiv row">
               <div class="col-md-3">
                  <div class="form-group m-form__group required select-contain" id='contain_cit_id_lab_req'>
                     <div>
                        {{ Form::label('cit_last_name', 'Name of Patient', ['class' =>'form-label required fs-6 fw-bold']) }}<span class="text-danger">*</span>
                     </div>
                     {{ 
                     Form::select('cit_id', 
                     isset($data->patient) ? [$data->cit_id => $data->patient->cit_fullname] : [],
                     $data->cit_id, 
                     $attributes = array(
                     'id' => 'cit_id_lab_req',
                     'data-url' => 'citizens/getCitizens',
                     'data-placeholder' => 'Search Citizen',
                     'class' => 'form-control ajax-select get-citizen select_id select3',
                     ($data->id && $data->lab_is_posted === 1)?'readonly':'',
                     isset($data->record_card)?'readonly':''
                     )) 
                     }}
                     <span class="validate-err"  id="err_cit_id"></span>
                  </div>
               </div>
                  @if($data->id)
                           
                  @else
                  <div class="col-md-1" style="margin-top: 30px;">
                     <button type="button" data-size="xl" class='btn btn-sm btn-info btn_open_second_modal' data-url="{{route('citizen.store',['field'=>'cit_id_lab_req'])}}" data-title="Add Records">
                        <i class="ti-plus"></i>
                     </button>
				  </div>
                  @endif
				  @if($data->id)
                    <div class="col-md-3">
					  <div class="form-group">
						 {{ Form::label('dob', __('Date Of Birth'),['class'=>'form-label']) }}
						 <span class="validate-err">{{ $errors->first('dob') }}</span>
						 <div class="form-icon-user">
							{{ Form::text('dob', isset($data->patient)? $data->patient->cit_date_of_birth:'', array('class' => 'form-control select_cit_date_of_birth','id'=>'dob','readonly'=>'true')) }}
						 </div>
						 <span class="validate-err" id="err_date"></span>
					  </div>
				    </div>      
				  @else
				  <div class="col-md-2">
					  <div class="form-group">
						 {{ Form::label('dob', __('Date Of Birth'),['class'=>'form-label']) }}
						 <span class="validate-err">{{ $errors->first('dob') }}</span>
						 <div class="form-icon-user">
							{{ Form::text('dob', isset($data->patient)? $data->patient->cit_date_of_birth:'', array('class' => 'form-control select_cit_date_of_birth','id'=>'dob','readonly'=>'true')) }}
						 </div>
						 <span class="validate-err" id="err_date"></span>
					  </div>
				   </div>
				  @endif
               
               <div class="col-md-2">
                  <div class="form-group">
                     {{ Form::label('age', __('Age'),['class'=>'form-label']) }}
                     <span class="validate-err">{{ $errors->first('age') }}</span>
                     <div class="form-icon-user">
                        {{ Form::text('age', isset($data->patient)? $data->patient->age_human:'', array('class' => 'form-control select_human_age','id'=>'age','readonly'=>'true')) }}
                     </div>
                     <span class="validate-err" id="err_age"></span>
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="form-group">
                     {{ Form::label('gender', __('Sex'),['class'=>'form-label']) }}
                     <span class="validate-err">{{ $errors->first('gender') }}</span>
                     <div class="form-icon-user">
                        {{ Form::text('gender', isset($data->patient)? $data->patient->gender():'', array('class' => 'form-control select_gender','id'=>"gender",'readonly'=>'true')) }}
                     </div>
                     <span class="validate-err" id="err_gender"></span>
                  </div>
               </div>
               <div class="col-md-2" >
                  <div class="form-group">
                     {{ Form::label('lab_reg_date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                     <div class="form-icon-user">
                        {{ Form::dateTimeLocal('lab_reg_date',$data->lab_reg_date, array('id'=>'lab_reg_date','class' => 'form-control','required'=>'required')) }}	
                     </div>
                     <span class="validate-err" id="err_lab_reg_date"></span>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     {{ Form::label('complete_address', __('Complete Address'),['class'=>'form-label']) }}
                     <span class="validate-err">{{ $errors->first('complete_address') }}</span>
                     <div class="form-icon-user">
                        {{ Form::text('complete_address', isset($data->patient)? $data->patient->cit_full_address:'', array('class' => 'form-control select_cit_full_address','id'=>'complete_address','readonly'=>'true',($data->id && $data->lab_is_posted === 1)?'readonly':'')) }}
                     </div>
                     <span class="validate-err" id="err_complete_address"></span>
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="form-group">
                     {{ Form::label('lab_control_no', __('Control Number'),['class'=>'form-label']) }}
                     <span class="validate-err">{{ $errors->first('lab_control_no') }}</span>
                     <div class="form-icon-user">
                        {{ Form::text('lab_control_no', $data->lab_control_no, array('class' => 'form-control','id'=>"gender",'readonly'=>'true')) }}
                     </div>
                     <span class="validate-err" id="err_gender"></span>
                  </div>
               </div>
               <div class="col-sm-4">
                  <div class="form-group">
                     {{ Form::label('hp_code', __('Internal Requestor'),['class'=>'form-label']) }}
                     <span class="validate-err">{{ $errors->first('hp_code') }}</span>
                     <div class="form-icon-user" id="contain_hp_code">
                           {{ 
										Form::select('hp_code',
										$getEmployee,
										$data->hp_code, 
										array(
                                 'class' =>'form-control ajax-select',
                                 'id'=>'hp_code',
                                 'data-url' => 'citizens/selectEmployee',
                                 'data-contain'=>'contain_hp_code',
                                 ($data->id && $data->lab_is_posted === 1)?'disabled':''
										)
										) 
										}}
                     </div>
                     <span class="validate-err" id="err_hp_code"></span>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-8">
                  <div class="form-group">
                     {{ Form::label('lab_req_diagnosis', __('Diagnosis'),['class'=>'form-label']) }}
                     <div class="form-icon-user">
                        {{ Form::text('lab_req_diagnosis', $data->lab_req_diagnosis, array('class' => 'form-control',($data->id && $data->lab_is_posted === 1)?'readonly':'')) }}
                     </div>
                     <span class="validate-err" id="err_lab_req_diagnosis"></span>
                  </div>
               </div>
               <div class="col-sm-4">
                  <div class="form-group">
                     {{ Form::label('req_phys', __('Requesting Physicican'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                     <div class="form-icon-user">
                        {{ Form::text('req_phys', $data->req_phys, array('class' => 'form-control','id' => 'req_phys','required'=>'required')) }}
                     </div>
                     <span class="validate-err" id="err_req_phys"></span>
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
         <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
            <div class="basicinfodiv">
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {{ Form::label('payor_id', __('Payor Name'),['class'=>'form-label']) }}
                        <div class="form-icon-user" id="contain_payor_id">
                        {{ 
                           Form::select('payor_id', 
                           isset($data->payor) ? [$data->payor_id => $data->payor->cit_fullname] : [],
                           $data->payor_id, 
                           $attributes = array(
                              'id' => 'payor_id',
                              'data-url' => 'citizens/getCitizens',
                              'data-placeholder' => 'Search Citizen',
                              'class' => 'form-control select-cit_id_lab_req_id ajax-select get-citizen select_id',
                              ($data->id && $data->accept != 0 )?'readonly':'',
                           )) 
                        }}
                        </div>
                        <span class="validate-err" id="err_top_transaction_no"></span>
                     </div>
                  </div>
                  
                     @if($data->id)
                              
                     @else
					<div class="col-md-1">
                     <div style="margin-top: 30px;">
                        <button type="button" data-size="xl" class='btn btn-sm btn-info btn_open_second_modal' data-url="{{route('citizen.store',['field'=>'payor_id'])}}" data-title="Add Records">
                           <i class="ti-plus"></i>
                        </button>
                     </div>
					 </div>
                     @endif
                  
                  <div class="col-md-7">
                     <div class="form-group">
                        {{ Form::label('payor_add', __('Payor Address'),['class'=>'form-label']) }}
                        <div class="form-icon-user">
                           {{ Form::text(
                           'payor_add', 
                           isset($data->payor) ? $data->payor->cit_full_address:'', 
                           array(
                           'class' => 'form-control select_cit_full_address select-cit_id_lab_req_cit_full_address',
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
                        {{ Form::label('lab_req_or', __('O.R. No.'),['class'=>'form-label']) }}
                        <div class="form-icon-user">
                           {{ Form::text('lab_req_or', 
                           $data->lab_req_or, 
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
                        {{ Form::label('or_date', __('O.R. Date'),['class'=>'form-label']) }}
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
                        {{ Form::label('lab_req_amount', __('Total Amount'),['class'=>'form-label']) }}
                        <div class="form-icon-user">
                           {{ Form::text(
                           'lab_req_amount', 
                           ($data->lab_req_amount)?number_format($data->lab_req_amount,2):'', 
                           array(
                           'class' => 'form-control fee',
                           'required'=>'required', 
                           'data-value'=>($data->lab_req_amount)?number_format($data->lab_req_amount,2):'', 
                           'readonly',
                           ($data->id && $data->lab_is_posted === 1)?'readonly':'')
                           ) 
                           }}
                        </div>
                        <span class="validate-err" id="err_lab_req_amount"></span>
                     </div>
                  </div>
                  <div class="col-md-1">
                     <div class="form-group">
                        {{ Form::label('lab_is_free', __('Free'),['class'=>'form-label']) }}
                        <div class="form-icon-user">
                           {{ Form::checkbox('lab_is_free', 
                           '0', 
                           ($data->lab_is_free === 1)?true:false, 
                           array(
                           'id'=>'free',
                           'class'=>'form-check-input code',
                           ($data->id && $data->lab_is_posted === 1)?'disabled':''
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
               <h6 class="sub-title accordiantitle">{{__("Laboratory")}}</h6>
               <!-- <h6 style="margin-left: 750px;" id="btn_addmore_healthcert" class="btn btn-success" value="Add More">{{__("AddMore")}}</h6> -->
            </button>
         </h6>
         <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
            <div class="basicinfodiv">
               <div class="row">
                  <div class="row field-requirement-details-status">
                     <div class="col-md-1 text-center">
                        {{Form::label('code',__('No.'),['class'=>'form-label'])}}
                     </div>
                     <div class="col-md-3">
                        {{Form::label('code',__('Fees & Charge'),['class'=>'form-label'])}}
                     </div>
                     <div class="col-md-2">
                        {{Form::label('date',__('Service Name'),['class'=>'form-label numeric'])}}
                     </div>
                     <div class="col-md-1">
                        {{Form::label('date',__('Free'),['class'=>'form-label numeric'])}}
                     </div>
                     <div class="col-md-2">
                        {{Form::label('result',__('Fee'),['class'=>'form-label'])}}
                     </div>
                     <div class="col-md-2 text-center">
                        <button type="button" id="btn_addmore_healthcert" class="btn btn-info"  style="padding: 0.4rem 0.76rem !important;" {{($data->id && $data->lab_is_posted == 1)?'disabled':''}}>
						<i class="ti-plus text-white"></i>
						</button>
                     </div>
                  </div>
                  <span class="Healthcerti nature-details" id="Healthcerti">
                     <span class="validate-err" id="err_fees"></span>
                     @php $i=0; $j=0;  @endphp  
                     @if(isset($data->fees))
                     @foreach($data->fees as $key=>$val)
                     @php 
                     
                     $j=$j+1; 
                     $hlf_is_free_class = ($data->id && $data->lab_is_posted === 1) ? 'form-check-input code' : 'form-check-input code fee'
                     
                     @endphp
                     <div class="row removenaturedata">
                        <div class="col-md-1">
                           <div class="form-group text-center">
                              {{$j}}
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <div class="form-icon-user" id='contain_{{$val->id}}'>
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
                                 ($data->id && $data->lab_is_posted === 1)?'readonly':''
                                 )) 
                                 }}
                                 <span class="validate-err" id="err_fees.{{$val->id}}.service_id"></span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="form-group">
                              <div class="form-icon-user">
                                 {{ Form::hidden('fees['.$val->id.'][id]',$val->id, array('id' => 'serviceid'.$val->id,'class' => 'healthreqid')) }}
                                 {{Form::text(
                                 'fees['.$val->id.'][service_name]',
                                 $val->hlf_service_name,
                                 array(
                                 'class'=>'form-control select_service_name tet',
                                 'placeholder'=>'',
                                 'id'=>'service_name'.$val->id, 
                                 'readonly',
                                 ($data->id && $data->lab_is_posted === 1)?'readonly':'')
                                 )
                                 }}
                              </div>
                           </div>
                        </div>
                        <div class="col-md-1">
                           <div class="form-group">
                              <div class="form-icon-user">
                                 {{ Form::checkbox('fees['.$val->id.'][hlf_is_free]',
                                 1, 
                                 ($val->hlf_is_free === 1)?true:false,
                                    array(
                                    'class'=>'form-check-input code fee',
                                    'id'=>'is_service_free_'.$val->id, 
                                    ($data->hlf_is_free === 1) ? 'disabled':'' ,
                                    ($data->lab_is_posted === 1)?'disabled':''
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
                                 ($val->hlf_fee)?number_format($val->hlf_fee,2):'',
                                 array(
                                    'class'=>'form-control select_ho_service_amount fee',
                                    'placeholder'=>'',
                                    'id'=>'fee'.$val->id, 
                                    'data-value' => number_format($val->desc->ho_service_amount,2),
                                    ($data->lab_is_free === 1) ? 'readonly':'',
                                    ($val->hlf_is_free === 1) ? 'disabled':'',
                                    ($data->id && $data->lab_is_posted === 1)?'disabled':''
                                 )
                                 
                                 )
                                 }}
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-1">
                           <button class='btn btn-info btn_open_labreqform_modal'
                           style="padding: 0.4rem 1rem !important;"
                           type="button" 
                           @switch($val->desc->ho_service_form)
                           @case(1)
                           data-url="{{route('hematology.store',['lab_id'=>$data->id,'service_id'=>$val->service_id])}}"
                           data-title="Hematology: {{$val->desc->ho_service_name}}" 
                           @break
                           @case(2)
                           data-url="{{route('serology.store',['lab_id'=>$data->id,'service_id'=>$val->service_id])}}"
                           data-title="Serology: {{$val->desc->ho_service_name}}" 
                           @break
                           @case(3)
                           data-url="{{route('urinalysis.store',['lab_id'=>$data->id])}}"
                           data-title="{{$val->desc->ho_service_name}}" 
                           @break
                           @case(4)
                           data-url="{{route('fecalysis.store',['lab_id'=>$data->id])}}"
                           data-title="{{$val->desc->ho_service_name}}" 
                           @break
                           @case(5)
                           data-url="{{route('pregnancy-test.store',['lab_id'=>$data->id])}}"
                           data-title="{{$val->desc->ho_service_name}}" 
                           @break
                           @case(6)
                           data-url="{{route('blood-sugar-test.store',['lab_id'=>$data->id,'service_id'=>$val->service_id])}}"
                           data-title="{{$val->desc->ho_service_name}}" 
                           @break
                           @case(7)
                           data-url="{{route('gram-staining-test.store',['lab_id'=>$data->id,'service_id'=>$val->service_id])}}"
                           data-title="{{$val->desc->ho_service_name}}" 
                           @break
                           @default
                           data-url="#"  data-title="" disabled
                           @break
                           @endSwitch
                           {{($data->id && $data->accept != 0 )?'':'disabled'}}>Result </button>
                        </div>
                        <div class="col-sm-1">
                           <button class='btn btn-danger btn_cancel_healthcert' style="padding: 0.4rem 1rem !important;"  type="button" {{($data->id && $data->lab_is_posted === 1)?'disabled':''}}>
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
      <a href="{{route('laboratoryreq.print',['id'=>$data->id])}}" target="_blank">
      <button class="btn btn-primary" type="button" >
      <i class="fa fa-print icon" ></i>
      {{__('Print')}}
      </button>
      </a>
      <button class="btn btn-primary" type="submit" value="submit" {{($data->id && $data->lab_is_posted === 1)?'disabled':''}}>
      {{__('Submit')}}
      </button>
      <input type="hidden" class="required-hide" name="submit" value="submit">
      @endif
      @if(empty($data->id))
      <button class="btn btn-primary" type="submit" value="submit">
      {{__('Submit')}}
      </button>
      <input type="hidden" class="required-hide" name="submit" value="submit" {{($data->id && $data->lab_is_posted === 1)?'disabled':''}}>
      @endif
      <button class="btn btn-primary"  id="savechanges" type="submit2" value="save">
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
<script src="{{ asset('js/add_laboratoryreq.js?v='.filemtime(getcwd().'/js/add_laboratoryreq.js').'') }}"></script>
<script src="{{ asset('js/partials/forms_validation.js?v='.filemtime(getcwd().'/js/partials/forms_validation.js').'') }}"></script>
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
            url :DIR+'laboratory-request/formValidation', // json datasource
            type: "POST", 
            data: $('#labrequest').serialize(),
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
                    $('#labrequest').submit();
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
            var form = $('#labrequest');
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
});


</script>
<script type="text/javascript">
   $(document).ready(function () {
      var now = new Date();
      now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
      document.getElementById('lab_reg_date').value = now.toISOString().slice(0,16);
    $("#commonModal").find('.body').css({overflow: 'unset'}); 
   	$("#uploadAttachmentonly").click(function(){
   		uploadAttachmentonly();
   	});
   	$(".deleteAttachment").click(function(){
   		deleteAttachment($(this));
   	});
	// $('#free').on('change', function(){
	//    this.value = this.checked ? 1 : 0;
	// }).change();
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
   		   url : DIR+'laboratory-request/uploadDocument',
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
   				   url :DIR+'laboratory-request/deleteAttachment', // json datasource
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