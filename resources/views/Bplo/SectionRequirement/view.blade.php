<div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Requirements')}}</th>
                                <th>{{__('Remarks')}}</th>
                                <th>{{__('Status')}}</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                                @if(!$Requirement)
                                    <tr class="font-style">
                                        <td colspan="3" style="text-align:center;">No Requirements</td>
                                     </tr>
                                @else
                                    @foreach ($Requirement as $val)
                                      <tr class="font-style">
                                        <td>{{ $val['requirement_name'] }}</td>
                                        <td>{{ $val['remark'] }}</td>
                                        <td>
                                            @if($val['is_active']==1)
                                                <span class="btn btn-success">Active</span>
                                            @else
                                                 <span class="btn btn-warning">InActive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

