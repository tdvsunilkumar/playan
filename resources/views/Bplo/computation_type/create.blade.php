{{ Form::open(array('url' => 'CtoComputationType','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}

    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('cctype_desc', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('cctype_desc') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('cctype_desc', $data->cctype_desc, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_cctype_desc"></span>
                </div>
            </div>    
            <div class="col-md-12"></div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('cctype_remarks', __('Remark'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('cctype_remarks') }}</span>
                    <div class="form-icon-user">
                         {{ Form::textarea('cctype_remarks', $data->cctype_remarks, array('class' => 'form-control','rows' => '5')) }}
                    </div>
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
  