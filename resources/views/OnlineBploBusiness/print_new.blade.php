<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <style type="text/css">
            @import url('https://fonts.googleapis.com/css?family=Fjalla+One|Montserrat:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
            @page {margin-top: 12mm; margin-bottom: 12mm; margin-left: 5mm; margin-right: 5mm; margin-header: 0mm;margin-footer: 0mm;}
            * {margin: 0; padding: 0; }
            body {font-family: 'Montserrat', sans-serif; font-size: 10px; }
            table td, table th {border: 1px solid black; padding: 5px; }
            textarea {border: 0; font-size: 14px; font-family: 'Montserrat', sans-serif; overflow: hidden; resize: none; }
            table {border-collapse: collapse; }
            p{margin: 0px}
            input{border: none;}
            .clear{clear: both;}
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
        <title>Application Form</title>
    </head>
    <body>
        <table width="100%">
            <tr>
                <th style="text-align: center;border:0px;">
                    <p style="font-size: 15px;">UNIFIED APPLICATION FORM FOR RENEWAL OF BUSINESS PERMIT</p>
                </th>
            </tr>
        </table>

        <div style="width: 35%; float:left;">
            <table width="100%" style="border:1px;">
                <tr>
                    <td style="width:50%; padding-top: 2px; padding-bottom: 2px;"></td>
                    <td style="width:50%; padding-top: 2px; padding-bottom: 2px; text-align:center;">Payment</td>
                </tr>
                <tr>
                    <td style="width:50%; padding:0px;">
                        <img src="assets/storage/uploads/logo/{{ $bplo_business->app_code == 1 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; padding-bottom: 5px; vertical-align: middle;">
                        <label for="new_form">NEW</label>
                    </td>
                    <td style="width:50%; padding:0px;">
                        <img src="assets/storage/uploads/logo/{{ $bplo_business->pm_id == 1 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; padding-bottom: 5px; vertical-align: middle;">
                        <label for="annually">Annually</label>
                    </td>
                </tr>
                <tr>
                    <td style="width:50%; padding:0px;">
                        <img src="assets/storage/uploads/logo/{{ $bplo_business->app_code == 2 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; padding-bottom: 5px; vertical-align: middle;">
                        <label for="new_form">RENEWAL</label>
                    </td>
                    <td style="width:50%; padding:0px;">
                        <img src="assets/storage/uploads/logo/{{ $bplo_business->pm_id == 2 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; padding-bottom: 5px; vertical-align: middle;">
                        <label for="annually">Bi-Annually</label>
                    </td>
                </tr>
                <tr>
                    <td style="width:50%; padding:0px; border-bottom: 0;">
                        <img src="assets/storage/uploads/logo/{{ $bplo_business->app_code == 3 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; padding-bottom: 5px; vertical-align: middle;">
                        <label for="new_form">ADDITIONAL</label>
                    </td>
                    <td style="width:50%; padding:0px; border-bottom: 0;">
                        <img src="assets/storage/uploads/logo/{{ $bplo_business->pm_id == 3 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; padding-bottom: 5px; vertical-align: middle;">
                        <label for="annually">Quarterly</label>
                    </td>
                </tr>
            </table>
        </div>

        <div style="width:35%; font-size: 12px; float:left; margin-left: 30%;">

            <table width="100%" style="border:0px solid black;">
                <tr>
                    <td width="60%" style="text-align:left; border:0px;">
                        <p>Date of Receipt</p>
                        <p>Tracking Number</p>
                    </td>
                    <td width="40%" style="text-align:left; border:0px">
                        <p style="border-bottom:solid 1px black;">:{{$bplo_business->application_date}}</p>
                        <p>:{{$bplo_business->busn_tracking_no}}</p>
                    </td>
                </tr>
                <tr>
                    <td width="60%" style="text-align:left; border:0px; vertical-align: bottom;">
                        <p></p>
                        <p style="">Business ID Number</p>
                    </td>
                    <td width="40%" style="text-align:left; border:0px">
                        <p style="border-bottom:solid 1px black;"></p>
                        <p>:{{$bplo_business->busns_id_no}}</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="clear"></div>

        <table width="100%" style="border:0px solid black;">
            <tr>
                <td colspan="6" style="border-bottom:0px solid black; padding-bottom:0px">
                    <p style="text-decoration: uppercase;"><strong>A. BUSINESS INFORMATION AND REGISTRATION</strong></p>
                </td>
            </tr>
            <tr>
                <td width="18%" style="border:0px solid black; border-bottom:solid 1px black; border-left:solid 1px black; vertical-align: top;">
                    <p style="">Please choose One :</p>
                </td>
                <td width="21%" style="border:0px solid black; border-bottom:solid 1px black;">
                    <p style="padding-bottom: 5px;">
                        <img src="assets/storage/uploads/logo/{{ $bplo_business->btype_id == 1 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; padding-bottom: 5px; vertical-align: middle;">Single Proprietorship
                    </p>
                    <p style="padding-bottom: 5px;">
                        <img src="assets/storage/uploads/logo/{{ $bplo_business->gender == 1 && $bplo_business->btype_id == 1  ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; vertical-align: middle;">Male <img src="assets/storage/uploads/logo/{{ $bplo_business->gender == 0 && $bplo_business->btype_id == 1  ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-left: 10px; padding-right:2px; vertical-align: middle;">Female
                    </p>
                </td>
                <td width="22%" style="border:0px solid black; border-bottom:solid 1px black;">
                    <p style="">
                        <img src="assets/storage/uploads/logo/{{ $bplo_business->btype_id == 2 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; padding-bottom: 5px; vertical-align: middle;">One Person Corporation
                    </p>
                    <p>
                        <img src="assets/storage/uploads/logo/{{ $bplo_business->gender == 1 && $bplo_business->btype_id == 2  ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; vertical-align: middle;">Male <img src="assets/storage/uploads/logo/{{ $bplo_business->gender == 0 && $bplo_business->btype_id == 2  ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-left: 10px; padding-right:2px; vertical-align: middle;">Female
                    </p>
                </td>
                <td width="13%" style="border:0px solid black; border-bottom:solid 1px black;vertical-align:top;">
                    <p style="">
                        <img src="assets/storage/uploads/logo/{{ $bplo_business->btype_id == 3 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; vertical-align: middle;">Partnership
                    </p>
                </td>
                <td width="13%" style="border:0px solid black; border-bottom:solid 1px black;vertical-align:top;">
                    <p style="">
                        <img src="assets/storage/uploads/logo/{{ $bplo_business->btype_id == 4 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; vertical-align: middle;">Corporation
                    </p>
                </td>
                <td width="13%" style="border:0px solid black; border-bottom:solid 1px black; border-right:solid 1px black;vertical-align:top;">
                    <p style="">
                        <img src="assets/storage/uploads/logo/{{ $bplo_business->btype_id == 5 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; vertical-align: middle;">Cooperative
                    </p>
                </td>
            </tr>
        </table>

        <table width="100%" style="border:0px solid black;">
            <tr>
                <td width="50%" style="border:0px solid black; border-left: 1px solid black;">DTI/SEC/CDA Registration Number:</td>
                <td width="50%" style="border:0px solid black; border-right: 1px solid black;">Tax Identification Number:</td>
            </tr>
            <tr>
                <td width="50%" style="border:0px solid black; border-left: 1px solid black; border-bottom: 1px solid black; padding-top: 0px;">
                    <strong>{{$bplo_business->busn_registration_no}}</strong>
                </td>
                <td width="50%" style="border:0px solid black; border-right: 1px solid black; border-bottom: 1px solid black; padding-top: 0px;">
                    <strong>{{$bplo_business->busn_tin_no}}</strong>
                </td>
            </tr>
        </table>

        <table width="100%" style="border:0px solid black;">
            <tr>
                <td width="50%" style="border-bottom: none; border-top:none;">Business Name:</td>
            </tr>
            <tr>
                <td width="50%" style="border-top: none; padding-top:0px">
                    <strong>{{$bplo_business->busn_name}}</strong>
                </td>
            </tr>
        </table>

        <table width="100%" style="border:0px solid black;">
            <tr>
                <td width="50%" style="text-align:left;border:1px solid black; border-top: none;">Trade Name/ Franchise(if applicable): <span style="">{{$bplo_business->busn_trade_name}}</span>
                </td>
            </tr>
        </table>

        <table width="100%" style="font-size:12px;">
            <tr>
                <td width="17%" style="border: solid 1px #000; border-bottom: none; border-top: none; border-right:none; padding-bottom: 0px; padding-bottom:0px;">Main Office Address:
                </td>
                <td width="23%" style="border:none; padding-bottom:0px;">
                    House/Bldg No:<u> {{$bplo_business->busn_office_main_building_no}}</u>
                </td>
                <td width="27%" style="border:none; padding-bottom:0px;">
                    Name of Building:<u> {{$bplo_business->busn_office_main_building_name}}</u>
                </td>
                <td width="16%" style="border:none; padding-bottom:0px;">
                    Lot No:<u> {{$bplo_business->busn_office_main_add_lot_no}}</u>
                </td>
                <td width="17%" style="border:none; border-right: solid 1px #000; padding-bottom:0px;">
                    Block No:<u> {{$bplo_business->busn_office_main_add_block_no}}</u>
                </td>
            </tr>
        </table>

        <table width="100%" style="font-size:12px;">
          <tr>
              <td width="33.33%" style="border:none; border-left:solid 1px #000; padding-bottom:0px;">
                  Street Name: <strong><u>{{$bplo_business->busn_office_main_add_street_name}}</u></strong>
              </td>
              <td width="33.33%" style="border:none; padding-bottom:0px;">
                  Barangay: <strong><u>{{$bplo_business->main_brgy_name}}</u></strong>
              </td>
              <td width="33.33%" style="border:none; border-right:solid 1px black; padding-bottom:0px;">
                  Subdivision: <strong><u>{{$bplo_business->busn_office_main_add_subdivision}}</u></strong>
              </td>
          </tr>
        </table>

        <table width="100%" style="font-size:12px;">
          <tr>
              <td width="33.33%" style="border:none; border-left:solid 1px #000; border-bottom:solid 1px #000;">
                  City/Municipality: <strong><u>{{$bplo_business->main_mun_desc}}</u></strong>
              </td>
              <td width="33.33%" style="border:none; border-bottom:solid 1px #000;">
                  Province: <strong><u>{{$bplo_business->main_prov_desc}}</u></strong>
              </td>
              <td width="33.33%" style="border:none; border-bottom:solid 1px #000; border-right:solid 1px black;">
                  Zip Code: <strong><u>{{$bplo_business->main_mun_zip_code}}</u></strong>
              </td>
          </tr>
        </table>

        <table width="100%" style="border:0px solid black;">
            <tr>
                <td width="33%" style="border: none; border-left:1px solid black; ">
                    Telephone No:<br>
                    <strong>{{$bplo_business->p_telephone_no}}</strong>
                </td>
                <td width="33%" style="border: none; border-left:1px solid black; ">
                    Mobile No:<br>
                    <strong>{{$bplo_business->p_mobile_no}}</strong>
                </td>
                <td width="33%" style="border: none; border-left:1px solid black; border-right:1px solid black; ">
                    Email Address:<br>
                    <strong>{{$bplo_business->p_email_address}}</strong>
                </td>
            </tr>
        </table>

        <table width="100%" style="border:0px solid black;">
            <tr>
                <td width="40%" style="border: none; border-left:1px solid black; border-top: 1px solid black;">
                    (For Sole Proprietorship)<br>
                    <strong>Name of Owner</strong>
                </td>
                <td width="15%" style="border: none; border-left:1px solid black; border-top: 1px solid black;">
                    Surname:<br>
                    <strong>{{$bplo_business->rpo_custom_last_name}}</strong>
                </td>
                <td width="15%" style="border: none; border-left:1px solid black; border-top: 1px solid black;">
                    Given Name:<br>
                    <strong>{{$bplo_business->rpo_first_name}}</strong>
                </td>
                <td width="15%" style="border: none; border-left:1px solid black; border-top: 1px solid black;">
                    Middle Name:<br>
                    <strong>{{$bplo_business->rpo_middle_name}}</strong>
                </td>
                <td width="15%" style="border: none; border-left:1px solid black; border-top: 1px solid black; border-right: 1px solid black;">
                    Suffix:<br>
                    <strong>{{$bplo_business->suffix}}</strong>
                </td>
            </tr>
        </table>

        <table width="100%" style="border:0px solid black;">
            <tr>
                <td width="40%" style="border: none; border-left:1px solid black; border-top: 1px solid black;">
                    (For Corporations/Cooperative/Partnership)<br>
                    <strong>Name of President/Office in Charge:</strong>
                </td>
                <td width="15%" style="border: none; border-left:1px solid black; border-top: 1px solid black;">
                    Surname:<br>
                    <strong></strong>
                </td>
                <td width="15%" style="border: none; border-left:1px solid black; border-top: 1px solid black;">
                    Given Name:<br>
                    <strong></strong>
                </td>
                <td width="15%" style="border: none; border-left:1px solid black; border-top: 1px solid black;">
                    Middle Name:<br>
                    <strong></strong>
                </td>
                <td width="15%" style="border: none; border-left:1px solid black; border-top: 1px solid black; border-right: 1px solid black;">
                    Suffix:<br>
                    <strong></strong>
                </td>
            </tr>
        </table>

        <table width="100%" style="border:0px solid black;">
            <tr>
                <td width="10%" style="border:none; border-top:1px solid black; border-bottom:solid 1px black; border-left:solid 1px black; vertical-align: top;">
                    For Corporation:
                </td>
                <td width="10%" style="border:none; border-top:1px solid black; border-bottom:solid 1px black;">
                    <img src="assets/storage/uploads/logo/checkbox.png" style="height:15px; width:15px; padding-right:2px; vertical-align: middle;">Filipino
                </td>
                <td width="10%" style="border:none; border-top:1px solid black; border-bottom:solid 1px black; border-right:solid 1px black;">
                    <img src="assets/storage/uploads/logo/checkbox.png" style="height:15px; width:15px; padding-right:2px; vertical-align: middle;">Foreign
                </td>
            </tr>
        </table>

        <table width="100%" style="border:0px solid black;">
            <tr>
                <td style="border-bottom:1px solid black; border-top: none; padding-bottom:0px">
                    <strong>B. BUSINESS OPERATION</strong>
                </td>
            </tr>
        </table>

        <table width="100%" style="border:0px solid black; font-size:12px;">
            <tr>
                <td width="25%" style="vertical-align:top; border: none; border-left:1px solid black;">
                    Business Area (In sq. m) <span style="border-bottom:1px solid black; font-weight: bold;">{{$bplo_business->busn_bldg_area}}</span>
                </td>
                <td width="25%" style="vertical-align:top; border:0px solid black;">Total No. of Employees in Establishment </td>
                <td width="25%" style="vertical-align:top; border:0px solid black;">No.Of Employees Residing Within </td>
                <td width="25%" style="vertical-align:top; border: none; border-right:1px solid black;">No. of Delivery Vehicles(if applicable) </td>
            </tr>
        </table>

        <table width="100%" style="border:0px solid black; font-size:12px;">
            <tr>
                <td width="25%" style="border:0px solid black; border-left: 1px solid black;">Floor Area(in sq. m) <span style=""><u>{{$bplo_business->busn_bldg_total_floor_area}}</u></span>
                </td>
                <td width="25%" style="border:0px solid black; ">
                <u><span style="">{{$bplo_business->busn_employee_no_male}}</span>&nbsp;Male <span style="">{{$bplo_business->busn_employee_no_female}}</span>&nbsp;Female</u>
                </td>
                <td width="25%" style="border:0px solid black; ">
                <u> <span style="">{{$bplo_business->busn_employee_no_lgu}}</span></u>
                </td>
                <td width="25%" style="border:0px solid black;border-right: 1px solid black; ">
                <u> <span style="">{{$bplo_business->busn_vehicle_no_van_truck}}</span></u>&nbsp;<u>Van/Truck <span style="">{{$bplo_business->busn_vehicle_no_motorcycle}}</span>&nbsp;Motorcycle</u>
                </td>
            </tr>
        </table>

        <table width="100%" style="border:0px solid black;">
            <tr>
                <td width="50%" style="border:1px solid black; border-bottom: 0px solid black;">
                    <img src="assets/storage/uploads/logo/{{ $bplo_business->busn_office_is_same_as_main == 1 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; padding-bottom: 0px; vertical-align: middle;">Same as Main Office Address
                </td>
            </tr>
        </table>

        <table width="100%" style="font-size:12px;">
            <tr>
                <td width="23%" style="border: solid 1px #000; border-bottom: none; border-top: none; border-right:none; padding-bottom: 0px; padding-bottom:0px;">Business Location Address:
                </td>
                <td width="20%" style="border:none; padding-bottom:0px;">
                    House/Bldg No:<u> {{$bplo_business->busn_office_building_no}}</u>
                </td>
                <td width="27%" style="border:none; padding-bottom:0px;">
                    Name of Building:<u> {{$bplo_business->busn_office_building_name}}</u>
                </td>
                <td width="14%" style="border:none; padding-bottom:0px;">
                    Lot No:<u> {{$bplo_business->busn_office_add_lot_no}}</u>
                </td>
                <td width="16%" style="border:none; border-right: solid 1px #000; padding-bottom:0px;">
                    Block No:<u> {{$bplo_business->busn_office_add_block_no}}</u>
                </td>
            </tr>
        </table>

        <table width="100%" style="font-size:12px;">
          <tr>
              <td width="33.33%" style="border:none; border-left:solid 1px #000; padding-bottom:0px;">
                  Street Name: <strong><u>{{$bplo_business->busn_office_add_street_name}}</u></strong>
              </td>
              <td width="33.33%" style="border:none; padding-bottom:0px;">
                  Barangay: <strong><u>{{$bplo_business->busn_brgy_name}}</u></strong>
              </td>
              <td width="33.33%" style="border:none; border-right:solid 1px black; padding-bottom:0px;">
                  Subdivision: <strong><u>{{$bplo_business->busn_office_add_subdivision}}</u></strong>
              </td>
          </tr>
        </table>

        <table width="100%" style="font-size:12px;">
            <tr>
                <td width="33.33%" style="border:none; border-left:solid 1px #000; border-bottom:solid 1px #000;">
                    City/Municipality: <strong><u>{{$bplo_business->busn_mun_desc}}</u></strong>
                </td>
                <td width="33.33%" style="border:none; border-bottom:solid 1px #000;">
                    Province: <strong><u>{{$bplo_business->busn_prov_desc}}</u></strong>
                </td>
                <td width="33.33%" style="border:none; border-bottom:solid 1px #000; border-right:solid 1px black;">
                    Zip Code: <strong><u>{{$bplo_business->busn_mun_zip_code}}</u></strong>
                </td>
            </tr>
        </table>

        <table width="100%" style="border:0px solid black;">
            <tr>
                <td width="30%" style="border:0px solid black; border-left: 1px solid black; border-bottom: 1px solid black;">Owned ?
                    <span>
                        <img src="assets/storage/uploads/logo/{{ $bplo_business->busn_bldg_is_owned == 1 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; padding-bottom: 0px; vertical-align: middle;"> Yes &nbsp;&nbsp; <img src="assets/storage/uploads/logo/{{ $bplo_business->busn_bldg_is_owned == 0 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; padding-bottom: 0px; vertical-align: middle;"> No
                    </span>
                </td>
                <td width="70%" style="border:0px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">
                    If Yes, Tax Declaration No. <u>{{$bplo_business->busn_bldg_tax_declaration_no}}</u>
                    or Property Identification No. <u>{{$bplo_business->busn_bldg_property_index_no}}</u>
                </td>
            </tr>
        </table>

        <table width="100%" style="border:0px solid black;">
            <tr>
                <td width="50%" style="border:0px solid black; border-bottom: 1px solid black; padding-bottom: 0px; border-right: 1px solid black; border-left: 1px solid black">
                    Total Capitalization(PH): {{$total_capitalisation}}<br>
                    <strong></strong>
                </td>
            </tr>
        </table>

        <table width="100%" style="border:0px solid black;">
            <tr>
                <td width="100%" style="border:0px solid black; border-bottom: 1px solid black; border-right: 1px solid black; border-left: 1px solid black">
                    Do you have tax incentives from any Government Entity?&nbsp;&nbsp;
                    <img src="assets/storage/uploads/logo/{{ $bplo_business->busn_tax_incentive_enjoy == 1 ? 'checked.png' : 'checkbox.png' }} " style="height:15px; width:15px; padding-right:2px; padding-bottom: 0px; vertical-align: middle;"> Yes &nbsp;&nbsp;
                    (Please attach a copy of your Certificate) &nbsp; <img src="assets/storage/uploads/logo/{{ $bplo_business->busn_tax_incentive_enjoy == 0 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; padding-bottom: 0px; vertical-align: middle;"> No
                </td>
            </tr>
        </table>

        <table width="100%" id="summary" style="border:0px solid black;">
            <tr>
                <td width="20%" style="border:0px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                    Business Activity(Please check one):

                    <img src="assets/storage/uploads/logo/{{ $bplo_business->busloc_id == 1 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; padding-bottom: 0px; vertical-align: middle;">Main Office

                    <img src="assets/storage/uploads/logo/{{ $bplo_business->busloc_id == 2 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; padding-bottom: 0px; vertical-align: middle;">Branch Office

                    <img src="assets/storage/uploads/logo/{{ $bplo_business->busloc_id == 3 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; padding-bottom: 0px; vertical-align: middle;">Admin Office Only

                    <img src="assets/storage/uploads/logo/{{ $bplo_business->busloc_id == 4 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; padding-bottom: 0px; vertical-align: middle;">Warehouse

                    <img src="assets/storage/uploads/logo/{{ $bplo_business->busloc_id == 5 ? 'checked.png' : 'checkbox.png' }}" style="height:15px; width:15px; padding-right:2px; padding-bottom: 0px; vertical-align: middle;">Other Pls. Specify
                </td>
            </tr>
        </table>

        <table width="100%" id="summary" style="border:0px solid black;">
            <tr>
                <td width="70%" style="border:1px solid black; text-align:center;">Line of Business</td>
                <td width="10%" style="border:1px solid black; text-align:center;">PSIC</td>
                <td width="20%" style="border:1px solid black; text-align:center;">Given product/Services</td>
            </tr>
            @foreach($bplo_business_plan as $plan)
            <tr>
                <td width="80%" style="border:1px solid black; ">
                    {{$plan->subclass_description}}
                </td>
                <td width="5%" style="border:1px solid black; ">
                    {{$plan->subclass_code}}
                </td>
                <td width="15%" style="border:1px solid black; ">
                    
                </td>
            </tr>
            @endforeach
        </table>

        <table width="100%">
            <tr>
                <td style="border-top: none;">
                    <span style="font-weight:600; text-decoration: uppercase;">I DECLARE UNDER PENALTY OF PERJURY</span> that all information in this application are true and correct based on my Personal knowledge and authentic records submitted to the <span style="font-weight:600; text-decoration: uppercase;"><b>City of Palayan</b></span>. Any false or misleading information supplied,or production of fake/falsified documents shall be grounds for appropriate legal action against me and automatically revokes the permit. I hereby agree that all personal data(as defined under the Data privacy Law of 2012 and its Implementing Rules and Regulations) and account transaction information or records with the city/Municipal Government may be processed, profiled or shared to requesting parties or for the purpose of any court,legal process,examination,inquiry and audit or investigation of any authority.
                </td>
            </tr>
        </table>

        <div style="width:100%; text-align:center;">
            <table width="100%">
                <tr>
                    <td style="border-top: none; text-align:center; border-bottom: none; padding-top:30px;">
                        <span style="text-align:center;">{{ (!empty($bplo_business->rpo_first_name) ? $bplo_business->rpo_first_name . ' ' : '') . (!empty($bplo_business->rpo_middle_name) ? $bplo_business->rpo_middle_name . ' ' : '') . (!empty($bplo_business->rpo_custom_last_name) ? $bplo_business->rpo_custom_last_name . ' ' : '') }}</span><br>
                        <span style="border-top: 1px solid black;">SIGNATURE OF APPLICANT/OWNER OVER PRINTED NAME</span>
                    </td>
                </tr>
                <tr>
                    <td style="border-top:none; text-align:center; padding-top:40px;">
                        <span style="padding-top: 50px; border-top: 1px solid black;">DESIGNATION/POSITION/TITLE</span>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>