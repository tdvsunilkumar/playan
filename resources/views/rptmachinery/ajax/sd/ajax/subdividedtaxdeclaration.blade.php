 <table class="table" id="new_added_land_apraisal">
                                <thead>
                                    <tr>
                                        <th width="10%">{{__('Select')}}</th>
                                        <th width="20%">{{__('Brgy.')}}</th>
                                        <th  width="10%">{{__("T.D. No.")}}</th>
                                        <th  width="10%">{{__('Suffix')}}</th>
                                        <th  width="40%">{{__('Declared Owner')}}</th>
                                        <th  width="10%"><button type="button" class="btn btn-success" id="addSubdividedTaxDeclaration" style="margin: 0px;
                                        /* padding: 0px; */padding-top: 0px; padding-bottom: 0px;background: #1f3996;"><b style="padding:0px;font-size:22px;">+</b></button></th>
                                        
                                    </tr>
                                </thead>
                                <tbody>

                                    
                                    @foreach($selectedProperty as $key => $prop)

                                        <tr class="font-style">
                                            <td width="10%"><input type="checkbox" data-subdividedtaxdeclarationid="{{ $prop->id }}" class="subdividedtaxdeclarationid" value="{{ $prop->id }}" {{ ($key == '0')?'checked':''}} />
                                                <input type="hidden" name="newCreatedTaxDeclarationForSd[]"  value="{{ $prop->id }}">

                                            </td>
                                            <td class="app_qurtr" width="20%">{{ $prop->barangay_details->brgy_code }}</td>
                                            <td class="app_qurtr" width="10%">{{ $prop->rp_tax_declaration_no }}</td>
                                             <td class="app_qurtr" width="10%"></td>
                                            <td class="selectedPropertyOwner" data-id="{{ $prop->id }}" width="40%" >{{ Form::select('set_property_owner_for_tasdeclaration',$propOwners,$prop->rpo_code,array('class'=>'form-control select3 set_property_owner_for_tasdeclaration','placeholder'=>'Select Property Owner','required' => 'required'))}}
                                                <div class="validate-err" class="peopertyownererror"></div>
                                            </td> 
                                            <td width="10%"><button type="button" class="btn btn-danger deleteSubdividedTaxDeclaration" data-id="{{ $prop->id }}"><i class="ti-trash"></i></button>
                                            </td> 
                                        </tr>
                                   @endforeach
                                  <tr class="font-style last-option">
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
                            <script src="{{ asset('js/select2.min.js') }}"></script>
                            <script src="{{ asset('js/subdivitionTaxDe.js') }}?rand={{ rand(000,999) }}"></script>