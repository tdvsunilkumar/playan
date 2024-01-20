{{ Form::open(array('url' => 'rptappraisers','id'=>'submitLandUnitValueForm')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  
    <style>
    
    .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 600px;
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
                    {{ Form::label('ra_appraiser_id', __('Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{ Form::select('ra_appraiser_id',$arrHrEmpCode,$data->ra_appraiser_id, array('class' => 'form-control select3','id'=>'ra_appraiser_id','required'=>'required')) }}     
                       </div>
                    <span class="validate-err" id="err_ra_appraiser_id"></span>
                </div>
            </div>
            <div class="col-md-12">
               <div class="form-group">
                    {{ Form::label('ra_appraiser_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('ra_appraiser_position', $data->ra_appraiser_position, array('class' => 'form-control','required'=>'required','id'=>'ra_appraiser_position')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div> 
            <div class="col-md-12">
                                        <div class="d-flex radio-check"><br>
                                            <div class="form-check form-check-inline form-group">
                                                {{ Form::checkbox('is_sgd', '1', ($data->is_sgd)?true:false, array('id'=>'is_sgd','class'=>'form-check-input is_sgd')) }}
                                                {{ Form::label('Change Property Ownership', __('SGD'),['class'=>'form-label']) }}
                                            </div>
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
<script src="{{ asset('js/addRptAppraisal.js') }}"></script>
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
            url :DIR+'rptappraisers/formValidation', // json datasource
            type: "POST", 
            data: $('#submitLandUnitValueForm').serialize(),
            dataType: 'json',
            success: function(html){
                if(html.ESTATUS){
                  
                    $("#err_"+html.field_name).html(html.error);
                    $("."+html.field_name).focus();
                }else{
                    var areFieldsFilled = checkIfFieldsFilled();
                    if (areFieldsFilled) {
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
            }
        })
     
   });
   function checkIfFieldsFilled() {
            var form = $('#submitLandUnitValueForm');
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
<script type="text/javascript">
setTimeout(function(){ 
      var id = "{{($data->ra_appraiser_id != '')?$data->ra_appraiser_id:''}}";

      if(id > 0){
      var text = "{{(isset($data->fullname) && $data->fullname != '')?$data->fullname:'Please Select'}}";
               $("#ra_appraiser_id").select3("trigger", "select", {
    data: { id: id ,text:text}
});
            }

}, 500);
</script>




