$(document).ready(function(){	
	$("#status").select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
 	$('#status').on('change',function(){
 		datatablefunction();
 	});
 	if($("#remote_cashier_id").val()>0){
 		updateCashierBillHistoryTaxpayers();
 	}
 	
});

function updateCashierBillHistoryTaxpayers(){
  $.ajax({
    url: DIR+'cashier/cashier-business-permit/updateCashierBillHistoryTaxpayers',
    type: 'POST',
    data: {
        "remote_cashier_id": $("#remote_cashier_id").val(), 
        "_token": $("#_csrf_token").val(),
    },
    success: function (data) {
       console.log(data);
    }
  });
}

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
		"columnDefs": [{ orderable: false, targets: [0,11,12] }],
		"pageLength": 25,
		"ajax":{ 
			url :DIR+'cashier/cashier-business-permit/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				'fromdate':$('#fromdate').val(),
				'todate':$('#todate').val(),
				'status':$('#status').val(),
				'crdate':$('#datecreated1').val(),
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
        	{ "data": "transaction_no" },
        	{ "data": "or_no" },
        	{ "data": "total_amount" },
        	{ "data": "total_paid_amount" },
        	{ "data": "tax_credit_amount" },
        	{ "data": "payment_terms" },
        	{ "data": "status" },
        	{ "data": "Date" },
        	{ "data": "cashier_name" },
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