{{ Form::open(array('url' => 'cemetery-cashering','class'=>'formDtls','id'=>'mainForm')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('tcm_id',(int)$data->tcm_id, array('id' => 'tcm_id')) }}
{{ Form::hidden('finaltotalamt','', array('id' => 'finaltotalamt')) }}
{{ Form::hidden('final_totalamt','', array('id' => 'final_totalamt')) }}
{{ Form::hidden('tax_credit_gl_id',(int)$data->tax_credit_gl_id, array('id' => 'tax_credit_gl_id')) }}
{{ Form::hidden('tax_credit_sl_id',(int)$data->tax_credit_sl_id, array('id' => 'tax_credit_sl_id')) }}
{{ Form::hidden('previous_cashier_id',(int)$data->previous_cashier_id, array('id' => 'previous_cashier_id')) }}
{{ Form::hidden('payee_type',(int)$data->payee_type, array('id' => 'payee_type')) }}
{{ Form::hidden('transaction_typeid','', array('id' => 'transaction_typeid')) }}

@php
    $readonly = ($data->id>0)?'readonly':'';
    $select3 = ($data->id>0)?'disabled-field':'select3';
    $isDisabledChkboxOr = ($data->id>0)?'disabled':'';
    $isDisableUserOr = (!$isOrAssigned)?'disabled':$isDisabledChkboxOr;
@endphp
<link href="https://fonts.cdnfonts.com/css/digital-numbers" rel="stylesheet">
<style type="text/css">
    .amount-dtls .row{text-align: center;}
    .amount-dtls .row input{ margin-bottom: 5px; }
    .box-border{ border: 1px solid #80808059;margin: 6px -2px 6px -4px; }
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
    .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: #fff;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .red{
        background: red !important;
        color:#fff;
        font-weight: bold;
        text-align: right !important;
    }
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
    .select-box{text-align: left;}
    #various_payment {width: 18px;height: 18px;}
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
                                        <div class="col-md-2">
                                            {{Form::label('client_topno',__('Top No.'),['class'=>'form-label'])}}
                                        </div>
                                        <div class="col-md-10 select-box">
                                            {{ Form::select('client_topno',$arrTransactionNum,$data->top_transaction_id, array('class' => 'form-control '.$select3,'id'=>'client_topno')) }}
                                        </div>
                                        <span class="validate-err" id="err_client_citizen_id" style="text-align: right;"></span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            {{Form::label('client_citizen_id',__('Name'),['class'=>'form-label'])}}
                                        </div>
                                        <div class="col-md-9 select-box" id="clientdiv">
                                            {{ Form::select('client_citizen_id',$arrUser,$data->client_citizen_id, array('class' => 'form-control '.$select3,'id'=>'client_citizen_id','required'=>'required')) }}
                                        </div>
                                        <div class="col-md-0 hide" style="margin-top: 0px;">
                                            <!--  <a href="#" class="btn btn-sm btn-primary" id="refreshCitizen" style="font-size: 16px;margin-top: 0px;">
                                            <span class="btn-inner--icon"><i class="ti-reload"></i> </span></a> -->
                                        </div>
                                         <div class="col-md-1" style="margin-top: 0px;">    
                                           <a target="_blank" href="{{ url('/citizens') }}" data-size="lg"  title="{{__('Manage Citizens')}}" class="btn btn-sm btn-primary"><i class="ti-plus"></i></a></div>
                                        <span class="validate-err" id="err_client_citizen_id" style="text-align: right;"></span>
                                    </div>
                                </div>
                                <div class="box-border {{($data->client_citizen_id>0)?'':'hide'}}" id="userDetails">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <p id="ownar_name"></p>
                                                <p id="ownar_address"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-border">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <span style="float:left;">
                                                {{Form::label('cashier_particulars',__('Particulars'),['class'=>'form-label'])}}
                                            </span>
                                            <span style="float:right;">
                                                {{ Form::checkbox('various_payment', '1', '', array('id'=>'various_payment','class'=>'form-check-input ')) }}
                                                {{ Form::label('various_payment', __('Various Payments'),['class'=>'form-label']) }}
                                            </span>
                                            {{Form::textarea('cashier_particulars',$data->cashier_particulars,array('class'=>'form-control','id'=>'cashier_particulars','rows'=>'2'))}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            {{Form::label('cashier_remarks',__('Remarks'),['class'=>'form-label'])}}
                                            {{Form::textarea('cashier_remarks',$data->cashier_remarks,array('class'=>'form-control','id'=>'cashier_remarks','rows'=>'2'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="amount-dtls box-border">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="d-flex radio-check">
                                                <div class="form-check form-check-inline form-group col-md-1">
                                                    {{ Form::radio('payment_terms', '1', ($data->payment_terms =='1')?true:false, array('id'=>'cash','class'=>'form-check-input code', 'required'=>'required',$readonly)) }}
                                                    {{ Form::label('cash', __('Cash'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex radio-check">
                                                <div class="form-check form-check-inline form-group col-md-1">
                                                    {{ Form::radio('payment_terms', '3',  ($data->payment_terms =='3')?true:false, array('id'=>'cheque','class'=>'form-check-input code', 'required'=>'required')) }}
                                                    {{ Form::label('cheque', __('Cheque'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex radio-check">
                                                <div class="form-check form-check-inline form-group col-md-1">
                                                    {{ Form::radio('payment_terms', '2',  ($data->payment_terms =='2')?true:false, array('id'=>'bank','class'=>'form-check-input code', 'required'=>'required')) }}
                                                    {{ Form::label('bank', __('Bank'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex radio-check">
                                                <div class="form-check form-check-inline form-group col-md-1">
                                                    {{ Form::radio('payment_terms', '5',  ($data->payment_terms =='5')?true:false, array('id'=>'Online','class'=>'form-check-input code', 'required'=>'required','disabled')) }}
                                                    {{ Form::label('Online', __('Online'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Total Tax Due......</b></p>
                                        </div>
                                        <div class="col-md-6 currency">
                                            {{Form::text('total_amount',number_format($data->total_amount,2),array('class'=>'form-control disabled-field','id'=>'total_amount'))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                        <span class="validate-err" id="err_bfpas_total_amount" style="text-align: right;"></span>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Amount Paid......</b></p>
                                        </div>
                                        <div class="col-md-6 currency">
                                            {{Form::text('total_paid_amount',number_format($data->total_paid_amount,2),array('class'=>'form-control numeric','id'=>'total_paid_amount'))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                        <span class="validate-err" id="err_total_paid_amount" style="text-align: right;"></span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Change......</b></p>
                                        </div>
                                        <div class="col-md-6 currency">
                                            {{Form::text('total_amount_change',$data->total_amount_change,array('class'=>'form-control numeric','readonly'=>'readonly','id'=>'total_amount_change'))}}
                                            <div class="currency-sign"><span>Php</span></div>
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
                                            {{Form::label('or_no',__('O R. No.'),['class'=>'form-label'])}}
                                        </div>
                                        <div class="col-md-6">
                                            {{Form::text('or_no',$data->or_no,array('class'=>'form-control numeric disabled-field','id'=>'or_no','required'=>'required',$readonly))}}
                                        </div>
                                        <span class="validate-err" id="err_or_no" style="text-align: right;"></span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{Form::label('or_no',__('O.R. Date'),['class'=>'form-label'])}}
                                        </div>
                                        <div class="col-md-6">
                                            {{Form::text('cashier_or_date',$data->cashier_or_date,array('class'=>'form-control','id'=>'cashier_or_date'))}}
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
                                             @php $data->total_amount = number_format((float)$data->total_amount, 2, '.', ','); @endphp
                                            <h4 class="blue-color finalTotal">{{$data->total_amount}}<h4>
                                        </div>
                                    </div>
                                </div>

                                <!------ Start Nature Of Payment Details -------->
                                <div class="box-border check-cash-dtls" id="addmoreNatureDetails">
                                    <div class="row check-cash-heading">
                                        <div class="col-md-7">
                                            {{ Form::label('nature_of_payment', __('Nature Of Payment'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="col-md-2">
                                            {{ Form::label('taxable_amt', __('Taxable Amount'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="col-md-2">
                                            {{ Form::label('amt', __('Amount'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="col-md-1 {{($data->id>0)?'hide':''}}">
                                            <span class="btn-sm btn-primary" id="btn_addmoreNature">
                                               <!--  <i class="ti-plus"></i> -->
                                            </span>
                                        </div>
                                    </div>
                                    <span class="taxfeesDetails activity-details tablestripped" id="FeesDetails">
                                    </span> 
                                    @php $i=0; @endphp
                                    @foreach($arrNature as $key=>$val)
                                        <div class="removeNatureData">
                                            <div class="row">
                                                {{ Form::hidden('f_id[]',$val['id'], array('id' => 'f_id')) }}
                                                <div class="col-md-7" style="padding: 0px;padding-left: 5px;">
                                                    {{Form::select('tfoc_id[]',$arrTfocFees,$val['tfoc_id'],array('class'=>'form-control tfoc_id '.$select3,'id'=>'tfoc_id'.$key,'required'=>'required'))}}
                                                </div>
                                                <div class="col-md-2">
                                                    {{Form::text('ctc_taxable_amount[]',$val['ctc_taxable_amount'],array('class'=>'form-control numeric'))}}
                                                </div>
                                                 <div class="col-md-2">
                                                    {{Form::text('tfc_amount[]',$val['tfc_amount'],array('class'=>'form-control numeric tfc_amount'))}}
                                                </div>
                                                <div class="col-md-1 delete-btn-dtls {{($data->id>0)?'hide':''}}">
                                                    <span class="btnCancelNature btn-sm btn-danger" f_id="{{$val['id']}}">
                                                        <i class="ti-trash"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        @php $i++; @endphp
                                    @endforeach 
                                </div>
                                <!------ End Nature Of Payment Details -------->
                                 <div class="box-border check-cash-dtls">
                                     <div class="row check-cash-heading">
                                        <div class="col-md-12">
                                            {{ Form::label('nature_of_payment', __('Billing Details'),['class'=>'form-label']) }}
                                        </div>
                                    </div>
                                    <span id="BillingDetails"></span>
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
                                                {{Form::text('opayment_amount3[]',$val['opayment_amount'],array('class'=>'form-control'))}}
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
                                                {{Form::text('opayment_amount2[]',$val['opayment_amount'],array('class'=>'form-control'))}}
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
            <div class="modal-footer" style="float:left;margin-left: -27px;">
                <h6 class="note {{((!$isOrAssigned)?'':'hide')}}" id="commonNote">{{ ((!$isOrAssigned)?'User O.R. Range not has been added, if you want to use then please add ':'')}}<a href="{{url('/bplo-or-asssignment')}}" target="_blank">Click Here</a></h6>
            </div>
            <div class="modal-footer" style="float:right;">
                @if($data->id>0 && !empty($data->ocr_id) && $data->status==0)
                    <input type="button" value="{{$arrCancelReason[$data->ocr_id]}} - {{$data->cancellation_reason}}" class="btn  btn-danger">
                @endif

                @if($data->id>0 && empty($data->ocr_id))
                    <input type="button" name="cancel_or"  value="Cancel O.R." class="btn  btn-danger" id="jqCancelOr">
                @endif
                @if(empty($data->ocr_id) && $data->id<=0)
                <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                    <i class="fa fa-save icon"></i>
                    <input type="submit" name="submit" value="{{('Save Payment')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                </div>
                    <!-- <input type="submit" name="submit"  value="Save Payment" class="btn btn-primary" id="jqPaidAmount"> -->
                @endif
                <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
            </div>
        </div>
    </div>
{{Form::close()}}

<!--- Start Hidden Nature of payemnt Details  --->
@php 
    $i=(count($arrNature)>0)?count($arrNature):0;
@endphp
<!-- <div id="hiddenNatureDtls" class="hide">
    <div class="removeNatureData">
        <div class="row pt10">
            {{ Form::hidden('f_id[]','', array('id' => 'f_id')) }}
            <div class="col-md-7" style="padding: 0px;padding-left: 5px;">
                {{Form::select('tfoc_id[]',$arrTfocFees,'',array('class'=>'form-control tfoc_id','id'=>'tfoc_id'.$i,'required'=>'required'))}}
            </div>
            <div class="col-md-2">
                {{Form::text('ctc_taxable_amount[]','0.00',array('class'=>'form-control numeric'))}}
            </div>
             <div class="col-md-2">
                {{Form::text('tfc_amountold[]','0.00',array('class'=>'form-control numeric tfc_amountold'))}}
            </div>
            <div class="col-md-1 delete-btn-dtls">
                <span class="btnCancelNature btn-sm btn-danger" f_id="">
                    <i class="ti-trash"></i>
                </span>
            </div>
        </div> 
    </div>
</div> -->
<!--- Start Hidden Nature of payemnt Details  --->


<!--- Start Hidden Cheque Details  --->
@php 
    $i=(count($arrCheque)>0)?count($arrCheque):0;
@endphp
<div id="hiddenChequeDtls" class="hide">
    <div class="row removeChequeData pt10">
        {{ Form::hidden('pid3[]','', array('id' => 'pid')) }}
        <div class="col-md-1" style="padding: 0px;padding-left: 5px;">
            {{Form::select('fund_id3[]',$arrFund,'',array('class'=>'form-control fund_id3','id'=>'fund_id3_'.$i))}}
        </div>
        <div class="col-md-2">
            {{Form::text('opayment_check_no3[]','',array('class'=>'form-control'))}}
        </div>
        <div class="col-md-2">
            {{ Form::select('bank_id3[]',$arrBank,'',array('class' => 'form-control bank_id3','id'=>'bank_id3_'.$i)) }}
        </div>
        <div class="col-md-2">
            {{Form::date('opayment_date3[]','',array('class'=>'form-control'))}}
        </div>
        <div class="col-md-2">
            {{Form::select('check_type_id3[]',$arrChequeTypes,'',array('class'=>'form-control check_type_id3','id'=>'check_type_id3_'.$i))}}
        </div>
        <div class="col-md-2">
            {{Form::text('opayment_amount3[]','0.00',array('class'=>'form-control'))}}
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
            {{Form::select('fund_id2[]',$arrFund,'',array('class'=>'form-control fund_id2','id'=>'fund_id2_'.$i))}}
        </div>
        <div class="col-md-2">
            {{Form::text('opayment_transaction_no2[]','',array('class'=>'form-control'))}}
        </div>
        <div class="col-md-2">
            {{Form::text('bank_account_no2[]','',array('class'=>'form-control'))}}
        </div>
        <div class="col-md-2">
            {{ Form::select('bank_id2[]',$arrBank,'',array('class' => 'form-control bank_id2','id'=>'bank_id2_'.$i)) }}
        </div>
        <div class="col-md-2">
            {{Form::date('opayment_date2[]','',array('class'=>'form-control'))}}
        </div>
        <div class="col-md-2">
            {{Form::text('opayment_amount2[]','',array('class'=>'form-control'))}}
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
    {{ Form::open(array('url' => 'cemetery-cashering/cancelOr','class'=>'formDtls','id'=>'formdtlcancelid')) }}
        {{ Form::hidden('cashier_id',$data->id, array('id' => 'cashier_id')) }}
        {{ Form::hidden('paidamountcancel',$data->total_paid_amount, array('id' => 'paidamountcancel')) }}
        {{ Form::hidden('cancelorno',$data->or_no, array('id' => 'cancelorno')) }}
        <div class="modal fade form-inner" id="orderCanceltModal">
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
                                        {{ Form::hidden('toptno',(int)$data->top_transaction_id, array('id' => 'toptno')) }}
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
    <div class="modal" id="verifyPsw" tabindex="-1" role="dialog" style="z-index:9999999;">
        <div class="modal-dialog" role="document">
         {{Form::open(array('name'=>'forms','url'=>'rptproperty/verifypsw','method'=>'post','id'=>'verifyPswForm'))}}
            <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title">Verify Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               
                </button>
            </div>
           
            <div class="modal-body">
                <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                        <div class="form-icon-user">
                            {{ Form::password('verify_psw',array('class' => 'form-control','placeholder' => 'Input Password'))}}
                            <input type="hidden" name="verify_psw_id" value="{{ $data->id }}">
                            <input type="hidden" name="verify_psw_for" value="cahering">
                        </div>
                        <span class="validate-err" id="err_verify_psw"></span>
                        
                    </div>
                </div>
            
            </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Verify</button>
                <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
            </div>
            
            </div>
            {{ Form::close()}}
        </div>
    </div>
@endif  
<!--- End Cancel Module Details  --->
<script src="{{asset('js/econandinvcashering/add_econandinvcashering.js')}}?rand={{ rand(000,999) }}"></script>




  