<div class="modal-header">
                                <h4 class="modal-title">Land Unit Value</h4>
                                <a class="close closeLandUnitValueScheduleView" mid="" type="edit" data-dismiss="modal" aria-hidden="true">X</a>
                            </div>
                            <div class="container"></div>
                            <div class="modal-body">
                                <div class="basicinfodiv">
                                    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row">

                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="form-group">
                        {{Form::label('from_rvy_revision_year_id',__("Revision Year"),['class'=>'form-label'])}}
                <div class="form-icon-user">
                    {{Form::text('rvy_revision_year_id',($activeRevisionYearDetails != null)?$activeRevisionYearDetails->rvy_revision_year.'-'.$activeRevisionYearDetails->rvy_revision_code:'No Active Revision',array('class'=>'form-control rp_tax_declaration_no','readonly'=>'readonly'))}}
                    <input type="hidden" name="from_rvy_revision_year_id" value="{{-- ($oldRevisionYearDetails != null)?$oldRevisionYearDetails->id:'0' --}}">
                </div>
                <span class="validate-err" id="err_rvy_revision_year_id"></span>
            </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="form-group">
                        {{Form::label('rvy_revision_year_id',__("Location Group"),['class'=>'form-label'])}}
                <div class="form-icon-user">
                    {{Form::text('brgy_code_id_desc',($barangayDetails != null)?$barangayDetails->brgy_code.'-'.$barangayDetails->brgy_name:'No Active Revision',array('class'=>'form-control brgy_code_id_desc','readonly'=>'readonly'))}}
                    <input type="hidden" name="brgy_code_id" value="{{($barangayDetails != null)?$barangayDetails->brgy_code.'-'.$barangayDetails->brgy_name:'0'}}">
                </div>
                <span class="validate-err" id="err_rvy_revision_year_id"></span>
            </div>
                </div>


                 <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="form-group">
                        {{Form::label('rvy_revision_year_id',__("Muncipality"),['class'=>'form-label'])}}
                <div class="form-icon-user">
                    {{Form::text('municipality',($barangayDetails != null)?$barangayDetails->mun_desc:'No Active Revision',array('class'=>'form-control municipality','readonly'=>'readonly'))}}
                    <input type="hidden" name="to_rvy_revision_year_id" value="{{-- ($activeRevisionYearDetails != null)?$activeRevisionYearDetails->id:'0' --}}">
                </div>
                <span class="validate-err" id="err_rvy_revision_year_id"></span>
            </div>
                </div>

            </div>
        </div>
        
    </div>
    <div class="row pt10" >
        <!--------------- Owners Information Start Here---------------->
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <!-- <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                            <h6 class="sub-title accordiantitle">{{--__("Owner's Information")--}}</h6>
                        </button> -->
                    </h6>
                    <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                        <div class="basicinfodiv">
                           <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive" id="landUnitValueListing">
                            
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
    </div>
                                </div>
                               
                            </div>
                            <div class="modal-footer">
                                <a href="#" data-dismiss="modal" class="btn closeLandUnitValueScheduleView" mid=""  type="edit">Close</a>
                            </div>
                            