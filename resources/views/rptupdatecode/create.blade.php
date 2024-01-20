{{ Form::open(array('url' => 'rptupdatecode','id'=>'submitLandUnitValueForm')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<style>
    .modal-xl {
        max-width: 1350px !important;
    }
    .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 100%;
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
    .accordion-button{
        margin-bottom: 12px;
    }
    .form-group{
        margin-bottom: unset;
    }
    .form-group label {
        font-weight: 600;
        font-size: 12px;
    }
    .form-control, .custom-select{
        padding-left: 5px;
        font-size: 12px;
    }
    .pt10{
        padding-top:10px;
    }
    .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #8080802e;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .choices__inner {
        min-height: 35px;
        padding:5px ;
        padding-left:5px;
    }
    
 </style>

    <div class="modal-body">

         <div class="row">
             <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample2">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingtwo">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                        <h6 class="sub-title accordiantitle">{{__('Details')}}</h6>
                        </button>
                    </h6>
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                        <div class="row"  id="otheinfodiv">
               
             
             <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('uc_code', __('Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('uc_code', $data->uc_code, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_uc_code"></span>
                </div>
            </div>
              
                        <div class="col-md-12">
               <div class="form-group">
                    {{ Form::label('uc_description', __('Declaration'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('uc_description', $data->uc_description, array('class' => 'form-control','value'=>'Street & Number','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_uc_description"></span>
                </div>
            </div>    
           
           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



       <div class="row">
             <div class="col-lg-6 col-md-6 col-sm-6" id="accordionFlushExample2">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingtwo">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                        <h6 class="sub-title accordiantitle">{{__('Usage')}}</h6>
                        </button>
                    </h6>
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                        <div class="row"  id="otheinfodiv">
              
             <div class="col-md-12">
               <div class="d-flex radio-check"><br>
                                                <div class="form-check form-check-inline form-group">
                                                    {{ Form::checkbox('uc_usage_land', '1', ($data->uc_usage_land)?true:false, array('id'=>'uc_usage_land','class'=>'form-check-input uc_usage_building code')) }}
                                                    {{ Form::label('Land Usage', __('Land'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
            </div>
             <div class="col-md-12">
               <div class="d-flex radio-check"><br>
                                                <div class="form-check form-check-inline form-group">
                                                    {{ Form::checkbox('uc_usage_building', '1', ($data->uc_usage_building)?true:false, array('id'=>'uc_usage_building','class'=>'form-check-input uc_usage_building code')) }}
                                                    {{ Form::label('Building Usage', __('Building'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
            </div>
             <div class="col-md-12">
                <div class="d-flex radio-check"><br>
                                                <div class="form-check form-check-inline form-group">
                                                    {{ Form::checkbox('uc_usage_machine', '1', ($data->uc_usage_machine)?true:false, array('id'=>'uc_usage_machine','class'=>'form-check-input uc_usage_machine code')) }}
                                                    {{ Form::label('Machine Usage', __('Machineries'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
            </div>
               
            <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample2" style="padding: 1px;">
                <div class="accordion accordion-flush">
                    <div class="accordion-item" style="border:0px;">
                        <h6 class="accordion-header" id="flush-headingtwo">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                            <h6 class="sub-title accordiantitle">{{__('For New/Fresh Record Only')}}</h6>
                            </button>
                        </h6>
                        
                            <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                                <div class="row"  id="otheinfodiv">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            
                                            </div>
                                    </div>
                                <div class="col-md-12">
                                        <div class="d-flex radio-check"><br>
                                            <div class="form-check form-check-inline form-group">
                                                {{ Form::checkbox('uc_new_fresh', '1', ($data->uc_new_fresh)?true:false, array('id'=>'uc_new_fresh','class'=>'form-check-input printupdate code')) }}
                                                {{ Form::label('Change Property Ownership', __('Yes'),['class'=>'form-label']) }}
                                            </div>
                                        </div>
                                </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample2" style="padding: 1px;">
                <div class="accordion accordion-flush">
                    <div class="accordion-item" style="border:0px;">
                        <h6 class="accordion-header" id="flush-headingtwo">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                            <h6 class="sub-title accordiantitle">{{__('Direct Cancellation')}}</h6>
                            </button>
                        </h6>
                        
                            <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                                <div class="row"  id="otheinfodiv">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            
                                            </div>
                                    </div>
                                <div class="col-md-12">
                                        <div class="d-flex radio-check"><br>
                                            <div class="form-check form-check-inline form-group">
                                                {{ Form::checkbox('direct_cancellation', '1', ($data->direct_cancellation)?true:false, array('id'=>'direct_cancellation','class'=>'form-check-input printupdate code')) }}
                                                {{ Form::label('Change Property Ownership', __('Yes'),['class'=>'form-label']) }}
                                            </div>
                                        </div>
                                </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            



               </div>
               
               
                        
                    </div>
                </div>
            </div>
        </div>



        <div class="col-lg-6 col-md-6 col-sm-6" id="accordionFlushExample2">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingtwo">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                        <h6 class="sub-title accordiantitle">{{__('Options')}}</h6>
                        </button>
                    </h6>
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                        <div class="row"  id="otheinfodiv">
                    <div class="col-md-12">
                       <div class="form-group">
                           
                        </div>
                    </div>
                    
                               <div class="col-md-12">
                                    <div class="d-flex radio-check"><br>
                                        <div class="form-check form-check-inline form-group">
                                            {{ Form::checkbox('uc_change_property_of_ownership', '1', ($data->uc_change_property_of_ownership)?true:false, array('id'=>'uc_change_property_of_ownership','class'=>'form-check-input uc_change_property_of_ownership code')) }}
                                            {{ Form::label('Change Property Ownership', __('Change Property Ownership'),['class'=>'form-label']) }}
                                        </div>
                                     </div>
                               </div>
                                 <div class="col-md-12">
                                     <div class="d-flex radio-check"><br>
                                            <div class="form-check form-check-inline form-group">
                                                {{ Form::checkbox('uc_cancel_existing_faas', '1', ($data->uc_cancel_existing_faas)?true:false, array('id'=>'uc_cancel_existing_faas','class'=>'form-check-input uc_cancel_existing_faas code')) }}
                                                {{ Form::label('Cancel Exisiting Faas', __('Cancel Existing FAAS'),['class'=>'form-label']) }}
                                            </div>
                                        </div>
                                    
                                </div>
                                    <div class="col-md-12">
                                                <div class="d-flex radio-check"><br>
                                                    <div class="form-check form-check-inline form-group">
                                                        {{ Form::checkbox('uc_consolidate_existing_faas', '1', ($data->uc_consolidate_existing_faas)?true:false, array('id'=>'uc_consolidate_existing_faas','class'=>'form-check-input uc_consolidate_existing_faas code')) }}
                                                        {{ Form::label('Consolidate Existing Faas', __('Consolidate Existing FAAS'),['class'=>'form-label']) }}
                                                    </div>
                                                </div>
                                    </div>
                                       </div>
               
                                        <div class="col-md-12">
                                             <div class="d-flex radio-check"><br>
                                                <div class="form-check form-check-inline form-group">
                                                    {{ Form::checkbox('uc_subdivide_existing_faas', '1', ($data->uc_subdivide_existing_faas)?true:false, array('id'=>'uc_subdivide_existing_faas','class'=>'form-check-input uc_subdivide_existing_faas code')) }}
                                                    {{ Form::label('Subdivide Existing Faas', __('Subdivide Existing FAAS'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                             <div class="d-flex radio-check"><br>
                                                <div class="form-check form-check-inline form-group">
                                                    {{ Form::checkbox('uc_cancel_only_one_existing_faas', '1', ($data->uc_cancel_only_one_existing_faas)?true:false, array('id'=>'uc_cancel_only_one_existing_faas','class'=>'form-check-input uc_cancel_only_one_existing_faas code')) }}
                                                    {{ Form::label('Cancel Only one Existing Faas', __('Cancel Only one Existing FAAS'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                    </div>
                                    <div class="col-md-12">
                                         <div class="d-flex radio-check"><br>
                                                <div class="form-check form-check-inline form-group">
                                                    {{ Form::checkbox('uc_cease_tax_declaration', '1', ($data->uc_cease_tax_declaration)?true:false, array('id'=>'uc_cease_tax_declaration','class'=>'form-check-input uc_cease_tax_declaration code')) }}
                                                    {{ Form::label('Cease Tax Declaration', __('Cease Tax Declaration'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                    </div>

                                    <div class="col-md-12">
                                         <div class="d-flex radio-check"><br>
                                                <div class="form-check form-check-inline form-group">
                                                    {{ Form::checkbox('uc_revised_tax_declaration', '1', ($data->uc_revised_tax_declaration)?true:false, array('id'=>'uc_revised_tax_declaration','class'=>'form-check-input uc_revised_tax_declaration code')) }}
                                                    {{ Form::label('Revised TD', __('Revised TD'),['class'=>'form-label']) }}
                                                </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                         <div class="d-flex radio-check"><br>
                                                <div class="form-check form-check-inline form-group">
                                                    
                                                </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                         <div class="d-flex radio-check"><br>
                                                <div class="form-check form-check-inline form-group">
                                                    
                                                </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                         <div class="d-flex radio-check"><br>
                                                <div class="form-check form-check-inline form-group">
                                                    
                                                </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                         <div class="d-flex radio-check"><br>
                                                <div class="form-check form-check-inline form-group">
                                                   
                                                </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                         <div class="d-flex radio-check"><br>
                                                <div class="form-check form-check-inline form-group">
                                                   
                                                </div>
                                        </div>
                                    </div>
                    </div>
                </div>
            </div>
        </div>

      

        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('js/addUpdateCode.js') }}"></script>
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
            url :DIR+'rptupdatecode/formValidation', // json datasource
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


