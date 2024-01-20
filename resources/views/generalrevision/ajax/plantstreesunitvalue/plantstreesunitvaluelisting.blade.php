<table class="table" id="">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>{{__('Class - Subclass')}}</th>
                                        <th>{{__('Plant Trees')}}</th>
                                        <th>{{__('Unit Value')}}</th>
                                        <th>{{__('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php $i=1; @endphp
                                    @foreach($landUnitValues as $row)
                                        <tr class="font-style">
                                            <td class="app_qurtr">{{ $i }}</td>
                                             <td class="app_qurtr">{{ $row->pc_class_code.'-'.$row->pc_class_description.', '.$row->ps_subclass_code.'-'.$row->ps_subclass_desc }}</td>
                                             <td class="app_qurtr">{{ $row->pt_ptrees_code.'-'.$row->pt_ptrees_description }}</td>
                                            <td class="app_qurtr">{{ Helper::money_format($row->ptuv_unit_value) }}</td> 
                                            
                                            <td class="app_qurtr"><div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{url('/rptplanttressunitvalue/store?id='.$row->id)}}" id="editPlantTreesUnitValue" title="Edit"  data-title="Manage Land Unit Value">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div></td>
                                        </tr>
                                        @php $i++; @endphp
                                    @endforeach
                                </tbody>
                            </table>