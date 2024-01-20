$(document).ready(function(){
	var yearpickerInput = $('#addPreviousOwnerForBuildingModal').find('input[name="rp_building_completed_year"]').val();
	$('#addPreviousOwnerForBuildingModal').find('.yearpicker').yearpicker({dropdownAutoWidth: false, dropdownParent: $("#year")});
    //$('#addPreviousOwnerForBuildingModal').find("#profile_id").select3({dropdownAutoWidth : false,dropdownParent: $("#owner")});
    //$('#addPreviousOwnerForBuildingModal').find("#rp_code_lref").select3({dropdownAutoWidth : false,dropdownParent: $("#tax")});
    $('#addPreviousOwnerForBuildingModal #rp_app_cancel_by_td_id_pre').select3({dropdownParent : '#addPreviousOwnerForBuildingModal'});
   // $('#addPreviousOwnerForBuildingModal').find("select[name=permit_id]").select3({dropdownAutoWidth : false,dropdownParent: $('#addPreviousOwnerForBuildingModal').find("select[name=permit_id]").parent()});
    $('#addPreviousOwnerForBuildingModal #rp_app_taxability_pre').select3({dropdownParent : $('#rp_app_taxability_pre').parent()});
	$('#addPreviousOwnerForBuildingModal #rp_app_effective_quarter_pre').select3({dropdownParent : $('#rp_app_effective_quarter_pre').parent()});
	$('#addPreviousOwnerForBuildingModal #rp_app_approved_by_pre').select3({dropdownParent : $('#rp_app_approved_by_pre').parent()});
	setTimeout(function(){ 
		    	setManualPermit();
			        }, 500);
	initiateTdRemoteSelectList(0);
	$('#addPreviousOwnerForBuildingModal').find("#permit_id_pre").select3({
    placeholder: 'Select Building Permit',
    allowClear: true,
    dropdownParent: $('#addPreviousOwnerForBuildingModal').find("#permit_id_pre").parent(),
    ajax: {
        url: DIR+'rptbuilding/getremotedataforpermits',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
    autoFillMainFormPre($('#addPreviousOwnerForBuildingModal').find('input[name=id]').val());
    $('#addPreviousOwnerForBuildingModal').find("#profile_id_pre").select3({
    placeholder: 'Select Property Owner',
    allowClear: true,
    dropdownParent: $('#addPreviousOwnerForBuildingModal').find("#profile_id_pre").parent(),
    ajax: {
        url: DIR+'rptpropertyowner/getallclients',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
	$('#addPreviousOwnerForBuildingModal').find("#property_administrator_id_pre").select3({
    placeholder: 'Select Property Administrator',
    allowClear: true,
    dropdownParent: $('#addPreviousOwnerForBuildingModal').find("#property_administrator_id_pre").parent(),
    ajax: {
        url: DIR+'rptpropertyowner/getallclients',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
    loadPreviousOwnerTdDetails();
    $('#addPreviousOwnerForBuildingModal').off('click','#newAddedAssessementSummary tbody tr').on('click','#newAddedAssessementSummary tbody tr',function(){
    	var text = $(this).find('.property_kind').text();
    	//alert(text);
    	if(text != ''){
    		if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    $('#addPreviousOwnerForBuildingModal').find('#newAddedAssessementSummary tbody tr').removeClass('selected');
                    $(this).addClass('selected');
                }
    	}
    });

	 $('#addPreviousOwnerForBuildingModal').off('change','#rp_building_gf_area').on('change','#rp_building_gf_area',function(){
		if($('#addPreviousOwnerForBuildingModal').find("#rp_building_total_area").val() =="" || $('#addPreviousOwnerForBuildingModal').find("#rp_building_total_area").val() == 0){
			$('#addPreviousOwnerForBuildingModal').find("#rp_building_total_area").val($('#addPreviousOwnerForBuildingModal').find("#rp_building_gf_area").val());
		}
	})
	 if($('#addPreviousOwnerForBuildingModal').find("#rvy_revision_year_id").val()>0 && $('#addPreviousOwnerForBuildingModal').find("input[name=id]").val() == 0){
		getRvyRevisionYearDetailsPre($('#addPreviousOwnerForBuildingModal').find("#rvy_revision_year_id").val()); 
	}

	 $('#addPreviousOwnerForBuildingModal').off('change','#addPreviousOwnerForLandModal #rp_app_cancel_by_td_id_pre').on('change','#addPreviousOwnerForLandModal #rp_app_cancel_by_td_id_pre',function(){
       loadPreviousOwnerTdDetails();
	});

	$('#addPreviousOwnerForBuildingModal').off('change','#rp_occupied_month').on('change','#rp_occupied_month',function(){
		var occudate = $('#addPreviousOwnerForBuildingModal').find("#rp_occupied_month").val();
		occudate = occudate +'-01';
		var dob = new Date(occudate);
		var today = new Date();
		var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
		age =Math.abs(age);
		$('#addPreviousOwnerForBuildingModal').find('#rp_building_age').val(age);
	})
	$('#addPreviousOwnerForBuildingModal').off('change','#rp_building_completed_year').on('change','#rp_building_completed_year',function(){
			$('#addPreviousOwnerForBuildingModal').find('#rp_building_completed_percent').val('100');
	})

    $('#addPreviousOwnerForBuildingModal').off('change','#profile_id_pre').on('change','#profile_id_pre',function(){
    	var id=$(this).val();
    	var propId = $('#addPreviousOwnerForBuildingModal').find('#id').val();
    	if($('#addPreviousOwnerForBuildingModal').find('#old_property_id').val() == '' && propId == 0){
    		initiateTdRemoteSelectList(id);
			$('#addPreviousOwnerForBuildingModal').find('.myCheckbox').prop('checked', true);
    	}
	});
	
    $('#addPreviousOwnerForBuildingModal').on('click','.manual_entry',function() {
		setManualPermit();
	});

	$('#addPreviousOwnerForBuildingModal').on('click','.myCheckbox',function() {
		if($(this).is(":checked")) {
		    var id=$('#addPreviousOwnerForBuildingModal').find("#profile_id_pre").val();
			initiateTdRemoteSelectList(id);
	    } else {
	      initiateTdRemoteSelectList(0);
	    }
	}); 
	
    $('#addPreviousOwnerForBuildingModal').off('change','#rp_code_lref_pre').on('change','#rp_code_lref_pre',function(){
		var id=$(this).val();
		taxDeclarationIdPre(id);
		//createPinSuffix(id, $('#addPreviousOwnerForBuildingModal').find('#id').val());
	});

    if($('#addPreviousOwnerForBuildingModal').find("#rp_code_lref_pre").val()>0){
		taxDeclarationIdPre($('#addPreviousOwnerForBuildingModal').find("#rp_code_lref_pre").val());
    }

    $('#addPreviousOwnerForBuildingModal').off('click','.displayAdditionalItensForDepreciation').on('click','.displayAdditionalItensForDepreciation',function(){
    	var modal = $(this).data('target');
    	$(modal).modal('show');
    });

    $('#addPreviousOwnerForBuildingModal').find('#floorValueDepreciationModal').on('hidden.bs.modal', function () {
       loadAssessementSumaryPre($('#addPreviousOwnerForBuildingModal').find('input[name=id]').val());
    });

    $('#addPreviousOwnerForBuildingModal').find('#floorValueModal').on('hidden.bs.modal', function () {
       autoFillMainFormPre($('#addPreviousOwnerForBuildingModal').find('input[name=id]').val());
       loadAssessementSumaryPre($('#addPreviousOwnerForBuildingModal').find('input[name=id]').val());
    });

	$('#addPreviousOwnerForBuildingModal').on('click','.cancelMoreAdditionalItemsForFloorValue',function(){
		$(this).closest(".removeactivitydata").remove();
	});
	
	$('#addPreviousOwnerForBuildingModal').off('click',"#addMoreAdditionalItemsForFloorValue").on('click',"#addMoreAdditionalItemsForFloorValue",function(){
		$('html, body').stop().animate({
      scrollTop: $('#addPreviousOwnerForBuildingModal').find("#addMoreAdditionalItemsForFloorValue").offset().top
    }, 600);
		addmoreAdditionalItemsPre();
	});


	$('#addPreviousOwnerForBuildingModal').find('.numeric').numeric();
	$('#addPreviousOwnerForBuildingModal').off('click',".btn_cancel_activity").on('click',".btn_cancel_activity",function(){
		 $(this).closest(".removeactivitydata").remove();
	});

	//$('#addPreviousOwnerForBuildingModal').find(".profile_id").select3({dropdownAutoWidth : false,dropdownParent : '#addPreviousOwnerForBuildingModal'});
	//$('#addPreviousOwnerForBuildingModal').find(".property_administrator_id").select3({dropdownAutoWidth : false,dropdownParent : '#addPreviousOwnerForBuildingModal'});
	$('#addPreviousOwnerForBuildingModal').find(".rvy_revision_year_id").select3({dropdownAutoWidth : false,dropdownParent : $('#addPreviousOwnerForBuildingModal').find('.rvy_revision_year_id').parent()});
	$('#addPreviousOwnerForBuildingModal').find(".bk_building_kind_code").select3({dropdownAutoWidth : false,dropdownParent : $('#addPreviousOwnerForBuildingModal').find(".bk_building_kind_code").parent()});
	$('#addPreviousOwnerForBuildingModal').find(".pc_class_code").select3({dropdownAutoWidth : false,dropdownParent : $('#addPreviousOwnerForBuildingModal').find(".pc_class_code").parent()});
    $('#addPreviousOwnerForBuildingModal').find(".brgy_code_id").select3({dropdownAutoWidth : false,dropdownParent : '#addPreviousOwnerForBuildingModal'});
    $('#addPreviousOwnerForBuildingModal').find(".bei_extra_item_code").select3({dropdownAutoWidth : false,dropdownParent : $('#addPreviousOwnerForBuildingModal').find(".bei_extra_item_code").parent()});
    
    $('#addPreviousOwnerForBuildingModal').on('change','.bei_extra_item_code',function(){
    	var text = $(this).find(':selected').text();
    	const myArray = text.split("-");
    	if (typeof myArray[1] !== 'undefined') {
         $(this).closest('.removeactivitydata').find('.bei_extra_item_desc').val(myArray[1]);
         }
    })

	$('#addPreviousOwnerForBuildingModal').on('click','.refeshbuttonselect2',function(){
    	showLoader();
		$.ajax({
			type: "GET",
			url: DIR+'rptbuilding/get-all-profiles',
			dataType: "html",
			success: function(html){
				html = JSON.parse(html);
				let $data = `<option>Select Name</option>`;
				html.forEach((element, index) => {
					$data+=`<option value="${element.id}">${element.standard_name}</option>`;
				});
				$('#addPreviousOwnerForBuildingModal').find('.profile_id_pre').html($data);
				hideLoader();
			},
			error: function(err){
				console.log(err);
				hideLoader();
			}
		});
    })
	$('#addPreviousOwnerForBuildingModal').on('click','.refeshbuttonselect',function(){
    	showLoader();
		$.ajax({
			type: "GET",
			url: DIR+'rptbuilding/get-all-profiles',
			dataType: "html",
			success: function(html){
				html = JSON.parse(html);
				let $data = `<option>Select Name</option>`;
				html.forEach((element, index) => {
					$data+=`<option value="${element.id}">${element.standard_name}</option>`;
				});
				$('#addPreviousOwnerForBuildingModal').find('.property_administrator_id_pre').html($data);
				hideLoader();
			},
			error: function(err){
				console.log(err);
				hideLoader();
			}
		});
    })


    $('#addPreviousOwnerForBuildingModal').on('keyup', 'input[name="rpbfv_floor_area"]',function(){
    	calculateDataForOtherFieldsPre();
    });
    $('#addPreviousOwnerForBuildingModal').on('keyup', '.depreciation_rate_depreciationmodal',function(){
    	var dep = $(this).val();
    	if(isNaN(dep) || dep == ''){
    		dep = 0;
    	}
    	dep = parseFloat(dep);
    	var totalMarketValue = parseFloat($('#addPreviousOwnerForBuildingModal').find('#floorValueDepreciationModal').find('input[name=total_market_value_of_floor]').val());
    	
    	var accumulatedVal   = (dep*totalMarketValue)/100;
    	var totalDepreciatedValue = totalMarketValue-accumulatedVal;
    	$('#addPreviousOwnerForBuildingModal').find('#floorValueDepreciationModal').find('.accumaultatedValue').val(numberWithCommas(parseFloat(accumulatedVal).toFixed(2)));
    	$('#addPreviousOwnerForBuildingModal').find('#floorValueDepreciationModal').find('.rpbfv_total_floor_market_value_temp').val(numberWithCommas(parseFloat(totalDepreciatedValue).toFixed(2)));
    	$('#addPreviousOwnerForBuildingModal').find('input[name=rp_depreciation_rate]').val(dep);

    });
    $('#addPreviousOwnerForBuildingModal').find('input[name="rpbfv_floor_area"]').unbind('keyup');

     $('#addPreviousOwnerForBuildingModal').on('keyup', 'input[name="rpbfv_floor_additional_value"]',function(){
    	calculateDataForOtherFieldsPre();
    });
     $('#addPreviousOwnerForBuildingModal').find('input[name="rpbfv_floor_additional_value"]').unbind('keyup');

     $('#addPreviousOwnerForBuildingModal').on('keyup', 'input[name="rpbfv_floor_adjustment_value"]',function(){
    	calculateDataForOtherFieldsPre();
    });
     $('#addPreviousOwnerForBuildingModal').find('input[name="rpbfv_floor_adjustment_value"]').unbind('keyup');
	/* Land Appraisal Value Adjustment Factors*/

	$('#addPreviousOwnerForBuildingModal').find("#loadLandApprisalForm").unbind("click");
	$('#addPreviousOwnerForBuildingModal').off('click',"#loadLandApprisalForm").on('click',"#loadLandApprisalForm",function(){
		$('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').modal('show');
		loadAddLandAppraisalFormPre(id = '');
	});


    var propertyId = $('#addPreviousOwnerForBuildingModal').find('input[name=id]').val();
	loadAssessementSumaryPre(propertyId);
	commonFunctionPre();
	$('#addPreviousOwnerForBuildingModal').on('change','#profile_id_pre',function(){
		var id=$(this).val();
		if(id){ getprofiledataPre(id); }

	})
	$('#addPreviousOwnerForBuildingModal').on('change','#property_administrator_id_pre',function(){
		var id=$(this).val();
		if(id){ getAdminprofiledataPre(id); }

	})
	$('#addPreviousOwnerForBuildingModal').on('change',"#rvy_revision_year_id",function(){
		var id=$(this).val();
		if(id){ 
			getRvyRevisionYearDetailsPre(id); 
			}

	})
	$('#addPreviousOwnerForBuildingModal').off('click',"#brgy_code_id").on('click',"#brgy_code_id",function(){
		var id=$(this).val(); 
		var updateCode = $('#addPreviousOwnerForBuildingModal').find('input[name=update_code]').val();
		getbarangayaDetailsPre(id); 

	})
	if($('#addPreviousOwnerForBuildingModal').find("#profile").val()>0){
		getprofiledataPre($('#addPreviousOwnerForBuildingModal').find("#profile").val());
	}
	if($('#addPreviousOwnerForBuildingModal').find('input[name=id]').val() == 0){
		getbarangayaDetailsPre($('#addPreviousOwnerForBuildingModal').find('#brgy_code_id').val());
	}
	if($('#addPreviousOwnerForBuildingModal').find("#property_administrator_id_pre").val()>0){
		getAdminprofiledataPre($('#addPreviousOwnerForBuildingModal').find("#property_administrator_id_pre").val());
	}
   
});


function getbarangayaDetailsPre(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptproperty/getbarangycodedetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$('#addPreviousOwnerForBuildingModal').find('input[name=loc_group_brgy_no]').val(html.brgy_name);
	    	//$('textarea[name=rp_location_number_n_street]').val(html.brgy_name+', '+html.mun_desc);
	    	$('#addPreviousOwnerForBuildingModal').find('input[name=brgy_code]').val(html.brgy_code);
	    	$('#addPreviousOwnerForBuildingModal').find('input[name=dist_code]').val(html.dist_code);
	    	$('#addPreviousOwnerForBuildingModal').find('input[name=dist_code_name]').val(html.dist_code+'-'+html.dist_name);
	    	$('#addPreviousOwnerForBuildingModal').find('input[name=brgy_code_and_desc]').val(html.brgy_code+'-'+html.brgy_name);
	    	$('#addPreviousOwnerForBuildingModal').find('input[name=loc_local_code_name]').val(html.mun_desc);
	    	$('#addPreviousOwnerForBuildingModal').find('input[name=loc_local_code]').val(html.loc_local_code_id);
	    }
	});
}

function getAdminprofiledataPre(id){
	$('.loadingGIF').show();
	var filtervars = {
	    pid:id,
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptproperty/getprofiledata',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	arr = $.parseJSON(html);
	    		$('#addPreviousOwnerForBuildingModal').find('input[name="rp_administrator_code"]').val(arr.id);
	    		$('#addPreviousOwnerForBuildingModal').find('input[name="rp_administrator_code_address"]').val(arr.standard_address);
	    	
	    }
	});
}

$('#addPreviousOwnerForBuildingModal').off('change','.searchlandDetails').on('change','.searchlandDetails',function(){
	var brgy = $('#addPreviousOwnerForBuildingModal').find('#brgy_code_id').val();
	var tdNo = $(this).val();
	showLoader();
	var url = $(this).data('url');
	$('.loadingGIF').show();
	var filtervars = {
	    rp_td_no_lref:tdNo,
	    brgy_code_id:brgy
	}; 
	$.ajax({
	    type: "post",
	    url: url,
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.validate-err').html('');
	    	hideLoader();
	    	if(html.status == 'validation_error'){
	    		$('#addPreviousOwnerForBuildingModal').find('.validate-err').html('');
                $('#addPreviousOwnerForBuildingModal').find('#err_'+html.field_name).html(html.error);
                $('#addPreviousOwnerForBuildingModal').find('.'+html.field_name).focus();
	    		$('#propertyPreviousOwnerForm').find('input[name=rp_code_lref]').val('');
	    		$('#propertyPreviousOwnerForm').find('input[name=rp_suffix_lref]').val('');
	    		$('#propertyPreviousOwnerForm').find('input[name=rpo_code_lref]').val('');
	    		$('#propertyPreviousOwnerForm').find('input[name=rp_oct_tct_cloa_no_lref]').val('');
	    		$('#propertyPreviousOwnerForm').find('input[name=land_owner]').val('');
	    		$('#propertyPreviousOwnerForm').find('input[name=land_location]').val('');
	    		$('#propertyPreviousOwnerForm').find('input[name=rp_cadastral_lot_no_lref]').val('');
	    		$('#propertyPreviousOwnerForm').find('input[name=rp_total_land_area]').val('');
	    	}if(html.status == 'success'){
	    		$('.validate-err').html('');
	    		$('#propertyPreviousOwnerForm').find('input[name=rp_code_lref]').val(html.data.rp_td_no_lref);
	    		$('#propertyPreviousOwnerForm').find('input[name=rp_suffix_lref]').val(html.data.rp_suffix_lref);
	    		$('#propertyPreviousOwnerForm').find('input[name=rpo_code_lref]').val(html.data.rpo_code_lref);
	    		$('#propertyPreviousOwnerForm').find('input[name=rp_oct_tct_cloa_no_lref]').val(html.data.rp_oct_tct_cloa_no_lref);
	    		$('#propertyPreviousOwnerForm').find('input[name=land_owner]').val(html.data.land_owner);
	    		$('#propertyPreviousOwnerForm').find('input[name=land_location]').val(html.data.land_location);
	    		$('#propertyPreviousOwnerForm').find('input[name=rp_cadastral_lot_no_lref]').val(html.data.rp_cadastral_lot_no_lref);
	    		$('#propertyPreviousOwnerForm').find('input[name=rp_total_land_area]').val(parseFloat(html.data.rp_total_land_area).toFixed(3));
	    	}
	    	
	    },error:function(){
	    	    $('#propertyPreviousOwnerForm').find('input[name=rp_code_lref]').val('');
	    		$('#propertyPreviousOwnerForm').find('input[name=rp_suffix_lref]').val('');
	    		$('#propertyPreviousOwnerForm').find('input[name=rpo_code_lref]').val('');
	    		$('#propertyPreviousOwnerForm').find('input[name=rp_oct_tct_cloa_no_lref]').val('');
	    		$('#propertyPreviousOwnerForm').find('input[name=land_owner]').val('');
	    		$('#propertyPreviousOwnerForm').find('input[name=land_location]').val('');
	    		$('#propertyPreviousOwnerForm').find('input[name=rp_cadastral_lot_no_lref]').val('');
	    		$('#propertyPreviousOwnerForm').find('input[name=rp_total_land_area]').val('');
	    	$('#addPreviousOwnerForBuildingModal').find('.validate-err').html('');
	    	hideLoader();
	    }
	});
});


function getRvyRevisionYearDetailsPre(id){
	$('.loadingGIF').show();
	var filtervars = {
	    pid:id,
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptproperty/getrevisionyeardetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$('#addPreviousOwnerForBuildingModal').find('input[name="rvy_revision_year"]').val(html.rvy_revision_year);
	    	$('#addPreviousOwnerForBuildingModal').find('input[name="rvy_revision_code"]').val(html.rvy_revision_code);
	    	$('#addPreviousOwnerForBuildingModal').find('input[name="rp_app_effective_year"]').val(html.rvy_revision_year);
	    }
	});
}

function getprofiledataPre(id){
	$('.loadingGIF').show();
	var filtervars = {
	    pid:id,
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptproperty/getprofiledata',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	arr = $.parseJSON(html);
	    		$('#addPreviousOwnerForBuildingModal').find('input[name="property_owner_address"]').val(arr.standard_address);
	    		$('#addPreviousOwnerForBuildingModal').find('input[name="rpo_code"]').val(arr.id);
	    }
	});
}


function deleteLandAppraisalPre(id, sessionId) {
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    "_token": $("#_csrf_token").val()
	}; 
	$.ajax({
	    type: "post",
	    url: DIR+'rptbuilding/deletefloorvalue',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	loadFloorValuesPre($('#addPreviousOwnerForBuildingModal').find('input[name=id]').val());
	    }
	});
}


function commonFunctionPre(){

	$('#addPreviousOwnerForBuildingModal').off('click','.deleteLandAppraisal').on('click','.deleteLandAppraisal',function(){
		var type = $(this).attr('type');
		var landAppraisalId = $(this).data('id');
		
        var mid = $(this).attr('mid');
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
			          if(typeof landAppraisalId !== 'undefined' ){
			          	deleteLandAppraisalPre(landAppraisalId);
			          }
			          calculateTotalMarketValuePre();
			        }
			    })
		
	});

	$('#addPreviousOwnerForBuildingModal').find("#loadFloorValueForm").unbind("click");
	$('#addPreviousOwnerForBuildingModal').off('click',"#loadFloorValueForm").on('click',"#loadFloorValueForm",function(){
		$('#addPreviousOwnerForBuildingModal').find('#addFloorValueFormmodal').modal('show');
		loadAddFloorValueFormPre(id = '');
	}); 

	$('#addPreviousOwnerForBuildingModal').find("#loadStructuralCharacter").unbind("click");
	$('#addPreviousOwnerForBuildingModal').off('click',"#loadStructuralCharacter").on('click',"#loadStructuralCharacter",function(){
		$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').modal('show');
		loadBuildingStructureFormPre(id = '');
		
		$('#addPreviousOwnerForBuildingModal').on('click','.addStructuralCharacterModal',function(){
			$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').modal('hide');
		});
	}); 
 

	$('#addPreviousOwnerForBuildingModal').find("#displayFloorValueModal").unbind("click");
	$('#addPreviousOwnerForBuildingModal').on('click','#displayFloorValueModal',function(){
		if($('#addPreviousOwnerForBuildingModal').find('select[name=bk_building_kind_code]').val() == ''){
			$('#addPreviousOwnerForBuildingModal').find('#err_pc_class_code').html('');
			$('#addPreviousOwnerForBuildingModal').find('#err_bk_building_kind_code').html('');
			$('#addPreviousOwnerForBuildingModal').find('#err_bk_building_kind_code').html('Required Field');
		}else if($('#addPreviousOwnerForBuildingModal').find('select[name=pc_class_code]').val() == ''){
			$('#addPreviousOwnerForBuildingModal').find('#err_pc_class_code').html('');
			$('#addPreviousOwnerForBuildingModal').find('#err_bk_building_kind_code').html('');
			$('#addPreviousOwnerForBuildingModal').find('#err_pc_class_code').html('Required Field');
		}else{
			$('#addPreviousOwnerForBuildingModal').find('#err_pc_class_code').html('');
			$('#addPreviousOwnerForBuildingModal').find('#err_bk_building_kind_code').html('');
			var propertyId = $(this).data('propertyid');
		    $('#addPreviousOwnerForBuildingModal').find('#floorValueModal').modal('show');
		    saveFloorValuePre(propertyId);
		}
        
	});

	$('#addPreviousOwnerForBuildingModal').find("#displayFloorValueDepreciationModal").unbind("click");
	$('#addPreviousOwnerForBuildingModal').on('click','#displayFloorValueDepreciationModal',function(){
		var propertyId = $(this).data('propertyid');
		var actualUse  = $('#addPreviousOwnerForBuildingModal').find('#newAddedAssessementSummary tbody').find('tr.selected').data('id');
		var depreciation = $('#addPreviousOwnerForBuildingModal').find('input[name=rp_depreciation_rate]').val();
		//console.log(actualUse.data('id'));
		if(actualUse === undefined){
			$('#addPreviousOwnerForBuildingModal').find('#selectAtLeastOneSummary').html('');
			$('#addPreviousOwnerForBuildingModal').find('#selectAtLeastOneSummary').html('Please select at least one assessement summary to continue');
		}else{
			$('#addPreviousOwnerForBuildingModal').find('#selectAtLeastOneSummary').html('');
			$('#addPreviousOwnerForBuildingModal').find('#floorValueDepreciationModal').modal('show');
		saveFloorDepreciationValuePre(propertyId,actualUse,depreciation);
		}
        
	});

	$('#addPreviousOwnerForBuildingModal').off('change','.bt_building_type_code').on('change','.bt_building_type_code',function(){
		var text = $(this).find(':selected').text();
		$('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').find('.bt_building_type_code_desc').val(text);
			getLandUnitValuePre();

		})
	$('#addPreviousOwnerForBuildingModal').off('change','.pau_actual_use_code').on('change','.pau_actual_use_code',function(){
		var text = $(this).find(':selected').text();
		$('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').find('.pau_actual_use_code_desc').val(text);
			calculateDataForOtherFieldsPre();
		})

	$('#addPreviousOwnerForBuildingModal').on('keyup','.rpbfv_total_floor_market_value',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
		});

	$('#addPreviousOwnerForBuildingModal').on('keyup','.rp_building_age',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
		});
	$('#addPreviousOwnerForBuildingModal').on('keyup','.rp_building_completed_percent',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
		});
	$('#addPreviousOwnerForBuildingModal').on('keyup','.rp_building_gf_area',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
		});
    $('#addPreviousOwnerForBuildingModal').on('keyup','.decimalvalue',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
		});
	$('#addPreviousOwnerForBuildingModal').on('keyup','.rpa_total_land_area',function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
       calculateLandMarketBaseValuePre();
    });

    $('#addPreviousOwnerForBuildingModal').on('keyup','.rpa_adjusted_market_value',function () { 
      this.value = this.value.replace(/[^0-9\.]/g,'');
    });	

    $('#addPreviousOwnerForBuildingModal').on('keyup','.lav_strip_unit_value',function () { 
       this.value = this.value.replace(/[^0-9\.]/g,'');
    });

	$('#addPreviousOwnerForBuildingModal').off('click','.editLandAppraisal').on('click','.editLandAppraisal',function(){
            var id        = $(this).data('id');
            var sessionId = $(this).data('sessionid');
            $('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').modal('show');
		    loadAddLandAppraisalFormPre(id, sessionId);
            
		});

	$('#addPreviousOwnerForBuildingModal').off('submit','#propertyPreviousOwnerForm').on('submit','#propertyPreviousOwnerForm',function(e){
		showLoader();
		e.preventDefault();
		var url = $(this).attr('action');
		var method = $(this).attr('method');
		var data   = $(this).serialize();
		$.ajax({
	    type: "POST",
	    url: url,
	    data: data,
	    dataType: "json",
	    success: function(html){ 
	    	hideLoader();
	    	if(html.status == 'success'){
	    		$('#addPreviousOwnerForBuildingModal').modal('hide');
	    		Swal.fire({
                      position: 'center',
                      icon: 'success',
                      title: html.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                          	var propertyId = $('#propertyTaxDeclarationForm').find('input[name=id]').val();
                            var updateCode = $('#propertyTaxDeclarationForm').find('input[name=uc_code]').val();
                            saveApprovalFormData(propertyId,updateCode);
                          }
                   });
	    	}if(html.status == 'error'){
	    		Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: false
                    })
	    		
	    	}
	    },error:function(){
	    	hideLoader();
	    }
	});
	});

	$('#addPreviousOwnerForBuildingModal').off('submit','#storefloorbuildval').on('submit','#storefloorbuildval',function(e){
			e.preventDefault();
			e.stopPropagation();
			var url = $(this).attr('action');
			var method = $(this).attr('method');
			var data   = $(this).serialize();
			$.ajax({
	    type: "post",
	    url: url,
	    data: data,
	    dataType: "json",
	    success: function(html){ 
	    	if(html.status == 'validation_error'){
	    		$('#addPreviousOwnerForBuildingModal').find('.validate-err').html('');
                    $('#addPreviousOwnerForBuildingModal').find('#err_'+html.field_name).html(html.error);
                    $('#addPreviousOwnerForBuildingModal').find('.'+html.field_name).focus();
	    	}if(html.status == 'success'){
	    		$('#addPreviousOwnerForBuildingModal').find('.validate-err').html('');
	    		$('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').modal('hide');
	    		loadLandAppraisalPre($('#addPreviousOwnerForBuildingModal').find('input[name=id]').val());
	    		loadAssessementSumaryPre($('#addPreviousOwnerForBuildingModal').find('input[name=id]').val());
	    	}
	    }
	     });
		});

	$('#addPreviousOwnerForBuildingModal').off('submit','#storeBuildingStructural').on('submit','#storeBuildingStructural',function(e){
			e.preventDefault();
			e.stopPropagation();
			var url = $(this).attr('action');
			var method = $(this).attr('method');
			var data   = $(this).serialize();
			$.ajax({
	    type: "post",
	    url: url,
	    data: data,
	    dataType: "json",
	    success: function(html){ 
	    	if(html.status == 'validation_error'){
	    		$('#addPreviousOwnerForBuildingModal').find('.validate-err').html('');
                $('#addPreviousOwnerForBuildingModal').find('#err_'+html.field_name).html(html.error);
                $('#addPreviousOwnerForBuildingModal').find('.'+html.field_name).focus();
	    	}if(html.status == 'success'){
	    		$('#addPreviousOwnerForBuildingModal').find('.validate-err').html('');
	    		$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').modal('hide');
	    	}
	    }
	     });
		});
}

function calculateTotalMarketValuePre(mid) {
	var totalMarketValue = 0;
	$('#addPreviousOwnerForBuildingModal').find('#new_added_land_apraisal').find("tbody tr .rpa_base_market_value").each(function(total){
		var marketValue = $(this).text();
		totalMarketValue += parseFloat(marketValue);

	});
	var previousValue = $('#addPreviousOwnerForBuildingModal').find('#landApraisalTotalValueToDisplay').val();
	$('#addPreviousOwnerForBuildingModal').find('#landApraisalTotalValueToDisplay').val(parseFloat(totalMarketValue).toFixed(2));
}


function saveFloorValuePre(id) {
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptbuilding/fllorvalue',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#addPreviousOwnerForBuildingModal').find('#floorValueform').html(html);
	    	var id = $('#addPreviousOwnerForBuildingModal').find('input[name=id]').val();
	    	loadFloorValuesPre(id);
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function saveFloorDepreciationValuePre(id, actualUse,depreciation) {
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    actualUse:actualUse,
	    depreciation:depreciation
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptbuilding/fllorvaluedepreciation',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#addPreviousOwnerForBuildingModal').find('#floorValueDepreciationform').html(html);
	    },error:function(){
	    	hideLoader();
	    }
	});
}


function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function loadAddLandAppraisalFormPre(id, sessionId) {
	var buildingKind = $('#addPreviousOwnerForBuildingModal').find('select[name=bk_building_kind_code]').val();
	var classCode    = $('#addPreviousOwnerForBuildingModal').find('select[name=pc_class_code]').val();
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    buildingkind:buildingKind,
	    classId:classCode,
	    property_id:$('#addPreviousOwnerForBuildingModal').find('input[name=id]').val()
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptbuilding/storefloorvalue',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#addPreviousOwnerForBuildingModal').find('#landappraisalform').html(html);
	    	var revisionYearId = $('#addPreviousOwnerForBuildingModal').find('.rvy_revision_year_id').val();
		    var revisionYear   = $('#addPreviousOwnerForBuildingModal').find('input[name=rvy_revision_year]').val();
		    setTimeout(function(){ 
		    	$('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').find(".bt_building_type_code").select3({dropdownParent:$('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').find(".bt_building_type_code").parent()});
		    	$('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').find(".pau_actual_use_code").select3({dropdownParent:$('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').find(".pau_actual_use_code").parent()});
			    $('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').find('#additionalItemsForPreviousOwner').css('display','none');
			        }, 500);
	    },
	    error: function(){
	    	hideLoader();
	    }
	});
}


function loadAddFloorValueFormPre(id, sessionId) {
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    property_id:$('#addPreviousOwnerForBuildingModal').find('input[name=id]').val()
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptbuildingy/addfloorvaluedescription',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('#addPreviousOwnerForBuildingModal').find('#floorValueForm').html('');
	    	$('#addPreviousOwnerForBuildingModal').find('#floorValueForm').html(html);
	    	var revisionYearId = $('#addPreviousOwnerForBuildingModal').find('.rvy_revision_year_id').val();
		    var revisionYear   = $('#addPreviousOwnerForBuildingModal').find('input[name=rvy_revision_year]').val();
		    setTimeout(function(){ 
		    	$('#addPreviousOwnerForBuildingModal').find('#addFloorValueFormmodal').find(".bt_building_type_code").select3({});
		    	$('#addPreviousOwnerForBuildingModal').find('#addFloorValueFormmodal').find(".pau_actual_use_code").select3({});
			        }, 500);
	    }
	});
}

function loadBuildingStructureFormPre(id, sessionId) {
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    property_id:$('#addPreviousOwnerForBuildingModal').find('input[name=id]').val()
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptbuildingy/addbuildingstructres',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('#addPreviousOwnerForBuildingModal').find('#structuralCharacterForm').html('');
	    	$('#addPreviousOwnerForBuildingModal').find('#structuralCharacterForm').html(html);
	    	var revisionYearId = $('#addPreviousOwnerForBuildingModal').find('.rvy_revision_year_id').val();
		    var revisionYear   = $('#addPreviousOwnerForBuildingModal').find('input[name=rvy_revision_year]').val();
		    setTimeout(function(){ 
		    	$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_roof_desc1").select3({dropdownParent:$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_roof_desc1").parent()});
		    	$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_roof_desc2").select3({dropdownParent:$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_roof_desc2").parent()});
		    	$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_roof_desc3").select3({dropdownParent:$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_roof_desc3").parent()});
		    	$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_floor_desc1").select3({dropdownParent:$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_floor_desc1").parent()});
		    	$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_floor_desc2").select3({dropdownParent:$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_floor_desc2").parent()});
		    	$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_floor_desc3").select3({dropdownParent:$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_floor_desc3").parent()});
		    	$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_wall_desc1").select3({dropdownParent:$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_wall_desc1").parent()});
		    	$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_wall_desc2").select3({dropdownParent:$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_wall_desc2").parent()});
		    	$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_wall_desc3").select3({dropdownParent:$('#addPreviousOwnerForBuildingModal').find('#addStructuralCharacterModal').find(".rbf_building_wall_desc3").parent()});
			        }, 500);
	    }
	});
}

function loadPreviousOwnerTdDetails() {
	var propId = $('#addPreviousOwnerForBuildingModal #rp_app_cancel_by_td_id_pre').find("option:selected").val();
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:propId
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptproperty/getpreviousownertddetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	hideLoader();
	    	$('#addPreviousOwnerForBuildingModal').find('.prop_index_no').val(html.index_no);
	    	//$('textarea[name=rp_location_number_n_street]').val(html.brgy_name+', '+html.mun_desc);
	    	$('#addPreviousOwnerForBuildingModal').find('.tax_payer_name').val(html.tax_payer_name);
	    	$('#addPreviousOwnerForBuildingModal').find('.tax_payer_address').val(html.address);
	    	$('#addPreviousOwnerForBuildingModal').find('#previousownerlandappraisaldetails').html(html.view);
	    	$('#addPreviousOwnerForBuildingModal').find('.taxability').val(html.taxability);
	    	$('#addPreviousOwnerForBuildingModal').find('.effectivity').val(html.effectivity);
	    	$('#addPreviousOwnerForBuildingModal').find('.quarter').val(html.quarter);
	    	$('#addPreviousOwnerForBuildingModal').find('.approved_by').val(html.approvedby);
	    	$('#addPreviousOwnerForBuildingModal').find('.date').val(html.date);
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function loadAssessementSumaryPre(id) {
	var depRate = $('#addPreviousOwnerForBuildingModal').find('.rp_depreciation_rate').val();
	var classCode =  $('#addPreviousOwnerForBuildingModal').find('.pc_class_code').val();
	var brgyCode =  $('#addPreviousOwnerForBuildingModal').find('#brgy_code_id').val();
	var revisionYear =  $('#addPreviousOwnerForBuildingModal').find('#rvy_revision_year_id').val();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    class:classCode,
	    brgy:brgyCode,
	    revisionYear:revisionYear,
	    depRate:depRate
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptbuilding/loadassessementsummary',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('#addPreviousOwnerForBuildingModal').find('#assessementSummaryData').html(html);
	    }
	});
}

function enableDisableSelect3(action) {
	if($('#addPreviousOwnerForBuildingModal').find('#bk_building_kind_code').val() > 0){
		$('#addPreviousOwnerForBuildingModal').find('#bk_building_kind_code').prop('disabled',action);
        $('#addPreviousOwnerForBuildingModal').find('select[name=pc_class_code]').prop('disabled',action);
	}
}


function getAssessementLevelPre(){
	/*$('#myModal'+mid).find(".activity_id").val('');*/
	var barangay              = $('#addPreviousOwnerForBuildingModal').find('select[name=brgy_code_id]').val(); 
	var propertyKind          = $('#addPreviousOwnerForBuildingModal').find('input[name=pk_id]').val();
	var propertyClass         = $('#addPreviousOwnerForBuildingModal').find('#propertyPreviousOwnerForm').find('.pc_class_code').val();
	var propertyActualUseCode = $('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').find('.pau_actual_use_code').val();
	var propertyRevisionYear  = $('#addPreviousOwnerForBuildingModal').find('#rvy_revision_year_id').val();
	var totalMarketValue      = $('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').find('.rpbfv_total_floor_market_value').val();
	console.log(propertyKind, propertyClass, propertyActualUseCode, propertyRevisionYear);
	if(propertyKind != '' && propertyClass != '' && propertyActualUseCode !== null && propertyRevisionYear != ''){
		//alert();
		$('.loadingGIF').show();
	var filtervars = {
		barangay:barangay,
	    propertyKind:propertyKind,
	    propertyClass:propertyClass,
	    propertyActualUseCode:propertyActualUseCode,
	    propertyRevisionYear:propertyRevisionYear,
	    totalMarketValue:totalMarketValue,
	    "_token": $("#_csrf_token").val()
	}; 
	$.ajax({
	    type: "POST",
	    url: DIR+'rptproperty/getassessementlevel',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	if(html.status == 'success'){
	    	$('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').find("input[name=al_assessment_level]").val(html.data.al_assessment_level);
	    	}else{
	    	$('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').find("input[name=al_assessment_level]").val('00.00');
	    	}
	    //calculateLandAssessedValue();
	    	
	    }
	});
	}
}

function calculateLandMarketBaseValuePre() {
	var buildingUnitValue = $('#addPreviousOwnerForBuildingModal').find('#addFloorValueFormmodal').find('.rpbfv_floor_unit_value').val();
    var tatalLandArea    = $('#addPreviousOwnerForBuildingModal').find('#addFloorValueFormmodal').find('.rpbfv_floor_area').val();
    var totalMarketValue = tatalLandArea*buildingUnitValue;
    $('#addPreviousOwnerForBuildingModal').find('#addFloorValueFormmodal').find('.base_market_value').val(parseFloat(totalMarketValue).toFixed(2));
    $('#addPreviousOwnerForBuildingModal').find('#addFloorValueFormmodal').find('.rpbfv_total_floor_market_value').val(parseFloat(totalMarketValue).toFixed(2));
}

function getLandUnitValuePre(classId = '', subClassId = '', actualUseCodeId = ''){
	var baranGy        = $('#addPreviousOwnerForBuildingModal').find('#brgy_code_id').val();
	var revisionYearId = $('#addPreviousOwnerForBuildingModal').find('#rvy_revision_year_id').val();
	var bt_building_type_code     = $('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').find('.bt_building_type_code').val();
	var buildingKing   = $('#addPreviousOwnerForBuildingModal').find('#propertyPreviousOwnerForm').find('.bk_building_kind_code').val();
	if(baranGy != '' && revisionYearId != '' && bt_building_type_code != '' && buildingKing != ''){
		showLoader();
	var filtervars = {
	    baranGy:baranGy,
	    revisionYearId:revisionYearId,
	    bt_building_type_code:bt_building_type_code,
	    buildingKing:buildingKing,
	    "_token": $("#_csrf_token").val()
	}; 
	$.ajax({
	    type: "POST",
	    url: DIR+'rptbuilding/getbuildingunitvalue',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
            hideLoader();
	    	if(html.status == 'success'){
	    	$('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').find(".rpbfv_floor_unit_value").val(parseFloat(html.data.buv_minimum_unit_value).toFixed(2));
	    	calculateDataForOtherFieldsPre();
	    	}else{
	    		$('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').find(".rpbfv_floor_unit_value").val(parseFloat(0).toFixed(2));
	    	calculateDataForOtherFieldsPre();
	    	}
	    },error:function(){
	    	hideLoader();
	    }
	});
	}
}

function isNumber(evt, element) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (            
        (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
        (charCode < 48 || charCode > 57))
        return false;
        return true;
}


$('#addPreviousOwnerForBuildingModal').off('submit','#storelandappraisal').on('submit','#storelandappraisal',function(e){
		    showLoader();
			e.preventDefault();
			e.stopPropagation();
			var url = $(this).attr('action');
			var method = $(this).attr('method');
			var data   = $(this).serialize();
			$.ajax({
	    type: "post",
	    url: url,
	    data: data,
	    dataType: "json",
	    success: function(html){ 
	    	hideLoader();
	    	if(html.status == 'validation_error'){
	    		$('#addPreviousOwnerForBuildingModal').find('.validate-err').html('');
                $('#addPreviousOwnerForBuildingModal').find('#err_'+html.field_name).html(html.error);
                $('#addPreviousOwnerForBuildingModal').find('.'+html.field_name).focus();
	    	}if(html.status == 'success'){
	    		$('#addPreviousOwnerForBuildingModal').find('.validate-err').html('');
	    		$('#addPreviousOwnerForBuildingModal').find('#addlandappraisalmodal').modal('hide');
	    		loadFloorValuesPre($('#addPreviousOwnerForBuildingModal').find('input[name=id]').val());
	    		loadAssessementSumaryPre($('#addPreviousOwnerForBuildingModal').find('input[name=id]').val());
	    	}if(html.status == 'error'){
	    		Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: false
                    })
	    	}
	    },error:function(){
	    	hideLoader();
	    }
	});

		});


function calculateDataForOtherFieldsPre(){
    	/* Calculate Base Market Value */
    	var floorArea          = parseFloat($('#addPreviousOwnerForBuildingModal').find('#storelandappraisal').find('.rpbfv_floor_area ').val()).toFixed(3);
	    var unitValue          = parseFloat($('#addPreviousOwnerForBuildingModal').find('#storelandappraisal').find('.rpbfv_floor_unit_value').val()).toFixed(2);
	    if(isNaN(floorArea)){
	    	floorArea = 0;
	    }if(isNaN(unitValue)){
	    	unitValue = 0;
	    }
	    var basemarketvalue    = floorArea*unitValue;
	    $('#addPreviousOwnerForBuildingModal').find('#storelandappraisal').find('.rpbfv_floor_base_market_value').val(parseFloat(basemarketvalue).toFixed(2));
	    /* Calculate Base Market Value */

	    /* Calculate Total Base Market Value */
    	var additionalVal      =  parseFloat($('#addPreviousOwnerForBuildingModal').find('#storelandappraisal').find('.rpbfv_floor_additional_value').val()).toFixed(2);
	    var adjustmentVal      =  parseFloat($('#addPreviousOwnerForBuildingModal').find('#storelandappraisal').find('.rpbfv_floor_adjustment_value').val()).toFixed(2);
	    if(isNaN(additionalVal)){
	    	additionalVal = 0;
	    }else{
            additionalVal = parseFloat(additionalVal);
	    }if(isNaN(adjustmentVal)){
	    	adjustmentVal = 0;
	    }else{
            adjustmentVal = parseFloat(adjustmentVal);
	    }
	    var totalAddiAndAdjValue = (additionalVal+adjustmentVal);
	    //alert(totalAddiAndAdjValue);
	    var toTalMarketValue     = (basemarketvalue+totalAddiAndAdjValue);
	    $('#addPreviousOwnerForBuildingModal').find('#storelandappraisal').find('.rpbfv_total_floor_market_value').val(parseFloat(toTalMarketValue).toFixed(2));
	    getAssessementLevelPre();
	    /* Calculate Total Base Market Value */

	    /* Calculate Assesment level and Assessed Value*/
	    setTimeout(function(){
	    	var assessmentLevel          = parseFloat($('#addPreviousOwnerForBuildingModal').find('#storelandappraisal').find('input[name=al_assessment_level]').val()).toFixed(2);
	    if(isNaN(assessmentLevel)){
	    	assessmentLevel = 0;
	    }
	    var assessedValue = (toTalMarketValue*assessmentLevel)/100;
	    //alert(assessmentLevel);
	    $('#addPreviousOwnerForBuildingModal').find('#storelandappraisal').find('input[name=rpb_assessed_value]').val(parseFloat(assessedValue).toFixed(2));

	     }, 500);

	    /* Calculate Assesment level and Assessed Value*/
    }

    function loadFloorValuesPre(id) {
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    "_token": $("#_csrf_token").val()
	}; 
	$.ajax({
	    type: "POST",
	    url: DIR+'rptbuilding/getfloorvalues',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#addPreviousOwnerForBuildingModal').find('#floorValueDescription').html(html);
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function setManualPermit() {
		var manualPermit = $('#addPreviousOwnerForBuildingModal').find('.manual_entry').prop('checked');
		var id = $('#addPreviousOwnerForBuildingModal').find('input[name=id]').val();
		if(manualPermit) {
			$('#addPreviousOwnerForBuildingModal').find('select[name=permit_id]').next(".select3-container").hide();
		    $('#addPreviousOwnerForBuildingModal').find('input[name=rp_bulding_permit_no]').attr('type','text');
		    //$('#commonModal').find('input[name=rp_bulding_permit_no]').val('');
	    }else {
	        $('#addPreviousOwnerForBuildingModal').find('select[name=permit_id]').next(".select3-container").show();
		    $('#addPreviousOwnerForBuildingModal').find('input[name=rp_bulding_permit_no]').attr('type','hidden');
	    }
	}

function autoFillMainFormPre(id) {
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptbuilding/autofillmainform',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	var groundArea = (html.areaOfGround > 0)?parseFloat(html.areaOfGround).toFixed(2):'';
	    	var totalArar = (html.totalArea > 0)?parseFloat(html.totalArea).toFixed(2):'';
	    	if(html.stucTypes != ''){
	    		$('#addPreviousOwnerForBuildingModal').find('input[name=buildingtype]').val(html.stucTypes);
	    	}if(html.storeys != ''){
	    		$('#addPreviousOwnerForBuildingModal').find('input[name=rp_building_no_of_storey]').val(html.storeys);
	    	}if(html.areaOfGround > 0){
	    		$('#addPreviousOwnerForBuildingModal').find('input[name=rp_building_gf_area]').val(groundArea);
	    	}if(html.totalArea > 0){
	    		$('#addPreviousOwnerForBuildingModal').find('input[name=rp_building_total_area]').val(totalArar);
	    	}
	    	if(html.disableKindOfBuilding){
	    		$('#addPreviousOwnerForBuildingModal').find('#bk_building_kind_code').find(':not(:selected)').prop('disabled',true);
	    		$('#addPreviousOwnerForBuildingModal').find('select[name=pc_class_code]').find(':not(:selected)').prop('disabled',true);
	    	}
	    },error:function(){
	    	
	    }
	});
}

/*function createPinSuffix(id, propId) {
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    propId:propId
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptbuilding/generatepinsuffix',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('input[name=rp_pin_suffix]').val(html.data.suffix);
	    },error:function(){
	    	
	    }
	});
}*/

function getTaxDePre(id,brgy_code_id,rvy_revision_year_id){
   $.ajax({
 
        url :DIR+'getTaxDeclaresionNODetails', // json datasource
        type: "POST", 
        data: {
           "id": id,
           "brgy_code_id": brgy_code_id,
           "rvy_revision_year_id": rvy_revision_year_id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $('#addPreviousOwnerForBuildingModal').find("#rp_code_lref_pre").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function getTaxDeAllPre(brgy_code_id,rvy_revision_year_id){
   $.ajax({
 
        url :DIR+'getTaxDeclaresionNODetailsAll', // json datasource
        type: "POST", 
        data: {
           "brgy_code_id": brgy_code_id,
           "rvy_revision_year_id": rvy_revision_year_id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $('#addPreviousOwnerForBuildingModal').find("#rp_code_lref_pre").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function taxDeclarationIdPre(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'taxDeclarationsId',
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

			    $('#addPreviousOwnerForBuildingModal').find("#land_owner").val(clientName);
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

			    $('#addPreviousOwnerForBuildingModal').find("#land_owner").val(clientName);
			}
	    	$('#addPreviousOwnerForBuildingModal').find("#land_location").val(html.loc_local_name)
	    	$('#propertyPreviousOwnerForm').find('input[name=rpo_code_lref]').val(html.rpo_code);
	    	$('#addPreviousOwnerForBuildingModal').find("#rp_cadastral_lot_no_lref").val(html.rp_cadastral_lot_no)
	    	$('#addPreviousOwnerForBuildingModal').find("#rp_total_land_area").val(html.rp_total_land_area)
            $('#addPreviousOwnerForBuildingModal').find("#rp_pin").val(html.rp_pin_no)
	    	$('#addPreviousOwnerForBuildingModal').find("#sectionNo").val(html.rp_section_no)
	    	$('#addPreviousOwnerForBuildingModal').find("#asslot").val(html.rp_app_assessor_lot_no)
	    	$('#propertyPreviousOwnerForm').find('input[name=rp_td_no_lref]').val(html.rp_td_no_lref);
	    	$('#propertyPreviousOwnerForm').find('input[name=rp_suffix_lref]').val(html.rp_suffix_lref);
	    	$('#propertyPreviousOwnerForm').find('input[name=rp_oct_tct_cloa_no_lref]').val(html.rp_oct_tct_cloa_no_lref);
	    	$('#propertyPreviousOwnerForm').find('input[name=rp_section_no]').val(html.rp_section_no_for_build);
	    	$('#propertyPreviousOwnerForm').find('input[name=rp_pin_no]').val(html.rp_pin_no_for_build);
	    }
	});
}

function initiateTdRemoteSelectList(rpoCode) {
 	$('#propertyPreviousOwnerForm').find("#rp_code_lref_pre").select3({
    placeholder: 'Select Tax Declaration No.',
    allowClear: true,
    dropdownParent: $('#propertyPreviousOwnerForm').find("#rp_code_lref_pre").parent(),
    ajax: {
        url: DIR+'rptproperty/gettdsforajaxselectlist',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
            	brgy_code_id:$('#propertyPreviousOwnerForm').find("#brgy_code_id").val(),
			    rvy_revision_year_id:$('#propertyPreviousOwnerForm').find("#rvy_revision_year_id").val(),
            	column:'id',
                pk_id:2,
                rpo_code:rpoCode,
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
 }