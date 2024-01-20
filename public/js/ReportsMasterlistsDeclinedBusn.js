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
		"columnDefs": [{ orderable: false, targets: [0,3,5] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'report-declined-business/getList', // json datasource
			type: "POST", 
			"data": {
				"q":$("#q").val(),
                "from_date":$("#from_date").val(),
                "to_date":$("#to_date").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
			{ "data": "srno" },
            { "data": "owner_name" },
			{ "data": "busn_name" },
            { "data": "location_address" },
            { "data": "app_type" },
            { "data": "line_of_busn" },
            { "data": "p_mobile_no" },
			{ "data": "busn_app_method" },
            { "data": "p_email_address" },
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	         $("#common_pagesize").html(dropdown_html);
	        api.$('.deleterow').click(function() {
	            var recordid = $(this).attr('id');
	            DeleteRecord(recordid);
	        });

			api.$('.activeinactive').click(function() {
				var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
				 	ActiveInactiveUpdate(recordid,is_activeinactive);
			});
	    }
	});  
}


