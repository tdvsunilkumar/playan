<table width="100%" style="margin-top:5px; margin-bottom:5px;">
          <tr>
              <td style="border:0px solid black; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; width: 50%;">
                  <h4 style="padding:0px">PROPERTY BOUNDARIES</h4>
              </td>
          </tr>
      </table>

      <table width="100%">
          <tr>
              <td style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 10px;">
                  North: {{strtoupper($rptProperty->rp_bound_north)}}
              </td>
              <td rowspan="7" style="width: 50%; padding:2px; vertical-align: top;  border-bottom: 0;">
                  Land Sketch:
              </td>
          </tr>
          <tr>
              <td style="width: 50%; border-bottom: 0; border-top:0; padding:2px; vertical-align: top;">
                  &nbsp;
              </td>
          </tr>
          <tr>
              <td style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 20px; vertical-align: top;">
                  East: {{strtoupper($rptProperty->rp_bound_east)}}
              </td>
          </tr>
          <tr>
              <td style="width: 50%; border-top: 0; padding:2px; text-align: left; vertical-align: bottom;"></td>
          </tr>
          <tr>
              <td style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 20px; vertical-align: top;">
                  South: {{strtoupper($rptProperty->rp_bound_south)}}
              </td>
          </tr>
          <tr>
              <td style="width: 50%; border-top: 0; padding:2px; text-align: left; vertical-align: bottom;">
              
              </td>
          </tr>
          <tr>
              <td style="width: 50%; border-bottom: 0; padding:2px; padding-bottom: 10px; vertical-align: top;">
                  West: {{strtoupper($rptProperty->rp_bound_west)}}
              </td>
          </tr>
          <tr>
              <td rowspan="2" style="width: 50%; border-top: 0; padding:2px; text-align: left; vertical-align: bottom;">
              
              </td>
              <td style="width: 50%; border-top: 0; padding:2px; text-align: left; vertical-align: bottom; text-align: center;">
                  (Not necessarly drawn to scale)
              </td>
          </tr>
      </table>