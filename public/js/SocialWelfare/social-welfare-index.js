$(document).ready(function(){
    $(".amount_money").numeric({ decimal : "." });
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
	$('#commonModal').attr('data-bs-keyboard', 'false');
	$('#commonModal').attr('data-bs-backdrop', 'static');
	
});
function callToggle(){
	$('[data-bs-toggle="tooltip"]').tooltip({
		trigger : 'hover'
	});
}

function drawTable(slug, columns, order)
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
		"columnDefs": [{ orderable: false, targets: order }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+slug+'/getList', // json datasource  
			type: "GET", 
			"data": {
				"q":$("#q").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": columns,
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
			callToggle();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	         $("#common_pagesize").html(dropdown_html);
	        api.$('.activeinactive').click(function() {
	            var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
	            activeInactive(recordid, is_activeinactive,slug, tbl_data = {columns, order});
	        });

			
	    }
	});  
}


function activeInactive(id, is_activeinactive,slug,tbl_data ){
	var msg = is_activeinactive==1?'restored':'removed';
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
				url :DIR+slug+'/ActiveInactive', // json datasource
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
					drawTable(slug,tbl_data.columns,tbl_data.order);
					setInterval(function(){
				   
					   });
					// location.reload();
				}
			})
		}
	})
}