$(document).ready(function(){	
	datatablefunction();
    $("#hrtk_department_id").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});  
    $("#hrtk_division_id").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
    $("#hrtk_emp_id").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
    $("#cut_off_period").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
    $('#hrtk_department_id').on('change', function() {
        var dept_id =$(this).val();
          $.ajax({
                url :DIR+'hr-timekeeping/getDivByDept', // json datasource
                type: "POST", 
                data: {
                        "dept_id": dept_id, 
                        "_token": $("#_csrf_token").val(),
                    },
                success: function(html){
                  $("#hrtk_division_id").html(html);
                  $("#hrtk_emp_id").html("<option value=''>Please Select</option>");
                }
            })
      }); 
    $('#hrtk_division_id').on('change', function() {
            var div_id =$(this).val();
          $.ajax({
                url :DIR+'hr-timekeeping/getEmpByDiv', // json datasource
                type: "POST", 
                data: {
                        "div_id": div_id, 
                        "_token": $("#_csrf_token").val(),
                    },
                success: function(html){
                  $("#hrtk_emp_id").html(html);
                }
            })
    });   
	$("#btn_search").click(function(){
    datatablefunction();
  });
    $('#process').click(function(){
        var isCheckboxChecked = $('#hrtk_is_processed').prop('checked');
        if (isCheckboxChecked) {
            var hrtk_is_processed=1;
        } else {
            var hrtk_is_processed=0;
        }
        var filtervars = {
          id:$(this).val(),
          sequence:$(this).attr('sequence'),
          "_token": $("#_csrf_token").val()
        };

      const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
          title: 'Are you sure?',
          text: "You want to process the data.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
          reverseButtons: true
        }).then((result) => {
        if(result.isConfirmed){
              $.ajax({
                type: "POST",
                url: DIR+'hr-timekeeping/process-timekeeping',
                data:  {
                    "q":$("#q").val(),
                    "hrtk_department_id":$("#hrtk_department_id").val(),
                    "hrtk_division_id":$("#hrtk_division_id").val(),
                    "hrtk_emp_id":$("#hrtk_emp_id").val(),
                    "cut_off_period":$("#cut_off_period").val(),
                    "hrtk_is_processed":hrtk_is_processed,
                    "_token":$("#_csrf_token").val()
                }, 
                dataType: "html",
                success: function(data){ 
                  msg = $.parseJSON(data);
                    console.log(msg);
                    hideLoader();
                    if (msg.ESTATUS == 0 ) {
                      datatablefunction();
                      Swal.fire({
                          position: 'center',
                          icon: 'success',
                          title: 'The Timekeeping is Processed Succesfully',
                          showConfirmButton: false,
                          timer: 1500
                        })
                    } else {
                      Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: msg.msg,
                        showConfirmButton: false,
                        timer: 1500
                      })
                    }
                    
                },error:function(){
                  hideLoader();
                }
              });
        }
      })
    });
    
});





function datatablefunction()
{
    var isCheckboxChecked = $('#hrtk_is_processed').prop('checked');
    if (isCheckboxChecked) {
        // Checkbox is checked
        console.log('Checkbox is checked.');
        var hrtk_is_processed=1;
        $("#process").addClass('disabled');
    } else {
        // Checkbox is not checked
        console.log('Checkbox is not checked.');
        var hrtk_is_processed=0;
    }
	var dropdown_html=get_page_number('1'); 
	var table = $('#Jq_datatablelist').DataTable({ 
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
		"order": [],
		"columnDefs": [{ orderable: false, targets: [0] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'hr-timekeeping/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
                "hrtk_department_id":$("#hrtk_department_id").val(),
                "hrtk_division_id":$("#hrtk_division_id").val(),
                "hrtk_emp_id":$("#hrtk_emp_id").val(),
                "cut_off_period":$("#cut_off_period").val(),
                "hrtk_is_processed":hrtk_is_processed,
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
            { "data": "srno" },
            { "data": "user_id_no" },
			{ "data": "emp_name" },
            { "data": "dept_name" },
            { "data": "div_name" },
            { "data": "hrtk_date" },
        	{ "data": "hrtk_total_hours" },
            { "data": "hrtk_total_aut" },
        	{ "data": "hrtk_total_overtime" },
        	{ "data": "hrtk_total_leave" },

        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
            if(info.recordsTotal == 0 || $('#hrtk_is_processed').prop('checked')){
                $("#process").addClass('disabled');
            } else {
              $("#process").removeClass('disabled');
            }
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	         $("#common_pagesize").html(dropdown_html);
			    api.$('.activeinactive').click(function() {
				var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
				ActiveInactiveUpdate(recordid,is_activeinactive);
	
			});
	    }
	});  
}



