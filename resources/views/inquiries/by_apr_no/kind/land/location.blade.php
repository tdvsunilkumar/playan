<table width="100%" style="margin-top:5px; margin-bottom:5px;">
          <tr>
              <td style="border:0px solid black; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; width: 10%;">
                  <h4 style="padding:0px">PROPERTY LOCATION</h4>
              </td>
          </tr>
      </table>

      <table width="100%" border="none">
          <tr>
              <td width="50%" style="padding-bottom:10px;">No./Street: {{$rptProperty->rp_location_number_n_street}}</td>
              <td width="50%" style="padding-bottom:10px;">Brgy: {{$rptProperty->barangay->brgy_name}}</td>
          </tr>
          <tr>
              <td width="50%" style="border-top: 0; padding-bottom:10px;">District: {{$rptProperty->dist_code}} </td>
              <td width="50%" style="border-top: 0; padding-bottom:10px;">City: {{$rptProperty->locality->mun_desc}}</td>
          </tr>
      </table>