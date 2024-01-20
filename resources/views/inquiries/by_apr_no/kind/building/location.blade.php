@php
$area = 0;
$landAppraisal = (isset($landRef->landAppraisals))?$landRef->landAppraisals:[];
foreach($landAppraisal as $app){
   if($app->lav_unit_measure == 1){
    $newArea = $app->rpa_total_land_area/10000;
   }else{
    $newArea = $app->rpa_total_land_area;
   }
   $area += $newArea;
}
@endphp
<table width="100%" style="margin-top:5px; margin-bottom:5px;">
          <tr>
              <td style="border:0px solid black; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; width: 50%;">
                  <h4 style="padding:0px">BUILDING LOCATION</h4>
              </td>
              <td style="border:0px solid black; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; width: 50%;">
                  <h4 style="padding:0px">LAND REFERENCE(Where Bldg is located)</h4>
              </td>
          </tr>
      </table>

      <table width="100%">
          <tr>
              <td colspan="2" style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 15px;">
                  No./Street: {{$rptProperty->rp_location_number_n_street}}
              </td>
              <td colspan="2" style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 15px;">
                  Owner: {{(isset($landRef->propertyOwner->standard_name)) ? $landRef->propertyOwner->standard_name : ''}}
              </td>
          </tr>
          <tr>
              <td colspan="2" style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 10px;">
                  Brgy.: 
              </td>
              <td style="width: 25%; border-bottom: 0; border-right: 0; padding:2px; padding-bottom: 10px; vertical-align: top;">
                  OCT/TCT No.: {{(isset($landRef->rp_oct_tct_cloa_no))?$landRef->rp_oct_tct_cloa_no:''}}
              </td>
              <td style="width: 25%; top:0px;border: none; border-top: 1px solid black; border-right: 1px solid black; padding:2px; padding-bottom: 10px;line-break: anywhere;">
                  Survey No.: {{wordwrap((isset($landRef->rp_cadastral_lot_no))?$landRef->rp_cadastral_lot_no:'', 10, "\r\n",true)}}
              </td>
          </tr>
          <tr>
              <td colspan="2" style="width: 50%; border-top: 0; padding:2px; text-align: center;">{{$rptProperty->barangay->brgy_name}}</td>
              <td style="width: 25%; border-top: 0; border-bottom: 0; border-right: 0; padding:2px; text-align: left;">Lot No.: {{(isset($landRef->rp_app_assessor_lot_no))?$landRef->rp_app_assessor_lot_no:''}}</td>
              <td style="width: 25%; border: none; border-bottom: 0; border-right: 1px solid black; padding:2px;">
                  Blk No.:
              </td>
          </tr>
          <tr>
              <td style="width: 25%; border-top: 0; border-right: 0; border-bottom: 1px solid black; padding:2px; padding-top: 20px;">
                  District:
              </td>
              <td style="width: 25%; border: none; border-bottom: 1px solid black; padding:2px; padding-top: 20px;">
                @if(isset($landRef->dist_code))
                  {{$landRef->dist_code}}
                @endif  
              </td>
              <td colspan="2" style="width: 50%; padding:2px; padding-top: 20px;">ARP No.: 
                
                @if(isset($landRef->pr_tax_arp_no))
                @php
                $array = explode("-",$landRef->pr_tax_arp_no);
                if(count($array) > 2){
                    unset($array[0]);
                }
                @endphp
                @else
                @php
                $array = [];
                @endphp
                @endif
                
                {{ implode("-",$array) }}
              </td>
          </tr>
          <tr>
              <td colspan="2" style="width: 25%; border-top: 0; border-bottom: 1px solid black; padding:2px; padding-top: 20px;">
                  City: {{$rptProperty->locality->mun_desc}}
              </td>
              <td colspan="2" style="width: 50%; padding:2px; padding-top: 20px;">Area: {{Helper::area_format($area)}} Has.
              </td>
          </tr>          
      </table>