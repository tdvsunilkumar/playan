{{ Form::open(array('url' => 'online-payment-history/approve','class'=>'formDtls','id'=>'mainForm')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('pid',$data->pid, array('id' => 'pid')) }}
@php
    $readonly = ($data->id>0)?'readonly':'';
    $select3 = ($data->id>0)?'disabled-field':'select3';
    $isDisabledChkboxOr = ($data->id>0)?'disabled':'';
@endphp
<link href="https://fonts.cdnfonts.com/css/digital-numbers" rel="stylesheet">
<style type="text/css">
    .form-group{text-align: left;}
    .removeChequeData, .check-cash-dtls{text-align: left !important;}
    .amount-dtls .row{text-align: center;}
    .amount-dtls .row input{ margin-bottom: 5px; }
    .from-to-dtls .row{margin-bottom: -14px !important;}
    .box-border{ text-align: center;border: 1px solid #80808059;margin: 6px -2px 6px -4px; }

    .basicinfodiv .row { margin: 6px 0px 6px 0px;}
    .box-heading{height: 75px;background: black;margin: 0px !important;}
    .wht-color{color: #ffffff;}
    .blue-color{color: #3ec9d6;}
    .box-heading h4{float: left;}
    .box-heading h6{float: right;}
    .amount-heading{font-family: 'Digital Numbers', sans-serif;}
    .fee-details th{
        text-align: right; !important;
    }
    .fee-details th:nth-child(1){
        text-align: left; !important;
    }
    .fee-details td:nth-child(5), .fee-details td:nth-child(7), .fee-details td:nth-child(6) {
        background: #80808052;
        text-align: right;
    }
    .fee-details td:nth-child(2), .fee-details td:nth-child(3), .fee-details td:nth-child(4){
        background: #20b7cc42;
        text-align: right;
    }
    .fee-details tr:last-child{
        background: #80808052;
    }
    .red{
        background: red !important;
        color:#fff;
        font-weight: bold;
        text-align: right !important;
    }
    .tax-reference{ float:left; color: #00000078; }
    .label-value{color: gray;}
    .basicinfodiv input {height: 35px;}
    .radio-check input{height: 25px;width: 25px;}
    .radio-check label {padding: 7px;}
    .check-cash-dtls{ font-size:10px;}
    .check-cash-heading{background: #20B7CC;padding-top: 9px;color: #ffffff;font-weight: bold;}
    .btn-primary, .btn-danger {cursor: pointer;}
    .delete-btn-dtls{padding-top: 9px;}
    .main-form{padding: 0px;padding-left: 5px;}
    .currency-sign{border-radius: 3px 0px 0px 3px;height: 34px;margin-top: -22px;}
    #footer-details .modal-footer{border-top:unset !important;}
    #footer-details{border-top: 1px solid #f1f1f1;}
</style>
    <div class="modal-body payment-dtls">
        <div class="row">
             <div class="col-lg-4 col-md-4 col-sm-4 main-form" >  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingone">
                            <button class="accordion-button collapsed btn-primary" type="button" style="">
                                <h6 class="sub-title accordiantitle">{{__("Pay What?")}}</h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
                            <div class="basicinfodiv">
                                <div class="box-border">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('top_transaction_id', __('Transaction No.'),['class'=>'form-label']) }}
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{ Form::select('top_transaction_id',$arrTransactionNum,$data->top_transaction_id, array('class' => 'form-control top_transaction_id '.$select3,'id'=>'top_transaction_id','required'=>'required',$readonly)) }}
                                                </div>
                                                <span class="validate-err" id="err_top_transaction_id" style="text-align: right;"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="from-to-dtls">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {{ Form::label('from_year', __('From'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                       {{Form::text('from_year','',array('class'=>'form-control','readonly'=>'readonly'))}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{ Form::select('from_period',array(''=>'Please Select'),'', array('class' => 'form-control disabled-field ','id'=>'from_period','readonly'=>'readonly')) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    {{ Form::label('to', __('To'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                       {{Form::text('to_year','',array('class'=>'form-control','readonly'=>'readonly','id'=>'to_year'))}}
                                                    </div>
                                                    <span class="validate-err" id="err_top_transaction_id"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{ Form::select('to_period',array(''=>'Please Select'),'', array('class' => 'form-control disabled-field','id'=>'to_period','readonly'=>'readonly')) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-border">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <p id="busn_name"></p>
                                                <p id="busn_address"></p>
                                                <p><b id="ownar_name"></b></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="amount-dtls box-border">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Subtotal......</b></p>
                                        </div>
                                        <div class="col-md-6 currency">
                                            {{Form::text('sub_total','0.00',array('class'=>'form-control','id'=>'sub_total','readonly'=>'readonly'))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Surcharge......</b></p>
                                        </div>
                                        <div class="col-md-6 currency">
                                            {{Form::text('total_paid_surcharge','0.00',array('class'=>'form-control','id'=>'total_surcharges','readonly'=>'readonly'))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Interest......</b></p>
                                        </div>
                                        <div class="col-md-6 currency">
                                           {{Form::text('total_paid_interest','0.00',array('class'=>'form-control','id'=>'total_interest','readonly'=>'readonly'))}}
                                           <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Total Tax Due......</b></p>
                                        </div>
                                        <div class="col-md-6 currency">
                                            {{Form::text('total_amount','0.00',array('class'=>'form-control','id'=>'total_amount','readonly'=>'readonly'))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <h7 class="tax-reference">Less: Discount ....</h7>  
                                        </div>
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Tax Credit(<span class="label-value previous_or_no" style="    font-size: 10px;">OR No.</span>)......</b></p>
                                        </div>
                                        <div class="col-md-6 currency">
                                            {{Form::text('prev_tax_credit_amount','',array('class'=>'form-control numeric','id'=>'prev_tax_credit_amount','readonly'=>'readonly'))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Net Tax Due......</b></p>
                                        </div>
                                        <div class="col-md-6 currency">
                                            {{Form::text('net_tax_due_amount',$data->net_tax_due_amount,array('class'=>'form-control numeric','id'=>'net_tax_due_amount','readonly'=>'readonly'))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-border">
                                    <div class="row hide">
                                        <div class="col-md-3">
                                            <div class="d-flex radio-check">
                                                <div class="form-check form-check-inline form-group col-md-1">
                                                    {{ Form::radio('payment_terms', '1',true, array('id'=>'cash','class'=>'form-check-input code', 'required'=>'required',$isDisabledChkboxOr)) }}
                                                    {{ Form::label('cash', __('Cash'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex radio-check">
                                                <div class="form-check form-check-inline form-group col-md-1">
                                                    {{ Form::radio('payment_terms', '3', false, array('id'=>'cheque','class'=>'form-check-input code', 'required'=>'required',$isDisabledChkboxOr)) }}
                                                    {{ Form::label('cheque', __('Cheque'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex radio-check">
                                                <div class="form-check form-check-inline form-group col-md-1">
                                                    {{ Form::radio('payment_terms', '2',false, array('id'=>'bank','class'=>'form-check-input code', 'required'=>'required',$isDisabledChkboxOr)) }}
                                                    {{ Form::label('bank', __('Bank'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Amount Paid......</b></p>
                                        </div>
                                        <div class="col-md-6 currency">
                                            {{Form::text('total_paid_amount',number_format($data->total_paid_amount,2),array('class'=>'form-control numeric','id'=>'total_paid_amount'))}}
                                            <div class="currency-sign" style="margin-top: -18px;"><span>Php</span></div>
                                        </div>
                                        <span class="validate-err" id="err_total_paid_amount" style="text-align: right;"></span>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Change......</b></p>
                                        </div>
                                        <div class="col-md-6 currency">
                                            {{Form::text('total_amount_change',$data->total_amount_change,array('class'=>'form-control numeric','readonly'=>'readonly','id'=>'total_amount_change'))}}
                                            <div class="currency-sign" style="margin-top: -18px;"><span>Php</span></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="box-border">
                                    <div class="row">
                                        <div class="col-md-12"> 
                                            <div class="d-flex radio-check">
                                             <div class="form-check form-check-inline form-group">
                                                {{ Form::checkbox('isuserrange', '1', '', array('id'=>'isuserrange','class'=>'form-check-input ','disabled')) }}
                                                {{ Form::label('isuserrange', __('Manual OR-Series'),['class'=>'form-label']) }}
                                              </div>
                                            </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{Form::label('or_no',__('O.R. No.'),['class'=>'form-label'])}}
                                        </div>
                                        <div class="col-md-6">
                                            {{Form::text('or_no',$data->or_no,array('class'=>'form-control numeric disabled-field','id'=>'or_noshow','required'=>'required disabled-field',$isDisabledChkboxOr))}}
                                        </div>
                                        <span class="validate-err" id="err_or_no" style="text-align: right;"></span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{Form::label('or_no',__('O.R. Date'),['class'=>'form-label'])}}
                                        </div>
                                        <div class="col-md-6">
                                            {{Form::text('cashier_or_date',$data->cashier_or_date,array('class'=>'form-control ','id'=>'cashier_or_date'))}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{Form::label('paymentcontrolno',__('Payment Control No.'),['class'=>'form-label'])}}
                                        </div>
                                        <div class="col-md-6">
                                            {{Form::text('paymentcontrolno',$data->cashier_batch_no,array('class'=>'form-control disabled-field','id'=>'order_number','step'=>'0.01'))}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="form-icon-user" style="color:green;"><br>
                           <p>NOTE:You can manage official receipt details using this <a target="_blank" href="{{url('/bplo-or-asssignment?iscashier=1')}}">OR Assignment</a></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-8 col-sm-8 main-form">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-heading2">
                            <button class="accordion-button collapsed btn-primary" type="button" style="">
                                <h6 class="sub-title accordiantitle">{{__("Payment Details")}}</h6>
                            </button>
                        </h6>
                        <div id="flush-collapse2" class="accordion-collapse collapse show" aria-labelledby="flush-heading2">
                            <div class="basicinfodiv">
                                <div class="box-border box-heading">
                                    <div class="row ">
                                        <div class="col-md-6">  
                                            <h4 class="wht-color" id="or_number"><?=$data->or_no?></h4>
                                        </div>
                                        <div class="col-md-6">  
                                            <h6 class="wht-color" id="cashier_date"><?=$data->created_at?></h6>
                                        </div>
                                    </div>
                                    <div class="row amount-heading">
                                        <div class="col-md-6">  
                                            <h5 class="blue-color">Amt. Due</h5>
                                        </div>
                                        <div class="col-md-6">  
                                            <h4 class="blue-color finalTotal">0.00<h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-border">
                                    <div class="row ">
                                        <div class="col-xl-12 fee-details">
                                            <div class="card">
                                                <div class="card-body table-border-style">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Tax Year</th>
                                                                    <th>Particulars</th>
                                                                    <th>Amount</th>
                                                                    <th>Surcharge/Interest</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="jqFeeDetails">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-border">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h7 class="tax-reference">Tax Credit Reference</h7>  
                                        </div>
                                    </div> 
                                     <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('or_no_lbl', __('O.R. No. :'),['class'=>'form-label']) }}

                                                {{ Form::label('or_no', __('-'),['class'=>'label-value previous_or_no']) }}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('dated_lbl', __('Dated :'),['class'=>'form-label']) }}

                                                {{ Form::label('dated', __('-'),['class'=>'label-value','id'=>'previous_or_date']) }}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('dated_lbl', __('Amount :'),['class'=>'form-label']) }}

                                                {{ Form::label('dated', __('0.00'),['class'=>'label-value','id'=>'previous_tax_credit_amount']) }}
                                            </div>
                                        </div>
                                    </div>      
                                </div>
                              
                                <!------ End Cheque Details -------->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer-details">
            <div class="modal-footer" style="float:right;">
                <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
                 <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?__('Accept'):__('Save Payment')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                </div>
            </div>
        </div>
    </div>
{{Form::close()}}

<!--- Start Hidden Cheque Details  --->

<script src="{{asset('js/OnlinePayment/bplopayments.js')}}"></script>

  