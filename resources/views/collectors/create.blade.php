{{ Form::open(array('url' => 'payment-system/side-menu/collectors-file/store')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  
   

    <div class="modal-body">

         <div class="row">
            
                    
                

                <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('col_code', __('Code'),['class'=>'form-label']) }}
        
                    <div class="form-icon-user">
                        {{ Form::number('col_code', $data->col_code, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('col_initial', __('Intial From Amount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::number('col_initial', $data->col_initial, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}

                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                   {{ Form::label('col_initial2', __('Intial To Amount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::number('col_initial2', $data->col_initial2, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('col_name', __('Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('col_name', $data->col_name, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_fee_option"></span>
                </div>
            </div>
             <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('col_desc', __('Designation'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('col_desc', $data->col_desc, array('class' => 'form-control','required'=>'required')) }}
                       <!-- {{ Form::textarea('col_desc', $data->col_desc, array('class' => 'form-control','rows'=>'2')) }} -->
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
              <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('col_type', __('Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('col_type',array('' =>'Select Fee','1' =>'1-Cedula','2' =>'2-Cedula','3' =>'3-Cedula','4' =>'4-Cedula'), $data->col_type, array('class' => 'form-control spp_type','id'=>'col_type','required'=>'required')) }}
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
<!-- <script src="{{ asset('js/addBusinessEnvfee.js') }}"></script> -->



