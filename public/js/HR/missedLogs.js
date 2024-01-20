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
		"columnDefs": [{ orderable: false, targets: [0,6,11] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'hr-missed-logs/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				"apv":0,
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
            { "data": "srno" },
            { "data": "applicationno" },
			{ "data": "emp_name" },
			{ "data": "filed_date" },
        	{ "data": "work_date" },
        	{ "data": "time" },
        	{ "data": "log_type" },
        	{ "data": "reason" },
            { "data": "status" },
        	{ "data": "apv_by" },
        	{ "data": "review_by" },
        	{ "data": "noted_by" },
        	{ "data": "action" }
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	         $("#common_pagesize").html(dropdown_html);
			api.$('.remove').click(function() {
				var rowId = $(this).attr('id');
				 removeMissedLog(rowId);
	
			});
	    }
	});  
}

function removeMissedLog(id){
	// alert(id);
	// alert(is_activeinactive);
   const swalWithBootstrapButtons = Swal.mixin({
	   customClass: {
		   confirmButton: 'btn btn-success',
		   cancelButton: 'btn btn-danger'
	   },
	   buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
	   title: 'Are you sure?',
	   text: "You want to Remove?",
	   icon: 'warning',
	   showCancelButton: true,
	   confirmButtonText: 'Yes',
	   cancelButtonText: 'No',
	   reverseButtons: true
   }).then((result) => {
	   if(result.isConfirmed)
	   {
		  $.ajax({
			   url :DIR+'hr-missed-logs/RemoveMissedLog', // json datasource
			   type: "POST", 
			   data: {
				 "id": id,
				 "_token": $("#_csrf_token").val(),
			   },
			   success: function(html){
				var response = JSON.parse(html);
				console.log(response.icon);
				   Swal.fire({
					 position: 'center',
					 icon: response.icon,
					 title: response.title,
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
