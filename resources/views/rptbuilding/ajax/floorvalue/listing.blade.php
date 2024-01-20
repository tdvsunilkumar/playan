<table class="table" >
	<thead>
		<tr>
			<th>No.</th>
			<th>{{__('Floor No.')}}</th>
			<th>{{__("Structural Type")}}</th>
			<th>{{__("Actual Use")}}</th>
			<th>{{__("Floor Area")}}</th>
			<th>{{__("Unit Value")}}</th>
			<th>{{__("Base Market Value")}}</th>
			<th>{{__("Additional Value")}}</th>
			<th>{{__("Adjustment Value")}}</th>
			<th>{{__("Total Floor Market Value")}}</th>
			<th>{{__("Action")}}</th>
		</tr>
	</thead>
	<tbody>
		@php $i=1; $totalMarketValue=0; @endphp
		@foreach($floorValues as $key=>$val)
			<tr class="font-style">
				<td class="app_qurtr">{{ $i }}</td>
				<td class="app_qurtr">{{ $val->rpbfv_floor_no }}</td>
				<td class="app_qurtr">{{ (isset($val->bt_building_type_code_desc))?$val->bt_building_type_code_desc:'' }}</td>
				<td class="app_qurtr">{{ (isset($val->pau_actual_use_code_desc))?$val->pau_actual_use_code_desc:'' }}</td>
				 <td class="app_qurtr">{{ $val->rpbfv_floor_area }} Sq. M.</td><!-- Helper::decimal_format($val->rpbfv_floor_area) -->
				<td class="app_qurtr">{{ Helper::money_format($val->rpbfv_floor_unit_value) }}</td> 
				<td class="app_qurtr">{{ Helper::money_format($val->rpbfv_floor_base_market_value) }}</td>
				<td class="app_qurtr">{{ Helper::money_format($val->rpbfv_floor_additional_value) }}</td> 
				<td class="rpa_base_market_value">{{ Helper::money_format($val->rpbfv_floor_adjustment_value) }}</td>
				<td class="app_qurtr">{{ Helper::money_format($val->rpbfv_total_floor_market_value) }}</td>
				
				 <td class="action"><a href="javascript:void(0)" data-sessionid="{{ (isset($val->id) && $val->id != '')?'':$key }}" data-id="{{ $val->id }}" class="editLandAppraisal"><i class="fas fa-edit"></i></a>&nbsp;&nbsp;<a href="javascript:void(0)" data-sessionid="{{ (isset($val->id) && $val->id != '')?'':$key }}" data-id="{{ $val->id }}" class="deleteLandAppraisal"><i class="fas fa-trash"></i></a></td>
				@php $totalMarketValue+=0;$i++; @endphp
			</tr>
		@endforeach
		<tr class="font-style last-option">
			<td><!-- <input type="checkbox" data-sessionid="12" class="addLandAppraisalAdjustmentFactorOrPlantTree" value="13"/> --></td>
			<td></td>
			<td></td>
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
			<td><!-- <input type="checkbox" data-sessionid="14" class="addLandAppraisalAdjustmentFactorOrPlantTree" value="15"/> --></td>
			<td></td>
			<td></td>
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
			<td><!-- <input type="checkbox" data-sessionid="16" class="addLandAppraisalAdjustmentFactorOrPlantTree" value="17"/> --></td>
			<td></td>
			<td></td>
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