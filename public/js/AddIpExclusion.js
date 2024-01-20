$(document).ready(function () {
    if($('#employee_id').val() != null){
        empDetails($('#employee_id').val());    
    }
    $('#employee_id').on('change', function() {
        empDetails($(this).val());       
    });
    function empDetails(emp_id)
    {
        $.ajax({
            url: DIR+'ip-security-exclusion/get_employee_details/'+ emp_id, 
            method: 'GET',
            data: {
                "_token": $("#_csrf_token").val(),
              },
            success: function (html) {
                $('#email_address').val(html.data.emp_email);
                $('#department').val(html.data.emp_dept_name);
                $('#position').val(html.data.emp_position);
            },
            error: function () {
                console.error('Failed to fetch');
            }
        });
    }
});