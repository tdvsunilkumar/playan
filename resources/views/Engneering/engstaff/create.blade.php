{{ Form::open(array('url' => 'engineeringstaff','class'=>'formDtls','id'=>'engineeringstaff')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  
    <style>
    
    .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 600px;
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
                    {{ Form::label('ees_employee_id', __('Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{ Form::select('ees_employee_id',$arrHrEmpCode,$data->ees_employee_id, array('class' => 'form-control select3','id'=>'ees_employee_id','required'=>'required')) }}     
                       </div>
                    <span class="validate-err" id="err_ra_appraiser_id"></span>
                </div>
            </div>
            <div class="col-md-12">
               <div class="form-group">
                    {{ Form::label('ees_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('ees_position', $data->ees_position, array('class' => 'form-control','required'=>'required','id'=>'ees_position')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
		 </div>
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" id="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
        </div>
    </div>
{{Form::close()}}
<!-- <script src="{{ asset('js/ajax_validation.js') }}"></script> -->
<script src="{{ asset('js//Engneering\add_engstaff.js') }}"></script>
<script type="text/javascript">
setTimeout(function(){ 
      var id = "{{($data->ees_employee_id != '')?$data->ees_employee_id:''}}";

      if(id > 0){
      var text = "{{(isset($data->fullname) && $data->fullname != '')?$data->fullname:'Please Select'}}";
               $("#ees_employee_id").select3("trigger", "select", {
    data: { id: id ,text:text}
});
            }

}, 500);

 $(document).ready(function () {
    $(document).on('keyup','.decimalvalue',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
        });
    var shouldSubmitForm = false;
    $('form').submit(function(e) {
        e.preventDefault();
        $(".validate-err").html('');
        var myForm = $(this);
        myForm.find("input[name='submit']").unbind("click");
        // var myform = $('form');
        var disabled = myForm.find(':input:disabled').removeAttr('disabled');
        var data = myForm.serialize().split("&");
        disabled.attr('disabled','disabled');
        var obj={};
        for(var key in data){
            obj[decodeURIComponent(data[key].split("=")[0])] = decodeURIComponent(data[key].split("=")[1]);
        }
        $.ajax({
            url :$(this).attr("action")+'/formValidation', // json datasource
            type: "POST", 
            data: obj,
            dataType: 'json',
            success: function(html){
                if(html.ESTATUS){
                    $("#err_"+html.field_name).html(html.error)
                    $("#"+html.field_name).focus();
                    $("#main_error_msg").html(html.error)
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
                                shouldSubmitForm = true;
                                 myForm.unbind('submit');
                                    myForm.find("input[name='submit']").trigger("click");
                                    myForm.find("input[name='submit']").attr("type","button");
                            } else {
                                console.log("Form submission canceled");
                            }
                        });
                   
                }
            }
        })
    });
      $('.product-list').on('change', function() {
     $('.product-list').not(this).prop('checked', false);  
   });
});

</script>




