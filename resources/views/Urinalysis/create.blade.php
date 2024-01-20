{{ Form::open(array('url' => 'urinalysis','class'=>'formDtls','id'=>'Urinalysis')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('lab_req_id',$data->lab_req_id, array('id' => 'lab_req_id')) }}
{{ Form::hidden('urin_lab_no',$data->urin_lab_no, array('id' => 'lab_req_id')) }}
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
                              {{ Form::label('urin_age', __('Age'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('urin_age') }}</span>
                              <div class="form-icon-user">
                                 {{ 
                                 Form::text('urin_age', 
                                 $data->patient->age_human, 
                                 array(
                                 'id'=>'urin_age',
                                 'class' => 'form-control',
                                 'required'=>'required',
                                 'readonly')
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_urin_age"></span>
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
                              {{ Form::label('urin_date', __('Date'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('urin_date') }}</span>
                              <div class="form-icon-user">
                                 <!--{{ Form::date('urin_date',date('Y-m-d'), array('id'=>'urin_date','class' => 'form-control','required'=>'required')) }} -->
                                 <input id="urin_date"  name="urin_date"  value="{{$data->urin_date}}" type="datetime-local" class="form-control" {{($data->id && $data->urin_is_posted === 1)?'disabled':''}} required/>
                              </div>
                              <span class="validate-err" id="err_pt_date"></span>
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
                              {{ Form::label('urin_or_num', __('O.R. Number'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('urin_or_num') }}</span>
                              <div class="form-icon-user">
                                 {{ 
                                 Form::text('urin_or_num', 
                                 $data->urin_or_num, 
                                 array(
                                 'id'=>'urin_or_num',
                                 'class' => 'form-control',
                                 'required'=>'required',
                                 'readonly')
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_urin_or_num"></span>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              {{ Form::label('urin_lab_num', __('Lab. No.'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('urin_lab_num') }}</span>
                              <div class="form-icon-user">
                                 {{ Form::text('urin_lab_num',$data->urin_lab_num, array('id'=>'urin_lab_num','class' => 'form-control','readonly','maxlength'=>'20')) }}
                              </div>
                              <span class="validate-err" id="err_pt_lab_num"></span>
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
							  ($data->id > 0 && isset($data->med_tech))?$data->med_tech: $last_user_data->med_tech_id,
                              array(
                              'class' =>'form-control ajax-select',
                              'id'=>'med_tech',
                              'data-url' => 'citizens/selectEmployee',
                              'data-contain'=>'contain_med_tech',
                              ($data->id && $data->urin_is_posted === 1)?'disabled':''
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
                              ($data->id && $data->urin_is_posted === 1)?'disabled':''
                              )
                              ) 
                              }}
                           </div>
                           <span class="validate-err" id="err_health_officer"></span>
                           </div>
                        </div>
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
                              ($data->id && $data->urin_is_posted === 1)?'disabled':''
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
                              ($data->id && $data->urin_is_posted === 1)?'disabled':''
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
                        <div class="col-md-6">
                           <h4>MACROSCOPIC</h4>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_color', __('Color'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_color') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_color',
                                       $data->urin_color, 
                                       array(
                                       'id'=>'urin_color',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_color"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_appearance', __('Appearance'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_appearance') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_appearance',
                                       $data->urin_appearance, 
                                       array(
                                       'id'=>'urin_appearance',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_appearance"></span>
                                 </div>
                              </div>
                           </div>
                           <h4>CHEMICAL</h4>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_leukocytes', __('Leukocytes'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_leukocytes') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_leukocytes',
                                       $data->urin_leukocytes, 
                                       array(
                                       'id'=>'urin_leukocytes',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_leukocytes"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_nitrite', __('Nitrite'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_nitrite') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_nitrite',
                                       $data->urin_nitrite, 
                                       array(
                                       'id'=>'urin_nitrite',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_nitrite"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_urobilinogen', __('Urobilinogen'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_urobilinogen') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_urobilinogen',
                                       $data->urin_urobilinogen, 
                                       array(
                                       'id'=>'urin_urobilinogen',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_urobilinogen"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_protein', __('Protein'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_protein') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_protein',
                                       $data->urin_protein, 
                                       array(
                                       'id'=>'urin_protein',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_protein"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_reaction', __('Reaction'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_reaction') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_reaction',
                                       $data->urin_reaction, 
                                       array(
                                       'id'=>'urin_reaction',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_reaction"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_blood', __('Blood'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_blood') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_blood',
                                       $data->urin_blood, 
                                       array(
                                       'id'=>'urin_blood',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_blood"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_sg', __('Specific Gravity'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_sg') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_sg',
                                       $data->urin_sg, 
                                       array(
                                       'id'=>'urin_sg',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_sg"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_ketones', __('Ketones'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_ketones') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_ketones',
                                       $data->urin_ketones, 
                                       array(
                                       'id'=>'urin_ketones',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_ketones"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_bilirubin', __('Bilirubin'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_bilirubin') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_bilirubin',
                                       $data->urin_bilirubin, 
                                       array(
                                       'id'=>'urin_bilirubin',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_bilirubin"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_glucose', __('Glucose'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_glucose') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_glucose',
                                       $data->urin_glucose, 
                                       array(
                                       'id'=>'urin_glucose',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_glucose"></span>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <h4>MACROSCOPIC</h4>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_rbc', __('Red Blood Cells'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_rbc') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_rbc',
                                       $data->urin_rbc, 
                                       array(
                                       'id'=>'urin_rbc',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_color"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_pc', __('Pus Cells'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_pc') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_pc',
                                       $data->urin_pc, 
                                       array(
                                       'id'=>'urin_pc',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_pc"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_bac', __('Bacteria'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_bac') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_bac',
                                       $data->urin_bac, 
                                       array(
                                       'id'=>'urin_bac',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_bac"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_yc', __('Yeast Cells'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_yc') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_yc',
                                       $data->urin_yc, 
                                       array(
                                       'id'=>'urin_yc',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_yc"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_ec', __('Epithelial Cells'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_ec') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_ec',
                                       $data->urin_ec, 
                                       array(
                                       'id'=>'urin_ec',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_ec"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_mt', __('Mucus Threads'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_mt') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_mt',
                                       $data->urin_mt, 
                                       array(
                                       'id'=>'urin_mt',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_mt"></span>
                                 </div>
                              </div>
                           </div>
                           <h4>CRYSTAL</h4>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_aup', __('Amorphous Urates/Phosphate'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_aup') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_aup',
                                       $data->urin_aup, 
                                       array(
                                       'id'=>'urin_aup',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_aup"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_others1', __('OTHERS'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_others1') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_others1',
                                       $data->urin_others1, 
                                       array(
                                       'id'=>'urin_others1',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_others1"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_cast', __('Cast/s'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_cast') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_cast',
                                       $data->urin_cast, 
                                       array(
                                       'id'=>'urin_cast',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_cast"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_others2', __('Others'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_cast') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_others2',
                                       $data->urin_others2, 
                                       array(
                                       'id'=>'urin_others2',
                                       'class' => 'form-control',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':'',
                                       'maxlength'=>'30'
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_others2"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    {{ Form::label('urin_remarks', __('Remarks'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('urin_remarks') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('urin_remarks',
                                       $data->urin_remarks, 
                                       array(
                                       'id'=>'urin_remarks',
                                       'class' => 'form-control',
                                       'maxlength'=>'100',
                                       ($data->id && $data->urin_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_urin_remarks"></span>
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
      <a href="{{route('urinalysis.print',['id'=>$data->id])}}" target="_blank">
      <button class="btn btn-primary" type="button" >
      <i class="fa fa-print icon" ></i>
      {{__('Print')}}
      </button>
      </a>
      <button class="btn btn-primary" type="submit" value="submit" {{($data->id && $data->urin_is_posted === 1)?'disabled':''}}>
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
<script src="{{ asset('js/partials/citizen-ajax.js?v='.filemtime(getcwd().'/js/partials/citizen-ajax.js').'') }}"></script>
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
	$('#med_tech').change(function (e) { 
		$('#med_tech_position').val(text_loader);
		$.ajax({
			type: "get",
			url: "urinalysis/designation/"+$(this).val(),
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
			url: "urinalysis/designation/"+$(this).val(),
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
	   url : DIR+'urinalysis-uploadDocument',
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
			   url :DIR+'urinalysis-deleteAttachment', // json datasource
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
   selectNormal('.select3');
   
   var now = new Date();
   now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
   document.getElementById('urin_date').value = now.toISOString().slice(0,16);
   
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
           url :DIR+'urinalysis/getCitizensName', // json datasource
           type: "POST", 
           data: {
                   "id": id, "_token": $("#_csrf_token").val(),
               },
           success: function(html){
     $("#urin_age").val('');
     $("#sex").val('');
   //    $("#lab_req_id").val('');
   //    $("#lab_req_id").val(html.lab_req_no);
              $("#urin_age").val(html.cit_age);
     if(html.cit_gender == 0){
   $("#sex").val('Male');
     }else{
   $("#sex").val('Female');   
     }
     
           }
       })
   } 
   });
</script>