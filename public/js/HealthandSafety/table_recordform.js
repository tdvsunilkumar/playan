$(document).ready(function(){
    medicalRecordTable()
	medicalIssuanceTable()
	LaboratoryRecordTable()
});

function medicalRecordTable()
{
	var dropdown_html=get_page_number('1'); 
    var url = $('#medical-record-table').data('href');
	var table = $('#medical-record-table').DataTable({ 
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
		"columnDefs": [{ orderable: false }],
		"pageLength": 5,
		"ajax":{ 
			url :url, // json datasource  
			type: "GET", 
			"data": {
				"q":$("#q").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
			{ "data": "no" },
			{ "data": "date_created" },
        	{ "data": "diagnosis" },
        	{ "data": "treatment" },
        	{ "data": "nurse_notes" },
        	{ "data": "action" },
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
			callToggle()
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	         $("#common_pagesize").html(dropdown_html);
	        api.$('.activeinactive').click(function() {
	            var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
	            // deactivateRecord(recordid, is_activeinactive);
	        });

			
	    }
	});  
}

function LaboratoryRecordTable()
{
	var dropdown_html=get_page_number('1'); 
    var url = $('#laboratory-record-table').data('href');
	var table = $('#laboratory-record-table').DataTable({ 
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
		"columnDefs": [{ orderable: false }],
		"pageLength": 5,
		"ajax":{ 
			url :url, // json datasource  
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
			{ "data": "service_name" },
        	{ "data": "or_no" },
			{ "data": "date" },
        	{ "data": "action" },
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
			callToggle()
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	         $("#common_pagesize").html(dropdown_html);
	        api.$('.activeinactive').click(function() {
	            var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
	            // deactivateRecord(recordid, is_activeinactive);
	        });

			
	    }
	});  
}

// function deactivateRecord(id, is_activeinactive){
// 	var msg = is_activeinactive==1?'restored':'removed';
// 	const swalWithBootstrapButtons = Swal.mixin({
// 		customClass: {
// 			confirmButton: 'btn btn-success',
// 			cancelButton: 'btn btn-danger'
// 		},
// 		buttonsStyling: false
// 	})
// 	swalWithBootstrapButtons.fire({
// 		title: 'Are you sure?',
// 		text: "This record will be "+msg,
// 		icon: 'warning',
// 		showCancelButton: true,
// 		confirmButtonText: 'Yes',
// 		cancelButtonText: 'No',
// 		reverseButtons: true
// 	}).then((result) => {
// 			if(result.isConfirmed){
// 			   $.ajax({
// 				url :DIR+'medical/recordActiveInactive', // json datasource
// 				type: "POST", 
// 				data: {
// 				  "id": id,
// 				  "is_activeinactive": is_activeinactive,  
// 				  "_token": $("#_csrf_token").val(),
// 				},
// 				success: function(html){
// 					Swal.fire({
//                     position: 'center',
//                     icon: 'success',
//                     title: 'Update Successfully.',
//                     showConfirmButton: false,
//                     timer: 1500
// 					})
// 					    medicalRecordTable();
//                     //     setInterval(function(){
// 					// });
// 					//location.reload();
// 				}
// 			})
// 		}
// 	})
// }


function medicalIssuanceTable()
{
	var dropdown_html=get_page_number('1'); 
    var url = $('#item-table').data('href');
	var table = $('#item-table').DataTable({ 
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
		"columnDefs": [{ orderable: false }],
		"pageLength": 5,
		"ajax":{ 
			url :url, // json datasource  
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
			{ "data": "item_name" },
        	{ "data": "uom_code" },
        	{ "data": "quantity" },
        	{ "data": "date_recieved" },
        	{ "data": "action" },
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
			callToggle()
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	         $("#common_pagesize").html(dropdown_html);
	        api.$('.activeinactive').click(function() {
	            var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
	            // deactivateIssueance(recordid, is_activeinactive);
	        });

			
	    }
	});  
}

// function deactivateIssueance(id, is_activeinactive){
// 	var msg = is_activeinactive==1?'restored':'removed';
// 	const swalWithBootstrapButtons = Swal.mixin({
// 		customClass: {
// 			confirmButton: 'btn btn-success',
// 			cancelButton: 'btn btn-danger'
// 		},
// 		buttonsStyling: false
// 	})
// 	swalWithBootstrapButtons.fire({
// 		title: 'Are you sure?',
// 		text: "This record will be "+msg,
// 		icon: 'warning',
// 		showCancelButton: true,
// 		confirmButtonText: 'Yes',
// 		cancelButtonText: 'No',
// 		reverseButtons: true
// 	}).then((result) => {
// 			if(result.isConfirmed){
// 			   $.ajax({
// 				url :DIR+'medical/recordActiveInactive', // json datasource
// 				type: "POST", 
// 				data: {
// 				  "id": id,
// 				  "is_activeinactive": is_activeinactive,  
// 				  "_token": $("#_csrf_token").val(),
// 				},
// 				success: function(html){
// 					Swal.fire({
//                     position: 'center',
//                     icon: 'success',
//                     title: 'Update Successfully.',
//                     showConfirmButton: false,
//                     timer: 1500
// 					})
// 					    medicalRecordTable();
//                     //     setInterval(function(){
// 					// });
// 					//location.reload();
// 				}
// 			})
// 		}
// 	})
// }