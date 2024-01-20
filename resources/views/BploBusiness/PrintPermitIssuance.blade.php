<!doctype html>
<html lang="en">

<head>

  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <style type="text/css">
        @import url('https://fonts.googleapis.com/css?family=Fjalla+One|Montserrat:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
        * { margin: 0; padding: 0; }
        @page {margin-top: 12mm; margin-bottom: 12mm; margin-header: 0mm;margin-footer: 0mm;}
        body { font-family: 'Montserrat', sans-serif; font-size: 12px; }
        table td, table th { border: 1px solid black; padding: 5px; }
        p {margin: 0px; padding: 0px}
        h4 {margin: 0px; padding: 0px}
        textarea { border: 0; font-size: 14px; font-family: 'Montserrat', sans-serif;overflow: hidden; resize: none; }
        table { border-collapse: collapse; }
        .mb-2{margin-bottom: 2mm}
        .bg{background-color: red;}
        .bg1{background-color: blue;}
        .w100{width: 100%}
        .w80{width: 80%}
        .w70{width: 70%}
        .w60{width: 60%}
        .w50{width: 50%}
        .w40{width: 40%}
        .w30{width: 30%}
        .w20{width: 20%}
        .w15{width: 15%}
        .w10{width: 10%}
        .w13{width: 13%}
        .w25{width: 25%}
        .w5{width: 5%;}
        .clear{clear: both;}
        .bt{border-top: solid 1px #000;}
        .br{border-right: solid 1px #000;}
        .bb{border-bottom: solid 1px #000;}
        .bl{border-left: solid 1px #000;}
        .nobt{border-top: none;}
        .nobr{border-right: none;}
        .nobb{border-bottom: none;}
        .nobl{border-left: none;}
        .noborder{border: none;}
        .border{border: solid 1px #000;}
        .fwb{font-weight: bold;}
        .txt-center{text-align: center;}
        .uppercase{text-transform: uppercase;}
        .fl{float: left;}
        .p5{padding:5px;}
        .ptb0{padding-top: 0px; padding-bottom: 0px;}
        .mt30{margin-top: 30px;}
        .mb30{margin-bottom: 30px;}
        .mb40{margin-bottom: 40px;}
        .mt40{margin-top: 40px;}
        .mt45{margin-top: 45px;}
        .mt50{margin-top: 50px;}
        .headerbg{background-color: #9acd32;}
        .clrwhite{color: white;}
        .f15{font-size: 15px;}
        .indent{text-indent: 50px;}
        .redbg{background-color: #f7b7bb}
        .lightbg{background-color: #fffcd0;}
        .w33{width: 33.33%}
    </style>
  <title>Business Permit</title>
</head>

<body>
  <div style="">
      <table width="100%" class="headerbg nobb border clrwhite">
          <tr>
              <td class="w10 p5"  style="border-top: none; border-right: none; border-bottom: none; border-left: none;">
                  <img src="{{url('/assets/images/issuanceLogo.png')}}" width="100px" height="100px">
              </td>
              <td class="w80 txt-center f15" style="border-top: none; border-right: none; border-bottom: none; border-left: none;">
                  <p><strong>Republic of the Philippines</strong></p>
                  <p><strong>Provision of Nueva Ecija</strong></p>
                  <p><strong>City of Palayan</strong></p>
                  <p><strong>Office of the City Mayor</strong></p>
                  <h1><strong>Business Permit</strong></h1>
              </td>
              <td class="w10 p5" style="border-top: none; border-right: none; border-bottom: none; border-left: none;">
                  <img src="" style="visibility:hidden;">
              </td>   
          </tr>
      </table>

      <div class="w100 fl border p5 lightbg">
          <p class="fwb">To whom it may concern,</p>
          <p class="indent">
              Pursuant to the revenue code of this Municipality/City, after payment of taxes, fees and charges, etc.. and compliance with existing requirements, Permit is hereby granted to the herein Taxpayer.
          </p>
      </div>

      <table width="100%" style="border:none;">
          <tr>
              <td class="txt-center" style="border-top:none;"><h3>{{$bplo_business->busn_name}}</h3></td>
          </tr>
          <tr>
              <td class="lightbg txt-center" style="border-top:none;">Business Name</td>
          </tr>
          <tr>
              <td class="txt-center" style="border-top:none;"><h3>
                @if($bplo_business_plan=='')
                @else
                {{$bplo_business_plan->subclass_description}}
                @endif
                
              </h3></td>
          </tr>
          <tr>
            
              <td class="lightbg txt-center" style="border-top:none;">Line of Business</td>
          </tr>
          <tr>
              <td class="txt-center" style="border-top:none;"><h3>Brgy. {{$bplo_business_address->brgy_name}}, {{$bplo_business_address->mun_desc}}, {{$bplo_business_address->prov_desc}}, {{$bplo_business_address->reg_region}}</h3></td>
          </tr>
          <tr>
              <td class="lightbg txt-center" style="border-top:none; border-bottom: none;">Business Address</td>
          </tr>
      </table>

      <div class="w100 fl border p5">
          <p class="indent">
              This <strong>PERMIT</strong> can be revoked any time if any of the Conditions and Provisions set forth by the Code is violated and/or the peace and order, health, environment, safety and security of the public are at stake.
          </p>
      </div>
       <table width="100%" style="border:none; float:left;">
          <tr>
              <td class="w33" style="border-top:none;"><strong> 
                @if($bplo_business->client->suffix)
                {{$bplo_business->client->rpo_first_name}} {{$bplo_business->client->rpo_middle_name}} {{$bplo_business->client->rpo_custom_last_name}}, {{$bplo_business->client->suffix}}
                @else
                {{$bplo_business->client->rpo_first_name}} {{$bplo_business->client->rpo_middle_name}} {{$bplo_business->client->rpo_custom_last_name}}
                @endif
                <strong></td>
              <td class="w33" style="border-top:none;"><strong>{{$bplo_business->busns_id_no}}<strong></td>
              <td class="w33" style="border-top:none;"><strong>@if($getPermitIsseuPrint=='')
                            @else
                             {{$getPermitIsseuPrint->bpi_permit_no}}
                            @endif<strong></td>
          </tr>
          <tr>
              <td class="lightbg w33" style="border-top:none;">Owner's Name</td>
              <td class="lightbg w33" style="border-top:none;">Business ID No.</td>
              <td class="lightbg w33" style="border-top:none;">Permit No</td>
          </tr>
      </table>

      <div class="clear"></div>

      <table style="width:100%; border:none;">
          <tr>
              <td class="" style="border-top:none; width: 31.50%"><strong>{{$bplo_business->busn_tin_no}}<strong></td>
              <td class="w20" style="border-top:none;"><strong>{{$BusinessTypeData}}<strong></td>
              <td class="w20" style="border-top:none;"><strong>{{$bplo_business->busn_registration_no}}<strong></td>
              <td class="w20" style="border-top:none;"><strong>@if($getPermitIsseuPrint->app_type_id==1)
                               New
                            @elseif($getPermitIsseuPrint->app_type_id==2)
                              Renew
                            @elseif($getPermitIsseuPrint->app_type_id==3)
                              Retire
                            @endif<strong></td>
          </tr>
          <tr>
              <td class="lightbg" style="border-top:none; width: 31.50%;">Business TIN</td>
              <td class="lightbg w20" style="border-top:none;">Type of Business</td>
              <td class="lightbg w20" style="border-top:none;">DTI Registration No.</td>
              <td class="lightbg w20" style="border-top:none;">Type of Application</td>
          </tr>
      </table>
      <div class="clear"></div>
      <table style="width:100%; border:none;">
          <tr>
              <td class="w13 lightbg" style="border-top:none;"><strong>Date Issued<strong></td>
              <td class="w13" style="border-top:none;">@if($getPermitIsseuPrint=='')
                            @else
                            {{ date("d M Y", strtotime($getPermitIsseuPrint->bpi_issued_date))}}
                            @endif</td>

              <td class="w13 lightbg" style="border-top:none;"><strong>Valid Until<strong></td>
              <td class="w13" style="border-top:none;">@if($getPermitIsseuPrint=='')
                            @else
                            {{ date("d M Y", strtotime($getPermitIsseuPrint->bpi_date_expired))}}
                            @endif</td>

              <td class="w20 lightbg" style="border-top:none;"><strong>Business Place No.<strong></td>
              <td class="w10" style="border-top:none;"> {{$bplo_business->busn_plate_number}}</td>

              <td class="lightbg" style="border-top:none; padding-top: 0px; padding-bottom:0px;"><strong>No. of Employees<strong></td>
              <td class="" style="border-top:none;">{{$bplo_business->busn_employee_total_no}}</td>
          </tr>
      </table>

      <div style="clear: both;"></div>

      <table style="width:100%; border:none;">
          <tr>
              <td rowspan="6" style="border-top:none; text-align: center; width:40%; vertical-align:bottom; padding-bottom:50px;">
                  <strong style="border-top:solid 1px #000">VIANDREI NICOLE J. CUEVAS</strong><br>
                  <strong>Local Chief Executive</strong>
              </td>
              <td style="border-top:none;">Official Receipt No.</td>
              <td style="border-top:none;"><strong>
                @if($prevTrans=='')
                @else
                {{$prevTrans->transaction_no}}
                @endif
                
            </strong></td>
          </tr>
          <tr>
              <td style="border-top:none;">OR Date</td>
              <td style="border-top:none;"><strong>
                @if($prevTrans=='')
                @else
                {{$prevTrans->created_at}}
                @endif
                
            </strong></td>
          </tr>
          <tr>
              <td style="border-top:none;">Payment Mode</td>
              <td style="border-top:none;"><strong>{{$paymentMode}}</strong></td>
          </tr>
          <tr>
              <td style="border-top:none; border-bottom: 0px; text-align:center;"><strong>KIND OF FEE/TAX</strong></td>
              <td style="border-top:none; border-bottom: 0px; text-align:center;"><strong>AMOUNT</strong></td>
          </tr>

          
          <tr>
              <td style="border-top:none; text-align:left;">
                @foreach($arrAssDtls as $arrAss)
                  {{$arrAss->description}}<br>
                 @endforeach
              </td>
            
              <td style="border-top:none; text-align:right;">
                @foreach($arrAssDtls as $arrAss)
                  @php 
                 global $grandTotal;
                $total = $arrAss->tfoc_amount+$arrAss->surcharge_fee+$arrAss->interest_fee;
                $grandTotal +=$total;
                @endphp
                
                  <strong>{{number_format($total,2)}}</strong><br>
                   @endforeach
              </td>
          </tr>
         
          
          
          <tr>
              <td style="border-top:none; text-align: right;"><strong>Total<strong></td>
              <td style="border-top:none; text-align: right;">Php<br>
              @isset($grandTotal)
                    {{ number_format($grandTotal, 2) }}
                @endisset
            </td>
          </tr>
      </table>

      <table style="width:100%; border:none;">
          <tr>
              <td style="border-top:none; width: 60%;">
                  <strong>NOTE/S:</strong>
                  <ol type="1">
                      <li>Exhibit this Permit in Your Establishment.</li>
                      <li>This Permit is only a privilege and not a right, subject to revocation and closure of Business Establishment for any violation of existing Laws and Ordinances and conditions set forth in the Permit.</li>
                      <li>This Permit must be renewed on or before January 20 of the following year unless sooner revoked for cause. failure to renew  within the time required shall subject the Taxpayer to a surcharge of 25% of the amount of taxes, fees of charges due, plus on interest of 2% per month of the unpaid taxes, fees or charges including surcharges.</li>
                      <li>Your Business Establishment is subject to final inspection or regulatory compliance.</li>
                      <li>Surrender this Permit upon retirement of your Establishment.</li>
                  </ol>
              </td>
              <td style="border-top:none; width: 20%; vertical-align: top;"><strong>Remarks: </strong>
                @if($getPermitIsseuPrint=='')
                @else
                {{$getPermitIsseuPrint->bpi_remarks}}
                @endif
              </td>

              <td style="border-top:none; width: 20%; vertical-align:middle; text-align: center;">
                @if($getPermitIsseuPrint=='')
                @else
                <?php 
                $qrcode="";
                $qrcode=QrCode::size(100)->generate('permit No.:'.$getPermitIsseuPrint->bpi_permit_no);
                 $code = (string)$qrcode;
                 echo substr($code,38);
                ?>
                @endif

              </td>
          </tr>
      </table>


















      
    </div>

    </div>
</body>
</html>