$(document).ready(function(){	
	$('#commonModal').modal({backdrop: 'static', keyboard: false});
	$("#status").select3({dropdownAutoWidth : false,dropdownParent : $(document.body)});
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	

 	$('#status').on('change',function(){
 		datatablefunction();
 	});

 	 $('.addNewCashierRealProperty').on('click',function(){
                var url = $(this).data('url');
                var title1 = 'Manage Real Property Cashiering';
                var title2 = 'Manage Real Property Cashiering';
                var title = (title1 != undefined) ? title1 : title2;
                var size = 'xll';
                loadMainForm(url, title, size,'commonModal');
     });

});

function updateRptOnlineAccess(){
  $.ajax({
    url: DIR+'cashier-real-property/updateRptOnlineAccessTaxpayers',
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

function updateCashierBillHistoryTaxpayers(){
  $.ajax({
    url: DIR+'cashier-real-property/updateCashierBillHistoryTaxpayers',
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

function loadMainForm(url, title, size,modalId) {
    showLoader();
    $("#"+modalId).unbind("click");
    $("#"+modalId+" .modal-title").html(title);
    $("#"+modalId+" .modal-dialog").addClass('modal-' + size);
    
    $.ajax({
        url: url,
        success: function (data) {
            hideLoader();
            if(typeof data.status !== 'undefined' && data.status == 'error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: data.msg,
                      showConfirmButton: true,
                      timer: 3000
                    })

            }else{
                $('#'+modalId+' .body').html('');
                $('#'+modalId+' .body').html(data);
                $("#"+modalId).modal('show');
                taskCheckbox();
                //common_bind("#"+modalId);
                commonLoader();
            }
            
        },
        error: function (data) {
            hideLoader();
            $('#'+modalId).modal('hide');
                /*Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: false,
                      timer: 3000
                    })*/
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
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
		"columnDefs": [{ orderable: false, targets: [0,9] }],
		"pageLength": 25,
		"ajax":{ 
			url :DIR+'cashier-real-property/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				'fromdate':$('#fromdate').val(),
				'todate':$('#todate').val(),
				'status':$('#status').val(),
				/*'crdate':$('#datecreated1').val(),*/
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
        "columns": [
        	{ "data": "srno"},
        	{ "data": "ownar_name" },
			{ "data": "top_no" },
        	{ "data": "or_no" },
        	{ "data": "total_paid_amount" },
        	{ "data": "tax_credit" },
            { "data": "Date" },
            { "data": "cashier" },
            { "data": "payment_terms" },
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