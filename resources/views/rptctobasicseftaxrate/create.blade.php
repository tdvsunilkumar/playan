{{ Form::open(array('url' => 'rptctobasicseftaxrate','class'=>'formDtls')) }}
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
                        <div class="col-md-12">
                           <div class="form-group">
                                {{ Form::label('pc_class_code', __('Property Class Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    
                                <div class="form-icon-user">
                                    {{ Form::select('pc_class_code',$arrClassCode,$data->pc_class_code, array('class' => 'form-control select3','id'=>'pc_class_code')) }}
                                    
                                </div>
                                <span class="validate-err" id="err_pc_class_code"></span>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('bsst_basic_rate', __('Basic rate[%]'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('bsst_basic_rate') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('bsst_basic_rate', $data->bsst_basic_rate, array('class' => 'form-control decimalvalue','maxlength'=>'5')) }}
                                </div>
                                <span class="validate-err" id="err_bsst_basic_rate"></span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('bsst_sef_rate', __('SEF Rate[%]'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('bsst_sef_rate') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('bsst_sef_rate', $data->bsst_sef_rate, array('class' => 'form-control decimalvalue','maxlength'=>'5')) }}
                                </div>
                                <span class="validate-err" id="err_bsst_sef_rate"></span>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('bsst_sh_rate', __('SHT Rate[%]'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('bsst_sh_rate') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('bsst_sh_rate', $data->bsst_sh_rate, array('class' => 'form-control decimalvalue','maxlength'=>'5')) }}
                                </div>
                                <span class="validate-err" id="err_bsst_sh_rate"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('assessed_value_max_amount', __('SHT[Execute When Assessed Value Greater Than]'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('assessed_value_max_amount') }}</span>
                                <div class="form-icon-user">
                                    
                                     {{ Form::text('assessed_value_max_amount', $data->assessed_value_max_amount, array('class' => 'form-control decimalvalue','disabled'=>'disabled')) }}
                                </div>
                                <span class="validate-err" id="err_assessed_value_max_amount"></span>
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
    <script type="text/javascript">
        if($('input[name=bsst_sh_rate]').val() > 0){
            $('input[name=assessed_value_max_amount]').prop('disabled',false);
        }
        $('input[name=bsst_sh_rate]').on('keyup',function(){
            if($(this).val() > 0){
                $('input[name=assessed_value_max_amount]').prop('disabled',false);
            }else{
                $('input[name=assessed_value_max_amount]').val('');
                $('input[name=assessed_value_max_amount]').prop('disabled',true);
            }
        });
    </script>
    <script src="{{ asset('js/ajax_rptctobasictaxsetup.js') }}?rand={{0,999}}"></script>
    
   