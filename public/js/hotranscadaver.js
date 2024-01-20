$(document).ready(function(){
	
    datatablefunction();
   
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
						{ orderable: false, targets: 5 },
						{ orderable: true, targets: 6 },
						{ orderable: false, targets: 7 },
						],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'civil-registrar/permits/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
		
			{ "data": "no"},
       	    { "data": "service_name" },
        	{ "data": "fullname" },
        	{ "data": "brgy_name" },
			{ "data": "or_no" },
            { "data": "issue_date" },
			{ "data": "status" },
			{ "data": "action" }
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	       $("#common_pagesize").html(dropdown_html);
	        api.$('.deleterow').click(function() {
	            var hocadaverid = $(this).attr('id');
	            DeleteRecord(hocadaverid);
	        });
	        api.$('.activeinactive').click(function() {
				var hocadaverid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
				 ActiveInactiveUpdate(hocadaverid,is_activeinactive);
	
			});
	    }
	});  
}




function ActiveInactiveUpdate(id,is_activeinactive){
	// alert(id);
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
	   if(result.isConfirmed)
	   {
		  $.ajax({
			   url :DIR+'civil-registrar/permits/ActiveInactive', // json datasource
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
