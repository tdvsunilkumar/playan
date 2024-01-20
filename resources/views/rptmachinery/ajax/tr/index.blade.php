{{Form::open(array('name'=>'forms','url'=>'rptmachinery/tr/submit','method'=>'post','id'=>'transferOfOwnershipIntermediateSubmission'))}}
 {{ Form::hidden('oldpropertyid',$selectedProperty->id, array('id' => 'id')) }}
 {{ Form::hidden('updateCode',$updateCode, array('id' => 'uc_code','class'=>'uc_code')) }}
 {{ Form::hidden('propertykind',$selectedProperty->pk_id, array('id' => 'pk_id','class'=>'pk_id')) }}


 @php $readonly = 'disabled-field'; @endphp
<style>
    
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
    .pt10{
        padding-top:10px;
    }
    .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #8080802e;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .choices__inner {
        min-height: 35px;
        padding:5px ;
        padding-left:5px;
    }
    
 </style>

    <div class="modal-body">

         <div class="row">
             <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample2">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingtwo">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                        <h6 class="sub-title accordiantitle">{{__('Selected Property Tax Declaration No. to cancel')}}</h6>
                        </button>
                    </h6>
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                        <div class="row"  id="otheinfodiv">
               
             
             <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('uc_code', __('Series Year'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('uc_code',(isset($selectedProperty->revisionYearDetails->rvy_revision_year))?$selectedProperty->revisionYearDetails->rvy_revision_year:'', array('class' => 'form-control '.$readonly,'required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_uc_code"></span>
                </div>
            </div>
              
                        <div class="col-md-3">
               <div class="form-group">
                    {{ Form::label('uc_description', __('Barangay'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('uc_description', (isset($selectedProperty->barangay->brgy_code))?$selectedProperty->barangay->brgy_code.'-'.$selectedProperty->barangay->brgy_name:'', array('class' => 'form-control '.$readonly,'value'=>'Street & Number')) }}
                    </div>
                    <span class="validate-err" id="err_uc_description"></span>
                </div>
            </div>    
            <div class="col-md-3">
               <div class="form-group">
                    {{ Form::label('uc_description', __('Series No.'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('uc_description', (isset($selectedProperty->rp_td_no))?$selectedProperty->rp_td_no:'', array('class' => 'form-control '.$readonly,'value'=>'Street & Number')) }}
                    </div>
                    <span class="validate-err" id="err_uc_description"></span>
                </div>
            </div> 
            <div class="col-md-3">
               <div class="form-group">
                    {{ Form::label('uc_description', __('Suffix'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('uc_description',(isset($selectedProperty->rp_suffix))?$selectedProperty->rp_suffix:'', array('class' => 'form-control '.$readonly,'value'=>'Street & Number')) }}
                    </div>
                    <span class="validate-err" id="err_uc_description"></span>
                </div>
            </div>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



       <div class="row">
             <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample2">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingtwo">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                        <h6 class="sub-title accordiantitle">{{__('Owner Details')}}</h6>
                        </button>
                    </h6>
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                        <div class="row"  id="otheinfodiv">
                             <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('uc_code', __('Declared Owner'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::textarea('uc_code', (isset($selectedProperty->propertyOwner->standard_name))?$selectedProperty->propertyOwner->standard_name:'', array('class' => 'form-control '.$readonly,'required'=>'required','rows'=>2)) }}
                    </div>
                    <span class="validate-err" id="err_uc_code"></span>
                </div>
            </div>
               
               </div>
               
               
                        
                    </div>
                </div>
            </div>
        </div>
               
               </div>

               <div class="row" >
        <!--------------- Taxable Items Start Here---------------->
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" style="padding-top: 10px;">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                        
                    </h6>
                    <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                        <div class="basicinfodiv">
                           
                            <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="new_added_machine_apraisal">
                                <thead>
                                    <tr>
                                       
                                        <th>{{__('Machine Description')}}</th>
                                        <th>{{__('Brand & model')}}</th>
                                        <th>{{__("Capacity / HP")}}</th>
                                        <th>{{__('Date Acquired')}}</th>
                                        <th>{{__('Condition When Acquired')}}</th>
                                        <th>{{__('Eco Life Estimate')}}</th>
                                        <th>{{__('Eco Life Remain')}}</th>
                                        <th>{{__('Date Installed')}}</th>
                                        <th>{{__('Date Operated')}}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                   
                                    @foreach($selectedProperty->machineAppraisals as $key=>$val)
                                        <tr class="font-style">
                                            <td class="app_qurtr">{{ $val->rpma_description }}</td>
                                            <td class="app_qurtr">{{ $val->rpma_brand_model }}</td>
                                             <td class="app_qurtr">{{ $val->rpma_capacity_hp }}</td>
                                            <td class="app_qurtr">{{ $val->rpma_date_acquired}}</td> 
                                            <td class="app_qurtr">{{ $val->rpma_condition }}</td>
                                            <td class="app_qurtr">{{ $val->rpma_estimated_life }}</td> 
                                            <td class="">{{ $val->rpma_remaining_life }}</td>
                                            <td class="app_qurtr">{{ $val->rpma_date_installed }}</td>
                                            <td class="app_qurtr">{{ $val->rpma_date_operated }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="font-style last-option">
                                       
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Land Apraisal Listing End Here------------------><br />
                            
                         
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Taxable Items End Here------------------>
    </div>

    <div class="row" >
        <!--------------- Taxable Items Start Here---------------->
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" style="padding-top: 10px;">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                            <h6 class="sub-title accordiantitle">{{__("Machine Appraisal")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                        <div class="basicinfodiv">
                            <div class="row" style="padding-top: 10px; padding-bottom:10px;">
                            
                            
                       </div>
                            <!--------------- Machine Apraisal Listing Start Here------------------>
                           <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="new_added_land_apraisal">
                                <thead>
                                    <tr>
                                        
                                        <th>{{__('Machine Description')}}</th>
                                        <th>{{__('No. of Unit')}}</th>
                                        <th>{{__("Acquisition Cost")}}</th>
                                        <th>{{__('Additional Cost')}}</th>
                                        <th>{{__('Base Market Value')}}</th>
                                        <th>{{__('Depreciation')}}</th>
                                        <th>{{__('Market Value')}}</th>
                                        
                                        
                                    </tr>
                                </thead>
                                <tbody>

                                    @php $i=1; $totalMarketValue=0; @endphp
                                    @foreach($selectedProperty->machineAppraisals as $key=>$val)
                                        <tr class="font-style">
                                            <td class="app_qurtr">{{$val->rpma_description}}</td>
                                            <td class="app_qurtr">{{$val->rpma_appr_no_units}}</td>
                                            <td class="app_qurtr">{{number_format($val->rpma_acquisition_cost,2)}}</td>
                                            <td class="app_qurtr">{{number_format($val->rpma_freight_cost+$val->rpma_insurance_cost+$val->rpma_insurance_cost+$val->rpma_installation_cost+$val->rpma_other_cost,2)}}</td>
                                            <td class="app_qurtr">{{number_format($val->rpma_base_market_value,2)}}</td>
                                            <td class="app_qurtr">{{number_format($val->rpma_depreciation,2)}}</td> 
                                            <td class="app_qurtr">{{number_format($val->rpma_market_value,2)}}</td>
                                           
                                            @php $totalMarketValue+=$val->rpma_market_value;$i++; @endphp
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
                                    </tr>
                                    <tr class="font-style">
                                        <td><!-- <input type="checkbox" data-sessionid="14" class="addLandAppraisalAdjustmentFactorOrPlantTree" value="15"/> --></td>
                                       
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
                                        
                                        
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
        <!--------------- Land Apraisal Listing End Here------------------><br />
                            <!--------------- Machine Apraisal Listing End Here------------------>
                           
                        <br />



                  
                    </div>
                    
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Taxable Items End Here------------------>
    </div>
               
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <input type="submit" name="Next" id=""  value="Next" class="btn  btn-primary">
        </div>
    </div>
{{Form::close()}}



