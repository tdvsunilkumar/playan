{{ Form::open(array('url' => 'hr-appointment','class'=>'formDtls','enctype'=>'multipart/form-data','id'=>'submitLandUnitValueForm')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
   <style>
      .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .field-requirement-details-status label{padding-top:10px;}
    .permitclick{  cursor:pointer;color:skyblue; }
    .orpayment > .row{ padding:10px; }
    .btn{padding: 0.575rem 0.5rem;}
    .accordion-button::after {
    background-image: url();
  }
 </style>
<div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group" id="hra_department_id_div">
                                {{ Form::label('hra_department_id', __('Department'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hra_department_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hra_department_id',$department,$data->hra_department_id, array('class' => 'form-control ','id'=>'hra_department_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hra_department_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="hra_division_id_div">
                                {{ Form::label('hra_division_id', __('Division'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hra_division_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hra_division_id',$division,$data->hra_division_id, array('class' => 'form-control ','id'=>'hra_division_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hra_division_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id='hr_emp_id_div'>
                                {{ Form::label('hr_emp_id', __('Employee Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hr_emp_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hr_emp_id',$employee,$data->hr_emp_id, array('class' => 'form-control ','id'=>'hr_emp_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hr_emp_id"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hra_employee_no', __('Employee No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hra_employee_no') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hra_employee_no',$data->hra_employee_no, array('class' => 'form-control','id'=>'hra_employee_no','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hra_employee_no"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hra_date_hired', __('Date Hired'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hra_date_hired') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::date('hra_date_hired',$data->hra_date_hired, array('class' => 'form-control','id'=>'hra_date_hired','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrlog_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hra_designation', __('Designation'), ['class' => 'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hra_designation') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hra_designation', $data->hra_designation, array('class' => 'form-control', 'id' => 'hra_designation', 'required' => 'required','readonly'=>'readonly')) }}                               
                                </div>
                                <span class="validate-err" id="err_hra_designation"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group" id="hres_id_div">
                                {{ Form::label('hres_id', __('Employment Status'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hres_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hres_id',$employee_status,$data->hres_id, array('class' => 'form-control ','id'=>'hres_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hres_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="hras_id_div">
                                {{ Form::label('hras_id', __('Appointment Status'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hras_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hras_id',$employee_appointment_status,$data->hras_id, array('class' => 'form-control ','id'=>'hras_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hras_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="hrpt_id_div">
                                {{ Form::label('hrpt_id', __('Payment Term'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrpt_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hrpt_id',$payment_term,$data->hrpt_id, array('class' => 'form-control ','id'=>'hrpt_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrpt_id"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group" id="hrol_id_div">
                                {{ Form::label('hrol_id', __('Occupational Level'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrol_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hrol_id',$occupation_lev,$data->hrol_id, array('class' => 'form-control ','id'=>'hrol_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrol_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="hrsg_id_div">
                                {{ Form::label('hrsg_id', __('Salary Grade'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrsg_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hrsg_id',$salary_grade,$data->hrsg_id, array('class' => 'form-control ','id'=>'hrsg_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrsg_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="hrsgs_id_div">
                                {{ Form::label('hrsgs_id', __('Step'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hra_division_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hrsgs_id',$salary_grade_step,$data->hrsgs_id, array('class' => 'form-control ','id'=>'hrsgs_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrsgs_id"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hra_monthly_rate', __('Monthly Rate'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hra_monthly_rate') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hra_monthly_rate',currency_format($data->hra_monthly_rate), array('class' => 'form-control','id'=>'hra_monthly_rate','required'=>'required','readonly'=>'readonly')) }}
                                </div>
                                <span class="validate-err" id="err_hra_monthly_rate"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hra_annual_rate', __('Annual Rate'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hra_annual_rate') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hra_annual_rate',currency_format($data->hra_annual_rate), array('class' => 'form-control','id'=>'hra_annual_rate','required'=>'required','readonly'=>'readonly')) }}
                                </div>
                                <span class="validate-err" id="err_hra_annual_rate"></span>
                            </div>
                        </div>
                    </div> 
                    <div class="modal-footer">
                
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-danger" data-bs-dismiss="modal">
                                <div class="button" style="background: #20b7cc;color: #fff;border-radius: 5px;">
                                    <input type="submit" id="savechanges" class="btn btn-primary add" name="submit" value="Submit">
                                </div>           
                    </div>
        </div>    
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script> 
<script src="{{ asset('js/HR/add_appointment.js') }}"></script>   
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
            url :DIR+'hr-appointment/formValidation', // json datasource
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
  
 
           