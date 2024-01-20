$(document).ready(function(){
	select3Ajax("inspection_officer_id","inspection_officer_group","getClientsBfpAjax");
select3Ajax("bfpcert_approved_recommending","recommending","getClientsBfpAjax");
select3Ajax("bfpcert_approved_recommending2","recommending2","getClientsBfpAjax");
select3Ajax("bfpcert_approved","approved","getClientsBfpAjax");
select3Ajax("bfpcert_approved2","approved2","getClientsBfpAjax");
// $("#bfpcert_approved_recommending").select3({dropdownAutoWidth : false,dropdownParent: $("#recommending")});
// $("#bfpcert_approved_recommending2").select3({dropdownAutoWidth : false,dropdownParent: $("#recommending2")});
// $("#bfpcert_approved").select3({dropdownAutoWidth : false,dropdownParent: $("#approved")});
// $("#bfpcert_approved2").select3({dropdownAutoWidth : false,dropdownParent: $("#approved2")});
$("#busn_id").change(function(){
		var id =$(this).val();
		var bend_id = $("#endorsing_dept_id").val();
		var year = $("#year").val();
		getprofiledata(id,year,bend_id); 
	})
if($("#busn_id").val()>0){
		var id =$("#busn_id").val();
		var bend_id = $("#endorsing_dept_id").val();
		var year = $("#year").val();
		getprofiledata(id,year,bend_id);
}
$("#bff_verified_by").select3({
 	
 });
//  $('.printCertificate').click(function () {
//     var id = $(this).attr('id');
//     var datavalue = $(this).attr('data-Value');
//     alert(datavalue);
//     inspectionPrints(id);
// });
$("#bfpcert_approved_recommending").change(function(){
		var id=$(this).val();
		getEmployeeRecommendin(id);
})
$("#bfpcert_approved_recommending2").change(function(){
		var id=$(this).val();
		getEmployeeRecommendin(id);
})
$("#bfpcert_approved").change(function(){
		var id=$(this).val();
		getEmployeeApproved(id);
})	
$("#bfpcert_approved2").change(function(){
		var id=$(this).val();
		getEmployeeApproved(id);
})
$('#recommending_status').change(function() {
     if ($(this).is(':checked')) {
      $('#myDiv').show();
      $('#myDiv2').show();
    } else {
      $('#myDiv').hide();
      $('#myDiv2').hide();
    }
});
$("#uploadAttachment").click(function(){
 		uploadAttachment();
});
$(".deleteEndrosmentInspections").click(function(){
 		deleteEndrosmentInspections($(this));
})

$('#approved_status').change(function() {
     if ($(this).is(':checked')) {
      $('#myDiv3').show();
      $('#myDiv4').show();
    } else {
      $('#myDiv3').hide();
      $('#myDiv4').hide();
    }
  });

$('.printCertificate').click(function () {
    var id = $(this).attr('id');
    bfpCertificatePrints(id);
});
// $('.release').click(function () {
//     var id = $(this).attr('id');
//     bfpCertificateRelease(id);
// });
var shouldSubmitForm = false;
    $('.release').click(function (e) {
    	var id = $(this).attr('id');
        if (!shouldSubmitForm) {
            var form = $('#mainForm');
            Swal.fire({
                title: "Are you sure?",
                text: "Release the FSIC will also update the reports.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    shouldSubmitForm = true;
                    $.ajax({
					   url :DIR+'bfpCertificateRelease', // json datasource
					   type: "POST", 
					   data: {
						 "id": id, 
						 "_token": $("#_csrf_token").val(),
					   },
					   success: function(html){
						   Swal.fire({
							 position: 'center',
							 icon: 'success',
							 title: 'Release Successfully.',
							 showConfirmButton: false,
							 timer: 1500
						   });
                        location.reload();
					   }
				   })
                } else {
                    console.log("Form submission canceled");
                }
            });

            e.preventDefault();
        }
    });

});

function setConfirmAlert(e){
    $("#mainForm input[name='submit']").unbind("click");
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
        if(result.isConfirmed){
            $('#mainForm').unbind('submit');
            $("#mainForm input[name='submit']").trigger("click");
            $("#mainForm input[name='submit']").attr("type","button");
        }
    });
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
			   url :DIR+'bfpcertificate/deleteAttachment', // json datasource
			   type: "POST", 
			   data: {
			   	 "id":$("#busn_id").val(),
				 "year":$("#year").val(),
				 "rid": rid,
				 "bbendo_id": $("#endorsing_dept_id").val(),  
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
	formData.append('busn_id', $("#busn_id").val());
	formData.append('bbendo_id', $("#endorsing_dept_id").val());
	formData.append('year', $("#year").val());
	showLoader();
	$.ajax({
       url : DIR+'bfpcertificate/uploadAttachment',
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


function bfpCertificatePrints(id){
              var id = id;
              $.ajax({
                url: DIR+'isPrinted',
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


function confirmDelete() {
    // Show SweetAlert popup
    swal({
      title: "Confirmation",
      text: "Do you want to delete this user?",
      icon: "warning",
      buttons: ["Cancel", "Delete"],
      dangerMode: true,
    }).then(function (willDelete) {
      if (willDelete) {
        // User clicked Delete button, perform deletion or any desired action
        // Your delete logic goes here
        swal("User deleted successfully", {
          icon: "success",
        });
      } else {
        // User clicked Cancel button, do nothing or perform any desired action
        swal("User deletion canceled", {
          icon: "info",
        });
      }
    });
}
function getEmployeeApproved(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getEmployeeApprovedDetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#bfpcert_approved_position").val(html.description)
	    }
	});
}
function getEmployeeRecommendin(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getEmployeeRecommendinDetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#bfpcert_approved_recommending_position").val(html.description)
	    }
	});
}

function getprofiledata(id,year,bend_id){
	$('.loadingGIF').show();
	var filtervars = {
	    pid:id,
	    year:year,
	    bend_id:bend_id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getBusineClient',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	arr = $.parseJSON(html);
	    	if(arr.length > 0){
	    		console.log(arr[0]['id']);
	    		var address = '';

				if (arr[0]['rpo_address_house_lot_no']) {
				    address += arr[0]['rpo_address_house_lot_no'] + ', ';
				}
				if (arr[0]['rpo_address_street_name']) {
				    address += arr[0]['rpo_address_street_name'] + ', ';
				}

				if (arr[0]['rpo_address_subdivision']) {
				    address += arr[0]['rpo_address_subdivision'] + ', ';
				}

				address += arr[0]['brgy_name'] + ', ' + arr[0]['mun_desc'] + ', ' + arr[0]['prov_desc'] + ', ' + arr[0]['reg_region'];

				$("#completeAddress").val(address);
	    		if (arr[0]['suffix']) {
				    var fullName = (arr[0]['rpo_first_name'] ? arr[0]['rpo_first_name'] + ' ' : '') +
				                   (arr[0]['rpo_middle_name'] ? arr[0]['rpo_middle_name'] + ' ' : '') +
				                   (arr[0]['rpo_custom_last_name'] ? arr[0]['rpo_custom_last_name'] : '');
				    $("#clientName").val(fullName + ', ' + arr[0]['suffix']);
				} else {
				    var fullName = (arr[0]['rpo_first_name'] ? arr[0]['rpo_first_name'] + ' ' : '') +
				                   (arr[0]['rpo_middle_name'] ? arr[0]['rpo_middle_name'] + ' ' : '') +
				                   (arr[0]['rpo_custom_last_name'] ? arr[0]['rpo_custom_last_name'] : '');
				    $("#clientName").val(fullName);
				}
                
	    		$("#busns_id_no").val(arr[0]['busns_id_no']);
	    		$("#client_id").val(arr[0]['client_id']);
	    		$("#bgy_id").val(arr[0]['barangay_id']);
	    		$("#busn_bldg_area").val(arr[0]['busn_bldg_area']);
	    		$("#businessName").val(arr[0]['busn_name']);
	    		$("#bend_id").val(arr[0]['bendId']);
	    		$("#bff_id").val(arr[0]['bff_id']);
	    		$("#bfpas_id").val(arr[0]['bfpas_id']);
	    		$("#bfpas_total_amount").val(arr[0]['bfpas_total_amount']);
	    		$("#bfpas_payment_or_no").val(arr[0]['bfpas_payment_or_no']);
	    		$("#bfpas_date_paid").val(arr[0]['bfpas_date_paid']);
	    		$("#bio_id").val(arr[0]['bio_id']);
	    		$("#busn_bldg_total_floor_area").val(arr[0]['busn_bldg_total_floor_area']);
	    		
	    		
	    	}
	    }
	});

}

// $('#approved_status').bind('change', function (){
// 	   if($(this).is(':checked')){
		
// 		const swalWithBootstrapButtons = Swal.mixin({
// 			customClass: {
// 				confirmButton: 'btn btn-success',
// 				cancelButton: 'btn btn-danger'
// 			},
// 			buttonsStyling: false
// 		})
// 		swalWithBootstrapButtons.fire({
// 			title: 'Are you sure?',
// 			text: "This action can not be undone. Do you want to continue?",
// 			icon: 'warning',
// 			showCancelButton: true,
// 			confirmButtonText: 'Yes',
// 			cancelButtonText: 'No',
// 			reverseButtons: true
// 		}).then((result) => {
// 			if(result.isConfirmed)
// 			{
// 				var id  =$('#id').val();
// 				var approved_status = $('#approved_status').val();
// 				var filtervars = {id:id,approved_status:approved_status};
// 				$.ajax({
// 					type: "post",
// 					url: DIR+'bfpCertificate-approvedsataus',
// 					data: filtervars,
// 					dataType: "json",
// 					success: function(html){
// 						$( "#approved_status").prop('checked', true);
// 					}
// 				});
// 			}else{
// 				$( "#approved_status").prop('checked', false);
// 			}
// 		})
		
// 	   }
// 	});
	
// 	$('#recommending_status').bind('change', function () {
// 		if($(this).is(':checked')){
// 		const swalWithBootstrapButtons = Swal.mixin({
// 			customClass: {
// 				confirmButton: 'btn btn-success',
// 				cancelButton: 'btn btn-danger'
// 			},
// 			buttonsStyling: false
// 		})
// 		swalWithBootstrapButtons.fire({
// 			title: 'Are you sure?',
// 			text: "This action can not be undone. Do you want to continue?",
// 			icon: 'warning',
// 			showCancelButton: true,
// 			confirmButtonText: 'Yes',
// 			cancelButtonText: 'No',
// 			reverseButtons: true
// 		}).then((result) => {
// 			if(result.isConfirmed)
// 			{
// 			    var id  =$('#id').val();
// 				var recommending_status = $('#recommending_status').val();
// 				var filtervars = {id:id,recommending_status:recommending_status};
// 				$.ajax({
// 					type: "post",
// 					url: DIR+'bfpCertificate-recommendingapproval',
// 					data: filtervars,
// 					dataType: "json",
// 					success: function(html){
// 						$( "#recommending_status").prop('checked', true);
// 					}
// 				});
// 			}else{
// 				$( "#recommending_status").prop('checked', false);
// 			}
// 		})
// 	   }
// 	});
//   $('form').submit(function(e) {
//         e.preventDefault();
//         $(".validate-err").html('');
//         $("form input[name='submit']").unbind("click");
//         var myform = $('form');
      
//         var data = myform.serialize().split("&");
      
//         var obj={};
//         for(var key in data){
//             obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
//         }
//         $.ajax({
//             url :$(this).attr("action")+'/formValidation', // json datasource
//             type: "POST", 
//             data: obj,
//             dataType: 'json',
//             success: function(html){
//                 setConfirmAlert(e);
                   
//             }
//         })


// });
 

