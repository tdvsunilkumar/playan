{{ Form::open(array('url' => 'fire-protection/cashiering/store','class'=>'formDtls','id'=>'mainForm')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('busn_id',$data->busn_id, array('id' => 'busn_id')) }}
{{ Form::hidden('bend_id',$data->bend_id, array('id' => 'bend_id')) }}
{{ Form::hidden('bff_id',$data->bff_id, array('id' => 'bff_id')) }}
{{ Form::hidden('bfpas_ops_year',$data->bfpas_ops_year, array('id' => 'bfpas_ops_year')) }}
{{ Form::hidden('barangay_id',$arrBusiness->busn_office_main_barangay_id, array('id' => 'barangay_id')) }}
{{ Form::hidden('client_id',$arrBusiness->client_id, array('id' => 'client_id')) }}
{{ Form::hidden('bfpas_is_fully_paid',$data->bfpas_is_fully_paid, array('id' => 'bfpas_is_fully_paid')) }}
{{ Form::hidden('submitAction','', array('id' => 'submitAction')) }}
@php
    $readonly = ($data->payment_status>0)?'readonly':'';
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
    .jqOptionDetails .form-check-input{height: 15px !important;width: 15px !important;}
    .jqOptionDetails .form-label{font-size: 10px !important; padding: unset;padding-left: 3px;}
    .jqOptionDetails .row { margin: 0px 0px 0px 0px !important;}
    .jqOptionDetails{background: #80808026;padding-top: 5px; }
    #flush-collapse3 .table thead th{padding: 6px 0.75rem;font-size: 10px;}
    #flush-collapse3 table td {padding: 0px 0.75rem;font-size: 12px;}
    .jqOptionDetails {margin-right: 27% !important;}
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
                                        <div class="col-md-6">
                                            {{Form::label('bff_application_no',__('Application No.'),['class'=>'form-label'])}}
                                        </div>
                                        <div class="col-md-6">
                                            {{Form::text('bff_application_no',$data->bff_application_no,array('class'=>'form-control','id'=>'bff_application_no','readonly'=>'readonly'))}}
                                        </div>
                                        <span class="validate-err" id="err_bff_application_no" style="text-align: right;"></span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{Form::label('bff_application_type',__('Application Type'),['class'=>'form-label'])}}
                                        </div>
                                        <div class="col-md-6 select-box">
                                            {{ Form::select('bff_application_type',$arrBfpType,$data->bff_application_type, array('class' => 'form-control disabled-field','id'=>'bff_application_type','required'=>'required')) }}
                                        </div>
                                        <span class="validate-err" id="err_bff_application_type" style="text-align: right;"></span>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{Form::label('bfpas_control_no',__('OPS No.'),['class'=>'form-label'])}}
                                        </div>
                                        <div class="col-md-3" style="width: 26%;">
                                            {{Form::text('bfpas_control_no','RO3-419-'.date('y'),array('class'=>'form-control','id'=>'bfpas_control_nobfpas_control_no','required'=>'required','readonly'=>'readonly'))}}
                                        </div>
                                        <div class="col-md-3" style="width: 24%;">
                                          {{ Form::text('bfpas_ops_no', $data->bfpas_ops_no ?? '', array('class' => 'form-control', 'id' => 'bfpas_ops_no', 'required' => 'required', 'maxlength' => '11')) }}
                                         </div>
                                        <span class="validate-err" id="err_bfpas_control_no" style="text-align: right;"></span>
                                    </div>

                                </div>
                                <div class="box-border">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <p id="busn_name">{{$arrBusiness->busn_name}}</p>
                                                <p id="busn_address">{{$arrBusiness->busn_address}}</p>
                                                <p><b id="ownar_name">{{$arrBusiness->ownar_name}}</b></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="box-border">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {{Form::label('bfpas_remarks',__('Remark'),['class'=>'form-label'])}}
                                            {{Form::textarea('bfpas_remarks',$data->bfpas_remarks,array('class'=>'form-control','id'=>'bfpas_remarks','rows'=>'2'))}}
                                        </div>
                                    </div>
                                </div>

                                <div class="amount-dtls box-border">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="d-flex radio-check">
                                                <div class="form-check form-check-inline form-group col-md-1">
                                                    {{ Form::radio('bfpas_payment_type', '1', ($data->bfpas_payment_type =='1')?true:false, array('id'=>'cash','class'=>'form-check-input code', 'required'=>'required',$readonly)) }}
                                                    {{ Form::label('cash', __('Cash'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex radio-check">
                                                <div class="form-check form-check-inline form-group col-md-1">
                                                    {{ Form::radio('bfpas_payment_type', '3',  ($data->bfpas_payment_type =='3')?true:false, array('id'=>'cheque','class'=>'form-check-input code', 'required'=>'required','disabled')) }}
                                                    {{ Form::label('cheque', __('Cheque'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex radio-check">
                                                <div class="form-check form-check-inline form-group col-md-1">
                                                    {{ Form::radio('bfpas_payment_type', '2',  ($data->bfpas_payment_type =='2')?true:false, array('id'=>'bank','class'=>'form-check-input code', 'required'=>'required','disabled')) }}
                                                    {{ Form::label('bank', __('Bank'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Total Tax Due......</b></p>
                                        </div>
                                        <div class="col-md-6 currency">
                                            {{Form::text('bfpas_total_amount',number_format($data->bfpas_total_amount,2),array('class'=>'form-control','id'=>'bfpas_total_amount','readonly'=>'readonly','required'=>'required'))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                        <span class="validate-err" id="err_bfpas_total_amount" style="text-align: right;"></span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Amount Paid......</b></p>
                                        </div>
                                        <div class="col-md-6 currency">
                                            {{Form::text('bfpas_total_amount_paid',number_format($data->bfpas_total_amount_paid,2),array('class'=>'form-control numeric','id'=>'bfpas_total_amount_paid','required'=>'required',$readonly))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                        <span class="validate-err" id="err_bfpas_total_amount_paid" style="text-align: right;"></span>
                                    </div>
                                </div>
                               
                                <div class="box-border">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{Form::label('bfpas_payment_or_no',__('O.R. No.'),['class'=>'form-label'])}}
                                        </div>
                                        <div class="col-md-6">
                                            {{Form::text('bfpas_payment_or_no',$data->bfpas_payment_or_no,array('class'=>'form-control numeric','id'=>'bfpas_payment_or_no',$readonly))}}
                                        </div>
                                        <span class="validate-err" id="err_bfpas_payment_or_no" style="text-align: right;"></span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{Form::label('bfpas_payment_or_no',__('O.R. Date'),['class'=>'form-label'])}}
                                        </div>
                                        <div class="col-md-6">
                                            @if($data->id>0)
                                           {{ Form::date('bfpas_date_paid',date('Y-m-d', strtotime(str_replace('/', '-', $data->bfpas_date_paid))), ['class' => 'form-control', 'id' => 'or_date']) }}

                                           @else
                                           {{ Form::date('bfpas_date_paid',date('Y-m-d'), ['class' => 'form-control', 'id' => 'or_date']) }}
                                           @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($data->id>0)
                    <div  class="accordion accordion-flush">
                        <div class="accordion-item">
                            <h6 class="accordion-header" id="flush-heading3">
                                <button class="accordion-button btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3">
                                    <h6 class="sub-title accordiantitle">
                                        <i class="ti-menu-alt text-white fs-12"></i>
                                        <span class="accordiantitle-icon">{{__("Documents")}}
                                        </span>
                                    </h6>
                                </button>
                            </h6>
                            <div id="flush-collapse3" class="accordion-collapse collapse" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                                <div class="basicinfodiv">
                                    <div class="row">
                                        <div>
                                            <div class="row">
                                            <div class="col-lg-10 col-md-10 col-sm-10" style="padding-left: 0px;">
                                                <div class="form-group">
                                                    {{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('file','document_name','',array('class'=>'form-control $readonly'))}}  
                                                    </div>
                                                    <span class="validate-err" id="err_document"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2" style="padding-top: 27px;text-align: end;padding-right: 0px;">
                                                <button type="button" style="float: right;" class="btn btn-sm btn-primary {{$readonly}}" id="uploadAttachment">Upload File</button>
                                            </div>
                                        </div></div>
                                        <div class="col-lg-12 col-md-12 col-sm-12" style="    margin-top: -30px;"><br>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Document Title</th>
                                                            <th>Attachment</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <thead id="DocumentDtls">
                                                        <?php echo $arrDocumentDetailsHtml?>
                                                        @if(empty($arrDocumentDetailsHtml))
                                                        <tr>
                                                            <td colspan="3"><i>No results found.</i></td>
                                                        </tr>
                                                        @endif 
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
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
                                            <h4 class="wht-color" id="or_number"><?=$data->bfpas_payment_or_no?></h4>
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
                                            <h4 class="blue-color finalTotal">{{number_format($data->bfpas_total_amount,2)}}<h4>
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
                                        <div class="col-md-1 {{($data->payment_status>0)?'hide':''}}">
                                            <span class="btn-sm btn-primary" id="btn_addmoreNature">
                                                <i class="ti-plus"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @php $i=0; @endphp
                                    @foreach($arrNature as $key=>$val)
                                        @php
                                            $arrOpt=array();
                                            if($data->id>0){
                                                if(!empty($val['fmaster_subdetails_json'])){
                                                    $arrOpt = json_decode($val['fmaster_subdetails_json'],true);
                                                }
                                            }
                                        @endphp
                                        <div class="removeNatureData">
                                            <div class="row">
                                                {{ Form::hidden('f_id[]',$val['id'], array('id' => 'f_id')) }}
                                                <div class="col-md-7" style="padding: 0px;padding-left: 5px;">
                                                    {{Form::select('fmaster_id[]',$arrFees,$val['fmaster_id'],array('class'=>'form-control fmaster_id','id'=>'fmaster_id'.$key,'required'=>'required'))}}
                                                </div>
                                                <div class="col-md-2">
                                                    {{Form::text('baaf_assessed_amount[]',$val['baaf_assessed_amount'],array('class'=>'form-control numeric'))}}
                                                </div>
                                                 <div class="col-md-2">
                                                    {{Form::text('baaf_amount_fee[]',$val['baaf_amount_fee'],array('class'=>'form-control numeric baaf_amount_fee'))}}
                                                </div>
                                                <div class="col-md-1 delete-btn-dtls {{($data->payment_status>0)?'hide':''}}">
                                                    <span class="btnCancelNature btn-sm btn-danger" f_id="{{$val['id']}}">
                                                        <i class="ti-trash"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row jqOptionDetails {{(count($arrOpt)>0)?'':'hide'}}">
                                                @foreach($arrOpt as $o_key=>$o_val)
                                                    @php
                                                        $value =$o_val['value'];
                                                        $value = wordwrap($value, 25, "\n");

                                                        $arrPrevChecked=array();
                                                        $checked='';
                                                        if(!empty($val['fee_option_json'])){
                                                            $arrPrevChecked = json_decode($val['fee_option_json'],true);
                                                            $checked = (in_array($o_val['value'],$arrPrevChecked))?'checked':'';
                                                        }
                                                        

                                                    @endphp
                                                    @if($value)
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <input id="option_{{$val['fmaster_id']}}_{{$o_val['key']}}" class="form-check-input code" name="option_{{$val['fmaster_id']}}[]" type="checkbox" value="{{$o_val['value']}}" {{$checked}}>
                                                                <label for="option_{{$val['fmaster_id']}}_{{$o_val['key']}}" class="form-label"><span class="showLess">{{$value}}</span></label>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                    @else
                                                    @endif
                                                @endforeach 
                                            </div>
                                        </div>

                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                $("#fmaster_id<?=$key?>").select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreNatureDetails")
                                                });
                                            });
                                        </script>
                                        @php $i++; @endphp
                                    @endforeach 
                                </div>
                                <!------ End Nature Of Payment Details -------->


                               
                                <!------ Start Cheque Details -------->
                                <div class="box-border check-cash-dtls {{($data->bfpas_payment_type==3)?'':'hide'}}" id="addmoreChequeDetails">
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
                                <div class="box-border check-cash-dtls {{($data->bfpas_payment_type==2)?'':'hide'}}" id="addmoreBankDetails">
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
            <div class="modal-footer" style="float:left;">
                <h6 class="note"></h6>
            </div>
            <div class="modal-footer" style="float:right;">
                

                @if($data->id>0 && !empty($data->ocr_id) && $data->payment_status==2)
                    <input type="button" value="{{$arrCancelReason[$data->ocr_id]}} - {{$data->cancellation_reason}}" class="btn  btn-danger">
                @endif

                @if($data->id>0)
                    <input type="button" name="cancel_or"  value="Cancel O.R." class="btn  btn-danger" id="jqCancelOr">
                    <a class="btn btn-primary digital-sign-btn" target="_blank" href="{{url('/fire-protection/cashiering/generatePaymentPdf?busn_id='.$data->busn_id.'&end_id='.$data->bend_id.'&year='.$data->bfpas_ops_year)}}"> <i class="ti-printer text-white fs-12"></i> Print Assessment</a>
                @endif
                @if($data->id>0 && !empty($data->bfpas_payment_or_no) && $data->payment_status==1)
                    <a class="btn btn-primary" target="_blank" href="{{url('/fire-protection/cashiering/printReceipt?busn_id='.$data->busn_id.'&end_id='.$data->bend_id.'&year='.$data->bfpas_ops_year)}}"> <i class="ti-printer text-white fs-12"></i> Print O.R.</a>
                @endif

                @if($data->payment_status<=0)
                    <input type="submit" name="submit"  value="Save As Draft" class="btn btn-primary saveData" id="jqSaveDraft">
                    <input type="submit" name="submit"  value="Make Payment" class="btn btn-primary saveData" id="jqPaidAmount">
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
<div id="hiddenNatureDtls" class="hide">
    <div class="removeNatureData">
        <div class="row pt10">
            {{ Form::hidden('f_id[]','', array('id' => 'f_id')) }}
            <div class="col-md-7" style="padding: 0px;padding-left: 5px;">
                {{Form::select('fmaster_id[]',$arrFees,'',array('class'=>'form-control fmaster_id','id'=>'fmaster_id'.$i,'required'=>'required'))}}
            </div>
            <div class="col-md-2">
                {{Form::text('baaf_assessed_amount[]','0.00',array('class'=>'form-control numeric'))}}
            </div>
             <div class="col-md-2">
                {{Form::text('baaf_amount_fee[]','0.00',array('class'=>'form-control numeric baaf_amount_fee'))}}
            </div>
            <div class="col-md-1 delete-btn-dtls">
                <span class="btnCancelNature btn-sm btn-danger" f_id="">
                    <i class="ti-trash"></i>
                </span>
            </div>
        </div> 
        <div class="row hide jqOptionDetails"></div>
    </div>
</div>
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

@if(($data->payment_status)>0)
    {{ Form::open(array('url' => 'fire-protection/cashiering/cancelOr','class'=>'formDtls')) }}
        {{ Form::hidden('app_ass_id',$data->id, array('id' => 'app_ass_id')) }}
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


<script src="{{asset('js/Bplo/add_BfpAssessment.js')}}?rand={{ rand(0000,9999) }}"></script>

  