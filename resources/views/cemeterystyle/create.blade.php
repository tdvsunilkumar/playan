<style>
.modal-content {
    position: absolute;
   float: left;
   margin-left: 50%;
   margin-top: 50%;
  transform: translate(-50%, -50%);
}
</style>
{{ Form::open(array('url' => 'cemeterystyle','class'=>'formDtls')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('eco_cemetery_style', __('Cemetery Style'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('eco_cemetery_style') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('eco_cemetery_style', $data->eco_cemetery_style, array('class' => 'form-control','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_eco_cemetery_style"></span>
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
<script src="{{ asset('js/ajax_validation_confir_mass.js') }}"></script>
  