{{ Form::open(array('url' => 'planttress','class'=>'formDtls','id'=>'service')) }} 
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}

    <style type="text/css">
     .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 500px;
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
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('pt_ptrees_code', __('Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('pt_ptrees_code') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('pt_ptrees_code', $data->pt_ptrees_code, array('class' => 'form-control','maxlength'=>'5','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_pt_ptrees_code"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('pt_ptrees_description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('pt_ptrees_description') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('pt_ptrees_description', $data->pt_ptrees_description, array('class' => 'form-control','maxlength'=>'50','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_pt_ptrees_description"></span>
                            </div>
                        </div>
                       </div>
                   
                   
                   
                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <input type="submit" name="submit" id="savechanges"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary">
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
            url :DIR+'planttress/formValidation', // json datasource
            type: "POST", 
            data: $('#service').serialize(),
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
                    $('#service').submit();
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
  