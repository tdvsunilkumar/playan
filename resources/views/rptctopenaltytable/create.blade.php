{{ Form::open(array('url' => 'rptctopenaltytable')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  
   


 <div class="modal-body">

         <div class="row">
            
                    
                <div class="col-md-12">
               <div class="form-group">
                    {{ Form::label('cpt_current_year', __('CURRENT'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('cpt_current_year', $data->cpt_current_year, array('class' => 'form-control cpt_current_year','readonly' => ($data->id != '')?true:false)) }}
                    </div>
                    <span class="validate-err" id="err_cpt_current_year"></span>
                </div>
            </div> 

                <div class="col-md-12">
               <div class="form-group">
                    {{ Form::label('cpt_effective_year', __('EFFECTIVE'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('cpt_effective_year', $data->cpt_effective_year, array('class' => 'form-control cpt_effective_year','readonly' => ($data->id != '')?true:false)) }}
                    </div>
                    <span class="validate-err" id="err_cpt_effective_year"></span>
                </div>
            </div>
             <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('cpt_month_1', __('JANUARY (%)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                       {{ Form::number('cpt_month_1', $data->cpt_month_1, array('class' => 'form-control','step'=>'0.01')) }}
                    </div>
                    <span class="validate-err" id="err_cpt_month_1"></span>
                </div>
            </div>
             <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('cpt_month_2', __('FEBRUARY (%)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::number('cpt_month_2', $data->cpt_month_2, array('class' => 'form-control','step'=>'0.01')) }}
                    </div>
                    <span class="validate-err" id="err_cpt_month_2"></span>
                </div>
            </div>
             <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('cpt_month_3', __('MARCH (%)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::number('cpt_month_3', $data->cpt_month_3, array('class' => 'form-control','step'=>'0.01')) }}
                    </div>
                    <span class="validate-err" id="err_cpt_month_3"></span>
                </div>
            </div>
             <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('cpt_month_4', __('APRIL (%)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::number('cpt_month_4', $data->cpt_month_4, array('class' => 'form-control','step'=>'0.01')) }}
                    </div>
                    <span class="validate-err" id="err_cpt_month_4"></span>
                </div>
            </div>
             <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('cpt_month_5', __('MAY (%)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::number('cpt_month_5', $data->cpt_month_5, array('class' => 'form-control','step'=>'0.01')) }}
                    </div>
                    <span class="validate-err" id="err_cpt_month_5"></span>
                </div>
            </div>
             <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('cpt_month_6', __('JUNE (%)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::number('cpt_month_6', $data->cpt_month_6, array('class' => 'form-control','step'=>'0.01')) }}
                    </div>
                    <span class="validate-err" id="err_cpt_month_6"></span>
                </div>
            </div>
             <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('cpt_month_7', __('JULY (%)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::number('cpt_month_7', $data->cpt_month_7, array('class' => 'form-control','step'=>'0.01')) }}
                    </div>
                    <span class="validate-err" id="err_cpt_month_7"></span>
                </div>
            </div>
             <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('cpt_month_8', __('AUGUST (%)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::number('cpt_month_8', $data->cpt_month_8, array('class' => 'form-control','step'=>'0.01')) }}
                    </div>
                    <span class="validate-err" id="err_cpt_month_8"></span>
                </div>
            </div>
             <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('cpt_month_9', __('SEPTEMBER (%)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::number('cpt_month_9', $data->cpt_month_9, array('class' => 'form-control','step'=>'0.01')) }}
                    </div>
                    <span class="validate-err" id="err_cpt_month_9"></span>
                </div>
            </div>
             <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('cpt_month_10', __('OCTOBER (%)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::number('cpt_month_10', $data->cpt_month_10, array('class' => 'form-control','step'=>'0.01')) }}
                    </div>
                    <span class="validate-err" id="err_cpt_month_10"></span>
                </div>
            </div>
             <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('cpt_month_11', __('NOVEMBER (%)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::number('cpt_month_11', $data->cpt_month_11, array('class' => 'form-control','step'=>'0.01')) }}
                    </div>
                    <span class="validate-err" id="err_cpt_month_11"></span>
                </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('cpt_month_12', __('DECEMBER (%)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::number('cpt_month_12', $data->cpt_month_12, array('class' => 'form-control','step'=>'0.01')) }}
                    </div>
                    <span class="validate-err" id="err_cpt_month_12"></span>
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
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datepicker/bootstrap-datepicker.css?v='.filemtime(getcwd().'/assets/vendors/datepicker/bootstrap-datepicker.css').'') }}"/>

<script src="{{ asset('assets/vendors/datepicker/bootstrap-datepicker.js?v='.filemtime(getcwd().'/assets/vendors/datepicker/bootstrap-datepicker.js').'') }}"></script>
<script src="{{ asset('/js/penaltytable/index.js?v='.filemtime(getcwd().'/js/penaltytable/index.js').'') }}"></script>

<!-- <script src="{{ asset('js/penaltytable/index.js') }}"></script> -->
<!-- <script src="{{ asset('js/addBusinessEnvfee.js') }}"></script> -->



