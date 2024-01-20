{{ Form::open(array('url' => 'hr-loan-cycle','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
    .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .field-requirement-details-status label{padding-top:5px;}
</style> 
<div class="modal-body">
	<div class="row">
		 <div class="col-md-6">
			<div class="form-group">
				{{ Form::label('hrlc_month', __('Month'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrlc_month') }}</span>
				<div class="form-icon-user">
					{{ Form::number('hrlc_month',$data->hrlc_month, array('class' => 'form-control','id'=>'hrlc_month','required'=>'required')) }}
				</div>
				<span class="validate-err" id="err_hrlc_month"></span>
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

  
 
           