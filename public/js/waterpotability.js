$(document).ready(function(){	
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
	 $("#business_id").change(function(){
		var business_id=$(this).val();
		getBrgyDetails(business_id);
	});
	if($("#business_id option:selected").val() > 0 ){
		 var business_id = $("#business_id option:selected").val();
		getBrgyDetails(business_id);
	}
	$("#inspected_by").change(function(){
		var inspected_by=$(this).val();
		getInsPosDetails(inspected_by);
	});
	$("#approved_by").change(function(){
		var approved_by=$(this).val();
		getAppPosDetails(approved_by);
	});
	$("#or_no").select3({dropdownAutoWidth : false,dropdownParent: $("#or_no-group")});

	$('#contain_requestor_id').on('change', '#requestor_id', function (e) {
        var id = $(this).val();
        select3Ajax('or_no','or_no-group','healthy-and-safety/water-potability/get-or-list/'+id);
    });

	$('#or_no').change(function (e) {
		// $('#amount_show').val(text_loader);
		
		$.ajax({
			type: "get",
			url: DIR+"healthy-and-safety/water-potability/get-or-no/"+$(this).val(),
			success: function (response) {
				if(response.status == 200){
					if(response.data != null){
						$('#amount_show').val(response.data.tfc_amount);
						$('#or_amount').val(response.data.tfc_amount);
						$('#or_date').val(response.data.cashier_or_date);
						$('#or_date_show').val(response.data.cashier_or_date);
						$('#cashierd_id').val(response.data.cashierd_id);
						$('#cashier_id').val(response.data.cashier_id);
					}else{
						$('#amount_show').val(0);
					}
				}
			},error(error){
				$('#postion_show').val('');
				$('#or_date').val('');
				$('#or_amount').val('');
			}
		});
	});

	$('#is_free').change(function (e) {
		if($('#is_free').not(':checked').length){
			// $('.or_no').prop('required', true);
			$('.or_no_star').show();

			$('#select3-or_no-container').text('Select OR No')

			$('#or_no').prop('disabled', false);
		}else{
			// $('.or_no').prop('required', false);
			$('.or_no_star').hide();

			$('#or_no').find('option').removeAttr("selected");
			$('#or_no').prop('disabled', true);
			$('#or_date_show').val('');
			$('#amount_show').val('0.00');
			$('#select3-or_no-container').text('Free')
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
		"columnDefs": [{ orderable: false, targets: [0,3] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'healthy-and-safety/water-potability/getList', // json datasource
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
        	{ "data": "name" },
            { "data": "address" },
            { "data": "cert_no" },
        	{ "data": "or_no" },
            /* { "data": "or_date" },
        	{ "data": "or_amount" },
            { "data": "start_date" },
            { "data": "end_date" },
			{ "data": "requestor_name" }, */
        	{ "data": "issuance_date" },
            { "data": "inspected_by" },
        	/* { "data": "inspector_position" },
            { "data": "approved_by" },
            { "data": "approver_position" },
        	{ "data": "approval" },
            { "data": "free" }, */
        	{ "data": "status" },
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

function getBrgyDetails(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	};
	$.ajax({
	    type: "GET",
	    url: DIR+'healthy-and-safety/water-potability/getBrgyDetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#address").val(html.addreass);
			$("#brgy_id").val(html.brgy_id);
	    }
	}); 
}
function getInsPosDetails(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	};
	$.ajax({
	    type: "GET",
	    url: DIR+'healthy-and-safety/water-potability/getInsPosDetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#inspector_position").val(html.inspector_position);
	    }
	}); 
}
function getAppPosDetails(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	};
	$.ajax({
	    type: "GET",
	    url: DIR+'healthy-and-safety/water-potability/getAppPosDetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#approver_position").val(html.approver_position);
	    }
	}); 
}




function DeleteRecord(id){
	// alert(id);
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
			        url :DIR+'healthy-and-safety/howaterpotability/delete', // json datasource
			        type: "POST", 
			        data: {
			          "id": id, 
			          "_token": $("#_csrf_token").val(),
			        },
			        success: function(html){
			        	Swal.fire({
	    				  position: 'center',
	    				  icon: 'success',
	    				  title: 'Hema Range Deleted Successfully.',
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
			   url :DIR+'healthy-and-safety/water-potability/ActiveInactive', // json datasource
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
