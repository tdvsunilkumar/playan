{{ Form::open(array('url' => 'hr-philhealth','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
    .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .field-requirement-details-status label{padding-top:5px;}
</style> 
<div class="modal-body">
                    <div class="row">
                         <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('hrpt_description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrpt_description') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrpt_description',$data->hrpt_description, array('class' => 'form-control','id'=>'hrpt_description','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrpc_description"></span>
                            </div>
                        </div>
                      </div>
                      <div class="row">  
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hrpt_amount_from', __('Amount Range From'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrpt_amount_from') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrpt_amount_from',$data->hrpt_amount_from, array('class' => 'form-control','id'=>'hrpt_amount_from','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrpt_amount_from"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hrpt_amount_to', __('To'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrpt_amount_to') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrpt_amount_to',$data->hrpt_amount_to, array('class' => 'form-control','id'=>'hrpt_amount_to','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrpt_amount_to"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hrpt_percentage', __('Percentage'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrpt_percentage') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hrpt_percentage',$data->hrpt_percentage, array('class' => 'form-control','id'=>'hrpt_percentage','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrpt_percentage"></span>
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

  
 
           