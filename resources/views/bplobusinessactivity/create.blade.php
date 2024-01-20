{{ Form::open(array('url' => 'administrative/bus.-classificication/activities/store')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('prev_tax_type_id',$data->tax_type_id, array('id' => 'prev_tax_type_id')) }}
    {{ Form::hidden('prev_classification_id',$data->business_classification_id, array('id' => 'prev_classification_id')) }}
    <div class="modal-body">
        <div class="row">
            
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('tax_class_id', __('Tax Class'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tax_class_id',$arrTaxClasses,$data->tax_class_id, array('class' => 'form-control select3 getclassification','id'=>'tax_class_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tax_class_id"></span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('tax_type_id', __('Tax Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tax_type_id',$arrTaxTypes,$data->tax_type_id, array('class' => 'form-control select3 getclassification','id'=>'tax_type_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tax_type_id"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('business_classification_id', __('Business Classification'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('business_classification_id',array(""=>"Please Select"),$data->business_classification_id, array('class' => 'form-control select3','id'=>'business_classification_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_business_classification_id"></span>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('bba_per_day', __('Per Day'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        
                        {{ Form::select('bba_per_day',array('1' =>'Yes','0'=>'No'), $data->bba_per_day, array('class' => 'form-control spp_type','id'=>'bba_per_day','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bba_code"></span>
                </div>
            </div>
        </div>
        <div class="row">
            
            <div class="form-group col-md-6">
                {{ Form::label('bba_code', __('Activity Code'),['class'=>'form-label']) }}
                <span class="text-danger">*</span>
                {!! Form::text('bba_code', $data->bba_code, ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
                <span class="validate-err" id="err_bba_code"></span>
            </div>
            
            <div class="form-group col-md-6">
                {{ Form::label('bba_desc', __('Activity Description'),['class'=>'form-label']) }}
                <span class="text-danger">*</span>
                {!! Form::textarea('bba_desc', $data->bba_desc, ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
                <span class="validate-err" id="err_bba_desc"></span>
            </div>
            
            <!-- <div class="d-flex radio-check">
                <div class="form-check form-check-inline form-group col-md-1">
                    {{ Form::radio('is_active', '1', ($data->is_active)?true:false, array('id'=>'active','class'=>'form-check-input code')) }}
                    {{ Form::label('active', __('Active'),['class'=>'form-label']) }}
                </div>
                <div class="form-check form-check-inline form-group col-md-1">
                    {{ Form::radio('is_active', '0', (!$data->is_active)?true:false, array('id'=>'inactive','class'=>'form-check-input code')) }}
                    {{ Form::label('inactive', __('InActive'),['class'=>'form-label']) }}
                </div>
            </div> -->
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
<script src="{{ asset('js/addBusinessActivity.js') }}"></script>



