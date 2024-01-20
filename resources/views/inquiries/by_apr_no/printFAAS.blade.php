
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
table td, table th { border: 1px solid black; padding: 2px; }
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

#logo { text-align: right; float: right;}
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

    <title>Bureau Cheque</title>
  </head>

  <body>
    <table style='width:700px;'>
      <tr>
        <td style="border:0px;">
           
            <table width="100%" id="summary" class="noborder">
                <tr>
                     <td><p style="text-align: center;"><b>REAL PROPERTY FIELD APPRAISAL & ASSESSMENT SHEET-LAND/PLANTS TREES</b></p>
                    </td>
                </tr>
            </table> 
            <table width="100%" id="summary" class="noborder">
                <tr>
                     <td style="text-align: right;"><p >TRANSACTION CODE <b><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$rptProperty->updatecode->uc_code}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></b></p></td>
                </tr>
            </table>
           
            <table width="100%" id="summary">
                <tr>
                     <td style="border-right: 0px;">
                        <p style="text-align: left;">ARP NO :</p>
                        <p style="text-align: center;"><b>{{($rptProperty->dist_code != null ?$rptProperty->dist_code : "").($rptProperty->brgy_no  != null ? $rptProperty->brgy_no  : "").($rptProperty->rp_td_no != null ? "-".$rptProperty->rp_td_no : "")}}</b></p>
                    </td>
                    <td style="border-left: 0px;"><p style="text-align: left;">PIN :</p>
                        <p style="text-align: center;"> <b>{{$prop_index_no}}</b></p>
                    
                    </td>
                    </td>
                </tr>
            </table> 
            <table width="100%" id="summary">
                <tr>
                     <td style="border-right: 0px;">
                        <p style="text-align: left;">OCT/TCT No. :</p>
                        <p style="text-align: center;"><b>{{$rptProperty->rp_oct_tct_cloa_no}}</b></p>
                    </td>
                    <td style="border-left: 0px;"><p style="text-align: left;">Lot No. :</p>
                        <p style="text-align: center;"> <b>{{$rptProperty->rp_cadastral_lot_no}}</b></p>
                    
                    </td>
                    </td>
                </tr>
            </table> 
            <table width="100%" id="summary">
                <tr>
                     <td width="50%">
                        <p style="text-align: left;">Owner:</p><br><br>
                        <p style="text-align: left;font-size: 11px;"><b>{{$own_name}}</b></p>
                    </td>
                    <td width="50%"><p style="text-align: left;">Address:</p><br><br>
                        <p style="text-align: left;font-size: 11px;"> <b>{{$own_address}}</b></p>
                    </td>
                </tr>
                <tr>
                    <td width="50%">
                       <p style="text-align: left;font-size: 11px;">Administrator/Beneficial User: {{$admin_name}}</p><br>
                       <p style="text-align: left;font-size: 11px;">TIN: {{$rptProperty->admin_p_tin_no}}</p>
                   </td>
                   <td width="50%"><p style="text-align: left;font-size: 11px;">Address: {{$admin_address}}</p><br>
                       <p style="text-align: left;font-size: 11px;">Tel No.: {{$rptProperty->admin_own_tel_no}}</p>
                   </td>
               </tr>
           
                <tr>
                     <td width="100%" colspan="2" style="border:0px;"><p style="text-align: left;"><b>PROPERTY LOCATION</b></p></td>
                </tr>
           
                    <tr>
                        <td >
                        <p style="text-align: left;font-size: 11px;width:50%;">No./Street:</p><br>
                        <p style="text-align: left;font-size: 11px;">{{$rptProperty->rp_location_number_n_street}}</p>
                    </td>
                    <td ><p style="text-align: left;font-size: 11px;width:50%;">Brgy.:</p><br>
                        <p style="text-align: left;font-size: 11px;">{{$rptProperty->barangay->brgy_name}}</p>
                    </td>
                </tr> 
                <tr>
                    <td width="50%">
                    <p style="text-align: left;font-size: 11px;">District: {{$rptProperty->dist_code}} </p><br>
                    
                </td>
                <td width="50%"><p style="text-align: left;font-size: 11px;">City: <b>{{$rptProperty->Locality->loc_address}}</b></p><br>
               </td>
            </tr> 
           
                <tr>
                     <td width="100%" colspan="2" style="border:0px;"><p style="text-align: left;"><b>PROPERTY BOUNDARIES</b></p></td>
                </tr>
        
            <tr>
                    <td width="50%">
                    <p style="text-align: left;font-size: 11px;">North:</p><br>
                    <p style="text-align: left;font-size: 11px;">{{$rptProperty->rp_bound_north}}</p>
                </td>
                <td width="50%" style="text-align: left;font-size: 11px;border-bottom: 0px;">Land Sketch:
                    
                    
                
                
                </td>
            </tr> 
            <tr>
                <td width="50%">
                    <p style="text-align: left;font-size: 11px;">East:</p><br>
                    <p style="text-align: left;font-size: 11px;">{{$rptProperty->rp_bound_east}}</p>  
                </td>
                <td width="50%" style="text-align: left;font-size: 11px;border-bottom: 0px;border-top: 0px;">
                </td>
            </tr> 
            <tr>
                <td width="50%">
                    <p style="text-align: left;font-size: 11px;">South:</p><br>
                    <p style="text-align: left;font-size: 11px;">{{$rptProperty->rp_bound_south}}</p>   
                </td>
                <td width="50%" style="text-align: left;font-size: 11px;border-bottom: 0px;border-top: 0px;">
                 </td>
            </tr> 
            <tr>
                <td width="50%">
                    <p style="text-align: left;font-size: 11px;">West:</p><br>
                    <p style="text-align: left;font-size: 11px;">{{$rptProperty->rp_bound_west}}</p>  
                </td>
                <td width="50%" style="text-align: center;font-size: 11px;border-top: 0px;">
                    (Not necessanly draw to scale)
                    
                
                
                </td>
            </tr> 
        </table>
        <table width="100%" id="summary" class="noborder">
            <tr>
                 <td><p style="text-align: left;"><b>LAND APPRAISAL</b></p></td>
            </tr>
        </table>
            <table width="100%" id="summary">
                <tr>
                    <th width="25%"style="font-size: 10px;">Classification</th>
                    <th width="25%"style="font-size: 10px;">Sub-Classification</th>
                    <th width="25%"style="font-size: 10px;">Area</th>
                    <th width="25%"style="font-size: 10px;">Unit Value</th>
                    <th width="25%"style="font-size: 10px;">Base Market value</th>
                </tr>
                @php
                $t_area=0;
                $t_market_val=0;
                @endphp
                @foreach($RptPropertyAppraisals as $RptPropertyAppraisal)
                @php
                $t_area=$t_area+$RptPropertyAppraisal->rpa_total_land_area;
                $t_market_val=$t_market_val+$RptPropertyAppraisal->rpa_base_market_value;
                @endphp
                <tr>
                    <td style="font-size: 10px;text-align: center;"><p>{{$RptPropertyAppraisal->getPcClassDescriptionAttribute()}}</p></td>
                    <td style="font-size: 10px;text-align: center;">{{$RptPropertyAppraisal->getPsSubclassDescAttribute()}}</td>
                    <td style="font-size: 10px;text-align: center;">{{$RptPropertyAppraisal->rpa_total_land_area}} has</td>
                    <td style="font-size: 10px;text-align: center;">{{$RptPropertyAppraisal->lav_unit_value}} </td>
                    <td class="pull-right" style="font-size: 10px;">{{$RptPropertyAppraisal->rpa_base_market_value}}</td>
                </tr>  
                @endforeach
                <tr>
                    <td style="font-size: 10px;text-align: center;border-right: 0px;"></td>
                    <td style="font-size: 10px;text-align: center;border-right: 0px;border-left: 0px;"><b>Total</b></td>
                    <td style="font-size: 10px;text-align: center;border-right: 0px;border-left: 0px;">{{$t_area}} has</td>
                    <td style="font-size: 10px;text-align: center;border-right: 0px;border-left: 0px;"></td>
                    <td class="pull-right" style="font-size: 10px;border-left: 0px;">{{$t_market_val}}</td>
                </tr> 
               
            </table>
            
            <table width="100%" id="summary" class="noborder">
                <tr>
                     <td><p style="text-align: left;"><b>PLANTS AND TREES APPRAISAL</b></p></td>
                </tr>
            </table>
                <table width="100%" id="summary">
                    <tr>
                        <th width="5"style="font-size: 10px;" rowspan="3">Kind of Plants/Trees</th>
                        <th width="5"style="font-size: 10px;" rowspan="3">Sub-Class/Age</th>
                        <th width="25%" colspan="5" style="font-size: 10px;">NUMBER OF TREES PLANTED
                           
                               
                             
                        </th>
                        
                        <th width="25%"style="font-size: 10px;" rowspan="3">Unit Value</th>
                        <th width="25%"style="font-size: 10px;" rowspan="3">Base Market value</th>
                    </tr>
                    <tr>
                        
                        <th width="25%"style="font-size: 10px;" rowspan="2">Non-Fruit Bearing</th>
                        <th width="75%"style="font-size: 10px;" colspan="4">Fruit Bearing</th>
                       
                    </tr>
                    <tr>
                        <th width="100%"style="font-size: 10px;" colspan="2">Productive</th>
                        <th width="25%"style="font-size: 10px;" rowspan=""></th>
                        <th width="75%"style="font-size: 10px;" colspan="1">Non Productive</th>
                       
                    </tr>
                    @php
                    $t_market_val=0;
                    @endphp
                    @foreach($RptPlantTreesAppraisals as $RptPlantTreesAppraisal)
                    @php
                    $t_market_val=$t_market_val+$RptPlantTreesAppraisal->rpta_market_value;
                    @endphp
                    <tr>
                        <td style="font-size: 10px;text-align: center;"><p>{{$RptPlantTreesAppraisal->getPcClassDescriptionAttribute()}}</p></td>
                        <td style="font-size: 10px;text-align: center;">{{$RptPlantTreesAppraisal->getPsSubclassDescAttribute()}}</td>
                        <td style="font-size: 10px;text-align: center;">{{$RptPlantTreesAppraisal->rpta_non_fruit_bearing}}  has</td>
                        <td style="font-size: 10px;text-align: center;">{{$RptPlantTreesAppraisal->rpta_fruit_bearing_productive}} </td>
                        <td class="pull-right" style="font-size: 10px;">2,177,176.50</td>
                        <td style="font-size: 10px;text-align: center;">{{$RptPlantTreesAppraisal->getPsSubclassDescAttribute()}}</td>
                        <td style="font-size: 10px;text-align: center;">{{$RptPlantTreesAppraisal->rpta_fruit_bearing_non_productive}} has</td>
                        <td style="font-size: 10px;text-align: center;">{{$RptPlantTreesAppraisal->rpta_unit_value}} </td>
                        <td class="pull-right" style="font-size: 10px;">{{$RptPlantTreesAppraisal->rpta_market_value}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td style="font-size: 10px;text-align: center;border-right: 0px;"></td>
                        <td style="font-size: 10px;text-align: center;border-right: 0px;border-left: 0px;"></td>
                        <td style="font-size: 10px;text-align: center;border-right: 0px;border-left: 0px;"></td>
                        <td style="font-size: 10px;text-align: center;border-right: 0px;border-left: 0px;"></td>
                        <td class="pull-right" style="font-size: 10px;border-right: 0px;border-left: 0px;"></td>
                        <td style="font-size: 10px;text-align: center;border-right: 0px;border-left: 0px;"></td>
                        <td class="pull-right" style="font-size: 10px;border-left: 0px;border-right: 0px;"></td>
                        <td style="font-size: 10px;text-align:left ;border-left:0px;" colspan="2">Sub-Total</td>
                        
                        
                    </tr>  
                    <tr>
                        <td style="font-size: 10px;text-align: center;border-right: 0px;"></td>
                        <td style="font-size: 10px;text-align: center;border-right: 0px;border-left: 0px;"></td>
                        <td style="font-size: 10px;text-align: center;border-right: 0px;border-left: 0px;"></td>
                        <td style="font-size: 10px;text-align: center;border-right: 0px;border-left: 0px;"></td>
                        <td class="pull-right" style="font-size: 10px;border-right: 0px;border-left: 0px;"colspan="2">Total (Land,Plants & Trees)</td>
                        <td style="font-size: 10px;text-align: center;border-right: 0px;border-left: 0px;"></td>
                        <td class="pull-right" style="font-size: 10px;border-left: 0px;border-right: 0px;"></td>
                        <td style="font-size: 10px;text-align:right ;border-left:0px;" colspan="2">{{$t_market_val}}</td>
                        
                        
                    </tr>     
                    
                   
                </table> 
            </td>
        </tr>
            </table>
     
    
    </body>
    </html>
    