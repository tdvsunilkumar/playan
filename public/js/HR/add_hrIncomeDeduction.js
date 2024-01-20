
var finalVal = [];
$(document).ready(function () {
    console.log($("#id").val());
    // var selectedVal= [];
    if($("#id").val() != ''){
        console.log(selValues);
        datatableSelectedEmpfunction(selValues);
    }
	$("#btn_search_emp").click(function(){
 		datatablefunction();
 	});
    $("#btn_emp_clear").click(function(){
        $("#q_emp").val("");
        datatablefunction();
    });
    $("#saveSelEmp").click(function(){
        listEmployees()
        
        //concat the two array
        // console.log(finalArray);
        // datatableSelectedEmpfunction(finalArray);
        $("#orderofpaymentModal").modal('hide');
    });
    $("#hridt_id").change(function(){
        listEmployees()

        var app_type =  $('#hridt_type').val();
        console.log(app_type == 1);
        
    });

    $("#hriad_amount").change(function(){
        listEmployees()
       
    });
    $("#hrlc_id").change(function(){
        listEmployees()
       
    });
    $("#hridt_id").select3({dropdownAutoWidth : false,dropdownParent: $("#hridt_id_div")});  
    $("#hrlc_id").select3({dropdownAutoWidth : false,dropdownParent: $("#hrlc_id_div")});  
    $("#btnAddEmployee").click(function(){
         finalVal = [];
        datatablefunction();
        $("#orderofpaymentModal").modal('show');
    });	
    $(".closeOrderModal").click(function(){
            $("#orderofpaymentModal").modal('hide');
    });


     // Select All checkbox
     $('#select-all').change(function() {
        if (this.checked) {
            $('.row-checkbox').prop('checked', true);
            // selectedVal = [];
            // updateSelectedValues();
        } else {
            $('.row-checkbox').prop('checked', false);
            // selectedVal = []; // Clear the array
            // console.log(selectedVal);
        }
         updateSelectedValues();
    });

    // $('body').on('click', '.row-checkbox', function (event) {
    //     alert('ss');
    // });

    // Individual checkboxes
    $('body').on('click', '.row-checkbox', function (event) {
        console.log('indd');
        if (this.checked) {
            finalVal.push($(this).val()); // Use the 'value' attribute of the checkbox
        } else {
            var valueIndex = finalVal.indexOf($(this).val());
            if (valueIndex !== -1) {
                finalVal.splice(valueIndex, 1);
            }
        }
        //  updateSelectedValues();
    });

    function updateSelectedValues() {
        // var preSelVal = document.querySelectorAll('input[name="selected_emp_f[]"]');
        // preSelVal = Array.from(preSelVal).map(function(checkbox) {
        //     return checkbox.value;
        // });
        var selectedVal = [];
        $('.row-checkbox:checked').each(function() {
            selectedVal.push($(this).val());
        });
        // const mergedArray = selectedVal.concat(preSelVal); // Merge the arrays
        const finalMergedArray = finalVal.concat(selectedVal); // Merge the arrays
        finalVal = [...new Set(finalMergedArray)]; // Convert to Set to remove duplicates and then back to array
        console.log(finalVal); // Display the array of selected values
    }


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
  });  
  function datatableSelectedEmpfunction(selectedValues)
{
    var dropdown_html=get_page_number('1'); 
    var amt = $('#hriad_amount').val();
    var cycle = $('#hrlc_id').val();
    var app_type = $('#hridt_id').val();
    var ref_no = $('#hriad_ref_no').val();
	var table = $('#selected_emp_list').DataTable({ 
		"language": {
            "infoFiltered":"",
            "processing": "<img src='"+DIR+"public/images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
        },
        dom: "<'row'<'col-sm-12'f>>" +"<'row'<'col-sm-3'l><'col-sm-9'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-12'p>>",
   
            oLanguage: {
	        sLengthMenu: dropdown_html
        },
		"bProcessing": true,
		"serverSide": true,
		"bDestroy": true,
		"searching": false,
        "paging": false,
		"order": [],
		"columnDefs": [{ orderable: false, targets: [0,1] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'hr-income-deduction/getSelEmpList', // json datasource
			type: "POST", 
			"data": {
				"selectedValues":selectedValues,
                "amt":amt,
                "cycle":cycle,
                "type":app_type,
                "ref_no":ref_no,
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			},
		},
       "columns": [
            { "data": "srno" },
			{ "data": "fullname" },
			{ "data": "dept_name" },
			{ "data": "designation" },
            { "data": "efective_date" },
            { "data": "deduct_amt" },
            { "data": "balance" },
            { "data": "action" },
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
            $("#common_pagesize").html(dropdown_html);
            
            $('#hridt_type').val(s.json.hridt_type);
            app_type = s.json.hridt_type;
            // $('#selected_emp_checkbox').empty();
            $.each(s.json.selectedValues, function(formID,formData){
                $('#selected_emp_checkbox').append("<input type='hidden' class='selected_emp_id_f' name='selected_emp_f[]' value="+formData.emp_id+">");
            });
			
            if (app_type == 1) {// if special fees [tax, pagibig, gsis, philhealth]
                $('#hriad_amount').prop( "disabled", true )
                $('#hrlc_id').prop( "disabled", true )
                
                $('#hriad_amount').val( 0)
                $('#hrlc_id').prop( 'selectedIndex',0 )
            }else if(app_type == 2){ // if income
                $('#hrlc_id').prop( "disabled", true )
                $('#hrlc_id').prop( 'selectedIndex',0 )
                $('#hriad_amount').prop( "disabled", false )
    
            } else { // if others
                $('#hriad_amount').prop( "disabled", false )
                $('#hrlc_id').prop( "disabled", false )
            }

            api.$('.remvSelData').click(function() {
				$(this).closest('tr').remove();
			});
	    }
	});  
}

  function datatablefunction()
{
    // finalVal = [];
    var preSelVal = document.querySelectorAll('input[name="selected_emp_f[]"]');
    preSelVal = Array.from(preSelVal).map(function(checkbox) {
        return checkbox.value;
    });
    // console.log(preSelVal); 
    var selectedVal = [];

    $('.row-checkbox:checked').each(function() {
        selectedVal.push($(this).val());
    });

    // Concatenate the arrays
    // finalVal = selectedVal.concat(preSelVal);
    // const mergedArray = selectedVal.concat(preSelVal); // Merge the arrays
    // finalVal = [...new Set(mergedArray)]; // Convert to Set to remove duplicates and then back to array
    // finalVal = selectedVal;
    // console.log(finalVal);   
	var dropdown_html=get_page_number('1'); 
	var table = $('#Jq_datatableEmplist').DataTable({ 
		"language": {
            "infoFiltered":"",
            "processing": "<img src='"+DIR+"public/images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
        },
        dom: "<'row'<'col-sm-12'f>>" +"<'row'<'col-sm-3'l><'col-sm-9'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-12'p>>",
   
            oLanguage: {
	         	sLengthMenu: dropdown_html
        },
		"bProcessing": true,
		"serverSide": true,
		"bDestroy": true,
		"searching": true,
		"columnDefs": [{ orderable: false, targets: [0,1] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'hr-income-deduction/getEmpList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q_emp").val(),
                "selectedValues":preSelVal,
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
            { "data": "select" },
			{ "data": "fullname" },
			{ "data": "dept_name" },
			{ "data": "designation" },
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	         $("#common_pagesize").html(dropdown_html);
			api.$('.activeinactive').click(function() {
				var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
				 ActiveInactiveUpdate(recordid,is_activeinactive);
	
			});
	    }
	});  

    table.on( 'draw', function () {
        // $('#payablesTable th input[type="checkbox"]').prop('checked', false);
        // if ($('#select-all').checked) {
        //     $('#Jq_datatableEmplist').find('tr input[type="checkbox"][value="'+ i +'"]').prop('checked', true);
        // } else {
        //     $('#Jq_datatableEmplist').find('tr input[type="checkbox"][value="'+ i +'"]').prop('checked', false);
        // }
        $('#select-all').prop('checked', false);
        console.log(finalVal);
        for (var i = 0; i < finalVal.length; i++) {
            console.log();
            $('#Jq_datatableEmplist').find('tr input[type="checkbox"][value="'+ finalVal[i] +'"]').prop('checked', true);
        }
    } );
}

// get Deductions
function getDeduction(data) {
    var deduct = $.ajax({
      url: DIR +'hr-income-deduction/getGovDeduction',
      type: 'POST',
      async: false,
      data: data,

    })
    return deduct.responseText
  }

//   multidimentional 
function listEmployees(){
    var token = $("#_csrf_token").val();	
    //created multidimentional array for selected value
    var finalArray=[];
    const hriad_eft_date = $('#eft_date').val();
    var selectedVal = [];
    var app_type =  $('#hridt_id').val();
    $('.row-checkbox:checked').each(function() {
        selectedVal.push($(this).val());
    });
    deduction = 0;
    const selectedArray = finalVal.map(emp_id => {
        // if (app_type > 2) {
        //     deduction = getDeduction(
        //     {
        //         'emp_id': emp_id,
        //         'type': app_type,
        //         _token: token
        //     });
            
        // }
        return {
            emp_id: emp_id,
            // deduction: deduction,
            hriad_effectivity_date: hriad_eft_date
        };
    });
    //created multidimentional array for previous value
    var preSelVal = document.querySelectorAll('input[name="selected_emp_f[]"]');
    var preArray = [];
    preSelVal.forEach(function(empInput) {
        var empId = empInput.value;
        var row = empInput.closest('tr'); // Assuming each input is in a table row
        
        if (row) {
            var nearestDateInput = row.querySelector('input[name="selected_efp_date[]"]');
            // var nearestDeductInput = row.querySelector('input[name="selected_efp_deduct[]"]');
            if (nearestDateInput) {
                var hriadEffectivityDate = nearestDateInput.value;
                // var deduction = nearestDeductInput.value;
                preArray.push({ emp_id: empId, hriad_effectivity_date: hriadEffectivityDate, /*deduction: deduction*/ });
            }
        }
    });
    
    finalArray=selectedArray.concat(preArray);

    console.log(finalArray);
    if (finalArray.length > 0) {
        datatableSelectedEmpfunction(finalArray);
    }
}