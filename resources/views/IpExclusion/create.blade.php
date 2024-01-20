{{ Form::open(array('url' => 'ip-security-exclusion','class'=>'formDtls')) }}
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
				{{ Form::label('employee_id', __('Employee'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('employee_id') }}</span>
				<div class="form-icon-user">
                    {{
                        Form::select('employee_id', $arrHrEmployee, $value = $data->employee_id, ['id' => 'employee_id', 'class' => 'form-control select3'])
                    }}				
				</div>
				<span class="validate-err" id="err_employee_id"></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="ip_address" class="form-label">{{ __('Email Address') }}</label>
				<span class="validate-err">{{ $errors->first('email_address') }}</span>
				<div class="form-icon-user">
					{{ Form::text('email_address', "", array('class' => 'form-control', 'id' => 'email_address', 'required' => 'required','readonly'=> true)) }}
				</div>
				<span class="validate-err" id="err_email_address"></span>
			</div>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="department" class="form-label">{{ __('Department') }}</label>
				<span class="validate-err">{{ $errors->first('department') }}</span>
				<div class="form-icon-user">
					{{ Form::text('department', "", array('class' => 'form-control', 'id' => 'department','readonly'=> true)) }}
				</div>
				<span class="validate-err" id="err_department"></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="position" class="form-label">{{ __('Position') }}</label>
				<span class="validate-err">{{ $errors->first('position') }}</span>
				<div class="form-icon-user">
					{{ Form::text('position', "", array('class' => 'form-control', 'id' => 'position','readonly'=> true)) }}
				</div>
				<span class="validate-err" id="err_position"></span>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				{{ Form::label('remarks', __('Remarks'),['class'=>'form-label']) }}
				<span class="validate-err">{{ $errors->first('remarks') }}</span>
				<div class="form-icon-user">
					{{ Form::textarea('remarks', $data->remarks, array('class' => 'form-control','rows'=>3,'id'=>'remarks')) }}
				</div>
				<span class="validate-err" id="err_remarks"></span>
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
	<script src="{{ asset('js/AddIpExclusion.js') }}"></script>  
 <script src="{{ asset('js/ajax_validation_confir_mass.js') }}"></script>  

  
 
           