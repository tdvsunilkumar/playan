$(document).ready(function(){	
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
 	
});


function datatablefunction()
{
	// var dropdown_html=get_page_number('1'); 
	var table = $('#Jq_datatablelist').DataTable({ 
		// "language": {
        //     "infoFiltered":"",
        //     "processing": "<img src='../images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
        // },
        dom: "<'row'<'col-sm-12'f>>" +"<'row'<'col-sm-3'l><'col-sm-9'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-12'p>>",
        //     oLanguage: {
	    //      	sLengthMenu: dropdown_html
        // },
		"bProcessing": true,
		"serverSide": true,
		"bDestroy": true,
		"searching": false,
		"order": [],
		"columnDefs": [{ orderable: false, targets: [8] }],
		"pageLength": 10,
		"ajax":{ 
			url :'check-disbursement/lists', // json datasource  
			type: "GET", 
			"data": {
				"q":$("#q").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
			{ "data": "date" },
        	{ "data": "voucher_no" },
        	{ "data": "particulars" },
        	{ "data": "transaction_type" },
        	{ "data": "credit" },
        	{ "data": "last_modified" },
        	{ "data": "is_active" },
        	{ "data": "action" }
        ],
		columnDefs: [
			{  orderable: true, targets: 0, className: 'text-start w-25' },
			{  orderable: true, targets: 1, className: 'text-start sliced' },
			{  orderable: true, targets: 2, className: 'text-end' },
			{  orderable: true, targets: 3, className: 'text-end' },
			{  orderable: true, targets: 4, className: 'text-end' },
			{  orderable: true, targets: 5, className: 'text-center' },
			{  orderable: false, targets: 6, className: 'text-center' },
			{  orderable: false, targets: 7, className: 'text-center' },
		],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
			// callToggle();
	        // var dropdown_html=get_page_number(info.recordsTotal,info.length);
	        //  $("#common_pagesize").html(dropdown_html);
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
				url :'check-disbursement/ActiveInactive', // json datasource
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
				}
			})
		}
	})
}