 <style>
 .modal-xll {
        max-width: 1550px !important;
    }
    .card-body-for-summary{
        padding: 5px 25px !important;
    }
</style>
 <div class="modal-body">
 <div class="row">
    @php $kindarray = array('1'=>'Building','2'=>'Land','3'=>'Machineries');@endphp
    <div class="col-lg-8 col-md-8 col-sm-8"  id="accordionFlushExample">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item" style="border: 0px solid #20B7CC;">
                    <h6 class="accordion-header" id="flush-headingone">
                        <!-- <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                            <h6 class="sub-title accordiantitle">{{--__("Owner's Information")--}}</h6>
                        </button> -->
                    </h6>
                    <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                        <div class="basicinfodiv">
                           <div class="row">
            <div class="col-xl-12" style="border: 1px solid #3ec9d6;margin-left: 8px;">
                <div class="card">
                    <div class="card-body card-body-for-summary" >
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                                    <div class="form-group">
                                            {{Form::label('tdno',__("TD-NO :"),['class'=>'form-label'])}}
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                          <span class="" id="err_rvy_revision_code">{{(isset($billingData->rptProperty->rp_tax_declaration_no))?$billingData->rptProperty->rp_tax_declaration_no:''}}</span>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                                    <div class="form-group">
                                            {{Form::label('rp_code',__("OWNER/ADMINISTRATOR :"),['class'=>'form-label'])}}
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                          <span class="" id="err_rvy_revision_code">{{(isset($billingData->rptProperty->taxpayer_name))?$billingData->rptProperty->taxpayer_name:''}}</span>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <div class="form-group">

                                    {{Form::label('rp_code',__("ADDRESS :"),['class'=>'form-label'])}}
                                   
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                  <span class="" id="err_rvy_revision_code"> {{(isset($billingData->rptProperty->property_owner_details->standard_address))?$billingData->rptProperty->property_owner_details->standard_address:''}}</span>
                                   
                                </div>
                            </div>
                    </div>
                    
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <div class="form-group">

                                    {{Form::label('barangay',__("BARANGAY LOCATION :"),['class'=>'form-label'])}}
                                   
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                  <span class="" id="err_rvy_revision_code"> {{(isset($billingData->rptProperty->barangay_details->brgy_name))?$billingData->rptProperty->barangay_details->brgy_name:''}}</span>
                                   
                                </div>
                            </div>
                    </div>
                    
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        
                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <div class="form-group">

                                    {{Form::label('rp_code',__("PROPERTY :"),['class'=>'form-label'])}}
                                   
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                  <span class="" id="err_rvy_revision_code"> {{(isset($billingData->rptProperty->pk_id))?$kindarray[$billingData->rptProperty->pk_id]:''}}</span>
                                   
                                </div>
                            </div>
                           
                    </div>
                    
                </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Land Apraisal Listing End Here------------------><br />
                          
                    
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4"  id="accordionFlushExample">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item" style="border: 0px solid #20B7CC;">
                    <h6 class="accordion-header" id="flush-headingone">
                        <!-- <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                            <h6 class="sub-title accordiantitle">{{--__("Owner's Information")--}}</h6>
                        </button> -->
                    </h6>
                    <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                        <div class="basicinfodiv">
                           <div class="row">
            <div class="col-xl-12" style="border: 1px solid #3ec9d6;margin-left: -5px;">
                <div class="card">
                    <div class="card-body card-body-for-summary">
                        <div class="row">

                

                
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">

                                    {{Form::label('rp_code',__("TAX YEARS :"),['class'=>'form-label'])}}
                                   
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                  <span class="" id="err_rvy_revision_code"> {{(isset($billingData->cb_covered_from_year))?$billingData->cb_covered_from_year:'1900'}} TO {{(isset($billingData->cb_covered_to_year))?$billingData->cb_covered_to_year:'1900'}}</span>
                                   
                                </div>
                            </div>
                    </div>
                    
                </div>
                 <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">

                                    {{Form::label('controlno',__("CONTROL NO. :"),['class'=>'form-label'])}}
                                   
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                  <span class="" id="err_rvy_revision_code"> <b>{{(isset($billingData->cb_control_no))?$billingData->cb_control_no:'0000-00000'}}</b></span>
                                   
                                </div>
                            </div>
                    </div>
                </div>
                
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">

                                    {{Form::label('rp_code',__("TOP NO. :"),['class'=>'form-label'])}}
                                   
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                  <span class="" id="err_rvy_revision_code"> <b>{{(isset($billingData->transaction_no))?$billingData->transaction_no:'0000000'}}</b></span>
                                   
                                </div>
                            </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">

                                    {{Form::label('rp_code',__("P.I.N :"),['class'=>'form-label'])}}
                                   
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                  <span class="" id="err_rvy_revision_code"> <b>{{(isset($billingData->rptProperty->rp_pin_declaration_no))?$billingData->rptProperty->rp_pin_declaration_no:''}}</b></span>
                                   
                                </div>
                            </div>
                    </div>
                    
                </div>

                 <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">

                                    {{Form::label('rp_code',__("ASSESSED VALUE :"),['class'=>'form-label'])}}
                                   
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                  <span class="" id="err_rvy_revision_code"> <b>{{(isset($billingData->rptProperty->assessed_value_for_all_kind))?Helper::decimal_format($billingData->rptProperty->assessed_value_for_all_kind):''}}</b></span>
                                   
                                </div>
                            </div>
                    </div>
                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Land Apraisal Listing End Here------------------><br />
                          
                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
 <table class="table" id="" style="font-size:11px;">
                                <thead>
                                    
                                    <tr>
                                        <th  style="font-size:10px;">{{__('NO')}}</th>
                                        <th style="font-size:10px;">{{__('Year')}}</th>
                                        <th style="font-size:10px;">{{__('Quarter')}}</th>
                                        <th style="font-size:10px;">{{__("Assessed Value")}}</th>
                                        <th style="font-size:10px;">{{__('Basic Amount')}}</th>
                                        <th style="font-size:10px;">{{__('Basic Interest')}}</th>
                                        <th style="font-size:10px;">{{__('Basic Discount')}}</th>
                                        <th style="font-size:10px;">{{__('SEF Amount')}}</th>
                                        <th style="font-size:10px;">{{__('SEF Interest')}}</th>
                                        <th style="font-size:10px;">{{__('SEF Discount')}}</th>
                                        <th style="font-size:10px;">{{__('SH Amount')}}</th>
                                        <th style="font-size:10px;">{{__('SH Interest')}}</th>
                                        <th style="font-size:10px;">{{__('SH Discount')}}</th>
                                        <th style="font-size:10px;">{{__('Total Amount Due')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $i =1;
                                    $totalDue = 0;
                                    $basicAmountTotal = 0;
                                    $basiInterst = 0;
                                    $basicDisc = 0;
                                    $sefAmount = 0;
                                    $sefInterst = 0;
                                    $sefDisc = 0;
                                    $shAmount = 0;
                                    $shInterst = 0;
                                    $shDiscount = 0;
                                    @endphp
                                    
                
                                    @foreach($billingDetails as $key=>$val)
                                     @php
                                     $newBasicPenalty = ($val->basicPenalty != null)?$val->basicPenalty:0;
                                     $newBasicDiscount = ($val->basicDiscount != null)?$val->basicDiscount:0;
                                     $newSefPenalty = ($val->sefPenalty != null)?$val->sefPenalty:0;
                                     $newSefDiscount = ($val->sefDiscount != null)?$val->sefDiscount:0;
                                     $newShPenalty = ($val->shPenalty != null)?$val->shPenalty:0;
                                     $newShDiscount = ($val->shDiscount != null)?$val->shDiscount:0;

                                    $totalAmountDue = $val->totalDue;
                                    
                                    $totalDue += $totalAmountDue;
                                    $basicAmountTotal += $val->basicAmount;
                                    $basiInterst += ($val->basicPenalty != null)?$val->basicPenalty:0;
                                    $basicDisc  += ($val->basicDiscount != null)?$val->basicDiscount:0;
                                    $sefAmount += $val->sefAmount;
                                    $sefInterst += ($val->sefPenalty != null)?$val->sefPenalty:0;
                                    $sefDisc += ($val->sefDiscount != null)?$val->sefDiscount:0;
                                    $shAmount += $val->shAmount;
                                    $shInterst += ($val->shPenalty != null)?$val->shPenalty:0;
                                    $shDiscount += ($val->shDiscount != null)?$val->shDiscount:0;
                                    @endphp
                                   
                                        <tr class="font-style">
                                            <td class="app_qurtr">{{ $i }}</td>
                                            <td class="app_qurtr">{{ $val->cbd_covered_year }}</td>
                                            <td class="app_qurtr">{{ (in_array($val->sd_mode,array_flip(Helper::billing_quarters())))?Helper::billing_quarters()[$val->sd_mode]:'Annually' }}</td>
                                             <td class="app_qurtr">{{ Helper::decimal_format($val->cbd_assessed_value) }}</td>
                                             <td class="app_qurtr">{{ Helper::decimal_format($val->basicAmount) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format(($val->basicPenalty != null)?$val->basicPenalty:0) }}</td> 
                                            <td class="app_qurtr">{{ Helper::decimal_format(($val->basicDiscount != null)?$val->basicDiscount:0) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($val->sefAmount) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format(($val->sefPenalty != null)?$val->sefPenalty:0) }}</td>
                                             <td class="app_qurtr">{{ Helper::decimal_format(($val->sefDiscount != null)?$val->sefDiscount:0) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($val->shAmount) }}</td> 
                                            <td class="app_qurtr">{{ Helper::decimal_format(($val->shPenalty != null)?$val->shPenalty:0) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format(($val->shDiscount != null)?$val->shDiscount:0) }}</td>
                                             <td class="app_qurtr">{{ Helper::decimal_format($totalAmountDue) }}</td>
                                        </tr>
                                        @php $i++; @endphp
                                    @endforeach
                                         <tr class="font-style">
                                           
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                             <td class="">Total:</td>
                                             <td class=""><input type="text" class="form-control" value="{{ Helper::decimal_format($basicAmountTotal)}}" readonly="readonly" /></td>
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($basiInterst)}}" /></td> 
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($basicDisc)}}" /></td>
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($sefAmount)}}" /></td>
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{Helper::decimal_format($sefInterst)}}" /></td>
                                             <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($sefDisc)}}" /></td>
                                             <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($shAmount)}}" /></td>
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($shInterst)}}" /></td> 
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($shDiscount)}}" /></td>
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($totalDue)}}" /></td>
                                        </tr>
                                </tbody>
                            </table>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <!-- <a href="{{ url('billingform/printbill/'.$billingData->id) }}" data-propertyid="{{ $billingData->id}}" target="_blank" class="btn btn-primary printSInglePropertyBill">Print</a> -->
</div>