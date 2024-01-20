<div class="container-fluid my-5">
    <table class="table dataTables">
        <thead>
            <tr>
                <th>Payroll No</th>
                <th>SL</th>
                <th>Employee Name</th>
                <th>Department</th>
                <th>Division</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payrolls as $payroll)
                @foreach($payroll->payrolls as $pay)
                    <tr>
                        <td>{{$payroll->trans_no}}</td>
                        <td>{{ ($payroll->sl_account) ? $payroll->sl_account->description : '' }}</td>
                        <td>{{$pay->employee->fullname}}</td>
                        <td>{{$pay->employee->department->name}}</td>
                        <td>{{$pay->employee->division->name}}</td>
                        <td>{{$pay->breakdown_amount($pay->employee->id, $payroll->gl_account_id,$payroll->sl_account_id)}}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>

<script src="{{ asset('js/partials/datatable.js?v='.filemtime(getcwd().'/js/partials/datatable.js').'') }}"></script>
