{{ Form::open(array('url' => 'HrleaveParameterDetail','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
	{{ Form::hidden('hrlp_id',$data->hrlp_id, array('id' => 'hrlp_id')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group" id="parrent_hrlt_id">
                    {{ Form::label('hrlt_id', __('Leave type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('hrlt_id') }}</span>
                    <div class="form-icon-user" >
                         {{ Form::select('hrlt_id',$Arrhrleavetype,$data->hrlt_id, array('class' => 'form-control','id'=>'hrlt_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_hrlt_id"></span>
                </div>
            </div>
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('hrlpc_days', __('Of Days'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('hrlt_id') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('hrlpc_days', $data->hrlpc_days, array('class' => 'form-control','id'=>'hrlpc_days','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_hrlpc_days"></span>
                </div>
            </div>
        </div> 
       <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('hrat_id', __('Accrual Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('hrlt_id') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('hrat_id', $data->hrat_id, array('class' => 'form-control','id'=>'hrat_id','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_hrat_id"></span>
                </div>
            </div>
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('hrlpc_credits', __('Accrual Credits'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('hrlt_id') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('hrlpc_credits', $data->hrlpc_credits, array('class' => 'form-control','id'=>'hrlpc_credits','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_hrlpc_credits"></span>
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
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
	$("#hrlt_id").select3({dropdownAutoWidth : false,dropdownParent: $("#parrent_hrlt_id")});
});
</script>

  