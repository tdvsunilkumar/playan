$(document).ready(function(){
	$("#bio_assigned_to").select3({dropdownAutoWidth : false,dropdownParent: $("#bioassignedparrent")});
	$("#bio_recommending_approval").select3({dropdownAutoWidth : false,dropdownParent: $("#bioapprovalparrent")});
	$("#bio_approved").select3({dropdownAutoWidth : false,dropdownParent: $("#bioapprovedparrent")});
	
	$("#uploadAttachmentInspection").click(function(){
		uploadAttachmentInspection();
	  });
	  $(".deleteEndrosmentInspections").click(function(){
			deleteEndrosmentInspections($(this));
	  })
	
	$("#bff_code").change(function(){
		var id=$(this).val();
		if(id){
			getAccounrnumber(id);
		}
	})
	if($("#bff_code").val()>0){
		getAccounrnumber($("#bff_code").val());
		$("#banldetail").removeClass('hide');
	}
	
	$("#busn_id").change(function(){
		var id=$(this).val();
		if(id){ getBusiness(id); }
	})
	if($("#busn_id").val()>0){
		getBusiness($("#busn_id").val());
		
	}
	$('#bio_inspection_region').on('change', function() {
        getprofileRegioncode($(this).val());
    });
	
    if($("#bio_inspection_region").val()>0){
		getprofileRegioncode($("#bio_inspection_region").val());
		
	}
	$("#bio_recommending_approval").change(function(){
		var id=$(this).val();
		getPositionApprover(id)
	});
	 
	$("#bio_approved").change(function(){
		var id=$(this).val();
		getApproval_By(id)
		
	});
	
	$('.printsss').click(function() {
		var id = $(this).attr('id');
        inspectionPrints(id);
	});
	
/* 	$('#bio_approved_status').bind('change', function (){
	   if($(this).is(':checked')){
		
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
			if(result.isConfirmed)
			{
				var id  =$('#id').val();
				var bio_approved = $('#bio_approved').val();
				var filtervars = {id:id,bio_approved:bio_approved};
				$.ajax({
					type: "post",
					url: DIR+'bfpinspectionorder-approvedsataus',
					data: filtervars,
					dataType: "json",
					success: function(html){
						$( "#bio_approved_status").prop('checked', true);
					}
				});
			}else{
				$( "#bio_approved_status").prop('checked', false);
			}
		})
		
	   }
	}); */
	
	/* $('#bio_recommending_status').bind('change', function () {
		if($(this).is(':checked')){
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
			if(result.isConfirmed)
			{
			    var id  =$('#id').val();
				var bio_recommending_approval = $('#bio_recommending_approval').val();
				var filtervars = {id:id,bio_recommending_approval:bio_recommending_approval};
				$.ajax({
					type: "post",
					url: DIR+'bfpinspectionorder-biorecommendingapproval',
					data: filtervars,
					dataType: "json",
					success: function(html){
						$( "#bio_recommending_status").prop('checked', true);
					}
				});
			}else{
				$( "#bio_recommending_status").prop('checked', false);
			}
		})
	   }
	}); */
});
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
			   url :DIR+'bfpinspectionorder/deleteEndrosmentInspectionAttachment', // json datasource
			   type: "POST", 
			   data: {
			   	 "id":$("#busn_ids").val(),
				 "year":$("#bio_year").val(),
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
function uploadAttachmentInspection(){
	$(".validate-err").html("");
	if (typeof $('#document_name')[0].files[0]== "undefined") {
		$("#err_document").html("Please upload Document");
		return false;
	}
	var formData = new FormData();
	formData.append('file', $('#document_name')[0].files[0]);
	formData.append('busn_id', $("#busn_ids").val());
	formData.append('bend_id', $("#bend_ids").val());
	formData.append('bio_year', $("#bio_year").val());
	showLoader();
	$.ajax({
       url : DIR+'bfpinspectionorder/uploadAttachmentInspection',
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
function getPositionApprover(id){
	var filtervars = {id:id};
	$.ajax({
	    type: "GET",
	    url: DIR+'bfpinspectionorder/getPosition',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){
	    	$("#bio_recommending_position").val(html.description);
	    }
	}); 
}
function getApproval_By(id){
	var filtervars = {id:id};
	$.ajax({
	    type: "GET",
	    url: DIR+'bfpinspectionorder/getPosition',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){
	    	$("#bio_approved_position").val(html.description);
	    }
	}); 
}



function recommendingapproval(){
    $.ajax({
        url :DIR+'getprofileRegioncodeId', // json datasource
        type: "POST", 
        data: {
          "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#bio_province").html(html);
          }
        }
    })
}


function getprofileRegioncode(id){
    $.ajax({
        url :DIR+'getprofileRegioncodeId', // json datasource
        type: "POST", 
        data: {
          "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#bio_province").html(html);
          }
        }
    })
}

function formatNumber(nStr){
 nStr += '';
 var x = nStr.split('.');
 var x1 = x[0];
 var x2 = x.length > 1 ? '.' + x[1] : '';
 var rgx = /(\d+)(\d{3})/;
 while (rgx.test(x1)) {
  x1 = x1.replace(rgx, '$1' + ',' + '$2');
 }
 return x1 + x2;
}

function getAccounrnumber(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	};
	$.ajax({
	    type: "GET",
	    url: DIR+'getAccounrnumber',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#ba_business_account_no").val(html.ba_business_account_no);
	    	$("#p_code").val(html.p_code);
	    	$("#ba_code").val(html.ba_code);
	    }
	}); 
}

function getBusiness(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	};
	$.ajax({
	    type: "GET",
	    url: DIR+'getBusinessId',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#bend_id").val(html.id);
	    	
	    }
	}); 
}

function inspectionPrints(id){
  var id = id;
  $.ajax({
	url: DIR+'inspectionPrint',
	type: 'POST',
	data: {
		"id": id, "_token": $("#_csrf_token").val(),
	},
	success: function (data) {
	   var url = data;
	   console.log(url);
		window.open(url, '_blank');
	}
  });
}

