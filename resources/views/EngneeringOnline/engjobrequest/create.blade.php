{{ Form::open(array('url' => 'engjobrequestonline','class'=>'formDtls','id'=>'storeJobService','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->frgn_ejr_id, array('id' => 'id')) }}
    {{ Form::hidden('appid',$data->id, array('id' => 'appid')) }}
    {{ Form::hidden('es_id',$data->es_id, array('id' => 'es_idhidden')) }}
    {{ Form::hidden('tfoc_id',$data->tfoc_id, array('id' => 'tfoc_idhidden')) }}
 <style>
    .modal.show .modal-dialog {
        transform: none;
        width: 1200px;
    }
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
    .permitclick{  cursor:pointer;color:skyblue; }
    .orpayment > .row{ padding:10px; }
    .btn{padding: 0.575rem 0.5rem;}
    .accordion-button::after {
    background-image: url();
  }
 </style>   
<div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('client_id', __('Applicant Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('client_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('client_id',$arrOwners,$data->client_id, array('class' => 'form-control select3','id'=>'client_id','required'=>'required','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_client_id"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('p_mobile_no', __('Contact No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('p_mobile_no') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('p_mobile_no',$data->p_mobile_no, array('class' => 'form-control ','id'=>'pmobileno','required'=>'required','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_req_id"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            @if(($data->id)>0)
                            <div class="form-group">
                                {{ Form::label('ejr_jobrequest_no', __('Job Request No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ejr_jobrequest_no') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('ejr_jobrequest_no',$data->ejr_jobrequest_no, array('class' => 'form-control ','id'=>'ejr_jobrequest_no','disabled'=>true,'required'=>'required','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_tfoc_id"></span>
                            </div>
                            @else
                             <div class="form-group">
                                {{ Form::label('ejr_jobrequest_no', __('Job Request No.'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('ejr_jobrequest_no') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('ejr_jobrequest_no',$data->ejr_jobrequest_no, array('class' => 'form-control','id'=>'ejr_jobrequest_no','readonly','disabled'=>true,)) }}
                                </div>
                                <span class="validate-err" id="err_tfoc_id"></span>
                            </div>
                            @endif
                        </div>
                        
                         <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('rpo_address_house_lot_no', __('House/ Lot No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('rpo_address_house_lot_no') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('rpo_address_house_lot_no',$data->rpo_address_house_lot_no, array('class' => 'form-control ','id'=>'rpo_address_house_lot_no','required'=>'required','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_req_id"></span>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('rpo_address_street_name', __('Street Name:'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('rpo_address_street_name') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('rpo_address_street_name',$data->rpo_address_street_name, array('class' => 'form-control ','id'=>'rpo_address_street_name','required'=>'required','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_req_id"></span>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('rpo_address_subdivision', __('Subdivision'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('rpo_address_subdivision') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('rpo_address_subdivision',$data->rpo_address_subdivision, array('class' => 'form-control ','id'=>'rpo_address_subdivision1','required'=>'required','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_req_id"></span>
                            </div>
                        </div>
                        <div class="col-md-3" id="divBarngayList">
                            <div class="form-group">
                                {{ Form::label('brgy_code', __('Barangay, Municipality, Province, Region:'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('brgy_code') }}</span>
                                <div class="form-icon-user disabled-field">
                                      {{ Form::select('brgy_code',$arrgetBrgyCode,$data->brgy_code,array('class'=>'form-control','id'=>'brgy_code','required'=>'required','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_req_id"></span>
                            </div>
                        </div>
                         <div class="col-md-3" id="divBarngayList">
                            <div class="form-group">
                                {{ Form::label('falcno', __('Falc No.:'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('falcno') }}</span>
                                <div class="form-icon-user disabled-field">
                                      {{ Form::select('falcno',array('0'=>'1','1'=>'2'),'',array('class'=>'form-control','id'=>'falcno','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_falcno"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('ebpa_location', __('Location:'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebpa_location') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('ebpa_location',$locationofapp, array('class' => 'form-control ','id'=>'ebpa_location','required'=>'required','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_ebpa_location"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('ebpa_remarks', __('Remarks:'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('ebpa_remarks') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('ebpa_remarks','', array('class' => 'form-control ','id'=>'ebpa_remarks $data->ebpa_remarks')) }}
                                </div>
                                <span class="validate-err" id="err_ebpa_location"></span>
                            </div>
                        </div>
                    </div>
      <div class="row" >
        <!--------------- Taxable Items Start Here---------------->
        <div class="col-lg-6 col-md-6 col-sm-6"   style="padding-top: 10px;">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                            <h6 class="sub-title accordiantitle">{{__("Services")}}</h6>
                        </button>
                    </h6>
                        <div class="basicinfodiv">
                            <!--  <a data-toggle="modal" href="javascript:void(0)" id="loadAddserviceForm" class="btn btn-primary" type="add">Add Service</a> -->
                            <!--------------- Land Apraisal Listing Start Here------------------>
                            <div class="row field-requirement-details-status" style="color: white;">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        {{Form::label('business_description',__('Service'),['class'=>'form-label'])}}
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        {{Form::label('completed_mark',__('Application Type'),['class'=>'form-label'])}}
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        {{Form::label('completed_mark',__('Application Name'),['class'=>'form-label'])}}
                                    </div>
                            </div>
                               <span class="serviceDetails service-details" id="serviceDetails">
                                 <div class="row removenaturedata pt10" style="padding-left:10px;padding-right: 10px;">
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                @if(($data->id)>0)  
                                                  <div class="form-group">
                                                    <div class="form-icon-user">
                                                    {{Form::select('es_id',$arrGetservices,$data->es_id,array('class'=>'form-control select3 es_id','id'=>'es_id','required'=>'required','disabled'=>true))}}
                                                    </div>
                                                </div>
                                                @else   @php $data->class =""; @endphp
                                                 <div class="form-group">
                                                    <div class="form-icon-user">
                                                    {{Form::select('es_id',$arrGetservices,$data->es_id,array('class'=>'form-control select3 es_id','id'=>'es_id','required'=>'required'))}}
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                       <span id=""> </span>
                                                         {{ Form::text('Applicationtype',$data->Applicationtype, array('class' => 'form-control permitclick','id'=>'applicationtype')) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    <div class="form-icon-user"  style="">
                                                       <!--  <input type="text" id="appnumber" readonly name="ebpa_application_no"> -->
                                                       {{ Form::text('ebpa_application_no',$data->Applicationtype, array('class' => 'form-control permitclick '.$data->class,'id'=>'appnumber','readonly')) }}
                                                    </div>
                                                </div>
                                        </div>
                                </div>
                             </span>
                        </div>
                </div>
            </div>
        </div>
             <!-- <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                            <h6 class="sub-title accordiantitle">{{__("Order of Payment Details")}}</h6>
                        </button>
                    </h6>
                    <div class="basicinfodiv orpayment"> -->
                            <!--------------- Oedwe Of Payment Details------------------>
                         <!-- <div class="row">  
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('ejr_project_name', __('Name of Project'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('ejr_project_name') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('ejr_project_name',$data->ejr_project_name, array('class' => 'form-control','id'=>'ejr_project_name')) }}
                                    </div>
                                    <span class="validate-err" id="err_es_id"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('ejr_lotarea', __('Lot Area(Sq. m.)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('ejr_lotarea') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('ejr_lotarea',$data->ejr_lotarea, array('class' => 'form-control','id'=>'ejr_lotarea')) }}
                                    </div>
                                    <span class="validate-err" id="err_es_id"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('ejr_perimeter', __('Perimeter(1 meter)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('ejr_perimeter') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('ejr_perimeter',$data->ejr_perimeter, array('class' => 'form-control','id'=>'ejr_perimeter')) }}
                                    </div>
                                    <span class="validate-err" id="err_es_id"></span>
                                </div>
                            </div>
                         </div>    
                        <div class="row">  
                        {{ Form::label('dimension', __('Floor Area & Costing'),['class'=>'form-label Bold','style'=>'background:#20b7cc;color: #fff;font-size: 15px;font-weight: 600;    padding: 5px;']) }}
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('ebfd_floor_area', __('Dimension'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('dimension') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('ebfd_floor_area',$data->ebfd_floor_area, array('class' => 'form-control','id'=>'ebfd_floor_area3')) }}
                                    </div>
                                    <span class="validate-err" id="err_es_id"></span>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('ejr_firstfloorarea', __('1st Floor'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('ejr_firstfloorarea') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('ejr_firstfloorarea',$data->ejr_firstfloorarea, array('class' => 'form-control','id'=>'ejr_firstfloorarea')) }}
                                    </div>
                                    <span class="validate-err" id="err_es_id"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('ejr_secondfloorarea', __('2nd Floor'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('ejr_secondfloorarea') }}</span>
                                    <div class="form-icon-user">
                                         {{ Form::text('ejr_secondfloorarea',$data->ejr_secondfloorarea, array('class' => 'form-control ','id'=>'ejr_secondfloorarea')) }}
                                    </div>
                                    <span class="validate-err" id="err_req_id"></span>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                     {{ Form::label('ejr_projectcost', __('Project Cost'),['class'=>'form-label Bold']) }} <span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('ejr_projectcost') }}</span>
                                    <div class="form-icon-user">
                                         <div class="form-icon-user currency">
                                             @php $data->ejr_projectcost = number_format((float)$data->ejr_projectcost, 2, '.', ','); @endphp
                                        {{ Form::text('ejr_projectcost',$data->ejr_projectcost, array('class' => 'form-control numeric-double amountpattern','id'=>'ejr_projectcost')) }}
                                        <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                    </div>
                                    <span class="validate-err" id="err_es_id"></span>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                   {{ Form::label('ejr_or_no', __('O.R. No.'),['class'=>'form-label Bold']) }}  <span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('ejr_or_no') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('ejr_or_no','', array('class' => 'form-control disabled-field','id'=>'ejr_or_no')) }}
                                    </div>
                                    <span class="validate-err" id="err_es_id"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                   {{ Form::label('ejr_date_paid', __('Date Paid'),['class'=>'form-label Bold']) }}  <span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('ejr_date_paid') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::date('ejr_date_paid',$data->ejr_date_paid, array('class' => 'form-control disabled-field','id'=>'ejr_date_paid')) }}
                                    </div>
                                    <span class="validate-err" id="err_es_id"></span>
                                </div>
                            </div>
                           
                         </div>
                         <div class="row" style="margin-top:-30px;">
                            <div class="row field-requirement-details-status" style="color: white;">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    {{Form::label('feedec',__('Fees Description'),['class'=>'form-label btn btn-primary'])}}
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    {{Form::label('amount',__('Amount'),['class'=>'form-label btn btn-primary'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('action',__('Action'),['class'=>'form-label btn btn-primary'])}}
                                </div> -->
                               <!--  <div class="col-lg-1 col-md-1 col-sm-1">
                                </div> -->
                          <!--  </div>
                             <span class="defaultfeesDetails" id="defaultfeesDetails">
                             @php $i=0; $isapproveclass= ($data->is_approve)?'disabled-field':''; @endphp
                            @foreach($defaultFeesarr as $key=>$val)
                            <div class="removerfeesdata row pt10">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                  <div class="form-group"><div class="form-icon-user">
                                     @if(empty($val->is_default))
                                     {{ Form::hidden('istfocid[]',0, array('id' => 'istfocid')) }}
                                     {{Form::text('feesdesc[]',$val->fees_description, array('class' => 'form-control','id'=>'feesdesc','required'=>'required','readonly'=>'true'))}}
                                     @else
                                     {{ Form::hidden('istfocid[]',1, array('id' => 'istfocid')) }}
                                     {{ Form::text('feesdesc[]',$val->fees_description, array('class' => 'form-control disabled-field','id'=>'feesdesc','required'=>'required','readonly'=>'true')) }}
                                     @endif
                                      </div>
                                  </div>
                                 </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    @if(!empty($val->tax_amount))
                                     <div class="form-group">
                                         @php $val->tax_amount = number_format((float)$val->tax_amount, 2, '.', ','); @endphp
                                        {{ Form::text('amountfee[]',$val->tax_amount, array('class' => 'form-control amount amountpattern numeric-double '.$isapproveclass,'id'=>'amountfee')) }}
                                    </div>
                                    @else
                                      <div class="form-group">
                                        {{ Form::text('amountfee[]','', array('class' => 'form-control amount numeric-double '.$isapproveclass,'id'=>'amountfee')) }}
                                    </div>  
                                    @endif
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                       @if(isset($val->is_default)) 
                                         @if(($val->is_default) >0 )
                                       
                                           @endif
                                        @endif   
                                   </div>
                                </div>
                                @php $i++; @endphp
                            </div>
                           @endforeach
                         </span>
                         </div> 
                         <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                  <div class="form-group"><div class="form-icon-user">
                                    {{ Form::text('ejr_total_net_amountlabel','Total Net Amount', array('class' => 'form-control disabled-field','id'=>'ejr_total_net_amountlabel')) }}
                                      </div>
                                  </div>
                                 </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                     <div class="form-group">
                                         @php $data->ejr_total_net_amount = number_format((float)$data->ejr_total_net_amount, 2, '.', ','); @endphp
                                        {{ Form::text('ejr_total_net_amount',$data->ejr_total_net_amount, array('class' => 'form-control numeric-double disabled-field','id'=>'ejr_total_net_amount')) }}
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        
                                   </div>
                                </div>
                                 <div class="col-lg-8 col-md-8 col-sm-8">
                                  <div class="form-group"><div class="form-icon-user">
                                    {{ Form::text('ejr_surcharge_feelabel','Surcharge Fee', array('class' => 'form-control disabled-field','id'=>'ejr_surcharge_feelabel')) }}
                                      </div>
                                  </div>
                                 </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                     <div class="form-group">
                                         @php $data->ejr_surcharge_fee = number_format((float)$data->ejr_surcharge_fee, 2, '.', ','); 
                                         
                                         @endphp
                                        {{ Form::text('ejr_surcharge_fee',$data->ejr_surcharge_fee, array('class' => 'form-control numeric-double amountpattern '.$isapproveclass ,'id'=>'ejr_surcharge_fee')) }}
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        
                                   </div>
                                </div>
                                 <div class="col-lg-8 col-md-8 col-sm-8">
                                  <div class="form-group"><div class="form-icon-user">
                                    {{ Form::text('Totalfee','Total Fee', array('class' => 'form-control disabled-field','id'=>'Totalfee')) }}
                                      </div>
                                  </div>
                                 </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                     <div class="form-group">
                                          @php $data->ejr_totalfees = number_format((float)$data->ejr_totalfees, 2, '.', ',');  @endphp
                                        {{ Form::text('ejr_totalfees',$data->ejr_totalfees, array('class' => 'form-control disabled-field','id'=>'ejr_totalfees')) }}
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        
                                   </div>
                                </div>
                         </div>
                        </div>
                </div>
            </div>
        </div>  -->
          <!---rquirements----->
          <div class="col-lg-6 col-md-6 col-sm-6"   style="padding-top: 10px;"> 
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                            <h6 class="sub-title accordiantitle">{{__("Requirements")}}</h6>
                        </button>
                    </h6>
                      <div class="row" style="width: 100%; margin: 0px;padding-bottom: 20px;">
                        <div class="row field-requirement-details-status" style="color: white;">
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                {{Form::label('subclass_id',__('Requirements'),['class'=>'form-label'])}}
                            </div>
                            <!-- <div class="col-lg-2 col-md-2 col-sm-2">
                                {{Form::label('taxable_item_name',__('Status'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                {{Form::label('taxable_item_qty',__('File'),['class'=>'form-label numeric'])}}
                            </div> -->
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                {{Form::label('taxable_item_qty',__('Action'),['class'=>'form-label numeric'])}}
                            </div>
                        </div>
                         <span class="requirementsDetails activity-details" id="requirementsDetails">
                             @php $i=0; @endphp
                            @foreach($arrRequirements as $key=>$val)
                            <div class="removerequirementsdata row pt10">
                                <div class="col-lg-10 col-md-10 col-sm-10">
                                  <div class="form-group"><div class="form-icon-user">
                                     {{ Form::hidden('reqid[]',$val->req_id, array('id' => 'reqid')) }}   
                                    {{$val->req_description}}  {{ Form::hidden('feid[]',$val->feid, array('id' => 'feid')) }}  </div>
                                  </div>
                                 </div>
                                <!-- <div class="col-lg-2 col-md-2 col-sm-2">
                                     <div class="form-group">
                                        <div class="form-icon-user">
                                       </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        <div class="form-icon-user"><input class="form-control" name="reqfile[]" type="file" value="">
                                        </div>
                                   </div>
                                </div> -->
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                             @if($val->fe_name)<a class="btn" href="{{config('constants.remoteserverurl')}}/{{$val->fe_path}}/{{$val->fe_name}}" target='_blank'><i class='ti-download'></i></a>@endif
                                </div>
                                <!--  <div class="col-lg-1 col-md-1 col-sm-1">               
                                           <div class="form-icon-user">
                                           </div>
                                 </div> -->
                                @php $i++; @endphp
                            </div>
                            
                        @endforeach
                         </span>
                     </div>
                   </div>
            </div>
          </div>
        <!--------------- Taxable Items End Here------------------>
     </div>
                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <button type="button" id="declinebtn" class="btn decline-btn btn-danger">Decline</button>
                        <button type="button" id="approvebtn" class="btn approve-btn bg-success btn-primary">Accept</button>
                        <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
                    </div>
        </div>    
    {{Form::close()}}
<div id="hidenFeesHtml" class="hide">
     <div class="removerfeesdata row pt10">
        <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="form-group"><div class="form-icon-user">
            {{ Form::select('feesdesc[]',$extrafeearr,'', array('class' => 'form-control','id'=>'feesdesc','required'=>'required')) }}
            {{ Form::hidden('istfocid[]',1, array('id' => 'istfocid')) }}
              </div>
          </div>
         </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
              <div class="form-group">
                {{ Form::text('amountfee[]','', array('class' => 'form-control amount numeric-double','id'=>'amountfee')) }}
            </div> 
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <button type="button" class="btn btn-danger btn_cancel_feesdetail"><i class="ti-trash"></i></button>
           </div>
        </div>
    </div> 
</div>

<div id="hidenarequirementHtml" class="hide">
    <div class="removerequirementsdata row pt10">
        <div class="col-lg-5 col-md-5 col-sm-5">
            <div class="form-group">
                <div class="form-icon-user">
                {{ Form::select('reqid[]',$requirements, '', array('class' => 'form-control naofbussi natureofbussiness','required'=>'required','id'=>'natureofbussiness')) }}
                </div>
            </div>
        </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                    <div class="form-icon-user">
                    </div>
            </div>
         </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="form-group">
                    <div class="form-icon-user"><input class="form-control" name="reqfile[]" type="file" value="">
                    </div>
               </div>
            </div>
         <div class="col-lg-1 col-md-1 col-sm-1">
            <div class="form-group">
                    <div class="form-icon-user">
                    </div>
            </div>
         </div>
         <div class="col-lg-1 col-md-1 col-sm-1">
                     <div class="form-group">
                       <div class="form-icon-user"><button type="button" class="btn btn-danger btn_cancel_requiremets"><i class="ti-trash"></i></button>
                       </div>
                   </div>
             </div>
    </div>
</div> 

      <div class="modal" id="addServicemodal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-lg modalDiv" >
            <div class="modal-content" id="serviceform">
                
            </div>
        </div>
    </div> 

      <div class="modal form-inner" id="addBuildingPermitmodal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-xl modalDiv modal-dialog-scrollable">
            <div class="modal-content" id="BuildingPermit">
                
            </div>
        </div>
    </div>
    <div class="modal form-inner" id="addSanitaryPermitmodal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-xl modalDiv modal-dialog-scrollable">
            <div class="modal-content" id="SanitaryPermit">
                
            </div>
        </div>
    </div>

    <div class="modal form-inner" id="addElecticPermitmodal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-xl modalDiv modal-dialog-scrollable">
            <div class="modal-content" id="ElecticPermit">
                
            </div>
        </div>
    </div> 
     <div class="modal form-inner" id="addCivilPermitmodal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-xl modalDiv modal-dialog-scrollable">
            <div class="modal-content" id="CivilPermit">
                
            </div>
        </div>
    </div> 
     <div class="modal form-inner" id="addElectronicPermitmodal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-xl modalDiv modal-dialog-scrollable">
            <div class="modal-content" id="ElectronicPermit">
                
            </div>
        </div>
    </div> 
    <div class="modal form-inner" id="addMechanicalPermitmodal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-xl modalDiv modal-dialog-scrollable">
            <div class="modal-content" id="MechanicalPermit">
                
            </div>
        </div>
    </div> 

     <div class="modal form-inner" id="addExcavationPermitmodal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-xl modalDiv modal-dialog-scrollable">
            <div class="modal-content" id="ExcavationPermit">
                
            </div>
        </div>
    </div> 

     <div class="modal form-inner" id="addArchitecturalPermitmodal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-xl modalDiv modal-dialog-scrollable">
            <div class="modal-content" id="ArchitecturalPermit">
                
            </div>
        </div>
    </div> 

    <div class="modal form-inner" id="addFencingPermitmodal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-xl modalDiv modal-dialog-scrollable">
            <div class="modal-content" id="FencingPermit">
                
            </div>
        </div>
    </div>

       <div class="modal form-inner" id="addSignPermitmodal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-xl modalDiv modal-dialog-scrollable">
            <div class="modal-content" id="Signpermit">
                
            </div>
        </div>
    </div>  
     <div class="modal form-inner" id="addDemolitionPermitmodal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-xl modalDiv modal-dialog-scrollable">
            <div class="modal-content" id="Demolitionpermit">
                
            </div>
        </div>
    </div>

    <div class="modal form-inner" id="addElectricalrevisionmodal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-xl modalDiv modal-dialog-scrollable">
            <div class="modal-content" id="Electricalrevision">
            </div>
        </div>
    </div> 

    <div class="modal form-inner" id="addBuildingrevisionmodal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-xl modalDiv modal-dialog-scrollable">
            <div class="modal-content" id="Buildingrevision">
            </div>
        </div>
    </div> 

 <script src="{{ asset('js/Engneering/ajax_jobrequestno.js')}}?rand={{ rand(000,999) }}"></script>  
 <script src="{{ asset('js/Engneering/ajax_jobrequestonline.js')}}?rand={{ rand(000,999) }}"></script>

 <script type="text/javascript">
    $(document).ready(function(){
     $('.numeric').numeric();
  });
</script>
  
 
           