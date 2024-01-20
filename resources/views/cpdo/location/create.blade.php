{{ Form::open(array('url' => 'cpdoservice','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<div class="modal-body">
     <div class="row">
        <div class="col-lg-12">
            <div class="accordion"   style="padding-top: 10px;">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingfive">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive" >
                                <h6 class="sub-title accordiantitle">{{__("Business Information")}}</h6>
                            </button>
                        </h6>
                        <div  class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                        <div class="basicinfodiv">
                        <div class="row">    
                             <div class="col-md-8">
                                <div class="form-group">
                                    {{ Form::label('businessname', __('Business Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('tfoc_id') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('businessname',$data->businessname, array('class' => 'form-control select3','id'=>'businessname','required'=>'required')) }}
                                    </div>
                                    <span class="validate-err" id="err_businessname"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('year', __('Year'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('year') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('year','', array('class' => 'form-control','id'=>'year','required'=>'required')) }}
                                    </div>
                                    <span class="validate-err" id="err_year"></span>
                                </div>
                            </div>
                         </div>
                         <div class="row"> 
                         <div class="col-md-8">
                            <div class="form-group">
                                {{ Form::label('completeaddress', __('Complete Address'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('completeaddress') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('completeaddress',$data->completeaddress, array('class' => 'form-control select3','id'=>'completeaddress','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_completeaddress"></span>
                            </div>
                        </div>
                          <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('date') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::date('date','', array('class' => 'form-control','id'=>'date','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_date"></span>
                            </div>
                        </div>
                       </div>
                       <div class="row"> 
                         <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('client_id', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('client_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('client_id',$arrowners,$data->client_id, array('class' => 'form-control ','id'=>'client_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_client_id"></span>
                            </div>
                          </div>
                       </div>
                        <div class="row">    
                             <div class="col-md-8">
                                <div class="form-group">
                                    {{ Form::label('preparedby', __('Prepared By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('preparedby') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('preparedby',$data->preparedby, array('class' => 'form-control select3','id'=>'preparedby','required'=>'required')) }}
                                    </div>
                                    <span class="validate-err" id="err_preparedby"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('position') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('position','', array('class' => 'form-control','id'=>'position','required'=>'required')) }}
                                    </div>
                                    <span class="validate-err" id="err_position"></span>
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
                        <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
                    </div>
                </div>
            </div>
          </div>
       </div>
    </div>
</div>    
{{Form::close()}}
 <script src="{{ asset('js/ajax_validation.js') }}"></script>  

  
 
           