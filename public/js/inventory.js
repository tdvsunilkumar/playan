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
			url :DIR+'Medicine-supplies-inventory/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
			{ "data": "srno"},
			{ "data": "cip_control_no"},
			{ "data": "cip_expiry_date"},
			{ "data": "cip_item_name"},
			{ "data": "cip_uom"},
			{ "data": "qty"},
			{ "data": "cip_issued_qty"},
			{ "data": "cip_adjust_qty"},
			{ "data": "cip_unit_cost"},
			{ "data": "cip_status"},
			{ "data": "civ_is_active"},
        	{ "data": "action"}
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
	    }
	});  
}

function ActiveInactiveUpdate(id,is_activeinactive){
	var msg = is_activeinactive==1?'restored':'removed';
	console.log(is_activeinactive);
	const swalWithBootstrapButtons = Swal.mixin({
		customClass: {
			confirmButton: 'btn btn-success',
			cancelButton: 'btn btn-danger'
		},
		buttonsStyling: false
	})
	swalWithBootstrapButtons.fire({
		title: 'Are you sure?',
		text: "This record will be "+msg,
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		reverseButtons: true
	}).then((result) => {
			if(result.isConfirmed){
			   $.ajax({
				url :DIR+'Medicine-supplies-inventory/ActiveInactive', // json datasource
				type: "POST", 
				data: {
				  "id": id,
				  "is_activeinactive": is_activeinactive,  
				  "_token": $("#_csrf_token").val(),
				},
				success: function(html){
					Swal.fire({
					  position: 'center',
					  icon: 'success',
					  title: 'Update Successfully.',
					  showConfirmButton: false,
					  timer: 1500
					})
					datatablefunction();
					setInterval(function(){
				   
					   });
					//location.reload();
				}
			})
		}
	})
 }