{{ Form::open(array('url' => 'CtoChargeTypes','class'=>'formDtls','id'=>'CtoChargeDescription')) }}
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
                    {{ Form::label('ctype_desc', __('Type Of Charge'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ctype_desc') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ctype_desc', $data->ctype_desc, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_ctype_desc"></span>
                </div>
            </div>
            <div class="col-md-6">
                    <div class="form-group">
               
                    {{ Form::checkbox('is_essential','1', ($data->is_essential)?true:false, array('id'=>'is_essential','class'=>'form-check-input ','style'=>'margin-top:38px')) }} {{Form::label('',__('Essential[or Non-Essential]'),['class'=>'form-label','style'=>'padding-top:38px;'])}}
               
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
    $('#submit').click(function (e) {
        e.preventDefault();
        $(".validate-err").html('');
        var data = $("form").serialize();
        var obj={};
        for(var key in data){
            obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
        }
        $.ajax({
            url :DIR+'CtoChargeTypes/formValidation', // json datasource
            type: "POST", 
            data: $('#CtoChargeDescription').serialize(),
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
                    $('#CtoChargeDescription').submit();
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