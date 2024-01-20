@php $qtrs = ['1' => '1st Quarter','2' => '2nd Quarter','3' => '3rd Quarter','4' => '4th Quarter']; @endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="x-apple-disable-message-reformatting">
  <title></title>
  <!--[if mso]>
  <noscript>
    <xml>
      <o:OfficeDocumentSettings>
        <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml>
  </noscript>
  <![endif]-->
  <style>
    table, td, div, h1, p {font-family: Arial, sans-serif;}
    table{
        width: 100%;
    }
    .main-heading{margin-top: -50px;}
    .main-heading h4{font-size: 1rem;}
    .left-content{ float: left; }
    .right-content{ float: right; }
    .center-content{ text-align:center; }
    .left-content p, .right-content p{ margin: 0px; }
    .left-right-content tr {background: unset;}
    .left-right-content td {border: 1px solid gray;padding-left: 4px;}
    .table-heading th{ border: 0.5px solid gray; border-style: dashed; text-align: center;font-weight: lighter;}
    .table-heading td{ text-align: right;}
    .align-left{text-align: left !important; padding-bottom: 7px;}
    .year-title {font-weight: bold; text-align: left !important;padding-top: 10px;padding-bottom: 10px;border-top: 1px dashed gray;}
    .table-heading tr:nth-child(even){background: unset;}
    .table-heading tr td:first-child{float: left !important;}
    .table-heading tr{padding-bottom: 10px;}
    .sub-details{ padding-top:15px !important; border-top:1px dashed;border-bottom:1px dashed gray;}


    @import url('https://fonts.googleapis.com/css?family=Fjalla+One|Montserrat:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
    * { margin: 0; padding: 0; }
    body { font-family: 'Montserrat', sans-serif;font-size: 14px; }

    table {font-size: 14px; width: 100%;border-collapse: collapse;}
    .table-heading th {
        border: 0.1px solid gray;
        border-bottom:unset;
        border-collapse: collapse;
        padding: 5px;
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .right-content{
        float: right;
        padding-left: 30%;
        margin-right: 0px;
    }
    .main-heading{padding-bottom: 20px;}
    .sub-details{ border:1px dashed;border-right:unset;border-left:unset; }
  </style>
</head>
<body style="margin:0;padding:0;">
  <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
    <tr>
      <td align="center" style="padding:0;">
        <table role="presentation" style="width:800px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
          <tr>
            <td align="center" style="padding:20px 0 20px 0;background:#fff;">
              <img src="https://scontent.fcrk2-2.fna.fbcdn.net/v/t39.30808-6/243517373_234120712096426_5917659485004806887_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=09cbfe&_nc_eui2=AeEdDbKmCSo86dqg1oipAb6-H1o9mjAAviMfWj2aMAC-I0Eds7tI_vLwiPO9DinIp-A&_nc_ohc=Ryc-7zlz05kAX9zldcu&_nc_ht=scontent.fcrk2-2.fna&oh=00_AfBDxQ_oLnrNCvz1r3cDg7KpsJctu69cHxSZyqztlqTlHg&oe=64904079" alt="" width="120" style="height:auto;display:block;" />
            </td>
          </tr>
          <tr>
            <td style="padding:36px 30px 0px 30px;">
              <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                <tr>
                  <td style="padding:0 0 20px 0;color:#153643; text-align: center">
                    <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">Hi {{(isset($data->propertyOwner->rpo_first_name))?$data->propertyOwner->rpo_first_name:''}},</h1>
                    <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Your payment still pending, Please pay as soon as possible.</p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td  style="padding:36px 30px 0px 30px;">
              <table class="main-heading">
                <tr>
                  <td  style="text-align:center;">
                    <h4>PALAYAN CITY</h4>
                    <h4>TREASURER'S OFFICE</h4>
                    <h4>TAX DELINQUENCY AND OUTSTANDING PAYMENT</h4>
                    <h4>Land Tax Division</h4>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding: 0px 20px 35px 20px">
              <table class="left-right-content">
                <tr>
                    <td width="600px"><b>TD-NO. </b>: {{(isset($data->rp_tax_declaration_no))?$data->rp_tax_declaration_no:''}}</td>
                    <td width="430px"><b>Property Index No. </b>: <span style="text-transform: uppercase;">{{(isset($data->rp_pin_declaration_no))?$data->rp_pin_declaration_no:''}}</span></td>
                    
                </tr>
                <tr>
                    <td><b>Taxpayer Name </b>: <span style="text-transform: uppercase;">{{(isset($data->taxpayer_name))?$data->taxpayer_name:''}}</span></td>
                    <td><b>Property Kind </b>: {{(isset($data->propertyKindDetails->pk_description))?$data->propertyKindDetails->pk_description:''}}</td>
                    
                </tr>
                
                <tr>
                    <td><b>Address </b>: <span style="text-transform: uppercase;">{{(isset($data->property_owner_details->standard_address))?$data->property_owner_details->standard_address:''}}</span></td>
                    <td><b>Class </b>: {{(isset($data->class_for_kind->pc_class_description))?$data->class_for_kind->pc_class_description:''}}</td>
                    
                </tr>
                <tr>
                    <td><b>Location </b>: <span style="text-transform: uppercase;">{{(isset($data->barangay_details->brgy_name))?$data->barangay_details->brgy_name:''}}</span></td>
                    <td><b>Assessed Value </b>: {{(isset($data->assessed_value_for_all_kind))?Helper::decimal_format($data->assessed_value_for_all_kind):''}}</td>
                    
                </tr>
                <tr>
                    <td><b>Description </b>: <span style="text-transform: uppercase;">{{(isset($data->rp_lot_cct_unit_desc))?$data->rp_lot_cct_unit_desc:''}}</span></td>
                    <td><b>Effectivity </b>: {{(isset($data->rp_app_effective_year))?$data->rp_app_effective_year:''}} - {{ (isset($data->rp_app_effective_quarter))?$qtrs[$data->rp_app_effective_quarter]:''}}</td>
                    
                </tr>
                <?=$data['activityDetails']?>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding: 0px 20px 35px 20px">
              <table class="table-heading" role="presentation2" style="width:100%;border-collapse:collapse;border-spacing:0; font-size: 0.85rem">
                <tr>
                   <th style="border-bottom: 1px solid #000;border-top: 1px solid #000;border-left: 1px solid #000;">TAXPAYER NAME</th>
                    <th style="border-bottom: 1px solid #000;border-top: 1px solid #000;">EFFECTIVITY</th>
                    <th style="border-bottom: 1px solid #000;border-top: 1px solid #000;">TAX YEAR</th>
                    <th style="border-bottom: 1px solid #000;border-top: 1px solid #000;">BASIC TAX</th>
                    <th style="border-bottom: 1px solid #000;border-top: 1px solid #000;">SEF TAX</th>
                    <th style="border-bottom: 1px solid #000;border-top: 1px solid #000;">SHT TAX</th>
                    <th style="border-bottom: 1px solid #000;border-top: 1px solid #000;border-right: 1px solid #000;">TOTAL AMOUNT</th>
                </tr>
                <tr>
                  <!-- border-bottom: 0.1px dashed gray; -->
                    <th style="border: 0px;"></th>
                    <th style="border: 0px;"></th>
                    <th style="border: 0px;"></th>
                    <th style="border: 0px;"></th>
                    <th style="border: 0px;"></th>
                    <th style="border: 0px;"></th>
                    <th style="border: 0px;"></th>
                </tr>
                @php $i = 1; 
                $totalBasic = 0;
                $totalSef = 0;
                $totalSh = 0;
                $overAllTotal = 0;
                @endphp
                                @foreach($receiableDetails as $key => $val)
                                <tr>
                                <td style="border-bottom: 0.1px dashed gray; text-align: left;">{{$val->customername}}</td>
                                <td style="text-align: center;">{{$val->rp_app_effective_year}}</td>
                                <td style="text-align: center;">{{$val->ar_covered_year}}</td>
                                @php $basicTotal = $val->basic_amount+$val->basic_penalty_amount-$val->basic_discount_amount;
                                $sefTotal = $val->sef_amount+$val->sef_penalty_amount-$val->sef_discount_amount;
                                $shTotal = $val->sh_amount+$val->sh_penalty_amount-$val->sh_discount_amount; 
                                $totalBasic += $basicTotal;
                                $totalSef += $sefTotal;
                                $totalSh += $shTotal;
                                $overAllTotal += $basicTotal+$sefTotal+$shTotal;
                                @endphp
                                <td style="text-align: right; padding:0 0 0 10px;">{{Helper::decimal_format($basicTotal)}}</td>
                                <td style="text-align: right; padding:0 0 0 10px;">{{Helper::decimal_format($sefTotal)}}</td>
                                <td style="text-align: right; padding:0 0 0 10px;">{{Helper::decimal_format($shTotal)}}</td>
                                <td style="text-align: right; padding:0 0 0 10px;">{{Helper::decimal_format($basicTotal+$sefTotal+$shTotal)}}</td>
                              </tr>
                                @endforeach
                                <tr class="sub-details">
                <td colspan="3" style="text-align: center;padding-top: 10px;padding-bottom: 10px;">TOTAL </td>
                <td style="text-align: right; padding:0 0 0 10px;"><b>{{Helper::decimal_format($totalBasic)}}</b></td>
                <td style="text-align: right; padding:0 0 0 10px;"><b>{{Helper::decimal_format($totalSef)}}</b></td>
                <td style="text-align: right; padding:0 0 0 10px;"><b>{{Helper::decimal_format($totalSh)}}</b></td>
                <td style="text-align: right; padding:0 0 0 10px;"><b>{{Helper::decimal_format($overAllTotal)}}</b></td>
            </tr>
                
              </table>
            </td>
          </tr>

          <tr>
            <td style="padding:0px 30px 0px 30px;">
              <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                <tr>
                  <td style="padding:0 0 40px 0;color:#153643; text-align: center">
                    @if($data['isShowBtn']==1)
                        <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><a href="{APPROVE_URL}" style="text-decoration:none;background-color:#96ce73; color:#fff; padding: 10px; margin-right: 5px; min-width: 100px; display: inline-block;">I ACKNOWLEDGE THIS</a></p>
                    @endif
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding:0px 30px 0px 30px;">
              <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                <tr>
                  <td style="padding:0 0 40px 0;color:#153643; text-align: center">
                    <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                      Please do not reply to this message. This is a system-generated email sent to <a href="mailto:{{(isset($data->propertyOwner->p_email_address))?$data->propertyOwner->p_email_address:''}}" target="_blank">{{(isset($data->propertyOwner->p_email_address))?$data->propertyOwner->p_email_address:''}}</a>.
                    </p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding:30px;background:#96ce73;">
              <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                <tr>
                  <td style="padding:0;width:50%;" align="left">
                    <p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                      &reg; Palayan, Dyn Edge 2023<br/>
                    </p>
                  </td>
                  <td style="padding:0;width:50%;" align="right">
                    <table role="presentation" style="border-collapse:collapse;border:0;border-spacing:0;">
                      <tr>
                        <td style="padding: 1px 0 0 278px;width:38px;">
                          <a href="http://www.twitter.com/" style="color:#ffffff;"><img src="https://assets.codepen.io/210284/tw_1.png" alt="Twitter" width="38" style="height:auto;display:block;border:0;" /></a>
                        </td>
                        <td style="padding:0 0 0 10px;width:38px;">
                          <a href="http://www.facebook.com/" style="color:#ffffff;"><img src="https://assets.codepen.io/210284/fb_1.png" alt="Facebook" width="38" style="height:auto;display:block;border:0;" /></a>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>