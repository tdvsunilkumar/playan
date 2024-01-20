{{ Form::open(array('url' => 'serology','class'=>'formDtls','id'=>'serology-form')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('lab_req_id',$data->lab_req_id, array('id' => 'lab_req_id')) }}
{{ Form::hidden('ser_lab_no',$data->ser_lab_no, array('id' => 'ser_lab_no')) }}
<style>
   table {
   font-family: arial, sans-serif;
   border-collapse: collapse;
   width: 100%;
   }
   .tbody_calss, .thead_class {
   border: 1px solid #dddddd;
   text-align: left;
   padding: 8px;
   }
</style>
<div class="modal-body">
   <div class="row pt10">
      <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample">
         <div class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingone">
                  <button class="accordion-button  btn-primary" type="button">
                     <h6 class="sub-title accordiantitle">Laboratory Details</h6>
                  </button>
               </h6>
               <div id="flush-collapseone" class="accordion-collapse collapse show">
                  <div class="basicinfodiv">
                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              {{ Form::label('cit_id', __('Name'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('cit_id') }}</span>
                              <div class="form-icon-user">
                                 {{ 
                                 Form::text('cit_fullname', 
                                 $data->patient->cit_fullname, 
                                 array(
                                 'id'=>'cit_fullname',
                                 'class' => 'form-control',
                                 'required'=>'required',
                                 'readonly')
                                 ) 
                                 }}
                                 {{ 
                                 Form::hidden('cit_id', 
                                 $data->cit_id, 
                                 array(
                                 'id'=>'cit_id',
                                 'class' => 'form-control',
                                 'required'=>'required',
                                 'readonly')
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_cit_id"></span>
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="form-group">
                              {{ Form::label('ser_age', __('Age'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('ser_age') }}</span>
                              <div class="form-icon-user">
                                 {{ 
                                 Form::text('ser_age', 
                                 $data->patient->age_human, 
                                 array(
                                 'id'=>'ser_age',
                                 'class' => 'form-control',
                                 'required'=>'required',
                                 'readonly')
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_ser_age"></span>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              {{ Form::label('sex', __('Sex'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('sex') }}</span>
                              <div class="form-icon-user">
                                 {{ 
                                 Form::text('sex', 
                                 $data->patient->gender(), 
                                 array(
                                 'id'=>'sex',
                                 'class' => 'form-control',
                                 'required'=>'required',
                                 'readonly')
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_lab_req_id"></span>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              {{ Form::label('ser_date', __('Date'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('ser_date') }}</span>
                              <div class="form-icon-user">
                                 <!--{{ Form::date('ser_date',date('Y-m-d'), array('id'=>'ser_date','class' => 'form-control','required'=>'required')) }} -->
                                 <input id="ser_date"  name="ser_date"  value="{{$data->ser_date}}" type="datetime-local" class="form-control" {{($data->id && $data->ser_is_posted === 1)?'disabled':''}} required/> 
                              </div>
                              <span class="validate-err" id="err_ser_date"></span>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              {{ Form::label('lab_control_no', __('Laboratory Control No.'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('lab_control_no') }}</span>
                              <div class="form-icon-user">
                                 {{ Form::text('lab_control_no',$data->lab_control_no, array('id'=>'lab_control_no','class' => 'form-control','readonly')) }}
                              </div>
                              <span class="validate-err" id="err_lab_control_no"></span>
                           </div>
                        </div>
                        <div class="col-md-5">
                           <div class="form-group">
                              {{ Form::label('ser_or_num', __('O.R. Number'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('pt_or_num') }}</span>
                              <div class="form-icon-user">
                                 {{ 
                                 Form::text('ser_or_num', 
                                 $data->ser_or_num, 
                                 array(
                                 'id'=>'ser_or_num',
                                 'class' => 'form-control',
                                 'required'=>'required',
                                 'readonly')
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_ser_or_num"></span>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              {{ Form::label('ser_lab_num', __('Lab. No.'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('ser_lab_num') }}</span>
                              <div class="form-icon-user">
                                 {{ Form::text('ser_lab_num',$data->ser_lab_num, array('id'=>'ser_lab_num','class' => 'form-control','readonly')) }}
                              </div>
                              <span class="validate-err" id="err_ser_lab_num"></span>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingone">
                  <button class="accordion-button  btn-primary" type="button">
                     <h6 class="sub-title accordiantitle">Health Officer Details</h6>
                  </button>
               </h6>
               <div id="flush-collapseone" class="accordion-collapse collapse show">
                  <div class="basicinfodiv">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('med_tech', __('Medical Technologist'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                              <span class="validate-err">{{ $errors->first('med_tech') }}</span>
                              <div class="form-icon-user" id="contain_med_tech">
                                 {{ 
                                 Form::select('med_tech',
                                 $getphysician,
								 ($data->id > 0 && isset($data->med_tech))?$data->med_tech:$last_user_data->med_tech_id,
                                 $attributes = array(
                                 'id'=>'med_tech',
                                 'data-url' => 'citizens/selectEmployee',
                                 'data-contain'=>'med_tech_contain',
                                 'class' =>'form-control ajax-select',
                                 ($data->id && $data->ser_is_posted === 1)?'disabled':''
                                 )
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_med_tech"></span>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('health_officer', __('Health Officer'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                              <span class="validate-err">{{ $errors->first('health_officer') }}</span>
                              <div class="form-icon-user" id="contain_health_officer">
                                 {{ 
                                 Form::select('health_officer',
                                 $getphysician,
								 ($data->id > 0 && isset($data->health_officer))?$data->health_officer:$last_user_data->health_officer_id,
                                 array(
                                 'class' =>'form-control ajax-select',
                                 'id'=>'health_officer',
                                 'data-url' => 'citizens/selectEmployee',
                                 'data-contain'=>'contain_health_officer',
                                 ($data->id && $data->ser_is_posted === 1)?'disabled':''
                                 )
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_health_officer"></span>
                           </div>
                        </div>
                        <!-- <div class="col-md-4">
                           <div class="form-group">
                              {{ Form::label('hp_code', __('Requesting Physician'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                              <span class="validate-err">{{ $errors->first('hp_code') }}</span>
                              <div class="form-icon-user" id="hp_code_contain">
                                 {{ 
                                 Form::select('hp_code',
                                 $getphysician,
                                 $data->hp_code, 
                                 array(
                                 'class' =>'form-control select3',
                                 'id'=>'hp_code',
                                 'data-contain'=>'hp_code_contain',
                                 ($data->id && $data->ser_is_posted === 1)?'disabled':''
                                 )
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_hp_code"></span>
                           </div>
                           </div> -->
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('med_tech_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                              <span class="validate-err">{{ $errors->first('med_tech_position') }}</span>
                              <div class="form-icon-user">
                                 {{ 
                                 Form::text('med_tech_position',
                                 ($data->id > 0 && isset($data->med_tech_position))?$data->med_tech_position:$last_user_data->med_tech_position,
                                 array(
                                 'id'=>'med_tech_position',
                                 'class' => 'form-control',
                                 'required'=>'required',
                                 ($data->id && $data->ser_is_posted === 1)?'disabled':''
                                 )
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_med_tech_position"></span>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('health_officer_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                              <span class="validate-err">{{ $errors->first('health_officer_position') }}</span>
                              <div class="form-icon-user">
                                 {{ 
                                 Form::text('health_officer_position',
                                 ($data->id > 0 && isset($data->health_officer_position))?$data->health_officer_position:$last_user_data->health_officer_position,
                                 array(
                                 'id'=>'health_officer_position',
                                 'class' => 'form-control',
                                 'required'=>'required',
                                 ($data->id && $data->ser_is_posted === 1)?'disabled':''
                                 )
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_health_officer_position"></span>
                           </div>
                        </div>
                        <div class="col-md-6">
						 @if($esignisapproveds == \Auth::user()->id)
                           <div class="form-group" id="esign_is_approved">
                              {{ Form::label('esign_is_approved', __('Approved by Medical Technologist'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('esign_is_approved') }}</span>
                              <div class="form-icon-user">
                                 {{ Form::checkbox('esign_is_approved',  
                                       1, $data->esign_is_approved,
								 ['id' => 'esign_is_approved']) }}
                              </div>
                              <span class="validate-err" id="err_esign_is_approved"></span>
                           </div>
						   @endif
                        </div>
						<div class="col-md-6">
						   @if($officerisapproved == \Auth::user()->id)
                              <div class="form-group" id="officer_is_approved" style="margin-left:5px;">
                                 {{ Form::label('officer_is_approved', __('Approved by Health Officer'),['class'=>'form-label']) }}
                                 <span class="validate-err">{{ $errors->first('officer_is_approved') }}</span>
                                 <div class="form-icon-user">
                                    {{ Form::checkbox('officer_is_approved',  
                                    1, $data->officer_is_approved,
                                    ['id' => 'officer_is_approved']) }}
                                 </div>
                                 <span class="validate-err" id="err_officer_is_approved"></span>
                              </div>
							  @endif
                        </div> 
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="row pt10">
      <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample">
         <div class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingone">
                  <button class="accordion-button collapsed btn-primary" type="button">
                     <h6 class="sub-title accordiantitle">Laboratory Result</h6>
                  </button>
               </h6>
               <div id="flush-collapseone" class="accordion-collapse collapse show">
                  <div class="basicinfodiv">
                     <div class="row">
                        <div class="col-md-12">
                           <table>
                              <thead>
                                 <tr>
                                    <th class="thead_class">TEST</th>
                                    <th class="thead_class">SPECIMEN</th>
                                    <th class="thead_class">BRAND</th>
                                    <th class="thead_class">LOT #</th>
                                    <th class="thead_class">EXPIRY</th>
                                    <th class="thead_class">RESULT</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    @foreach($serologyFields as $field)
                                    <td class="tbody_calss">{{$field->ho_service_name}}</td>
                                    <td class="tbody_calss">
                                       {{ Form::text('field['.$field->id.'][ser_specimen]',
                                       ($data->id)? $data->dataField($field->id,'ser_specimen') : '', 
                                       array(
                                       'id'=>'ser_specimen',
                                       'class' => 'form-control',
                                       'style'=>'border-radius:10px;',
                                       ($data->id && $data->checkAvail($field->id) && !empty($service) && $field->id === (int)$service ) || (!empty($service) && $field->id === (int)$service) || (empty($service) && $data->id && $data->checkAvail($field->id))? '' : 'readonly',
                                       ($data->id && $data->ser_is_posted === 1)?'readonly':'',
                                       )
                                       ) }}
                                       {{ Form::hidden('field['.$field->id.'][id]',
                                       ($data->id)? $data->dataField($field->id,'id') : '',
                                       ) }}
                                       {{ Form::hidden('field['.$field->id.'][disabled]',
                                       ($data->id && $data->checkAvail($field->id) && !empty($service) && $field->id === (int)$service ) || (!empty($service) && $field->id === (int)$service) || (empty($service) && $data->id && $data->checkAvail($field->id))? 'true' : 'false',
                                       ) }}
                                    </td>
                                    <td class="tbody_calss">
                                       {{ Form::text('field['.$field->id.'][ser_brand]',
                                       ($data->id)? $data->dataField($field->id,'ser_brand') : '',
                                       array(
                                       'id'=>'ser_brand',
                                       'class' => 'form-control',
                                       ($data->id && $data->checkAvail($field->id) && !empty($service) && $field->id === (int)$service ) || (!empty($service) && $field->id === (int)$service) || (empty($service) && $data->id && $data->checkAvail($field->id))? '' : 'readonly',
                                       ($data->id && $data->ser_is_posted === 1)?'readonly':'',
                                       'style'=>'border-radius:10px;'
                                       )
                                       ) }}
                                    </td>
                                    <td class="tbody_calss">
                                       {{ Form::text('field['.$field->id.'][ser_lot]',
                                       ($data->id)? $data->dataField($field->id,'ser_lot') : '', 
                                       array(
                                       'id'=>'ser_lot',
                                       'class' => 'form-control',
                                       ($data->id && $data->checkAvail($field->id) && !empty($service) && $field->id === (int)$service ) || (!empty($service) && $field->id === (int)$service) || (empty($service) && $data->id && $data->checkAvail($field->id))? '' : 'readonly',
                                       ($data->id && $data->ser_is_posted === 1)?'readonly':'',
                                       'style'=>'border-radius:10px;'
                                       )
                                       ) }}
                                    </td>
                                    <td class="tbody_calss">
                                       {{ Form::date('field['.$field->id.'][ser_exp]',
                                       ($data->id)? $data->dataField($field->id,'ser_exp') : '', 
                                       array(
                                       'id'=>'ser_exp',
                                       'class' => 'form-control',
                                       ($data->id && $data->checkAvail($field->id) && !empty($service) && $field->id === (int)$service ) || (!empty($service) && $field->id === (int)$service) || (empty($service) && $data->id && $data->checkAvail($field->id))? '' : 'readonly',
                                       ($data->id && $data->ser_is_posted === 1)?'readonly':'',
                                       'style'=>'border-radius:10px;'
                                       )
                                       ) }}
                                    </td>
                                    <td class="tbody_calss">
                                       <div class="row">
                                          <div class="col-md-2">
                                             <div class="form-group">
                                                <span class="validate-err">{{ $errors->first('ser_result') }}</span>
                                                <div class="form-icon-user">
                                                   {{ Form::radio(
                                                   'field['.$field->id.'][ser_result]', 
                                                   1, 
                                                   ($data->id && (int)$data->dataField($field->id,'ser_result') === 1)? 'true':'',
                                                   array(
                                                   'class' => 'form-check-input',
                                                   ($data->id && $data->checkAvail($field->id) && !empty($service) && $field->id === (int)$service ) || (!empty($service) && $field->id === (int)$service) || (empty($service) && $data->id && $data->checkAvail($field->id))? '' : 'disabled',
                                                   ($data->id && $data->ser_is_posted === 1)?'disabled':''
                                                   )
                                                   ) }}
                                                </div>
                                                <span class="validate-err" id="err_ser_result"></span>
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                {{ Form::label('ser_result', __('Reactive'),['class'=>'form-label']) }}
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-md-2">
                                             <div class="form-group">
                                                <span class="validate-err">{{ $errors->first('ser_result') }}</span>
                                                <div class="form-icon-user">
                                                   {{ Form::radio(
                                                   'field['.$field->id.'][ser_result]', 
                                                   0, 
                                                   ($data->id && $data->dataField($field->id,'ser_result') === 0)? 'true':'',
                                                   array(
                                                   'class' => 'form-check-input',
                                                   ($data->id && $data->checkAvail($field->id) && !empty($service) && $field->id === (int)$service ) || (!empty($service) && $field->id === (int)$service) || (empty($service) && $data->id && $data->checkAvail($field->id))? '' : 'disabled',
                                                   ($data->id && $data->ser_is_posted === 1)?'disabled':''
                                                   )
                                                   ) }}
                                                </div>
                                                <span class="validate-err" id="err_ser_result"></span>
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                {{ Form::label('ser_result', __('Non-Reactive'),['class'=>'form-label']) }}
                                             </div>
                                          </div>
                                       </div>
                                    </td>
                                 </tr>
                                 @endforeach
                              </tbody>
                           </table>
                        </div>
                     </div>
                     <br><br>
                     <div class="row">
                        <div class="col-md-2">
                           <div class="form-group">
                              {{ Form::label('ser_remarks', __('Remarks'),['class'=>'form-label']) }}
                           </div>
                        </div>
                        <div class="col-md-10">
                           <div class="form-group">
                              <span class="validate-err">{{ $errors->first('ser_remarks') }}</span>
                              <div class="form-icon-user">
                                 {{ Form::text('ser_remarks',
                                 $data->ser_remarks, 
                                 array(
                                 'id'=>'ser_remarks',
                                 'class' => 'form-control',
                                 'maxlength'=>'100',
                                 ($data->id && $data->ser_is_posted === 1)?'readonly':''
                                 )
                                 ) }}
                              </div>
                              <span class="validate-err" id="err_ser_remarks"></span>
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
      <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
      @if($data->id)
      <a href="{{route('serology.print',['id'=>$data->id])}}" class="digital-sign-btn" target="_blank">
      <button class="btn btn-primary" type="button" >
      <i class="fa fa-print icon" ></i>
      {{__('Print')}}
      </button>
      </a>
      <button class="btn btn-primary" type="submit" value="submit" {{($data->id && $data->ser_is_posted === 1)?'disabled':''}}>
      {{__('Submit')}}
      </button>
      @endif
      <button class="btn btn-primary" id="savechanges" type="submit2" value="save">
      <i class="fa fa-save icon" ></i>
      {{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}
      </button>
   </div>
</div>
{{Form::close()}}
<script src="{{ asset('js/partials/select-ajax.js?v='.filemtime(getcwd().'/js/partials/select-ajax.js').'') }}"></script>
<script src="{{ asset('/js/HealthandSafety/LabForms.js?v='.filemtime(getcwd().'/js/HealthandSafety/LabForms.js').'') }}"></script>

<script type="text/javascript">
   $(document).ready(function () {
      var shouldSubmitForm = false;
      $('#savechanges').click(function (e) {
            var form = $('#Pregnancytest');
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
	
      
   	var text_loader = "Loading...";
	var now = new Date();
   now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
   document.getElementById('ser_date').value = now.toISOString().slice(0,16);
   
   
   	$('#med_tech').change(function (e) { 
   		$('#med_tech_position').val(text_loader);
   		$.ajax({
   			type: "get",
   			url: "serology/designation/"+$(this).val(),
   			success: function (response) {
   				if(response.status == 200){
   					$('#med_tech_position').val(response.data.description);
   				}
   			},error(error){
   				$('#med_tech_position').val('');
   			}
   		});
   	});
   	$('#health_officer').change(function (e) { 
   		$('#health_officer_position').val(text_loader);
   		$.ajax({
   			type: "get",
   			url: "serology/designation/"+$(this).val(),
   			success: function (response) {
   				if(response.status == 200){
   					$('#health_officer_position').val(response.data.description);
   				}
   			},error(error){
   				$('#health_officer_position').val('');
   			}
   		});
   	});
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
   	   url : DIR+'serology-uploadDocument',
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
   			   url :DIR+'serology-deleteAttachment', // json datasource
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

<script type="text/javascript">
   $(document).ready(function () {
   FormAjax()
   
   // selectNormal('.select3');
    if($("#cit_id option:selected").val() > 0 ){
         var val = $("#cit_id option:selected").val();
         getcitizens(val);
    }
    $('#cit_id').on('change', function() {
       var id =$(this).val();
       getcitizens(id);
    });
   
   function  getcitizens(aglcode){
    var id =aglcode;
      $.ajax({
           url :DIR+'serology/getCitizensName', // json datasource
           type: "POST", 
           data: {
                   "id": id, "_token": $("#_csrf_token").val(),
               },
           success: function(html){
     $("#hema_age").val('');
     $("#sex").val('');
   //    $("#lab_req_id").val('');
   //    $("#lab_req_id").val(html.lab_req_no);
     if(html.cit_gender == 0){
   $("#sex").val('Male');
     }else{
   $("#sex").val('Female');   
     }
   $("#ser_age").val(html.cit_age);
           }
       })
   } 
   
   });
</script>
   