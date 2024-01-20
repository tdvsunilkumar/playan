<table class="table" id="">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>{{__('Property Kind')}}</th>
                                        <th>{{__('Classification')}}</th>
                                        <th>{{__('Actual Use')}}</th>
                                        <th>{{__('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php $i=1; @endphp
                                    @foreach($landUnitValues as $row)
                                        <tr class="font-style">
                                            <td class="app_qurtr">{{ $i }}</td>
                                             <td class="app_qurtr">{{ $row->pk_code.'-'.$row->pk_description }}</td>
                                             <td class="app_qurtr">{{ $row->pc_class_code.'-'.$row->pc_class_description }}</td>
                                             <td class="app_qurtr">{{ $row->pau_actual_use_code.'-'.$row->pau_actual_use_desc }}</td>
                                            
                                            <td class="app_qurtr"><div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{url('/assessmentlevel/store?id='.$row->id)}}" id="editAssessementLevel" title="Edit"  data-title="Manage Building Unit Value">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div></td>
                                        </tr>
                                        @php $i++; @endphp
                                    @endforeach
                                </tbody>
                            </table>