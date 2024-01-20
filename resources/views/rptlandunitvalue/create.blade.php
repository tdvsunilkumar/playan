{{ Form::open(array('url' => 'rptlandunitvalue','id'=>'submitLandUnitValueForm')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
        .select3-container{
        z-index:  !important;
    }
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
                    {{ Form::label('loc_local_code', __('Municipality'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('loc_local_code',$arrLocalCode,$data->loc_local_code, array('class' => 'form-control 
                        ','id'=>'loc_local_code','required'=>'required','onmousedown'=>'return false;','readonly')) }}
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
              <div class="form-group" id="year">
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
                    {{ Form::label('pc_class_code', __('Class'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('pc_class_code',$arrClassCode,$data->pc_class_code, array('class' => 'form-control select3 
                        ','id'=>'pc_class_code','required'=>'required')) }}

                    </div>
                    <span class="validate-err" id="err_pc_class_code"></span>
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('ps_subclass_code', __('Subclass'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('ps_subclass_code',$arrSubclassCode,$data->ps_subclass_code, array('class' => 'form-control select3 
                        ','id'=>'ps_subclass_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_ps_subclass_code"></span>
                </div>
            </div>   
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('pau_actual_use_code', __('Actual Use'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('pau_actual_use_code',$arrActualCode,$data->pau_actual_use_code, array('class' => 'form-control select3 
                        ','id'=>'pau_actual_use_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_pau_actual_use_code"></span>
                </div>
            </div>
             <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('lav_unit_value', __('Unit Value'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user currency">
                        {{ Form::number('lav_unit_value', $data->lav_unit_value, array('class' => 'form-control','required'=>'required','step'=>'0.001')) }}
                        <div class="currency-sign"><span>Php</span></div>
                    </div>
                    <span class="validate-err" id="err_lav_unit_value"></span>
                </div>
            </div>
             <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('lav_unit_measure', __('Unit Measure'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                 
                    <div class="form-icon-user">
                        @php 
                        $lav_unit_measure=config('constants.lav_unit_measure');
                        @endphp
                        {{ Form::select('lav_unit_measure',$lav_unit_measure, $data->lav_unit_measure, array('class' => 'form-control spp_type','id'=>'lav_unit_measure','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_lav_unit_measure"></span>
                </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('lav_location_name', __('Location Name'),['class'=>'form-label']) }}
        
                    <div class="form-icon-user">
                        {{ Form::text('lav_location_name', $data->lav_location_name, array('class' => 'form-control')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
</div>
		<div class="modal-footer" style="margin-bottom:100px;">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
             <button type="button" id="submitlandunitvalueformButton" class="btn  btn-primary" >{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}</button>
           <!--  <input type="button" name="submit" id="submitlandunitvalueformButton" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_rptLandUnitValue.js') }}?rand={{ rand(000,999) }}"></script>
<script src="{{ asset('js/addLandUnitValue.js') }}?rand={{ rand(000,999) }}"></script>
<script src="{{ asset('js/ajax_validation.js') }}"></script> 

<script type="text/javascript">
    setTimeout(function(){ 
      var id = "{{($data->pc_class_code != '')?$data->pc_class_code:''}}";
      if(id > 0){
      var text = "{{(isset($data->class) && $data->class != '')?$data->class:'Please Select'}}";
               $("#pc_class_code").select3("trigger", "select", {
    data: { id: id ,text:text}
});
            }
      var subclassid = "{{($data->ps_subclass_code != '')?$data->ps_subclass_code:''}}";
      if(subclassid > 0){
      var subclasstext = "{{(isset($data->subClass) && $data->subClass != '')?$data->subClass:'Please Select'}}";
               $("#ps_subclass_code").select3("trigger", "select", {
    data: { id: subclassid ,text:subclasstext}
});
            }
      var actualuseid = "{{($data->pau_actual_use_code != '')?$data->pau_actual_use_code:''}}";
      if(actualuseid > 0){
      var actualusetext = "{{(isset($data->actualUses) && $data->actualUses != '')?$data->actualUses:'Please Select'}}";
               $("#pau_actual_use_code").select3("trigger", "select", {
    data: { id: actualuseid ,text:actualusetext}
});
            }      

}, 500);
</script>



