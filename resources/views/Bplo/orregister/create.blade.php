{{ Form::open(array('url' => 'ctoorregister','class'=>'formDtls','id'=>'submitLandUnitValueForm')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}

    <style type="text/css">
    .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 50%;
        pointer-events: auto;
        background-color: #ffffff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 15px;
        outline: 0;
	   float: left;
	   margin-left: 50%;
	   margin-top: 30%;
	  transform: translate(-50%, -50%);
    }
</style>


            <div class="modal-body">
                    <div class="row">
                        <span class="validate-err" id="err_rangeExistError"></span>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('cpot_id', __('OR Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('cpot_id') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::select('cpot_id',$arrOrtypes,$data->cpot_id, array('class' => 'form-control select3','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_cpot_id"></span>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('shortname', __('Short Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('shortname') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('shortname','', array('class' => 'form-control disabled-field','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_shortname"></span>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('ora_from', __('OR Series From'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ora_from') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::number('ora_from',$data->ora_from, array('class' => 'form-control','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_ora_from"></span>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('ora_to', __('OR Series To'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ora_to') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::number('ora_to',$data->ora_to, array('class' => 'form-control','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_ora_to"></span>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('coa_no', __('Tag No'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('coa_no') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('coa_no', $data->coa_no, array('class' => 'form-control','maxlength'=>'100','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_coa_no"></span>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('or_count', __('OR Count'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('or_count') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('or_count', $data->or_count, array('class' => 'form-control disabled-field','maxlength'=>'100','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_or_count"></span>
                            </div>
                        </div>
                        
                        @if(($data->id) > 0)
                        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample3">
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading3">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Uploads")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapse3" class="accordion-collapse collapse" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    {{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('file','documentname','',array('class'=>'form-control','id'=>'documentname'))}}  
                                                    </div>
                                                    <span class="validate-err" id="err_document"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <button type="button" style="float:right;margin-top: 24px;" class="btn btn-primary" id="uploadAttachmentbtn">Upload File</button>
                                                </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6"></div>
                                                
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Document Title</th>
                                                                <th>Attachment</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <thead id="DocumentDtls">
                                                             <?php echo $data->document_details?>
                                                            @if(empty($data->document_details))
                                                            <tr>
                                                                <td colspan="3"><i>No results found.</i></td>
                                                            </tr>
                                                            @endif 
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    @endif
                    </div> 
                   
                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <input type="submit" name="submit" id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                        <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
                    </div>
            </div>
    {{Form::close()}}
    <script src="{{ asset('js/ajax_validation.js') }}"></script>
    <script src="{{ asset('js/Bplo/add_orregister.js') }}?rand={{ rand(000,999) }}"></script>
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
            url :DIR+'ctoorregister/formValidation', // json datasource
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