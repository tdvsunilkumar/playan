$(document).ready(function(){	
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
 	 $('#datecreated').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'mm-dd-yy'
    });
});
function grosssaleReceipt(id){
              var id = id;
              $.ajax({
                url: DIR+'bploapplication/grosssalereceipt',
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
		"columnDefs": [{ orderable: false, targets: [0,5] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'bploapplication/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				'year':$('#yeardate').val(),
				'approve':$('#aproved').val(),
				'crdate':$('#datecreated1').val(),
				'barangay':$('#barangay').val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
        "columns": [
        	{ "data": "ba_business_account_no" },
			{ "data": "p_complete_name_v1" },
        	{ "data": "ba_business_name" },
        	{ "data": "business_address" },
        	{ "data": "created_at" },
            { "data": "action" }
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	        $("#common_pagesize").html(dropdown_html);
	         api.$('.print').click(function() {
	            var rowid = $(this).attr('id');
	            grosssaleReceipt(rowid);
	        });
	    }
	});  
}
