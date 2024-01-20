$(document).ready(function(){	
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	

	// $(document).on('change','.agl_id', function() {
	// 	var id=$(this).val();
	// 	  getdataagl(id,$(this));
	//  });
	//  if($("#agl_id0").val()>0){
	// 	   getdataagl($("#agl_id0").val());
	// 	 }
	
});



	
	// function getdataagl(id, selectedElement){
	// 	//console.log(selectedElement);
	// 	$('.loadingGIF').show();
	// 	var filtervars = {
	// 		id:id
	// 	}; 
	// 	$.ajax({
	// 		type: "GET",
	// 		url: DIR+'getdata',
	// 		data: filtervars,
	  
	// 		dataType: "json",
	// 		success: function(html){ 
	// 			selectedElement.closest('.removenaturedata').find('.description').val(html.description);
	// 		  $('.loadingGIF').hide();
	// 		  //$("#description0").val(html.description)
			  
	// 		}
	// 	});
	//   }


// $('#updateAdjustcode').on('click',function(){
// 	var aglcode       		= $('#agl_id').val();
// 	var quarter             =$('#bud_budget_quarter').val();
// 	var anual               =$('#bud_budget_annual').val();
// 	var propertyId         = $('#selectedPropertyId').val();
	
// 	$.ajax({
// 		url :DIR+'cbobudget/Adjust', // json datasource
// 		type: "POST", 
// 		data: {
// 		  "id": propertyId,
// 		  "aglcode": aglcode,
// 		  "quarter": quarter, 
// 		  "anual": anual,  
// 		  "_token": $("#_csrf_token").val(),
// 		},
// 		success: function(html){
// 			Swal.fire({
// 			  position: 'center',
// 			  icon: 'success',
// 			  title: 'Adjust Budget Successfully.',
// 			  showConfirmButton: false,
// 			  timer: 1500
// 			})
// 		   location.reload();
// 		}
// 	})
//  });


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
		"columnDefs": [{ orderable: false, targets: [7] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'cbobudget/getList', // json datasource
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
			{ "data": "bud_year" },
			{ "data": "dept_id" },
			{ "data": "ddiv_id" },
			{ "data": "bud_budget_quarter" },
            { "data": "bud_budget_annual" },
			// { "data": "agl_id" },
        	// { "data": "description" },
        	// { "data": "ddiv_id" },
        	// { "data": "fc_code" },
        	//
        	
            // { "data": "bud_budget_total" },
            // { "data": "bud_is_locked" },
			// { "data": "bud_generated_by" },
            // { "data": "bud_approved_by" },
           
            // { "data": "bud_disapproved_by" },
			
            { "data": "budget_status" },
			// { "data": "next_step" },
        	// { "data": "is_active" },
			
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
			   url :DIR+'cbobudget/ActiveInactive', // json datasource
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
				  location.reload();
			   }
		   })
	   }
   })
}

function loadMainForm(url, title, size) {
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
	$("#commonModal").modal('show');

    $.ajax({
        url: url,
        success: function (data) {
            $('#commonModal .body').html(data);
            
            // daterange_set();
            taskCheckbox();
            common_bind("#commonModal");
            commonLoader();
        },
        error: function (data) {
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
}


function DeleteRecord(id){
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
			        url :DIR+'cbobudget/delete', // json datasource
			        type: "POST", 
			        data: {
			          "id": id, 
			          "_token": $("#_csrf_token").val(),
			        },
			        success: function(html){
			        	Swal.fire({
	    				  position: 'center',
	    				  icon: 'success',
	    				  title: 'Budget Deleted Successfully.',
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
