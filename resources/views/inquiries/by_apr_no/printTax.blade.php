
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style type="text/css">
      @import url('https://fonts.googleapis.com/css?family=Fjalla+One|Montserrat:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
* { margin: 0; padding: 0; }
body { font-family: 'Montserrat', sans-serif;font-size: 14px; }
table td, table th { border: 1px solid black; padding: 5px; }
table .noborder td{border: 0px solid black !important;}
.page-wrap { width: 800px; margin: 100px auto;}

textarea { border: 0; font-size: 14px; font-family: 'Montserrat', sans-serif;overflow: hidden; resize: none; }
table { border-collapse: collapse; }


/*#header { height: 15px; width: 100%; margin: 20px 0; background: #222; text-align: center; color: white; font: bold 15px 'Montserrat', sans-serif text-decoration: uppercase; letter-spacing: 20px; padding: 8px 0px; }*/
.header{margin: 20px 0;text-align: left;font-size: 45px;font-weight: 700; font-family: 'Fjalla One', sans-serif; text-decoration: uppercase;
    letter-spacing: 1px;padding: 8px 0px;color:#1f4888;}
.company-name {font-size: 20px;font-weight: 600;}

#address { width: 250px; height: 150px; float: left;font-weight: 500; }
/*#customer { overflow: hidden; }*/
.company-address {font-size: 15px;color: #1f4888 !important;height: 115px !important;}

#logo { text-align: right; float: right; position:}
/*#logo:hover, #logo.edit { border: 1px solid #000; margin-top: 0px; max-height: 125px; }*/
#logoctr { display: none; }

#logohelp { text-align: left; display: none; font-style: italic; padding: 10px 5px;}
#logohelp input { margin-bottom: 5px; }
.edit #logohelp { display: block; }
.edit #save-logo, .edit #cancel-logo { display: inline; }
.edit #image, #save-logo, #cancel-logo, .edit #change-logo, .edit #delete-logo { display: none; }
#customer-title { font-size: 20px; font-weight: bold; float: left;color: #1f4888; }

#meta { margin-top: 1px; width: 300px; float: right; }
#meta td { text-align: right;  }
#meta td.meta-head { text-align: left; background: #eee;font-weight: 500; }
#meta td textarea { width: 100%; height: 20px; text-align: right; }
textarea:hover, textarea:focus, #items td.total-value textarea:hover, #items td.total-value textarea:focus, .delete:hover { background-color:#EEFF88; }
.pull-right{text-align: right;}
.delete-wpr { position: relative; }
.delete { display: block; color: #000; text-decoration: none; position: absolute; background: #EEEEEE; font-weight: bold; padding: 0px 3px; border: 1px solid; top: -6px; left: -22px; font-family: Verdana; font-size: 12px; }
.footer-contain {text-align: center;font-size: 35px; font-weight: 700;color: #1f4888;}
.font_change{white-space: pre !important;font-size: 12px;margin-left: 10px;}
    </style>

    <title>Inspection Certificate</title>
  </head>

<body>
<table style='width:700px;'>
  <tr>
    <td style="border: 1px solid black;">
        <table width="100%" id="summary" class="noborder">
                 <tr>
                <td width='10%'><img src="http://localhost/playan/assets/images/logo.png" height="100px" width="100">
                       </td>
                <td  width='90%' style="text-align:center;text-transform: uppercase;">
                            <p style="font-size:22px;"><strong>Tax Declaration Of Real Property</strong></p>
                </tr>
        </table>
        <table width="100%" id="summary" class="noborder">
                 <tr>
                    <td width='40%' style="text-align: left;"><p style="text-decoration: uppercase;"><span style="width: 100px;">TD No.</span> <u>{{$rptProperty->td_no}}</u></p></td>
                    <td width='60%' style="text-align: center;"><p style="text-decoration: uppercase;">Property Indentification No.<u>{{$prop_index_no}}</u></p></td>
                </tr>
         </table>
         <table width="100%" id="summary" class="noborder">        
                 <tr>
                  <td  width='10%' style="text-align: left;"><p style="text-decoration: uppercase;">Owner.</p></td>
                  <td  width='90%' style="text-align: left;"><p style="text-decoration: uppercase;"> <u>{{$own_name}}</u></p></td>  
                </tr> 
                <tr>
                  <td  width='10%' style="text-align: left;"><p style="text-decoration: uppercase;">Address.</p></td>
                  <td  width='90%' style="text-align: left;"><p style="text-decoration: uppercase;"> <u>{{$own_address}}</u></p></td>  
                </tr>
         </table>
         <table width="100%" id="summary" class="noborder"> 
                <tr>
                  <td  width='50%' style="text-align: left;"><p style="text-decoration: uppercase;">Telephone No:</p></td>
                  <td  width='50%' style="text-align: right;"><p style="text-decoration: uppercase;"><u>{{$rptProperty->own_tel_no}}</u></p></td>  
                </tr>
          </table>
          <table width="100%" id="summary" class="noborder">
                 <tr>
                    <td width='' style="text-align: left;"><p style="text-decoration: uppercase;"><span style="width: 100px;">Administrator/Benefisher User:</span> <u>{{$admin_name}}</u></p></td>
                    <td width='' style="text-align: left;"><p style="text-decoration: uppercase;">TIN.<u> {{$rptProperty->admin_p_tin_no}}</u></p></td>
                </tr>
         </table>
          <table width="100%" id="summary" class="noborder">
                 <tr>
                    <td width='60%' style="text-align: left;"><p style="text-decoration: uppercase;"><span style="width: 100px;">Address:</span> <u> {{$admin_address}}</u></p></td>
                    <td width='40%' style="text-align: left;"><p style="text-decoration: uppercase;">Telephone No.<u> {{$rptProperty->admin_own_tel_no}}</u></p></td>
                </tr>
         </table>
          <table width="100%" id="summary" class="noborder"> 
                <tr>
                  <td  width='50%' style="text-align: left;"><p style="text-decoration: uppercase; padding-right:20px;">Location Of Property:<u>{{($rptProperty->rp_location_number_n_street != null ?$rptProperty->rp_location_number_n_street : "").($rptProperty->brgy_no  != null ? ",".$rptProperty->brgy_no  : "").($rptProperty->dist_code != null ? ",".$rptProperty->dist_code : "").($rptProperty->loc_local_code != null ? ",".$rptProperty->loc_local_code : "")}}</u></p></td>
                </tr>
                <tr>
                  <td  width='50%' style="text-align: left;"><p style="text-decoration: uppercase;">OCT/CTC/CLOA No: {{$rptProperty->rp_oct_tct_cloa_no}}</p></td>
                </tr>
                 <tr>
                  <td  width='50%' style="text-align: left;"><p style="text-decoration: uppercase;">LOT/BLK/SURVEY No: {{$rptProperty->rp_cadastral_lot_no}}</p></td>
                </tr>
          </table>
     
         <table width="100%" id="summary" class="noborder">
                 <tr>
                    <td width='50%' style="text-align: left;"><p><strong>BOUNDRIES</strong></p></td>
                </tr>
         </table>
          <table width="100%" id="summary" class="noborder">
                 <tr>
                    <td width='20%' style="text-align: left;"><p>North</p></td>
                    <td width='30%' style="border:1px solid black !important;"><p>{{$rptProperty->rp_bound_north}}</p></td>
                    <td width='20%' style="text-align: center;"><p>East</p></td>
                    <td width='30%' style="border:1px solid black !important;"><p>{{$rptProperty->rp_bound_east}}</p></td>
                </tr>
                <tr>
                    <td width='20%' style="text-align: left;"><p>South</p></td>
                    <td width='30%' style="border:1px solid black !important;"><p>{{$rptProperty->rp_bound_south}}</p></td>
                    <td width='20%' style="text-align: center;"><p>West</p></td>
                    <td width='30%' style="border:1px solid black !important;"><p>{{$rptProperty->rp_bound_west}}</p></td>
                </tr>
        </table>
        <table width="100%" id="summary" class="noborder">
                 <tr>
                    <td width='50%' style="text-align: left;"><p><strong>KIND OF PROPERTY ASSESSED</strong></p></td>
                </tr>
         </table>
          <table width="100%" id="summary" class="noborder">
                 <tr>
                    <td width='50%' style="text-align: left;"><p><input type="text" value="{{$rptProperty->propertyKindDetails->pk_description == 'Land' ? 'X' : ''}}" style="width: 12px;padding-left: 9px;"> &nbsp;&nbsp;&nbsp;&nbsp;LAND</p></td>
                    <td width='50%'><p><input type="text" value="{{$rptProperty->propertyKindDetails->pk_description == 'Machinery' ? 'X' : ''}}" style="width: 12px;padding-left: 9px;"> &nbsp;&nbsp;&nbsp;&nbsp;MACHINERY</p></td>
                </tr>
                <tr>
                    <td width='50%' style="text-align: left;"><p><input type="text" value="{{$rptProperty->propertyKindDetails->pk_description == 'Building' ? 'X' : ''}}" style="width: 12px;padding-left: 9px;"> &nbsp;&nbsp;&nbsp;&nbsp;BUILDING</p></td>
                    <td width='50%'><p><input type="text" value="{{$rptProperty->propertyKindDetails->pk_description == 'Others' ? 'X' : ''}}" style="width: 12px;padding-left: 9px;"> &nbsp;&nbsp;&nbsp;&nbsp;OTHERS</p> <span>Brief Description:</span></td>
                </tr>
        </table>
        <table width="100%" id="summary" class="noborder">
                 <tr>
                    <td width='60%' style="text-align: left;"><p style="text-decoration: uppercase;"><span style="width: 100px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No Of Safety:</span> <u></u></p></td>
                    <td width='40%' style="text-align: left;"><p style="text-decoration: uppercase;">Safety</u></p></td>
                </tr>
                 <tr>
                    <td width='60%' style="text-align: left;"><p style="text-decoration: uppercase;"><span style="width: 100px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Brief Description:</span> <u></u></p></td>
                    <td width='40%' style="text-align: left;"><p style="text-decoration: uppercase;"></u></p></td>
                </tr>
        </table>
        <table width="100%" id="summary" class="noborder">
                 <tr>
                   <td width='18%'><p><u>Classification</u></p></td>
                   <td width='18%'><p><u>Area</u></p></td>
                   <td width='18%'><p><u>Market Value</u></p></td>
                   <td width='18%'><p><u>Actual Value</u></p></td>
                   <td width='18%'><p><u>Assessment</u></p></td>
                   <td width='10%'><p><u>Assessed value</u></p></td>
                </tr>
                @php
                $t_area=0;
                $t_market_val=0;
                $t_assesed_val=0;
                @endphp
                @foreach($RptPropertyAppraisals as $RptPropertyAppraisal)
                @php
                $t_area=$t_area+$RptPropertyAppraisal->rpa_total_land_area;
                $t_market_val=$t_market_val+$RptPropertyAppraisal->rpa_base_market_value;
                $t_assesed_val=$t_assesed_val+$RptPropertyAppraisal->rpa_assessed_value;
                @endphp
                <tr>
                   <td width='18%'><p><u>{{$RptPropertyAppraisal->getPcClassDescriptionAttribute()}}</u></p></td>
                   <td width='18%'><p><u>{{$RptPropertyAppraisal->rpa_total_land_area}} </u></p></td>
                   <td width='18%'><p><u>{{$RptPropertyAppraisal->rpa_base_market_value}} </u></p></td>
                   <td width='18%'><p><u>{{$RptPropertyAppraisal->getPauActualUseDescAttribute()}}</u></p></td>
                   <td width='18%'><p><u>{{$RptPropertyAppraisal->al_assessment_level}}</u></p></td>
                   <td width='10%'><p><u>{{$RptPropertyAppraisal->rpa_assessed_value}}</u></p></td>
                </tr>
                @endforeach
        </table>
        <table width="100%" id="summary" class="noborder">
                <tr>
                   <td width='20%'><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total</p></td>
                   <td width='20%'><p><u>{{$t_area}}</u></p></td>
                   <td width='30%'><p><u>{{$t_market_val}}</u></p></td>
                   <td width='40%'><p style="text-align: right;"><u>{{$t_assesed_val}}</u></p></td>
                </tr>
        </table>
         <table width="100%" id="summary" class="noborder"> 
                <tr>
                    @php
                    $t_assesed_val_words = $rptProperty->numberToWord($t_assesed_val);
                    @endphp
                  <td  width='50%' style="text-align: left;"><p style="text-decoration: uppercase; padding-right:20px;">Total Assessed Value:<u>{{$t_assesed_val_words}}</u></p></td>
                </tr>
          </table>
          <table width="100%" id="summary" class="noborder">
                <tr>
                   <td width='20%'><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" value="{{$rptProperty->rp_app_taxability == 1 ? 'X' : ''}}" style="width: 12px;padding-left: 9px;"> Taxable</p></td>
                   <td width='20%'><p><input type="text" value="{{$rptProperty->rp_app_taxability == 0 ? 'X' : ''}}" style="width: 12px;padding-left: 9px;"> Exempt</p></td>
                   <td width='30%'><p>Effectivity Of Asessment</p></td>
                   <td width='40%'><p style="text-align: center;"><u>{{$rptProperty->rp_app_effective_year}}</u></p></td>
                </tr>
                 <tr>
                   <td width='30%' style="text-transform: uppercase;"><p>Recommended By</p></td>
                   <td width='20%'></td>
                   <td width='30%' style="text-transform: uppercase;"><p>Approved BY</p></td>
                   <td width='20%'><p style="text-align: center;"></p></td>
                </tr>
        </table>
         <table width="100%" id="summary" class="noborder">
                <tr>
                   <td width='50%'><p><u>{{(!empty($rptProperty->propertyApproval->recommendBy->name)  ? $rptProperty->propertyApproval->recommendBy->name : "")}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{(!empty($rptProperty->propertyApproval->rp_app_recommend_date)  ? $rptProperty->propertyApproval->rp_app_recommend_date : "")}}</u></p></td>
                   <td width='50%'><p><u>{{(!empty($rptProperty->propertyApproval->approveBy->name)  ? $rptProperty->propertyApproval->approveBy->name : "")}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{(!empty($rptProperty->propertyApproval->rp_app_approved_date)  ? $rptProperty->propertyApproval->rp_app_approved_date : "")}}</u></td>
                   
                </tr>
        </table>
         <table width="100%" id="summary" class="noborder">
                <tr>
                   <td><p>THis Declaration Cancel TD NO:</p>{{!empty($rptProperty->propertyApproval->rp_app_cancel_by_td_no) ? $rptProperty->propertyApproval->rp_app_cancel_by_td_no : ""}}</td>
                 </tr>
                 <tr>
                    <td><p>Owner&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Maruti Devkate&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pre Av Php&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;287,760.00</u></p></td>
                </tr>
                <tr>
                    <td><p>MEMORANDA</p></td>
                </tr>
                <tr>
                    <td style="border:1px solid black !important;"><p>{{$rptProperty->rp_app_memoranda}}</p><br><br></td>
                </tr>
                <tr>
                    <td><p>Notes: This declaration is real property taxation purpose only and the valuation indicated herein are based on the schedule of unit values prepared for the purpose and duly inacted into an Ordinanace by the Sanggunian Panlungsod.It does not and cannot by itself alone can offer ownership of legal title to the property.</p></td>
                </tr>
        </table>
    </td>
  </tr>
</table>
</body>
</html>



