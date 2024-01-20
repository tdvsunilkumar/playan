{{Form::open(array('name'=>'forms','url'=>'rptproperty/sd/submit','method'=>'post','id'=>'subdivisionIntermediateSubmission'))}}
 {{ Form::hidden('oldpropertyid',$selectedProperty->id, array('id' => 'id')) }}
 {{ Form::hidden('updateCode',$updateCode, array('id' => 'uc_code','class'=>'uc_code')) }}
 {{ Form::hidden('propertykind',$selectedProperty->pk_id, array('id' => 'pk_id','class'=>'pk_id')) }}
 {{ Form::hidden('selectedlandappraisal',$selectedLandAppraisal, array('id' => 'selectedlandappraisal','class'=>'selectedlandappraisal')) }}
 {{ Form::hidden('action','taxDeclarationDivisionFinsish', array('id' => 'selectedlandappraisal','class'=>'selectedlandappraisal')) }}
            <div class="row">
             <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample2">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingtwo">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                        <h6 class="sub-title accordiantitle">{{__('New Subdivided TD Records')}}</h6>
                        </button>
                    </h6>
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                        <div class="row" style="padding-top: 10px;">
            <div class="col-sm-12">
            <!-- <a data-toggle="modal" href="javascript:void(0)" id="loadLandApprisalForm" class="btn btn-primary btnPopupOpen" type="add">Add</a> -->
            <a data-toggle="modal" href="javascript:void(0)" id="loadLandApprisalFormForSubDivision" class="btn btn-primary" type="add">Create Tax Declaration Record</a> 
            </div>
            <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-6">
                    <!-- <a data-toggle="modal" href="javascript:void(0)" id="loadLandApprisalFormForSubDivision" class="btn btn-primary" type="add">Sworn Statement of Property Owner</a> -->
                </div>
                <div class="col-sm-6">
                    <!-- <a data-toggle="modal" href="javascript:void(0)" id="" class="btn btn-primary" type="add">Annotation & special Property Status</a> -->
                </div>
            </div>
            </div>
        </div>
                        <div class="row"  id="otheinfodiv">
                             <div class="col-md-12">
                <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                           <div id="sdSubdividedTaxDeclarations"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
               
               </div><br/>
               <!-- <div class="row">
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2"> -->
                                        <!-- <button type="button" class="btn btn-success" id="plantstreesadjustmentfactor">Plants/Trees and Value Adjustment Factors</button> -->
                                        <!-- <button type="button" class="btn btn-success" id="addSubdividedTaxDeclaration">Add</button>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2"> -->
                                        <!-- <button type="button" class="btn btn-success" id="plantstreesadjustmentfactor">Plants/Trees and Value Adjustment Factors</button> -->
                                        <!-- <button type="button" class="btn btn-success" id="deleteSubdividedTaxDeclaration">Delete</button>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4"> -->
                                         <!--  <button type="button" class="btn btn-success" id="plantstreesadjustmentfactor">Plants/Trees and Value Adjustment Factors</button> -->
                                    <!-- </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        
                                    </div>
                                </div> -->
               
                        
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
                        <h6 class="sub-title accordiantitle">{{__('Property Floor Values')}}</h6>
                        </button>
                    </h6>
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                        <div class="row"  id="otheinfodiv">
                             <div class="col-md-12">
                <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <div id="sdappraisallisting"></div>
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
        </div>
    </div>
       <!--  <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <input type="submit" name="Finish" id="finishSubdivision"  value="Finish" class="btn  btn-primary">
        </div> -->
    </div>
{{Form::close()}}