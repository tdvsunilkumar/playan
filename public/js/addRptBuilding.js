$(document).ready(function(){
	var yearpickerInput = $('#commonModal').find('input[name="rp_building_completed_year"]').val();
	setTimeout(function(){ 
		    	setManualPermit();
			        }, 500);
	
	$('#commonModal').find('.yearpicker').yearpicker({dropdownAutoWidth: false, dropdownParent: $('#commonModal').find("#year")});
    //$('#commonModal').find("#profile_id").select3({dropdownAutoWidth : false,dropdownParent: $('#commonModal').find("#owner")});
   // $('#commonModal').find("select[name=permit_id]").select3({dropdownAutoWidth : false,dropdownParent: $('#commonModal').find("select[name=permit_id]").parent()});
    //$('#commonModal').find("#rp_code_lref").select3({dropdownAutoWidth : false,dropdownParent: $('#commonModal').find("#rp_code_lref_parent")});
    autoFillMainForm($('#commonModal').find('input[name=id]').val());
	if($('#commonModal').find("#profile_id").val() > 0){ 
		getprofiledata($('#commonModal').find("#profile_id").val()); 
	}

	$('#annotationSpecialPropertyStatusModal').off('click','.property_type').on('click','.property_type', function(){
		$('#annotationSpecialPropertyStatusModal').find('.property_type').not(this).prop('checked', false);
	})

	$('#annotationSpecialPropertyStatusModal').off('click','.property_type_new').on('click','.property_type_new', function(){
		$('#annotationSpecialPropertyStatusModal').find('.property_type_new').not(this).prop('checked', false);
	})

	$('#commonModal').find("#profile_id").select3({
    placeholder: 'Select Property Owner',
    allowClear: true,
    dropdownParent: $('#commonModal').find("#profile").parent(),
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
	$('#commonModal').find("select[name=permit_id]").select3({
    placeholder: 'Select Building Permit',
    allowClear: true,
    dropdownParent: $('#commonModal').find("select[name=permit_id]").parent(),
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
	initiateTdRemoteSelectList(0);
    $('#commonModal').off('click','#newAddedAssessementSummary tbody tr').on('click','#newAddedAssessementSummary tbody tr',function(){
    	var text = $(this).find('.property_kind').text();
    	//alert(text);
    	if(text != ''){
    		if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    $('#commonModal').find('#newAddedAssessementSummary tbody tr').removeClass('selected');
                    $(this).addClass('selected');
                }
    	}
    });
    if($('#commonModal').find("#id").val() >0){
		getgeolocations();
	}

	$('#commonModal').off('change',"#rp_building_gf_area").on('change',"#rp_building_gf_area",function(){
		if($('#commonModal').find("#rp_building_total_area").val() =="" || $('#commonModal').find("#rp_building_total_area").val() == 0){
			$('#commonModal').find("#rp_building_total_area").val($('#commonModal').find("#rp_building_gf_area").val());
		}
	})

	$('#commonModal').off('change',"#rp_occupied_month").on('change',"#rp_occupied_month",function(){
		var occudate = $('#commonModal').find("#rp_occupied_month").val();
		occudate = occudate +'-01';
		var dob = new Date(occudate);
		var today = new Date();
		var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
		age =Math.abs(age);
		$('#commonModal').find('#rp_building_age').val(age);
	})
	$('#commonModal').off('change',"#rp_building_completed_year").on('change',"#rp_building_completed_year",function(){
			$('#commonModal').find('#rp_building_completed_percent').val('100');
	})

    $('#commonModal').off('change',"#profile_id").on('change',"#profile_id",function(){
    	var id=$(this).val();
    	var propId = $('#commonModal').find('#id').val();
    	if($('#commonModal').find('#old_property_id').val() == '' && propId == 0){
    	    initiateTdRemoteSelectList(id);
			$('#commonModal').find('.myCheckbox').prop('checked', true);
		}
			if(id){ getprofiledata(id); }
	});
	


	$('#commonModal').on('click','.myCheckbox',function() {
		if($(this).is(":checked")) {
		    var id=$('#commonModal').find("#profile_id").val();
			initiateTdRemoteSelectList(id);
	      //getTaxDe(id,brgy_code_id,rvy_revision_year_id);
	    } else {
	      initiateTdRemoteSelectList(0);
	    }
	}); 

	$('#commonModal').off('change','select[name=permit_id]').on('change','select[name=permit_id]',function(){
		var selected = $(this).find(':selected').text();
		//$('#commonModal').find('input[name=rp_bulding_permit_no]').val(selected);
	});

	$('#commonModal').on('click','.manual_entry',function() {
		setManualPermit();
	}); 



	/*$('#commonModal').off('click','input[name=pk_is_active]').on('click','input[name=pk_is_active]',function(){
		var ucCode = $('#commonModal').find('input[name=update_code]').val();
		if(ucCode == 'DC'){
			activeInactiveCancelledPart();
		}
	});*/
	
    $('#commonModal').off('change',"#rp_code_lref").on('change',"#rp_code_lref",function(){
		var id=$(this).val();
		taxDeclarationId(id);
		var propId = $('#commonModal').find('#id').val();
		var ucCode = $('input[name=update_code]').val();
		if(propId == 0 && ucCode == 'DC'){
			createPinSuffix(id, $('#commonModal').find('#id').val());
		}
		
	});

    if($('#commonModal').find("#rp_code_lref").val()>0){
		taxDeclarationId($('#commonModal').find("#rp_code_lref").val());
    }

    $('#commonModal').off('click','.displayAdditionalItensForDepreciation').on('click','.displayAdditionalItensForDepreciation',function(){
    	var modal = $(this).data('target');
    	$(modal).modal('show');
    });

    $('#commonModal').find('#floorValueDepreciationModal').on('hidden.bs.modal', function () {
       loadAssessementSumary($('#commonModal').find('input[name=id]').val());
    });

    $('#commonModal').find('#floorValueModal').on('hidden.bs.modal', function () {
       autoFillMainForm($('#commonModal').find('input[name=id]').val());
       loadAssessementSumary($('#commonModal').find('input[name=id]').val());
    });

	$('#commonModal').on('click','.cancelMoreAdditionalItemsForFloorValue',function(){
		$(this).closest(".removeactivitydata").remove();
	});
	
	$('#commonModal').off('click',"#addMoreAdditionalItemsForFloorValue").on('click',"#addMoreAdditionalItemsForFloorValue",function(){
		$('html, body').stop().animate({
      scrollTop: $('#commonModal').find("#addMoreAdditionalItemsForFloorValue").offset().top
    }, 600);
		addmoreAdditionalItems();
	});

	$("#uploadAttachmentbtn").click(function(){
 		uploadAttachment();
 	});

 	$(".btn_delete_documents").click(function(){
 		deleteDocuments($(this));
 	})

 	$(".btn_cancel_locations").click(function(){
     var id = $(this).val();
     var thisval = $(this);
     if(id >0){
      deletelocations(id,thisval);
     }
	});

	 $("#btn_addmore_geolocation").click(function(){
      $('html, body').stop().animate({
        scrollTop: $("#btn_addmore_geolocation").offset().top
      }, 600);
      addmoreLocations();
    });

	$('.numeric').numeric();
	$(".btn_cancel_activity").click(function(){
		 $(this).closest(".removeactivitydata").remove();
	});

	$('#commonModal').find(".profile_id").select3({dropdownAutoWidth : false,dropdownParent : '#commonModal'});
	//$('#commonModal').find(".property_administrator_id").select3({dropdownAutoWidth : false,dropdownParent : '#commonModal'});
	$('#commonModal').find(".rvy_revision_year_id").select3({dropdownAutoWidth : false,dropdownParent : '#commonModal'});
	$('#commonModal').find(".bk_building_kind_code").select3({dropdownAutoWidth : false,dropdownParent : $('#commonModal').find(".bk_building_kind_code").parent()});
	$('#commonModal').find(".pc_class_code").select3({dropdownAutoWidth : false,dropdownParent : $('#commonModal').find(".pc_class_code").parent()});
    $('#commonModal').find(".brgy_code_id").select3({dropdownAutoWidth : false,dropdownParent : '#commonModal'});
    $('#commonModal').find(".bei_extra_item_code").select3({dropdownAutoWidth : false,dropdownParent : '#accordionFlushExample4'});
    
    $(document).on('change','.bei_extra_item_code',function(){
    	var text = $(this).find(':selected').text();
    	const myArray = text.split("-");
    	if (typeof myArray[1] !== 'undefined') {
         $(this).closest('.removeactivitydata').find('.bei_extra_item_desc').val(myArray[1]);
         }
    })

	$('#commonModal').on('click','.refeshbuttonselect2',function(){
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
				$('#commonModal').find('.profile_id').html($data);
				hideLoader();
			},
			error: function(err){
				console.log(err);
				hideLoader();
			}
		});
    })
	$('#commonModal').on('click','.refeshbuttonselect',function(){
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
				$('#commonModal').find('.property_administrator_id').html($data);
				hideLoader();
			},
			error: function(err){
				console.log(err);
				hideLoader();
			}
		});
    })


    $('#commonModal').on('keyup', 'input[name="rpbfv_floor_area"]',function(){
    	calculateDataForOtherFields();
    });
    $('#commonModal').on('keyup', '.depreciation_rate_depreciationmodal',function(){
    	var dep = $(this).val();
    	if(isNaN(dep) || dep == ''){
    		dep = 0;
    	}
    	dep = parseFloat(dep);
    	var totalMarketValue = parseFloat($('#commonModal').find('#floorValueDepreciationModal').find('input[name=total_market_value_of_floor]').val());
    	
    	var accumulatedVal   = (dep*totalMarketValue)/100;
    	var totalDepreciatedValue = totalMarketValue-accumulatedVal;
    	$('#commonModal').find('#floorValueDepreciationModal').find('.accumaultatedValue').val(numberWithCommas(parseFloat(accumulatedVal).toFixed(2)));
    	$('#commonModal').find('#floorValueDepreciationModal').find('.rpbfv_total_floor_market_value_temp').val(numberWithCommas(parseFloat(totalDepreciatedValue).toFixed(2)));
    	$('#commonModal').find('input[name=rp_depreciation_rate]').val(dep);

    });
    $('#commonModal').find('input[name="rpbfv_floor_area"]').unbind('keyup');

     $('#commonModal').on('keyup', 'input[name="rpbfv_floor_additional_value"]',function(){
    	calculateDataForOtherFields();
    });
     $('#commonModal').find('input[name="rpbfv_floor_additional_value"]').unbind('keyup');

     $('#commonModal').on('keyup', 'input[name="rpbfv_floor_adjustment_value"]',function(){
    	calculateDataForOtherFields();
    });
     $('#commonModal').find('input[name="rpbfv_floor_adjustment_value"]').unbind('keyup');

     $('#commonModal').on('click','#saveAnnotationData',function(){
     	saveAnnotationDataToDB();
     });
    
    /*$("#constructedmonth" ).datepicker({dateFormat: 'MM yy'});
    $("#rp_occupied_month" ).datepicker({dateFormat: 'MM yy'});*/
	/* Land Appraisal Value Adjustment Factors*/

	$('#commonModal').find("#loadLandApprisalForm").unbind("click");
	$('#commonModal').on('click',"#loadLandApprisalForm",function(){
		$('#commonModal').find('#addlandappraisalmodal').modal('show');
		loadAddLandAppraisalForm(id = '');
	});
	$('#commonModal').on('click','.closeLandAppraisalModal',function(){
			$('#commonModal').find('#addlandappraisalmodal').modal('hide');
		});

	

	$('#commonModal').off('submit','#storePropertyOwnerForm').on('submit','#storePropertyOwnerForm',function(e){
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
	$("#addPreviousOwnerForBuilding").unbind("click");
	$(document).off('click','#addPreviousOwnerForBuilding').on('click','#addPreviousOwnerForBuilding',function(){
		var propId = $(this).data('propertyid');
		var url = DIR+'rptbuilding/loadpreviousowner'+'?oldpropertyid='+propId;
        var title1 = 'Manage Previous Owner';
        var title2 = 'Manage Previous Owner';
        var title = (title1 != undefined) ? title1 : title2;
        var size = 'xll';
        var modalId = 'addPreviousOwnerForBuildingModal';
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

	$("#savelocations").unbind("click");
	$(document).off('click','#savelocations').on('click','#savelocations',function(e){
		e.preventDefault();
		//alert("hi"); 
		var url = $(this).data('url');
		var method = "POST";
		var data   = $('#geolocationDetails').find('select, textarea, input').serialize(); 
        		showLoader();
				$.ajax({
			    type: method,
			    url: url,
			    data: data,
			    dataType: "json",
			    success: function(html){ 
			    	hideLoader();
			    	if(html.status == 'success'){
			    		getgeolocations();
			    		Swal.fire({
		                      position: 'center',
		                      icon: 'success',
		                      title: "Locations Saved Successfully",
		                      showConfirmButton: true,
		                      timer: false
		                    })
			    		var ordererror =0; var arr = [];

			    	}if(html.ESTATUS){
                    if(html.field_name.indexOf('.') != -1){
                        var checkedNew = html.field_name.split('.').join("");
                         $("#"+checkedNew).html(html.error);
                    }
                    $("#err_"+html.field_name).html(html.error);
                    $("."+html.field_name).focus();
                }
			    },error:function(){
			    	hideLoader();
			    }
			  });
  });

  function getgeolocations(){
  	      $.ajax({
			     url :DIR+'rptproperty/getLocationsbypropid', // json datasource
		         type: "POST", 
		         data: {
		         "property_code": $('input[name="rp_property_code"]').val(),
		         "_token": $("#_csrf_token").val(),
		         },
	         
			    dataType: "json",
			    success: function(html){ 
			    	hideLoader();
			    	if(html.status == 'success'){
			    		$("#geolocationDetails").html(html.dynadata);
			    		$(".btn_cancel_locations").click(function(){
					     var id = $(this).val();
					     var thisval = $(this);
					     if(id >0){
					      deletelocations(id,thisval);
					     }
						});
			    	}if(html.status == 'error'){
			    		
			    	}
			    },error:function(){
			    	hideLoader();
			    }
			  });
  }

  function deletelocations(id,thisval){
	  var rid = id; 
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
	         url :DIR+'rptproperty/deletelocationlink', // json datasource
	         type: "POST", 
	         data: {
	         "id": id,
	         "_token": $("#_csrf_token").val(),
	         },
	         dataType: "html",
	         success: function(html){
	          hideLoader();
	          thisval.closest(".removedocumentsdata").remove();
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

	function saveAnnotationDataToDB() {
		showLoader();
		var url      = DIR+'rptbuilding/anootationspeicalpropertystatus';
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
    });
	$('#approvalformModal').on('click','.editPreviousOwnerTd',function(){
		var propId = $(this).data('id');
		var url = DIR+'rptbuilding/loadpreviousowner'+'?id='+propId;
        var title1 = 'Edit Previous Owner';
        var title2 = 'Edit Previous Owner';
        var title = (title1 != undefined) ? title1 : title2;
        var size = 'xll';
        var modalId = 'addPreviousOwnerForBuildingModal';
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

	$('#approvalformModal').on('click','.deletePreviousOwnerTd',function(){
		var id = $(this).data('id');
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
    var propertyId = $('#commonModal').find('input[name=id]').val();
	//loadPlantsTreesAdjustmentFactor(propertyId);
	//loadLandAppraisal(propertyId);
	loadAssessementSumary(propertyId);
	commonFunction();
	   $("#submit").on("click",function(){
    if (($("input[name*='Completed']:checked").length)<=0) {
       // alert("You must check at least 1 box");
    }
    return true;
  });
	// $("#btn_addmore_nature").click(function(){
	// 	$('html, body').stop().animate({
 //      scrollTop: $("#btn_addmore_nature").offset().top
 //    }, 600);
	// 	addmoreNature();
	// });
	// $("#btn_addmore_requirement").click(function(){
	// 	$('html, body').stop().animate({
 //      scrollTop: $("#btn_addmore_requirement").offset().top
 //    }, 600);
	// 	addmoreRequirements();

	// });
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
	/*$('#commonModal').off('change',"#profile_id").on('change',"#profile_id",function(){

		var id=$(this).val();
		if(id){ getprofiledata(id); }

	})*/
	$('#commonModal').off('change',"#property_administrator_id").on('change',"#property_administrator_id",function(){
		var id=$(this).val();
		if(id){ getAdminprofiledata(id); 
			  getTradedopdown(id);}

	})
	$('#commonModal').off('change',"#rvy_revision_year_id").on('change',"#rvy_revision_year_id",function(){
		var id=$(this).val();
		if(id){ 
			getRvyRevisionYearDetails(id); 
			}

	})
	$('#commonModal').off('change',"#brgy_code_id").on('change',"#brgy_code_id",function(){
		var id=$(this).val(); 
		var updateCode = $('#commonModal').find('input[name=update_code]').val();
		// if(id != '' && updateCode == 'DD'){ 
		// 	getbarangayaDetails(id); 
		// }
		getbarangayaDetails(id); 

	})
	// if($('#commonModal').find("#profile").val()>0){
	// 	getprofiledata($('#commonModal').find("#profile").val());
	// }
	if($('#commonModal').find('input[name=id]').val() == 0){
		getbarangayaDetails($('#commonModal').find('#brgy_code_id').val());
	}
	if($('#commonModal').find("#property_administrator_id").val()>0){
		getAdminprofiledata($('#commonModal').find("#property_administrator_id").val());
	}

	$('#commonModal').off('change',"#barangay_id").on('change',"#barangay_id",function(){
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
	    	$('.loadingGIF').hide();
	    	$('#commonModal').find('input[name=loc_group_brgy_no]').val(html.brgy_name);
	    	//$('textarea[name=rp_location_number_n_street]').val(html.brgy_name+', '+html.mun_desc);
	    	$('#commonModal').find('input[name=brgy_code]').val(html.brgy_code);
	    	$('#commonModal').find('input[name=dist_code]').val(html.dist_code);
	    	$('#commonModal').find('input[name=dist_code_name]').val(html.dist_code+'-'+html.dist_name);
	    	$('#commonModal').find('input[name=brgy_code_and_desc]').val(html.brgy_code+'-'+html.brgy_name);
	    	$('#commonModal').find('input[name=loc_local_code_name]').val(html.mun_desc);
	    	$('#commonModal').find('input[name=loc_local_code]').val(html.loc_local_code_id);
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
	    		$('#commonModal').find('input[name="rp_administrator_code"]').val(arr.id);
	    		$('#commonModal').find('input[name="rp_administrator_code_address"]').val(arr.standard_address);
	    	
	    }
	});
}

$('#commonModal').off('change',".searchlandDetails").on('change',".searchlandDetails",function(){
	var brgy = $('#commonModal').find('#brgy_code_id').val();
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
	    	$('#commonModal').find('.validate-err').html('');
	    	hideLoader();
	    	if(html.status == 'validation_error'){
	    		$('#commonModal').find('.validate-err').html('');
                $('#commonModal').find('#err_'+html.field_name).html(html.error);
                $('#commonModal').find('.'+html.field_name).focus();
	    		$('#propertyTaxDeclarationForm').find('input[name=rp_code_lref]').val('');
	    		$('#propertyTaxDeclarationForm').find('input[name=rp_suffix_lref]').val('');
	    		$('#propertyTaxDeclarationForm').find('input[name=rpo_code_lref]').val('');
	    		$('#propertyTaxDeclarationForm').find('input[name=rp_oct_tct_cloa_no_lref]').val('');
	    		$('#propertyTaxDeclarationForm').find('input[name=land_owner]').val('');
	    		$('#propertyTaxDeclarationForm').find('input[name=land_location]').val('');
	    		$('#propertyTaxDeclarationForm').find('input[name=rp_cadastral_lot_no_lref]').val('');
	    		$('#propertyTaxDeclarationForm').find('input[name=rp_total_land_area]').val('');
	    	}if(html.status == 'success'){
	    		$('#commonModal').find('.validate-err').html('');
	    		$('#propertyTaxDeclarationForm').find('input[name=rp_code_lref]').val(html.data.rp_td_no_lref);
	    		$('#propertyTaxDeclarationForm').find('input[name=rp_suffix_lref]').val(html.data.rp_suffix_lref);
	    		$('#propertyTaxDeclarationForm').find('input[name=rpo_code_lref]').val(html.data.rpo_code_lref);
	    		$('#propertyTaxDeclarationForm').find('input[name=rp_oct_tct_cloa_no_lref]').val(html.data.rp_oct_tct_cloa_no_lref);
	    		$('#propertyTaxDeclarationForm').find('input[name=land_owner]').val(html.data.land_owner);
	    		$('#propertyTaxDeclarationForm').find('input[name=land_location]').val(html.data.land_location);
	    		$('#propertyTaxDeclarationForm').find('input[name=rp_cadastral_lot_no_lref]').val(html.data.rp_cadastral_lot_no_lref);
	    		$('#propertyTaxDeclarationForm').find('input[name=rp_total_land_area]').val(parseFloat(html.data.rp_total_land_area).toFixed(3));
	    	}
	    	
	    },error:function(){
	    	$('#propertyTaxDeclarationForm').find('input[name=rp_code_lref]').val('');
	    		$('#propertyTaxDeclarationForm').find('input[name=rp_suffix_lref]').val('');
	    		$('#propertyTaxDeclarationForm').find('input[name=rpo_code_lref]').val('');
	    		$('#propertyTaxDeclarationForm').find('input[name=rp_oct_tct_cloa_no_lref]').val('');
	    		$('#propertyTaxDeclarationForm').find('input[name=land_owner]').val('');
	    		$('#propertyTaxDeclarationForm').find('input[name=land_location]').val('');
	    		$('#propertyTaxDeclarationForm').find('input[name=rp_cadastral_lot_no_lref]').val('');
	    		$('#propertyTaxDeclarationForm').find('input[name=rp_total_land_area]').val('');
	    	$('#commonModal').find('.validate-err').html('');
	    	hideLoader();
	    }
	});
});


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
	    	$('#commonModal').find('input[name="rvy_revision_year"]').val(html.rvy_revision_year);
	    	$('#commonModal').find('input[name="rvy_revision_code"]').val(html.rvy_revision_code);
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
	    		$('#commonModal').find('input[name="property_owner_address"]').val(arr.standard_address);
	    		$('#commonModal').find('input[name="rpo_code"]').val(arr.id);
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
	    url: DIR+'rptbuilding/deleteannotaion',
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

function deleteLandAppraisal(id, sessionId) {
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
	    dataType: "json",
	    success: function(html){ 
	    	if(html.status == 'verifypsw'){
                $('#verifyPsw').modal('show');
                $('#verifyPsw').find('.validate-err').html('');
                $('#verifyPsw').find('input[name=verify_psw]').val('');
                
            }if(html.status == 'success'){
                loadFloorValues($('#commonModal').find('input[name=id]').val());
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

	$('#commonModal').off('click',".deleteLandAppraisal").on('click',".deleteLandAppraisal",function(){
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

	$('#commonModal').find("#loadFloorValueForm").unbind("click");
	$('#commonModal').off('click',"#loadFloorValueForm").on('click',"#loadFloorValueForm",function(){
		$('#commonModal').find('#addFloorValueFormmodal').modal('show');
		loadAddFloorValueForm(id = '');
		
		$('#commonModal').on('click','.closeLandAppraisalModal',function(){
			$('#commonModal').find('#addFloorValueFormmodal').modal('hide');
		});
	}); 

	$('#commonModal').find("#loadStructuralCharacter").unbind("click");
	$('#commonModal').off('click',"#loadStructuralCharacter").on('click',"#loadStructuralCharacter",function(){
		$('#commonModal').find('#addStructuralCharacterModal').modal('show');
		loadBuildingStructureForm(id = '');
		
		$('#commonModal').on('click','.addStructuralCharacterModal',function(){
			$('#commonModal').find('#addStructuralCharacterModal').modal('hide');
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

	$("#displaySwornStatementModal").unbind("click");
	$(document).on('click','#displaySwornStatementModal',function(){
		var propertyId = $(this).data('propertyid');
		var landPropId = $('input[name=rp_td_no_lref]').val();
		$('#swornStatementModal').modal('show');
		saveSwornStatement(propertyId,landPropId);
		$(document).on('click','.closeSwornStatement',function(){
			$('#swornStatementModal').modal('hide');
		});

        
	});

	$('#commonModal').find("#displayFloorValueModal").unbind("click");
	$('#commonModal').on('click','#displayFloorValueModal',function(){
		if($('#commonModal').find('select[name=bk_building_kind_code]').val() == ''){
			$('#commonModal').find('#err_pc_class_code').html('');
			$('#commonModal').find('#err_bk_building_kind_code').html('');
			$('#commonModal').find('#err_bk_building_kind_code').html('Required Field');
		}else if($('#commonModal').find('select[name=pc_class_code]').val() == ''){
			$('#commonModal').find('#err_pc_class_code').html('');
			$('#commonModal').find('#err_bk_building_kind_code').html('');
			$('#commonModal').find('#err_pc_class_code').html('Required Field');
		}else{
			$('#commonModal').find('#err_pc_class_code').html('');
			$('#commonModal').find('#err_bk_building_kind_code').html('');
			var propertyId = $(this).data('propertyid');
		    $('#commonModal').find('#floorValueModal').modal('show');
		    saveFloorValue(propertyId);
		}
		$('#commonModal').on('click','.closeSwornStatement',function(){
			$('#commonModal').find('#floorValueModal').modal('hide');
		});

        
	});

	$('#commonModal').find("#displayFloorValueDepreciationModal").unbind("click");
	$('#commonModal').on('click','#displayFloorValueDepreciationModal',function(){
		var propertyId = $(this).data('propertyid');
		var actualUse  = $('#commonModal').find('#newAddedAssessementSummary tbody').find('tr.selected').data('id');
		var depreciation = $('#commonModal').find('input[name=rp_depreciation_rate]').val();
		//console.log(actualUse.data('id'));
		if(actualUse === undefined){
			$('#commonModal').find('#selectAtLeastOneSummary').html('');
			$('#commonModal').find('#selectAtLeastOneSummary').html('Please select at least one assessement summary to continue');
		}else{
			$('#commonModal').find('#selectAtLeastOneSummary').html('');
			$('#commonModal').find('#floorValueDepreciationModal').modal('show');
		saveFloorDepreciationValue(propertyId,actualUse,depreciation);
		$('#commonModal').on('click','.closeSwornStatement',function(){
			$('#commonModal').find('#floorValueModal').modal('hide');
		});
		}
        
	});

	
	$('#commonModal').off('click','#displayAnnotationSpecialPropertyStatusModal').on('click','#displayAnnotationSpecialPropertyStatusModal',function(){
		var propertyId = $(this).data('propertyid');
		$('#annotationSpecialPropertyStatusModal').modal('show');
		saveAnnotationSpecialPropertyStatus(propertyId);
	});

	$('#commonModal').off('change','.bt_building_type_code').on('change','.bt_building_type_code',function(){
		var text = $(this).find(':selected').text();
		$('#commonModal').find('#addlandappraisalmodal').find('.bt_building_type_code_desc').val(text);
			getLandUnitValue();

		})
	$('#commonModal').off('change','.pau_actual_use_code').on('change','.pau_actual_use_code',function(){
		var text = $(this).find(':selected').text();
		$('#commonModal').find('#addlandappraisalmodal').find('.pau_actual_use_code_desc').val(text);
			calculateDataForOtherFields();
		})

	$('#commonModal').on('keyup','.rpbfv_total_floor_market_value',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
		});

	$('#commonModal').on('keyup','.rp_building_age',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
		});
	$('#commonModal').on('keyup','.rp_building_completed_percent',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
		});
	$('#commonModal').on('keyup','.rp_building_gf_area',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
		});
    $('#commonModal').on('keyup','.decimalvalue',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
		});
	$('#commonModal').on('keyup','.rpa_total_land_area',function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
       calculateLandMarketBaseValue();
    });

    $('#commonModal').on('keyup','.rpa_adjusted_market_value',function () { 
      this.value = this.value.replace(/[^0-9\.]/g,'');
    });	

    $('#commonModal').on('keyup','.lav_strip_unit_value',function () { 
       this.value = this.value.replace(/[^0-9\.]/g,'');
    });

	$('#commonModal').on('click','.deletePlantTreeAppraisal',function(){
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
	$('#commonModal').on('click','.deleteLandAppraisal',function(){
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
	//$('.rpss_beneficial_user_code').unbind('change');
	$(document).on('change','.rpss_beneficial_user_code',function(){
		var ownerName = $(this).find("option:selected").text();
		$('input[name=rpss_beneficial_user_name]').val(ownerName);

	});

	$(document).on('change','.rpss_is_mortgaged',function(){
		if(this.checked){
			$(".mortgage_details_dection").css("pointer-events","auto");
		}else{
			$(".mortgage_details_dection").css("pointer-events","none");
			$('.mortgage_details_dection').find('input').val('');
			$('.mortgage_details_dection').find('select').val('');
		}

	});

	$(document).on('click','.editPlantTreeAppraisal',function(){
            var id        = $(this).data('id');
            var sessionId = $(this).data('sessionid');
            $('#treesplantsadjustmentfactormodal').modal('show');
		    savePlantsTreesAdjustmentFactorForm(id, sessionId);
            //savePlantsTreesAdjustmentFactorForm(id);
            
		});
	$('#commonModal').off('click','.editLandAppraisal').on('click','.editLandAppraisal',function(){
            var id        = $(this).data('id');
            var sessionId = $(this).data('sessionid');
            $('#addlandappraisalmodal').modal('show');
		    loadAddLandAppraisalForm(id, sessionId);
            //savePlantsTreesAdjustmentFactorForm(id);
            
		});
	$('#commonModal').off('submit','#propertyTaxDeclarationForm').on('submit','#propertyTaxDeclarationForm',function(e){
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
	    },error:function(){
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

	$('#commonModal').off('submit','#storefloorbuildval').on('submit','#storefloorbuildval',function(e){
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
	    		$('#commonModal').find('.validate-err').html('');
                    $('#commonModal').find('#err_'+html.field_name).html(html.error);
                    $('#commonModal').find('.'+html.field_name).focus();
	    	}if(html.status == 'success'){
	    		$('#commonModal').find('.validate-err').html('');
	    		$('#commonModal').find('#addlandappraisalmodal').modal('hide');
	    		loadLandAppraisal($('#commonModal').find('input[name=id]').val());
	    		loadAssessementSumary($('#commonModal').find('input[name=id]').val());
	    	}
	    }
	     });
		});

	$('#commonModal').off('submit','#storeBuildingStructural').on('submit','#storeBuildingStructural',function(e){
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
	    		$('#commonModal').find('.validate-err').html('');
                    $('#commonModal').find('#err_'+html.field_name).html(html.error);
                    $('#commonModal').find('.'+html.field_name).focus();
	    	}if(html.status == 'verifypsw'){
                $('#verifyPsw').modal('show');
                $('#verifyPsw').find('.validate-err').html('');
                $('#verifyPsw').find('input[name=verify_psw]').val('');
                
            }if(html.status == 'success'){
	    		$('#commonModal').find('.validate-err').html('');
	    		$('#commonModal').find('#addStructuralCharacterModal').modal('hide');
	    		/*loadLandAppraisal($('input[name=id]').val());
	    		loadAssessementSumary($('input[name=id]').val());*/
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

function calculateTotalMarketValue(mid) {
	var totalMarketValue = 0;
	/*var rows= $('#new_added_land_apraisal tbody tr').length;
	$("#new_added_land_apraisal tbody tr").each(function () {
    alert($(this).find('td.rpa_base_market_value').text());
});*/
	$('#commonModal').find('#new_added_land_apraisal').find("tbody tr .rpa_base_market_value").each(function(total){
		var marketValue = $(this).text();
		totalMarketValue += parseFloat(marketValue);

	});
	var previousValue = $('#commonModal').find('#landApraisalTotalValueToDisplay').val();
	$('#commonModal').find('#landApraisalTotalValueToDisplay').val(parseFloat(totalMarketValue).toFixed(2));
}

function saveAnnotationData(argument) {
	// body...
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
	    	$('#commonModal').find('#addlandappraisalmodal').find(".pau_actual_use_code").html(html);
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

function saveFloorValue(id) {
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
	    	$('#commonModal').find('#floorValueform').html(html);
	    	var id = $('#commonModal').find('input[name=id]').val();
	    	loadFloorValues(id);
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function saveFloorDepreciationValue(id, actualUse,depreciation) {
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
	    	$('#commonModal').find('#floorValueDepreciationform').html(html);
	    	/*var id = $('input[name=id]').val();
	    	loadFloorValues(id);*/
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
	    url: DIR+'rptbuilding/swornstatment',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#swornStatementForm').html(html);
	    	$('#saveSwornStatement').find(".rps_person_taking_oath_code").select3({});
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

function saveAnnotationSpecialPropertyStatus(id) {
	
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptbuilding/anootationspeicalpropertystatus',
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
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function loadAddLandAppraisalForm(id, sessionId) {
	var buildingKind = $('#commonModal').find('select[name=bk_building_kind_code]').val();
	var classCode    = $('#commonModal').find('select[name=pc_class_code]').val();
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    buildingkind:buildingKind,
	    classId:classCode,
	    property_id:$('#commonModal').find('input[name=id]').val()
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptbuilding/storefloorvalue',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#commonModal').find('#landappraisalform').html(html);
	    	var revisionYearId = $('#commonModal').find('.rvy_revision_year_id').val();
		    var revisionYear   = $('#commonModal').find('input[name=rvy_revision_year]').val();
		    setTimeout(function(){ 
		    	$('#commonModal').find('#addlandappraisalmodal').find(".bt_building_type_code").select3({dropdownParent:$('#commonModal').find('#addlandappraisalmodal').find(".bt_building_type_code").parent()});
		    	$('#commonModal').find('#addlandappraisalmodal').find(".pau_actual_use_code").select3({dropdownParent:$('#commonModal').find('#addlandappraisalmodal').find(".pau_actual_use_code").parent()});
			        }, 500);
	    },
	    error: function(){
	    	hideLoader();
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
	    url: DIR+'rptbuilding/approve',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('#approvalform').html(html);
	    	var status = $('#saveApprovelFormData').find("input[name=pk_is_active]:checked").val();
	    	$('#saveApprovelFormData').find(".err_rp_app_appraised_by").select3({});
	    	$('#saveApprovelFormData').find(".err_rp_app_recommend_by").select3({});
	    	$('#saveApprovelFormData').find(".err_rp_app_approved_by").select3({});
	    	$('#saveApprovelFormData').find(".rp_app_cancel_type").select3({});
	    	$('#saveApprovelFormData').find(".rp_app_cancel_by_td_id").select3({});
	    	$('#saveApprovelFormData').find(".err_rp_app_taxability").select3({});
	    	//$('#saveApprovelFormData').find(".err_rp_app_effective_quarter").select3({});
	    	if(status == 0){
	    		$('#saveApprovelFormData').find(".rp_app_cancel_type").prop('disabled',true);
	    		$('#saveApprovelFormData').find(".rp_app_cancel_by_td_id").prop('disabled',true);
	    	}
	    }
	});
}

function loadAddFloorValueForm(id, sessionId) {
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    property_id:$('#commonModal').find('input[name=id]').val()
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptbuildingy/addfloorvaluedescription',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('#commonModal').find('#floorValueForm').html('');
	    	$('#commonModal').find('#floorValueForm').html(html);
	    	var revisionYearId = $('#commonModal').find('.rvy_revision_year_id').val();
		    var revisionYear   = $('#commonModal').find('input[name=rvy_revision_year]').val();
		    setTimeout(function(){ 
		    	$('#commonModal').find('#addFloorValueFormmodal').find(".bt_building_type_code").select3({});
		    	$('#commonModal').find('#addFloorValueFormmodal').find(".pau_actual_use_code").select3({});
			        }, 500);
	    }
	});
}

function loadBuildingStructureForm(id, sessionId) {
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    property_id:$('#commonModal').find('input[name=id]').val()
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptbuildingy/addbuildingstructres',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('#commonModal').find('#structuralCharacterForm').html('');
	    	$('#commonModal').find('#structuralCharacterForm').html(html);
	    	var revisionYearId = $('#commonModal').find('.rvy_revision_year_id').val();
		    var revisionYear   = $('#commonModal').find('input[name=rvy_revision_year]').val();
		    setTimeout(function(){ 
		    	$('#commonModal').find('#addStructuralCharacterModal').find(".rbf_building_roof_desc1").select3({});
		    	$('#commonModal').find('#addStructuralCharacterModal').find(".rbf_building_roof_desc2").select3({});
		    	$('#commonModal').find('#addStructuralCharacterModal').find(".rbf_building_roof_desc3").select3({});
		    	$('#commonModal').find('#addStructuralCharacterModal').find(".rbf_building_floor_desc1").select3({});
		    	$('#commonModal').find('#addStructuralCharacterModal').find(".rbf_building_floor_desc2").select3({});
		    	$('#commonModal').find('#addStructuralCharacterModal').find(".rbf_building_floor_desc3").select3({});
		    	$('#commonModal').find('#addStructuralCharacterModal').find(".rbf_building_wall_desc1").select3({});
		    	$('#commonModal').find('#addStructuralCharacterModal').find(".rbf_building_wall_desc2").select3({});
		    	$('#commonModal').find('#addStructuralCharacterModal').find(".rbf_building_wall_desc3").select3({});
			        }, 500);
	    }
	});
}
function loadAssessementSumary(id) {
	var depRate = $('#commonModal').find('.rp_depreciation_rate').val();
	var classCode =  $('#commonModal').find('.pc_class_code').val();
	var brgyCode =  $('#commonModal').find('#brgy_code_id').val();
	var revisionYear =  $('#commonModal').find('#rvy_revision_year_id').val();
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
	    	$('#commonModal').find('#assessementSummaryData').html(html);
	    	//calculateTotalMarketValue();
	    	//$('#myModal'+mid).find(".pc_class_code_description").val(html.pc_class_description);
			/*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);*/
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

function enableDisableSelect3(action) {
	if($('#commonModal').find('#bk_building_kind_code').val() > 0){
		$('#commonModal').find('#bk_building_kind_code').prop('disabled',action);
        $('#commonModal').find('select[name=pc_class_code]').prop('disabled',action);
	}
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
	var barangay              = $('#commonModal').find('select[name=brgy_code_id]').val(); 
	var propertyKind          = $('#commonModal').find('input[name=pk_id]').val();
	var propertyClass         = $('#propertyTaxDeclarationForm').find('.pc_class_code').val();
	var propertyActualUseCode = $('#commonModal').find('#addlandappraisalmodal').find('.pau_actual_use_code').val();
	var propertyRevisionYear  = $('#commonModal').find('#rvy_revision_year_id').val();
	var totalMarketValue      = $('#commonModal').find('#addlandappraisalmodal').find('.rpbfv_total_floor_market_value').val();
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
	    	$('#commonModal').find('#addlandappraisalmodal').find("input[name=al_assessment_level]").val(html.data.al_assessment_level);
	    	}else{
	    	$('#commonModal').find('#addlandappraisalmodal').find("input[name=al_assessment_level]").val('00.00');
	    	}
	    //calculateLandAssessedValue();
	    	
	    }
	});
	}
}

function calculateLandMarketBaseValue() {
	var buildingUnitValue = $('#commonModal').find('#addFloorValueFormmodal').find('.rpbfv_floor_unit_value').val();
    var tatalLandArea    = $('#commonModal').find('#addFloorValueFormmodal').find('.rpbfv_floor_area').val();
    // var mesureType       = $('#addlandappraisalmodal').find('.lav_unit_measure').val();
    // if(mesureType == 2){
    // 	tatalLandArea = tatalLandArea*10000;
    // }if(mesureType == 1){
    // 	tatalLandArea = tatalLandArea;
    // }
    var totalMarketValue = tatalLandArea*buildingUnitValue;
    $('#commonModal').find('#addFloorValueFormmodal').find('.base_market_value').val(parseFloat(totalMarketValue).toFixed(2));
    $('#commonModal').find('#addFloorValueFormmodal').find('.rpbfv_total_floor_market_value').val(parseFloat(totalMarketValue).toFixed(2));
}

function calculateLandAssessedValue() {
	var totalBaseMarketValue = $('#commonModal').find('#addlandappraisalmodal').find('.rpa_base_market_value').val();
    var assessementPerscenta = $('#commonModal').find('#addlandappraisalmodal').find('.al_assessment_level').val();
    var assessedValue        = (totalBaseMarketValue*assessementPerscenta)/100;
    $('#commonModal').find('#addlandappraisalmodal').find('.rpa_assessed_value').val(parseFloat(assessedValue).toFixed(2));
}

function getLandUnitValue(classId = '', subClassId = '', actualUseCodeId = ''){
	var baranGy        = $('#commonModal').find('#brgy_code_id').val();
	var revisionYearId = $('#commonModal').find('#rvy_revision_year_id').val();
	var bt_building_type_code     = $('#commonModal').find('#addlandappraisalmodal').find('.bt_building_type_code').val();
	var buildingKing   = $('#propertyTaxDeclarationForm').find('.bk_building_kind_code').val();
	console.log('building unit value'+' baranGy '+baranGy+' revisionYearId '+revisionYearId+' bt_building_type_code '+bt_building_type_code+' buildingKing '+buildingKing);
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
	    	$('#commonModal').find('#addlandappraisalmodal').find(".rpbfv_floor_unit_value").val(parseFloat(html.data.buv_minimum_unit_value).toFixed(2));
	    	calculateDataForOtherFields();
	    	}else{
	    		$('#commonModal').find('#addlandappraisalmodal').find(".rpbfv_floor_unit_value").val(parseFloat(0).toFixed(2));
	    	calculateDataForOtherFields();
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
/*$('.deleteLandAppraisal').live('change', function(){
   alert('OK!');
});*/

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
function checkReadyToSubmit() {
		var dataToValidate = ['bt_building_type_code','pau_actual_use_code','rpbfv_floor_area','rpbfv_floor_unit_value','rpbfv_floor_base_market_value','rpbfv_total_floor_market_value','al_assessment_level'];
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

$('#commonModal').off('submit','#storelandappraisal').on('submit','#storelandappraisal',function(e){
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
			          	storeFloorValues($(this));
			        }
			    })
			
		}else{
			storeFloorValues($(this));
		}

		});

function storeFloorValues(ele) {
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
	    		$('#commonModal').find('.validate-err').html('');
                    $('#commonModal').find('#err_'+html.field_name).html(html.error);
                    $('#commonModal').find('.'+html.field_name).focus();
	    	}if(html.status == 'success'){
	    		$('#commonModal').find('.validate-err').html('');
	    		$('#commonModal').find('#addlandappraisalmodal').modal('hide');
	    		loadFloorValues($('#commonModal').find('input[name=id]').val());
	    		loadAssessementSumary($('#commonModal').find('input[name=id]').val());
	    		//loadAssessementSumary($('input[name=id]').val());
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
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function loadPropertyAnnotations(id = '') {
    	showLoader();
    	var id = $('#saveAnnotationPropertyStatus').find('input[name=property_id]').val();
		$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptbuilding/loadpropertyannotations',
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
function addmoreAdditionalItems(){
	var prevLength = $('#commonModal').find("#busiactivityDetails").find(".removeactivitydata").length;
	var html = $('#commonModal').find('#addlandappraisalmodal').find("#hidenactivityHtml").html();
	$('#commonModal').find('#addlandappraisalmodal').find(".activity-details").append(html);
	//$('#commonModal').find('#addlandappraisalmodal').find(".activity-details").find('input, select').attr('required','required');
	$('#commonModal').find('#addlandappraisalmodal').find(".activity-details").find('select').attr('required','required');

	var classid = $('#commonModal').find("#busiactivityDetails").find(".removeactivitydata").length;
     $('#commonModal').find("#bei_extra_item_code"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#accordionFlushExample4")});
     $('#commonModal').find("#hidenactivityHtml").find('.bei_extra_item_code').attr('id','bei_extra_item_code'+classid); 
     $('#commonModal').find("#hidenactivityHtml").find('.bei_extra_item_code').attr('class','bei_extra_item_code bei_extra_item_code_new');

}	

function calculateDataForOtherFields(){
    	/* Calculate Base Market Value */
    	var floorArea          = parseFloat($('#commonModal').find('#storelandappraisal').find('.rpbfv_floor_area ').val()).toFixed(3);
	    var unitValue          = parseFloat($('#commonModal').find('#storelandappraisal').find('.rpbfv_floor_unit_value').val()).toFixed(2);
	    if(isNaN(floorArea)){
	    	floorArea = 0;
	    }if(isNaN(unitValue)){
	    	unitValue = 0;
	    }
	    var basemarketvalue    = floorArea*unitValue;
	    $('#commonModal').find('#storelandappraisal').find('.rpbfv_floor_base_market_value').val(parseFloat(basemarketvalue).toFixed(2));
	    /* Calculate Base Market Value */

	    /* Calculate Total Base Market Value */
    	var additionalVal      =  parseFloat($('#commonModal').find('#storelandappraisal').find('.rpbfv_floor_additional_value').val()).toFixed(2);
	    var adjustmentVal      =  parseFloat($('#commonModal').find('#storelandappraisal').find('.rpbfv_floor_adjustment_value').val()).toFixed(2);
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
	    $('#commonModal').find('#storelandappraisal').find('.rpbfv_total_floor_market_value').val(parseFloat(toTalMarketValue).toFixed(2));
	    getAssessementLevel();
	    /* Calculate Total Base Market Value */

	    /* Calculate Assesment level and Assessed Value*/
	    setTimeout(function(){
	    	var assessmentLevel          = parseFloat($('#commonModal').find('#storelandappraisal').find('input[name=al_assessment_level]').val()).toFixed(2);
	    if(isNaN(assessmentLevel)){
	    	assessmentLevel = 0;
	    }
	    var assessedValue = (toTalMarketValue*assessmentLevel)/100;
	    //alert(assessmentLevel);
	    $('#commonModal').find('#storelandappraisal').find('input[name=rpb_assessed_value]').val(parseFloat(assessedValue).toFixed(2));

	     }, 500);

	    /* Calculate Assesment level and Assessed Value*/
    }

    function loadFloorValues(id) {
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
	    	$('#commonModal').find('#floorValueModal').find('#floorValueDescription').html(html);
	    	//calculateTotalMarketValue();
	    	//$('#myModal'+mid).find(".pc_class_code_description").val(html.pc_class_description);
			/*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);*/
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function autoFillMainForm(id) {
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
	    		$('#commonModal').find('input[name=buildingtype]').val(html.stucTypes);
	    	}if(html.storeys != ''){
	    		$('#commonModal').find('input[name=rp_building_no_of_storey]').val(html.storeys);
	    	}if(html.areaOfGround > 0){
	    		$('#commonModal').find('input[name=rp_building_gf_area]').val(groundArea);
	    	}if(html.totalArea > 0){
	    		$('#commonModal').find('input[name=rp_building_total_area]').val(totalArar);
	    	}
	    	if(html.disableKindOfBuilding){
	    		$('#commonModal').find('#bk_building_kind_code').find(':not(:selected)').prop('disabled',true);
	    		$('#commonModal').find('select[name=pc_class_code]').find(':not(:selected)').prop('disabled',true);
	    	}
	    },error:function(){
	    	
	    }
	});
}

function setManualPermit() {
		var manualPermit = $('#commonModal').find('.manual_entry').prop('checked');
		var id = $('#commonModal').find('input[name=id]').val();
		if(manualPermit) {
			$('#commonModal').find('select[name=permit_id]').next(".select3-container").hide();
		    $('#commonModal').find('input[name=rp_bulding_permit_no]').attr('type','text');
		    //$('#commonModal').find('input[name=rp_bulding_permit_no]').val('');
	    }else {
	        $('#commonModal').find('select[name=permit_id]').next(".select3-container").show();
		    $('#commonModal').find('input[name=rp_bulding_permit_no]').attr('type','hidden');
	    }
	}

function createPinSuffix(id, propId) {
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
}

function getTaxDe(id,brgy_code_id,rvy_revision_year_id){
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
           $('#commonModal').find("#rp_code_lref").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function getTaxDeAll(brgy_code_id,rvy_revision_year_id){
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
           $('#commonModal').find("#rp_code_lref").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function taxDeclarationId(id){
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

			    $('#commonModal').find("#land_owner").val(clientName);
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

			    $('#commonModal').find("#land_owner").val(clientName);
			}
	    	$('#commonModal').find("#land_location").val(html.loc_local_name)
	    	$('#propertyTaxDeclarationForm').find('input[name=rpo_code_lref]').val(html.rpo_code);
	    	$('#commonModal').find("#rp_cadastral_lot_no_lref").val(html.rp_cadastral_lot_no)
	    	$('#commonModal').find("#rp_total_land_area").val(html.rp_total_land_area)
            $('#commonModal').find("#rp_pin").val(html.rp_pin_no)
	    	$('#commonModal').find("#sectionNo").val(html.rp_section_no)
	    	$('#commonModal').find("#asslot").val(html.rp_app_assessor_lot_no)
	    	$('#propertyTaxDeclarationForm').find('input[name=rp_td_no_lref]').val(html.rp_td_no_lref);
	    	$('#propertyTaxDeclarationForm').find('input[name=rp_suffix_lref]').val(html.rp_suffix_lref);
	    	$('#propertyTaxDeclarationForm').find('input[name=rp_oct_tct_cloa_no_lref]').val(html.rp_oct_tct_cloa_no_lref);
	    	$('#propertyTaxDeclarationForm').find('input[name=rp_section_no]').val(html.rp_section_no_for_build);
	    	$('#propertyTaxDeclarationForm').find('input[name=rp_pin_no]').val(html.rp_pin_no_for_build);
	    }
	});
}

 function addmoreLocations(){
      var previousLength = $("#geolocationDetails").find(".serialnoclass").length;
      $("#hiddenlocationHtml").find('.validate-err.linkdesc').attr('id','linkdesc'+previousLength);
      var srcount = previousLength;
      srcount = parseFloat(srcount) + 1;
      $("#hiddenlocationHtml").find('.serialnoclass').text(srcount);
       var html = $("#hiddenlocationHtml").html();
      $("#geolocationDetails").append(html);
      $(".btn_cancel_locations").click(function(){
        $(this).closest(".removelocationdata").remove();
         });
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

function initiateTdRemoteSelectList(rpoCode) {
 	$('#commonModal').find("select[name=rp_code_lref]").select3({
    placeholder: 'Select Tax Declaration No.',
    allowClear: true,
    dropdownParent: $('#commonModal').find("select[name=rp_code_lref]").parent(),
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