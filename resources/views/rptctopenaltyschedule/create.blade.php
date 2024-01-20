{{ Form::open(array('url' => 'rptctopenaltyschedule')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<style>
.modal-content {
    position: absolute;
   float: left;
   margin-left: 50%;
   margin-top: 50%;
  transform: translate(-50%, -50%);
}
</style>
 <div class="modal-body">

         <div class="row">
            
                    
                <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('cps_prevailing_law', __('Prevailing Law'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('cps_prevailing_law', $data->cps_prevailing_law, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div> 

                <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('cps_from_year', __('From'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::number('cps_from_year', $data->cps_from_year, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('cps_to_year', __('To'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::number('cps_to_year', $data->cps_to_year, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('cps_penalty_rate', __('Penalty Rate (%)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::number('cps_penalty_rate', $data->cps_penalty_rate, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('cps_penalty_limitation', __('Penalty Limitation'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                         {{ Form::select('cps_penalty_limitation',array('1' =>'Yes','0'=>'No'), $data->cps_penalty_limitation, array('class' => 'form-control spp_type','id'=>'cps_penalty_limitation','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('cps_maximum_penalty', __('Maximum Penalty (%)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::number('cps_maximum_penalty', $data->cps_maximum_penalty, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
              
              
                    

    </div>
 
 

        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<!-- <script src="{{ asset('js/ajax_validation.js') }}"></script> -->
<script src="{{ asset('js/ajax_common_save.js') }}"></script>
<!-- <script src="{{ asset('js/addBusinessEnvfee.js') }}"></script> -->



