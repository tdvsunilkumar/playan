{{ Form::open(array('url' => 'CtoPaymentBank','class'=>'formDtls')) }}
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
                    {{ Form::label('bank_code', __('Bank Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bank_code') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('bank_code', $data->bank_code, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bank_code"></span>
                </div>
            </div>    
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bank_branch_code', __('Branch Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bank_branch_code') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('bank_branch_code', $data->bank_branch_code, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bank_branch_code"></span>
                </div>
            </div>    
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bank_desc', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bank_desc') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('bank_desc', $data->bank_desc, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bank_desc"></span>
                </div>
            </div>    
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bank_address', __('Address'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bank_address') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('bank_address', $data->bank_address, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bank_address"></span>
                </div>
            </div>    
        </div> 
       
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<!-- <script src="{{ asset('js/ajax_validation.js') }}"></script> -->
<script src="{{ asset('js/ajax_common_save.js') }}"></script>

  