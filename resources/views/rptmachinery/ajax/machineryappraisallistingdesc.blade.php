<table class="table" id="new_added_machine_apraisal" style="width: 100%;padding: 0px;margin: 0px;">
                                <thead>
                                    <tr>
                                        <th>{{__('No.')}}</th>
                                        <th>{{__('Machine Description')}}</th>
                                        <th>{{__('Brand & model')}}</th>
                                        <th>{{__("Capacity / HP")}}</th>
                                        <th>{{__('Date Acquired')}}</th>
                                        <th>{{__('Condition When Acquired')}}</th>
                                        <th>{{__('Eco Life Estimate')}}</th>
                                        <th>{{__('Eco Life Remain')}}</th>
                                        <th>{{__('Date Installed')}}</th>
                                        <th>{{__('Date Operated')}}</th>
                                    </tr>
                                </thead>
                                
                                <tbody>

                                   @php $i =1; @endphp
                                    @foreach($machineAppraisals as $key=>$val)
                                        <tr class="font-style">
                                            <td class="app_qurtr">{{ $i }}</td>
                                             @php $desc = wordwrap($val->rpma_description, 30, "\n"); @endphp
                                            <td class="app_qurtr"><div class='showLess'>{{$desc}}</div></td>
                                            <td class="app_qurtr">{{ $val->rpma_brand_model }}</td>
                                             <td class="app_qurtr">{{ $val->rpma_capacity_hp }}</td>
                                            <td class="app_qurtr">{{ ($val->rpma_date_acquired != '')?date('d/m/Y',strtotime($val->rpma_date_acquired)):''}}</td> 
                                            <td class="app_qurtr">{{ $val->rpma_condition }}</td>
                                            <td class="app_qurtr">{{ $val->rpma_estimated_life }}</td> 
                                            <td class="">{{ $val->rpma_remaining_life }}</td>
                                            <td class="app_qurtr">{{ ($val->rpma_date_installed != '')?date('d/m/Y',strtotime($val->rpma_date_installed)):'' }}</td>
                                            <td class="app_qurtr">{{ ($val->rpma_date_operated != '')?date('d/m/Y',strtotime($val->rpma_date_operated)):'' }}</td>
                                        </tr>
                                        @php $i++; @endphp
                                    @endforeach
                                    <!-- <tr class="font-style last-option">
                                       
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
                                        
                                    </tr> -->
                                </tbody>
                            </table>
                            <script>
        $(document).ready(function () {
            $(".showLess").shorten({
            "showChars" : 20,
            "moreText"  : "More",
            "lessText"  : "Less",
        });
        });
        </script>