{{Form::open(array('name'=>'forms','url'=>'rptbuilding/anootationspeicalpropertystatus','method'=>'post','id'=>'saveAnnotationPropertyStatus'))}}
{{ Form::hidden('id',(isset($propertyStatus->id))?$propertyStatus->id:'', array('id' => 'id')) }}
{{ Form::hidden('property_id',(isset($propertyId))?$propertyId:'', array('id' => 'property_id')) }}
{{ Form::hidden('action','main_form', array()) }}
  
 <style>
    .modal-xll {
        max-width: 1350px !important;
    }
    .accordion-button{
        margin-bottom: 12px;
    }
    .form-group{
        margin-bottom: unset;
    }
    .form-group label {
        font-weight: 600;
        font-size: 12px;
    }
    .form-control, .custom-select{
        padding-left: 5px;
        font-size: 12px;
    }
    .textright{text-align:right;}
    .pt10{
        padding-top:10px;
    }
    .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: #fff;
        background: #29b6c9;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .row{padding-top:10px;}
    .choices__inner {
        min-height: 35px;
        padding:5px ;
        padding-left:5px;
    }
    .field-requirement-details-status label{margin-top: 7px;}
    #flush-collapsetwo{
/*        padding-bottom: 80px;*/
    }
 </style>
<div class="modal-header">
                <h4 class="modal-title">Building Value Computation</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div><div class="container"></div>
<div class="modal-body">
    <div class="row pt10" >
        

        

        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                       <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="" aria-expanded="false" aria-controls="flush-headingfive">
                                <h6 class="sub-title accordiantitle">{{__("Building Core")}}</h6>
                            </button>
                    </h6>
                    <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                        <div class="basicinfodiv">
                            <!--------------- Land Apraisal Listing Start Here------------------>
        <div class="row">
           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                   {{Form::label('',__('Total Building Area'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                    {{Form::text('',(!$floorValues->isEmpty())?Helper::decimal_format($floorValues->sum('rpbfv_floor_area')):0.00,array('class'=>'form-control','rows'=>1,'readonly'=>true))}}
                                </div>
                            </div>
                                </div>
           </div>  

           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                   {{Form::label('',__('Unit Value'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                    {{Form::text('',(!$floorValues->isEmpty())?Helper::decimal_format($floorValues[0]->rpbfv_floor_unit_value):0.00,array('class'=>'form-control','readonly'=>true,'rows'=>1))}}
                                </div>
                            </div>
                                </div>
           </div> 
           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                   {{Form::label('',__('Base Market Value'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                    {{Form::text('',(!$floorValues->isEmpty())?Helper::decimal_format($floorValues->sum('rpbfv_floor_base_market_value')):0.00,array('class'=>'form-control','rows'=>1,'readonly'=>true))}}
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

        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                       <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="" aria-expanded="false" aria-controls="flush-headingfive">
                                <h6 class="sub-title accordiantitle">{{__("Additional Items")}}</h6>
                            </button>
                    </h6>
                    <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                        <div class="basicinfodiv">
                            <!--------------- Land Apraisal Listing Start Here------------------>
        <div class="row">
           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                   {{Form::label('',__('Item(s)'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                   {{Form::label('',__('Value'),['class'=>'form-label'])}}
                                </div>
                            </div>
                                </div>
           </div>  

           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                   <button type="button" class="btn btn-primary displayAdditionalItensForDepreciation" data-toggle="modal" data-target="#floorValueDepAdditionalValues">
  View
</button>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                    {{Form::text('',(!$floorValues->isEmpty())?Helper::decimal_format($floorValues->sum('rpbfv_floor_additional_value')):0.00,array('class'=>'form-control','rows'=>1,'readonly'=>true))}}
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

        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                       <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="" aria-expanded="false" aria-controls="flush-headingfive">
                                <h6 class="sub-title accordiantitle">{{__("Adjustments")}}</h6>
                            </button>
                    </h6>
                    <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                        <div class="basicinfodiv">
                            <!--------------- Land Apraisal Listing Start Here------------------>
        <div class="row">
           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                   {{Form::label('',__('Item(s)'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                   {{Form::label('',__('Value'),['class'=>'form-label'])}}
                                </div>
                            </div>
                                </div>
           </div>  

           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                   {{Form::text('','',array('class'=>'form-control','rows'=>1,'readonly'=>true))}}
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                   {{Form::text('',(!$floorValues->isEmpty())?Helper::decimal_format($floorValues->sum('rpbfv_floor_adjustment_value')):0.00,array('class'=>'form-control','rows'=>1,'readonly'=>true))}}
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
        
       
      <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" >  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                       <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="" aria-expanded="false" aria-controls="flush-headingfive">
                                <h6 class="sub-title accordiantitle">{{__("Value Depreciation")}}</h6>
                            </button>
                    </h6>
                    <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                        <div class="basicinfodiv">
                            <!--------------- Land Apraisal Listing Start Here------------------>
        <div class="row">
           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                   {{Form::label('',__('Depreciation Rate'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                    {{Form::text('',(isset($depreCiation))?Helper::decimal_format($depreCiation):0.00,array('class'=>'form-control depreciation_rate_depreciationmodal','rows'=>1))}}
                                </div>
                            </div>
                                </div>
           </div>  

           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                   {{Form::label('',__('Accumulated Depreciation'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        @php
                                        $totalBaseMarketValue = (!$floorValues->isEmpty())?$floorValues->sum('rpbfv_total_floor_market_value'):0.00;
                                        $accumulatedValue = ($depreCiation*$totalBaseMarketValue)/100;
                                        $depreciatedValue  = $totalBaseMarketValue-$accumulatedValue;
                                        @endphp
                                    {{Form::text('',Helper::decimal_format($accumulatedValue),array('class'=>'form-control accumaultatedValue','rows'=>1,'readonly'=>true))}}
                                </div>
                            </div>
                                </div>
           </div> 
           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                   {{Form::label('',__('Adjusted/Depreciated Market Value'),['class'=>'form-label '])}}
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                    {{Form::text('',Helper::decimal_format($depreciatedValue),array('class'=>'form-control rpbfv_total_floor_market_value_temp','rows'=>1,'readonly'=>true))}}
                                    <input type="hidden" name="total_market_value_of_floor" value="{{(!$floorValues->isEmpty())?$floorValues->sum('rpbfv_total_floor_market_value'):0.00}}">
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
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
  
</div>
{{Form::close()}}

<div class="modal" id="floorValueDepAdditionalValues" data-backdrop="static" style="z-index:9999999;">
    <div class="modal-dialog modal-xl modalDiv" >
        <div class="modal-content" id="floorValueform">
            <div class="modal-header">
                <h4 class="modal-title">Additional Items</h4>
                <a class="close closeSwornStatement" data-dismiss="modal" aria-hidden="true" type="add" mid="">X</a>
                </div><div class="container"></div>
<div class="modal-body">
    <div class="row pt10" >
        <!----  Approval Data Info ------------>
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" style="padding-top: 10px;">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                       
                    </h6>
                    <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                        <div class="basicinfodiv">
                          <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="newAddedAssessementSummary">
                                <thead>
                                    <tr>
                                        <th >{{__('Item Code')}}</th>
                                        <th>{{__("Item Description")}}</th>
                                        <th>{{__("Area")}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                   @if(!$floorValues->isEmpty())
                                  @foreach($floorValues as $floor)
                                  
                                   @foreach($floor->additionalItems as $value)
                                   @php $value = (object)$value; @endphp
                                    <tr class="font-style" data-id="">
                                        <td class="property_kind">{{ $value->bei_extra_item_code }}</td>
                                        <td>{{ $value->bei_extra_item_desc }}</td>
                                        <td>{{ number_format($value->rpbfai_total_area,3) }} Sq. m</td>
                                        
                                    </tr>
                                   @endforeach
                                    @endforeach
                                   
                                    @else
                                     <span>No Data FOund!</span>
                                    @endif
                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <div class="row">
                                
                                
        </div>            

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
       

        

        
        

       
    </div>

</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
  
</div>

        </div>
    </div>
</div>

