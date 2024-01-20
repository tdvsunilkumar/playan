{{Form::open(array('url'=>'bplopermitandlicence','method'=>'post'))}}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('accountnumber',$data->id, array('id' => 'accountnumber')) }}
{{ Form::hidden('bas_id',$data->bas_id, array('id' => 'bas_id')) }}
 <style>
    .modal-xl {
        max-width: 1100px !important;
    }
    .accordion-button{
        margin-bottom: 12px;
    }
    .form-group{
        margin-bottom: unset;
    }
    .form-group label {
        font-weight: 600;
        font-size: 12px;
    }
    .form-control, .custom-select{
        padding-left: 5px;
        font-size: 12px;
    }
    .pt10{
        padding-top:10px;
    }
    .modal-xll {
    max-width: 1493px !important;
    }
    .fee-details td:nth-child(5), .fee-details td:nth-child(7) {
        background: #80808052;
    }
    .fee-details td:nth-child(4), .fee-details td:nth-child(6), .fee-details td:nth-child(8) {
        background: #20b7cc42;
    }
    .fee-details tr:last-child{
        background: #80808052;
    }
    .sky-blue{
        background: #20B7CC !important;
        color:#fff;
        font-weight: bold;
    }
    .top25{padding-top: 27px;}
    .red{
        background: red !important;
        color:#fff;
        font-weight: bold;
    }
    .closeModel{cursor:pointer;}
    .align-center{text-align: center;}
    .inputpicker-div{
        height: unset !important;
    }
    .accordion-button::after{background-image: url();}
 </style>

<div class="modal-body">
    <div class="row pt10" >
        <!--------------- Account No. Date & Period Start Here---------------->
        <div class="col-lg-5 col-md-5 col-sm-5">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" style="">
                            <h6 class="sub-title accordiantitle">{{__("Pay What?")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
                        <div class="basicinfodiv">
                            <div class="row">
                              @if(!empty($data->id))
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('ba_business_account_no',__('Business No.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{Form::text('ba_businessaccno',$data->ba_business_account_no,array('class'=>'form-control  ','required'=>'required'))}}
                                        </div>
                                    </div>
                                </div>
                                @elseif(empty($data->id))
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('ba_business_account_no',__('Business No.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('ba_business_account_no',$accountnos,'', array('class' => 'form-control  select3','id'=>'ba_business_account_no')) }}
                                        </div>
                                    </div>
                                </div>
                                 @endif
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        {{Form::label('from',__('From'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{Form::number('ba_cover_year',$data->ba_cover_year,array('class'=>'form-control','required'=>'required'))}}
                                        </div>
                                        <span class="validate-err" id="err_from"></span>
                                    </div>
                                </div>
                                 <div class="col-lg-4 col-md-4 col-sm-4 top25">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                            {{ Form::select('fromqtr',array("1"=>"11-1st Qtr","22"=>"2nd Qtr"),'', array('class' => 'form-control select3','id'=>'ba_business_account_no')) }}
                                        </div>
                                        <span class="validate-err" id="err_from"></span>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        {{Form::label('to',__('To'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::number('to','2022',array('class'=>'form-control'))}}
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-lg-4 col-md-4 col-sm-4 top25">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                            {{ Form::select('toqtr',array("1"=>"11-1st Qtr","22"=>"2nd Qtr"),'', array('class' => 'form-control select3','id'=>'ba_business_account_no')) }}
                                        </div>
                                        <span class="validate-err" id="err_from"></span>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        {{Form::label('taxpayername',__('Tax Payers/ Business Name'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{Form::text('taxpayername','',array('class'=>'form-control','required'=>'required','id'=>'taxpayername'))}}
                                        </div>
                                        <span class="validate-err" id="err_account_number"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="basicinfodiv">
                            <div class="row" style="padding-top:10px;">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('totaltaxdue',__('Total Tax Due'),['class'=>'form-label'])}}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::number('totaltax_due',$data->totaltax_due,array('class'=>'form-control','required'=>'required','id'=>'totaltax_due','step'=>'0.01'))}}
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('surcharge',__('Surcharge'),['class'=>'form-label'])}}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::number('surcharge',$data->surcharge,array('class'=>'form-control','required'=>'required','id'=>'surcharge','step'=>'0.01'))}}
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('interest',__('Interest'),['class'=>'form-label'])}}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::number('interest',$data->interest,array('class'=>'form-control','required'=>'required','id'=>'interest','step'=>'0.01'))}}
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('subtotal',__('Subtotal'),['class'=>'form-label'])}}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::number('subtotal',$data->subtotal,array('class'=>'form-control','required'=>'required','id'=>'subtotal','step'=>'0.01'))}}
                                    </div>
                                </div>
                            </div>
                            <div class="row"> {{Form::label('lessdiscount',__('Less:Discount'),['class'=>'form-label'])}}</div>
                             <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('otherdeduction',__('Other Deduction'),['class'=>'form-label'])}}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::number('otherdeduction',$data->otherdeduction,array('class'=>'form-control','required'=>'required','id'=>'otherdeduction','step'=>'0.01'))}}
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('appliedtax_credit',__('Applied Tax Credit'),['class'=>'form-label'])}}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::number('appliedtax_credit',$data->appliedtax_credit,array('class'=>'form-control','required'=>'required','id'=>'appliedtax_credit','step'=>'0.01'))}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('nettax_due',__('Net Tax Due'),['class'=>'form-label'])}}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::number('nettax_due',$data->nettax_due,array('class'=>'form-control','required'=>'required','id'=>'nettax_due','step'=>'0.01'))}}
                                    </div>
                                </div>
                            </div>
                               <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('checkamount_paid',__('Check Amount Paid'),['class'=>'form-label'])}}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::number('checkamount_paid',$data->checkamount_paid,array('class'=>'form-control','required'=>'required','id'=>'checkamount_paid','step'=>'0.01'))}}
                                    </div>
                                </div>
                            </div>
                               <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('cashamount_paid',__('Cash Amount Paid'),['class'=>'form-label'])}}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::number('cashamount_paid',$data->cashamount_paid,array('class'=>'form-control','required'=>'required','id'=>'cashamount_paid','step'=>'0.01'))}}
                                    </div>
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
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show">
                        <div class="row"  id="otheinfodiv">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('order_number',__('Order Number'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::number('order_number',$data->order_number,array('class'=>'form-control','required'=>'required','id'=>'order_number','step'=>'0.01'))}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4"></div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('date',__('Date'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('applicationdate','',array('class'=>'form-control','readonly'=>'readonly','id'=>'applicationdate'))}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('amount',__('Amount'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::number('amountdispaly',$data->subtotal,array('class'=>'form-control','required'=>'required','id'=>'amountdispaly','step'=>'0.01'))}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4"></div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                            </div>
                        </div>
                        <div class="row" style="padding-top:10px;">
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                    <div class="form-group">
                                        {{Form::label('orno',__('OR NO'),['class'=>'form-label'])}}
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::number('order_number','',array('class'=>'form-control','required'=>'required','id'=>'ordernumber','step'=>'0.01'))}}
                                    </div>
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                    <div class="form-group">
                                        {{Form::label('dated',__('Dated'),['class'=>'form-label'])}}
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::date('dated','',array('class'=>'form-control','required'=>'required','id'=>'dated'))}}
                                    </div>
                                </div>
                                 <div class="col-lg-1 col-md-1 col-sm-1">
                                    <div class="form-group">
                                        {{Form::label('amount',__('Amount'),['class'=>'form-label'])}}
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::number('totalamt_due','',array('class'=>'form-control','required'=>'required','id'=>'totalamt_due','step'=>'0.01'))}}
                                    </div>
                                </div>
                        </div>
                        <div class="row fee-details" style="height: 399px; overflow:auto;">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body table-border-style">
                                        <div class="table-responsive" id="feetable">
                                            
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

    <div class="row pt10" >
  
    <!--------------- Fees Details End Here------------------>
    <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 hide" id="banldetail">    
            <div class="row fee-details">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body table-border-style">
                            <div class="" id="paymenttable">
                                <table class="table">
                                    <thead>
                                        <tr><th>Fund</th><th>Check No</th><th>Drawee Bank</th><th>Date</th><th>Check Type</th><th>Amount</th></tr>
                                    </thead>
                                    @if(!empty($data->id))
                                    <tbody>
                                       @foreach ($checkdetails as $val)
                                       <tr>
                                            {{ Form::hidden('licence_payment_detailid[]',$val->id, array('class' => 'licence_payment_detailid')) }}
                                            <td>{{Form::text('fund[]',$val->fund,array('class'=>'form-control fund','placeholder'=>''))}}</td>
                                            <td>{{Form::text('checkno[]',$val->checknumber,array('class'=>'form-control checkno','placeholder'=>''))}}</td>
                                            <td>{{Form::text('bank[]',$val->bankname,array('class'=>'form-control bankname','placeholder'=>''))}}</td>
                                            <td>{{Form::date('date[]',$val->date,array('class'=>'form-control date','placeholder'=>''))}}</td>
                                            <td>{{Form::text('checktype[]',$val->checktype,array('class'=>'form-control checktype','placeholder'=>''))}}</td>
                                            <td>{{Form::text('amount[]',$val->amount,array('class'=>'form-control amount','placeholder'=>''))}}</td>
                                       </tr>
                                        @endforeach
                                    </tbody>
                                    @elseif(empty($data->id))
                                    <tbody>
                                        <tr>
                                        <td><input class='form-control' id='fund' name='fund[]' type='text' value=""></td>
                                        <td><input class='form-control' id='checkno' name='checkno[]' type='text' value=""></td>
                                        <td><input class='form-control' id='bank' name='bank[]' type='text' value=""></td>
                                        <td><input class='form-control' id='date' name='date[]' type='date' value=""></td>
                                        <td><input class='form-control' id='checktype' name='checktype[]' type='text' value=""></td>
                                        <td><input class='form-control' id='amount' name='amount[]' type='text' value=""></td>
                                        </tr>
                                        <tr>
                                        <td><input class='form-control' id='fund' name='fund[]' type='text' value=""></td>
                                        <td><input class='form-control' id='checkno' name='checkno[]' type='text' value=""></td>
                                        <td><input class='form-control' id='bank' name='bank[]' type='text' value=""></td>
                                        <td><input class='form-control' id='date' name='date[]' type='date' value=""></td>
                                        <td><input class='form-control' id='checktype' name='checktype[]' type='text' value=""></td>
                                        <td><input class='form-control' id='amount' name='amount[]' type='text' value=""></td>
                                        </tr>
                                     </tbody>
                                     @endif
                                     
                                </table>
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
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
        <i class="fa fa-save icon"></i>
        <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
    </div>
                            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Save')}}" class="btn  btn-primary"> -->
</div>
{{Form::close()}}



<script src="{{ asset('js/add_BplopermitLicence.js') }}"></script>
<script src="{{ asset('js/ajax_validation.js') }}"></script>



