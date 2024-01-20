{{ Form::open(array('url' => 'healthy-and-safety/health-certificate/approve')) }}
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
                    {{ Form::label('citizen_id', __('Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('citizen_id') }}</span>
                    <div class="form-icon-user">
                         {{ Form::select('citizen_id',$citizen,$data->citizen_id, array('class' => 'form-control select3','id'=>'citizen_id','required'=>'required','disabled'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_citizen_id"></span>
                </div>
             </div>
             <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('age', __('Age'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('age') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('age', $age, array('class' => 'form-control','id'=>'age','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_age"></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('hahc_app_year', __('Year Applied'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('hahc_app_year') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('hahc_app_year', $data->hahc_app_year, array('class' => 'form-control numeric-only','id'=>'hahc_app_year','required'=>'required','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_hahc_app_year"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('complete_address', __('Complete Address'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('complete_address') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('complete_address', $complete_address, array('class' => 'form-control','id'=>'complete_address','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_complete_address"></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('hahc_transaction_no', __('Control No'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('hahc_transaction_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('hahc_transaction_no', $data->hahc_transaction_no, array('class' => 'form-control','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_hahc_transaction_no"></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('hahc_registration_no', __('Registration No'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('hahc_registration_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('hahc_registration_no', $data->hahc_registration_no, array('class' => 'form-control numeric-only','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_hahc_registration_no"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('nationality', __('Nationality'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('nationality') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('nationality', $nationality, array('class' => 'form-control','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_c_code"></span>
                </div>
             </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('gender', __('Gender'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('gender') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('gender', $gender, array('class' => 'form-control','id'=>"gender",'readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_gender"></span>
                </div>
             </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('employee_occupation', __('Occupation'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('employee_occupation') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('employee_occupation', $data->employee_occupation, array('class' => 'form-control','required'=>'required','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="employee_occupation"></span>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('bend_id', __('Business Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bend_id') }}</span>
                    <div class="form-icon-user">
                         {{ Form::select('bend_id',$busn_name,$data->bend_id, array('class' => 'form-control select3','id'=>'bend_id','required'=>'required','disabled' => 'true')) }}
                    </div>
                    <span class="validate-err" id="err_bend_id"></span>
                </div>
             </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('hahc_place_of_work', __('Address of the workplace'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">

                        {{ Form::text('hahc_place_of_work',$data->hahc_place_of_work, array('class' => 'form-control','id'=>'hahc_place_of_work','required'=>'required','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_hahc_place_of_work"></span>
                </div>
            </div>            
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('hahc_issuance_date', __('Issuance Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('hahc_issuance_date') }}</span>
                    <div class="form-icon-user">
                         {{ Form::date('hahc_issuance_date', $data->hahc_issuance_date, array('class' => 'form-control','required'=>'required','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_hahc_issuance_date"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('hahc_expired_date', __('Expiration Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('hahc_expired_date') }}</span>
                    <div class="form-icon-user">
                         {{ Form::date('hahc_expired_date', $data->hahc_expired_date, array('class' => 'form-control','required'=>'required','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_hahc_expired_date"></span>
                </div>
             </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('hahc_remarks', __('Remarks or Additional Instruction'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('hahc_remarks') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('hahc_remarks', $data->hahc_remarks, array('class' => 'form-control','required'=>'required','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>

             <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('hahc_recommending_approver', __('Prepared By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('hahc_recommending_approver') }}</span>
                    <div class="form-icon-user">
                         {{ Form::select('hahc_recommending_approver',$employee,$data->hahc_recommending_approver, array('class' => 'form-control select3','id'=>'hahc_recommending_approver','required'=>'required','disabled'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_hahc_recommending_approver"></span>
                </div>
             </div>
             <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('hahc_recommending_approver_position', __('Position'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('hahc_recommending_approver_position') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('hahc_recommending_approver_position', $data->hahc_recommending_approver_position, array('class' => 'form-control','id'=>'hahc_recommending_approver_position','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_hahc_recommending_approver_position"></span>
                </div>
             </div>

             <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('hahc_approver', __('Approved'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('hahc_approver') }}</span>
                    <div class="form-icon-user">
                         {{ Form::select('hahc_approver',$employee,$data->hahc_approver, array('class' => 'form-control select3','id'=>'hahc_approver','required'=>'required','disabled'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_hahc_approver"></span>
                </div>
            </div>
             <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('hahc_approver_position', __('Position'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('hahc_approver_position') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('hahc_approver_position',  $data->hahc_approver_position, array('class' => 'form-control','id'=>'hahc_approver_position','readonly'=>'true')) }}
                    </div>
                    <span class="validate-err" id="err_hahc_approver_position"></span>
                </div>
             </div>
             
             <div class="row">
                        <div class="row field-requirement-details-status">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                {{Form::label('code',__('Code'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                {{Form::label('category',__('Category'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                {{Form::label('date',__('Exam Date'),['class'=>'form-label numeric'])}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                {{Form::label('result',__('Result'),['class'=>'form-label'])}}
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
                            @foreach($healthcertreq as $key=>$val)
                                <div class="row removenaturedata pt10">
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                             {{ Form::select('req_id[]',$requirements, $val->req_id, array('class' => 'form-control naofbussi','disabled'=>'true')) }}
                                             {{ Form::hidden('healthreqid[]',$val->id, array('id' => 'healthreqid','class' => 'healthcl')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                 {{ Form::select('hahcr_category[]',array('0'=>'Immunization','1'=>'X-Ray','2'=>'Stool and Other Exam'),$val->hahcr_category, array('class' => 'form-control naofbussi','disabled'=>'true')) }} 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                               {{Form::date('hahcr_exam_date[]',$val->hahcr_exam_date,array('class'=>'form-control','readonly'=>'true'))}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user" >
                                               {{Form::text('hahcr_exam_result[]',$val->hahcr_exam_result,array('class'=>'form-control','readonly'=>'true'))}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                               {{Form::text('hahcr_remarks[]',$val->hahcr_remarks,array('class'=>'form-control','placeholder'=>'','id'=>'hahcr_remarks','readonly'=>'true'))}}
                                            </div>
                                        </div>
                                    </div>
                                  
                                    
                                    
                                    @php $i++; @endphp
                                </div>
                            @endforeach
                        </span>
            </div>
		<a href="#"  style="flot:left;"title="Print Health Certificate"  data-title="Print Health Certificate" class="mx-3 btn print btn-sm  align-items-center" id="'.$data->id.'">
            <input type="button" value="{{__('Print')}}" class="btn  btn-primary">
        </a>	
        <div class="modal-footer" style="flot:right;">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <input type="submit" name="submit" value="{{ ($data->id)>0?__('Approve'):__('Approve')}}" class="btn  btn-primary"> 
        </div>
    </div>
</div> 
{{Form::close()}}

<script src="{{ asset('js/hoapphealthcert.js') }}"></script>
<script src="{{ asset('js/add_hoapphealthcert.js') }}"></script>



