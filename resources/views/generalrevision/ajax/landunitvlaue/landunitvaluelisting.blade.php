<table class="table" id="">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>{{__('Classification - Actual Use')}}</th>
                                        <th>{{__('Unit Value')}}</th>
                                        <th>{{__('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php $i=1; @endphp
                                    @foreach($landUnitValues as $row)
                                        <tr class="font-style">
                                            <td class="app_qurtr">{{ $i }}</td>
                                             <td class="app_qurtr">{{ $row->pc_class_code.'-'.$row->pc_class_description.', '.$row->ps_subclass_code.'-'.$row->ps_subclass_desc.', '.$row->pau_actual_use_code.'-'.$row->pau_actual_use_desc }}</td>
                                            <td class="app_qurtr">{{ Helper::money_format($row->lav_unit_value) }}</td> 
                                            
                                            <td class="app_qurtr"><div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{url('/rptlandunitvalue/store?id='.$row->id)}}" id="editLandUnitValue" title="Edit"  data-title="Manage Land Unit Value">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div></td>
                                        </tr>
                                        @php $i++; @endphp
                                    @endforeach
                                </tbody>
                            </table>