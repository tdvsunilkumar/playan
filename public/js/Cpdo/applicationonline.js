$(document).ready(function(){	
	//select3Ajax("flt_busn_office_barangay","multiCollapseExample1","getBarngayList");
	$("#flt_busn_office_barangay").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
	$("#flt_Status").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});
 	 $(document).on('click','.closeReqModal',function(){
          $('#showrequiremets').modal('hide');
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
		"columnDefs": [{ orderable: false, targets: [0,5] },{ orderable: false, targets: [0,6] },{ orderable: false, targets: [0,7] },{ orderable: false, targets: [0,8] },{ orderable: false, targets: [0,9] },{ orderable: false, targets: [0,10] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'online-cpdoapplication/getList', // json datasource
			type: "POST", 
			"data": {
				"q":$("#q").val(),
				"barangay":$("#flt_busn_office_barangay").val(),
				"fromdate":$("#from_date").val(),
				"todate":$("#to_date").val(),
				"status":$("#flt_Status").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
            { "data": "srno" },
			{ "data": "caf_control_no" },
			{ "data": "ownername" },
        	{ "data": "projectname" },
        	{ "data": "address" },
        	{ "data": "sdetail" },
        	{ "data": "status" },
        	{ "data": "date" },
        	{ "data": "method" },
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
			api.$('.viewreq').click(function() {
				var recordid = $(this).attr('id');
				 viewrequirements(recordid);
	
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
			        url :DIR+'engservice/delete', // json datasource
			        type: "POST", 
			        data: {
			          "id": id, 
			          "_token": $("#_csrf_token").val(),
			        },
			        success: function(html){
			        	Swal.fire({
	    				  position: 'center',
	    				  icon: 'success',
	    				  title: 'Building Roofing Deleted Successfully.',
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
			   url :DIR+'cpdoapplication/ActiveInactive', // json datasource
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
