 <div class="col-md-12">
                <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="new_added_land_apraisal">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{__('TD. No.')}}</th>
                                        <th>{{__("Declared Owner")}}</th>
                                        <th>{{__('Actual Use Code')}}</th>
                                        <th>{{__('Market Value')}}</th>
                                        <th>{{__('Assessed Value')}}</th>
                                        <th>{{__('Effective')}}</th>
                                        <th>{{__('Action')}}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(isset($taxDeclarations) && !empty($taxDeclarations))
                                    @foreach($taxDeclarations as $selectedProperty)  
                                    @php $i=1;@endphp
                                    
                                        <tr class="font-style">
                                            <td>{{$i}}</td>
                                            <td class="app_qurtr">{{ $selectedProperty->rp_tax_declaration_no }}</td>
                                            <td class="app_qurtr">{{ $selectedProperty->propertyOwner->standard_name }}</td>
                                             <td class="app_qurtr">{{(isset($selectedProperty->propertyKindDetails->pk_code))?$selectedProperty->propertyKindDetails->pk_code:''}}-{{ (isset($selectedProperty->floorValues[0]->actualUses->pau_actual_use_desc))?$selectedProperty->floorValues[0]->actualUses->pau_actual_use_desc:'' }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($selectedProperty->rpb_accum_deprec_market_value) }}</td> 
                                            <td class="app_qurtr">{{ Helper::decimal_format($selectedProperty->rpb_assessed_value) }}</td>
                                            <td class="app_qurtr">{{ $selectedProperty->rp_app_effective_year }}</td>
                                            <td><button type="button" class="btn btn-danger deleteTaxDeclaToConsolidate" id="deleteTaxDeclaToConsolidate" data-id="{{$selectedProperty->id}}"><i class="ti-trash"></i></button></td>
                                        </tr>
                                        @php $i++; @endphp
                                   
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
                        </div>
                    </div>
                </div>
            </div>
            </div>