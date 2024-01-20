{{ Form::open(array('url' => 'buildingtype','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
            <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('bt_building_type_code', __('Building Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('bt_building_type_code') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('bt_building_type_code', $data->bt_building_type_code, array('class' => 'form-control','maxlength'=>'10','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_bt_building_type_code"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('bt_building_type_desc', __('Building Type Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('bt_building_type_desc') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('bt_building_type_desc', $data->bt_building_type_desc, array('class' => 'form-control','maxlength'=>'75','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_bt_building_type_desc"></span>
                            </div>
                        </div>
                       </div>
                   
                    <div class="row">
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
                    </div>
                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <input type="submit" name="submit" onclick="return check()" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                        <!-- <input type="submit" name="submit" onclick="return check()" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
                    </div>
            </div>
    {{Form::close()}}
    
  