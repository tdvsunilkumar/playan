$(document).ready(function(){
	 var requisition = function() {
        this.$body = $("body");
    };
	select2();
	$(".numeric").numeric({ decimal : "." });
	//$('#requirement').select3({dropdownAutoWidth : false,dropdownParent: $("#flush-collapse3")});
	$("#btnAssessment").click(function(){
		getAssesmentDetails();
 	});	
	$('#psicclass').on('change', function() {
          var psicid =$(this).val();
          if(psicid !=""){
          var bussinessid = $("#id").val();
          var apptype = $("#appcode").val();
          $.ajax({
            url :DIR+'business-permit/application/getrequirements', // json datasource
            type: "POST", 
            data: {
              "psicid": psicid, "bussinessid": bussinessid,"apptype": apptype,"_token": $("#_csrf_token").val(),
            },
            success: function(html){
              //var arr = html.split('#');

              $("#requirement").html(html);
              //$("#tfoc_idhidden").val(arr[1]);
			       $('#requirement').on('change', function() {
			              var psicidnew =  $("#requirement option:selected").attr('subclassid');
			              $("#subclasshidden").val(psicidnew);

			              var br_code =$("#requirement option:selected").attr('brcode');
			              $("#br_code").val(br_code);
			     });
            }
           })
         } 
     });

 	$("#uploadAttachmentbtn").click(function(){
 		uploadAttachment();
 	});
 	$(".deleteRequirefille").click(function(){
 		deleteRequirement($(this));
 	})
 	$("#declineApplication").click(function(){
 		DeclineApplication($(this));
 	})
 	$("#activateApplication").click(function(){
 		ActivateApplication($(this));
 	})

 	$(".saveData").click(function(){
 		saveDeta($(this));
 	})
 	$("#jqVerifyApplication").click(function(){
 		varifiyApplication();
 	})
       
});
function varifiyApplication() {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: 'Are you sure want to verify the application?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoader();

                    // First AJAX request
                    $.ajax({
                        url: DIR + 'business-permit/application/change-status',
                        type: 'POST',
                        data: {
                            app_code: $('#appcode').val(),
                            year: $('#year').val(),
                            busn_id: $('#busn_id').val(),
                            busn_app_status: 2,
                            pm_id: $('#payment_mode').val(),
                            _token: $("#_csrf_token").val(),
                        },
                        success: function (html) {
                                $.ajax({
                                    url: DIR + 'business-permit/application/sendEmail',
                                    type: 'POST',
                                    data: {
                                        id: $('#busn_id').val(),
                                        _token: $("#_csrf_token").val(),
                                    },
                                    success: function (secondResponse) {
                                            Swal.fire({
                                                position: 'center',
                                                icon: 'success',
                                                title: 'Verified Successfully',
                                                showConfirmButton: false,
                                                timer: 1500
                                            });
                                            location.reload(true);
                                    },
                                    error: function (error) {
                                        console.log('request error:', error);
                                        Swal.fire({
                                            position: 'center',
                                            icon: 'success',
                                            title: 'Verified Successfully',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }
                                });
                            
                        },
                       
                    });
                }
            });
        }

        function showLoader() {
            // Implement your loading indicator here
        }

        function hideLoader() {
            // Implement hiding/loading indicator here
        }

// function varifiyApplication(thisval){
// 	var busn_id = $("#busn_id").val();
// 	const swalWithBootstrapButtons = Swal.mixin({
// 	   customClass: {
// 		   confirmButton: 'btn btn-success',
// 		   cancelButton: 'btn btn-danger'
// 	   },
// 	   buttonsStyling: false
//    })
//    swalWithBootstrapButtons.fire({
// 	   title: 'Are you sure?',
//        text: "Are you sure want Verify Applications?",
// 	   icon: 'warning',
// 	   showCancelButton: true,
// 	   confirmButtonText: 'Yes',
// 	   cancelButtonText: 'No',
// 	   reverseButtons: true
//    }).then((result) => {
// 	   	if(result.isConfirmed){
// 	   		showLoader();
// 		  	$.ajax({
// 			   url :DIR+'business-permit/application/change-status', // json datasource
// 			   type: "POST", 
// 			   data: {
// 			   	app_code : $("#appcode").val(),
// 			   	year:$("#year").val(),
// 					busn_id: busn_id,
//           busn_app_status:2,
//           pm_id:$("#payment_mode").val(),
// 				 	"_token": $("#_csrf_token").val(),
// 			   },
// 			   success: function(html){
// 			   		hideLoader();
// 			   		if(html.ESTATUS){
// 					   	 Swal.fire({
// 							 position: 'center',
// 							 icon: 'success',
// 							 title: 'Update Successfully.',
// 							 showConfirmButton: false,
// 							 timer: 1500
// 					    })
// 				   		location.reload(true);
// 				    }
// 			    }
// 		   })
// 	   }
//    })
// }
function getAssesmentDetails(){
	var id =$("#busn_id").val();
	showLoader();
    $.ajax({
        url :DIR+'Endrosement/assessmentDetails', // json datasource
        type: "POST", 
        dataType: "html", 
        data: {
          "id": id, 
          "year":$("#year").val(),
          "app_code":$("#appcode").val(),
          "bbendo_id":1,
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

function select2() {
    if ($("form .select3_view").length > 0) {
        $($("form .select3_view")).each(function (index, element) {
            if (!$(this).hasClass("select3-hidden-accessible")) {
                $(this).select3({dropdownAutoWidth : false,dropdownParent: $(this).parent()});
            }
        })
        /*$($(".select2")).each(function (index, element) {
            var id = $(element).attr('id');
            var multipleCancelButton = new Choices(
                '#' + id, {
                    removeItemButton: true,
                    matcher: function(term, text, option) {
            return text.toUpperCase().indexOf(term.toUpperCase())>=0 || option.val().toUpperCase().indexOf(term.toUpperCase())>=0;
          }
                }
            );
        });*/

    }

}

function DeclineApplication(thisval){
	var appid = thisval.val();
	const swalWithBootstrapButtons = Swal.mixin({
	   customClass: {
		   confirmButton: 'btn btn-success',
		   cancelButton: 'btn btn-danger'
	   },
	   buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
	   text: "Are you sure want to decline application?",
	   icon: 'warning',
	   showCancelButton: true,
	   confirmButtonText: 'Yes',
	   cancelButtonText: 'No',
	   reverseButtons: true
   }).then((result) => {
	   	if(result.isConfirmed){
	   		showLoader();
		  	$.ajax({
			   url :DIR+'business-permit/application/DeclineAttachment', // json datasource
			   type: "POST", 
			   data: {
				 "appid": appid,
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
				   location.reload(true);
			   }
		   })
	   }
   })
}

function ActivateApplication(thisval){
	var appid = thisval.val();
	const swalWithBootstrapButtons = Swal.mixin({
	   customClass: {
		   confirmButton: 'btn btn-success',
		   cancelButton: 'btn btn-danger'
	   },
	   buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
	   text: "Are you sure want to activate application?",
	   icon: 'warning',
	   showCancelButton: true,
	   confirmButtonText: 'Yes',
	   cancelButtonText: 'No',
	   reverseButtons: true
   }).then((result) => {
	   	if(result.isConfirmed){
	   		showLoader();
		  	$.ajax({
			   url :DIR+'business-permit/application/ActivateAttachment', // json datasource
			   type: "POST", 
			   data: {
				 "appid": appid,
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
				   location.reload(true);
			   }
		   })
	   }
   })
}

function saveDeta(thisval){
	var busEndorsementStatus = thisval.val();
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
				url :DIR+'Endrosement/updateEndorsementStatus', // json datasource
				type: "POST", 
				dataType: "json",
				data: {
					"busEndorsementStatus":busEndorsementStatus,
					"id":$("#id").val(),
					"end_tfoc_id": $("#end_tfoc_id").val(), 
					"enddept_fee": $("#enddept_fee").val(),
					"end_fee_name":$("#end_fee_name").val(),
					"bbendo_id":$("#bbendo_id").val(),
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
			   url :DIR+'business-permit/application/deleteAttachment', // json datasource
			   type: "POST", 
			   data: {
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
					 title: 'Removed Successfully.',
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
	if($("#requirement").val()==0){
		$("#err_end_requirement_id").html("Please select requirement");
		return false;
	}else if($("#psicclass").val()==0){
		$("#err_psicclass").html("Please select nature of bussiness");
		return false;
	}else if (typeof $('#documentname')[0].files[0]== "undefined") {
		$("#err_documentname").html("Please upload Document");
		return false;
	}
	var formData = new FormData();
	formData.append('file', $('#documentname')[0].files[0]);
	formData.append('busn_id', $("#busn_id").val());
	formData.append('appcode', $("#appcode").val());
	formData.append('subclass_id', $("#subclasshidden").val());
	formData.append('requirement', $("#requirement option:selected").val());
	formData.append('psicid', $("#psicclass option:selected").val());
	formData.append('brcode', $("#br_code").val());
	showLoader();
	$.ajax({
       url : DIR+'business-permit/application/uploadDocument',
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
				$("#requirement").empty();
				$("#psicclass").val(0);
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
			    $(".deleteRequirefille").unbind("click");
			    $(".deleteRequirefille").click(function(){
			 		deleteRequirement($(this));
			 	})
			}
       }
	});
}
