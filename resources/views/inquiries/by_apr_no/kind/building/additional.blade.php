<table width="100%" style="padding:0px; margin-top: 10px;">
          <tr>
              <td style="border:1px solid black; width: 100%; border-bottom: 0px;">
                  <h4 style="padding:0px">ADDITIONAL ITEMS (Use additional sheet if necessary)</h4>
              </td>
          </tr>
      </table>

      <table width="100%" border="none">
        @php
        $outOfList = [];
       
        @endphp
        @foreach($additionalItems as $items)
          <tr>
            @foreach($items as $item)
              <td style="text-align:left; border-right: none; border-bottom:none; padding-bottom: 0px;">{{($item['sr_no'] != '')?$item['sr_no'].'.':''}} {{$item['desc']}}
              </td>
              
              <td style="text-align:center; border-left: none; border-bottom:none; padding-bottom: 0px;">
                @if($item['id'] != '' && in_array($item['id'],array_keys($propAdditionalItems)))
                @php unset($propAdditionalItems[$item['id']]) @endphp
                  <img src="{{ asset('assets/images/checkbox_X.jpeg') }}" style="height:15px; width:15px; vertical-align: middle; margin-left: 20px;">
                  @else
                  @if($item['id'] == 'other_items')
                  @php  @endphp
                  {{ implode(", ",array_values($propAdditionalItems)) }}
                  @endif

                @endif
              </td>
              
              @endforeach
              
          </tr>
          @endforeach
      </table>