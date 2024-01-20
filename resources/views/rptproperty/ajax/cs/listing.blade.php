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
                                    @php $i=1; $var = 0;@endphp

                                    @foreach($taxDeclarations as $selectedProperty)  
                                    
                                    @foreach($selectedProperty->landAppraisals as $key=>$val)
                                        <tr class="font-style">
                                            <td>{{$i}}</td>
                                            <td class="app_qurtr">{{ $val->rptProperty->rp_tax_declaration_no }}</td>
                                            <td class="app_qurtr">{{ $val->rptProperty->propertyOwner->standard_name }}</td>
                                             <td class="app_qurtr">{{ $val->pk_code.'-'.$val->class->pc_class_code }}</td>
                                            <td class="app_qurtr">{{ $val->rpa_base_market_value }}</td> 
                                            <td class="app_qurtr">{{ $val->rpa_assessed_value }}</td>
                                            <td class="app_qurtr">{{ $val->rptProperty->rp_app_effective_year }}</td>
                                            <td><button type="button" class="btn btn-danger deleteTaxDeclaToConsolidate" id="deleteTaxDeclaToConsolidate" rowid="{{$val->rptProperty->id}}"><i class="ti-trash"></i></button></td>
                                        </tr>
                                        @php $var = 1;$i++; @endphp
                                    @endforeach
                                     @php (!$var)?$i++:$i; @endphp
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