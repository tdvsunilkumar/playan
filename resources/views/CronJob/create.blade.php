{{ Form::open(array('url' => 'cron-job','class'=>'formDtls')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('schedule_val',$data->schedule_value, array('id' => 'schedule_val')) }}
{{ Form::hidden('h_day',$data->day, array('id' => 'h_day')) }}
{{ Form::hidden('h_hours',$data->hours, array('id' => 'h_hours')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('department', __('Department'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('department') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('department', $data->department, array('class' => 'form-control','maxlength'=>'50','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_department"></span>
                </div>
            </div> 
            <div class="col-md-6">
                <div class="form-group" id="schedule_type_parrent">
                    {{ Form::label('schedule_type', __('Schedule Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('schedule_type') }}</span>
                    <div class="form-icon-user">
                         {{ Form::select('schedule_type',$scheduleType,$data->schedule_type, array('class' => 'form-control','id' => 'schedule_type','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_schedule_type"></span>
                </div>
            </div> 
        </div>
        <div class="row" id="divSchduleValue"> 

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('url', __('URL'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('url') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('url', $data->url, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_url"></span>
                </div>
            </div> 
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('description') }}</span>
                    <div class="form-icon-user">
                         {{ Form::textarea('description', $data->description, array('class' => 'form-control','maxlength'=>'50','required'=>'required','rows'=>'2')) }}
                    </div>
                    <span class="validate-err" id="err_description"></span>
                </div>
            </div>    
      
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('remarks', __('Remarks'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('remarks') }}</span>
                    <div class="form-icon-user">
                         {{ Form::textarea('remarks', $data->remarks, array('class' => 'form-control','maxlength'=>'50','rows'=>'2')) }}
                    </div>
                    <span class="validate-err" id="err_remarks"></span>
                </div>
            </div> 
        </div>   
        
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation_confir_mass.js') }}"></script>
<script src="{{ asset('js/add_cron_job.js') }}"></script>

  