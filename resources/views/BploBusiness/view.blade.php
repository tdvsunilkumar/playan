{{ Form::open(array('url' => 'Endrosement','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('busn_id',$busn_id, array('id' => 'busn_id')) }}
    {{ Form::hidden('payment_mode',$data->pm_id, array('id' => 'payment_mode')) }}
    {{ Form::hidden('year',$data->busn_tax_year, array('id' => 'year')) }}
     {{ Form::hidden('app_code',$data->app_code, array('id' => 'appcode')) }} 
     {{ Form::hidden('subclasshidden','', array('id' => 'subclasshidden')) }}  
     {{ Form::hidden('br_code','', array('id' => 'br_code')) }}  
    @php
        $dclass = ($data->busn_app_status==1 || $data->busn_app_status==0)?'':'disabled-status';
    @endphp
    
    <style type="text/css">
         .assesment-heading thead th {font-size: unset !important;}
        .table thead th{ padding-top: 4px;padding-bottom: 4px; }
        .btn{padding: 0.4rem 1.3rem !important;font-size: 12px !important;}
        #assessmentModal{
                padding-top: 5%;
        }
        .assesment-heading thead tr th{
            color: black;
            background: white;
        }
        .btn-success{
            background-color: #59ae36 !important;
            border-color: #59ae36 !important;
        }
        table td, .table th{
            padding: 0.4rem 0.75rem !important;
            border-right: 1px solid #f1f1f1 !important;
        }
        .assesment-heading{border: 1px solid #f1f1f1;}
        .modal.form-sub {
            background: rgba(0,0,0,0.3);
        }
        .select3-dropdown {
            background-color: white;
            border: 1px solid #aaa;
            border-radius: 4px;
            box-sizing: border-box;
            display: block;
            position: absolute;
            left: -100000px;
            width: 100%;
            display: flex;
            flex-direction: column-reverse;
            align-items: stretch;
            z-index: 1051;
        }
    </style>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="modal-footer">
                       <!--  <button type="button" class="btn  btn-primary">Link Business Application </button> -->
                        <button type="button" class="btn  btn-primary {{$dclass}}" data-busn_tax_year="" data-busn_id="" id="jqVerifyApplication">Verify Application</button>
                        <!-- <a title="Print Order Of Payment" data-title="Print Order Of Payment" class="mx-3 btn print btn-sm align-items-center" target="_blank" href="{{ url('/business-permit/application/generatePaymentPdf?busn_id=' . (int)$data->client_id) }}">
    <i class="ti-printer text-white"></i> Print Order
</a>
 -->
                        @if($data->busn_app_status==7)
                         <button type="button" class="btn  btn-primary" id="activateApplication" value="{{$data->id}}">Activate Application</button>
                        @else
                         <button type="button" class="btn  btn-warning {{$dclass}}" value="{{$data->id}}" id="declineApplication" >Declined Application</button>
                        @endif
                    <!-- <input type="button" value="{{__('Close')}}" class="btn  btn-light" data-bs-dismiss="modal"> -->
                </div>
            </div>

            <div class="col-md-5">
                <!--- Start Status--->
                <div class="row" >
                   <div class=" border-right  space-right" style="border: 1px solid gray;margin: 0;float:left;">
                        <h4 class="text-header">Application Requirements</h4>
                        <p style="text-align:left; font-weight: bold;">Generate Barangay Clearance:<a style="color:red;">NO</a></p>
                        <div class="row">
                             <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('psicclass', __('Nature of Business'),['class'=>'form-label']) }}
                                    <span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                        {{ Form::select('psicclass',$psicclassess,'', array('class' => 'form-control select3_view $dclass','id'=>'psicclass','required'=>'required')) }}
                                   </div>
                                   <span class="validate-err" id="err_psicclass"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('requirement_id', __('Requirements'),['class'=>'form-label']) }}
                                    <span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                      {{ Form::select('requirement',array(),'', array('class' => 'form-control select3_view $dclass','id'=>'requirement','required'=>'required')) }}
                                   </div>
                                </div>
                                <span class="validate-err" id="err_end_requirement_id"></span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
                                    <div class="form-icon-user">
                                        {{ Form::input('file','documentname','',array('class'=>'form-control','id'=>'documentname'))}}  
                                    </div>
                                    <span class="validate-err" id="err_documentname"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6"></div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <button type="button" style="float: right;" class="btn btn-primary {{$dclass}}" id="uploadAttachmentbtn">Upload File</button>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="width: 70%;">Document Title</th>
                                                <th width="30%">Action</th>
                                            </tr>
                                        </thead>
                                         <thead id="DocumentDtls">
                                                            <?php echo $data->document_details?>
                                                            @if(empty($data->document_details))
                                                            <tr>
                                                                <td colspan="3"><i>No results found.</i></td>
                                                            </tr>
                                                            @endif 
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row" >
                           @if($data->busn_app_status > 1)
                            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExampleAssessment">  

                                <div  class="accordion accordion-flush">
                                    <div class="accordion-item">
                                        <h6 class="accordion-header" id="flush-headingAssessment">
                                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseAssessment" aria-expanded="false" aria-controls="flush-collapseAssessment">
                                                <h6 class="sub-title accordiantitle">
                                                    <i class="ti-menu-alt text-white fs-12"></i>
                                                    <span class="accordiantitle-icon">{{__("Payment & Assessment")}}
                                                    </span>
                                                </h6>
                                            </button>
                                        </h6>
                                        <div id="flush-collapseAssessment" class="accordion-collapse collapse show" aria-labelledby="flush-headingAssessment" data-bs-parent="#accordionFlushExampleAssessment">
                                            <div class="basicinfodiv">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6"></div>
                                                    <!-- <div class="col-lg-6 col-md-6 col-sm-6">
                                                        <button type="button" style="float: right;" class="btn  btn-primary" id="btnAssessment">View Initial Assessment</button>
                                                    </div> -->
                                                </div><br>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Description</th>
                                                                        <th>Status</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Assessment</td>
                                                                        <td><button type="button" style="float: right;" class="btn  btn-primary" id="btnAssessment">View Assessment</button></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>TOP No.</td>
                                                                        <td>{{ Form::text('transaction_no',!empty($cto_cashier) ? $cto_cashier->transaction_no : "", array('class' => 'form-control','id'=>'transaction_no','readonly' => 'readonly')) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Assessment Date</td>

                                                                        <td>{{ Form::date('assessment_date',!empty($cto_cashier) ? date("Y-m-d",strtotime($cto_cashier->assessment_date)) : "", array('class' => 'form-control','id'=>'assessment_date', 'readonly' => 'readonly')) }}</td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td>OR No.</td>
                                                                        <td>{{ Form::text('or_no',!empty($cto_cashier) ? $cto_cashier->or_no : "", array('class' => 'form-control','id'=>'or_no', 'readonly' => 'readonly')) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>OR Date</td>
                                                                        <td>{{ Form::date('cashier_or_date',!empty($cto_cashier) ? $cto_cashier->cashier_or_date : "", array('class' => 'form-control','id'=>'cashier_or_date','readonly' => 'readonly')) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>OR Amount</td>
                                                                        <td>{{ Form::text('total_amount',!empty($cto_cashier) ? number_format($cto_cashier->total_amount, 2, '.', ',') : "", array('class' => 'form-control','id'=>'total_amount','readonly' => 'readonly')) }}</td>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @endif
                            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExampleFire">  
                                <div  class="accordion accordion-flush">
                                    <div class="accordion-item">
                                        <h6 class="accordion-header" id="flush-headingFire">
                                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFire" aria-expanded="false" aria-controls="flush-collapse1">
                                                <h6 class="sub-title accordiantitle">
                                                    <i class="ti-menu-alt text-white fs-12"></i>
                                                    <span class="accordiantitle-icon">{{__("Fire Protection : Requirements")}}
                                                    </span>
                                                </h6>
                                            </button>
                                        </h6>
                                        <div id="flush-collapseFire" class="accordion-collapse collapse show" aria-labelledby="flush-headingFire" data-bs-parent="#accordionFlushExampleFire">
                                            <div class="basicinfodiv">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6"></div>
                                                    <!-- <div class="col-lg-6 col-md-6 col-sm-6">
                                                        <button type="button" style="float: right;" class="btn  btn-primary" id="btnAssessment">View Initial Assessment</button>
                                                    </div> -->
                                                </div><br>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <p style="margin-top:-10px;">
                                                            @if($fire_status != null)
                                                            Status : @if($fire_status == "Completed") <span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">{{$fire_status}}</span>@elseif($fire_status == "Incomplete")<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">{{$fire_status}}</span>@else<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">{{$fire_status}}</span>@endif
                                                            @endif
                                                        </p>
                                                        <div class="table-responsive">
                                                          
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="70%">Document Title</th>
                                                                        <th width="20%">Status</th>
                                                                        <th width="10%">Action</th>
                                                                    </tr>
                                                                    @if(isset($arrFireReqDoc))
                                                                    @foreach($arrFireReqDoc as $item)
                                                                    <tr>
                                                                        <td>{{$item['requirement_name']}}</td>
                                                                        <td>{!! $item['is_required'] !!}</td>
                                                                        <td>{!! $item['file'] !!}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                    @endif
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExamplePlanning">  
                                <div  class="accordion accordion-flush">
                                    <div class="accordion-item">
                                        <h6 class="accordion-header" id="flush-headingPlanning">
                                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsePlanning" aria-expanded="false" aria-controls="flush-collapsePlanning">
                                                <h6 class="sub-title accordiantitle">
                                                    <i class="ti-menu-alt text-white fs-12"></i>
                                                    <span class="accordiantitle-icon">{{__("Planning & Development : Requirements")}}
                                                    </span>
                                                </h6>
                                            </button>
                                        </h6>
                                        <div id="flush-collapsePlanning" class="accordion-collapse collapse show" aria-labelledby="flush-headingPlanning" data-bs-parent="#accordionFlushExamplePlanning">
                                            <div class="basicinfodiv">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6"></div>
                                                    <!-- <div class="col-lg-6 col-md-6 col-sm-6">
                                                        <button type="button" style="float: right;" class="btn  btn-primary" id="btnAssessment">View Initial Assessment</button>
                                                    </div> -->
                                                </div><br>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <p style="margin-top:-10px;">
                                                            @if($plan_status != null)
                                                            Status : @if($plan_status == "Completed") <span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">{{$plan_status}}</span>@elseif($plan_status == "Incomplete")<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">{{$plan_status}}</span>@else<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">{{$plan_status}}</span>@endif
                                                            @endif
                                                        </p>
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="70%">Document Title</th>
                                                                        <th width="20%">Status</th>
                                                                        <th width="10%">Action</th>
                                                                    </tr>
                                                                    @if(isset($arrPlanReqDoc))
                                                                    @foreach($arrPlanReqDoc as $item)
                                                                    <tr>
                                                                        <td>{{$item['requirement_name']}}</td>
                                                                        <td>{!! $item['is_required'] !!}</td>
                                                                        <td>{!! $item['file'] !!}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                    @endif
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExampleEnvironmental">  
                                <div  class="accordion accordion-flush">
                                    <div class="accordion-item">
                                        <h6 class="accordion-header" id="flush-headingEnvironmental">
                                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseEnvironmental" aria-expanded="false" aria-controls="flush-collapseEnvironmental">
                                                <h6 class="sub-title accordiantitle">
                                                    <i class="ti-menu-alt text-white fs-12"></i>
                                                    <span class="accordiantitle-icon">{{__("Environmental : Requirements")}}
                                                    </span>
                                                </h6>
                                            </button>
                                        </h6>
                                        <div id="flush-collapseEnvironmental" class="accordion-collapse collapse show" aria-labelledby="flush-headingEnvironmental" data-bs-parent="#accordionFlushExampleEnvironmental">
                                            <div class="basicinfodiv">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6"></div>
                                                    <!-- <div class="col-lg-6 col-md-6 col-sm-6">
                                                        <button type="button" style="float: right;" class="btn  btn-primary" id="btnAssessment">View Initial Assessment</button>
                                                    </div> -->
                                                </div><br>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <p style="margin-top:-10px;">
                                                            @if($env_status != null)
                                                            Status : @if($env_status == "Completed") <span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">{{$env_status}}</span>@elseif($env_status == "Incomplete")<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">{{$env_status}}</span>@else<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">{{$env_status}}</span>@endif
                                                            @endif
                                                        </p>
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="70%">Document Title</th>
                                                                        <th width="20%">Status</th>
                                                                        <th width="10%">Action</th>
                                                                    </tr>
                                                                    @if(isset($arrEnvReqDoc))
                                                                    @foreach($arrEnvReqDoc as $item)
                                                                    <tr>
                                                                        <td>{{$item['requirement_name']}}</td>
                                                                        <td>{!! $item['is_required'] !!}</td>
                                                                        <td>{!! $item['file'] !!}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                    @endif
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExampleHealthy">  
                                <div  class="accordion accordion-flush">
                                    <div class="accordion-item">
                                        <h6 class="accordion-header" id="flush-headingHealthy">
                                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseHealthy" aria-expanded="false" aria-controls="flush-collapseHealthy">
                                                <h6 class="sub-title accordiantitle">
                                                    <i class="ti-menu-alt text-white fs-12"></i>
                                                    <span class="accordiantitle-icon">{{__("Healthy & Safety : Requirements")}}
                                                    </span>
                                                </h6>
                                            </button>
                                        </h6>
                                        <div id="flush-collapseHealthy" class="accordion-collapse collapse show" aria-labelledby="flush-headingHealthy" data-bs-parent="#accordionFlushExampleHealthy">
                                            <div class="basicinfodiv">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6"></div>
                                                    <!-- <div class="col-lg-6 col-md-6 col-sm-6">
                                                        <button type="button" style="float: right;" class="btn  btn-primary" id="btnAssessment">View Initial Assessment</button>
                                                    </div> -->
                                                </div><br>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <p style="margin-top:-10px;">
                                                            @if($health_status != null)
                                                            Status : @if($health_status == "Completed") <span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">{{$health_status}}</span>@elseif($health_status == "Incomplete")<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">{{$health_status}}</span>@else<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">{{$health_status}}</span>@endif
                                                            @endif
                                                        </p>
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="70%">Document Title</th>
                                                                        <th width="20%">Status</th>
                                                                        <th width="10%">Action</th>
                                                                    </tr>
                                                                    @if(!empty($arrHealthReqDoc))
                                                                    @foreach($arrHealthReqDoc as $item)
                                                                    <tr>
                                                                        <td>{{$item['requirement_name']}}</td>
                                                                        <td>{!! $item['is_required'] !!}</td>
                                                                        <td>{!! $item['file'] !!}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                    @endif
                                                                </thead>
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

                <!--- End Status --->
            </div>    


            <!--- Start Print Details--->
            <div class="col-md-7 pdf-details">
                <div class="form-group" style="text-align: center;">
                  <!-- <button type="button" class="btn  btn-primary"> <i class="ti-printer text-white fs-12"></i> Print</button> -->
                  <iframe id="pdf-iframe" src="{{$sunmary_url}}" width="100%" height="1200px"></iframe> 

                </div>
            </div>    
            <!--- End Print Details--->
        </div> 
    </div>
</div>
<div class="modal fade form-sub" id="assessmentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
           <div class="modal-header">
                <h4 class="modal-title">Initial Assessment</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="container"></div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table assesment-heading">
                            <thead>
                                <tr>
                                    <th>Business Identification No.</th>
                                    <th><?=$data->busns_id_no?></th>
                                </tr>
                                <tr>
                                    <th>Business Name</th>
                                    <th><?=$data->busn_name?></th>
                                </tr>
                                <tr >
                                    <th>Fee Name</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <thead id="feeDetails">
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- <div class="modal-footer"> 
                   <button class="btn btn-primary"> <i class="ti-printer text-white fs-12"></i> Print</button>
                </div> -->
            </div>
        </div>
    </div>
</div>



<script src="{{ asset('js/Bplo/view_application.js') }}?rand={{ rand(000,999) }}"></script>

  