{{ Form::open(array('url' => 'hr-work-schedule-employee','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
    .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .field-requirement-details-status label{padding-top:5px;}
</style> 
<div class="modal-body">
    <div class="row">
            <div class="col-md-3">
            <div class="form-group">
                {{ Form::label('hr_employeesid', __('Employee'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <span class="validate-err">{{ $errors->first('hr_employeesid') }}</span>
                <div class="form-icon-user select-contain" id="hr_employeesid_contain">
                    {{ Form::select('hr_employeesid',
                        isset($data->employee) ? [$data->hr_employeesid=>$data->employee->fullname]:[],
                        $data->hr_employeesid, 
                        array('class' => 'form-control select_emp',
                        'id'=>'hr_employeesid',
                        'placeholder'=>'Select Employee',
                        'required'=>'required'
                        )
                    ) }}
                </div>
                <span class="validate-err" id="err_hr_employeesid"></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {{ Form::label('hrds_id', __('Default Schedule'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <span class="validate-err">{{ $errors->first('hrds_id') }}</span>
                <div class="form-icon-user">
                    {{ Form::select('hrds_id',$arrdefaultschedule,$data->hrds_id, array('class' => 'form-control select3','id'=>'hrds_id','required'=>'required')) }}
                </div>
                <span class="validate-err" id="err_hrds_id"></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {{Form::label('start_date',__('Effectivity Date'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                {{ Form::date('start_date',
                    $data->start_date, 
                array(
                    'class' => 'form-control',
                    'id'=>'start_date',
                    'required'=>'required',
                    
                    )
                ) }}
                </div>
                <span class="validate-err" id="err_start_date"></span>
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