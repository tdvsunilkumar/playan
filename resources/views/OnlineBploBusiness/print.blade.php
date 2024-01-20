
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

#address { width: 250px; height: 150px; float: left;font-weight: bold; }
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
#meta td.meta-head { text-align: left; background: #eee;font-weight: bold; }
#meta td textarea { width: 100%; height: 20px; text-align: right; }
textarea:hover, textarea:focus, #items td.total-value textarea:hover, #items td.total-value textarea:focus, .delete:hover { background-color:#EEFF88; }

.delete-wpr { position: relative; }
.delete { display: block; color: #000; text-decoration: none; position: absolute; background: #EEEEEE; font-weight: bold; padding: 0px 3px; border: 1px solid; top: -6px; left: -22px; font-family: Verdana; font-size: 12px; }
.footer-contain {text-align: center;font-size: 35px; font-weight: 700;color: #1f4888;}
.font_change{white-space: pre !important;font-size: 12px;margin-left: 10px;}
    </style>

    <title>Application Form</title>
  </head>

<body>
<table>
  <tr>
    <td style="border:0px;">
<table width="100%" id="summary">
        <tr>
            <th  colspan="2" style="text-align: center;border:0px;"><p style="text-align: center;text-decoration: uppercase;font-size: 20px;">UNIFIED APPLICATION FORM FOR RENEWAL OF BUSINESS PERMIT</p></th>
           
        </tr>
        <tr>
            <td colspan="2" style="border:0px;">
                <table width="100%" id="summary" style="border:0px solid black;">
                    <tr>
                        <td width="30%" style="text-align:left;border:0px">
                            <table width="100%" style="border:1px;">
                                <tr>
                                    <td width="50%">
                                        
                                    </td>
                                    <td width="50%">
                                        Payment
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">
                                        <input type="text" value="{{$bplo_business->app_code == '1' ? 'X' : ''}}" style="width: 15px;padding-left: 9px;">
                                        <label for="new_form">New</label>
                                    </td>
                                    <td width="50%">
                                        <input type="text" value="{{$bplo_business->pm_id == '1' ? 'X' : ''}}" style="width: 15px;padding-left: 9px;">
                                        <label for="annually">Annually</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">
                                        <input type="text" value="{{$bplo_business->app_code == '2' ? 'X' : ''}}" style="width: 15px;padding-left: 9px;">
                                        <label for="new_form">RENEWAL</label>
                                    </td>
                                    <td width="50%">
                                        <input type="text" value="{{$bplo_business->pm_id == '2' ? 'X' : ''}}" style="width: 15px;padding-left: 9px;">
                                        <label for="annually">Bi-Annually</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">
                                        <input type="text" value="{{$bplo_business->app_code == '3' ? 'X' : ''}}" style="width: 15px;padding-left: 9px;">
                                        <label for="new_form">ADDITONAL</label>
                                    </td>
                                    <td width="50%">
                                        <input type="text" value="{{$bplo_business->pm_id == '3' ? 'X' : ''}}" style="width: 15px;padding-left: 9px;">
                                        <label for="annually">Quarterly</label>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="30%" style="text-align:left;border:0px">
                        </td>
                        <td width="30%" style="text-align:left;border:0px">
                            <table width="100%" id="summary" style="border:0px solid black;">
                                <tr>
                                    <td width="50%" style="text-align:left;border:0px">
                                        Date of Receipt: 
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" style="text-align:left;border:0px">
                                        Tracking Number: {{$bplo_business->busn_tracking_no}}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" style="text-align:left;border:0px">
                                        Business ID Number: {{$bplo_business->busns_id_no}}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p style="font-weight:600;font-size: 20px;text-decoration: uppercase;">BUSINESS INFORMATION AND REGISTRATION</p>
                <table width="100%" id="summary" style="border:0px solid black;">
                    <tr>
                      <td  colspan="2" style="text-align:left;border:0px solid black; padding-right:30px;"><p style="font-size: 16px;font-weight: bold;">Please choose One :</p></td>
                    </tr>
                    <tr>
                          <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">
                           <p style="font-size: 16px;font-weight: bold;"><input type="checkbox"> Single Proprietorship</p></td>
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">
                           <p style="font-size: 16px;font-weight: bold;"><input type="checkbox">Male</p></td>
                            <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">
                           <p style="font-size: 16px;font-weight: bold;"><input type="checkbox">Female</p></td>       
                     </tr>
                      <tr>
                          <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">
                           <p style="font-size: 16px;font-weight: bold;"><input type="checkbox">One Person Corporation</p></td>
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">
                           <p style="font-size: 16px;font-weight: bold;"><input type="checkbox">Male <span class="textborder">MUNCIPALITY</span></p></td>
                            <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">
                           <p style="font-size: 16px;font-weight: bold;"><input type="checkbox">Female <span class="textborder">MUNCIPALITY</span></p></td>
                     </tr>
                       <tr>
                          <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">
                           <p style="font-size: 16px;font-weight: bold;"><input type="checkbox">Partnership</p></td>
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">
                           <p style="font-size: 16px;font-weight: bold;"><input type="checkbox">Corporation</p></td>
                            <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">
                           <p style="font-size: 16px;font-weight: bold;"><input type="checkbox">Corperative</p></td>
                     </tr>
                </table>
            </td>
           
        </tr>
        <tr>
            <td colspan="2">
                <table width="100%" id="summary" style="border:0px solid black;">
                    <tr>
                      <td width="50%" style="text-align:left;border:0px solid black; padding-right:30px;">DTI/SEC/CDA Registration Number:</td>
                       <td  width="50%" style="text-align:right;border:0px solid black; padding-right:30px;">Tax Identification Number:</td>
                    </tr>
                    <tr>
                      <th width="50%" style="text-align:left;border:0px solid black; padding-right:30px;"><p style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_registration_no}}</p></th>
                       <th  width="50%" style="text-align:right;border:0px solid black; padding-right:30px;"><p style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_tin_no}}</p></th>
                    </tr>
                </table>
            </td>
        </tr>     
        <tr>
            <td colspan="2">
                <table width="100%" id="summary" style="border:0px solid black;">
                    <tr>
                      <td width="50%" style="text-align:left;border:0px solid black; padding-right:30px;">Business Name:</td>
                    </tr>
                    <tr>
                      <th width="50%" style="text-align:left;border:0px solid black; padding-right:30px;"><p style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_name}}</p></th>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table width="100%" id="summary" style="border:0px solid black;">
                    <tr>
                      <td width="50%" style="text-align:left;border:0px solid black; padding-right:30px;">Trade Name/ Franchise(if applicable): <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_trade_name}}</span></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table width="100%" id="summary" style="border:0px solid black;">
                    <tr>
                      <td  colspan="2" style="text-align:left;border:0px solid black; padding-right:30px;"><p style="font-size: 16px;font-weight: bold;">Main Office Address :</p></td>
                    </tr>
                    <tr>
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">House/Bldg No. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_office_main_building_no}}</span></td> 
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">Name of Buidling. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_office_main_building_name}}</span></td>
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">Lot No. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_office_main_add_lot_no}}</span></td>
                    </tr>
                    <tr>
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;"> Block No. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_office_main_add_block_no}}</span></td> 
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">Street No. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_office_main_add_street_name}}</span></td>
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">Barangay. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->mainBarangay->brgy_name}}</span></td>
                    </tr>
                    <tr>
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">Subdivision. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_office_main_add_subdivision}}</span></td> 
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">City/Municipality. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->mainBarangay->municipality->mun_desc}}</span></td>
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">Province. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->mainBarangay->province->prov_desc}}</span></td>
                    </tr>
                    <tr>
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">Zip Code. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->mainBarangay->municipality->mun_zip_code}}</span></td> 
                    </tr>
                     
                </table>
            </td>
           
        </tr>        
        <tr>
            <td colspan="2">
            <table width="100%" id="summary" style="border:0px solid black;">
                    <tr>
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">Telephone No:</td> 
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">Mobile No: </td>
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">Email Address: </td>
                    </tr>
                    <tr>
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;"><span style="font-size: 16px;font-weight: bold;">{{$bplo_business->client->p_telephone_no}}</span></td> 
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;"><span style="font-size: 16px;font-weight: bold;">{{$bplo_business->client->p_mobile_no}}</span></td>
                           <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;"><span style="font-size: 16px;font-weight: bold;">{{$bplo_business->client->p_email_address}}</span></td>
                     </tr>        
            </table>
            </td>
        </tr> 
        <tr>
            <td colspan="2">
            <table width="100%" id="summary" style="border:0px solid black;">
                    <tr>
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;">(For Sole Proprietorship)</td> 
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;">Surname </td>
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;">Given Name: </td>
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;">Middle Name: </td>
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;">Suffix: </td>
                    </tr>
                    <tr>
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;"> Name of Owner:</td> 
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;"><span style="font-size: 16px;font-weight: bold;">{{$bplo_business->client->rpo_custom_last_name}}</span></td>
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;"><span style="font-size: 16px;font-weight: bold;">{{$bplo_business->client->rpo_first_name}}</span></td>
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;"><span style="font-size: 16px;font-weight: bold;">{{$bplo_business->client->rpo_middle_name}}</span></td>
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;"><span style="font-size: 16px;font-weight: bold;">{{$bplo_business->client->suffix}}</span></td>
                     </tr>        
            </table>
            </td>
        </tr> 
        <tr>
            <td colspan="2">
            <table width="100%" id="summary" style="border:0px solid black;">
                    <tr>
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;">(For Corporations/Cooperative/Partnership)</td> 
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;">Surname </td>
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;">Given Name: </td>
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;">Middle Name: </td>
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;">Suffix: </td>
                    </tr>
                    <tr>
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;">Name of President/Office in Charge:</td> 
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;"><span style="font-size: 16px;font-weight: bold;"></span></td>
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;"><span style="font-size: 16px;font-weight: bold;"></span></td>
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;"><span style="font-size: 16px;font-weight: bold;"></span></td>
                           <td width="20%" style="text-align:left;border:0px solid black; padding-right:30px;"><span style="font-size: 16px;font-weight: bold;"></span></td>
                     </tr>        
            </table>
            </td>
        </tr> 
        <tr>
                  <td colspan="2">
                      <table width="100%" id="summary" style="border:0px solid black;">
                          <tr>
                            <td width="100%" style="text-align:left;border:0px solid black; padding-right:30px;">Trade For Corporation : <span style="font-size: 16px;font-weight: bold;"><input type="checkbox"> Single Proprietorship &nbsp;<input type="checkbox"> Single Proprietorship</span></td>
                          </tr>
                      </table>
                  </td>
        </tr>
              <tr>
                  <td colspan="2">
                       <p style="font-weight:600;font-size: 20px;text-decoration: uppercase;">B. BUSINESS OPERATION</p>
                  </td>
              </tr>
              <tr>
                  <td colspan="2">
                  <table width="100%" id="summary" style="border:0px solid black;">
                          <tr>
                                 <td width="25%" style="text-align:left;border:0px solid black; padding-right:30px;">Business Area (In sq. m). <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_bldg_area}}</span></td> 
                                 <td width="25%" style="text-align:left;border:0px solid black; padding-right:30px;">Total No. of Employees in Establishment </td>
                                 <td width="25%" style="text-align:left;border:0px solid black; padding-right:30px;">No.Of Employees Residing Within </td>
                                 <td width="25%" style="text-align:left;border:0px solid black; padding-right:30px;">No. of Delivery Vehicles(if applicable) </td>
                                 
                          </tr>
                          <tr>
                                 <td width="25%" style="text-align:left;border:0px solid black; padding-right:30px;"> Floor Area(in sq. m). <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_bldg_total_floor_area}}</span></td> 
                                 <td width="25%" style="text-align:left;border:0px solid black; padding-right:30px;">
                                  <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_employee_no_male}}</span>&nbsp;Male
                                  <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_employee_no_female}}</span>&nbsp;Female</td>
                                 <td width="25%" style="text-align:left;border:0px solid black; padding-right:30px;"><span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_employee_no_lgu}}</span></td>
                                 <td width="25%" style="text-align:left;border:0px solid black; padding-right:30px;">
                                  <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_vehicle_no_van_truck}}</span>&nbsp;Van/Truck
                                  <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_vehicle_no_motorcycle}}</span>&nbsp;Motorcycle</td>
                           </tr>        
                  </table>
                  </td>
              </tr>
               <tr>
                  <td colspan="2">
                      <table width="100%" id="summary" style="border:0px solid black;">
                        <tr>
                            <td  colspan="2" style="text-align:left;border:0px solid black; padding-right:30px;"><p style="font-size: 16px;font-weight: bold;">Business Location Address :</p></td>
                        </tr>
                        <tr>
                            <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">House/Bldg No. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_office_main_building_no}}</span></td> 
                            <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">Name of Buidling. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_office_main_building_name}}</span></td>
                            <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">Lot No. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_office_main_add_lot_no}}</span></td>
                        </tr>
                        <tr>
                            <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;"> Block No. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_office_main_add_block_no}}</span></td> 
                            <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">Street No. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_office_main_add_street_name}}</span></td>
                            <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">Barangay. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busnBarangay->brgy_name}}</span></td>
                        </tr>
                        <tr>
                            <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">Subdivision. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busn_office_main_add_subdivision}}</span></td> 
                            <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">City/Municipality. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busnBarangay->municipality->mun_desc}}</span></td>
                            <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">Province. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busnBarangay->province->prov_desc}}</span></td>
                        </tr>
                        <tr>
                            <td width="33%" style="text-align:left;border:0px solid black; padding-right:30px;">Zip Code. <span style="font-size: 16px;font-weight: bold;">{{$bplo_business->busnBarangay->municipality->mun_zip_code}}</span></td> 
                        </tr>
                           
                      </table>
                  </td>
                 
              </tr>    
              <tr>
                  <td colspan="2">
                      <table width="100%" id="summary" style="border:0px solid black;">
                          <tr>
                            <td width="30%" style="text-align:left;border:0px solid black; padding-right:30px;">Owned ? <span style="font-size: 16px;font-weight: bold;"><input type="text" value="{{$bplo_business->busn_bldg_is_owned == '1' ? 'X' : ''}}" style="width: 15px;padding-left: 9px;"> Yes &nbsp;<input type="text" value="{{$bplo_business->busn_bldg_is_owned == '0' ? 'X' : ''}}" style="width: 15px;padding-left: 9px;"> No</span></td>
                            <td width="70%" style="text-align:left;border:0px solid black; padding-right:30px;"> If Yes, Tax Declartion No.&nbsp;<span style="font-size: 16px;font-weight: bold;"> {{$bplo_business->busn_bldg_tax_declaration_no}}</span> or Property Identification No. &nbsp;<span style="font-size: 16px;font-weight: bold;"> {{$bplo_business->busn_bldg_property_index_no}}</span></td>
                          </tr>
                      </table>
                  </td>
              </tr>
              <tr>
                  <td colspan="2">
                      <table width="100%" id="summary" style="border:0px solid black;">
                          <tr>
                            <td width="50%" style="text-align:left;border:0px solid black; padding-right:30px;">Total Capitalization(PH):</td>
                          </tr>
                          <tr>
                            <th width="50%" style="text-align:left;border:0px solid black; padding-right:30px;"><p style="font-size: 16px;font-weight: bold;"></p></th>
                          </tr>
                      </table>
                  </td>
              </tr>
              <tr>
                  <td colspan="2">
                      <table width="100%" id="summary" style="border:0px solid black;">
                          <tr>
                            <td width="100%" style="text-align:left;border:0px solid black; padding-right:30px;">Do you have tax incentives from any Government Entity?&nbsp;&nbsp; <span style="font-size: 16px;font-weight: bold;"><input type="checkbox"> Yes&nbsp;&nbsp;(Please attach a copy of your Certificate)  &nbsp;<input type="checkbox"> No</span></td>
                          </tr>
                      </table>
                  </td>
              </tr>
               <tr>
                  <td colspan="2">
                      <p style="font-weight:600;font-size: 20px;text-decoration: uppercase;">A. BUSINESS INFORMATION AND REGISTRATION</p>
                      <table width="100%" id="summary" style="border:0px solid black;">
                          <tr>
                                <td  width="20%" style="text-align:left;border:0px solid black; padding-right:30px;"><p style="font-size: 12px;font-weight: bold;">Business Activity(Please check one) :</p></td>
                                <td width="13%" style="text-align:left;border:0px solid black; padding-right:30px;">
                                 <p style="font-size: 12px;font-weight: bold;"><input type="checkbox">Main Office</p></td>
                                 <td width="13%" style="text-align:left;border:0px solid black; padding-right:30px;">
                                 <p style="font-size: 12px;font-weight: bold;"><input type="checkbox">Branch Office</p></td>
                                  <td width="13%" style="text-align:left;border:0px solid black; padding-right:30px;">
                                 <p style="font-size: 12px;font-weight: bold;"><input type="checkbox">Admin Office Only</p></td>  
                                 <td width="13%" style="text-align:left;border:0px solid black; padding-right:30px;">
                                 <p style="font-size: 12px;font-weight: bold;"><input type="checkbox">Warehouse</p></td>   
                                 <td width="13%" style="text-align:left;border:0px solid black; padding-right:30px;">
                                 <p style="font-size: 12px;font-weight: bold;"><input type="checkbox">Other Pls. Speciy</p></td>        
                           </tr>
                      </table>
                  </td>
                 
              </tr>
              <tr>
                  <td colspan="2">
                  <table width="100%" id="summary" style="border:0px solid black;">
                          <tr>
                                 <td width="20%" style="text-align:left;border:1px solid black; padding-right:30px;">Line of Business</td> 
                                 <td width="20%" style="text-align:left;border:1px solid black; padding-right:30px;">Philippine standard Industrial Code(If Available) </td>
                                 <td width="20%" style="text-align:left;border:1px solid black; padding-right:30px;">Given product/Services </td>
                                 <td width="20%" style="text-align:left;border:1px solid black; padding-right:30px;">No. of Units </td>
                                 <td width="20%" style="text-align:left;border:1px solid black; padding-right:30px;">Last Year's Gross Sales/Receipts </td>
                          </tr>
                          @foreach($bplo_business_plan as $plan)
                          <tr>
                                 <td width="20%" style="text-align:left;border:1px solid black; padding-right:30px;"> <span style="font-size: 16px;font-weight: bold;">{{$plan->subclass_description}}</span></td> 
                                 <td width="20%" style="text-align:left;border:1px solid black; padding-right:30px;"><span style="font-size: 16px;font-weight: bold;">{{$plan->subclass_code}}</span></td>
                                 <td width="20%" style="text-align:left;border:1px solid black; padding-right:30px;"><span style="font-size: 16px;font-weight: bold;"></span></td>
                                 <td width="20%" style="text-align:left;border:1px solid black; padding-right:30px;"><span style="font-size: 16px;font-weight: bold;">{{$plan->busp_no_units}}</span></td>
                                 <td width="20%" style="text-align:left;border:1px solid black; padding-right:30px;"><span style="font-size: 16px;font-weight: bold;"></span></td>
                           </tr>  
                           @endforeach      
                  </table>
                  </td>
              </tr>
</table> 
<table width="100%" id="summary">
        <tr>
            <td colspan="2"><p><span style="font-weight:600;font-size: 16px;text-decoration: uppercase;">I DECLARE UNDER PENALTY OF PERJURY</span> that all information in this application are true and correct based on my Personal knowledge and authentic records submitted to the <span style="font-weight:600;font-size: 16px;text-decoration: uppercase;">City of Palayan</span>. Any false or misleading information supplied,or production of fake/falsified doucments shall be grounds for appropriate legal action against me and automatically revokes the permit. I hereby agree that all personal data(as defined under the Data privacy Law of 2012 and its Implementing Rules and Regulations) and account transaction information or records with the city/Municipal Government may be processed, profiled or shared to requesting parties or for the purpose of any court,legal process,examination,inquiry and audit or investigation of any authority.<br><br></p></td>
        </tr>  
        <tr>
            <td width="50%"></td><td width="50%"></p></td>
        </tr>
         <tr style="height:50px;">
            <td width="50%"><p></p></td><td width="50%" style="text-align:center;font-weight:600;font-size: 20px;text-decoration: uppercase !important;"><p>PINEDA,CAROLINE MAGNO</p></td>
        </tr>
        <tr style="height:50px;">
            <td width="50%"><p></p></td><td width="50%" style="text-align:center;font-weight:600;font-size: 20px;text-decoration: uppercase !important;"><p>SIGNSTURE OF APPLICANT/OWNER OVER PRINTED NAME</p></td>
        </tr>
        <tr style="height:50px;">
            <td width="50%"><p></p></td><td width="50%" style="text-align:center;font-weight:600;font-size: 20px;text-decoration: uppercase !important;"><p>DESIGNSTION/POSITION/TITLE</p></td>
        </tr>
  </table>

    </td>
  </tr>
</table>
 

</body>
</html>




