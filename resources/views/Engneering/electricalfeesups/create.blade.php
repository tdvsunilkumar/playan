{{ Form::open(array('url' => 'engelectricalfeesups','class'=>'formDtls','id'=>'excavationgroundtype')) }}
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
				{{ Form::label('eefu_kva_range_from', __('KVA Range Form'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('eefu_kva_range_from') }}</span>
				<div class="form-icon-user">
					{{ Form::number('eefu_kva_range_from',$data->eefu_kva_range_from, array('class' => 'form-control','step'=>'any','id'=>'eefu_kva_range_from','required'=>'required')) }}
				</div>
				<span class="validate-err" id="err_eefu_kva_range_from"></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('eefu_kva_range_to', __('KVA Range To'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('eefu_kva_range_to') }}</span>
				<div class="form-icon-user">
					{{ Form::number('eefu_kva_range_to',$data->eefu_kva_range_to, array('class' => 'form-control','step'=>'any','id'=>'eefu_kva_range_to','required'=>'required')) }}
				</div>
				<span class="validate-err" id="err_eefu_kva_range_to"></span>
			</div>
		</div>
	</div>
	<div class="row">
		 <div class="col-md-6">
			<div class="form-group">
				{{ Form::label('eefu_fees', __('Fees'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('eefu_fees') }}</span>
				<div class="form-icon-user">
					{{ Form::number('eefu_fees',$data->eefu_fees, array('class' => 'form-control','step'=>'any','id'=>'eefu_fees','required'=>'required')) }}
				</div>
				<span class="validate-err" id="err_eefu_fees"></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('eefu_in_excess_fees', __('Excess Fees'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('eefu_in_excess_fees') }}</span>
				<div class="form-icon-user">
					{{ Form::number('eefu_in_excess_fees',$data->eefu_in_excess_fees, array('class' => 'form-control','id'=>'eefu_in_excess_fees','step'=>'any','required'=>'required')) }}
				</div>
				<span class="validate-err" id="err_eefu_in_excess_fees"></span>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
		<div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
			<i class="fa fa-save icon"></i>
			<input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
		</div>
	</div>
</div>    
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>  
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
  
 
           