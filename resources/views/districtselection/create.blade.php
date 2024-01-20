{{ Form::open(array('url' => 'district','class'=>'formDtls','id'=>'submitLandUnitValueForm')) }}
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
                        <div class="col-md-4">
                           <div class="form-group">
                                {{ Form::label('loc_local_code', __('Locality'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    
                                <div class="form-icon-user">
                                    {{ Form::select('loc_local_code',$arrMunCode,$data->loc_local_code, array('class' => 'form-control select3','id'=>'loc_local_code')) }}
                                    
                                </div>
                                <span class="validate-err" id="err_loc_local_code"></span>
                            </div>
                        </div> 

                        
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('dist_code', __('Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('dist_code') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('dist_code', $data->dist_code, array('class' => 'form-control','maxlength'=>'10')) }}
                                </div>
                                <span class="validate-err" id="err_dist_code"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('dist_name', __('Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('dist_name') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('dist_name', $data->dist_name, array('class' => 'form-control','maxlength'=>'30')) }}
                                </div>
                                <span class="validate-err" id="err_dist_name"></span>
                            </div>
                        </div>
                    </div>
                   
                    
                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <input type="submit" name="submit" id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                        <!-- <input type="submit" id="submit"  name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
       
                    </div>
            </div>
    {{Form::close()}}
    <script src="{{ asset('js/add_district.js') }}"></script>
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
            url :DIR+'district/formValidation', // json datasource
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
                    location.reload();
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