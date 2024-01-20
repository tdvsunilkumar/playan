{{ Form::open(array('url' => 'revisionyear','class'=>'formDtls','id'=>'submitLandUnitValueForm')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style>
    
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
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('rvy_revision_year', __('Year'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('rvy_revision_year') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::number('rvy_revision_year', $data->rvy_revision_year, array('class' => 'form-control','maxlength'=>'4','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_rvy_revision_year"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('rvy_revision_code', __('Revision Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('rvy_revision_code') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('rvy_revision_code', $data->rvy_revision_code, array('class' => 'form-control','maxlength'=>'30','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_rvy_revision_code"></span>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-6">
                           <div class="form-group">
                                {{ Form::label('rvy_city_assessor_code', __('Assessor'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    
                                <div class="form-icon-user">
                                    {{ Form::select('rvy_city_assessor_code',$arrClassCode,$data->rvy_city_assessor_code, array('class' => 'form-control select3','id'=>'rvy_city_assessor_code','required'=>'required')) }}
                                    
                                </div>
                                <span class="validate-err" id="err_rvy_city_assessor_code"></span>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                    {{ Form::label('rvy_city_assessor_assistant_code', __('Assistant Assessor'),['class'=>'form-label']) }}
                        
                                    <div class="form-icon-user">
                                        {{ Form::select('rvy_city_assessor_assistant_code',$arrClassCode,$data->rvy_city_assessor_assistant_code, array('class' => 'form-control select3','id'=>'rvy_city_assessor_assistant_code')) }}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rvy_city_assessor_assistant_code"></span>
                                </div>
                        </div> 
                    
                   <!--  <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('display_for_bplo', __('BPLO System'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('display_for_bplo',array('0' =>'No','1' =>'Yes'), $data->display_for_bplo, array('class' => 'form-control spp_type','id'=>'display_for_bplo','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bsf_tax_schedule"></span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('display_for_rpt', __('RPT System'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('display_for_rpt',array('0' =>'No','1' =>'Yes'), $data->display_for_rpt, array('class' => 'form-control spp_type','id'=>'display_for_rpt','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bsf_tax_schedule"></span>
                </div>
            </div> -->
            </div>
                <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExampl3">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading3" >
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3" >
                                        <h6 class="sub-title accordiantitle" >
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon" >{{__("Applicable Tax Scenario")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                
                                <div id="flush-collapse3" class="accordion-collapse collapse show" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        @if(($data->id)>0)
                                                            @if($data->has_tax_basic == 1)
                                                            {{ Form::checkbox('has_tax_basic','1', ($data->has_tax_basic)?true:false, array('id'=>'has_tax_basic','class'=>'form-check-input new','style'=>'margin-top:2px','readonly' => ($data->has_tax_basic) ? 'readonly' : null,
                                                             'onclick' => ($data->has_tax_basic) ? 'return false;' : null)) }} {{Form::label('',__('Basic Tax'),['class'=>'form-label'])}}
                                                            @else
                                                            {{ Form::checkbox('has_tax_basic','1', ($data->has_tax_basic)?true:false, array('id'=>'has_tax_basic','class'=>'form-check-input new','style'=>'margin-top:2px')) }} {{Form::label('',__('Basic Tax'),['class'=>'form-label'])}}
                                                            @endif
                                                            @else
                                                            {{ Form::checkbox('has_tax_basic','1', ($data->has_tax_basic)?false:true, array('id'=>'has_tax_basic','class'=>'form-check-input new','style'=>'margin-top:2px','readonly' =>'readonly','onclick' => 'return false;')) }} {{Form::label('',__('Basic Tax'),['class'=>'form-label'])}}
                                                        @endif

                                                    </div>
                                                        <span class="validate-err" id="err_rvy_city_assessor_assistant_code"></span>
                                                </div>
                                            </div> 
                                           <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        @if(($data->id)>0)
                                                            @if($data->has_tax_sef == 1)
                                                            {{ Form::checkbox('has_tax_sef','1', ($data->has_tax_sef)?true:false, array('id'=>'has_tax_sef','class'=>'form-check-input new','style'=>'margin-top:2px','readonly' => ($data->has_tax_sef) ? 'readonly' : null,
                                                             'onclick' => ($data->has_tax_sef) ? 'return false;' : null)) }}  {{Form::label('',__('SEF[Special Education Fund]'),['class'=>'form-label'])}}
                                                            @else
                                                            {{ Form::checkbox('has_tax_sef','1', ($data->has_tax_sef)?true:false, array('id'=>'has_tax_sef','class'=>'form-check-input new','style'=>'margin-top:2px')) }} {{Form::label('',__('SEF[Special Education Fund]'),['class'=>'form-label'])}}
                                                            @endif
                                                            @else
                                                            {{ Form::checkbox('has_tax_sef','1', ($data->has_tax_sef)?false:true, array('id'=>'has_tax_sef','class'=>'form-check-input new','style'=>'margin-top:2px','readonly' =>'readonly','onclick' => 'return false;')) }} {{Form::label('',__('SEF[Special Education Fund]'),['class'=>'form-label'])}}
                                                        @endif
                                                            
                                                    </div>
                                                        <span class="validate-err" id="err_rvy_city_assessor_assistant_code"></span>
                                                </div>
                                            </div> 
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        @if(($data->id)>0)
                                                           
                                                            {{ Form::checkbox('has_tax_sh','1', ($data->has_tax_sh)?true:false, array('id'=>'has_tax_sh','class'=>'form-check-input new','style'=>'margin-top:2px')) }} {{Form::label('',__('SHT[Socialize Housing Tax]'),['class'=>'form-label'])}}
                                                        @else
                                                            {{ Form::checkbox('has_tax_sh','1', ($data->has_tax_sh)?false:true, array('id'=>'has_tax_sh','class'=>'form-check-input new','style'=>'margin-top:2px')) }} {{Form::label('',__('SHT[Socialize Housing Tax]'),['class'=>'form-label'])}}
                                                        @endif
                                                            
                                                    </div>
                                                        <span class="validate-err" id="err_rvy_city_assessor_assistant_code"></span>
                                                </div>
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
                        <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
                    </div>
            </div>
    {{Form::close()}}
    

   <script src="{{ asset('js/ajax_validation.js') }}"></script> 
   <script src="{{ asset('js/add_revisionyear.js') }}"></script>
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
            url :DIR+'revisionyear/formValidation', // json datasource
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
   <script type="text/javascript">
   setTimeout(function(){ 
      var id = "{{($data->rvy_city_assessor_code != '')?$data->rvy_city_assessor_code:''}}";

      if(id > 0){
      var text = "{{(isset($data->assesser) && $data->assesser != '')?$data->assesser:'Please Select'}}";
               $("#rvy_city_assessor_code").select3("trigger", "select", {
    data: { id: id ,text:text}
});
            }
      var adminid = "{{($data->rvy_city_assessor_assistant_code != '')?$data->rvy_city_assessor_assistant_code:''}}";
      if(adminid > 0){
      var admintext = "{{(isset($data->assassesser) && $data->assassesser != '')?$data->assassesser:'Please Select'}}";
               $("#rvy_city_assessor_assistant_code").select3("trigger", "select", {
    data: { id: adminid ,text:admintext}
});
            }

}, 500);
   </script>