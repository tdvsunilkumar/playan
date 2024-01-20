{{ Form::open(array('url' => 'hr-work-schedule','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('hrds_id', __('Default Schedule'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <span class="validate-err">{{ $errors->first('hrds_id') }}</span>
                <div class="form-icon-user">
                    {{ Form::select('hrds_id',
                        $arrdefaultschedule,
                        $data->hrds_id, 
                        array(
                            'class' => 'form-control select3',
                            'id'=>'hrds_id_sched',
                            'required'=>'required'
                        )
                    ) }}
                </div>
                <span class="validate-err" id="err_hrds_id"></span>
            </div>
        </div>
        <div class="col-md-7">
        </div>
        <div class="col-md-1 justify-content-right">
            <a class="btn btn-primary add-emp" id="add-emp" data-id="0" data-bs-toggle="tooltip" title="{{__('Add Requirement')}}">
                <i class="ti-plus"></i>
            </a>
        </div>
    </div>
    <div class="row">
        <table class="table" id="employees-tbl">
            <thead>
                <tr>
                    <th style="width: 50%;">Employee Name</th>
                    <th>Schedule</th>
                    <th style="width: 20%;">Effectivity Date</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
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
<table>
    <tbody class="hidden" id="addEmployee">
        <tr class="new-row" id="sched-changeid-contain">

            <td class="select-contain" id="contain-select-employee-changeid">
                {{ Form::select('sched[changeid][hr_employeesid]',
                    [],
                    '', 
                    array('class' => 'form-control select_emp',
                    'id'=>'hr_employeesid_changeid',
                    'placeholder'=>'Select Employee',
                    'required'=>'required'
                    )
                ) }}
            </td>

            <td>
                {{ Form::select('sched[changeid][hrds_id]',
                    $arrdefaultschedule,
                    0, 
                    array('class' => 'form-control select3 select_sched',
                    'id'=>'hrds_id_changeid',
                    'required'=>'required'
                    )
                ) }}
            </td>
            <td>
                {{ Form::date('sched[changeid][start_date]',
                '', 
                array(
                    'class' => 'form-control',
                    'id'=>'start_date_changeid',
                    'required'=>'required'
                    )
                ) }}
            </td>
            
        </tr>
    </tbody>
</table>