{{ Form::open(array('url' => 'bplopaymentsschedule')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('psched_year', __('Year'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                         {{ Form::select('psched_year',$yeararr,$data->psched_year, array('class' => 'form-control ','id'=>'psched_year','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_psched_year"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('psched_mode_no', __('Mode'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('psched_mode_no', $data->psched_mode_no, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_psched_mode_no"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('psched_description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                         {{ Form::select('psched_description',$description,$data->psched_description, array('class' => 'form-control ','id'=>'psched_description','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_psched_description"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('psched_short_desc', __('Short Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                         {{ Form::select('psched_short_desc',$shortdesc,$data->psched_short_desc, array('class' => 'form-control ','id'=>'psched_short_desc','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_psched_short_desc"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('psched_date_start', __('Start Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::date('psched_date_start', $data->psched_date_start, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_psched_short_desc"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('psched_date_end', __('End Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::date('psched_date_end', $data->psched_date_end, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_psched_description"></span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('psched_penalty_due_date', __('Penalty Due Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::date('psched_penalty_due_date', $data->psched_penalty_due_date, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_psched_short_desc"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('psched_discount_due_date', __('Discount Due Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::date('psched_discount_due_date', $data->psched_discount_due_date, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_psched_description"></span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>




