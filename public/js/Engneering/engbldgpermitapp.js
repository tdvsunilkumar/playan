$(document).ready(function(){	
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
		"columnDefs": [{ orderable: false, targets: [6] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'engbldgpermitapp/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
        "columns": [
        	{ "data": "municipal" },
        	{ "data": "appno" },
        	{ "data": "permitno" },
        	{ "data": "appdate" },
        	{ "data": "ownername" },
        	{ "data": "issueddate" },
        	{ "data": "address" },
            { "data": "action"}
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	        $("#common_pagesize").html(dropdown_html);
	    }
	});  
}