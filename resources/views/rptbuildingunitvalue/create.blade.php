{{ Form::open(array('url' => 'rptbuildingunitvalue','id'=>'submitBuildingUnitValueForm')) }}
   
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
        margin-left: 4%;
        margin-top: 53%;
        transform: translate(0%, -50%);
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
                    <span class="validate-err" id="err_mun_no"></span>
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
                    <span class="validate-err" id="err_loc_group_brgy_no"></span>
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
            <div class="col-md-12">
              <div class="form-group">
                    {{ Form::label('bk_building_kind_code', __('Kind'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('bk_building_kind_code',$arrKindCode,$data->bk_building_kind_code, array('class' => 'form-control select3 
                        ','id'=>'bk_building_kind_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bk_building_kind_code"></span>
                </div>
            </div>       
             <div class="col-md-12">
              <div class="form-group">
                    {{ Form::label('bt_building_type_code', __('Building Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('bt_building_type_code',$arrBulidingTypeCode,$data->bt_building_type_code, array('class' => 'form-control select3 
                        ','id'=>'bt_building_type_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bt_building_type_code"></span>
                </div>
            </div>
           
            
           
            <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('buv_minimum_unit_value', __('Minimum Unit Value'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user currency">
                        {{ Form::number('buv_minimum_unit_value', $data->buv_minimum_unit_value, array('class' => 'form-control','required'=>'required','step'=>'0.001')) }}
                        <div class="currency-sign"><span>Php</span></div>
                    </div>
                    <span class="validate-err" id="err_buv_minimum_unit_value"></span>
                </div>
            </div>


            
             <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('buv_maximum_unit_value', __('Maximum Unit Value'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user currency">
                        {{ Form::number('buv_maximum_unit_value', $data->buv_maximum_unit_value, array('class' => 'form-control','required'=>'required','step'=>'0.001','onBlur'=>'serial_no_to_validation();')) }}
                        <div class="currency-sign"><span>Php</span></div>
                    </div>
                    <span class="validate-err" id="err_buv_maximum_unit_value"></span>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <!-- <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('buv_revision_year', __('Revision Year'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('buv_revision_year', $data->buv_revision_year, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div> -->
        
      
</div>
       





        <div class="modal-footer" style="margin-bottom:100px;">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <button id="submitBuildingUnitValueFormButton" class="btn  btn-primary">{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}</button>
        </div>
    </div>
    

{{Form::close()}}
<script src="{{ asset('js/ajax_rptBuildingUnitValue.js') }}"></script>
<script src="{{ asset('js/addBuildingUnitValue.js') }}"></script>
<script type="text/javascript">

    function serial_no_to_validation(){
    'use strict';
    var buv_maximum_unit_value = document.getElementById("buv_maximum_unit_value");
    var buv_maximum_unit_value_value = document.getElementById("buv_maximum_unit_value").value;
    var buv_minimum_unit_value = document.getElementById("buv_minimum_unit_value");
    var buv_minimum_unit_value_value = document.getElementById("buv_minimum_unit_value").value;
    var numbers = /^[0-9]+$/;
    if( parseInt(buv_maximum_unit_value_value) < parseInt(buv_minimum_unit_value_value) )
    {
       document.getElementById('buv_maximum_unit_value_err').innerHTML = 'Please Enter maximum value.';
       buv_maximum_unit_value.focus();
       document.getElementById('buv_maximum_unit_value_err').style.color = "#FF0000";
       var button = document.getElementById('submit');
       button.disabled = true;
    }
    else
    {
      var button = document.getElementById('submit');
      button.disabled = false;
      document.getElementById('buv_maximum_unit_value_err').innerHTML = '';
      document.getElementById('buv_maximum_unit_value_err').style.color = "#00AF33";
      
    }
}

setTimeout(function(){ 
      var id = "{{($data->bk_building_kind_code != '')?$data->bk_building_kind_code:''}}";

      if(id > 0){
      var text = "{{(isset($data->kindText) && $data->kindText != '')?$data->kindText:'Please Select'}}";
               $("#bk_building_kind_code").select3("trigger", "select", {
    data: { id: id ,text:text}
});
            }
      var adminid = "{{($data->bt_building_type_code != '')?$data->bt_building_type_code:''}}";
      if(adminid > 0){
      var admintext = "{{(isset($data->text) && $data->text != '')?$data->text:'Please Select'}}";
               $("#bt_building_type_code").select3("trigger", "select", {
    data: { id: adminid ,text:admintext}
});
            }

}, 500);

</script>



