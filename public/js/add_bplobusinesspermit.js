$(document).ready(function(){
	$("#saveData").click(function(){
 		saveDeta($(this));
 	})
 	$("#cancel").click(function(){
 		cancel($(this));
 	})
 	$("#Approved").click(function(){
 		Approved($(this));
 	})
 	if($("#issuance_id").val()>0){
		getPermitIsseu();
	}
    $("#uploadAttachment").click(function(){
 		uploadAttachment();
 	});
 	
 	$(".deleteEndrosmentInspections").click(function(){
 		deleteEndrosmentInspections($(this));
 	})
 	$("#btnOrderofPayment").click(function(){
		 $("#orderofpaymentModal").modal('show');
 	});
 	$(".closeOrderModal").click(function(){
		 $("#orderofpaymentModal").modal('hide');
 	});	
 	$("#btnOrderofPayment1").click(function(){
		 $("#orderofpaymentModal2").modal('show');
 	});
 	$(".closeOrderModal2").click(function(){
		 $("#orderofpaymentModal2").modal('hide');
 	});
 	$("#btnOrderofPayment2").click(function(){
		 $("#orderofpaymentModal3").modal('show');
 	});
 	$(".closeOrderModal3").click(function(){
		 $("#orderofpaymentModal3").modal('hide');
 	});
 	$("#btnOrderofPayment3").click(function(){
		 $("#orderofpaymentModal4").modal('show');
 	});
 	$(".closeOrderModal4").click(function(){
		 $("#orderofpaymentModal4").modal('hide');
 	});
});

function deleteEndrosmentInspections(thisval){
	var issuance_id = thisval.attr('issuance_id');
	const swalWithBootstrapButtons = Swal.mixin({
	   customClass: {
		   confirmButton: 'btn btn-success',
		   cancelButton: 'btn btn-danger'
	   },
	   buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
	   text: "Are you sure?",
	   icon: 'warning',
	   showCancelButton: true,
	   confirmButtonText: 'Yes',
	   cancelButtonText: 'No',
	   reverseButtons: true
   }).then((result) => {
	   	if(result.isConfirmed){
	   		showLoader();
		  	$.ajax({
			   url :DIR+'deleteAttachment', // json datasource
			   type: "POST", 
			   data: {
			   	 "issuance_id":issuance_id,
				 "_token": $("#_csrf_token").val(),
			   },
			   success: function(html){
			   	hideLoader();
			   	thisval.closest("tr").remove();
				   Swal.fire({
					 position: 'center',
					 icon: 'success',
					 title: 'Delete Successfully.',
					 showConfirmButton: false,
					 timer: 1500
				   })
			   }
		   })
	   }
   })
}
function uploadAttachment(){
	$(".validate-err").html("");
	if (typeof $('#document_name')[0].files[0]== "undefined") {
		$("#err_document").html("Please upload Document");
		return false;
	}
	var formData = new FormData();
	formData.append('file', $('#document_name')[0].files[0]);
	formData.append('issuance_id', $("#issuance_id").val());
	showLoader();
	$.ajax({
       url : DIR+'uploadAttachment',
       type : 'POST',
       data : formData,
       processData: false,  // tell jQuery not to process the data
       contentType: false,  // tell jQuery not to set contentType
       success : function(data) {
       		hideLoader();
       		var data = JSON.parse(data);
       	    if(data.ESTATUS==1){
       	    	$("#err_end_requirement_id").html(data.message);
       	    }else{
	       	    $("#end_requirement_id").val(0);
	       	    $("#document_name").val(null);
		       	if(data!=""){
		       		$("#DocumentDtls").html(data.documentList);
		       	}
	          	Swal.fire({
					 position: 'center',
					 icon: 'success',
					 title: 'Document uploaded successfully.',
					 showConfirmButton: false,
					 timer: 1500
			    })
			    $(".deleteEndrosmentInspections").unbind("click");
			    $(".deleteEndrosmentInspections").click(function(){
			 		deleteEndrosmentInspections($(this));
			 	})
			}
       }
	});
}

function getPermitIsseu(){
	$('.loadingGIF').show();
	var filtervars = {
	    issuance_id:$("#issuance_id").val()
	}; 
	$.ajax({
	    type: "POST",
	    url: DIR+'getPermitIsseuDetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#permit_no").val(html.bpi_permit_no)
	    	$("#bpi_remarks").val(html.bpi_remarks)
	    	$("#business_plate_no").val(html.business_plate_no)
	    	$("#cancel").hide();
		    $("#Approved").show();
	    	if(html.bpi_issued_status==1){
		    	$("#cancel").show();
		        $("#Approved").hide();	
		    }/*else if(html.bpi_issued_status==2){
		    	$("#cancel").hide();
		        $("#Approved").show();
		    }*/
	    }
	});
}
function saveDeta(thisval){
	var busEndorsementStatus = thisval.val();
	const swalWithBootstrapButtons = Swal.mixin({
	   customClass: {
		   confirmButton: 'btn btn-primary',
		   cancelButton: 'btn btn-dark'
	   },
	   buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
	   text: "Are you sure want to continue?",
	   icon: 'warning',
	   showCancelButton: true,
	   confirmButtonText: 'Yes',
	   cancelButtonText: 'No',
	   reverseButtons: true
   }).then((result) => {
	   	if(result.isConfirmed){
	   		showLoader();
		  	$.ajax({
				url :DIR+'updateBusinessPermit', // json datasource
				type: "POST", 
				dataType: "json",
				data: {
					"issuance_id":$("#issuance_id").val(),
					"id":$("#id").val(),
					"business_plate_no": $("#business_plate_no").val(),
					"bpi_year": $("#bpi_year").val(),
					"bpi_issued_date": $("#bpi_issued_date").val(),
					"bpi_remarks": $("#bpi_remarks").val(),
					
					"_token": $("#_csrf_token").val(),
			   },
			   success: function(html){
			   		hideLoader();
				   if(html.ESTATUS){
					   	Swal.fire({
						 position: 'center',
						 icon: 'success',
						 title: 'Submit Successfully.',
						 showConfirmButton: false,
						 timer: 1500
					   })
					   	
				   		// location.reload(true);
				   }
			   }
		   })
	   }
   })
}

function cancel(thisval){
	var busEndorsementStatus = thisval.val();
	const swalWithBootstrapButtons = Swal.mixin({
	   customClass: {
		   confirmButton: 'btn btn-primary',
		   cancelButton: 'btn btn-dark'
	   },
	   buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
	   text: "Are you sure want to cancel?",
	   icon: 'warning',
	   showCancelButton: true,
	   confirmButtonText: 'Yes',
	   cancelButtonText: 'No',
	   reverseButtons: true
   }).then((result) => {
	   	if(result.isConfirmed){
	   		showLoader();
		  	$.ajax({
				url :DIR+'cancelBusinessPermit', // json datasource
				type: "POST", 
				dataType: "json",
				data: {
					"issuance_id":$("#issuance_id").val(),
					"id":$("#id").val(),
					"bpi_year": $("#bpi_year").val(),
					"_token": $("#_csrf_token").val(),
			   },
			   success: function(html){
			   		hideLoader();
				   if(html.ESTATUS){
					   	Swal.fire({
						 position: 'center',
						 icon: 'success',
						 title: 'Successfully Canceled.',
						 showConfirmButton: false,
						 timer: 1500
					   })
					   updateRemoteServer();
				   		location.reload();
				   }
			   }
		   })
	   }
   })
}
function updateRemoteServer(){
	$.ajax({
        type: "post",
        url: DIR+'api/remoteUpdateBusinessTable',
        data: {
            busn_id:$("#id").val()
        },
        dataType: "json",
        success: function(html){ 
        }
    });
}
function Approved(thisval){
	var busEndorsementStatus = thisval.val();
	const swalWithBootstrapButtons = Swal.mixin({
	   customClass: {
		   confirmButton: 'btn btn-primary',
		   cancelButton: 'btn btn-dark'
	   },
	   buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
	   text: "Business permit will be issued. Are you sure want to continue?",
	   icon: 'warning',
	   showCancelButton: true,
	   confirmButtonText: 'Yes',
	   cancelButtonText: 'No',
	   reverseButtons: true
   }).then((result) => {
	   	if(result.isConfirmed){
	   		showLoader();
		  	$.ajax({
				url :DIR+'approverBusinessPermit', // json datasource
				type: "POST", 
				dataType: "json",
				data: {
					"issuance_id":$("#issuance_id").val(),
					"app_type_id":$("#app_type_id").val(),
					"id":$("#id").val(),
					"bpi_year": $("#bpi_year").val(),
					"_token": $("#_csrf_token").val(),
			   },
			   success: function(html){
			   		hideLoader();
				   if(html.ESTATUS){
					   	Swal.fire({
						 position: 'center',
						 icon: 'success',
						 title: 'Submit Successfully.',
						 showConfirmButton: false,
						 timer: 1500
					   })
					   	updateRemoteServer();
				   		location.reload();
				   }
			   }
		   })
	   }
   })
}

