<style type="text/css">
    .rmmargin{
        margin: 0px;
        margin-left: 10px;
    }
    .perticular .table thead th, .payment-schedule .table thead th {
        padding: 8px;
    }
    .perticular .table thead td, .payment-schedule .table thead td {
        padding: 8px;
    }
</style>
<div class="col-md-12">
    <div id="accordionFlushExample{{$data['year']}}">  
        <div  class="accordion accordion-flush">
            <div class="accordion-item">
                <h6 class="accordion-header" id="flush-headingone{{$data['year']}}">
                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone{{$data['year']}}" aria-expanded="false" aria-controls="flush-heading{{$data['year']}}">
                        <h6 class="sub-title accordiantitle">
                            <i class="ti-menu-alt text-white fs-12"></i>
                            <span class="accordiantitle-icon">{{$data['year']}}</span>
                        </h6>
                    </button>
                </h6>
                <div id="flush-collapseone{{$data['year']}}" class="accordion-collapse collapse {{($data['isShowTab']==1)?'show':''}}" aria-labelledby="flush-headingone{{$data['year']}}" data-bs-parent="#accordionFlushExample{{$data['year']}}">
                    <div class="basicinfodiv">
                        <div class="row">
                            @if($data['year_type']==2)
                                <div class="currentyearDetails">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <div class="form-group rmmargin">
                                                {{ Form::label('payment_mode', __('Payment Mode'),['class'=>'form-label']) }}
                                                <div class="form-icon-user">
                                                    {{ Form::select('payment_mode',$data['arrPayMode'],$data['pm_id'], array('class' => 'form-control payment_mode','id'=>'payment_mode'.$data['year'],'year'=>$data['year'],'disabled')) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <div class="form-group rmmargin">
                                                {{ Form::label('assesment_period', __('Assessment Period'),['class'=>'form-label']) }}
                                                <div class="form-icon-user">
                                                    {{ Form::select('assesment_period',$data['arrModePartition'],'', array('class' => 'form-control assesment_period','id'=>'assesment_period'.$data['year'],'year'=>$data['year'])) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-xl-12 perticular">
                                <div class="card">
                                    <div class="card-body table-border-style">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Particulars</th>
                                                        <th>Tax Due</th>
                                                        <th>Interest</th>
                                                        <th>Surcharge</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="jqperticularDetails{{$data['year']}}"><?php echo $data['perticular_details']; ?></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12 payment-schedule" >
                                <div class="card">
                                    <h5 class="pay-schedule-title">Payment Schedule</h5>
                                    <div class="card-body table-border-style">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Mode - {{$data['payment_mode']}}</th>
                                                        <th>Payment Due Date (on or before)</th>
                                                        <th>Status</th>
                                                        <th>Amount</th>
                                                        <th>Interest/Surcharge</th>
                                                        <th>Total Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="jqPaymentSchedule{{$data['year']}}"><?php echo $data['payment_schedule']; ?></tbody>
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
