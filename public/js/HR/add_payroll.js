$(document).ready(function () {
    $('#process_btn').click(function (e) {
        $(this).prop('disabled',true);
        e.preventDefault();
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "Once submit, This form can't be updated",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
            if(result.isConfirmed)
            {
                $('#btn_send').val(1);
                myform = $(this).closest('form')
                myform.unbind('submit');
                myform.trigger('submit');
            }
            $(this).prop('disabled',false);
        });
    })
    $('.modal-body').on('change', '.employee-filter', function (e) {
        var designation = $('#hra_division_id').val();
        var type = $('#hrpr_appointment_type').val();
        var cutoff_id = $('#hrcp_id').val();
        if (designation != null && type != null) {
            $.ajax({
                url :'hr-payroll-calculate/selectEmployees/'+designation+'/'+type,
                type: "POST", 
                data: {
                        "_token": $("#_csrf_token").val(),
                        "payroll_no": $("#hrpr_payroll_no").val(),
                        "cutoff_id": cutoff_id, 
                        "id": $("#id").val(),
                    },
                success: function(response){
                    $('#employee_list').find('tbody').empty();
                    res = JSON.parse(response)
                    console.log(res.data);
                    $.each(res.data, function(formID,formData){
                        $('#employee_list').find('tbody').append('<tr data-emp_id="'+formData.id+'"><td>'+formData.text+'</td></tr>');
                    });
                }
            }); 
        }
    });
    $('#employee_list').on('click', 'tr', function (e) {
        var emp_id = $(this).data('emp_id');
        getPayroll(emp_id)

    });
});

function removeComma(num) {
    return parseFloat(num.replaceAll(',', ''));
}

function computeTotalSalary(totalAUT,totalOt,salary) {
    total = parseFloat(removeComma(salary)) + totalOt - parseFloat(removeComma(totalAUT));
    $('#hrpr_total_salary').val(total.toLocaleString(2));
    return total;
}

function computeAdditonalEarnings(income) {
                console.log(income)
                $('#hrpr_earnings').val(income.toLocaleString(2));
}

function computeTotalDeductions(total) {
                console.log(total)
                $('#hrpr_deductions').val(total.toLocaleString(2));
}

function computeNetSalary(salary, income, deduction) {
    total = salary + income - deduction;
    $('#hrpr_net_salary').val(total.toLocaleString(2));
    
}

function getPayroll(emp_id) {
    var cutoff_id = $('#hrcp_id').val();
        $.ajax({
        url :DIR+'hr-payroll-calculate/getPayroll',
        type: "POST", 
        data: {
                "emp_id": emp_id, 
                "cutoff_id": cutoff_id, 
                "payroll_no": $("#hrpr_payroll_no").val(),
                "id": $("#id").val(),
                "_token": $("#_csrf_token").val(),
            },
        success: function(response){
            $('#deduction-sec').empty();
            $('#income-sec').empty();
            $('#ot-sec').empty();
            var res = JSON.parse(response); 
            console.log(res);
            $.each(res, function(formID,formData){
                $('#'+formID).val(formData);
            });

            // Deduction
            $.each(res.deduction, function(formID,formData){
                var html = $('#addField').html();
                html = html.replace(/changeType/g, 'deduction');
                html = html.replace(/changeName/g, formData.hriad_description);
                html = html.replace(/changeID/g, formData.id);
                html = html.replace(/changeValue/g, parseFloat(formData.hriad_deduct).toLocaleString(undefined, {minimumFractionDigits: 2,  maximumFractionDigits: 2}));
                console.log()
                $('#deduction-sec').append(html);
            });

            // OTS
            $.each(res.ot, function(formID,formData){
                var html = $('#addOT').html();
                html = html.replace(/changeType/g, formID);
                html = html.replace(/changeName/g, formData.name);
                html = html.replace(/changeID/g, formID);
                html = html.replace(/changeHours/g, formData.hours);
                html = html.replace(/changeEarn/g, parseFloat(formData.earn).toLocaleString(undefined, {minimumFractionDigits: 2,  maximumFractionDigits: 2}));
                
                $('#ot-sec').append(html);
            });

            // Income
            $.each(res.income, function(formID,formData){
                var html = $('#addField').html();
                html = html.replace(/changeType/g, 'income');
                html = html.replace(/changeName/g, formData.hriad_description);
                html = html.replace(/changeID/g, formData.id);
                html = html.replace(/changeValue/g, parseFloat(formData.hriad_deduct).toLocaleString(undefined, {minimumFractionDigits: 2,  maximumFractionDigits: 2}));
                
                $('#income-sec').append(html);
            });

            totalOt = 0;
            $('#ot-sec .compute-ot').each(function(i) {
                var val = $(this).val();
                if (val) {
                    totalOt += removeComma(val);
                }
            });

            totalIncome = 0;
            $('#income-sec .compute-income').each(function(i) {
                var val = $(this).val();
                if (val) {
                    totalIncome += removeComma(val);
                }
            });
            totalDeduction = 0;
            $('#deduction-sec .compute-deduction').each(function(i) {
                var val = $(this).val();
                if (val) {
                    totalDeduction += removeComma(val);
                }
            });
            totalAUT = $('#hrpr_aut_compute').val();
            salary = $('#hrpr_monthly_rate').val();
            // console.log(parseFloat(removeComma(salary)));
            totalSalary = computeTotalSalary(totalAUT,totalOt,salary)
            computeAdditonalEarnings(totalIncome)
            computeTotalDeductions(totalDeduction)
            computeNetSalary(totalSalary, totalIncome, totalDeduction);
        }
    }); 
}