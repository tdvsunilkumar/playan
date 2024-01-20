{{--Form::open(array('name'=>'forms','url'=>route('billing.store'),'method'=>'post','id'=>'generateBilling'))--}}
 <style>
    .modal-xll {
        max-width: 1330px !important;
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
    .field-requirement-details-status label{margin-top: 7px;}
    #flush-collapsetwo{
/*        padding-bottom: 80px;*/
    }

 </style>

<div class="modal-body">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">

                                    {{Form::label('rp_code',__("TD No"),['class'=>'form-label'])}}
                                    <input type="hidden" name="rp_code">
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5" style="width: 41.66667%;">
                                <div class="form-group">
                                       {{Form::text('rvy_revision_code',(isset($data->rp_tax_declaration_no))?$data->rp_tax_declaration_no:'',array('class'=>'form-control rvy_revision_code','id'=>'rvy_revision_code','readonly'=>true))}}
                                  <span class="validate-err" id="err_rvy_revision_code"></span>
                                   
                                </div>
                            </div>
                            <!-- <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                       {{Form::text('brgy_no',(isset($data->barangay->brgy_code))?$data->barangay->brgy_code:'',array('class'=>'form-control brgy_no','id'=>'brgy_no','readonly'=>true))}}
                                       
                                  <span class="validate-err" id="err_brgy_no"></span>
                                   
                                </div>
                            </div> -->
                            <div class="col-lg-5 col-md-5 col-sm-5" style="width: 41.66667%;">
                                <div class="form-group">
                                       {{Form::text('rp_td_no',(isset($data->rp_class))?$data->rp_class:'',array('class'=>'form-control rp_td_no','id'=>'rp_td_no','readonly'=>true))}}
                                  <span class="validate-err" id="err_rp_td_no"></span>
                                   
                                </div>
                            </div>
                    </div>
                    
                </div>
                
                <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">

                                    {{Form::label('cb_covered_to_year',__('PIN'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <div class="form-group">
                                       {{Form::text('cb_covered_to_year',(isset($data->complete_pin))?$data->complete_pin:'',['class'=>'form-control cb_covered_to_year','id'=>'cb_covered_to_year','disabled'=>true]);}} 
                                       <span class="validate-err" id="err_cb_covered_to_year"></span>

                                   
                                </div>
                            </div>
                           
                            <!-- <div class="col-lg-2 col-md-2 col-sm-2"> -->
                                <!-- <div class="form-group">
                                    
<input type="submit" value="Go" class="btn btn-primary" >
                                   
                                </div> -->
                            <!-- </div> -->
                    </div>
                    
                </div>
                
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12">

                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">

                                    {{Form::label('rpo_code_desc',__('Owner'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <div class="form-group">
                                       {{Form::text('rpo_code_desc',(isset($data->propertyOwner->standard_name))?$data->propertyOwner->standard_name:'',['class'=>'form-control rpo_code_desc','id'=>'rpo_code_desc','readonly'=>true])}} 
                                  <span class="validate-err" id="err_rpo_code_desc"></span>
                                   
                                </div>
                            </div>

                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">

                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">

                                    {{Form::label('owner_address',__('Address'),['class'=>'form-label'])}}
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9">
                                <div class="form-group">
                                       {{Form::text('owner_address',(isset($data->propertyOwner->standard_address))?$data->propertyOwner->standard_address:'',['class'=>'form-control owner_address','id'=>'owner_address','readonly'=>true]);}} 
                   
                                  <span class="validate-err" id="err_owner_address"></span>
                                   
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1" style="text-align:end;margin-left: -10px;">
                                            <div class="" style="margin-top: 20px;">
                                       <a href="#" id="checkHistoryOfTd" data-id="{{$data->id}}" data-url="{{ url('/rpt-payments-file/viewhistory') }}"  data-bs-toggle="tooltip" title="{{__('View History')}}" class="btn btn-sm btn-info" style="margin-top: -21px;padding: 7px 7px;">History</a>
                                    </a>
                                 </div>
                            </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
{{-- Form::close() --}}
    

    <div class="row pt10" >
        <!--------------- Owners Information Start Here---------------->
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                            <h6 class="sub-title accordiantitle">{{__("Selected Details")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                        <div class="basicinfodiv">
                           <div class="row">
            <div class="col-xl-12" style="margin-top: -30px;">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive" id="computedBillingData">
                            <table class="table" id="">
                                <thead>
                                    <tr>
                                        <th rowspan="2"  style="text-align:center;border: 1px solid #fff;">{{__('No')}}</th>
                                        <th rowspan="2"  style="text-align:center;border: 1px solid #fff;">{{__('O.R. No')}}</th>
                                        <th rowspan="2"  style="text-align:center;border: 1px solid #fff;">{{__('O.R. Date')}}</th>
                                        <th rowspan="2"  style="text-align:center;border: 1px solid #fff;">{{__("Period Covered")}}</th>
                                        <th colspan="3" style="text-align:center;border: 1px solid #fff;">{{__("Basic")}}</th>
                                        <th colspan="3" style="text-align:center;border: 1px solid #fff;">{{__("SEF")}}</th>
                                        <th colspan="3" style="text-align:center;border: 1px solid #fff;">{{__("SOCIALIZE HOUSING")}}</th>
                                        <th rowspan="2"  style="text-align:center;border: 1px solid #fff;">{{__('Total Amount')}}</th>
                                        <th rowspan="2"  style="text-align:center;border: 1px solid #fff;">{{__('Cashier')}}</th>
                                    </tr>
                                    <tr>
                                        <th style="text-align:center;border: 1px solid #fff;">{{__('Tax Amount')}}</th>
                                        <th style="text-align:center;border: 1px solid #fff;">{{__('Interest')}}</th>
                                        <th style="text-align:center;border: 1px solid #fff;">{{__('Discount')}}</th>
                                        <th style="text-align:center;border: 1px solid #fff;">{{__('Tax Amount')}}</th>
                                        <th style="text-align:center;border: 1px solid #fff;">{{__('Interest')}}</th>
                                        <th style="text-align:center;border: 1px solid #fff;">{{__('Discount')}}</th>
                                        <th style="text-align:center;border: 1px solid #fff;">{{__('Tax Amount')}}</th>
                                        <th style="text-align:center;border: 1px solid #fff;">{{__('Interest')}}</th>
                                        <th style="text-align:center;border: 1px solid #fff;">{{__('Discount')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $i = 1;
                                    $basicTotalAmount = 0;
                                    $sefTotalAmount = 0;
                                    $shTotalAmount = 0;

                                    $basicPenTotalAmount = 0;
                                    $sefPenTotalAmount = 0;
                                    $shPenTotalAmount = 0;

                                    $basicDiscTotalAmount = 0;
                                    $sefDiscTotalAmount = 0;
                                    $shDiscTotalAmount = 0;

                                    $overAllTotal = 0;
                                    @endphp
                                    @foreach($details as $del)
                                    @php
                                    $startMode = 11;
                                    $endMode   = 44;
                                    if($del->sd_mode != null && !in_array($del->sd_mode,[14,44])){
                                        $indesx = array_search($del->sd_mode,array_keys(Helper::billing_quarters()));

                                        $qtrOfBilling = array_keys(Helper::billing_quarters())[$indesx+1];
                                        //dd($qtrOfBilling);
                                        $startMode = $qtrOfBilling;
                                        $endMode   = $qtrOfBilling;
                                    }
                                    //dd(Helper::billing_quarters()[$startMode]);
                                    
                                    @endphp
                                    
                                    <tr class="font-style">
                                            <td class="">{{ $i}}</td>
                                            <td class="">{{ $del->or_no}}</td>

                                            <td class="">{{ $del->cashier_or_date}}</td>

                                            <td class="">{{ $del->cbd_covered_year.' '.Helper::billing_quarters()[$startMode].' - '.Helper::billing_quarters()[$endMode]}}</td>

                                             @php $basicAmount = (isset($del->basic_amount) && $del->basic_amount != null)?$del->basic_amount:0; @endphp

                                             <td class="">{{ Helper::decimal_format($basicAmount) }}</td>

                                             @php $basicPenalty = (isset($del->basic_penalty_amount) && $del->basic_penalty_amount != null)?$del->basic_penalty_amount:0; @endphp

                                             <td class="">{{ Helper::decimal_format($basicPenalty) }}</td>

                                             @php $basicDiscount = (isset($del->basic_discount_amount) && $del->basic_discount_amount != null)?$del->basic_discount_amount:0; @endphp

                                            <td class="">{{ Helper::decimal_format($basicDiscount) }}</td>

                                            @php $sefAmount = (isset($del->sef_amount) && $del->sef_amount != null)?$del->sef_amount:0; @endphp

                                            <td class="">{{ Helper::decimal_format($sefAmount) }}</td>

                                            @php $sefPenalty = (isset($del->sef_penalty_amount) && $del->sef_penalty_amount != null)?$del->sef_penalty_amount:0; @endphp

                                            <td class="">{{ Helper::decimal_format($sefPenalty) }}</td>

                                            @php $sefDisc = (isset($del->sef_discount_amount) && $del->sef_discount_amount != null)?$del->sef_discount_amount:0; @endphp

                                            <td class="">{{ Helper::decimal_format($sefDisc) }}</td>

                                            @php 
                                            $shAmount = (isset($del->sh_amount) && $del->sh_amount != null)?$del->sh_amount:0; 
                                            @endphp
                                            <td class="">{{ Helper::decimal_format($shAmount) }}</td>
                                            @php
                                            $shPenalty = (isset($del->sh_penalty_amount) && $del->sh_penalty_amount != null)?$del->sh_penalty_amount:0;
                                            @endphp
                                            <td class="">{{ Helper::decimal_format($shPenalty) }}</td>
                                            @php
                                            $shDisc = (isset($del->sh_discount_amount) && $del->sh_discount_amount != null)?$del->sh_discount_amount:0;
                                            @endphp
                                            <td class="">{{ Helper::decimal_format($shDisc) }}</td>
                                            @php
                                            $totalDueRow = ($basicAmount+$sefAmount+$shAmount)+($basicPenalty+$sefPenalty+$shPenalty)-($basicDiscount+$sefDisc+$shDisc)
                                            @endphp


                                             <td class="">{{ Helper::decimal_format($totalDueRow) }}</td>
                                            <td class="">{{$del->cashier}}</td>
                                             <!-- <td  class=""></td> -->
                                        </tr>
                                        
                                        @php
                                        $i++;
                                    $basicTotalAmount += $del->basic_amount;
                                    $sefTotalAmount += $del->sef_amount;
                                    $shTotalAmount += $del->sh_amount;

                                    $basicPenTotalAmount += $del->basic_penalty_amount;
                                    $sefPenTotalAmount += $del->sef_penalty_amount;
                                    $shPenTotalAmount += $del->sh_penalty_amount;

                                    $basicDiscTotalAmount += $del->basic_discount_amount;
                                    $sefDiscTotalAmount += $del->sef_discount_amount;
                                    $shDiscTotalAmount += $del->sh_discount_amount;

                                    $overAllTotal += $totalDueRow;
                                    @endphp
                                        @endforeach
                                         
                                         <tr class="font-style"> 
                                            <td class="" style="text-align: end;" colspan="4"><b>Total</b></td>
                                            <td class=""><b>{{Helper::decimal_format($basicTotalAmount)}}</b></td>
                                            <td class=""><b>{{Helper::decimal_format($basicPenTotalAmount)}}</b></td>
                                            <td class=""><b>{{Helper::decimal_format($basicDiscTotalAmount)}}</b></td>
                                             <td class=""><b>{{Helper::decimal_format($sefTotalAmount)}}</b></td>
                                             <td class=""><b>{{Helper::decimal_format($sefPenTotalAmount)}}</b></td>
                                             <td class=""><b>{{Helper::decimal_format($sefDiscTotalAmount)}}</b></td>

                                             <td class=""><b>{{Helper::decimal_format($shTotalAmount)}}</b></td>
                                             <td class=""><b>{{Helper::decimal_format($shPenTotalAmount)}}</b></td>
                                             <td class=""><b>{{Helper::decimal_format($shDiscTotalAmount)}}</b></td>
                                          
                                            
                                            <td class=""><b>{{ Helper::decimal_format($overAllTotal)}}</b></td>
                                            <td class=""></td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Land Apraisal Listing End Here------------------><br />
                          
                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="col-lg-12 col-md-12 col-sm-12"  id="loadHistoryViewHere">  </div>
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample3">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading3">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Upload Payment Reference")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapse3" class="accordion-collapse collapse" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                            <div class="col-lg-5 col-md-5 col-sm-5" style="width: 42.66667%;padding-right: 0px;">
                                                <div class="form-group">
                                                    {{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('file','documentname','',array('class'=>'form-control','id'=>'documentname'))}}  
                                                        <input type="hidden" name="rp_code" id="rpCodeDocument" value="{{(isset($data->id))?$data->id:''}}">
                                                        <input type="hidden" name="action" value="upload">
                                                    </div>
                                                    <span class="validate-err" id="err_document"></span>
                                                </div>
                                             </div>
                                             <div class="col-lg-1 col-md-1 col-sm-1" style="padding-top: 26px;    width: 7.33333%;padding-left: 0px;">
                                                 <button type="button" style="float: right;padding: 8px 19px;" class="btn btn-primary" id="uploadAttachmentbtn" >Upload File</button>
                                            </div>

                                            <div class="col-lg-5 col-md-5 col-sm-5" style="width: 43.66667%;padding-right: 0px;">
                                                <div class="form-group">
                                                    {{ Form::label('document_name', __('Select Tax Declaration'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::select('rp_code_from_copy',[],'',array('class'=>'form-control','id'=>'rp_code_from_copy'))}}  
                                                         <input type="hidden" name="action" value="copy">
                                                    </div>
                                                    <span class="validate-err" id="err_copy"></span>
                                                </div>
                                             </div>
                                             <div class="col-lg-1 col-md-1 col-sm-1" style="padding-top: 26px;width: 6.33333%;padding-left: 0px; ">
                                                 <button type="button" style="float: right;padding: 8px 17px" class="btn btn-primary" id="copyFile" >Copy File</button>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Sr. No.</th>
                                                                <th>Document Title</th>
                                                                <th>Attachment</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <thead id="loadPaymentFilesHere">
                                                            
                                                            
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
<div class="modal-footer">
    <input type="button" value="Ok" class="btn btn-light" data-bs-dismiss="modal">
    <!-- <input type="button" id="" value="Bill Now" class="btn btn-primary" > -->
</div>           

<!-- <input type="hidden" name="dynamicid" value="3" id="dynamicid">
<script src="{{-- asset('js/billingform/addBillingForm.js') --}}"></script> -->
<!-- <script src="{{---- asset('js/ajax_rptProperty.js') ----}}"></script> -->