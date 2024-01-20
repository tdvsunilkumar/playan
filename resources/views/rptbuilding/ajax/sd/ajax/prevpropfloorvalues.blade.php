<table class="table" >
	<thead>
		<tr>
			<th></th>
			<th>{{__("Floor No.")}}</th>
			<th>{{__('Structural Type')}}</th>
			<th>{{__("Floor Area")}}</th>
			<th>{{__("Base Market Value")}}</th>
			<th>{{__('Additional Value')}}</th>
			<th>{{__('Adjustment Value')}}</th>
			<th>{{__('Total Floor Market Value')}}</th>
			
		</tr>
	</thead>
	<tbody>

		@if(isset($selectedProperty->floorValues) && !$selectedProperty->floorValues->isEmpty())
		@foreach($selectedProperty->floorValues as $key=>$val)
			<tr class="font-style">
				<td>
					@if(!in_array($val->id,$assignedFloors))
					<input type="checkbox" data-landappraisalid="{{ $val->id }}" name="selectedFloorValues[]" class="landAppraisalIdForSubdivision" value="{{ $val->id }}"  {{($key == '0')?"checked":" "}}/>
					@else
					<span style="color: green;">Assigned</span>
					@endif
				</td>
				<td class="app_qurtr">{{ $val->rpbfv_floor_no }}</td>
				<td class="app_qurtr">{{ (isset($val->bt_building_type_code_desc))?$val->bt_building_type_code_desc:'' }}</td>
				<td >{{number_format($val->rpbfv_floor_area,3) }} Sq. m.</td>
				 <td class="app_qurtr">{{ Helper::money_format($val->rpbfv_floor_base_market_value) }}</td>
				<td class="app_qurtr">{{ Helper::money_format($val->rpbfv_floor_additional_value) }}</td> 
				<td class="app_qurtr">{{ Helper::money_format($val->rpbfv_floor_adjustment_value) }}</td>
				<td class="app_qurtr">{{ Helper::money_format($val->rpbfv_total_floor_market_value) }}</td>
				
			</tr>
		@endforeach
		@endif
		<tr class="font-style last-option">
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr class="font-style">
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		
	</tbody>
</table>