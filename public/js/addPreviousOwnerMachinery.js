$(document).ready(function(){
	if($('#addPreviousOwnerForMachineryModal').find("#old_property_id").val()>0){
		loadAssessementSumaryPre($('#addPreviousOwnerForMachineryModal').find('input[name=id]').val());
	}
	//$('#addPreviousOwnerForMachineryModal').find(".profile_id").select3({dropdownAutoWidth : false,dropdownParent: $('#addPreviousOwnerForMachineryModal').find(".profile_id").parent()});
	//$('#addPreviousOwnerForMachineryModal').find(".property_administrator_id").select3({dropdownAutoWidth : false,dropdownParent: $('#addPreviousOwnerForMachineryModal').find(".property_administrator_id").parent()});
	$('#addPreviousOwnerForMachineryModal').find(".brgy_code_id").select3({dropdownParent : '#addPreviousOwnerForMachineryModal'});
	$('#addPreviousOwnerForMachineryModal').find(".rvy_revision_year_id").select3({dropdownParent : '#addPreviousOwnerForMachineryModal'});
	$('#addPreviousOwnerForMachineryModal #rp_app_cancel_by_td_id_pre').select3({dropdownParent : '#addPreviousOwnerForMachineryModal'});

	$('#addPreviousOwnerForMachineryModal #rp_app_taxability_pre').select3({dropdownParent : $('#rp_app_taxability_pre').parent()});
	$('#addPreviousOwnerForMachineryModal #rp_app_effective_quarter_pre').select3({dropdownParent : $('#rp_app_effective_quarter_pre').parent()});
	$('#addPreviousOwnerForMachineryModal #rp_app_approved_by_pre').select3({dropdownParent : $('#rp_app_approved_by_pre').parent()});
	$('#addPreviousOwnerForMachineryModal #rp_app_effective_year_pre').yearpicker();
    //$('#addPreviousOwnerForMachineryModal').find("#B").select3({dropdownAutoWidth : false,dropdownParent : '#accordionFlushExampleOwner'});
	//$('#addPreviousOwnerForMachineryModal').find("#L").select3({dropdownAutoWidth : false,dropdownParent : '#accordionFlushExampleOwner'});
    $('#addPreviousOwnerForMachineryModal').on('keyup','.calclulatebasemarketvalueandmarketvalue',function(){
		calculateDataForOtherFieldsPre();
	});
	initiateTdRemoteSelectListBuildingPre(0);
	initiateTdRemoteSelectListLandPre(0);

	$('#addPreviousOwnerForMachineryModal').find("#profile_id_pre").select3({
    placeholder: 'Select Property Owner',
    allowClear: true,
    dropdownParent: $('#addPreviousOwnerForMachineryModal').find("#profile_id_pre").parent(),
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
	$('#addPreviousOwnerForMachineryModal').find("#property_administrator_id_pre").select3({
    placeholder: 'Select Property Administrator',
    allowClear: true,
    dropdownParent: $('#addPreviousOwnerForMachineryModal').find("#property_administrator_id_pre").parent(),
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

     $('#addPreviousOwnerForMachineryModal').off('change','select[name=profile_id]').on('change','select[name=profile_id]',function(){
     	    var propId = $('#addPreviousOwnerForMachineryModal').find('#id').val();
     	    if($('#addPreviousOwnerForMachineryModal').find('#old_property_id').val() == '' && propId == 0){
     	    	var id=$(this).val();
				initiateTdRemoteSelectListBuildingPre(id);
				$('#addPreviousOwnerForMachineryModal').find('.myCheckboxBuilding').prop('checked', true);
				initiateTdRemoteSelectListLandPre(id);
				$('#addPreviousOwnerForMachineryModal').find('.myCheckboxLand').prop('checked', true);
     	    }
    		
	});

    $("#addPreviousOwnerForMachineryModal").on("click",'.eventOnCloseModal', function(){
		  $("#addPreviousOwnerForMachineryModal .body").html("");
		});
    $('#addPreviousOwnerForMachineryModal').off('change','#rp_app_cancel_by_td_id_pre').on('change','#rp_app_cancel_by_td_id_pre',function(){
       loadPreviousOwnerTdDetails();
	});
    $('#addPreviousOwnerForMachineryModal').off('click','.myCheckboxBuilding').on('click','.myCheckboxBuilding',function() {
		if($(this).is(":checked")) {
		    var id=$('#addPreviousOwnerForMachineryModal').find("#profile_id_pre").val();
			initiateTdRemoteSelectListBuildingPre(id);
	        $('#addPreviousOwnerForMachineryModal').find('.myCheckboxLand').click().prop('checked', true);
	    } else {
	      initiateTdRemoteSelectListBuildingPre(0);
		  $('#addPreviousOwnerForMachineryModal').find('.myCheckboxLand').click().prop('checked', false);
	    }
	}); 
	$('#addPreviousOwnerForMachineryModal').off('click','.myCheckboxLand').on('click','.myCheckboxLand',function() {
		if($(this).is(":checked")) {
		    var id = $('#addPreviousOwnerForMachineryModal').find("#profile_id_pre").val();
			initiateTdRemoteSelectListLandPre(id);
	    } else {
	      initiateTdRemoteSelectListLandPre(0);
	    }
	}); 
	$('#addPreviousOwnerForMachineryModal').off('click','.refeshbuttonselect2').on('click','.refeshbuttonselect2',function(){
    	showLoader();
		$.ajax({
			type: "GET",
			url: DIR+'rptmachinery/get-all-profiles',
			dataType: "html",
			success: function(html){
				html = JSON.parse(html);
				let $data = `<option>Select Name</option>`;
				html.forEach((element, index) => {
					$data+=`<option value="${element.id}">${element.standard_name}</option>`;
				});
				$('#addPreviousOwnerForMachineryModal').find('.profile_id').html($data);
				hideLoader();
			},
			error: function(err){
				console.log(err);
				hideLoader();
			}
		});
    })
	$('#addPreviousOwnerForMachineryModal').off('click','.refeshbuttonselect').on('click','.refeshbuttonselect',function(){
    	showLoader();
		$.ajax({
			type: "GET",
			url: DIR+'rptmachinery/get-all-profiles',
			dataType: "html",
			success: function(html){
				html = JSON.parse(html);
				let $data = `<option>Select Name</option>`;
				html.forEach((element, index) => {
					$data+=`<option value="${element.id}">${element.standard_name}</option>`;
				});
				$('#addPreviousOwnerForMachineryModal').find('.property_administrator_id').html($data);
				hideLoader();
			},
			error: function(err){
				console.log(err);
				hideLoader();
			}
		});
    })

    $('#addPreviousOwnerForMachineryModal').off('keyup','.decimalvalue').on('keyup','.decimalvalue',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
		});
$('#addPreviousOwnerForMachineryModal').find('.searchForLandOrBuilding').unbind('click');
$('#addPreviousOwnerForMachineryModal').off('change','.searchForLandOrBuildingpre').on('change','.searchForLandOrBuildingpre',function(){
	var propertyKind = $(this).attr('id');
	if(propertyKind == "B_pre"){
		var tdNo = $(this).val(); 
		var newPropKind = "B";
	}else{
		var tdNo = $(this).val(); 
		var newPropKind = "L";
	}
	var brgy = $('#addPreviousOwnerForMachineryModal').find('#brgy_code_id').val();
	showLoader();
	var url = DIR+'rptmachinery/searchlandorbuilding';
	$('.loadingGIF').show();
	var filtervars = {
	    rp_td_no_bref:tdNo,
	    rp_td_no_lref:tdNo,
	    brgy_code_id:brgy,
	    propertyKind:newPropKind
	}; 
	$.ajax({
	    type: "post",
	    url: url,
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('#addPreviousOwnerForMachineryModal').find('.validate-err').html('');
	    	hideLoader();
	    	if(html.status == 'validation_error'){
	    		$('.validate-err').html('');
                    $('#addPreviousOwnerForMachineryModal').find('#err_'+html.field_name).html(html.error);
                    $('#addPreviousOwnerForMachineryModal').find('.'+html.field_name).focus();
                    //alert(html.data.properyKind);
                    if(html.data.propertyKind == "B_pre"){
                        $('#propertyPreviousOwnerForm').find('input[name=rp_code_bref]').val('');
                    	$('#propertyPreviousOwnerForm').find('input[name=building_owner]').val('');
                    	$('#propertyPreviousOwnerForm').find('input[name=rp_pin_no_bref]').val('');
                    	$('#propertyPreviousOwnerForm').find('input[name=rp_pin_suffix_bref]').val('');
                    	$('#propertyPreviousOwnerForm').find('input[name=rp_section_no_bref]').val('');
                    }else{
                    	$('#propertyPreviousOwnerForm').find('input[name=rp_code_lref]').val('');
                    	$('#propertyPreviousOwnerForm').find('input[name=land_owner]').val('');
                    	$('#propertyPreviousOwnerForm').find('input[name=rp_pin_no_lref]').val('');
                    	$('#propertyPreviousOwnerForm').find('input[name=rp_pin_suffix_lref]').val('');
                    	$('#propertyPreviousOwnerForm').find('input[name=rp_section_no_lref]').val('');
                    	$('#propertyPreviousOwnerForm').find('input[name=rpo_code_lref]').val('');
                    }
	    	}if(html.status == 'success'){
	    		$('#propertyPreviousOwnerForm').find('.validate-err').html('');
	    		if(html.data.propertyKind == "B"){
	    			var source = $('#propertyPreviousOwnerForm').find('#B_pre').find(':selected').data('custom-attribute');
	    			    var rpcodelref = html.data.rp_code_lref;
					      if(rpcodelref > 0 && source === undefined){
					         var rpcodelreftext = html.data.buildRefLandTdNo;
					               $('#propertyPreviousOwnerForm').find("#L_pre").select3("trigger", "select", {
					    data: { id: rpcodelref ,text:rpcodelreftext}
					});
					      }
	    			    $('#propertyPreviousOwnerForm').find('select[name=rp_code_lref]').val(html.data.rp_code_lref).change();
	    			    $('#propertyPreviousOwnerForm').find('input[name=rp_code_bref]').val(html.data.rp_code);
                    	$('#propertyPreviousOwnerForm').find('input[name=building_owner]').val(html.data.building_owner);
                    	$('#propertyPreviousOwnerForm').find('input[name=rp_pin_no_bref]').val(html.data.rp_pin_no_bref);
                    	$('#propertyPreviousOwnerForm').find('input[name=rp_pin_suffix_bref]').val(html.data.rp_pin_suffix_bref);
                    	$('#propertyPreviousOwnerForm').find('input[name=rp_section_no_bref]').val(html.data.rp_section_no_bref);
                    	$('#propertyPreviousOwnerForm').find("#bpin").val(html.data.rp_pin_declaration_no);

                    }else{
                    	$('#propertyPreviousOwnerForm').find('input[name=rp_code_lref]').val(html.data.rp_code);
                    	$('#propertyPreviousOwnerForm').find('input[name=land_owner]').val(html.data.land_owner);
                    	$('#propertyPreviousOwnerForm').find('input[name=rp_pin_no_lref]').val(html.data.rp_pin_no_lref);
                    	$('#propertyPreviousOwnerForm').find('input[name=rp_pin_suffix_lref]').val(html.data.rp_pin_suffix_lref);
                    	$('#propertyPreviousOwnerForm').find('input[name=rp_section_no_lref]').val(html.data.rp_section_no_lref);
                    	$('#propertyPreviousOwnerForm').find('input[name=rpo_code_lref]').val(html.data.rpo_code_lref);
                    	$('#propertyPreviousOwnerForm').find("#lpin").val(html.data.rp_pin_declaration_no);
                    	/*$('#propertyPreviousOwnerForm').find('input[name=rp_section_no]').val(html.data.rp_section_no_lref);
                    	$('#propertyPreviousOwnerForm').find('input[name=rp_pin_no]').val(html.data.rp_pin_no_lref);*/
                    	//createPinSuffixPre(html.data.rp_code, $('#addPreviousOwnerForMachineryModal').find('#id').val());
                    }
	    	}
	    	
	    },error:function(){
	    	$('#addPreviousOwnerForMachineryModal').find('.validate-err').html('');
	    	hideLoader();
	    }
	});
});


    var propertyId = $('#addPreviousOwnerForMachineryModal').find('input[name=id]').val();
	loadMachineAppraisalPre(propertyId);
    loadPreviousOwnerTdDetails();
	$('#addPreviousOwnerForMachineryModal').off('change','#asse_summary_pc_class_code').on('change','#asse_summary_pc_class_code',function(){
		var propertyId = $('#addPreviousOwnerForMachineryModal').find('input[name=id]').val();
	    loadAssessementSumaryPre(propertyId);
	});
	
	commonFunction();
	$('#addPreviousOwnerForMachineryModal').find("#profile_id_pre").change(function(){
		var id=$(this).val();
		if(id){ 
			getprofiledataPre(id); 
			  }
	})

	$('#addPreviousOwnerForMachineryModal').find("#property_administrator_id_pre").change(function(){
		var id=$(this).val();
		if(id){ 
			getAdminprofiledataPre(id); 
			  }

	})
    if($('#addPreviousOwnerForMachineryModal').find("#property_administrator_id_pre").val()>0){
		getAdminprofiledataPre($('#addPreviousOwnerForMachineryModal').find("#property_administrator_id_pre").val());
    }

	$('#addPreviousOwnerForMachineryModal').find("#rvy_revision_year_id").change(function(){
		var id=$(this).val();
		if(id){ 
			getRvyRevisionYearDetailsPre(id); 
			}

	});
	if($('#addPreviousOwnerForMachineryModal').find("#rvy_revision_year_id").val()>0 && $('#addPreviousOwnerForMachineryModal').find("input[name=id]").val() == 0){
		getRvyRevisionYearDetailsPre($('#addPreviousOwnerForMachineryModal').find("#rvy_revision_year_id").val()); 
	}
	$('#addPreviousOwnerForMachineryModal').find("#brgy_code_id").change(function(){
		var id=$(this).val();
		var updateCode = $('#addPreviousOwnerForMachineryModal').find('input[name=update_code]').val();
		if(id != '' && updateCode == 'DC'){ 
			getbarangayaDetailsPre(id); 
			}

	})
	if($('#addPreviousOwnerForMachineryModal').find("#profile_id_pre").val()>0){
		getprofiledataPre($('#addPreviousOwnerForMachineryModal').find("#profile_id_pre").val());
	}
	if($('#addPreviousOwnerForMachineryModal').find('input[name=id]').val() == 0 ){
		getbarangayaDetailsPre($('#addPreviousOwnerForMachineryModal').find('#brgy_code_id').val());
	}
	if($('#addPreviousOwnerForMachineryModal').find("#property_administrator_id_pre").val()>0){
		getAdminprofiledataPre($('#addPreviousOwnerForMachineryModal').find("#property_administrator_id_pre").val());
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
	    	$('#addPreviousOwnerForMachineryModal').find('input[name=loc_group_brgy_no]').val(html.brgy_name);
	    	$('#addPreviousOwnerForMachineryModal').find('input[name=loc_local_name]').val(html.loc_local_name);
	    	$('#addPreviousOwnerForMachineryModal').find('input[name=brgy_code]').val(html.brgy_code);
	    	$('#addPreviousOwnerForMachineryModal').find('input[name=dist_code]').val(html.dist_code);
	    	$('#addPreviousOwnerForMachineryModal').find('input[name=dist_code_name]').val(html.dist_code+'-'+html.dist_name);
	    	$('#addPreviousOwnerForMachineryModal').find('input[name=brgy_code_and_desc]').val(html.brgy_code+'-'+html.brgy_name);
	    	$('#addPreviousOwnerForMachineryModal').find('input[name=loc_local_code_name]').val(html.mun_desc);
	    	$('#addPreviousOwnerForMachineryModal').find('input[name=loc_local_code]').val(html.loc_local_code_id);
	    	
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
	    		$('#addPreviousOwnerForMachineryModal').find('input[name="rp_administrator_code"]').val(arr.id);
	    		$('#addPreviousOwnerForMachineryModal').find('input[name="rp_administrator_code_address"]').val(arr.standard_address);
	    }
	});
}

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
	    	$('#addPreviousOwnerForMachineryModal').find('input[name="rvy_revision_year"]').val(html.rvy_revision_year);
	    	$('#addPreviousOwnerForMachineryModal').find('input[name="rvy_revision_code"]').val(html.rvy_revision_code);
	        $('#addPreviousOwnerForMachineryModal').find('input[name="rp_app_effective_year"]').val(html.rvy_revision_year);
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
	    	arr = $.parseJSON(html);
	    		$('#addPreviousOwnerForMachineryModal').find('input[name="property_owner_address"]').val(arr.standard_address);
	    		$('#addPreviousOwnerForMachineryModal').find('input[name="rpo_code"]').val(arr.id);
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
	    url: DIR+'rptmachinery/deletemachineappraisal',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	loadMachineAppraisalPre($('#addPreviousOwnerForMachineryModal').find('input[name=id]').val());
	    }
	});
}

function commonFunction(){

	$('#addPreviousOwnerForMachineryModal').off('click','.deleteLandAppraisal').on('click','.deleteLandAppraisal',function(){
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
			          $(this).closest("#trId"+mid).remove();
			          $('#myModal'+mid).remove();
			          if(typeof landAppraisalId !== 'undefined' ){
			          	deleteLandAppraisalPre(landAppraisalId);
			          }
			          calculateTotalMarketValuePre();
			        }
			    })
		
	});

	$("#addPreviousOwnerForMachineryModal").find("#loadMachineApprisalForm").unbind("click");
	$("#addPreviousOwnerForMachineryModal").off('click',"#loadMachineApprisalForm").on('click','#loadMachineApprisalForm',function(){
        $('#addmachineappraisalmodalForPreOwner').modal('show');
		loadAddLandAppraisalFormPre(id = '');
	}); 

	$('#addPreviousOwnerForMachineryModal').off('click','.editLandAppraisal').on('click','.editLandAppraisal',function(){
            var id        = $(this).data('id');
            var sessionId = $(this).data('sessionid');
            $('#addPreviousOwnerForMachineryModal').find('#addmachineappraisalmodalForPreOwner').modal('show');
		    loadAddLandAppraisalFormPre(id, sessionId);
            //savePlantsTreesAdjustmentFactorForm(id);
            
		});
	$('#addPreviousOwnerForMachineryModal').off('submit','#propertyPreviousOwnerForm').on('submit','#propertyPreviousOwnerForm',function(e){
		showLoader();
		$('#propertyPreviousOwnerForm #brgy_code_id').prop('disabled', false);
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
	    		$('#addPreviousOwnerForMachineryModal').modal('hide');
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
	    		$('#propertyPreviousOwnerForm #brgy_code_id').prop('disabled', true);
	    		Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: false
                    })
	    		
	    	}
	    }
	});
	});
	$('#addPreviousOwnerForMachineryModal').off('submit','#storelandappraisal').on('submit','#storelandappraisal',function(e){
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
	    		$('#addPreviousOwnerForMachineryModal').find('.validate-err').html('');
                    $('#addPreviousOwnerForMachineryModal').find('#err_'+html.field_name).html(html.error);
                    $('#addPreviousOwnerForMachineryModal').find('.'+html.field_name).focus();
	    	}if(html.status == 'success'){
	    		$('#addPreviousOwnerForMachineryModal').find('.validate-err').html('');
	    		$('#addPreviousOwnerForMachineryModal').find('#addmachineappraisalmodalForPreOwner').modal('hide');
	    		loadMachineAppraisalPre($('#addPreviousOwnerForMachineryModal').find('input[name=id]').val());
	    	}
	    },error:function(){
	    	hideLoader();
	    }
	});

		});

}

function createPinSuffixPre(id, propId) {
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    propId:propId
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptmachinery/generatepinsuffix',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('#addPreviousOwnerForMachineryModal').find('input[name=rp_pin_suffix]').val(html.data.suffix);
	    },error:function(){
	    	
	    }
	});
}


function calculateTotalMarketValuePre(mid) {
	var totalMarketValue = 0;
	$('#new_added_land_apraisal').find("tbody tr .rpa_base_market_value").each(function(total){
		var marketValue = $(this).text();
		totalMarketValue += parseFloat(marketValue);

	});
	var previousValue = $('#landApraisalTotalValueToDisplay').val();
	$('#landApraisalTotalValueToDisplay').val(parseFloat(totalMarketValue).toFixed(2));
}

function calculateDataForOtherFieldsPre(){
    	/* Calculate Base Market Value */
    	var units          = parseFloat($('#addmachineappraisalmodalForPreOwner').find('.rpma_appr_no_units ').val());
	    var acquCost       = parseFloat($('#addmachineappraisalmodalForPreOwner').find('.rpma_acquisition_cost').val());
	    var freistCost     = parseFloat($('#addmachineappraisalmodalForPreOwner').find('.rpma_freight_cost').val());
	    var insuCost       = parseFloat($('#addmachineappraisalmodalForPreOwner').find('.rpma_insurance_cost').val());
	    var installCost    = parseFloat($('#addmachineappraisalmodalForPreOwner').find('.rpma_installation_cost').val());
	    var otherCost      = parseFloat($('#addmachineappraisalmodalForPreOwner').find('.rpma_other_cost').val());
	    var depRate        = parseFloat($('#addmachineappraisalmodalForPreOwner').find('.rpma_depreciation_rate').val());
	    if(isNaN(units)){
	    	units = 0;
	    }if(isNaN(acquCost)){
	    	acquCost = 0;
	    }if(isNaN(freistCost)){
	    	freistCost = 0;
	    }if(isNaN(insuCost)){
	    	insuCost = 0;
	    }if(isNaN(installCost)){
	    	installCost = 0;
	    }if(isNaN(otherCost)){
	    	otherCost = 0;
	    }if(isNaN(depRate)){
	    	depRate = 0;
	    }

	    //var basemarketvalue    = (acquCost/units)+(freistCost+insuCost+installCost+otherCost);
	    var basemarketvalue    = acquCost+(freistCost+insuCost+installCost+otherCost);
	    $('#addmachineappraisalmodalForPreOwner').find('.rpma_base_market_value').val(parseFloat(basemarketvalue).toFixed(2));
	    /* Calculate Base Market Value */

	    /* Calculate Total Base Market Value */
	    var depreValue  = (basemarketvalue*depRate)/100;
	    var marketValue = (basemarketvalue-depreValue);
	    $('#addmachineappraisalmodalForPreOwner').find('.rpma_depreciation').val(parseFloat(depreValue).toFixed(2));
    	$('#addmachineappraisalmodalForPreOwner').find('.rpma_market_value').val(parseFloat(marketValue).toFixed(2));
	    /* Calculate Total Base Market Value */
    }


function loadAddLandAppraisalFormPre(id, sessionId) {
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    property_id:$('#addPreviousOwnerForMachineryModal').find('input[name=id]').val()
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptmachinery/storemachineappraisal',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('#addPreviousOwnerForMachineryModal').find('#machineappraisalformForPreOwner').html(html);
	    }
	});
}

function loadMachineAppraisalPre(id) {
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    "_token": $("#_csrf_token").val()
	}; 
	$.ajax({
	    type: "POST",
	    url: DIR+'rptmachinery/getmachineryappraisal',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('#addPreviousOwnerForMachineryModal').find('#landAppraisalListing').html(html.view);
	    	$('#addPreviousOwnerForMachineryModal').find('#machineAppraisalDescription').html(html.view2);
	    	loadAssessementSumaryPre($('#addPreviousOwnerForMachineryModal').find('input[name=id]').val());
	    }
	});
}

function isNumber(evt, element) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (            
        (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
        (charCode < 48 || charCode > 57))
        return false;
        return true;
}

function loadAssessementSumaryPre(id) {

	var classCode = $('#addPreviousOwnerForMachineryModal').find('.asse_summary_pc_class_code').val();
	var propertyRevisionYear  = $('#addPreviousOwnerForMachineryModal').find('.rvy_revision_year_id').val();
	var barangay              = $('#addPreviousOwnerForMachineryModal').find('select[name=brgy_code_id]').val(); 
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    propertyClass:classCode,
	    propertyRevisionYear:propertyRevisionYear,
	    barangay:barangay,
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptmachinery/loadassessementsummary',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	if(html.status == 'success'){
	    		$('#addPreviousOwnerForMachineryModal').find('#assessementSummaryData').html(html.view);
	    		if(html.assessementLevel == false){
	    			Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'No assessementLevel found, Please try again!',
                      showConfirmButton: true,
                      timer: false
                    });
	    		$('#addPreviousOwnerForMachineryModal').find('.asse_summary_pc_class_code').val('');
	    		}
	    		
	    	}
	    	
	    }
	});
}	


function getTaxDePre(id,brgy_code_id,rvy_revision_year_id){
   $.ajax({
 
        url :DIR+'getTaxDeclaresionNOBuildingDetails', // json datasource
        type: "POST", 
        data: {
           "id": id,
           "brgy_code_id": brgy_code_id,
           "rvy_revision_year_id": rvy_revision_year_id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $('#addPreviousOwnerForMachineryModal').find("#B_pre").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function getTaxDeAllPre(brgy_code_id,rvy_revision_year_id){
   $.ajax({
 
        url :DIR+'getTaxDeclaresionNODetailsBuildingAll', // json datasource
        type: "POST", 
        data: {
           "brgy_code_id": brgy_code_id,
           "rvy_revision_year_id": rvy_revision_year_id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $('#addPreviousOwnerForMachineryModal').find("#B_pre").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function loadPreviousOwnerTdDetails() {
	var propId = $('#addPreviousOwnerForMachineryModal').find('#rp_app_cancel_by_td_id_pre').find("option:selected").val();
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
	    	$('#addPreviousOwnerForMachineryModal').find('.prop_index_no').val(html.index_no);
	    	//$('textarea[name=rp_location_number_n_street]').val(html.brgy_name+', '+html.mun_desc);
	    	$('#addPreviousOwnerForMachineryModal').find('.tax_payer_name').val(html.tax_payer_name);
	    	$('#addPreviousOwnerForMachineryModal').find('.tax_payer_address').val(html.address);
	    	$('#addPreviousOwnerForMachineryModal').find('#previousownerlandappraisaldetails').html(html.view);
	    	$('#addPreviousOwnerForMachineryModal').find('.taxability').val(html.taxability);
	    	$('#addPreviousOwnerForMachineryModal').find('.effectivity').val(html.effectivity);
	    	$('#addPreviousOwnerForMachineryModal').find('.quarter').val(html.quarter);
	    	$('#addPreviousOwnerForMachineryModal').find('.approved_by').val(html.approvedby);
	    	$('#addPreviousOwnerForMachineryModal').find('.date').val(html.date);
	    },error:function(){
	    	hideLoader();
	    }
	});
}
function getTaxDeLandPre(id,brgy_code_id,rvy_revision_year_id){
   $.ajax({
 
        url :DIR+'getTaxDeclaresionNOLandDetails', // json datasource
        type: "POST", 
        data: {
           "id": id,
           "brgy_code_id": brgy_code_id,
           "rvy_revision_year_id": rvy_revision_year_id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $('#addPreviousOwnerForMachineryModal').find("#L_pre").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function getTaxDeAllLandPre(brgy_code_id,rvy_revision_year_id){
   $.ajax({
 
        url :DIR+'getTaxDeclaresionNODetailsLandAll', // json datasource
        type: "POST", 
        data: {
           "brgy_code_id": brgy_code_id,
           "rvy_revision_year_id": rvy_revision_year_id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $('#addPreviousOwnerForMachineryModal').find("#L_pre").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}

function initiateTdRemoteSelectListLandPre(rpoCode) {
 	$('#addPreviousOwnerForMachineryModal').find("#L_pre").select3({
    placeholder: 'Select Land Tax Declaration No.',
    allowClear: true,
    dropdownParent: $('#addPreviousOwnerForMachineryModal').find("#L_pre").parent(),
    ajax: {
        url: DIR+'rptproperty/gettdsforajaxselectlist',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
            	brgy_code_id:$('#addPreviousOwnerForMachineryModal').find("#brgy_code_id").val(),
			    rvy_revision_year_id:$('#addPreviousOwnerForMachineryModal').find("#rvy_revision_year_id").val(),
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

 function initiateTdRemoteSelectListBuildingPre(rpoCode) {
 	$('#addPreviousOwnerForMachineryModal').find("#B_pre").select3({
    placeholder: 'Select Building Tax Declaration No.',
    allowClear: true,
    dropdownParent: $('#addPreviousOwnerForMachineryModal').find("#B_pre").parent(),
    ajax: {
        url: DIR+'rptproperty/gettdsforajaxselectlist',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
            	brgy_code_id:$('#addPreviousOwnerForMachineryModal').find("#brgy_code_id").val(),
			    rvy_revision_year_id:$('#addPreviousOwnerForMachineryModal').find("#rvy_revision_year_id").val(),
            	column:'id',
                pk_id:1,
                rpo_code:rpoCode,
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
 }

$(document).ready(function(){
    var isPropertyAdminIdSelected = false;
    var property_administrator_id_id=$('#addPreviousOwnerForMachineryModal').find("#property_administrator_id_pre").val();
    // alert(property_administrator_id_id);
    $('#addPreviousOwnerForMachineryModal').off('click',"#profile_id_pre").on('click',"#profile_id_pre",function(){
        var clientid = $(this).val();
        if(property_administrator_id_id === ''){
        	if (!isPropertyAdminIdSelected) {
            getAdmistrativePre(clientid);
            getAdminprofiledataPre(clientid);
        }
        }
        
    });
    
    function getAdmistrativePre(clientid){
        $.ajax({
            url: DIR + 'getAdmistrativeDetails',
            type: 'POST',
            data: {
                "clientid": clientid,
                "_token": $("#_csrf_token").val(),
            },
            success: function(html){
                if (html !== '') {
                    $('#addPreviousOwnerForMachineryModal').find("#property_administrator_id_pre").html(html);

                    if (!isPropertyAdminIdSelected) {
                        isPropertyAdminIdSelected = true;
                    } else {
                        $('#addPreviousOwnerForMachineryModal').find("#property_administrator_id_pre").prop("disabled", true);
                    }
                }
            }
        });
    }
});