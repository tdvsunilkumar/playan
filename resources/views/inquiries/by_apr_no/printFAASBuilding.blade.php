<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css?family=Fjalla+One|Montserrat:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
        * { margin: 0; padding: 0; }
        @page {sheet-size: Letter; margin-top: 15mm; margin-bottom: 15mm; margin-header: 0mm;margin-footer: 0mm;}
        /*@page rotated {
            size: landscape;
        }*/
        body { font-family: 'Montserrat', sans-serif; font-size: 12px; }
        table td, table th { border: 1px solid black; padding: 5px; }
        p {margin: 0px; padding: 0px}
        p .indent{text-indent: 25px; margin-left: 25px;}
        h4 {margin: 0px; padding: 0px}
        textarea { border: 0; font-size: 14px; font-family: 'Montserrat', sans-serif;overflow: hidden; resize: none; }
        table { border-collapse: collapse; }
        .mb-2{margin-bottom: 2mm}
        .bg{background-color: red;}
        .bg1{background-color: blue;}
    </style>
    <title>Building FAAS</title>
</head>

<body>
  <div style="">
      <p>RPTA Form No. 1-B (Revised 1998)</p>
      <h2 style="margin-top:10px; text-align: center;">REAL PROPERTY FIELD APPRAISAL & ASSESSMENT SHEET-BUILDING</h2>

      <table style="text-align:right; width:100%;">
          <tr>
              <td style="border: none; padding-bottom: 0px; padding-right:50px">TRANSACTON CODE</td>
              <td style="width:100px; border: none; border-bottom: 1px solid black; padding-bottom: 0px; text-align:center;">GR</td>
          </tr>
      </table>
      
      <table width="100%" style="margin-top:10px; margin-bottom:0px;">
          <tr>
              <td style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 0px;">
                  ARP NO
              </td>
              <td style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 0px;">PIN
              </td>
          </tr>
          <tr>
              <td style="border-top: 0; padding:2px; text-align: center;"><strong>{{($rptProperty->dist_code != null ?$rptProperty->dist_code : "").($rptProperty->brgy_no  != null ? $rptProperty->brgy_no  : "").($rptProperty->rp_td_no != null ? "-".$rptProperty->rp_td_no : "")}}</strong></td>
              <td style="border-top: 0; padding:2px; text-align: center;">{{$prop_index_no}}</td>
          </tr>
          <tr>
              <td style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 10px;">
                  OWNER
              </td>
              <td style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 10px;">Address:
              </td>
          </tr>
          <tr>
              <td style="border-top: 0; padding:2px; text-align: left;"><b>{{$own_name}}</b></td>
              <td style="border-top: 0; padding:2px; text-align: left;"><b>Brgy. {{$own_address}}</b></td>
          </tr>
          <tr>
              <td style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 10px;">
                  Administrator/Beneficial User: {{$admin_name}}
              </td>
              <td style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 10px;">Address: Brgy. {{$admin_address}}
              </td>
          </tr>
          <tr>
              <td style="border-top: 0; padding:2px; text-align: left;">TIN: {{$rptProperty->admin_p_tin_no}}</td>
              <td style="border-top: 0; padding:2px; text-align: left;">Tel No.: {{$rptProperty->admin_own_tel_no}}</td>
          </tr>
      </table>

      <table width="100%" style="margin-top:5px; margin-bottom:5px;">
          <tr>
              <td style="border:0px solid black; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; width: 50%;">
                  <h4 style="padding:0px">BUILDING LOCATION</h4>
              </td>
              <td style="border:0px solid black; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; width: 50%;">
                  <h4 style="padding:0px">LAND REFERENCE(Where Bldg. is located)</h4>
              </td>
          </tr>
      </table>

      <table width="100%">
          <tr>
              <td colspan="2" style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 15px;">
                  No./Street: {{$landDetails->rp_location_number_n_street}}
              </td>
              <td colspan="2" style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 15px;">
                  Owner: {{$Land_own_name}}
              </td>
          </tr>
          <tr>
              <td colspan="2" style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 10px;">
                  Brgy.:
              </td>
              <td style="width: 25%; border-bottom: 0; border-right: 0; padding:2px; padding-bottom: 10px;">
                  OCT/TCT No.: {{$landDetails->rp_oct_tct_cloa_no}}
              </td>
              <td style="width: 25%; border: none; border-top: 1px solid black; border-right: 1px solid black; padding:2px; padding-bottom: 10px;">
                  Survey No.:
              </td>
          </tr>
          <tr>
              <td colspan="2" style="width: 50%; border-top: 0; padding:2px; text-align: center;">{{$landDetails->barangay->brgy_name}}</td>
              <td style="width: 25%; border-top: 0; border-bottom: 0; border-right: 0; padding:2px; text-align: left;">Lot No.: {{$landDetails->rp_cadastral_lot_no}}</td>
              <td style="width: 25%; border: none; border-bottom: 0; border-right: 1px solid black; padding:2px;">
                  Bik No.:
              </td>
          </tr>
          <tr>
              <td style="width: 25%; border-top: 0; border-right: 0; border-bottom: 1px solid black; padding:2px; padding-top: 20px;">
                  District
              </td>
              <td style="width: 25%; border: none; border-bottom: 1px solid black; padding:2px; padding-top: 20px;">
                  {{$landDetails->dist_code}}
              </td>
              <td colspan="2" style="width: 50%; padding:2px; padding-top: 20px;">ARP No.: {{($landDetails->dist_code != null ?$landDetails->dist_code : "").($landDetails->brgy_no  != null ? $landDetails->brgy_no  : "").($landDetails->rp_td_no != null ? "-".$landDetails->rp_td_no : "")}}
              </td>
          </tr>
          <tr>
              <td colspan="2" style="width: 25%; border-top: 0; border-bottom: 1px solid black; padding:2px; padding-top: 20px;">
                  City: {{$landDetails->Locality->loc_address}}
              </td>
              <td colspan="2" style="width: 50%; padding:2px; padding-top: 20px;">Area: {{ $rptProperty->rp_total_land_area}}
              </td>
          </tr>          
      </table>

      <table width="100%" style="margin-top:5px; margin-bottom:5px;">
          <tr>
              <td style="border:0px solid black; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; width: 10%;">
                  <h4 style="padding:0px">GENERAL DESCRIPTION</h4>
              </td>
          </tr>
      </table>

      <table width="100%" border="none">
          <tr>
              <td width="35%" colspan="2" style="border-right: 0;font-size: 11px;">Kind of Bldg.: {{$buildingKind->bk_building_kind_desc}}</td>
              <!-- <td width="15%" style="border-left: 0;">{{$buildingKind->bk_building_kind_desc}}</td> -->
              <td width="35%" style="border-right: 0;">Bldg. Age:</td>
              <td width="15%" style="border-left: 0;">{{$rptProperty->rp_building_age}}</td>
          </tr>
          <tr>
              <td width="35%" style="border-right: 0; border-top: 0;">Structural Type:</td>
              <td width="15%" style="border-left: 0; border-top: 0;">{{$BuildingType->bt_building_type_desc}}</td>
              <td width="35%" style="border-right: 0; border-top: 0;">No. of Storeys</td>
              <td width="15%" style="border-left: 0; border-top: 0;">{{$rptProperty->rp_building_no_of_storey}}</td>
          </tr>
      </table>

      <table width="100%" border="none">
          <tr>
              <td width="50%" style="border-top: 0;">Bldg. Permit No.: {{$rptProperty->rp_bulding_permit_no}}</td>
              <td width="25%" style="border-top: 0; border-right: 0; ">Area of 1st Flr</td>
              <td width="10%" style="border-top: 0; border-left: 0; border-right: 0;">63</td>
              <td width="15%" style="border-top: 0; border-left: 0;">Residential</td>
          </tr>
      </table>

      <table width="100%" border="none">
          <tr>
              <td width="50%" style="border-top: 0;">Certificate of Completion Issued on:</td>
              <td width="50%" style="border-top: 0;">Area of 2nd Flr</td>
          </tr>
          <tr>
              <td width="50%">Certificate of Occupancy Issued on:</td>
              <td width="50%">Area of 3rd Flr</td>
          </tr>
      </table>

      <table width="100%" border="none">
          <tr>
              <td width="35%" style="border-top: 0; border-right:0;">Date Constructed/Completed:</td>
              <td width="15%" style="border-top: 0; border-left:0;">{{$rptProperty->rp_constructed_year}}</td>
              <td width="50%" style="border-top: 0;">Area of 4th Flr</td>
          </tr>
      </table>

      <table width="100%" border="none">
          <tr>
              <td width="35%" style="border-top: 0; border-right:0;">Date Occupied:</td>
              <td width="15%" style="border-top: 0; border-left:0;">{{$rptProperty->rp_occupied_year}}</td>
              <td width="35%" style="border-top: 0; border-right:0;">Total Floor Area:</td>
              <td width="15%" style="border-top: 0; border-left:0;">{{$BuildingUnitValue->rpbfv_floor_area}}</td>
          </tr> 
          <tr>
              <td colspan="4" width="100%" style="border-top:0; font-style:italic;">Attach the building plan or sketch of floor paln, A photograph may also be attached if necessary.</td>
          </tr>      
      </table>

      <table width="100%" style="margin-top:5px; margin-bottom:5px;">
          <tr>
              <td style="border:0px solid black; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; width: 10%;">
                  <h4 style="padding:0px">STRUCTURAL CHARACTERISTICS (Check list)</h4>
              </td>
          </tr>
      </table>

      <table width="100%" border="none">
          <tr>
              <td colspan="2" style="text-align:center;"><strong>ROOF</strong></td>
              <td style="text-align:center;"><strong>FLOORING</strong></td>
              <td style="text-align:center;">1st<br>Flr.</td>
              <td style="text-align:center;">2nd<br>Flr.</td>
              <td style="text-align:center;">3rd<br>Flr.</td>
              <td style="text-align:center;">4th<br>Flr.</td>
              <td style="text-align:center;"><strong>WALLS</strong></td>
              <td style="text-align:center;">1st<br>Flr.</td>
              <td style="text-align:center;">2nd<br>Flr.</td>
              <td style="text-align:center;">3rd<br>Flr.</td>
              <td style="text-align:center;">4th<br>Flr.</td>
          </tr>
          <tr>
              <td>Reinforced Concrete</td>
              <td>@if($rptProperty->rbf_building_roof_desc1 == 1)
                  Yes
                  @elseif($rptProperty->rbf_building_roof_desc2 == 1)
                   Yes
                  @elseif($rptProperty->rbf_building_roof_desc3 == 1)
                  Yes
                  @else
                  @endif</td>
              <td>Reinforced Concrete</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 1 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 1 && $BuildingUnitValue->rpbfv_floor_no == 1)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 1 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 1 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 1 && $BuildingUnitValue->rpbfv_floor_no == 2)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 1 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 1 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 1 && $BuildingUnitValue->rpbfv_floor_no == 3)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 1 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 1 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 1 && $BuildingUnitValue->rpbfv_floor_no == 4)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 1 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @else
                  @endif</td>
              
              <td>Reinforced Concrete</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 1 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 1 && $BuildingUnitValue->rpbfv_floor_no == 1)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 1 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 1 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 1 && $BuildingUnitValue->rpbfv_floor_no == 2)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 1 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 1 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 1 && $BuildingUnitValue->rpbfv_floor_no == 3)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 1 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 1 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 1 && $BuildingUnitValue->rpbfv_floor_no == 4)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 1 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @else
                  @endif</td>

          </tr>
          <tr>
              <td>Tiles</td>
              <td>@if($rptProperty->rbf_building_roof_desc1 == 2)
                  Yes
                  @elseif($rptProperty->rbf_building_roof_desc2 == 2)
                   Yes
                  @elseif($rptProperty->rbf_building_roof_desc3 == 2)
                  Yes
                  @else
                  @endif
              </td>
              <td>Plain Concrete</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 2 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 2 && $BuildingUnitValue->rpbfv_floor_no == 1)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 2 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 2 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 2 && $BuildingUnitValue->rpbfv_floor_no == 2)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 2 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 2 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 2 && $BuildingUnitValue->rpbfv_floor_no == 3)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 2 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 2 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 2 && $BuildingUnitValue->rpbfv_floor_no == 4)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 2 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @else
                  @endif</td>
              <td>Plain Concrete</td>
               <td>@if($rptProperty->rbf_building_wall_desc1 == 2 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 2 && $BuildingUnitValue->rpbfv_floor_no == 1)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 2 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 ==2 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 2 && $BuildingUnitValue->rpbfv_floor_no == 2)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 2 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 2 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 2 && $BuildingUnitValue->rpbfv_floor_no == 3)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 2 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 2 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 2 && $BuildingUnitValue->rpbfv_floor_no == 4)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 2 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @else
                  @endif</td>
          </tr>
          <tr>
              <td>G.I. Sheet</td>
              <td>@if($rptProperty->rbf_building_roof_desc1 == 3)
                  Yes
                  @elseif($rptProperty->rbf_building_roof_desc2 == 3)
                   Yes
                  @elseif($rptProperty->rbf_building_roof_desc3 == 3)
                  Yes
                  @else
                  @endif</td>
              <td>Marble</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 3 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 3 && $BuildingUnitValue->rpbfv_floor_no == 1)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 3 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 3 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 3 && $BuildingUnitValue->rpbfv_floor_no == 2)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 3 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 3 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 3 && $BuildingUnitValue->rpbfv_floor_no == 3)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 3 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 3 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 3 && $BuildingUnitValue->rpbfv_floor_no == 4)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 3 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @else
                  @endif</td>
              <td>Wood</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 3 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 3 && $BuildingUnitValue->rpbfv_floor_no == 1)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 3 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 3 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 3 && $BuildingUnitValue->rpbfv_floor_no == 2)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 3 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 3 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 3 && $BuildingUnitValue->rpbfv_floor_no == 3)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 3 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 3 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 3 && $BuildingUnitValue->rpbfv_floor_no == 4)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 3 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @else
                  @endif</td>
          </tr>
          <tr>
              <td>Nipa/Anahaw/Cogon</td>
              <td>@if($rptProperty->rbf_building_roof_desc1 == 4)
                  Yes
                  @elseif($rptProperty->rbf_building_roof_desc2 == 4)
                   Yes
                  @elseif($rptProperty->rbf_building_roof_desc3 == 4)
                  Yes
                  @else
                  @endif</td>
              <td>Wood</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 4 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 4 && $BuildingUnitValue->rpbfv_floor_no == 1)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 4 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 4 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 4 && $BuildingUnitValue->rpbfv_floor_no == 2)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 4 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 4 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 4 && $BuildingUnitValue->rpbfv_floor_no == 3)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 4 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 4 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 4 && $BuildingUnitValue->rpbfv_floor_no == 4)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 4 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @else
                  @endif</td>
              <td>CHB</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 4 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 4 && $BuildingUnitValue->rpbfv_floor_no == 1)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 4 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 4 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 4 && $BuildingUnitValue->rpbfv_floor_no == 2)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 4 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 4 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 4 && $BuildingUnitValue->rpbfv_floor_no == 3)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 4 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 4 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 4 && $BuildingUnitValue->rpbfv_floor_no == 4)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 5 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @else
                  @endif</td>
          </tr>
          <tr>
              <td>Others (Specify)</td>
              <td>@if($rptProperty->rbf_building_roof_desc1 == 5)
                  Yes
                  @elseif($rptProperty->rbf_building_roof_desc2 == 5)
                   Yes
                  @elseif($rptProperty->rbf_building_roof_desc3 == 5)
                  Yes
                  @else
                  @endif</td>
              <td>Tiles</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 5 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 5 && $BuildingUnitValue->rpbfv_floor_no == 1)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 5 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 5 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 5 && $BuildingUnitValue->rpbfv_floor_no == 2)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 5 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 5 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 5 && $BuildingUnitValue->rpbfv_floor_no == 3)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 5 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 5 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 5 && $BuildingUnitValue->rpbfv_floor_no == 4)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 5 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @else
                  @endif</td>
              <td>Playwood</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 5 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 5 && $BuildingUnitValue->rpbfv_floor_no == 1)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 5 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 5 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 5 && $BuildingUnitValue->rpbfv_floor_no == 2)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 5 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 5 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 5 && $BuildingUnitValue->rpbfv_floor_no == 3)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 5 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 5 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 5 && $BuildingUnitValue->rpbfv_floor_no == 4)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 5 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @else
                  @endif</td>
          </tr>
          <tr>
              <td></td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <td>Bamboo</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 6 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 6 && $BuildingUnitValue->rpbfv_floor_no == 1)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 6 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 6 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 6 && $BuildingUnitValue->rpbfv_floor_no == 2)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 6 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 6 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 6 && $BuildingUnitValue->rpbfv_floor_no == 3)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 6 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 6 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 6 && $BuildingUnitValue->rpbfv_floor_no == 4)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 6 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @else
                  @endif</td>
              <td>Sawali</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 6 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 6 && $BuildingUnitValue->rpbfv_floor_no == 1)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 6 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 6 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 6 && $BuildingUnitValue->rpbfv_floor_no == 2)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 6 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 6 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 6 && $BuildingUnitValue->rpbfv_floor_no == 3)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 6 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 6 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 6 && $BuildingUnitValue->rpbfv_floor_no == 4)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 6 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @else
                  @endif</td>
          </tr>
          <tr>
              <td></td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <td>Others (Specify)</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 7 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 7 && $BuildingUnitValue->rpbfv_floor_no == 1)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 7 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 7 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 7 && $BuildingUnitValue->rpbfv_floor_no == 2)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 7 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 7 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 7 && $BuildingUnitValue->rpbfv_floor_no == 3)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 7 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_floor_desc1 == 7 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @elseif($rptProperty->rbf_building_floor_desc2 == 7 && $BuildingUnitValue->rpbfv_floor_no == 4)
                   Yes
                  @elseif($rptProperty->rbf_building_floor_desc3 == 7 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @else
                  @endif</td>
              <td>Bamboo</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 7 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 7 && $BuildingUnitValue->rpbfv_floor_no == 1)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 7 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 7 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 7 && $BuildingUnitValue->rpbfv_floor_no == 2)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 7 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 7 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 7 && $BuildingUnitValue->rpbfv_floor_no == 3)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 7 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 7 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 7 && $BuildingUnitValue->rpbfv_floor_no == 4)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 7 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @else
                  @endif</td>
          </tr>
          <tr>
              <td></td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>G.I Sheet</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 8 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 8 && $BuildingUnitValue->rpbfv_floor_no == 1)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 8 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 8 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 8 && $BuildingUnitValue->rpbfv_floor_no == 2)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 8 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 8 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 8 && $BuildingUnitValue->rpbfv_floor_no == 3)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 8 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 8 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 8 && $BuildingUnitValue->rpbfv_floor_no == 4)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 8 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @else
                  @endif</td>
          </tr>
          <tr>
              <td></td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>Others (Specify)</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 9 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 9 && $BuildingUnitValue->rpbfv_floor_no == 1)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 9 && $BuildingUnitValue->rpbfv_floor_no == 1)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 9 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 9 && $BuildingUnitValue->rpbfv_floor_no == 2)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 9 && $BuildingUnitValue->rpbfv_floor_no == 2)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 9 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 9 && $BuildingUnitValue->rpbfv_floor_no == 3)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 9 && $BuildingUnitValue->rpbfv_floor_no == 3)
                  Yes
                  @else
                  @endif</td>
              <td>@if($rptProperty->rbf_building_wall_desc1 == 9 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @elseif($rptProperty->rbf_building_wall_desc2 == 9 && $BuildingUnitValue->rpbfv_floor_no == 4)
                   Yes
                  @elseif($rptProperty->rbf_building_wall_desc3 == 9 && $BuildingUnitValue->rpbfv_floor_no == 4)
                  Yes
                  @else
                  @endif</td>
          </tr>
      </table>

      <table width="100%" style="padding:0px">
          <tr>
              <td style="border:1px solid black; width: 100%; border-bottom: 0px;">
                  <h4 style="padding:0px">ADDITIONAL ITEMS (Use additional sheet if necessary)</h4>
              </td>
          </tr>
      </table>

      <table width="100%" border="none">
          <tr>
              <td style="text-align:left; border-right: none; border-bottom:none; padding-bottom: 0px;">1. Fence
              @if ($RptPropertyBuildingFloorAdItem->isNotEmpty())
                  @foreach ($RptPropertyBuildingFloorAdItem as $AdItem)
                      @if($AdItem->bei_extra_item_desc == 'Fence')
                      <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle;">
                      @else
                      @endif
                  @endforeach
              @else
                  
              @endif

              </td>
              <td style="text-align:center; border-left: none; border-bottom:none; padding-bottom: 0px;">
                  
              </td>
              <td style="text-align:left; border-bottom: none; padding-bottom: 0px;">4. Balcony
                @if ($RptPropertyBuildingFloorAdItem->isNotEmpty())
                  @foreach ($RptPropertyBuildingFloorAdItem as $AdItem)
                      @if($AdItem->bei_extra_item_desc == 'Balcony')
                      <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle;">
                      @else
                      @endif
                  @endforeach
              @else
                  
              @endif</td>
              <td style="text-align:left; border-bottom:none; border-right: none; padding-bottom: 0px;">7. Basement</td>
              <td style="text-align:center; border-left: none; border-bottom:none; padding-bottom: 0px;">
                 @if ($RptPropertyBuildingFloorAdItem->isNotEmpty())
                  @foreach ($RptPropertyBuildingFloorAdItem as $AdItem)
                      @if($AdItem->bei_extra_item_desc == 'Basement')
                      <img src="../../images/checkbox.png" style="height:15px; width:15px; vertical-align: middle;">
                      @else
                      @endif
                  @endforeach
              @else
                  
              @endif
              </td>
              <td colspan="2" style="text-align:left; border-bottom:none; padding-bottom: 0px;">10. Finishes: Wall
              @if ($RptPropertyBuildingFloorAdItem->isNotEmpty())
                  @foreach ($RptPropertyBuildingFloorAdItem as $AdItem)
                      @if($AdItem->bei_extra_item_desc == 'Finishes: Wall')
                      <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle;">
                      @else
                      @endif
                  @endforeach
              @else
                  
              @endif</td>
              <td style="text-align:left; border-bottom:none; padding-bottom: 0px;">11. Electrical
              @if ($RptPropertyBuildingFloorAdItem->isNotEmpty())
                  @foreach ($RptPropertyBuildingFloorAdItem as $AdItem)
                      @if($AdItem->bei_extra_item_desc == 'Electrical')
                      <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle;">
                      @else
                      @endif
                  @endforeach
              @else
                  
              @endif</td>
          </tr>
          <tr>
              <td style="border-right: none; border-bottom:none; border-top:none; text-align: left; padding-bottom: 0px;">2. Gate (s)
            </td>
              <td style="text-align:center; border-left: none; border-top: none; border-bottom:none; padding-bottom: 0px;">
                @if ($RptPropertyBuildingFloorAdItem->isNotEmpty())
                  @foreach ($RptPropertyBuildingFloorAdItem as $AdItem)
                      @if($AdItem->bei_extra_item_desc == 'Gate (s)')
                      <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle;">
                      @else
                      @endif
                  @endforeach
              @else
                  
              @endif
              </td>
              <td style="border-top:none; border-bottom:none; padding-bottom: 0px;">5. Terrace
              @if ($RptPropertyBuildingFloorAdItem->isNotEmpty())
                  @foreach ($RptPropertyBuildingFloorAdItem as $AdItem)
                      @if($AdItem->bei_extra_item_desc == 'Terrace')
                      <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle;">
                      @else
                      @endif
                  @endforeach
              @else
                  
              @endif</td>
              <td style="border-top:none; border-bottom:none; border-right: none; padding-bottom: 0px;">8. Mezzanine</td>
              <td style="text-align:center; border-left: none; border-bottom: none; border-top:none; padding-bottom: 0px;">
                  @if ($RptPropertyBuildingFloorAdItem->isNotEmpty())
                  @foreach ($RptPropertyBuildingFloorAdItem as $AdItem)
                      @if($AdItem->bei_extra_item_desc == 'Mezzanine')
                      <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle;">
                      @else
                      @endif
                  @endforeach
              @else
                  
              @endif
              </td>
              <td style="border-top:none; border-bottom:none; border-right:none; padding-left: 25px; padding-bottom: 0px;">11. Wall</td>
              <td style="text-align:center; border-left: none; border-bottom:none; border-top: none; border-left:none; padding-bottom: 0px;">
                   @if ($RptPropertyBuildingFloorAdItem->isNotEmpty())
                  @foreach ($RptPropertyBuildingFloorAdItem as $AdItem)
                      @if($AdItem->bei_extra_item_desc == 'Wall')
                      <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle;">
                      @else
                      @endif
                  @endforeach
              @else
                  
              @endif
              </td>
              <td style="border-bottom:none; border-top:none; padding-bottom: 0px;">13. Plumbing
              @if ($RptPropertyBuildingFloorAdItem->isNotEmpty())
                  @foreach ($RptPropertyBuildingFloorAdItem as $AdItem)
                      @if($AdItem->bei_extra_item_desc == 'Plumbing')
                      <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle;">
                      @else
                      @endif
                  @endforeach
              @else
                  
              @endif</td>
          </tr>
          <tr>
              <td style="text-align:let; border-bottom:none; border-top:none; border-right: none;">3. Garage
             </td>
              <td style="text-align:center; border-left: none; border-bottom: none; border-top:none;">@if ($RptPropertyBuildingFloorAdItem->isNotEmpty())
                  @foreach ($RptPropertyBuildingFloorAdItem as $AdItem)
                      @if($AdItem->bei_extra_item_desc == 'Garage')
                      <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle;">
                      @else
                      @endif
                  @endforeach
              @else
                  
              @endif</td>
              <td style="border-top: none; border-bottom:none;">6. Roof Deck
              @if ($RptPropertyBuildingFloorAdItem->isNotEmpty())
                  @foreach ($RptPropertyBuildingFloorAdItem as $AdItem)
                      @if($AdItem->bei_extra_item_desc == 'Roof Deck')
                      <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle;">
                      @else
                      @endif
                  @endforeach
              @else
                  
              @endif</td>
              <td style="border-top:none; border-bottom:none; border-right: none;">9. Ceiling
             </td>
              <td style="text-align:center; border-left: none; border-top: none; border-bottom: none;">
                  @if ($RptPropertyBuildingFloorAdItem->isNotEmpty())
                  @foreach ($RptPropertyBuildingFloorAdItem as $AdItem)
                      @if($AdItem->bei_extra_item_desc == 'Roof Deck')
                      <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle;">
                      @else
                      @endif
                  @endforeach
              @else
                  
              @endif
              </td>
              <td style="border-top:none; border-bottom:none; border-right:none; padding-left: 25px;">12.Floor
               
              </td>
              <td style="text-align:center; border-left: none; border-top:none; border-bottom: none;">
                  @if ($RptPropertyBuildingFloorAdItem->isNotEmpty())
                  @foreach ($RptPropertyBuildingFloorAdItem as $AdItem)
                      @if($AdItem->bei_extra_item_desc == 'Floor')
                      <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle;">
                      @else
                      @endif
                  @endforeach
              @else
                  
              @endif
              </td>
              <td style="border-bottom:none; border-top:none;">Others (Specify)
              @if ($RptPropertyBuildingFloorAdItem->isNotEmpty())
                  @foreach ($RptPropertyBuildingFloorAdItem as $AdItem)
                      @if($AdItem->bei_extra_item_desc == 'Others (Specify)')
                      <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle;">
                      @else
                      @endif
                  @endforeach
              @else
                  
              @endif</td>
          </tr>
      </table>

      <table width="100%" style="padding:0px">
          <tr>
              <td style="border:1px solid black; width: 100%; border-bottom: 0px;">
                  <h4 style="padding:0px">PROPERTY APPRAISAL</h4>
              </td>
          </tr>
      </table>

      <table width="100%" style="padding:0px;">
          <tr>
              <td colspan="3" style="padding:2px 5px; border-bottom: none; width:75%;">Unit Construction Cost: P _________________________/Sq. m.</td>
              <td style="padding:2px 5px; border-bottom: none; width:25%;">Cost of Additional Items:</td>
          </tr>
          <tr>
              <td colspan="3" style="padding:2px 5px; border-bottom: none; border-top:none;">Building Core: (Use additional sheets if necessary)</td>
              <td style="padding:2px 5px; border-bottom: none; border-top:none; text-align:right;">-</td>
          </tr>
          <tr>
              <td colspan="3" style="padding:2px 5px; border-bottom: none; border-top:none;">&nbsp;</td>
              <td style="padding:2px 5px; border-bottom: none; border-top:none;">Adjusments:</td>
          </tr>
          <tr>
              <td colspan="2" style="padding:2px 5px; border-bottom: none; border-top:none; border-right: none; text-align:right;">63.00 x 5,77.00 = </td>
              <td style="padding:2px 5px; border-bottom: none; border-top:none; border-left: none; text-align:center;"> 363,510.00</td>
              <td style="padding:2px 5px; border-bottom: none; border-top:none; text-align:right;">-</td>
          </tr>
          <tr>
              <td colspan="2" rowspan="4" style="padding:2px 5px; border-bottom: none; border-top:none; border-right: none; vertical-align: bottom;">Sub-Total</td>
              <td rowspan="4" style="padding:2px 5px; border-bottom: none; border-top:none; vertical-align: bottom; text-align: right; border-left:none;">363,510.00</td>
              <td style="padding:2px 5px; border-bottom: none; border-top:none;">Total Construction Cost:</td>
          </tr>
          <tr>
              <td style="padding:2px 5px; border-bottom: none; border-top:none; text-align:right;">363,510.00</td>
          </tr>
          <tr>
              <td style="padding:2px 5px; border-bottom: none; border-top:none;">Depreciated Value: P</td>
          </tr>
          <tr>
              <td style="padding:2px 5px; border-bottom: none; border-top:none; text-align:right;">{{Helper::decimal_format($rptProperty->rp_accum_depreciation)}}</td>
          </tr>
          <tr>
              <td colspan="2" style="padding:2px 5px; border-bottom: none;">Depreciation Rate: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{Helper::decimal_format($rptProperty->rp_depreciation_rate)}}</td>
              <td style="padding:2px 5px; border-bottom: none;">Total % Depreciation:</td>
              <td style="padding:2px 5px; border-bottom: none; border-top:none;">Market Value P</td>
          </tr>
          <tr>
              <td colspan="3" style="padding:2px 5px;">Depriciation Cost: P</td>
              <td style="padding:2px 5px; border-top:none; text-align:right;"><strong>{{Helper::decimal_format($rptProperty->rpb_accum_deprec_market_value)}}</strong></td>
          </tr>
      </table>

      <table width="100%" style="margin-top:5px; margin-bottom:5px;">
          <tr>
              <td style="border:0px solid black; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; width: 10%;">
                  <h4 style="padding:0px">PROPERTY ASSESSMENT</h4>
              </td>
          </tr>
      </table>

      <table width="100%">
          <tr>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">Actual Use</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">Market Value</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">Assessment Level</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">Assessed Value</td>
          </tr>
          <tr>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">Residential</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{Helper::decimal_format($rptProperty->rpb_accum_deprec_market_value)}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{$rptProperty->al_assessment_level}} %</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: right;">{{Helper::decimal_format($rptProperty->rpb_assessed_value)}}</td>
          </tr>
          <tr>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">&nbsp;</td>
          </tr>
          <tr>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px;">
                  <table width="100%">
                      <tr>
                          <td style="padding:0px; text-align:left; border:none;">P</td>
                          <td style="padding:0px; text-align:right; border:none;">{{Helper::decimal_format($rptProperty->rpb_assessed_value)}}</td>
                      <tr>
                  </table>
              </td>
          </tr>
      </table>

      <table width="100%" style="font-size:11px; padding:0px;">
          <tr>
              <td rowspan="2" style="border: solid 1px #000; border-right: none; border-top: none; padding-top:0px; padding-bottom: 0px; vertical-align: middle; text-align: right;">
                  Taxable 
                  @if($rptProperty->rp_app_taxability == 1)
                  <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 20px;">
                  @else
                  <img src="{{ asset('assets/images/checkbox-unchecked.jpg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 20px;">
                  @endif
              </td>
              <td rowspan="2" style="border: solid 1px #000; border-right: none; border-top: none; border-left: none; padding-top:0px; padding-bottom: 0px; vertical-align: middle;">
                   Exempt 
                  @if($rptProperty->rp_app_taxability == 0)
                  <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 20px;">
                  @else
                  <img src="{{ asset('assets/images/checkbox-unchecked.jpg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 20px;">
                  @endif
              </td>
              <td rowspan="2" style="border: solid 1px #000; border-right: none; border-top: none; border-left: none; padding-top:0px; padding-bottom: 0px; padding-right: 20px; vertical-align: middle; text-align: right;">
                  Effectivity of Assessment
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
                @endif &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$rptProperty->rp_app_effective_year}}
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
              {{(isset($apprisedPosition->fullname))?$apprisedPosition->fullname:''}} 
              </p>
              {{(isset($apprisedPosition->description))?$apprisedPosition->description:''}} 
          </div>

          <div style="width:100px; float: left; text-align: center; padding-top:10px; margin-left: 37px;">
              <p style="border-bottom:solid 1px black; text-align: center;">
              {{ date('d-M-Y', strtotime((isset($RptPropertyApproval->rp_app_appraised_date))?$RptPropertyApproval->rp_app_appraised_date:''))}}
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
              {{(isset($recommendPosition->fullname))?$recommendPosition->fullname:''}} 
              </p>
              {{(isset($recommendPosition->description))?$recommendPosition->description:''}} 
          </div>

          <div style="width:100px; float: left; text-align: center; padding-top:10px; margin-left: 37px;">
              <p style="border-bottom:solid 1px black; text-align: center;">
              {{ date('d-M-Y', strtotime((isset($RptPropertyApproval->rp_app_recommend_date))?$RptPropertyApproval->rp_app_recommend_date:''))}}
              </p>
              Date
          </div>
          
          <div style="width:200px; float: left; text-align: center; padding-top:10px; margin-left: 30px;">
              <p style="border-bottom:solid 1px black; text-align: center;">
              {{(isset($approvedPosition->fullname))?$approvedPosition->fullname:''}} 
              </p>
             {{(isset($approvedPosition->description))?$approvedPosition->description:''}} 
          </div>
          <div style="width:100px; float: left; text-align: center; padding-top:10px; margin-left: 30px;">
              <p style="border-bottom:solid 1px black; text-align: center;">
              {{ date('d-M-Y', strtotime((isset($RptPropertyApproval->rp_app_approved_date))?$RptPropertyApproval->rp_app_approved_date:''))}}
              </p>
              Date
          </div>
      </div>
      
      <table width="100%" style="border:1px solid black; border-top: none; margin-top:15px;">
          <tr>
              <td style=" width:100%; text-align:left; border-bottom:none;">MEMORANDA:</td>
          </tr>
          <tr>
              <td style=" width:100%; text-align:left; padding-top:0px; border-top:none; padding-bottom:30px;">{{$rptProperty->rp_app_memoranda}}</td>
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
              <td style="width: 50%; padding-left: 100px; text-align: center; padding-top:2px; padding-bottom: 2px; border-right:none; border-bottom: none;border-top: none;">
                  Recording Personnel:</td>
              <td style="border-left: none; padding-right: 100px; text-align: center; padding-top:2px; padding-bottom: 2px; border-bottom: none;border-top: none;">Date:
              </td>
          </tr>

          <tr>
              <td style="width: 50%; border-top:none; text-align: right; padding-right: 0; border-right: none;">
                  <table>
                      <tr>
                          <td style="width: 100%; text-align: center; padding-top:2px; padding-right: 100px; padding-left: 50px; padding-bottom: 0px; border: none; border-bottom:1px solid black;">{{$registered_by->fullname}}
                          </td>
                      </tr>
                  </table>
              </td>
              <td style="width: 50%; border-top:none; text-align:left; padding-left: 0; border-left:none;">
                  <table>
                      <tr>
                          <td style="width: 100%; border-left: none; text-align: center; padding-left: 100px; padding-right: 50px; padding-top:2px; padding-bottom: 0px; border: none; border-bottom:1px solid black;">{{ date('d-M-Y', strtotime($rptProperty->created_at))}}
                          </td>
                      </tr>
                  </table>
              </td>
          </tr>
      </table>
      
  </div>
</body>

</html>







