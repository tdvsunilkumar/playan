{{ Form::open(array('url' => 'hr-change-schedule','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
     {{ Form::hidden('hr_employeesid',$data->hr_employeesid, array('id' => 'id')) }}
   <style>
      .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: #fff;
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
                         <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('appno', __('Application No'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('appno') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('appno',$data->applicationno, array('class' => 'form-control disabled-field','id'=>'appno','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_hrcos_start_date"></span>
                            </div>
                        </div>
                         @if($data->id > 0)
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('status', __('Status'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('status') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('cos_status',$status, array('class' => 'form-control ','id'=>'cos_status','readonly'=>'readonly')) }}
                                </div>
                                <span class="validate-err" id="err_hml_status"></span>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                         <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hrcos_start_date', __('Start Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrcos_start_date') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::date('hrcos_start_date',$data->hrcos_start_date, array('class' => 'form-control','id'=>'hrcos_start_date','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_hrcos_start_date"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hrcos_end_date', __('End Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrcos_end_date') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::date('hrcos_end_date',$data->hrcos_end_date, array('class' => 'form-control ','id'=>'hrcos_end_date','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_hrcos_end_date"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hrcos_original_schedule', __('Original Schedule'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrds_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hrcos_original_schedule',$arrdefaultschedule,$data->hrcos_original_schedule, array('class' => 'form-control select3','id'=>'hrcos_original_schedule','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_hrcos_original_schedule"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hrcos_new_schedule', __('New Schedule'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrcos_new_schedule') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hrcos_new_schedule',$arrdefaultschedule,$data->hrcos_new_schedule, array('class' => 'form-control select3','id'=>'hrcos_new_schedule','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_hrcos_new_schedule"></span>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('reason', __('Reason'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('reason') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::textarea('reason',$data->reason, array('class' => 'form-control','id'=>'reason','rows'=>'2','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_reason"></span>
                            </div>
                       </div>
                     </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                          <div class="row field-requirement-details-status">
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                {{Form::label('id',__('Id'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9">
                                {{Form::label('filename',__('File Name'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                               <!--  <span class="btn_addmore_document btn btn-primary" id="btn_addmore_document" style="color:white;"><i class="ti-plus"></i></span> -->
                            </div>
                        </div>
                         <span class="documentsDetails activity-details" id="documentsDetails">
                             @php $i=1; @endphp
                            @foreach($arrDocuments as $key=>$val)
                            <div class="removedocumentsdata row pt10">
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                  <div class="form-group"><div class="form-icon-user">
                                    <p class="serialnoclass" style="text-align:center;">{{$i}}</p>
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
                                            @if(!empty($val->hrcos_file_name))
                                            <a class="btn" href="{{asset('uploads/')}}/{{$val->hrcos_file_path}}/{{$val->hrcos_file_name}}" target='_blank'><i class='ti-download'></i></a>
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
                      @if($data->id)
                        @if($approve_btn['sequence'])
                        <div class="button">
                             <button  type="button" name="submit" value="{{$data->id}}"  id="approve-btn" sequence="{{$approve_btn['sequence']}}" class="btn btn-primary approve-btn {{ ($data->status === $approve_btn['status'])>0?__(''):__('disabled -field')}}">Approve</button>
                        </div>
                        @endif
                        @if($data->status !=2)
                        <div class="button">
                             <button  type="button" name="submit" value="{{$data->id}}"  id="disapprove-btn" sequence="1" class="btn  btn-warning {{ ($data->status) == 2?__('disabled -field'):__('')}}">Disapprove</button>
                        </div>
                        @endif
                      @endif  
                        <!-- <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal"> -->
                       
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
                     <div class="form-group">
                       <div class="form-icon-user"><button type="button" class="btn btn-danger btn_cancel_documents"><i class="ti-trash"></i></button>
                       </div>
                   </div>
             </div>
    </div>
</div> 
<script src="{{ asset('js/ajax_validation.js') }}"></script> 
<script src="{{ asset('js/HR/add_chnageofschedule.js') }}"></script>   

  
 
           