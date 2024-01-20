 <style>
 .modal-xll {
    /* max-width: 2071px !important; */
    display: contents;
}
</style>
 <div class="modal-body">
 <div class="row">
    @php $kindarray = array('1'=>'Building','2'=>'Land','3'=>'Machineries');@endphp
    <input type="hidden" name="receiableId" value="{{ $id }}">
    <input type="hidden" name="rp_code" value="{{(isset($propDetails->id))?$propDetails->id:''}}">
    <input type="hidden" name="rp_property_code" value="{{(isset($propDetails->rp_property_code))?$propDetails->rp_property_code:''}}">
    <input type="hidden" name="user_email" value="{{(isset($propDetails->property_owner_details->p_email_address))?$propDetails->property_owner_details->p_email_address:''}}">
    <div class="col-lg-8 col-md-8 col-sm-8"  id="accordionFlushExample">  
            <div  class="accordion accordion-flush">
                    <div class="accordion-item" >
                        <h6 class="accordion-header" id="flush-headingone">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse"  aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle">{{__("Property Details")}}</h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                            <div class="basicinfodiv">
                               <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2" style="border-right: 1px solid #20B7CC;">
                                                    <div class="form-group" style="margin-bottom: -18px;">
                                            {{Form::label('tdno',__("TD-NO :"),['class'=>'form-label'])}}
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                          <span class="" id="err_rvy_revision_code">{{(isset($propDetails->rp_tax_declaration_no))?$propDetails->rp_tax_declaration_no:''}}</span>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2" style="border-right: 1px solid #20B7CC;">
                                                    <div class="form-group" style="margin-top:-10px ;">
                                            {{Form::label('rp_code',__("Taxpayer Name :"),['class'=>'form-label'])}}
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group" style="margin-top: -10px;">
                                          <span class="" id="err_rvy_revision_code">{{(isset($propDetails->taxpayer_name))?$propDetails->taxpayer_name:''}}</span>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2" style="border-right: 1px solid #20B7CC;">
                                            <div class="form-group" style="margin-top: -12px;">

                                    {{Form::label('rp_code',__("Address :"),['class'=>'form-label'])}}
                                   
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group" style="margin-top: -12px;">
                                  <span class="" id="err_rvy_revision_code"> {{(isset($propDetails->property_owner_details->standard_address))?$propDetails->property_owner_details->standard_address:''}}</span>
                                   
                                </div>
                            </div>
                    </div>
                    
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2" style="border-right: 1px solid #20B7CC;">
                                            <div class="form-group" style="margin-top: -12px;">

                                    {{Form::label('rp_code',__("Administrator :"),['class'=>'form-label'])}}
                                   
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group" style="margin-top: -12px;">
                                  <span class="" id="err_rvy_revision_code"> {{(isset($propDetails->propertyAdmin->standard_name))?$propDetails->propertyAdmin->standard_name:''}}</span>
                                   
                                </div>
                            </div>
                    </div>
                    
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2" style="border-right: 1px solid #20B7CC;">
                                            <div class="form-group" style="margin-top: -12px;">

                                    {{Form::label('barangay',__("Location :"),['class'=>'form-label'])}}
                                   
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group" style="margin-top: -12px;">
                                  <span class="" id="err_rvy_revision_code"> {{(isset($propDetails->barangay_details->brgy_name))?$propDetails->barangay_details->brgy_name:''}}</span>
                                   
                                </div>
                            </div>
                    </div>
                    
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        
                        <div class="col-lg-2 col-md-2 col-sm-2" style="border-right: 1px solid #20B7CC;">
                                            <div class="form-group" style="margin-top: -12px;">

                                    {{Form::label('rp_code',__("Description :"),['class'=>'form-label'])}}
                                   
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group" style="margin-top: -12px;">
                                  <span class="" id="err_rvy_revision_code"> {{(isset($propDetails->rp_lot_cct_unit_desc))?$propDetails->rp_lot_cct_unit_desc:''}}</span>
                                   
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

        <div class="col-lg-4 col-md-4 col-sm-4"  id="accordionFlushExample">  
            <div  class="accordion accordion-flush">
                    <div class="accordion-item" >
                        <h6 class="accordion-header" id="flush-headingone">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse"  aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle">{{__("")}}</h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                            <div class="basicinfodiv">
                              <div class="row">

                

                
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4" style="border-right: 1px solid #20B7CC;">
                                            <div class="form-group">

                                    {{Form::label('rp_code',__("Property Index No :"),['class'=>'form-label'])}}
                                   
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                  <span class="" id="err_rvy_revision_code"> {{(isset($propDetails->rp_pin_declaration_no))?$propDetails->rp_pin_declaration_no:''}}</span>
                                   
                                </div>
                            </div>
                    </div>
                    
                </div>
                 <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4" style="border-right: 1px solid #20B7CC;">
                                            <div class="form-group" style="margin-top: -12px;">

                                    {{Form::label('controlno',__("Property Kind :"),['class'=>'form-label'])}}
                                   
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group" style="margin-top: -12px;">
                                  <span class="" id="err_rvy_revision_code"> {{(isset($propDetails->propertyKindDetails->pk_description))?$propDetails->propertyKindDetails->pk_description:''}}</span>
                                   
                                </div>
                            </div>
                    </div>
                </div>
                
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4" style="border-right: 1px solid #20B7CC;">
                                            <div class="form-group" style="margin-top: -12px;">

                                    {{Form::label('rp_code',__("Class"),['class'=>'form-label'])}}
                                   
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group" style="margin-top: -12px;">
                                  <span class="" id="err_rvy_revision_code"> {{(isset($propDetails->class_for_kind->pc_class_description))?$propDetails->class_for_kind->pc_class_description:''}}</span>
                                   
                                </div>
                            </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4" style="border-right: 1px solid #20B7CC;">
                                            <div class="form-group" style="margin-top: -12px;">

                                    {{Form::label('rp_code',__("Market Value"),['class'=>'form-label'])}}
                                   
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group" style="margin-top: -12px;">
                                  <span class="" id="err_rvy_revision_code"> {{(isset($propDetails->market_value_for_all_kind))?Helper::decimal_format($propDetails->market_value_for_all_kind):''}}</span>
                                   
                                </div>
                            </div>
                    </div>
                    
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4" style="border-right: 1px solid #20B7CC;">
                                            <div class="form-group" style="margin-top: -12px;">

                                    {{Form::label('rp_code',__("Assessed Value"),['class'=>'form-label'])}}
                                   
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group" style="margin-top: -12px;">
                                  <span class="" id="err_rvy_revision_code"> {{(isset($propDetails->assessed_value_for_all_kind))?Helper::decimal_format($propDetails->assessed_value_for_all_kind):''}}</span>
                                   
                                </div>
                            </div>
                    </div>
                    
                </div>

                 <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4" style="border-right: 1px solid #20B7CC;">
                                            <div class="form-group" style="margin-top: -12px;">

                                    {{Form::label('rp_code',__("Effectivity :"),['class'=>'form-label'])}}
                                    @php $qtrs = ['1' => '1st Quarter','2' => '2nd Quarter','3' => '3rd Quarter','4' => '4th Quarter']; @endphp
                                   
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group" style="margin-top: -12px;">
                                  <span class="" id="err_rvy_revision_code"> {{(isset($propDetails->rp_app_effective_year))?$propDetails->rp_app_effective_year:''}} - {{ (isset($propDetails->rp_app_effective_quarter) && isset($qtrs[$propDetails->rp_app_effective_quarter]))?$qtrs[$propDetails->rp_app_effective_quarter]:''}}</span>
                                   
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
        
            <div class="col-xl-12">
                <div  class="accordion accordion-flush">
                    <div class="accordion-item" >
                        <h6 class="accordion-header" id="flush-headingone">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse"  aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle">{{__("Property Details")}}</h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                           <div class="row">
                            <div class="row" style="padding-top: 10px;padding-bottom: 0px;padding-right: 0px;">
                        <div class="col-sm-8">
                           <div class="row">
                              <div class="col-sm-8">
                               <div class="form-group row">
                              <div class="col-sm-3">
                                 <div class="form-group" id="cbd_is_paid_statusID">
                                    <div class="btn-box">
                                     {{ Form::select('cbd_is_paid_status',['0'=>'Pending','4'=>'All','1'=>'Paid','2'=>'Partial'],'', array('class' => 'form-control','id'=>'cbd_is_paid_status','required'=>'required')) }}
                                    </div>
                                </div>
                              </div>
                           </div>
                              </div>
                              <div class="col-sm-4">
                                
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-4" style="padding: 0px;">
                           <div class="row">
                              <div class="col-sm-8" style="width: 82%;text-align: end;padding-left: 0px;padding-right: 0px;margin: 0px;">
                               <div class="form-group row">
                              <label for="staticEmail" class="col-sm-6 col-form-label" style="text-align:end;">Total: </label>
                              <div class="col-sm-6" style="padding-right: 0px;">
                                 <div class="form-icon-user currency">
                                    <input type="text" readonly class="form-control decimalvalue" value="{{ (isset($totalMarketValue))?number_format((float)$totalMarketValue, 2, '.', ''):0.00 }}" id="landAppraisalTotalValueToDisplay" >
                                    <div class="currency-sign"><span>Php</span></div>
                                 </div>
                              </div>
                           </div>
                              </div>
                              <div class="col-sm-4" style="width:18%;text-align: end;padding-left: 0px;">
                                 <a data-toggle="modal" href="javascript:void(0)"  id="displayAnnotationSpecialPropertyStatusModal" class="btn btn-primary sendEmailDtls" style="padding-bottom: 6px;padding-top: 6px;"><i class="ti-email text-white"></i> Send</a>
                              </div>
                           </div>
                        </div>
                     </div>
        <div class="col-xl-12" style="margin-top: -40px;">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive" id="accountReceiableDetails">
                                
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
    </div>
    <div class="modal-footer">
    <input type="button" value="{{--__('Cancel')--}}" class="btn btn-light" data-bs-dismiss="modal">
    <!-- <a href="{{-- url('billingform/printbill/'.$billingData->id) --}}" data-propertyid="{{-- $billingData->id--}}" target="_blank" class="btn btn-primary printSInglePropertyBill">Print</a> -->
</div>
<script type="text/javascript">
    $(document).ready(function(){   
        $("#cbd_is_paid_status").select3({dropdownAutoWidth : false,dropdownParent: $("#cbd_is_paid_statusID")});
    });
</script>