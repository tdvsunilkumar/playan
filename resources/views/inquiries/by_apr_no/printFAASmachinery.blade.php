@php
                $array = explode("-",$rptProperty->pr_tax_arp_no);
                if(count($array) > 2){
                    unset($array[0]);
                }
                $newPin = explode("-",$rptProperty->rp_pin_declaration_no);
                @endphp
                
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css?family=Fjalla+One|Arial:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
        * { margin: 0; padding: 0; }
        @page {sheet-size: Letter; margin-top: 15mm; margin-bottom: 15mm; margin-left: 10mm; margin-right: 10mm; margin-header: 0mm;margin-footer: 0mm;}
        /*@page rotated {
            size: landscape;
        }*/
        body { font-family: 'Arial', sans-serif; font-size: 12px; }
        table td, table th { border: 1px solid black; padding: 5px; }
        p {margin: 0px; padding: 0px}
        p .indent{text-indent: 25px; margin-left: 25px;}
        h4 {margin: 0px; padding: 0px}
        textarea { border: 0; font-size: 14px; font-family: 'Arial', sans-serif;overflow: hidden; resize: none; }
        table { border-collapse: collapse; }
        .mb-2{margin-bottom: 2mm}
        .bg{background-color: red;}
        .bg1{background-color: blue;}
    </style>
    <title>Machinery-Print TD No. {{ implode("-",$array) }}</title>
</head>

<body>
  <div style="">
      <p style="">RPA FORM NO. 1-C</p>
      <p style="text-align: right;margin-top:-15px;">UPDATE CODE :  
        <!-- <b style="font-size:20px;padding-left: 10px;">DC</b> -->
        <u>&nbsp;&nbsp;DC&nbsp;&nbsp;</u>
      </p>
      <table style="width:100%; border:none;">
          <tr>
              <td style="border-bottom:none; text-align:center; padding: 10px 0px;">
                  <h3>REAL PROPERTY FIELD APPRAISAL & ASSESSMENT SHEET - MACHINERY</h3>
              </td>
          </tr>
      </table>

      <table width="100%" border="none">
          <tr>
              <td width="50%" style="padding:0px 5px; vertical-align:bottom;">
                  ARP NO. <strong style="font-size: 17px;">{{ implode("-",$array) }}</strong>
              </td>
              <td width="50%" style="padding:0px;">
                  <table style="width:100%; ">
                      <tr>
                          <td style="border-top: none; border-bottom: none; padding: 15px 0px 5px 0px; vertical-align: bottom; text-align: center; border-left: none;">PIN</td>
                          <td style="border-top: none; border-bottom: none; padding: 15px 0px 5px 0px; vertical-align: bottom; text-align: center;">{{(isset($rptProperty->locality->mun_no))?$rptProperty->locality->mun_no:''}}</td>
                          <td style="border-top: none; border-bottom: none; padding: 15px 0px 5px 0px; vertical-align: bottom; text-align: center;">{{(isset($newPin[1]))?$newPin[1]:''}}</td>
                          <td style="border-top: none; border-bottom: none; padding: 15px 0px 5px 0px; vertical-align: bottom; text-align: center;">{{(isset($newPin[2]))?$newPin[2]:''}}</td>
                          <td style="border-top: none; border-bottom: none; padding: 15px 0px 5px 0px; vertical-align: bottom; text-align: center;">{{(isset($newPin[3]))?$newPin[3]:''}}</td>
                          <td style="border-top: none; border-bottom: none; padding: 15px 0px 5px 0px;  vertical-align: bottom; text-align: center;">{{(isset($newPin[4]))?$newPin[4]:''}}</td>
                          <td style="border-top: none; border-bottom: none; padding: 15px 0px 5px 0px; vertical-align: bottom; text-align: center; border-right: none;">{{(isset($newPin[5]))?$newPin[5]:''}}</td>
                      </tr>
                  </table>
              </td>
          </tr>
          <tr>
              <td width="50%" style="border-top: 0; padding-bottom:15px;padding-top: 0px;">Owner :
                  <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$own_name}}
              </td>
              <td width="50%" style="border-top: 0; padding-bottom:5px; vertical-align: bottom;">
                  Address: {{$own_address}}
                  <br>Tel No.: {{(isset($rptProperty->propertyOwner->p_telephone_no))?$rptProperty->propertyOwner->p_telephone_no:''}}
              </td>
          </tr>
          <tr>
              <td width="50%" style="border-top: 0; padding-bottom:5px; vertical-align: top;padding-top: 0px;">User / Administrator : 
                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$admin_name}}
              </td>
              <td width="50%" style="border-top: 0; padding-bottom:15px; vertical-align: bottom;">
                  Address : {{$admin_address}}
                  <br>Tel No.: {{(isset($rptProperty->propertyAdmin->p_telephone_no))?$rptProperty->propertyAdmin->p_telephone_no:''}}
              </td>
          </tr>
          <tr>
              <td width="50%" style="border-top: 0; padding-bottom:5px; vertical-align: top;padding-top: 0px;">
                  Bldg. Owner (Where Mach is located) :<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  {{(isset($buildRef->propertyOwner->standard_name)) ? $buildRef->propertyOwner->standard_name : ''}}
              </td>
              <td width="50%" style="border-top: 0; padding-bottom:5px; vertical-align: bottom;padding-top: 0px;">
                  Bldg. PIN :
                  <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  {{(isset($buildRef->rp_pin_declaration_no)) ? $buildRef->rp_pin_declaration_no : ''}}
              </td>
          </tr>
          <tr>
              <td width="50%" style="border-top: 0; padding-bottom:5px; vertical-align: top;padding-top: 0px;">
                  Land Owner ( Where Mach is located ) :<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  {{(isset($landRef->propertyOwner->standard_name)) ? $landRef->propertyOwner->standard_name : ''}}
              </td>
              <td width="50%" style="border-top: 0; padding-bottom:5px; vertical-align: bottom;padding-top: 0px;">
                  Land PIN:
                  <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{(isset($landRef->rp_pin_declaration_no)) ? $landRef->rp_pin_declaration_no : ''}}
              </td>
          </tr>
          <tr>
              <td width="50%" style="border-top: 0; padding-bottom:5px; vertical-align: top;padding-top: 0px;">
                  No. / Street :<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  {{$rptProperty->rp_location_number_n_street}}
              </td>
              <td width="50%" style="border-top: 0; padding-bottom:5px; vertical-align: bottom;padding-top: 0px;">
                  Barangay :
                  <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  {{(isset($rptProperty->barangay->brgy_name))?strtoupper($rptProperty->barangay->brgy_name):''}}
              </td>
          </tr>
          <tr>
              <td width="50%" style="border-top: 0; border-bottom: none; padding-bottom:5px; vertical-align: top;padding-top: 0px;">
                  City :<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  {{$rptProperty->locality->mun_desc}}
              </td>
              <td width="50%" style="border-top: 0; border-bottom: none; padding-bottom:5px; vertical-align: bottom;padding-top: 0px;">
                  Province :
                  <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ (isset($rptProperty->locality->provinceDetails->prov_desc))?strtoupper($rptProperty->locality->provinceDetails->prov_desc):''}}
              </td>
          </tr>
      </table>
      
      <table width="100%" style="">
          <tr>
              <td rowspan="2" style="text-align:center; vertical-align: middle; border-bottom: none;">Machinery<br>Description</td>
              <td rowspan="2" style="text-align:center; vertical-align: middle; border-bottom: none;">Brand &<br>Model No.</td>
              <td rowspan="2" style="text-align:center; vertical-align: middle; border-bottom: none;">Capacity / HP</td>
              <td rowspan="2" style="text-align:center; vertical-align: middle; border-bottom: none;">Date<br>Acquired</td>
              <td rowspan="2" style="text-align:center; vertical-align: middle; border-bottom: none;">Conditions<br>When<br>Acquired</td>
              <td colspan="2" style="text-align:center; vertical-align: middle; border-bottom: none;">Economic Life</td>
              <td rowspan="2" style="text-align:center; vertical-align: middle; border-bottom: none;">Date <br> of <br>Installation</td>
              <td rowspan="2" style="text-align:center; vertical-align: middle; border-bottom: none;">Date <br> of <br>Operation</td>
              <td rowspan="2" style="text-align:center; vertical-align: middle; border-bottom: none;">Remarks</td>
          </tr>
          <tr>
              <td style="text-align:center; vertical-align: middle; border-bottom: none;">Estimate</td>
              <td style="text-align:center; vertical-align: middle; border-bottom: none;">Remaining</td>
          </tr>
            @if(isset($rptProperty->machineAppraisals))
            @php
                $appCount = count($rptProperty->machineAppraisals);
                $rowCount = max($appCount, 5);
            @endphp
            @foreach($rptProperty->machineAppraisals as $apps)
          <tr>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{$apps->rpma_description}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{$apps->rpma_brand_model}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{$apps->rpma_capacity_hp}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{($apps->rpma_date_acquired != '')?date("d/m/Y",strtotime($apps->rpma_date_acquired)):''}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{$apps->rpma_condition}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{$apps->rpma_estimated_life}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{$apps->rpma_remaining_life}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{($apps->rpma_date_installed != '')?date("d/m/Y",strtotime($apps->rpma_date_installed)):''}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{($apps->rpma_date_operated != '')?date("d/m/Y",strtotime($apps->rpma_date_operated)):''}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{$apps->rpma_remarks}}</td>
          </tr>
          @endforeach
          @for($i = $appCount; $i < $rowCount; $i++)
          <tr>
                <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
                <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
                <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
                <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
                <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
                <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
                <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
                <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
                <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
                <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
            </tr>
          @endfor
          @endif
      </table>

      <table width="100%" style="">
          <tr>
              <td style="border:0px solid black; border-right:1px solid black; border-left:1px solid black; padding:10px 5px; width: 10%;">
                  <h4 style="padding:0px">PROPERTY APPRAISAL</h4>
              </td>
          </tr>
      </table>

      <table width="100%" style="">
          <tr>
              <td rowspan="2" style="text-align:center; vertical-align: middle; border-bottom: none;">Machinery<br>Description</td>
              <td rowspan="2" style="text-align:center; vertical-align: middle; border-bottom: none;">No. of<br>Units</td>
              <td rowspan="2" style="text-align:center; vertical-align: middle; border-bottom: none;">Acquisition <br>Cost</td>
              <td colspan="4" style="text-align:center; vertical-align: middle; border-bottom: none;">Additional Cost</td>
              <td rowspan="2" style="text-align:center; vertical-align: middle; border-bottom: none;">Market<br>Value</td>
              <td rowspan="2" style="text-align:center; vertical-align: middle; border-bottom: none;">Depreciation</td>
              <td rowspan="2" style="text-align:center; vertical-align: middle; border-bottom: none;">Depreciated <br> Market <br>Value</td>
          </tr>
          <tr>
              <td style="text-align:center; vertical-align: middle; border-bottom: none;">Freight</td>
              <td style="text-align:center; vertical-align: middle; border-bottom: none;">Insurance</td>
              <td style="text-align:center; vertical-align: middle; border-bottom: none;">Installation</td>
              <td style="text-align:center; vertical-align: middle; border-bottom: none;">Others</td>
          </tr>
         @if(isset($rptProperty->machineAppraisals))
             @php
                $appCount = count($rptProperty->machineAppraisals);
                $rowCount = max($appCount, 10);
            @endphp
            @foreach($rptProperty->machineAppraisals as $apps)
          <tr>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{$apps->rpma_description}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{$apps->rpma_appr_no_units}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{Helper::decimal_format($apps->rpma_acquisition_cost)}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{Helper::decimal_format($apps->rpma_freight_cost)}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{Helper::decimal_format($apps->rpma_insurance_cost)}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{Helper::decimal_format($apps->rpma_installation_cost)}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{Helper::decimal_format($apps->rpma_other_cost)}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{Helper::decimal_format($apps->rpma_base_market_value)}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{Helper::decimal_format($apps->rpma_depreciation)}}</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">{{Helper::decimal_format($apps->rpma_market_value)}}</td>
          </tr>
        @endforeach
        @for($i = $appCount; $i < $rowCount; $i++)
        <tr>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
              <td style="padding-top:3px; padding-bottom:3px; text-align: center;">&nbsp;</td>
          </tr>
        @endfor
          @endif
         
      </table>
      <div style="page-break-before: always;"></div>
      <table width="100%">
          <tr>
              <td style="border:1px solid black; padding: 15px 5px; width: 10%;">
                  <h4 style="padding:0px">PROPERTY ASSESSMENT</h4>
              </td>
          </tr>
      </table>

      <table width="100%">
          <tr>
              <td width="50%" style="text-align:center; paddding:10px 5px; border-top:none;">Classification</td>
              <td style="text-align:center; paddding:10px 5px; border-top:none;">Market Value</td>
              <td style="text-align:center; paddding:10px 5px; border-top:none;">Assessment<br>Level</td>
              <td style="text-align:center; paddding:10px 5px; border-top:none;">Assessed<br>Value</td>
          </tr>
          <tr>
              <td width="50%" style="text-align:center; paddding:10px 5px; border-top:none;vertical-align:top;">{{(isset($rptProperty->machineAppraisals[0]->pc_class_description))?$rptProperty->machineAppraisals[0]->pc_class_description:''}}</td>
              <td style="text-align:center; padding-left:5px; padding-top: 5px; padding-right: 5px; padding-bottom: 50px; paddding:10px 5px; border-top:none;">{{Helper::decimal_format($rptProperty->machineAppraisals->sum('rpma_market_value'))}}</td>
              <td style="text-align:center; padding-left:5px; padding-top: 5px; padding-right: 5px; padding-bottom: 50px; paddding:10px 5px; border-top:none;">{{(isset($rptProperty->machineAppraisals[0]->al_assessment_level))?number_format($rptProperty->machineAppraisals[0]->al_assessment_level, 0, '.', ''):''}}%</td>
              <td style="text-align:center; padding-left:5px; padding-top: 5px; padding-right: 5px; padding-bottom: 50px; paddding:10px 5px; border-top:none;">{{Helper::decimal_format($rptProperty->machineAppraisals->sum('rpm_assessed_value'))}}</td>
          </tr>
          <tr>
              <td width="50%" style="text-align:center; padding-left:20px; padding-top: 10px; padding-right: 0px; padding-bottom: 10px; border-top:none; border-right: none;"><strong>TOTAL</strong></td>
              <td width="20%" style="text-align:center; paddding:10px 5px; border-top:none; border-left: none;">{{Helper::decimal_format($rptProperty->machineAppraisals->sum('rpma_market_value'))}}</td>
              <td width="15%" style="text-align:center; paddding:10px 5px; border-top:none;"></td>
              <td width="15%" style="text-align:center; paddding:10px 5px; border-top:none;">{{Helper::decimal_format($rptProperty->machineAppraisals->sum('rpm_assessed_value'))}}</td>
          </tr>

          <tr>
              <td width="70%" colspan="2" style="text-align:left; padding-left:20px; padding-top: 10px; padding-right: 0px; padding-bottom: 10px; border-top:none; border-right: none;">PREVIOUS OWNER : {{$cancelTdByOwner}}</td>
              <td width="15%" style="text-align:left; paddding:10px 5px; border-top:none; border-right: none;">TAXABILITY :</td>
              <td width="15%" style="text-align:left; paddding:10px 5px; border-top:none; border-left: none;"><strong>{{($rptProperty->rp_app_taxability == 1)?'TAXABLE':'EXEMPT'}}</strong></td>
          </tr>
          <tr>
              <td width="70%" colspan="2" style="text-align:left; padding-left:20px; padding-top: 10px; padding-right: 0px; padding-bottom: 10px; border-top:none; border-right: none;">PREVIOUS VALUE : {{$cacnceledTdAssValue}}</td>
              <td width="15%" style="text-align:left; paddding:10px 5px; border-top:none; border-right: none;">EFFECTIVITY :</td>
              <td width="15%" style="text-align:left; paddding:10px 5px; border-top:none; border-left: none;"><strong>{{$rptProperty->rp_app_effective_year}}</strong></td>
          </tr>
      </table>

      <table width="100%" style="padding:0px">
          <tr>
              <td width="50%" style="border:1px solid black; border-top: none; border-bottom: none;  padding-bottom: 50px;">
                  <h4 style="padding:0px">APPRAISED BY:</h4>
              </td>
              <td width="50%" style="border:1px solid black; border-top: none; border-bottom: none; padding-bottom: 50px;">
                  <h4 style="padding:0px">ASSESSD BY:</h4>
              </td>
          </tr>
      </table>


      <table width="100%" style="padding:0px">
          <tr>
              <td width="35%" style="border:1px solid black; border-right: none; border-top: none; padding-bottom: 10px; text-align: center;">
                  <strong>{{($apprisedPosition->is_sgd == 1)?'SGD ':''}}{{(isset($apprisedPosition->fullname))? strtoupper($apprisedPosition->fullname):''}} </strong>
              </td>
              <td width="15%" style="border:1px solid black; border-top: none; border-left: none;padding-bottom: 0px; text-align:center;">
                  <strong>{{ date('m/d/Y', strtotime((isset($RptPropertyApproval->rp_app_appraised_date))?$RptPropertyApproval->rp_app_appraised_date:''))}}</strong>
              </td>
              <td width="35%" style="border:1px solid black; border-right: none; border-top: none; padding-bottom: 0px; text-align: center;">
                  <strong></strong>
              </td>
              <td width="15%" style="border:1px solid black; border-top: none; border-left: none;padding-bottom: 0px; text-align:center;">
                  <strong></strong>
              </td>
          </tr>
          <tr>
              <td width="35%" style="border:1px solid black; border-right: none; border-bottom: none;padding-bottom: 10px; border-top: none; padding-top: 0px; text-align: center;">
                  <small>{{(isset($apprisedPosition->description))?$apprisedPosition->description:''}} </small>
              </td>
              <td width="15%" style="border:1px solid black; border-top: none; border-bottom: none; border-left: none;padding-top: 0px; text-align:center;">
                  <small>Date</small>
              </td>
              <td width="35%" style="border:1px solid black; border-right: none; border-bottom: none; border-top: none; padding-top: 0px; text-align: center;">
                  <small>Name</small>
              </td>
              <td width="15%" style="border:1px solid black; border-top: none; border-bottom: none; border-left: none;padding-top: 0px; text-align:center;">
                  <small>Date</small>
              </td>
          </tr>
      </table>

      <table width="100%" style="padding:0px">
          <tr>
              <td width="50%" style="border:1px solid black; border-top: none; border-bottom: none;  padding-bottom: 50px; padding-top: 30px;">
                  RECOMMENDING APPROVAL:
              </td>
              <td width="50%" style="border:1px solid black; border-top: none; border-bottom: none; padding-bottom: 50px; padding-top: 30px;">
                  <strong>APPROVED</strong>
              </td>
          </tr>
      </table>

      <table width="100%" style="padding:0px">
          <tr>
              <td width="35%" style="border:1px solid black; border-right: none; border-top: none; padding-bottom: 10px; text-align: center;">
                  <strong>{{($recommendPosition->is_sgd == 1)?'SGD ':''}}{{(isset($recommendPosition->fullname))? strtoupper($recommendPosition->fullname):''}}</strong>
              </td>
              <td width="15%" style="border:1px solid black; border-top: none; border-left: none;padding-bottom: 0px; text-align:center;">
                  <strong>{{ date('m/d/Y', strtotime((isset($RptPropertyApproval->rp_app_recommend_date))?$RptPropertyApproval->rp_app_recommend_date:''))}}</strong>
              </td>
              <td width="35%" style="border:1px solid black; border-right: none; border-top: none; padding-bottom: 10px; text-align: center;">
                  <strong>{{($approvedPosition->is_sgd == 1)?'SGD ':''}}{{(isset($approvedPosition->fullname))? strtoupper($approvedPosition->fullname):''}} </strong>
              </td>
              <td width="15%" style="border:1px solid black; border-top: none; border-left: none;padding-bottom: 0px; text-align:center;">
                  <strong>{{ date('m/d/Y', strtotime((isset($RptPropertyApproval->rp_app_approved_date))?$RptPropertyApproval->rp_app_approved_date:''))}}</strong>
              </td>
          </tr>
          <tr>
              <td width="35%" style="border:1px solid black; border-right: none; border-top: none; padding-top: 0px; padding-bottom: 20px; text-align: center;">
                  <small>{{(isset($recommendPosition->description))?$recommendPosition->description:''}} </small>
              </td>
              <td width="15%" style="border:1px solid black; border-top: none; border-left: none;padding-top: 0px; padding-bottom: 20px; text-align:center;">
                  <small>Date</small>
              </td>
              <td width="35%" style="border:1px solid black; border-right: none; border-top: none; padding-top: 0px; padding-bottom: 20px; text-align: center;">
                  <small>{{(isset($approvedPosition->description))?$approvedPosition->description:''}} </small>
              </td>
              <td width="15%" style="border:1px solid black; border-top: none; border-left: none;padding-top: 0px; padding-bottom: 20px; text-align:center;">
                  <small>Date</small>
              </td>
          </tr>
      </table>


      <table width="100%" style="border:1px solid black; border-top: none;">
          <tr>
              <td style=" width:100%; text-align:left; border-top:none; padding-bottom: 40px;"><strong>MEMORANDA :</strong> {{strtoupper($rptProperty->rp_app_memoranda)}}<br />@if($annotation != '')<span style="font-weight: bold;">Annotation:</span><br />{{$annotation}}@endif</td>
          </tr>
      </table>

      <table width="100%">
          <tr>
              <td style="border:1px solid black; border-top: none; border-bottom: none;  padding-top: 10px; padding-bottom:10px;">
                  <h4 style="padding:0px">REFERENCE & POSTING SUMMARY</h4>
              </td>
          </tr>
      </table>

      <table width="100%">
          <tr>
              <td rowspan="2" style="padding-top:3px; padding-bottom:3px; text-align: center;">Reference</td>
              <td rowspan="2" style="padding-top:3px; padding-bottom:3px; text-align: center;">Previous Record</td>
              <td  colspan="2" style="padding-top:3px; padding-bottom:3px; text-align: center;">Posting Report</td>
              <td rowspan="2" style="padding-top:3px; padding-bottom:3px; text-align: center;">Post Inspection</td>
          </tr>
          <tr>
              <td style="padding-top:5px; padding-bottom:5px; text-align: center;">Date</td>
              <td style="padding-top:5px; padding-bottom:5px; text-align: center;">Initial</td>
          </tr>
          <tr>
              <td style="padding-top:5px; padding-bottom:5px; text-align: left;">PIN</td>
              <td style="padding-top:5px; padding-bottom:5px; text-align: center;">{{$cacnceledPinArray}}</td>
              <td style="padding-top:5px; padding-bottom:5px; text-align: center;"></td>
              <td style="padding-top:5px; padding-bottom:5px; text-align: center;">&nbsp;</td>
              <td style="padding-top:5px; padding-bottom:5px; text-align: center;">&nbsp;</td>
          </tr>
          <tr>
              <td style="padding-top:5px; padding-bottom:5px; text-align: left;">TDN/ARP No.</td>
              <td style="padding-top:5px; padding-bottom:5px; text-align: center;">{{$cancelTdByThis}}</td>
              <td style="padding-top:5px; padding-bottom:5px; text-align: center;">&nbsp;</td>
              <td style="padding-top:5px; padding-bottom:5px; text-align: center;">&nbsp;</td>
              <td style="padding-top:5px; padding-bottom:5px; text-align: center;">&nbsp;</td>
          </tr>
          <tr>
              <td style="padding-top:5px; padding-bottom:5px; text-align: left;">Ass. Roll Page and No.</td>
              <td style="padding-top:5px; padding-bottom:5px; text-align: center;">&nbsp;</td>
              <td style="padding-top:5px; padding-bottom:5px; text-align: center;">&nbsp;</td>
              <td style="padding-top:5px; padding-bottom:5px; text-align: center;">&nbsp;</td>
              <td style="padding-top:5px; padding-bottom:5px; text-align: center;">&nbsp;</td>
          </tr>
      </table>
  </div>
</body>
</html>







