{{ Form::open(array('url' => 'hr-leaves','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('hr_employeesid',$data->hr_employeesid, array('id' => 'hr_employeesid')) }}
    {{ Form::hidden('submit_type',"", array('id' => 'submit_type')) }}
<style>
      .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: #fff;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .field-requirement-details-status label{padding-top:10px;}
    .permitclick{  cursor:pointer;color:skyblue; }
    .orpayment > .row{ padding:10px; }
    .btn{padding: 0.575rem 0.5rem;}
    .accordion-button::after {
    background-image: url();
  }
    select[readonly].select3-hidden-accessible + .select3-container {
        pointer-events: none;
        touch-action: none;
    }

    select[readonly].select3-hidden-accessible + .select3-container .select3-selection {
        background: #eee;
        box-shadow: none;
    }

    select[readonly].select3-hidden-accessible + .select3-container .select3-selection__arrow, select[readonly].select3-hidden-accessible + .select3-container .select3-selection__clear {
        display: none;
    }
 </style>
<div class="modal-body">
                    <div class="row">
                       <span class="validate-err" id="err_hr_employeesid"></span>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('appno', __('Application No'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('appno') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('appno',
                                        $data->applicationno, 
                                        array(
                                            'class' => 'form-control disabled-field',
                                            (($data->hrla_status) >= 1 ) ? 'readonly':'',
                                            'id'=>'appno'
                                            )) }}
                                </div>
                                <span class="validate-err" id="err_hrcos_start_date"></span>
                            </div>
                        </div>
                        @if($data->id > 0)
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('status', __('Status'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('status') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('cos_status',
                                        $status, 
                                        array(
                                            'class' => 'form-control ',
                                            'id'=>'cos_status',
                                            'readonly'=>'readonly'
                                            )) }}
                                </div>
                                <span class="validate-err" id="err_hml_status"></span>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('date', __('Applied Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('date') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::date('date',
                                        $date, 
                                        array(
                                            'class' => 'form-control',
                                            'id'=>'date',
                                            'required'=>'required',
                                            'readonly'
                                            )) }}
                                </div>
                                <span class="validate-err" id="date"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hrl_start_date', __('Start Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrl_start_date') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::date('hrl_start_date',
                                        $data->hrl_start_date, 
                                        array(
                                            'class' => 'form-control leave-date',
                                            'id'=>'hrl_start_date',
                                            (($data->hrla_status) >= 1 ) ? 'readonly':'',
                                            'required'=>'required'
                                            )) }}
                                </div>
                                <span class="validate-err" id="err_hrl_start_date"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hrl_end_date', __('End Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrl_end_date') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::date('hrl_end_date',
                                        $data->hrl_end_date, 
                                        array(
                                            'class' => 'form-control leave-date',
                                            'id'=>'hrl_end_date',
                                            (($data->hrla_status) >= 1 ) ? 'readonly':'',
                                            'required'=>'required'
                                            )) }}
                                </div>
                                <span class="validate-err" id="err_hrl_end_date"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hrlt_id', __('Leave Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrds_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hrlt_id',
                                        $arrLeavetypes,
                                        $data->hrlt_id, 
                                        array(
                                            'class' => 'form-control select3',
                                            'id'=>'hrlt_id',
                                            (($data->hrla_status) >= 1 ) ? 'readonly':'',
                                            'required'=>'required'
                                            )) }}
                                </div>
                                <span class="validate-err" id="err_hrlt_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('remainingdays', __('Remainning Leave Days'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('remainingdays') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('remainingdays',
                                        $data->remainingdays, 
                                        array(
                                            'class' => 'form-control ',
                                            'id'=>'remainingdays',
                                            'readonly',
                                            'required'=>'required'
                                            )) }}
                                </div>
                                <span class="validate-err" id="err_remainingdays"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hrla_id', __('Application'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('reason') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hrla_id',
                                        $arrApplicationtypes,
                                        $data->hrla_id, 
                                        array(
                                            'class' => 'form-control select3',
                                            (($data->hrla_status) >= 1 ) ? 'readonly':'',
                                            'id'=>'hrla_id',
                                            'required'=>'required'
                                            )) }}
                                </div>
                                <span class="validate-err" id="err_reason"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hrl_incase_vl_special_privilege', __('In Case of Vacation/Special Privilege Leave'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('hrla_reason') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::radio('hrl_incase_vl_special_privilege',
                                        0, 
                                        false, 
                                        array(
                                            'class' => 'form-check-input special-field spl-grp',
                                            'id'=>'hrl_incase_vl_special_privilege_ph',
                                            (($data->hrla_status) >= 1 ) ? 'disabled':'',
                                            ($data->id) ? $data->checker('VL', 'hrl_incase_vl_special_privilege', 0, 'checked') : ''
                                            )) }}
                                    {{ Form::label('hrl_incase_vl_special_privilege_ph', __('Within the Philippines'),['class'=>'fs-6 fw-bold mx-2']) }}
                                    <div class="form-inline">
                                        {{ Form::radio('hrl_incase_vl_special_privilege',
                                            1, 
                                            false, 
                                            array(
                                                'class' => 'form-check-input special-field spl-grp',
                                                'id'=>'hrl_incase_vl_special_privilege_abroad',
                                                'data-textfield' => 'hrl_incase_vl_sp_speficy_remarks',
                                                (($data->hrla_status) >= 1 ) ? 'disabled':'',
                                                ($data->id) ? $data->checker('VL', 'hrl_incase_vl_special_privilege', 1, 'checked') : ''
                                                )) }}
                                        {{ Form::label('hrl_incase_vl_special_privilege_abroad', __('Abroad (Specify)'),['class'=>'fs-6 fw-bold mx-2']) }}
                                        @if(($data->id) && $data->checker('VL', 'hrl_incase_vl_special_privilege', 1))
                                            {{ Form::text('hrl_incase_vl_sp_speficy_remarks',
                                            $data->hrl_incase_vl_sp_speficy_remarks, 
                                            array(
                                                'class' => 'form-control new-field',
                                                'style' => 'width:200px',
                                                'id'=>'hrl_incase_vl_sp_speficy_remarks',
                                                (($data->hrla_status) >= 1 ) ? 'disabled':'',
                                                )) }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hrl_incase_sl', __('In Case of Sick Leave'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('hrla_reason') }}</span>
                                <div class="form-icon-user">
                                    
                                    <div class="form-inline">
                                        {{ Form::radio('hrl_incase_sl',
                                            0, 
                                            false, 
                                            array(
                                                'class' => 'form-check-input special-field sick-leave-grp',
                                                'id'=>'hrl_incase_sl_in',
                                                'data-textfield' => 'hrl_incase_sl_specify_remarks',
                                                (($data->hrla_status) >= 1 ) ? 'disabled':'',
                                                ($data->id) ? $data->checker('SL', 'hrl_incase_sl', 0, 'checked') : ''
                                                )) }}
                                        {{ Form::label('hrl_incase_sl_in', __('In Hospital (Specify Illness)'),['class'=>'fs-6 mx-2']) }}
                                        
                                        @if(($data->id) && $data->checker('SL', 'hrl_incase_sl', 0))
                                            {{ Form::text('hrl_incase_sl_specify_remarks',
                                            $data->hrl_incase_sl_specify_remarks, 
                                            array(
                                                'class' => 'form-control new-field',
                                                'style' => 'width:200px',
                                                'id'=>'hrl_incase_sl_specify_remarks',
                                                (($data->hrla_status) >= 1 ) ? 'disabled':'',
                                                )) }}
                                        @endif
                                    </div>
                                    <div class="form-inline">
                                        {{ Form::radio('hrl_incase_sl',
                                            1, 
                                            false, 
                                            array(
                                                'class' => 'form-check-input special-field sick-leave-grp',
                                                'id'=>'hrl_incase_sl_out',
                                                'data-textfield' => 'hrl_incase_sl_specify_remarks',
                                                (($data->hrla_status) >= 1 ) ? 'disabled':'',
                                                ($data->id) ? $data->checker('SL', 'hrl_incase_sl', 1, 'checked') : ''
                                                )) }}
                                        {{ Form::label('hrl_incase_sl_out', __('Out Patient (Specify Illness)'),['class'=>'fs-6 fw-bold mx-2']) }}
                                        @if(($data->id) && $data->checker('SL', 'hrl_incase_sl', 1))
                                            {{ Form::text('hrl_incase_sl_specify_remarks',
                                            $data->hrl_incase_sl_specify_remarks, 
                                            array(
                                                'class' => 'form-control new-field',
                                                'style' => 'width:200px',
                                                'id'=>'hrl_incase_sl_specify_remarks',
                                                (($data->hrla_status) >= 1 ) ? 'disabled':'',
                                                )) }}
                                        @endif
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hrl_incase_special_leave_women_remarks', __('In Case of Special Leave Benefits for Women'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('hrla_reason') }}</span>
                                <div class="form-icon-user form-inline">
                                    {{ Form::label('hrl_incase_special_leave_women_remarks', __('(Specify Illness)'),['class'=>'fs-6 fw-bold mx-2']) }}
                                    {{ Form::text('hrl_incase_special_leave_women_remarks',
                                        $data->hrl_incase_special_leave_women_remarks, 
                                        array(
                                            'class' => 'form-control special-field maternity-grp',
                                            'style' => 'width:200px',
                                            'id'=>'hrl_incase_special_leave_women_remarks',
                                            (($data->hrla_status) >= 1 ) ? 'disabled':'',
                                            )) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hrl_incase_study_leave', __('In Case of Study Leave'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('hrla_reason') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::radio('hrl_incase_study_leave',
                                        0, 
                                        false, 
                                        array(
                                            'class' => 'form-check-input special-field study-grp',
                                            'id'=>'hrl_incase_study_leave_masters',
                                            (($data->hrla_status) >= 1 ) ? 'disabled':'',
                                            ($data->id) ? $data->checker('STL', 'hrl_incase_study_leave', 0, 'checked') : ''
                                            )) }}
                                    {{ Form::label('hrl_incase_study_leave_masters', __("Completation of Master's Degree"),['class'=>'fs-6 fw-bold mx-2']) }}
                                    <br>
                                    {{ Form::radio('hrl_incase_study_leave',
                                        1, 
                                        false, 
                                        array(
                                            'class' => 'form-check-input special-field study-grp',
                                            'id'=>'hrl_incase_study_leave_bar',
                                            (($data->hrla_status) >= 1 ) ? 'disabled':'',
                                            ($data->id) ? $data->checker('STL', 'hrl_incase_study_leave', 1, 'checked') : ''
                                            )) }}
                                    {{ Form::label('hrl_incase_study_leave_bar', __('BAR/Board Examination Review'),['class'=>'fs-6 fw-bold mx-2']) }}
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hrl_incase_others', __('Others'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('hrla_reason') }}</span>
                                <div class="form-icon-user">
                                    @foreach(config('constants.hrIncaseOther') as $other)
                                        {{ Form::checkbox('hrl_incase_others[]',
                                            $other['value'], 
                                            false, 
                                            array(
                                                'class' => 'form-check-input ',
                                                'id'=>'hrl_incase_others_'.$other['shortcode'],
                                                (($data->hrla_status) >= 1 ) ? 'disabled':'',
                                                in_array($other['value'],explode(',',$data->hrl_incase_others)) ? 'checked': '',
                                                )) }}
                                        {{ Form::label('hrl_incase_others_'.$other['shortcode'], __($other['name']),['class'=>'fs-6 fw-bold mx-2']) }}
                                        <br>
                                    @endforeach
                                    
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hrla_reason', __('Reason'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('hrla_reason') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrla_reason',
                                        $data->hrla_reason, 
                                        array(
                                            'class' => 'form-control ',
                                            'id'=>'hrla_reason',
                                            (($data->hrla_status) >= 1 ) ? 'readonly':'',
                                            )) }}
                                </div>
                                <span class="validate-err" id="err_hrla_reason"></span>
                            </div>
                        </div>
                        @if($data->hrla_status === 2 || $data->hrla_status === 1)
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hrla_disapproved_remarks', __('Disapprove Reason'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('hrla_disapproved_remarks') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrla_disapproved_remarks',
                                        $data->hrla_disapproved_remarks, 
                                        array(
                                            'class' => 'form-control ',
                                            'id'=>'hrla_disapproved_remarks',
                                            'readonly'=>'readonly'
                                            )) }}
                                </div>
                                <span class="validate-err" id="err_hrla_disapproved_remarks"></span>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                          <div class="row field-requirement-details-status">
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                {{Form::label('id',__('Id'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9">
                                {{Form::label('filename',__('File Name'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <span class="btn_addmore_document btn btn-primary" id="btn_addmore_document" style="color:white;"><i class="ti-plus"></i></span>
                            </div>
                        </div>
                         <span class="documentsDetails activity-details" id="documentsDetails">
                             @php $i=1; @endphp
                            @foreach($arrDocuments as $key=>$val)
                            <div class="removedocumentsdata row pt10">
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                  <div class="form-group"><div class="form-icon-user">
                                    <p class="serialnoclass" style="text-align:center;">{{$i}}</p>
                                    {{ Form::hidden('totalfiles[]','1', array('id' => 'totalfiles')) }}
                                    @if(!empty($val->id)) 
                                    {{ Form::hidden('fileid[]',$val->id, array('id' => 'fileid')) }}
                                    @endif
                                    </div>
                                  </div>
                                 </div>
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <div class="form-group">
                                        <div class="form-icon-user"><input class="form-control" name="documents[]" type="file" value="">
                                        </div>
                                   </div>
                                </div>
                                @if($i>=0)
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                         <div class="form-group">
                                            @if(!empty($val->hrl_file_name))
                                            <a class="btn" href="{{asset('uploads/')}}/{{$val->hrl_file_path}}/{{$val->hrl_file_name}}" target='_blank'><i class='ti-download'></i></a>
                                            @endif
                                         <button type="button" class="btn btn-danger btn_cancel_documents"  value="{{$val->id}}"><i class="ti-trash"></i></button>
                                       </div>
                                 </div>
                                @endif
                                @php $i++; @endphp
                            </div>
                        @endforeach
                         </span>
                       </div>  
                    </div>
                    <div class="modal-footer">
                      
                        @if($data->id ) 
                        <div class="button">
                             <button  type="button" name="submit" value="{{$data->id}}"  id="disapprove-btn" sequence="0" class="btn  btn-warning {{ ($data->hrla_status) === 1 || ($data->hrla_status) === 2 ?__('disabled -field'):__('')}}">Cancel</button>
                        </div>
                        <a href="{{route('hrleaves.print',['id'=>$data->id])}}" class="btn  btn-info" target="_blank">
                            <i class="fa fa-print icon"></i>
                            Print
                        </a>
                        @endif
                        @if($data->hrla_status < 1) 
                         <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <input type="submit" name="submit" value="{{ ($data->id)>0?__('Submit'):__('Submit')}}" class="btn btn-primary add" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                         </div>
                        @endif
                        @if($data->hrla_status ==0)
                         <div class="button" style="background: #000;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <input type="submit" name="submit" value="Save as Draft" class="btn btn-warning add" style="background: #000;padding-left: 4px;border:1px solid #000;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                        @endif
                        <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
                    </div>
        </div>    
{{Form::close()}}
<div id="hiddendocumentsHtml" class="hide">
    <div class="removedocumentsdata row pt10">
         <div class="col-lg-1 col-md-1 col-sm-1">
            <div class="form-group">
                    <div class="form-icon-user">
                        <p class="serialnoclass" style="text-align:center;"></p>
                         {{ Form::hidden('totalfiles[]','1', array('id' => 'totalfiles')) }}
                    </div>
            </div>
          </div>
         <div class="col-lg-9 col-md-9 col-sm-9">
                <div class="form-group">
                    <div class="form-icon-user"><input class="form-control" name="documents[]" type="file" value="">
                    </div>
               </div>
            </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
                     <div class="form-group">
                       <div class="form-icon-user"><button type="button" class="btn btn-danger btn_cancel_documents"><i class="ti-trash"></i></button>
                       </div>
                   </div>
             </div>
    </div>
</div>   
<script src="{{ asset('js/ajax_validation.js') }}"></script> 
<script src="{{ asset('js/HR/add_leaves.js') }}"></script>   
<script>
    @if(($data->hrla_status) <= 1 ) 
        disableLeaveApp()
        disableLeaveType()
    @endif
    
</script>
  
 
           