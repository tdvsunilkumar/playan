<div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExampleHistory">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingoneHistory">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingoneHistory">
                            <h6 class="sub-title accordiantitle">{{__("History")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapseoneHistory" class="accordion-collapse collapse show" aria-labelledby="flush-headingoneHistory" data-bs-parent="#accordionFlushExampleHistory">
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
                                        <th rowspan="2"  style="text-align:center;border: 1px solid #fff;">{{__('TD No')}}</th>
                                        <th rowspan="2"  style="text-align:center;border: 1px solid #fff;">{{__('O.R. No')}}</th>
                                        <th rowspan="2"  style="text-align:center;border: 1px solid #fff;">{{__('O.R. Date')}}</th>
                                        <th rowspan="2"  style="text-align:center;border: 1px solid #fff;">{{__("Period Covered")}}</th>
                                         <th colspan="3" style="text-align:center;border: 1px solid #fff;">{{__("Basic")}}</th>
                                        <th colspan="3" style="text-align:center;border: 1px solid #fff;">{{__("SEF")}}</th>
                                        <th colspan="3" style="text-align:center;border: 1px solid #fff;">{{__("SOCIALIZE HOUSING")}}</th>
                                        <th rowspan="2"  style="text-align:center;border: 1px solid #fff;">{{__('Total Amount')}}</th>
                                        <th rowspan="2"  style="text-align:center;border: 1px solid #fff;">{{__('Status')}}</th>
                                        
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
                                    $totalBaisc = 0;
                                    $totalSef = 0;
                                    $totalSh = 0;
                                    $totalBasDisc = 0;
                                    $totalSEFDisc = 0;
                                    $totalShDisc = 0;
                                    $totalBasPen = 0;
                                    $totalSEFPen = 0;
                                    $totalShPen = 0;
                                    $overallDue = 0;
                                    @endphp
                                   @foreach($cashierdata as $key => $value)
                                    <tr class="font-style">
                                           
                                            <td class="">{{$i}}</td>
                                            <td class="">{{$value->rp_tax_declaration_no}}</td>
                                            <td class="">{{$value->or_no}}</td>
                                            <td class="">{{date("m/d/Y",strtotime($value->cashier_or_date))}}</td>
                                            <?php
                                            if($value->fromQtr == 14){
                                                $pCov = $value->fromYear.' 1st Qtr - '.$value->toYear.' 4th Qtr';
                                            }else{
                                                 $pCov = $value->fromYear.' '.Helper::billing_quarters()[$value->fromQtr].' - '.$value->toYear.' '.Helper::billing_quarters()[$value->toQtr];
                                            }
                                             ?>
                                            <td class="">{{$pCov}}</td>
                                            <td class="">{{Helper::decimal_format($value->basicAmount)}}</td>
                                            <td class="">{{Helper::decimal_format($value->basicPenalty)}}</td>
                                            <td class="">{{Helper::decimal_format($value->basicDiscount)}}</td>
                                            <td class="">{{Helper::decimal_format($value->sefAmount)}}</td>
                                            <td class="">{{Helper::decimal_format($value->sefPenalty)}}</td>
                                            <td class="">{{Helper::decimal_format($value->sefDiscount)}}</td>
                                            <td class="">{{Helper::decimal_format($value->shAmount)}}</td>
                                            <td class="">{{Helper::decimal_format($value->shPenalty)}}</td>
                                            <td class="">{{Helper::decimal_format($value->shDiscount)}}</td>
                                            
                                            <td class="">{{Helper::decimal_format($value->totalDue)}}</td>
                                            <td class="">{{($value->pk_is_active == 1)?'Active':'In-Active'}}</td>
                                            <td class="">{{$value->cashier}}</td>

                                        </tr>
                                         @php 
                                         $i++; 
                                         $totalBaisc += $value->basicAmount;
                                         $totalSef += $value->sefAmount;
                                         $totalSh += $value->shAmount;
                                         $totalBasDisc += $value->basicDiscount;
                                         $totalSEFDisc += $value->sefDiscount;
                                         $totalShDisc += $value->shDiscount;
                                         $totalBasPen += $value->basicPenalty;
                                         $totalSEFPen += $value->sefPenalty;
                                         $totalShPen += $value->shPenalty;
                                         $overallDue += $value->totalDue;
                                         @endphp
                                        @endforeach
                                        @if(!empty($cashierdata))
                                         <tr class="font-style"> 
                                            <td class="" style="text-align: end;" colspan="5"><b>Total</b></td>
                                            <td class=""><b>{{Helper::decimal_format($totalBaisc)}}</b></td>
                                            <td class=""><b>{{Helper::decimal_format($totalBasPen)}}</b></td>
                                            <td class=""><b>{{Helper::decimal_format($totalBasDisc)}}</b></td>
                                            <td class=""><b>{{Helper::decimal_format($totalSef)}}</b></td>
                                            <td class=""><b>{{Helper::decimal_format($totalSEFPen)}}</b></td>
                                            <td class=""><b>{{Helper::decimal_format($totalSEFDisc)}}</b></td>
                                            <td class=""><b>{{Helper::decimal_format($totalSh)}}</b></td>
                                            <td class=""><b>{{Helper::decimal_format($totalShPen)}}</b></td>
                                            <td class=""><b>{{Helper::decimal_format($totalShDisc)}}</b></td>
                                            <td class=""><b></b></td>
                                            <td class=""><b>{{Helper::decimal_format($overallDue)}}</b></td>
                                            <td class=""></td>
                                        </tr>
                                        @endif
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