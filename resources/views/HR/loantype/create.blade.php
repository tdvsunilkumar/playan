{{ Form::open(array('url' => 'hr-loan-type','class'=>'formDtls','id'=>'submitLandUnitValueForm')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
        .modal.show .modal-dialog {
        transform: none;
        width: 900px;
    }
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
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hrlt_description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrlt_description',$data->hrlt_description, array('class' => 'form-control','id'=>'hrlt_description','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrlt_description"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hrlt_code', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrlt_code',$data->hrlt_code, array('class' => 'form-control','id'=>'hrlt_code','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrlt_code"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('gl_id', __('GL Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <div class="form-icon-user" id="contain_gl_id">
                                    {{ Form::select('gl_id',
                                        $gl,
                                        $data->gl_id, 
                                        array(
                                            'class' => 'form-control ajax-select',
                                            'data-url' => 'general-ledgers/getGL',
                                            'id'=>'gl_id',
                                            )) }}
                                </div>
                                <span class="validate-err" id="err_gl_id"></span>
                            </div>
                            
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('sl_id', __('SL Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <div class="form-icon-user" id="contain_sl_id">
                                    {{ Form::select('sl_id',
                                        $sl,
                                        $data->sl_id, 
                                        array(
                                            'class' => 'form-control ajax-select',
                                            'data-url' => 'subsidiary-ledgers/getSL/'.$data->gl_id,
                                            'id'=>'sl_id',
                                            )) }}
                                </div>
                                <span class="validate-err" id="err_sl_id"></span>
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
<script src="{{ asset('js/partials/select-ajax.js?v='.filemtime(getcwd().'/js/partials/select-ajax.js').'') }}"></script>
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
            url :DIR+'hr-loan-type/formValidation', // json datasource
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
<script>
$(document).ready(function(){
	$("#commonModal").find('.body').css({overflow: 'unset'})
	$('#contain_gl_id').on('change','#gl_id',(function(){
		console.log($(this).val())
		select3Ajax('sl_id','contain_sl_id','subsidiary-ledgers/getSL/'+$(this).val());
		$('#sl_id').empty()

	}))
});
</script>
<script>
    $('#hrlt_description').keyup(function() {
        text = $(this).val().toLowerCase().replace(" ", "_");
        $('#hrlt_code').val(text);
    });
</script>
  
 
           