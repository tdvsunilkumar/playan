$(document).ready(function () {
    $('#employee_id').on('change', function () {
        var selectedEmp = $(this).val();

        // Use AJAX to fetch menu_sub_id options based on the selected menu_module_id.
        $.ajax({
            url: DIR+'get_emp_dept', // Replace with your server-side endpoint
            method: 'GET',
            data: {
                "_token": $("#_csrf_token").val(),
                "employee_id" : selectedEmp,
              },
            success: function (data) {
                $('#department_name').val(data);
            }
        });
    });
});