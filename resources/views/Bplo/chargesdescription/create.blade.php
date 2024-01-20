{{ Form::open(array('url' => 'CtoChargeDescription','class'=>'formDtls','id'=>'CtoChargeDescription')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<!-- <style>
.modal-content {
     position: absolute;
   float: left;
   margin-left: 50%;
   margin-top: 50%;
  transform: translate(-50%, -50%);
}
</style>

     -->

    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('charge_desc', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                         {{ Form::text('charge_desc', $data->charge_desc, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_charge_desc"></span>
                </div>
            </div>   
            <div class="col-md-6">
                 <div class="form-group">
                    {{ Form::label('charge_remarks', __('Remark'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                         {{ Form::text('charge_remarks', $data->charge_remarks, array('class' => 'form-control')) }}
                    </div>
                </div>
            </div>   
            <div class="col-md-12"></div>
            <div class="col-md-3">
               <div class="d-flex radio-check"><br>
                    <div class="form-check form-check-inline form-group">
                        {{ Form::checkbox('req_formula', '1', ($data->req_formula)?true:false, array('id'=>'req_formula','class'=>'form-check-input')) }}
                        {{ Form::label('req_formula', __('Req Formula'),['class'=>'form-label']) }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
               <div class="d-flex radio-check"><br>
                    <div class="form-check form-check-inline form-group">
                        {{ Form::checkbox('req_measure_pax', '1', ($data->req_measure_pax)?true:false, array('id'=>'req_measure_pax','class'=>'form-check-input')) }}
                        {{ Form::label('req_measure_pax', __('Req Measure and Pax'),['class'=>'form-label']) }}
                    </div>
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
<script type="text/javascript">
      $(document).ready(function () {
    var shouldSubmitForm = false;

        $('#submit').click(function (e) {
            var form = $('#CtoChargeDescription');
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
            var form = $('#CtoChargeDescription');
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