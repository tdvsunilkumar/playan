{{ Form::open(array('url' => 'cpdocashering')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
    .accordion-button::after{background-image: url();}
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
    .modal-lg, .modal-xl {
    max-width: 975px !important;
  }
  .red {
    background: red !important;
    color: #fff;
    font-weight: bold;
   }
   b, strong {
    font-weight: 800;
    font-size: 15px;
  }
  .topten{margin-top: 35px}
</style>
<div class="modal-body">
    <div class="row">
         <div class="col-lg-5 col-md-5 col-sm-5">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" style="">
                            <h6 class="sub-title accordiantitle">{{__("Taxpayer Information")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
                        <div class="basicinfodiv">
                            <div class="row">
                                 <div class="col-md-12">
                                        <div class="form-group">
                                            {{ Form::label('top_transaction_id', __('Transaction No'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('top_transaction_id',$arrgetTransactions,$data->top_transaction_id, array('class' => 'form-control select3 ','id'=>'top_transaction_id','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_top_transaction_id"></span>
                                        </div>
                                </div>
                                 <div class="col-md-11">
                                        <div class="form-group">
                                            {{ Form::label('clientid', __('Taxpayers Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('client_citizen_id',$clientsarr,$data->client_citizen_id, array('class' => 'form-control select3 ','id'=>'clientid','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_client_id"></span>
                                        </div>
                                </div>
                                <div class="col-sm-1"><span class="btn btn-sm btn-primary topten" id="refeshclient"><i class="ti-reload"></i></span></div>
                                  <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('controlno', __('App Control No'),['class'=>'form-label']) }}
                                            <div class="form-icon-user">
                                                {{ Form::text('controlno','', array('class' => 'form-control disabled-field','id'=>'controlno')) }}
                                            </div>
                                            <span class="validate-err" id="err_client_id"></span>
                                        </div>
                                </div>
                                <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('appdate', __('Application Date'),['class'=>'form-label']) }}
                                            <div class="form-icon-user">
                                                {{ Form::text('appdate','', array('class' => 'form-control disabled-field','id'=>'appdate')) }}
                                            </div>
                                        </div>
                                </div>
                                 <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('apptype', __('Application Type'),['class'=>'form-label']) }}
                                            <div class="form-icon-user">
                                                {{ Form::text('apptype','', array('class' => 'form-control disabled-field','id'=>'apptype')) }}
                                            </div>
                                        </div>
                                </div>
                                 <div class="col-md-12">
                                        <div class="form-group">
                                            {{ Form::label('particulars', __('Particular'),['class'=>'form-label']) }}
                                            <div class="form-icon-user">
                                                {{ Form::text('cashier_particulars',$data->cashier_particulars, array('class' => 'form-control ','id'=>'particulars','required'=>'required')) }}
                                            </div>
                                        </div>
                                 </div>
                                @if(($data->id)>0)
                               <div class="col-sm-12">
                                      <div class="d-flex radio-check">
                                        <div class="form-check form-check-inline form-group col-md-3"></div>
                                        <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('payment_terms', '1', ($checkdetail->payment_terms =='1')?true:false, array('id'=>'cash','disabled'=>'true','class'=>'form-check-input code','required'=>'required')) }}
                                            {{ Form::label('payment_terms', __('Cash'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('payment_terms', '3', ($checkdetail->payment_terms =='3')?true:false, array('id'=>'cheque','disabled'=>'true','class'=>'form-check-input code')) }}{{ Form::label('payee_type', __('Cheque'),['class'=>'form-label']) }}
                                        </div>
                                         <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('payment_terms', '2', ($checkdetail->payment_terms =='2')?true:false, array('id'=>'bank','disabled'=>'true','class'=>'form-check-input code')) }}{{ Form::label('payee_type', __('Bank'),['class'=>'form-label']) }}
                                        </div>
                                    </div>
                                 </div>
                                @else
                                <div class="col-sm-12">
                                      <div class="d-flex radio-check">
                                        <div class="form-check form-check-inline form-group col-md-3"></div>
                                        <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('payment_terms', '1', ($checkdetail->payment_terms =='1')?true:false, array('id'=>'cash','class'=>'form-check-input code','required'=>'required')) }}
                                            {{ Form::label('payment_terms', __('Cash'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('payment_terms', '3', ($checkdetail->payment_terms =='3')?true:false, array('id'=>'cheque','class'=>'form-check-input code')) }}{{ Form::label('payee_type', __('Cheque'),['class'=>'form-label']) }}
                                        </div>
                                         <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('payment_terms', '2', ($checkdetail->payment_terms =='2')?true:false, array('id'=>'bank','class'=>'form-check-input code')) }}{{ Form::label('payee_type', __('Bank'),['class'=>'form-label']) }}
                                        </div>
                                    </div>
                                 </div>
                                @endif
                                 
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="totaltaxdue" class="form-label labelbold"><strong>Total Tax Due</strong><span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::number('total_amount',$data->total_amount,array('class'=>'form-control','required'=>'required','id'=>'total_amount','step'=>'0.01'))}}
                                    </div>
                                </div>
                               <!--   <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="amountpaid" class="form-label labelbold"><strong>Less: Discount</strong><span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::number('lessdiscount',$data->total_paid_amount,array('class'=>'form-control','required'=>'required','id'=>'lessdiscount','step'=>'0.01'))}}
                                    </div>
                                </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="amountpaid" class="form-label labelbold"><strong>Net Tax Due</strong><span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::number('nettaxdue',$data->total_paid_amount,array('class'=>'form-control','required'=>'required','id'=>'nettaxdue','step'=>'0.01'))}}
                                    </div>
                                </div> -->
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="amountpaid" class="form-label labelbold"><strong>Amount Paid</strong><span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::number('total_paid_amount',$data->total_paid_amount,array('class'=>'form-control','required'=>'required','id'=>'total_paid_amount','step'=>'0.01'))}}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                         <label for="amountpaid" class="form-label labelbold"><strong>Change</strong></label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::number('total_amount_change',$data->total_amount_change,array('class'=>'form-control','id'=>'total_amount_change','step'=>'0.01'))}}
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                         <label for="amountpaid" class="form-label">Amount in words</label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        {{Form::text('amountinword','',array('class'=>'form-control','id'=>'amountinword','step'=>'0.01'))}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Account No. Date & Period Start Here---------------->

        <!--------------- Owners & Busines Information Start Here---------------->
        <div class="col-lg-7 col-md-7 col-sm-7">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingtwo">
                        <button class="accordion-button collapsed btn-primary" type="button">
                        <h6 class="sub-title accordiantitle">{{__('')}}</h6>
                        </button>
                    </h6>
                     <div class="row"  style="padding:10px;">
                                <div class="col-lg-4 col-md-4 col-sm-4"> 
                                    <div class="d-flex radio-check">
                                     <div class="form-check form-check-inline form-group">
                                        {{ Form::checkbox('isuserrange', '1', '', array('id'=>'isuserrange','class'=>'form-check-input')) }}
                                        {{ Form::label('isuserrange', __('User O.R. Range'),['class'=>'form-label']) }}
                                      </div>
                                    </div>
                               </div>
                              <!--   <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        {{Form::label('date',__('Official Receipt Date'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::date('applicationdate',date('Y-m-d'),array('class'=>'form-control','id'=>'applicationdate'))}}
                                        </div>
                                    </div>
                                </div> -->
                        </div>
                     <div class="row"  style="padding:10px;">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('or_no',__('O R. No'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                         {{Form::text('or_no',$data->or_no,array('class'=>'form-control','id'=>'or_no'))}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('date',__('O.R.Date'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('applicationdate',$data->createdat,array('class'=>'form-control disabled-field','id'=>'applicationdate'))}}
                                    </div>
                                </div>
                            </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('paymentcontrolno',__('Payment Control No'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('paymentcontrolno',$data->cashier_batch_no,array('class'=>'form-control disabled-field','id'=>'order_number','step'=>'0.01'))}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding:10px;">
                               
                               <!--  <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('credit',__('Credit'),['class'=>'form-label labelbold'])}}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::number('credit','',array('class'=>'form-control','required'=>'required','id'=>'credit','step'=>'0.01'))}}
                                    </div>
                                </div> -->
                               
                                <!--  <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('placeofissue', __('Place Of Issue'),['class'=>'form-label']) }}
                                            <div class="form-icon-user">
                                                {{ Form::text('ctc_place_of_issuance','Palayan City', array('class' => 'form-control ','id'=>'ctc_place_of_issuance','required'=>'required')) }}
                                            </div>
                                        </div>
                                 </div> -->
                            </div>
                    <div class="row" style="width: 100%; margin: 0px;padding-bottom: 20px;">
                            <div class="row field-requirement-details-status">
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                    {{Form::label('subclass_id',__('Years'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    {{Form::label('taxable_item_name',__('Particulars'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('taxable_item_qty',__('Taxable Amount'),['class'=>'form-label numeric'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('taxable_item_qty',__('Amount'),['class'=>'form-label numeric'])}}
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                    {{Form::label('capital_investment',__('Action'),['class'=>'form-label'])}}
                                </div>
                                 <div class="col-lg-1 col-md-1 col-sm-1"> <span class="btn_addmore_feetaxes btn btn-primary" id="btn_addmore_feetaxes" ><!-- <i class="ti-plus"></i> --></span></div>
                            </div>
                            <span class="taxfeesDetails activity-details tablestripped" id="FeesDetails">
                                @php $i=0; @endphp
                                @foreach($arrFeeDetails as $key=>$val)
                                <div class="removetaxfeesdata row pt10">
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group"> <div class="form-icon-user">{{Form::text('year[]',$val->cashier_year,array('class'=>'form-control','readonly'=>'readonly','id'=>'year'))}}</div>
                                        
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                 {{ Form::select('taxfees[]',$feesaray,$val->tfoc_id,array('class' => 'form-control taxfees','required'=>'required','id'=>'taxfees')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                         {{Form::text('taxableamount[]',$val->ctc_taxable_amount,array('class'=>'form-control taxableamount','id'=>'taxableamount'))}}
                                       </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            {{Form::text('amount[]',$val->tfc_amount,array('class'=>'form-control amount','id'=>'amount'))}}
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        
                                    </div>
                                    @php $i++; @endphp
                                </div>

                                @endforeach
                        </span>
                       <div class="row"> <div class="col-sm-8"></div><div class="col-sm-2">  <label for="amountpaid" class="form-label labelbold"><strong>Total</strong></label></div><div class="col-sm-2" style="font-size:20px;"> {{Form::text('totalamount','',array('class'=>'form-control red','id'=>'totalamount'))}}</div></div>
                    </div>
                     <div class="row hide" style="width: 100%; margin: 0px;padding-bottom: 20px;" id="checkdetaildiv">
                            <div class="row field-requirement-details-status">
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('funcdid',__('Fundid'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                    {{Form::label('checkid',__('Checkid'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('bank',__('Drawee bank'),['class'=>'form-label numeric'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('checktype',__('Check Type'),['class'=>'form-label numeric'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('checkamount',__('Amount'),['class'=>'form-label'])}}
                                </div>
                                 <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('checkdate',__('Date'),['class'=>'form-label'])}}
                                </div>
                                 <div class="col-lg-1 col-md-1 col-sm-1"> <span class="btn_addmore_check btn btn-primary" id="btn_addmore_check" ><i class="ti-plus"></i></span></div>
                            </div>
                            <span class="checkDetails activity-details tablestripped" id="checkDetails">
                                @php $i=0; @endphp
                                @foreach($arrPaymentDetails as $key=>$val)
                                <div class="row removecheckdata" style="padding: 5px 0px;">
                                     <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::select('fundid[]',$fundarray,$val->fund_id,array('class'=>'form-control fundid','id'=>'fundid'.$key))}}
                                         {{ Form::hidden('pid',$val->id, array('id' => 'id')) }} 
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        {{ Form::text('checkno[]',$val->opayment_check_no,array('class' => 'form-control','required'=>'required','id'=>'checkno')) }}
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{ Form::select('bank[]',$bankaray,$val->bank_id,array('class' => 'form-control bankid','required'=>'required','id'=>'bankid'.$key)) }}
                                    </div>
                                     <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::text('checktype[]',$val->check_type_id,array('class'=>'form-control checktype','id'=>'checktype'))}}
                                    </div>
                                     <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::text('checkamount[]',$val->opayment_amount,array('class'=>'form-control checkamount','id'=>'checkamount'))}}
                                    </div>
                                      <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::date('checkdate[]',$val->opayment_date,array('class'=>'form-control checkdate','id'=>'checkdate'))}}
                                    </div>
                                     <div class="col-lg-1 col-md-1 col-sm-1"><div class="form-group"><button type="button" class="btn btn-primary btn_cancel_check"><i class="ti-trash"></i></button></div></div>
                                     @php $i++; @endphp
                                </div>
                                 @endforeach
                        </span>
                    </div>
                     <div class="row hide" style="width: 100%; margin: 0px;padding-bottom: 20px;" id="bankdetaildiv">
                            <div class="row field-requirement-details-status">
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                    {{Form::label('funcdid',__('Fundid'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('trnsactionno',__('Transactionno'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('accountno',__('Account no'),['class'=>'form-label numeric'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('drawee',__('Drawee Bank'),['class'=>'form-label numeric'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('Amount',__('Amount'),['class'=>'form-label'])}}
                                </div>
                                 <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('date',__('Date'),['class'=>'form-label'])}}
                                </div>
                                 <div class="col-lg-1 col-md-1 col-sm-1"> <span class="btn_addmore_check btn btn-primary" id="btn_addmore_bank" ><i class="ti-plus"></i></span></div>
                            </div>
                            <span class="bankDetails activity-details tablestripped" id="bankDetails">
                                @php $i=0; @endphp
                                @foreach($arrPaymentbankDetails as $key=>$val)
                                 <div class="row removebankdata" style="padding: 5px 0px;">
                                     <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::select('fundid[]',$fundarray,$val->fund_id,array('class'=>'form-control fundidbank','id'=>'fundidbank0'))}}
                                          {{ Form::hidden('pid',$val->id, array('id' => 'id')) }} 
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        {{ Form::text('transactionno[]',$val->opayment_transaction_no,array('class' => 'form-control','required'=>'required','id'=>'transactionno')) }}
                                    </div>
                                     <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::text('accountno[]',$val->bank_account_no,array('class'=>'form-control accountno','id'=>'accountno'))}}
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{ Form::select('bank[]',$bankaray,$val->bank_id,array('class' => 'form-control bankidbk','required'=>'required','id'=>'bankidbk0')) }}
                                    </div>
                                     <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::text('checkamount[]',$val->opayment_amount,array('class'=>'form-control checkamount','id'=>'checkamount'))}}
                                    </div>
                                      <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::date('checkdate[]',$val->opayment_date,array('class'=>'form-control checkdate','id'=>'checkdate'))}}
                                    </div>
                                     <div class="col-lg-1 col-md-1 col-sm-1"><div class="form-group"><button type="button" class="btn btn-primary btn_cancel_bank"><i class="ti-trash"></i></button></div></div>
                                      @php $i++; @endphp
                                </div>
                                @endforeach
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
             @if(($data->id)>0)
              {{ Form::checkbox('cancelor', '1', (empty($data->status))? true:false, array('id'=>'cancelor','class'=>'form-check-input')) }}
                                        {{ Form::label('cancelor', __('Cancel O.R.'),['class'=>'form-label']) }}
             @endif
            <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
             <input type="submit" name="submit" id="submit"  value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary">
        </div>
    </div>
</div>
{{Form::close()}}
<div id="hidenTaxfeesHtml" class="hide">
     <div class="row removefeetaxdata" style="padding: 5px 0px;">
         <div class="col-lg-1 col-md-1 col-sm-1">
            <div class="form-group">
                <div class="form-icon-user">
                      {{Form::text('year[]',date('Y'),array('class'=>'form-control','readonly'=>'readonly','id'=>'year'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-md-5 col-sm-5">
            {{ Form::select('taxfees[]',$feesaray,'',array('class' => 'form-control taxfees','required'=>'required','id'=>'taxfees')) }}
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::text('taxableamount[]','',array('class'=>'form-control taxableamount','id'=>'taxableamount'))}}
        </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::text('amount[]','',array('class'=>'form-control amount','id'=>'amount'))}}
        </div>
         <div class="col-lg-1 col-md-1 col-sm-1"><div class="form-group"><button type="button" class="btn btn-primary btn_cancel_feetax"><i class="ti-trash"></i></button></div></div>
    </div>
</div>

<div id="hidencheckHtml" class="hide">
     <div class="row removecheckdata" style="padding: 5px 0px;">
         <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::select('fundid[]',$fundarray,'',array('class'=>'form-control fundid','readonly'=>'readonly','id'=>'fundid0'))}}
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1">
            {{ Form::text('checkno[]','',array('class' => 'form-control','required'=>'required','id'=>'checkno')) }}
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            {{ Form::select('bank[]',$bankaray,'',array('class' => 'form-control bankid','required'=>'required','id'=>'bankid0')) }}
        </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::text('checktype[]','',array('class'=>'form-control checktype','id'=>'checktype'))}}
        </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::text('checkamount[]','',array('class'=>'form-control checkamount','id'=>'checkamount'))}}
        </div>
          <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::date('checkdate[]','',array('class'=>'form-control checkdate','id'=>'checkdate'))}}
        </div>
         <div class="col-lg-1 col-md-1 col-sm-1"><div class="form-group"><button type="button" class="btn btn-primary btn_cancel_check"><i class="ti-trash"></i></button></div></div>
    </div>
</div>
<div id="hidenbankHtml" class="hide">
     <div class="row removebankdata" style="padding: 5px 0px;">
         <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::select('fundid[]',$fundarray,'',array('class'=>'form-control fundidbank','readonly'=>'readonly','id'=>'fundidbank0'))}}
              
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1">
            {{ Form::text('transactionno[]','',array('class' => 'form-control','required'=>'required','id'=>'transactionno')) }}
        </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::text('accountno[]','',array('class'=>'form-control accountno','id'=>'accountno'))}}
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            {{ Form::select('bank[]',$bankaray,'',array('class' => 'form-control bankidbk','required'=>'required','id'=>'bankidbk0')) }}
        </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::text('checkamount[]','',array('class'=>'form-control checkamount','id'=>'checkamount'))}}
        </div>
          <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::date('checkdate[]','',array('class'=>'form-control checkdate','id'=>'checkdate'))}}
        </div>
         <div class="col-lg-1 col-md-1 col-sm-1"><div class="form-group"><button type="button" class="btn btn-primary btn_cancel_bank"><i class="ti-trash"></i></button></div></div>
    </div>
</div>
 @if(($data->id)>0)
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
                                {{ Form::label('reason', __('Reason'),['class'=>'form-label']) }}

                                 <div class="form-icon-user">
                                    {{ Form::select('cancelreason',$arrcancelreason,$data->ocr_id, array('class' => 'form-control','id'=>'cancelreason')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                           <div class="form-group">
                                {{ Form::label('remark', __('Remark | Other Comment'),['class'=>'form-label']) }}

                                 <div class="form-icon-user">
                                    {{ Form::text('remarkother',$data->cancellation_reason, array('class' => 'form-control','id'=>'remarkother')) }}
                                </div>
                            </div>
                        </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light closeCancelModal" data-bs-dismiss="modal">
                   <button class="btn btn-primary" id="cancelorbutton"> Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif  

<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{asset('js/Cpdo/add_cpdocashering.js')}}"></script>



