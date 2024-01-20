{{Form::open(array('name'=>'forms','url'=>'generalrevision/store','method'=>'post','id'=>'generalRevisionForm'))}}
 {{-- Form::hidden('id',$data->id, array('id' => 'id')) --}}
 {{-- Form::hidden('uc_code',$data->uc_code, array('id' => 'uc_code','class'=>'uc_code')) --}}
 {{-- Form::hidden('update_code',$data->update_code, array('id' => 'uc_code','class'=>'uc_code')) --}}
 {{-- Form::hidden('pk_id',$propertyKind, array('id' => 'pk_id','class'=>'pk_id')) --}}
 {{-- Form::hidden('old_property_id',$oldpropertyid, array('id' => 'old_property_id','class'=>'old_property_id')) --}}
 
  
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
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row">

                <div class="col-lg-2 col-md-2 col-sm-2">
                    <div class="form-group">
                        {{Form::label('from_rvy_revision_year_id',__("From Revision Year"),['class'=>'form-label','style'=>'color:red'])}}
                <div class="form-icon-user">
                    {{Form::text('from_rvy_revision_year',($oldRevisionYearDetails != null)?$oldRevisionYearDetails->rvy_revision_year.'-'.$oldRevisionYearDetails->rvy_revision_code:'No Revision Found',array('class'=>'form-control rp_tax_declaration_no','readonly'=>'readonly'))}}
                    <input type="hidden" name="from_rvy_revision_year_id" value="{{ ($oldRevisionYearDetails != null)?$oldRevisionYearDetails->id:'0' }}">
                </div>
                <span class="validate-err" id="err_rvy_revision_year_id"></span>
            </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <div class="form-group">
                        {{Form::label('rvy_revision_year_id',__("To Revision Year"),['class'=>'form-label','style'=>'color:green'])}}
                <div class="form-icon-user">
                    {{Form::text('rvy_revision_year_id',($activeRevisionYearDetails != null)?$activeRevisionYearDetails->rvy_revision_year.'-'.$activeRevisionYearDetails->rvy_revision_code:'No Active Revision',array('class'=>'form-control rp_tax_declaration_no','readonly'=>'readonly'))}}
                    <input type="hidden" name="to_rvy_revision_year_id" value="{{ ($activeRevisionYearDetails != null)?$activeRevisionYearDetails->id:'0' }}">
                </div>
                <span class="validate-err" id="err_rvy_revision_year_id"></span>
            </div>
                </div>


                <div class="col-lg-1 col-md-1 col-sm-1">
                    <div class="form-group">
                <div class="form-icon-user">
                     <input type="button" id="launchTaxRateScheduleModal" data-toggle="modal" data-target="#updateTaxRateScheduleModal" value="Tax Rate Schedule" class="btn btn-info" style="margin-top: 24px;border:none;padding-right: 6px;margin-left: -7px;padding-left: 6px;">
                </div>
                <span class="validate-err" id="err_brgy_code_id"></span>
            </div>
                </div>
               <div class="col-lg-1 col-md-1 col-sm-1">
                    <div class="form-group" style="text-align: end;">
                <div class="form-icon-user">
                  <input type="submit" value="Revise Now" class="btn btn-primary" style="margin-top: 24px;border:none;padding-right: 6px;padding-left:6px;">
                </div>
                <span class="validate-err" id="err_brgy_code_id"></span>
            </div>
                </div>
                
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <div class="form-group">
                        {{Form::label('brgy_code_id',__("Barangay No."),['class'=>'form-label'])}}
                <div class="form-icon-user">
                    {{ Form::select('brgy_code_id',$arrBarangay,'', array('class' => 'form-control brgy_code_id','id'=>'brgy_code_id','disabled'=>true)) }}
                </div>
                <span class="validate-err" id="err_rp_suffix"></span>
            </div>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-2">
                    <div class="form-group">
                        {{Form::label('brangay_name',__("Property Kind"),['class'=>'form-label'])}}
                <div class="form-icon-user">
                    {{ Form::select('pk_id',$kinds,'', array('class' => 'form-control pk_id','id'=>'pk_id')) }}
                </div>
                <span class="validate-err" id="err_rvy_revision_year_id"></span>
            </div>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-2">
                    <div class="form-group">
                        {{Form::label('mun_desc',__("Municipality"),['class'=>'form-label'])}}
                <div class="form-icon-user">
                    {{Form::text('mun_desc','',array('class'=>'form-control mun_desc','readonly'=>'readonly'))}}
                </div>
                <span class="validate-err" id="err_rvy_revision_year_id"></span>
            </div>
                </div>

            </div>
        </div>
        
    </div>

    

    <div class="row pt10" >
        <!--------------- Owners Information Start Here---------------->
        <div class="col-lg-6 col-md-6 col-sm-6"  id="accordionFlushExample">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item" style="border-top: none;">
                    <h6 class="accordion-header" id="flush-headingone">
                        <!-- <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                            <h6 class="sub-title accordiantitle">{{--__("Owner's Information")--}}</h6>
                        </button> -->
                    </h6>
                    <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample" style="padding-top: 0px;">
                        <div class="basicinfodiv">
                           <div class="row">
           
                
                        <div class="table-responsive" id="oldTaxDeclarations" style="padding: 2px;padding-top: 0px;">
                           
                        </div>
                    
            
        </div>
        <!--------------- Land Apraisal Listing End Here------------------><br />
                          
                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Owners Information Start Here---------------->

        <!--------------- Business Information Start Here---------------->
        <div class="col-lg-6 col-md-6 col-sm-6" id="accordionFlushExample2">
            <div class="accordion accordion-flush">
                <div class="accordion-item" style="border-top: none;">
                    <h6 class="accordion-header" id="flush-headingtwo">
                        <!-- <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                        <h6 class="sub-title accordiantitle">{{--__('Business Information')--}}</h6>
                        </button> -->
                    </h6>
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2" style="padding-top: 0px;">
                        <div class="basicinfodiv">
<div class="row">
           
                        <div class="table-responsive" id="newTaxDeclarations"  style="padding: 2px;padding-top: 0px;">
                           
                       
            </div>
        </div>
        <!--------------- Land Apraisal Listing End Here------------------><br />
                                
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Business Information End Here------------------>
    </div>

   

</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    
</div>

 <div class="modal" id="updateTaxRateScheduleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content"  style=" 
    position: relative;
    display: flex;
    flex-direction: column;
    width: 678px;
    pointer-events: auto;
    background-color: #ffffff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 15px;
    outline: 0;
    float: left;
    margin-left: 50%;
    margin-top: 90%;
    transform: translate(-50%, -50%);">
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
                            <!-- <input type="button" id="showLandUnitValueScheduleModal" value="Schedule" class="btn btn-light" > -->
                                    <a href="{{ route('rptlandunitvalue.index') }}" target="_blank"><input type="button" value="Schedule" class="btn btn-primary" ></i></a>
                                       
                               
                            </div>
                                </div><br />

                                <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            
                                    <label for="land_market_value" class="form-label" style="margin-top:8px;">Plants|Trees Unit Value</label>
                               
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                    <a href="{{ route('rptplanttressunitvalue.index') }}" target="_blank"><input type="button" value="Schedule" class="btn btn-primary" ></i></a>
                                     <!-- <input type="button" id="showPlantsTressUnitValueScheduleModal" value="Schedule" class="btn btn-light" >    -->
                               
                            </div>
                                </div>
                                <br />

                                <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            
                                    <label for="land_market_value" class="form-label" style="margin-top:8px;">Building Unit Value</label>
                               
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                    <a href="{{ route('rptbuildingunitvalue.index') }}" target="_blank"><input type="button" value="Schedule" class="btn btn-primary" ></i></a>

                                     <!-- <input type="button" id="showBuildingUnitValueScheduleModal" value="Schedule" class="btn btn-light" >    -->
                               
                            </div>
                                </div>
                                <br />

                                <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            
                                    <label for="land_market_value" class="form-label" style="margin-top:8px;">Assessment Level</label>
                               
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                    <a href="{{ route('assessmentlevel.index') }}" target="_blank"><input type="button" value="Schedule" class="btn btn-primary" ></i></a>
                                     <!-- <input type="button" id="showAssessementLevelScheduleModal" value="Schedule" class="btn btn-light" >    -->
                               
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
{{Form::close()}}
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
<script src="{{ asset('js/addGeneralRevision.js') }}"></script>
<!-- <script src="{{-- asset('js/ajax_rptProperty.js') --}}"></script> -->