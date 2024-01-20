{{ Form::open(array('url' => 'realpropertyarsetup','enctype'=>'multipart/form-data','id'=>'submitLandUnitValueForm')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
    .select3-container{
        z-index:  !important;
    }
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
        margin-left: 4%;
        margin-top: 53%;
        transform: translate(0%, -50%);
    }
    </style>
            <div class="modal-body" style="overflow-x: hidden;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('pk_id', __('Property Kind'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <div class="form-icon-user">
                                     {{ Form::select('pk_id',$kinds, $data->pk_id, array('class' => 'form-control pk_id select3','placeholder' => 'Select Property kind')) }}
                                </div>
                                <span class="validate-err" id="err_pk_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ars_category', __('Category'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <div class="form-icon-user">
                                     {{ Form::select('ars_category', config('constants.accReceiveSetupCategory'), $data->ars_category, array('class' => 'form-control ars_category select3','placeholder' => 'Select Category')) }}
                                </div>
                                <span class="validate-err" id="err_ars_category"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ars_fund_id', __('Fund Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <div class="form-icon-user">
                                     {{ Form::select('ars_fund_id', $fundCodes,$data->ars_fund_id, array('class' => 'form-control ars_fund_id select3','placeholder' => 'Select Fund')) }}
                                </div>
                                <span class="validate-err" id="err_ars_fund_id"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('chart_of_account', __('Chart of Account'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <div class="form-icon-user">
                                     {{ Form::select('chart_of_account',$glAndSlIds, $data->chart_of_account, array('class' => 'form-control chart_of_account select3','placeholder' => 'Select Chart of Account')) }}
                                     <input type="hidden" name="gl_id" value="{{$data->gl_id}}">
                                     <input type="hidden" name="sl_id" value="{{$data->sl_id}}">
                                </div>
                                <span class="validate-err" id="err_gl_id"></span>
                                <span class="validate-err" id="err_sl_id"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('ars_remarks', __('Remarks'),['class'=>'form-label']) }}
                                <div class="form-icon-user">
                                     {{ Form::textarea('ars_remarks', $data->ars_remarks, array('class' => 'form-control ars_remarks','rows'=>2)) }}
                                </div>
                                <span class="validate-err" id="err_ars_remarks"></span>
                            </div>
                             <br><br><br><br>
                        </div>
                       
                       </div>
                   
                    
                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <input type="submit" name="submit" id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                        <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Update'):__('Save Changes')}}" class="btn  btn-primary"> -->
                    </div>
            </div>
    {{Form::close()}}
    
   <script src="{{ asset('js/ajax_validation.js') }}"></script> 
   <script type="text/javascript">
    $('.chart_of_account').change(function(){
        var selectedValue = $(this).val();
        if(selectedValue != ''){
            var array = selectedValue.split(",");
            
            var gl_id = (array.length == 2)?array[0]:'';
            var sl_id = (array.length == 2)?array[1]:'';
            $('input[name=gl_id]').val(gl_id);
            $('input[name=sl_id]').val(sl_id);
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
            url :DIR+'realpropertyarsetup/formValidation', // json datasource
            type: "POST", 
            data: $('#submitLandUnitValueForm').serialize(),
            dataType: 'json',
            success: function(html){
                if(html.ESTATUS){
                  
                    $("#err_"+html.field_name).html(html.error);
                    $("."+html.field_name).focus();
                }else{
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
        })
     
   });
});


</script>  