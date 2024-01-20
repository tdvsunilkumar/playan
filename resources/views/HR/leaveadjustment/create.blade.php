{{ Form::open(array('url' => 'hr-leaveearn-adjustment','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('submit_type',"", array('id' => 'submit_type')) }}
    @php
    $readonly = ($data->id>0)?'readonly':'';
    $select3 = ( $data->id>0 && $data->hrlea_status != 1)?'disabled-field':'select3';
    @endphp
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
 </style>
<div class="modal-body">
                    <div class="row">
                       <span class="validate-err" id="err_hr_employeesid"></span>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hr_employeesid', __('Employee'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('reason') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hr_employeesid',$arrEmployee,$data->hr_employeesid, array('class' => 'form-control select3','id'=>'hr_employeesid','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hr_employeesid"></span>
                            </div>
                       </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hrlp_id', __('Leave Parameter'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('reason') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('hrlp_id',$arrLeaveparameters,$data->hrlp_id, array('class' => 'form-control '.$select3,'id'=>'hrlp_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_hrlp_id"></span>
                            </div>
                       </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('hrlea_date_effective', __('Date Effectivity'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('hrlea_date_effective') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::date('hrlea_date_effective',$data->hrlea_date_effective, array('class' => 'form-control ','id'=>'hrlea_date_effective')) }}
                                </div>
                                <span class="validate-err" id="err_hrlea_date_effective"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                          <div class="row field-requirement-details-status">
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                {{Form::label('id',__('Leave Type'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                {{Form::label('filename',__('Entitled'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                {{Form::label('filename',__('Adjustment'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                {{Form::label('filename',__('Used'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                {{Form::label('filename',__('balance'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                <span class="btn_addmore_adjustments btn btn-primary" id="btn_addmore_adjustments" style="color:white;"><i class="ti-plus"></i></span>
                            </div>
                        </div>
                         <span class="AdjustmentDetails" id="AdjustmentDetails">
                             @php $i=1; @endphp
                                @foreach($arrAdjustmentDetials as $key=>$val)
                                <div class="removerequirementdata row pt10">
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                 {{ Form::select('hrlt_id[]',$arrLeaveType,$val->hrlt_id,array('class' => 'form-control hrlt_id','required'=>'required','id'=>'hrlt_id','disabled')) }}
                                                {{Form::hidden('hrlt_id[]',$val->hrlt_id)}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                         {{Form::text('entitled[]',$val->hrlpc_days,array('class'=>'form-control entitled','required'=>'required','id'=>'entitled','readonly'))}}
                                       </div>
                                    </div>
                                      <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                         {{Form::text('adjustment[]',($val->hrlad_adjustment =="")?'0':$val->hrlad_adjustment,array('class'=>'form-control adjustment','required'=>'required','id'=>'adjustment'))}}
                                       </div>
                                    </div>
                                      <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                         {{Form::text('used[]',$val->hrlead_used,array('class'=>'form-control used','required'=>'required','id'=>'used','disabled'))}}
                                       </div>
                                    </div>
                                      <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                         {{Form::text('balance[]',$val->hrlead_balance,array('class'=>'form-control balance','required'=>'required','id'=>'balance','disabled'))}}
                                       </div>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <!-- <button type="button" class="btn btn-primary btn_cancel_requiremnt"><i class="ti-trash"></i></button> -->
                                    </div>
                                    @php $i++; @endphp
                                </div>

                                @endforeach
                         </span>
                       </div>  
                    </div>
                    <div class="modal-footer">
                        @if(  isset($data->hrlea_status) && $data->hrlea_status === 1)
                        <div class="button">
                             <button  type="button" name="submit" value="{{$data->id}}"  id="approve-btn" sequence="1" class="btn  btn-primary approve-btn ">Approve</button>
                        </div>
                        @endif
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary add" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                    </div>
        </div>    
{{Form::close()}}
<div id="hiddenLeaveAdjustmentHtml" class="hide">
    <div class="removeadjustmentdata row pt10">
         <div class="col-lg-3 col-md-3 col-sm-3">
            <div class="form-group">
                    <div class="form-icon-user">
                         {{ Form::select('hrlt_id[]',$arrLeaveType,'', array('id' => 'hrlt_id0','class'=>'hrlt_id')) }}
                    </div>
            </div>
          </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                    <div class="form-icon-user"><input class="form-control" name="entitled[]" type="text" value="" readonly>
                    </div>
               </div>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                    <div class="form-icon-user"><input class="form-control adjustment" name="adjustment[]" type="text" value=""></div>
               </div>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                    <div class="form-icon-user"><input class="form-control" name="used[]" type="text" value="0" disabled>
                    </div>
               </div>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                    <div class="form-icon-user"><input class="form-control" name="balance[]" type="text" value="" disabled>
                    </div>
               </div>
          </div>
         <div class="col-lg-1 col-md-1 col-sm-1">
                     <div class="form-group">
                       <div class="form-icon-user"><button type="button" class="btn btn-danger btn_cancel_adjustment"><i class="ti-trash"></i></button>
                       </div>
                   </div>
             </div>
    </div>
</div> 
<script src="{{ asset('js/ajax_validation.js') }}"></script> 
<script src="{{ asset('js/HR/add_leaveadjustment.js') }}"></script>   

  
 
           