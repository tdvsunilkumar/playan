$(document).ready(function(){
	$("#flt_Status").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
    $("#flt_busn_office_barangay").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
});

  function printoccupancy(id){
              var appliid = id;
              $.ajax({
                url: DIR+'engoccupancyapponline/certificateoccupancyprint',
                type: 'POST',
                data: {
                    "id": appliid, "_token": $("#_csrf_token").val(),
                },
                success: function (data) {
                   var url =  data;
                    window.open(url, '_blank');
                }
              });
}

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
		"columnDefs": [{ orderable: false, targets: [0,7] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'engoccupancyapponline/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				"barangay":$("#flt_busn_office_barangay").val(),
				"fromdate":$("#from_date").val(),
				"todate":$("#to_date").val(),
				"status":$("#flt_Status").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
            { "data": "srno" },
			// { "data": "ebpa_id" },
			{ "data": "ownername" },
			{ "data": "barangay" },
        	{ "data": "eoa_application_type" },
        	{ "data": "appno" },
        	{ "data": "is_active" },
        	{ "data": "date" },
        	{ "data": "method" },
			{ "data": "duration" },
        	{ "data": "action" }
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
			 api.$('.print').click(function() {
	            var appid = $(this).attr('id');
	            printoccupancy(appid);
	        });
	    }
	});  
}

