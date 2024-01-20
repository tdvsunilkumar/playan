{{ Form::open(array('url' => 'engoccupancyapp','class'=>'formDtls','id'=>'engoccupancyapp','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    @if(($data->id)>0)
   {{ Form::hidden('eoa_application_no',$data->eoa_application_no, array('id' => 'eoa_application_no')) }}  
    @endif
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
    .field-requirement-details-status label{padding-top:5px;}
    .permitclick{  cursor:pointer;color:skyblue; }
    .orpayment > .row{ padding:10px; }
    .btn{padding: 0.575rem 0.5rem;}
    .accordion-button::after {
    background-image: url();
  }
  .form-group {
    margin-bottom: 0.5rem;
}
 </style>   
<div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_id', __('Building Permit No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebpa_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('ebpa_id',$arrPermitno,$data->ebpa_id, array('class' => 'form-control ','id'=>'ebpa_id','required'=>'required','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_ebpa_id"></span>
                            </div>
                        </div>
                       <div class="col-md-4">
                             <div class="form-group">
                                {{ Form::label('eoa_application_type', __('Application Type'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('eoa_application_type') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('eoa_application_type',array('0'=>'Partial','1'=>'Full'),$data->eoa_application_type, array('class' => 'form-control','id'=>'eoa_application_type')) }}
                                </div>
                                <span class="validate-err" id="err_eoa_application_no"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                {{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('dateissued') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::date('dateissued',$data->dateissued, array('class' => 'form-control','id'=>'dateissued','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_dateissued"></span>
                            </div>
                        </div>
                         <div class="col-md-8">
                            <div class="form-group">
                                {{ Form::label('client_id', __('Owner Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('client_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('client_id',$arrOwners,$data->client_id, array('class' => 'form-control disabled-field','id'=>'clientidnew','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_client_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('p_mobile_no', __('Contact No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('p_mobile_no') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('p_mobile_no',$data->p_mobile_no, array('class' => 'form-control ','id'=>'p_mobile_no','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_req_id"></span>
                            </div>
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
                                     {{ Form::text('rpo_address_subdivision',$data->rpo_address_subdivision, array('class' => 'form-control ','id'=>'rpo_address_subdivision','required'=>'required','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_req_id"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="divBarngayList">
                                {{ Form::label('brgy_code', __('Barangay, Muncipality, Province, Region'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('brgy_code') }}</span>
                                <div class="form-icon-user">
                                      {{ Form::select('brgy_code',$arrgetBrgyCode,$data->brgy_code,array('class'=>'form-control disabled-field','id'=>'brgy_code','required'=>'required','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_req_id"></span>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('nameofproject', __('Name of the Project:'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('nameofproject') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('nameofproject',$data->nameofproject, array('class' => 'form-control ','id'=>'nameofproject $data->rpo_address_subdivision','required'=>'required')) }}
                                </div>
                         
                                <span class="validate-err" id="err_nameofproject"></span>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('ebpa_location', __('Location:'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebpa_location') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('ebpa_location',$arrlocgetBrgyCode, array('class' => 'form-control ','id'=>'ebpa_location $data->ebpa_location','required'=>'required','readonly')) }}
                                </div>
                                <span class="validate-err" id="err_ebpa_location"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebot_id', __('Use / Character of Occupancy'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebot_id') }}</span>
                                <div class="form-icon-user">
                                      {{ Form::select('ebot_id',$arrTypeofOccupancy,$data->ebot_id,array('class'=>'form-control select3','id'=>'ebot_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_ebot_id"></span>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('ebfd_no_of_storey', __('No. of Storeys'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebfd_no_of_storey') }}</span>
                                <div class="form-icon-user">
                                      {{ Form::text('ebfd_no_of_storey',$data->ebfd_no_of_storey,array('class'=>'form-control','id'=>'ebfd_no_of_storey','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_ebfd_no_of_storey"></span>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('no_of_units', __('No. of Units'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('no_of_units') }}</span>
                                <div class="form-icon-user">
                                      {{ Form::text('no_of_units',$data->no_of_units,array('class'=>'form-control','id'=>'no_of_units','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_no_of_units"></span>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('ebfd_floor_area', __('Total Floor Area (Sq. m.)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebfd_floor_area') }}</span>
                                <div class="form-icon-user">
                                      {{ Form::text('ebfd_floor_area',$data->ebfd_floor_area,array('class'=>'form-control','id'=>'ebfd_floor_area','required'=>'required')) }}
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
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('remarks', __('Remarks'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('remarks') }}</span>
                                <div class="form-icon-user">
                                      {{ Form::text('remarks','',array('class'=>'form-control','id'=>'remarks')) }}
                                </div>
                                <span class="validate-err" id="err_ebfd_floor_area"></span>
                            </div>
                        </div>
                        
                    </div>
      <div class="row" >
        <div class="col-lg-6 col-md-6 col-sm-6"   style="padding-top: 10px;">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                            <h6 class="sub-title accordiantitle">{{__("Services")}}</h6>
                        </button>
                    </h6>
                        <div class="basicinfodiv">
                            <div class="row" style="    padding: 10px;">
                                <div class="col-md-12">
                                <div class="form-group">
                                        <div class="form-icon-user">
                                        {{Form::select('tfoc_id',$getServices,$data->tfoc_id,array('class'=>'form-control disabled-field es_id','id'=>'tfoc_id','required'=>'required'))}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  <a data-toggle="modal" href="javascript:void(0)" id="loadAddserviceForm" class="btn btn-primary" type="add">Add Service</a> -->
                            <!--------------- Land Apraisal Listing Start Here------------------>
                            <!-- <div class="row field-requirement-details-status" style="color: white;">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        {{Form::label('business_description',__('Service'),['class'=>'form-label'])}}
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        {{Form::label('completed_mark',__('Application Type'),['class'=>'form-label'])}}
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        {{Form::label('completed_mark',__('Application Name'),['class'=>'form-label'])}}
                                    </div>
                            </div> -->
                              <!--  <span class="serviceDetails service-details" id="serviceDetails">
                                 <div class="row removenaturedata pt10" style="padding-left:10px;padding-right: 10px;">
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                @if(($data->id)>0)  
                                                  <div class="form-group">
                                                    <div class="form-icon-user">
                                                    {{ Form::text('ebpa_application_no','$data->Applicationtype', array('class' => 'form-control permitclick ','id'=>'appnumber','readonly')) }}
                                                    </div>
                                                </div>
                                                @else   @php $data->class =""; @endphp
                                                 <div class="form-group">
                                                    <div class="form-icon-user">
                                                    {{ Form::text('ebpa_application_no','$data->Applicationtype', array('class' => 'form-control permitclick ','id'=>'appnumber','readonly')) }}
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                       <span id=""> </span>
                                                         {{ Form::text('Applicationtype','$data->Applicationtype', array('class' => 'form-control permitclick','id'=>'applicationtype')) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    <div class="form-icon-user"  style="">
                                                         <input type="text" id="appnumber" readonly name="ebpa_application_no"> -->
                                                       <!-- {{ Form::text('ebpa_application_no','$data->Applicationtype', array('class' => 'form-control permitclick ','id'=>'appnumber','readonly')) }}
                                                    </div>
                                                </div>
                                        </div>
                                </div>
                             </span> --> 
                        </div>
                </div>
            </div>
        <!--------------- Taxable Items Start Here---------------->
        <!-- <div class="col-lg-6 col-md-6 col-sm-6"  id="accordionFlushExample5" style="padding-top: 10px;"> 
             <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                            <h6 class="sub-title accordiantitle">{{__("Order of Payment Details")}}</h6>
                        </button>
                    </h6>
                    <div class="basicinfodiv orpayment">
                            <!--------------- Oedwe Of Payment Details------------------>
                        <!--  <div class="row">  
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('eoa_building_structure', __('Building Structure'),['class'=>'form-label']) }}<span class="text-danger" id="starbuildingstructure"></span>
                                    <span class="validate-err">{{ $errors->first('eoa_building_structure') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('eoa_building_structure',$data->eoa_building_structure, array('class' => 'form-control','id'=>'eoa_building_structure','readonly')) }}
                                    </div>
                                    <span class="validate-err" id="err_eoa_building_structure"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('eoa_lotarea', __('Lot Area(Sq. m.)'),['class'=>'form-label']) }}
                                    <span class="validate-err">{{ $errors->first('eoa_lotarea') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('eoa_lotarea',$data->eoa_lotarea, array('class' => 'form-control','id'=>'eoa_lotarea','readonly')) }}
                                    </div>
                                    <span class="validate-err" id="err_eoa_lotarea"></span>
                                </div>
                            </div>
                            
                             <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('eoa_perimeter', __('Perimeter(1 meter)'),['class'=>'form-label']) }}
                                    <span class="validate-err">{{ $errors->first('eoa_perimeter') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('eoa_perimeter',$data->eoa_perimeter, array('class' => 'form-control','id'=>'eoa_perimeter','readonly')) }}
                                    </div>
                                    <span class="validate-err" id="err_es_id"></span>
                                </div>
                            </div>
                         </div>    
                        <div class="row" style="margin-top: -24px;">  
                        {{ Form::label('dimension', __('Floor Area & Costing'),['class'=>'form-label Bold','style'=>'background:#20b7cc;color: #fff;font-size: 15px;font-weight: 600;    padding: 5px;']) }}  
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('eoa_floor_area', __('Dimension'),['class'=>'form-label']) }}
                                    <span class="validate-err">{{ $errors->first('eoa_floor_area') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('eoa_floor_area',$data->eoa_floor_area, array('class' => 'form-control','id'=>'eoa_floor_area','readonly')) }}
                                    </div>
                                    <span class="validate-err" id="err_eoa_floor_area"></span>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('eoa_firstfloorarea', __('1st Floor'),['class'=>'form-label']) }}
                                    <span class="validate-err">{{ $errors->first('eoa_firstfloorarea') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('eoa_firstfloorarea',$data->eoa_firstfloorarea, array('class' => 'form-control','id'=>'eoa_firstfloorarea','readonly')) }}
                                    </div>
                                    <span class="validate-err" id="err_es_id"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('eoa_secondfloorarea', __('2nd Floor'),['class'=>'form-label']) }}
                                    <span class="validate-err">{{ $errors->first('eoa_secondfloorarea') }}</span>
                                    <div class="form-icon-user">
                                         {{ Form::text('eoa_secondfloorarea',$data->eoa_secondfloorarea, array('class' => 'form-control ','id'=>'eoa_secondfloorarea','readonly')) }}
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
                                        {{ Form::text('eoa_projectcost',$data->eoa_projectcost, array('class' => 'form-control numeric-double amountpattern','id'=>'eoa_projectcost','readonly')) }}
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
                          <div class="row" style="margin-top: -40px;padding-right: 0px;">  
                                    <div class="row" style="padding-right: 0px;">
                                    <div class="row field-requirement-details-status">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            {{Form::label('feedec',__('Fees Description'),['class'=>'form-label btn btn-primary'])}}
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            {{Form::label('amount',__('Amount'),['class'=>'form-label btn btn-primary'])}}
                                        </div> -->
                                        <!-- <div class="col-lg-2 col-md-2 col-sm-2">
                                            {{Form::label('action',__('Action'),['class'=>'form-label btn btn-primary'])}}
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1">
                                        
                                        </div> -->
                                   <!-- </div>
                                     <span class="defaultfeesDetails" id="defaultfeesDetails" style="margin-top: -20px;">
                                     @php $i=0; @endphp
                                    @foreach($defaultFeesarr as $key=>$val)
                                    <div class="removerfeesdata row pt10">
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
                                                {{ Form::text('amountfee[]',$val->tax_amount, array('class' => 'form-control amount amountpattern','id'=>'amountfee','readonly')) }}
                                            </div>
                                            @else
                                              <div class="form-group">
                                                {{ Form::text('amountfee[]','', array('class' => 'form-control amount numeric-double','id'=>'amountfee','readonly')) }}
                                            </div>  
                                            @endif
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                 @if(empty($val->tfoc_id))
                                               
                                                @endif
                                           </div>
                                        </div>
                                        @php $i++; @endphp
                                    </div>
                                   @endforeach
                                 </span>
                                 </div> 
                        </div> --> 
                         <!-- <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                  <div class="form-group"><div class="form-icon-user">
                                    {{ Form::text('eoa_total_net_amountlabel','Total Net Amount', array('class' => 'form-control disabled-field','id'=>'eoa_total_net_amountlabel')) }}
                                      </div>
                                  </div>
                                 </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                     <div class="form-group">
                                        @php $data->eoa_total_net_amount = number_format((float)$data->eoa_total_net_amount, 2, '.', ','); @endphp
                                        {{ Form::text('eoa_total_net_amount',$data->eoa_total_net_amount, array('class' => 'form-control numeric-double disabled-field','id'=>'eoa_total_net_amount','readonly')) }}
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        
                                   </div>
                                </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                  <div class="form-group"><div class="form-icon-user">
                                    {{ Form::text('eoa_surcharge_feelabel','Surcharge Fee', array('class' => 'form-control disabled-field','id'=>'eoa_surcharge_feelabel')) }}
                                      </div>
                                  </div>
                                 </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                     <div class="form-group">
                                        @php $data->eoa_surcharge_fee = number_format((float)$data->eoa_surcharge_fee, 2, '.', ','); @endphp
                                        {{ Form::text('eoa_surcharge_fee',$data->eoa_surcharge_fee, array('class' => 'form-control numeric-double amountpattern','id'=>'eoa_surcharge_fee','readonly')) }}
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        
                                   </div>
                                </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                  <div class="form-group"><div class="form-icon-user">
                                    {{ Form::text('Totalfee','Total Fee', array('class' => 'form-control disabled-field','id'=>'Totalfee')) }}
                                      </div>
                                  </div>
                                 </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                     <div class="form-group">
                                           @php $data->eoa_total_fees = number_format((float)$data->eoa_total_fees, 2, '.', ','); @endphp
                                        {{ Form::text('eoa_total_fees',$data->eoa_total_fees, array('class' => 'form-control disabled-field','id'=>'eoa_total_fees','readonly')) }}
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        
                                   </div>
                                </div>
                         </div>
                        </div>
                </div>
            </div> -->
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
                            <div class="col-lg-2 col-md-2 col-sm-2" style="text-align:right;">
                               {{Form::label('taxable_item_qty',__('Action'),['class'=>'form-label numeric'])}}
                            </div>
                        </div>
                         <span class="requirementsDetails activity-details" id="requirementsDetails">
                             @php $i=0; @endphp
                            @foreach($arrRequirements as $key=>$val)
                            <div class="removerequirementsdata row pt10">
                                <div class="col-lg-10 col-md-10 col-sm-10">
                                  <div class="form-group"><div class="form-icon-user">
                                    <!-- {{ Form::select('reqid[]',$requirements,$val->req_id,array('class' => 'form-control reqid','required'=>'required','id'=>'reqid')) }}  -->
                                    {{$val->req_description}}
                                    @if(!empty($val->id)) 
                                    {{ Form::hidden('ofid[]',$val->id, array('id' => 'ofid')) }}
                                    @endif
                                    </div>
                                  </div>
                                 </div>
                                <!-- <div class="col-lg-1 col-md-1 col-sm-1">
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
                                @if($i>=0)
                                    <div class="col-lg-2 col-md-2 col-sm-2" style="text-align:right;">
                                         <div class="form-group">
                                            @if(!empty($val->fe_path))
                                            <a class="btn" href="{{config('constants.remoteserverurl')}}/{{$val->fe_path}}/{{$val->fe_name}}" target='_blank'><i class='ti-download'></i></a>
                                            @endif
                                          <!--  <button type="button" class="btn btn-primary btn_cancel_requiremets"><i class="ti-trash"></i></button> -->
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
                    
                   </div>
                    <div class="modal-footer">
                        <input type="button" value="{{__('Close')}}" class="btn  btn-light" data-bs-dismiss="modal">
                       <button type="button" id="declinebtn" class="btn decline-btn btn-danger">Decline</button>
                        <button type="button" id="approvebtn" class="btn approve-btn bg-success btn-primary">Accept</button>
                    </div>
        </div>    
    {{Form::close()}}
 <div id="hidenFeesHtml" class="hide">
     <div class="removerfeesdata row pt10">
        <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="form-group">
            {{ Form::select('feesdesc[]',$extrafeearr,'',array('class' => 'form-control','id'=>'feesdesc','required'=>'required')) }}
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
    <div class="removerequirementsdata row pt10">
        <div class="col-lg-5 col-md-5 col-sm-5">
            <div class="form-group">
                <div class="form-icon-user">
                {{ Form::select('reqid[]',$requirements, '', array('class' => 'form-control naofbussi natureofbussiness','required'=>'required','id'=>'natureofbussiness')) }}
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
         <div class="col-lg-3 col-md-3 col-sm-3">
                     <div class="form-group">
                       <div class="form-icon-user"><button type="button" class="btn btn-primary btn_cancel_requiremets"><i class="ti-trash"></i></button>
                       </div>
                   </div>
             </div>
    </div>
</div>    

 <script src="{{ asset('js/ajax_validation.js') }}"></script>  
 <script src="{{ asset('js/Engneering/add_occupancyapp.js') }}"></script>

 <script type="text/javascript">
    $(document).ready(function(){
     $('.numeric').numeric();
  });
</script>
  
 
           