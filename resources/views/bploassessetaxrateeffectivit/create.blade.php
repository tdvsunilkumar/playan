{{ Form::open(array('url' => 'administrative/taxation-schedule/tax-rate-effectivity/store')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  
  
    <style type="text/css">
       .modal-content {
                position: relative;
                width: 540px;
                display: flex;
                flex-direction: column;
               /*width: 100%;*/
                pointer-events: auto;
                background-color: #ffffff;
                background-clip: padding-box;
                border: 1px solid rgba(0, 0, 0, 0.2);
                border-radius: 15px;
                outline: 0;
        }
    </style>


    <div class="modal-body">

         <div class="row">
            
                    
                  
               <!--  <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('tre_code', __('Code'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('tre_code', $data->tre_code, array('class' => 'form-control')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div> -->
             <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('tre_effectivity_year', __('Effectivity Year'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                         {{ Form::select('tre_effectivity_year',array('' =>'Please Select','2021' =>'2021','2022'=>'2022','2023' =>'2023','2024'=>'2024','2025'=>'2025'), $data->tre_effectivity_year, array('class' => 'form-control spp_type','id'=>'tre_effectivity_year','required'=>'required')) }}

                        <!-- {{ Form::text('tre_effectivity_year', $data->tre_effectivity_year, array('class' => 'form-control','required'=>'required')) }} -->
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tre_quarter', __('Quarter'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                         {{ Form::select('tre_quarter',array('' =>'Please Select','5' =>'1st','2'=>'2nd','3' =>'3rd','4'=>'4th'), $data->tre_quarter, array('class' => 'form-control spp_type','id'=>'tre_quarter','required'=>'required')) }}

                        
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tre_ordinance_number', __('Ordinance Number'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('tre_ordinance_number', $data->tre_ordinance_number, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_fee_option"></span>
                </div>
            </div>

            
            <!-- <div class="col-md-12">
                <div class="d-flex radio-check">
                    <div class="form-check form-check-inline form-group col-md-1" style="padding-right: 30px;">

                        {{ Form::radio('is_active', '1', ($data->is_active)?true:false, array('id'=>'active','class'=>'form-check-input code')) }}
                        {{ Form::label('active', __('Active'),['class'=>'form-label']) }}
                    </div>
                    <div class="form-check form-check-inline form-group col-md-1">
                        {{ Form::radio('is_active', '0', (!$data->is_active)?true:false, array('id'=>'InActive','class'=>'form-check-input code')) }}
                        {{ Form::label('InActive', __('InActive'),['class'=>'form-label']) }}
                    </div>
                </div>
            </div>     -->
               <div class="col-md-12">
                <div class="form-group ">
                    {{ Form::label('tre_remarks', __('Remarks'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                      {{ Form::textarea('tre_remarks', $data->tre_remarks, array('class' => 'form-control','rows'=>'2')) }}
                     
                    </div>
                    <span class="validate-err" id="err_bbef_fee_amount"></span>
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
<!-- <script src="{{ asset('js/addBusinessEnvfee.js') }}"></script> -->



