{{Form::open(array('name'=>'forms','url'=>'rptproperty/sd/submit','method'=>'post','id'=>'subdivisionIntermediateSubmission'))}}
 {{ Form::hidden('oldpropertyid',$selectedProperty->id, array('id' => 'id')) }}
 {{ Form::hidden('updateCode',$updateCode, array('id' => 'uc_code','class'=>'uc_code')) }}
 {{ Form::hidden('propertykind',$selectedProperty->pk_id, array('id' => 'pk_id','class'=>'pk_id')) }}
 {{ Form::hidden('selectedlandappraisal','', array('id' => 'selectedlandappraisal','class'=>'selectedlandappraisal')) }}

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
    }.hilight-row{
        background-color: #c4eff5 !important;
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
                        {{ Form::text('rp_td_no', (isset($selectedProperty->rp_td_no))?$selectedProperty->rp_td_no:'', array('class' => 'form-control '.$readonly,'value'=>'Street & Number')) }}
                    </div>
                    <span class="validate-err" id="err_uc_description"></span>
                </div>
            </div> 
            
            <div class="col-md-3">
               <div class="form-group">
                    {{ Form::label('rp_suffix', __('Suffix'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('rp_suffix',(isset($selectedProperty->rp_suffix))?$selectedProperty->rp_suffix:'', array('class' => 'form-control '.$readonly,'value'=>'Street & Number')) }}
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
                            <table class="table" id="prevPropertyLandAppraisal">
                                <thead>
                                    <tr>
                                        <!-- <th></th> -->
                                        <th>{{__('Kind/Class')}}</th>
                                        <th>{{__("Total Area")}}</th>
                                        <!-- <th>{{__("Remaining Area")}}</th> -->
                                        <th>{{__('Unit Value')}}</th>
                                        <th>{{__('Market Value')}}</th>
                                        <th>{{__('Assessed Value')}}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($selectedProperty->landAppraisals as $RptPropertyAppraisal)
                                      @php
                                      if($RptPropertyAppraisal->lav_unit_measure == 1){
                                                   $selectedMesuare[] = 's';
                                              }else{
                                                   $selectedMesuare[] = 'h';
                                              }
                                      @endphp
                                      @endforeach
                                    @if(isset($selectedProperty->landAppraisals) && !$selectedProperty->landAppraisals->isEmpty())
                                    @php
                                    $t_area=0;
                                    $measureIn = '';
                                    @endphp
                                    @foreach($selectedProperty->landAppraisals as $key=>$val)
                                    @php
                                    if($val->lav_unit_measure == 1){
                                     $newArea = $val->rpa_total_land_area/10000;
                                }else{
                                     $newArea = $val->rpa_total_land_area;
                                }

                                if(count(array_unique($selectedMesuare)) == 2){
                                           $newArea = $newArea;
                                           $measureIn = '2';
                                      }else{
                                        if(isset(array_unique($selectedMesuare)[0]) && array_unique($selectedMesuare)[0] == 's'){
                                           $newArea = $val->rpa_total_land_area;
                                           $measureIn = '1';
                                      }if(isset(array_unique($selectedMesuare)[0]) && array_unique($selectedMesuare)[0] == 'h'){
                                           $newArea = $val->rpa_total_land_area;
                                           $measureIn = '2';
                                      }
                                    }
                                    $t_area=$t_area+$newArea;
                                    @endphp
                                        <tr class="font-style">
                                            <!-- <td><input type="checkbox" data-landappraisalid="{{ $val->id }}" class="landAppraisalIdForSubdivision" value="{{ $val->id }}" {{ ($key == '0')?'checked':''}}/></td> -->
                                            <td class="app_qurtr">{{ $val->pk_code.'-'.$val->class->pc_class_code }}</td>
                                            <!-- <td class="app_qurtr"><span class="total-area-{{ $val->id }}">{{($val->lav_unit_measure == 1)?number_format($val->rpa_total_land_area,3).' ':number_format($val->rpa_total_land_area,4).' '}} </span> {{ config('constants.lav_unit_measure')[$val->lav_unit_measure] }}</td> -->
											@php $remaining = $val->rpa_total_land_area; @endphp
                                            <td ><span class="">{{($val->lav_unit_measure == 1)?number_format($remaining,3).' ':number_format($remaining,4).' '}}</span> {{ config('constants.lav_unit_measure')[$val->lav_unit_measure] }}</td>
											<!--remaining-area-{{ $val->id }}-->
                                             <td class="app_qurtr">{{ number_format($val->lav_unit_value,2) }}</td>
                                            <td class="app_qurtr">{{ number_format($val->rpa_base_market_value,2) }}</td> 
                                            <td class="app_qurtr">{{ number_format($val->rpa_assessed_value,2) }}</td>
                                            
                                        </tr>
                                    @endforeach
                                    @endif
                                    <tr class="font-style last-option">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <!-- <td></td> -->
                                    </tr>
                                    <tr class="font-style">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <!-- <td></td> -->
                                    </tr>
                                    <tr class="font-style">
                                        <td>Total</td>
                                        <td>{{number_format((float)$t_area, (($measureIn == 2)?4:3))}} {{($measureIn == 1)?config('constants.lav_unit_measure_short.1'):config('constants.lav_unit_measure_short.2')}}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <!-- <td></td> -->
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
               <div id="newSubDividedTaxDeclarationDetails"></div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <input type="submit" name="Next"   value="Finish" class="btn  btn-primary">
        </div>
    </div>
{{Form::close()}}
<div class="modal" id="subdivisionstep2modal" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-lg modalDiv" >
            <div class="modal-content" >
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="submit" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="body">
            </div>
            </div>
        </div>
    </div>


