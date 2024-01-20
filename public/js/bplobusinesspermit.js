$(document).ready(function(){	
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
});

function BploBusinessPermitPrint(id){
              var id = id;
              $.ajax({
                url: DIR+'/bplobusinesspermitPrint',
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
		"columnDefs": [ { orderable: true, targets: 0 },
						{ orderable: true, targets: 1 },
						{ orderable: true, targets: 2 },
						{ orderable: true, targets: 3 },
						{ orderable: true, targets: 4 },
						{ orderable: true, targets: 5 },
						{ orderable: true, targets: 6 },
						{ orderable: true, targets: 7 },
						{ orderable: true, targets: 8 },
						{ orderable: true, targets: 9 },
						{ orderable: false, targets: 10 }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'bplobusinesspermit/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				'year':$('#year').val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
        "columns": [
        	{ "data": "srno" },
        	{ "data": "busn_tax_year" },
			{ "data": "busns_id_no" },
			{ "data": "full_name" },
			{ "data": "busn_name" },
			{ "data": "app_type" },
			{ "data": "app_date" },
			{ "data": "busn_app_status" },
			{ "data": "app_method" },
			{ "data": "busn_plate_number" },
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
