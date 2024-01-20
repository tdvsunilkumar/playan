$(document).ready(function(){	
	$('#search_year').select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
	$("#search_year").change(function(){
 		datatablefunction();
 	});	
	datatablefunction();
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
		"columnDefs": [{ orderable: false, targets: [0,6,8,9] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'treasurer/assessment/getList', // json datasource
			type: "GET", 
			"data": {
				"year":$('#search_year').val(),
				"q":$("#q").val(),
	             "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
	    "columns": [
			{ "data": "srno" },
			{ "data": "busns_id_no" },
			{ "data": "owner" },
			{ "data": "busn_name" },
			{ "data": "app_type" },
			{ "data": "app_date" },
			{ "data": "busn_app_status" },
			{ "data": "app_method" },
			{ "data": "top_no" },
			{ "data": "action" }
	  ],
	   drawCallback: function(s){ 
			var api = this.api();
			var info=table.page.info();
			var dropdown_html=get_page_number(info.recordsTotal,info.length);
			$("#common_pagesize").html(dropdown_html);
	   }
	});  
}
