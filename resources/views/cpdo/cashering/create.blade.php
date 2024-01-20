{{ Form::open(array('url' => 'cpdocashering','id'=>'casherform')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('maintfoc_id','', array('id' => 'maintfoc_id')) }}
    @php $hideclass= 'hide'; @endphp
    @if(($data->id)>0)
     {{ Form::hidden('payment_terms',$checkdetail->payment_terms, array('id' => 'payment_terms')) }}
     @php $hideclass= ''; @endphp
    @endif
    @if(($data->id)>0)
     @php  $readonlyclass ="disabled-field"; $select3class ="" ;@endphp
    @else
     @php  $readonlyclass ="";  $select3class ="select3"; @endphp
    @endif
    @php if(count($arrPaymentDetails)==0){
        $hideclass= 'hide'; 
     }
     if(count($arrPaymentbankDetails)==0){
        $hideclass= 'hide'; 
     }
     @endphp
<style type="text/css">
   
    .modal-body {
        position: relative;
        flex: 1 1 auto;
        padding: 1.25rem;
        /* padding-bottom: 5%; */
    }
    .form-group {
        margin-bottom: 5px;
    }
    @font-face {
    font-family: 'Digital Numbers';
    font-style: normal;
    font-weight: 400;
    src: local('Digital Numbers'), url('css/DigitalNumbers-Regular.woff?v=4.7.0"') format('woff');
    }
    .accordion-button::after{background-image: url();}
    .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: #fff;
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
                                <div class="col-md-12 hide">
                                        <div class="form-group">
                                            {{ Form::label('permittype', __('Select Permit Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('permittype',$arrtypeofcashering,$data->cpdo_type, array('class' => 'form-control '.$readonlyclass,'id'=>'permittype','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_permittype"></span>
                                        </div>
                                </div>
                                 <div class="col-md-12">
                                        <div class="form-group">
                                            {{ Form::label('top_transaction_id', __('Transaction No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('top_transaction_id',$arrgetTransactions,$data->top_transaction_id, array('class' => 'form-control '.$readonlyclass.' '.$select3class,'id'=>'top_transaction_id','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_top_transaction_id"></span>
                                        </div>
                                </div>
                                 <div class="col-md-12">
                                        <div class="form-group">
                                            {{ Form::label('clientid', __('Taxpayers Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('client_citizen_id',$clientsarr,$data->client_citizen_id, array('class' => 'form-control disabled-field ','id'=>'clientid','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_client_id"></span>
                                        </div>
                                </div>
                                <!-- <div class="col-sm-1"><span class="btn btn-sm btn-primary topten" id="refeshclient"><i class="ti-reload"></i></span></div> -->
                                  <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('controlno', __('App Control No.'),['class'=>'form-label']) }}
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
                                                {{ Form::text('cashier_particulars',$data->cashier_particulars, array('class' => 'form-control '.$readonlyclass,'id'=>'particulars','required'=>'required')) }}
                                            </div>
                                        </div>
                                 </div>
                                @if(($data->id)>0)
                               <div class="col-sm-12">
                                      <div class="d-flex radio-check">
                                       
                                        <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('payment_terms', '1', ($checkdetail->payment_terms =='1')?true:false, array('id'=>'cash','class'=>'form-check-input code','required'=>'required','disabled')) }}
                                            {{ Form::label('payment_terms', __('Cash'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('payment_terms', '3', ($checkdetail->payment_terms =='3')?true:false, array('id'=>'cheque','class'=>'form-check-input code','disabled')) }}{{ Form::label('payee_type', __('Cheque'),['class'=>'form-label']) }}
                                        </div>
                                         <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('payment_terms', '2', ($checkdetail->payment_terms =='2')?true:false, array('id'=>'bank','class'=>'form-check-input code','disabled')) }}{{ Form::label('payee_type', __('Bank'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="form-check form-check-inline form-group col-md-3">
                                                    {{ Form::radio('payment_terms', '5',  ($data->payment_terms =='5')?true:false, array('id'=>'Online','class'=>'form-check-input code','disabled')) }}
                                                    {{ Form::label('Online', __('Online'),['class'=>'form-label']) }}
                                        </div>
                                    </div>
                                 </div>
                                @else
                                <div class="col-sm-12">
                                      <div class="d-flex radio-check">
                                        <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('payment_terms', '1', ($checkdetail->payment_terms =='1')?true:false, array('id'=>'cash','class'=>'form-check-input code','required'=>'required','style'=>'color:blue;')) }}
                                            {{ Form::label('payment_terms', __('Cash'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('payment_terms', '3', ($checkdetail->payment_terms =='3')?true:false, array('id'=>'cheque','class'=>'form-check-input code')) }}{{ Form::label('payee_type', __('Cheque'),['class'=>'form-label']) }}
                                        </div>
                                         <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('payment_terms', '2', ($checkdetail->payment_terms =='2')?true:false, array('id'=>'bank','class'=>'form-check-input code')) }}{{ Form::label('payee_type', __('Bank'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="form-check form-check-inline form-group col-md-3">
                                                    {{ Form::radio('payment_terms', '5',  ($data->payment_terms =='5')?true:false, array('id'=>'Online','class'=>'form-check-input code','disabled')) }}
                                                    {{ Form::label('Online', __('Online'),['class'=>'form-label']) }}
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
                                        {{Form::text('total_amount',$data->total_amount,array('class'=>'form-control numeric-double disabled-field '.$readonlyclass,'required'=>'required','id'=>'total_amount','step'=>'0.01'))}}
                                    </div>
                                </div>
                               <!--   <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="amountpaid"readonlyclass class="form-label labelbold"><strong>Less: Discount</strong><span class="text-danger">*</span></label>
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
                                        {{Form::text('total_paid_amount',$data->total_paid_amount,array('class'=>'form-control numeric-double '.$readonlyclass,'required'=>'required','id'=>'total_paid_amount','step'=>'0.01'))}}
                                    </div>
                                    <span class="validate-err" id="err_total_paid_amount"></span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                         <label for="amountpaid" class="form-label labelbold"><strong>Change</strong></label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::text('total_amount_change',$data->total_amount_change,array('class'=>'form-control numeric-double '.$readonlyclass,'id'=>'total_amount_change','step'=>'0.01'))}}
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group" style="margin-bottom:0px;">
                                         <label for="amountpaid" class="form-label">Amount in words</label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        {{Form::text('amountinword','',array('class'=>'form-control '.$readonlyclass,'id'=>'amountinword','step'=>'0.01'))}}
                                    </div>
                                </div>
                            </div>
                            <div class="box-border">
                                    <div class="row">
                                        <div class="col-md-12"> 
                                            <div class="d-flex radio-check">
                                             <div class="form-check form-check-inline form-group">
                                                {{ Form::checkbox('isuserrange', '1', '', array('id'=>'isuserrange','class'=>'form-check-input')) }}
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
                                           {{Form::text('or_no',$data->or_no,array('class'=>'form-control disabled-field','id'=>'or_no'))}}
                                        </div>
                                        <span class="validate-err" id="err_or_no" style="text-align: right;"></span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{Form::label('or_no',__('O.R. Date'),['class'=>'form-label'])}}
                                        </div>
                                        <div class="col-md-6">
                                            {{Form::text('applicationdate',$data->createdat,array('class'=>'form-control','id'=>'applicationdate'))}}
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
        <!--------------- Account No. Date & Period Start Here---------------->

        <!--------------- Owners & Busines Information Start Here---------------->
        <div class="col-lg-7 col-md-7 col-sm-7">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingtwo">
                        <button class="accordion-button collapsed btn-primary" type="button">
                        <h6 class="sub-title accordiantitle">{{__("Payment Details")}}</h6>
                        </button>
                    </h6>
                     <div class="row"  style="padding:10px;">
                           <!--   <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('paymentcontrolno',__('Payment Control No.'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('paymentcontrolno',$data->cashier_batch_no,array('class'=>'form-control disabled-field','id'=>'order_number','step'=>'0.01'))}}
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <div class="row" style="padding:10px;">
                             <div id="flush-collapse2" class="accordion-collapse collapse show" aria-labelledby="flush-heading2">
                              <div class="basicinfodiv">  
                                <div class="box-border box-heading">
                                    <div class="row ">
                                        <div class="col-md-6">  
                                            <h4 class="wht-color" id="or_number"><?=$data->or_no?></h4>
                                        </div>
                                        <div class="col-md-6">  
                                            <h6 class="wht-color" id="cashier_date"><?=$data->createdat?></h6>
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
                                </div>
                              </div>
                            </div>
                    <div class="row" style="width: 100%; margin: 0px;padding-bottom: 10px; margin-top: -30px;">
                            <div class="row field-requirement-details-status">
                                <div class="col-lg-1 col-md-1 col-sm-1" style="padding: 5px;">
                                    {{Form::label('subclass_id',__('Tax Year'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-7 col-md-7 col-sm-7" style="padding: 5px;">
                                    {{Form::label('taxable_item_name',__('Particulars'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2" style="padding: 5px;">
                                    {{Form::label('taxable_item_qty',__('Taxable Amount'),['class'=>'form-label numeric','style'=>'padding-left: 15px;'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2" style="padding: 5px;">
                                    {{Form::label('taxable_item_qty',__('Amount'),['class'=>'form-label numeric','style'=>'padding-left: 23px;'])}}
                                </div>
                                
                                 <!-- <div class="col-lg-1 col-md-1 col-sm-1" style="padding-top: 10px;"> <span class="btn_addmore_feetaxes btn btn-primary" id="btn_addmore_feetaxes"> <i class="ti-plus"></i> </span></div> -->
                            </div>
                            <span class="taxfeesDetails activity-details tablestripped" id="FeesDetails">
                                 @php $i=0;  $iteratio = count($arrFeeDetails);  $iteratio = $iteratio -1;@endphp  
                                @foreach($arrFeeDetails as $key=>$val)
                                <div class="removetaxfeesdata row pt10 {{ ($i == $iteratio)? '':'' }}">
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group"> <div class="form-icon-user">{{Form::text('year[]',$val->cashier_year,array('class'=>'form-control','readonly'=>'readonly','id'=>'year'))}}</div>
                                        
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                 {{ Form::select('taxfees[]',$feesaray,$val->tfoc_id,array('class' => 'form-control taxfees disabled-field','required'=>'required','id'=>'taxfees','readonly'=>'true')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                         {{Form::text('taxableamount[]',$val->ctc_taxable_amount,array('class'=>'form-control taxableamount disabled-field','id'=>'taxableamount'))}}
                                       </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            {{Form::text('amount[]',$val->tfc_amount,array('class'=>'form-control amount disabled-field ','id'=>'amount'))}}
                                        </div>
                                    </div>
                                    
                                   
                                    @php $i++; @endphp
                                </div>

                                @endforeach 
                                @if(($data->id) <= 0)
                                <div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group"> <div class="form-icon-user">{{Form::text('year[]',$data->cashier_year,array('class'=>'form-control','readonly'=>'readonly','id'=>'year'))}}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                 {{ Form::select('taxfees[]',$feesaray,'',array('class' => 'form-control taxfees '.$readonlyclass,'required'=>'required','id'=>'taxfeescpdo')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                         {{Form::text('taxableamount[]','',array('class'=>'form-control taxableamount '.$readonlyclass,'id'=>'taxableamount'))}}
                                       </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            {{Form::text('amount[]','',array('class'=>'form-control disabled-field amount '.$readonlyclass,'id'=>'cpdoamount'))}}
                                        </div>
                                    </div>
                                   
                               </div>
                               @endif
                        </span>
                        <span class="taxfeesDetails activity-details tablestripped" id="FeesDetailspenalty">
                            
                        </span>
                       <div class="row hide"> <div class="col-sm-8"></div><div class="col-sm-2">  <label for="amountpaid" class="form-label labelbold"><strong>Total</strong></label></div><div class="col-sm-2" style="font-size:20px;"> {{Form::text('totalamount',$data->total_amount,array('class'=>'form-control red','id'=>'totalamount'))}}</div></div>
                    </div>
                     <div class="row {{$hideclass}}" style="width: 100%; margin: 0px;padding-bottom: 20px;" id="checkdetaildiv">
                            <div class="row field-requirement-details-status">
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('funcdid',__('Fund ID'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-2 col-md-1 col-sm-1">
                                    {{Form::label('checkid',__('Cheque id'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('bank',__('Drawee bank'),['class'=>'form-label numeric'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('checktype',__('Cheque Type'),['class'=>'form-label numeric'])}}
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1">
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
                                        {{Form::select('fundid3[]',$fundarray,$val->fund_id,array('class'=>'form-control fundid','id'=>'fundid'.$key))}}
                                         {{ Form::hidden('pid',$val->id, array('id' => 'id')) }} 
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        {{ Form::text('checkno3[]',$val->opayment_check_no,array('class' => 'form-control','required'=>'required','id'=>'checkno')) }}
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{ Form::select('bank3[]',$bankaray,$val->bank_id,array('class' => 'form-control bankid','required'=>'required','id'=>'bankid'.$key)) }}
                                    </div>
                                     <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::text('checktype3[]',$val->check_type_id,array('class'=>'form-control checktype','id'=>'checktype'))}}
                                    </div>
                                     <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::text('checkamount3[]',$val->opayment_amount,array('class'=>'form-control checkamount numeric-double','id'=>'checkamount'))}}
                                    </div>
                                      <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::date('checkdate3[]',$val->opayment_date,array('class'=>'form-control checkdate','id'=>'checkdate'))}}
                                    </div>
                                     <div class="col-lg-1 col-md-1 col-sm-1"><!-- <div class="form-group"><button type="button" class="btn btn-primary btn_cancel_check"><i class="ti-trash"></i></button></div> --></div>
                                     @php $i++; @endphp
                                </div>
                                 @endforeach
                        </span>
                    </div>
                     <div class="row {{$hideclass}}" style="width: 100%; margin: 0px;padding-bottom: 20px;" id="bankdetaildiv">
                            <div class="row field-requirement-details-status">
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('funcdid',__('Fund id'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('trnsactionno',__('Transaction No.'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('accountno',__('Account no.'),['class'=>'form-label numeric'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('drawee',__('Drawee Bank'),['class'=>'form-label numeric'])}}
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1">
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
                                        {{Form::select('fundid2[]',$fundarray,$val->fund_id,array('class'=>'form-control fundidbank','id'=>'fundidbank0'))}}
                                          {{ Form::hidden('pid',$val->id, array('id' => 'id')) }} 
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        {{ Form::text('transactionno2[]',$val->opayment_transaction_no,array('class' => 'form-control','required'=>'required','id'=>'transactionno')) }}
                                    </div>
                                     <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::text('accountno2[]',$val->bank_account_no,array('class'=>'form-control accountno','id'=>'accountno'))}}
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{ Form::select('bank2[]',$bankaray,$val->bank_id,array('class' => 'form-control bankidbk','required'=>'required','id'=>'bankidbk0')) }}
                                    </div>
                                     <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::text('checkamount2[]',$val->opayment_amount,array('class'=>'form-control checkamount numeric-double','id'=>'checkamount'))}}
                                    </div>
                                      <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::date('checkdate2[]',$val->opayment_date,array('class'=>'form-control checkdate','id'=>'checkdate'))}}
                                    </div>
                                     <div class="col-lg-1 col-md-1 col-sm-1"><!-- <div class="form-group"><button type="button" class="btn btn-primary btn_cancel_bank"><i class="ti-trash"></i></button></div> --></div>
                                      @php $i++; @endphp
                                </div>
                                @endforeach
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            @if($data->id>0 && !empty($data->ocr_id) && $data->status==0)
                <input type="button" value="{{$arrcancelreason[$data->ocr_id]}} - {{$data->cancellation_reason}}" class="btn  btn-danger">
            @endif

            @if($data->id>0 && empty($data->ocr_id))
                <input type="button" name="cancel_or"  value="Cancel O.R." class="btn  btn-danger" id="jqCancelOr">
            @endif
            <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
            @if(($data->id) <= 0)
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?__('Update'):__('Save Payment')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            @endif
             <!-- <input type="submit" name="submit" id="submit"  value="{{ ($data->id)>0?__('Update'):__('Save Payment')}}" class="btn  btn-primary"> -->
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
        <div class="col-lg-6 col-md-6 col-sm-6">
            {{ Form::select('taxfees[]',$feesaray,'',array('class' => 'form-control taxfees','required'=>'required','id'=>'taxfees')) }}
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::text('taxableamount[]','',array('class'=>'form-control taxableamount','id'=>'taxableamount'))}}
        </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::text('amount[]','',array('class'=>'form-control disabled-field amount','id'=>'amount'))}}
        </div>
         <div class="col-lg-1 col-md-1 col-sm-1"><div class="form-group"><button type="button" class="btn btn-primary btn_cancel_feetax"><i class="ti-trash"></i></button></div></div>
    </div>
</div>

<div id="hidencheckHtml" class="hide">
     <div class="row removecheckdata" style="padding: 5px 0px;">
         <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::select('fundid3[]',$fundarray,'',array('class'=>'form-control fundid','required'=>'required','id'=>'fundid0'))}}
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1">
            {{ Form::text('checkno3[]','',array('class' => 'form-control','required'=>'required','id'=>'checkno')) }}
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            {{ Form::select('bank3[]',$bankaray,'',array('class' => 'form-control bankid','required'=>'required','id'=>'bankid0')) }}
        </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::text('checktype3[]','',array('class'=>'form-control checktype','id'=>'checktype','required'=>'required'))}}
        </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::text('checkamount3[]','',array('class'=>'form-control checkamount numeric-double','id'=>'checkamount','required'=>'required'))}}
        </div>
          <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::date('checkdate3[]','',array('class'=>'form-control checkdate','id'=>'checkdate','required'=>'required'))}}
        </div>
         <div class="col-lg-1 col-md-1 col-sm-1"><div class="form-group"><button type="button" class="btn btn-primary btn_cancel_check"><i class="ti-trash"></i></button></div></div>
    </div>
</div>
<div id="hidenbankHtml" class="hide">
     <div class="row removebankdata" style="padding: 5px 0px;">
         <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::select('fundid2[]',$fundarray,'',array('class'=>'form-control fundidbank','required'=>'required','id'=>'fundidbank0'))}}
              
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1">
            {{ Form::text('transactionno2[]','',array('class' => 'form-control','required'=>'required','id'=>'transactionno')) }}
        </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::text('accountno2[]','',array('class'=>'form-control accountno','id'=>'accountno','required'=>'required'))}}
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            {{ Form::select('bank2[]',$bankaray,'',array('class' => 'form-control bankidbk','required'=>'required','id'=>'bankidbk0')) }}
        </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::text('checkamount2[]','',array('class'=>'form-control checkamount numeric-double','id'=>'checkamount','required'=>'required'))}}
        </div>
          <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::date('checkdate2[]','',array('class'=>'form-control checkdate','id'=>'checkdate','required'=>'required'))}}
        </div>
         <div class="col-lg-1 col-md-1 col-sm-1"><div class="form-group"><button type="button" class="btn btn-primary btn_cancel_bank"><i class="ti-trash"></i></button></div></div>
    </div>
</div>
 @if(($data->id)>0)
  {{ Form::open(array('url' => 'cpdocashering/cancelorpayment','class'=>'formDtlscancel','id'=>'formdtlcancelid')) }}
        {{ Form::hidden('cashier_id',$data->id, array('id' => 'cashier_id')) }}
        {{ Form::hidden('toptno',$data->top_transaction_id, array('id' => 'cashier_id')) }}
        {{ Form::hidden('paymentterms',$checkdetail->payment_terms, array('id' => 'paymentterms')) }}
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
                                {{ Form::label('reason', __('Reason'),['class'=>'form-label']) }}

                                 <div class="form-icon-user">
                                    {{ Form::select('ocr_id',$arrcancelreason,$data->ocr_id, array('class' => 'form-control','id'=>'cancelreason','required'=>'required')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                           <div class="form-group">
                                {{ Form::label('remark', __('Remark | Other Comment'),['class'=>'form-label']) }}

                                 <div class="form-icon-user">
                                    {{ Form::text('remark',$data->cancellation_reason, array('class' => 'form-control','id'=>'remarkother','required'=>'required')) }}
                                </div>
                            </div>
                        </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light closeCancelModal" data-bs-dismiss="modal">
                   <button class="btn btn-primary" id="cancelorbutton"><i class="la la-save"></i> Submit</button>
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

<!-- <script src="{{ asset('js/ajax_validation.js') }}"></script> -->
<script src="{{asset('js/Cpdo/add_cpdocashering.js')}}?rand={{ rand(000,999) }}"></script>



