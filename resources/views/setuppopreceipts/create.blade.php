{{ Form::open(array('url' => 'payment-system/side-menu/setup-receipts/store')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  
   


    <div class="modal-body">

         <div class="row">
            
          
                 <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('stp_type', __('Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('stp_type',array('1' =>'Business Permit & License','2' =>'Real Property (Land Tax)','3' =>'Burial Permit','4' =>'Community Tax - Indivitual','5' =>'Community Tax - Crop','6' =>'Miscellaneous'), $data->stp_type, array('class' => 'form-control spp_type','id'=>'stp_type','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_stp_type"></span>
                </div>
            </div>
            <div class="col-md-2">
               <div class="form-group">
                    {{ Form::label('code', __('code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('code', $data->code, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('stp_accountable_form', __('Accountable Form Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('stp_accountable_form', $data->stp_accountable_form, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
            <div class="col-md-3">
               <div class="form-group">
                    {{ Form::label('serial_no_from', __('Serial No. From'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('serial_no_from', $data->serial_no_from, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <div class="col-md-3">
               <div class="form-group">
                    {{ Form::label('serial_no_to', __('Serial No. To'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('serial_no_to', $data->serial_no_to, array('class' => 'form-control','required'=>'required','onkeyup'=>'qty();','onchange'=>'qty();','onBlur'=>'serial_no_to_validation();')) }}
                    </div>
                    <span class="validate-err" id="serial_no_to_err"></span>
                </div>
            </div>
            
            <div class="col-md-2">
               <div class="form-group">
                    {{ Form::label('stp_qty', __('Qty'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('stp_qty', $data->stp_qty, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
            <div class="col-md-2">
               <div class="form-group">
                    {{ Form::label('stp_value', __('Value'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('stp_value', $data->stp_value, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             
              <div class="col-md-2">
               <div class="form-group">
                    {{ Form::label('stp_print', __('Print Option'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::checkbox('stp_print','1', ($data->stp_print)?true:false, array('id'=>'id','class'=>'form-check-input code')) }}
                    </div>
                    
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
               
              
              <div class="row">
           <div class="d-flex radio-check">
                <div class="form-check form-check-inline form-group col-md-1">
                    {{ Form::radio('is_active', '1', ($data->is_active)?true:false, array('id'=>'active','class'=>'form-check-input code')) }}
                    {{ Form::label('active', __('Active'),['class'=>'form-label']) }}
                </div>
                <div class="form-check form-check-inline form-group col-md-1">
                    {{ Form::radio('is_active', '0', (!$data->is_active)?true:false, array('id'=>'InActive','class'=>'form-check-input code')) }}
                    {{ Form::label('InActive', __('InActive'),['class'=>'form-label']) }}
                </div>
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
<script src="{{ asset('js/addSetupPopReceipts.js') }}"></script>



