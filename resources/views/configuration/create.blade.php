{{ Form::open(array('url' => 'configuration')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  
   
    <div class="modal-body">


         <div class="row">            
                    
                  
                <div class="col-md-8">
               <div class="form-group">
                    {{ Form::label('configuration_value', __('Configuration value'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('configuration_value', $data->configuration_value, array('class' => 'form-control')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             
            
        
     





    </div>
       





        <div class="modal-footer">
        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<!-- <script src="{{ asset('js/addBusinessEnvfee.js') }}"></script> -->



