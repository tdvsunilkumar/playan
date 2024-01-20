<style>
.modal-content {
   position: absolute;
   float: left;
   margin-left: 50%;
   margin-top: 80%;
  transform: translate(-50%, -50%);
}
</style>
{{ Form::open(array('url' => 'imperial-system/store','class'=>'formDtls','id'=>'cpdomodule')) }}
{!! Form::hidden('id', $selected['id'], array('id' => 'id')) !!}
{!! Form::hidden('is_active', $selected['is_active'], array('id' => 'is_active')) !!}

<div class="modal-body">
	<div class="row pt10">
		<div class="col-lg-12 col-md-16 col-sm-12" id="accordionFlushExample">  
			<div class="accordion accordion-flush">
				<div class="accordion-item">
					<h6 class="accordion-header" id="flush-headingone">
						<button class="accordion-button  btn-primary" type="button">
							Imperial System
						</button>
					</h6>
					<div id="flush-collapseone" class="accordion-collapse collapse show">
						<div class="basicinfodiv">
							<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::label('Code', __('Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('code') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::text('cis_code',  
                                                $selected['cis_code'], 
                                                ['class' => 'form-control', 'id' => 'code', 'placeholder' => 'Code','required'=>'required']) !!}
                                        </div>
                                        <span class="validate-err" id="err_code"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::label('imperial_system', __('Imperial System'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('imperial_system') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::text('cis_imperial_system',  
                                                $selected['cis_imperial_system'], 
                                                ['class' => 'form-control', 'id' => 'imperial_system', 'placeholder' => 'Imperial System','required'=>'required']) !!}
                                        </div>
                                        <span class="validate-err" id="err_imperial_system"></span>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> 
	<div class="modal-footer">
		<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="submit" value="Save Changes" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
		<!-- <input type="submit" name="submit" value="Save Changes" class="btn  btn-primary"> -->
	</div>
</div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script type="text/javascript">
      $(document).ready(function () {
        $("#cm_type").select3({ dropdownAutoWidth: false });
    var shouldSubmitForm = false;

        $('#submit').click(function (e) {
            var form = $('#cpdomodule');
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
            var form = $('#cpdomodule');
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