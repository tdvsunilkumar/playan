{{ Form::open(array('url' => 'serologymethod','class'=>'formDtls','id'=>'service')) }}
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
                    {{ Form::label('ser_id', __('Serology Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ser_id') }}</span>
                    <div class="form-icon-user">
						 {{ Form::select('ser_id',$SerologyType,$data->ser_id, array('class' =>'form-control select3','id'=>'ser_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_ser_id"></span>
                </div>
            </div>
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('ser_m_method', __('Method'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ser_m_method') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ser_m_method', $data->ser_m_method, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_ser_m_method"></span>
                </div>
            </div>			
        </div> 
       <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('ser_m_remarks', __('Remarks'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('ser_id') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ser_m_remarks', $data->ser_m_remarks, array('class' =>'form-control')) }}
                    </div>
                    <span class="validate-err" id="err_ser_m_remarks"></span>
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
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {

    var shouldSubmitForm = false;
    $('#savechanges').click(function (e) {
            var form = $('#service');
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
            var form = $('#service');
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
                
            }

            return isValid;
        }
    });
</script>