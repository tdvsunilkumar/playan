
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

    <title>Bureau Cheque</title>
  </head>

  <body>
    <table style='width:700px;'>
      <tr>
        <td style="border:0px;">
           
           
        <table width="100%" id="summary" class="noborder">
            <tr>
                 <td><p style="text-align: left;"><b>VALUE ADJUSTMENT</b></p></td>
            </tr>
        </table>
            <table width="100%" id="summary">
                <tr>
                    <th width="25%"style="font-size: 10px;">Base Market value</th>
                    <th width="25%"style="font-size: 10px;">Adjustment Factors <br>
                        (a)&nbsp;&nbsp;&nbsp;(b)&nbsp;&nbsp;&nbsp;(c)&nbsp;&nbsp;&nbsp;
                    </th>
                    <th width="25%"style="font-size: 10px;"> % Adjustment</th>
                    <th width="25%"style="font-size: 10px;">Adjustment Value</th>
                    <th width="25%"style="font-size: 10px;">Market value</th>
                </tr>
                @php
                $t_market_val=0;
                @endphp
                @foreach($RptPropertyAppraisals as $RptPropertyAppraisal)
                @php
                $mar_value=$RptPropertyAppraisal->rpa_base_market_value + $RptPropertyAppraisal->rpa_adjustment_value;
                $t_market_val=$t_market_val+$mar_value;
                @endphp
                <tr>
                    <td style="font-size: 10px;text-align: center;">
                        <table width="100%">
                            <tr>
                                <td style="font-size: 10px;text-align: left;border:0px;"></td>
                                <td style="font-size: 10px;text-align: right;border:0px;">{{$RptPropertyAppraisal->rpa_base_market_value}}</td>
                            </tr>
                        </table>
                    </td>
                    <td style="font-size: 10px;text-align: center;">
                    {{$RptPropertyAppraisal->rpa_adjustment_factor_a + $RptPropertyAppraisal->rpa_adjustment_factor_b + $RptPropertyAppraisal->rpa_adjustment_factor_c}} %
                    </td>
                    <td style="font-size: 10px;text-align: center;">
                      {{$RptPropertyAppraisal->rpa_adjustment_percent}}%
                    </td>
                    <td style="font-size: 10px;text-align: center;">
                        <table width="100%">
                            <tr>
                                <td style="font-size: 10px;text-align: left;border:0px;"></td>
                                <td style="font-size: 10px;text-align: right;border:0px;">{{$RptPropertyAppraisal->rpa_adjustment_value}}</td>
                            </tr>
                        </table>
                    </td>
                    <td class="pull-right" style="font-size: 10px;">{{$RptPropertyAppraisal->rpa_base_market_value + $RptPropertyAppraisal->rpa_adjustment_value}}</td>
                </tr> 
                @endforeach
                
                <tr>
                    <td style="font-size: 10px;text-align: center;">
                        <table width="100%">
                            <tr>
                                <td style="font-size: 10px;text-align: left;border:0px;"></td>
                                <td style="font-size: 10px;text-align: right;border:0px;"></td>
                            </tr>
                        </table>
                    </td>
                    <td style="font-size: 10px;text-align: center;">
                    </td>
                    <td style="font-size: 10px;text-align: center;">
                       
                    </td>
                    <td style="font-size: 10px;text-align: center;">
                        <table width="100%">
                            <tr>
                                <td style="font-size: 10px;text-align: left;border:0px;"></td>
                                <td style="font-size: 10px;text-align: right;border:0px;"></td>
                            </tr>
                        </table>
                    </td>
                    <td class="pull-right" style="font-size: 10px;"></td>
                </tr> 
                <tr>
                    <td style="font-size: 10px;text-align: center;border-right: 0px;"></td>
                    <td style="font-size: 10px;text-align: center;border-right: 0px;border-left: 0px;"><b>Total</b></td>
                    <td style="font-size: 10px;text-align: center;border-right: 0px;border-left: 0px;"><b>%</b></td>
                    <td style="font-size: 10px;text-align: center;border-right: 0px;border-left: 0px;">
                        <table width="100%">
                            <tr>
                                <td style="font-size: 10px;text-align: left;border:0px;">p</td>
                                <td style="font-size: 10px;text-align: right;border:0px;"></td>
                            </tr>
                        </table>
                    </td>
                    <td class="pull-right" style="font-size: 10px;border-left: 0px;">{{$t_market_val}}</td>
                </tr> 
               
            </table>
            
            <table width="100%" id="summary" class="noborder">
                <tr>
                     <td><p style="text-align: left;"><b>PROPERTY ASSESSMENT</b></p></td>
                </tr>
            </table>
                <table width="100%" id="summary">
                    <tr>
                        <th width="25%"style="font-size: 10px;">Actual Use</th>
                        <th width="25%"style="font-size: 10px;">Market Value</th>
                        <th width="25%"style="font-size: 10px;">Assessment level</th>
                        <th width="25%"style="font-size: 10px;">Assessed Value</th>
                    </tr>
                    @php
                    $t_assessed_val=0;
                    @endphp
                    @foreach($RptPropertyAppraisals as $RptPropertyAppraisal)
                    @php
                    $t_assessed_val=$t_assessed_val+$RptPropertyAppraisal->rpa_assessed_value;
                    @endphp
                    <tr>
                        <td style="font-size: 10px;text-align: center;"><p>{{$RptPropertyAppraisal->getPcClassDescriptionAttribute()}}</p></td>
                        <td style="font-size: 10px;text-align: center;">{{$RptPropertyAppraisal->rpa_base_market_value}}</td>
                        <td style="font-size: 10px;text-align: center;">{{$RptPropertyAppraisal->al_assessment_level}}%</td>
                        <td style="font-size: 10px;text-align: center;">{{$RptPropertyAppraisal->rpa_assessed_value}} </td>
                        
                    </tr>
                    @endforeach
                    <tr>
                        <td style="font-size: 10px;text-align: center;border-right: 0px;"><p>Total</p></td>
                        <td style="font-size: 10px;text-align: left;border-left: 0px;">p</td>
                        <td style="font-size: 10px;text-align: left;border-left: 1px;border-right: 0px;">TOTAL</td>
                        <td style="font-size: 10px;text-align: right;border-left: 0px;">{{$t_assessed_val}} </td>
                        
                    </tr>
                    <tr>
                        <td style="font-size: 10px;text-align: center !important;border-right: 0px;" colspan="2">
                            Taxable &nbsp;&nbsp;&nbsp;&nbsp; 
                             <input type="text" value="{{$rptProperty->rp_app_taxability == 1 ? 'X' : ''}}" style="width: 9px;text-align: center !important;">
                             &nbsp;&nbsp;&nbsp;&nbsp; Exempt &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                             <input type="text" value="{{$rptProperty->rp_app_taxability == 0 ? 'X' : ''}}" style="width: 9px;padding-left: 9px;">
                           
                        </td>
                        
                        <td style="font-size: 10px;text-align: right;border-left: 0px;"colspan="2">
                            <table>
                                <tr>
                                    <td style="font-size: 10px;text-align: left;border: 0px;" colspan="2">Effectivity of Assessment /Reassossment:</td>
                                    <td style="font-size: 10px;text-align: left;border: 0px;">
                                        <!-- &nbsp;&nbsp;&nbsp;&nbsp; 11 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2022
                                        <br>
                                       <b><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></b>
                                       <br>
                                       &nbsp;&nbsp;&nbsp;&nbsp; Qtr &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Year -->

                                       <table>
                                          <tr>
                                            <td style="border-top: 0px;border-left: 0px;border-right: 0px;">&nbsp;&nbsp;&nbsp;&nbsp; {{$rptProperty->rp_app_effective_quarter}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$rptProperty->rp_app_effective_year}}</td>
                                            </tr>
                                            <tr>
                                            <td style="border-bottom: 0px;border-left: 0px;border-right: 0px;">&nbsp;&nbsp;&nbsp;&nbsp; Qtr &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Year</td>
                                          </tr>
                                       </table>
                                    </td>
                                </tr>
                            </table>
                             
                        </td>
                        <!-- <td style="font-size: 10px;text-align: right;border-left: 0px;">Effectivity of Assessment /Reassossment:&nbsp;&nbsp;&nbsp;&nbsp;
                        </td> -->
                    </tr>
                    
                    
                   
                </table> 
                <table width="100%" id="summary" class="noborder">
                    <tr>
                         <td colspan="4"><p style="text-align: left;">APPRAISED BY:</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;" ><b> {{(isset($rptProperty->propertyApproval->apprisedBy->name))?$rptProperty->propertyApproval->apprisedBy->name:''}} </b><br> LAOO-IV
                       </td>
                       <td>
                        <table >
                            <tr>
                              <td style="border-top: 0px;border-left: 0px;border-right: 0px;text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp; {{ date('d-M-Y', strtotime((isset($rptProperty->propertyApproval->rp_app_appraised_date))?$rptProperty->propertyApproval->rp_app_appraised_date:''))}}</td>
                              </tr>
                              <tr >
                              <td style="border-bottom: 0px;border-left: 0px;border-right: 0px;text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp; Date</td>
                            </tr>
                         </table>
                       </td>
                       <td >
                       </td>
                       <td >
                       </td>
                       <td >
                       </td>
                   </tr>
                   <tr>
                    <td colspan="2">RECOMMENDING APPROVAL:
                   </td>
                   <td colspan="2" style="text-align: left;">APPROVED:
                   </td>
               </tr>
               <tr>
                <td style="text-align: center;"><b> {{(isset($rptProperty->propertyApproval->recommendBy->name))?$rptProperty->propertyApproval->recommendBy->name:''}} </b><br> LAOO-IV
               </td>
               <td style="text-align: center;">  
                     <!-- &nbsp;&nbsp;&nbsp;&nbsp; 26/11/2019
                    <br>
                    <b><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></b>
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp; Date -->
                    <table >
                        <tr>
                          <td style="border-top: 0px;border-left: 0px;border-right: 0px;text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp; {{ date('d-M-Y', strtotime((isset($rptProperty->propertyApproval->rp_app_recommend_date))?$rptProperty->propertyApproval->rp_app_recommend_date:''))}}</td>
                          </tr>
                          <tr >
                          <td style="border-bottom: 0px;border-left: 0px;border-right: 0px;text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp; Date</td>
                        </tr>
                     </table>
               </td>
               <td style="text-align: center;"><b> {{(isset($rptProperty->propertyApproval->approveBy->name))?$rptProperty->propertyApproval->approveBy->name:''}} </b><br> City Assessor
                </td>
                <td style="text-align: center;">
                    <table >
                        <tr>
                          <td style="border-top: 0px;border-left: 0px;border-right: 0px;text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ date('d-M-Y', strtotime((isset($rptProperty->propertyApproval->rp_app_approved_date))?$rptProperty->propertyApproval->rp_app_approved_date:''))}}</td>
                          </tr>
                          <tr >
                          <td style="border-bottom: 0px;border-left: 0px;border-right: 0px;text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp; Date</td>
                        </tr>
                     </table>

                  

                </td>
           </tr>
                </table>
                <table width="100%" id="summary">
                    <tr>
                        <td width="25%"style="font-size: 10px; padding-bottom: 56px;">MEMORANDA:<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                        
                            {{$rptProperty->rp_app_memoranda}}
                        </td>
                        
                    </tr>
                </table>
                <table width="100%" id="summary" class="noborder">
                    <tr>
                         <td><p style="text-align: left;">Date of Entry in the journal of Assessment Transaction:   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <u>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;
                         </u></p></td>
                    </tr>
                    <tr>
                        <td><p style="text-align: left;">
                        <b>RECORD OF SUPERSEDED ASSESSMENT</b>
                        </td>
                   </tr>
                </table>
                <table width="100%" id="summary">
                    <tr>
                        <td width="50%" style="text-align: center;border-right: 0px;" colspan="2">                        
                            Reference
                        </td>
                        <td width="50%" style="text-align: center;border-left: 0px;"colspan="2">Record of Superseded Assessment
                           
                        </td>
                        
                    </tr>
                    <tr>
                        <td width="25%" style="text-align: left;border-right: 0px;" >                        
                           PIN
                        </td>
                        <td width="25%" style="text-align: center;border-left: 0px;border-right: 0px;">
                        {{$prop_index_no}}
                        </td>
                        <td width="25%" style="text-align: center;border-right: 0px;border-left: 0px;" >                        
                           
                        </td>
                        <td width="25%" style="text-align: center;border-left: 0px;">
                           
                        </td>
                        
                    </tr>
                    <tr>
                        <td width="25%" style="text-align: left;border-right: 0px;" >                        
                           ARP No.:
                        </td>
                        <td width="25%" style="text-align: center;border-left: 0px;border-right: 0px;">
                            {{($rptProperty->dist_code != null ?$rptProperty->dist_code : "").($rptProperty->brgy_no  != null ? $rptProperty->brgy_no  : "").($rptProperty->rp_td_no != null ? "-".$rptProperty->rp_td_no : "")}}
                        </td>
                        <td width="25%" style="text-align: center;border-left: 0px;border-right: 0px;" >                        
                           
                        </td>
                        <td width="25%" style="text-align: center;border-left: 0px;">
                           
                        </td>
                        
                    </tr>
                    <tr>
                        <td width="25%" style="text-align: left;border-right: 0px;" >                        
                           TD No.:
                        </td>
                        <td width="25%" style="text-align: center;border-left: 0px;border-right: 0px;">
                        {{$rptProperty->td_no}}
                        </td>
                        <td width="25%" style="text-align: center;border-left: 0px;border-right: 0px;" >                        
                           
                        </td>
                        <td width="25%" style="text-align: center;border-left: 0px;">
                           
                        </td>
                        
                    </tr>
                    <tr>
                        <td width="25%" style="text-align: left;border-right: 0px;" >                        
                           AR/TR Page No.:
                        </td>
                        <td width="25%" style="text-align: center;border-left: 0px;border-right: 0px;">
                           
                        </td>
                        <td width="25%" style="text-align: center;border-left: 0px;border-right: 0px;" >                        
                           
                        </td>
                        <td width="25%" style="text-align: center;border-left: 0px;">
                           
                        </td>
                        
                    </tr>
                    <tr>
                        <td width="25%" style="text-align: left;border-right: 0px;" >                        
                           Total Assessed Value:
                        </td>
                        <td width="25%" style="text-align: center;border-left: 0px;border-right: 0px;">
                        {{$rptProperty->rpb_assessed_value}} 
                        </td>
                        <td width="25%" style="text-align: center;border-left: 0px;border-right: 0px;" >                        
                           
                        </td>
                        <td width="25%" style="text-align: center;border-left: 0px;">
                           
                        </td>
                        
                    </tr>
                    <tr>
                        <td width="25%" style="text-align: left;border-right: 0px;" >                        
                            Previous Owner 
                        </td>
                        <td width="75%" style="text-align: center;border-left: 0px;font-size: 12px;" colspan="3">
                        @if($rptProperty->updatecode->uc_code != "DC")
                            RIVSON ORO VENTURE HOLDINGS CORP, REP. BY MS. MARLENE R. LAMATA                             
                        @endif
                        </td>
                        
                        
                    </tr>
                    <tr>
                        <td width="25%" style="text-align: left;border-right: 0px;" >                        
                            Effectivity of Assessment.
                        </td>
                        <td width="25%" style="text-align: center;border-left: 0px;border-right: 0px;">
                           {{$rptProperty->rp_app_effective_year}}
                        </td>
                        <td width="25%" style="text-align: center;border-left: 0px;border-right: 0px;" >                        
                           
                        </td>
                        <td width="25%" style="text-align: center;border-left: 0px;">
                           
                        </td>
                        
                    </tr>
                    <tr>
                        <td width="50%" style="text-align: center;border-right: 0px;" colspan="2">                        
                            <table width="100%">
                                <tr>
                                  <td style="text-align: center;border: 0px;">Recording Personal:</td>  
                                </tr>
                                <tr>
                                    <td style="text-align: center;border: 0px;">rbondoc</td>  
                                </tr>
                                <tr>
                                    <td width="50%" style="text-align: center;border-left: 0px;border-right: 0px;border-bottom: 0px;width: 50px;"></td>  
                                </tr>
                            </table> 
                        </td>
                        
                        <td width="50%" style="text-align: center;border-left: 0px;" colspan="2">
                          
                            <table width="100%">
                                <tr>
                                <td style="text-align: center;border: 0px;">Date:</td>  
                                </tr>
                                <tr>
                                    <td style="text-align: center;border: 0px;">17/07/2017</td>  
                                </tr>
                                <tr>
                                    <td width="50%" style="text-align: center;border-left: 0px;border-right: 0px;border-bottom: 0px;width: 50px;"></td>  
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
    