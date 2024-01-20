$(document).ready(function () {
    $('body').on('keypress', '.numeric-double', function (event) {
        var $this = $(this);
        if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
          ((event.which < 48 || event.which > 57) &&
            (event.which != 0 && event.which != 8))) {
          event.preventDefault();
      }
  
      var text = $(this).val();
      if ((event.which == 46) && (text.indexOf('.') == -1)) {
        setTimeout(function () {
          if ($this.val().substring($this.val().indexOf('.')).length > 3) {
            $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
          }
        }, 1);
      }
  
      if ((text.indexOf('.') != -1) &&
        (text.substring(text.indexOf('.')).length > 2) &&
        (event.which != 0 && event.which != 8) &&
        ($(this)[0].selectionStart >= text.length - 2)) {
        event.preventDefault();
        }
      });  
      $("#hra_department_id").select3({dropdownAutoWidth : false,dropdownParent: $("#hra_department_id_div")});  
      $("#hra_division_id").select3({dropdownAutoWidth : false,dropdownParent: $("#hra_division_id_div")});  
      $("#hr_emp_id").select3({dropdownAutoWidth : false,dropdownParent: $("#hr_emp_id_div")});  
      $("#hres_id").select3({dropdownAutoWidth : false,dropdownParent: $("#hres_id_div")});  
      $("#hras_id").select3({dropdownAutoWidth : false,dropdownParent: $("#hras_id_div")});  
      $("#hrpt_id").select3({dropdownAutoWidth : false,dropdownParent: $("#hrpt_id_div")});  
      $("#hrol_id").select3({dropdownAutoWidth : false,dropdownParent: $("#hrol_id_div")});  
      $("#hrsg_id").select3({dropdownAutoWidth : false,dropdownParent: $("#hrsg_id_div")});  
      $("#hrsgs_id").select3({dropdownAutoWidth : false,dropdownParent: $("#hrsgs_id_div")});  

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
                  $("#hra_designation").val("");
                  $("#hra_employee_no").val("");
                }
            })
      }); 
      $('#hra_division_id').on('change', function() {
        var div_id =$(this).val();
          $.ajax({
                url :DIR+'hr-appointment/getEmpByDiv', // json datasource
                type: "POST", 
                data: {
                        "div_id": div_id, 
                        "_token": $("#_csrf_token").val(),
                    },
                success: function(html){
                  $("#hr_emp_id").html(html);
                  $("#hra_designation").val("");
                  $("#hra_employee_no").val("");
                }
            })
      }); 
      $('#hr_emp_id').on('change', function() {
        var emp_id =$(this).val();
          $.ajax({
                url :DIR+'hr-appointment/getEmpdetById', // json datasource
                type: "POST", 
                data: {
                        "emp_id": emp_id, 
                        "_token": $("#_csrf_token").val(),
                    },
                success: function(html){
                  console.log(html.designation);
                  $("#hra_designation").val(html.designation);
                  $("#hra_employee_no").val(html.identification_no);
                }
            })
      }); 
      $('#hrsgs_id').on('change', function() {
        var step_id =$(this).val();
        var grade_id =$('#hrsg_id').val();
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
      $('#hrsg_id').on('change', function() {
        var grade_id  =$(this).val();
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

      
  });  