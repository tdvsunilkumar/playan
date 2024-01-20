{{ Form::open(array('url' => 'rptbuildingkind','class'=>'formDtls','id'=>'submitLandUnitValueForm')) }}   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
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
        margin-left: 50%;
        margin-top: 50%;
        transform: translate(-50%, -50%);
  }
</style>
            <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('bk_building_kind_code', __('Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('bk_building_kind_code') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('bk_building_kind_code', $data->bk_building_kind_code, array('class' => 'form-control','maxlength'=>'10','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_bk_building_kind_desc"></span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                {{ Form::label('bk_building_kind_desc', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('bk_building_kind_desc') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('bk_building_kind_desc', $data->bk_building_kind_desc, array('class' => 'form-control','maxlength'=>'75','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_bt_building_type_desc"></span>
                            </div>
                        </div>
                       </div>
                   
                    
                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <!-- <button type="button" id="submitlandunitvalueformButton" value="" class="btn btn-primary" >{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}</button> -->
                             <input type="submit" name="submit" id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                        <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Update'):__('Save Changes')}}" class="btn  btn-primary"> -->
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
            url :DIR+'rptbuildingkind/formValidation', // json datasource
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