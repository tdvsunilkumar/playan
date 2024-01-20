<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <style type="text/css">
        @import url('https://fonts.googleapis.com/css?family=Fjalla+One|Montserrat:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
        * { margin: 0; padding: 0; }
        @page {margin: 0mm; margin-header: 0mm;margin-footer: 0mm;}
        body { font-family: 'Montserrat', sans-serif; font-size: 12px; }
        table td, table th { border: 1px solid black; padding: 5px; }
        p {margin: 0px; padding: 0px}
        p .indent{text-indent: 25px; margin-left: 25px;}
        h4 {margin: 0px; padding: 0px;font-size:12px;}
        textarea { border: 0; font-size: 14px; font-family: 'Montserrat', sans-serif;overflow: hidden; resize: none; }
        table { border-collapse: collapse; }
        .mb-2{margin-bottom: 2mm}
        .bg{background-color: red;}
        .bg1{background-color: blue;}
    </style>
  <title>Assessment & Fees</title>
</head>

<body>
    <div style="">
        <div style="float: left; width: 100%; border-top:none; border-right:none; border-bottom:none; border-left:none;">
            <table width="100%">
                <tr>
                    <td width="100%" style="text-align:center; border:none; padding-top:0px; border-bottom:none;">
                        <p style="">Republic of the Philippines</p>
                        <p style="">Department of Interior and Local Government</p>
                        <p style="">BUREAU OF FIRE PROTECTION</p>
                    </td>
                </tr>
            </table>
            <div style="width: 35%; float:left; margin-top: -35px;">
                <div style="text-align:center; width:80%; margin-right:20%;">
                    <p style="border-bottom: solid 1px black;">REGION III, NUEVA ECIJA</p>
                    <p style="border-bottom: solid 1px black;">PALAYAN CITY FIRE STATION</p>
                    <p style="font-weight: 500; text-align: center;">BARANGAY ATATE, PALAYAN CITY NUEVA ECIJA</p>
                </div>
            </div>
            <div style="width:29.8%; float:left; display:block; margin-top:30px">
                <div width="50%" style="text-align:center; margin-left:25%">
                    <p style="text-transform: uppercase;"><strong style="font-size:14px">ORDER OF PAYMENT</strong></p>
                </div>
            </div>
            <div style="width: 35%; float:right; text-align: right; border-right: none; text-align:right; margin-top: -30px;">
                <div style="text-align:center; width:80%; margin-left:20%;">
                    <p style="border-bottom: solid 1px black; text-align: left;"><strong>OPS NO.:</strong> {{ $data ? $data->bfpas_control_no : ''}}</p>
                    <p style="border-bottom: solid 1px black; text-align: left;"><strong>DATE:</strong> {{ $data ? $data->created_at : ''}}</p>
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>

        <table width="100%" style="padding:0px">
            <tr>
                <td style="width: 22%; border:none; border-top: none; border-bottom: none; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">
                    <h4 style="padding:0px">NAME OF ESTABLISHMENT/PROJECT</h4>
                </td>
                <td style="border-top: none; border-right: none; border-bottom: 1px solid black; border-left: none; padding-top: 5px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">{{$arrBusiness->busn_name}} </td>
            </tr>
        </table>

        <table width="100%" style="padding:0px">
            <tr>
                <td style="width: 7%; border:none; border-top: none; border-bottom: none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">
                    <h4 style="padding:0px">LOCATION</h4>
                </td>
                <td style="border-top: none; border-right: none; border-bottom: 1px solid black; border-left: none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">{{$arrBusiness->busn_address}} </td>
            </tr>
        </table>

        <table width="100%" style="padding:0px">
            <tr>
                <td style="width: 22%; border:none; border-top: none; border-bottom: none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">
                    <h4 style="padding:0px">OWNER/NAME OF REPRESENTATIVE</h4>
                </td>
                <td style="border-top: none; border-right: none; border-bottom: 1px solid black; border-left: none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">{{$arrBusiness->ownar_name}} </td>
            </tr>
        </table>

        <table width="100%" style="font-size:12px; font-style: italic;">
            <tr>
                <td colspan="3" style="text-align:left; padding:0px; padding-top: 5px; border:none; border-bottom: 0px; border-top:0px">
                    <strong>FIRE SAFETY CLEARANCE APPLYING FOR:</strong>
                </td>
            </tr>
            <tr>
                <td style="width:33%; border:none; padding:0px; padding-top: 0px; vertical-align: top;">
                    <table width="100%" style="font-size:12px;">
                        <tr>
                            <td style="border:none; padding:2px;">
                                <img src="{{url('/assets/images/unchecked-checkbox.jpg')}}" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">Fire Safety Evaluation Clearance (FSEC)
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:33%; border:none; padding:0px; padding-top: 0px; vertical-align: top;">
                    <table width="100%" style="font-size:12px;">
                        <tr>
                            <td style="border:none; padding:2px;">
                                <img src="{{url('/assets/images/checked-checkbox.jpeg')}}" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">Fire Safety Inspection Certificate (FSIC) 
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:33%; border:none; padding:0px; padding-top: 0px; vertical-align: top;">
                    <table width="100%" style="font-size:12px;">
                        <tr>
                            <td style="border:none; padding:2px;">
                                <img src="{{url('/assets/images/unchecked-checkbox.jpg')}}" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">Others (Please Indicate) _____________________________
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" style="border:none; font-style: italic;">
            <tr>
                <td width="100%" style="text-align:left; border:none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">
                    <h4 style="padding:0px">APPLICABLE FEES (Fill-up)</h4>
                </td>
            </tr>
        </table>


        <table width="100%" style="border:none; font-size:10px">

            <tr>
                <td width="70%" style="text-align:center; padding-top:1px; padding-bottom:1px;font-size:9px;">
                  <strong style="font-size:9px">NATURE OF PAYMENT</strong>
                </td>
                <td width="7%" style="text-align:center; padding-top:1px; padding-bottom:1px;">
                  <strong style="font-size:9px">ACCOUNT CODE</strong>
                </td>
                <td width="13%" style="text-align:center; padding-top:1px; padding-bottom:1px;">
                  <strong style="font-size:9px">BASIS OF COMPUTATION</strong>
                </td>
                <td width="10%" style="text-align:center; padding-top:1px; padding-bottom:1px;">
                  <strong style="font-size:9px">TOTAL</strong>
                </td>
            </tr>
            @foreach($arrNature as $key=>$val)
                @php
                    $arrOpt=array();
                    if($data->id>0){
                        if(!empty($val['fmaster_subdetails_json'])){
                            $arrOpt = json_decode($val['fmaster_subdetails_json'],true);
                        }
                    }
                    $assessed_amount='';
                    $amount_fee='';
                    if($val['baaf_assessed_amount']>0){
                        $assessed_amount=number_format($val['baaf_assessed_amount'],2);
                    }
                    if($val['baaf_amount_fee']>0){
                        $amount_fee=number_format($val['baaf_amount_fee'],2);
                    }
                @endphp
                <tr>
                    @if(count($arrOpt)==0)
                        <td style="text-align:left; padding-top:1px; padding-bottom:1px;">
                            {{$val['fmaster_description']}}
                        </td>
                    @else 
                        <td style="padding:0px;">
                            <table width="100%" style="font-size:9px;">
                                <tr>
                                    <td colspan="4" style="text-align:left; border:none; padding: 0px;">
                                        <strong>{{$val['fmaster_description']}}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:100%; border:none; padding: 0px; vertical-align: top;">
                                        <table width="100%" style="font-size:10px;">
                                            <tr>
                                            @php $i=0; @endphp
                                            @foreach($arrOpt as $o_key=>$o_val)
                                                @php
                                                    $value =$o_val['value'];
                                                    $arrPrevChecked=array();
                                                    $checked='';
                                                    $img='unchecked-checkbox.jpg';
                                                    if(!empty($val['fee_option_json'])){
                                                        $arrPrevChecked = json_decode($val['fee_option_json'],true);
                                                        $img = (in_array($o_val['value'],$arrPrevChecked))?'checked-checkbox.jpeg':'unchecked-checkbox.jpg';
                                                    }
                                                @endphp

                                                @if($i%4==0)
                                                    </tr>
												<tr>
														@endif
														<td style="border:none; padding:2px;padding-left:18px;" >
                                                           @if($value)
															<img src="{{url('/assets/images/'.$img)}}" style="height:10px; width:10px; padding-right:5px; vertical-align: middle;padding-left:-15px;">
                                                            @else
                                                            @endif
                                                            <span style="">{{$o_val['value']}}</span>
														</td>
                                                       
														@php $i++; @endphp
													@endforeach
												</tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    @endif

                    <td style="text-align:center; padding: 0px;">{{$val['fmaster_code']}}</td>
                    <td style="text-align:center; padding: 0px;">{{$assessed_amount}}</td>
                    <td style="text-align:center; padding: 0px;">{{$amount_fee}}</td>
                </tr>
            @endforeach 
            
        </table>

        
        <table width="100%" style="padding:0px">
            <tr>
                <td style="width: 23%; border:none; border-top: none; border-bottom: none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">
                    <h4 style="padding:0px">TOTAL AMOUNT OF FIRE CODE FEES:</h4>
                </td>
                <td style="border-top: none; border-right: none; border-bottom: 1px solid black; border-left: none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;"></td>
                <td style="text-align: center;width: 10%;border: none;border-left: 1px solid #000; border-right: 1px solid #000;border-bottom: 1px solid;">{{ optional($data)->bfpas_total_amount_paid ? number_format($data->bfpas_total_amount_paid, 2) : '' }} </td>
            </tr>
        </table>
        <table width="100%" style="padding:0px">
            <tr>
                <td style="width: 18%; border:none; border-top: none; border-bottom: none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">
                    <h4 style="padding:0px">TOTAL AMOUNT (IN WORDS):</h4>
                </td>
                <td style="border-top: none; border-right: none; border-bottom: 1px solid black; border-left: none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">{{$amountinWord}} </td>
            </tr>
        </table>
        <div style="clear:both;"></div>

        
        <div style="float: left; width: 100%; border-top:none; border-right:none; border-bottom:none; border-left:none;">
            <div style="width: 33.33%; float:left;">
                <table width="100%" style="padding:0px">
                    <tr>
                        <td style="width: 40%; border:none; border-top: none; border-bottom: none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">
                            <h4 style="padding:0px">Official Receipt No. :</h4>
                        </td>
                        <td style="border-top: none; border-right: none; border-bottom: 1px solid black; border-left: none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">{{ $data ? $data->bfpas_payment_or_no : ''}}</td>
                    </tr>
                </table>
            </div>
            
            <div style="width:33.33%; float:left; display:block;">
                <table width="100%" style="padding:0px">
                    <tr>
                        <td style="width: 41%; border:none; border-top: none; border-bottom: none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 50px;">
                            <h4 style="padding:0px">Amount Paid:</h4>
                        </td>
                        <td style="border-top: none; border-right: none; border-bottom: 1px solid black; border-left: none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">{{ isset($data->bfpas_total_amount_paid) ? number_format($data->bfpas_total_amount_paid, 2) : ''}}</td>
                    </tr>
                </table>
            </div>
            <div style="width: 33.33%; float:right; text-align: right; border-right: none; text-align:right;">
                <table width="100%" style="padding:0px">
                    <tr>
                        <td style="width: 43%; border:none; border-top: none; border-bottom: none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 50px;">
                            <h4 style="padding:0px">Payment Date:</h4>
                        </td>
                        <td style="border-top: none; border-right: none; border-bottom: 1px solid black; border-left: none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">{{ $data ? $data->bfpas_date_paid : ''}}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div style="clear:both;"></div>
        <div style="float: left; width: 100%; border-top:none; border-right:none; border-bottom:none; border-left:none;">
            <div style="width: 33.33%; float:left;">
                &nbsp;
            </div>
            
            <div style="width:66.66%; float:left; display:block;">
               <table width="100%" style="padding:0px; text-align:center;  ">
                    <tr>
                        <td style="width: 35%;text-align:center; border:none; border-top: none; border-bottom: none; padding-top: 5px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">
                            <p>{{ $employee ? $employee->employee_name : ''}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 35%; border-top: none; border-right: none; border-bottom: none; border-left: none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">

                            <p style="border-top: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name and Signature of Fire Fee Assessor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div style="clear:both;"></div>

        

        <table width="100%" style="padding:0px; text-align:center; font-size: 10px;">
            <tr>
                <td style="border:none; border-top: none; border-bottom: none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">
                    <strong style="color: red;">PAALALA "MAHIGPIT NA IPINAGBABAWAL NG PAMUNUAN NG BUREAU OF FIRE PROTECTION SA MGA KAWANI NITO ANG MAGBENTA O MAGREKOMENDA NG ANUMANG BRAND NG FIRE EXTINGUISHER</strong><br>
                    <strong>"FIRE SAFETY IS OUR MAIN CONCERN"</strong>
                </td>
            </tr>
          
        </table>
        <table width="100%" style="padding:0px; text-align:left; font-size: 10px;">
            <tr>
                <td style="border:none; border-top: none; border-bottom: none; padding-top: 2px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;">
                    <strong style="">BFP-QSF-FSED-030 Rev. 02 (11.27.99)</strong>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>














