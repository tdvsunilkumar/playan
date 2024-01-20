
<div class="modal-header">
                <h4 class="modal-title">Plants/Trees and Value Adjustment Factors</h4>
                <a class="close closelandAppraisalAdjustmentFactorsmodal" data-dismiss="modal" aria-hidden="true" type="add" mid="">X</a>
                </div><div class="container"></div>
                <div class="modal-body">
                    <div class="basicinfodiv">
                       <div class="row" >
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" style="padding-top: 10px;">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                            <h6 class="sub-title accordiantitle">{{__("Plants/Tree Adjustment Factor")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                        <div class="basicinfodiv">
                           <div class="row" style="padding-top: 10px;">
            <div class="col-sm-6">
            
            <a data-toggle="modal" href="javascript:void(0)" id="plantstreesadjustmentfactor" class="btn btn-primary" type="add">New</a>
            </div>
            <div class="col-sm-6">
           
            </div>
        </div>                                                          
        <div id="plantstreesadjustmentfactorlisting">

                                </div> 

                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
    {{Form::open(array('name'=>'forms','url'=>'rptproperty/landAppraisalFactors','method'=>'post','id'=>'storelandAppraisalFactors'))}}
{{ Form::hidden('id',(isset($landAppraisal->id))?$landAppraisal->id:'', array('id' => 'id')) }}
{{ Form::hidden('property_id',(isset($propertyCode->id))?$propertyCode->id:NULL, array('id' => 'property_id')) }}
{{ Form::hidden('session_id',$sessionId, array('id' => 'session_id')) }}

    <div class="row" >
        <!--------------- Taxable Items Start Here---------------->
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" style="padding-top: 10px;">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                            <h6 class="sub-title accordiantitle">{{__("Value Adjustment Factor")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                        <div class="basicinfodiv">
                           <div class="row" style="padding-top: 10px;">
            
            <div class="col-sm-6">
           
            </div>
        </div>
         
                                                                <!--------------- Land Apraisal Listing Start Here------------------>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="new_added_land_apraisal">
                                <thead>
                                    <tr>
                                        
                                        <th>{{__('Base Market Value')}}</th>
                                        <th>{{__("Adjustment Factor(a)")}}</th>
                                        <th>{{__("Adjustment Factor(b)")}}</th>
                                        <th>{{__("Adjustment Factor(c)")}}</th>
                                        <th>{{__('Adjustment Percent')}}</th>
                                        <th>{{__('Value Adjustment')}}</th>
                                        <th>{{__('Adjusted Market Value')}}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr class="font-style">
                                        <td>
                                            <div class="form-icon-user currency">
                                            <input type="text" class="form-control rpa_base_market_value" name="rpa_base_market_value" value="{{ isset($landAppraisal->rpa_base_market_value)?$landAppraisal->rpa_base_market_value:'0.00'}}" readonly="readonly">
                                            <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                        </td>
                                        <td>
                                            
                                            <input type="number" class="form-control rpa_adjustment_factor_a" name="rpa_adjustment_factor_a" value="{{ isset($landAppraisal->rpa_adjustment_factor_a)?$landAppraisal->rpa_adjustment_factor_a:'0.00'}}" >
                                        
                                        </td>
                                        <td>
                                            <input type="number" class="form-control rpa_adjustment_factor_b" name="rpa_adjustment_factor_b" value="{{ isset($landAppraisal->rpa_adjustment_factor_b)?$landAppraisal->rpa_adjustment_factor_b:'0.00'}}" >
                                        </td>
                                        <td>
                                            <input type="number" class="form-control rpa_adjustment_factor_c" name="rpa_adjustment_factor_c" value="{{ isset($landAppraisal->rpa_adjustment_factor_c)?$landAppraisal->rpa_adjustment_factor_c:'0.00'}}" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control rpa_adjustment_percent" name="rpa_adjustment_percent"  readonly="readonly" value="{{ isset($landAppraisal->rpa_adjustment_percent)?$landAppraisal->rpa_adjustment_percent.'%':'100%'}}">
                                        </td>
                                        <td>
                                            <div class="form-icon-user currency">
                                            <input type="text" class="form-control rpa_adjustment_value" name="rpa_adjustment_value" value="{{ isset($landAppraisal->rpa_adjustment_value)?(($landAppraisal->rpa_adjustment_percent < 100)?'('.$landAppraisal->rpa_adjustment_value.')':$landAppraisal->rpa_adjustment_value):'0.00'}}" readonly="readonly">
                                            <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                        </td>
                                        <td>
                                            @php
                                            $baseMarketValue = isset($landAppraisal->rpa_base_market_value)?$landAppraisal->rpa_base_market_value:0.00;
                                            $adjustedValue = (isset($landAppraisal->rpa_adjustment_value))?$landAppraisal->rpa_adjustment_value:0;
                                            $adjustPercent = (isset($landAppraisal->rpa_adjustment_percent))?$landAppraisal->rpa_adjustment_percent:0;
                                            if($adjustPercent < 100){
                                                $adjustedValue = -1*$adjustedValue;
                                            }
                                            $adjustedValue = $baseMarketValue+$adjustedValue;
                                            @endphp
                                            <div class="form-icon-user currency">
                                            <input type="text" class="form-control rpa_adjusted_market_value" name="rpa_adjusted_market_value" value="{{ isset($adjustedValue)?$adjustedValue:'0.00'}}" readonly="readonly">
                                            <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                        </td>
                                    </tr>

{{Form::close()}}
                                    <tr class="font-style">
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
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Land Apraisal Listing End Here------------------><br />
        
                            <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                       <div class="form-group row">
    <label for="staticEmail" class="col-sm-7 col-form-label">Adjustment : </label>
    <div class="col-sm-5">
        <div class="form-icon-user currency">
      <input type="text" class="form-control" name="rpa_adjustment_value_for_display" value="{{ isset($landAppraisal->rpa_adjustment_value)?(($landAppraisal->rpa_adjustment_percent < 100)?'('.$landAppraisal->rpa_adjustment_value.')':$landAppraisal->rpa_adjustment_value):'0.00'}}" readonly >
      <div class="currency-sign"><span>Php</span></div>
  </div>
    </div>
  </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group row">
    <label for="staticEmail" class="col-sm-7 col-form-label">Total Adj Market Value : </label>
    <div class="col-sm-5">
        <div class="form-icon-user currency">
      <input type="text" class="form-control" name="rpa_adjusted_market_value_for_display" value="{{ isset($adjustedValue)?$adjustedValue:'0.00'}}" readonly>
      <div class="currency-sign"><span>Php</span></div>
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
        <!--------------- Taxable Items End Here------------------>
    </div>
            
                   
                </div>

                <div class="modal-footer">
                    <a href="#" data-dismiss="modal" class="btn closelandAppraisalAdjustmentFactorsmodal" mid="" type="add">Close</a>
                </div>
                <div class="modal" id="treesplantsadjustmentfactormodal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-lg modalDiv" >
            <div class="modal-content" id="plantstreesadjustmentfacctorform">
                
            </div>
        </div>
    </div>