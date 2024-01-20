$(document).ready(function(){
	 var requisition = function() {
        this.$body = $("body");
    };
	$(".numeric").numeric({ decimal : "." });
	$('#bri_issued_by').select3({dropdownAutoWidth : false,dropdownParent: $("#accordionFlushExample")});

 	$("#uploadAttachmentbtn").click(function(){
 		uploadAttachment();
 	});
 	$(".deleteDocument").click(function(){
 		deleteRequirement($(this));
 	})
 	$("#savedata").click(function(){
 		$("#err_bri_issued_by").text('');
 		if($("#bri_issued_by").val()=="" || $("#position").val()==""){
 			$("#err_bri_issued_by").text("Busness Permit Personnel is required");
 		}else{
 			saveDeta($(this));
 		}
 	})
 	$(".updatestatus").click(function(){
 			updatestatus($(this));
 		
 	})
 	$("#bri_issued_by").change(function(){
		var id=$(this).val();
		getApproval_By(id)
		
	});
	$('input[type=checkbox]').attr('disabled', true);
});

function getApproval_By(id){
	var filtervars = {id:id};
	$.ajax({
	    type: "GET",
	    url: DIR+'bfpinspectionorder/getPosition',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){
	    	$("#position").val(html.description);
	    }
	}); 
}

function saveDeta(thisval){
	var busEndorsementStatus = thisval.val();
	var id = $("#id").val();
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
				url :DIR+'bplo-retirementcertificate/updateremark', // json datasource
				type: "POST", 
				dataType: "json",
				data: {
					"remark":$("#remark").val(),
					"bri_issued_by":$("#bri_issued_by").val(),
					"position":$("#position").val(),
					"bri_issued_byold":$("#bri_issued_byold").val(),
					"busn_id":$("#busn_id").val(),
					"retire_year":$("#retire_year").val(),
					"id":$("#id").val(),
					"_token": $("#_csrf_token").val(),
			   },
			   success: function(html){
			   	hideLoader();
				   if(html.ESTATUS){
					   	Swal.fire({
						 position: 'center',
						 icon: 'success',
						 title: 'Update Successfully.',
						 showConfirmButton: false,
						 timer: 1500
					   })
					   	
				   		location.reload(true);
				   }
			   }
		    })
	    }
   })
}

function updatestatus(thisval){
	var status = thisval.val();
	var certificateid = $("#id").val();
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
				url :DIR+'bplo-retirementcertificate/updatestatus', // json datasource
				type: "POST", 
				dataType: "json",
				data: {
					"busn_id":$("#busn_id").val(),
					"certificateid":certificateid,
					"status":status,
					"_token": $("#_csrf_token").val(),
			   },
			   success: function(html){
			   	hideLoader();
				   if(html.ESTATUS){
					   	Swal.fire({
						 position: 'center',
						 icon: 'success',
						 title: 'Update Successfully.',
						 showConfirmButton: false,
						 timer: 1500
					   })
					   	
				   		location.reload(true);
				   }
			   }
		    })
	    }
   })
}

function deleteRequirement(thisval){
	var rid = thisval.attr('rid');
	var id = thisval.attr('id');
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
			   url :DIR+'bplo-retirementcertificate/deleteAttachment', // json datasource
			   type: "POST", 
			   data: {
				 "rid": rid,
				 "id": id,  
				 "_token": $("#_csrf_token").val(),
			   },
			   success: function(html){
			   	hideLoader();
			   	thisval.closest("tr").remove();
				   Swal.fire({
					 position: 'center',
					 icon: 'success',
					 title: 'Update Successfully.',
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
	 if (typeof $('#documentname')[0].files[0]== "undefined") {
		$("#err_document").html("Please upload Document");
		return false;
	}
	var formData = new FormData();
	formData.append('file', $('#documentname')[0].files[0]);
	formData.append('id', $("#id").val());
	showLoader();
	$.ajax({
       url : DIR+'bplo-retirementcertificate/uploadDocument',
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
	       	    $("#documentname").val(null);
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
			    $(".deleteDocument").unbind("click");
			    $(".deleteDocument").click(function(){
			 		deleteRequirement($(this));
			 	})
			}
       }
	});
}
