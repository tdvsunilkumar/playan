{{ Form::open(array('url' => 'assessmentlevel','id'=>'submitAssessementLevelForm')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('pc_class_code',$data->pc_class_code,array('id' => 'pc_class_code')) }}
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
        margin-left: 4%;
        margin-top: 53%;
        transform: translate(0%, -50%);
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
    .field-requirement-details-status label{margin-top: 7px;}
    #flush-collapsetwo{
/*        padding-bottom: 80px;*/
}

</style>
    <div class="modal-body" style="overflow-x: hidden;">
         <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('mun_no', __('Municipality'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('mun_no',$arrLocalCode,$data->mun_no, array('class' => 'form-control 
                        ','id'=>'mun_no','required'=>'required','onmousedown'=>'return false;','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_loc_local_code"></span>
                </div>
            </div>       
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('loc_group_brgy_no', __('Location Group'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('loc_group_brgy_no',$arrBrgyCode,$data->loc_group_brgy_no, ($brgyId != null ) ? array('class' => 'form-control 
                        ','id'=>'loc_group_brgy_no','required'=>'required','onmousedown'=>'return false;','readonly') : array('class' => 'form-control select3 
                        ','id'=>'loc_group_brgy_no','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_rvy_revision_year"></span>
                </div>
            </div>
            @if($data->rvy_revision_year)
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('rvy_revision_year', __('Revision'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('rvy_revision_year',$arrRevisionCode,$data->rvy_revision_year, array('class' => 'form-control 
                        ','id'=>'rvy_revision_year','onmousedown'=>'return false;','readonly')) }}

                    </div>
                    <span class="validate-err"  id="err_rvy_revision_year"></span>
                </div>
            </div>
            
            @else
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('rvy_revision_year', __('Revision'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('rvy_revision_year',$arrRevisionCode,$data->rvy_revision_year, array('class' => 'form-control select3 
                        ','id'=>'rvy_revision_year','selected')) }}

                    </div>
                    <span class="validate-err"  id="err_rvy_revision_year"></span>
                </div>
            </div>
            @endif
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('pk_code', __('Kind'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('pk_code',$arrpkCode,$data->pk_code, array('class' => 'form-control select3 
                        ','id'=>'pk_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tax_class_id"></span>
                </div>
            </div>       
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('pc_class_desc', __('Class'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::text('pc_class_desc','',array('class' => 'form-control','required'=>'required','readonly'=>'true','id'=>'pc_class_desc')) }}

                       
                    </div>
                    <span class="validate-err" id="err_pk_code"></span>
                </div>
            </div>
             
            <div class="col-md-4">
                
                    @if($data->pk_code==3)
                    <div class="form-group" id="myDiv" style="display:none;">
                        {{ Form::label('ps_subclass_desc', __('Actual Use'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                           {{ Form::text('ps_subclass_desc','',array('class' => 'form-control','required'=>'required','readonly'=>'true')) }}
                        </div>
                        <span class="validate-err" id="err_ps_subclass_code"></span>
                    </div>
                    @else
                  <div class="form-group" id="myDiv">
                        {{ Form::label('ps_subclass_desc', __('Actual Use'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                           {{ Form::text('ps_subclass_desc','',array('class' => 'form-control','required'=>'required','readonly'=>'true')) }}
                        </div>
                        <span class="validate-err" id="err_ps_subclass_code"></span>
                    </div>
                    @endif
                

            </div>   
            
            
            <div class="col-md-12">
              <div class="form-group">
                    @if($data->pk_code==3)
                    {{ Form::label('classcode', __('Class Description'),['class'=>'form-label','id'=>'pau_actual_use_codelabel']) }}<span class="text-danger">*</span>
                    @else
                    {{ Form::label('pau_actual_use_code', __('Class-Actual Use Description'),['class'=>'form-label','id'=>'pau_actual_use_codelabel']) }}<span class="text-danger">*</span>
                    @endif
                    <div class="form-icon-user">
                        
                        @if($data->pk_code==3)
                        <div id="myDivClass" >
                        {{ Form::select('classcode',$arrClassCode,$data->pc_class_code, array('class' => 'form-control select3 
                        ','id'=>'classcode')) }}
                        </div>
                        <div id="myDivactual" style="display:none;">
                            {{ Form::select('pau_actual_use_code',[],$data->pau_actual_use_code, array('class' => 'form-control select3 
                            ','id'=>'pau_actual_use_code')) }}
                        </div>
                        @else
                        <div id="myDivClass" style="display:none;">
                        {{ Form::select('classcode',$arrClassCode,$data->pc_class_code, array('class' => 'form-control select3 
                        ','id'=>'classcode')) }}
                        </div>
                        <div id="myDivactual" >
                            {{ Form::select('pau_actual_use_code',[],'', array('class' => 'form-control 
                            ','id'=>'pau_actual_use_code')) }}
                        </div>
                        @endif
                       
                    </div>
                    <span class="validate-err" id="err_tax_class_id"></span>
                </div>
            </div>
          <!--   <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('al_minimum_unit_value', __('Minimum Value'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                     <div class="form-icon-user currency">
                        {{ Form::number('al_minimum_unit_value',$data->al_minimum_unit_value,array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
                        <div class="currency-sign"><span>Php</span></div>
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div> -->
            
             <!-- <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('al_maximum_unit_value', __('Maximum Value'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user currency">
                        {{ Form::number('al_maximum_unit_value',$data->al_maximum_unit_value,array('class' => 'form-control','required'=>'required','step'=>'0.01','onBlur'=>'serial_no_to_validation();')) }}
                    <div class="currency-sign"><span>Php</span></div>
                </div>
                    <span class="validate-err" id="al_maximum_unit_value_err"></span>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('al_assessment_level', __('Assessment Level(%)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('al_assessment_level',$data->al_assessment_level,array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div> -->
           
        </div>
        
    <div class="row" >
       <div class="row field-requirement-details-status">
        <div class="col-lg-3 col-md-3 col-sm-3">
            {{Form::label('minimum_unit_value',__('Minimum Value'),['class'=>'form-label'])}}
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3">
            {{Form::label('maximum_unit_value',__('Maximum Value'),['class'=>'form-label'])}}
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3">
            {{Form::label('assessment_level',__('Assessment Level(%)'),['class'=>'form-label numeric'])}}
        </div>
         <div class="col-lg-1 col-md-1 col-sm-1">
            {{Form::label('is_active',__('Status'),['class'=>'form-label numeric'])}}
        </div>
       <div class="col-lg-1 col-md-1 col-sm-1">
            <input type="button" id="btn_addmore_nature" class="btn btn-success" value="Add More" style="padding: 0.4rem 0.76rem !important;">
        </div>
        </div>
   
   
    <span class="natureDetails nature-details" id="natureDetails">
       @php $i=0; @endphp
        @foreach($arrNature as $key=>$val)
        <div class="row removenaturedata pt10">
             <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="form-group">
                    <div class="form-icon-user currency">
                         {{ Form::hidden('relationId[]',$val['id'], array('id' => 'relationId')) }}

                        {{ Form::number('minimum_unit_value[]',$val['minimum_unit_value'],array('class' => 'form-control min','required'=>'required','step'=>'0.001','id'=>'minimum_unit_value')) }}
                        <div class="currency-sign"><span>Php</span></div>

                    </div>
                    <span class="validate-err" id="err_minimum_unit_value{{$i}}"></span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="form-group">
                    <div class="form-icon-user currency">
                        {{ Form::number('maximum_unit_value[]',$val['maximum_unit_value'],array('class' => 'form-control max','required'=>'required','id'=>'maximum_unit_value','step'=>'0.001','onBlur'=>'serial_no_to_validation();')) }}

                        <div class="currency-sign"><span>Php</span></div>
                    </div>
                    <span class="validate-err" id="err_maximum_unit_value{{$i}}"></span>
                </div>
            </div>
           
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                    <div class="form-icon-user">
                       {{ Form::number('assessment_level[]',$val['assessment_level'],array('class' => 'form-control','required'=>'required','step'=>'0.001')) }}
                    </div>
                    <span class="validate-err" id="err_assessment_level{{$i}}"></span>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                    <div class="form-icon-user">
                        {{ Form::select('re_is_active[]',array('1'=>'Active','0' =>'InActive'), '', array('class' => 'form-control spp_type','id'=>'re_is_active')) }}
                    </div>
                </div>
            </div>
            
            <div class="col-sm-1">
                <input type="button" name="btn_cancel_nature" class="btn btn-success btn_cancel_nature delete" id="" value="Delete" style="padding: 0.4rem 1rem !important;" data-id="{{$val['id']}}">
            </div>
           
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#requirement_id<?=$i?>").select3({dropdownAutoWidth : false,dropdownParent: $("#natureDetails")});
                });
            </script>
         
        </div>

       @php $i++; @endphp
        @endforeach 

    </span>
    
</div>
        <div class="modal-footer" style="margin-bottom:100px;">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <button id="submitAssessementLevelFormButton" class="btn  btn-primary">{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}</button>
        </div>
    </div>
    <input type="hidden" name="dynamicid" value="3" id="dynamicid">
       
{{Form::close()}}
@php 
$i=(count($arrNature)>0)?count($arrNature):0;
@endphp

<!-- <script type="text/javascript">

    function serial_no_to_validation2(){
    'use strict';
    var maximum_unit_value1 = document.getElementById("maximum_unit_value1");
    var maximum_unit_value_value1 = document.getElementById("maximum_unit_value1").value;
    var minimum_unit_value1 = document.getElementById("minimum_unit_value1");
    var minimum_unit_value_value1 = document.getElementById("minimum_unit_value1").value;
    var numbers = /^[0-9]+$/;
    
    if( maximum_unit_value_value1 < minimum_unit_value_value1 )
    {
       document.getElementById('maximum_unit_value_err1').innerHTML = 'Please Enter maximum value.';
       maximum_unit_value1.focus();
       document.getElementById('maximum_unit_value_err1').style.color = "#FF0000";
    }
    else
    {
      document.getElementById('maximum_unit_value_err1').innerHTML = '';
      document.getElementById('maximum_unit_value_err1').style.color = "#00AF33";
      
    }
}


</script> -->
<div id="hidennatureHtml" class="hide">
    <div class="removenaturedata row pt10">
        <div class="col-lg-3 col-md-3 col-sm-3">
            <div class="form-group">
                <div class="form-icon-user currency">

                    {{ Form::hidden('relationId[]','', array('id' => 'relationId'.$i)) }}

                    {{ Form::number('minimum_unit_value[]','',array('class' => 'form-control min','required'=>'required','step'=>'0.001','id'=>'minimum_unit_value')) }}
                    <div class="currency-sign"><span>Php</span></div>

                </div>
                <span class="validate-err minimum_unit_value" id="err_minimum_unit_value{{$i}}"></span>
            </div>
        </div>
        
        

        <div class="col-lg-3 col-md-3 col-sm-3">
            <div class="form-group">
                <div class="form-icon-user currency">
                    {{ Form::number('maximum_unit_value[]','',array('class' => 'form-control max','required'=>'required','step'=>'0.001','id'=>'maximum_unit_value'.$i,'onBlur'=>'serial_no_to_validation();')) }}
                    <div class="currency-sign"><span>Php</span></div>
                </div>
                 <span class="validate-err maximum_unit_value" id="err_maximum_unit_value{{$i}}"></span>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                   {{ Form::number('assessment_level[]','',array('class' => 'form-control','required'=>'required','step'=>'0.001')) }}
                </div>
                <span class="validate-err assessment_level" id="err_assessment_level{{$i}}"></span>
            </div>
        </div>


        
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                    {{ Form::select('re_is_active[]',array('1'=>'Active','0' =>'InActive'), '', array('class' => 'form-control spp_type','id'=>'re_is_active')) }}
                </div>
            </div>
        </div>
        <div class="col-sm-1">
            <input type="button" name="btn_cancel"  class="btn btn-success btn_cancel_nature delete" cid="" value="Delete" style="padding: 0.4rem 1rem !important;">
        </div>
    </div>
</div> 
<script src="{{ asset('js/ajax_assessementLevel.js') }}"></script>
<script src="{{ asset('js/add_rptAssessmentLevel.js') }}?rand={{ rand(000,999) }}"></script>
<script type="text/javascript">


    function serial_no_to_validation(){
    'use strict';
    var maximum_unit_value = document.getElementById("maximum_unit_value");
    var maximum_unit_value_value = document.getElementById("maximum_unit_value").value;
    var minimum_unit_value = document.getElementById("minimum_unit_value");
    var minimum_unit_value_value = document.getElementById("minimum_unit_value").value;
    var numbers = /^[0-9]+$/;

    if( parseInt(maximum_unit_value_value) < parseInt(minimum_unit_value_value) )
    {
       document.getElementById('maximum_unit_value_err').innerHTML = 'Please Enter maximum value.';
       maximum_unit_value.focus();
       document.getElementById('maximum_unit_value_err').style.color = "#FF0000";
       var button = document.getElementById('submit');
       button.disabled = true;
    }
    else
    {
       var button = document.getElementById('submit');
       button.disabled = false;
      document.getElementById('maximum_unit_value_err').innerHTML = '';
      document.getElementById('maximum_unit_value_err').style.color = "#00AF33";
      
    }
}
// function serial_no_to_validation(){

//  var error = document.getElementById("error-message");
//   var inputs = document.querySelectorAll('input[type="number"]');
//   var maxValues = [];
//   var minValues = [];
//   var values = [];
//   inputs.forEach(function(input) {
//     maxValues.push(parseInt(input.max));
//     minValues.push(parseInt(input.min));
//     values.push(parseInt(input.value));
//   });
//   var valid = true;
//   for (var i = 0; i < inputs.length; i++) {
//     if (maxValues[i] < minValues[i]) {
//       error.innerHTML = "Error: Max value cannot be less than min value for field " + (i+1) + ".";
//       valid = false;
//       break;
//     } else if (values[i] > maxValues[i] || values[i] < minValues[i]) {
//       error.innerHTML = "Error: Invalid input value for field " + (i+1) + ".";
//       valid = false;
//       break;
//     }
//   }
//   if (valid) {
//     error.innerHTML = "";
//     // Do something with the input values
//   }
// }

setTimeout(function(){ 
      var id = "{{($data->id != '')?$data->id:''}}";
      var pkId = "{{($data->pk_code != '')?$data->pk_code:''}}";
      var classCode = "{{($data->pc_class_code != '')?$data->pc_class_code:''}}";
      if(id > 0 && pkId == 3){
      var text = "{{(isset($data->text) && $data->text != '')?$data->text:'Please Select'}}";
               $("#classcode").select3("trigger", "select", {
    data: { id: classCode ,text:text}
});
            }
      if(id > 0 && pkId != 3){
      var text = "{{(isset($data->text) && $data->text != '')?$data->text:'Please Select'}}";
      var auId =  "{{(isset($data->pau_actual_use_code) && $data->pau_actual_use_code != '')?$data->pau_actual_use_code:''}}";
               $("#pau_actual_use_code").select3("trigger", "select", {
    data: { id: auId ,text:text}
});
            }      

}, 500);

</script>



