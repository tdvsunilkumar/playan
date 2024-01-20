$(document).ready(function(){	
	datatablefunction();
	
	$("#flt_status").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
	 $('#busn_tin_no').on('input', function() {
		var tinInput = $(this).val();
		var pattern = new RegExp($(this).attr('pattern'));
	
		if (tinInput.length !== 15 || !pattern.test(tinInput)) {
		  $('#tin_error').show();
		} else {
		  $('#tin_error').hide();
		}
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
		"columnDefs": [{ orderable: false, targets: [0,9,10] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'business-permit/application/lists', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				"from_date":$("#from_date").val(),
				"to_date":$("#to_date").val(),
                "brgy":$("#flt_busn_office_barangay").val(),
				"flt_status":$("#flt_status").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
			{ "data": "srno" },
        	{ "data": "busn_id_no" },
            { "data": "owner" },
            { "data": "busn_name" },
			{ "data": "barangay" },
            { "data": "app_type" },
            { "data": "app_date" },
			{ "data": "last_pay_date" },
            { "data": "busn_app_status" },
        	{ "data": "app_method" },
			{ "data": "duration" },
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

			api.$('.activeinactive').click(function() {
				var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
				 ActiveInactiveUpdate(recordid,is_activeinactive);
	
			});
			api.$('.newRenew').click(function() {
				var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
				newRenewUpdate(recordid,is_activeinactive);
	
			});
	    }
	});  
}


function ActiveInactiveUpdate(id,is_activeinactive){
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
			   url :DIR+'business-permit/application/ActiveInactive', // json datasource
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

function newRenewUpdate(id,is_newRenew){
	var msg = is_newRenew==1?'renew':'new';
	const swalWithBootstrapButtons = Swal.mixin({
		customClass: {
			confirmButton: 'btn btn-success',
			cancelButton: 'btn btn-danger'
		},
		buttonsStyling: false
	})
	swalWithBootstrapButtons.fire({
		title: 'Are you sure?',
		text: "You want to change application type to Renew",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		reverseButtons: true
	}).then((result) => {
			if(result.isConfirmed){
			   $.ajax({
				url :DIR+'business-permit/application/NewRenew', // json datasource
				type: "POST", 
				data: {
				  "id": id,
				  "is_newRenew": is_newRenew,  
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
