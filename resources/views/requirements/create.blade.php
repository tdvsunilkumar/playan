
{{ Form::open(array('url' => 'requirements','id'=>'submitLandUnitValueForm')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('req_code_abbreviation', __('Code Abbreviation'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('req_code_abbreviation', $data->req_code_abbreviation, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_req_code_abbreviation"></span>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('req_dept_bplo', __('Req-BPLO'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::select('req_dept_bplo',array('0' =>'No','1' =>'Yes'),$data->req_dept_bplo, array('class' => 'form-control spp_type','id'=>'req_dept_bplo')) }}
                    </div>
                    <span class="validate-err" id="err_req_dept_bplo"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('req_dept_bfp', __('Req-BFP'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                         {{ Form::select('req_dept_bfp',array('0' =>'No','1' =>'Yes'),$data->req_dept_bfp, array('class' => 'form-control spp_type','id'=>'req_dept_bfp')) }}
                    </div>
                    <span class="validate-err" id="err_req_dept_bfp"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('req_dept_health_office', __('Req-Health Office'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                         {{ Form::select('req_dept_health_office',array('0' =>'No','1' =>'Yes'),$data->req_dept_health_office, array('class' => 'form-control spp_type','id'=>'req_dept_health_office')) }}
                    </div>
                    <span class="validate-err" id="err_req_dept_health_office"></span>
                </div>
            </div>
             <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('req_dept_eng', __('Req Depart Eng'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                         {{ Form::select('req_dept_eng',array('0' =>'No','1' =>'Yes'),$data->req_dept_eng, array('class' => 'form-control spp_type','id'=>'req_dept_eng')) }}
                    </div>
                    <span class="validate-err" id="err_req_dept_health_office"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('req_dept_cpdo', __('Req-Cpdo Office'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                         {{ Form::select('req_dept_cpdo',array('0' =>'No','1' =>'Yes'),$data->req_dept_cpdo, array('class' => 'form-control spp_type','id'=>'req_dept_cpdo')) }}
                    </div>
                    <span class="validate-err" id="err_req_dept_health_office"></span>
                </div>
            </div>

        </div>
        <div class="row">   
            <div class="form-group col-md-12">
                {{ Form::label('req_description', __('Description'),['class'=>'form-label']) }}
                <span class="text-danger">*</span>
                {!! Form::textarea('req_description', $data->req_description, ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
                <span class="validate-err" id="err_req_description"></span>
            </div>
            
            <div class="d-flex radio-check">
                <div class="form-check form-check-inline form-group col-md-1">
                    {{ Form::radio('is_active', '1', ($data->is_active)?true:false, array('id'=>'active','class'=>'form-check-input code')) }}
                    {{ Form::label('active', __('Active'),['class'=>'form-label']) }}
                </div>
                <div class="form-check form-check-inline form-group col-md-1">
                    {{ Form::radio('is_active', '0', (!$data->is_active)?true:false, array('id'=>'inactive','class'=>'form-check-input code')) }}
                    {{ Form::label('inactive', __('InActive'),['class'=>'form-label']) }}
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
            url :DIR+'requirements/formValidation', // json datasource
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