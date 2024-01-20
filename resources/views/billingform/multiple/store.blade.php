{{Form::open(array('name'=>'forms','url'=>route('billing.store'),'method'=>'post','id'=>'generateMultipleBilling'))}}
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
.select3-container {
    box-sizing: border-box;
    display: inline-block;
    margin: 0;
/*    margin-left: -48px;*/
    width: 1185px;
    position: relative;
    vertical-align: middle;
}
 </style>

<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-1 col-md-1 col-sm-1">
                                            <div class="form-group">

                                    {{Form::label('rpo_code',__("Bill To"),['class'=>'form-label'])}}
                                    
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7" style="margin-left: -48px;" id="div_rpo_code">
                                <div class="form-group">
                                        {{Form::select('rpo_code',[],'',array('class'=>'form-control rpo_code','id'=>'rpo_code','placeholder'=>'','style'=>'width: 1200px;'))}}
                                      
                                  <span class="validate-err" id="err_rpo_code"></span>
                                   
                                </div>
                            </div>
                             <div class="col-lg-1 col-md-1 col-sm-1">
                                            <div class="form-group">

                                    {{Form::label('cb_control_no',__('Control No.'),['class'=>'form-label','style'=>'margin-left: -px;'])}}
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                      {{Form::text('cb_control_no',(isset($controlDetails->cb_control_no))?$controlDetails->cb_control_no:'',['class'=>'form-control cb_control_no','id'=>'cb_control_no','readonly'=>'readonly','style'=>'margin-left: -48px;width: 454px;']);}} 
                                       <span class="validate-err" id="err_cb_control_no"></span>
                                </div>
                            </div>

                    </div>  

                </div>
                <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">
                    <div class="row">
                        <div class="col-lg-1 col-md-1 col-sm-1">
                                            <div class="form-group">

                                    {{Form::label('owner_address',__('Address'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                       {{Form::text('owner_address',(isset($controlDetails->billTo->standard_address))?$controlDetails->billTo->standard_address:'',['class'=>'form-control owner_address','id'=>'owner_address','readonly'=>'readonly','style'=>'margin-left: -48px;width: 543px;']);}} 
                                      <span class="validate-err" id="err_owner_address"></span>
                                   
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                            <div class="form-group">

                                    {{Form::label('cb_billing_date',__('Date'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                       {{Form::text('cb_billing_date',(isset($controlDetails->cb_billing_date))?$controlDetails->cb_billing_date:date("Y-m-d"),['class'=>'form-control cb_billing_date','id'=>'cb_billing_date','readonly'=>'readonly','style'=>'margin-left: -84px;width: 268px;']);}} 
                                       <span class="validate-err" id="err_cb_billing_date"></span>

                                   
                                </div>
                            </div>
                           

                            <div class="col-lg-1 col-md-1 col-sm-1">
                                            <div class="form-group">

                                    {{Form::label('topno',__('TOP No.'),['class'=>'form-label','style'=>'margin-left: -50px'])}}
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                      {{Form::text('topno',(isset($controlDetails->transaction_no))?$controlDetails->transaction_no:'',['class'=>'form-control top','id'=>'topno','readonly'=>'readonly','style'=>'margin-left: -98px;width: 454px;']);}} 
                                       <span class="validate-err" id="err_cb_control_no"></span>
                                </div>
                            </div>
                    </div>
                    
                </div>
                
                
            </div>
        </div>
        
    </div>
{{ Form::close() }}
    

    
        <!--------------- Owners Information Start Here---------------->
       
                           <div class="row" style="margin-top:30px;">
            <div class="col-xl-12">
               
                        <div class="table-responsive" id="computedBillingDataForMultiple" style="width:100%;border: 1px solid #20b7cc;border-top: none;">
                            
                        </div>
                   
            </div>
        </div>
        <!--------------- Land Apraisal Listing End Here------------------><br />
                          
                    
                      
        <!--------------- Owners Information Start Here---------------->

        
        <!--------------- Business Information End Here------------------>
    </div>

   

</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <a href="{{(isset($controlDetails->cb_control_no))?url('billingform/multiplepropertiesprintbill/'.$controlDetails->cb_control_no).'&pageNo=1':'#'}}" data-propertyid="" target="_blank" class="btn btn-primary printSInglePropertyBill">Print</a>
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
<script src="{{ asset('js/billingform/addMultipleBillingForm.js') }}"></script>
<script type="text/javascript">
    setTimeout(function(){
    var rpoCode = '{{(isset($controlDetails->rpo_code))?$controlDetails->rpo_code:0}}';

    if(rpoCode > 0){
        var fullaname = '{{(isset($controlDetails->full_name))?$controlDetails->full_name:''}}';
        $("#rpo_code").select3("trigger", "select", {
                                                        data: { id: rpoCode ,text:fullaname}
                                                      });
    }
    }, 500);
</script>