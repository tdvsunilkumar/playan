$(document).ready(function(){	
    $("#commonModal").find('.body').css({overflow: 'unset'})
	var dependentRows = 0;
	var token = $("#_csrf_token").val();	

    $("#hra_department_id").select3({dropdownAutoWidth : false,dropdownParent: $("#hra_department_id_div")});  
    $("#hra_division_id").select3({dropdownAutoWidth : false,dropdownParent: $("#hra_division_id_div")});  

    $('#hra_department_id').on('change', function() {
        var dept_id =$(this).val();
          $.ajax({
                url :DIR+'hr-appointment/getDivByDept', // json datasource
                type: "POST", 
                data: {
                        "dept_id": dept_id, 
                        "_token": $("#_csrf_token").val(),
                    },
                success: function(html){
                  $("#hra_division_id").html(html);
                  $("#hr_emp_id").html('<option>Please Select</option>');
                }
            })
      }); 

    employeeAjax('hr_employeesid','hr_employeesid_contain','hr-appointment/getEmployees');

	$('.modal-body').on('click', '.add-emp', function (e) {
        var default_schedule = $('#hrds_id_sched').val();
        dependentRows = dependentRows + 1;
		var html = $('#addEmployee').html();
		html = html.replace(/changeid/g, dependentRows+'a');
		// html = html.replace(/changeSched/g, default_schedule);
		$('#employees-tbl').find('tbody').append(html);
        $("#hrds_id_"+dependentRows+'a').val(default_schedule);
        $("#hrds_id_"+dependentRows+'a').select3();
        employeeAjax('hr_employeesid_'+dependentRows+'a','contain-select-employee-'+dependentRows+'a','hr-appointment/getEmployees');
    });
	$('.modal-body').on('change', '#hrds_id_sched', function (e) {
        var default_schedule = $(this).val();
        var text = $(this).find('option:selected').text();
        $(".select_sched").val(default_schedule);
        $(".select_sched").next('.select3').find('.select3-selection__rendered').text(text);

        $.ajax({
            type: "POST",
            url: DIR+'getEmployeeSched',
            data: {
                'sched_id': default_schedule,
                _token: token
            },
            success: function(response){
                $('#employees-tbl').find('tbody').find('tr.remove').remove();
                $.each(JSON.parse(response), function(id,data){
                    var html = $('#addEmployee').html();
                    html = html.replace(/changeid/g, data.id);
                    $('#employees-tbl').find('tbody').append(html);
                    $("#hr_employeesid_"+data.id).append('<option selected="selected" value="'+data.hr_employeesid+'">'+data.employee_name+'</option>');

                    employeeAjax('hr_employeesid_'+data.id,'contain-select-employee-'+data.id);
                    $("#hrds_id_"+data.id).select3();

                    $('#sched-'+data.id+'-contain').addClass('remove');
                    $("#hrds_id_"+data.id).val(default_schedule);
                    $("#start_date_"+data.id).val(data.hrds_date);
                    // $("#hr_employeesid_"+data.id).val(data.hr_employeesid);

                });
            }
        });
    });

    $('#hra_division_id').change(function(){
        division = $(this).val()
        $('form .select_emp').each(function(){
            contain = $(this).closest('.select-contain').attr('id')
            id =$(this).attr('id')
            select3Ajax(id, contain, 'hr-appointment/getEmployees/'+division)

        })
    })
});

function employeeAjax(id, contain) {
    division = $('#hra_division_id').val()
    if (division) {
        select3Ajax(id, contain, 'hr-appointment/getEmployees/'+division)
    } else {
        select3Ajax(id, contain, 'hr-appointment/getEmployees')
    }
}