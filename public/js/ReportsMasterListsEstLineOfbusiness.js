$(document).ready(function(){
	$("#btn_download_spreadsheet").click(function(){
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		window.location.href= DIR+"export-est-LineOfbusiness?from_date="+ from_date + "&to_date=" + to_date; 
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
		"columnDefs": [{ orderable: false, targets: [0,3,5,8,9] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'report-est-LineOfbusiness/getList', // json datasource
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
			{ "data": "busns_id_no" },
            { "data": "busn_name" },
            { "data": "location_address" },
            { "data": "full_name" },
			{ "data": "ownar_address" },
            { "data": "p_mobile_no" },
        	{ "data": "busn_tax_year" },
            { "data": "line_of_busn_code" },
            { "data": "line_of_busn" },
            { "data": "busn_plate_number" },
            { "data": "bpi_issued_date" },
            { "data": "app_date" },
            { "data": "btype_desc" },
            { "data": "busn_app_method" },
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	         $("#common_pagesize").html(dropdown_html);
	    }
	});  
}


