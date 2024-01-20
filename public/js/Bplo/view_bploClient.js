$(document).ready(function(){	
	viewDatatableFunction();
});



function viewDatatableFunction()
{
	var table = $('#Jq_ViwDatatableList').DataTable({ 
		"language": {
            "infoFiltered":"",
            "processing": "<img src='"+DIR+"public/images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
        },
		"bProcessing": true,
		"serverSide": true,
		"bDestroy": true,
		"searching": false,
		"info":     false,
		"order": [],
		"columnDefs": [{ orderable: false, targets: [0,1,2,3,4]}],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'bploclients/getViewList', // json datasource
			type: "GET", 
			"data": {
				"client_id":$("#client_id").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
			
		},
        "columns": [
        	{ "data": "srno" },
        	{ "data": "busn_name" },
        	{ "data": "last_or_no" },
        	{ "data": "last_or_date" },
        	{ "data": "payment_status" }
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	    }
	});  
}
