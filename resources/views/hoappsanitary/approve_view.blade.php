{{ Form::open(array('url' => 'healthy-and-safety/app-sanitary/approve')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
        .accordion-button::after{background-image: url();}
         .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #8080802e;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .field-requirement-details-status label{padding-top:5px;}
    .modal-lg, .modal-xl {
    max-width: 975px !important;
  }
    </style>
    
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('bend_id', __('Business Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bend_id') }}</span>
                    <div class="form-icon-user">
                         {{ Form::select('bend_id',$busn_name,$data->bend_id, array('class' => 'form-control select3','id'=>'bend_id','disabled'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_bend_id"></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('has_app_year', __('Year'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('has_app_year') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('has_app_year', $data->has_app_year, array('class' => 'form-control numeric-only','id'=>'has_app_year','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_has_app_year"></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('has_app_no', __('Application No.'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('has_app_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('has_app_no', $data->has_app_no, array('class' => 'form-control','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_has_app_no"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('complete_address', __('complete_address'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('complete_address') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('complete_address', $complete_address, array('class' => 'form-control','id'=>"complete_address",'readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_complete_address"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('has_permit_no', __('Permit No'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('has_permit_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('has_permit_no', $data->has_permit_no, array('class' => 'form-control','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_has_is_paid"></span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('owner', __('Owner'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('owner') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('owner', $owner, array('class' => 'form-control','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_owner"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('has_type_of_establishment', __('Type Of Establishment'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('has_type_of_establishment') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('has_type_of_establishment', $data->has_type_of_establishment, array('class' => 'form-control','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_has_type_of_establishment"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('has_issuance_date', __('Issuance Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('has_issuance_date') }}</span>
                    <div class="form-icon-user">
                         {{ Form::date('has_issuance_date', $data->has_issuance_date, array('class' => 'form-control','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_has_issuance_date"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('has_expired_date', __('Expiration Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('has_expired_date') }}</span>
                    <div class="form-icon-user">
                         {{ Form::date('has_expired_date', $data->has_expired_date, array('class' => 'form-control','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_has_expired_date"></span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('has_remarks', __('Remarks or Comments'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('has_remarks') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('has_remarks', $data->has_remarks, array('class' => 'form-control','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('has_recommending_approver', __('Prepared By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('has_recommending_approver') }}</span>
                    <div class="form-icon-user">
                         {{ Form::select('has_recommending_approver',$employee,$data->has_recommending_approver, array('class' => 'form-control select3','id'=>'has_recommending_approver','disabled'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_has_recommending_approver"></span>
                </div>
             </div>
             <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('has_recommending_approver_position', __('Position'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('has_recommending_approver_position') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('has_recommending_approver_position', $data->has_recommending_approver_position, array('class' => 'form-control','id'=>'has_recommending_approver_position','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_has_recommending_approver_position"></span>
                </div>
             </div>

             <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('has_approver', __('Approved'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('has_approver') }}</span>
                    <div class="form-icon-user">
                         {{ Form::select('has_approver',$employee,$data->has_approver, array('class' => 'form-control select3','id'=>'has_approver','disabled'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_has_approver"></span>
                </div>
            </div>
             <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('has_approver_position', __('Position'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('has_approver_position') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('has_approver_position',  $has_approver_position, array('class' => 'form-control','id'=>'has_approver_position','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_has_approver_position"></span>
                </div>
             </div>     

             <div class="row">
                        <div class="row field-requirement-details-status">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                {{Form::label('code',__('Code'),['class'=>'form-label'])}}
                            </div>
                            
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                {{Form::label('date',__('Completed'),['class'=>'form-label numeric'])}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                {{Form::label('result',__('Completed Date'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                {{Form::label('remark',__('Remark'),['class'=>'form-label'])}}
                            </div>
                            <!-- <div class="col-lg-2 col-md-2 col-sm-2">
                                <input type="button" id="btn_addmore_healthcert" class="btn btn-success" value="Add More" style="padding: 0.4rem 0.76rem !important;">
                            </div> -->
                        </div>
                        <span class="Healthcerti nature-details" id="Healthcerti">
                             @php $i=0;  @endphp  
                            @foreach($reldata as $key=>$val)
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                        {{ Form::hidden('relid[]',$val->id, array('id' => 'id')) }}    
                                        {{ Form::select('req_id[]',$requirements,$val->req_id, array('class' => 'form-control naofbussi','disabled'=>'true')) }}
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="d-flex radio-check"><br>
                                                <div class="form-check form-check-inline form-group">
                                                    {{ Form::checkbox('hasr_is_complete[]', '1', ($val->hasr_is_complete)?true:false, array('id'=>'Completed','class'=>'form-check-input code','readonly'=>'true')) }}
                                                </div>
                                            </div>
                                </div>
                                 <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                            {{Form::date('hasr_completed_date[]',$val->hasr_completed_date,array('class'=>'form-control numeric','readonly'=>'true'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                        {{Form::text('hasr_remarks[]',$val->hasr_remarks,array('class'=>'form-control','placeholder'=>'','id'=>'hasr_remarks','readonly'=>'true'))}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php $i++; @endphp
                            @endforeach
                        </span>
            </div>

        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <input type="submit" name="submit" value="Approve" class="btn  btn-primary">
            <a href="#" title="Print Health Certificate"  data-title="Print Health Certificate" class="mx-3 btn print btn-sm  align-items-center" id="'.$data->id.'">
                 <input type="button" value="{{__('Print')}}" class="btn  btn-primary">
             </a>
        </div>
    </div>
</div> 
{{Form::close()}}
<div id="hidenhealthcertiHtml" class="hide">
    <div class="removehealthcertidata row pt10">
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                {{ Form::select('req_id[]',$requirements, '', array('class' => 'form-control naofbussi')) }}
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                    {{ Form::checkbox('hasr_is_complete[]', '1', '', array('id'=>'Completed','class'=>'form-check-input code')) }}
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                    {{Form::date('hasr_completed_date[]','',array('class'=>'form-control numeric'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                    {{Form::text('hasr_remarks[]','',array('class'=>'form-control','placeholder'=>'','id'=>'hasr_remarks'))}}
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <input type="button" name="btn_cancel" class="btn btn-success btn_cancel_healthcert" cid="" value="Delete" style="padding: 0.4rem 1rem !important;">
        </div>
    </div>
</div>
<!-- <script src="{{ asset('js/ajax_validation.js') }}"></script> -->
<script src="{{ asset('js/add_hoappsanitary.js') }}"></script>



