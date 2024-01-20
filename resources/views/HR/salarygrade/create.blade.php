{{ Form::open(array('url' => 'hr-salary-grade','class'=>'formDtls','id'=>'submitLandUnitValueForm')) }}
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
                                {{ Form::label('hrsg_salary_grade', __('Salary grade'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrsg_salary_grade') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrsg_salary_grade',$data->hrsg_salary_grade, array('class' => 'form-control','id'=>'hrsg_salary_grade','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrsg_salary_grade"></span>
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('hrsg_step_1', __('Step 1'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrsg_step_1') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrsg_step_1',currency_format($data->hrsg_step_1), array('class' => 'form-control numeric-double','id'=>'hrsg_step_1','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrsg_step_1"></span>
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('hrsg_step_2', __('Step 2'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrsg_step_2') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrsg_step_2',currency_format($data->hrsg_step_2), array('class' => 'form-control numeric-double','id'=>'hrsg_step_2','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrsg_step_2"></span>
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('hrsg_step_3', __('Step 3'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrsg_step_3') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrsg_step_3',currency_format($data->hrsg_step_3), array('class' => 'form-control numeric-double','id'=>'hrsg_step_3','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrsg_step_3"></span>
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('hrsg_step_4', __('Step 4'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrsg_step_4') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrsg_step_4',currency_format($data->hrsg_step_4), array('class' => 'form-control numeric-double','id'=>'hrsg_step_4','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrsg_step_4"></span>
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('hrsg_step_5', __('Step 5'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrsg_step_5') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrsg_step_5',currency_format($data->hrsg_step_5), array('class' => 'form-control numeric-double','id'=>'hrsg_step_5','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrsg_step_5"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('hrsg_step_6', __('Step 6'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrsg_step_6') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrsg_step_6',currency_format($data->hrsg_step_6), array('class' => 'form-control numeric-double','id'=>'hrsg_step_6','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrsg_step_3"></span>
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('hrsg_step_7', __('Step 7'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrsg_step_7') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrsg_step_7',currency_format($data->hrsg_step_7), array('class' => 'form-control numeric-double','id'=>'hrsg_step_7','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrsg_step_7"></span>
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('hrsg_step_8', __('Step 8'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrsg_step_8') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrsg_step_8',currency_format($data->hrsg_step_8), array('class' => 'form-control numeric-double','id'=>'hrsg_step_8','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrsg_step_8"></span>
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
 <script src="{{ asset('js/HR/add_salarygrade.js') }}"></script> 
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
            url :DIR+'hr-salary-grade/formValidation', // json datasource
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
 
           