$(document).ready(function(){
	$("#rvy_revision_year_id").select3({dropdownAutoWidth : false,dropdownParent : '#commonModal'});
	$("#brgy_code_id").select3({dropdownAutoWidth : false,dropdownParent : '#commonModal'});
	$("#asse_summary_pc_class_code").select3({dropdownAutoWidth : false,dropdownParent : '#commonModal'});
	/*$("#B").select3({dropdownAutoWidth : false,dropdownParent : '#accordionFlushExampleOwner'});
	$("#L").select3({dropdownAutoWidth : false,dropdownParent : '#accordionFlushExampleOwner'});*/
	$('#commonModal').on('keyup','.calclulatebasemarketvalueandmarketvalue',function(){
		calculateDataForOtherFields();
	});
	initiateTdRemoteSelectListBuilding(0);
	initiateTdRemoteSelectListLand(0);

	$('#annotationSpecialPropertyStatusModal').off('click','.property_type').on('click','.property_type', function(){
		$('#annotationSpecialPropertyStatusModal').find('.property_type').not(this).prop('checked', false);
	})

	$('#annotationSpecialPropertyStatusModal').off('click','.property_type_new').on('click','.property_type_new', function(){
		$('#annotationSpecialPropertyStatusModal').find('.property_type_new').not(this).prop('checked', false);
	})

	$('#commonModal').find(".profile_id").select3({
    placeholder: 'Select Property Owner',
    allowClear: true,
    dropdownParent: $('#commonModal').find(".profile_id").parent(),
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
	
	$('#commonModal').find("#property_administrator_id").select3({
    placeholder: 'Select Property Administrator',
    allowClear: true,
    dropdownParent: $('#commonModal').find("#property_administrator_id").parent(),
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

	/* Land Appraisal Value Adjustment Factors*/
    $(document).on('keyup', 'input[name="rpa_adjustment_factor_a"]',function(){
    	calculateLandAppAdjustmentPercentValue();
    });
    $('input[name="rpa_adjustment_factor_a"]').unbind('keyup');

     $(document).on('keyup', 'input[name="rpa_adjustment_factor_b"]',function(){
    	calculateLandAppAdjustmentPercentValue();
    });
     $('input[name="rpa_adjustment_factor_b"]').unbind('keyup');

     $(document).on('keyup', 'input[name="rpa_adjustment_factor_c"]',function(){
    	calculateLandAppAdjustmentPercentValue();
    });

     $('#commonModal').off('change','select[name=profile_id]').on('change','select[name=profile_id]',function(){
     	var id=$(this).val();
     	var propId = $('#commonModal').find('#id').val();
    	if($('#commonModal').find('#old_property_id').val() == '' && propId == 0){
			initiateTdRemoteSelectListBuilding(id);
			$('#commonModal').find('.myCheckboxBuilding').prop('checked', true);
			initiateTdRemoteSelectListLand(id);
			$('#commonModal').find('.myCheckboxLand').prop('checked', true);
    	}
	});

    /* $('#commonModal').off('click','input[name=pk_is_active]').on('click','input[name=pk_is_active]',function(){
		var ucCode = $('#commonModal').find('input[name=update_code]').val();
		if(ucCode == 'DC'){
			activeInactiveCancelledPart();
		}
	});*/
    
    $('#commonModal').off('click','.myCheckboxBuilding').on('click','.myCheckboxBuilding',function() {
		if($(this).is(":checked")) {
		    var id=$('#commonModal').find("#profile").val();
			initiateTdRemoteSelectListBuilding(id);
	        $('#commonModal').find('.myCheckboxLand').click().prop('checked', true);
	    } else {
		  $('#commonModal').find('.myCheckboxLand').click().prop('checked', false);
	      initiateTdRemoteSelectListBuilding(0);
	    }
	}); 
	$('#commonModal').off('click','.myCheckboxLand').on('click','.myCheckboxLand',function() {
		if($(this).is(":checked")) {
		    var id=$('#commonModal').find("#profile").val();
			initiateTdRemoteSelectListLand(id);
	    } else {
	      initiateTdRemoteSelectListLand(0);
	    }
	}); 
	$(document).on('click','.refeshbuttonselect2',function(){
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
				$('.profile_id').html($data);
				hideLoader();
			},
			error: function(err){
				console.log(err);
				hideLoader();
			}
		});
    })
	$(document).on('click','.refeshbuttonselect',function(){
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
				$('.property_administrator_id').html($data);
				hideLoader();
			},
			error: function(err){
				console.log(err);
				hideLoader();
			}
		});
    })

     $('input[name="rpa_adjustment_factor_c"]').unbind('keyup');
    $(document).on('keyup','.decimalvalue',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
		});
   
    $("#uploadAttachmentbtn").click(function(){
 		uploadAttachment();
 	});

 	$(".btn_delete_documents").click(function(){
 		deleteDocuments($(this));
 	})

    $('#commonModal').off('click','#displayAnnotationSpecialPropertyStatusModal').on('click','#displayAnnotationSpecialPropertyStatusModal',function(){
		var propertyId = $(this).data('propertyid');
		$('#annotationSpecialPropertyStatusModal').modal('show');
		saveAnnotationSpecialPropertyStatus(propertyId);
	});

	$('#approvalformModal').on('click','.deletePreviousOwnerTd',function(){
		var id      = $(this).data('id');
		var history = $(this).data('history');
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
			          	deletePreviousOwnerTd(id,history);
			        }
			    })
		
	});

	$("#addPreviousOwnerForMachinery").unbind("click");
	$(document).off('click','#addPreviousOwnerForMachinery').on('click','#addPreviousOwnerForMachinery',function(){
		var propId = $(this).data('propertyid');
		var url = DIR+'rptmachinery/loadpreviousowner'+'?oldpropertyid='+propId;
        var title1 = 'Manage Previous Owner';
        var title2 = 'Manage Previous Owner';
        var title = (title1 != undefined) ? title1 : title2;
        var size = 'xll';
        var modalId = 'addPreviousOwnerForMachineryModal';
        showLoader();
	    //$("#"+modalId).unbind("click");
	    $("#"+modalId+" .modal-title").html(title);
	    $("#"+modalId+" .modal-dialog").addClass('modal-' + size);
    
	    $.ajax({
	        url: url,
	        success: function (data) {
	            hideLoader();
	            if(typeof data.status !== 'undefined' && data.status == 'error'){
	                Swal.fire({
	                      position: 'center',
	                      icon: 'error',
	                      title: data.msg,
	                      showConfirmButton: true,
	                      timer: 3000
	                    })

	            }else{
	                $('#'+modalId+' .body').html('');
	                $('#'+modalId+' .body').html(data);
	                $("#"+modalId).modal('show');
	                taskCheckbox();
	                commonLoader();
	            }
	            
	        },
	        error: function (data) {
	            hideLoader();
	            $('#'+modalId).modal('hide');
	            data = data.responseJSON;
	            show_toastr('Error', data.error, 'error')
	        }
	    });
	});
    
	/* Land Appraisal Value Adjustment Factors*/
	$(document).off('submit','#storePropertyOwnerForm').on('submit','#storePropertyOwnerForm',function(e){
		e.preventDefault();
		var url =  $('#addPropertyOwnerModal').find('form').attr('action');
		var method = $('#addPropertyOwnerModal').find('form').attr('method');
		var data   = $('#addPropertyOwnerModal').find('form').serialize();
		$.ajax({
	    type: "POST",
	    url: url,
	    data: data,
	    dataType: "json",
	    success: function(html){ 
	    	if(html.status == 'success'){
	    		$('#addPropertyOwnerModal').modal('hide');
	    		loadPropertyOwners();
	    	}
	    }
	});

	});

	$('#approvalformModal').on('click','.editPreviousOwnerTd',function(){
		var propId = $(this).data('id');
		var url = DIR+'rptmachinery/loadpreviousowner'+'?id='+propId;
        var title1 = 'Edit Previous Owner';
        var title2 = 'Edit Previous Owner';
        var title = (title1 != undefined) ? title1 : title2;
        var size = 'xll';
        var modalId = 'addPreviousOwnerForMachineryModal';
        showLoader();
	    //$("#"+modalId).unbind("click");
	    $("#"+modalId+" .modal-title").html(title);
	    $("#"+modalId+" .modal-dialog").addClass('modal-' + size);
    
	    $.ajax({
	        url: url,
	        success: function (data) {
	            hideLoader();
	            if(typeof data.status !== 'undefined' && data.status == 'error'){
	                Swal.fire({
	                      position: 'center',
	                      icon: 'error',
	                      title: data.msg,
	                      showConfirmButton: true,
	                      timer: 3000
	                    })

	            }else{
	                $('#'+modalId+' .body').html('');
	                $('#'+modalId+' .body').html(data);
	                $("#"+modalId).modal('show');
	                taskCheckbox();
	                commonLoader();
	            }
	            
	        },
	        error: function (data) {
	            hideLoader();
	            $('#'+modalId).modal('hide');
	            data = data.responseJSON;
	            show_toastr('Error', data.error, 'error')
	        }
	    });

		});

	$('.searchForLandOrBuilding').unbind('click');
$('#commonModal').off('change','.searchForLandOrBuilding').on('change','.searchForLandOrBuilding',function(){
	searchForLandOrBuilding($(this),true);
});
/*if($('#commonModal').find('.searchForLandOrBuilding').val() > 0){
	searchForLandOrBuilding($('#commonModal').find('.searchForLandOrBuilding'),true);
}*/

function searchForLandOrBuilding(element,$flag = false) {
	var propertyKind = element.attr('id');
	if(propertyKind == "B"){
		var tdNo = element.val(); 
	}else{
		var tdNo = element.val(); 
	}
	var brgy = $('#brgy_code_id').val();
	showLoader();
	var url = DIR+'rptmachinery/searchlandorbuilding';
	$('.loadingGIF').show();
	var filtervars = {
	    rp_td_no_bref:tdNo,
	    rp_td_no_lref:tdNo,
	    brgy_code_id:brgy,
	    propertyKind:propertyKind
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
	    		$('.validate-err').html('');
                    $('#err_'+html.field_name).html(html.error);
                    $('.'+html.field_name).focus();
                    //alert(html.data.properyKind);
                    if(html.data.propertyKind == "B"){
                        $('#propertyTaxDeclarationForm').find('input[name=rp_code_bref]').val('');
                    	$('#propertyTaxDeclarationForm').find('input[name=building_owner]').val('');
                    	$('#propertyTaxDeclarationForm').find('input[name=rp_pin_no_bref]').val('');
                    	$('#propertyTaxDeclarationForm').find('input[name=rp_pin_suffix_bref]').val('');
                    	$('#propertyTaxDeclarationForm').find('input[name=rp_section_no_bref]').val('');
                    }else{
                    	$('#propertyTaxDeclarationForm').find('input[name=rp_code_lref]').val('');
                    	$('#propertyTaxDeclarationForm').find('input[name=land_owner]').val('');
                    	$('#propertyTaxDeclarationForm').find('input[name=rp_pin_no_lref]').val('');
                    	$('#propertyTaxDeclarationForm').find('input[name=rp_pin_suffix_lref]').val('');
                    	$('#propertyTaxDeclarationForm').find('input[name=rp_section_no_lref]').val('');
                    	$('#propertyTaxDeclarationForm').find('input[name=rpo_code_lref]').val('');
                    	$('input[name=rp_section_no]').val('');
                    	$('input[name=rp_section_no]').val('');
                    }
	    	}if(html.status == 'success'){
	    		$('.validate-err').html('');
	    		if(html.data.propertyKind == "B"){
	    			    var source = $('#B').find(':selected').data('custom-attribute');
	    			    var rpcodelref = html.data.rp_code_lref;
					      if(rpcodelref > 0 && source === undefined){
					         var rpcodelreftext = html.data.buildRefLandTdNo;
					               $('#commonModal').find("#L").select3("trigger", "select", {
					    data: { id: rpcodelref ,text:rpcodelreftext}
					});
					      }
	    			    $('#propertyTaxDeclarationForm').find('input[name=rp_code_bref]').val(html.data.rp_code);
                    	$('#propertyTaxDeclarationForm').find('input[name=building_owner]').val(html.data.building_owner);
                    	$('#propertyTaxDeclarationForm').find('input[name=rp_pin_no_bref]').val(html.data.rp_pin_no_bref);
                    	$('#propertyTaxDeclarationForm').find('input[name=rp_pin_suffix_bref]').val(html.data.rp_pin_suffix_bref);
                    	$('#propertyTaxDeclarationForm').find('input[name=rp_section_no_bref]').val(html.data.rp_section_no_bref);
                    	$("#bpin").val(html.data.rp_pin_declaration_no);

                    }else{
                    	$('#propertyTaxDeclarationForm').find('input[name=rp_code_lref]').val(html.data.rp_code);
                    	$('#propertyTaxDeclarationForm').find('input[name=land_owner]').val(html.data.land_owner);
                    	$('#propertyTaxDeclarationForm').find('input[name=rp_pin_no_lref]').val(html.data.rp_pin_no_lref);
                    	$('#propertyTaxDeclarationForm').find('input[name=rp_pin_suffix_lref]').val(html.data.rp_pin_suffix_lref);
                    	$('#propertyTaxDeclarationForm').find('input[name=rp_section_no_lref]').val(html.data.rp_section_no_lref);
                    	$('#propertyTaxDeclarationForm').find('input[name=rpo_code_lref]').val(html.data.rpo_code_lref);
                    	$("#lpin").val(html.data.rp_pin_declaration_no);
                    	$('input[name=rp_section_no]').val(html.data.rp_section_no_lref);
                    	$('input[name=rp_pin_no]').val(html.data.rp_pin_no_lref);
                    	if($('#commonModal').find('#id').val() == 0 && $('#commonModal').find('input[name=update_code]').val() == 'DC'){
                    		createPinSuffix(html.data.rp_code, $('#id').val());
                    	}
                    	
                    }
	    	}
	    	
	    },error:function(){
	    	$('.validate-err').html('');
	    	hideLoader();
	    }
	});
}


	function loadPropertyOwners(id = '') {
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
	    	$('.loadingGIF').hide();
	    	$('select[name="profile_id"]').html(html); 
	    	$('select[name="property_administrator_id"]').html(html);
	    }
	});
	}
    $("#addPropertyOwnerModal").unbind("click");
	$(document).on('click','.addNewPropertyOwner',function(){
		var url = $(this).data('url');
        var title1 = 'Add New Property Owner';
        var title2 = 'Add New Property Owner';
        var title = (title1 != undefined) ? title1 : title2;
        var size = 'xll';
        $("#addPropertyOwnerModal .modal-title").html(title);
        $("#addPropertyOwnerModal .modal-dialog").addClass('modal-' + size);
        $("#addPropertyOwnerModal").modal('show');
    $.ajax({
        url: url,
        success: function (data) {
            $('#addPropertyOwnerModal .body').html(data);
            setTimeout(function(){ 
		    	$('#addPropertyOwnerModal').find("#p_barangay_id_no").select3({});
			        }, 500);
            taskCheckbox();
            //common_bind("#addPropertyOwnerModal");
            commonLoader();
        },
        error: function (data) {
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });

	})
    var propertyId = $('input[name=id]').val();
	
	loadMachineAppraisal(propertyId);

	$(document).off('change','#asse_summary_pc_class_code').on('change','#asse_summary_pc_class_code',function(){
		var propertyId = $('input[name=id]').val();
	    loadAssessementSumary(propertyId);
	});
	
	commonFunction();
	   $("#submit").on("click",function(){
    if (($("input[name*='Completed']:checked").length)<=0) {
       // alert("You must check at least 1 box");
    }
    return true;
});
	$("#btn_addmore_nature").click(function(){
		$('html, body').stop().animate({
      scrollTop: $("#btn_addmore_nature").offset().top
    }, 600);
		addmoreNature();
	});
	$("#btn_addmore_requirement").click(function(){
		$('html, body').stop().animate({
      scrollTop: $("#btn_addmore_requirement").offset().top
    }, 600);
		addmoreRequirements();

	});
	$('.numeric').numeric();
	$(".btn_cancel_nature").click(function(){
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
			          $(this).closest(".removenaturedata").remove();
			            var id =0;
						getNatureofRequirements(id);
			        }
			    })
		
	}); 
	$(".btn_cancel_requirement").click(function(){
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
			          $(this).closest(".removerequirementdata").remove();
			          setIscompletedname();
			        }
			    })
		
	});

	$(".btn_cancel_requirementedit").click(function(){
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
			          var id = $(this).attr('id');
		              $.ajax({
		                url: DIR+'deleteBploRequirement',
		                type: 'POST',
		                data: {
		                    "id": id, "_token": $("#_csrf_token").val(),
		                },
		                success: function (data) {
		                   var url = data;
		                   console.log(url);
		                   
		                }
		              });
		              $(this).closest(".removerequirementdata").remove();
		              setIscompletedname();
			        }
			    })
		
	});
	$("#profile").change(function(){

		var id=$(this).val();
		if(id){ getprofiledata(id); 
			  getTradedopdown(id);
			  }
	})

	$("#property_administrator_id").change(function(){
		var id=$(this).val();
		if(id){ getAdminprofiledata(id); 
			  getTradedopdown(id);}

	})
    if($("#property_administrator_id").val()>0){
		getAdminprofiledata($("#property_administrator_id").val());
		getTradedopdown($("#property_administrator_id").val());
    }
	$(document).on('change','.rpss_is_mortgaged',function(){
		if(this.checked){
			$(".mortgage_details_dection").css("pointer-events","auto");
		}else{
			$(".mortgage_details_dection").css("pointer-events","none");
			$('.mortgage_details_dection').find('input').val('');
			$('.mortgage_details_dection').find('select').val('');
		}

	});

	$("#displaySwornStatementModal").unbind("click");
	$(document).on('click','#displaySwornStatementModal',function(){
		var propertyId = $(this).data('propertyid');
		var landPropId = $('input[name=rp_code_lref]').val();
		$('#swornStatementModal').modal('show');
		saveSwornStatement(propertyId,landPropId);
		$(document).on('click','.closeSwornStatement',function(){
			$('#swornStatementModal').modal('hide');
		});

        
	});

	$(document).off('submit','#saveSwornStatement').on('submit','#saveSwornStatement',function(e){
		showLoader();
		e.preventDefault();
		var url = $(this).attr('action');
		var method = $(this).attr('method');
		var data   = $(this).serialize();
		$.ajax({
	    type: method,
	    url: url,
	    data: data,
	    dataType: "json",
	    success: function(html){ 
	    	hideLoader();
	    	if(html.status == 'success'){
	    		$('#swornStatementModal').modal('hide');
	    	}if(html.status == 'validation_error'){
	    		$('.validate-err').html('');
                    $('#err_'+html.field_name).html(html.error);
                    $('.'+html.field_name).focus();
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

	$(document).on('click','.deleteAnnotation',function(){
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
					  deleteAnnotation(id, sessionId);
			        }
			    })
            
		});
	$("#rvy_revision_year_id").change(function(){
		var id=$(this).val();
		if(id){ 
			getRvyRevisionYearDetails(id); 
			}

	})
	$("#brgy_code_id").change(function(){
		var id=$(this).val();
		var updateCode = $('input[name=update_code]').val();
		if(id != '' && updateCode == 'DC'){ 
			getbarangayaDetails(id); 
			}

	})
	if($("#profile").val()>0){
		//alert($("#profile").val());
		getprofiledata($("#profile").val());
	}
	if($('input[name=id]').val() == 0){
		getbarangayaDetails($('#brgy_code_id').val());
	}
	//alert($("#property_administrator_id").val());
	if($("#property_administrator_id").val()>0){
		getAdminprofiledata($("#property_administrator_id").val());
	}

	$("#barangay_id").change(function(){
		var id=$(this).val();
		getBarangyaDetails(id);
	})
	$("#locality_id").change(function(){
		var id=$(this).val();
		getLocalityDetails(id);
		setDistrictCodes(id);
	})
	$(".natureofbussiness").change(function(){
		var id=$(this).val();
		if(id){ 
			 getNatureofRequirements(id);
			  }
	})

	$("#ba_business_name").change(function(){
		checkNewandRenew();
	})
   
});

function deleteDocuments(thisval){
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
				   url :DIR+'rptproperty/deleteAttachment', // json datasource
				   type: "POST", 
				   data: {
					 "id": id,  
					 "_token": $("#_csrf_token").val(),
				   },
				   dataType: "html",
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
		formData.append('property_code', $('input[name="rp_property_code"]').val());
		showLoader();
		$.ajax({
	       url : DIR+'rptproperty/uploadDocument',
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
				    $(".btn_delete_documents ").unbind("click");
				    $(".btn_delete_documents ").click(function(){
				 		deleteRequirement($(this));
				 	})
				}
	       }
		});
	 }

function setIscompletedname(){
	$('.bariscompleted').each(function(index, value){
		$(this).attr("name",index+"_bar_is_complied");
    })
}

function setDistrictCodes(id) {
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptproperty/getdistrictcodes',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	//console.log(html.type);
	    	$('select[name="dist_code"]').html(html);
	    	/*$.each(html, function (index, value) {
	    		
    $('select[name="dist_code"]').append($('<option/>', { 
        value: value.dist_code,
        text : value.dist_code 
    }));
});*/  
	    	/*$('#app_type_id option[value="'+html.type+'"]').prop('selected',true);
	    	$("#bplo_code_abbreviation").html(html.reqoption);
	    	$("#bplo_code_abbreviation1").html(html.reqoption);*/
	    }
	});
}

function checkNewandRenew(){
	$('.loadingGIF').show();
	var filtervars = {
	    id:$("#profile").val(),
	    trade:$("#ba_business_name").val()
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'checkApptype',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	console.log(html.type);
	    	$('#app_type_id option[value="'+html.type+'"]').prop('selected',true);
	    	$("#bplo_code_abbreviation").html(html.reqoption);
	    	$("#bplo_code_abbreviation1").html(html.reqoption);
	    }
	});
}

function getbarangayaDetails(id){
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
	    	$('input[name=loc_group_brgy_no]').val(html.brgy_name);
	    	$('input[name=loc_local_name]').val(html.loc_local_name);
	    	$('input[name=brgy_code]').val(html.brgy_code);
	    	$('input[name=dist_code]').val(html.dist_code);
	    	$('input[name=dist_code_name]').val(html.dist_code+'-'+html.dist_name);
	    	$('input[name=brgy_code_and_desc]').val(html.brgy_code+'-'+html.brgy_name);
	    	$('input[name=loc_local_code_name]').val(html.mun_desc);
	    	$('input[name=loc_local_code]').val(html.loc_local_code_id);
	    	
	    }
	});
}

function getLocalityDetails(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptproperty/getmuncipalitycodedetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	/*$('input[name=brgy_code_id]').val(html.id);
	    	$('input[name=loc_group_brgy_no]').val(html.brgy_name);*/
	    	
	    }
	});
}


function addmoreRequirements(){
	var html = $("#hidenrequirementHtml").html();
	$(".requirementDetails").append(html);
	setIscompletedname();
	$(".btn_cancel_requirement").click(function(){
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
			          $(this).closest(".removerequirementdata").remove();
			          setIscompletedname();
			        }
			    })
	});
}

function removeData(cid){
	/*$('.loadingGIF').show();
	var filtervars = {
	    do_what:'deleteContactdetals',
	    cid:cid
	}; 
	$.ajax({
	    type: "GET",
	    url: 'savequestion/save1',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    }
	}); */
}

function getTradedopdown(id){
   $('.loadingGIF').show();
	var filtervars = {
	    pid:id,
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getTradedropdown',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#ba_business_name").html(html);
	    }
	});
}

function getAdminprofiledata(id){
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
	    		$('input[name="rp_administrator_code"]').val(arr.id);
	    		$('input[name="rp_administrator_code_address"]').val(arr.standard_address);
	    	
	    }
	});
}

function getRvyRevisionYearDetails(id){
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
	    	/*arr = $.parseJSON(html);*/
	    	$('input[name="rvy_revision_year"]').val(html.rvy_revision_year);
	    	$('input[name="rvy_revision_code"]').val(html.rvy_revision_code);
	    }
	});
}

function getprofiledata(id){
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
	    		$('input[name="property_owner_address"]').val(arr.standard_address);
	    		$('input[name="rpo_code"]').val(arr.id);
	    }
	});
}

function deleteLandAppraisal(id, sessionId) {
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
	    dataType: "json",
	    success: function(html){ 
	    	if(html.status == 'verifypsw'){
                $('#verifyPsw').modal('show');
                $('#verifyPsw').find('.validate-err').html('');
                $('#verifyPsw').find('input[name=verify_psw]').val('');
                
            }if(html.status == 'success'){
                loadMachineAppraisal($('input[name=id]').val());
            }
	    	
	    }
	});
}

function deletePlantTreeAppraisal(id, sessionId) {
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    "_token": $("#_csrf_token").val()
	}; 
	$.ajax({
	    type: "post",
	    url: DIR+'rptproperty/deleteplanttreeappraisal',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	loadPlantsTreesAdjustmentFactor($('input[name=id]').val());
	    }
	});
}


function commonFunction(){

	$('.deleteLandAppraisal').click(function(){
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
			          	deleteLandAppraisal(landAppraisalId);
			          }
			          calculateTotalMarketValue();
			        }
			    })
		
	});

	/*$("#loadLandApprisalForm").unbind("click");
	$("#loadLandApprisalForm").click(function(){
		$('#addlandappraisalmodal').modal('show');
		loadAddLandAppraisalForm(id = '');
		
		$(document).on('click','.closeLandAppraisalModal',function(){
			$('#addlandappraisalmodal').modal('hide');
		});

        
	}); */

	$("#loadMachineApprisalForm").unbind("click");
	$('#commonModal').off('click',"#loadMachineApprisalForm").on('click',"#loadMachineApprisalForm",function(){
		$('#addlandappraisalmodal').modal('show');
		loadAddLandAppraisalForm(id = '');
		
		$(document).on('click','.closeLandAppraisalModal',function(){
			$('#addlandappraisalmodal').modal('hide');
		});

        
	});

    $("#plantstreesadjustmentfactor").unbind("click");
	$("#plantstreesadjustmentfactor").click(function(){
		$('#treesplantsadjustmentfactormodal').modal('show');
		savePlantsTreesAdjustmentFactorForm(id = '');
		
		$(document).on('click','.closePlantsTreeFormModel',function(){
			$('#treesplantsadjustmentfactormodal').modal('hide');
		});

        
	}); 

    $('#plantstreesadjustmentfactornew').click(function(){
    	var selectedLandAppraisals = $('#new_added_land_apraisal').find("tbody tr .addLandAppraisalAdjustmentFactorOrPlantTree:checkbox:checked");
    	var length                 = selectedLandAppraisals.length;
    	if(length == 0){
    		Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'Please select at least one Land Appraisal',
                      showConfirmButton: true,
                      timer: 3000
                    })
    	}else if(length > 1){
    		Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'Please select only one record!',
                      showConfirmButton: true,
                      timer: 3000
                    })
    	}else{
    		var propertyId = $('#commonModal').find('input[name=id]').val();
    		//alert(propertyId);
    		var id        = selectedLandAppraisals.val();
    		var sessionId = selectedLandAppraisals.data('sessionid');
    		$('#landAppraisalAdjustmentFactorsmodal').modal('show');
            displayLandAppraisalAdjustmnetFactorForm(id, sessionId, propertyId);
    		$(document).on('click','.closelandAppraisalAdjustmentFactorsmodal',function(){
    			$('#landAppraisalAdjustmentFactorsmodal').modal('hide');

    		});
    	}
    });

	$(".collectapprovalformdata").unbind("click");
	$(".collectapprovalformdata").click(function(){
		var propertyId = $(this).data('propertyid');
        var updateCode = $('input[name=uc_code]').val();
		$('#approvalformModal').modal('show');
		
		saveApprovalFormData(propertyId,updateCode);
		
		$(document).on('click','.closeApprovalFormModel',function(){
			$('#approvalformModal').modal('hide');
		});

        
	}); 
	$(document).off('change','.pc_class_code').on('change','.pc_class_code',function(){
		var text = $('.pc_class_code option:selected').text();
		$('#addlandappraisalmodal').find("input[name=pc_class_description]").val(text);
			var id=$(this).val();
			if(id){ 
				getClassDetails(id);
				getSubClasses(id); 
				getActualUses(id);
				getLandUnitValue();
				getAssessementLevel();
				setTimeout(function(){ 
				var landArea = $('#addlandappraisalmodal').find('.rpa_total_land_area').val();
			var unitValue =$('#addlandappraisalmodal').find('.lav_unit_value').val();
			var baseMarketValue = landArea*unitValue;
            $('#addlandappraisalmodal').find('.rpa_base_market_value').val(baseMarketValue);
				 }, 500);
			}else{
				$('#addlandappraisalmodal').find(".tax_type_desc").val('');
				$('#addlandappraisalmodal').find(".tax_type_id").val('');
			}
		})
	$(document).off('change','.ps_subclass_code').on('change','.ps_subclass_code',function(){
			var id=$(this).val();
		    var text = $('.ps_subclass_code option:selected').text();
		$('#addlandappraisalmodal').find("input[name=ps_subclass_desc]").val(text);
			getLandUnitValue();
			setTimeout(function(){ 
			var landArea = $('#addlandappraisalmodal').find('.rpa_total_land_area').val();
			var unitValue =$('#addlandappraisalmodal').find('.lav_unit_value').val();
			var baseMarketValue = landArea*unitValue;
            $('#addlandappraisalmodal').find('.rpa_base_market_value').val(baseMarketValue);
				 }, 500);
		})
	$(document).off('change','.pau_actual_use_code').on('change','.pau_actual_use_code',function(){
		    var text = $('.pau_actual_use_code option:selected').text();
		$('#addlandappraisalmodal').find("input[name=pau_actual_use_desc]").val(text);
			getLandUnitValue();
			getAssessementLevel();
			setTimeout(function(){ 
				var landArea = $('#addlandappraisalmodal').find('.rpa_total_land_area').val();
			var unitValue =$('#addlandappraisalmodal').find('.lav_unit_value').val();
			var baseMarketValue = landArea*unitValue;
            $('#addlandappraisalmodal').find('.rpa_base_market_value').val(baseMarketValue);
				 }, 500);
			
		})
	$(document).off('change','.plants_tree_pc_class_code').on('change','.plants_tree_pc_class_code',function(){
            var id = $(this).val();
            var text = $('.plants_tree_pc_class_code option:selected').text();
		    $('#treesplantsadjustmentfactormodal').find('input[name=pc_class_description]').val(text);
			getSubClassesForPlantsTreeSection(id); 
			getPlantTreesUnitValue();
		});
	$(document).off('change','.rp_planttree_code').on('change','.rp_planttree_code',function(){
		var text = $('.rp_planttree_code option:selected').text();
		$('#treesplantsadjustmentfactormodal').find('input[name=pt_ptrees_description]').val(text);
            var id = $(this).val();
			getPlantTreesUnitValue();
		});
	$(document).off('change','.plants_tree_ps_subclass_code').on('change','.plants_tree_ps_subclass_code',function(){
            var text = $('.plants_tree_ps_subclass_code option:selected').text();
		    $('#treesplantsadjustmentfactormodal').find('input[name=ps_subclass_desc]').val(text);
            var id = $(this).val(); 
			getPlantTreesUnitValue();
		});
	$(document).on('keyup','.rpta_total_area_planted',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
            calculatePlantTreeMarketValue();

		});
	$(document).on('keyup','.rpta_non_fruit_bearing',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
		});
	$(document).on('keyup','.rpta_fruit_bearing_productive',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
		});
	$(document).on('keyup','.rpta_unit_value',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
		});
	$(document).on('keyup','.rpta_market_value',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
		});

	$(document).on('keyup','.rpa_total_land_area',function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
    calculateLandMarketBaseValue();
});


		 $(document).on('keyup','.rpa_adjusted_market_value',function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});
		 $(document).on('keyup','.rpa_assessed_value',function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
    });

        $(document).on('keyup','.lav_strip_unit_value',function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
    });

	$(document).on('click','.deletePlantTreeAppraisal',function(){
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
					  deletePlantTreeAppraisal(id, sessionId);
			        }
			    })
            
		});
	$(document).on('click','.deleteLandAppraisal',function(){
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
					  deleteLandAppraisal(id, sessionId);
			        }
			    })
            
		});
	$(document).on('click','.editPlantTreeAppraisal',function(){
            var id        = $(this).data('id');
            var sessionId = $(this).data('sessionid');
            $('#treesplantsadjustmentfactormodal').modal('show');
		    savePlantsTreesAdjustmentFactorForm(id, sessionId);
            //savePlantsTreesAdjustmentFactorForm(id);
            
		});
	$(document).off('click','.editLandAppraisal').on('click','.editLandAppraisal',function(){
            var id        = $(this).data('id');
            var sessionId = $(this).data('sessionid');
            $('#addlandappraisalmodal').modal('show');
		    loadAddLandAppraisalForm(id, sessionId);
            //savePlantsTreesAdjustmentFactorForm(id);
            
		});
	$(document).off('submit','#propertyTaxDeclarationForm').on('submit','#propertyTaxDeclarationForm',function(e){
		e.preventDefault();
		var id = $('#commonModal').find('#id').val();
		if(id > 0){
			var textForSwal = 'Update the New Tax Declaration?';
		}else{
			var textForSwal = 'Create New Tax Declaration?';
		}
		const swalWithBootstrapButtons = Swal.mixin({
	        customClass: {
	            confirmButton: 'btn btn-success',
	            cancelButton: 'btn btn-danger'
	        },
	        buttonsStyling: false
	    })

	    swalWithBootstrapButtons.fire({
	        title: 'Are you sure?',
	        text: textForSwal,
	        icon: 'warning',
	        showCancelButton: true,
	        confirmButtonText: 'Yes',
	        cancelButtonText: 'No',
	        reverseButtons: true
	    }).then((result) => {
	        if(result.isConfirmed)
	        {
	        	showLoader();
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
	    		$('#commonModal').modal('hide');
	    		$('#Jq_datatablelist').DataTable().ajax.reload();
	    		Swal.fire({
                      position: 'center',
                      icon: 'success',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: false
                    })
	    		
	    	}if(html.status == 'verifypsw'){
                $('#verifyPsw').modal('show');
                $('#verifyPsw').find('.validate-err').html('');
                $('#verifyPsw').find('input[name=verify_psw]').val('');
                
            }if(html.status == 'error'){

	    		Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: false
                    })
	    		
	    	}
	    },error:function() {
	    	hideLoader();
	    }
	});
	        	 }
	    })
		
	});

	$('#commonModal').off('submit','#verifyPswForm').on('submit','#verifyPswForm',function(e){
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
	    		Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: html.msg,
                  showConfirmButton: true,
                  timer: false
                }).then(function() {
				    $('#verifyPsw').modal('hide');
				});
	    		
	    	}if(html.status == 'validation_error'){
	    		$('#verifyPswForm').find("#err_"+html.field_name).html(html.error);
                $('#verifyPswForm').find("."+html.field_name).focus();
	    		
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

	
	
	$(document).off('submit','#saveApprovelFormData').on('submit','#saveApprovelFormData',function(e){
			e.preventDefault();
			e.stopPropagation();
			var url = $(this).attr('action');
			var method = $(this).attr('method');
			var cancelledBy = $('input[name=cancelled_by_id]').val();
			var data   = $(this).serialize() + '&cancelled_by_id=' + cancelledBy;
			//alert(data);
			$.ajax({
	    type: "POST",
	    url: url,
	    data: data,
	    dataType: "json",
	    success: function(html){ 
	    	if(html.status == 'validation_error'){
	    		$('#saveApprovelFormData').find('.validate-err').html('');
	    		$.each( html.data, function( key, value) {
                    $('#saveApprovelFormData').find('#err_'+key).html(value[0]);
                    $('#saveApprovelFormData').find('.err_'+key).focus();
        });
	    	}if(html.status == 'success'){
	    		$('#saveApprovelFormData').find('.validate-err').html('');
	    		$('#approvalformModal').modal('hide');
	    		
	    	}if(html.status == 'error'){
	    		$('#saveApprovelFormData').find('.validate-err').html('');
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

	function checkReadyToSubmit() {
		var dataToValidate = ['rpma_description','rpma_appr_no_units','rpma_acquisition_cost','rpma_base_market_value','rpma_market_value'];
		var checkArray = new Array();
		$('#storelandappraisal').find("input:text,textarea,select").each(function(a,b){
			var inputName = $(this).attr("name");
			if($.inArray(inputName,dataToValidate) !== -1 && $(this).val() == ''){
                  checkArray.push(inputName);
			}
		});
		if(checkArray.length > 0){
			return false;
		}else{
			return true;
		}
	}

	$(document).off('submit','#storelandappraisal').on('submit','#storelandappraisal',function(e){
		
		e.preventDefault();
		e.stopPropagation();
		if(checkReadyToSubmit()){
			const swalWithBootstrapButtons = Swal.mixin({
			        customClass: {
			            confirmButton: 'btn btn-success',
			            cancelButton: 'btn btn-danger'
			        },
			        buttonsStyling: false
			    })

			    swalWithBootstrapButtons.fire({
			        title: 'Are you sure?',
			        text: "This Will save the Current Changes",
			        icon: 'warning',
			        showCancelButton: true,
			        confirmButtonText: 'Yes',
			        cancelButtonText: 'No',
			        reverseButtons: true
			    }).then((result) => {
			        if(result.isConfirmed){
			          	storeMachineAppraisal($(this));
			        }
			    })
			
		}else{
			storeMachineAppraisal($(this));
		}
		

		});
	$(document).on('change','.rpss_beneficial_user_code',function(){
		var ownerName = $(this).find("option:selected").text();
		$('input[name=rpss_beneficial_user_name]').val(ownerName);

	});

	$(document).on('click','#saveAnnotationData',function(){
     	saveAnnotationDataToDB();
     });

	$(document).off('submit','#saveAnnotationPropertyStatus').on('submit','#saveAnnotationPropertyStatus',function(e){
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
	    		$('#annotationSpecialPropertyStatusModal').modal('hide');
	    	}if(html.status == 'validation_error'){
	    		$('.validate-err').html('');
                    $('#err_'+html.field_name).html(html.error);
                    $('.'+html.field_name).focus();
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

	function storeMachineAppraisal(ele) {
		showLoader();
		var url = ele.attr('action');
			var method = ele.attr('method');
			var data   = ele.serialize();
			$.ajax({
	    type: "post",
	    url: url,
	    data: data,
	    dataType: "json",
	    success: function(html){ 
	    	hideLoader();
	    	if(html.status == 'validation_error'){
	    		$('.validate-err').html('');
                    $('#err_'+html.field_name).html(html.error);
                    $('.'+html.field_name).focus();
	    	}if(html.status == 'verifypsw'){
                $('#verifyPsw').modal('show');
                $('#verifyPsw').find('.validate-err').html('');
                $('#verifyPsw').find('input[name=verify_psw]').val('');
                
            }if(html.status == 'success'){
	    		$('.validate-err').html('');
	    		$('#addlandappraisalmodal').modal('hide');
	    		loadMachineAppraisal($('input[name=id]').val());
	    	}
	    },error:function(){
	    	hideLoader();
	    }
	});
	}

	function saveAnnotationDataToDB() {
		showLoader();
		var url      = DIR+'rptmachinery/anootationspeicalpropertystatus';
		var dateTime = $('input[name=rpa_annotation_date_time]').val();
		var anooBy = $('.rpa_annotation_by_code').val();
		var anno = $('.rpa_annotation_desc').val();
		var propId = $('input[name=property_id]').val();
		var data   = {
			rpa_annotation_date_time:dateTime,
            rpa_annotation_by_code:anooBy,
            rpa_annotation_desc:anno,
            action:'annotation',
            property_id:propId,
            "_token": $("#_csrf_token").val()
		};
		$.ajax({
	    type: "POST",
	    url: url,
	    data: data,
	    dataType: "json",
	    success: function(html){ 
	    	hideLoader();
	    	if(html.status == 'success'){
	    		$('.validate-err').html('');
	    		$('#anootationForm').find('input[name=rpa_annotation_date_time]').val('');
	    		$('#anootationForm').find('.rpa_annotation_by_code').val('');
	    		$('#anootationForm').find('.rpa_annotation_desc').val('');
	    		loadPropertyAnnotations();
	    	}if(html.status == 'validation_error'){
	    		$('.validate-err').html('');
                    $('#err_'+html.field_name).html(html.error);
                    $('.'+html.field_name).focus();
                }
	    },error:function(){
	    	hideLoader();
	    }
	});
	}

	

    $(document).off('submit','#storeplantstreesadjustmentfactor').on('submit','#storeplantstreesadjustmentfactor',function(e){
			e.preventDefault();
			e.stopPropagation();
			var url = $(this).attr('action');
			var method = $(this).attr('method');
			var data   = $(this).serialize();
			$.ajax({
	    type: "POST",
	    url: url,
	    data: data,
	    dataType: "json",
	    success: function(html){ 
	    	if(html.status == 'validation_error'){
	    		$('.validate-err').html('');
	    		$.each( html.data, function( key, value) {
	    			if(key == 'plant_tree_revision_year_code'){
	    				Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'Please select revision year from main form!',
                      showConfirmButton: false,
                      timer: 3000
                    })
	    			}
                    $('#err_'+key).html(value[0]);
        });
	    	}if(html.status == 'success'){
	    		$('.validate-err').html('');
	    		$('#treesplantsadjustmentfactormodal').modal('hide');
	    		loadPlantsTreesAdjustmentFactor($('input[name=id]').val());
	    	}
	    }
	});

		});


	$(".btnPopupOpen").unbind("click");
	$(".btnPopupOpen").click(function(){
		
		type = $(this).attr('type');
		totalMarketValue = 0;
		if(type=='edit'){
			mid = $(this).attr('mid');
			$('#myModal'+mid).modal('show');
			var classId = $('#myModal'+mid).find(".pc_class_code").val();
			  /* getSubClasses(classId); 
				getActualUses(classId);
				getLandUnitValue();*/
			getClassDetails(classId);
		}else{
			var totalPopup = $("#popupDetails").find(".modalDiv").length;
			mid = totalPopup;
			$("#hidenPopupHtml").find(".bussiness-model").attr("id","myModal"+mid);
			$("#hidenPopupHtml").find(".closeModel").attr("mid",mid);
			var modelHtml = $("#hidenPopupHtml").html();
			$("#popupDetails").append(modelHtml);
			$('#myModal'+mid).modal('show');

			
			$('#myModal'+mid).find(".pc_class_code").attr('id','pc_class_code'+mid);
			$('#myModal'+mid).find(".pc_class_code").addClass('pc_class_code'+mid);

			$('#myModal'+mid).find(".ps_subclass_code").attr('id','ps_subclass_code'+mid);
			$('#myModal'+mid).find(".ps_subclass_code").addClass('ps_subclass_code'+mid);

			$('#myModal'+mid).find(".pau_actual_use_code").attr('id','activity_code'+mid);
			$('#myModal'+mid).find(".pau_actual_use_code").addClass('pau_actual_use_code'+mid);

			$('#myModal'+mid).find(".land_stripping_id").attr('id','land_stripping_id'+mid);
			$('#myModal'+mid).find(".land_stripping_id").addClass('land_stripping_id'+mid);

	       	if ($(".pc_class_code"+mid).length > 0) {
	       		
	       		$(".pc_class_code"+mid).select3({dropdownAutoWidth : false,dropdownParent: $("#myModal"+mid)});
	        }
	        if ($(".ps_subclass_code"+mid).length > 0) {
	       		$(".ps_subclass_code"+mid).select3({dropdownAutoWidth : false,dropdownParent: $("#myModal"+mid)});
	        }
	        if ($(".pau_actual_use_code"+mid).length > 0) {
	       		$(".pau_actual_use_code"+mid).select3({dropdownAutoWidth : false,dropdownParent: $("#myModal"+mid)});
	        }
	        if ($(".land_stripping_id"+mid).length > 0) {
	       		$(".land_stripping_id"+mid).select3({dropdownAutoWidth : false,dropdownParent: $("#myModal"+mid)});
	        }

		}
		
		$(".savebusinessDetails").unbind("click");
		$(".savebusinessDetails").click(function(){
			var inputFields=[
			'pc_class_code',
			'ps_subclass_code',
			'pau_actual_use_code',
			'lav_strip_unit_value',
			'rpa_total_land_area',
			'lav_unit_measure',
			'lav_unit_value',
			'rpa_base_market_value',
			'rpa_adjusted_market_value',
			'al_assessment_level',
			];
			for (let i = 0; i < inputFields.length; i++) {
				var inputValue = $('#myModal'+mid).find("."+inputFields[i]).val();
				if(inputValue==""){
					if(inputFields[i] == 'lav_unit_measure' || inputFields[i] == 'lav_unit_value' || inputFields[i] == 'al_assessment_level'){
						Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'Before add Land Appraisal, please complete main form!',
                      showConfirmButton: false,
                      timer: 3000
                    })

					}
					$('#myModal'+mid).find("#err_"+inputFields[i]).html("Required Field");
					$('#myModal'+mid).find("."+inputFields[i]).focus();
					return false;
				}else{
					$('#myModal'+mid).find("#err_"+inputFields[i]).html("");
				}
			}
			
			var classification = $('#myModal'+mid).find(".pc_class_code_description").val();
			var ps_subclass_code = $('#myModal'+mid).find(".ps_subclass_code :selected").text();
			var pau_actual_use_code = $('#myModal'+mid).find(".pau_actual_use_code :selected").text();
			var lav_strip_unit_value = $('#myModal'+mid).find(".rls_percent").val();
            var lav_unit_measure = $('#myModal'+mid).find(".lav_unit_measure").val();
			var rpa_total_land_area = $('#myModal'+mid).find(".rpa_total_land_area").val();
			var lav_unit_measure = $('#myModal'+mid).find(".lav_unit_measure").val();
			var lav_unit_value = $('#myModal'+mid).find(".lav_unit_value").val();
			var rpa_base_market_value = $('#myModal'+mid).find(".rpa_base_market_value").val();

			$("#hidenPopupListHtml").find(".pc_class_code").html(classification);
			$("#hidenPopupListHtml").find(".ps_subclass_code").html(ps_subclass_code);
			$("#hidenPopupListHtml").find(".pau_actual_use_code").html(pau_actual_use_code);
			$("#hidenPopupListHtml").find(".rpa_total_land_area").html(parseFloat(rpa_total_land_area).toFixed(3)+' '+lav_unit_measure);
			$("#hidenPopupListHtml").find(".lav_strip_unit_value").html(lav_strip_unit_value);
			$("#hidenPopupListHtml").find(".lav_unit_value").html(lav_unit_value);
			$("#hidenPopupListHtml").find(".rpa_base_market_value").html(parseFloat(rpa_base_market_value).toFixed(2));
			$("#hidenPopupListHtml").find(".btnPopupOpen").attr('mid',mid);
			$("#hidenPopupListHtml").find(".deleteLandAppraisal").attr('mid',mid);
			var listHtml = $("#hidenPopupListHtml").find(".font-style").html();
			if(type=='edit'){
				$("#trId"+mid).html(listHtml);
			}else{
				$(".last-option").before('<tr class="font-style" id="trId'+mid+'">'+listHtml+'</tr>');
			}
			$('#myModal'+mid).find(".closeModel").attr("mid",mid);
			$('#myModal'+mid).find(".closeModel").attr("type",'edit');

			$('#myModal'+mid).modal('hide');
			$("#hidenPopupHtml").find(".bussiness-model").attr("id","myModal");
			calculateTotalMarketValue(mid);
			commonFunction();

		});

		$(".closeModel").unbind("click");
		$(".closeModel").click(function(){
			mid = $(this).attr('mid');
			type = $(this).attr('type');
			$('#myModal'+mid).modal('hide');
			if(type=='add'){
				$('#myModal'+mid).remove();
			}
			mid = 0;
			commonFunction();
			/* $("form").css({
				'opacity': '',
				'z-index': ''
			})*/
			/*$("form").css({
				'opacity': 0.5,
				'z-index': 1000
			})*/
		})
		/*$(document).off('change','.pc_class_code').on('change','.pc_class_code',function(){
			var id=$(this).val();
			if(id){ 
				getClassDetails(id);
				getSubClasses(id); 
				getActualUses(id);
				getLandUnitValue();
				getAssessementLevel();
				setTimeout(function(){ 
				var landArea = $('#myModal'+mid).find('.rpa_total_land_area').val();
			var unitValue =$('#myModal'+mid).find('.lav_unit_value').val();
			var baseMarketValue = landArea*unitValue;
            $('#myModal'+mid).find('.rpa_base_market_value').val(baseMarketValue);
				 }, 500);
			}else{
				$('#myModal'+mid).find(".tax_type_desc").val('');
				$('#myModal'+mid).find(".tax_type_id").val('');
			}
		})*/
		

		

		$(".land_stripping_id").change(function(){
		var id=$(this).val();
		getLandStrippingDetails(id);
	})
		

		

		$(".land_stripping_id").change(function(){
		var id=$(this).val();
		getLandStrippingDetails(id);
	})

		 
		$(".classification_code").change(function(){
			var id=$(this).val();
			if(id){ getClasificationDesc(id); getActivityDrodown(id);
				var currcapitalize = $('#myModal'+mid).find(".capitalization").val();
				if(currcapitalize ==""){ $('#myModal'+mid).find("#err_capitalization").html("Please Enter Capitalization"); }
				else{ $('#myModal'+mid).find("#err_capitalization").html("");}
			}else{
				$('#myModal'+mid).find(".classification_desc").val('');
				$('#myModal'+mid).find(".classification_id").val('');
			}
		})  
		$(".activity_code").change(function(){
			var id=$(this).val();
			if(id){ getActivityDesc(id); getAllFeeDetails();
			}else{
				$('#myModal'+mid).find(".activity_desc").val('');
				$('#myModal'+mid).find(".activity_id").val('');
			}
		}) 

		$(".capitalization").focusout(function(){
			$capvalue = $(this).val();
			if($capvalue !=""){$('#myModal'+mid).find("#err_capitalization").html("");}
		})

		
		
	})
}

function createPinSuffix(id, propId) {
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
	    	$('input[name=rp_pin_suffix]').val(html.data.suffix);
	    },error:function(){
	    	
	    }
	});
}

function calculateDataForOtherFields(){
    	/* Calculate Base Market Value */
    	var units          = parseFloat($('#addlandappraisalmodal').find('.rpma_appr_no_units ').val());
	    var acquCost       = parseFloat($('#addlandappraisalmodal').find('.rpma_acquisition_cost').val());
	    var freistCost     = parseFloat($('#addlandappraisalmodal').find('.rpma_freight_cost').val());
	    var insuCost       = parseFloat($('#addlandappraisalmodal').find('.rpma_insurance_cost').val());
	    var installCost    = parseFloat($('#addlandappraisalmodal').find('.rpma_installation_cost').val());
	    var otherCost      = parseFloat($('#addlandappraisalmodal').find('.rpma_other_cost').val());
	    var depRate        = parseFloat($('#addlandappraisalmodal').find('.rpma_depreciation_rate').val());
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
	    $('#addlandappraisalmodal').find('.rpma_base_market_value').val(parseFloat(basemarketvalue).toFixed(2));
	    /* Calculate Base Market Value */

	    /* Calculate Total Base Market Value */
	    var depreValue  = (basemarketvalue*depRate)/100;
	    var marketValue = (basemarketvalue-depreValue);
	    $('#addlandappraisalmodal').find('.rpma_depreciation').val(parseFloat(depreValue).toFixed(2));
    	$('#addlandappraisalmodal').find('.rpma_market_value').val(parseFloat(marketValue).toFixed(2));
	    /* Calculate Total Base Market Value */
    }

function calculateTotalMarketValue(mid) {
	var totalMarketValue = 0;
	/*var rows= $('#new_added_land_apraisal tbody tr').length;
	$("#new_added_land_apraisal tbody tr").each(function () {
    alert($(this).find('td.rpa_base_market_value').text());
});*/
	$('#new_added_land_apraisal').find("tbody tr .rpa_base_market_value").each(function(total){
		var marketValue = $(this).text();
		totalMarketValue += parseFloat(marketValue);

	});
	var previousValue = $('#landApraisalTotalValueToDisplay').val();
	$('#landApraisalTotalValueToDisplay').val(parseFloat(totalMarketValue).toFixed(2));
}

function deletePreviousOwnerTd(id,history) {
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    historyid:history,
	    "_token": $("#_csrf_token").val()
	}; 
	$.ajax({
	    type: "post",
	    url: DIR+'rptproperty/deletepreviousownertd',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	var propertyId = $('#propertyTaxDeclarationForm').find('input[name=id]').val();
            var updateCode = $('#propertyTaxDeclarationForm').find('input[name=uc_code]').val();
		    saveApprovalFormData(propertyId,updateCode);
	    	
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function getActualUses(id){
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
	    	console.log($('#addlandappraisalmodal').find(".classification_code"));
	    	$('#addlandappraisalmodal').find(".pau_actual_use_code").html(html);
			/*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);*/
	    }
	});
}

function getSubClasses(id){
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
	    	$('#addlandappraisalmodal').find(".ps_subclass_code").html(html);
			/*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);*/
	    }
	});
}

function getSubClassesForPlantsTreeSection(id){
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
	    	$('#treesplantsadjustmentfactormodal').find(".plants_tree_ps_subclass_code").html(html);
			/*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);*/
	    }
	});
}

function saveApprovalFormData(id,ucCode) {
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    updatecode:ucCode,
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptmachinery/approve',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('#approvalform').html(html);
	    	$('#saveApprovelFormData').find(".err_rp_app_appraised_by").select3({dropdownAutoWidth : false,dropdownParent : $('#saveApprovelFormData').find(".err_rp_app_appraised_by").parent()});
	    	$('#saveApprovelFormData').find(".err_rp_app_recommend_by").select3({dropdownAutoWidth : false,dropdownParent : $('#saveApprovelFormData').find(".err_rp_app_recommend_by").parent()});
	    	$('#saveApprovelFormData').find(".err_rp_app_approved_by").select3({dropdownAutoWidth : false,dropdownParent : $('#saveApprovelFormData').find(".err_rp_app_approved_by").parent()});
	    	$('#saveApprovelFormData').find(".rp_app_cancel_type").select3({dropdownAutoWidth : false,dropdownParent : $('#saveApprovelFormData').find(".rp_app_cancel_type").parent()});
	    	$('#saveApprovelFormData').find(".rp_app_cancel_by_td_id").select3({dropdownAutoWidth : false,dropdownParent : $('#saveApprovelFormData').find(".rp_app_cancel_by_td_id").parent()});
	    	$('#saveApprovelFormData').find(".err_rp_app_taxability").select3({dropdownAutoWidth : false,dropdownParent : $('#saveApprovelFormData').find(".err_rp_app_taxability").parent()});
	    	$('#saveApprovelFormData').find(".err_rp_app_effective_quarter").select3({dropdownAutoWidth : false,dropdownParent : $('#saveApprovelFormData').find(".err_rp_app_effective_quarter").parent()});
	    }
	});
}

function loadAddLandAppraisalForm(id, sessionId) {
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    property_id:$('input[name=id]').val()
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptmachinery/storemachineappraisal',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('#landappraisalform').html(html);
	    	var revisionYearId = $('.rvy_revision_year_id').val();
		    var revisionYear   = $('input[name=rvy_revision_year]').val();
		    setTimeout(function(){ 
		    	$('#addlandappraisalmodal').find(".pc_class_code").select3({});
		    	$('#addlandappraisalmodal').find(".ps_subclass_code").select3({});
		    	$('#addlandappraisalmodal').find(".pau_actual_use_code").select3({});
		    	$('#addlandappraisalmodal').find(".land_stripping_id").select3({});
		        /*$('#addlandappraisalmodal').find('input[name=plant_tree_revision_year_code]').val(revisionYearId);
		        $('#addlandappraisalmodal').find('input[name=plant_tree_revision_year]').val(revisionYear);
		         getPlantTreesUnitValue();*/
			        }, 500);
	    }
	});
}

function savePlantsTreesAdjustmentFactorForm(id, sessionId){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    property_id:$('input[name=id]').val()
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptproperty/storetressadjustmentfactor',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('#plantstreesadjustmentfacctorform').html(html);
	    	var revisionYearId = $('input[name=rvy_revision_year_id]').val();
		    var revisionYear   = $('input[name=rvy_revision_year]').val();
		    setTimeout(function(){ 
		    	$('#treesplantsadjustmentfactormodal').find(".rp_planttree_code").select3({});
		    	$('#treesplantsadjustmentfactormodal').find(".plants_tree_pc_class_code").select3({});
		    	$('#treesplantsadjustmentfactormodal').find(".plants_tree_ps_subclass_code").select3({});
		        $('#treesplantsadjustmentfactormodal').find('input[name=plant_tree_revision_year_code]').val(revisionYearId);
		        $('#treesplantsadjustmentfactormodal').find('input[name=plant_tree_revision_year]').val(revisionYear);
		         getPlantTreesUnitValue();
			        }, 500);
	    }
	});
}

function getClassDetails(id){
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
	    	
	    	$('#addlandappraisalmodal').find(".pc_class_code_description").val(html.pc_class_description);
	    	
	    }
	});
}

function getLandStrippingDetails(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptproperty/gelandstrippingdetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	/*$('#myModal'+mid).find(".rls_percent").val(html.rls_percent);
	    	$('#myModal'+mid).find(".rls_code").val(html.rls_code);
	    	$('#myModal'+mid).find(".lav_strip_unit_value").val(0);*/
	    }
	});
}

function getSubClassDetails(argument) {
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptproperty/getsubclassdetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$('#addlandappraisalmodal').find(".pc_class_code_id").val(html.id);
	    }
	});
}

function loadMachineAppraisal(id) {
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
	    	$('#landAppraisalListing').html(html.view);
	    	$('#machineAppraisalDescription').html(html.view2);
	    	loadAssessementSumary($('input[name=id]').val());
	    }
	});
}


function getPlantTreesUnitValue(){
	/*$('#myModal'+mid).find(".activity_id").val('');*/
	var platTreeId               = $('#treesplantsadjustmentfactormodal').find('.rp_planttree_code').val();
	var plantTreeclassId         = $(document).find('.plants_tree_pc_class_code').val();
	var plantTreesubClassId      = $('#treesplantsadjustmentfactormodal').find('.plants_tree_ps_subclass_code').val();
	var plantTreerevisionYearId  = $('#treesplantsadjustmentfactormodal').find('input[name=plant_tree_revision_year_code]').val();
	if(platTreeId != '' && plantTreeclassId != '' && plantTreesubClassId !== null && plantTreerevisionYearId != ''){
		$('.loadingGIF').show();
	var filtervars = {
	    platTreeId:platTreeId,
	    classId:plantTreeclassId,
	    subClassId:plantTreesubClassId,
	    revisionYearId:plantTreerevisionYearId,
	    "_token": $("#_csrf_token").val()
	}; 
	$.ajax({
	    type: "post",
	    url: DIR+'rptproperty/getplanttreeunitvalue',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	if(html.status == 'success'){
	    	$('#treesplantsadjustmentfactormodal').find("input[name=rpta_unit_value]").val(parseFloat(html.data.ptuv_unit_value).toFixed(2));
	    	calculatePlantTreeMarketValue();
	    	}else{
	    	$('#treesplantsadjustmentfactormodal').find("input[name=rpta_unit_value]").val('0.00');	
	    	calculatePlantTreeMarketValue();
	    	}
	    	
	    }
	});
	}
}

function getAssessementLevel(){
	/*$('#myModal'+mid).find(".activity_id").val('');*/
	var propertyKind          = $('input[name=pk_id]').val();
	var propertyClass         = $('#addlandappraisalmodal').find('.pc_class_code').val();
	var propertyActualUseCode = $('#addlandappraisalmodal').find('.pau_actual_use_code').val();
	var propertyRevisionYear  = $('.rvy_revision_year_id').val();
	console.log(propertyKind, propertyClass, propertyActualUseCode, propertyRevisionYear);
	if(propertyKind != '' && propertyClass != '' && propertyActualUseCode !== null && propertyRevisionYear != ''){
		//alert();
		$('.loadingGIF').show();
	var filtervars = {
	    propertyKind:propertyKind,
	    propertyClass:propertyClass,
	    propertyActualUseCode:propertyActualUseCode,
	    propertyRevisionYear:propertyRevisionYear,
	    "_token": $("#_csrf_token").val()
	}; 
	$.ajax({
	    type: "POST",
	    url: DIR+'rptproperty/getassessementlevel',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	if(html.status == 'success'){
	    	$('#addlandappraisalmodal').find(".al_assessment_level").val(html.data.al_assessment_level);
	    	}else{
	    	$('#addlandappraisalmodal').find(".al_assessment_level").val('00.00');
	    	}
	    calculateLandAssessedValue();
	    	
	    }
	});
	}
}

function calculateLandMarketBaseValue() {
	var landAppraisalUnitValue = $('#addlandappraisalmodal').find('.lav_unit_value').val();
    var tatalLandArea    = $('#addlandappraisalmodal').find('.rpa_total_land_area').val();
    var mesureType       = $('#addlandappraisalmodal').find('.lav_unit_measure').val();
    if(mesureType == 2){
    	tatalLandArea = tatalLandArea*10000;
    }if(mesureType == 1){
    	tatalLandArea = tatalLandArea;
    }
    var totalMarketValue = tatalLandArea*landAppraisalUnitValue;
    $('#addlandappraisalmodal').find('.rpa_base_market_value').val(parseFloat(totalMarketValue).toFixed(2));
    $('#addlandappraisalmodal').find('.rpa_adjusted_market_value').val(parseFloat(totalMarketValue).toFixed(2));
    calculateLandAssessedValue();
}

function calculateLandAssessedValue() {
	var totalBaseMarketValue = $('#addlandappraisalmodal').find('.rpa_base_market_value').val();
    var assessementPerscenta = $('#addlandappraisalmodal').find('.al_assessment_level').val();
    var assessedValue        = (totalBaseMarketValue*assessementPerscenta)/100;
    $('#addlandappraisalmodal').find('.rpa_assessed_value').val(parseFloat(assessedValue).toFixed(2));
}

function getLandUnitValue(classId = '', subClassId = '', actualUseCodeId = ''){
	/*$('#myModal'+mid).find(".activity_id").val('');*/
	var revisionYearId = $('.rvy_revision_year_id').val();
	var barangayId     = $('select[name=brgy_code_id]').val();
	var localityId     = $('input[name=loc_local_code]').val();
	var classId        = $('#addlandappraisalmodal').find('.pc_class_code').val();
	var subCkassId     = $('#addlandappraisalmodal').find('.ps_subclass_code').val();
	var actualUseCodeId= $('#addlandappraisalmodal').find('.pau_actual_use_code').val();
	console.log('land unit value',revisionYearId,barangayId,localityId,classId,subCkassId,actualUseCodeId);
	if(revisionYearId != '' && barangayId != '' && localityId != '' && classId != '' && subCkassId != '' && actualUseCodeId != ''){
		$('.loadingGIF').show();
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

	    	if(html.status == 'success'){
	    	$('#addlandappraisalmodal').find(".rls_percent").val(html.data.rls_percent);
	    	$('#addlandappraisalmodal').find(".rls_code").val(html.data.rls_code);
	    	$('#addlandappraisalmodal').find(".lav_strip_unit_value").val(html.data.lav_strip_unit_value);
	    	$('#addlandappraisalmodal').find(".lav_unit_value").val(html.data.lav_unit_value);
	    	$('#addlandappraisalmodal').find(".lav_unit_measure option[value='"+html.data.lav_unit_measure+"']").prop('selected', true);
	    	}else{
	    		$('#addlandappraisalmodal').find(".rls_percent").val('0.00');
	    	$('#addlandappraisalmodal').find(".rls_code").val('NO');
	    	$('#addlandappraisalmodal').find(".lav_strip_unit_value").val('0.00');
	    	$('#addlandappraisalmodal').find(".lav_unit_value").val('0.00');
	    	$('#addlandappraisalmodal').find(".lav_unit_measure").val('Nothing');
	    	}
	    	calculateLandMarketBaseValue();
	    	
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
/*$('.deleteLandAppraisal').live('change', function(){
   alert('OK!');
});*/

function loadAssessementSumary(id) {

	var classCode = $('.asse_summary_pc_class_code').val();
	var propertyRevisionYear  = $('.rvy_revision_year_id').val();
	var barangay              = $('select[name=brgy_code_id]').val(); 
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
	    		$('#assessementSummaryData').html(html.view);
	    		if(html.assessementLevel == false){
	    			Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'No Assessment Level found, Please try again!',
                      showConfirmButton: true,
                      timer: false
                    });
	    		$('.asse_summary_pc_class_code').val('');
	    		}
	    		
	    	}
	    	
	    }
	});
}


function loadPlantsTreesAdjustmentFactor(id = ''){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    "_token": $("#_csrf_token").val()
	}; 
	$.ajax({
	    type: "POST",
	    url: DIR+'rptmachinery/getassessmentsummarylisting',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('#AssessmentSummarylisting').html(html);
	    	//$('#myModal'+mid).find(".pc_class_code_description").val(html.pc_class_description);
			/*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);*/
	    }
	});
}

function calculatePlantTreeMarketValue() {
	var plantTreeUnitValue = $('#treesplantsadjustmentfactormodal').find('input[name=rpta_unit_value]').val();
    var tatalAreaPlated    = $('#treesplantsadjustmentfactormodal').find('input[name=rpta_total_area_planted]').val();
    var totalMarketValue = tatalAreaPlated*plantTreeUnitValue;
    $('#treesplantsadjustmentfactormodal').find('input[name=rpta_market_value]').val(parseFloat(totalMarketValue).toFixed(2));
}
function calculateLandAppAdjustmentPercentValue() {
	alert('function');
	var baseMarketVal = $('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_base_market_value]').val();
	var adjFactorA    = $('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_adjustment_factor_a]').val();
	var adjFactorB    = $('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_adjustment_factor_b]').val();
	var adjFactorC    = $('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_adjustment_factor_c]').val();
    baseMarketVal     = parseFloat(baseMarketVal);
	adjFactorA        = parseFloat(adjFactorA);
	adjFactorB        = parseFloat(adjFactorB);
	adjFactorC        = parseFloat(adjFactorC);
	var TotalPercent  = (adjFactorA)+(adjFactorB)+(adjFactorC);
	console.log(baseMarketVal,adjFactorA,adjFactorB,adjFactorC,TotalPercent);
	var adjTotalPer   = 100-TotalPercent;
	var valAdjusted   = baseMarketVal*TotalPercent/100;
	var adjMarketVal  = baseMarketVal-valAdjusted;
	$('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_adjustment_percent]').val(parseFloat(adjTotalPer).toFixed(2)+'%');
	$('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_adjustment_value]').val(parseFloat(valAdjusted).toFixed(2));
	$('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_adjusted_market_value]').val(parseFloat(adjMarketVal).toFixed(2));
	$('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_adjustment_value_for_display]').val(parseFloat(valAdjusted).toFixed(2));
	$('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_adjusted_market_value_for_display]').val(parseFloat(adjMarketVal).toFixed(2));
	$('#storelandAppraisalFactors').submit();
}

function displayLandAppraisalAdjustmnetFactorForm(id, sessionId, propertyId) {
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionid:sessionId,
	    property_id:propertyId
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptproperty/landappraisalfactors',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('#landAppraisalAdjustmentFactorsform').html(html);
	    	//$('#myModal'+mid).find(".pc_class_code_description").val(html.pc_class_description);
			/*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);*/
	    }
	});
}
$(document).off('submit','#storelandAppraisalFactors').on('submit','#storelandAppraisalFactors',function(e){
		e.preventDefault();
		var url    =  $(this).attr('action');
		var method = $(this).attr('method');
		var data   = $('#storelandAppraisalFactors').serialize();
		$.ajax({
	    type: "post",
	    url: url,
	    data: data,
	    dataType: "json",
	    success: function(html){ 
	    	if(html.status == 'success'){
	    		//$('#landAppraisalAdjustmentFactorsmodal').modal('hide');
	    	}
	    }
	});

	})

function loadPropertyAnnotations(id = '') {
    	showLoader();
    	var id = $('#saveAnnotationPropertyStatus').find('input[name=property_id]').val();
		$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptmachinery/loadpropertyannotations',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('.loadingGIF').hide();
	    	$('#listAnnotationshere').html(html);

	    },error:function(){
	    	hideLoader();
	    }
	});
	}
function deleteAnnotation(id, sessionId) {
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    "_token": $("#_csrf_token").val()
	}; 
	$.ajax({
	    type: "post",
	    url: DIR+'rptmachinery/deleteannotaion',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	loadPropertyAnnotations();
	    },error:function(){
	    	hideLoader();
	    }
	});
}	

function saveAnnotationSpecialPropertyStatus(id) {
	
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptmachinery/anootationspeicalpropertystatus',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#annotationSpecialPropertyStatusForm').html(html);
	    	loadPropertyAnnotations();
	    	$('#saveAnnotationPropertyStatus').find(".rpss_beneficial_user_code").select3({dropdownAutoWidth:false,dropdownParent:$('#saveAnnotationPropertyStatus').find(".rpss_beneficial_user_code").parent()});
	    	$('#saveAnnotationPropertyStatus').find(".rpss_mortgage_to_code").select3({dropdownAutoWidth:false,dropdownParent:$('#saveAnnotationPropertyStatus').find(".rpss_mortgage_to_code").parent()});
	    	//$('#saveAnnotationPropertyStatus').find(".rpss_mortgage_exec_by").select3({});
	    	$('#saveAnnotationPropertyStatus').find(".rpa_annotation_by_code").select3({dropdownAutoWidth:false,dropdownParent:$('#saveAnnotationPropertyStatus').find(".rpa_annotation_by_code").parent()});
				if($('#saveAnnotationPropertyStatus').find('.rpss_is_mortgaged').prop("checked")){
					$(".mortgage_details_dection").css("pointer-events","auto");
				}else{
					$(".mortgage_details_dection").css("pointer-events","none");
				}
			setTimeout(function(){ ; }, 2000);	
				
	    	
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function saveSwornStatement(id,landPropId) {
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    landprpid:landPropId
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptmachinery/swornstatment',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#swornStatementForm').html(html);
	    	/*//loadPropertyAnnotations();
	    	$('#saveAnnotationPropertyStatus').find(".rpss_beneficial_user_code").select3({});
	    	$('#saveAnnotationPropertyStatus').find(".rpss_mortgage_to_code").select3({});
	    	$('#saveAnnotationPropertyStatus').find(".rpss_mortgage_exec_by").select3({});
	    	$('#saveAnnotationPropertyStatus').find(".rpa_annotation_by_code").select3({});
				if($('#saveAnnotationPropertyStatus').find('.rpss_is_mortgaged').prop("checked")){
					$(".mortgage_details_dection").css("pointer-events","auto");
				}else{
					$(".mortgage_details_dection").css("pointer-events","none");
				}
			setTimeout(function(){ ; }, 2000);	*/
				
	    	
	    },error:function(){
	    	hideLoader();
	    }
	});
}
function getTaxDe(id,brgy_code_id,rvy_revision_year_id){
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
           $("#B").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function getTaxDeAll(brgy_code_id,rvy_revision_year_id){
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
           $("#B").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function getTaxDeLand(id,brgy_code_id,rvy_revision_year_id){
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
           $("#L").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function getTaxDeAllLand(brgy_code_id,rvy_revision_year_id){
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
           $("#L").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}

function activeInactiveCancelledPart() {
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear()+"-"+(month)+"-"+(day) ;

	if($('#commonModal').find('#saveApprovelFormData').find('input:radio[name=pk_is_active]:checked').val() == '0'){
        $('#commonModal').find('#saveApprovelFormData').find('input:checkbox[name=rp_app_cancel_is_direct]').prop('disabled',false);
        $('#commonModal').find('#saveApprovelFormData').find('select[name=rp_app_cancel_type]').prop('disabled',false);
        $('#commonModal').find('#saveApprovelFormData').find('input[name=rp_app_cancel_date]').prop('disabled',false);
        $('#commonModal').find('#saveApprovelFormData').find('input[name=rp_app_cancel_date]').val(today);
        $('#commonModal').find('#saveApprovelFormData').find('#rp_app_cancel_by_td_id').prop('disabled',false);
        $('#commonModal').find('#saveApprovelFormData').find('textarea[name=rp_app_cancel_remarks]').prop('disabled',false);
    }else{
    	$('#commonModal').find('#saveApprovelFormData').find('input[name=rp_app_cancel_is_direct]').prop('checked',false);
        $('#commonModal').find('#saveApprovelFormData').find('select[name=rp_app_cancel_type]').val('');
        $('#commonModal').find('#saveApprovelFormData').find('input[name=rp_app_cancel_date]').val('');
        $('#commonModal').find('#saveApprovelFormData').find('#rp_app_cancel_by_td_id').val('');
        $('#commonModal').find('#saveApprovelFormData').find('textarea[name=rp_app_cancel_remarks]').val('');

    	$('#commonModal').find('#saveApprovelFormData').find('input:checkbox[name=rp_app_cancel_is_direct]').prop('disabled',true);
        $('#commonModal').find('#saveApprovelFormData').find('select[name=rp_app_cancel_type]').prop('disabled',true);
        $('#commonModal').find('#saveApprovelFormData').find('input[name=rp_app_cancel_date]').prop('disabled',true);
        $('#commonModal').find('#saveApprovelFormData').find('#rp_app_cancel_by_td_id').prop('disabled',true);
        $('#commonModal').find('#saveApprovelFormData').find('textarea[name=rp_app_cancel_remarks]').prop('disabled',true);
    }
} 

function initiateTdRemoteSelectListLand(rpoCode) {
 	$('#commonModal').find("#L").select3({
    placeholder: 'Select Land Tax Declaration No.',
    allowClear: true,
    dropdownParent: $('#commonModal').find("#L").parent(),
    ajax: {
        url: DIR+'rptproperty/gettdsforajaxselectlist',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
            	brgy_code_id:$('#commonModal').find("#brgy_code_id").val(),
			    rvy_revision_year_id:$('#commonModal').find("#rvy_revision_year_id").val(),
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

 function initiateTdRemoteSelectListBuilding(rpoCode) {
 	$('#commonModal').find("#B").select3({
    placeholder: 'Select Building Tax Declaration No.',
    allowClear: true,
    dropdownParent: $('#commonModal').find("#B").parent(),
    ajax: {
        url: DIR+'rptproperty/gettdsforajaxselectlist',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
            	brgy_code_id:$('#commonModal').find("#brgy_code_id").val(),
			    rvy_revision_year_id:$('#commonModal').find("#rvy_revision_year_id").val(),
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
    var property_administrator_id_id=$("#property_administrator_id").val();
    //alert(property_administrator_id_id);
   /* $("#profile").change(function(){
        var clientid = $(this).val();
        if(property_administrator_id_id === ''){
        	if (!isPropertyAdminIdSelected) {
            getAdmistrative(clientid);
            getAdminprofiledata(clientid);
        }
        }
        
    });*/
    
    function getAdmistrative(clientid){
        $.ajax({
            url: DIR + 'getAdmistrativeDetails',
            type: 'POST',
            data: {
                "clientid": clientid,
                "_token": $("#_csrf_token").val(),
            },
            success: function(html){
                if (html !== '') {
                    $("#property_administrator_id").html(html);

                    if (!isPropertyAdminIdSelected) {
                        isPropertyAdminIdSelected = true;
                    } else {
                        $("#property_administrator_id").prop("disabled", true);
                    }
                }
            }
        });
    }
});








