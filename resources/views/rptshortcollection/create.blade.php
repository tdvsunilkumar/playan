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
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                       {{Form::text('rvy_revision_code',(isset($data->revisionYearDetails->rvy_revision_year))?$data->revisionYearDetails->rvy_revision_year:'',array('class'=>'form-control rvy_revision_code','id'=>'rvy_revision_code','readonly'=>true))}}
                                  <span class="validate-err" id="err_rvy_revision_code"></span>
                                   
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                       {{Form::text('brgy_no',(isset($data->barangay->brgy_code))?$data->barangay->brgy_code:'',array('class'=>'form-control brgy_no','id'=>'brgy_no','readonly'=>true))}}
                                       
                                  <span class="validate-err" id="err_brgy_no"></span>
                                   
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                       {{Form::text('rp_td_no',(isset($data->rp_td_no))?$data->rp_td_no:'',array('class'=>'form-control rp_td_no','id'=>'rp_td_no','readonly'=>true))}}
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
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                       {{Form::text('cb_covered_to_year',(isset($data->complete_pin))?$data->complete_pin:'',['class'=>'form-control cb_covered_to_year','id'=>'cb_covered_to_year','disabled'=>true]);}} 
                                       <span class="validate-err" id="err_cb_covered_to_year"></span>

                                   
                                </div>
                            </div>
                           
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <!-- <div class="form-group">
                                    
<input type="submit" value="Go" class="btn btn-primary" >
                                   
                                </div> -->
                            </div>
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
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <div class="form-group">
                                       {{Form::text('owner_address',(isset($data->propertyOwner->standard_address))?$data->propertyOwner->standard_address:'',['class'=>'form-control owner_address','id'=>'owner_address','readonly'=>true]);}} 
                   
                                  <span class="validate-err" id="err_owner_address"></span>
                                   
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
                        <!-- <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                            <h6 class="sub-title accordiantitle">{{----__("Owner's Information")----}}</h6>
                        </button> -->
                    </h6>
                    <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                        <div class="basicinfodiv">
                           <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive" id="computedBillingData">
                            <table class="table" id="">
                                <thead>
                                    <tr>
                                        
                                        <th>{{__('Reference O.R. No')}}</th>
                                        <th>{{__("Period Covered")}}</th>
                                        <th>{{__('Basic Tax (Collected)')}}</th>
                                        <th>{{__('SEF Tax (Collected)')}}</th>
                                        <th>{{__('SH Tax (Collected)')}}</th>
                                        <th>{{__('Basic Tax (Actual)')}}</th>
                                        <th>{{__('SEF Tax (Actual)')}}</th>
                                        <th>{{__('SH Tax (Actual)')}}</th>
                                        <th>{{__('Basic Tax (Short)')}}</th>
                                        <th>{{__('SEF Tax (Short)')}}</th>
                                        <th>{{__('SH Tax (Short)')}}</th>
                                        <th>{{__('Paid')}}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $basicShortTotal  = 0;
                                    $sefShortTotal    = 0;
                                    $shShortTotal     = 0;
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
                                           
                                            <td class="">{{ $del->or_no}}</td>

                                            <td class="">{{ $del->cbd_covered_year.' '.Helper::billing_quarters()[$startMode].' - '.Helper::billing_quarters()[$endMode]}}</td>

                                             <td class="">{{ Helper::money_format($del->basicCollectedTax) }}</td>

                                             <td class="">{{ Helper::money_format($del->sefCollectedTax) }}</td>

                                            <td class="">{{ Helper::money_format($del->shCollectedTax) }}</td>

                                            <td class="">{{ Helper::money_format($del->basicActualTax) }}</td>

                                            <td class="">{{ Helper::money_format($del->sefActualTax) }}</td>

                                            <td class="">{{ Helper::money_format($del->shActualTax) }}</td>

                                             <td class="">{{ Helper::money_format($del->basicActualTax-$del->basicCollectedTax) }}</td>

                                             <td class="">{{ Helper::money_format($del->sefActualTax-$del->sefCollectedTax) }}</td>

                                             <td class="">{{ Helper::money_format($del->shActualTax-$del->shCollectedTax) }}</td>
                                             
                                             <td class="">{{ ($del->is_short_collection_paid == 1)?'Yes':'No' }}</td>


                                             <!-- <td  class=""></td> -->
                                            
                                        </tr>
                                        
                                        @php
                                    $basicShortTotal += ($del->basicActualTax-$del->basicCollectedTax);

                                    $sefShortTotal += ($del->sefActualTax-$del->sefCollectedTax);

                                    $shShortTotal += ($del->shActualTax-$del->shCollectedTax);
                                    @endphp
                                        @endforeach
                                         <tr class="font-style">
                                           
                                             <td class=""></td>
                                            <td class=""></td> 
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                             <td class=""></td>
                                             <td class=""></td>
                                            <td class=""></td> 
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                        </tr>
                                         <tr class="font-style">
                                           
                                             <td class=""></td>
                                            <td class=""></td> 
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                             <td class=""></td>
                                             <td class=""></td>
                                            <td class=""></td> 
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                        </tr>
                                              <tr class="font-style">
                                           
                                             <td class=""></td>
                                            <td class=""></td> 
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                             <td class=""></td>
                                             <td class=""></td>
                                            <td class=""></td> 
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                        </tr>
                                         <tr class="font-style"> 

                                            <td class="" style="text-align: end;" colspan="4"><b>Total</b></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                             <td class=""></td>
                                             <td class=""><b>{{Helper::money_format($basicShortTotal)}}</b></td>
                                             <td class=""><b>{{Helper::money_format($sefShortTotal)}}</b></td>
                                          
                                            
                                            <td class=""><b>{{ Helper::money_format($shShortTotal)}}</b></td>
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
        <!--------------- Owners Information Start Here---------------->

        
        <!--------------- Business Information End Here------------------>
    </div>

   

</div>
<div class="modal-footer">
    <input type="button" value="Ok" class="btn btn-light" data-bs-dismiss="modal">
    <!-- <input type="button" id="" value="Bill Now" class="btn btn-primary" > -->
</div>           

<!-- <input type="hidden" name="dynamicid" value="3" id="dynamicid">
<script src="{{-- asset('js/billingform/addBillingForm.js') --}}"></script> -->
<!-- <script src="{{---- asset('js/ajax_rptProperty.js') ----}}"></script> -->