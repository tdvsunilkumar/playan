{{Form::open(array('url'=>'bploapplication','method'=>'post'))}}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('retire_id',$data->id, array('id' => 'retire_id')) }}
{{ Form::hidden('busn_id',$data->busn_id, array('id' => 'busn_id')) }}
{{ Form::hidden('app_code',3, array('id' => 'app_code')) }}
{{ Form::hidden('year',date("Y", strtotime($data->busn_created_date)), array('id' => 'year')) }}
{{ Form::hidden('current_year',date('Y'), array('id' => 'current_year')) }}
{{ Form::hidden('is_deleteFee',1, array('id' => 'is_deleteFee')) }}

 @php
    use App\Models\CommonModelmaster;
    $commonmodel = new CommonModelmaster();
    $dclass = ($data->retire_is_final_assessment==1)?'disabled-status':'';
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
        padding-top: 8px;
        height: 60px;
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
                                                    <td>{{__('Application Date')}}</td>
                                                    <td>{{ date("M d, Y H:i a", strtotime($data->busn_created_date))}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Established')}}</td>
                                                    <td>{{ date("M d, Y", strtotime($data->retire_date_start))}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Actual Closure')}}</td>
                                                    <td>{{ date("M d, Y", strtotime($data->retire_date_closed))}}</td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Mode of Payment')}}</td>
                                                    <td>Annual</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Application Type')}}</td>
                                                    <td>Retire</td>
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
                                    <span class="accordiantitle-icon">{{__("Line of Business")}}</span>
                                </h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone2" class="accordion-collapse collapse show" aria-labelledby="flush-headingone2" data-bs-parent="#accordionFlushExample2">
                            <div class="basicinfodiv">
                                <div class="row">
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">

                                            <div class="modal-footer">
                                                <button type="button" class="btn  btn-green saveData {{$dclass}}" value="1"><i class="ti-reload text-white"></i>Re-Assess</button>
                                                <button type="button" class="btn  btn-danger saveData {{$dclass}}" value="2"><i class="ti-eye text-white"></i>Tax-Orders of Payment</button>
                                                <button type="button" class="btn  btn-green saveData {{$dclass}}" value="3"><i class="ti-new-window text-white"></i>Final Assessment</button>
                                            </div>
                                        </div>
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
               <!-- <a class="btn btn-primary" target="_blank" href="{{url('/treasurer/assessment/generatePaymentPdf?id='.$data->busn_id.'&year='.date('Y').'&app_code=3&retire_id='.$data->id)}}"> <i class="ti-printer text-white fs-12"></i> Print</a> -->

                <a class="btn btn-primary" target="_blank" href="{{url('/treasurer/assessment/generatePaymentPdf?id='.$commonmodel->encryptData((int)$data->busn_id).'&year='.$commonmodel->encryptData(date('Y')).'&app_code='.$commonmodel->encryptData(3).'&retire_id='.$data->id)}}" id="jqPrintPayment"> <i class="ti-printer text-white fs-12"></i> Print</a>

            </div>
           <div class="container">
                <div class="modal-body" id="feeDetails">
                </div>
                
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/Bplo/add_TreasurerAssessment.js') }}"></script>



