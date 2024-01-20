{{ Form::open(array('url' => 'servicerequirements','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('tfoc_id', __('Service Fee'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('tfoc_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('tfoc_id',$getServicefee,$data->tfoc_id, array('class' => 'form-control select3','id'=>'tfoc_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_tfoc_id"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('es_id', __('Application Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('es_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('es_id',$arrService,$data->es_id, array('class' => 'form-control select3','id'=>'es_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_es_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('req_id', __('Requirements'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('req_id') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::select('req_id',$arrRequirements,$data->req_id, array('class' => 'form-control select3','id'=>'req_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_req_id"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('esr_is_required', __('Is Required'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('esr_is_required') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::select('esr_is_required',array('0'=>'No','1'=>'Yes'),$data->esr_is_required, array('class' => 'form-control ','id'=>'esr_is_required','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_req_id"></span>
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

 <script src="{{ asset('js/ajax_validation.js') }}"></script>  
  
 
           