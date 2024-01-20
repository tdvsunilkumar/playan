{{ Form::open(array('url' => 'bfpoccupancytype','enctype'=>'multipart/form-data','id'=>'excavationgroundtype')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  <style>
    .modal.show .modal-dialog {
        transform: none;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 80%;
        pointer-events: auto;
        background-color: #ffffff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 15px;
        outline: 0;
        float: left;
        margin-left: 50%;
        margin-top: 50%;
        transform: translate(-50%, -50%);
  }
    .col-md-1 {
        flex: 0 0 auto;
        width: 15.33333%;
    }
   
 </style>



    <div class="modal-body">
         <div class="row">
            <div class="col-md-12">
               <div class="form-group">
                    {{ Form::label('bot_occupancy_type', __('Occupancy type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('bot_occupancy_type', $data->bot_occupancy_type, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bot_occupancy_type"></span>
                </div>
            </div>
             <div class="col-md-12">
               <div class="form-group">
                    {{ Form::label('bot_occupancy_pdf', __('Occupancy type'),['class'=>'form-label']) }}
        
                    <div class="form-icon-user">
                         {{ Form::input('file', 'bot_occupancy_pdf','',array('class'=>'form-control','accept'=>'application/pdf'))}} 
                        
                         @if(!empty($data->bot_occupancy_pdf))
                         <p><a href="../../uploads/bfpocuupancy/{{$data->bot_occupancy_pdf}}" target="_blank">View File</a></p>
                         @endif 
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
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



