@php
$stucTypes = [];
        foreach ($rptProperty->floorValues as $key => $value) {
               $stucTypes[] = $value->buildingType->bt_building_type_code;            
        }
        @endphp
<table width="100%" style="margin-top:5px; margin-bottom:5px;">
          <tr>
              <td style="border:0px solid black; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; width: 10%;">
                  <h4 style="padding:0px">GENERAL DESCRIPTION</h4>
              </td>
          </tr>
      </table>

      <table width="100%" border="none">
          <tr>
              <td width="50%" style="border-right: 0;">Kind of Bldg.: {{(isset($rptProperty->bk_building_kind_desc))?$rptProperty->bk_building_kind_desc:''}}</td>
              
              <td width="35%" style="border-right: 0;">Bldg. Age:</td>
              <td width="15%" style="border-left: 0;">{{(isset($rptProperty->rp_building_age))?$rptProperty->rp_building_age:''}}</td>
          </tr>
          <tr>
              <td width="35%" style="border-right: 0; border-top: 0;">Structural Type: {{implode("; ",array_unique($stucTypes));}}</td>
              <td width="35%" style="border-right: 0; border-top: 0;">No. of Storeys</td>
              <td width="15%" style="border-left: 0; border-top: 0;">{{$rptProperty->floorValues->count() }}</td>
          </tr>
      </table>

      <table width="100%" border="none">
          <tr>
              <td width="50%" style="border-top: 0;">Bldg. Permit No.: {{(isset($rptProperty->rp_bulding_permit_no))?$rptProperty->rp_bulding_permit_no:''}}</td>
              <td width="15%" style="border-top: 0; border-right: 0; ">Area of 1st Flr.</td>
              <td width="20%" style="border-top: 0; border-left: 0; border-right: 0;">{{ isset($rptProperty->floorValues[0]->rpbfv_floor_area)?number_format((float)$rptProperty->floorValues[0]->rpbfv_floor_area, 3, '.', '').' Sq. m.':0}}</td>
              <td width="15%" style="border-top: 0; border-left: 0;">{{ isset($rptProperty->propertyClass->pc_class_description)?$rptProperty->propertyClass->pc_class_description:''}}</td>
          </tr>
      </table>

      <table width="100%" border="none">
          <tr>
              <td width="35%" style="border-top: 0; border-right: 0;">Certificate of Completion Issued on:</td>
              <td width="15%" style="border-top: 0; border-left: 0; border-right: 0;">-</td>
              <td width="15%" style="border-top: 0; border-right: 0;">Area of 2nd Flr.</td>
              <td width="20%" style="border-top: 0; border-left: 0; border-right: 0;">{{ isset($rptProperty->floorValues[1]->rpbfv_floor_area)?number_format((float)$rptProperty->floorValues[1]->rpbfv_floor_area, 3, '.', '').' Sq. m.':''}}</td>
              <td width="15%" style="border-top: 0; border-left: 0;"></td>
          </tr>
          <tr>
              <td width="35%" style="border-top: 0; border-right: 0;">Certificate of Occupancy Issued on:</td>
              <td width="15%" style="border-top: 0; border-left: 0; border-right: 0;">-</td>
              <td width="15%" style="border-right: 0; border-top: 0">Area of 3rd Flr.</td>
              <td width="20%" style="border-top: 0; border-left: 0;border-right: 0;">{{ isset($rptProperty->floorValues[2]->rpbfv_floor_area)?number_format((float)$rptProperty->floorValues[2]->rpbfv_floor_area, 3, '.', '').' Sq. m.':''}}</td>
              <td width="15%" style="border-top: 0; border-left: 0;"></td>
          </tr>
      </table>

      <table width="100%" border="none">
          <tr>

              <td width="35%" style="border-top: 0; border-right:0;">Date Constructed/Completed:</td>
              <td width="15%" style="border-top: 0; border-left:0;">{{(isset($rptProperty->rp_constructed_year))?$rptProperty->rp_constructed_year:''}}</td>
              <td width="15%" style="border-top: 0; border-right: 0;">Area of 4th Flr.</td>
              <td width="20%" style="border-top: 0; border-left: 0;border-right: 0;">{{ isset($rptProperty->floorValues[3]->rpbfv_floor_area)?number_format((float)$rptProperty->floorValues[3]->rpbfv_floor_area, 3, '.', '').' Sq. m.':''}}</td>
              <td width="15%" style="border-top: 0; border-left: 0;"></td>

          </tr>
      </table>

      <table width="100%" border="none">
          <tr>
              <td width="35%" style="border-top: 0; border-right:0;">Date Occupied:</td>
              <td width="15%" style="border-top: 0; border-left:0;">{{(isset($rptProperty->rp_occupied_year))?$rptProperty->rp_occupied_year:''}}</td>
              <td width="15%" style="border-top: 0; border-right:0;">Total Floor Area:</td>
              <td width="20%" style="border-top: 0; border-left:0;border-right: 0;">{{number_format((float)$rptProperty->floorValues->sum('rpbfv_floor_area'), 3, '.', '')}} Sq. m.</td>
              <td width="15%" style="border-top: 0; border-left: 0;"></td>
          </tr> 
          <tr>
              <td colspan="4" width="100%" style="border-top:0; font-style:italic;">Attach the building plan or sketch of floor paln, A photograph may also be attached if necessary.</td>
          </tr>      
      </table>