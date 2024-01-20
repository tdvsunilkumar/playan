$(document).ready(function(){	
	datatablefunction();
     $("#hrbr_department_id").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});  
    $("#hrbr_division_id").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
    $('#hrbr_department_id').on('change', function() {
        var dept_id =$(this).val();
          $.ajax({
                url :DIR+'employee-biometric-record/getDivByDept', // json datasource
                type: "POST", 
                data: {
                        "dept_id": dept_id, 
                        "_token": $("#_csrf_token").val(),
                    },
                success: function(html){
                  $("#hrbr_division_id").html(html);
                }
            })
      }); 
	$("#btn_search").click(function(){
 		datatablefunction();
 	});
});





function datatablefunction()
{
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
			url :DIR+'employee-biometric-record/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
                "hrbr_department_id":$("#hrbr_department_id").val(),
                "hrbr_division_id":$("#hrbr_division_id").val(),
                "from_date":$("#from_date").val(),
                "to_date":$("#to_date").val(),
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
            { "data": "hrbr_date" },
        	{ "data": "hrbr_time" },
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
}



