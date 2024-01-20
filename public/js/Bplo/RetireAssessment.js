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
		"columnDefs": [{ orderable: false, targets: [0,7,8,9] }],
		"pageLength": 25,
		"ajax":{ 
			url :DIR+'treasury-business-retirement-assessment/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				"year":$('#search_year').val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
        "columns": [
        	{ "data": "srno"},
        	{ "data": "busns_id_no" },
			{ "data": "ownar_name" },
        	{ "data": "busn_name" },
        	{ "data": "retire_app_type" },
        	{ "data": "retire_date_start" },
        	{ "data": "retire_date_closed" },
        	{ "data": "duration" },
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