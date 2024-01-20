<style>
    .modal.form-inner .table th{
        background: #29b6c9;
        color: #fff;
    }
</style>
<div class="container-fluid my-5">
    <div class="row">
        <div class="col-md-6">
            <p><b>Name: </b>{{$data->appointment->employee->fullname}}</p>
            <p><b>Cuttoff Period: </b>{{$data->cutoff->hrcp_date_from}} - {{$data->cutoff->hrcp_date_to}}</p>
            <p><b>ACA /PERA: </b>{{currency_format($data->hrpr_earnings)}} </p>
        </div>
        <div class="col-md-6">
            <p><b>Designation: </b>{{$data->appointment->employee->designation->description}}</p>
            <p><b>Monthly Basic: </b>{{currency_format($data->hrpr_monthly_rate)}} </p>
            <p><b>Daily: </b>{{currency_format($data->hrpr_monthly_rate / config('constants.hrSettings.work_days'))}} </p>
        </div>
    </div>
    <h4 class="text-header text-center mt-1 mb-3">D E D U C T I O N S</h4>
    <table class="display dataTable table w-100 mt-5">
            <tr>
                <th>GSIS P.S.</th>
                <th>Pagibig P.S. </th>
                <th>Philhealth P.S. </th>
                <th>GSIS Conso</th>
                <th>Multipurpose</th>
                <th>GSIS EDUC Loan</th>
                <th>Cash Advance ECard</th>
            </tr>
            <tr>
                <td>{{currency_format($data->getJSONData('hrpr_deduction','gsis_contribution','hriad_deduct'))}}</td>
                <td>{{currency_format($data->getJSONData('hrpr_deduction','pag_ibig','hriad_deduct'))}}</td>
                <td>{{currency_format($data->getJSONData('hrpr_deduction','philhealth','hriad_deduct'))}}</td>
                <td>{{currency_format($data->getJSONData('hrpr_deduction','gsis_conso','hriad_deduct'))}}</td>
                <td>{{currency_format($data->getJSONData('hrpr_deduction','gsis_multipurpose','hriad_deduct'))}}</td>
                <td>{{currency_format($data->getJSONData('hrpr_deduction','gsis_educ','hriad_deduct'))}}</td>
                <td>{{currency_format($data->getJSONData('hrpr_deduction','cash_loan','hriad_deduct'))}}</td>

            </tr>
            <tr>
                <th>ECC G.S.</th>
                <th>GSIS G.S.</th>
                <th>Pagibig G.S. </th>
                <th>Pagibig G.S. </th>
                <th>CPL</th>
                <th>GSIS Emergency Loan</th>
                <th>Witholding Tax</th>
            </tr>
            <tr>
                <td>{{currency_format($data->getJSONData('hrpr_gov_share','ecc','hriad_deduct'))}}</td>
                <td>{{currency_format($data->getJSONData('hrpr_gov_share','gsis_gs','hriad_deduct'))}}</td>
                <td>{{currency_format($data->getJSONData('hrpr_gov_share','pagibig_gs','hriad_deduct'))}}</td>
                <td>{{currency_format($data->getJSONData('hrpr_gov_share','philhealth_gs','hriad_deduct'))}}</td>
                <td>{{currency_format($data->getJSONData('hrpr_deduction','computer_loan','hriad_deduct'))}}</td>
                <td>{{currency_format($data->getJSONData('hrpr_deduction','gsis_emergency','hriad_deduct'))}}</td>
                <td>{{currency_format($data->getJSONData('hrpr_deduction','tax','hriad_deduct'))}}</td>

            </tr>
            <tr>
                <th>Policy Loan</th>
                <th>Pagibig Loan</th>
                <th>Calamity</th>
                <th>GSIS Add'l Prem.</th>
                <th>COOP Loan</th>
                <th>LWOP</th>
                <th>Others</th>
            </tr>
            <tr>
                <td>{{currency_format($data->getJSONData('hrpr_deduction','policy_loan','hriad_deduct'))}}</td>
                <td>{{currency_format($data->getJSONData('hrpr_deduction','pagibig_loan','hriad_deduct'))}}</td>
                <td>{{currency_format($data->getJSONData('hrpr_deduction','pagibig_calamity','hriad_deduct'))}}</td>
                <td>{{currency_format($data->getJSONData('hrpr_deduction','gsis_add_prem','hriad_deduct'))}}</td>
                <td>{{currency_format($data->getJSONData('hrpr_deduction','coop_loan','hriad_deduct'))}}</td>
                <td>{{currency_format($data->getJSONData('hrpr_deduction','coop_lwop','hriad_deduct'))}}</td>
                <td>{{currency_format($other_deduct)}}</td>

            </tr>
    </table>
    <div class="row">
        <div class="col-md-6">
            
        </div>
        <div class="col-md-6">
            <p><b>Total Deductions: </b>{{currency_format($data->hrpr_deductions)}} </p>
            <p><b>Net Pay: </b>{{currency_format($data->hrpr_net_salary)}} </p>
        </div>
    </div>
</div>