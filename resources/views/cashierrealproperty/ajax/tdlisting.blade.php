
<table class="table">
	<thead>
		<tr>
			<th>T.D. No.</th>
			<th>Period</th>
			<th>Assessed Value</th>
			<th>Class</th>
			<th>Taxpayer</th>
			<th>O.R. No.</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@foreach($billingData as $billing)
		@if(isset($cashierDetails->id) && $cashierDetails->id > 0)
		@if(in_array($billing->id,$acceptedTds))
		@include('cashierrealproperty.include.tdlist')
		@endif
		@else
		@include('cashierrealproperty.include.tdlist')
		@endif
		@endforeach
		 <tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		 <tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		 <tr>
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