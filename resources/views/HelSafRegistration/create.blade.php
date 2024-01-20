{{ Form::open(array('url' => 'Health-and-safety/registration','class'=>'formDtls')) }}
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
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('cit_id', __('Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('cit_id') }}</span>
                    <div class="form-icon-user">
                         {{ Form::select('cit_id',$arrcitizens,$data->cit_id, array('class' => 'form-control select3','id'=>'cit_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_cit_id"></span>
                </div>
				<div class="form-group paymentrateinput">
                    
                </div>
            </div>
			<div class="col-md-2 mt-4">
                <div class="form-group">
                    <div class="action-btn bg-info">
						<a class="mx-3 btn btn-sm  align-items-center refeshbuttonselect ti-reload text-white"  name="stp_print" title="{{__('Refesh')}}"></a>
					</div>
					<a href="{{ url('/health-safety-citizens?isopenAddform=1') }}" target="_blank" data-size="lg"  data-bs-toggle="tooltip" title="{{__('Add More')}}" class="btn btn-sm btn-primary addmoreslectcitize">
						<i class="ti-plus"></i>
					</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('reg_remarks', __('Remarks'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('reg_remarks') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('reg_remarks', $data->reg_remarks, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_reg_remarks"></span>
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
 <script src="{{ asset('js/addHelSafRegistration.js') }}"></script>
 <script type="text/javascript">
$(document).ready(function () {

	$("#commonModal").find('.body').css({overflow: 'unset'}) 
});
</script>

  