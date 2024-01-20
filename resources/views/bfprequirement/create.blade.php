{{ Form::open(array('url' => 'bfprequirement','id'=>'excavationgroundtype')) }}

{{ Form::hidden('id',$data->id, array('id' => 'id')) }}

<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group"id="parrent_btype_id">
                {{ Form::label('btype_id', __('Application Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::select('btype_id',$arrApplicationType,$data->btype_id, array('class' => 'form-control','id'=>'btype_id','required'=>'required')) }}
                </div>
                <span class="validate-err" id="err_ra_appraiser_id"></span>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group" id="parrent_bap_id">
                {{ Form::label('bap_id', __('Purpose'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::select('bap_id',$arrPurpose,$data->bap_id, array('class' => 'form-control','id'=>'bap_id','required'=>'required')) }}
                </div>
                <span class="validate-err" id="err_ra_appraiser_id"></span>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group" id="parrent_bac_id">
                {{ Form::label('bac_id', __('Category'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::select('bac_id',$arrCategory,$data->bac_id, array('class' => 'form-control','id'=>'bac_id','required'=>'required')) }}
                </div>
                <span class="validate-err" id="err_bac_id"></span>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group" id="parrent_req_id" style="margin-bottom:200px;">
                {{ Form::label('req_id', __('Requirement'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::select('req_id',$arrRequirements,$data->req_id, array('class' => 'form-control','id'=>'req_id','required'=>'required')) }}
                </div>
                <span class="validate-err" id="err_bac_id"></span>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
            <i class="fa fa-save icon"></i>
            <input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
        </div>
        <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
    </div>
</div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}?rand={{ rand(000,999) }}"></script>
<script src="{{ asset('js/add_bfprequirement.js') }}"></script>
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



