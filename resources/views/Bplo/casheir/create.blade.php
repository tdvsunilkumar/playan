{{ Form::open(array('url' => 'cashier/cashier-business-permit/store','class'=>'formDtls','id'=>'mainForm')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}

{{ Form::hidden('tcm_id',(int)$data->tcm_id, array('id' => 'tcm_id')) }}
{{ Form::hidden('tax_credit_gl_id',(int)$data->tax_credit_gl_id, array('id' => 'tax_credit_gl_id')) }}
{{ Form::hidden('tax_credit_sl_id',(int)$data->tax_credit_sl_id, array('id' => 'tax_credit_sl_id')) }}
{{ Form::hidden('previous_cashier_id',(int)$data->previous_cashier_id, array('id' => 'previous_cashier_id')) }}
@php
    $readonly = ($data->id>0)?'readonly':'';
    $select3 = ($data->id>0)?'disabled-field':'select3';
    $isDisabledChkboxOr = ($data->id>0)?'disabled':'';
    $isDisableUserOr = (!$isOrAssigned)?'disabled':$isDisabledChkboxOr;
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
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="d-flex radio-check">
                                                <div class="form-check form-check-inline form-group col-md-1">
                                                    {{ Form::radio('payment_terms', '1', ($data->payment_terms =='1')?true:false, array('id'=>'cash','class'=>'form-check-input code', 'required'=>'required',$isDisabledChkboxOr)) }}
                                                    {{ Form::label('cash', __('Cash'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex radio-check">
                                                <div class="form-check form-check-inline form-group col-md-1">
                                                    {{ Form::radio('payment_terms', '3',  ($data->payment_terms =='3')?true:false, array('id'=>'cheque','class'=>'form-check-input code', 'required'=>'required',$isDisabledChkboxOr)) }}
                                                    {{ Form::label('cheque', __('Cheque'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex radio-check">
                                                <div class="form-check form-check-inline form-group col-md-1">
                                                    {{ Form::radio('payment_terms', '2',  ($data->payment_terms =='2')?true:false, array('id'=>'bank','class'=>'form-check-input code', 'required'=>'required',$isDisabledChkboxOr)) }}
                                                    {{ Form::label('bank', __('Bank'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex radio-check">
                                                <div class="form-check form-check-inline form-group col-md-1">
                                                    {{ Form::radio('payment_terms', '5',  ($data->payment_terms =='5')?true:false, array('id'=>'Online','class'=>'form-check-input code', 'required'=>'required',$isDisabledChkboxOr,'disabled')) }}
                                                    {{ Form::label('Online', __('Online'),['class'=>'form-label']) }}
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
                                                {{ Form::checkbox('isuserrange', '1', '', array('id'=>'isuserrange','class'=>'form-check-input ',$isDisableUserOr)) }}
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
                                            {{Form::text('or_no',$data->or_no,array('class'=>'form-control numeric disabled-field','id'=>'or_no','required'=>'required disabled-field',$isDisabledChkboxOr))}}
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
                                <!------ Start Cheque Details -------->
                                <div class="box-border check-cash-dtls {{($data->payment_terms==3)?'':'hide'}}" id="addmoreChequeDetails">
                                    <div class="row check-cash-heading">
                                        <div class="col-md-1">
                                            {{ Form::label('fund', __('Fund'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="col-md-2">
                                            {{ Form::label('check_no', __('Cheque No.'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="col-md-2">
                                            {{ Form::label('drawee_bank', __('Drawee Bank'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="col-md-2">
                                            {{ Form::label('date', __('Date'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="col-md-2">
                                            {{ Form::label('check_type', __('Cheque Type'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="col-md-2">
                                            {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="col-md-1 {{($data->id>0)?'hide':''}}">
                                            <span class="btn-sm btn-primary" id="btn_addmoreCheque">
                                                <i class="ti-plus"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @php $i=0; @endphp
                                    @foreach($arrCheque as $key=>$val)
                                        <div class="row removeChequeData">
                                            {{ Form::hidden('pid3[]',$val['id'], array('id' => 'pid')) }}
                                            <div class="col-md-1" style="padding: 0px;padding-left: 5px;">
                                                {{Form::select('fund_id3[]',$arrFund,$val['fund_id'],array('class'=>'form-control fund_id3','id'=>'fund_id3_'.$key))}}
                                            </div>
                                            <div class="col-md-2">
                                                {{Form::text('opayment_check_no3[]',$val['opayment_check_no'],array('class'=>'form-control'))}}
                                            </div>
                                            <div class="col-md-2">
                                                {{ Form::select('bank_id3[]',$arrBank,$val['bank_id'],array('class' => 'form-control bank_id3','id'=>'bank_id3_'.$key)) }}
                                            </div>
                                            <div class="col-md-2">
                                                {{Form::date('opayment_date3[]',$val['opayment_date'],array('class'=>'form-control'))}}
                                            </div>
                                            <div class="col-md-2">
                                                {{Form::select('check_type_id3[]',$arrChequeTypes,$val['check_type_id'],array('class'=>'form-control check_type_id3','id'=>'check_type_id3_'.$key))}}
                                            </div>
                                            <div class="col-md-2">
                                                {{Form::text('opayment_amount3[]',$val['opayment_amount'],array('class'=>'form-control numeric'))}}
                                            </div>
                                            <div class="col-md-1 delete-btn-dtls {{($data->id>0)?'hide':''}}">
                                                <span class="btnCancelCheque btn-sm btn-danger">
                                                    <i class="ti-trash"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                $("#fund_id3_<?=$i?>, #bank_id3_<?=$i?>, #check_type_id3_<?=$i?>").select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreChequeDetails")
                                                });
                                            });
                                        </script>

                                        @php $i++; @endphp
                                    @endforeach 
                                </div>
                                <!------ End Cheque Details -------->

                                <!------ Start Bank Details -------->
                                <div class="box-border check-cash-dtls {{($data->payment_terms==2)?'':'hide'}}" id="addmoreBankDetails">
                                    <div class="row check-cash-heading">
                                        <div class="col-md-1">
                                            {{ Form::label('fund_id', __('Fund'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="col-md-2">
                                            {{ Form::label('transaction_no', __('Transaction No.'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="col-md-2">
                                            {{ Form::label('account_no', __('A/C No.'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="col-md-2">
                                            {{ Form::label('drawee_bank', __('Drawee Bank'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="col-md-2">
                                            {{ Form::label('date', __('Date'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="col-md-2">
                                            {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="col-md-1 {{($data->id>0)?'hide':''}}">
                                            <span class="btn-sm btn-primary" id="btn_addmoreBank">
                                                <i class="ti-plus"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @php $i=0; @endphp
                                    @foreach($arrBankDtls as $key=>$val)
                                        <div class="row removeBankData">
                                            {{ Form::hidden('pid2[]',$val['id'], array('id' => 'pid')) }}
                                            <div class="col-md-1" style="padding: 0px;padding-left: 5px;">
                                                {{Form::select('fund_id2[]',$arrFund,$val['fund_id'],array('class'=>'form-control fund_id2','id'=>'fund_id2_'.$i))}}
                                            </div>
                                            <div class="col-md-2">
                                                {{Form::text('opayment_transaction_no2[]',$val['opayment_transaction_no'],array('class'=>'form-control'))}}
                                            </div>
                                            <div class="col-md-2">
                                                {{Form::text('bank_account_no2[]',$val['bank_account_no'],array('class'=>'form-control'))}}
                                            </div>
                                            <div class="col-md-2">
                                                {{ Form::select('bank_id2[]',$arrBank,$val['bank_id'],array('class' => 'form-control bank_id2','id'=>'bank_id2_'.$i)) }}
                                            </div>
                                            <div class="col-md-2">
                                                {{Form::date('opayment_date2[]',$val['opayment_date'],array('class'=>'form-control'))}}
                                            </div>
                                            <div class="col-md-2">
                                                {{Form::text('opayment_amount2[]',$val['opayment_amount'],array('class'=>'form-control numeric'))}}
                                            </div>
                                            <div class="col-md-1 delete-btn-dtls {{($data->id>0)?'hide':''}}">
                                                <span class="btnCancelBank btn-sm btn-danger">
                                                    <i class="ti-trash"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                $("#fund_id2_<?=$i?>, #bank_id2_<?=$i?>").select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreBankDetails")
                                                });
                                            });
                                        </script>
                                        @php $i++; @endphp
                                    @endforeach 
                                </div>
                                <!------ End Bank Details -------->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer-details">
            <div class="modal-footer" style="float:left;">
                <h6 class="note {{((!$isOrAssigned)?'':'hide')}}" id="commonNote">{{ ((!$isOrAssigned)?'User O.R. Range not has been added, if you want to use then please add ':'')}}<a href="{{url('/bplo-or-asssignment')}}" target="_blank">Click Here</a></h6>
            </div>
            <div class="modal-footer" style="float:right;">
                
                <input type="button" name="applyCredit"  value="Apply Credit" class="btn btn-green hide" id="jqApplyCredit">
                <input type="button" name="removeapplyCredit"  value="Remove Applied Credit" class="btn btn-danger hide" id="jqRemoveApplyCredit">
                @if($data->id>0 && !empty($data->ocr_id) && $data->status==0)

                    <input type="button" value="{{$arrCancelReason[$data->ocr_id]}} - {{$data->cancellation_reason}}" class="btn  btn-danger">
                @endif

                @if($data->id>0 && empty($data->ocr_id))
                    <input type="button" name="cancel_or"  value="Cancel O.R." class="btn  btn-danger" id="jqCancelOr">
                @endif
                @if(empty($data->ocr_id) && $data->id<=0)
                    <input type="submit" name="submit"  value="Save Payment" class="btn btn-primary" id="jqPaidAmount">
                @endif

                <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
            </div>
        </div>
    </div>
{{Form::close()}}

<!--- Start Hidden Cheque Details  --->
@php 
    $i=(count($arrCheque)>0)?count($arrCheque):0;
@endphp
<div id="hiddenChequeDtls" class="hide">
    <div class="row removeChequeData pt10">
        {{ Form::hidden('pid3[]','', array('id' => 'pid')) }}
        <div class="col-md-1" style="padding: 0px;padding-left: 5px;">
            {{Form::select('fund_id3[]',$arrFund,'',array('class'=>'form-control fund_id3','required'=>'required','id'=>'fund_id3_'.$i))}}
        </div>
        <div class="col-md-2">
            {{Form::text('opayment_check_no3[]','',array('class'=>'form-control','required'=>'required'))}}
        </div>
        <div class="col-md-2">
            {{ Form::select('bank_id3[]',$arrBank,'',array('class' => 'form-control bank_id3','required'=>'required','id'=>'bank_id3_'.$i)) }}
        </div>
        <div class="col-md-2">
            {{Form::date('opayment_date3[]','',array('class'=>'form-control','required'=>'required'))}}
        </div>
        <div class="col-md-2">
            {{Form::select('check_type_id3[]',$arrChequeTypes,'',array('class'=>'form-control check_type_id3','required'=>'required','id'=>'check_type_id3_'.$i))}}
        </div>
        <div class="col-md-2">
            {{Form::text('opayment_amount3[]','0.00',array('class'=>'form-control numeric','required'=>'required'))}}
        </div>
        <div class="col-md-1 delete-btn-dtls">
            <span class="btnCancelCheque btn-sm btn-danger">
                <i class="ti-trash"></i>
            </span>
        </div>
    </div> 
</div>
<!--- Start Hidden Cheque Details  --->

<!--- Start Hidden Bank Details  --->
@php 
    $i=(count($arrBankDtls)>0)?count($arrBankDtls):0;
@endphp

<div id="hiddenBankDtls" class="hide">
    <div class="row removeBankData pt10">
        {{ Form::hidden('pid2[]','', array('id' => 'pid')) }}
        <div class="col-md-1" style="padding: 0px;padding-left: 5px;">
            {{Form::select('fund_id2[]',$arrFund,'',array('class'=>'form-control fund_id2','required'=>'required','id'=>'fund_id2_'.$i))}}
        </div>
        <div class="col-md-2">
            {{Form::text('opayment_transaction_no2[]','',array('class'=>'form-control','required'=>'required'))}}
        </div>
        <div class="col-md-2">
            {{Form::text('bank_account_no2[]','',array('class'=>'form-control','required'=>'required'))}}
        </div>
        <div class="col-md-2">
            {{ Form::select('bank_id2[]',$arrBank,'',array('class' => 'form-control bank_id2','required'=>'required','id'=>'bank_id2_'.$i)) }}
        </div>
        <div class="col-md-2">
            {{Form::date('opayment_date2[]','',array('class'=>'form-control','required'=>'required'))}}
        </div>
        <div class="col-md-2">
            {{Form::text('opayment_amount2[]','',array('class'=>'form-control numeric','required'=>'required'))}}
        </div>
        <div class="col-md-1 delete-btn-dtls">
            <span class="btnCancelBank btn-sm btn-danger">
                <i class="ti-trash"></i>
            </span>
        </div>
    </div> 
</div>
<!--- Start Hidden Bank Details  --->

<!--- Start Cancel Module Details  --->

@if(($data->id)>0)
    {{ Form::open(array('url' => 'cashier/cashier-business-permit/cancelOr','class'=>'formDtls')) }}
        {{ Form::hidden('cashier_id',$data->id, array('id' => 'cashier_id')) }}
        {{ Form::hidden('top_id',$data->top_transaction_id, array('id' => 'top_id')) }}
        <div class="modal fade" id="orderCanceltModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                   <div class="modal-header">
                        <h4 class="modal-title">Reason For Cancellation</h4>
                        <button type="button" class="btn-close closeCancelModal" data-bs-dismiss="modal" aria-label="Close"></button>
                   </div>
                       <div class="container">
                        <div class="modal-body">
                               <div class="row">
                                 <div class="col-md-12" id="cancelreasondiv">
                                   <div class="form-group">
                                        {{ Form::label('reason', __('Cancellation Reason'),['class'=>'form-label']) }}<span class="text-danger">*</span>

                                         <div class="form-icon-user">
                                            {{ Form::select('cancelreason',$arrCancelReason,$data->ocr_id, array('class' => 'form-control','id'=>'cancelreason','required'=>'required')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                   <div class="form-group">
                                        {{ Form::label('remark', __('Cancellation Remarks'),['class'=>'form-label']) }}<span class="text-danger">*</span>

                                         <div class="form-icon-user">
                                            {{ Form::text('remarkother',$data->cancellation_reason, array('class' => 'form-control','id'=>'remarkother','required'=>'required')) }}
                                        </div>
                                    </div>
                                </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light closeCancelModal" data-bs-dismiss="modal">
                           <button class="btn btn-primary" id="cancelorbutton"> Cancel O.R.</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{Form::close()}}
@endif  

<!--- End Cancel Module Details  --->

<script src="{{asset('js/Bplo/add_cashering.js')}}"></script>

  