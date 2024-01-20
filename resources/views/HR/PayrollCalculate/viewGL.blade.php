<div class="container-fluid my-5">
    <table class="table datatable">
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
            @foreach($data as $payroll)
                <tr>
                    <td>{{$payroll_no}}</td>
                    <td>{{ ($payroll->sl_account) ? $payroll->sl_account->description : '' }}</td>
                    <td>{{$payroll->employee->fullname}}</td>
                    <td>{{$payroll->employee->department->name}}</td>
                    <td>{{$payroll->employee->division->name}}</td>
                    <td>{{currency_format($payroll->amount)}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="{{ asset('js/partials/datatable.js?v='.filemtime(getcwd().'/js/partials/datatable.js').'') }}"></script>
