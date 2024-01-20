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
/*        border: 0.1px solid gray;*/
/*        border-bottom:unset;*/
      border: 1px solid #000;
/*        border-collapse: collapse;*/
        padding: 5px;
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .table-heading tr {
/*        border: 0.1px solid gray;*/
        border-bottom:none;
      border-right: 1px solid #000;
      border-left: 1px solid #000;
/*        border-collapse: collapse;*/
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
            <td  style="padding:36px 30px 0px 30px;">
              <table class="main-heading">
                <tr>
                  <td  style="text-align:center;">
                    <h4>Republic of the Philippines</h4>
                    <h4>CITY OF PALAYAN</h4>
                    <h4>OFFICE OF THE CITY TREASURER</h4>
                    <h4>Land Tax Division</h4>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <td  style="padding:36px 30px 0px 30px;">
              <table class="main-heading">
                <tr>
                  <td  style="text-align:center;"><h4><u>NOTICE OF DELINQUENCY</u></h4></td>
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <td style="padding:36px 30px 0px 30px;">
              <table class="left-right-content-without-border">
                <tr>
                    <td width="600px"><b>{{(isset($data->propertyOwner->standard_name))?$data->propertyOwner->standard_name:''}}</b> <br /><small><b><i>{{(isset($data->propertyOwner->standard_address))?$data->propertyOwner->standard_address:''}}</i></b></small></td>
                    <td width="430px" style="text-align:right;">{{date("d F, Y")}}</td>
                    
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <td style="padding:36px 30px 0px 30px;">
              <table class="left-right-content-without-border">
                <tr>
                    <td width="50px">Sir/Madam :</td>
                    <td width="500px" style="text-align:left;"><br />Records of the office show that you have not paid your real property tax as follows:</td>
                    
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <td style="padding:36px 30px 0px 30px;">
              <table class="table-heading" role="presentation2" style="width:100%; font-size: 0.85rem">
                <tr>
                    <th style="border-bottom: 1px solid #000;border-top: 1px solid #000;border-left: 1px solid #000;">A.R.P. Number</th>
                    <th style="border-bottom: 1px solid #000;border-top: 1px solid #000;">Location of Property</th>
                    <th style="border-bottom: 1px solid #000;border-top: 1px solid #000;">Assessed Value</th>
                    <th style="border-bottom: 1px solid #000;border-top: 1px solid #000;">Tax Year(s)</th>
                    <th style="border-bottom: 1px solid #000;border-top: 1px solid #000;">Basic/SEF Tax Dues</th>
                    <th style="border-bottom: 1px solid #000;border-top: 1px solid #000;">Basic/SEF Penalities</th>
                    <th style="border-bottom: 1px solid #000;border-top: 1px solid #000;border-right: 1px solid #000;">Total Due</th>
                </tr>
               
                @php $i = 1; 
                $totalBasic = 0;
                $totalSef = 0;
                $totalSh = 0;
                $overAllTotal = 0;
                @endphp
                                @foreach($receiableDetails as $key => $val)
                                <tr>
                                <td style="border-bottom: 0px; text-align: left;"> @php
                $array = explode("-",$val->pr_tax_arp_no);
                if(count($array) > 2){
                    unset($array[0]);
                }
                @endphp
                {{ implode("-",$array) }}</td>
                                <td style="text-align: center;border-left: 1px solid #000; ">{{strtoupper($val->brgy_name)}}</td>
                                <td style="text-align: center; border-left: 1px solid #000;">{{Helper::decimal_format($val->rp_assessed_value)}}</td>
                                <td style="text-align: center; border-left: 1px solid #000;">{{$val->ar_covered_year}}</td>
                                @php $basicTotal = $val->basic_amount+$val->basic_penalty_amount-$val->basic_discount_amount;
                                $sefTotal = $val->sef_amount+$val->sef_penalty_amount-$val->sef_discount_amount;
                                $shTotal = $val->sh_amount+$val->sh_penalty_amount-$val->sh_discount_amount; 
                                $totalBasic += $basicTotal;
                                $totalSef += $sefTotal;
                                $totalSh += $shTotal;
                                $overAllTotal += $basicTotal+$sefTotal+$shTotal;
                                @endphp
                                <td style="text-align: left;  border-left: 1px solid #000; padding:0 0 0 10px;">{{Helper::decimal_format($basicTotal)}}</td>
                                <td style="text-align: left; border-left: 1px solid #000; padding:0 0 0 10px;">{{Helper::decimal_format($sefTotal)}}</td>
                                <td style="text-align: left; border-left: 1px solid #000; padding:0 0 0 10px;">{{Helper::decimal_format($basicTotal+$sefTotal+$shTotal)}}</td>
                              </tr>
                                @endforeach
                                <tr class="sub-details">
                <td colspan="4" style="text-align: center;padding-top: 10px;padding-bottom: 10px;">TOTAL </td>
                <td style="text-align: right; padding:0 0 0 10px;"><b>{{Helper::decimal_format($totalBasic)}}</b></td>
                <td style="text-align: right; padding:0 0 0 10px;"><b>{{Helper::decimal_format($totalSef)}}</b></td>
                <td style="text-align: right; padding:0 0 0 10px;"><b>{{Helper::decimal_format($overAllTotal)}}</b></td>
            </tr>
                
              </table>
            </td>
          </tr>

          <tr>
            <td style="padding:36px 30px 0px 30px;">
              <table class="left-right-content-without-border">
                <tr>
                    <!-- <td width="0px"></td> -->
                    <td width="500px" style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Please reconcile this with your records and inform our office of any discrepancies as soon as possible that the necessary corrections be effected. It has been our ernest desire to keep our records accurate in order to avoid inconvenience to you.</td>
                    
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <td style="padding:15px 30px 0px 30px;">
              <table class="left-right-content-without-border">
                <tr>
                    <!-- <td width="0px"></td> -->
                    <td width="500px" style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; On the other hand, if you simply missed to effect payments. we would appreciate it very much if you could pay the same within the period of fifteen (15) days from receipt hereof, so that your name could be deleted or dropped from the list of deliquent taxpayers in this city.</td>
                    
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding:15px 30px 0px 30px;">
              <table class="left-right-content-without-border">
                <tr>
                    <!-- <td width="0px"></td> -->
                    <td width="500px" style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If we do not hear from you within the period aforementioned, we shall be constrained to avail of the administrative and/or judical remedies for the collection thereof pursuant to Secs. 256-266, R.A. 7160, otherwaise known as "The Local Goverment Code of 1991", to wit;</td>
                    
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding:15px 30px 0px 30px;">
              <table class="left-right-content-without-border">
                <tr>
                    <!-- <td width="50px"></td> -->
                    <td width="500px" style="text-align:left;"><i><b>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(a) distrain of personal property and/or levy of real property and goverment thereof for sale at publication, or simultataneously,<br /><br />

                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(b) file civil suit with the proper court.</b></i></td>
                    
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding:15px 30px 0px 30px;">
              <table class="left-right-content-without-border">
                <tr>
                    <!-- <td width="50px"></td> -->
                    <td width="500px" style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We hope this notice merit your preferential attention.<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>ADDENDUM &nbsp;&nbsp;Deliquent RPT are subject to 2%<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; penalty per month up to date of payment.</i></td>
                    
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <td style="padding:36px 30px 0px 30px;">
              <table class="left-right-content-without-border">
                <tr>
                    <td width="500px"></td>
                    <td colspan="2"  width="300px" style="text-align:center;">
                        Very truly yours,<br /><br /><b>
                        {{(isset($data->barangay->municipality->newLocality->tresurer->standard_name))?$data->barangay->municipality->newLocality->tresurer->standard_name:''}}</b><br /><i><small>City Treasurer</small></i>
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
                        <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><a href="{APPROVE_URL}" style="text-decoration:none;background-color:#96ce73; color:#fff; padding: 10px; margin-right: 5px; min-width: 100px; display: inline-block;">I ACKNOWLEDGE THIS</a></p>
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