<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css?family=Fjalla+One|Arial:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
        * { margin: 0; padding: 0; }
        @page {sheet-size: Letter; margin-top: 15mm; margin-bottom: 15mm; margin-header: 0mm;margin-footer: 0mm;}
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
    <title>{{(isset($rptProperty->propertyKindDetails->pk_code) && $rptProperty->propertyKindDetails->pk_code == 'L')?'LAND/PLANTS & TREES':((isset($rptProperty->propertyKindDetails->pk_code) && $rptProperty->propertyKindDetails->pk_code == 'B')?'BUILDING':'MACHINERY')}} FAAS</title>
</head>

<body>
  <div style="">
      <h2 style="margin-top:10px; font-size: 17px; text-align: center;">REAL PROPERTY FIELD APPRAISAL & ASSESSMENT SHEET-{{(isset($rptProperty->propertyKindDetails->pk_code) && $rptProperty->propertyKindDetails->pk_code == 'L')?'LAND/PLANTS & TREES':((isset($rptProperty->propertyKindDetails->pk_code) && $rptProperty->propertyKindDetails->pk_code == 'B')?'BUILDING':'MACHINERY')}}</h2>

      <table style="text-align:right; width:100%;">
          <tr>
              <td style="border: none; padding-bottom: 0px; padding-right:50px">TRANSACTION CODE</td>
              <td style="width:100px; border: none; border-bottom: 1px solid black; padding-bottom: 0px; text-align:center;">{{(isset($rptProperty->updateCode->uc_code))?$rptProperty->updateCode->uc_code:''}}</td>
          </tr>
      </table>
      
      <table width="100%" style="margin-top:10px; margin-bottom:5px;">
          <tr>
              <td style="width: 50%; border-bottom: 0; border-right: 0; padding:2px; padding-bottom: 0px;">
                  ARP NO
              </td>
              <td style="width: 50%; border-bottom: 0; border-left: 0; padding:2px; padding-bottom: 0px;">PIN
              </td>
          </tr>
          <tr>
              <td style="border-top: 0; border-right: 0; padding:2px; text-align: center;"><strong>@php
                $array = explode("-",$rptProperty->pr_tax_arp_no);
                if(count($array) > 2){
                    unset($array[0]);
                }
                @endphp
                {{ implode("-",$array) }}</strong></td>
              <td style="border-top: 0; border-left: 0; padding:2px; text-align: center;"><strong>{{$rptProperty->rp_pin_declaration_no}}</strong></td>
          </tr>
          @if(isset($rptProperty->propertyKindDetails->pk_code) && $rptProperty->propertyKindDetails->pk_code == 'L')
          <tr>
              <td style="width: 50%; border-top: 0; border-bottom: 0; border-right: 0; padding:2px; padding-bottom: 0px;">
                  OCT/TCT No.:
              </td>
              <td style="width: 50%; border-top: 0; border-bottom: 0; border-left: 0; padding:2px; padding-bottom: 0px;">Lot No.:
              </td>
          </tr>
          <tr>
              <td style="border-top: 0; border-right: 0; padding:2px; text-align: center;"><strong>{{$rptProperty->rp_oct_tct_cloa_no}}</strong></td>
              <td style="border-top: 0; border-left: 0; padding:2px; text-align: center;"><strong>{{$rptProperty->rp_cadastral_lot_no}}</strong></td>
          </tr>
          @endif
          <tr>
              <td style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 10px;">
                  Owner:
              </td>
              <td style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 10px;">Address:
              </td>
          </tr>
          <tr>
              <td style="border-top: 0; padding:2px; text-align: left;"><strong>{{$own_name}}</strong></td>
              <td style="border-top: 0; padding:2px; text-align: left; vertical-align: bottom;"><strong>{{$own_address}}</strong></td>
          </tr>
          <tr>
              <td style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 10px;">
                  Administrator/Beneficial User: {{$admin_name}}
              </td>
              <td style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 10px;">Address: {{$admin_address}}
              </td>
          </tr>
          <tr>
              <td style="border-top: 0; padding:2px; text-align: left;">TIN: {{(isset($rptProperty->propertyAdmin->p_tin_no))?$rptProperty->propertyAdmin->p_tin_no:''}}</td>
              <td style="border-top: 0; padding:2px; text-align: left;">Tel No.: {{(isset($rptProperty->propertyAdmin->p_telephone_no))?$rptProperty->propertyAdmin->p_telephone_no:''}}</td>
          </tr>
      </table>
      @if(isset($rptProperty->propertyKindDetails->pk_code) && $rptProperty->propertyKindDetails->pk_code == 'L')
      @include('inquiries.by_apr_no.kind.land.location')
      @include('inquiries.by_apr_no.kind.land.bound')
      @include('inquiries.by_apr_no.appraisals.land')
      @endif

       @if(isset($rptProperty->propertyKindDetails->pk_code) && $rptProperty->propertyKindDetails->pk_code == 'B')
      @include('inquiries.by_apr_no.kind.building.location')
      @include('inquiries.by_apr_no.kind.building.general_desc')
      @include('inquiries.by_apr_no.kind.building.stucture')
      @include('inquiries.by_apr_no.kind.building.additional')
      @include('inquiries.by_apr_no.kind.building.appraisal')
      @endif
      
      <table width="100%" style="padding:0px;">
          <tr>
              <td rowspan="2" style="border: solid 1px #000; border-right: none; border-top: none; padding-top:0px; padding-bottom: 0px; vertical-align: middle; text-align: right;">
                  Taxable 
                  @if($rptProperty->rp_app_taxability == 1)
                  <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 5px;">
                  @else
                  <img src="{{ asset('assets/images/checkbox-unchecked.jpg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 5px;">
                  @endif
              </td>
              
              <td rowspan="2" style="border: solid 1px #000; border-right: none; border-top: none; border-left: none; padding-top:0px; padding-bottom: 0px; text-align: center;">
                  Exempt 
                  @if($rptProperty->rp_app_taxability == 0)
                  <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 5px;">
                  @else
                  <img src="{{ asset('assets/images/checkbox-unchecked.jpg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 5px;">
                  @endif
              </td>
              <td rowspan="2" style="border: solid 1px #000; border-right: none; border-top: none; border-left: none; padding-top:0px; padding-bottom: 0px; padding-right: 20px; vertical-align: middle; text-align: right;">
                  Effectivity of Assessment/Re-Assessment
              </td>
              <td width="15%"  style="border: solid 1px #000; border-top: none; border-left: none; padding-top:5px; padding-bottom: 0px; vertical-align: bottom;">
                @if($rptProperty->rp_app_effective_quarter == 1)
                 1st
                @elseif($rptProperty->rp_app_effective_quarter == 2)
                2nd
                @elseif($rptProperty->rp_app_effective_quarter == 3)
                3rd
                @elseif($rptProperty->rp_app_effective_quarter == 4)
                4th
                @else
                @endif
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$rptProperty->rp_app_effective_year}}
              </td>
          </tr>
          <tr>
            
              <td width="15%" style="border: solid 1px #000; border-top: none; border-left: none; padding-top:0px; padding-bottom: 5px; vertical-align: top;">
                  Qtr. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Year
              </td>
          </tr>
      </table>

      <table width="100%" style="padding:0px">
          <tr>
              <td style="border:0px solid black; padding-top: 5px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; width: 100%;">
                  <h4 style="padding:0px">APPRAISED BY:</h4>
              </td>
          </tr>
      </table>

      <div style="width: 100%; border:none; padding:5px;">
          <div style="width:220px; float: left; text-align: center; padding-top:10px;">
              <p style="text-align: center; font-weight: bold;">
              {{($apprisedPosition->is_sgd == 1)?'SGD ':''}}{{(isset($apprisedPosition->fullname))?$apprisedPosition->fullname:''}} 
              </p>
              {{(isset($apprisedPosition->description))?$apprisedPosition->description:''}} 
          </div>

          <div style="width:100px; float: left; text-align: center; padding-top:10px; margin-left: 37px;">
              <p style="border-bottom:solid 1px black; text-align: center;">
              {{ date('m/d/Y', strtotime((isset($RptPropertyApproval->rp_app_appraised_date))?$RptPropertyApproval->rp_app_appraised_date:''))}}
              </p>
              Date
          </div>

          <div style="width:150px; float: left; padding-top:60px; margin-left: 30px;">
              <p style="border-bottom:none; text-align: left; font-weight: bold;">
                  APPROVED:
              </p>
          </div>

          <div style="clear:both;"></div>

          <p style="margin-top:-18px;"><strong>RECOMMENDING APPROVAL:</strong></p>
          <!-- <p style="margin-top:-15px;text-align:;margin-left: 300px;"><strong>APPROVED:</strong></p> -->
          <div style="width:200px; float: left; text-align: center; padding-top:10px;">
              <p style="border-bottom:solid 1px black; text-align: center;">
              {{($recommendPosition->is_sgd == 1)?'SGD ':''}}{{(isset($recommendPosition->fullname))?$recommendPosition->fullname:''}} 
              </p>
              {{(isset($recommendPosition->description))?$recommendPosition->description:''}} 
          </div>

          <div style="width:100px; float: left; text-align: center; padding-top:10px; margin-left: 37px;">
              <p style="border-bottom:solid 1px black; text-align: center;">
              {{ date('m/d/Y', strtotime((isset($RptPropertyApproval->rp_app_recommend_date))?$RptPropertyApproval->rp_app_recommend_date:''))}}
              </p>
              Date
          </div>
          
          <div style="width:200px; float: left; text-align: center; padding-top:10px; margin-left: 30px;">
              <p style="border-bottom:solid 1px black; text-align: center;">
              {{($approvedPosition->is_sgd == 1)?'SGD ':''}}{{(isset($approvedPosition->fullname))?$approvedPosition->fullname:''}} 
              </p>
             {{(isset($approvedPosition->description))?$approvedPosition->description:''}} 
          </div>
          <div style="width:100px; float: left; text-align: center; padding-top:10px; margin-left: 30px;">
              <p style="border-bottom:solid 1px black; text-align: center;">
              {{ date('m/d/Y', strtotime((isset($RptPropertyApproval->rp_app_approved_date))?$RptPropertyApproval->rp_app_approved_date:''))}}
              </p>
              Date
          </div>
      </div>
      
      <table width="100%" style="border:1px solid black; border-top: none; margin-top:15px;">
          <tr>
              <td style=" width:100%; text-align:left; border-bottom:none; padding-bottom:40px;">MEMORANDA: {{strtoupper($rptProperty->rp_app_memoranda)}}<br />@if($annotation != '')<span style="font-weight: bold;">Annotation:</span><br />{{$annotation}}@endif</td>
          </tr>
          
      </table>

      <div style="width: 100%; border:none; padding:5px;">
          <div style="width:415px; float: left; text-align: left; padding-top:0px;">
              <p>
                  Data of Entry in the Journal of Assessment Transaction:_____________________
              </p>
          </div>


          <!-- <div style="width:30px; float: left; text-align: center; padding-top:0px; margin-left: 5px;">
              <p>
                  By:
              </p>
          </div>

          <div style="width:100px; float: left; text-align: left; padding-top:0px;">
              <p style="border-bottom:solid 1px black;">
                  &nbsp;
              </p>
              Name
          </div> -->
      </div>

      <table width="100%" style="padding:0px">
          <tr>
              <td style="border:0px solid black; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; width: 100%;">
                  <h4 style="padding:0px">RECORD OF SUPERSEDED ASSESSMENT</h4>
              </td>
          </tr>
      </table>

      <table width="100%">
          <tr>
              <td style="width: 30%; padding-top:2px; padding-bottom: 2px; border-right:none;"><strong>Reference</strong></td>
              <td style="border-left: none; padding-top:2px; padding-bottom: 2px;"><strong>Record of Superseded Assessment</strong></td>
          </tr>
          <tr>
              <td style="width: 30%; padding-top:2px; padding-bottom: 2px; border-right:none;">PIN:</td>
              <td style="border-left: none; padding-top:2px; padding-bottom: 2px;">{{$cacnceledPinArray}}</td>
          </tr>
          <tr>
              <td style="width: 30%; padding-top:2px; padding-bottom: 2px; border-right:none;">ARP No:</td>
              <td style="border-left: none; padding-top:2px; padding-bottom: 2px;">{{ $cacnceledArpArray }}</td>
          </tr>
          <tr>
              <td style="width: 30%; padding-top:2px; padding-bottom: 2px; border-right:none;">TD No:</td>
              <td style="border-left: none; padding-top:2px; padding-bottom: 2px;">{{$cancelComTdArray}}</td>
          </tr>
          <tr>
              <td style="width: 30%; padding-top:2px; padding-bottom: 2px; border-right:none;">AR/TR Page No:</td>
              <td style="border-left: none; padding-top:2px; padding-bottom: 2px;">{{$cancelTdByThis}}</td>
          </tr>
          <tr>
              <td style="width: 30%; padding-top:2px; padding-bottom: 2px; border-right:none;">Total Assessed Value:</td>
              <td style="border-left: none; padding-top:2px; padding-bottom: 2px;">{{$cacnceledTdAssValue}} </td>
          </tr>
          <tr>
              <td style="width: 30%; padding-top:2px; padding-bottom: 2px; border-right:none;">Previous Owner:</td>
              <td style="border-left: none; padding-top:2px; padding-bottom: 2px;">{{$cancelTdByOwner}}</td>
          </tr>
          <tr>
              <td style="width: 30%; padding-top:2px; padding-bottom: 2px; border-right:none;">Effectivity of Assessment:</td>
              <td style="border-left: none; padding-top:2px; padding-bottom: 2px;">{{$cancelPropEffectivity}}</td>
          </tr>
          </table>
          <table width="100%">
          <tr>
              <td style="width: 50%;  text-align: center; padding-top:2px; padding-bottom: 2px; border-right:none; border-bottom: none;border-top: none;">
                  Recording Personnel:</td>
              <td style="border-left: none;  text-align: center; padding-top:2px; padding-bottom: 2px; border-bottom: none;border-top: none;">Date:
              </td>
          </tr>

          <tr>
              <td style="width: 50%; border-top:none; text-align: right; padding-right: 0; border-right: none;">
                  <table style="width:100%">
                      <tr>
                          <td style="width: 100%; text-align: center; padding-top:2px;  padding-bottom: 0px; border: none; border-bottom:1px solid black;">{{$registered_by->fullname}}
                          </td>
                      </tr>
                  </table>
              </td>
              <td style="width: 50%; border-top:none; text-align:left; padding-left: 0; border-left:none;">
                  <table style="width:100%">
                      <tr>
                          <td style="width: 100%; border-left: none; text-align: center;  padding-top:2px; padding-bottom: 0px; border: none; border-bottom:1px solid black;">{{ date('d/m/Y', strtotime($rptProperty->created_at))}}
                          </td>
                      </tr>
                  </table>
              </td>
          </tr>
      </table>
      
  </div>
</body>
</html>







