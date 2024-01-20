{{ Form::open(array('url' => 'buildingfeesdivision','class'=>'formDtls','id'=>'excavationgroundtype')) }}
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
				{{ Form::label('ebpfd_group', __('Group'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('ebpfd_group') }}</span>
				<div class="form-icon-user">
					{{ Form::text('ebpfd_group',$data->ebpfd_group, array('class' => 'form-control','id'=>'ebpfd_group','required'=>'required')) }}
				</div>
				<span class="validate-err" id="err_ebpfd_group"></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('ebpfd_description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('ebpfd_description') }}</span>
				<div class="form-icon-user">
					{{ Form::text('ebpfd_description',$data->ebpfd_description, array('class' => 'form-control','id'=>'ebpfd_description','required'=>'required')) }}
				</div>
				<span class="validate-err" id="err_ebpfd_description"></span>
			</div>
		</div>
	</div>
	<div class="row">
		 <div class="col-md-6">
			<div class="form-group">
				{{ Form::label('ebpfc_id', __('Fees Category'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('ebpfc_id') }}</span>
				<div class="form-icon-user">
					{{ Form::select('ebpfc_id',$arrfeescategory,$data->ebpfc_id, array('class' => 'form-control select3','id'=>'ebpfc_id','required'=>'required')) }}
				</div>
				<span class="validate-err" id="err_ebpfc_id"></span>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2" style="width: 2.66667%;">
			<div class="form-group">
				<div class="form-icon-user">
				{{ Form::radio('ebpfd_feessetid','1',($data->ebpfd_feessetid == 1)? 'true':'', array('class' => 'form-check-input','id'=>'ebpfd_feessetid')) }}
				</div>
				<span class="validate-err" id="err_ebpfd_feessetid"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<div class="form-icon-user">
					{{ Form::label('ebpfd_feessetid', __('Building Permit Fees Set A'),['class'=>'form-label']) }}
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2" style="width: 2.66667%;">
			<div class="form-group">
				<div class="form-icon-user">
				{{ Form::radio('ebpfd_feessetid','2',($data->ebpfd_feessetid == 2)? 'true':'', array('class' => 'form-check-input','id'=>'ebpfd_feessetid')) }}
				</div>
				<span class="validate-err" id="err_ebpfd_feessetid"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<div class="form-icon-user">
					{{ Form::label('ebpfd_feessetid', __('Building Permit Fees Set B'),['class'=>'form-label']) }}
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2" style="width: 2.66667%;">
			<div class="form-group">
				<div class="form-icon-user">
				{{ Form::radio('ebpfd_feessetid','3',($data->ebpfd_feessetid == 3)? 'true':'', array('class' => 'form-check-input','id'=>'ebpfd_feessetid')) }}
				</div>
				<span class="validate-err" id="err_ebpfd_feessetid"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<div class="form-icon-user">
					{{ Form::label('ebpfd_feessetid', __('Building Permit Fees Set C'),['class'=>'form-label']) }}
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2" style="width: 2.66667%;">
			<div class="form-group">
				<div class="form-icon-user">
				{{ Form::radio('ebpfd_feessetid','4',($data->ebpfd_feessetid == 4)? 'true':'', array('class' => 'form-check-input','id'=>'ebpfd_feessetid')) }}
				</div>
				<span class="validate-err" id="err_ebpfd_feessetid"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<div class="form-icon-user">
					{{ Form::label('ebpfd_feessetid', __('Building Permit Fees Set D'),['class'=>'form-label']) }}
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
		<div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
			<i class="fa fa-save icon"></i>
			<input type="submit" name="submit" id="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
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
  
 
           