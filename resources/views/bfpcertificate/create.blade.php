{{ Form::open(array('url' => 'bfpcertificate','class'=>'formDtls','id'=>'mainForm')) }} 
    @php 
        $busid=$_GET['busn_id'];
        $bend_id=$_GET['bend_id'];
        $year=$_GET['year'];
    @endphp
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('busn_id',$busid, array('id' => 'busn_id')) }}
    {{ Form::hidden('endorsing_dept_id',$bend_id, array('id' => 'endorsing_dept_id')) }}
    {{ Form::hidden('bend_id',$data->bend_id, array('id' => 'bend_id')) }}
    {{ Form::hidden('year',$year, array('id' => 'year')) }}
    {{ Form::hidden('bff_id',$data->bff_id, array('id' => 'bff_id')) }}
    {{ Form::hidden('bfpas_id',$data->bfpas_id, array('id' => 'bfpas_id')) }}
    {{ Form::hidden('bio_id',$data->bio_id, array('id' => 'bio_id')) }}
    {{ Form::hidden('client_id',$data->client_id, array('id' => 'client_id')) }}
    {{ Form::hidden('bgy_id',$data->bgy_id, array('id' => 'bgy_id')) }}
    <style type="text/css">
        .accordion-button::after{background-image: url();}
        .modal.show .modal-dialog {
            transform: none;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 950px;
            pointer-events: auto;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            outline: 0;
        }
        .modal-footer {
            display: flex;
            flex-wrap: wrap;
            flex-shrink: 0;
            align-items: center;
            justify-content: flex-end;
            padding-right: 41px;
            /* padding: 1rem; */
            border-top: 1px solid #f1f1f1;
            border-bottom-right-radius: 14px;
            border-bottom-left-radius: 14px;
        }
    </style>
    <div class="modal-body">
        @if($data->id > 0 )
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('busn_id', __('Business Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('businessName','', array('class' => 'form-control','required'=>'required','readonly','id'=>'businessName')) }}
                    </div>
                    <span class="validate-err" id="err_tax_class_id"></span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('bfpcert_year', __('Year'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        @php 
                         $year=date("Y");
                         $date = date('Y-m-d');
                        @endphp
                        @if($data->bfpcert_year)
                            {{ Form::text('bfpcert_year', $data->bfpcert_year, array('class' => 'form-control','required'=>'required','readonly')) }}
                        @else
                            {{ Form::text('bfpcert_year', $year, array('class' => 'form-control','required'=>'required','readonly')) }}
                        @endif
                    </div>
                    <span class="validate-err" id="err_tax_class_id"></span>
                </div>
            </div>
             <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('bfpcert_date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                    @if($data->bfpcert_date)
                        {{ Form::date('bfpcert_date', $data->bfpcert_date, array('class' => 'form-control','required'=>'required','readonly')) }}
                    @else
                        {{ Form::date('bfpcert_date', $date, array('class' => 'form-control','required'=>'required')) }}
                    @endif

                    @if(($data->bfpcert_approved_recommending_date)=="0000-00-00")
                     {{ Form::hidden('bfpcert_approved_recommending_date', $date, array('class' => 'form-control')) }}
                       
                    @elseif($data->bfpcert_approved_recommending_date)
                     {{ Form::hidden('bfpcert_approved_recommending_date', $data->bfpcert_approved_recommending_date, array('class' => 'form-control')) }}
                    @else
                        {{ Form::hidden('bfpcert_approved_recommending_date',$date, array('class' => 'form-control')) }}
                    @endif

                    @if(($data->bfpcert_approved_date)=="0000-00-00")
                        {{ Form::hidden('bfpcert_approved_date', $date, array('class' => 'form-control')) }}
                    @elseif($data->bfpcert_approved_date)
                        {{ Form::hidden('bfpcert_approved_date', $data->bfpcert_approved_date, array('class' => 'form-control')) }}
                    @else
                        {{ Form::hidden('bfpcert_approved_date', $date, array('class' => 'form-control')) }}
                    @endif
                    </div>
                    <span class="validate-err" id="err_tax_class_id"></span>
                </div>
            </div>
             <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('completeAddress', __('Complete Address '),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('completeAddress') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('completeAddress', '', array('class' => 'form-control','required'=>'required','id'=>'completeAddress','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('busns_id_no', __('Business ID No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('brgy_code') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('busns_id_no', '', array('class' => 'form-control','id'=>'busns_id_no','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('clientName', __('Taxpayer Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('clientName') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('clientName', '', array('class' => 'form-control','id'=>'clientName','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bfpcert_no', __('FSIC No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bfpcert_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('bfpcert_no', $data->bfpcert_no, array('class' => 'form-control','required'=>'required','id'=>'bfpcert_no','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('busn_bldg_total_floor_area', __('Floor Area (sq. m.)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('busn_bldg_total_floor_area') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('busn_bldg_total_floor_area', '', array('class' => 'form-control','id'=>'busn_bldg_total_floor_area','readonly')) }}
                          
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bfpcert_date_issue', __('Issuance'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bfpcert_date_issue') }}</span>
                    <div class="form-icon-user">
                        @if($data->bfpcert_date_issue)
                             {{ Form::date('bfpcert_date_issue', $data->bfpcert_date_issue, array('class' => 'form-control','required'=>'required','readonly')) }}
                        @else
                             {{ Form::date('bfpcert_date_issue', $date, array('class' => 'form-control','required'=>'required','readonly')) }}
                        @endif
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bfpcert_date_expired', __('Expiration'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('bfpcert_date_expired') }}</span>
                    <div class="form-icon-user">
                        @php 
                         $now = date('Y-m-d'); 
                         
                        $future_date = date('Y-m-d', strtotime("now + 1 years"));
                        @endphp
                        @if($data->bfpcert_date_expired)
                         {{ Form::date('bfpcert_date_expired', $data->bfpcert_date_expired, array('class' => 'form-control')) }}
                         @else
                         {{ Form::date('bfpcert_date_expired','', array('class' => 'form-control')) }}
                         @endif
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('orno', __('O.R. No.'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('ba_business_account_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('orno', $data->orno, array('class' => 'form-control','readonly','id'=>'bfpas_payment_or_no')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('oramount', __('Amount'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('ba_business_account_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::number('oramount', $data->oramount, array('class' => 'form-control','step'=>'0.001','readonly','id'=>'bfpas_total_amount')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ordate', __('O.R. Date'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('ba_business_account_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::date('ordate',$data->ordate, array('class' => 'form-control','readonly','id'=>'bfpas_date_paid')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            <div class="col-md-12">
                <!--- Start Status--->
                <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
                        <div  class="accordion accordion-flush" >
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-headingone" >
                                    <button class="accordion-button collapsed btn-primary btn-endorsementStatus" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Inspection Details")}}</span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample" >
                                    <div class="basicinfodiv">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group" id="inspection_officer_group">
                                                    {{ Form::label('inspection_officer_id', __('Fire Safety Inspection Officer'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('inspection_officer_id') }}</span>
                                                    <div class="form-icon-user">
                                                         {{ Form::select('inspection_officer_id',$employee,$data->inspection_officer_id, array('class' => 'form-control select3','id'=>'inspection_officer_id')) }}

                                                    </div>
                                                    <span class="validate-err" id="err_p_address_street_name"></span>
                                                </div>
                                            </div>
                                            
                                             <div class="col-md-3">
                                                <div class="form-group">
                                                    {{ Form::label('inspection_date', __('Inspection Date'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('inspection_date') }}</span>
                                                    <div class="form-icon-user">
                                                         {{ Form::date('inspection_date',$data->inspection_date, array('class' => 'form-control','id'=>'inspection_date')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_p_address_street_name"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {{ Form::label('bio_inspection_no', __('Inspection Order No.'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('bio_inspection_no') }}</span>
                                                    <div class="form-icon-user">
                                                         {{ Form::text('bio_inspection_no', $data->bio_inspection_no, ['class' => 'form-control', 'maxlength' => 20]) }}

                                                    </div>
                                                    <span class="validate-err" id="err_p_address_street_name"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('bfpcert_remarks', __('Remarks/Comments'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('bfpcert_remarks') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('bfpcert_remarks', $data->bfpcert_remarks, array('class' => 'form-control')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            
            
               @if($data->bfpcert_no)
               @if($data->recommending_status == 0 && $data->created_by == $current_user_login )
               <div class="col-md-8">
                    <div class="form-group">
                        {{ Form::label('bfpcert_approved_recommending', __('Recommending Approval'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('bio_recommending_approval') }}</span>
                        <div class="form-icon-user">
                            {{ Form::select('bfpcert_approved_recommending',$employee,$data->bfpcert_approved_recommending, array('class' => 'form-control ','id'=>'bfpcert_approved_recommending','onmousedown'=>'return false;','readonly')) }}
                        </div>
                        <span class="validate-err" id="err_bio_recommending_approval"></span>
                    </div>  
                </div>
                @elseif($data->recommending_status == 1 && $data->created_by == $current_user_login )
               <div class="col-md-8">
                    <div class="form-group">
                        {{ Form::label('bfpcert_approved_recommending', __('Recommending Approval'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('bio_recommending_approval') }}</span>
                        <div class="form-icon-user">
                            {{ Form::select('bfpcert_approved_recommending',$employee,$data->bfpcert_approved_recommending, array('class' => 'form-control ','id'=>'bfpcert_approved_recommending','onmousedown'=>'return false;','readonly')) }}
                        </div>
                        <span class="validate-err" id="err_bio_recommending_approval"></span>
                    </div>  
                </div>
               @else
                <div class="col-md-8" >
               
                    <div class="form-group" id="recommending2">
                        {{ Form::label('bfpcert_approved_recommending', __('Recommending Approval'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                           
                            {{ Form::select('bfpcert_approved_recommending',$employee,$data->bfpcert_approved_recommending, array('class' => 'form-control select3','id'=>'bfpcert_approved_recommending2')) }}
                           
                           
                        </div>
                        <span class="validate-err" id="err_tax_class_id"></span>
                    </div>
                </div>
              @endif
              @else
                <div class="col-md-8" >
               
                    <div class="form-group" id="recommending2">
                        {{ Form::label('bfpcert_approved_recommending', __('Recommending Approval'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                           
                            {{ Form::select('bfpcert_approved_recommending',$employee,$data->bfpcert_approved_recommending, array('class' => 'form-control select3','id'=>'bfpcert_approved_recommending2')) }}
                           
                           
                        </div>
                        <span class="validate-err" id="err_tax_class_id"></span>
                    </div>
                </div>
              @endif
              @if($data->recommending_status == 0)
              <div class="col-md-4" >
                    <div class="form-group">
                        {{ Form::label('bfpcert_approved_recommending_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('ba_business_account_no') }}</span>
                        <div class="form-icon-user">
                             {{ Form::text('bfpcert_approved_recommending_position', $data->bfpcert_approved_recommending_position, array('class' => 'form-control','id'=>'bfpcert_approved_recommending_position')) }}
                        </div>
                        <span class="validate-err" id="err_p_address_street_name"></span>
                    </div>
                </div>
              @else
              <div class="col-md-4" >
                    <div class="form-group">
                        {{ Form::label('bfpcert_approved_recommending_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('ba_business_account_no') }}</span>
                        <div class="form-icon-user">
                             {{ Form::text('bfpcert_approved_recommending_position', $data->bfpcert_approved_recommending_position, array('class' => 'form-control','id'=>'bfpcert_approved_recommending_position')) }}
                        </div>
                        <span class="validate-err" id="err_p_address_street_name"></span>
                    </div>
                </div>
              @endif
                @if($data->bfpcert_no)
                @if($recommending_user_id == $current_user_login && $data->recommending_status == 0)
                <div class="row">  
                <div class="col-md-12">
                    <div class="form-group" >
                        
                        <div class="form-icon-user">
                             {{ Form::checkbox('recommending_status',1, ($data->recommending_status)?true:false, array('id'=>'recommending_status','class'=>'form-check-input code')) }} Approved: Confirmation
                        </div>
                        <span class="validate-err" id="err_tax_class_id"></span>
                    </div>
                </div>
                </div>
                @elseif($data->recommending_status == 1)
                <div class="row" >
                <div class="col-md-12">
                    <div class="form-group" style="color: #f10808;">
                        
                        <div class="form-icon-user">
                             {{ Form::checkbox('recommending_status',1, ($data->recommending_status)?true:false, array('id'=>'recommending_status','class'=>'form-check-input code','onclick'=>'return false;')) }} Approved: Confirmation
                        </div>
                        
                        <span class="validate-err" id="err_tax_class_id"></span>
                    </div>
                </div>
                </div>
                @endif            
                @else
                @endif
                @if($data->bfpcert_no)
                @if($data->approved_status == 0 && $data->recommending_status == 1 && $data->created_by == $current_user_login)
                <div class="col-md-8">
                    <div class="form-group">
                        {{ Form::label('bfpcert_approved', __('Approved By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err"></span>
                        <div class="form-icon-user">
                            {{ Form::select('bfpcert_approved',$employee,$data->bfpcert_approved, array('class' => 'form-control ','id'=>'bfpcert_approved','onmousedown'=>'return false;','readonly')) }}
                        </div>
                        <span class="validate-err" id="err_p_bio_approved"></span>
                    </div>
                </div>
                @elseif($data->approved_status == 1 && $data->recommending_status == 1 && $data->created_by == $current_user_login)
                <div class="col-md-8">
                    <div class="form-group">
                        {{ Form::label('bfpcert_approved', __('Approved By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err"></span>
                        <div class="form-icon-user">
                            {{ Form::select('bfpcert_approved',$employee,$data->bfpcert_approved, array('class' => 'form-control ','id'=>'bfpcert_approved','onmousedown'=>'return false;','readonly')) }}
                        </div>
                        <span class="validate-err" id="err_p_bio_approved"></span>
                    </div>
                </div>
                @else
                <div class="col-md-8" >
                
                
                    <div class="form-group" id="approved2">
                       {{ Form::label('bfpcert_approved', __('Approved By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err"></span>
                        <div class="form-icon-user">
                            {{ Form::select('bfpcert_approved',$employee,$data->bfpcert_approved, array('class' => 'form-control select3','id'=>'bfpcert_approved2','required'=>'required')) }}
                        </div>
                        <span class="validate-err" id="err_p_bio_approved"></span>
                    </div>
                </div>
                
                @endif
                @else
                <div class="col-md-8" >
                
                
                    <div class="form-group" id="approved2">
                       {{ Form::label('bfpcert_approved', __('Approved By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err"></span>
                        <div class="form-icon-user">
                            {{ Form::select('bfpcert_approved',$employee,$data->bfpcert_approved, array('class' => 'form-control select3','id'=>'bfpcert_approved2','required'=>'required')) }}
                        </div>
                        <span class="validate-err" id="err_p_bio_approved"></span>
                    </div>
                </div>
                
                @endif
            
                @if($data->approved_status == 0)
                <div class="col-md-4" >
                    <div class="form-group">
                        {{ Form::label('bfpcert_approved_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('bfpcert_approved_position') }}</span>
                        <div class="form-icon-user">
                             {{ Form::text('bfpcert_approved_position', $data->bfpcert_approved_position, array('class' => 'form-control','id'=>'bfpcert_approved_position')) }}
                        </div>
                        <span class="validate-err" id="err_p_address_street_name"></span>
                    </div>
                </div>
                @else
                <div class="col-md-4" >
                    <div class="form-group">
                        {{ Form::label('bfpcert_approved_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('bfpcert_approved_position') }}</span>
                        <div class="form-icon-user">
                             {{ Form::text('bfpcert_approved_position', $data->bfpcert_approved_position, array('class' => 'form-control','id'=>'bfpcert_approved_position')) }}
                        </div>
                        <span class="validate-err" id="err_p_address_street_name"></span>
                    </div>
                </div>
                @endif
                @if($data->bfpcert_no)
                @if($data->recommending_status == 1 && $approved_user_id == $current_user_login && $data->approved_status == 0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::checkbox('approved_status',1, ($data->recommending_status)?false:true, array('id'=>'approved_status','class'=>'form-check-input code')) }} Approved: Confirmation
                        </div>
                    </div>
                 </div>
                @elseif($data->recommending_status == 1 && $data->approved_status ==1)
                 <div class="row" >
                   
                    <div class="col-md-12">
                        <div class="form-group" style="color: #f10808;">
                        
                        <div class="form-icon-user">
                             {{ Form::checkbox('approved_status',1, ($data->approved_status)?true:false, array('id'=>'approved_status','class'=>'form-check-input code','onclick'=>'return false;')) }} Approved: Confirmation
                        </div>
                            
                    </div>
                 </div>
                 @endif
                @else
                @endif
            @else
                <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('busn_id', __('Business Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('businessName','', array('class' => 'form-control','required'=>'required','readonly','id'=>'businessName')) }}
                    </div>
                    <span class="validate-err" id="err_tax_class_id"></span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('bfpcert_year', __('Year'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        @php 
                         $year=date("Y");
                         $date = date('Y-m-d');
                        @endphp
                        @if($data->bfpcert_year)
                            {{ Form::text('bfpcert_year', $data->bfpcert_year, array('class' => 'form-control','required'=>'required','readonly')) }}
                        @else
                            {{ Form::text('bfpcert_year', $year, array('class' => 'form-control','required'=>'required','readonly')) }}
                        @endif
                    </div>
                    <span class="validate-err" id="err_tax_class_id"></span>
                </div>
            </div>
             <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('bfpcert_date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                    @if($data->bfpcert_date)
                        {{ Form::date('bfpcert_date', $data->bfpcert_date, array('class' => 'form-control','required'=>'required')) }}
                    @else
                        {{ Form::date('bfpcert_date', $date, array('class' => 'form-control','required'=>'required')) }}
                    @endif

                    @if(($data->bfpcert_approved_recommending_date)=="0000-00-00")
                     {{ Form::hidden('bfpcert_approved_recommending_date', $date, array('class' => 'form-control')) }}
                       
                    @elseif($data->bfpcert_approved_recommending_date)
                     {{ Form::hidden('bfpcert_approved_recommending_date', $data->bfpcert_approved_recommending_date, array('class' => 'form-control')) }}
                    @else
                        {{ Form::hidden('bfpcert_approved_recommending_date',$date, array('class' => 'form-control')) }}
                    @endif

                    @if(($data->bfpcert_approved_date)=="0000-00-00")
                        {{ Form::hidden('bfpcert_approved_date', $date, array('class' => 'form-control')) }}
                    @elseif($data->bfpcert_approved_date)
                        {{ Form::hidden('bfpcert_approved_date', $data->bfpcert_approved_date, array('class' => 'form-control')) }}
                    @else
                        {{ Form::hidden('bfpcert_approved_date', $date, array('class' => 'form-control')) }}
                    @endif
                    </div>
                    <span class="validate-err" id="err_tax_class_id"></span>
                </div>
            </div>
             <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('completeAddress', __('Complete Address '),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('completeAddress') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('completeAddress', '', array('class' => 'form-control','required'=>'required','id'=>'completeAddress','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('busns_id_no', __('Business ID No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('brgy_code') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('busns_id_no', '', array('class' => 'form-control','id'=>'busns_id_no','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('clientName', __('Taxpayer Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('clientName') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('clientName', '', array('class' => 'form-control','id'=>'clientName','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bfpcert_no', __('FSIC No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bfpcert_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('bfpcert_no', $data->bfpcert_no, array('class' => 'form-control','required'=>'required','id'=>'bfpcert_no')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('busn_bldg_total_floor_area', __('Floor Area (sq. m.)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('busn_bldg_total_floor_area') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('busn_bldg_total_floor_area', '', array('class' => 'form-control','id'=>'busn_bldg_total_floor_area','readonly')) }}
                          
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bfpcert_date_issue', __('Issuance'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bfpcert_date_issue') }}</span>
                    <div class="form-icon-user">
                        @if($data->bfpcert_date_issue)
                             {{ Form::date('bfpcert_date_issue', $data->bfpcert_date_issue, array('class' => 'form-control','required'=>'required')) }}
                        @else
                             {{ Form::date('bfpcert_date_issue', $date, array('class' => 'form-control','required'=>'required')) }}
                        @endif
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bfpcert_date_expired', __('Expiration'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('bfpcert_date_expired') }}</span>
                    <div class="form-icon-user">
                        @php 
                         $now = date('Y-m-d'); 
                         
                        $future_date = date('Y-m-d', strtotime("now + 1 years"));
                        @endphp
                        @if($data->bfpcert_date_expired)
                         {{ Form::date('bfpcert_date_expired', $data->bfpcert_date_expired, array('class' => 'form-control')) }}
                         @else
                         {{ Form::date('bfpcert_date_expired','', array('class' => 'form-control')) }}
                         @endif
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('orno', __('O.R. No.'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('ba_business_account_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('orno', $data->orno, array('class' => 'form-control','readonly','id'=>'bfpas_payment_or_no')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('oramount', __('Amount'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('ba_business_account_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::number('oramount', $data->oramount, array('class' => 'form-control','step'=>'0.001','readonly','id'=>'bfpas_total_amount')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ordate', __('O.R. Date'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('ba_business_account_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::date('ordate',$data->ordate, array('class' => 'form-control','readonly','id'=>'bfpas_date_paid')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            <div class="col-md-12">
                <!--- Start Status--->
                <div class="row" >
                    <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExampleInspection">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-headingInspection">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseInspection" aria-expanded="false" aria-controls="flush-collapseInspection">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Inspection Details")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                
                                
                                <div id="flush-collapseInspection" class="accordion-collapse collapse" aria-labelledby="flush-headingInspection" data-bs-parent="#accordionFlushExampleInspection">
                                
                                    <div class="basicinfodiv">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group" id="inspection_officer_group">
                                                    {{ Form::label('inspection_officer_id', __('Fire Safety Inspection Officer'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('inspection_officer_id') }}</span>
                                                    <div class="form-icon-user">
                                                         {{ Form::select('inspection_officer_id',$employee,$data->inspection_officer_id, array('class' => 'form-control select3','id'=>'inspection_officer_id')) }}

                                                    </div>
                                                    <span class="validate-err" id="err_p_address_street_name"></span>
                                                </div>
                                            </div>
                                            
                                             <div class="col-md-3">
                                                <div class="form-group">
                                                    {{ Form::label('inspection_date', __('Inspection Date'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('inspection_date') }}</span>
                                                    <div class="form-icon-user">
                                                         {{ Form::date('inspection_date',$data->inspection_date, array('class' => 'form-control','id'=>'inspection_date')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_p_address_street_name"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {{ Form::label('bio_inspection_no', __('Inspection Order No.'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('bio_inspection_no') }}</span>
                                                    <div class="form-icon-user">
                                                         {{ Form::text('bio_inspection_no', $data->bio_inspection_no, ['class' => 'form-control', 'maxlength' => 20]) }}

                                                    </div>
                                                    <span class="validate-err" id="err_p_address_street_name"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('bfpcert_remarks', __('Remarks/Comments'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('bfpcert_remarks') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('bfpcert_remarks', $data->bfpcert_remarks, array('class' => 'form-control')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            
            
            
               
                <div class="col-md-8">
                    <div class="form-group" id="recommending">
                        {{ Form::label('bfpcert_approved_recommending', __('Recommending Approval'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('bio_recommending_approval') }}</span>
                        <div class="form-icon-user">
                            {{ Form::select('bfpcert_approved_recommending',$employee,$data->bfpcert_approved_recommending, array('class' => 'form-control select3','id'=>'bfpcert_approved_recommending','required'=>'required')) }}
                        </div>
                        <span class="validate-err" id="err_bio_recommending_approval"></span>
                    </div>  
                </div>
              
              
                <div class="col-md-4" >
               
                <!-- <div class="col-md-4" id="myDiv2" style="display: none;"> -->
                
                    <div class="form-group">
                        {{ Form::label('bfpcert_approved_recommending_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err">{{ $errors->first('ba_business_account_no') }}</span>
                        <div class="form-icon-user">
                             {{ Form::text('bfpcert_approved_recommending_position', $data->bfpcert_approved_recommending_position, array('class' => 'form-control','id'=>'bfpcert_approved_recommending_position','required'=>'required')) }}
                        </div>
                        <span class="validate-err" id="err_p_address_street_name"></span>
                    </div>
                </div>
               
            
                
                <div class="col-md-8">
                    <div class="form-group" id="approved">
                        {{ Form::label('bfpcert_approved', __('Approved By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <span class="validate-err"></span>
                        <div class="form-icon-user">
                            {{ Form::select('bfpcert_approved',$employee,$data->bfpcert_approved, array('class' => 'form-control select3','id'=>'bfpcert_approved','required'=>'required')) }}
                        </div>
                        <span class="validate-err" id="err_p_bio_approved"></span>
                    </div>
                </div>
                
            
            
                
                <div class="col-md-4" >
               
                <!-- <div class="col-md-4" id="myDiv4" style="display: none;"> -->
                
                 
                <div class="form-group">
                    {{ Form::label('bfpcert_approved_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bfpcert_approved_position') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('bfpcert_approved_position', $data->bfpcert_approved_position, array('class' => 'form-control','id'=>'bfpcert_approved_position','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                  </div>
                </div>

               @endif

               @if($data->id>0)
        <div class="col-md-12">
                <!--- Start Status--->
                <div class="row" >
                    <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample3">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading3">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Uploads")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                
                                
                                <div id="flush-collapse3" class="accordion-collapse collapse" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                                
                                    <div class="basicinfodiv">
                                        <div class="row">
                                           
                                            <div class="col-lg-10 col-md-10 col-sm-10">
                                                <div class="form-group">
                                                    {{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('file','document_name','',array('class'=>'form-control '))}}  
                                                    </div>
                                                    <span class="validate-err" id="err_document"></span>
                                                </div>
                                            </div>
                                            
                                                <div class="col-lg-2 col-md-2 col-sm-2" style="padding-top:24px;">
                                                    <button type="button" style="float: right;" class="btn btn-primary " id="uploadAttachment">Upload File</button>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:-20px;"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                           <tr>
                                                               <th>File Name</th>
                                                                <th>Attachment</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <thead id="DocumentDtls">
                                                            <?php echo $arrdocDtls?>
                                                            @if(empty($arrdocDtls))
                                                            <tr>
                                                                <td colspan="3"><i>No results found.</i></td>
                                                            </tr>
                                                            @endif 
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
    <div class="row" class="col-md-12" style="text-align: left;">
        <div class="col-md-4" style="padding-top:17px;padding-left: 0px;padding-right: 0px;">            
			@if($data->recommending_status == 1 && $data->approved_status == 1)
				<a href="{{url('/bfpCertificatePrint?id='.$data->id)}}" title="Print Certificate" style="background: #20b7cc;padding: 10px;color: #fff;" data-title="Print Certificate" target="_blank" class="mx-3 btn printCertificate btn-sm digital-sign-btn" id="{{$data->id}}">
					<i class="ti-printer text-white"></i> Print
				</a>
			@endif 
            @if($data->id > 0 )
            @if($data->is_printed == 1)
            <style>
                .mx-10 {
                    margin-right: -1rem !important;
                    margin-left: -1rem !important;
                }
            </style>
                <a href="" title="Release Certificate" style="background: #20b7cc;padding: 10px;color: #fff;" data-title="Release Certificate" class="mx-10 btn release" id="{{$data->id}}">
                    <i class="ti-pencil-alt text-white"></i> Release
                </a>
            @endif
            @endif  
        </div>
        
        <div class="col-md-8" style="padding-left: 0px;padding-right: 0px;">       
            <div class="modal-footer" style="padding-right: 8px;">
                <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit"  id="submit"  name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                </div>
                 
            </div>
        </div>   
    </div>    
</div>

{{Form::close()}}
<!-- <script src="{{ asset('js/ajax_validation.js') }}"></script> -->
<script src="{{ asset('js/addBfpCertificate.js') }}?rand={{ rand(000,999) }}"></script>
<script>
$(document).ready(function() {
    var shouldSubmitForm = false;
    $('#submit').click(function (e) {
        if (!shouldSubmitForm) {
            var form = $('#mainForm');
            Swal.fire({
                title: "Are you sure?",
                text: "It will not change details after the confirmation?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    shouldSubmitForm = true;
                    form.submit();
                    $('#submit').click();
                } else {
                    console.log("Form submission canceled");
                }
            });

            e.preventDefault();
        }
    });
});
</script>