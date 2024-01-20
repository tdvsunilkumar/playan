$(document).ready(function(){
    
	if($('#addPreviousOwnerForLandModal').find("#old_property_id").val()>0){
		loadAssessementSumaryPre($('#addPreviousOwnerForLandModal').find('input[name=id]').val());
	}
	/*$('#addPreviousOwnerForLandModal').find("#profile").select3({dropdownAutoWidth : false,dropdownParent: $('#addPreviousOwnerForLandModal').find(".profile_id_group")});
	$('#addPreviousOwnerForLandModal').find("#property_administrator_id").select3({dropdownAutoWidth : false,dropdownParent: $('#addPreviousOwnerForLandModal').find(".property_administrator_id_group")});*/
	$('#addPreviousOwnerForLandModal').find(".brgy_code_id").select3({dropdownParent : '#addPreviousOwnerForLandModal'});
	$('#addPreviousOwnerForLandModal').find(".rvy_revision_year_id").select3({dropdownParent : '#addPreviousOwnerForLandModal'});
	$('#addPreviousOwnerForLandModal #rp_app_cancel_by_td_id_pre').select3({dropdownParent : '#addPreviousOwnerForLandModal'});

	$('#addPreviousOwnerForLandModal #rp_app_taxability_pre').select3({dropdownParent : $('#rp_app_taxability_pre').parent()});
	$('#addPreviousOwnerForLandModal #rp_app_effective_quarter_pre').select3({dropdownParent : $('#rp_app_effective_quarter_pre').parent()});
	$('#addPreviousOwnerForLandModal #rp_app_approved_by_pre').select3({dropdownParent : $('#rp_app_approved_by_pre').parent()});
	$('#addPreviousOwnerForLandModal #rp_app_effective_year').yearpicker();
	/* Land Appraisal Value Adjustment Factors*/

	$("#profile_pre").select3({
    placeholder: 'Select Property Owner',
    allowClear: true,
    dropdownParent: $(".pre_profile_id").parent(),
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
	$('#addPreviousOwnerForLandModal').find("#property_administrator_id_pre").select3({
    placeholder: 'Select Property Administrator',
    allowClear: true,
    dropdownParent: $('#addPreviousOwnerForLandModal').find("#property_administrator_id_pre").parent(),
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
     
	function loadPropertyOwners(id = '') {
		showLoader();
		$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptproperty/getpropertyowners',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('.loadingGIF').hide();
	    	$('select[name="profile_id"]').html(html); 
	    	$('select[name="property_administrator_id_pre"]').html(html);
	    },error:function(){
	    	hideLoader();
	    }
	});
	}

		$("#addPreviousOwnerForLandModal").on("click",'.eventOnCloseModal', function(){
		  $("#addPreviousOwnerForLandModal .body").html("");
		});

    var propertyId = $('#addPreviousOwnerForLandModal').find('input[name=id]').val();
	/*loadPlantsTreesAdjustmentFactor(propertyId);*/
	loadLandAppraisalPre(propertyId);
	loadPreviousOwnerTdDetails();
	loadAssessementSumaryPre(propertyId);
	commonFunctionPre();
	   $("#submit").on("click",function(){
    if (($("input[name*='Completed']:checked").length)<=0) {
       // alert("You must check at least 1 box");
    }
    return true;
});
	$(document).off('change','#addPreviousOwnerForLandModal #rp_app_cancel_by_td_id_pre').on('change','#addPreviousOwnerForLandModal #rp_app_cancel_by_td_id_pre',function(){
       loadPreviousOwnerTdDetails();
	});

	$('#addPreviousOwnerForLandModal').find("#profile_pre").change(function(){
		var id=$(this).val();
		if(id != ''){
		 getprofiledataPre(id); 
			 }

	})
	$('#addPreviousOwnerForLandModal').find("#property_administrator_id_pre").change(function(){
		var id=$(this).val();
		if(id){ getAdminprofiledataPre(id); }

	})
	$('#addPreviousOwnerForLandModal').find("#rvy_revision_year_id").change(function(){
		var id=$(this).val();
		if(id){ 
			getRvyRevisionYearDetailsPre(id); 
			}

	})
	$('#addPreviousOwnerForLandModal').find("#brgy_code_id").change(function(){
		var id=$(this).val();
		var updateCode = $('#addPreviousOwnerForLandModal').find('input[name=update_code]').val();
		if(id != '' && updateCode == 'DC'){ 
			getbarangayaDetailsPre(id); 
			}

	})
	if($('#addPreviousOwnerForLandModal').find("#rvy_revision_year_id").val()>0 && $('#addPreviousOwnerForLandModal').find("input[name=id]").val() == 0){
		getRvyRevisionYearDetailsPre($('#addPreviousOwnerForLandModal').find("#rvy_revision_year_id").val()); 
	}
	if($('#addPreviousOwnerForLandModal').find("#profile_pre").val()>0){
		getprofiledataPre($('#addPreviousOwnerForLandModal').find("#profile_pre").val());
	}
	if($('#addPreviousOwnerForLandModal').find('input[name=id]').val() == 0){
		getbarangayaDetailsPre($('#addPreviousOwnerForLandModal').find('#brgy_code_id').val());
	}

	if($('#addPreviousOwnerForLandModal').find("#property_administrator_id_pre").val()>0){
		getAdminprofiledataPre($('#addPreviousOwnerForLandModal').find("#property_administrator_id_pre").val());
	}

	$('#addPreviousOwnerForLandModal').find('.refeshbuttonselect1').click(function(){
		showLoader();
		$.ajax({
			type: "GET",
			url: DIR+'rptproperty/get-all-profiles',
			dataType: "html",
			success: function(html){
				html = JSON.parse(html);
				let $data = `<option>Select Name</option>`;
				html.forEach((element, index) => {
					$data+=`<option value="${element.id}">${element.standard_name}</option>`;
				});
				$('#addPreviousOwnerForLandModal').find('#property_administrator_id_pre').html($data);
				hideLoader();
			},
			error: function(err){
				console.log(err);
				hideLoader();
			}
		});
	});

	$('#addPreviousOwnerForLandModal').find('.refeshbuttonselect2').click(function(){
		showLoader();
		$.ajax({
			type: "GET",
			url: DIR+'rptproperty/get-all-profiles',
			dataType: "html",
			success: function(html){
				html = JSON.parse(html);
				console.log(html);
				let $data = `<option>Select Name</option>`;
				html.forEach((element, index) => {
					$data+=`<option value="${element.id}">${element.standard_name}</option>`;
				});
				$('#addPreviousOwnerForLandModal').find('.profile_id').html($data);
				hideLoader();
			},
			error: function(err){
				console.log(err);
				hideLoader();
			}
		});
	});



	
   
});



function loadPreviousOwnerTdDetails() {
	var propId = $('#addPreviousOwnerForLandModal #rp_app_cancel_by_td_id_pre').find("option:selected").val();
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
	    	$('#addPreviousOwnerForLandModal').find('.prop_index_no').val(html.index_no);
	    	//$('textarea[name=rp_location_number_n_street]').val(html.brgy_name+', '+html.mun_desc);
	    	$('#addPreviousOwnerForLandModal').find('.tax_payer_name').val(html.tax_payer_name);
	    	$('#addPreviousOwnerForLandModal').find('.tax_payer_address').val(html.address);
	    	$('#addPreviousOwnerForLandModal').find('#previousownerlandappraisaldetails').html(html.view);
	    	$('#addPreviousOwnerForLandModal').find('.taxability').val(html.taxability);
	    	$('#addPreviousOwnerForLandModal').find('.effectivity').val(html.effectivity);
	    	$('#addPreviousOwnerForLandModal').find('.quarter').val(html.quarter);
	    	$('#addPreviousOwnerForLandModal').find('.approved_by').val(html.approvedby);
	    	$('#addPreviousOwnerForLandModal').find('.date').val(html.date);
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function getbarangayaDetailsPre(id){
	showLoader();
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
	    	hideLoader();
	    	$('.loadingGIF').hide();
	    	$('#addPreviousOwnerForLandModal').find('input[name=loc_group_brgy_no]').val(html.brgy_name);
	    	//$('textarea[name=rp_location_number_n_street]').val(html.brgy_name+', '+html.mun_desc);
	    	$('#addPreviousOwnerForLandModal').find('input[name=brgy_code]').val(html.brgy_code);
	    	$('#addPreviousOwnerForLandModal').find('input[name=dist_code]').val(html.dist_code);
	    	$('#addPreviousOwnerForLandModal').find('input[name=dist_code_name]').val((typeof html.dist_code !== "undefined")?html.dist_code+'-'+html.dist_name:'');
	    	$('#addPreviousOwnerForLandModal').find('input[name=brgy_code_and_desc]').val(html.brgy_code+'-'+html.brgy_name);
	    	$('#addPreviousOwnerForLandModal').find('input[name=loc_local_code_name]').val(html.mun_desc);
	    	$('#addPreviousOwnerForLandModal').find('input[name=loc_local_code]').val(html.loc_local_code_id);
	    	
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function getAdminprofiledataPre(id){
	showLoader();
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
	    	hideLoader();
	    	$('.loadingGIF').hide();
	    	arr = $.parseJSON(html);
	    		$('#addPreviousOwnerForLandModal').find('input[name="rp_administrator_code"]').val(arr.id);
	    		$('#addPreviousOwnerForLandModal').find('input[name="rp_administrator_code_address"]').val(arr.standard_address)
	    	
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function getRvyRevisionYearDetailsPre(id){
	showLoader();
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
	    	hideLoader();
	    	$('.loadingGIF').hide();
	    	/*arr = $.parseJSON(html);*/
	    	$('#addPreviousOwnerForLandModal').find('input[name="rvy_revision_year"]').val(html.rvy_revision_year);
	    	$('#addPreviousOwnerForLandModal').find('input[name="rvy_revision_code"]').val(html.rvy_revision_code);
	    	$('#addPreviousOwnerForLandModal').find('input[name="rp_app_effective_year"]').val(html.rvy_revision_year);
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function getprofiledataPre(id){
	showLoader();
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
	    	hideLoader();
	    	$('.loadingGIF').hide();
	    	arr = $.parseJSON(html);
	    		$('#addPreviousOwnerForLandModal').find('input[name="property_owner_address"]').val(arr.standard_address);
	    		$('#addPreviousOwnerForLandModal').find('input[name="rpo_code"]').val(arr.id);
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function deleteLandAppraisalPre(id, sessionId) {
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    "_token": $("#_csrf_token").val()
	}; 
	$.ajax({
	    type: "post",
	    url: DIR+'rptproperty/deletelandappraisal',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	loadLandAppraisalPre($('#addPreviousOwnerForLandModal').find('input[name=id]').val());
	    	loadAssessementSumaryPre($('#addPreviousOwnerForLandModal').find('input[name=id]').val());

	    },error:function(){
	    	hideLoader();
	    }
	});
}


function commonFunctionPre(){

	$('#addPreviousOwnerForLandModal').find('.deleteLandAppraisal').click(function(){
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

	$("#addPreviousOwnerForLandModal #loadLandApprisalForm").unbind("click");
	$('#addPreviousOwnerForLandModal').off('click','#loadLandApprisalForm').on('click','#loadLandApprisalForm',function(){
		loadAddLandAppraisalFormForPre(id = '');
	}); 
	/*$('#addPreviousOwnerForLandModal').on('click','.closeLandAppraisalModal',function(){
			$('#addlandappraisalmodalForPreOwner').modal('hide');
		});*/
 
 
	    $('#addlandappraisalmodalForPreOwner').off('change','#pc_class_code').on('change','#pc_class_code',function(){
		var text = $('#addPreviousOwnerForLandModal .pc_class_code option:selected').text();
		$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find("input[name=pc_class_description]").val(text);
			var id=$(this).val();
			if(id){ 
				getClassDetailsPre(id);
				getSubClassesPre(id); 
				getActualUsesPre(id);
				getLandUnitValuePre();
				getAssessementLevelPre();
				setTimeout(function(){ 
				var landArea = $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.rpa_total_land_area').val();
			var unitValue =$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.lav_unit_value').val();
			var baseMarketValue = landArea*unitValue;
            $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.rpa_base_market_value').val(baseMarketValue);
				 }, 500);
			}else{
				$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".tax_type_desc").val('');
				$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".tax_type_id").val('');
			}
		})
	$(document).off('change','#addPreviousOwnerForLandModal .ps_subclass_code').on('change','#addPreviousOwnerForLandModal .ps_subclass_code',function(){
			var id=$(this).val();
		    var text = $('#addPreviousOwnerForLandModal .ps_subclass_code option:selected').text();
		$('#addPreviousOwnerForLandModal #addlandappraisalmodalForPreOwner').find("input[name=ps_subclass_desc]").val(text);
			getLandUnitValuePre();
			setTimeout(function(){ 
			var landArea = $('#addPreviousOwnerForLandModal #addlandappraisalmodalForPreOwner').find('.rpa_total_land_area').val();
			var unitValue =$('#addPreviousOwnerForLandModal #addlandappraisalmodalForPreOwner').find('.lav_unit_value').val();
			var baseMarketValue = landArea*unitValue;
            $('#addPreviousOwnerForLandModal #addlandappraisalmodalForPreOwner').find('.rpa_base_market_value').val(baseMarketValue);
				 }, 500);
		})
	$(document).off('change','#addPreviousOwnerForLandModal .pau_actual_use_code').on('change','#addPreviousOwnerForLandModal .pau_actual_use_code',function(){
		    var text = $('#addPreviousOwnerForLandModal .pau_actual_use_code option:selected').text();
		$('#addPreviousOwnerForLandModal #addlandappraisalmodalForPreOwner').find("input[name=pau_actual_use_desc]").val(text);
			getLandUnitValuePre();
			getAssessementLevelPre();
			setTimeout(function(){ 
				var landArea = $('#addPreviousOwnerForLandModal #addlandappraisalmodalForPreOwner').find('.rpa_total_land_area').val();
			var unitValue =$('#addPreviousOwnerForLandModal #addlandappraisalmodalForPreOwner').find('.lav_unit_value').val();
			var baseMarketValue = landArea*unitValue;
            $('#addPreviousOwnerForLandModal #addlandappraisalmodalForPreOwner').find('.rpa_base_market_value').val(baseMarketValue);
				 }, 500);
			
		})
	


	$(document).on('keyup','#addPreviousOwnerForLandModal .rpa_total_land_area',function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
    calculateLandMarketBaseValuePre();
});


		 $(document).on('keyup','#addPreviousOwnerForLandModal .rpa_adjusted_market_value',function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});
		 $(document).on('keyup','#addPreviousOwnerForLandModal .rpa_assessed_value',function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
    });

        $(document).on('keyup','#addPreviousOwnerForLandModal .lav_strip_unit_value',function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
    });


	$(document).on('click','#addPreviousOwnerForLandModal .deleteLandAppraisal',function(){
            var id = $(this).data('id');
            var sessionId = $(this).data('sessionid');
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
					  deleteLandAppraisalPre(id, sessionId);
			        }
			    })
            
		});

	$(document).off('click','#addPreviousOwnerForLandModal .editLandAppraisal').on('click','#addPreviousOwnerForLandModal .editLandAppraisal',function(){
            var id        = $(this).data('id');
            var sessionId = $(this).data('sessionid');
            $('#addPreviousOwnerForLandModal #addlandappraisalmodalForPreOwner').modal('show');
		    loadAddLandAppraisalFormForPre(id, sessionId);
            //savePlantsTreesAdjustmentFactorForm(id);
            
		});
	
	$(document).off('submit','#propertyPreviousOwnerForm').on('submit','#propertyPreviousOwnerForm',function(e){
		showLoader();
		//$('#rvy_revision_year_id').prop('disabled', false);
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
	    		//alert('from dc');
	    		$('#addPreviousOwnerForLandModal').modal('hide');
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
                            console.log('after save previous owner data '+propertyId+','+ updateCode);
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
	    },error:function(){
	    	hideLoader();
	    }
	});
	});
    $('#addPreviousOwnerForLandModal').find("#storelandappraisal").unbind("submit");
	$('#addPreviousOwnerForLandModal').off('submit','#storelandappraisal').on('submit','#storelandappraisal',function(e){
		//alert('before save');
		    showLoader();
			e.preventDefault();
			e.stopPropagation();
			var url = $(this).attr('action');
			var method = $(this).attr('method');
			var data   = $(this).serialize();
			//console.log(data);
			$.ajax({
	    type: "post",
	    url: url,
	    data: data+'&from=previousowner',
	    dataType: "json",
	    success: function(html){ 
	    	hideLoader();
	    	if(html.status == 'validation_error'){
	    		$('#addPreviousOwnerForLandModal .validate-err').html('');
	    			
                    $('#addPreviousOwnerForLandModal #err_'+html.field_name).html(html.error);
                    $('#addPreviousOwnerForLandModal .'+html.field_name).focus();
	    	}if(html.status == 'success'){
	    		//alert('after save');
	    		$('#addPreviousOwnerForLandModal .validate-err').html('');
	    		$('#addPreviousOwnerForLandModal #addlandappraisalmodalForPreOwner').modal('hide');
	    		loadLandAppraisalPre($('#addPreviousOwnerForLandModal input[name=id]').val());
	    		loadAssessementSumaryPre($('#addPreviousOwnerForLandModal input[name=id]').val());
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
}

function calculateTotalMarketValuePre(mid) {
	var totalMarketValue = 0;
	$('#addPreviousOwnerForLandModal').find('#new_added_land_apraisal').find("tbody tr .rpa_base_market_value").each(function(total){
		var marketValue = $(this).text().replace(/\,/g,'').replace('₱','');
		totalMarketValue += parseFloat(marketValue);

	});
	//alert(totalMarketValue);
	var previousValue = $('#addPreviousOwnerForLandModal').find('#landApraisalTotalValueToDisplay').val();
	$('#addPreviousOwnerForLandModal').find('#landAppraisalTotalValueToDisplay').val(numberWithCommas(parseFloat(totalMarketValue).toFixed(2)));
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function getActualUsesPre(id){
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptproperty/getactualuses',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	console.log($('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".classification_code"));
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".pau_actual_use_code").html(html);
			/*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);*/
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function getSubClassesPre(id){
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptproperty/getsubclasses',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".ps_subclass_code").html(html);
			/*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);*/
	    },error:function(){
	    	hideLoader();
	    }
	});
}



function loadAddLandAppraisalFormForPre(id, sessionId) {
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    property_id:$('#addPreviousOwnerForLandModal').find('input[name=id]').val()
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptproperty/storelandappraisal',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#addPreviousOwnerForLandModal #landappraisalformForPreOwner').html(html);
	    	$('#addlandappraisalmodalForPreOwner').modal('show');
	    	taskCheckbox();
	        commonLoader();
	    	var revisionYearId = $('#addPreviousOwnerForLandModal .rvy_revision_year_id').val();
		    var revisionYear   = $('#addPreviousOwnerForLandModal input[name=rvy_revision_year]').val();
		    setTimeout(function(){ 
		    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find("input[name=rpa_assessed_value]").prop('type','text');
		    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find("#rpa_assessed_value_label").attr('class','form-label show');
		    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find("#pc_class_code").select3({dropdownParent:$("#pc_class_code").parent()});
		    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".ps_subclass_code").select3({dropdownParent:$("#ps_subclass_code").parent()});
		    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".pau_actual_use_code").select3({dropdownParent:$("#pau_actual_use_code").parent()});
		    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".land_stripping_id").select3({dropdownParent:$("#land_stripping_id").parent()});
			        }, 500);
	    },
	    error: function(){
	    	hideLoader();
	    }
	});
}

function getClassDetailsPre(id){
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptproperty/getclassdetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	hideLoader();
	    	$('#addlandappraisalmodalForPreOwner').find(".pc_class_code_description").val(html.pc_class_description);
	    	
	    },error:function(){
	    	hideLoader();
	    }
	});
}


function loadLandAppraisalPre(id) {
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    "_token": $("#_csrf_token").val()
	}; 
	$.ajax({
	    type: "POST",
	    url: DIR+'rptproperty/getlandappraisal',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#addPreviousOwnerForLandModal #landAppraisalListing').html(html);
	    	calculateTotalMarketValuePre();
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function loadAssessementSumaryPre(id) {
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptproperty/loadassessementsummary',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#addPreviousOwnerForLandModal #assessementSummaryData').html(html);
	    	/*setTimeout(function(){ 
	            var lan = $('#addPreviousOwnerForLandModal #assessementSummaryData table tbody tr');
	            if(lan.find('.assessedValueAssSumary').length == 0){
	            	$('#addPreviousOwnerForLandModal').find('input[name=rp_assessed_value]').prop('readonly',false);
	            }
			        }, 500);*/
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function getAssessementLevelPre(){
	/*$('#myModal'+mid).find(".activity_id").val('');*/
	var barangay              = $('#addPreviousOwnerForLandModal').find('select[name=brgy_code_id]').val();         
	var propertyKind          = $('#addPreviousOwnerForLandModal').find('input[name=pk_id]').val();
	var propertyClass         = $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.pc_class_code').val();
	var propertyActualUseCode = $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.pau_actual_use_code').val();
	var propertyRevisionYear  = $('#addPreviousOwnerForLandModal').find('.rvy_revision_year_id').val();
	var baseMarketValue       = $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.rpa_base_market_value').val();
	console.log(propertyKind, propertyClass, propertyActualUseCode, propertyRevisionYear);
	if(propertyKind != '' && propertyClass != '' && propertyActualUseCode !== null && propertyRevisionYear != ''){
		//alert();
		$('.loadingGIF').show();
		showLoader();
	var filtervars = {
		barangay:barangay,
	    propertyKind:propertyKind,
	    propertyClass:propertyClass,
	    propertyActualUseCode:propertyActualUseCode,
	    propertyRevisionYear:propertyRevisionYear,
	    totalMarketValue:baseMarketValue,
	    "_token": $("#_csrf_token").val()
	}; 
	$.ajax({
	    type: "POST",
	    url: DIR+'rptproperty/getassessementlevel',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	hideLoader();
	    	if(html.status == 'success'){
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".al_assessment_level_hidden").val(html.data.al_assessment_level);
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".al_minimum_unit_value").val(html.data.al_minimum_unit_value);
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".al_maximum_unit_value").val(html.data.al_maximum_unit_value);
	    	}else{
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".al_assessment_level_hidden").val('00.00');
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".al_minimum_unit_value").val('00.00');
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".al_maximum_unit_value").val('00.00');
	    	}
	    calculateLandAssessedValuePre();
	    	
	    },error:function(){
	    	hideLoader();
	    }
	});
	}
}

function calculateLandMarketBaseValuePre() {
	var landAppraisalUnitValue = $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.lav_unit_value').val();
    var tatalLandArea    = $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.rpa_total_land_area').val();
    var mesureType       = $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.lav_unit_measure').val();
    tatalLandArea = tatalLandArea;
    var totalMarketValue = tatalLandArea*landAppraisalUnitValue;
    $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.rpa_base_market_value').val(parseFloat(totalMarketValue).toFixed(2));
    $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.rpa_adjusted_market_value').val(parseFloat(totalMarketValue).toFixed(2));
    getAssessementLevelPre();
    calculateLandAssessedValuePre();

}

function calculateLandAssessedValuePre() {
	var totalBaseMarketValue = $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.rpa_base_market_value').val();
	var maxUnitValue         = $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.al_maximum_unit_value').val();
	var minUnitValue         = $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.al_minimum_unit_value').val();
	var assessementPerscenta = $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.al_assessment_level_hidden').val();
	console.log(totalBaseMarketValue, maxUnitValue, minUnitValue, assessementPerscenta);
	if(parseFloat(totalBaseMarketValue) >= parseFloat(minUnitValue) && parseFloat(totalBaseMarketValue) <= parseFloat(maxUnitValue)){
		var newAssessementPerscenta = assessementPerscenta;
	}else{
		var newAssessementPerscenta = 0;
	}
	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.al_assessment_level').val(newAssessementPerscenta);
    var assessedValue        = (totalBaseMarketValue*newAssessementPerscenta)/100;
    $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.rpa_assessed_value').val(parseFloat(assessedValue).toFixed(2));
}

function getLandUnitValuePre(classId = '', subClassId = '', actualUseCodeId = ''){
	/*$('#myModal'+mid).find(".activity_id").val('');*/
	var revisionYearId = $('#addPreviousOwnerForLandModal').find('.rvy_revision_year_id').val();
	var barangayId     = $('#addPreviousOwnerForLandModal').find('select[name=brgy_code_id]').val();
	var localityId     = $('#addPreviousOwnerForLandModal').find('input[name=loc_local_code]').val();
	var classId        = $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.pc_class_code').val();
	var subCkassId     = $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.ps_subclass_code').val();
	var actualUseCodeId= $('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find('.pau_actual_use_code').val();
	console.log('land unit value','revision Year '+revisionYearId,'barngy '+barangayId,'locality '+localityId,'class '+classId,'subclass '+subCkassId,'actual use '+actualUseCodeId);
	if(revisionYearId != '' && barangayId != '' && localityId != '' && classId != '' && subCkassId != '' && actualUseCodeId != ''){
		$('.loadingGIF').show();
		showLoader();
	var filtervars = {
	    revisionYearId:revisionYearId,
	    barangayId:barangayId,
	    localityId:localityId,
	    classId:classId,
	    subCkassId:subCkassId,
	    actualUseCodeId:actualUseCodeId,
	    "_token": $("#_csrf_token").val()
	}; 
	$.ajax({
	    type: "POST",
	    url: DIR+'rptproperty/getlandunitvalue',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
            hideLoader();
	    	if(html.status == 'success'){
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".rls_percent").val(html.data.rls_percent);
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".rls_code").val(html.data.rls_code);
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".lav_strip_unit_value").val(html.data.lav_strip_unit_value);
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".lav_unit_value").val(html.data.lav_unit_value);
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".lav_unit_measure").val(html.data.lav_unit_measure_name);
	    	}else{
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".rls_percent").val('');
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".rls_code").val('');
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".lav_strip_unit_value").val('');
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".lav_unit_value").val('');
	    	$('#addPreviousOwnerForLandModal').find('#addlandappraisalmodalForPreOwner').find(".lav_unit_measure").val('');
	    	}
	    	calculateLandMarketBaseValuePre();
	    	
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