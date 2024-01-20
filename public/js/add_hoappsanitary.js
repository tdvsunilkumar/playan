$(document).ready(function(){
	var yearpickerInput = $('input[name="has_app_year"]').val();
      $('.yearpicker').yearpicker();
      $('.yearpicker').val(yearpickerInput).trigger('change');
	select3Ajax("has_recommending_approver","has_recommending_approver_div","getClientsBfpAjax");
    select3Ajax("has_approver","has_approver_div","getClientsBfpAjax");
	// $("#has_recommending_approver").select3({dropdownAutoWidth : false,dropdownParent: $("#has_recommending_approver_div")});  
	// $("#has_approver").select3({dropdownAutoWidth : false,dropdownParent: $("#has_approver_div")});  
	$("#end_requirement_id").select3({dropdownAutoWidth : false,dropdownParent: $("#end_requirement_id_div")});  
    $("#ba_code").change(function(){
		var id=$(this).val();
		if(id){ getAccounrnumber(id); }
	})
	if($("#ba_code").val()>0){
		getAccounrnumber($("#ba_code").val());
		$("#banldetail").removeClass('hide');
	}
	$("#btn_addmore_healthcert").click(function(){
		$('html, body').stop().animate({
      scrollTop: $("#btn_addmore_healthcert").offset().top
    }, 600);
		addmorehealthcert();
	});
	$("#uploadAttachment").click(function(){
		uploadAttachment();
	});
	$("#uploadAttachmentonly").click(function(){
		uploadAttachmentonly();
	});
	$(".deleteAttachment").click(function(){
		deleteAttachment($(this));
	})
	$(".btn_cancel_healthcert").click(function(){
		$(this).closest(".removehealthcertidata").remove();
	});
	$("#bend_id").change(function(){
		var bend_id=$(this).val();
		getBusnComAddress(bend_id);
	});
	$("#has_recommending_approver").change(function(){
		var has_recommending_approver=$(this).val();
		getPosition(has_recommending_approver);
	});
	$('.print').click(function() {
		var id = $(this).attr('id');
		HealthsanitaryPermitPrint(id);
	});
	$("#has_approver").change(function(){
		var id=$(this).val();
		getPositionApprover(id);
	});
	$(".deleteSanitaryReq").click(function(){
		deleteSanitaryReq($(this));
	})
	$('#has_approver_status').change(function() {
		if ($(this).is(':checked')) {
			$('#apv_div').show();
		} else {
			$('#apv_div').hide();
		}
	});
	$(".btn_cancel_sanitary").click(function(){
		var inputVal = $(this).closest(".removenaturedata").find("#relid").val();
		$(this).closest(".removenaturedata").remove();
		$.ajax({
			type: "GET",
			url: DIR+'healthy-and-safety/app-sanitary/deleteSanitaryReq/'+inputVal,
			data: inputVal,
			dataType: "json",
			success: function(html){ 
				console.log(inputVal);
			}
		}); 
		console.log(inputVal);
	});
	var has_type_of_establishment = $('#has_type_of_establishment');
    var suggestionsDiv = $('#suggestionsDiv');
	
	has_type_of_establishment.on('input', function() {
        var query = has_type_of_establishment.val();

        if (query.length >= 2) { // Minimum characters required for suggestions
            $.ajax({
                url: DIR+'healthy-and-safety/app-sanitary/getEstablisSuggestions',
                method: 'GET',
                data: { query: query },
                success: function(response) {
                    var suggestions = response;

                    // Clear previous suggestions
                    suggestionsDiv.empty();

                    // Append new suggestions
                    suggestions.forEach(function(suggestion) {
                        suggestionsDiv.append('<p class="suggestion">' + suggestion + '</p>');
                    });
                }
            });
        } else {
            suggestionsDiv.empty(); // Clear suggestions if input length is below minimum
        }
    });

    // Handle suggestion selection
    suggestionsDiv.on('click', '.suggestion', function() {
        var selectedSuggestion = $(this).text();
        has_type_of_establishment.val(selectedSuggestion);
        suggestionsDiv.empty(); // Clear suggestions after selection
    });
});

function addmorehealthcert(){
	var html = $("#hidenhealthcertiHtml").html();
	$(".Healthcerti").append(html);
	$(".btn_cancel_healthcert").click(function(){
		$(this).closest(".removehealthcertidata").remove();
	});
	
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
	formData.append('sanitary_id', $("#id").val());
	formData.append('end_requirement_name', $("#end_requirement_id option:selected").text());
	formData.append('end_requirement_id', $("#end_requirement_id").val());
	showLoader();
	$.ajax({
       url : DIR+'Endrosement/uploadSanitaryDoc',
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
			    $(".deleteSanitaryReq").unbind("click");
			    $(".deleteSanitaryReq").click(function(){
			 		deleteSanitaryReq($(this));
			 	})
			}
       }
	});
}
function uploadAttachmentonly(){
	$(".validate-err").html("");
	if (typeof $('#document_names')[0].files[0]== "undefined") {
		$("#err_documents").html("Please upload Document");
		return false;
	}
	var formData = new FormData();
	formData.append('file', $('#document_names')[0].files[0]);
	formData.append('healthCertId', $("#id").val());
	showLoader();
	$.ajax({
       url : DIR+'healthy-and-safety/app-sanitary/uploadDocument',
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
	       	    $("#document_names").val(null);
		       	if(data!=""){
		       		$("#DocumentDtlsss").html(data.documentList);
		       	}
	          	Swal.fire({
					 position: 'center',
					 icon: 'success',
					 title: 'Document uploaded successfully.',
					 showConfirmButton: false,
					 timer: 1500
			    })
				$(".deleteAttachment").unbind("click");
			    $(".deleteAttachment").click(function(){
					deleteAttachment($(this));
			 	})
			}
       }
	});
}

function deleteAttachment(thisval){
	var healthCertid = thisval.attr('healthCertid');
	var doc_id = thisval.attr('doc_id');
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
			   url :DIR+'healthy-and-safety/app-sanitary/deleteAttachment', // json datasource
			   type: "POST", 
			   data: {
					"healthCertid": healthCertid,
					"doc_id": doc_id,  
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

function deleteSanitaryReq(thisval){
	var sid = thisval.attr('sid');
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
			   url :DIR+'Endrosement/deleteSanitaryReq', // json datasource
			   type: "POST", 
			   data: {
					"id":$("#id").val(),
					"sid": sid,
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
function getBusnComAddress(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	};
	$.ajax({
	    type: "GET",
	    url: DIR+'healthy-and-safety/app-sanitary/getBusnComAddress',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#complete_address").val(html.complete_address);
			$("#owner").val(html.owner);
	    }
	}); 
}

function getAccounrnumber(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	};
	$.ajax({
	    type: "GET",
	    url: DIR+'getpbloAppdetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#ba_business_account_no").val(html.ba_business_account_no);
	    	$("#p_code").val(html.profile_id);
	    	$("#brgy_code").val(html.barangay_id);
	    }
	}); 
}

function getPosition(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	};
	$.ajax({
	    type: "GET",
	    url: DIR+'healthy-and-safety/app-sanitary/getPosition',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#has_recommending_approver_position").val(html.position);
	    }
	}); 
}
function HealthsanitaryPermitPrint(id){
	var id = id;
	$.ajax({
	  url: DIR+'healthy-and-safety/app-sanitary/hoapphealthsanitaryprint/'+id,
	  type: 'GET',
	
	  success: function (data) {
		 var url = data;
		 console.log(url);
		  window.open(url, '_blank');
	  }
	});
}
function getPositionApprover(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	};
	$.ajax({
	    type: "GET",
	    url: DIR+'healthy-and-safety/app-sanitary/getPosition',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#has_approver_position").val(html.position);
	    }
	}); 
}

