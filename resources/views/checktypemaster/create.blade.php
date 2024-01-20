{{ Form::open(array('url' => 'payment-system/side-menu/check-type-master-file/store')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  
   

    <div class="modal-body">

         <div class="row">
            
          
                <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('ctm_code', __('Code'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('ctm_code', $data->ctm_code, array('class' => 'form-control')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             
             <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('ctm_description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::textarea('ctm_description', $data->ctm_description, array('class' => 'form-control','rows'=>'2')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('ctm_short_name', __('Short name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('ctm_short_name', $data->ctm_short_name, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_fee_option"></span>
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



