{{ Form::open(array('url' => 'CtoPaymentHoliday','class'=>'formDtls')) }}
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
                    {{ Form::label('htype_id', __('Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('htype_id',$arrType,$data->htype_id, array('class' => 'form-control select3','id'=>'htype_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_htype_id"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('hol_desc', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('hol_desc') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('hol_desc', $data->hol_desc, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_hol_desc"></span>
                </div>
            </div> 
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('hol_start_date', __('Start Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('hol_start_date') }}</span>
                    <div class="form-icon-user">
                         {{ Form::date('hol_start_date', $data->hol_start_date, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_hol_start_date"></span>
                </div>
            </div> 
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('hol_end_date', __('End Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('hol_end_date') }}</span>
                    <div class="form-icon-user">
                         {{ Form::date('hol_end_date', $data->hol_end_date, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_hol_end_date"></span>
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
  