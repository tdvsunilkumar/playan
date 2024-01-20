{{ Form::open(array('url' => 'scheduledescription','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<style>
.modal-content {
    position: absolute;
   float: left;
   margin-left: 50%;
   margin-top: 50%;
  transform: translate(-50%, -50%);
}
</style>
            <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('sd_mode', __('Mode'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('sd_mode') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('sd_mode', $data->sd_mode, array('class' => 'form-control','maxlength'=>'10')) }}
                                </div>
                                <span class="validate-err" id="err_sd_mode"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('sd_description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('sd_description') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('sd_description', $data->sd_description, array('class' => 'form-control','maxlength'=>'75')) }}
                                </div>
                                <span class="validate-err" id="err_sd_description"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('sd_description_short', __('Short Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('sd_description_short') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('sd_description_short', $data->sd_description_short, array('class' => 'form-control','maxlength'=>'75')) }}
                                </div>
                                <span class="validate-err" id="err_sd_description_short"></span>
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
    
  <!-- <script src="{{ asset('js/ajax_validation.js') }}"></script> -->
  <script src="{{ asset('js/ajax_common_save.js') }}"></script>