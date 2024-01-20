@php
$pendingBasicAmount = 0;
$pendingBasicPenalty = 0;
$pendingBasicDesc    = 0;
$pendingSEFAmount = 0;
$pendingSEFPenalty = 0;
$pendingSEFDesc    = 0;
$pendingSHAmount = 0;
$pendingSHPenalty = 0;
$pendingSHDesc    = 0;
$pendingTotal     = 0;
$j = $i;
@endphp
@foreach($pendingQtrData as $data)
<tr class="font-style">
                                            <td class="app_qurtr">{{ $j }}</td>
                                            <td class="app_qurtr">{{ $data['year'] }}</td>
                                            <td class="app_qurtr">{{ (in_array($data['qtr'],array_flip(Helper::billing_quarters())))?Helper::billing_quarters()[$data['qtr']]:'Annually' }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($data['asses_value']) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($data['basicAmount']) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($data['basicPenalty']) }}</td> 
                                            <td class="app_qurtr">{{ Helper::decimal_format($data['basicDisc']) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($data['basicSefAmount']) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($data['sefPenalty']) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($data['sefDisc']) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($data['basicShAMount']) }}</td> 
                                            <td class="app_qurtr">{{ Helper::decimal_format($data['shPenalty']) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($data['shDisc']) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($data['total']) }}</td>
                                            <td class="app_qurtr">Pending</td>
                                            <td class="app_qurtr"></td>
                                        </tr>
                                        @php
$pendingBasicAmount += $data['basicAmount'];
$pendingBasicPenalty += $data['basicPenalty'];
$pendingBasicDesc    += $data['basicDisc'];
$pendingSEFAmount += $data['basicSefAmount'];
$pendingSEFPenalty += $data['sefPenalty'];
$pendingSEFDesc    += $data['sefDisc'];
$pendingSHAmount += $data['basicShAMount'];
$pendingSHPenalty += $data['shPenalty'];
$pendingSHDesc    += $data['shDisc'];
$pendingTotal += $data['total'];
$j++;
@endphp
                                        @endforeach
                                        <tr class="font-style">
                                           
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                             <td class="">Total:</td>
                                             <td class=""><input type="text" class="form-control" value="{{ Helper::decimal_format($pendingBasicAmount)}}" readonly="readonly" /></td>
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($pendingBasicPenalty)}}" /></td> 
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($pendingBasicDesc)}}" /></td>
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($pendingSEFAmount)}}" /></td>
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{Helper::decimal_format($pendingSEFPenalty)}}" /></td>
                                             <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($pendingSEFDesc)}}" /></td>
                                             <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($pendingSHAmount)}}" /></td>
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($pendingSHPenalty)}}" /></td> 
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($pendingSHDesc)}}" /></td>
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($pendingTotal)}}" /></td>
                                        </tr>