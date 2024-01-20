$(document).ready(function(){
select3Ajax("bff_verified_by","verified","getClientsBfpAjax");
select3Ajax("bff_certified_by","certified","getClientsBfpAjax");
select3Ajax("bff_representative_id","representative","getBploTaxpayersAutoSearchList");
select3Ajax("bff_representative_id2","representative","getBploTaxpayersAutoSearchList");  
// $("#bff_representative_id").select3({dropdownAutoWidth : false,dropdownParent: $("#representative")});
$("#bot_occupancy_type").select3({dropdownAutoWidth : false,dropdownParent: $("#occupancy")});
// $("#bff_verified_by").select3({dropdownAutoWidth : false,dropdownParent: $("#verified")});
// $("#bff_certified_by").select3({dropdownAutoWidth : false,dropdownParent: $("#certified")});
$("#busn_id").change(function(){
		var id=$(this).val();
		getprofiledata(id);
		getCleint($("#busn_id").val());
		getCategory(id); 
		
	});
if($("#busn_id").val()>0){
		getprofiledata($("#busn_id").val());
		getCleint($("#busn_id").val());
        getCategory($("#busn_id").val());
}
$("#uploadAttachment").click(function(){
 		uploadAttachment();
});
$(".deleteEndrosmentInspections").click(function(){
 		deleteEndrosmentInspections($(this));
})
$('.print').click(function () {
    var id = $(this).attr('id');
    inspectionPrints(id);
});
$("#brgy_code").change(function(){
		var id=$(this).val();
		getBarangyaDetails(id);
	})
	$("#subclass_code").change(function(){
		var id=$(this).val();
		bfpgetBussinessData(id);
});
$("#bff_application_type").change(function(){
var id=$(this).val();
getPurpose(id);
});
$("#bot_occupancy_type").change(function(){
var id=$(this).val();
getBotId(id);

});
if($("#bot_occupancy_type").val()>0){
		getBotId($("#bot_occupancy_type").val());
}
$("#bff_representative_id").change(function(){
var id=$(this).val();
representative(id);
});
if($("#bff_representative_id").val()>0){
		representative($("#bff_representative_id").val());
}
$("#bff_representative_id2").change(function(){
var id=$(this).val();
representative2(id);
});
if($("#bff_representative_id2").val()>0){
		representative2($("#bff_representative_id2").val());
}
$("#bff_certified_by").change(function(){
var id=$(this).val();
certified(id);
});

$("#bff_certified_by2").change(function(){
var id=$(this).val();
certified(id);
});

$("#refreshCitizen").click(function(){
    refreshCitizen();
});
$("#refreshEmployee").click(function(){
    refreshEmployee();
  
});
$("#refreshEmployeeCert").click(function(){
    refreshEmployeeCert();
  
});
 //   $("#purpase").change(function(){
 //    var id=$(this).val();
 //    getCategory(id);
 //  });	
 //  if($("#purpase").val()>0){
 // 	var id = $("#purpase").val();
 //   getCategory(id);

 // }
 $("#bot_occupancy_type").change(function(){
    var id=$(this).val();
    getocuppancyDetails(id);
  });
 if($("#id").val()>0){
 	var id = $("#subclass_code").val();
 bfpgetBussinessData(id);

 }
 $("#checkederror").hide();
 $("form").submit(function(){
var x= $("input[type='checkbox']:checked");
if(x.length>0)
{
    return true;
}
else{
    $("#checkederror").show();
    return false;
}

});
	
});
function getCleint(id){
   $.ajax({
 
        url :DIR+'getClientDetails', // json datasource
        type: "POST", 
        data: {
           "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#bff_representative_id").html(html);
            var id=$("#bff_representative_id").val();
           representative2(id);
          }
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
			   url :DIR+'bfpapplicationform/deleteAttachment', // json datasource
			   type: "POST", 
			   data: {
			   	 "id":$("#busn_id").val(),
				 "year":$("#bff_year").val(),
				 "rid": rid,
				 "bbendo_id": $("#bend_id").val(),  
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
	formData.append('bbendo_id', $("#bend_id").val());
	formData.append('year', $("#bff_year").val());
	showLoader();
	$.ajax({
       url : DIR+'bfpapplicationform/uploadAttachment',
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
function refreshCitizen(){
   $.ajax({
        url :DIR+'getRefreshCitizen', // json datasource
        type: "POST", 
        data: {
          // "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#bff_representative_id").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function refreshEmployee(){
   $.ajax({
 
        url :DIR+'getRefreshEmployee', // json datasource
        type: "POST", 
        data: {
          // "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#bff_verified_by").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function refreshEmployeeCert(){
   $.ajax({
 
        url :DIR+'getRefreshEmployee', // json datasource
        type: "POST", 
        data: {
          // "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#bff_certified_by").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function getPurpose(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getPurposeDetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#purpase").val(html.id)
	    	
	    }
	});
}
function getBotId(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getBotIdDetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#bot_id").val(html.id)
	    	
	    }
	});
}

function certified(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getCertified',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#empPosition").val(html.description)
	    	
	    }
	});
}
function representative2(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getRepresentative',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	if (html.suffix) {
    var clientName = '';

    if (html.rpo_first_name) {
        clientName += html.rpo_first_name + ' ';
    }

    if (html.rpo_middle_name) {
        clientName += html.rpo_middle_name + ' ';
    }

    if (html.rpo_custom_last_name) {
        clientName += html.rpo_custom_last_name + ', ';
    }

    if (html.suffix) {
        clientName += html.suffix;
    }

    $("#clientName").val(clientName);
} else {
    var clientName = '';

    if (html.rpo_first_name) {
        clientName += html.rpo_first_name + ' ';
    }

    if (html.rpo_middle_name) {
        clientName += html.rpo_middle_name + ' ';
    }

    if (html.rpo_custom_last_name) {
        clientName += html.rpo_custom_last_name;
    }

    $("#clientName").val(clientName);
}
	    	
	    }
	});
}
function representative(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getRepresentative',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	if (html.suffix) {
    var clientName = '';

    if (html.rpo_first_name) {
        clientName += html.rpo_first_name + ' ';
    }

    if (html.rpo_middle_name) {
        clientName += html.rpo_middle_name + ' ';
    }

    if (html.rpo_custom_last_name) {
        clientName += html.rpo_custom_last_name + ', ';
    }

    if (html.suffix) {
        clientName += html.suffix;
    }

    $("#clientName").val(clientName);
} else {
    var clientName = '';

    if (html.rpo_first_name) {
        clientName += html.rpo_first_name + ' ';
    }

    if (html.rpo_middle_name) {
        clientName += html.rpo_middle_name + ' ';
    }

    if (html.rpo_custom_last_name) {
        clientName += html.rpo_custom_last_name;
    }

    $("#clientName").val(clientName);
}
	    	
	    }
	});
}
function getCategory(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getCategoryDetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#bff_category").val(html.app_code)
	    	if($("#bff_category").val() == 2){
		      $('.new').prop('disabled', true);
		      $("#bff_req_renew_business").prop('checked', true);

		    } else if($("#bff_category").val() == 1) {
		      $('.renew').prop('disabled', true);
		      $("#bff_req_new_business").prop('checked', true);
		    }
        }
	});
}
function inspectionPrints(id){
              var id = id;
              $.ajax({
                url: DIR+'BfpChequePrint',
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
function getocuppancyDetails(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getocuppancyDetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#bot_code").val(html.id)
	    	$("#bot_id").val(html.id)
	    	//$("#bot_occupancy_type").html('<option>Please Select</option>');
	    	// $("#bot_occupancy_type").val(html.bot_occupancy_type)
	    }
	});
}

function getBarangyaDetails(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getBarangyaDetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#barangay_id").val(html.id)
	    	$("#brgy_name").val(html.brgy_name)
	    }
	});
}

function bfpgetBussinessData(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'bfpgetBussinessData',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
			//alert(html)
	    	$('.loadingGIF').hide();
			$("#bussiness_application_desc").val(html.subclass_description);
			$("#subclass_id").val(html.id);
	    }
	});
}
// function getBusinessNo(id){
//     $.ajax({
//         url :DIR+'getBusinessNoId', // json datasource
//         type: "POST", 
//         data: {
//           "id": id, 
//           "_token": $("#_csrf_token").val(),
//         },
//         success: function(html){
//           if(html !=''){
//            $("#ba_business_account_no").html(html);
           
//           }
//         }
//     })
// }

function getprofiledata(id){
	$('.loadingGIF').show();
	var filtervars = {
	    pid:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getprofileClient',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	arr = $.parseJSON(html);
	    	if(arr.length > 0){
	    		console.log(arr[0]['id']);
	    		if (arr[0]['suffix']) {
				    var fullName = (arr[0]['rpo_first_name'] ? arr[0]['rpo_first_name'] + ' ' : '') +
				                   (arr[0]['rpo_middle_name'] ? arr[0]['rpo_middle_name'] + ' ' : '') +
				                   (arr[0]['rpo_custom_last_name'] ? arr[0]['rpo_custom_last_name'] : '');
				    $("#ba_p_first_name").val(fullName + ', ' + arr[0]['suffix']);
				} else {
				    var fullName = (arr[0]['rpo_first_name'] ? arr[0]['rpo_first_name'] + ' ' : '') +
				                   (arr[0]['rpo_middle_name'] ? arr[0]['rpo_middle_name'] + ' ' : '') +
				                   (arr[0]['rpo_custom_last_name'] ? arr[0]['rpo_custom_last_name'] : '');
				    $("#ba_p_first_name").val(fullName);
				}
	    		
	    		$("#ba_p_middle_name").val(arr[0]['rpo_middle_name']);
	    		$("#ba_p_last_name").val(arr[0]['rpo_custom_last_name']);
	    		
	    		$("#bff_telephone_no").val(arr[0]['p_telephone_no']);
	    		$("#bff_mobile_no").val(arr[0]['p_mobile_no']);
	    		$("#bff_email_addrress").val(arr[0]['p_email_address']); 
	    		$("#ba_telephone_no2").val(arr[0]['p_telephone_no']);
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

				$("#ba_p_address").val(address);
				$("#bff_no_of_storey").val(arr[0]['rp_building_no_of_storey']);
	    		$("#ba_business_name").val(arr[0]['ba_business_name']);
	    		$("#ba_address_house_lot_no").val(arr[0]['ba_address_house_lot_no']);
	    		$("#ba_address_street_name").val(arr[0]['ba_address_street_name']);
	    		$('#subclass_code>option:eq('+arr[0]['subclass_code']+')').prop('selected', true);
	    		$("#busns_id_no").val(arr[0]['busns_id_no']);
	    		$("#businessName").val(arr[0]['busn_name']);
	    		$("#brgy_code").val(arr[0]['brgy_code']);
	    		$("#client_id").val(arr[0]['client_id']);
	    		$("#barangay_id").val(arr[0]['barangay_id']);
	    		$("#brgy_name").val(arr[0]['brgy_name']);
	    		$("#busn_bldg_area").val(arr[0]['busn_bldg_area']);
	    		$("#bend_id").val(arr[0]['bendId']);
	    		$("#busn_bldg_total_floor_area").val(arr[0]['busn_bldg_total_floor_area']);
	    		$("#p_tin_no").val(arr[0]['p_tin_no']);
	    		if(arr[0]['ba_building_is_owned'] =='1'){ $("#Owned").prop("checked", true);              }
	    		else{ $("#Rented").prop("checked", true);  }	
	    		$("#profile_id").val(arr[0]['profile_id']);
	    		$("#application_id").val(arr[0]['id']);

	    		$("#accountnumber").val(arr[0]['ba_business_account_no']);
	    		$("#ba_registration_ctc_no").val(arr[0]['ba_registration_ctc_no']);
	    		$("#ba_registration_ctc_issued_date").val(arr[0]['ba_registration_ctc_issued_date']);
	    		$("#ba_registration_ctc_place_of_issuance").val(arr[0]['ba_registration_ctc_place_of_issuance']);
	    		$("#ba_registration_ctc_amount_paid").val(arr[0]['ba_registration_ctc_amount_paid']);
	    		$("#ba_locational_clearance_no").val(arr[0]['ba_locational_clearance_no']);
	    		$("#ba_locational_clearance_date_issued").val(arr[0]['ba_locational_clearance_date_issued']);
	    		$("#ba_bureau_domestic_trade_no").val(arr[0]['ba_bureau_domestic_trade_no']);
	    		$("#ba_bureau_domestic_trade_date_issued").val(arr[0]['ba_bureau_domestic_trade_date_issued']);
	    		$("#ba_sec_registration_no").val(arr[0]['ba_sec_registration_no']);
	    		$("#ba_sec_registration_date_issued").val(arr[0]['ba_sec_registration_date_issued']);
	    		$("#ba_dti_no").val(arr[0]['ba_dti_no']);
	    		$("#ba_dti_date_issued").val(arr[0]['ba_dti_date_issued']);
	    		$("#ba_building_property_index_number").val(arr[0]['ba_building_property_index_number']);
	    		
	    		$("#p_full_name").val(arr[0]['p_first_name']+' '+arr[0]['p_middle_name']+' '+arr[0]['p_family_name']);
	    		$("#bff_date2").val(arr[0]['applicationdate']);
	    		$("#ba_code").val(arr[0]['ba_code']);
	    		$("#p_code").val(arr[0]['p_code']);
	    		$("#app_type").val(arr[0]['app_type_id']);
	    		$("#ba_taxable_owned_truck_wheeler_10above").val(arr[0]['ba_taxable_owned_truck_wheeler_10above']);
	    		$("#ba_taxable_owned_truck_wheeler_6above").val(arr[0]['ba_taxable_owned_truck_wheeler_6above']);
	    		$("#ba_taxable_owned_truck_wheeler_4above").val(arr[0]['ba_taxable_owned_truck_wheeler_4above']);
	    	}
	    }
	});

}
 // $(".radio").change(function() {
 //             var checked = $(this).is(':checked');
 //             $(".radio").prop('checked',false);
 //             $(".code").prop('checked',false);
 //             if(checked) {
 //             $(this).prop('checked',true);
 //             }
 //             });


