{{ Form::open(array('url' => 'profileprovince','id'=>'submitLandUnitValueForm')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    
  
   <style type="text/css">
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
   </style>

    <div class="modal-body">

         <div class="row">
            <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('prov_no', __('Number'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('prov_no', $data->prov_no, array('class' => 'form-control','required'=>'required','maxlength'=>'20')) }}
                    </div>
                    <span class="validate-err" id="err_prov_no"></span>
                </div>
            </div>
            
            
            
               <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('reg_no', __('Region'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::select('reg_no',$nofbusscode,$data->reg_no, array('class' => 'form-control select3 ','id'=>'reg_no','required'=>'required')) }}
                        
                    </div>
                    <span class="validate-err" id="err_reg_no"></span>
                </div>
            </div>   
            
                


              <!--   <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('reg_no2', __('Region'),['class'=>'form-label']) }}
        
                    <div class="form-icon-user">
                        {{ Form::text('reg_no2', '', array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div> -->
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('prov_desc', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('prov_desc', $data->prov_desc, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}

                    </div>
                    <span class="validate-err" id="err_prov_desc"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('uacs_code', __('UACS Code'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::number('uacs_code', $data->uacs_code, array('class' => 'form-control')) }}

                    </div>
                    <span class="validate-err" id="err_uacs_code"></span>
                </div>
            </div>
            
               
              
              
            
    </div>
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <input type="submit" name="submit" id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary">
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('js/addProfileProvince.js') }}"></script>
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
            url :DIR+'profileprovince/formValidation', // json datasource
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


