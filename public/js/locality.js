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
		"columnDefs": [{ orderable: false, targets: [15] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'locality/getList', // json datasource
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
        	{ "data": "loc_local_code" },
        	{ "data": "loc_address" },
        	{ "data": "loc_telephone_no" },
        	{ "data": "loc_fax_no" },
            { "data": "loc_mayor" },
        	{ "data": "loc_administrator_name" },
        	{ "data": "loc_budget_officer_name" },
        	{ "data": "loc_budget_officer_position" },
        	{ "data": "loc_treasurer_name" },
            { "data": "loc_treasurer_position" },
        	// { "data": "loc_chief_land_tax" },
        	// { "data": "loc_chief_land_tax_position" },
        	{ "data": "loc_assessor_name" },
            { "data": "loc_assessor_position" },
        	{ "data": "loc_assessor_assistant_name" },
        	
        	{ "data": "loc_assessor_assistant_position" },
        	{ "data": "asment_id" },
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
			api.$('.activeinactive').click(function() {
				var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
				 ActiveInactiveUpdate(recordid,is_activeinactive);
	
			});
	    }
	});  
}
function DeleteRecord(id){
	alert(id);
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
            if(result.isConfirmed
            )
            {
               $.ajax({
			        url :DIR+'locality/delete', // json datasource
			        type: "POST", 
			        data: {
			          "id": id, 
			          "_token": $("#_csrf_token").val(),
			        },
			        success: function(html){
			        	Swal.fire({
	    				  position: 'center',
	    				  icon: 'success',
	    				  title: 'Business Tax Deleted Successfully.',
	    				  showConfirmButton: false,
	    				  timer: 1500
	    				})
			           location.reload();
			        }
			    })
            }
        })
}

function ActiveInactiveUpdate(id,is_activeinactive){
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
	   text: "You want to Active/InActive?",
	   icon: 'warning',
	   showCancelButton: true,
	   confirmButtonText: 'Yes',
	   cancelButtonText: 'No',
	   reverseButtons: true
   }).then((result) => {
	   if(result.isConfirmed)
	   {
		  $.ajax({
			   url :DIR+'locality/ActiveInactive', // json datasource
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
				  // Reload the page after a short delay
					setTimeout(function() {
						location.reload(); // Reloads the current page
					}, 2000); // 2000 milliseconds (2 seconds)
			   }
		   })
	   }
   })
}
