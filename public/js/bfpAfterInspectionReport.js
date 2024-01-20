$(document).ready(function(){	
	$("#year").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
 	$("#year").change(function(){
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
			url :DIR+'aftertinspectionreport/getList', // json datasource
			type: "GET", 
			"data": {
				"pageTitle":$("#pageTitle").val(),
				"bbendo_id":$("#bbendo_id").val(),
				"q":$("#q").val(),
				"year":$('#year').val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
			{ "data": "srno" },
        	{ "data": "busn_registration_no" },
        	{ "data": "ownar_name" },
        	{ "data": "busn_name" },
        	{ "data": "app_type" },
        	{ "data": "created_at" },
        	{ "data": "action" }
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	        $("#common_pagesize").html(dropdown_html);
	        api.$('.print').click(function() {
                var id = $(this).attr('id');
                inspectionPrints(id);
            });
	    }
	});  
}


function inspectionPrints(id){
              var id = id;
              $.ajax({
                url: DIR+'firePrint',
                type: 'POST',
                data: {
                    "id": id, "_token": $("#_csrf_token").val(),
                },
                success: function (data) {
                   var url = data;
                   console.log(url);
                    window.open(url, '_blank');
                }
              });
}

