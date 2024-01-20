{{ Form::open(array('url' => 'fees-master/environmental-fee/store')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('prev_tax_type_id',$data->tax_type_id, array('id' => 'prev_tax_type_id')) }}
    <div class="modal-body">
        <div class="row"> 
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tax_class_id', __('Tax Class'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tax_class_id',$arrTaxClasses,$data->tax_class_id, array('class' => 'form-control select3','id'=>'tax_class_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tax_class_id"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tax_type_id', __('Tax Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tax_type_id',$arrTaxTypes,$data->tax_type_id, array('class' => 'form-control select3','id'=>'tax_type_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tax_type_id"></span>
                </div>
            </div>  
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bbc_classification_code', __('Classification'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('bbc_classification_code',$arrClassificationCode,$data->bbc_classification_code, array('class' => 'form-control select3','id'=>'bbc_classification_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbc_classification_code"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bba_code', __('Business Activity'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                         {{ Form::select('bba_code',$arrbbaCode,$data->bba_code, array('class' => 'form-control select3','id'=>'bba_code','required'=>'required')) }}
                    <span class="validate-err" id="err_bba_code"></span>
                    </div>
               </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bbef_code', __('Business Classification Code'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('bbef_code', $data->bbef_code, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bbef_fee_option', __('Fee Option'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('bbef_fee_option',array('' =>'Please Select','0' =>'None','1'=>'Basic By Activity','2' =>'By Category','3'=>'By Area','4'=>'By Tax Paid'), $data->bbef_fee_option, array('class' => 'form-control spp_type','id'=>'bbef_fee_option')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_fee_option"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group currency">
                    {{ Form::label('bbef_fee_amount', __('Environmental Fee'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                      {{ Form::text('bbef_fee_amount', $data->bbef_fee_amount, array('class' => 'form-control','required'=>'required')) }}
                       <div class="currency-sign"><span>Php</span></div>
                    </div>
                    <span class="validate-err" id="err_bbef_fee_amount"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bbef_tax_schedule', __('Tax Schedule'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('bbef_tax_schedule',array('' =>'Select Fee','1' =>'Annually','2' =>'Quaterly'), $data->bbef_tax_schedule, array('class' => 'form-control spp_type','id'=>'bbef_tax_schedule','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_tax_schedule"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bbef_fee_schedule_option', __('Fee Schedule Option'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('bbef_fee_schedule_option',array('' =>'Please Select','1' =>'Environmental Fee','2' =>'Environmental Fee Multiply By item declared','3' =>'Environmental Fee Multiply By Area in Buss'), $data->bbef_fee_schedule_option, array('class' => 'form-control spp_type','id'=>'bbef_fee_schedule_option')) }}
                    </div>
                    <span class="validate-err" id="err_bsf_fee_schedule_option"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bbef_fee_amount_not_in_revenue', __('Fee Not in Revenue'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::text('bbef_fee_amount_not_in_revenue', $data->bbef_fee_amount_not_in_revenue, array('class' => 'form-control')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_fee_amount_not_in_revenue"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bbef_sched', __('Syched'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{ Form::text('bbef_sched', $data->bbef_sched, array('class' => 'form-control','readonly' => 'true')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_sched"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bbef_category_code', __('Category Code'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{ Form::text('bbef_category_code', $data->bbef_category_code, array('class' => 'form-control','readonly' => 'true')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_category_code"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bbef_category_description', __('Category Description'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{ Form::text('bbef_category_description', $data->bbef_category_description, array('class' => 'form-control','readonly' => 'true')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_category_description"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bbef_area_minimum', __('Minimum Area(sq.m)'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{ Form::text('bbef_area_minimum', $data->bbef_area_minimum, array('class' => 'form-control','readonly' => 'true')) }}
                    </div>
                    <span class="validate-err" id="err_bsf_area_minimum"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bbef_area_maximum', __('Maximum Area(sq.m)'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{ Form::text('bbef_area_maximum', $data->bbef_area_maximum, array('class' => 'form-control','readonly' => 'true')) }}
                    </div>
                    <span class="validate-err" id="err_bsf_area_maximum"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bbef_revenue_code', __('Revenue code description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::text('bbef_revenue_code', $data->bbef_revenue_code, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bsf_revenue_code"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bbef_remarks', __('Remarks'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::text('bbef_remarks', $data->bbef_remarks, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bsf_remarks"></span>
                </div>
            </div>
        </div>
        <!-- <div class="row">
             <div class="d-flex radio-check">
                <div class="form-check form-check-inline form-group col-md-1">
                    {{ Form::radio('is_active', '1', ($data->is_active)?true:false, array('id'=>'active','class'=>'form-check-input code')) }}
                    {{ Form::label('active', __('Active'),['class'=>'form-label']) }}
                </div>
                <div class="form-check form-check-inline form-group col-md-1">
                    {{ Form::radio('is_active', '0', (!$data->is_active)?true:false, array('id'=>'inactive','class'=>'form-check-input code')) }}
                    {{ Form::label('inactive', __('InActive'),['class'=>'form-label']) }}
                </div>
            </div>
        </div> -->
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
<script src="{{ asset('js/addBusinessEnvfee.js') }}"></script>



