{{Form::open(array('name'=>'forms','url'=>'rptbuilding/cs/submit','method'=>'post','id'=>'consolidationIntermediateSubmission'))}}
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

    <div class="modal-body" style="overflow-x: hidden;">

         <div class="row">
             <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample2">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingtwo">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                        <h6 class="sub-title accordiantitle">{{__('Selected Property Tax Declaration No. to Cancel')}}</h6>
                        </button>
                    </h6>
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">

                    <div class="row hide">
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('uc_code', __('Series Year'),['class'=>'form-label']) }}
                            <div class="form-icon-user">
                                {{ Form::text('',(isset($selectedProperty->revisionYearDetails->rvy_revision_year))?$selectedProperty->revisionYearDetails->rvy_revision_year:'', array('class' => 'form-control '.$readonly,'required'=>'required','readonly'=>'readonly')) }}
                                <input type="hidden" name="rvy_revision_year_id" value="{{ $selectedProperty->rvy_revision_year_id }}">
                            </div>
                            <span class="validate-err" id="err_uc_code"></span>
                        </div>
                    </div>
                     <div class="col-md-3">
                       <div class="form-group">
                            {{ Form::label('', __('Barangay'),['class'=>'form-label']) }}
                            <div class="form-icon-user">
                                {{ Form::text('', (isset($selectedProperty->barangay->brgy_code))?$selectedProperty->barangay->brgy_code.'-'.$selectedProperty->barangay->brgy_name:'', array('class' => 'form-control '.$readonly,'value'=>'Street & Number','readonly'=>'readonly')) }}
                                <input type="hidden" name="brgy_code_id" value="{{ $selectedProperty->brgy_code_id }}">
                            </div>
                            <span class="validate-err" id="err_uc_description"></span>
                        </div>
                    </div>    
                    <div class="col-md-3">
                       <div class="form-group">
                            {{ Form::label('uc_description', __('Series No.'),['class'=>'form-label']) }}
                            <div class="form-icon-user">
                                {{ Form::number('selectedPropertyId', (isset($selectedProperty->id))?$selectedProperty->id:'', array('class' => 'form-control '.$readonly,'value'=>'Street & Number')) }}
                               
                            </div>
                           <!--  <span class="validate-err" id="err_id"></span> -->
                        </div>
                    </div> 
               </div>    
              <div class="row"  id="otheinfodiv">
             <div class="col-md-6">
                        <div class="form-icon-user rp_app_cancel_by_td_id_group">
                           
                            {{ Form::select('selectedPropertyId',[],'', array('class' => 'form-control selectedPropertyId','id'=>'selectedPropertyId'))}}
                         </div>
                        <span class="validate-err" id="err_id"></span>
                        <span class="validate-err" id="err_selectedPropertyId"></span>
                               
            </div>
            
            <div class="col-md-3">
               <div class="form-group">
                    <div class="form-icon-user">
                       <input type="button" name="Add Td. No." value="Apply" id="addTaxDeclarationToSession" class="btn btn-primary ">
                    </div>
                    <span class="validate-err" id="err_uc_description"></span>
                </div>
            </div>    
                        </div>
                        <div class="row"  id="taxDeclarationsToConsolidate">
                            
               
               </div>
               <br/>
               <div class="row">
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                        <!-- <button type="button" class="btn btn-success" id="plantstreesadjustmentfactor">Plants/Trees and Value Adjustment Factors</button> -->
                                       <!--  <button type="button" class="btn btn-success" id="deleteTaxDeclaToConsolidate">Delete</button> -->
                                    </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                        
                                    </div>
                                    
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        
                                    </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        
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
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <input type="submit" name="Next" id=""  value="Next" class="btn  btn-primary">
        </div>
    </div>
{{Form::close()}}



