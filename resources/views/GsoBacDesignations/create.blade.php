{{ Form::open(array('url' => 'bac-designations','class'=>'formDtls','id'=>'submitLandUnitValueForm')) }}
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
		<div class="col-md-12">
			<div class="form-group">
				{{ Form::label('employee_id', __('Employee Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('employee_id') }}</span>
				<div class="form-icon-user">
					{{
                        Form::select('employee_id', $employee, $value = $data->employee_id , ['id' => 'employee_id', 'class' => 'form-control select3 required', 'data-placeholder' => 'Select a employee'])
                    }}
				</div>
				<span class="validate-err" id="err_employee_id"></span>
			</div>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				{{ Form::label('department_name', __('Department|Division'), ['class' => 'form-label']) }}
				<span class="validate-err">{{ $errors->first('department_name') }}</span>
				<div class="form-icon-user">
				
                        {{ Form::text('department_name',$department, array('class' => 'form-control ','id'=>'department_name','required'=>'required','readonly' => 'true')) }}
				
				</div>
				<span class="validate-err" id="err_department_name"></span>
			</div>
		</div>
	</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{{ Form::label('app_id', __('Application Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
					<span class="validate-err">{{ $errors->first('app_id') }}</span>
					<div class="form-icon-user">
						{{
							Form::select('app_id', $application, $value = $data->app_id, ['id' => 'app_id', 'class' => 'form-control select3 required', 'data-placeholder' => 'select'])
						}}
					</div>
					<span class="validate-err" id="err_app_id"></span>
				</div>
			</div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('position', __('Position'), ['class' => 'form-label']) }}
                    <span class="validate-err">{{ $errors->first('position') }}</span>
                    <div class="form-icon-user">
                            {{ Form::text('position',$data->position, array('class' => 'form-control required','id'=>'position')) }}
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
				<input type="submit" name="submit" id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
			</div>
		</div>
	</div>    
    {{Form::close()}}
	<script src="{{ asset('js/AddGsoBacDesignations.js') }}"></script>  
 <script src="{{ asset('js/ajax_validation.js') }}"></script>  
<script type="text/javascript">
    $(document).ready(function () {
    $('#savechanges').click(function (e) {
        e.preventDefault();
        $(".validate-err").html('');
        var data = $("form").serialize();
        var obj={};
        for(var key in data){
            obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
        }
        $.ajax({
            url :DIR+'bac-designations/formValidation', // json datasource
            type: "POST", 
            data: $('#submitLandUnitValueForm').serialize(),
            dataType: 'json',
            success: function(html){
                if(html.ESTATUS){
                  
                    $("#err_"+html.field_name).html(html.error);
                    $("."+html.field_name).focus();
                }else{
                    Swal.fire({
                    title: "Are you sure?",
                    html: '<span style="color: red;">This will save the current changes.</span>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                    $('#submitLandUnitValueForm').submit();
                    form.submit();
                    // location.reload();
                    } else {
                        console.log("Form submission canceled");
                    }
                });
                    
                }
            }
        })
     
   });
});


</script>  
  
 
           