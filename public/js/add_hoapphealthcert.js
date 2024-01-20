$(document).ready(function(){
	select3Ajax("citizen_id","citizen_id_group","getCitizenAjax");
	select3Ajax("bend_id","bend_id_group","getBusinessAjax");
	select3Ajax("hahc_recommending_approver","hahc_recommending_approver_group","getClientsBfpAjax");
    select3Ajax("hahc_approver","hahc_approver_group","getClientsBfpAjax");
	function initializeSelect3() {
		$('.req_id_s').select3({ dropdownAutoWidth: false, dropdownParent: $('#Healthcerti') });
	  }
	// Initial initialization of select3
	  initializeSelect3();
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
	$("#citizen_id").change(function(){
		var citizen_id=$(this).val();
		getCitizenDetails(citizen_id);
	});
	$("#hahc_approver").change(function(){
		var hahc_approver=$(this).val();
		getPosition(hahc_approver);
	});
	$("#hahc_recommending_approver").change(function(){
		var id=$(this).val();
		getPositionApprover(id);
	});
	$("#hahc_recommending_approver").change(function(){
		var id=$(this).val();
		getPositionApprover(id);
	});
	$("#reloadCitizen").click(function(){
		reloadCitizen();
	});
	$("#uploadAttachment").click(function(){
		uploadAttachment();
	});
	$('.print').click(function() {
		var id = $(this).attr('id');
		HealthcertificatePrint(id);
	});
	$(".deleteAttachment").click(function(){
		deleteAttachment($(this));
	})
	var employee_occupation = $('#employee_occupation');
    var empOccupSuggestionsDiv = $('#empOccupSuggestionsDiv');
	var hahc_place_of_work = $('#hahc_place_of_work');
    var addressSuggestionsDiv = $('#addressSuggestionsDiv');
	

	hahc_place_of_work.on('input', function() {
        var query = hahc_place_of_work.val();

        if (query.length >= 2) { // Minimum characters required for suggestions
            $.ajax({
                url: DIR+'healthy-and-safety/health-certificate/getWorkAddressSuggestions',
                method: 'GET',
                data: { query: query },
                success: function(response) {
                    var suggestions = response;

                    // Clear previous suggestions
                    addressSuggestionsDiv.empty();

                    // Append new suggestions
                    suggestions.forEach(function(suggestion) {
                        addressSuggestionsDiv.append('<p class="suggestion">' + suggestion + '</p>');
                    });
                }
            });
        } else {
            addressSuggestionsDiv.empty(); // Clear suggestions if input length is below minimum
        }
    });

    // Handle suggestion selection
    addressSuggestionsDiv.on('click', '.suggestion', function() {
        var selectedSuggestion = $(this).text();
        hahc_place_of_work.val(selectedSuggestion);
        addressSuggestionsDiv.empty(); // Clear suggestions after selection
    });
	

    employee_occupation.on('input', function() {
        var query = employee_occupation.val();

        if (query.length >= 2) { // Minimum characters required for suggestions
            $.ajax({
                url: DIR+'healthy-and-safety/health-certificate/getOccuSuggestions',
                method: 'GET',
                data: { query: query },
                success: function(response) {
                    var suggestions = response;

                    // Clear previous suggestions
                    empOccupSuggestionsDiv.empty();

                    // Append new suggestions
                    suggestions.forEach(function(suggestion) {
                        empOccupSuggestionsDiv.append('<p class="suggestion">' + suggestion + '</p>');
                    });
                }
            });
        } else {
            empOccupSuggestionsDiv.empty(); // Clear suggestions if input length is below minimum
        }
    });

    // Handle suggestion selection
    empOccupSuggestionsDiv.on('click', '.suggestion', function() {
        var selectedSuggestion = $(this).text();
        employee_occupation.val(selectedSuggestion);
        empOccupSuggestionsDiv.empty(); // Clear suggestions after selection
    });
	
	$('#hahc_approver_status').change(function() {
		if ($(this).is(':checked')) {
			$('#apv_div').show();
		} else {
			$('#apv_div').hide();
		}
	});
	
	
	$(".btn_cancel_healthcert").click(function(){
		var inputVal = $(this).closest(".removehealthcertidata").find("#healthreqid").val();
		$(this).closest(".removehealthcertidata").remove();
		isDuplicate=0;
		var bcnt=0;
		$("#Healthcerti").find(".removehealthcertidata").each(function(id){
			$(this).find('.req_id').attr("id",'req_id'+bcnt);
			bcnt++;
		});
		var cnt = $("#Healthcerti").find(".removehealthcertidata").length;
		$("#hidenhealthcertiHtml").find('.req_id').attr('id','req_id'+cnt);
		$.ajax({
			type: "GET",
			url: DIR+'healthy-and-safety/health-certificate/deleteCertificateReq/'+inputVal,
			data: inputVal,
			dataType: "json",
			success: function(html){ 
				console.log(inputVal);
			}
		}); 
		console.log(inputVal);
	});
});

function addmorehealthcert(){
	var prevLength = $("#Healthcerti").find(".removehealthcertidata").length;

	$("#hidenhealthcertiHtml").find("#increment").html(prevLength+1);
	var html = $("#hidenhealthcertiHtml").html();
	$(".Healthcerti").append(html);
	$(".btn_cancel_healthcert").click(function(){
		$(this).closest(".removehealthcertidata").remove();
		isDuplicate=0;
		var bcnt=0;
		$("#Healthcerti").find(".removehealthcertidata").each(function(id){
			$(this).find('.req_id').attr("id",'req_id'+bcnt);
			bcnt++;
		});
		var cnt = $("#Healthcerti").find(".removehealthcertidata").length;
		$("#hidenhealthcertiHtml").find('.req_id').attr('id','req_id'+cnt);
	});
	$("#req_id"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#Healthcerti")});
	var classid = $("#Healthcerti").find(".removehealthcertidata").length;
	$("#hidenhealthcertiHtml").find('.req_id').attr('id','req_id'+classid);

	
}

function HealthcertificatePrint(id){
	var id = id;
	$.ajax({
	  url: DIR+'healthy-and-safety/health-certificate/hoapphealthcertPrint/'+id,
	  type: 'GET',
	
	  success: function (data) {
		 var url = data;
		 console.log(url);
		  window.open(url, '_blank');
	  }
	});
}

function uploadAttachment(){
	$(".validate-err").html("");
	if (typeof $('#document_name')[0].files[0]== "undefined") {
		$("#err_document").html("Please upload Document");
		return false;
	}
	var formData = new FormData();
	formData.append('file', $('#document_name')[0].files[0]);
	formData.append('healthCertId', $("#id").val());
	showLoader();
	$.ajax({
       url : DIR+'healthy-and-safety/health-certificate/uploadDocument',
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
			   url :DIR+'healthy-and-safety/health-certificate/deleteAttachment', // json datasource
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
					 title: 'Removed Successfully.',
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

function getAccounrnumber(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	};
	$.ajax({
	    type: "GET",
	    url: DIR+'getpdoPbloClearancedetails',
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

function getCitizenDetails(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	};
	$.ajax({
	    type: "GET",
	    url: DIR+'healthy-and-safety/health-certificate/getCitizenDetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#age").val(html.age);
	    	$("#complete_address").val(html.complete_address);
			$("#gender").val(html.gender);
			$("#nationality").val(html.nationality);
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
	    url: DIR+'healthy-and-safety/health-certificate/getPosition',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#hahc_approver_position").val(html.position);
	    }
	}); 
}
function reloadCitizen(id){
	$('.loadingGIF').show();
	$.ajax({
	    type: "GET",
	    url: DIR+'healthy-and-safety/health-certificate/reloadCitizen',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#hahc_approver_position").val(html.position);
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
	    url: DIR+'healthy-and-safety/health-certificate/getPosition',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#hahc_recommending_approver_position").val(html.position);
	    }
	}); 
}


