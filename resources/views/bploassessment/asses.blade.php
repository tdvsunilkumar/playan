{{Form::open(array('url'=>'bploapplication','method'=>'post'))}}
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
 </style>

<div class="modal-body">
    <div class="row pt10" >
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
                                            {{Form::text('account_number',$data->ba_business_account_no,array('class'=>'form-control','required'=>'required','id'=>'account_number'))}}
                                        </div>
                                        <span class="validate-err" id="err_account_number"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        {{Form::label('date',__('Date'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{Form::date('date',$data->ba_date_started,array('class'=>'form-control','required'=>'required'))}}
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
                                        {{Form::text('ownar_name',$data->p_complete_name_v1,array('class'=>'form-control','required'=>'required','id'=>'ownar_name'))}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('business_name',__('Business Name'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('business_name',$data->ba_business_name,array('class'=>'form-control','required'=>'required','id'=>'business_name'))}}
                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('area',__('Area [Sq.m]'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('area',$data->ba_building_total_area_occupied,array('class'=>'form-control'))}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('no_of_personnel',__('No. of Personnel'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('no_of_personnel',$data->no_of_personnel,array('class'=>'form-control'))}}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    {{Form::label('address',__('Address'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::textarea('address',$data->ba_address_house_lot_no.','.$data->ba_address_street_name,array('class'=>'form-control','rows'=>1))}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Owners & Busines Information End Here------------------>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <p>In case of New Application. Please click the 2nd, 3rd or 4th Qtr. Sale buttons whichever it available for the assessment of subsequent quarters Gross Sales.</p>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8">
            <div class="row btns-sales-assesment">
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <a type="button" name="btn_2nd" class="btn">2nd Qtr. Sales Assessment</a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <a type="button" name="btn_2nd" class="btn">3rd Qtr. Sales Assessment</a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <a type="button" name="btn_2nd" class="btn">4th Qtr. Sales Assessment</a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <a type="button" name="btn_2nd" class="btn">Schedule of Paid UP Capital & Gross Sales</a>
                </div>
                
            </div>
        </div>
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
                                    <th>{{__('1st Qut Tax/Fee')}}</th>
                                    <th>{{__('2nd Qut Tax/Fee')}}</th>
                                    <th>{{__('3rd Qut Tax/Fee')}}</th>
                                    <th>{{__('4th Qut Tax/Fee')}}</th>
                                    <th>{{__('Total Taxes/Fees')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $qutr_total_1=0; 
                                    $qutr_total_2=0; 
                                    $qutr_total_3=0; 
                                    $qutr_total_4=0;
                                    $totalFee=0; 
                                
                                @endphp
                                @foreach ($arrFees as $val)
                                    @php 
                                        $qutr_total_1+=$val['1_qutr_fee']; 
                                        $qutr_total_2+=$val['2_qutr_fee']; 
                                        $qutr_total_3+=$val['3_qutr_fee']; 
                                        $qutr_total_4+=$val['4_qutr_fee'];
                                        $totalFee+=$val['total_fee'];
                                    @endphp
                                    <tr class="font-style">
                                        <td>{{ $val['cover_year']}}</td>
                                        <td>{{ $val['tax_type_fee']}}</td>
                                        <td>{{ $val['top_code']}}</td>
                                        <td>{{ number_format($val['1_qutr_fee'],2)}}</td>
                                        <td>{{ number_format($val['2_qutr_fee'],2)}}</td>
                                        <td>{{ number_format($val['3_qutr_fee'],2)}}</td>
                                        <td>{{ number_format($val['4_qutr_fee'],2)}}</td>
                                        <td>{{ number_format($val['total_fee'],2)}}</td>
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
                                    <td class="sky-blue">{{number_format($qutr_total_1,2)}}</td>
                                    <td class="sky-blue">{{number_format($qutr_total_2,2)}}</td>
                                    <td class="sky-blue">{{number_format($qutr_total_3,2)}}</td>
                                    <td class="sky-blue">{{number_format($qutr_total_4,2)}}</td>
                                    <td class="red">{{number_format($totalFee,2)}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--------------- Fees Details End Here------------------>

    <!--------------- Other Details End Here------------------>
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2"><br>
            <a type="button" name="make_payment" class="btn btn-primary">Payments</a>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2"><br>
            <a type="button" name="bill_details" id="bill_details" class="btn btn-primary" disabled="disabled">Bill Now</a>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-4">
            <div class="form-group">
                {{Form::label('assessed_by',__('Assessed By'),['class'=>'form-label'])}}
                <div class="form-icon-user">
                    {{Form::text('area','',array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-4">
            <div class="form-group">
                {{Form::label('assessed_date',__('Date'),['class'=>'form-label'])}}
                <div class="form-icon-user">
                    {{Form::date('assessed_date',$data->ba_date_started,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-4">
            <div class="form-group">
                {{Form::label('assessed_time',__('Time'),['class'=>'form-label'])}}
                <div class="form-icon-user">
                    {{Form::text('assessed_time','',array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
    </div>
    <!--------------- Other Details End Here------------------>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
</div>
{{Form::close()}}

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
<script src="{{ asset('js/ajax_validation.js') }}"></script>



