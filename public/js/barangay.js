$(document).ready(function(){	
	select3Ajax("barangayId","this_is_filter","getBarngayMunProvRegionList");
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
 	$("#barangayId").change(function(){
 		datatablefunction();
 	})
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
		"columnDefs": [{ orderable: false, targets: [0,7] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'barangay/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				'barangayId':$('#barangayId').val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
			{ "data": "srno" },
        	{ "data": "reg_region" },
        	{ "data": "prov_desc" },
        	{ "data": "mun_desc" },
        	{ "data": "brgy_code" },
        	{ "data": "brgy_name" },
        	{ "data": "brgy_office" },
        	{ "data": "brgy_display_for_bplo" },
        	{ "data": "brgy_display_for_rpt" },
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
			api.$(".copybrgyId").click(function () {
		        // Select the text in the input field
		        var elementId = $(this).attr('bid');
		       	 // Create a "hidden" input
				  var aux = document.createElement("input");
				  // Assign it the value of the specified element
				  aux.setAttribute("value", elementId);

				  // Append it to the body
				  document.body.appendChild(aux);

				  // Highlight its content
				  aux.select();

				  // Copy the highlighted text
				  document.execCommand("copy");

				  $(this).html('Copied!');
				  // // Remove it from the body
				  // document.body.removeChild(aux);

		    });
	    }
	});  
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
			   url :DIR+'barangay/ActiveInactive', // json datasource
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