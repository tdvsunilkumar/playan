<style>
.modal-content {
    position: absolute;
   float: left;
   margin-left: 50%;
   margin-top: 50%;
  transform: translate(-50%, -50%);
}
</style>
{{ Form::open(array('url' => 'receptions','class'=>'formDtls')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6" style="margin-bottom:150px;">
                <div class="form-group">
                    {{ Form::label('brgy_id', __('Location'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('brgy_id') }}</span>
                    <div class="form-icon-user" id="accordionFlushExample">
                         {{ Form::select('brgy_id',$location,$data->brgy_id, array('class' => 'form-control')) }}
                    </div>
                    <span class="validate-err" id="err_brgy_id"></span>
                </div>
            </div>  
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('reception_name', __('Receptions'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('reception_name') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('reception_name', $data->reception_name, array('class' => 'form-control','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_reception_name"></span>
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
<script type="text/javascript">
    $(document).ready(function () {
        $("#brgy_id").select3({dropdownAutoWidth : false,dropdownParent: $("#accordionFlushExample")});
  // select3Ajax("brgy_id","accordionFlushExample","getBarngayNameList");
});
</script>