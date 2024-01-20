{{Form::open(array('name'=>'forms','url'=>'rptproperty/dp/submit','method'=>'post','id'=>'disputeIntermediateSubmission'))}}
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('pk_description', __('Property Kind'),['class'=>'form-label']) }}
                                    <div class="form-icon-user">
                                        {{ Form::text('pk_description', (isset($selectedProperty->propertyKindDetails->pk_description))?$selectedProperty->propertyKindDetails->pk_description:'', array('class' => 'form-control '.$readonly)) }}
                                    </div>
                                    <span class="validate-err" id="err_pk_description"></span>
                                </div>
                            </div>  

                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('pau_actual_use_desc', __('Actual Use'),['class'=>'form-label']) }}
                                    <div class="form-icon-user">
                                        {{ Form::text('pau_actual_use_desc', (isset($selectedProperty->landAppraisals[0]->pau_actual_use_desc))?$selectedProperty->landAppraisals[0]->pau_actual_use_desc:'', array('class' => 'form-control '.$readonly)) }}
                                    </div>
                                    <span class="validate-err" id="err_pau_actual_use_desc"></span>
                                </div>
                            </div>    

                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('assessed_value_for_all_kind', __('Assessed Value'),['class'=>'form-label']) }}
                                    <div class="form-icon-user">
                                        {{ Form::text('assessed_value_for_all_kind', (isset($selectedProperty->assessed_value_for_all_kind))?number_format($selectedProperty->assessed_value_for_all_kind,2):'', array('class' => 'form-control '.$readonly)) }}
                                    </div>
                                    <span class="validate-err" id="err_assessed_value_for_all_kind"></span>
                                </div>
                            </div>      


                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('rp_app_effective_year', __('Effective Year'),['class'=>'form-label']) }}
                                    <div class="form-icon-user">
                                        {{ Form::text('rp_app_effective_year', (isset($selectedProperty->rp_app_effective_year))?$selectedProperty->rp_app_effective_year:'', array('class' => 'form-control '.$readonly)) }}
                                    </div>
                                    <span class="validate-err" id="err_rp_app_effective_year"></span>
                                </div>
                            </div>    

                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('rp_app_taxability', __('Taxability'),['class'=>'form-label']) }}
                                    <div class="form-icon-user">
                                        {{ Form::text('rp_app_taxability', (isset($selectedProperty->rp_app_taxability))?($selectedProperty->rp_app_taxability==1)?'Taxable':'Exempt':'', array('class' => 'form-control '.$readonly)) }}
                                    </div>
                                    <span class="validate-err" id="err_rp_app_taxability"></span>
                                </div>
                            </div>    

                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('rp_pin_declaration_no', __('PIN'),['class'=>'form-label']) }}
                                    <div class="form-icon-user">
                                        {{ Form::text('rp_pin_declaration_no', (isset($selectedProperty->rp_pin_declaration_no))?$selectedProperty->rp_pin_declaration_no:'', array('class' => 'form-control '.$readonly)) }}
                                    </div>
                                    <span class="validate-err" id="err_rp_pin_declaration_no"></span>
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
                        <h6 class="sub-title accordiantitle">{{__('Property Appraisals & Assessment Details')}}</h6>
                        </button>
                    </h6>
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                        <div class="row"  id="otheinfodiv">
                             <div class="col-md-12">
                <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="new_added_land_apraisal">
                                <thead>
                                    <tr>
                                        <th>{{__('Kind/Class')}}</th>
                                        <th>{{__("Area")}}</th>
                                        <th>{{__('Unit Value')}}</th>
                                        <th>{{__('Market Value')}}</th>
                                        <th>{{__('Assessed Value')}}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(isset($selectedProperty->landAppraisals) && !$selectedProperty->landAppraisals->isEmpty())
                                    @foreach($selectedProperty->landAppraisals as $val)
                                        <tr class="font-style">
                                            <td class="app_qurtr">{{ $val->pk_code.'-'.$val->class->pc_class_code }}</td>
                                            <td class="app_qurtr">{{($val->lav_unit_measure == 1)?number_format($val->rpa_total_land_area,3).' ':number_format($val->rpa_total_land_area,4).' '}} </span> {{ config('constants.lav_unit_measure')[$val->lav_unit_measure] }}</td>
                                             <td class="app_qurtr">{{ Helper::decimal_format($val->lav_unit_value) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($val->rpa_base_market_value) }}</td> 
                                            <td class="app_qurtr">{{ Helper::decimal_format($val->rpa_assessed_value) }}</td>
                                            
                                        </tr>
                                    @endforeach
                                    @endif
                                    <tr class="font-style last-option">
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
        </div>
               
               </div>

               <div class="row">
             <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample2">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingtwo">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                        <h6 class="sub-title accordiantitle">{{__('Remarks')}}</h6>
                        </button>
                    </h6>
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                        <div class="row"  id="otheinfodiv">
                           
               
             
             <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('uc_code', __('Remarks'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::textarea('remarks',(isset($selectedProperty->propertyApproval->rp_app_cancel_remarks))?$selectedProperty->propertyApproval->rp_app_cancel_remarks:'', array('class' => 'form-control')) }}
                        <input type="hidden" name="approvalformid" value="{{(isset($selectedProperty->propertyApproval->id))?$selectedProperty->propertyApproval->id:''}}">
                    </div>
                    <span class="validate-err" id="err_remarks"></span>
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
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <input type="submit" name="Finish" id=""  value="Finish" class="btn  btn-primary">
        </div>
    </div>
{{Form::close()}}



