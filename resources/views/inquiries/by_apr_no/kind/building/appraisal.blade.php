<table width="100%" style="padding:0px">
          <tr>
              <td style="border:1px solid black; width: 100%; border-bottom: 0px;">
                  <h4 style="padding:0px">PROPERTY APPRAISAL</h4>
              </td>
          </tr>
      </table>

      <table width="100%" style="padding:0px;">
          <tr>
              <td colspan="3" style="padding:2px 5px; border-bottom: none; width:75%;">Unit Construction Cost: P <span style="text-decoration:underline;">{{isset($rptProperty->floorValues[0]->rpbfv_floor_unit_value)?Helper::decimal_format($rptProperty->floorValues[0]->rpbfv_floor_unit_value):0}}&nbsp;&nbsp;&nbsp;</span>/sq.m.</td>
              <td style="padding:2px 5px; border-bottom: none; width:25%;">Cost of Additional Items:</td>
          </tr>
          <tr>
              <td colspan="3" style="padding:2px 5px; border-bottom: none; border-top:none;">Building Core: (Use additional sheets if necessary)</td>
              <td style="padding:2px 5px; border-bottom: none; border-top:none; text-align:right;">-</td>
          </tr>
          <tr>
              <td colspan="3" style="padding:2px 5px; border-bottom: none; border-top:none;">&nbsp;</td>
              <td style="padding:2px 5px; border-bottom: none; border-top:none;">Adjustments:</td>
          </tr>
          <tr>
              <td colspan="2" style="padding:2px 5px; border-bottom: none; border-top:none; border-right: none; text-align:right;">{{Helper::area_format($rptProperty->floorValues->sum('rpbfv_floor_area'))}} x {{isset($rptProperty->floorValues[0]->rpbfv_floor_unit_value)?Helper::decimal_format($rptProperty->floorValues[0]->rpbfv_floor_unit_value):0}} = </td>
              <td style="padding:2px 5px; border-bottom: none; border-top:none; border-left: none; text-align:center;">{{Helper::decimal_format($rptProperty->floorValues->sum('rpbfv_floor_base_market_value'))}}</td>
              <td style="padding:2px 5px; border-bottom: none; border-top:none; text-align:right;">-</td>
          </tr>
          <tr>
              <td colspan="2" rowspan="4" style="padding:2px 5px; border-bottom: none; border-top:none; border-right: none; vertical-align: bottom;">Sub-Total</td>
              <td rowspan="4" style="padding:2px 5px; border-bottom: none; border-top:none; vertical-align: bottom; text-align: right; border-left:none;">{{Helper::decimal_format($rptProperty->floorValues->sum('rpbfv_floor_base_market_value'))}}</td>
              <td style="padding:2px 5px; border-bottom: none; border-top:none;">Total Construction Cost:</td>
          </tr>
          <tr>
              <td style="padding:2px 5px; border-bottom: none; border-top:none; text-align:right;">{{Helper::decimal_format($rptProperty->floorValues->sum('rpbfv_total_floor_market_value'))}}</td>
          </tr>
          <tr>
              <td style="padding:2px 5px; border-bottom: none; border-top:none;">Depreciated Value: P</td>
          </tr>
          <tr>
              <td style="padding:2px 5px; border-bottom: none; border-top:none; text-align:right;">{{Helper::decimal_format($rptProperty->rp_accum_depreciation)}}</td>
          </tr>
          <tr>
              <td colspan="2" style="padding:2px 5px; border-bottom: none;">Depreciation Rate: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{Helper::decimal_format($rptProperty->rp_depreciation_rate)}}%</td>
              <td style="padding:2px 5px; border-bottom: none;">Total % Depreciation:</td>
              <td style="padding:2px 5px; border-bottom: none; border-top:none;">Market Value P</td>
          </tr>
          <tr>
              <td colspan="3" style="padding:2px 5px;">Depriciation Cost: P &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Helper::decimal_format($rptProperty->rp_accum_depreciation)}}</td>
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
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{ isset($rptProperty->floorValues[0]->actualUses->pau_actual_use_desc)?$rptProperty->floorValues[0]->actualUses->pau_actual_use_desc:''}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{Helper::decimal_format($rptProperty->rpb_accum_deprec_market_value)}}</td>
              <td style="padding-top:2px; padding-bottom:2px; text-align: center;">{{number_format($rptProperty->al_assessment_level, 0, '.', '')}}%</td>
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
                          <!-- <td style="padding:0px; text-align:left; border:none;">P</td> -->
                          <td style="padding:0px; text-align:right; border:none;">{{Helper::decimal_format($rptProperty->rpb_assessed_value)}}</td>
                      <tr>
                  </table>
              </td>
          </tr>
      </table>