{{ Form::open(array('url' => 'psicdivision','class'=>'formDtls','id'=>'submitLandUnitValueForm')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
        .modal.show .modal-dialog {
            transform: none;
            width: 800px;
        }
    </style>
    
            <div class="modal-body">
                    <div class="row">
                        
                         <div class="col-md-12">
                            <div class="form-group" id="group_section_id">
                                {{ Form::label('section_id', __('Section'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('section_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('section_id',$arrsection,$data->section_id, array('class' => 'form-control select3','id'=>'section_id','required'=>'required')) }}
                                </div>
                                 <span class="validate-err" id="err_section_id"></span>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="form-group" >
                                {{ Form::label('division_code', __('Division Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('division_code') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('division_code', $data->division_code, array('class' => 'form-control','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_division_code"></span>
                            </div>
                        </div>
                        <div class="col-md-4" style="padding-top:38px;">
                           <div class="d-flex radio-check">
                            <div class="form-check form-check-inline form-group col-md-3">
                                {{ Form::radio('division_status', '1', ($data->division_status)?true:false, array('id'=>'active','class'=>'form-check-input code')) }}
                                {{ Form::label('active', __('Active'),['class'=>'form-label']) }}
                            </div>
                            <div class="form-check form-check-inline form-group col-md-3">
                                {{ Form::radio('division_status', '0', (!$data->division_status)?true:false, array('id'=>'inactive','class'=>'form-check-input code')) }}
                                {{ Form::label('inactive', __('InActive'),['class'=>'form-label']) }}
                            </div>
                        </div>
                        </div>
                    </div>
                    
                    <div class="row"> 
                        <div class="form-group col-md-12">
                            {{ Form::label('division_description', __('Division Desc'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                            <span class="text-danger">*</span>
                            <span class="validate-err">{{ $errors->first('division_description') }}</span>
                            {!! Form::textarea('division_description', $data->division_description, ['class'=>'form-control','rows'=>'3','required'=>'required']) !!}
                            <span class="validate-err" id="err_division_description"></span>
                        </div>
                        
                    </div>
                   
                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <input type="submit" name="submit"  id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                        <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
                    </div>
            </div>
    {{Form::close()}}
   <script src="{{ asset('js/ajax_validation.js') }}"></script> 
  <script type="text/javascript">
    $(document).ready(function () {
    select3Ajax("section_id","group_section_id","sectionAjaxList");
    $('#savechanges').click(function (e) {
        e.preventDefault();
        $(".validate-err").html('');
        var data = $("form").serialize();
        var obj={};
        for(var key in data){
            obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
        }
        $.ajax({
            url :DIR+'psicdivision/formValidation', // json datasource
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