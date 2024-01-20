{{Form::open(array('name'=>'forms','url'=>'rptmachinery/approve','method'=>'post','id'=>'saveApprovelFormData'))}}
 {{ Form::hidden('id',(isset($approvelFormData->id))?$approvelFormData->id:'', array('id' => 'id')) }}
  <!-- <link href="{{ asset('assets/js/yearpicker.css')}}" rel="stylesheet"> -->
 <style>
    .form-control:focus {
  outline: none;
  box-shadow:none !important;
  border:1px solid #ced4da;
}
    .modal-body {
        position: relative;
        flex: 1 1 auto;
        padding: 1.25rem;
        /* padding-bottom: 5%; */
    }
    .modal-xll {
        max-width: 1350px !important;
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
    .textright{text-align:right;}
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
    .row{padding-top:10px;}
    .choices__inner {
        min-height: 35px;
        padding:5px ;
        padding-left:5px;
    }
    .field-requirement-details-status label{margin-top: 7px;}
    #flush-collapsetwo{
/*        padding-bottom: 80px;*/
    }
    .custom-date-input input[type="date"] {
                                        width: 125px;
    }
    .custom-date-input {
        position: relative;
        z-index: 999;
    }
 </style>
 
<div class="modal-header">
                <h4 class="modal-title">Approval Form</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div><div class="container"></div>
<div class="modal-body">
    <div class="row pt10" >
        <!----  Approval Data Info ------------>
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" style="margin-top: -20px;">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                            <h6 class="sub-title accordiantitle">{{__("Previous Owner (Cancelled TD or FAAS)")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                        <div class="basicinfodiv" style="    margin-top: -40px;">
                            <!--------------- Land Apraisal Listing Start Here------------------>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="new_added_land_apraisal">
                                <thead>
                                    <tr>
                                        <th>{{__('Cancelled By TD No.')}}</th>
                                        <th>{{__("Year")}}</th>
                                        <th>{{__('Cancelled TD No.')}}</th>
                                        <th>{{__('Owner')}}</th>
                                        <th>{{__('Assessed Value')}}</th>
                                        <th>{{__('Actual Use Code')}}</th>
                                        <th>{{__('Taxable')}}</th>
                                        <th>{{__('Effectivity')}}</th>
                                        <th>{{__('Approved Date')}}</th>
                                        <th>{{__('P.I.N')}}</th>
                                        <th style="width: 5%;">
                                            @if(isset($approvelFormData->rp_code) && $approvelFormData->rp_code != '')
                                            <a data-toggle="modal" href="javascript:void(0)" data-propertyid="{{ (isset($approvelFormData->rp_code) && $approvelFormData->rp_code != '')?$approvelFormData->rp_code:'' }}" id="{{(isset($approvelFormData->rp_code) && $approvelFormData->rp_code != '')?'addPreviousOwnerForMachinery':''}}" class="btn btn-primary" ><i class="ti-plus"></i>
                                            </a>
                                            @endif
                                        </th>
                                    
                                    </tr>
                                </thead>
                                <tbody>

                                    
                                    @foreach($history as $key=>$val)
                                      @if($val->cancelProp != null)
                                       @php
                                        $pau_actual_use_code = $val->cancelProp->machineAppraisals[0]->actualUses->pau_actual_use_code.'-'.$val->cancelProp->machineAppraisals[0]->actualUses->pau_actual_use_desc;
                                         @endphp
                                        <tr class="font-style" >
                                                                    <td class="app_qurtr">{{ ($val->activeProp != null)?$val->activeProp->rp_tax_declaration_no:'' }}</td>

                                                                    <td class="app_qurtr">{{ ($val->cancelProp != null)?$val->cancelProp->revisionYearDetails->rvy_revision_year:'' }}</td>
                                                                    <td class="app_qurtr">{{ $val->cancelProp->rp_tax_declaration_aform}}</td>
                                                                    <td class="app_qurtr">{{ $val->cancelProp->propertyOwner->standard_name }}</td> 
                                                                    
                                                                    <td class="app_qurtr">{{ number_format($val->cancelProp->machineAppraisals->sum('rpm_assessed_value'), 2, '.', ',') }}</td>
                                                                    <td class="app_qurtr"><span class="showLess">{{ $pau_actual_use_code }}</span></td> 
                                                                    <td class="app_qurtr">{{ ($val->cancelProp->rp_app_taxability==1)?'Taxable':'Exempt'}}</td>
                                                                    <td class="app_qurtr">{{ $val->cancelProp->rp_app_effective_year}} - {{ config('constants.payModePartitionShortCut')[3][$val->cancelProp->rp_app_effective_quarter]  }}</td>
                                                                    <td class="app_qurtr">{{ $val->cancelProp->propertyApproval->rp_app_approved_date}}</td>
                                                                    <td class="app_qurtr">{{ $val->cancelProp->rp_pin_declaration_no}}</td>
                                                                    <td class="app_qurtr">
                                                                        @if($val->cancelProp->pk_is_active == 9)
                                                                        <a href="javascript:void(0)"  data-id="{{ $val->cancelProp->id }}" class="editPreviousOwnerTd"><i class="fas fa-edit"></i></a>&nbsp;&nbsp;<a href="javascript:void(0)" data-id="{{ $val->cancelProp->id }}" data-history="{{ $val->id }}" class="deletePreviousOwnerTd"><i class="fas fa-trash"></i></a>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @endif
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
                                        <td></td>
                                        <td></td>
                                    </tr>
                                  <!--   <tr class="font-style">
                                        <td></td>
                                        <td></td>
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
                                        <td></td>
                                        <td></td>
                                    </tr> -->
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

        
        <!--------------- Owners Information Start Here---------------->
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <!-- <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                            <h6 class="sub-title accordiantitle">{{__("Owner's Information")}}</h6>
                        </button> -->
                    </h6>
                    <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                        <div class="basicinfodiv">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 textright">
                                        {{Form::label('rp_app_appraised_by',__('Appraised By'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                </div>
                                 <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-icon-user">
                                            {{Form::select('rp_app_appraised_by',$appraisers,(isset($approvelFormData->rp_app_appraised_by))?$approvelFormData->rp_app_appraised_by:'',array('class'=>'form-control err_rp_app_appraised_by','placeholder'=>'Select Name'))}}
                                        </div>
                                        <span class="validate-err" id="err_rp_app_appraised_by"></span>
                                </div>
                                 <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-icon-user custom-date-input">
                                            {{Form::date('rp_app_appraised_date',(isset($approvelFormData->rp_app_appraised_date) && $approvelFormData->rp_app_appraised_date != '')?$approvelFormData->rp_app_appraised_date:date("Y-m-d"),array('class'=>'form-control err_rp_app_appraised_date','rows'=>1))}}
                                        </div>
                                        <span class="validate-err" id="err_rp_app_appraised_date"></span>
                                </div>
                                 <div class="col-lg-1 col-md-1 col-sm-1" style="text-align: right;">
                                
                                {{ Form::checkbox('rp_app_appraised_is_signed', '1', (isset($approvelFormData->rp_app_appraised_is_signed) && $approvelFormData->rp_app_appraised_is_signed)?true:false, array('id'=>'rp_app_appraised_is_signed','class'=>'form-check-input rp_app_appraised_is_signed code')) }}
                                                    
                                                   
                                 </div>  
                                 <div class="col-lg-2 col-md-2 col-sm-2">
                                       {{Form::label('rp_app_taxability',__('Taxability'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                </div>                 
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-icon-user">
                                            {{Form::select('rp_app_taxability',[1=>'Taxable',0=>'Exempt'],(isset($approvelFormData->rp_app_taxability))?$approvelFormData->rp_app_taxability:'',array('class'=>'form-control err_rp_app_taxability'))}}
                                        </div>
                                        <span class="validate-err" id="err_rp_app_taxability"></span>
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 textright">
                                        {{Form::label('rp_app_recommend_by',__('Recommended By'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                </div>
                                 <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-icon-user">
                                            {{Form::select('rp_app_recommend_by',$appraisers,(isset($approvelFormData->rp_app_recommend_by))?$approvelFormData->rp_app_recommend_by:'',array('class'=>'form-control err_rp_app_recommend_by','placeholder'=>'Select Name'))}}
                                        </div>
                                        <span class="validate-err" id="err_rp_app_recommend_by"></span>
                                </div>
                                 <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-icon-user custom-date-input">
                                            {{Form::date('rp_app_recommend_date',(isset($approvelFormData->rp_app_recommend_date) && $approvelFormData->rp_app_recommend_date != '')?$approvelFormData->rp_app_recommend_date:date("Y-m-d"),array('class'=>'form-control err_rp_app_recommend_date','rows'=>1))}}
                                        </div>
                                        <span class="validate-err" id="err_rp_app_recommend_date"></span>
                                </div>
                                 <div class="col-lg-1 col-md-1 col-sm-1" style="text-align: right;">
                                {{ Form::checkbox('rp_app_recommend_is_signed', '1', (isset($approvelFormData->rp_app_recommend_is_signed))?$approvelFormData->rp_app_recommend_is_signed:0, array('id'=>'rp_app_recommend_is_signed','class'=>'form-check-input rp_app_recommend_is_signed')) }}
                                                   
                                 </div>  
                                 <div class="col-lg-2 col-md-2 col-sm-2">
                                       {{Form::label('rp_app_effective_year',__('Effectivity'),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                </div>  
                                 <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-icon-user">
                                            <?php
                                                $currentYear = date('Y');
                                                $nextYear = date('Y', strtotime('+1 year'));
                                            ?>
                                            {{ Form::text('rp_app_effective_year', isset($approvelFormData->rp_app_effective_year) ? $approvelFormData->rp_app_effective_year : $nextYear, array('class' => 'yearpicker form-control', 'rows' => 1)) }}
                                        </div>
                                        <span class="validate-err" id="err_rp_app_effective_year"></span>
                                </div>               
                                 <div class="col-lg-1 col-md-1 col-sm-1">
                                       {{Form::label('rp_app_effective_quarter',__('Quarter'),['class'=>'form-label'])}}
                                </div>  
                                 <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-icon-user">
                                            {{Form::select('rp_app_effective_quarter',['1'=>'1st','2'=>'2nd','3'=>'3rd','4'=>'4th'],(isset($approvelFormData->rp_app_effective_quarter))?$approvelFormData->rp_app_effective_quarter:'',array('class'=>'form-control err_rp_app_effective_quarter','disabled'=>'disabled'))}}
                                        </div>
                                        <span class="validate-err" id="err_rp_app_effective_quarter"></span>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 textright">
                                        {{Form::label('rp_app_approved_by',__('Approved By'),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                </div>
                                 <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-icon-user">
                                            {{Form::select('rp_app_approved_by',$appraisers,(isset($approvelFormData->rp_app_approved_by))?$approvelFormData->rp_app_approved_by:'',array('class'=>'form-control err_rp_app_approved_by','placeholder'=>'Select Name'))}}
                                        </div>
                                        <span class="validate-err" id="err_rp_app_approved_by"></span>
                                </div>
                                 <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-icon-user custom-date-input">
                                            {{Form::date('rp_app_approved_date',(isset($approvelFormData->rp_app_approved_date) && $approvelFormData->rp_app_approved_date != '')?$approvelFormData->rp_app_approved_date:date("Y-m-d"),array('class'=>'form-control err_rp_app_approved_date','rows'=>1))}}
                                        </div>
                                        <span class="validate-err" id="err_rp_app_approved_date"></span>
                                </div>
                                 <div class="col-lg-1 col-md-1 col-sm-1" style="text-align: right;">
                                {{ Form::checkbox('rp_app_approved_is_signed', '1', (isset($approvelFormData->rp_app_approved_is_signed))?$approvelFormData->rp_app_approved_is_signed:0, array('id'=>'rp_app_approved_is_signed','class'=>'form-check-input rp_app_approved_is_signed')) }}
                                                   
                                 </div>  
                                 <div class="col-lg-2 col-md-2 col-sm-2">
                                       {{Form::label('updatedcode',__('Updated Code'),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                </div>                 
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-icon-user" id="uc_codediv">
                                            {{Form::select('uc_code',$arrUpdateCodes,$ucCode,array('class'=>'form-control select3 uc_code err_uc_code','readonly'=>'readonly','id'=>'uc_code1'))}}
                                        </div>
                                        <span class="validate-err" id="err_uc_code"></span>
                                </div>
                            </div>
                            
                            
                            <div class="row">
                                 <div class="col-lg-7 col-md-7 col-sm-7">
                                        <div class="form-icon-user">
                                           
                                        </div>
                                        <span class="validate-err" id="err_loc_group_brgy_no"></span>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::label('rp_app_posting_date',__('Posting Date'),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                </div>
                                
                                 <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-icon-user">
                                            {{Form::date('rp_app_posting_date',(isset($approvelFormData->rp_app_posting_date) && $approvelFormData->rp_app_posting_date)?$approvelFormData->rp_app_posting_date:date("Y-m-d"),array('class'=>'form-control err_rp_app_posting_date','rows'=>1))}}
                                        </div>
                                        <span class="validate-err" id="err_rp_app_posting_date"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 textright">
                                        {{Form::label('rp_app_memoranda',__('Memoranda'),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                </div>
                                 <div class="col-lg-10 col-md-10 col-sm-10">
                                    @php $annotation = (isset($annotation))?'&#13;&#10;'.$annotation:''; @endphp
                                        <div class="form-icon-user">
                                             {{Form::textarea('rp_app_memoranda',(isset($approvelFormData->rp_app_memoranda))?$approvelFormData->rp_app_memoranda:'',array('class'=>'form-control err_rp_app_memoranda','rows'=>1,'style' => "border-bottom: 0px;border-radius: 5px 5px 0px 0px;resize: none;"))}}<label for="name" style="font-weight: bold;position :absolute; margin-left: 5px;">Annotation:</label>
                                             {{Form::textarea('annotation',$annotation,array('class'=>'form-control annotation','rows'=>2,'readonly'=>true,'style' => "border-top: 0px;border-radius: 0px 0px 5px 5px;resize: none;background-color: #fff;"))}}
                                        </div>
                                        <span class="validate-err" id="err_rp_app_memoranda"></span>
                                </div>
                            </div>
                             <div class="row">
                                
                                 <div class="col-lg-2 col-md-2 col-sm-2 textright">
                                        {{Form::label('rp_app_extension_section',__('Extension - Section No:'),['class'=>'form-label'])}} 
                                </div>
                                 <div class="col-lg-5 col-md-5 col-sm-5">
                                        <div class="form-icon-user">
                                             {{Form::text('rp_app_extension_section',(isset($approvelFormData->rp_app_extension_section))?$approvelFormData->rp_app_extension_section:'',array('class'=>'form-control err_rp_app_extension_section','rows'=>1))}}
                                        </div>
                                        <span class="validate-err" id="err_rp_app_extension_section"></span>
                                </div>
                                 <div class="col-lg-2 col-md-2 col-sm-2 ">
                                       {{Form::label('rp_app_assessor_lot_no',__('Ass lot No'),['class'=>'form-label'])}} 
                                </div>  
                                 <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-icon-user">
                                            {{Form::text('rp_app_assessor_lot_no',(isset($approvelFormData->rp_app_assessor_lot_no))?$approvelFormData->rp_app_assessor_lot_no:'',array('class'=>'form-control err_rp_app_assessor_lot_no','rows'=>1))}}
                                        </div>
                                        <span class="validate-err" id="err_rp_app_assessor_lot_no"></span>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Owners Information Start Here---------------->

        <!--------------- Business Information Start Here---------------->
        <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample2">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingtwo">
                    </h6>
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                        <div class="basicinfodiv">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 textright">
                                        {{Form::label('status',__('Status'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                </div>
                                 <div class="col-lg-3 col-md-3 col-sm-3">
                                       
                                    <div class="d-flex radio-check">
                                        <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('pk_is_active', 1, (isset($approvelFormData->pk_is_active) && $approvelFormData->pk_is_active =='1')?true:false, array('id'=>'annualy','class'=>'form-check-input code','checked'=>true)) }}
                                            {{ Form::label('pk_is_active', __('Active'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('pk_is_active', '0', (isset($approvelFormData->pk_is_active) && $approvelFormData->pk_is_active =='0')?true:false, array('id'=>'queterly','class'=>'form-check-input code')) }}
                                            {{ Form::label('pk_is_active', __('Cancelled'),['class'=>'form-label']) }}
                                        </div>
                                    </div>
                                    <span class="validate-err" id="err_pk_is_active"></span>
                                </div>
                            </div>
                            <div class="row" >
                                <div class="col-lg-2 col-md-2 col-sm-2 textright">
                                        {{Form::label('rp_app_cancel_is_direct',__('Direct Cancellation'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                </div>
                                 <div class="col-lg-3 col-md-3 col-sm-3">
                                    {{ Form::checkbox('rp_app_cancel_is_direct', '1', (isset($approvelFormData->rp_app_cancel_is_direct))?$approvelFormData->rp_app_cancel_is_direct:false, array('id'=>'rp_app_cancel_is_direct','class'=>'form-check-input rp_app_cancel_is_direct','disabled'=>'true')) }}
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3"></div>
                                <div class="col-lg-2 col-md-2 col-sm-2 textright">
                                     {{Form::label('rp_app_cancel_by',__('Cancelled By:-'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                   <span>{{ (isset($approvelFormData->name))?$approvelFormData->name:\Auth::user()->name }}</span>
                                   <input type="hidden" name="rp_app_cancel_by" value="{{ \Auth::user()->creatorId() }}">

                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 textright">
                                        {{Form::label('cance;',__('Cancellation Type'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                </div>
                                 <div class="col-lg-3 col-md-3 col-sm-3">
                                    
                                     {{ Form::select('rp_app_cancel_type',$arrUpdateCodes,(isset($approvelFormData->rp_app_cancel_type))?$approvelFormData->rp_app_cancel_type:'', array('class' => 'form-control select3 rp_app_cancel_type','id'=>'rp_app_cancel_type','placeholder'=>'Select Cancelation Type','disabled'=>'true')) }}
                                     <span class="validate-err" id="err_rp_app_cancel_type"></span>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3"></div>
                                <div class="col-lg-2 col-md-2 col-sm-2 textright">
                                     {{Form::label('datecancelled',__('Date Cancelled'),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                     {{Form::date('rp_app_cancel_date',(isset($approvelFormData->rp_app_cancel_date))?$approvelFormData->rp_app_cancel_date:'',array('class'=>'form-control','id'=>'rp_app_cancel_date','rows'=>1,'disabled'=>'true'))}}
                                     <span class="validate-err" id="err_rp_app_cancel_date"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 textright">
                                        {{Form::label('remark',__('Cancelled By TD No.'),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                </div>
                                 <div class="col-lg-10 col-md-10 col-sm-10">
                                        <div class="form-icon-user">
                                            {{ Form::select('rp_app_cancel_by_td_id',$allTds,(isset($approvelFormData->rp_app_cancel_by_td_id))?$approvelFormData->rp_app_cancel_by_td_id:'', array('class' => 'form-control select3 rp_app_cancel_by_td_id','id'=>'rp_app_cancel_by_td_id','disabled'=>'true','multiple'=>'multiple')) }}
                                     
                                        </div>
                                        <span class="validate-err" id="err_rp_app_cancel_by_td_id"></span>
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 textright">
                                        {{Form::label('remark',__('Remark'),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                </div>
                                 <div class="col-lg-10 col-md-10 col-sm-10">
                                        <div class="form-icon-user">
                                             {{Form::textarea('rp_app_cancel_remarks',(isset($approvelFormData->rp_app_cancel_remarks))?$approvelFormData->rp_app_cancel_remarks:'',array('class'=>'form-control','id'=>'rp_app_cancel_remarks','rows'=>2,'disabled'=>'true'))}}
                                        </div>
                                        <span class="validate-err" id="err_rp_app_cancel_remarks"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Business Information End Here------------------>
    </div>

</div>
<div class="modal-footer" style="    margin-top: -10px;">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">

     <input type="submit" name="submit" id="submit"  value="{{ (isset($approvedata->id) && $approvedata->id > 0)?__('Update'):__('Save')}}" class="btn  btn-primary">
  
</div>
{{Form::close()}}
<script type="text/javascript">
var status          = '{{(isset($propertyDetails->pk_is_active))?$propertyDetails->pk_is_active:''}}';
        var directCancelled = '{{(isset($approvelFormData->rp_app_cancel_is_direct))?$approvelFormData->rp_app_cancel_is_direct:0}}';
        if(status > 0 || directCancelled > 0){
                activeInactiveCancelledPart();
            }
        var directCancelCodes  = '<?php echo json_encode($directCancelUcCOdes,JSON_UNESCAPED_UNICODE) ?>';
        var allUpdateCodes     = '<?php echo json_encode($arrUpdateCodes,JSON_UNESCAPED_UNICODE) ?>';
        var idofproperty       = '{{(isset($approvelFormData->rp_code))?$approvelFormData->rp_code:''}}';
        var selectedUpdateCode = '{{(isset($approvelFormData->rp_app_cancel_type))?$approvelFormData->rp_app_cancel_type:''}}';
        //alert(approvelFormData);
        var allUpdateCodesSelectList = "<option value=''>Select Cancellation Type</option>";
        allUpdateCodes = JSON.parse(allUpdateCodes);

         for (var key in allUpdateCodes) {
            if (allUpdateCodes.hasOwnProperty(key)) {
                if(selectedUpdateCode != '' && key == selectedUpdateCode){
                    allUpdateCodesSelectList+="<option selected value="+key+">"+allUpdateCodes[key]+"</option>";
                }else{
                    allUpdateCodesSelectList+="<option value="+key+">"+allUpdateCodes[key]+"</option>";
                }
                
            }
        }
        var directCancelCodes = '<?php echo json_encode($directCancelUcCOdes,JSON_UNESCAPED_UNICODE) ?>';
        var directCancelCodesSelectList = "<option value=''>Select Cancellation Type</option>";
        directCancelCodes = JSON.parse(directCancelCodes);
         for (var key in directCancelCodes) {
            if (directCancelCodes.hasOwnProperty(key)) {
                if(selectedUpdateCode != '' && key == selectedUpdateCode){
                    directCancelCodesSelectList+="<option selected value="+key+">"+directCancelCodes[key]+"</option>";
                }else{
                    directCancelCodesSelectList+="<option value="+key+">"+directCancelCodes[key]+"</option>";
                }
            }
        }
        
            $('input[name=rp_app_cancel_is_direct]').click(function(){
            if($('input[name=rp_app_cancel_is_direct]').prop('checked') == true){
                    $('select[name=rp_app_cancel_type]').html(directCancelCodesSelectList);
                }else{
                    $('select[name=rp_app_cancel_type]').html(allUpdateCodesSelectList);
                }
        });
        $(document).off('click','input[name=pk_is_active]').on('click','input[name=pk_is_active]',function(){
        var ucCode          = '{{(isset($propertyDetails->uc_code))?$propertyDetails->uc_code:''}}';
        var id              = '{{(isset($propertyDetails->id))?$propertyDetails->id:0}}';
        

        if(id > 0){
            if(status > 0 || directCancelled > 0){
                activeInactiveCancelledPart();
            }
            
        }
    });
</script>


