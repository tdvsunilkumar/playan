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
		"columnDefs": [{ orderable: false, targets: [0,5] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'payment-system/side-menu/setup-receipts/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
			{ "data": "srno" },
        	{ "data": "stp_type" },
        	{ "data": "stp_accountable_form" },
        	{ "data": "serial_no_from" },
        	{ "data": "serial_no_to" },
        	{ "data": "stp_qty" },
        	{ "data": "stp_value" },
        	{ "data": "stp_print" },
        	{ "data": "is_active" },
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

	         api.$('.printupdate').click(function() {
	            var recordid = $(this).attr('id');
	            var is_print = 0;
	            if($(this).is(':checked')) { 
                   is_print=1;
                }
	             PrintOptionUpdate(recordid,is_print);

	        });
	    }
	});  
}
function DeleteRecord(id){
	// alert(id);
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "This action can not be undone. Do you want to continue?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if(result.isConfirmed)
        {
           $.ajax({
		        url :DIR+'payment-system/side-menu/setup-receipts/delete', // json datasource
		        type: "POST", 
		        data: {
		          "id": id,
		         "_token": $("#_csrf_token").val(),
		        },

		        success: function(html){
		        	Swal.fire({
    				  position: 'center',
    				  icon: 'success',
    				  title: 'Setup pop receipts Deleted Successfully.',
    				  showConfirmButton: false,
    				  timer: 1500
    				})
		           location.reload();
		        }
		    })
        }
    })
}



function PrintOptionUpdate(id,is_print){
	 // alert(id);
	 // alert(is_print);
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You wont to update print option?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if(result.isConfirmed)
        {
           $.ajax({
		        url :DIR+'payment-system/side-menu/setup-receipts/PrintOptionUpdate', // json datasource
		        type: "POST", 
		        data: {
		          "id": id,
		          "is_print": is_print,  
		          "_token": $("#_csrf_token").val(),
		        },
		        success: function(html){
		        	Swal.fire({
    				  position: 'center',
    				  icon: 'success',
    				  title: 'print option Update Successfully.',
    				  showConfirmButton: false,
    				  timer: 1500
    				})
		           location.reload();
		        }
		    })
        }
    })
}
