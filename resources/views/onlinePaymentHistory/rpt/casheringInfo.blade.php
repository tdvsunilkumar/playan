@php
$totalTaXDue = $billingData->sum('totalBasicDue');

$totalPenaltyDue = $billingData->sum('totalPenaltyDue');

$totalDiscountDue = $billingData->sum('totalDiscountDue');

$subTotal = $totalTaXDue+$totalPenaltyDue;

$netTaxDue = $subTotal-$totalDiscountDue;

$appliedTaxCredit = 0;
$netTaxDue -= $appliedTaxCredit;
@endphp
<div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">

                                    {{Form::label('rp_code',__("Total Tax Due .................."),['class'=>'form-label'])}}
                                    <input type="hidden" name="rp_code">
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-sm-6 currency">
                               
                                            {{Form::text('total_paid_surcharge',Helper::decimal_format($totalTaXDue),array('class'=>'form-control','id'=>'total_surcharges','readonly'=>'readonly'))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        
                            </div>
                             <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">

                                    {{Form::label('rp_code',__("Penalties .........................."),['class'=>'form-label'])}}
                                    <input type="hidden" name="rp_code">
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-sm-6 currency">
                               
                                            {{Form::text('total_paid_surcharge',Helper::decimal_format($totalPenaltyDue),array('class'=>'form-control','id'=>'total_surcharges','readonly'=>'readonly'))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">

                                    {{Form::label('rp_code',__("Transfer Tax ...................."),['class'=>'form-label'])}}
                                    <input type="hidden" name="rp_code">
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-sm-6 currency">
                               
                                            {{Form::text('total_paid_surcharge','0.00',array('class'=>'form-control','id'=>'total_surcharges','readonly'=>'readonly'))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">

                                    {{Form::label('rp_code',__("Subtotal ..........................."),['class'=>'form-label'])}}
                                    <input type="hidden" name="rp_code">
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-sm-6 currency">
                               
                                            {{Form::text('total_paid_surcharge',Helper::decimal_format($subTotal),array('class'=>'form-control','id'=>'total_surcharges','readonly'=>'readonly'))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">

                                    {{Form::label('rp_code',__("Less Discount ................"),['class'=>'form-label'])}}
                                    <input type="hidden" name="rp_code">
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-sm-6 currency">
                               
                                            {{Form::text('total_paid_surcharge',Helper::decimal_format($totalDiscountDue),array('class'=>'form-control','id'=>'total_surcharges','readonly'=>'readonly'))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">

                                    {{Form::label('rp_code',__("Applied Tax Credit ........"),['class'=>'form-label'])}}
                                    <input type="hidden" name="rp_code">
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-sm-6 currency">
                               
                                            {{Form::text('prev_tax_credit_amount',Helper::decimal_format($appliedTaxCredit),array('class'=>'form-control prev_tax_credit_amount','id'=>'prev_tax_credit_amount','readonly'=>'readonly'))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">

                                    {{Form::label('rp_code',__("Net Tax Due ...................."),['class'=>'form-label'])}}
                                    <input type="hidden" name="rp_code">
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-sm-6 currency">
                               
                                            {{Form::text('net_tax_due_amount',Helper::decimal_format($netTaxDue),array('class'=>'form-control','id'=>'net_tax_due_amount','readonly'=>'readonly'))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        
                            </div>
                          

                    </div>