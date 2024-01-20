{{ Form::open(array('url' => 'hr-offset','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('hr_employeesid',$data->hr_employeesid, array('id' => 'hr_employeesid')) }}
    {{ Form::hidden('submit_type',"", array('id' => 'submit_type')) }}
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
                       <span class="validate-err" id="err_hr_employeesid"></span>
                          <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('date', __('Applied Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('date') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::date('date',
                                        $date, 
                                        array(
                                            'class' => 'form-control',
                                            'id'=>'date',
                                            'required'=>'required',
                                            'readonly',
                                            )) }}
                                </div>
                                <span class="validate-err" id="date"></span>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('appno', __('Application No'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('appno') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('appno',
                                        $data->applicationno, 
                                        array(
                                            'class' => 'form-control disabled-field',
                                            'id'=>'appno',
                                            (($data->hro_status) >= 1 ) ? 'readonly':'',
                                            )) }}
                                </div>
                                <span class="validate-err" id="err_hrcos_start_date"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hro_work_date', __('Work Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hro_work_date') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::date('hro_work_date',
                                        $data->hro_work_date, 
                                        array('class' => 'form-control',
                                        'id'=>'hro_work_date',
                                        (($data->hro_status) >= 1 ) ? 'readonly':'',
                                        'required'=>'required'
                                        )) }}
                                </div>
                                <span class="validate-err" id="err_hro_work_date"></span>
                            </div>
                        </div>
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
                    </div>
                    <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hro_id', __('App Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hro_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hro_id',
                                        $arrApplicationtypes,
                                        $data->hro_id,
                                        array(
                                            'class' => 'form-control select3',
                                            'id'=>'hro_id',
                                            (($data->hro_status) >= 1 ) ? 'readonly':'',
                                            'required'=>'required'
                                            )) }}
                                </div>
                                <span class="validate-err" id="err_hro_id"></span>
                            </div>
                       </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hro_remaining_offset_hours', __('Remaining Offset Hours'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hro_remaining_offset_hours') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('hro_remaining_offset_hours',
                                        $data->hro_remaining_offset_hours, 
                                        array(
                                            'class' => 'form-control disabled-field',
                                            'id'=>'hro_remaining_offset_hours',
                                            (($data->hro_status) >= 1 ) ? 'readonly':'',
                                            'required'=>'required'
                                            )) }}
                                </div>
                                <span class="validate-err" id="err_hro_remaining_offset_hours"></span>
                            </div>
                        </div>
                     </div>
                      <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('hro_reason', __('Reason'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hro_reason') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::textarea('hro_reason',
                                        $data->hro_reason, 
                                        array(
                                            'class' => 'form-control',
                                            'id'=>'hro_reason',
                                            (($data->hro_status) >= 1 ) ? 'readonly':'',
                                            'rows'=>'2'
                                            )) }}
                                </div>
                                <span class="validate-err" id="err_hro_reason"></span>
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
                                <span class="btn_addmore_document btn btn-primary" id="btn_addmore_document" style="color:white;"><i class="ti-plus"></i></span>
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
                                         <button type="button" class="btn btn-danger btn_cancel_documents"  value="{{$val->id}}"><i class="ti-trash"></i></button>
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
                    <div class="button">
                            <button  type="button" name="submit" value="{{$data->id}}"  id="disapprove-btn" sequence="0" class="btn  btn-warning {{ ($data->hro_status) === 1 || ($data->hro_status) === 2 ?__('disabled -field'):__('')}}">Cancel</button>
                    </div>
                    @endif
                    @if($data->hro_status < 1) 
                        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                        <i class="fa fa-save icon"></i>
                        <input type="submit" name="submit" value="{{ ($data->id)>0?__('Submit'):__('Submit')}}" class="btn btn-primary add" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                    @endif
                    @if($data->hro_status == 0) 
                        <div class="button" style="background: #000;padding-left: 8px;color: #fff;border-radius: 5px;">
                        <i class="fa fa-save icon"></i>
                        <input type="submit" name="submit" value="Save as Draft"  class="btn btn-warning add" style="background: #000;padding-left: 4px;border:1px solid #000;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                    @endif
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
<script src="{{ asset('js/HR/add_offset.js') }}"></script>
  

  
 
           