    <div class="modal-body">
        
        <div class="row pt10" >
            <!--------------- Owners Information Start Here---------------->
            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header text-center" id="flush-headingone">
                            <button class="accordion-button collapsed btn-primary"  aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle">{{__("Summary of Real Property Assessments Series of ")}}{{(isset($activeRevisionYear->rvy_revision_year))?$activeRevisionYear->rvy_revision_year:''}}</h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                            <div class="row" style="padding-top: 10px;">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <a href="{{ route('realpropertytaxpayerfile.printbill',$propOwner) }}" target="_blank" data-toggle="modal" href="javascript:void(0)" data-propertyid="" id="displaySwornStatementModal" class="btn btn-primary" >Print Notice of Assessment</a>
                                        </div>
                                        <!-- <div class="col-sm-3">
                                            <a data-toggle="modal" href="javascript:void(0)" data-propertyid="" id="displayAnnotationSpecialPropertyStatusModal" class="btn btn-primary" >Print Notice of Assessment</a>
                                        </div>
                                        <div class="col-sm-2">
                                            <a data-toggle="modal" href="javascript:void(0)" data-propertyid="" id="displaySwornStatementModal" class="btn btn-primary" >Print OR</a>
                                        </div>
                                        <div class="col-sm-2">
                                            <a data-toggle="modal" href="javascript:void(0)" data-propertyid="" id="displaySwornStatementModal" class="btn btn-primary" >Print ORF</a>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="summaryOfTaxDeclarations">
                                <thead>
                                    <tr>
                                        <th >{{__('Select')}}</th>
                                        <th >{{__('T.D. No')}}</th>
                                        <th>{{__("Kind")}}</th>
                                        <th>{{__("PIN")}}</th>
                                        <th>{{__("Class")}}</th>
                                        <th>{{__("Market Value")}}</th>
                                        <th>{{__('Assessed Value')}}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                   @foreach($rptProperties as $key=>$prop)
                                   @php //dd($prop); @endphp
                                    <tr class="font-style">
                                        <td><input type="checkbox" {{($key == 0)?'checked':''}} value="{{ $prop->rp_property_code }}"  class="selectedTdForHistory" /></td>
                                        <td class="property_kind">{{$prop->rp_tax_declaration_no}}</td>
                                        <td>{{$prop->propertyKindDetails->pk_code.'-'.$prop->propertyKindDetails->pk_description}}</td>
                                        <td>{{$prop->rp_pin_declaration_no}}</td>
                                        <td>{{(isset($prop->class_for_kind->pc_class_code))?$prop->class_for_kind->pc_class_code.'-'.$prop->class_for_kind->pc_class_description:''}}</td>
                                        <td>{{Helper::money_format($prop->market_value_for_all_kind)}}</td>
                                        <td>{{Helper::money_format($prop->assessed_value_for_all_kind)}}</td>
                                    </tr>
                                   
                                    @endforeach
                                    <tr class="font-style">
                                        <td class="property_kind"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr class="font-style">
                                        <td class="property_kind"></td>
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
                           
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingone">
                            <button class="accordion-button collapsed btn-primary" type="button"  aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle text-center tax-declaration-history-of">{{__("Tax Declaration History")}}</h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body table-border-style">
                                            <div class="table-responsive" id="loadHistoryHere">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         
            
        </div>
       
             
                 

               
           </div>
         </div>
        </div>
        <!--------------- Business Details Listing End Here------------------>

          

              <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
           
        </div>
            </div>

        </div>
    </div>
</div>





