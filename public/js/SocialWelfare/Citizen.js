$(document).ready(function(){	
	var isopen=$("#isopen").val();
	if(isopen==1){
		$("#addCitizen").trigger("click");
	}
	citizenTable();
	$("#btn_search").click(function(){
 		citizenTable();
 	});	
 	
});


function citizenTable()
{
	var dropdown_html=get_page_number('1'); 
	var table = $('#citizen-datatable').DataTable({ 
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
		"columnDefs": [{ orderable: false, targets: [0,6] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'citizens/getList', // json datasource  
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
			{ "data": "cit_fullname" },
        	{ "data": "brgy_name" },
        	{ "data": "birthdate" },
        	{ "data": "gender" },
        	{ "data": "is_active" },
        	{ "data": "action" }
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
			callToggle();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	         $("#common_pagesize").html(dropdown_html);
	        api.$('.activeinactive').click(function() {
	            var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
	            activeInactive(recordid, is_activeinactive);
	        });

			
	    }
	});  
}


function activeInactive(id, is_activeinactive){
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
				url :DIR+'citizens/ActiveInactive', // json datasource
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
					citizenTable();
					setInterval(function(){
				});
				}
			})
		}
	})
}