{{ Form::open(array('url' => 'recordcard','id'=>'recordcard-form')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<style type="text/css">
   .accordion-button::after{background-image: url();}
   .field-requirement-details-status {
       border-bottom: 1px solid #f1f1f1;
		font-size: 13px;
		color: white;
		background: #2e2e322e;
		text-transform: uppercase;
		margin: 20px 0px 6px 0px;
		margin-top: 20px;
		background-color: #20b7cc;
		width: 99%;
		margin-left: 5px;
   }
   .field-requirement-details-status label{padding-top:5px;}
   .modal-lg, .modal-xl {
   max-width: 975px !important;
   }
   #guardian-group .citizen_group .form-icon-user row div{
      padding: 5px;
   }

   /* The container */
#is_philhealth .container {
   display: block;
   position: relative;
   padding-left: 35px;
   cursor: pointer;
   font-size: 22px;
   -webkit-user-select: none;
   -moz-user-select: none;
   -ms-user-select: none;
   user-select: none;
}

/* Hide the browser's default checkbox */
#is_philhealth  .container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
#is_philhealth  .checkmark {
   position: absolute;
   left: 33%;
   height: 30px;
   width: 30px;
   background-color: #eee;
}

/* On mouse-over, add a grey background color */
#is_philhealth  .container:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
#is_philhealth .container input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
#is_philhealth .checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
#is_philhealth .container input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
#is_philhealth .container .checkmark:after {
   left: 12px;
   top: 5px;
   width: 6px;
   height: 18px;
   border: solid white;
   border-width: 0 3px 3px 0;
   -webkit-transform: rotate(45deg);
   -ms-transform: rotate(45deg);
   transform: rotate(45deg);
}
.citizen_group .col-md-1 {
    padding: 0;
}
</style>
<div class="modal-body">
   <div  class="accordion accordion-flush">
      <div class="accordion-item">
         <h6 class="accordion-header" id="flush-headingone1">
            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse1" aria-expanded="false" aria-controls="flush-collapse1">
               <h6 class="sub-title accordiantitle">
			   <i class="ti-menu-alt text-white fs-12"></i>
			   <span class="sub-title accordiantitle">{{__("Medical Information sefe")}}</span>
			   </h6>
            </button>
         </h6>
         <div id="flush-collapse1" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
            <div class="basicinfodiv">
               <div class="row">
                  <div class="col-md-5">
                     <div class="form-group">
                        {{ Form::label('rec_card_num', __('Record No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                           {{ Form::text('rec_card_num', $data->rec_card_num, array('class' => 'form-control')) }}
                        </div>
                        <span class="validate-err" id="err_rec_card_num"></span>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        {{ Form::label('philhealth_no', __('PhilHealth Member?'),['class'=>'form-label']) }}
                        <span class="validate-err">{{ $errors->first('rec_card_status') }}</span>
                        <div class="form-icon-user" id="is_philhealth">
                           <label class="container">
                              <input type="checkbox" disabled id="is_philhealth_no" class="check-citizen_id_cit_philhealth_no" name="philhealth_no" value="yes" {{isset($data->patient->cit_philhealth_no)?'checked':''}}>
                              <span class="checkmark"></span>
                           </label>
                           <!-- {{ Form::checkbox('philhealth_no', 'yes', isset($data->patient->cit_philhealth_no)?true:false, array('id'=>'is_philhealth_no','disabled'=>'disabled','class'=>'check-citizen_id_cit_philhealth_no')) }} -->
                        </div>
                        <span class="validate-err" id="err_rec_card_status"></span>
                     </div>
                  </div>
                  <div class="col-md-5">
                     <div class="form-group">
                        {{ Form::label('philhealth_no', __('PhilHealth No.'),['class'=>'form-label']) }}
                        <div class="form-icon-user">
                           {{ Form::text('philhealth_no', isset($data->patient)?$data->patient->cit_philhealth_no:'', array('class' => 'form-control select-citizen_id_cit_philhealth_no','readonly'=>'true')) }}
                        </div>
                        <span class="validate-err" id="err_philhealth_no"></span>
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
			   <span class="sub-title accordiantitle">{{__("Patient Information")}}</span>
			   </h6>
            </button>
         </h6>
         <div id="flush-collapse2" class="accordion-collapse collapse show citizen_group" aria-labelledby="flush-headingone2">
            <div class="basicinfodiv">
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {{ Form::label('cit_id', __('Name'),['class'=>'form-label']) }}
                        <span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('cit_id') }}</span>
                        <div class="form-icon-user d-flex align-items-center">
                           <div class="flex-grow-1">
                              <div class="m-form__group required select-contain" id='select-contain-citizen'>
                                 {{ 
                                       Form::select('cit_id', 
                                          [],
                                          $data->cit_id, 
                                          $attributes = array(
                                          'id' => 'citizen_id',
                                          'data-url' => 'citizens/getCitizens',
                                          'data-placeholder' => 'Search Citizen',
                                          'data-contain' => 'select-contain-citizen',
                                          'data-value' =>isset($data->patient->cit_fullname) ? $data->patient->cit_fullname : '',
                                          'data-value_id' =>$data->cit_id,
                                          'class' => 'form-control ajax-select select_id get-citizen',
                                          ($data->id) ? 'readonly' : ''
                                       )) 
                                 }}
                                 
                                 <span class="validate-err"  id="err_cit_id"></span>
                              </div>
                           </div>
                           @if($data->id)
                           
                           @else
                           <div class="ms-2">
                              <button type="button" class='btn btn-sm btn-info btn_open_labreq_modal' data-url="{{route('citizen.store',['field'=>'citizen_id'])}}" data-title="Add Records">
                                 <i class="ti-plus"></i>
                              </button>
                           </div>
                           @endif
                        </div>
                        <span class="validate-err" id="err_cit_id"></span>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        {{ Form::label('age', __('Age'),['class'=>'form-label']) }}
                        <span class="validate-err">{{ $errors->first('age') }}</span>
                        <div class="form-icon-user">
                           {{ Form::text('age', isset($data->patient)?$data->patient->age_human:'', array('class' => 'form-control select_age','id'=>'age','readonly'=>'true')) }}
                        </div>
                        <span class="validate-err" id="err_age"></span>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        {{ Form::label('gender', __('Sex'),['class'=>'form-label']) }}
                        <span class="validate-err">{{ $errors->first('gender') }}</span>
                        <div class="form-icon-user">
                           {{ Form::text('gender', isset($data->patient)?$data->patient->gender():'', array('class' => 'form-control select_gender','id'=>"gender",'readonly'=>'true')) }}
                        </div>
                        <span class="validate-err" id="err_gender"></span>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        {{ Form::label('occupation', __('Occupation'),['class'=>'form-label']) }}
                        <span class="validate-err">{{ $errors->first('occupation') }}</span>
                        <div class="form-icon-user">
                           {{ Form::text('occupation', isset($citizenDetails->occupation)?$citizenDetails->occupation:'', array('class' => 'form-control select_cit_occupation','id'=>'occupation','readonly'=>'true')) }}
                        </div>
                        <span class="validate-err" id="err_occupation"></span>
                     </div>
                  </div>
               </div>
               <div class="row">
               <div class="col-md-7">
                     <div class="form-group">
                        {{ Form::label('complete_address', __('Complete Address'),['class'=>'form-label']) }}
                        <span class="validate-err">{{ $errors->first('complete_address') }}</span>
                        <div class="form-icon-user">
                           {{ Form::text('complete_address', isset($data->patient)?$data->patient->cit_full_address:'', array('class' => 'form-control select_cit_full_address','id'=>'complete_address','readonly'=>'true')) }}
                        </div>
                        <span class="validate-err" id="err_complete_address"></span>
                     </div>
                  </div>
                  
                  <div class="col-md-5">
                     <div class="form-group">
                        {{ Form::label('nationality', __('Nationality'),['class'=>'form-label']) }}
                        <span class="validate-err">{{ $errors->first('nationality') }}</span>
                        <div class="form-icon-user">
                           {{ Form::text('nationality', isset($data->patient)?$data->patient->nationality():'', array('class' => 'form-control select_nationality','id'=>'nationality','readonly'=>'true')) }}
                        </div>
                        <span class="validate-err" id="err_nationality"></span>
                     </div>
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
                 <span class="sub-title accordiantitle">{{__("Guardian Information")}}</span>
			   </h6>
            </button>
         </h6>
         <div class="row">
            <div class="col-md-2" style="margin-top: 20px;">
               <button type="button" id="btn_add_guardian" class='btn btn-sm btn-primary' style="margin-left: 885px;"><i class="ti-plus"></i></button>
            </div>
         </div>
         <div id="flush-collapse3" class="accordion-collapse collapse show" aria-labelledby="flush-headingone3">
            <div class="basicinfodiv">
               <div class="row">
                  <div class="row field-requirement-details-status">
                     <div class="col-md-4">
                        {{Form::label('name',__('Name'),['class'=>'form-label'])}}
                     </div>
                     <div class="col-md-3">
                        {{Form::label('',__('Complete Address'),['class'=>'form-label numeric'])}}
                     </div>
                     <div class="col-md-2">
                        {{Form::label('result',__('Contact No.'),['class'=>'form-label'])}}
                     </div>
                     <div class="col-md-1">
                        {{Form::label('result',__('Status'),['class'=>'form-label'])}}
                     </div>
                     <div class="col-md-2">
                        {{Form::label('result',__('Action ddg'),['class'=>'form-label'])}}
                     </div>
                  </div>
                  <span class="Healthcerti nature-details" id="guardian-group">
                     @php $i=0; $j=0; @endphp 
                     @if(isset($data->guardians)) 
                     @foreach($data->guardians as $key=>$val)
                     @php $j=$j+1; @endphp
                     <div class="row updatehealthcertidata citizen_group">
                        <div class="col-md-4">
                           <div class="form-group form-icon-user align-items-center">
                              <div class="row">
                                 <div class="m-form__group required col-md-10" id='select-contain-guardian-{{$val->id}}'>
                                    {{ 
                                          Form::select('guardian['.$val->id.'][cit_id]', 
                                             [],
                                             $val->cit_id, 
                                             $attributes = array(
                                             'id' => 'guardian-'.$val->id,
                                             'data-url' => 'citizens/getCitizens',
                                             'data-placeholder' => 'Search Citizen',
                                             'data-contain' => 'select-contain-guardian-'.$val->id,
                                             'data-value' =>isset($val->citizen) ? $val->citizen->cit_fullname : '',
                                             'data-value_id' =>$val->cit_id,
                                             'class' => 'form-control select-guardian guardian ajax-select select_id get-citizen',
                                             ($data->id && $data->lab_is_posted === 1)?'readonly':'',
                                             isset($data->record_card)?'readonly':''
                                          )) 
                                    }}
                                    {{ Form::hidden('guardian['.$val->id.'][id]',$val->id, array('id' => 'serviceid','class' => 'fees_charges')) }}
                                 </div>
                                 <div class="col-md-2">
                                    <button type="button" class='btn btn-sm btn-info btn_open_labreq_modal' data-url="{{route('citizen.store',['field'=>'guardian-'.$val->id])}}" data-title="Add Records">
                                       <i class="ti-plus"></i>
                                    </button>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <div class="form-icon-user">
                                 {{Form::text('guardian['.$val->id.'][comaddress]',isset($val->citizen) ? $val->citizen->cit_full_address : '', array('class'=>'form-control select_cit_full_address','placeholder'=>'','id'=>'service_name','readonly'))}}
                              </div>
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="form-group">
                              <div class="form-icon-user">
                                 {{Form::text('guardian['.$val->id.'][contactno]',isset($val->citizen) ? $val->citizen->cit_mobile_no : '',array('class'=>'form-control select_cit_mobile_no','placeholder'=>'','id'=>'fee','readonly'))}}
                              </div>
                           </div>
                        </div>
                        <div class="col-md-1">
                           <div class="form-group">
                              <div class="form-icon-user">
                                 {{-- @if($val->guardian_status===1)
                                 <span class="btn btn-success delete-grd-status-{{ $val->id }}" style="padding: 0.1rem 0.5rem !important;">Active</span>
                                 @else
                                 <span class="btn btn-warning restore-grd-status-{{ $val->id }}" style="padding: 0.1rem 0.5rem !important;">InActive</span>
                                 @endif --}}
                                 <span class="btn btn-success delete-grd-status-{{ $val->id }}
                                    {{ $val->guardian_status===1 ? '' : 'hide' }}"
                                    style="padding: 0.1rem 0.5rem !important;">
                                    Active
                                 </span>
                                 <span class="btn btn-warning restore-grd-status-{{ $val->id }}
                                    {{ $val->guardian_status===0 ? '' : 'hide' }}" 
                                    style="padding: 0.1rem 0.5rem !important;">
                                    InActive
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-1">
                           {{-- @if($val->guardian_status===1)
                              <button class="btn btn-small btn-danger btn_cancel_healthcert" 
                                 style="padding: 0.4rem 1rem !important;" 
                                 type="button" 
                                 data-value="{{$val->guardian_status}}" 
                                 data-id="{{$val->id}}">
                                 <i class="ti-trash text-white"></i>
                              </button>
                           @else
                              <button class="btn btn-small btn-danger btn_cancel_healthcert" 
                              style="padding: 0.4rem 1rem !important;" 
                              type="button" 
                              data-value="{{$val->guardian_status}}" 
                              data-id="{{$val->id}}">
                              <i class="ti-reload text-white"></i>
                           </button>
                           @endif --}}
                           <button class="btn btn-danger btn_cancel_healthcert delete-{{$val->id}} 
                              {{ $val->guardian_status===1 ? '' : 'hide' }}" 
                              style="padding: 0.4rem 1rem !important;" 
                              type="button" 
                              data-value="1" 
                              data-id="{{$val->id}}">
                              <i class="ti-trash text-white"></i>
                           </button>
                           <button class="btn btn-info btn_cancel_healthcert restore-{{$val->id}} 
                              {{ $val->guardian_status===0 ? '' : 'hide' }}" 
                              style="padding: 0.4rem 1rem !important;" 
                              type="button"
                              data-value="0" 
                              data-id="{{$val->id}}">
                              <i class="ti-reload text-white"></i>
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
   @if($citizensdata)
   <div  class="accordion accordion-flush" >
      <div class="accordion-item">
         <h6 class="accordion-header" id="flush-headingone5">
            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse4" aria-expanded="false" aria-controls="flush-collapse4">
			   <h6 class="sub-title accordiantitle">
			   <i class="ti-menu-alt text-white fs-12"></i>
               <span class="sub-title accordiantitle">{{__("Medical Records")}}</span>
			   </h6>
            </button>
         </h6>
         <div class="row">
            <div class="col-md-5">
            </div>
            <div class="col-md-2" style="margin-top: 20px;">
               <!-- <span>Attending Health Officers</span> -->
            </div>
            <div class="col-md-3" style="margin-top: 20px;">
               {{-- Form::select('emp_id[]', $empdata, '', array('class' => 'form-control','id'=>'emp_id')) --}}
            </div>
            <div class="col-md-2" style="margin-top: 20px;">
               <button type="button" class='btn btn-sm btn-primary btn_open_labreq_modal' data-url="{{route('medical.store',['record_id'=>$data->id])}}" data-title="Add Records" style="margin-left: 85px;"><i class="ti-plus"></i></button>
            </div>
         </div>
         <div id="flush-collapse4" class="accordion-collapse collapse" aria-labelledby="flush-headingone5">
            <div class="basicinfodiv">
               <div class="row">
                  {{-- <div class="row field-requirement-details-status">
                     <div class="col-md-2">
                        {{Form::label('date',__('Date And Time'),['class'=>'form-label'])}}
                     </div>
                     <div class="col-md-3">
                        <table>
                           <tr>
                              <td>{{Form::label('diagnosis',__('Diagnosis'),['class'=>'form-label numeric'])}}</td>
                              <td>
                           </tr>
                        </table>
                     </div>
                     <div class="col-md-3">
                        <table>
                           <tr>
                              <td>{{Form::label('treatment',__('Treatment/Management'),['class'=>'form-label'])}}</td>
                              <td></td>
                           </tr>
                        </table>
                     </div>
                     <div class="col-md-2">
                        {{Form::label('notes',__('Nurse Notes'),['class'=>'form-label'])}}
                     </div>
                     <div class="col-md-2">
                        {{Form::label('action',__('Action'),['class'=>'form-label'])}}
                     </div>
                  </div>
                  <span class="Medical nature-details" id="Medical">
                     @php $i=0; $j=0; @endphp  
                     @foreach($medicaldata as $key=>$val)
                     @php $j=$j+1; @endphp
                     <div class="row removemedicaldata" data-id="{{$val->id}}">
                        <div class="col-md-2">
                           <div class="form-group">
                              <div class="form-icon-user">{{Carbon\Carbon::parse($val->med_rec_date)->toDayDateTimeString()}}</div>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <div class="form-icon-user">
                                       @if($val->diagnosis)
                                       @foreach($val->diagnosis as $diagnose)
                                          <p>{{$diagnose->diag_name}} {{($diagnose->is_specified)?': '.$diagnose->is_specified:''}}</p>
                                       @endforeach
                                       @endif
                              </div>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <div class="form-icon-user">
                                 @if($val->treatment)
                                    @foreach($val->treatment as $diagnose)
                                       <p>{{$diagnose->treat_medication}} / {{$diagnose->treat_management}}</p>
                                    @endforeach
                                 @endif
                              </div>
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="form-group">
                              <div class="form-icon-user" style="word-wrap: break-word;">{!!nl2br($val->med_rec_nurse_note)!!}</div>
                           </div>
                        </div>
                        <div class="col-sm-1">
                           <button type="button" data-url="{{route('medical.store',['id'=>$val->id])}}" class="btn btn-sm btn-primary btn_open_labreq_modal">
                              <i class="ti-eye text-white"></i>
                           </button>
                        </div>
                        <div class="col-sm-1">
                           <button type="button" class="btn btn-sm btn-danger btn_cancel_medical" >
                              <i class="ti-trash text-white"></i>
                           </button>
                        </div>
                     </div>
                     <hr>
                     @php $i++; @endphp
                     @endforeach 
                  </span>--}}

                  @if($data->id)
                  <table width="100%" class="table align-middle" id="medical-record-table" data-href="{{route('medical.getListSpecficPatient',['id' => $data->id])}}">
                     <thead>
                        <tr>
                           <th width="10%">#</th>
                           <th width="10%">Date And Time</th>
                           <th width="20%">Diagnosis</th>
                           <th width="20%">Treatment/Management</th>
                           <th width="30%">Nurse Notes</th>
                           <th width="10%">Action</th>
                        </tr>
                     </thead>
                     <tbody>

                     </tbody>
                  </table>
                  @endif
               </div>
            </div>
         </div>
      </div>
   </div>
   <div  class="accordion accordion-flush" >
      <div class="accordion-item">
         <h6 class="accordion-header" id="flush-headingone5">
            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse5" aria-expanded="false" aria-controls="flush-collapse5">
			   <h6 class="sub-title accordiantitle">
			   <i class="ti-menu-alt text-white fs-12"></i>
               <span class="sub-title accordiantitle">{{__("Laboratory Request")}}</span>
			   </h6>
            </button>
         </h6>
         <div class="row">
            <div class="col-md-10"></div>
            <div class="col-md-2" style="margin-top: 20px;">
               <div class="float-end" style="margin-right:20px;">
                  <a type="button"
                     data-url="{{route('laboratoryreq.store',['cit_id'=>$data->cit_id])}}" 
                     data-bs-toggle="tooltip" 
                     title="{{__('Manage Laboratory')}}" 
                     data-title="{{__('Manage Laboratory')}}" 
                     data-size="xxll"
                     class="btn btn-sm btn-primary btn_open_issue_modal">
                     <i class="ti-plus"></i>
                  </a> 
               </div> 
            </div>
         </div>
         <div id="flush-collapse5" class="accordion-collapse collapse" aria-labelledby="flush-headingone5">
            <div class="basicinfodiv">
               <div class="row">
                  <div class="table-responsive">
                     <table width="100%" class="table align-middle" id="laboratory-record-table" data-href="{{route('laboratoryreq/getListSpecific', ['cit_id'=>$data->cit_id])}}">
                        <thead>
                           <tr>
                              <th width="10%">No.</th>
                              <th width="50%">Service Name</th> 
                              <th width="20%">O.R. No.</th>
                              <th width="10%">Date</th>
                              <th width="10%">Action</th>
                           </tr>
                        </thead>
                        <tbody></tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   {{-- Nausad Code --}}
   <div  class="accordion accordion-flush" id="accordionFlushExample7">
      <div class="accordion-item">
         <h6 class="accordion-header" id="flush-headingone6">
            <button class="accordion-button collapsed btn-primary" 
               type="button" data-bs-toggle="collapse" 
               data-bs-target="#flush-collapse6"
               aria-expanded="false" aria-controls="flush-collapse6">
              <h6 class="sub-title accordiantitle">
			   <i class="ti-menu-alt text-white fs-12"></i>
			   <span class="sub-title accordiantitle">{{__("Medicine Issuances")}}</span>
			   </h6>
            </button>
         </h6>
         <div class="row">
            <div class="col-md-10"></div>
            <div class="col-md-2" style="margin-top: 20px;">
               <div class="float-end" style="margin-right:20px;">
                  <a type="button"
                     data-url="{{ url('/medicine-supplies-issuance/add?type=1&patient_id=' . $data->cit_id) }}" 
                     data-bs-toggle="tooltip" 
                     title="{{__('Manage Issuances')}}" 
                     data-title="{{__('Manage Issuances')}}" 
                     data-size="xxll"
                     class="btn btn-sm btn-primary btn_open_issue_modal">
                     <i class="ti-plus"></i>
                  </a> 
               </div> 
            </div>
         </div>
         <div id="flush-collapse6" class="accordion-collapse collapse" aria-labelledby="flush-headingone6" data-bs-parent="#accordionFlushExample7">
            <div class="basicinfodiv">
               <div class="row">
                  <div class="table-responsive">
                     <table width="100%" class="table" id="item-table" data-href="{{route('medicine-supplies-issuance/getListSpecific', ['cit_id'=>$data->cit_id])}}">
                        <thead>
                           <tr>
                              <th width="10%">#</th>
                              <th width="20%">Product Name And Description</th>
                              <th width="20%">Unit</th>
                              <th width="20%">Quantity</th>
                              <th width="20%">Date</th>
                              <th width="10%">Action</th>
                           </tr>
                        </thead>
                        
                        <tbody></tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   {{-- End of Nausad's Code --}}
   @endif
   @if($data->id > 0)
		<div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample7">  
			<div  class="accordion accordion-flush">
				<div class="accordion-item">
					<h6 class="accordion-header" id="flush-heading7">
						<button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse7" aria-expanded="false" aria-controls="flush-collapse7">
							<h6 class="sub-title accordiantitle">
								<i class="ti-menu-alt text-white fs-12"></i>
								<span class="accordiantitle-icon">{{__("Upload")}}
								</span>
							</h6>
						</button>
					</h6>
					<div id="flush-collapse7" class="accordion-collapse collapse" aria-labelledby="flush-heading7" data-bs-parent="#accordionFlushExample7">
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
<div class="modal-footer">
   @if($data->id)
      <!-- <button type="button" class='btn btn-info btn_open_labreq_modal' data-url="{{route('laboratoryreq.store',['cit_id'=>$data->cit_id])}}" data-title="Lab Request">Lab Request</button> -->
   @endif
   <input type="button" value="{{__('Close')}}" class="btn  btn-light" data-bs-dismiss="modal">
   <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
      <i class="fa fa-save icon"></i>
      <input type="submit" id="savechanges" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
   </div>
</div>
@php $i++; @endphp
</div>
{{Form::close()}}

<div id="hidenmedicalHtml" class="hide">
   <div class="row removemedicaldata">
      <div class="col-md-2">
         <div class="form-group">
            <div class="form-icon-user">
               {{ Form::date('date', '', array('class' => 'form-control','id'=>'date')) }}
            </div>
         </div>
      </div>
      <div class="col-md-3">
         <div class="form-group">
            <div class="form-icon-user">
               <table>
                  <tr>
                     <td>{{ Form::select('diagnosis[]',$diagnosisdata,'', array('class' => 'form-control','id'=>'diagnosis')) }}</td>
                     <td> <input type="button" name="btn_cancel_diagnosis" class="btn btn-sm btn-danger btn_cancel_diagnosis" value="D" ></td>
                  </tr>
               </table>
            </div>
         </div>
      </div>
      <div class="col-md-3">
         <div class="form-group">
            <div class="form-icon-user">
               <table>
                  <tr>
                     <td>{{Form::text('medication[]','',array('class'=>'form-control','id'=>'medication'))}}</td>
                     <td>{{Form::text('management[]','',array('class'=>'form-control','id'=>'management'))}}</td>
                     <td><input type="button" name="btn_cancel_treatment" class="btn btn-sm btn-danger btn_cancel_treatment" value="D" ></td>
                  </tr>
               </table>
            </div>
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <div class="form-icon-user">
               {{Form::textarea('notes[]','',array('class'=>'form-control','id'=>'notes'))}}
            </div>
         </div>
      </div>
      <div class="col-sm-1">
         <input type="submit" name="btn_cancel_sanitary" class="btn btn-sm btn-primary" ><i class="ti-pencil text-white"></i>
      </div>
      <div class="col-sm-1">
         <input type="button" name="btn_cancel_medical" class="btn btn-sm btn-danger btn_cancel_medical" value="D" >
      </div>
   </div>
</div>
<div class="hide col-md-3" id="hidendiagnosisHtml">
   <div class="removediagnosisdata form-group">
      <div class="form-icon-user">
         <table>
            <tr>
               <td>{{ Form::select('diagnosis[]',$diagnosisdata,'', array('class' => 'form-control','id'=>'diagnosis')) }}</td>
               <td><input type="button" name="btn_cancel_healthcert" class="btn btn-sm btn-danger" value="D" ></td>
            </tr>
         </table>
      </div>
   </div>
</div>
<div class="hide col-md-3" id="hidentreatmentHtml">
   <div class="removetreatmentdata form-group">
      <div class="form-icon-user">
         <table>
            <tr>
               <td>{{Form::text('medication[]','',array('class'=>'form-control','id'=>'medication'))}}</td>
               <td>{{Form::text('management[]','',array('class'=>'form-control','id'=>'management'))}}</td>
            
               <td><input type="button" name="btn_cancel_healthcert" class="btn btn-sm btn-danger" value="D" ></td>
            </tr>
         </table>
      </div>
   </div>
</div>
<script src="{{ asset('js/SocialWelfare/select-ajax.js?v='.filemtime(getcwd().'/js/SocialWelfare/select-ajax.js').'') }}"></script>
<script src="{{ asset('js/SocialWelfare/citizen-ajax.js?v='.filemtime(getcwd().'/js/SocialWelfare/citizen-ajax.js').'') }}"></script>
<script src="{{ asset('js/ajax_validation_issuance.js') }}?rand={{ rand(000,999) }}"></script>
<script src="{{ asset('js/add_recordcard.js') }}?rand={{ rand(000,999) }}"></script>
<script src="{{ asset('js/HealthandSafety/table_recordform.js?v='.filemtime(getcwd().'/js/HealthandSafety/table_recordform.js').'') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
   var shouldSubmitForm = false;
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
		if (typeof $('#ora_document')[0].files[0]== "undefined") {
			$("#err_documents").html("Please upload Document");
			return false;
		}
		var formData = new FormData();
		formData.append('file', $('#ora_document')[0].files[0]);
		formData.append('healthCertId', $("#id").val());
		showLoader();
		$.ajax({
		   url : DIR+'recordcard-uploadDocument',
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
				   url :DIR+'recordcard-deleteAttachment', // json datasource
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