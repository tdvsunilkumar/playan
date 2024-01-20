{{Form::open(array('name'=>'forms','url'=>route('billing.store'),'method'=>'post','id'=>'generateBilling'))}}
@if($billingMode == 1)
<input type="hidden" name="control_number_for_multiple" value="{{ $controlNumber }}">
<input type="hidden" name="txn_id_for_multiple" value="{{ (isset($controlNumberDetails->transaction_id))?$controlNumberDetails->transaction_id:'' }}">
<input type="hidden" name="txn_no_for_multiple" value="{{ (isset($controlNumberDetails->transaction_no))?$controlNumberDetails->transaction_no:'' }}">
@endif
 <style>
    .modal-xll {
        max-width: 1550px !important;
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
    .field-requirement-details-status label{margin-top: 7px;}
    #flush-collapsetwo{
/*        padding-bottom: 80px;*/
    }

 </style>

<div class="modal-body">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="row">
               <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item" style="    height: 300px;">
                        <h6 class="accordion-header" id="flush-headingone">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle">{{__("Search Details")}}</h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                            <div class="basicinfodiv">
                                <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row hide">
                        <div class="col-lg-2 col-md-2 col-sm-2">
                                 <div class="form-group">
                                    {{Form::label('rp_code',__("TD No"),['class'=>'form-label'])}}
                                    <input type="hidden" name="rp_code">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                       {{Form::text('rvy_revision_code',(isset($revisionYearDetails->rvy_revision_code))?$revisionYearDetails->rvy_revision_code:'',array('class'=>'form-control rvy_revision_code','id'=>'rvy_revision_code','readonly'=>true))}}
                                       <input type="hidden" name="rvy_revision_year" value="{{(isset($revisionYearDetails->id))?$revisionYearDetails->id:''}}">
                                  <span class="validate-err" id="err_rvy_revision_code"></span>
                                   
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                       {{Form::text('brgy_no',(isset($brngyDetails->brgy_code))?$brngyDetails->brgy_code:'',array('class'=>'form-control brgy_no','id'=>'brgy_no','readonly'=>true))}}
                                       <input type="hidden" name="brgy_code" value="{{(isset($brngyDetails->id))?$brngyDetails->id:''}}">
                                  <span class="validate-err" id="err_brgy_no"></span>
                                   
                                </div>
                            </div>
                        </div>
                         <div class="row" id="tdlistingdiv">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                 <div class="form-group">
                                    {{Form::label('rp_code',__("TD No."),['class'=>'form-label'])}}
                                    <input type="hidden" name="rp_code">
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                       {{Form::select('rp_td_no',[],'',array('class'=>'form-control rp_td_no','id'=>'rp_td_no'))}}
                                  <span class="validate-err" id="err_rp_td_no"></span>
                                   
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                       <input type="button" id="searchTdNo" value="Search" class="btn btn-primary" >
                                   
                                </div>
                            </div>

                    </div>
                    
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">
                    <div class="row" id="fromyear">
                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">

                                    {{Form::label('currentbarangay',__('Barangay'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                       {{Form::text('currentbarangay','',['class'=>'form-control disabled-field currentbarangay','id'=>'currentbarangay']);}} 
                                    <span class="validate-err" id="err_currentbarangay"></span>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                 <div class="select-group">
                                    <div class="form-icon-user">
                                     {{ Form::checkbox('cb_all_quarter_paid_checkbox','1', true, array('id'=>'cb_all_quarter_paid_checkbox','class'=>'form-check-input cb_all_quarter_paid_checkbox','style'=>'margin-top:9px')) }} {{Form::label('',__('Annual'),['class'=>'form-label','style'=>'padding-top: 7px;'])}}
                                    </div>
                                 </div>
                              </div>
                    </div>
                    
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">
                    <div class="row" id="fromyear">
                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">

                                    {{Form::label('cb_covered_from_year',__('From'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                       {{Form::text('cb_covered_from_year','',['class'=>'form-control cb_covered_from_year','id'=>'cb_covered_from_year','autocomplete'=>'off']);}} 
<span class="validate-err" id="err_cb_covered_from_year"></span>
                                   
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                       {{Form::select('sd_mode',Helper::billing_quarters(),11,array('class'=>'form-control sd_mode select3','id'=>'sd_mode','disabled'=>true))}}
                                       <span class="validate-err" id="err_sd_mode"></span>

                                   
                                </div>
                            </div>
                    </div>
                    
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">
                    <div class="row" id="toyear">
                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">

                                    {{Form::label('cb_covered_to_year',__('To'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                       {{Form::text('cb_covered_to_year','',['class'=>'form-control yearpicker cb_covered_to_year','id'=>'cb_covered_to_year','autocomplete'=>'off']);}} 
                                       <span class="validate-err" id="err_cb_covered_to_year"></span>

                                   
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                       {{Form::select('sd_mode_to',Helper::billing_quarters(),44,array('class'=>'form-control sd_mode_to select3','id'=>'sd_mode_to','disabled'=>true))}}
                                       <span class="validate-err" id="err_sd_mode_to"></span>
                                       <input type="hidden" name="cb_all_quarter_paid" value="1">
                                       <input type="hidden" name="compute_for_discount" value="1">
                                       <input type="hidden" name="compute_for_penalty" value="1">
                                       <input type="hidden" name="cb_billing_mode" value="{{ $billingMode}}">
                                   
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    
<input type="submit" value="Go" class="btn btn-primary" style="height: 85px;
    width: 88px;
    margin-top: -50px;">
                                   
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
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="row">
               <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample1">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item" style="height:300px;">
                        <h6 class="accordion-header" id="flush-headingone1">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone1" aria-expanded="false" aria-controls="flush-headingtwo1">
                                <h6 class="sub-title accordiantitle">{{__("Client Information")}}</h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone1" class="accordion-collapse collapse show" aria-labelledby="flush-headingone2" data-bs-parent="#accordionFlushExample1">
                            <div class="basicinfodiv">
                                <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">

                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">

                                    {{Form::label('rpo_code_desc',__('Owner'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <div class="form-group">
                                       {{Form::text('rpo_code_desc','',['class'=>'form-control rpo_code_desc','id'=>'rpo_code_desc','readonly'=>true]);}} 
                   <input type="hidden" name="rpo_code">
                                  <span class="validate-err" id="err_rpo_code_desc"></span>
                                   
                                </div>
                            </div>

                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">

                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">

                                    {{Form::label('owner_address',__('Address'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <div class="form-group">
                                       {{Form::text('owner_address','',['class'=>'form-control owner_address','id'=>'owner_address','readonly'=>true]);}} 
                   <input type="hidden" name="rpo_code">
                   @if($billingMode == 1)
                   <input type="hidden" name="rpo_code_for_multiple" value="{{ $rpoCode }}">
                   @endif
                                  <span class="validate-err" id="err_owner_address"></span>
                                   
                                </div>
                            </div>

                    </div>
                </div>
                 <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">
                    <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    {{Form::label('owner_address',__('P.I.N'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <div class="form-group">
                                       {{Form::text('rp_pindcno','',['class'=>'form-control rp_pindcno','id'=>'rp_pindcno','readonly'=>true]);}} 
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">

                                    {{Form::label('cb_assessed_value',__('Assessed Value'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                       {{Form::text('cb_assessed_value','',['class'=>'form-control cb_assessed_value','id'=>'cb_assessed_value','readonly'=>true]);}} 
                  
                                  <span class="validate-err" id="err_cb_assessed_value"></span>
                                   
                                </div>
                            </div>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">

                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">

                                    {{Form::label('pk_code_desc',__('Property'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                       {{Form::text('pk_code_desc','',['class'=>'form-control pk_code_desc','id'=>'pk_code_desc','readonly'=>true]);}} 
                   <input type="hidden" name="pk_code">
                                  <span class="validate-err" id="err_pk_code_desc"></span>
                                   
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7">
                                <div class="form-group">
                                       {{Form::text('prop_class','',['class'=>'form-control prop_class','id'=>'prop_class','readonly'=>true]);}} 
                                       <input type="hidden" name="pc_class_code">
                                  <span class="validate-err" id="err_prop_class"></span>
                                   
                                </div>
                            </div>
                    </div>
                </div>
                <div id="noteNeedsToAppearHere"></div>

            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
        </div>
    </div>
{{ Form::close() }}
    
<input type="hidden" name="readyForSubmission" value="0">
    <div class="row pt10" >
        <!--------------- Owners Information Start Here---------------->
       
            <div class="col-xl-12">
                
                        <div class="table-responsive" id="computedBillingData">
                            <table class="table" id="">
                                <thead>
                                    <tr>
                                        <th>{{__('No.')}}</th>
                                        <th>{{__('Year')}}</th>
                                        <th>{{__('Quarter')}}</th>
                                        <th>{{__("Assessed Value")}}</th>
                                        <th>{{__('Basic Amount')}}</th>
                                        <th>{{__('Basic Interest')}}</th>
                                        <th>{{__('Basic Discount')}}</th>
                                        <th>{{__('SEF Amount')}}</th>
                                        <th>{{__('SEF Interest')}}</th>
                                        <th>{{__('SEF Discount')}}</th>
                                        <th>{{__('SH Amount')}}</th>
                                        <th>{{__('SH Interest')}}</th>
                                        <th>{{__('SH Discount')}}</th>
                                        <th>{{__('Total Amount Due')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="font-style">
                                           
                                            <td class=""></td>
                                            <td class=""></td>
                                             <td class=""></td>
                                             <td class=""></td>
                                            <td class=""></td> 
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                             <td class=""></td>
                                             <td class=""></td>
                                            <td class=""></td> 
                                            <td class=""></td>
                                            <td class=""></td>
                                        </tr>
                                         <tr class="font-style">
                                           
                                            <td class=""></td>
                                            <td class=""></td>
                                             <td class=""></td>
                                             <td class=""></td>
                                            <td class=""></td> 
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                             <td class=""></td>
                                             <td class=""></td>
                                            <td class=""></td> 
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                        </tr>
                                         <tr class="font-style">
                                           
                                            <td class=""></td>
                                            <td class=""></td>
                                             <td class=""></td>
                                             <td class=""></td>
                                            <td class=""></td> 
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                             <td class=""></td>
                                             <td class=""></td>
                                            <td class=""></td> 
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                   
            </div>
        </div>
        <!--------------- Land Apraisal Listing End Here------------------><br />
                          
                    
             
        <!--------------- Owners Information Start Here---------------->

        
        <!--------------- Business Information End Here------------------>
    </div>

   

</div>
<div class="modal-footer justify-content-between">
    <div class="row">
        <div class="col-lg-1 col-md-1 col-sm-1">
            <div class="select-group">
                                    <div class="form-icon-user">
                                     {{ Form::checkbox('waive_discount','1', ('')?true:false, array('id'=>'waive_discount','class'=>'form-check-input waive_discount','style'=>'margin-top:9px')) }} 
                                    </div>
                                 </div>
                             </div>
        <div class="col-lg-5 col-md-5 col-sm-5">{{Form::label('',__('Waive Discount'),['class'=>'form-label','style'=>'padding-top: 7px;'])}}</div>                     
        <div class="col-lg-1 col-md-1 col-sm-1">
            <div class="select-group">
                                    <div class="form-icon-user">
                                     {{ Form::checkbox('waive_penalty','1', ('')?true:false, array('id'=>'waive_penalty','class'=>'form-check-input waive_penalty','style'=>'margin-top:9px')) }} 
                                    </div>
                                 </div>
                             </div>
        <div class="col-lg-5 col-md-5 col-sm-5">{{Form::label('',__('Waive Penalty'),['class'=>'form-label','style'=>'padding-top: 7px;'])}}</div>                      
    </div>

    <div><input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="button" id="generateBillFromTemporaryData" value="Bill Now" class="btn btn-primary" ></div>
</div>
 <div class="modal" id="updateTaxRateScheduleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center">Update Rate Schedule</h5>
                <button type="button" class="close closeUpdateCodeNodal" data-dismiss="modal" aria-label="Close" >
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row pt10" >
                    <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                    </h6>
                    <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                        <div class="basicinfodiv">
                           <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            
                                    <label for="land_market_value" class="form-label" style="margin-top:8px;">Land Unit Value Schedule</label>
                               
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                               
                                     <input type="button" id="showLandUnitValueScheduleModal" value="Schedule" class="btn btn-light" >   
                               
                            </div>
                                </div><br />

                                <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            
                                    <label for="land_market_value" class="form-label" style="margin-top:8px;">Plants And Trees Unit Value</label>
                               
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                               
                                     <input type="button" id="showPlantsTressUnitValueScheduleModal" value="Schedule" class="btn btn-light" >   
                               
                            </div>
                                </div>
                                <br />

                                <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            
                                    <label for="land_market_value" class="form-label" style="margin-top:8px;">Building Unit Value</label>
                               
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                               
                                     <input type="button" id="showBuildingUnitValueScheduleModal" value="Schedule" class="btn btn-light" >   
                               
                            </div>
                                </div>
                                <br />

                                <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            
                                    <label for="land_market_value" class="form-label" style="margin-top:8px;">Assessement Level</label>
                               
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                               
                                     <input type="button" id="showAssessementLevelScheduleModal" value="Schedule" class="btn btn-light" >   
                               
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeUpdateCodeNodal" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
<div class="modal" id="LandUnitValueScheduleModal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-lg modalDiv" >
            <div class="modal-content" id="landunitvaluesceduleview">
                
            </div>
        </div>
    </div>
<div class="modal" id="plantsTreesUnitValueScheduleModal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-lg modalDiv" >
            <div class="modal-content" id="plantstreesunitvaluesceduleview">
                
            </div>
        </div>
    </div>
<div class="modal" id="buildingUnitValueScheduleModal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-lg modalDiv" >
            <div class="modal-content" id="buildingunitvaluesceduleview">
                
            </div>
        </div>
    </div>    
<div class="modal" id="assessementLevelScheduleModal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-lg modalDiv" >
            <div class="modal-content" id="assessementlevelsceduleview">
                
            </div>
        </div>
    </div>    
<div class="modal" id="editLandUnitValueModal" data-backdrop="static" style="z-index:9999999 !important;">
        <div class="modal-dialog " >
            <div class="modal-content" >
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="body">
            </div>
            </div>
        </div>
    </div>   
<div class="modal" id="editAssessementLevelModal" data-backdrop="static" style="z-index:9999999 !important;">
        <div class="modal-dialog " >
            <div class="modal-content" >
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="body">
            </div>
            </div>
        </div>
    </div>
<div class="modal" id="editPlantTreesUnitValueModal" data-backdrop="static" style="z-index:9999999 !important;">
        <div class="modal-dialog " >
            <div class="modal-content" >
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="body">
            </div>
            </div>
        </div>
    </div>  

<div class="modal" id="editBuildingUnitValueModal" data-backdrop="static" style="z-index:9999999 !important;">
        <div class="modal-dialog " >
            <div class="modal-content" >
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="body">
            </div>
            </div>
        </div>
    </div>        

    <div class="modal" id="landAppraisalAdjustmentFactorsmodal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-xl modalDiv" >
            <div class="modal-content" id="landAppraisalAdjustmentFactorsform">
                
            </div>
        </div>
    </div>
<div class="modal" id="approvalformModal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-xl modalDiv" >
            <input type="hidden" name="cancelled_by_id" value="{{-- $oldpropertyid --}}">
            <div class="modal-content" id="approvalform">
                
            </div>
        </div>
    </div>
    <div class="modal" id="annotationSpecialPropertyStatusModal" data-backdrop="static" style="z-index:9999999;">
    <div class="modal-dialog modal-xl modalDiv" >
        <div class="modal-content" id="annotationSpecialPropertyStatusForm">

        </div>
    </div>
</div>
<div class="modal" id="swornStatementModal" data-backdrop="static" style="z-index:9999999;">
    <div class="modal-dialog modal-xl modalDiv" >
        <div class="modal-content" id="swornStatementForm">

        </div>
    </div>
</div>

<input type="hidden" name="dynamicid" value="3" id="dynamicid">
<script src="{{ asset('js/billingform/addBillingForm.js') }}"></script>
<!-- <script src="{{-- asset('js/ajax_rptProperty.js') --}}"></script> -->