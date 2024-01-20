{{ Form::open(array('url' => 'hr-pagibig','class'=>'formDtls','id'=>'submitLandUnitValueForm')) }}
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
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('hrpit_description', __('Desciption'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrpit_description') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrpit_description',$data->hrpit_description, array('class' => 'form-control','id'=>'hrpit_description','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrpit_description"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hrpit_amount_from', __('Amount Range From'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrpit_amount_from') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrpit_amount_from',currency_format($data->hrpit_amount_from), array('class' => 'form-control','id'=>'hrpit_amount_from','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrpit_amount_from"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hrpit_amount_to', __('Amount Range To'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrpit_amount_to') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrpit_amount_to',currency_format($data->hrpit_amount_to), array('class' => 'form-control','id'=>'hrpit_amount_to','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrpit_amount_to"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hrpit_personal_share', __('Personal Share'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                
                                {{Form::radio('hrpit_personal_type', 0, $data->hrpit_personal_type  === 0 ? true:false, ['id'=> 'hrpit_personal_type_0'])}}
                                {{ Form::label('hrpit_personal_type_0', __('%'),['class'=>'form-label']) }}
                                {{Form::radio('hrpit_personal_type', 1, $data->hrpit_personal_type  === 1 ? true:false, ['id'=> 'hrpit_personal_type_1'])}}
                                {{ Form::label('hrpit_personal_type_1', __('Fixed'),['class'=>'form-label']) }}

                                <div class="form-icon-user">
                                    {{ Form::text('hrpit_personal_share',$data->hrpit_personal_share, array('class' => 'form-control','id'=>'hrpit_personal_share','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrpit_personal_share"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hrpit_gov_share', __('Government Share'),['class'=>'form-label']) }}<span class="text-danger">*</span>

                                {{Form::radio('hrpit_gov_type', 0, $data->hrpit_gov_type  === 0 ? true:false, ['id'=> 'hrpit_gov_type_0'])}}
                                {{ Form::label('hrpit_gov_type_0', __('%'),['class'=>'form-label']) }}
                                {{Form::radio('hrpit_gov_type', 1, $data->hrpit_gov_type  === 1 ? true:false, ['id'=> 'hrpit_gov_type_1'])}}
                                {{ Form::label('hrpit_gov_type_1', __('Fixed'),['class'=>'form-label']) }}

                                <div class="form-icon-user">
                                    {{ Form::text('hrpit_gov_share',$data->hrpit_gov_share, array('class' => 'form-control','id'=>'hrpit_gov_share','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrpit_gov_share"></span>
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
            url :DIR+'hr-pagibig/formValidation', // json datasource
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
  
 
           