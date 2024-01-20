
$(document).ready(function () {
	var requireRows = 0;
	// dual citizenship
    $('#citizenship-group').on('change', 'input[name="hr_emp_citizenship"]', function(){
        if ($('input#hr_emp_citizenship_dual').is(':checked')) {
            $('#if-dual-group :input').prop('disabled',false)
        } else {
            $('#if-dual-group :input').prop('disabled',true)
        }
	});

    // permament is same to current
    $('#collapsePermanent').on('change', 'input[name="hr_emp_is_same_permanent"]', function(){
        if ($('input#hr_emp_is_same_permanent').is(':checked')) {
	        ifPermamentFunc()
        } else {
            $('#collapsePermanent :input').not('#hr_emp_is_same_permanent').val()
            $('#collapsePermanent :input').not('.disabled').prop('disabled',false)
        }
	});
    $('#collapseAdditional').on('change', 'input, select', function(){
        if ($('input#hr_emp_is_same_permanent').is(':checked')) {
            ifPermamentFunc()
        }
	});

    // select Department
    $('#acctg_department_id').change(function(){
        select3Ajax('acctg_department_division_id','contain_acctg_department_division_id','human-resource/employees/select-division/'+$(this).val())
    })
    // is restricted
    $('#is_dept_restricted').change(function(){
        $('#departmental_access').not('.disabled').prop('disabled',true)
        if($(this).val() == 0)
        {
            $('#departmental_access').not('.disabled').prop('disabled',false)
        }
    })

    // appoint compute salary
    $('.select_salary').on('change', function() {
        var grade_id =$('#hrsg_id').val();
        var step_id =$('#hrsgs_id').val();
          $.ajax({
                url :DIR+'hr-appointment/getSalaryDet', // json datasource
                type: "POST", 
                data: {
                        "step_id": step_id, 
                        "grade_id": grade_id, 
                        "_token": $("#_csrf_token").val(),
                    },
                success: function(val){
                  monthly_rate = parseFloat(val).toLocaleString(undefined, {minimumFractionDigits: 2,  maximumFractionDigits: 2})
                  annual_rate = parseFloat(val*12).toLocaleString(undefined, {minimumFractionDigits: 2,  maximumFractionDigits: 2})
                  $("#hra_monthly_rate").val(monthly_rate);
                  $("#hra_annual_rate").val(annual_rate);
                }
            })
      }); 

    $('form').on('click', '.add-row', function (e) {
		requireRows = requireRows + 1;
        group = $(this).data('add');

		var html = $('#'+group+'-row').html();
		html = html.replace(/changeid/g, requireRows+'n');
		
		$('#'+group+'-tbl').find('tbody').append(html);
        callToggle();
	});
    $('form').on('click', '.remove-row', function (e) {
        button = $(this);
		button.closest('.new-row').remove();
        if(button.data('id'))
        {
            $.ajax({
                url :DIR+'human-resource/employees/remove-row', // json datasource
                type: "POST", 
                data: {
                        "id": button.data('id'), 
                        "type": button.data('type'), 
                        "_token": $("#_csrf_token").val(),
                    },
                success: function(val){
                    button.closest('.old-row').remove();
                }
            })
        }
	});
});  

function ifPermamentFunc(){
    $('#collapsePermanent input,#collapsePermanent select').each(function(){
        var from = $(this).data('from');
        var data = $('#'+from);
        var value = data.val();
        if(data.is('select')){
            text = data.find('option:last').text()
            $(this).append('<option checked value="'+value+'">'+text+'</option>');
            brgyWrite(value,$('#collapsePermanent'))
            console.log(text);
            console.log(data.val());
        }
        $(this).val(value);

        $('#collapsePermanent :input').not('#hr_emp_is_same_permanent').prop('disabled',true)
    });	
}