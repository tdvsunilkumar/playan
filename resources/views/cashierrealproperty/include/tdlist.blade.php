<tr>
	<td>{{ ($billing->rptProperty != null)?$billing->rptProperty->rp_tax_declaration_no:''}}</td>
	<td>{{ isset($billing->period_covered)?$billing->period_covered:''}}</td>
	<td>{{ ($billing->rptProperty != null)?Helper::money_format($billing->rptProperty->assessed_value_for_all_kind):''}}</td>
	<td>{{ ($billing->rptProperty != null)?$billing->pk_code.'-'.$billing->rptProperty->class_for_kind->pc_class_code:''}}</td>
	<td>{{ ($billing->rptProperty != null)?$billing->rptProperty->taxpayer_name:''}}</td>
	<td>{{ (isset($billing->cb_or_no))?$billing->cb_or_no:''}}</td>
	@if(isset($cashierDetails->status) && $cashierDetails->status == 0)
	<td><button type="button" class="btn btn-danger" >Cancelled</button></td>
	@else
	@if($billing->cb_is_paid == 1)
	<td><button type="button" class="btn btn-primary" >Accepted</button></td>
	
	@elseif(!isset($cashierDetails->id) && in_array($billing->id,$acceptedTds) )
	<td><button type="button" class="btn btn-danger removeTdsFromHere" data-td="{{ (isset($billing->id))?$billing->id:0}}" data-url="{{ url('cashier-real-property/removetd') }}">Remove</button></td>
	@else
	<td><button type="button" class="btn btn-warning acceptTdsFromHere" data-td="{{ (isset($billing->id))?$billing->id:0}}" data-url="{{ url('cashier-real-property/accepttd') }}">Accept</button></td>
	@endif
	@endif
	
</tr>