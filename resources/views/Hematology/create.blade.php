{{ Form::open(array('url' => 'hematology','class'=>'formDtls')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('lab_req_id',$data->lab_req_id, array('id' => 'lab_req_id')) }}
{{ Form::hidden('hema_lab_no',$data->hema_lab_no, array('id' => 'hema_lab_no')) }}
{{ Form::hidden('hema_lab_year',$data->hema_lab_year, array('id' =>'hema_lab_year')) }}
{{ Form::hidden('cit_id', $data->cit_id, array('id'=>'cit_id' ,'class' =>'form-control','required'=>'required','readonly')) }}
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
                              <div class="form-icon-user" id="">
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
                              </div>
                              <span class="validate-err" id="err_cit_id"></span>
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="form-group">
                              {{ Form::label('hema_age', __('Age'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('hema_age') }}</span>
                              <div class="form-icon-user">
                                 {{ 
                                 Form::text('hema_age', 
                                 $data->patient->age_human, 
                                 array(
                                 'id'=>'hema_age',
                                 'class' => 'form-control',
                                 'required'=>'required',
                                 'readonly')
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_hema_age"></span>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              {{ Form::label('hema_or_num', __('O.R. Number'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('hema_or_num') }}</span>
                              <div class="form-icon-user">
                                 {{ 
                                 Form::text('hema_or_num', 
                                 $data->hema_or_num, 
                                 array(
                                 'id'=>'hema_or_num',
                                 'class' => 'form-control',
                                 'required'=>'required',
                                 'readonly')
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_hema_or_num"></span>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              {{ Form::label('hema_date', __('Date'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('hema_date') }}</span>
                              <div class="form-icon-user">
                                 <input id="hema_date" name="hema_date" value="{{$data->hema_date}}" type="datetime-local" class="form-control" {{($data->id && $data->hema_is_posted === 1)?'disabled':''}} required/>
                              </div>
                              <span class="validate-err" id="err_hema_date"></span>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              {{ Form::label('chc_id', __('Category'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                              <span class="validate-err">{{ $errors->first('chc_id') }}</span>
                              <div class="form-icon-user" id="chc_id_contain">
                                 {{ 
                                 Form::select('chc_id',
                                 $getCategories,
                                 $data->chc_id, 
                                 array(
                                 'class' =>'form-control select3',
                                 'data-contain'=>'chc_id_contain',
                                 'id'=>'chc_id',
                                 ($data->id && $data->hema_is_posted === 1)?'disabled':'')
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_chc_id"></span>
                           </div>
                        </div>
                        <div class="col-md-2">
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
                              <span class="validate-err" id="err_sex"></span>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              {{ Form::label('lab_control_no', __('Laboratory Control No.'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('lab_control_no') }}</span>
                              <div class="form-icon-user">
                                 {{ Form::text('lab_control_no',$data->lab_control_no, array('id'=>'lab_control_no','class' => 'form-control','readonly')) }}
                              </div>
                              <span class="validate-err" id="err_lab_control_no"></span>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              {{ Form::label('hema_lab_num', __('Lab. No.'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('hema_lab_num') }}</span>
                              <div class="form-icon-user">
                                 {{ Form::text('hema_lab_num',$data->hema_lab_num, array('id'=>'hema_lab_num','class' => 'form-control','readonly')) }}
                              </div>
                              <span class="validate-err" id="err_hema_lab_num"></span>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample">
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
                              <div class="form-icon-user" id="med_tech_contain">
                                 {{ 
                                 Form::select('med_tech',
                                 $getphysician,
                                 ($data->id > 0 && isset($data->med_tech))?$data->med_tech:$last_user_data->med_tech_id,
                                 array(
                                 'class' =>'form-control ajax-select',
                                 'id'=>'med_tech',
                                 'data-url' => 'citizens/selectEmployee',
                                 'data-contain'=>'med_tech_contain',
                                 ($data->id && $data->hema_is_posted === 1)?'disabled':''
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
                                 ($data->id && $data->hema_is_posted === 1)?'disabled':''
                                 )
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_health_officer"></span>
                           </div>
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
                           ($data->id && $data->hema_is_posted === 1)?'disabled':''
                           )
                           ) 
                           }}
                        </div>
                        <span class="validate-err" id="err_hp_code"></span>
                        </div>
                        </div> -->
                     <div class="row">
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
                                 ($data->id && $data->hema_is_posted === 1)?'disabled':''
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
                                 ($data->id && $data->hema_is_posted === 1)?'disabled':''
                                 )
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_health_officer_position"></span>
                           </div>
                        </div>
                        <div class="row">
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
                              <div class="form-group" id="officer_is_approved" style="margin-left:15px;">
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
   </div>
   <div class="row pt10">
      <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">
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
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <h6>PARA</h6>
                                    {{ Form::label('hema_wbc', __('WBC'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <h6>Result</h6>
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_wbc') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_wbc',
                                       $data->hema_wbc, 
                                       array(
                                       'id'=>'hema_wbc',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_wbc',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_pt_specimen"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <h6>Ref. Ranges</h6>
                                 <span id="hema_wb_range"></span>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    {{ Form::label('hema_lymph_num', __('Lymph #'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_lymph_num') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_lymph_num',
                                       $data->hema_lymph_num, 
                                       array(
                                       'id'=>'hema_lymph_num',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_lymph_num',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_lymph_num"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <span id="hema_lymph_num_range"></span>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    {{ Form::label('hema_gran_num', __('Gran #'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_gran_num') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_gran_num',
                                       $data->hema_gran_num, 
                                       array(
                                       'id'=>'hema_gran_num',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_gran_num',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_gran_num"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <span id="hema_gran_num_range"></span>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    {{ Form::label('hema_lymph_pct', __('Lymph %'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_lymph_pct') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_lymph_pct',
                                       $data->hema_lymph_pct, 
                                       array(
                                       'id'=>'hema_lymph_pct',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_lymph_pct',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_lymph_pct"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <span id="hema_lymph_pct_range"></span>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    {{ Form::label('hema_mid_pct', __('Mid %'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_mid_pct') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_mid_pct',
                                       $data->hema_mid_pct, 
                                       array(
                                       'id'=>'hema_mid_pct',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_mid_pct',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_mid_pct"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <span id="hema_mid_pct_range"></span>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    {{ Form::label('hema_gran_pct', __('Gran %'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_gran_pct') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_gran_pct',
                                       $data->hema_gran_pct, 
                                       array(
                                       'id'=>'hema_gran_pct',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_gran_pct',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_gran_pct"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <span id="hema_gran_pct_range"></span>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    {{ Form::label('hema_hgb', __('Hgb'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_hgb') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_hgb',
                                       $data->hema_hgb, 
                                       array(
                                       'id'=>'hema_hgb',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_hgb',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_hgb"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <span id="hema_hgb_range"></span>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    {{ Form::label('hema_rbc', __('RBC'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_rbc') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_rbc',
                                       $data->hema_rbc, 
                                       array(
                                       'id'=>'hema_rbc',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_rbc',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_rbc"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <span id="hema_rbc_range"></span>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    {{ Form::label('hema_hct', __('Hct'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_hct') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_hct',
                                       $data->hema_hct, 
                                       array(
                                       'id'=>'hema_hct',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_hct',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_hct"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <span id="hema_hct_range"></span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <h6>PARA</h6>
                                    {{ Form::label('hema_mcv', __('MCV'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <h6>Result</h6>
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_mcv') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_mcv',
                                       $data->hema_mcv, 
                                       array(
                                       'id'=>'hema_mcv',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_mcv',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_mcv"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <h6>Ref. Ranges</h6>
                                 <span id="hema_mcv_range"></span>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    {{ Form::label('hema_mch', __('MCH'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_mch') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_mch',
                                       $data->hema_mch, 
                                       array(
                                       'id'=>'hema_mch',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_mch',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':'')) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_mch"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <span id="hema_mch_range"></span>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    {{ Form::label('hema_mchc', __('MCHC'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_mchc') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_mchc',
                                       $data->hema_mchc, 
                                       array(
                                       'id'=>'hema_mchc',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_mchc',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_mchc"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <span id="hema_mchc_range"></span>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    {{ Form::label('hema_rdw_cv', __('RDW-CV'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_rdw_cv') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_rdw_cv',
                                       $data->hema_rdw_cv, 
                                       array(
                                       'id'=>'hema_rdw_cv',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_rdw_cv',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_rdw_cv"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <span id="hema_rdw_cv_range"></span>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    {{ Form::label('hema_rdw_sd', __('RDW-SD'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_rdw_sd') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_rdw_sd',
                                       $data->hema_rdw_sd, 
                                       array(
                                       'id'=>'hema_rdw_sd',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_rdw_sd',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_rdw_sd"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <span id="hema_rdw_sd_range"></span>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    {{ Form::label('hema_plt', __('PLT'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_plt') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_plt',
                                       $data->hema_plt, 
                                       array(
                                       'id'=>'hema_plt',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_plt',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_plt"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <span id="hema_plt_range"></span>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    {{ Form::label('hema_mpv', __('MPV'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_mpv') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_mpv',
                                       $data->hema_mpv, 
                                       array(
                                       'id'=>'hema_mpv',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_mpv',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_mpv"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <span id="hema_mpv_range"></span>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    {{ Form::label('hema_pdw', __('PDW'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_pdw') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_pdw',
                                       $data->hema_pdw, 
                                       array(
                                       'id'=>'hema_pdw',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_pdw',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_pdw"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <span id="hema_pdw_range"></span>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    {{ Form::label('hema_pct', __('PCT'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_pct') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_pct',
                                       $data->hema_pct, 
                                       array(
                                       'id'=>'hema_pct',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_pct',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_pct"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <span id="hema_pct_range"></span>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    {{ Form::label('hema_blood_type', __('BLOOD TYPE'),['class'=>'form-label']) }}
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <span class="validate-err">{{ $errors->first('hema_blood_type') }}</span>
                                    <div class="form-icon-user">
                                       {{ Form::text('hema_blood_type',
                                       $data->hema_blood_type, 
                                       array(
                                       'id'=>'hema_blood_type',
                                       'class' => 'form-control',
                                       'maxlength'=>'11',
                                       ($service && in_array('hema_blood_type',$service))?'':'readonly',
                                       ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                       )
                                       ) }}
                                    </div>
                                    <span class="validate-err" id="err_hema_blood_type"></span>
                                 </div>
                              </div>
                              <div class="col-md-5">
                                 <span id="hema_blood_type_range"></span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-2">
                           <div class="form-group">
                              {{ Form::label('hema_remarks', __('Remarks'),['class'=>'form-label']) }}
                           </div>
                        </div>
                        <div class="col-md-10">
                           <div class="form-group">
                              <span class="validate-err">{{ $errors->first('hema_remarks') }}</span>
                              <div class="form-icon-user">
                                 {{ Form::text('hema_remarks',
                                 $data->hema_remarks, 
                                 array(
                                 'id'=>'hema_remarks',
                                 'class' => 'form-control',
                                 'maxlength'=>'100',
                                 ($data->id && $data->hema_is_posted === 1)?'readonly':''
                                 )
                                 ) }}
                              </div>
                              <span class="validate-err" id="err_hema_remarks"></span>
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
      <button class="btn btn-light" data-bs-dismiss="modal" type="button">{{__('Close')}}</button>
      @if($data->id)
      <a href="{{route('hematology.print',['id'=>$data->id])}}" class="digital-sign-btn" target="_blank">
      <button class="btn btn-primary" type="button">
      <i class="fa fa-print icon"></i>
      {{__('Print')}}
      </button>
      </a>
      <button class="btn btn-primary" type="submit" value="submit" {{($data->id && $data->hema_is_posted === 1)?'disabled':''}}>
      {{__('Submit')}}
      </button>
      @endif
      <button class="btn btn-primary" type="submit2" value="save" >
      <i class="fa fa-save icon" ></i>
      {{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}
      </button>
   </div>
</div>
{{Form::close()}}
<script src="{{ asset('js/SocialWelfare/select-ajax.js?v='.filemtime(getcwd().'/js/SocialWelfare/select-ajax.js').'') }}"></script>
<script src="{{ asset('/js/HealthandSafety/LabForms.js?v='.filemtime(getcwd().'/js/HealthandSafety/LabForms.js').'') }}"></script>
<script type="text/javascript">
   $(document).ready(function () {
      var shouldSubmitForm = false;
      //FormAjax()
      selectNormal('.select3');
   
      var now = new Date();
      now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
      document.getElementById('hema_date').value = now.toISOString().slice(0,16);
   
      $("#commonModal").find('.body').css({overflow: 'unset'})
      
   	var text_loader = "Loading...";
   	$('#med_tech').change(function (e) { 
   		$('#med_tech_position').val(text_loader);
   		$.ajax({
   			type: "get",
   			url: "hematology/designation/"+$(this).val(),
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
   			url: "hematology/designation/"+$(this).val(),
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
   	   url : DIR+'hematology-uploadDocument',
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
   			   url :DIR+'hematology-deleteAttachment', // json datasource
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
   
   if($("#chc_id option:selected").val() > 0 ){
         var val = $("#chc_id option:selected").val();
         getRangelist(val);
    }
    $('#chc_id').on('change', function() {
       var id =$(this).val();
       getRangelist(id);
    });
   
   function  getcitizens(aglcode){
    var id =aglcode;
      $.ajax({
           url :DIR+'hematology/getCitizensName', // json datasource
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
   $("#hema_age").val(html.cit_age);
           }
       })
   } 
   //rangelist
   function  getRangelist(aglcode){
    var id =aglcode;
      $.ajax({
           url :DIR+'hematology/getrangelists', // json datasource
           type: "POST", 
           data: {
                   "id": id, "_token": $("#_csrf_token").val(),
               },
           success: function(html){
   
     $("#hema_wb_range").text('');
     $("#hema_lymph_num_range").text('');
     $("#hema_mid_num_range").text('');
     $("#hema_gran_num_range").text('');
     $("#hema_lymph_pct_range").text('');
     $("#hema_mid_pct_range").text('');
     $("#hema_gran_pct_range").text('');
     $("#hema_hgb_range").text('');
     $("#hema_rbc_range").text('');
     $("#hema_hct_range").text('');
     $("#hema_mcv_range").text('');
     $("#hema_mch_range").text('');
     $("#hema_mchc_range").text('');
     $("#hema_rdw_cv_range").text('');
     $("#hema_rdw_sd_range").text('');
     $("#hema_plt_range").text('');
     $("#hema_mpv_range").text('');
     $("#hema_pdw_range").text('');
     $("#hema_pct_range").text('');
     $("#hema_blood_type_range").text('');
   
     $("#hema_wb_range").text(html[0].chr_range);
     $("#hema_lymph_num_range").text(html[1].chr_range);
     $("#hema_mid_num_range").text(html[2].chr_range);
     $("#hema_gran_num_range").text(html[3].chr_range);
     $("#hema_lymph_pct_range").text(html[4].chr_range);
     $("#hema_mid_pct_range").text(html[5].chr_range);
     $("#hema_gran_pct_range").text(html[6].chr_range);
     $("#hema_hgb_range").text(html[7].chr_range);
     $("#hema_rbc_range").text(html[8].chr_range);
     $("#hema_hct_range").text(html[9].chr_range);
     $("#hema_mcv_range").text(html[10].chr_range);
     $("#hema_mch_range").text(html[11].chr_range);
     $("#hema_mchc_range").text(html[12].chr_range);
     $("#hema_rdw_cv_range").text(html[13].chr_range);
     $("#hema_rdw_sd_range").text(html[14].chr_range);
     $("#hema_plt_range").text(html[15].chr_range);
     $("#hema_mpv_range").text(html[16].chr_range);
     $("#hema_pdw_range").text(html[17].chr_range);
     $("#hema_pct_range").text(html[18].chr_range);
     $("#hema_blood_type_range").text(html[19].chr_range);
     
           }
       })
   }
   });
</script>