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
          @foreach($floorRoofWallData as $stuc)

          <tr>
              <td>{{(isset($stuc['roof']['desc']))?$stuc['roof']['desc']:''}}</td>
              <td>
                @if((isset($stuc['roof']['id'])) && $rptProperty->rbf_building_roof_desc1 == $stuc['roof']['id'])
                <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 20px;">
                @else
                @endif
                 @if((isset($stuc['roof']['id'])) && $rptProperty->rbf_building_roof_desc2 == $stuc['roof']['id'])
                <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 20px;">
                @else
                @endif
                 @if((isset($stuc['roof']['id'])) && $rptProperty->rbf_building_roof_desc3 == $stuc['roof']['id'])
                <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 20px;">
                @else
                @endif
              </td>
              
              <td>{{(isset($stuc['floor']['desc']))?$stuc['floor']['desc']:''}}</td>
              <td> @if((isset($stuc['floor']['id'])) && $rptProperty->rbf_building_floor_desc1 == $stuc['floor']['id'])
                <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 20px;">
                @else
                @endif</td>
              <td> @if((isset($stuc['floor']['id'])) && $rptProperty->rbf_building_floor_desc2 == $stuc['floor']['id'])
                <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 20px;">
                @else
                @endif</td>
              <td> @if((isset($stuc['floor']['id'])) && $rptProperty->rbf_building_floor_desc3 == $stuc['floor']['id'])
                <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 20px;">
                @else
                @endif</td>
              <td> </td>
              <td>{{(isset($stuc['wall']['desc']))?$stuc['wall']['desc']:''}}</td>
              <td>
                @if((isset($stuc['wall']['id'])) && $rptProperty->rbf_building_wall_desc1 == $stuc['wall']['id'])
                <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 20px;">
                @else
                @endif
              </td>
              <td>
                @if((isset($stuc['wall']['id'])) && $rptProperty->rbf_building_wall_desc2 == $stuc['wall']['id'])
                <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 20px;">
                @else
                @endif
              </td>
              <td>
                @if((isset($stuc['wall']['id'])) && $rptProperty->rbf_building_wall_desc3 == $stuc['wall']['id'])
                <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 20px;">
                @else
                @endif
              </td>
              <td></td>
          </tr>
          @endforeach
      </table>