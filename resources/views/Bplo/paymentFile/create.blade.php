{{Form::open(array('url'=>'','method'=>'post'))}}
{{ Form::hidden('busn_id',$data->busn_id, array('id' => 'busn_id')) }}
{{ Form::hidden('pm_id',$data->pm_id, array('id' => 'pm_id')) }}
{{ Form::hidden('period',$data->period, array('id' => 'period')) }}

{{ Form::hidden('app_code',$data->app_code, array('id' => 'app_code')) }}
{{ Form::hidden('year',date("Y", strtotime($data->application_date)), array('id' => 'year')) }}
{{ Form::hidden('current_year',date('Y'), array('id' => 'current_year')) }}
{{ Form::hidden('page_name','OutstandingPayment', array('id' => 'page_name')) }}
{{ Form::hidden('user_email',$data->p_email_address, array('id' => 'user_email')) }}

{{ Form::hidden('year_type',2, array('id' => 'year_type')) }}
 @php
    $dclass = '';
    $disabled ='';
@endphp
    
 <style>
   
    .application-details tr td:first-child { font-weight:bold; border-right:1px solid #20B7CC; text-align:center; }
    .fee-details thead th{padding:0.6rem 0.75rem;}

 </style>

<div class="modal-body">
    <div class="row">
        <!--- Start Status--->
        <div class="col-md-4">
            <div id="accordionFlushExample">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item ">
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
                                                    <td>{{ $data->app_code }}</td>
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

        <!--------------- Nature OF Business End Here------------------>
        <div class="col-md-8">
            <div id="accordionFlushExample2">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingone2">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone2" aria-expanded="false" aria-controls="flush-heading2">
                                <h6 class="sub-title accordiantitle">
                                    <i class="ti-menu-alt text-white fs-12"></i>
                                    <span class="accordiantitle-icon">{{__("Nature Of Business")}}</span>
                                </h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone2" class="accordion-collapse collapse show" aria-labelledby="flush-headingone2" data-bs-parent="#accordionFlushExample2">
                            <div class="basicinfodiv fee-details">
                                <div class="row">
                                    <div class="col-xl-12 perticular">
                                        <div class="card">
                                            <div class="card-body table-border-style">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Code</th>
                                                                <th>Description</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($dataPSIC as $key=>$row)
                                                            @php
                                                                $desc = wordwrap($row->subclass_description, 25, "\n");
                                                            @endphp
                                                            <tr class="font-style">
                                                                <td>{{$key+1}}</td>
                                                                <td>{{$row->subclass_code}}</td>
                                                                <td><span class="showLess">{{$desc}}</span></td>
                                                            </tr>
                                                            @endforeach
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
        <!--------------- Nature OF Business End Here------------------>
    </div>
     <div class="row">
        <!--- Start Payment Details--->
        <div class="col-md-12">
            <div id="accordionFlushExample3">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item ">
                        <h6 class="accordion-header" id="flush-heading3">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-heading3">
                                <h6 class="sub-title accordiantitle">
                                    <i class="ti-menu-alt text-white fs-12"></i>
                                    <span class="accordiantitle-icon">{{__("Payment Details")}}</span>
                                </h6>
                            </button>
                        </h6>
                        <div id="flush-collapse3" class="accordion-collapse collapse show" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                            <div class="basicinfodiv fee-details">
                                <div class="row">
                                    <div class="col-xl-12 perticular">
                                        <div class="card">
                                            <div class="card-body table-border-style">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>{{__('No.')}}</th>
                                                                <th>{{__('Business Id-No.')}}</th>
                                                                <th>{{__('Business Name')}}</th>
                                                                <th>{{__('TaxPayer Name')}}</th>
                                                                <th>{{__('Barangay')}}</th>
                                                                <th>{{__('Application Type')}}</th>
                                                                <th>{{__('Payment Mode')}}</th>
                                                                <th>{{__('Period')}}</th>
                                                                <th>{{__('O.R. No.')}}</th>
                                                                <th>{{__('Amount')}}</th>
                                                                <th>{{__('Credit Amount')}}</th>
                                                                <th>{{__('Date')}}</th>
                                                                <th>{{__('Assessed By')}}</th>
                                                                <th>{{__('Cashier')}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($arrDtls as $key=>$row)
                                                            <tr class="font-style">
                                                                <td>{{$key+1}}</td>
                                                                <td>{{$row->busns_id_no}}</td>
                                                                <td>{{$row->busn_name}}</td>
                                                                <td>{{$row->ownar_name}}</td>
                                                                <td>{{$row->brgy_name}}</td>
                                                                <td>{{$row->app_code}}</td>
                                                                <td>{{$row->payment_mode}}</td>
                                                                <td>{{$row->pap_id}}</td>
                                                                <td>{{$row->or_no}}</td>
                                                                <td>{{number_format($row->total_amount,2)}}</td>
                                                                <td>{{number_format($row->tax_credit_amount,2)}}</td>
                                                                <td>{{$row->cashier_or_date}}</td>
                                                                <td>{{$row->asseseedBy}}</td>
                                                                <td>{{$row->cashier}}</td>
                                                            </tr>
                                                            @endforeach
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
        <!--- End Payment Details --->
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
</div>
{{Form::close()}}
<script type="text/javascript">
    $(document).ready(function(){
        $(".showLess").shorten({
         "showChars" : 50,
         "moreText" : "More",
         "lessText" : "Less",
        });
    })
</script>

