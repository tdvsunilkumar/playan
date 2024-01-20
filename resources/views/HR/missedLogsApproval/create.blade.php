{{ Form::open(array('url' => 'hr-missed-logs','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
     {{ Form::hidden('hr_emp_id',$data->hr_emp_id, array('id' => 'id')) }}
   <style>
      .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .field-requirement-details-status label{padding-top:10px;}
    .permitclick{  cursor:pointer;color:skyblue; }
    .orpayment > .row{ padding:10px; }
    .btn{padding: 0.575rem 0.5rem;}
    .accordion-button::after {
    background-image: url();
  }
    select[readonly].select3-hidden-accessible + .select3-container {
        pointer-events: none;
        touch-action: none;
    }

    select[readonly].select3-hidden-accessible + .select3-container .select3-selection {
        background: #eee;
        box-shadow: none;
    }

    select[readonly].select3-hidden-accessible + .select3-container .select3-selection__arrow, select[readonly].select3-hidden-accessible + .select3-container .select3-selection__clear {
        display: none;
    }
 </style>
<div class="modal-body">
                    <div class="row">
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('created_at', __('Filed Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('created_at') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::date('created_at',$filed_date, array('class' => 'form-control','id'=>'created_at','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_created_at"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hml_application_no', __('Application No'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hml_application_no') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hml_application_no',$application_number, array('class' => 'form-control ','id'=>'hml_application_no','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_hml_application_no"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hml_status', __('Status'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hml_status') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hml_status',$status, array('class' => 'form-control ','id'=>'hml_status','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_hml_status"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hml_work_date', __('Work Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hml_work_date') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::date('hml_work_date',$data->hml_work_date, array('class' => 'form-control','id'=>'hml_work_date','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_hml_work_date"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hrlog_id', __('Log Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrlog_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hrlog_id',$log_type,$data->hrlog_id, array('class' => 'form-control select3','id'=>'hrlog_id','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_hrlog_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hml_actual_time', __('Actual Time'), ['class' => 'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hml_actual_time') }}</span>
                                <div class="form-icon-user">
                                {{ Form::time('hml_actual_time', isset($data->hml_actual_time) ? \Carbon\Carbon::parse($data->hml_actual_time)->format('H:i') : null, array('class' => 'form-control', 'id' => 'hml_actual_time', 'readonly')) }}                                </div>
                                <span class="validate-err" id="err_hml_actual_time"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('hml_reason', __('Reason'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hml_reason') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::textarea('hml_reason',$data->hml_reason, array('class' => 'form-control','id'=>'hml_reason','rows'=>'2','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_hml_reason"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                          <div class="row field-requirement-details-status">
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                {{Form::label('id',__('Id'),['class'=>'form-label','style' =>'color: #fcfcfc;'])}}
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9">
                                {{Form::label('filename',__('File Name'),['class'=>'form-label','style' =>'color: #fcfcfc;'])}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                {{Form::label('action',__('Action'),['class'=>'form-label','style' =>'color: #fcfcfc;'])}}
                            </div>
                        </div>
                         <span class="documentsDetails activity-details" id="documentsDetails">
                             @php $i=1; @endphp
                            @foreach($arrDocuments as $key=>$val)
                            <div class="removedocumentsdata row pt10">
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                  <div class="form-group"><div class="form-icon-user">
                                    <p style="text-align:center;" class="serialnoclass">{{$i}}</p>
                                    {{ Form::hidden('totalfiles[]','1', array('id' => 'totalfiles')) }}
                                    @if(!empty($val->id)) 
                                    {{ Form::hidden('fileid[]',$val->id, array('id' => 'fileid')) }}
                                    @endif
                                    </div>
                                  </div>
                                 </div>
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <div class="form-group">
                                        <div class="form-icon-user"><input class="form-control" name="documents[]" type="file" value="">
                                        </div>
                                   </div>
                                </div>
                                @if($i>=0)
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                         <div class="form-group">
                                            @if(!empty($val->fhml_file_name))
                                            <a class="btn" href="{{asset('uploads/')}}/{{$val->fhml_file_path}}/{{$val->fhml_file_name}}" target='_blank'><i class='ti-download'></i></a>
                                            @endif
                                       </div>
                                 </div>
                                @endif
                                @php $i++; @endphp
                            </div>
                        @endforeach
                         </span>
                       </div>  
                    </div>
                    
                    <div class="modal-footer">
                      @if($data->id > 0)
                        @if($approve_btn['sequence'])
                        <div class="button">
                             <button  type="button" name="submit" value="{{$data->id}}"  id="approve-btn" sequence="{{$approve_btn['sequence']}}" class="btn btn-primary approve-btn {{ ($data->hml_status === $approve_btn['status'])>0?__(''):__('disabled -field')}}">Approve</button>
                        </div>
                        @endif

                         @if($data->hml_status !=2)
                        <div class="button">
                             <button  type="button" name="submit" value="{{$data->id}}"  id="disapprove-btn" sequence="1" class="btn  btn-warning approve-btn {{ ($data->hml_status) == 2?__('disabled -field'):__('')}}">Disapprove</button>
                        </div>
                        @endif
                       @endif 
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
                    </div>
        </div>    
{{Form::close()}}
<div id="hiddendocumentsHtml" class="hide">
    <div class="removedocumentsdata row pt10">
         <div class="col-lg-1 col-md-1 col-sm-1">
            <div class="form-group">
                    <div class="form-icon-user">
                        <p class="serialnoclass" style="text-align:center;"></p>
                         {{ Form::hidden('totalfiles[]','1', array('id' => 'totalfiles')) }}
                    </div>
            </div>
          </div>
         <div class="col-lg-9 col-md-9 col-sm-9">
                <div class="form-group">
                    <div class="form-icon-user"><input class="form-control" name="documents[]" type="file" value="">
                    </div>
               </div>
            </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
                     
                   <div class='action-btn bg-danger col-sm-2'>
						<a href='#' class='mx-3 btn btn-sm btn_cancel_documents ti-trash text-white text-white'></a>
					</div>
             </div>
    </div>
</div> 
<script src="{{ asset('js/ajax_validation.js') }}"></script> 
<script src="{{ asset('js/HR/add_missedLog.js') }}"></script>   

  
 
           