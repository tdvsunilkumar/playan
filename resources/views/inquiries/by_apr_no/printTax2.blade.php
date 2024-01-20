<!doctype html>

<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style type="text/css">
        @page {sheet-size: A4; margin-top: 12mm; margin-bottom: 12mm; margin-left: 10mm; margin-right: 10mm; margin-header: 0mm; margin-footer: 0mm;}
        @page rotated {
            size: landscape;
        }
        @import url('https://fonts.googleapis.com/css?family=Fjalla+One|Arial:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
        * { margin: 0; padding: 0; }
        body { font-family: 'Arial', sans-serif;font-size: 12px; }
        p {margin: 0px}
        table td, table th { border: 1px solid black; padding: 5px; }
        .page-wrap { width: 800px; margin: 100px auto;}
        table { border-collapse: collapse; }
        h2, p, h1{margin: 0px; padding: 0px;}
    </style>
    <title>TAX DECLARATION OF REAL PROPERTY</title>
</head>
<body>    
    <watermarkimage src="{{url('/assets/images/rptCertLog/Logo.png')}}" alpha="0.08" position="auto" size="150,150" />
    <table style="width:100%; text-align: center; margin-bottom:5px;">
        <tr>
            <td style="vertical-align:middle; padding: 0; text-align:right; border:none;">
                <img src="{{url('/assets/images/rptCertLog/assessor-logo.png')}}" style="width:90px; height:90px;">
            </td>
            <td style="vertical-align:middle; padding: 0; border:none; text-align:left; padding-left:100px;">
                <h3>TAX DECLARATION OF REAL PROPERTY</h3>
            </td>
        </tr>
    </table>

    <table style="width:100%;">
        <tr>
            <td width="10%" style="padding: 2px 5px; border: none;">TD No.</td>
            <td style="padding: 5px 5px 2px 5px; border: none; border-bottom: 1 solid black; text-align: center;"><strong>
                @php
                $array = explode("-",$rptProperty->pr_tax_arp_no);
                if(count($array) > 2){
                    unset($array[0]);
                }
                @endphp
                {{ implode("-",$array) }}
            </strong></td>
            <td style="padding: 2px 5px; border: none; text-align:right;">Property Identification No.</td>
            <td style="padding: 2px 5px; border: none; border-bottom: 1 solid black; text-align:center;"><strong>{{$rptProperty->rp_pin_declaration_no}}</strong></td>
        </tr>
        <tr>
            <td width="10%" style="padding: 2px 5px; border: none; vertical-align:top;">Owner</td>
            <td colspan="3" style="padding: 5px 5px 2px 5px; border: none; border-bottom: 1 solid black;">{{$own_name}}</td>
        </tr>
        <tr>
            <td width="10%" style="padding: 5px 5px 2px 5px; border: none; vertical-align:top;">Address</td>
            <td colspan="3" style="padding: 5px 5px 2px 5px; border: none; border-bottom: 1 solid black;">{{$own_address}}</td>
        </tr>
        <tr>
            <td colspan="2" style="border:none; padding: 5px 5px 2px 5px;">&nbsp;</td>
            <td style="border: none; padding: 5px 5px 0px 5px; text-align: right;">Telephone No.</td>
            <td style="width: 35%; border: none; padding: 5px 5px 2px 5px; border-bottom: 1px solid black; vertical-align:bottom;">{{(isset($rptProperty->propertyOwner->p_telephone_no))?$rptProperty->propertyOwner->p_telephone_no:''}}</td>
        </tr>
    </table>

    <table style="width:100%; margin-top:5px;">
        <tr>
            <td style="width: 24%; padding: 5px 5px 0px 5px; border: none; vertical-align:bottom;">Administrator/Beneficial user</td>
            <td style="width: 55%;padding: 5px 5px 0px 5px; border: none; border-bottom: 1px solid black; vertical-align:bottom;">{{$admin_name}}</td>
            <td style="width: 6%; padding: 5px 5px 0px 5px;margin-left: ; border: none; vertical-align:bottom;">TIN</td>
            <td style="padding: 5px 5px 0px 5px; border: none; border-bottom: 1px solid black; vertical-align:bottom;">{{(isset($rptProperty->propertyAdmin->p_tin_no))?$rptProperty->propertyAdmin->p_tin_no:''}}</td>
        </tr>
    </table>

    <table style="width:100%;">
        <tr>
            <td style="width: 8%; padding: 5px 5px 0px 5px; border: none; vertical-align:bottom;">Address</td>
            <td style="width: 45%padding: 5px 5px 0px 5px; border: none; border-bottom: 1px solid black; vertical-align:bottom;"> {{$admin_address}}</td>
            <td style="width: 13%; padding: 5px 5px 0px 5px; border: none; vertical-align:bottom;">Telephone No.</td>
            <td style="width: 35%;padding: 5px 5px 0px 5px; border: none; border-bottom: 1px solid black; vertical-align:bottom;">{{(isset($rptProperty->propertyAdmin->p_telephone_no))?$rptProperty->propertyAdmin->p_telephone_no:''}}</td>
        </tr>
    </table>
    <table style="width:100%; margin-top:10px">
        <tr>
            <td style="width: 17%; padding: 5px 5px 0px 5px; border: none; vertical-align:bottom;">Location of Property</td>
            <td style="width: 25%; padding: 5px 5px 0px 5px; border: none; border-bottom: 1px solid black; vertical-align:bottom; text-align: center;">{{($rptProperty->rp_location_number_n_street != null ?$rptProperty->rp_location_number_n_street : "")}}</td>
            <td style="width: 25%; padding: 5px 5px 0px 5px; border: none; border-bottom: 1px solid black; vertical-align:bottom; text-align: center;">{{(isset($rptProperty->barangay->brgy_name))?strtoupper($rptProperty->barangay->brgy_name):''}}</td>
            <td style="width: 33%; padding: 5px 5px 0px 5px; border: none; border-bottom: 1px solid black; vertical-align:bottom; text-align: center;">{{($locality != null ?strtoupper($locality) : "")}}</td>
        </tr>
    </table>

    <table style="width:100%;">
        <tr>
            <td style="width: 17%; padding: 1px 5px 0px 5px; border: none; vertical-align:bottom;"></td>
            <td style="width: 25%; padding: 1px 5px 0px 5px; border: none; vertical-align:bottom; text-align: center;"><small>Number & Street</small></td>
            <td style="width: 25%; padding: 1px 5px 0px 5px; border: none; vertical-align:bottom; text-align: center;"><small>Barangay/District</small></td>
            <td style="width: 33%; padding: 1px 5px 0px 5px; border: none; vertical-align:bottom; text-align: center;"><small>Municipality/Provision</small></td>
        </tr>
    </table>

    <table style="width:100%; margin-top:5px;">
        <tr>
            <td style="width: 18%; padding: 5px 5px 0px 5px; border: none; vertical-align:bottom;">OCT/TCT/CLOA No.</td>
            <td style="padding: 5px 5px 0px 5px; border: none; vertical-align:bottom; text-align: left; border-bottom: 1px solid black; ">{{(isset($rptProperty->buildingReffernceLand->rp_oct_tct_cloa_no))?strtoupper($rptProperty->buildingReffernceLand->rp_oct_tct_cloa_no):strtoupper($rptProperty->rp_oct_tct_cloa_no)}}</td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px 5px 0px 5px; border: none; vertical-align:bottom;">LOT/BLK/SURVEY No.</td>
            <td style="padding: 5px 5px 0px 5px; border: none; vertical-align:bottom; text-align: left; border-bottom: 1px solid black; ">{{(isset($rptProperty->buildingReffernceLand->rp_cadastral_lot_no))?strtoupper($rptProperty->buildingReffernceLand->rp_cadastral_lot_no):strtoupper($rptProperty->rp_cadastral_lot_no)}}</td>
        </tr>
    </table>

    <table style="width:100%; margin-top:5px; margin-bottom: 0px;">
        <tr>
            <td style="width:100%; border:none;">BOUNDARIES</td>
        </tr>
    </table>

    <table style="width:100%;table-layout: fixed;" >
        <tr style="height:10px; overflow: hidden;">
            <td style="width: 10%; padding: 0px 5px 20px 5px; border: none; vertical-align:top;">North</td>
            <td style="width: 40%;padding: 5px 5px 0px 5px;  vertical-align:top; text-align: left; border-bottom: 1px solid black;">{{strtoupper($rptProperty->rp_bound_north)}}</td>
            <td style=" width: 10%;padding: 0px 5px 20px 5px; border: none; vertical-align:top; text-align: right;">East</td>
            <td style="width: 40%;padding: 5px 5px 0px 5px; vertical-align:top; text-align: left; border-bottom: 1px solid black; ">{{strtoupper($rptProperty->rp_bound_east)}}</td>
        </tr>
        <tr>
            <td style="width: 10%; padding: 0px 5px 20px 5px; border: none; vertical-align:top;">South</td>
            <td style="width: 40%;padding: 5px 5px 0px 5px; vertical-align:top; text-align: left; border-bottom: 1px solid black; ">{{strtoupper($rptProperty->rp_bound_south)}}</td>
            <td style="width: 10%; padding: 0px 5px 20px 5px; border: none; vertical-align:top; text-align: right;">West</td>
            <td style="width: 40%;padding: 5px 5px 0px 5px; vertical-align:top; text-align: left; border-bottom: 1px solid black; ">{{strtoupper($rptProperty->rp_bound_west)}}</td>
        </tr>
    </table>

    <table style="width:100%; margin-top:5px; margin-bottom: 0px;">
        <tr>
            <td style="width:100%; border:none;">KIND OF PROPERTY ASSESSED</td>
        </tr>
    </table>

    <table style="width:100%;">
        <tr>
            <td style="width: 28%; padding: 2px 5px 2px 80px; border: none; vertical-align:middle; text-align:left;">
                @if(isset($rptProperty->propertyKindDetails->pk_description) && $rptProperty->propertyKindDetails->pk_description == 'Land')
                    <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height: 15px; width: 15px; vertical-align: middle;margin-right: 15px;">
                @else
                    <img src="{{ asset('assets/images/checkbox-unchecked.jpg') }}" style="height: 15px; width: 15px; vertical-align: middle;  margin-right: 15px;">
                @endif

                  LAND<br><small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Brief Description</small>
            </td>
            @php $subClassDesc= []; @endphp
            @foreach($RptPropertyAppraisals as $appraisal)
             @php
             if($appraisal->subClass->is_td_display){
                $subClassDesc[] = $appraisal->subClass->ps_subclass_desc;
             }
             @endphp
            @endforeach
            @php
            $breifDesc = (isset($RptPropertyAppraisals[0]->subClass->is_td_display) && $RptPropertyAppraisals[0]->subClass->is_td_display == 1)?$RptPropertyAppraisals[0]->subClass->ps_subclass_desc:'';
            @endphp
            <td style="width: 22%; padding: 2px 5px 0px 5px; vertical-align:bottom; text-align: left; border:none; border-bottom: 1px solid black;">{{implode("; ",$subClassDesc)}}</td>
                
            </td>
            <td style="width: 28%; padding: 2px 5px 2px 80px; border: none; vertical-align:middle;">
                 @if(isset($rptProperty->propertyKindDetails->pk_description) && $rptProperty->propertyKindDetails->pk_description == 'Machineries')
                  <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height: 15px; width: 15px; vertical-align: middle;margin-right: 15px;">
                  @else
                  <img src="{{ asset('assets/images/checkbox-unchecked.jpg') }}" style="height:15px; width:15px; vertical-align: middle;  margin-right: 15px;">
                  @endif
                  MACHINERY<br><small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Brief Description</small>
            </td>
            <td style="width: 22%; padding: 2px 5px 1px 5px; vertical-align:bottom; text-align: left; border:none; border-bottom: 1px solid black;">{{(isset($rptProperty->propertyKindDetails->pk_code) && $rptProperty->propertyKindDetails->pk_code == 'M')?implode('; ',$RptPropertyMachineAppraisal->pluck('rpma_description')->take(5)->unique()->toArray()):''}}</td>
        </tr>
        <tr>
            <td style="width: 25%; padding: 2px 5px 2px 80px; border: none; vertical-align:middle; text-align:left;">
                @if(isset($rptProperty->propertyKindDetails->pk_description) && $rptProperty->propertyKindDetails->pk_description == 'Building')
                 <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height: 15px; width: 15px; vertical-align: middle;margin-right: 15px;">
                  @else
                  <img src="{{ asset('assets/images/checkbox-unchecked.jpg') }}" style="height:15px; width:15px; vertical-align: middle;  margin-right: 15px;">
                  @endif
                  BUILDING<br><small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No. of Storey</small>
            </td>
            <td style="width: 25%; padding: 2px 5px 1px 5px; vertical-align:bottom; text-align: left; border:none; border-bottom: 1px solid black; ">{{(isset($rptProperty->propertyKindDetails->pk_code) && $rptProperty->propertyKindDetails->pk_code == 'B')?$rptProperty->floorValues->count():''}}</td>
            <td style="width: 28%; padding: 2px 5px 2px 80px; border: none; vertical-align:middle; text-align: left;">
                 @if(isset($rptProperty->propertyKindDetails->pk_description) && $rptProperty->propertyKindDetails->pk_description == 'Others')
                  <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height: 15px; width: 15px; vertical-align: middle;margin-right: 15px;">
                  @else
                  <img src="{{ asset('assets/images/checkbox-unchecked.jpg') }}" style="height:15px; width:15px; vertical-align: middle;  margin-right: 15px;">
                  @endif
                  OTHERS<br><small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Specify</small>
            </td>
            <td style="width: 22%; padding: 2px 5px 2px 5px; vertical-align:middle; text-align: left; border:none; border-bottom: 1px solid black;"></td>
        </tr>
        <tr>
            
            <td style="width: 25%; padding: 0px 5px 2px 80px; border: none; vertical-align:middle; text-align:left;">&nbsp;<br><small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Brief Description</small>
            </td>
            <td style="width: 25%; padding: 2px 5px 1px 5px; vertical-align:bottom; text-align: left; border:none; border-bottom: 1px solid black; ">{{(isset($rptProperty->propertyKindDetails->pk_code) && $rptProperty->propertyKindDetails->pk_code == 'B' && isset($rptProperty->rptBuildingKindDetails->bk_building_kind_desc))?$rptProperty->rptBuildingKindDetails->bk_building_kind_desc:''}}</td>
            <td style="width: 28%; padding: 0px 5px 2px 80px; border: none; vertical-align:middle; text-align: left;">&nbsp;</td>
            <td style="width: 22%; padding: 2px 5px 2px 5px; vertical-align:middle; text-align: left; border:none;">&nbsp;</td>
        </tr>
    </table>

    <table style="width:100%; border-collapse:separate;">
        <tr>
            <td style="border: none; text-align: center; padding: 5px 5px 0px 5px; border-bottom: 1px solid black;">
                Classification
            </td>
            <td style="border: none; text-align: center; padding: 5px 5px 0px 5px; border-bottom: 1px solid black;">
                Area
            </td>
            <td style="border: none; text-align: center; padding: 5px 5px 0px 5px; border-bottom: 1px solid black;">
                Market Value
            </td>
            <td style="border: none; text-align: center; padding: 5px 5px 0px 5px; border-bottom: 1px solid black;">
                Actual Use
            </td>
            <td style="border: none; text-align: center; padding: 5px 5px 0px 5px; border-bottom: 1px solid black;">
                Assessment<br>Level
            </td>
            <td style="border: none; text-align: center; padding: 5px 5px 0px 5px; border-bottom: 1px solid black;">
                Assessed Value
            </td>
        </tr>
        @php
          $selectedMesuare = [];
          @endphp
          @foreach($RptPropertyAppraisals as $RptPropertyAppraisal)
          @php
          if($RptPropertyAppraisal->lav_unit_measure == 1){
                       $selectedMesuare[] = 's';
                  }else{
                       $selectedMesuare[] = 'h';
                  }
          @endphp
          @endforeach
        @php
        //dd($selectedMesuare);
            $t_area=0;
            $t_market_val=0;
            $t_assesed_val=0;
            $measureIn = '';
            @endphp
            @foreach($RptPropertyAppraisals as $RptPropertyAppraisal)
            @php
            if($RptPropertyAppraisal->lav_unit_measure == 1){
                 $newArea = $RptPropertyAppraisal->rpa_total_land_area/10000;
            }else{
                 $newArea = $RptPropertyAppraisal->rpa_total_land_area;
            }

            if(count(array_unique($selectedMesuare)) == 2){
                       $newArea = $newArea;
                       $measureIn = '2';
                  }else{
                    if(isset(array_unique($selectedMesuare)[0]) && array_unique($selectedMesuare)[0] == 's'){
                       $newArea = $RptPropertyAppraisal->rpa_total_land_area;
                       $measureIn = '1';
                  }if(isset(array_unique($selectedMesuare)[0]) && array_unique($selectedMesuare)[0] == 'h'){
                       $newArea = $RptPropertyAppraisal->rpa_total_land_area;
                       $measureIn = '2';
                  }
                }

            $t_area=$t_area+$newArea;
            $t_market_val=$t_market_val+$RptPropertyAppraisal->rpa_base_market_value;
            $t_assesed_val=$t_assesed_val+$RptPropertyAppraisal->rpa_assessed_value;
        @endphp
        <tr>
            <td style="border: none;text-align: center; padding: 5px 5px 0px 5px; border-bottom: 1px solid black;">
                {{$RptPropertyAppraisal->getPcClassDescriptionAttribute()}}
            </td>
            <td style="border: none;text-align: center; padding: 5px 5px 0px 5px; border-bottom: 1px solid black;">{{($RptPropertyAppraisal->lav_unit_measure ==1 )?number_format($RptPropertyAppraisal->rpa_total_land_area,3):number_format($RptPropertyAppraisal->rpa_total_land_area,4)}} {{ config('constants.lav_unit_measure_short.'.$RptPropertyAppraisal->lav_unit_measure) }}
            </td>
            <td style="border: none; text-align: right;padding: 5px 5px 0px 5px; border-bottom: 1px solid black; ">
                {{Helper::decimal_format($RptPropertyAppraisal->rpa_base_market_value)}}
            </td>
            <td style="border: none; text-align: center;padding: 5px 5px 0px 5px; border-bottom: 1px solid black;">{{$RptPropertyAppraisal->getPauActualUseDescAttribute()}}</td>
            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 1px solid black; text-align: center;">
                {{number_format((float)$RptPropertyAppraisal->al_assessment_level, 0, '.', '')}}%
            </td>
            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 1px solid black; text-align: right;">
                {{Helper::decimal_format($RptPropertyAppraisal->rpa_assessed_value)}}
            </td>
        </tr>
        
        @endforeach
        @if(isset($rptProperty->propertyKindDetails->pk_code) && $rptProperty->propertyKindDetails->pk_code == 'B')
        @php
            
            @endphp
            @php
            $t_area = $rptProperty->floorValues->sum('rpbfv_floor_area');
            $t_market_val = $rptProperty->rp_market_value;
            $t_assesed_val = $rptProperty->rp_assessed_value;
            
            
        @endphp
        <tr>
            <td style="border: none;text-align: center; padding: 5px 5px 0px 5px; border-bottom: 1px solid black;">
                {{(isset($rptProperty->propertyClass->pc_class_description))?$rptProperty->propertyClass->pc_class_description:''}}
            </td>
            <td style="border: none;text-align: center; padding: 5px 5px 0px 5px; border-bottom: 1px solid black;">{{number_format((float)$rptProperty->floorValues->sum('rpbfv_floor_area'), 3, '.', '')}} Sq. m.
            </td>
            <td style="border: none; text-align: right;padding: 5px 5px 0px 5px; border-bottom: 1px solid black; ">
                {{Helper::decimal_format($rptProperty->rp_market_value)}}
            </td>
            <td style="border: none; text-align: center;padding: 5px 5px 0px 5px; border-bottom: 1px solid black;">{{(isset($rptProperty->floorValues[0]->actualUses->pau_actual_use_desc))?$rptProperty->floorValues[0]->actualUses->pau_actual_use_desc:''}}</td>
            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 1px solid black; text-align: center;">
                {{number_format((float)$rptProperty->floorValues[0]->al_assessment_level, 0, '.', '')}}%
            </td>
            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 1px solid black; text-align: right;">
                {{Helper::decimal_format($rptProperty->rp_assessed_value)}}
            </td>
        </tr>
        
       
        @endif

        <!--  machinery  -->
        @if($RptPropertyMachineAppraisal)
        @php
            $RptPropertyMachineAppraisal = $RptPropertyMachineAppraisal->groupBy('pc_class_code');
            @endphp
            @foreach($RptPropertyMachineAppraisal as $key => $machinery)
            @php
            //dd($machinery);
            $t_market_val=$rptProperty->rp_market_value_adjustment;
            $t_assesed_val=$rptProperty->rp_assessed_value;
        @endphp
        <tr>
            <td style="border: none; padding: 5px 5px 0px 5px; text-align:center;border-bottom: 1px solid black;">{{(isset($machinery[0]->pc_class_description))?$machinery[0]->pc_class_description:''}}
            </td>
            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 1px solid black;text-align:center;">
            </td>
            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 1px solid black; text-align: right;">
                {{Helper::decimal_format($rptProperty->rp_market_value_adjustment)}}
            </td>
            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 1px solid black;"></td>
            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 1px solid black; text-align: center;">{{(isset($machinery[0]->al_assessment_level))?number_format($machinery[0]->al_assessment_level, 0, '.', ''):''}}%
            </td>
            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 1px solid black; text-align: right;">{{Helper::decimal_format($rptProperty->rp_assessed_value)}}
            </td>
        </tr>
        @endforeach

        <tr>
            <td style="border: none; padding: 5px 5px 0px 5px; text-align:center;border-bottom: 1px solid black;"></td>

            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 1px solid black;text-align: center;"></td>
            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 1px solid black; text-align: right;"></td>
            <td style="border: none; padding: 5px 5px 0px 5px;border-bottom: 1px solid black;">&nbsp;</td>
            <td style="border: none; padding: 5px 5px 0px 5px;border-bottom: 1px solid black;">&nbsp;</td>
            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 1px solid black; text-align: right;"></td>
        </tr>
        <tr>
            <td style="border: none; padding: 5px 5px 0px 5px; text-align:center;border-bottom: 1px solid black;"></td>

            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 1px solid black;text-align: center;"></td>
            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 1px solid black; text-align: right;"></td>
            <td style="border: none; padding: 5px 5px 0px 5px;border-bottom: 1px solid black;">&nbsp;</td>
            <td style="border: none; padding: 5px 5px 0px 5px;border-bottom: 1px solid black;">&nbsp;</td>
            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 1px solid black; text-align: right;"></td>
        </tr>


        <tr s>
            <td style="border: none; padding: 5px 5px 0px 5px; text-align:center;">
                <strong>Total</strong>
            </td>

            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 2px solid black;text-align: center;">
                <strong>{{(isset($rptProperty->propertyKindDetails->pk_code) && $rptProperty->propertyKindDetails->pk_code == 'M')?'':((isset($rptProperty->propertyKindDetails->pk_code) && $rptProperty->propertyKindDetails->pk_code == 'B')?number_format((float)$t_area, 3, '.', ''):number_format((float)$t_area, (($measureIn == 2)?4:3), '.', ''))}}{{(isset($rptProperty->propertyKindDetails->pk_code) && $rptProperty->propertyKindDetails->pk_code == 'B')?' Sq. m.':(($rptProperty->propertyKindDetails->pk_code == 'L')?' '.(($measureIn == 1)?config('constants.lav_unit_measure_short.1'):config('constants.lav_unit_measure_short.2')):'')}}
                </strong></td>
            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 2px solid black; text-align: right;">
               <strong> {{Helper::decimal_format($t_market_val)}}</strong>
            </td>
            <td style="border: none; padding: 5px 5px 0px 5px;">&nbsp;</td>
            <td style="border: none; padding: 5px 5px 0px 5px;">&nbsp;</td>
            <td style="border: none; padding: 5px 5px 0px 5px; border-bottom: 2px solid black; text-align: right;">
                @php //dd() @endphp
                <strong>{{Helper::decimal_format($t_assesed_val)}}</strong>
            </td>
        </tr>
    </table>
    @else
    @endif
    <table style="width:100%;">
        <tr>
            @php

            $t_assesed_val_words = Helper::numberToWord(number_format((float)sprintf("%.2f", floor($t_assesed_val * 100) / 100), 2, '.', ''));

            @endphp
            <td style="width: 18%; padding: 0px 5px 0px 5px; border: none; vertical-align:bottom;">Total Assessed Value</td>
            <td style="padding: 5px 5px 0px 5px; border: none; vertical-align:bottom; text-align: left; border-bottom: 1px solid black;">
                {{$t_assesed_val_words}}
            </td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 0px 5px 0px 5px; border: none; vertical-align:bottom;">&nbsp;</td>
            <td style="padding: 1px 5px 0px 0px; border: none; vertical-align:bottom; text-align: center;">
                <small>Amount in Words</small>
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-top:10px;">
        <tr>
            <td style="width: 15%; border: none; padding: 2px 5px 2px 5px; vertical-align: middle;">
                Taxable 
                @if($rptProperty->rp_app_taxability == 1)
                  <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 5px;">
                  @else
                  <img src="{{ asset('assets/images/checkbox-unchecked.jpg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 5px;">
                  @endif
            </td>
            <td style="width: 15%; border: none; padding: 2px 5px 2px 5px; text-align:right;">
                Exempt 
                 @if($rptProperty->rp_app_taxability == 0)
                  <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 5px;">
                  @else
                  <img src="{{ asset('assets/images/checkbox-unchecked.jpg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 5px;">
                  @endif
            </td>
            <td style="border: none; padding: 2px 5px 2px 5px; text-align:center;">
                Effectivity of Assessment
            </td>
            <td style="width: 15%; border: none; padding: 2px 5px 2px 5px; text-align:center; border-bottom: 1px solid black;">
                @if($rptProperty->rp_app_effective_quarter == 1)
                1st
                @elseif($rptProperty->rp_app_effective_quarter == 2)
                2nd
                @elseif($rptProperty->rp_app_effective_quarter == 3)
                3rd
                @elseif($rptProperty->rp_app_effective_quarter == 4)
                4th
                @endif
                </td>
            <td style="width: 15%; border: none; padding: 2px 5px 2px 5px; text-align:center; border-bottom: 1px solid black;">{{$rptProperty->rp_app_effective_year}}</td>
        </tr>
        <tr>
            <td colspan="3" style="border: none; padding: 2px 5px 2px 5px; text-align:center;">
                &nbsp;
            </td>
            <td style="width: 15%; border: none; padding: 2px 5px 2px 5px; text-align:center;"><small>(Qtr)</small></td>
            <td style="width: 15%; border: none; padding: 2px 5px 2px 5px; text-align:center;"><small>(Year)</small></td>
        </tr>
    </table>

    <table style="width:100%;">
        <tr>
            <td style="width: 50%; border: none; text-align: left;">RECOMMENDED BY:</td>
            <td style="width: 50%; border: none; text-align: left;">APPROVED BY:</td>
        </tr>
    </table>

    <table style="width:100%;">
        <tr>
            <td style="border: none; text-align: center; border-bottom: 1px solid black; padding-bottom: 0px;"><strong>{{(isset($recommendPosition->title))?$recommendPosition->title:''}}</strong></td>
            <td style="border: none; text-align: center; border-bottom: 1px solid black; padding-bottom: 0px;"><strong>
                {{($recommendPosition->is_sgd == 1)?'SGD ':''}}{{ (isset($recommendPosition->standard_name))?$recommendPosition->standard_name:''}}
            </strong></td>
            <td style="border: none; text-align: center; border-bottom: 1px solid black; padding-bottom: 0px;">{{(!empty($RptPropertyApproval->rp_app_recommend_date)  ? date("m/d/Y",strtotime($RptPropertyApproval->rp_app_recommend_date)) : "")}}</td>
            <td style="width: 3%; border: none; text-align: center; padding-bottom: 0px;">&nbsp;</td>
            <td style="border: none; text-align: center; border-bottom: 1px solid black; padding-bottom: 0px;"><strong>{{(isset($approvedPosition->title))?$approvedPosition->title:''}}</strong></td>
            <td style="border: none; text-align: center; border-bottom: 1px solid black; padding-bottom: 0px;"><strong>
				{{($approvedPosition->is_sgd == 1)?'SGD ':''}}{{(isset($approvedPosition->standard_name))?$approvedPosition->standard_name:''}}
            </strong></td>
            <td style="border: none; text-align: center; border-bottom: 1px solid black; padding-bottom: 0px;">{{(!empty($RptPropertyApproval->rp_app_approved_date)  ? date("m/d/Y",strtotime($RptPropertyApproval->rp_app_approved_date)) : "")}}</td>
        </tr>
        <tr>
            <td style="border: none; text-align: center; padding-bottom: 0px; padding-top: 0px;">
                <small>&nbsp;</small>
            </td>
            <td style="border: none; text-align: center; padding-bottom: 0px; padding-top: 0px;">
                <small>{{(isset($recommendPosition->description))?$recommendPosition->description:''}} </small>
            </td>
            <td style="border: none; text-align: center; padding-bottom: 0px; padding-top: 0px;">
                <small>Date</small>
            </td>
            <td style="width: 3%; border: none; text-align: center; padding-bottom: 0px; padding-top: 0px;">&nbsp;</td>
            <td style="border: none; text-align: center; padding-bottom: 0px; padding-top: 0px;">
                <small>&nbsp;</small>
            </td>
            <td style="border: none; text-align: center; padding-bottom: 0px; padding-top: 0px;">
                <small>{{(isset($approvedPosition->description))?$approvedPosition->description:''}} </small>
            </td>
            <td style="border: none; text-align: center; padding-bottom: 0px; padding-top: 0px;">
                <small>Date</small>
            </td>
        </tr>
    </table>

    <table style="width:100%;">
        <tr>
            <td style="width: 26%; padding: 5px 5px 0px 5px; border: none; vertical-align:bottom;">This declaration cancels TD No.</td>
            <td style="padding: 5px 5px 0px 5px; border: none; border-bottom: 1px solid black; vertical-align:bottom;">{{!empty($cancelTdByThis) ? $cancelTdByThis : ""}}</td>
        </tr>
    </table>

    <table style="width:100%;">
        <tr>
            <td style="width: 8%; padding: 5px 5px 0px 5px; border: none; vertical-align:bottom;">Owner</td>
            <td style="padding: 5px 5px 0px 5px; border: none; border-bottom: 1px solid black; vertical-align:bottom;">{{$cancelTdByOwner}}</td>
            <td style="width: 13%; padding: 5px 5px 0px 5px; border: none; vertical-align:bottom;">Prev A. V. Php</td>
            <td style="width: 20%; padding: 5px 5px 0px 5px; border: none; text-align: center; border-bottom: 1px solid black; vertical-align:bottom;">{{$cacnceledTdAssValue}}</td>
        </tr>
    </table>

    <table style="width:100%;">
        <tr>
            <td style="width: 100%; border: none; text-align: left; padding: 0px; padding-top: 10px;">MEMORANDA</td>
        </tr>
        <tr>
            <td style="width: 100%; text-align: left; padding: 0px; padding-bottom:60px;">{{!empty($rptProperty->rp_app_memoranda) ? strtoupper($rptProperty->rp_app_memoranda) : ""}}<br />@if($annotation != '')<span style="font-weight: bold; margin-left:5px;">Annotation:</span><br />{{$annotation}}@endif</td>
        </tr>
        <tr>
            <td style="border: none; padding: 0px; padding-top: 5px;">
                <p>
                    Notes: This declaration is real property taxation purpose only and the valuation indicated herein are based on the schedule of unit values prepared for the purpose and duly enacted into an Ordinance by the Sanggunian Panlungsod. It does not and cannot by itself alone can offer ownership or legal title to the property.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>







