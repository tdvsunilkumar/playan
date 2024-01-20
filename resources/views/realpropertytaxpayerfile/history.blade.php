<table class="table" >
    <thead>
        <tr>
            <th >{{__('No.')}}</th>
            <th >{{__('Cancelled By T.D. No.')}}</th>
            <th>{{__("Owner")}}</th>
            <th>{{__("Assessed Value")}}</th>
            <th>{{__("Cancelled  T.D. No.")}}</th>
            
        </tr>
    </thead>
    <tbody>
        
        <tr class="font-style">
            @foreach($history as $key=> $val)
            @php //dd($val->activeProp) @endphp
            <td>{{ ($key + 1) }}</td>
            <td>{{ isset($val->activeProp) ? $val->activeProp->rp_tax_declaration_no : null }}</td>
            <td>{{ (isset($val->cancelProp->propertyOwner->standard_name))?$val->cancelProp->propertyOwner->standard_name:'' }}</td>
            <td>{{(isset($val->cancelProp->assessed_value_for_all_kind))?Helper::money_format($val->cancelProp->assessed_value_for_all_kind):00.00}}</td>
            <td>{{ (isset($val->cancelProp->rp_tax_declaration_aform))?$val->cancelProp->rp_tax_declaration_aform:'' }}</td>
        </tr>
        @endforeach
        <tr class="font-style">
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
        </tr>
    </tbody>
</table>