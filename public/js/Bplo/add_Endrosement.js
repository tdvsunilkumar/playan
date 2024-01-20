$(document).ready(function(){
	$(".numeric").numeric({ decimal : "." });
	$('#end_requirement_id').select3({dropdownAutoWidth : false,dropdownParent: $("#flush-collapse3")});
	$("#btnAssessment").click(function(){
		getAssesmentDetails();
		
 	});	
 	$("#uploadAttachment").click(function(){
 		uploadAttachment();
 	});
 	$("#uploadAttachmentInspection").click(function(){
 		uploadAttachmentInspection();
 	});
 	$(".deleteEndrosment").click(function(){
 		deleteEndrosment($(this));
 	})
 	$(".deleteEndrosmentInspections").click(function(){
 		deleteEndrosmentInspections($(this));
 	})
 	$(".saveData").click(function(){
 		saveDeta($(this));
 	})
});
function saveDeta(thisval){
	var busEndorsementStatus = thisval.val();
	// alert(busEndorsementStatus);
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
				url :DIR+'Endrosement/updateEndorsementStatus', // json datasource
				type: "POST", 
				dataType: "json",
				data: {
					"busEndorsementStatus":busEndorsementStatus,
					"id":$("#id").val(),
					"year":$("#bend_year").val(),
					"app_code":$("#app_code").val(),
					"payment_mode":$("#payment_mode").val(),
					"end_tfoc_id": $("#end_tfoc_id").val(), 
					"enddept_fee": $("#enddept_fee").val(),
					"end_fee_name":$("#end_fee_name").val(),
					"prev_bend_status":$("#bend_status").val(),
					"bbendo_id":$("#bbendo_id").val(),
					"_token": $("#_csrf_token").val(),
			   },

			   success: function(html){
			   		hideLoader();
			   		if(busEndorsementStatus == 1){
			   			var title	='Saved Successfully.'
			   		}
			   		if(busEndorsementStatus == 2){
			   			var title	='update Successfully.'
			   		}
			   		if(busEndorsementStatus == 3){
			   			var title	='Declined Successfully.'
			   		}
			   		if(busEndorsementStatus == 'restore'){
			   			var title	='Restored Successfully.'
			   		}
			   		if(busEndorsementStatus == 'incomplete'){
			   			var title	='Update Successfully.'
			   		}
					
				   if(html.ESTATUS){
					   	Swal.fire({
						 position: 'center',
						 icon: 'success',
						 title: title,
						 showConfirmButton: false,
						 timer: 1500
					    })
					    if(html.busn_app_status==3){
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
				   		location.reload(true);
				   }
				   
			   	}
		   })
	   }
   })
}

function deleteEndrosment(thisval){
	var rid = thisval.attr('rid');
	var eid = thisval.attr('eid');
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
			   url :DIR+'Endrosement/deleteAttachment', // json datasource
			   type: "POST", 
			   data: {
					"id":$("#id").val(),
					"year":$("#bend_year").val(),
					"rid": rid,
					"eid": eid,  
					"_token": $("#_csrf_token").val(),
			   },
			   success: function(html){
			   	hideLoader();
			   	thisval.closest("tr").remove();
				   Swal.fire({
					 position: 'center',
					 icon: 'success',
					 title: 'Deleted Successfully.',
					 showConfirmButton: false,
					 timer: 1500
				   })
			   }
		   })
	   }
   })
}

function deleteEndrosmentInspections(thisval){
	var rid = thisval.attr('rid');
	var bbendo_id = thisval.attr('bbendo_id');
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
			   url :DIR+'Endrosement/deleteEndrosmentInspectionAttachment', // json datasource
			   type: "POST", 
			   data: {
			   	 "id":$("#id").val(),
				 "year":$("#bend_year").val(),
				 "rid": rid,
				 "bbendo_id": bbendo_id,  
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

function getAssesmentDetails(){
	var id =$("#id").val();
	var end_tfoc_id = $("#end_tfoc_id").val();
	var enddept_fee = $("#enddept_fee").val();
	var end_fee_name = $("#end_fee_name").val();
	showLoader();
    $.ajax({
        url :DIR+'Endrosement/assessmentDetails', // json datasource
        type: "POST", 
        dataType: "html", 
        data: {
          "id": id, 
          "year":$("#bend_year").val(),
          "app_code":$("#app_code").val(),
          "bbendo_id":$("#bbendo_id").val(),
          "end_tfoc_id": end_tfoc_id, 
          "enddept_fee": enddept_fee, 
          "end_fee_name":end_fee_name,
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
        	hideLoader();
        	$("#feeDetails").html(html)
            $("#assessmentModal").modal('show');
            if ($('.showLess')) {
	            $('.showLess').shorten({
	                "showChars" : 0,
	                "moreText"	: "More",
	                "lessText"	: "Less"
	            });
	        }
        }
    })
}

function uploadAttachment(){
	$(".validate-err").html("");
	if($("#end_requirement_id").val()==0){
		$("#err_end_requirement_id").html("Please select requirement");
		return false;
	}else if (typeof $('#document_name')[0].files[0]== "undefined") {
		$("#err_document").html("Please upload Document");
		return false;
	}
	var formData = new FormData();
	formData.append('file', $('#document_name')[0].files[0]);
	formData.append('busn_id', $("#id").val());
	formData.append('bbendo_id', $("#bbendo_id").val());
	formData.append('year', $("#bend_year").val());
	formData.append('end_requirement_name', $("#end_requirement_id option:selected").text());
	formData.append('end_requirement_id', $("#end_requirement_id").val());
	showLoader();
	$.ajax({
       url : DIR+'Endrosement/uploadDocument',
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
			    $(".deleteEndrosment").unbind("click");
			    $(".deleteEndrosment").click(function(){
			 		deleteEndrosment($(this));
			 	})
			}
       }
	});
}

function uploadAttachmentInspection(){
	$(".validate-err").html("");
	if (typeof $('#document_name')[0].files[0]== "undefined") {
		$("#err_document").html("Please upload Document");
		return false;
	}
	var formData = new FormData();
	formData.append('file', $('#document_name')[0].files[0]);
	formData.append('busn_id', $("#id").val());
	formData.append('bbendo_id', $("#bbendo_id").val());
	formData.append('year', $("#bend_year").val());
	showLoader();
	$.ajax({
       url : DIR+'Endrosement/uploadAttachmentInspection',
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
