<table width="100%" style="margin-top:5px; margin-bottom:5px;">
          <tr>
              <td style="border:0px solid black; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; width: 10%;">
                  <h4 style="padding:0px">LAND APPRAISAL</h4>
              </td>
          </tr>
      </table>

      <table width="100%">
          <tr>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">Classification</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">Sub-Classification</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">Area</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">Unit Value</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">Base Market Value</td>
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
                $t_area=0;
                $t_market_val=0;
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
                  //dd($selectedMesuare);
                $t_area=$t_area+$newArea;
                $t_market_val=$t_market_val+$RptPropertyAppraisal->rpa_base_market_value;
                @endphp
          <tr>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{$RptPropertyAppraisal->pc_class_description }}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{$RptPropertyAppraisal->getPsSubclassDescAttribute()}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{($RptPropertyAppraisal->lav_unit_measure ==1 )?number_format($RptPropertyAppraisal->rpa_total_land_area,3):number_format($RptPropertyAppraisal->rpa_total_land_area,4)}} {{ config('constants.lav_unit_measure_short.'.$RptPropertyAppraisal->lav_unit_measure) }}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{Helper::decimal_format($RptPropertyAppraisal->lav_unit_value)}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{Helper::decimal_format($RptPropertyAppraisal->rpa_base_market_value)}}</td>
          </tr>
          @endforeach

           <tr>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; ">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;  border-left: none; letter-spacing:5px;"></td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;  border-left: none;"></td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;  border-left: none;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-left: none;"></td>
          </tr>
          
          <tr>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none; border-left: none; letter-spacing:5px;"><strong>TOTAL</strong></td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none; border-left: none;">{{number_format($t_area, ($measureIn == 2)?4:3, '.', ',')}} {{($measureIn == 1)?config('constants.lav_unit_measure_short.1'):config('constants.lav_unit_measure_short.2')}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none; border-left: none;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-left: none;"><strong>{{Helper::decimal_format($t_market_val)}}</strong></td>
          </tr>
      </table>

      <table width="100%" style="margin-top:5px; margin-bottom:5px;">
          <tr>
              <td style="border:0px solid black; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; width: 10%;">
                  <h4 style="padding:0px">PLANTS AND TREES APPRAISAL</h4>
              </td>
          </tr>
      </table>

      <table width="100%">
          <tr>
              <td rowspan="3" style="padding-top:2px; padding-bottom:2px; text-align: center; vertical-align: middle;">Kind of Plants/Trees</td>
              <td rowspan="3" style="padding-top:2px; padding-bottom:2px; text-align: center; vertical-align: middle;">Sub-<br>Class/<br>Age</td>
              <td colspan="5" style="padding-top:2px; padding-bottom:2px; text-align: center; vertical-align: middle;">NUMBER OF TREES PLANTED</td>
              <td rowspan="3" style="padding-top:2px; padding-bottom:2px; text-align: center; vertical-align: middle;">Unit Value</td>
              <td rowspan="3" style="padding-top:2px; padding-bottom:2px; text-align: center; vertical-align: top;">Base Market<br>Value</td>
          </tr>
          <tr>
              <td rowspan="2" style="padding-top:2px; padding-bottom:2px; text-align: center;">Non-Fruit<br>Bearing</td>
              <td colspan="4" style="padding-top:2px; padding-bottom:2px; text-align: center;">Fruit-bearing</td>
          </tr>
          <tr>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">Productive</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; width: 10%;"></td>
              <td colspan="2" style="padding-top:2px; padding-bottom:2px; text-align: center;"> Not Productive</td>
          </tr>
                @php
                $t_market_val=0;
                @endphp
                @foreach($RptPlantTreesAppraisals as $RptPlantTreesAppraisal)
                @php
                //dd($RptPlantTreesAppraisal);
                $t_market_val=$t_market_val+$RptPlantTreesAppraisal->rpta_market_value;
                @endphp
          <tr>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{$RptPlantTreesAppraisal->palntTreeCode->pt_ptrees_description}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{$RptPlantTreesAppraisal->getPsSubclassDescAttribute()}}/{{ date("Y")-$RptPlantTreesAppraisal->rpta_date_planted }} Years</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{number_format((float)$RptPlantTreesAppraisal->rpta_non_fruit_bearing, 2, '.', '')}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{number_format((float)$RptPlantTreesAppraisal->rpta_fruit_bearing_productive, 2, '.', '')}} </td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{number_format((float)$RptPlantTreesAppraisal->rpta_total_area_planted, 2, '.', '')}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{--$RptPlantTreesAppraisal->getPsSubclassDescAttribute()--}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{--$RptPlantTreesAppraisal->rpta_fruit_bearing_non_productive--}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{Helper::decimal_format($RptPlantTreesAppraisal->rpta_unit_value)}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{Helper::decimal_format($RptPlantTreesAppraisal->rpta_market_value)}}</td>
          </tr>
          @endforeach
          <tr>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none; border-bottom:none;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none; border-left: none; border-bottom:none;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none; border-left: none; border-bottom:none;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none; border-left: none; border-bottom:none;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none; border-left: none; border-bottom:none;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none; border-left: none; border-bottom:none;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none; border-left: none; border-bottom:none;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none; border-left: none; border-bottom:none;">Sub-Total</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-left: none; border-bottom:none;">&nbsp;</td>
          </tr>
          <tr>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none; border-top:none;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none; border-left:none; border-top:none;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none; border-left:none; border-top:none;">&nbsp;</td>
              <td colspan="4" style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none; border-left:none; border-top:none;">TOTAL (Land, Plants & Trees)</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none; border-left:none; border-top:none;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-top:none; border-left:none;"><strong>{{Helper::decimal_format($t_market_val)}}</strong></td>
          </tr>
      </table>
      <div style="page-break-before: always;"></div>
      <table width="100%" style="margin-top:5px; margin-bottom:5px;">
          <tr>
              <td style="border:0px solid black; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; width: 10%;">
                  <h4 style="padding:0px">VALUE ADJUSTMENT</h4>
              </td>
          </tr>
      </table>

      <table width="100%">
          <tr>
              <td rowspan="2" colspan="2" style="padding-top:2px; padding-bottom:2px; text-align: center; border-bottom: none;">Base Market<br> Value</td>
              <td colspan="3" style="padding-top:2px; padding-bottom:2px; text-align: center; border-bottom: none;">Adjustment Factors[%]</td>
              <td rowspan="2" style="padding-top:2px; padding-bottom:2px; text-align: center; border-bottom: none;">Adjustment[%]</td>
              <td rowspan="2" colspan="2" style="padding-top:2px; padding-bottom:2px; text-align: center; border-bottom: none;">Value Adjustment</td>
              <td rowspan="2" style="padding-top:2px; padding-bottom:2px; text-align: center; border-bottom: none;">Market Value</td>
          </tr>
                
          <tr>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none;">(a)</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-left: none; border-right: none;">(b)</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-left: none;">(c)</td>
          </tr>
                @php
                $t_market_val=0;
                @endphp
                @foreach($RptPropertyAppraisals as $RptPropertyAppraisal)
                @php
                $mar_value=$RptPropertyAppraisal->rpa_base_market_value + $RptPropertyAppraisal->rpa_adjustment_value;
                //$t_market_val=$t_market_val+$mar_value;
                @endphp
          <tr>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none;"></td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-left: none;">{{Helper::decimal_format($RptPropertyAppraisal->rpa_base_market_value)}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none;">&nbsp; {{Helper::decimal_format($RptPropertyAppraisal->rpa_adjustment_factor_a)}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-left: none; border-right: none;">{{Helper::decimal_format($RptPropertyAppraisal->rpa_adjustment_factor_b)}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-left: none;">{{Helper::decimal_format($RptPropertyAppraisal->rpa_adjustment_factor_c)}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;"> {{Helper::decimal_format($RptPropertyAppraisal->rpa_adjustment_percent)}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: left; border-right: none;"></td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-left: none;">{{Helper::decimal_format($RptPropertyAppraisal->rpa_adjustment_value)}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">
                @php
                $percent = (isset($RptPropertyAppraisal->rpa_adjustment_percent) && $RptPropertyAppraisal->rpa_adjustment_percent > 0)?$RptPropertyAppraisal->rpa_adjustment_percent:0;
                $adjustment = $RptPropertyAppraisal->rpa_adjustment_value;
                if($percent < 100){
                  $adjustment = -1*$adjustment;
                }
                $adjustedMarketValue = $RptPropertyAppraisal->rpa_base_market_value + $adjustment;
                $t_market_val=$t_market_val+$adjustedMarketValue;
                @endphp
                {{Helper::decimal_format($adjustedMarketValue)}}</td>
          </tr>
          @endforeach
          <tr>
              <td style="padding-top:2px; padding-bottom:2px; text-align: left; border-right: none;"></td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: right; border-left: none; border-right: none;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-left: none; border-right: none;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-left: none; border-right: none;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-left: none; border-right: none;">Total</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-left: none; border-right: none;"></td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: left; border-left: none; border-right: none;"></td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: right; border-left: none;border-right: none;">&nbsp;</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;border-left: none;"><strong>{{Helper::decimal_format($t_market_val)}}</strong></td>
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
                @php
                    $t_assessed_val=0;
                    @endphp
                    @foreach($RptPropertyAppraisals as $RptPropertyAppraisal)
                    @php
                                        $adjustValue = $RptPropertyAppraisal->rpa_adjusted_total_planttree_market_value;
                                        if($adjustValue<=0){
                                            $adjustValue = $RptPropertyAppraisal->rpa_adjusted_market_value;
                                        }
                                    @endphp
                    @php
                    $t_assessed_val=$t_assessed_val+$RptPropertyAppraisal->rpa_assessed_value;
                @endphp
          <tr>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{$RptPropertyAppraisal->pau_actual_use_desc}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{Helper::decimal_format($adjustValue)}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{number_format($RptPropertyAppraisal->al_assessment_level, 0, '.', '')}}%</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{Helper::decimal_format($RptPropertyAppraisal->rpa_assessed_value)}}</td>
          </tr>
          @endforeach
          
          <tr>
              <td style="padding-top:2px; padding-bottom:2px; padding-right: 40px; text-align: right; border-right: none;">Total</td>

              <td style="padding-top:2px; padding-bottom:2px; text-align: left; border-left: none; border-right: none;">P</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-right: none; letter-spacing:5px">TOTAL</td>

              <td style="padding-top:2px; padding-bottom:2px; text-align: center; border-left: none;"><strong>{{Helper::decimal_format($t_assessed_val)}}</strong></td>
          </tr>
      </table>