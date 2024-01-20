@if($returarray['tax_credit_amount_new'] > 0)
<div  class="accordion accordion-flush">
                        <div class="accordion-item">
                            <h6 class="accordion-header" id="flush-headingfive1">
                                <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="true" aria-controls="flush-headingfive">
                                    <h6 class="sub-title accordiantitle">{{__('Excess of '.config('constants.paymentTerms.'.$returarray['payment_terms']))}}</h6>
                                </button>
                    </h6>
                            <div id="flush-collapsefive" class="collapse show" aria-labelledby="flush-headingfive1" style="padding:10px;">
<div class="row">
                                     <div class="col-md-8">
                                       <div class="form-group">
                                            {{ Form::label('paymenttype', __('Payment Type'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('paymenttype',$returarray['type'], array('class' => 'form-control disabled-field','id'=>'paymenttype')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                            {{ Form::label('ordate', __('O.R. Date'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('ordate',$returarray['ordate'], array('class' => 'form-control disabled-field','id'=>'ordate')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                 <div class="row">
                                     <div class="col-md-4">
                                       <div class="form-group">
                                            {{ Form::label('orno', __('Reference O.R. No.'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('reforno',$returarray['reforno'], array('class' => 'form-control disabled-field','id'=>'reforno')) }}
                                            </div>
                                        </div>
                                    </div>

                                   <div class="col-md-4">
                                       <div class="form-group">
                                            {{ Form::label('oramount', __('O.R. Amount'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('oramount',number_format($returarray['total_paid_amount'],2), array('class' => 'form-control disabled-field','id'=>'oramount')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                            {{ Form::label('ordate', __('Credit Amount'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('ordate',number_format($returarray['tax_credit_amount_new'],2), array('class' => 'form-control disabled-field','id'=>'ordate')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                     <div class="col-md-6">
                                       <div class="form-group">
                                            {{ Form::label('from', __('From'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('from',$returarray['from'], array('class' => 'form-control disabled-field','id'=>'from')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group">
                                            {{ Form::label('to', __('To'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('to',$returarray['to'], array('class' => 'form-control disabled-field','id'=>'to')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                     <div class="col-md-12">
                                       <div class="form-group">
                                            {{ Form::label('chartofaccount', __('Chart of Account'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('chartofaccount',$returarray['chartofaccount'], array('class' => 'form-control disabled-field','id'=>'chartofaccount')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                     <div class="col-md-12">
                                       <div class="form-group">
                                            {{ Form::label('cashier', __('Cashier'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('cashier',$returarray['cashier'], array('class' => 'form-control disabled-field','id'=>'cashier')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                        </div>
                    </div>
                    @endif
                    @if($returarray['additional_credit_amount'] > 0)
<div  class="accordion accordion-flush">
                        <div class="accordion-item">
                            <h6 class="accordion-header" id="flush-headingfive1">
                                <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="true" aria-controls="flush-headingfive">
                                    <h6 class="sub-title accordiantitle">{{__('Assessed Value')}}</h6>
                                </button>
                    </h6>
                            <div id="flush-collapsefive" class="collapse show" aria-labelledby="flush-headingfive1" style="padding:10px;">
<div class="row">
                                     <div class="col-md-8">
                                       <div class="form-group">
                                            {{ Form::label('paymenttype', __('Payment Type'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('paymenttype','Assessed Value', array('class' => 'form-control disabled-field','id'=>'paymenttype')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                            {{ Form::label('ordate', __('O.R. Date'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('ordate',$returarray['ordate'], array('class' => 'form-control disabled-field','id'=>'ordate')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                 <div class="row">
                                     <div class="col-md-4">
                                       <div class="form-group">
                                            {{ Form::label('orno', __('Reference O.R. No.'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('reforno',$returarray['reforno'], array('class' => 'form-control disabled-field','id'=>'reforno')) }}
                                            </div>
                                        </div>
                                    </div>

                                   <div class="col-md-4">
                                       <div class="form-group">
                                            {{ Form::label('oramount', __('O.R. Amount'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('oramount',$returarray['oramount'], array('class' => 'form-control disabled-field','id'=>'oramount')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                            {{ Form::label('ordate', __('Credit Amount'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('ordate',$returarray['additional_credit_amount'], array('class' => 'form-control disabled-field','id'=>'ordate')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                     <div class="col-md-6">
                                       <div class="form-group">
                                            {{ Form::label('from', __('From'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('from',$returarray['from'], array('class' => 'form-control disabled-field','id'=>'from')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group">
                                            {{ Form::label('to', __('To'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('to',$returarray['to'], array('class' => 'form-control disabled-field','id'=>'to')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                     <div class="col-md-12">
                                       <div class="form-group">
                                            {{ Form::label('chartofaccount', __('Chart of Account'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('chartofaccount',$returarray['chartofaccount'], array('class' => 'form-control disabled-field','id'=>'chartofaccount')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                     <div class="col-md-12">
                                       <div class="form-group">
                                            {{ Form::label('cashier', __('Cashier'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('cashier',$returarray['cashier'], array('class' => 'form-control disabled-field','id'=>'cashier')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                        </div>
                    </div>
                    @endif
                                @if($returarray['precashid'] > 0)
                                <div class="currentapplieddetail" id="currentapplieddetail">
                                  <div  class="accordion accordion-flush">
                        <div class="accordion-item">
                            <h6 class="accordion-header" id="flush-headingfive1">
                                <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="true" aria-controls="flush-headingfive">
                                    <h6 class="sub-title accordiantitle">{{__("Utilization Details")}}</h6>
                                </button>
                    </h6>
                            <div id="flush-collapsefive" class="collapse show" aria-labelledby="flush-headingfive1" style="padding:10px;">
                              
                                <div class="row">
                                     <div class="col-md-4">
                                       <div class="form-group">
                                            {{ Form::label('currentorno', __('Applied O.R. No.'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('reforno',(isset($returarray['currentreforno']))?$returarray['currentreforno']:'', array('class' => 'form-control disabled-field','id'=>'currentorno')) }}
                                            </div>
                                        </div>
                                    </div>

                                   <div class="col-md-4">
                                       <div class="form-group">
                                            {{ Form::label('currentcreditamt', __('Credited Amount'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('oramount',(isset($returarray['currentoramount']))?$returarray['currentoramount']:'', array('class' => 'form-control disabled-field','id'=>'currentcreditamt')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                            {{ Form::label('currentordate', __('O.R. Date'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('ordate',(isset($returarray['currentordate']))?$returarray['currentordate']:'', array('class' => 'form-control disabled-field','id'=>'currentordate')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                     <div class="col-md-12">
                                       <div class="form-group">
                                            {{ Form::label('chartofaccount', __('Chart of Account'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('chartofaccount',(isset($returarray['currentchartofaccount']))?$returarray['currentchartofaccount']:'', array('class' => 'form-control disabled-field','id'=>'currentchartofaccount')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                     <div class="col-md-12">
                                       <div class="form-group">
                                            {{ Form::label('cashier', __('Cashier'),['class'=>'form-label']) }}
                                             <div class="form-icon-user">
                                                {{ Form::text('cashier',(isset($returarray['currentcashier']))?$returarray['currentcashier']:'', array('class' => 'form-control disabled-field','id'=>'currentcashier')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             
                            </div>
                        </div>
                    </div>
                    </div>
                    @endif