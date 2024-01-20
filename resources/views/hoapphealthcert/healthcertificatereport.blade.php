
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
.chklabel{margin-bottom: 5px; margin-left:5px !important;}
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

    <title>Health Certificate Report</title>
  </head>
<body>
<table>
  <tr>
    <td style="border: 1px solid black;">
        <table width="100%" id="summary">
                <tr>
                   <td width="50%" style="text-align:center;border:0px solid black;">
                    <br><img src="{{$logo}}" style="max-width:100px;max-height:100px; padding-bottom:10px;">
                    <p style="font-size: 17px;font-weight: 700; padding-top:10px;">CITY OF PALAYAN</p><p>CAPITAL OF NUEVA ECUA</p>
                    </td>
                     <td width="50%" style="text-align:right;border:0px solid black;">
                      <p style="font-size: 17px;font-weight: 700; padding-top:10px;">IMPORTANT</p>
                      <p>This Health Certificate is non-transferable always wear your certificate in the upper left side front portion of your garment while working.</p>
                      <p>Valid only until the next date of submission as indicated below.</p>
                    </td>
                </tr>
         </table>
         <table width="100%" id="summary">       
                 <tr>
                   <td width="50%" style="text-align:right;border:0px solid black;"><br>
                    <p style="font-size: 12px;font-weight: 600; padding-top:10px;">Reg No:<span style="text-decoration: underline;">{{$data->hahc_registration_no}}</span></p>
                    </td><td width="25%" style="text-align:center;border:0px solid black;"> {{date("F d, Y",strtotime($data->hahc_issuance_date))}}<p>Date of Issurance</p></td>
                    <td width="25%" style="text-align:center;border:0px solid black;">{{date("F d, Y",strtotime($data->hahc_expired_date))}}<p>Date of Expiration</p></td>
                  </tr>
        </table> 
        
        <table width="100%" id="summary">       
                 <tr>
                   <td width="50%" style="border:0px solid black;">
                    <table width="100%" id="summary">
                      <tr>
                        <td style="text-align: right;border:0px solid black;"><p style="font-size: 40px;font-weight: 600; padding-top:10px;">HEALTH <br>CERTIFICATE</p></td>
                      </tr>
                      <tr>
                        <td style="border:0px solid black;"><p style="font-size: 18px;">Pursuant to the privision of P.D, 522, P.D. 856 and city Ordinance NO 22-A, 233 this certificate is issued for</p>
                        <p style="text-align: left !important;font-size: 20px;text-transform: uppercase;">Name: <span style="text-decoration: underline;">{{$data->cit_last_name}} {{$data->cit_first_name}} {{$data->cit_middle_name}}</span></p><br>
                        <p style="text-align: left !important;font-size: 20px;text-transform: uppercase;">Occupation: <span style="text-decoration: underline;"></span></p>
                        <p style="text-align: left !important;font-size: 20px;text-transform: uppercase;">Age: <span style="text-decoration: underline;">{{$data->cit_gender}}</span> SEX:<span style="text-decoration: underline;">@if($data->cit_gender == 1) Male @else Female @endif</span><br>NATIONALITY:<span style="text-decoration: underline;">Filipino</span></p>
                        <p style="text-align: left !important;font-size: 20px;text-transform: uppercase;">Place of Work: <span style="text-decoration: underline;">{{$data->hahc_place_of_work}}</span> </p>
                     </td>
                      </tr>
                    </table>
                     <table width="100%" id="summary">       
                             <tr>
                              <td style="border:1px solid black;width:200px;height:200px" rowspan="3" width="50%"><div ></div></td>
                              <td style="border:0px solid black; text-align: center;" ><p><img src="{{$sign}}" style="max-width:50px;max-height:50px; padding-bottom:10px;"></p><p>Signature</p></td>
                              </tr>
                              <tr>
                              <td style="border:0px solid black;text-align: center;"><p><img src="{{$sign}}" style="max-width:50px;max-height:50px; padding-bottom:10px;"></p></p><p>City Health Officer 1</p></td>
                              </tr>
                              <tr>
                              <td style="border:0px solid black;text-align: center;"><p><img src="{{$sign}}" style="max-width:50px;max-height:50px; padding-bottom:10px;"></p></p><p>City Health Officer 2</p></td>
                              </tr>
                    </table>
                    </td><td width="50%" style="text-align:center;border:0px solid black;"> 
                     <table width="100%" id="summary">
                      <tr>
                        <td style="text-align: center;border:0px solid black;"><p style="font-size: 20px;font-weight: 600; padding-top:10px;">IMMUNZATION</p></td>
                      </tr>
                    </table>
                    <table width="100%" id="summary">
                      <thead>
                        <tr><td>Date</td><td>Kind</td><td>Results</td></tr>
                      </thead>
                      @foreach($table1data as $val)
                      <tr>
                        <td><p>{{date("F d, Y",strtotime($val->hahcr_exam_date))}}</p></td>
                        <td>{{$val->req_code_abbreviation}}</td>
                        <td>{{$val->hahcr_exam_result}}</td>
                      </tr>
                      @endforeach
                    </table>  
                     <table width="100%" id="summary">
                      <tr>
                        <td style="text-align: center;border:0px solid black;"><p style="font-size: 20px;font-weight: 600; padding-top:10px;">X-Ray</p></td>
                      </tr>
                    </table>
                    <table width="100%" id="summary">
                      <thead>
                        <tr><td>Date</td><td>Kind</td><td>Results</td></tr>
                      </thead>
                      @foreach($table2data as $val)
                      <tr>
                        <td><p>{{date("F d, Y",strtotime($val->hahcr_exam_date))}}</p></td>
                        <td>{{$val->req_code_abbreviation}}</td>
                        <td>{{$val->hahcr_exam_result}}</td>
                      </tr>
                      @endforeach
                    </table>  
                    <table width="100%" id="summary">
                      <tr>
                        <td style="text-align: center;border:0px solid black;"><p style="font-size: 20px;font-weight: 600; padding-top:10px;">STOOL AND OTHER EXAM RQRD</p></td>
                      </tr>
                    </table>
                    <table width="100%" id="summary">
                      <thead>
                        <tr><td>Date</td><td>Kind</td><td>Results</td></tr>
                      </thead>
                      @foreach($table3data as $val)
                      <tr>
                        <td><p>{{date("F d, Y",strtotime($val->hahcr_exam_date))}}</p></td>
                        <td>{{$val->req_code_abbreviation}}</td>
                        <td>{{$val->hahcr_exam_result}}</td>
                      </tr>
                      @endforeach
                    </table>  
                  </td>
                  </tr>
        </table>
    </td>
  </tr>
</table>
 

</body>
</html>



