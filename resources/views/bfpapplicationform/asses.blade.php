{{Form::open(array('url'=>'bfpapplicationform/assesnow','method'=>'post'))}}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
 <style>
    .modal-xl {
        max-width: 1350px !important;
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
     .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #8080802e;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .field-requirement-details-status label{margin-top: 7px;}
 </style>

<div class="modal-body">
   
    <div class="row pt10" >
        
    

        <div class="col-lg-12 col-md-12 col-sm-12">
            <p style="text-align: center;"><b><u>ORDER OF PAYMENT</u></b></p>
          
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12" >
            <div class="row">
             <div class="col-sm-9">
                <p>NAME OF STABLEISHMENT/PROJECT : <b>{{$data->p_complete_name_v1}}</b></p>
                <p>LOCATION : <b>{{$data->ba_address_house_lot_no}} {{$data->ba_address_street_name}}</b></p>
                <p>OWNER/NAME OF REPRESENTATIVE :{{$data->p_first_name}} {{$data->p_middle_name}} {{$data->p_family_name}}</p>
             </div>
             <div class="col-sm-3">
                  <p>OPS No.<u style="width: 20px;"></u></p>
                  <p>Date:<u> {{$data->ba_date_started}}</u>
             </div>
           </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <p>FIRE SAFETY CLEARANCE APPLYING FOR :</p>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <p>Fire Safety Evaluation Clearance(FSIC)</p>
         </div>
         <div class="col-lg-4 col-md-4 col-sm-4">
            <p>Fire Safety Inspection Certificate(FSIC)</p>
         </div>
         <div class="col-lg-4 col-md-4 col-sm-4">
            <p>Others:<u>(Pis indicate)</u></p>
         </div>
    </div>
     </div>
        
    <!--------------- Fees Details End Here------------------>
    <div class="row fee-details">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="">
                            <div class="row field-requirement-details-status">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                           {{__('APPLICABLE FEES (FILL-up).')}}
                                      </div>
                                     <div class="col-lg-4 col-md-4 col-sm-4">
                                      </div>
                                      <div class="col-lg-2 col-md-2 col-sm-2">
                                        <input type="button" id="btn_addmore_feedetails" class="btn btn-success" value="Add More" style="padding: 0.4rem 0.76rem !important;">
                                     </div>
                            </div>
                           <span class="checkboxesdata activity-details" id="feedetails">
                            
                           </span>        
                    </div>
                </div>
            </div>
        </div>
    </div>

     <div class="row fee-details">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="">
                            <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                           {{__('Total Amount(in words).')}}
                                      </div>
                                     <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group"> {{ Form::label('', __(''),['class'=>'form-label','id'=>'amountinword']) }}</div>
                                      </div>
                                       <div class="col-lg-2 col-md-2 col-sm-2">
                                         {{ Form::label('showpaidamount', __('Paid Amount'),['class'=>'form-label','id'=>'']) }}
                                     </div>
                                      <div class="col-lg-2 col-md-2 col-sm-2">
                                         <div class="form-group">{{ Form::text('showpaidamount','', array('class' => 'form-control naofbussi showpaidamount','required'=>'required','id'=>'showpaidamount')) }}</div>
                                     </div>
                            </div>
                           <span class="checkboxesdata activity-details" id="feedetails">
                            
                           </span>        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--------------- Fees Details End Here------------------>

    <!--------------- Other Details End Here------------------>
   
        
    <!--------------- Other Details End Here------------------>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" name="submit" value="{{('Create')}}" class="btn  btn-primary">
</div>
{{Form::close()}}

<div id="hidenCheckboxHtml" class="hide">
     <div class="row removefeemasterdata" style="padding: 5px 0px;">
         <div class="col-lg-8 col-md-8 col-sm-8">
                    <div class="form-group">
                        <div class="form-icon-user">{{ Form::select('arrayoffee',$feemasterarray,'',array('class' => 'form-control arrayoffee','required'=>'required','id'=>'arrayoffee0')) }}</div>
                    </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">{{ Form::text('basicofcomputation[]','1000', array('class' => 'form-control naofbussi disabled-field','required'=>'required','id'=>'basicofcomputation')) }}</div>
        </div>
         <div class="col-lg-1 col-md-1 col-sm-1">
            <div class="form-group">{{ Form::text('paidamount[]','', array('class' => 'form-control naofbussi paidamount','required'=>'required','id'=>'paidamount')) }}</div>
        </div>
         <div class="col-lg-1 col-md-1 col-sm-1"><div class="form-group"><input type="button" name="btn_cancel" class="btn btn-success btn_cancel_feemaster" cid="" value="Delete" style="padding: 0.4rem 1rem !important;"></div></div>
         <span class="checkboxarea row"></span>
    </div>
</div>

<div class="modal bussiness-model" id="myModal" data-backdrop="static">
    <div class="modal-dialog modal-xll modalDiv">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Billing Process</h4>
                <a class="close closeModel"  type="edit" data-dismiss="modal" aria-hidden="true">X</a>
                </div>
                <div class="container"></div>
                <div class="modal-body">
                <div class="row pt10">                 
                                <!--------------- Account No. Date & Period Start Here---------------->
                <div class="col-lg-6 col-md-6 col-sm-6"  id="accordionFlushExample">  
                    <div  class="accordion accordion-flush">
                        <div class="accordion-item">
                            <h6 class="accordion-header" id="flush-headingone">
                                <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                                    <h6 class="sub-title accordiantitle">{{__("Account No. Date & Period")}}</h6>
                                </button>
                            </h6>
                            <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                                <div class="basicinfodiv">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                {{Form::label('account_number',__('Business Acct. No'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                <div class="form-icon-user">
                                                   {{Form::text('accountnumber',$data->ba_business_account_no,array('class'=>'form-control','required'=>'required','id'=>'accountnumber'))}}
                                                </div>
                                                <span class="validate-err" id="err_account_number"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                {{Form::label('date1',__('Date'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                <div class="form-icon-user">
                                                    {{Form::date('date1',$data->ba_date_started,array('class'=>'form-control','required'=>'required'))}}
                                                </div>
                                                <span class="validate-err" id="err_date"></span>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                {{Form::label('from',__('From'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                <div class="form-icon-user">
                                                    {{Form::text('from','',array('class'=>'form-control','required'=>'required'))}}
                                                </div>
                                                <span class="validate-err" id="err_from"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                {{Form::label('to',__('To'),['class'=>'form-label'])}}
                                                <div class="form-icon-user">
                                                    {{Form::text('to','',array('class'=>'form-control'))}}
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
                <div class="col-lg-6 col-md-6 col-sm-6" id="accordionFlushExample2">
                    <div class="accordion accordion-flush">
                        <div class="accordion-item">
                            <h6 class="accordion-header" id="flush-headingtwo">
                                <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                                    <h6 class="sub-title accordiantitle">{{__('Owners & Business  Information')}}</h6>
                                </button>
                            </h6>

                            <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                                <div class="row"  id="otheinfodiv">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('ownar_name',__('Owner'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('ownar_name',$data->p_complete_name_v1,array('class'=>'form-control','required'=>'required','id'=>'ownar_name1'))}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('business_name',__('Business Name'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('business_name',$data->ba_business_name,array('class'=>'form-control','required'=>'required','id'=>'business_name1'))}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('area',__('Area [Sq.m]'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('area1',$data->ba_building_total_area_occupied,array('class'=>'form-control'))}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('no_of_personnel',__('No. of Personnel'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('no_of_personnel1',$data->no_of_personnel,array('class'=>'form-control'))}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <div class="form-group">
                                            {{Form::label('address',__('Address'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::textarea('address1',$data->ba_address_house_lot_no.','.$data->ba_address_street_name,array('class'=>'form-control','rows'=>1,'id'=>"address1"))}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <!--------------- Owners & Busines Information End Here------------------>
              </div>
                 <!--------------- Fees Details End Here------------------>
            <div class="row fee-details">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{__('Cover Year.')}}</th>
                                            <th>{{__("Kind of Tax/Fee")}}</th>
                                            <th>{{__('Top Code')}}</th>
                                            <th>{{__('Tax Amount')}}</th>
                                            <th>{{__('Excess Tax')}}</th>
                                            <th>{{__('Rate')}}</th>
                                            <th>{{__('Surcharge')}}</th>
                                            <th>{{__('Interest')}}</th>
                                            <th>{{__('Total Due')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php 
                                            $qutr_total_tax=0; 
                                            $qutr_total_excesstax=0; 
                                            $qutr_total_rate=0; 
                                            $qutr_total_sircharge=0;
                                            $qutr_total_interest=0;
                                            $totalTax=0; 
                                        
                                        @endphp
                                        @foreach ($arrtaxFees as $val)
                                            @php 
                                                $qutr_total_tax+=$val['tax_amount']; 
                                                $qutr_total_excesstax+=$val['excess_tax']; 
                                                $qutr_total_rate+=$val['rate']; 
                                                $qutr_total_sircharge+=$val['sircharge'];
                                                $qutr_total_interest+=$val['interest'];
                                                $totalTax+=$val['totalTax'];
                                            @endphp
                                            <tr class="font-style">
                                                <td>{{ $val['cover_year']}}</td>
                                                <td>{{ $val['tax_type_fee']}}</td>
                                                <td>{{ $val['top_code']}}</td>
                                                <td>{{ number_format($val['tax_amount'],2)}}</td>
                                                <td>{{ $val['excess_tax']}}</td>
                                                <td>{{ $val['rate']}} %</td>
                                                <td>{{ $val['sircharge']}}</td>
                                                <td>{{ $val['interest']}}</td>
                                                <td>{{ number_format($val['totalTax'],2)}}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="font-style">
                                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                        </tr>
                                        <tr class="font-style">
                                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                        </tr>
                                        <tr class="font-style">
                                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                        </tr>
                                        
                                        <tr class="font-style">
                                            <td colspan="3"><b>Total Assessment....</b></td>
                                            <td class="sky-blue">{{number_format($qutr_total_tax,2)}}</td>
                                            <td class="sky-blue">{{number_format($qutr_total_excesstax,2)}}</td>
                                            <td class="sky-blue">{{number_format($qutr_total_rate,2)}}</td>
                                            <td class="sky-blue">{{number_format($qutr_total_sircharge,2)}}</td>
                                            <td class="sky-blue">{{number_format($qutr_total_interest,2)}}</td>
                                            <td class="sky-blue">{{number_format($totalTax,2)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                 <div class="col-lg-2 col-md-2 col-sm-2"><br>
                   <a type="button" name="Printbill" id="Printbill" class="btn btn-primary">Print</a>
                 </div>
            </div>
            </div> 
        </div>
    </div>
</div>

<div class="modal print-model" id="myModal1" data-backdrop="static">
    <div class="modal-dialog modal-xll modalDiv">
        <div class="modal-content">
                <div class="modal-body">
                  <div id="printtaxdiv">  
                  <div class="table-responsive">
                    <table class="table" style="width:100%; padding-top:20px;">
                        <tbody>
                            <td>
                                <div style="float:left;">
                                    <img src="{{ asset('assets/images/logo.png') }}" style="max-width:100px;max-height:100px;">
                                </div>
                                <div style="float:left;padding-left: 75px;">
                                    <p style="text-align: center;">PALAYAN CITY <br>TREASURE'S OFFICE <br>TAX ORDER OF PAYMENT <br>Business Permit & License</p>
                                </div>
                            </td>
                            <!-- <td><p style="text-align: center;">PALAYAN CITY <br>TREASURE'S OFFICE <br>TAX ORDER OF PAYMENT <br>Business Permit & License</p></td> -->
                        </tbody>
                    </table>
                    <table class="table" width="100%" style="padding-top: 20px;padding-bottom: 10px;">
                        <tbody>
                            <tr style="font-size:12px;">
                                <td>
                                    <p style="text-align:center;"> Date:<span id="pdate"><?php echo date('d/m/Y',strtotime($data->ba_date_started));?></span><br></p>
                                    <div style="float:left;" >
                                        <span style="text-transform: uppercase;">Owner's Name: <span id="pownername">{{$data->p_complete_name_v1}}</span></span><br>
                                        Address: <span id="paddress"></span>{{$data->ba_address_house_lot_no.','.$data->ba_address_street_name}}
                                        <br>
                                        Business Name: <span id="ptradingname">{{$data->ba_business_name}}</span><br>
                                        Last OR. No: 4956895,  05/09/2022 <br>
                                        Last Amount Paid:{{number_format($totalTax,2)}}
                                    </div>
                                    <div style="float:right;text-align: right;">
                                        <span style="text-transform: uppercase;">Business Account No:</span> <span id="paccountno"></span>{{$data->ba_business_account_no}}<br>
                                        Period:<span id="pyear">{{$data->ba_cover_year}}</span><br>
                                        Application Id:{{$data->application_id}}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table print-table-list" style="width:100%;border-collapse: collapse;">
                      <thead class="print-heading">
                         <tr style="font-size:9px;text-align:center;">
                            <th>TAX <br> CODE</th>
                            <th>PARTICULARS</th>
                            <th>TAX <br> BASE</th>
                            <th>TAX <br> AMOUNT</th>
                            <th>EXCESS</th>
                            <th>RATE</th>
                            <th>SURCHARGES<br>(2.5%)</th>
                            <th>INTEREST</th>
                            <th>TOTAL</th>
                        </tr>
                     </thead>
                       <tbody>
                                        @php 
                                            $qutr_total_tax=0; 
                                            $qutr_total_excesstax=0; 
                                            $qutr_total_rate=0; 
                                            $qutr_total_sircharge=0;
                                            $qutr_total_interest=0;
                                            $totalTax=0; 
                                        
                                        @endphp
                                        @foreach ($arrtaxFees as $val)
                                            @php 
                                                $qutr_total_tax+=$val['tax_amount']; 
                                                $qutr_total_excesstax+=$val['excess_tax']; 
                                                $qutr_total_rate+=$val['rate']; 
                                                $qutr_total_sircharge+=$val['sircharge'];
                                                $qutr_total_interest+=$val['interest'];
                                                $totalTax+=$val['totalTax'];
                                            @endphp
                                            <tr class="" style="font-size:9px;text-align:center;">
                                                <td></td>
                                                <td>{{ $val['tax_type_fee']}}</td>
                                                <td>{{ $val['top_code']}}</td>
                                                <td>{{ number_format($val['tax_amount'],2)}}</td>
                                                <td>{{ $val['excess_tax']}}</td>
                                                <td>{{ $val['rate']}} %</td>
                                                <td>{{ $val['sircharge']}}</td>
                                                <td>{{ $val['interest']}}</td>
                                                <td>{{ number_format($val['totalTax'],2)}}</td>
                                            </tr>
                                            
                                        @endforeach
                                        <tr style="font-size:9px;text-align:center;border:0.5px solid gray;border-style: dashed; border-width: 0.5px 0.1px 0.5px 0.5px;">
                                            <td></td>
                                            <td></td>
                                            <td><b>BUSSINESS/ PERMIT:</b></td>
                                            <td>{{number_format($qutr_total_tax,2)}}</td>
                                            <td>{{number_format($qutr_total_excesstax,2)}}</td>
                                            <td>{{number_format($qutr_total_rate,2)}}</td>
                                            <td>{{number_format($qutr_total_sircharge,2)}}</td>
                                            <td>{{number_format($qutr_total_interest,2)}}</td>
                                            <td>{{number_format($totalTax,2)}}</td>
                                        </tr>
                                         <tr style="font-size:9px;text-align:center;border:0.5px solid gray;border-style: dashed; border-width: 0.5px 0.1px 0.5px 0.5px;">
                                            <td></td>
                                            <td></td>
                                            <td><b>GRAND TOTAL:</b></td>
                                            <td>{{number_format($qutr_total_tax,2)}}</td>
                                            <td>{{number_format($qutr_total_excesstax,2)}}</td>
                                            <td>{{number_format($qutr_total_rate,2)}}</td>
                                            <td>{{number_format($qutr_total_sircharge,2)}}</td>
                                            <td>{{number_format($qutr_total_interest,2)}}</td>
                                            <td>{{number_format($totalTax,2)}}</td>
                                        </tr>
                        </tbody>
                 
                   </table>
                    <table class="table">
                        <tbody>
                        <tr style="font-size:12px;"><br><br><br>
                            <td><span>Please Proceed to License Tellor/Collector For Payment:</span></td>
                        </tr>
                        <tr style="font-size:12px;">
                            <td><span>Proceessed By:</span></td>
                        </tr>
                        </tbody>
                   </table>
                  </div> 
                 </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                   <a type="button" name="printinv" id="printinv" class="btn btn-primary">Print Tax Bill</a>
                </div>
             </div>
       </div>
   </div>
</div>
  

<script src="{{ asset('js/assesNow.js') }}"></script>




