{{ Form::open(array('url' => 'CtoPaymentMode','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}

    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('pm_desc', __('Payment Mode'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('pm_desc') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('pm_desc', $data->pm_desc, array('class' => 'form-control','maxlength'=>'50','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_pm_desc"></span>
                </div>
            </div> 
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('pm_no', __('Pay Every-Nth-Month'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('pm_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::number('pm_no', $data->pm_no, array('class' => 'form-control','max'=>'12','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_pm_no"></span>
                </div>
            </div>  
        </div> 
       
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
  