$(document).ready(function(){	
	$("#ora_to").change(function(){
		var count = 0;
        var from = +$("#ora_from").val();
        var to = +$("#ora_to").val();
        totalcount = parseFloat(to)- parseFloat(from);
        totalcount = parseFloat(totalcount) + 1;
        totalcount = totalcount.toFixed(2);
        $("#or_count").val(totalcount); 
	})
	
	$("#coa_no").change(function(){
        var shortname = $("#shortname").val();
        var coa_no = $("#coa_no").val();
        var cpor_series = shortname+',#'+coa_no;
        $("#cpor_series").val(cpor_series); 
	})

	$('#cpot_id').on('change', function() {
     var id = $(this).val();
     getShortname(id);
    });

    if($("#id").val() > 0 ){
    	var id = $("#cpot_id option:selected").val();;
       getShortname(id);
    }

    $("#uploadAttachmentbtn").click(function(){
 		uploadAttachment();
 	});
 	$(".deleteDocument").click(function(){
 		deleteAttachment($(this));
 	})
});

function getShortname(id){
	 var id =id;
      $.ajax({
        url :DIR+'ctoorregister/getShortname', // json datasource
        type: "POST", 
        data: {
          "id": id, "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          $("#shortname").val(html);
        }
       })
}

function deleteAttachment(thisval){
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
			   url :DIR+'ctoorregister/deleteAttachment', // json datasource
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
       url : DIR+'ctoorregister/uploadDocument',
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
			 		deleteAttachment($(this));
			 	})
			}
       }
	});
}








