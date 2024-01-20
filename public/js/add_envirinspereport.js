$(document).ready(function () {
	select3Ajax("ebir_inspected_by","ebir_inspected_byparrent","getClientsBfpAjax");
  select3Ajax("ebir_approved_by","ebir_approved_byparrent","getClientsBfpAjax");
  // $("#ebir_inspected_by").select3({dropdownAutoWidth : false,dropdownParent: $("#ebir_inspected_byparrent")});
  // $("#ebir_approved_by").select3({dropdownAutoWidth : false,dropdownParent: $("#ebir_approved_byparrent")});

  $("#uploadAttachmentInspection").click(function(){
	uploadAttachmentInspection();
  });
  $(".deleteEndrosmentInspections").click(function(){
 		deleteEndrosmentInspections($(this));
  })
  $('#ebir_inspected_by').on('change', function() {
 		var id =$(this).val();
 		var posid= "position";
 		Getposition(id,posid);	
  })
 /*  if($("#ebir_inspected_by option:selected").val() > 0 ){
    var id = $("#ebir_inspected_by option:selected").val();
    var posid= "position";
    Getposition(id,posid);	
  } */

  
  $('#ebir_approved_by').on('change', function() {
 		var id =$(this).val();
 		var posid= "position";
 		Getposition2(id,posid);	
  })
  /* if($("#ebir_approved_by option:selected").val() > 0 ){
    var id = $("#ebir_approved_by option:selected").val();
    var posid= "position";
    Getposition2(id,posid);	
  } */

  
  $("#btnPrintclearance").click(function(){
    var url =$(this).val();
    window.open(url, '_blank');
  })
})
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
			   url :DIR+'environmental/deleteEndrosmentInspectionAttachment', // json datasource
			   type: "POST", 
			   data: {
			   	 "id":$("#busn_id").val(),
				 "year":$("#ebir_year").val(),
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
	formData.append('busn_id', $("#busn_id").val());
	formData.append('bend_id', $("#bend_id").val());
	formData.append('ebir_year', $("#ebir_year").val());
	showLoader();
	$.ajax({
       url : DIR+'environmental/uploadAttachmentInspection',
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
function Getposition(id,posid){
  var filtervars = {
    id:id,
    posid:posid,
    "_token": $("#_csrf_token").val()
  }; 
  $.ajax({
    type: "POST",
    url: DIR+'environmental-positionbyid',
    data: filtervars,
    dataType: "html",
    success: function(html){ 
      hideLoader();
      $("#ebir_inspector_position").val(html);
    },error:function(){
      hideLoader();
    }
  });
}

function Getposition2(id,posid){
  var filtervars = {
    id:id,
    posid:posid,
    "_token": $("#_csrf_token").val()
  }; 
  $.ajax({
    type: "POST",
    url: DIR+'environmental-positionbyid',
    data: filtervars,
    dataType: "html",
    success: function(html){ 
      hideLoader();
      $("#ebir_approver_position").val(html);
    },error:function(){
      hideLoader();
    }
  });
}

$('#ebir_inspected_status').bind('change', function (){
   if($(this).is(':checked')){
		$( "#ebir_inspected_status").prop('checked', true);
	}else{
		$( "#ebir_inspected_status").prop('checked', false);
	}
});

/* $('#ebir_inspected_status').bind('change', function (){
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
			$( "#ebir_inspected_status").prop('checked', true);
		}else{
			$( "#ebir_inspected_status").prop('checked', false);
		}
	})
   }
}); */