<table class="table" id="">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>{{__('Building Type')}}</th>
                                        <th>{{__('Building Kind')}}</th>
                                        <th>{{__('Min Value')}}</th>
                                        <th>{{__('Max Value')}}</th>
                                        <th>{{__('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php $i=1; @endphp
                                    @foreach($landUnitValues as $row)
                                        <tr class="font-style">
                                            <td class="app_qurtr">{{ $i }}</td>
                                             <td class="app_qurtr">{{ $row->bt_building_type_code.'-'.$row->bt_building_type_desc }}</td>
                                             <td class="app_qurtr">{{ $row->bk_building_kind_code.'-'.$row->bk_building_kind_desc }}</td>
                                            <td class="app_qurtr">{{ Helper::money_format($row->buv_minimum_unit_value) }}</td> <td class="app_qurtr">{{ Helper::money_format($row->buv_maximum_unit_value) }}</td>
                                            
                                            <td class="app_qurtr"><div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{url('/rptbuildingunitvalue/store?id='.$row->id)}}" id="editBuildingUnitValue" title="Edit"  data-title="Manage Building Unit Value">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div></td>
                                        </tr>
                                        @php $i++; @endphp
                                    @endforeach
                                </tbody>
                            </table>