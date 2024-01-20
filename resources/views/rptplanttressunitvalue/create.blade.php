{{ Form::open(array('url' => 'rptplanttressunitvalue','id'=>'submitPlantTreeUnitValueForm')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('pc_class_code',(isset($data->pc_class_code) && $data->pc_class_code != '')?$data->pc_class_code:'',array('id' => 'pc_class_code')) }}
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
   
   
 </style>
    <div class="modal-body" style="overflow-x: hidden;">
        <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('loc_local_code', __('Municipality'),['class'=>'form-label']) }}<span class="text-danger">*</span>
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
                    <span class="validate-err" id="err_rvy_revision_year"></span>
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
                    <span class="validate-err" id="err_rvy_revision_year"></span>
                </div>
            </div>
            @endif
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('pt_ptrees_code', __('Plant/Trees'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('pt_ptrees_code',$arrPlantTressCode,$data->pt_ptrees_code, array('class' => 'form-control select3 
                        ','id'=>'pt_ptrees_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_pt_ptrees_code"></span>
                </div>
            </div> 
                  
           <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('pc_class_desc', __('Class'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::text('pc_class_desc','',array('class' => 'form-control','required'=>'required','readonly'=>'true')) }}
                     
                       
                    </div>
                    <span class="validate-err" id="err_pc_class_desc"></span>
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('ps_subclass_desc', __('Subclass'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::text('ps_subclass_desc','',array('class' => 'form-control','required'=>'required','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_ps_subclass_desc"></span>
                </div>
            </div>   
            
             <div class="col-md-8">
              <div class="form-group" id="ps_subclass_code_div">
                    {{ Form::label('ps_subclass_code', __('Class-Subclass Use Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('ps_subclass_code',$arrSubclassCode,$data->ps_subclass_code, array('class' => 'form-control select3 
                        ','id'=>'ps_subclass_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_ps_subclass_code"></span>
                </div>
            </div>

            
            <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('ptuv_unit_value', __('Unit Value'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user currency">
                        {{ Form::text('ptuv_unit_value', $data->ptuv_unit_value, array('class' => 'form-control decimalvalue','required'=>'required')) }}
                         <div class="currency-sign"><span>Php</span></div>
                    </div>
                    <span class="validate-err" id="err_ptuv_unit_value"></span>
                </div>
            </div>
            <br><br><br><br><br><br><br><br><br>
        </div>
        <div class="modal-footer" style="margin-bottom:100px;">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <button class="btn  btn-primary" id="submitPlantTreeUnitValueButton">{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}</button>
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_rptPlantTreeUnitValue.js') }}?rand={{ rand(000,999) }}"></script>
<script src="{{ asset('js/addPlantTressUnitValue.js') }}?rand={{ rand(000,999) }}"></script>
<script type="text/javascript">
    setTimeout(function(){ 
      var id = "{{($data->pt_ptrees_code != '')?$data->pt_ptrees_code:''}}";

      if(id > 0){
      var text = "{{(isset($data->plantText) && $data->plantText != '')?$data->plantText:'Please Select'}}";
               $("#pt_ptrees_code").select3("trigger", "select", {
    data: { id: id ,text:text}
});
            }
      var adminid = "{{($data->ps_subclass_code != '')?$data->ps_subclass_code:''}}";
      if(adminid > 0){
      var admintext = "{{(isset($data->text) && $data->text != '')?$data->text:'Please Select'}}";
               $("#ps_subclass_code").select3("trigger", "select", {
    data: { id: adminid ,text:admintext}
});
            }

}, 500);
</script>




