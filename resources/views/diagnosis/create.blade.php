{{ Form::open(array('url' => 'healthy-and-safety/setup-data/diagnosis', 'id'=>'diagnosis')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  
   <style type="text/css">
       .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 800px;
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
   </style>

    <div class="modal-body">

         <div class="row">
            <div class="col-md-12">
               <div class="form-group">
                    {{ Form::label('diag_name', __('Diagnose'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('diag_name', $data->diag_name, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
        </div>
        <div class="row">
           <div class="col-md-12">
              <div class="form-group">
                   {{ Form::label('icd10_details', __('ICD10 Details'),['class'=>'form-label']) }}
       
                   <div class="form-icon-user">
                       {{ Form::text('icd10_details', $data->icd10_details, array('class' => 'form-control','required'=>'required')) }}
                   </div>
               </div>
           </div>
       </div> 
       <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('diag_report_cat', 'Reporting Category', ['class' => 'form-label']) }}
                    {{ 
                        Form::select('diag_report_cat',array('1'=>'Disease','2'=>'Other Disease'),$data->diag_report_cat, array('class' => 'form-control select3','id'=>'diag_report_cat')) 
                    }}
                    <span class="validate-err" id="err_diag_report_cat">{{ $errors->first('diag_report_cat') }}</span>
                </div>
            </div>
        </div>
       <div class="row">
           <div class="col-md-12">
              <div class="form-group">
                   {{ Form::label('diag_remarks', __('Remarks'),['class'=>'form-label']) }}
                   <div class="form-icon-user">
                       {{ Form::text('diag_remarks', $data->diag_remarks, array('class' => 'form-control')) }}
                   </div>
               </div>
           </div>
       </div>
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" id="savechanges" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {

    var shouldSubmitForm = false;
    $('#savechanges').click(function (e) {
            var form = $('#diagnosis');
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
            var form = $('#diagnosis');
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




