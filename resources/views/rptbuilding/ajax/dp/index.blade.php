{{Form::open(array('name'=>'forms','url'=>'rptbuilding/dp/submit','method'=>'post','id'=>'disputeIntermediateSubmission'))}}
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
                                        <th>{{__('FLOOR NO.')}}</th>
                                        <th>{{__("STRUCTURAL TYPE")}}</th>
                                        <th>{{__('ACTUAL USE')}}</th>
                                        <th>{{__('FLOOR AREA')}}</th>
                                        <th>{{__('UNIT VALUE')}}</th>
                                        <th>{{__('BASE MARKET VALUE')}}</th>
                                        <th>{{__('ADDITIONAL VALUE')}}</th>
                                        <th>{{__('ADJUSTMENT VALUE')}}</th>
                                        <th>{{__('TOTAL FLOOR MARKET VALUE')}}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(isset($selectedProperty->floorValues) && !$selectedProperty->floorValues->isEmpty())
                                    @foreach($selectedProperty->floorValues as $val)
                                        <tr class="font-style">
                                            <td class="app_qurtr">{{ $val->rpbfv_floor_no }}</td>
                                            <td class="app_qurtr">{{ (isset($val->bt_building_type_code_desc))?$val->bt_building_type_code_desc:'' }}</td>
                                            <td class="app_qurtr">{{ (isset($val->pau_actual_use_code_desc))?$val->pau_actual_use_code_desc:'' }}</td>
                                             <td class="app_qurtr">{{ number_format($val->rpbfv_floor_area,3) }} Sq. m.</td>
                                            <td class="app_qurtr">{{ number_format($val->rpbfv_floor_unit_value,2) }}</td> 
                                            <td class="app_qurtr">{{ number_format($val->rpbfv_floor_base_market_value,2) }}</td>
                                            <td class="app_qurtr">{{number_format( $val->rpbfv_floor_additional_value,2) }}</td> 
                                            <td class="rpa_base_market_value">{{ number_format($val->rpbfv_floor_adjustment_value,2) }}</td>
                                            <td class="app_qurtr">{{ number_format($val->rpbfv_total_floor_market_value,2) }}</td>
                                        </tr>
                                    @endforeach
                                    @endif
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



