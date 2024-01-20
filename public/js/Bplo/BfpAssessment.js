$(document).ready(function(){	
	var yearpickerInput = $('input[name="search_year"]').val();
      $('.yearpicker').yearpicker();
      $('.yearpicker').val(yearpickerInput).trigger('change');
	$('#endorsement_status').select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
	$('#payment_status').select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
	$("#search_year").change(function(){
 		datatablefunction();
 	});
 	$("#payment_status").change(function(){
 		datatablefunction();
 	});
 	$("#endorsement_status").change(function(){
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
		"columnDefs": [{ orderable: false, targets: [0,10] }],
		"pageLength": 25,
		"ajax":{ 
			url :DIR+'fire-protection/cashiering/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				'year':$('#search_year').val(),
				'endorsement_status':$('#endorsement_status').val(),
				'payment_status':$('#payment_status').val(),
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
        	{ "data": "app_type" },
        	{ "data": "busn_app_status" },
        	{ "data": "end_status" },
        	{ "data": "bfpas_total_amount_paid" },
        	{ "data": "Date" },
        	{ "data": "status" },
            { "data": "action" }
        ],
        
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	        $("#common_pagesize").html(dropdown_html);
	         api.$('.print').click(function() {
	            var id = $(this).attr('id');
	            BploBusinessPermitPrint(id);
	        });
	    }
	});  
}