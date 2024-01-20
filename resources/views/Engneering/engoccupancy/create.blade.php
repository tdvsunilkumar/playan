{{ Form::open(array('url' => 'engoccupancyapp','class'=>'formDtls','id'=>'engoccupancyapp','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('location_brgy_id',$data->location_brgy_id, array('id' => 'location_brgy_id')) }}
    {{ Form::hidden('brgy_code',$data->brgy_code, array('id' => 'brgy_code')) }}
    @if(($data->id)>0)
   {{ Form::hidden('eoa_application_no',$data->eoa_application_no, array('id' => 'eoa_application_no')) }}  
    @endif

@php
    $disabled = ($issurcharge>0)?'':'disabled-field';
@endphp
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
    .field-requirement-details-status label {
    padding-top: 13px;
}
    .form-group {
        margin-bottom: 0rem;
    }
    
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
                            <div class="form-group" id="ebpa_id_group">
                                {{ Form::label('ebpa_id', __('Building Permit No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebpa_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('ebpa_id',$arrPermitno,$data->ebpa_id, array('class' => 'form-control ','id'=>'ebpa_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_ebpa_id"></span>
                            </div>
                        </div>
                       <div class="col-md-3">
                             <div class="form-group" id="eoa_application_type_group">
                                {{ Form::label('eoa_application_type', __('Application Type'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('eoa_application_type') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('eoa_application_type',array('0'=>'Partial','1'=>'Full'),$data->eoa_application_type, array('class' => 'form-control','id'=>'eoa_application_type')) }}
                                </div>
                                <span class="validate-err" id="err_eoa_application_no"></span>
                            </div>
                        </div>
                         <div class="col-md-3">
                             <div class="form-group">
                                {{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('dateissued') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::date('dateissued',$data->dateissued, array('class' => 'form-control','id'=>'dateissued','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_dateissued"></span>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('client_id', __('Owner Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('client_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('client_id',$arrOwners,$data->client_id, array('class' => 'form-control disabled-field','id'=>'clientidnew','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_client_id"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('p_mobile_no', __('Contact No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('p_mobile_no') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('p_mobile_no',$data->p_mobile_no, array('class' => 'form-control ','id'=>'p_mobile_no','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_req_id"></span>
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('rpo_address_house_lot_no', __('House/ Lot No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('rpo_address_house_lot_no') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('rpo_address_house_lot_no',$data->rpo_address_house_lot_no, array('class' => 'form-control disabled-field','id'=>'rpo_address_house_lot_no','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_req_id"></span>
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('rpo_address_street_name', __('Street Name:'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('rpo_address_street_name') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('rpo_address_street_name',$data->rpo_address_street_name, array('class' => 'form-control disabled-field','id'=>'rpo_address_street_name','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_req_id"></span>
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('rpo_address_subdivision', __('Subdivision'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('rpo_address_subdivision') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('rpo_address_subdivision',$data->rpo_address_subdivision, array('class' => 'form-control disabled-field','id'=>'rpo_address_subdivision','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_req_id"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="divBarngayList">
                                {{ Form::label('brgy_codetext', __('Barangay,Municipality,Province,Region'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('brgy_code') }}</span>
                                <div class="form-icon-user">
                                      {{ Form::text('brgy_codetext',$fulladdress,array('class'=>'form-control disabled-field','id'=>'brgy_codetext','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_req_id"></span>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('nameofproject', __('Name of the Project:'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('nameofproject') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('nameofproject',$data->nameofproject, array('class' => 'form-control disabled-field','id'=>'nameofproject','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_nameofproject"></span>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group" id="divLocbarangay">
                                {{ Form::label('location_brgy_id', __('Location of Construction:'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('location_brgy_id') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('location_brgytext',$arrlocgetBrgyCode, array('class' => 'form-control disabled-field','id'=>'location_brgytext','required'=>'required','placeholder'=>'location')) }}
                                </div>
                                <span class="validate-err" id="err_ebpa_location"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebot_id', __('Use / Character of Occupancy'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebot_id') }}</span>
                                <div class="form-icon-user">
                                      {{ Form::select('ebot_id',$arrTypeofOccupancy,$data->ebot_id,array('class'=>'form-control disabled-field','id'=>'ebot_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_ebot_id"></span>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('ebfd_no_of_storey', __('No. of Storeys'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebfd_no_of_storey') }}</span>
                                <div class="form-icon-user">
                                      {{ Form::text('ebfd_no_of_storey',$data->ebfd_no_of_storey,array('class'=>'form-control disabled-field','id'=>'ebfd_no_of_storey','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_ebfd_no_of_storey"></span>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('no_of_units', __('No. of Units'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('no_of_units') }}</span>
                                <div class="form-icon-user">
                                      {{ Form::text('no_of_units',$data->no_of_units,array('class'=>'form-control disabled-field','id'=>'no_of_units')) }}
                                </div>
                                <span class="validate-err" id="err_no_of_units"></span>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('ebfd_floor_area', __('Total Floor Area (Sq. m.)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebfd_floor_area') }}</span>
                                <div class="form-icon-user">
                                      {{ Form::text('ebfd_floor_area',$data->ebfd_floor_area,array('class'=>'form-control disabled-field','id'=>'ebfd_floor_area','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_ebfd_floor_area"></span>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('eoa_date_of_completion', __('Date of Completion'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('eoa_date_of_completion') }}</span>
                                <div class="form-icon-user">
                                      {{ Form::date('eoa_date_of_completion',$data->eoa_date_of_completion,array('class'=>'form-control','id'=>'eoa_date_of_completion','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_eoa_date_of_completion"></span>
                            </div>
                        </div>
                        <div class="col-md-12" style="padding-top: 10px;">
                            <div class="form-group">
                                    <div class="form-icon-user">
                                    {{Form::select('tfoc_id',$getServices,$data->tfoc_id,array('class'=>'form-control disabled-field es_id','id'=>'tfoc_id','required'=>'required'))}}
                                    </div>
                                </div>
                        </div>
                    </div>
      <div class="row" >
        <!--------------- Taxable Items Start Here---------------->
        <div class="col-lg-6 col-md-6 col-sm-6"  id="accordionFlushExample5" style="padding-top: 10px;"> 
             <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                            <h6 class="sub-title accordiantitle">{{__("Order of Payment Details")}}</h6>
                        </button>
                    </h6>
                    <div class="basicinfodiv orpayment">
                            <!--------------- Oedwe Of Payment Details------------------>
                         <div class="row">  
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('eoa_building_structure', __('Building Structure'),['class'=>'form-label']) }}<span class="text-danger" id="starbuildingstructure"></span>
                                    <span class="validate-err">{{ $errors->first('eoa_building_structure') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('eoa_building_structure',$data->eoa_building_structure, array('class' => 'form-control','id'=>'eoa_building_structure')) }}
                                    </div>
                                    <span class="validate-err" id="err_eoa_building_structure"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('eoa_lotarea', __('Lot Area (Sq. M.)'),['class'=>'form-label']) }}
                                    <span class="validate-err">{{ $errors->first('eoa_lotarea') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('eoa_lotarea',$data->eoa_lotarea, array('class' => 'form-control disabled-field','id'=>'eoa_lotarea')) }}
                                    </div>
                                    <span class="validate-err" id="err_eoa_lotarea"></span>
                                </div>
                            </div>
                            
                             <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('eoa_perimeter', __('Peremeter (1 mtr)'),['class'=>'form-label']) }}
                                    <span class="validate-err">{{ $errors->first('eoa_perimeter') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('eoa_perimeter',$data->eoa_perimeter, array('class' => 'form-control disabled-field','id'=>'eoa_perimeter')) }}
                                    </div>
                                    <span class="validate-err" id="err_es_id"></span>
                                </div>
                            </div>
                         </div>    
                        <div class="row">  
                        {{ Form::label('dimension', __('Floor Area & Costing'),['class'=>'form-label Bold','style'=>'background: #20b7cc;padding: 7px;color: #fff;']) }}  
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('eoa_floor_area', __('Dimension'),['class'=>'form-label']) }}
                                    <span class="validate-err">{{ $errors->first('eoa_floor_area') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('eoa_floor_area',$data->eoa_floor_area, array('class' => 'form-control disabled-field','id'=>'eoa_floor_area')) }}
                                    </div>
                                    <span class="validate-err" id="err_eoa_floor_area"></span>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('eoa_firstfloorarea', __('1st Floor'),['class'=>'form-label']) }}
                                    <span class="validate-err">{{ $errors->first('eoa_firstfloorarea') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('eoa_firstfloorarea',$data->eoa_firstfloorarea, array('class' => 'form-control disabled-field','id'=>'eoa_firstfloorarea')) }}
                                    </div>
                                    <span class="validate-err" id="err_es_id"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('eoa_secondfloorarea', __('2nd Floor'),['class'=>'form-label']) }}
                                    <span class="validate-err">{{ $errors->first('eoa_secondfloorarea') }}</span>
                                    <div class="form-icon-user">
                                         {{ Form::text('eoa_secondfloorarea',$data->eoa_secondfloorarea, array('class' => 'form-control disabled-field','id'=>'eoa_secondfloorarea')) }}
                                    </div>
                                    <span class="validate-err" id="err_eoa_secondfloorarea"></span>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                     {{ Form::label('eoa_projectcost', __('Project Cost'),['class'=>'form-label Bold']) }}
                                    <span class="validate-err">{{ $errors->first('eoa_projectcost') }}</span>
                                    <div class="form-icon-user">
                                        @php $data->eoa_projectcost = number_format((float)$data->eoa_projectcost, 2, '.', ','); @endphp
                                         <div class="form-icon-user currency">
                                        {{ Form::text('eoa_projectcost',$data->eoa_projectcost, array('class' => 'form-control numeric-double amountpattern disabled-field','id'=>'eoa_projectcost')) }}
                                        <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                    </div>
                                    <span class="validate-err" id="err_eoa_projectcost"></span>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                   {{ Form::label('eoa_or_no', __('O.R. No.'),['class'=>'form-label Bold']) }}
                                    <span class="validate-err">{{ $errors->first('eoa_or_no') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('eoa_or_no','', array('class' => 'form-control disabled-field','id'=>'eoa_or_no')) }}
                                    </div>
                                    <span class="validate-err" id="err_es_id"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                   {{ Form::label('eoa_date_paid', __('Date Paid'),['class'=>'form-label Bold']) }} 
                                    <span class="validate-err">{{ $errors->first('eoa_date_paid') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::date('eoa_date_paid',$data->eoa_date_paid, array('class' => 'form-control disabled-field','id'=>'eoa_date_paid')) }}
                                    </div>
                                    <span class="validate-err" id="err_es_id"></span>
                                </div>
                            </div>
                         </div>
                          <div class="row" style="padding-right:0px;margin-top: -25px;">  
                                    <div class="row" style="padding-right:0px;">
                                    <div class="row field-requirement-details-status" style="color:white;">

                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            {{Form::label('feedec',__('Fees Description'),['class'=>'form-label'])}}
                                        </div>
                                        <div class="col-lg-4 col-md-3 col-sm-3">
                                            {{Form::label('amount',__('Amount'),['class'=>'form-label '])}}
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            {{Form::label('action',__('Action'),['class'=>'form-label'])}}
                                        </div>
                                        <!-- <div class="col-lg-1 col-md-1 col-sm-1">
                                         <span class="btn_addmore_feedetails btn btn-primary" id="btn_addmore_feedetails" style="color:white;"><i class="ti-plus"></i></span>
                                        </div> -->
                                   </div>
                                     <span class="defaultfeesDetails" id="defaultfeesDetails">
                                     @php $i=0; @endphp
                                    @foreach($defaultFeesarr as $key=>$val)
                                    <div class="removerfeesdata row pt10" style="padding:4px;">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                          <div class="form-group"><div class="form-icon-user">
                                             @if(empty($val->is_default))
                                              {{ Form::hidden('istfocid[]',0, array('id' => 'istfocid')) }}
                                                {{ Form::text('feesdesc[]',$val->fees_description, array('class' => 'form-control ','id'=>'feesdesc','required'=>'required','readonly'=>'true')) }}
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
                                                {{ Form::text('amountfee[]',$val->tax_amount, array('class' => 'form-control amount amountpattern','id'=>'amountfee')) }}
                                            </div>
                                            @else
                                              <div class="form-group">
                                                {{ Form::text('amountfee[]','', array('class' => 'form-control amount numeric-double','id'=>'amountfee')) }}
                                            </div>  
                                            @endif
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                 @if(empty($val->tfoc_id))
                                                <button type="button" class="btn btn-primary btn_cancel_feesdetail" value="{{$val->id}}"><i class="ti-trash"></i></button>
                                                @endif
                                           </div>
                                        </div>
                                        @php $i++; @endphp
                                    </div>
                                   @endforeach
                                 </span>
                                 </div> 
                        </div>
                         <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6" style="padding-top:5px;">
                                  <div class="form-group"><div class="form-icon-user">
                                    {{ Form::text('eoa_total_net_amountlabel','Total Net Amount', array('class' => 'form-control disabled-field','id'=>'eoa_total_net_amountlabel')) }}
                                      </div>
                                  </div>
                                 </div>
                                <div class="col-lg-4 col-md-4 col-sm-4" style="padding-top:5px;">
                                     <div class="form-group">
                                        @php $data->eoa_total_net_amount = number_format((float)$data->eoa_total_net_amount, 2, '.', ','); @endphp
                                        {{ Form::text('eoa_total_net_amount',$data->eoa_total_net_amount, array('class' => 'form-control numeric-double disabled-field','id'=>'eoa_total_net_amount')) }}
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        
                                   </div>
                                </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6" style="padding-top:5px;">
                                  <div class="form-group"><div class="form-icon-user">
                                    {{ Form::text('eoa_surcharge_feelabel','Surcharge Fee', array('class' => 'form-control disabled-field','id'=>'eoa_surcharge_feelabel')) }}
                                      </div>
                                  </div>
                                 </div>
                                <div class="col-lg-4 col-md-4 col-sm-4" style="padding-top:5px;">
                                     <div class="form-group">
                                        @php $data->eoa_surcharge_fee = number_format((float)$data->eoa_surcharge_fee, 2, '.', ','); @endphp
                                        {{ Form::text('eoa_surcharge_fee',$data->eoa_surcharge_fee, array('class' => 'form-control numeric-double amountpattern '.$disabled ,'id'=>'eoa_surcharge_fee')) }}
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        
                                   </div>
                                </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6" style="padding-top:5px;">
                                  <div class="form-group"><div class="form-icon-user">
                                    {{ Form::text('Totalfee','Total Fee', array('class' => 'form-control disabled-field','id'=>'Totalfee')) }}
                                      </div>
                                  </div>
                                 </div>
                                <div class="col-lg-4 col-md-4 col-sm-4" style="padding-top:5px;">
                                     <div class="form-group">
                                           @php $data->eoa_total_fees = number_format((float)$data->eoa_total_fees, 2, '.', ','); @endphp
                                        {{ Form::text('eoa_total_fees',$data->eoa_total_fees, array('class' => 'form-control disabled-field','id'=>'eoa_total_fees')) }}
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
          </div>
          <!---rquirements----->
          <div class="col-lg-6 col-md-6 col-sm-6"  id="accordionFlushExample5" style="padding-top: 10px;"> 
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                            <h6 class="sub-title accordiantitle">{{__("Requirements")}}</h6>
                        </button>
                    </h6>
                      <div class="row" style="width: 100%; margin: 0px;padding-bottom: 20px;">
                        <div class="row field-requirement-details-status" style="color:white">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                {{Form::label('subclass_id',__('Requirements'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                {{Form::label('taxable_item_name',__('Status'),['class'=>'form-label'])}}
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                {{Form::label('taxable_item_qty',__('File'),['class'=>'form-label numeric'])}}
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2" style="text-align: end;padding-right: 30px;">
                                <span class="btn_addmore_activity btn btn-primary" id="btn_addmore_activity" style="color:white;"><i class="ti-plus"></i></span>
                            </div>
                        </div>
                         <span class="requirementsDetails activity-details" id="requirementsDetails">
                             @php $i=0; @endphp
                            @foreach($arrRequirements as $key=>$val)
                            <div class="removerequirementsdata row pt10" style="padding:5px;">
                                <div class="col-lg-6 col-md-5 col-sm-5">
                                  <div class="form-group" id="reqid_group"><div class="form-icon-user">
                                    {{ Form::select('reqid[]',$requirements,$val->req_id,array('class' => 'form-control reqid','required'=>'required','id'=>'reqid'.$i)) }}
                                    @if(!empty($val->id)) 
                                    {{ Form::hidden('ofid[]',$val->id, array('id' => 'ofid')) }}
                                    @endif
                                    </div>
                                  </div>
                                 </div>
                                <div class="col-lg-1 col-md-1 col-sm-1">
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
                                @if($i>=0)
                                    <div class="col-lg-2 col-md-2 col-sm-2" style="text-align:end;padding-right:35px;">
                                         <div class="form-group">
                                            @if(!empty($val->fe_path))
                                            <a class="btn" href="{{asset('uploads/')}}/{{$val->fe_path}}/{{$val->fe_name}}" target='_blank'><i class='ti-download'></i></a>
                                            @endif
                                            @if(!empty($data->id))
                                           <button type="button" fileid="{{$val->id}}" value="{{$val->rid}}" class="btn btn-danger btn_cancel_requiremets"><i class="ti-trash"></i></button>
                                           @else
                                           <button type="button" fileid="" value="" class="btn btn-danger btn_cancel_requiremets"><i class="ti-trash"></i></button>
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
            </div>
          </div>
        <!--------------- Taxable Items End Here------------------>
      </div>
                <div class="row"> 
                    @if(!empty($data->id))
                      @if($data->eoa_total_fees > 0)
                      @if($data->top_transaction_type_id > 0)
                        <div class="col-md-2">
                          
                             @if(empty($data->is_approve))
                            <button style="margin-top: 25px;" type="button" name="submit" value="{{$data->id}}" id="makeApprove" class="btn  btn-primary {{ ($data->is_approve)>0?__('disabled -field'):__('')}}">Approve</button>
                            @endif
                         
                       </div>
                       @endif
                       <div class="col-md-2">
                        @if(empty($data->top_transaction_type_id))
                         <div class="form-group">
                            <button style="margin-top: 25px;" type="button" name="submit" value="{{$data->id}}"  id="saveorderpayment" class="btn  btn-primary">Submit</button>
                         </div>
                         @endif
                       </div>
                      @endif
                       @if($data->id)
                         @if($data->top_transaction_type_id > 0 && $data->is_approve > 0)
                          <div class="col-md-2" style="    text-align: end;">
                           <div class="form-group">
                           <!--  <button style="margin-top: 25px;" type="button" name="submit"  value="{{ url('/engoccupancyapp/Printorder?id=').''.$data->id }}"  id="printorder" class="btn  btn-primary"><i class="ti-printer text-white"></i>&nbsp;Print Order</button>
                           </div> -->
                            <a  type="button" href="{{ url('/engoccupancyapp/Printorder?id=').''.$data->id }}" target="_blank" style="float: end;" class="btn btn-primary btnPrintclearance mt-2 digital-sign-btn" id="{{$data->id}}"><i class="ti-printer text-white" ></i> Print Order</a>
                          </div>
                         @endif
                       @endif
                     @endif
                   </div>
                    <div class="modal-footer">
                         @if($data->id > 0)
                         @if($data->eoa_is_permit_released == 0 && $data->is_approve > 0)
                         <button style="margin-top: 5px;" type="button" name="releasepermit" value="{{$data->id}}"  serviceid="" id="releasepermit" class="btn  btn-primary ">Release Permit</button>
                         @endif
                        @endif 
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary">
                    </div>
        </div>    
    {{Form::close()}}
 <div id="hidenFeesHtml" class="hide">
     <div class="removerfeesdata row pt10">
        <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="form-group">
            {{ Form::select('feesdesc[]',$extrafeearr,'',array('class' => 'form-control','id'=>'feesdesc')) }}
            {{ Form::hidden('istfocid[]',1, array('id' => 'istfocid')) }}
          </div>
         </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
              <div class="form-group">
                {{ Form::text('amountfee[]','', array('class' => 'form-control amount numeric-double','id'=>'amountfee')) }}
            </div> 
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <button type="button" class="btn btn-primary btn_cancel_feesdetail"><i class="ti-trash"></i></button>
           </div>
        </div>
    </div> 
</div>   
<div id="hidenarequirementHtml" class="hide">
    <div class="removerequirementsdata row pt10" style="padding:5px;">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group" id="reqid_group">
                <div class="form-icon-user">
                {{ Form::select('reqid[]',$requirements,'',array('class' => 'form-control reqid','required'=>'required','id'=>'reqid0')) }}
                </div>
            </div>
        </div>
         <div class="col-lg-1 col-md-1 col-sm-1">
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
         <div class="col-lg-2 col-md-2 col-sm-2" style="text-align: end;padding-right: 30px;">
                     <div class="form-group">
                       <div class="form-icon-user"><button type="button" class="btn btn-danger btn_cancel_requiremets" style="    padding: 5px 8px;"><i class="ti-trash"></i></button>
                       </div>
                   </div>
             </div>
    </div>
</div>    

 <script src="{{ asset('js/ajax_validation.js') }}"></script>  
 <script src="{{ asset('js/Engneering/add_occupancyapp.js') }}?rand={{ rand(000,999) }}"></script>

 <script type="text/javascript">
    $(document).ready(function(){
     $('.numeric').numeric();
     
  });
</script>
  
 
           