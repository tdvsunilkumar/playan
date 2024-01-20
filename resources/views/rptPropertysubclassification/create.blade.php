{{ Form::open(array('url' => 'rptPropertysubclassification')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  
   
   

    <div class="modal-body">

         <div class="row">
            
            <div class="col-md-8">
              <div class="form-group">
                    {{ Form::label('pc_class_code', __('Class'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('pc_class_code',$arrClassCode,$data->pc_class_code, array('class' => 'form-control select3 
                        ','id'=>'pc_class_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tax_class_id"></span>
                </div>
            </div>       
                

            <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('ps_subclass_code', __('Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('ps_subclass_code', $data->ps_subclass_code, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_ps_subclass_code"></span>
                </div>
            </div>
           
            <div class="col-md-8">
               <div class="form-group">
                    {{ Form::label('ps_subclass_desc', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('ps_subclass_desc', $data->ps_subclass_desc, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ps_is_for_plant_trees', __('Plant|Trees'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('ps_is_for_plant_trees',array('1' =>'Yes','2' =>'No'), $data->ps_is_for_plant_trees, array('class' => 'form-control spp_type','id'=>'ps_is_for_plant_trees','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_tax_schedule"></span>
                </div>
            </div>
          
</div>
       

        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>




