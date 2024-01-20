{{ Form::open(array('url' => 'engbldgoccupancytype','class'=>'formDtls','id'=>'excavationgroundtype')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}

    <style type="text/css">
    .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 100%;
        pointer-events: auto;
        background-color: #ffffff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 15px;
        outline: 0;
    }
	button.btn-close.ui-btn.ui-shadow.ui-corner-all {
		width: 10px;
	}
</style>
<div class="modal-body">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					{{ Form::label('ebot_description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
					<span class="validate-err">{{ $errors->first('ebot_description') }}</span>
					<div class="form-icon-user">
						 {{ Form::text('ebot_description', $data->ebot_description, array('class' => 'form-control','maxlength'=>'100','required'=>'required')) }}
					</div>
					<span class="validate-err" id="err_ebot_description"></span>
				</div>
			</div>
			
			<div class="col-md-2">
				<div class="form-group">
					{{ Form::label('ebot_is_building', __('Building'),['class'=>'form-label']) }}
					<span class="validate-err">{{ $errors->first('ebot_is_building') }}</span>
					<div class="form-icon-user">
				    {{ Form::checkbox('ebot_is_building',$data->ebot_is_building, ($data->ebot_is_building =='1')?true:false, array('id'=>'ebot_is_building','class'=>'custom-control-input')) }}
					</div>
					<span class="validate-err" id="err_id_building"></span>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					{{ Form::label('ebot_is_sanitary', __('Sanitary/Plumbing'),['class'=>'form-label']) }}
					<span class="validate-err">{{ $errors->first('ebot_is_sanitary') }}</span>
					<div class="form-icon-user">
						 {{ Form::checkbox('ebot_is_sanitary',$data->ebot_is_sanitary, ($data->ebot_is_sanitary =='1')?true:false, array('id'=>'ebot_is_sanitary','class'=>'custom-control-input')) }}
					</div>
					<span class="validate-err" id="err_is_sanitary"></span>
				</div>
			</div> 
			<div class="col-md-2">
				<div class="form-group">
					{{ Form::label('ebot_is_mechanical', __('Mechanical'),['class'=>'form-label']) }}
					<span class="validate-err">{{ $errors->first('ebot_is_mechanical') }}</span>
					<div class="form-icon-user">
						 {{ Form::checkbox('ebot_is_mechanical',$data->ebot_is_mechanical, ($data->ebot_is_mechanical =='1')?true:false, array('id'=>'ebot_is_mechanical','class'=>'custom-control-input')) }}
					</div>
					<span class="validate-err" id="err_is_sanitary"></span>
				</div>
			</div> 
			<div class="col-md-2">
				<div class="form-group">
					{{ Form::label('ebot_is_electrical', __('Electrical'),['class'=>'form-label']) }}
					<span class="validate-err">{{ $errors->first('ebot_is_electrical') }}</span>
					<div class="form-icon-user">
						 {{ Form::checkbox('ebot_is_electrical',$data->ebot_is_electrical, ($data->ebot_is_electrical =='1')?true:false, array('id'=>'ebot_is_electrical','class'=>'custom-control-input')) }}
					</div>
					<span class="validate-err" id="err_is_sanitary"></span>
				</div>
			</div> 			
		</div>
		<div class="row">
			<div class="col-md-2">
			<div class="form-group">
				{{ Form::label('ebot_is_electronics', __('Electronics'),['class'=>'form-label']) }}
				<span class="validate-err">{{ $errors->first('ebot_is_electronics') }}</span>
				<div class="form-icon-user">
					 {{ Form::checkbox('ebot_is_electronics',$data->ebot_is_electronics, ($data->ebot_is_electronics =='1')?true:false, array('id'=>'ebot_is_electronics','class'=>'custom-control-input')) }}
				</div>
				<span class="validate-err" id="err_is_sanitary"></span>
			</div>
			</div>
			<div class="col-md-2">
			<div class="form-group">
				{{ Form::label('ebot_is_excavation_and_ground', __('Excavation and Ground'),['class'=>'form-label']) }}
				<span class="validate-err">{{ $errors->first('ebot_is_excavation_and_ground') }}</span>
				<div class="form-icon-user">
					 {{ Form::checkbox('ebot_is_excavation_and_ground',$data->ebot_is_excavation_and_ground, ($data->ebot_is_excavation_and_ground =='1')?true:false, array('id'=>'ebot_is_excavation_and_ground','class'=>'custom-control-input')) }}
				</div>
				<span class="validate-err" id="err_is_sanitary"></span>
			</div>
			</div>
			<div class="col-md-2">
			<div class="form-group">
				{{ Form::label('ebot_is_civil_structural_permit', __('Civil/Structural Permit'),['class'=>'form-label']) }}
				<span class="validate-err">{{ $errors->first('ebot_is_civil_structural_permit') }}</span>
				<div class="form-icon-user">
					 {{ Form::checkbox('ebot_is_civil_structural_permit',$data->ebot_is_civil_structural_permit, ($data->ebot_is_civil_structural_permit =='1')?true:false, array('id'=>'ebot_is_civil_structural_permit','class'=>'custom-control-input')) }}
				</div>
				<span class="validate-err" id="err_is_sanitary"></span>
			</div>
			</div>
			<div class="col-md-2">
			<div class="form-group">
				{{ Form::label('ebot_is_architectural_permit', __('Architectural Permit'),['class'=>'form-label']) }}
				<span class="validate-err">{{ $errors->first('ebot_is_architectural_permit') }}</span>
				<div class="form-icon-user">
					 {{ Form::checkbox('ebot_is_architectural_permit',$data->ebot_is_architectural_permit, ($data->ebot_is_architectural_permit =='1')?true:false, array('id'=>'ebot_is_architectural_permit','class'=>'custom-control-input')) }}
				</div>
				<span class="validate-err" id="err_is_sanitary"></span>
			</div>
			</div>
			<div class="col-md-2">
			<div class="form-group">
				{{ Form::label('ebot_is_fencing', __('Fencing'),['class'=>'form-label']) }}
				<span class="validate-err">{{ $errors->first('ebot_is_fencing') }}</span>
				<div class="form-icon-user">
					 {{ Form::checkbox('ebot_is_fencing',$data->ebot_is_fencing, ($data->ebot_is_fencing =='1')?true:false, array('id'=>'ebot_is_fencing','class'=>'custom-control-input')) }}
				</div>
				<span class="validate-err" id="err_is_sanitary"></span>
			</div>
			</div>
			
		</div>		
		<div class="modal-footer">
			<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
			<div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
				<i class="fa fa-save icon"></i>
				<input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
			</div>
			<!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
		</div>
</div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('js/addengBldgOccupancytype.js') }}"></script>
<script type="text/javascript">
      $(document).ready(function () {
    var shouldSubmitForm = false;

        $('#submit').click(function (e) {
            var form = $('#excavationgroundtype');
            var areFieldsFilled = checkIfFieldsFilled();

            if (areFieldsFilled) {
                e.preventDefault(); // Prevent the default form submission

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
                        shouldSubmitForm = true;
                        form.submit();
                    } else {
                        console.log("Form submission canceled");
                    }
                });
            }
        });

        function checkIfFieldsFilled() {
            var form = $('#excavationgroundtype');
            var requiredFields = form.find('[required="required"]');
            var isValid = true;

            requiredFields.each(function () {
                var field = $(this);
                var fieldValue = field.val();

                if (fieldValue === '') {
                    isValid = false;
                    return false; // Exit the loop early if any field is empty
                }
            });

            if (!isValid) {
                // Swal.fire({
                //     title: "All required fields must be filled",
                //     icon: 'error',
                //     customClass: {
                //         confirmButton: 'btn btn-danger',
                //     },
                //     buttonsStyling: false
                // });
            }

            return isValid;
        }
});
  </script>
  