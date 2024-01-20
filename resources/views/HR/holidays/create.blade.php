{{ Form::open(array('url' => 'hr-holidays','class'=>'formDtls','id'=>'submitLandUnitValueForm')) }}
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
                         <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('hrh_date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrh_date') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::date('hrh_date',$data->hrh_date, array('class' => 'form-control','id'=>'hrh_date','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrh_date"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hrh_description', __('Desciption'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrh_description') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrh_description',$data->hrh_description, array('class' => 'form-control','id'=>'hrh_description','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrh_description"></span>
                            </div>
                            
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hrht_id', __('Holiday Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrht_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hrht_id',$arrholidaytype,$data->hrht_id, array('class' => 'form-control select3','id'=>'hrht_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrht_id"></span>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                {{ Form::label('hrh_is_paid', __('Is Paid'),['class'=>'form-label']) }} 
                                <span class="validate-err">{{ $errors->first('hrh_is_paid') }}</span>
                                <div class="form-icon-user">
                                    <input type="checkbox" name="hrh_is_paid" id="hrh_is_paid" value="1" {{ ($data->hrh_is_paid) ? 'checked': '' }} >

                                </div>
                                <span class="validate-err" id="err_hrh_is_paid"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <input type="submit" name="submit" id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                        <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
                    </div>
        </div>    
    {{Form::close()}}
 <script src="{{ asset('js/ajax_validation.js') }}"></script>  
<script>
    $('.modal-body').on('change', '#hrht_id', function (e) {
        holiday_type = $(this).find(':selected').text()
        if (holiday_type == 'Special Holiday' || holiday_type == 'Regular Holiday' ) {
            // $('#hrh_is_paid').prop('disabled',true);
            $('#hrh_is_paid').prop('checked',true);
        } else {
            // $('#hrh_is_paid').prop('disabled',false);
        }
    });

</script>
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
            url :DIR+'hr-holidays/formValidation', // json datasource
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
 
           