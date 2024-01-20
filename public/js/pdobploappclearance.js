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
		"columnDefs": [{ orderable: false, targets: [0,13] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'pdobploappclearance/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				'year':$('#yeardate').val(),
				'approve':$('#aproved').val(),
				'crdate':$('#datecreated1').val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
        "columns": [
        	{ "data": "srno"},
        	{ "data": "p_complete_name_v1" },
			{ "data": "ba_business_account_no" },
        	{ "data": "pbac_app_code" },
        	{ "data": "pbac_app_year" },
        	{ "data": "pbac_app_no" },
        	{ "data": "pbac_transaction_no" },
        	{ "data": "pbac_zoning_clearance_fee" },
        	{ "data": "pbac_is_paid" },
        	{ "data": "pbac_issuance_date" },
        	{ "data": "pbac_officer_position" },
        	{ "data": "pbac_approver_position" },
        	{ "data": "pbac_remarks" },
            { "data": "action" }
        ],
        
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	        $("#common_pagesize").html(dropdown_html);
	         api.$('.print').click(function() {
	            var id = $(this).attr('id');
	            bploClearancePrint(id);
	        });
	         api.$('.report').click(function() {
	            var id = $(this).attr('id');
	            GenerateReport(id);
	        });
	         api.$('.printreport').click(function() {
	            var id = $(this).attr('id');
	            PrintReport(id);
	        }); 
	    }
	});  
}
