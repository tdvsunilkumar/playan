$(document).ready(function () {
  $("#uploadAttachmentInspection").click(function(){
	uploadAttachmentInspection();
  });
  $(".deleteEndrosmentInspections").click(function(){
 		deleteEndrosmentInspections($(this));
  })
  select3Ajax("pend_approved_by","pendapprovedbyparrento","getClientsBfpAjax");
  select3Ajax("pend_inspected_by","pendapprovedbyparrent","getClientsBfpAjax");
  // $("#pend_approved_by").select3({dropdownAutoWidth : false,dropdownParent: $("#pendapprovedbyparrent")});
  $('#pend_inspected_by').on('change', function() {
 		var id =$(this).val();
 		var posid= "pend_inspected_officer_position";
 		Getpositioninspected(id,posid);	
  })
  $('#pend_approved_by').on('change', function() {
 		var id =$(this).val();
 		var posid= "position";
 		Getposition(id,posid);	
  })
   $('#orno').on('change', function() {
 		var id =$(this).val();
 		if(id > 0){
 			Getordetails(id);	
 		}
  })

 /*  if($("#pend_approved_by option:selected").val() > 0 ){
    var id = $("#pend_approved_by option:selected").val();
    var posid= "position";
    Getposition(id,posid);	
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
			   url :DIR+'locationclearance/deleteEndrosmentInspectionAttachment', // json datasource
			   type: "POST", 
			   data: {
			   	 "id":$("#busn_id").val(),
				 "year":$("#pend_year").val(),
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
	formData.append('pend_year', $("#pend_year").val());
	showLoader();
	$.ajax({
       url : DIR+'locationclearance/uploadAttachmentInspection',
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
function Getpositioninspected(id,posid){
  var filtervars = {
    id:id,
    posid:posid,
    "_token": $("#_csrf_token").val()
  }; 
  $.ajax({
    type: "POST",
    url: DIR+'locationclearance-positionbyid',
    data: filtervars,
    dataType: "html",
    success: function(html){ 
      hideLoader();
      $("#"+posid).val(html);
    },error:function(){
      hideLoader();
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
    url: DIR+'locationclearance-positionbyid',
    data: filtervars,
    dataType: "html",
    success: function(html){ 
      hideLoader();
      $("#"+posid).val(html);
    },error:function(){
      hideLoader();
    }
  });
}

 function Getordetails(id){
 	$("#cashierd_id").val(id);
      $('.loadingGIF').show();
      var filtervars = {
        id:id
      }; 
      $.ajax({
        type: "post",
        url: DIR+'locationclearance/getordata',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          $('.loadingGIF').hide();
          arr = $.parseJSON(html);
             $("#cashier_id").val(arr.cashier_id);  $("#ordate").val(arr.created_at); 
             $("#oramount").val(arr.tfc_amount); 
             $("#or_no").val(arr.or_no);
          }
      });
    }

$('#pend_approved_status').bind('change', function (){
   if($(this).is(':checked')){
		$( "#pend_approved_status").prop('checked', true);
	}else{
		$( "#pend_approved_status").prop('checked', false);
	}
	
});