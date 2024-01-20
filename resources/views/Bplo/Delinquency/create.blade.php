{{Form::open(array('url'=>'','method'=>'post'))}}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('busn_id',$data->busn_id, array('id' => 'busn_id')) }}
{{ Form::hidden('app_code',$data->app_code, array('id' => 'app_code')) }}
{{ Form::hidden('year',date("Y", strtotime($data->application_date)), array('id' => 'year')) }}
{{ Form::hidden('current_year',date('Y'), array('id' => 'current_year')) }}
{{ Form::hidden('page_name','delinquency', array('id' => 'page_name')) }}
{{ Form::hidden('user_email',$data->p_email_address, array('id' => 'user_email')) }}
 @php
    $dclass = '';
    $disabled ='';
@endphp
    
 <style>
    .select3-container{
        z-index: unset !important;
    }
    .modal-footer{ border-top:unset!important; }
    .fee-details th, .payment-schedule th{
        text-align: right; !important;
    }
    .fee-details th:nth-child(1), .payment-schedule th:nth-child(1), .payment-schedule th:nth-child(2), .payment-schedule th:nth-child(3){
        text-align: left; !important;
    }

    .fee-details td:nth-child(5), .fee-details td:nth-child(7), .fee-details td:nth-child(6) {
        background: #80808052;
        text-align: right;
    }
    .fee-details td:nth-child(2), .fee-details td:nth-child(3), .fee-details td:nth-child(4), .payment-schedule td:nth-child(5) {
        background: #20b7cc42;
        text-align: right;
    }
    .payment-schedule td:nth-child(3), .payment-schedule td:nth-child(2) {
        text-align: left !important;
    }
    .fee-details tr:last-child{
        background: #80808052;
    }
    .sky-blue{
        background: #20B7CC !important;
        color:#fff;
        font-weight: bold;
    }
    .red{
        background: red !important;
        color:#fff;
        font-weight: bold;
        text-align: right !important;
    }
    .application-details tr td:first-child { font-weight:bold; border-right:1px solid #20B7CC; text-align:center; }
    .instruction-dtls{
        background: linear-gradient(141.55deg, rgba(81, 69, 157, 0) 3.46%, #4ebbd3 99.86%), #1f3996c4 !important;
        color: white;
        padding-top: 10px;
    }
    .pay-schedule-title{ padding-left: 10px; padding-top: 10px; color: #00000078; }
    .modal-footer .btn i{font-size: 14px;padding-right: 5px;}

 </style>

<div class="modal-body">
    <div class="row">
        <!--- Start Status--->
        <div class="col-md-3">
            <div id="accordionFlushExample">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingone">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle">
                                    <i class="ti-menu-alt text-white fs-12"></i>
                                    <span class="accordiantitle-icon">{{__("Application Details")}}</span>
                                </h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                            <div class="basicinfodiv">
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table application-details">
                                            <thead>
                                                <tr>
                                                    <td>{{__('Business ID No.')}}</td>
                                                    <td>{{$data->busns_id_no}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Business Name')}}</td>
                                                    <td>{{$data->busn_name}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Owners Name')}}</td>
                                                    <td>{{$data->ownar_name}}</td>
                                                </tr>
                                             
                                                <tr>
                                                    <td>{{__('Established')}}</td>
                                                    <td>{{ date("M d, Y", strtotime($data->application_date))}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Mode of Payment')}}</td>
                                                    <td>{{ $data->pm_desc}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Application Type')}}</td>
                                                    <td>{{ config('constants.arrBusinessApplicationType')[(int)$data->app_code] }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Last Payment Date')}}</td>
                                                    <td>{{ $data->last_paid_date }}</td>
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
        <!--- End Status --->

        <!--------------- Assess Details End Here------------------>
        <div class="col-md-9">
            <div id="accordionFlushExample2">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingone2">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone2" aria-expanded="false" aria-controls="flush-heading2">
                                <h6 class="sub-title accordiantitle">
                                    <i class="ti-menu-alt text-white fs-12"></i>
                                    <span class="accordiantitle-icon">{{__("Tax Details")}}</span>
                                </h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone2" class="accordion-collapse collapse show" aria-labelledby="flush-headingone2" data-bs-parent="#accordionFlushExample2">
                            <div class="basicinfodiv">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    {{ Form::label('year_type', __('Type'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::select('year_type',array("1"=>"Current Year","2"=>"Previous Year"),2, array('class' => 'form-control select3 ','id'=>'year_type','disabled')) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">

                                            <div class="modal-footer">
                                                <button type="button" class="btn  btn-green saveData {{$dclass}}" value="1"><i class="ti-reload text-white"></i>Re-Calculate</button>
                                                <!-- <button type="button" class="btn  btn-danger saveData {{$dclass}}" value="2"><i class="ti-eye text-white"></i>Tax-Orders of Payment</button> -->
                                                <button type="button" class="btn  btn-primary sendEmailDtls {{$dclass}}"><i class="ti-email text-white"></i>Send</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row instruction-dtls currentyearDetails">
                                    <div class="col-md-12">
                                        <p><b>Note -</b> This SECTION display all the delinquent details of the taxpayers business tax.</p>
                                    </div>
                                </div>
                                <div class="fee-details" id="assesmentDetails"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           
        </div>
        <!--------------- Assess Details End Here------------------>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
</div>
{{Form::close()}}

<div class="modal fade" id="assessmentFeeModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
           <div class="modal-header">
                <h4 class="modal-title">Initial Assessment</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-footer"> 
               <a class="btn btn-primary" target="_blank" href="{{url('/treasurer/assessment/generatePaymentPdf?id='.$data->busn_id.'&year='.date('Y').'&app_code=3&retire_id='.$data->id)}}"> <i class="ti-printer text-white fs-12"></i> Print</a>
            </div>
           <div class="container">
                <div class="modal-body" id="feeDetails">
                </div>
                
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/Bplo/add_Delinquency.js') }}"></script>
<script src="{{ asset('js/Bplo/add_TreasurerAssessment.js') }}"></script>



