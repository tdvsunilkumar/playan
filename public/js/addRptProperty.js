$(document).ready(function(){
	if($("#old_property_id").val()>0){
		loadAssessementSumary($('input[name=id]').val());
	}
	if($("#id").val() >0){
		getgeolocations();
	}

	$('#annotationSpecialPropertyStatusModal').off('click','.property_type').on('click','.property_type', function(){
		$('#annotationSpecialPropertyStatusModal').find('.property_type').not(this).prop('checked', false);
	})

	$('#annotationSpecialPropertyStatusModal').off('click','.property_type_new').on('click','.property_type_new', function(){
		$('#annotationSpecialPropertyStatusModal').find('.property_type_new').not(this).prop('checked', false);
	})

	var yearpickerInput = $('input[name="rp_app_effective_year"]').val();
	$('.yearpicker').yearpicker();
	$('.yearpicker').val(yearpickerInput).trigger('change');
    relatedMachineries();
	// $(".profile_id").select3({dropdownParent : '#commonModal'});
	// $(".property_administrator_id").select3({dropdownParent : '#commonModal'});
	//$("#profile").select3({dropdownAutoWidth : false,dropdownParent: $(".profile_id_group")});

	$('.main_profile_id').select3({
    placeholder: 'Select Property Owner',
    allowClear: true,
    dropdownParent: $(".main_profile_id").parent(),
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
	
	//$("#property_administrator_id").select3({dropdownAutoWidth : false,dropdownParent: $(".property_administrator_id_group")});
	$(".brgy_code_id").select3({dropdownParent : '#commonModal'});
	$(".rvy_revision_year_id").select3({dropdownParent : '#commonModal'});
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
     $('input[name="rpa_adjustment_factor_c"]').unbind('keyup');
    $(document).on('keyup','.decimalvalue',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
	});

    $("#displaySwornStatementModal").unbind("click");
	$(document).on('click','#displaySwornStatementModal',function(){
		var propertyId = $(this).data('propertyid');
		$('#swornStatementModal').modal('show');
		saveSwornStatement(propertyId);
		$(document).on('click','.closeSwornStatement',function(){
			$('#swornStatementModal').modal('hide');
		});

        
	});
	$(document).on('change','.addLandAppraisalAdjustmentFactorOrPlantTree',function(){
        $('.addLandAppraisalAdjustmentFactorOrPlantTree').not(this).prop('checked', false); 
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

	$('#commonModal').off('click','#displayAnnotationSpecialPropertyStatusModal').on('click','#displayAnnotationSpecialPropertyStatusModal',function(){
		var propertyId = $(this).data('propertyid');
		$('#annotationSpecialPropertyStatusModal').modal('show');
		saveAnnotationSpecialPropertyStatus(propertyId);
	});

	$("#addPreviousOwnerForLand").unbind("click");
	$(document).off('click','#addPreviousOwnerForLand').on('click','#addPreviousOwnerForLand',function(){
		var propId = $(this).data('propertyid');
		var url = DIR+'rptproperty/loadpreviousowner'+'?oldpropertyid='+propId;
        var title1 = 'Manage Previous Owner';
        var title2 = 'Manage Previous Owner';
        var title = (title1 != undefined) ? title1 : title2;
        var size = 'xll';
        var modalId = 'addPreviousOwnerForLandModal';
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
	                /*taskCheckbox();
	                commonLoader();*/
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

	$(document).on('change','.rpss_is_mortgaged',function(){
		if(this.checked){
			$(".mortgage_details_dection").css("pointer-events","auto");
		}else{
			$(".mortgage_details_dection").css("pointer-events","none");
			$('.mortgage_details_dection').find('input').val('');
			$('.mortgage_details_dection').find('select').val('');
		}

	});
	 $('#commonModal').off('click','#btn_addmore_geolocation').on('click','#btn_addmore_geolocation',function(){
      $('html, body').stop().animate({
        scrollTop: $('#commonModal').find("#btn_addmore_geolocation").offset().top
      }, 600);
      addmoreLocations();
    });

	$(document).off('click','#saveAnnotationData').on('click','#saveAnnotationData',function(){
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

 $(document).on('change','.rpss_beneficial_user_code',function(){
		var ownerName = $(this).find("option:selected").text();
		$('input[name=rpss_beneficial_user_name]').val(ownerName);

	});
	/* Land Appraisal Value Adjustment Factors*/
	$(document).off('submit','#storePropertyOwnerForm').on('submit','#storePropertyOwnerForm',function(e){
		showLoader();
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
	    	hideLoader();
	    	if(html.status == 'success'){
	    		$('#addPropertyOwnerModal').modal('hide');
	    		loadPropertyOwners();
	    	}
	    },error:function(){
	    	hideLoader();
	    }
	});

	})
     
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
	    	$('select[name="property_administrator_id"]').html(html);
	    },error:function(){
	    	hideLoader();
	    }
	});
	}

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
	
    $(".addNewPropertyOwner").unbind("click");
	$(document).on('click','.addNewPropertyOwner',function(){
		var url = $(this).data('url');
        var title1 = 'Manage Property Owner';
        var title2 = 'Manage Property Owner';
        var title = (title1 != undefined) ? title1 : title2;
        var size = 'xll';
        $("#addPropertyOwnerModal .modal-title").html(title);
        $("#addPropertyOwnerModal .modal-dialog").addClass('modal-' + size);
        $("#addPropertyOwnerModal").modal('show');
        showLoader();
    $.ajax({
        url: url,
        success: function (data) {
        	hideLoader();
            $('#addPropertyOwnerModal .body').html(data);
            setTimeout(function(){ 
            	select3Ajax("p_barangay_id_no","p_barangay_id_no_div","getBarngayList");
            	$("#clientsregistered").select3({dropdownParent : $("#clientsregistered").parent()});
		    	$("#country").select3({dropdownParent : $("#country").parent()});
			        }, 500);
            taskCheckbox();
            //common_bind("#addPropertyOwnerModal");
            commonLoader();
        },
        error: function (data) {
        	hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });

	})
    var propertyId = $('input[name=id]').val();
	/*loadPlantsTreesAdjustmentFactor(propertyId);*/
	loadLandAppraisal(propertyId);
	loadAssessementSumary(propertyId);
	commonFunction();
	   $("#submit").on("click",function(){
    if (($("input[name*='Completed']:checked").length)<=0) {
       // alert("You must check at least 1 box");
    }
    return true;
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
		if(id != ''){
		 getprofiledata(id); 
			 }

	})
	$("#property_administrator_id").change(function(){
		var id=$(this).val();
		if(id){ getAdminprofiledata(id); 
			  getTradedopdown(id);}

	})
	$("#rvy_revision_year_id").change(function(){
		var id=$(this).val();
		if(id){ 
			getRvyRevisionYearDetails(id); 
			}

	})
	$("#brgy_code_id").change(function(){
		var id=$(this).val();
		var updateCode = $('input[name=update_code]').val();
		//alert(id+' '+updateCode);
		if(id != '' && updateCode == 'DC'){ 
			getbarangayaDetails(id); 
			}

	})
	if($("#profile").val()>0){
		getprofiledata($("#profile").val());
	}
	if($('input[name=id]').val() == 0){
		getbarangayaDetails($('#brgy_code_id').val());
	}

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
	});
	

	$("#ba_business_name").change(function(){
		checkNewandRenew();
	})

	$('.refeshbuttonselect1').click(function(){
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
				$('#property_administrator_id').html($data);
				hideLoader();
			},
			error: function(err){
				console.log(err);
				hideLoader();
			}
		});
	});

	$('.refeshbuttonselect2').click(function(){
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
				$('.profile_id').html($data);
				hideLoader();
			},
			error: function(err){
				console.log(err);
				hideLoader();
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

	$('#approvalformModal').on('click','.editPreviousOwnerTd',function(){
		var propId = $(this).data('id');
		var url = DIR+'rptproperty/loadpreviousowner'+'?id='+propId;
        var title1 = 'Edit Previous Owner';
        var title2 = 'Edit Previous Owner';
        var title = (title1 != undefined) ? title1 : title2;
        var size = 'xll';
        var modalId = 'addPreviousOwnerForLandModal';
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

	
   
});

function setIscompletedname(){
	$('.bariscompleted').each(function(index, value){
		$(this).attr("name",index+"_bar_is_complied");
    })
}

function setDistrictCodes(id) {
	showLoader();
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
	    },error:function(){
	    	hideLoader();
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
	    	$('input[name=loc_group_brgy_no]').val(html.brgy_name);
	    	//$('textarea[name=rp_location_number_n_street]').val(html.brgy_name+', '+html.mun_desc);
	    	$('input[name=brgy_code]').val(html.brgy_code);
	    	$('input[name=dist_code]').val(html.dist_code);
	    	$('input[name=dist_code_name]').val((typeof html.dist_code !== "undefined")?html.dist_code+'-'+html.dist_name:'');
	    	$('input[name=brgy_code_and_desc]').val(html.brgy_code+'-'+html.brgy_name);
	    	$('input[name=loc_local_code_name]').val(html.mun_desc);
	    	$('input[name=loc_local_code]').val(html.loc_local_code_id);
	    	
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function getLocalityDetails(id){
	showLoader();
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
	    	hideLoader();
	    	$('.loadingGIF').hide();
	    	/*$('input[name=brgy_code_id]').val(html.id);
	    	$('input[name=loc_group_brgy_no]').val(html.brgy_name);*/
	    	
	    },error:function(){
	    	hideLoader();
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
	    		$('input[name="rp_administrator_code"]').val(arr.id);
	    		$('input[name="rp_administrator_code_address"]').val(arr.standard_address)
	    	
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function getRvyRevisionYearDetails(id){
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
	    	$('input[name="rvy_revision_year"]').val(html.rvy_revision_year);
	    	$('input[name="rvy_revision_code"]').val(html.rvy_revision_code);
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function getprofiledata(id){
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
	    		$('input[name="property_owner_address"]').val(arr.standard_address);
	    		$('input[name="rpo_code"]').val(arr.id);
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function deleteLandAppraisal(id, sessionId) {
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
	    dataType: "json",
	    success: function(html){ 
	    	hideLoader();
	    	if(html.status == 'verifypsw'){
                $('#verifyPsw').modal('show');
                $('#verifyPsw').find('.validate-err').html('');
                $('#verifyPsw').find('input[name=verify_psw]').val('');
                
            }if(html.status == 'success'){
                loadLandAppraisal($('input[name=id]').val());
	    	    loadAssessementSumary($('input[name=id]').val());
                
            }
	    	
	    },error:function(){
	    	hideLoader();
	    }
	});
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


function deletePlantTreeAppraisal(id, sessionId) {
	var landAppraisalSessionId = $('#landAppraisalAdjustmentFactorsmodal').find('#session_id').val();
	//alert(landAppraisalSessionId);
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    land_appraisal_id:landAppraisalSessionId,
	    "_token": $("#_csrf_token").val()
	}; 
	$.ajax({
	    type: "post",
	    url: DIR+'rptproperty/deleteplanttreeappraisal',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	hideLoader();
	    	if(html.status == 'verifypsw'){
                $('#verifyPsw').modal('show');
                $('#verifyPsw').find('.validate-err').html('');
                $('#verifyPsw').find('input[name=verify_psw]').val('');
                
            }if(html.status == 'success'){
                var landAppraisalId        = $('#storelandAppraisalFactors').find('input[name="id"]').val();
		    	var landAppraisalSessId    = $('#storelandAppraisalFactors').find('input[name="session_id"]').val();
		    	loadPlantsTreesAdjustmentFactor(landAppraisalId, landAppraisalSessId);
		    	loadAssessementSumary($('input[name=id]').val());
            }
	    	
	    },error:function(){
	    	hideLoader();
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

	$("#loadLandApprisalForm").unbind("click");
	$("#commonModal").on('click',"#loadLandApprisalForm",function(){
		$('#commonModal').find('#addlandappraisalmodal').modal('show');
		loadAddLandAppraisalForm(id = '');
		
		$(document).on('click','.closeLandAppraisalModal',function(){
			$('#addlandappraisalmodal').modal('hide');
		});

        
	}); 

    $("#plantstreesadjustmentfactor").unbind("click");
	$(document).off('click',"#plantstreesadjustmentfactor").on('click',"#plantstreesadjustmentfactor",function(){
		//alert();
		var propertyId             = $('#storelandAppraisalFactors').find('input[name="property_id"]').val();
		var landAppraisalSessionId = $('#storelandAppraisalFactors').find('input[name="session_id"]').val();
		var landAppraisalId        = $('#storelandAppraisalFactors').find('input[name="id"]').val();
		console.log(propertyId, landAppraisalSessionId, landAppraisalId);
		$(document).find('#treesplantsadjustmentfactormodal').modal('show');
		savePlantsTreesAdjustmentFactorForm(id = '', '', landAppraisalId, landAppraisalSessionId, propertyId);
		
		$(document).on('click','.closePlantsTreeFormModel',function(){
			$('#treesplantsadjustmentfactormodal').modal('hide');
		});

        
	}); 

    $('#plantstreesadjustmentfactornew').click(function(){
    	var selectedLandAppraisals = $('#commonModal').find('#landAppraisalListing').find("table tbody tr .addLandAppraisalAdjustmentFactorOrPlantTree:checkbox:checked");
    	var length                 = selectedLandAppraisals.length;
    	//alert(length);
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
        var updateCode = $('#propertyTaxDeclarationForm').find('input[name=uc_code]').val();
        //alert(propertyId);
		$('#approvalformModal').modal('show');
		
		saveApprovalFormData(propertyId,updateCode);
		
		$(document).on('click','.closeApprovalFormModel',function(){
			$('#approvalformModal').modal('hide');
		});

        
	}); 
	$('#commonModal').off('change','.pc_class_code').on('change','.pc_class_code',function(){
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
            var propertyId             = $('#storelandAppraisalFactors').find('input[name="property_id"]').val();
		    var landAppraisalSessionId = $('#storelandAppraisalFactors').find('input[name="session_id"]').val();
		    var landAppraisalId        = $('#storelandAppraisalFactors').find('input[name="id"]').val();
            $('#treesplantsadjustmentfactormodal').modal('show');
		    savePlantsTreesAdjustmentFactorForm(id, sessionId, landAppraisalId, landAppraisalSessionId, propertyId);
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
		$('#rvy_revision_year_id').prop('disabled', false);
        $('#brgy_code_id').prop('disabled', false);
		
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
	    		$('#commonModal').modal('hide');
	    		$('#Jq_datatablelist').DataTable().ajax.reload();
	    		Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: html.msg,
                  showConfirmButton: true,
                  timer: false
                }).then(function() {
				    //location.reload();
				});
	    		
	    	}if(html.status == 'verifypsw'){
	    		$('#verifyPsw').modal('show');
                $('#verifyPsw').find('.validate-err').html('');
                $('#verifyPsw').find('input[name=verify_psw]').val('');
	    		
	    	}if(html.status == 'error'){
                $('#rvy_revision_year_id').prop('disabled', true);
                $('#brgy_code_id').prop('disabled', true);
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

	
	
	$(document).off('submit','#saveApprovelFormData').on('submit','#saveApprovelFormData',function(e){
		showLoader();
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
	    	hideLoader();
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
	    },error:function(){
	    	hideLoader();
	    }
	});

		});
    function checkReadyToSubmit() {
		var dataToValidate = ['pc_class_code','ps_subclass_code','pau_actual_use_code','rpa_total_land_area','lav_unit_measure','lav_unit_value','rpa_base_market_value','al_assessment_level'];
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
			          	storeLandAppraisal($(this));
			        }
			    })
			
		}else{
			storeLandAppraisal($(this));
		}
			
  });

    $(document).off('submit','#storeplantstreesadjustmentfactor').on('submit','#storeplantstreesadjustmentfactor',function(e){
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
	    		    $('.validate-err').html('');
                    $('#err_'+html.field_name).html(html.error);
                    $('.'+html.field_name).focus();
	    	}if(html.status == 'verifypsw'){
                $('#verifyPsw').modal('show');
                $('#verifyPsw').find('.validate-err').html('');
                $('#verifyPsw').find('input[name=verify_psw]').val('');
                
            }if(html.status == 'success'){
	    		$('.validate-err').html('');
	    		$('#treesplantsadjustmentfactormodal').modal('hide');
	    		var landAppraisalId        = $('#storelandAppraisalFactors').find('input[name="id"]').val();
	    	    var landAppraisalSessId    = $('#storelandAppraisalFactors').find('input[name="session_id"]').val();
	    	    loadPlantsTreesAdjustmentFactor(landAppraisalId, landAppraisalSessId);
	    	    loadAssessementSumary($('input[name=id]').val());

	    	}if(html.status == 'error'){
	    		$('.validate-err').html('');
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

function calculateTotalMarketValue(mid) {
	var totalMarketValue = 0;
	/*var rows= $('#new_added_land_apraisal tbody tr').length;
	$("#new_added_land_apraisal tbody tr").each(function () {
    alert($(this).find('td.rpa_base_market_value').text());
});*/
	$('#new_added_land_apraisal').find("tbody tr .rpa_base_market_value").each(function(total){
		var marketValue = $(this).text().replace(/\,/g,'').replace('₱','');
		console.log(marketValue);
		totalMarketValue += parseFloat(marketValue);

	});
	//alert(totalMarketValue);
	var previousValue = $('#landApraisalTotalValueToDisplay').val();
	$('#landAppraisalTotalValueToDisplay').val(numberWithCommas(parseFloat(totalMarketValue).toFixed(2)));
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function storeLandAppraisal(ele) {
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
	    		$('#commonModal .validate-err').html('');
	    			
                    $('#commonModal #err_'+html.field_name).html(html.error);
                    $('#commonModal .'+html.field_name).focus();
	    	}if(html.status == 'success'){
	    		$('#commonModal .validate-err').html('');
	    		$('#addlandappraisalmodal').modal('hide');
	    		loadLandAppraisal($('#commonModal input[name=id]').val());
	    		loadAssessementSumary($('#commonModal input[name=id]').val());
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

function getActualUses(id){
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
	    	console.log($('#addlandappraisalmodal').find(".classification_code"));
	    	$('#addlandappraisalmodal').find(".pau_actual_use_code").html(html);
			/*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);*/
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function getSubClasses(id){
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
	    	$('#addlandappraisalmodal').find(".ps_subclass_code").html(html);
			/*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);*/
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function getSubClassesForPlantsTreeSection(id){
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
	    	$('#treesplantsadjustmentfactormodal').find(".plants_tree_ps_subclass_code").html(html);
			/*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);*/
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function saveApprovalFormData(id,ucCode) {
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    updatecode:ucCode,
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptproperty/approve',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#approvalform').html(html);
	    	$('#saveApprovelFormData').find(".err_rp_app_appraised_by").select3({dropdownAutoWidth : false,dropdownParent: $(".err_rp_app_appraised_by_group")});
	    	$('#saveApprovelFormData').find(".err_rp_app_recommend_by").select3({dropdownAutoWidth : false,dropdownParent: $(".err_rp_app_recommend_by_group")});
	    	$('#saveApprovelFormData').find(".err_rp_app_approved_by").select3({dropdownAutoWidth : false,dropdownParent: $(".err_rp_app_approved_by_group")});
	    	$('#saveApprovelFormData').find(".rp_app_cancel_type").select3({dropdownAutoWidth : false,dropdownParent: $(".rp_app_cancel_type_group")});
	    	$('#saveApprovelFormData').find(".rp_app_cancel_by_td_no").select3({dropdownAutoWidth : false,dropdownParent: $(".rp_app_cancel_by_td_no_group")});
	    	$('#saveApprovelFormData').find(".err_rp_app_taxability").select3({dropdownAutoWidth : false,dropdownParent: $(".err_rp_app_taxability_group")});
	    	$('#saveApprovelFormData').find(".err_rp_app_effective_quarter").select3({dropdownAutoWidth : false,dropdownParent: $(".err_rp_app_effective_quarter_group")});
	        $('#saveApprovelFormData').find(".uc_code").select3({dropdownAutoWidth : false,dropdownParent: $("#uc_codediv")});
	        $('#saveApprovelFormData').find(".rp_app_cancel_by_td_id").select3({
                placeholder: {
				    id: '-1', // the value of the option
				    text: 'Select T.D. No.'
				  },

	        	dropdownAutoWidth : false,
	        	dropdownParent: $(".rp_app_cancel_by_td_id_group")
	        });
	        setTimeout(function(){ 
	        	var cancelledById = $('#approvalformModal').find("input[name=cancelled_by_id]").val();
	        	if(cancelledById > 0){
	        	//alert();
	        	$('#saveApprovelFormData').find(".rp_app_cancel_by_td_id").prop('disabled',true);
	        }
	        	}, 500);
	        
	        
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function loadAddLandAppraisalForm(id, sessionId) {
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    property_id:$('#commonModal').find('input[name=id]').val()
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptproperty/storelandappraisal',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#commonModal').find('#landappraisalform').html(html);
	    	var revisionYearId = $('#commonModal').find('.rvy_revision_year_id').val();
		    var revisionYear   = $('#commonModal').find('input[name=rvy_revision_year]').val();
		    setTimeout(function(){ 
		    	$('#commonModal').find('#addlandappraisalmodal').find(".pc_class_code").select3({dropdownParent:$('#commonModal').find('#addlandappraisalmodal').find(".pc_class_code").parent()});
		    	$('#commonModal').find('#addlandappraisalmodal').find(".ps_subclass_code").select3({dropdownParent:$('#commonModal').find('#addlandappraisalmodal').find(".ps_subclass_code").parent()});
		    	$('#commonModal').find('#addlandappraisalmodal').find(".pau_actual_use_code").select3({dropdownParent:$('#commonModal').find('#addlandappraisalmodal').find(".pau_actual_use_code").parent()});
		    	$('#commonModal').find('#addlandappraisalmodal').find(".land_stripping_id").select3({dropdownParent:$('#commonModal').find('#addlandappraisalmodal').find(".land_stripping_id").parent()});
		        /*$('#addlandappraisalmodal').find('input[name=plant_tree_revision_year_code]').val(revisionYearId);
		        $('#addlandappraisalmodal').find('input[name=plant_tree_revision_year]').val(revisionYear);
		         getPlantTreesUnitValue();*/
			        }, 500);
	    },
	    error: function(){
	    	hideLoader();
	    }
	});
}

function savePlantsTreesAdjustmentFactorForm(id, sessionId, landAppraisalId, landAppSessionId, propertyId){
	
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    landAppraisalId:landAppraisalId,
	    landAppSessionId:landAppSessionId,
	    property_id:propertyId,
	    //barangy:barangy
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'rptproperty/storetressadjustmentfactor',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#plantstreesadjustmentfacctorform').html(html);
	    	var revisionYearId = $('select[name=rvy_revision_year_id]').val();
		    var revisionYear   = $('input[name=rvy_revision_year]').val();
		    //alert(revisionYearId);
		    setTimeout(function(){ 
		    	//$('#treesplantsadjustmentfactormodal').find("#rpta_date_planted").yearpicker();
		    	$('#treesplantsadjustmentfactormodal').find(".rp_planttree_code").select3({});
		    	$('#treesplantsadjustmentfactormodal').find(".plants_tree_pc_class_code").select3({});
		    	$('#treesplantsadjustmentfactormodal').find(".plants_tree_ps_subclass_code").select3({});
		        $('#treesplantsadjustmentfactormodal').find('input[name=plant_tree_revision_year_code]').val(revisionYearId);
		        $('#treesplantsadjustmentfactormodal').find('input[name=plant_tree_revision_year]').val(revisionYear);
		         getPlantTreesUnitValue();
			        }, 500);
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function getClassDetails(id){
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
	    	$('#addlandappraisalmodal').find(".pc_class_code_description").val(html.pc_class_description);
	    	
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function getLandStrippingDetails(id){
	showLoader();
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
	    	hideLoader();
	    	$('.loadingGIF').hide();
	    	/*$('#myModal'+mid).find(".rls_percent").val(html.rls_percent);
	    	$('#myModal'+mid).find(".rls_code").val(html.rls_code);
	    	$('#myModal'+mid).find(".lav_strip_unit_value").val(0);*/
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function getSubClassDetails(argument) {
	showLoader();
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
	    	hideLoader();
	    	$('.loadingGIF').hide();
	    	$('#addlandappraisalmodal').find(".pc_class_code_id").val(html.id);
	    },error:function(){
	    	hideLoader();
	    }
	});
}
function loadLandAppraisal(id) {
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
	    	$('#commonModal').find('#landAppraisalListing').html(html);
	    	calculateTotalMarketValue();
	    	//$('#myModal'+mid).find(".pc_class_code_description").val(html.pc_class_description);
			/*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);*/
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function loadAssessementSumary(id) {
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
	    	$('#assessementSummaryData').html(html);
	    	//calculateTotalMarketValue();
	    	//$('#myModal'+mid).find(".pc_class_code_description").val(html.pc_class_description);
			/*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);*/
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function loadrelatedBuildingSummary(id) {
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptproperty/relatedBuildingsummary',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#relatedBuildingSummaryData').html(html);
	    	//calculateTotalMarketValue();
	    	//$('#myModal'+mid).find(".pc_class_code_description").val(html.pc_class_description);
			/*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);*/
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
	    url: DIR+'rptproperty/swornstatment',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#swornStatementForm').html(html);

	    	//loadPropertyAnnotations();
	    	$('#saveSwornStatement').find(".rps_person_taking_oath_code").select3({});
	    	/*
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




function getPlantTreesUnitValue(){

	/*$('#myModal'+mid).find(".activity_id").val('');*/
	var barangy = $('#brgy_code_id').val();
	var platTreeId               = $('#treesplantsadjustmentfactormodal').find('.rp_planttree_code').val();
	var plantTreeclassId         = $(document).find('.plants_tree_pc_class_code').val();
	var plantTreesubClassId      = $('#treesplantsadjustmentfactormodal').find('.plants_tree_ps_subclass_code').val();
	var plantTreerevisionYearId  = $('#treesplantsadjustmentfactormodal').find('input[name=plant_tree_revision_year_code]').val();
	if(platTreeId != '' && plantTreeclassId != '' && plantTreesubClassId !== null && plantTreerevisionYearId != ''){
		$('.loadingGIF').show();
		showLoader();
	var filtervars = {
		barangy:barangy,
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
	    	hideLoader();
	    	if(html.status == 'success'){
	    	$('#treesplantsadjustmentfactormodal').find("input[name=rpta_unit_value]").val(parseFloat(html.data.ptuv_unit_value).toFixed(2));
	    	calculatePlantTreeMarketValue();
	    	}else{
	    	$('#treesplantsadjustmentfactormodal').find("input[name=rpta_unit_value]").val('0.00');	
	    	calculatePlantTreeMarketValue();
	    	}
	    	
	    },error:function(){
	    	hideLoader();
	    }
	});
	}
}

function getAssessementLevel(){
	/*$('#myModal'+mid).find(".activity_id").val('');*/
	var barangay              = $('select[name=brgy_code_id]').val();         
	var propertyKind          = $('input[name=pk_id]').val();
	var propertyClass         = $('#addlandappraisalmodal').find('.pc_class_code').val();
	var propertyActualUseCode = $('#addlandappraisalmodal').find('.pau_actual_use_code').val();
	var propertyRevisionYear  = $('.rvy_revision_year_id').val();
	var baseMarketValue       = $('#addlandappraisalmodal').find('.rpa_base_market_value').val();
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
	    	$('#addlandappraisalmodal').find(".al_assessment_level_hidden").val(html.data.al_assessment_level);
	    	$('#addlandappraisalmodal').find(".al_minimum_unit_value").val(html.data.al_minimum_unit_value);
	    	$('#addlandappraisalmodal').find(".al_maximum_unit_value").val(html.data.al_maximum_unit_value);
	    	}else{
	    	$('#addlandappraisalmodal').find(".al_assessment_level_hidden").val('00.00');
	    	$('#addlandappraisalmodal').find(".al_minimum_unit_value").val('00.00');
	    	$('#addlandappraisalmodal').find(".al_maximum_unit_value").val('00.00');
	    	}
	    calculateLandAssessedValue();
	    	
	    },error:function(){
	    	hideLoader();
	    }
	});
	}
}

function calculateLandMarketBaseValue() {
	var landAppraisalUnitValue = $('#addlandappraisalmodal').find('.lav_unit_value').val();
    var tatalLandArea    = $('#addlandappraisalmodal').find('.rpa_total_land_area').val();
    var mesureType       = $('#addlandappraisalmodal').find('.lav_unit_measure').val();
    tatalLandArea = tatalLandArea;
    var totalMarketValue = tatalLandArea*landAppraisalUnitValue;
    $('#addlandappraisalmodal').find('.rpa_base_market_value').val(parseFloat(totalMarketValue).toFixed(2));
    $('#addlandappraisalmodal').find('.rpa_adjusted_market_value').val(parseFloat(totalMarketValue).toFixed(2));
    getAssessementLevel();
    calculateLandAssessedValue();

}

function calculateLandAssessedValue() {
	var totalBaseMarketValue = $('#addlandappraisalmodal').find('.rpa_base_market_value').val();
	var maxUnitValue         = $('#addlandappraisalmodal').find('.al_maximum_unit_value').val();
	var minUnitValue         = $('#addlandappraisalmodal').find('.al_minimum_unit_value').val();
	var assessementPerscenta = $('#addlandappraisalmodal').find('.al_assessment_level_hidden').val();
	console.log(totalBaseMarketValue, maxUnitValue, minUnitValue, assessementPerscenta);
	if(parseFloat(totalBaseMarketValue) >= parseFloat(minUnitValue) && parseFloat(totalBaseMarketValue) <= parseFloat(maxUnitValue)){
		var newAssessementPerscenta = assessementPerscenta;
	}else{
		var newAssessementPerscenta = 0;
	}
	$('#addlandappraisalmodal').find('.al_assessment_level').val(newAssessementPerscenta);
    var assessedValue        = (totalBaseMarketValue*newAssessementPerscenta)/100;
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
	    	$('#addlandappraisalmodal').find(".rls_percent").val(html.data.rls_percent);
	    	$('#addlandappraisalmodal').find(".rls_code").val(html.data.rls_code);
	    	$('#addlandappraisalmodal').find(".lav_strip_unit_value").val(html.data.lav_strip_unit_value);
	    	$('#addlandappraisalmodal').find(".lav_unit_value").val(html.data.lav_unit_value);
	    	$('#addlandappraisalmodal').find(".lav_unit_measure").val(html.data.lav_unit_measure_name);
	    	}else{
	    	$('#addlandappraisalmodal').find(".rls_percent").val('');
	    	$('#addlandappraisalmodal').find(".rls_code").val('');
	    	$('#addlandappraisalmodal').find(".lav_strip_unit_value").val('');
	    	$('#addlandappraisalmodal').find(".lav_unit_value").val('');
	    	$('#addlandappraisalmodal').find(".lav_unit_measure").val('');
	    	}
	    	calculateLandMarketBaseValue();
	    	
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

function loadPlantsTreesAdjustmentFactor(id = '', sessionId = ''){
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	    sessionId:sessionId,
	    "_token": $("#_csrf_token").val()
	}; 
	$.ajax({
	    type: "POST",
	    url: DIR+'rptproperty/getplantstreesadjustmentfactor',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	hideLoader();
	    	$('#plantstreesadjustmentfactorlisting').html(html);
	    	//$('#myModal'+mid).find(".pc_class_code_description").val(html.pc_class_description);
			/*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);*/
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function calculatePlantTreeMarketValue() {
	var plantTreeUnitValue = $('#treesplantsadjustmentfactormodal').find('input[name=rpta_unit_value]').val();
    var tatalAreaPlated    = $('#treesplantsadjustmentfactormodal').find('input[name=rpta_total_area_planted]').val();
    var totalMarketValue = tatalAreaPlated*plantTreeUnitValue;
    $('#treesplantsadjustmentfactormodal').find('input[name=rpta_market_value]').val(parseFloat(totalMarketValue).toFixed(2));
}

function calculateLandAppAdjustmentPercentValue(submitForm = true) {
	var baseMarketVal = $('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_base_market_value]').val();
	var adjFactorA    = $('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_adjustment_factor_a]').val();
	var adjFactorB    = $('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_adjustment_factor_b]').val();
	var adjFactorC    = $('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_adjustment_factor_c]').val();
    baseMarketVal     = parseFloat(baseMarketVal);
	adjFactorA        = parseFloat(adjFactorA);
	adjFactorB        = parseFloat(adjFactorB);
	adjFactorC        = parseFloat(adjFactorC);
	var TotalPercent  = (adjFactorA)+(adjFactorB)+(adjFactorC);
	var adjTotalPer   = 100+TotalPercent;
	var valAdjusted   = (TotalPercent/100)*baseMarketVal; //318750
	//var valAdjusted   = baseMarketVal*adjTotalPer/100;
	var adjMarketVal  = baseMarketVal+valAdjusted;
	console.log('base Market Value'+baseMarketVal,'A'+adjFactorA,'B'+adjFactorB,'C'+adjFactorC,'totalPercentage'+TotalPercent,'total Adjusted Percentage'+adjTotalPer,'valAdjusted'+valAdjusted,'adjMarketVal'+adjMarketVal);
	$('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_adjustment_percent]').val(parseFloat(adjTotalPer).toFixed(2)+'%');
	$('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_adjustment_value]').val((Math.sign(parseFloat(valAdjusted).toFixed(2)) == -1)?'('+Math.abs(parseFloat(valAdjusted).toFixed(2))+')':parseFloat(valAdjusted).toFixed(2));
	$('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_adjusted_market_value]').val(parseFloat(adjMarketVal).toFixed(2));
	$('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_adjustment_value_for_display]').val((Math.sign(parseFloat(valAdjusted).toFixed(2)) == -1)?'('+Math.abs(parseFloat(valAdjusted).toFixed(2))+')':parseFloat(valAdjusted).toFixed(2));
	$('#landAppraisalAdjustmentFactorsform').find('input[name=rpa_adjusted_market_value_for_display]').val(parseFloat(adjMarketVal).toFixed(2));
	if(submitForm){
		$('#storelandAppraisalFactors').submit();
	}
	
}

function displayLandAppraisalAdjustmnetFactorForm(id, sessionId, propertyId) {
	showLoader();
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
	    	hideLoader();
	    	$('#landAppraisalAdjustmentFactorsform').html(html);
	    	 var landAppraisalId        = $('#storelandAppraisalFactors').find('input[name="id"]').val();
	    	 var landAppraisalSessId    = $('#storelandAppraisalFactors').find('input[name="session_id"]').val();
	    	loadPlantsTreesAdjustmentFactor(landAppraisalId, landAppraisalSessId);
	    	//$('#myModal'+mid).find(".pc_class_code_description").val(html.pc_class_description);
			/*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);*/
	    },error:function(){
	    	hideLoader();
	    }
	});
}
$(document).off('submit','#storelandAppraisalFactors').on('submit','#storelandAppraisalFactors',function(e){
	showLoader();
		e.preventDefault();
		var url    = $(this).attr('action');
		var method = $(this).attr('method');
		var data   = $('#storelandAppraisalFactors').serialize();
		$.ajax({
	    type: "post",
	    url: url,
	    data: data,
	    dataType: "json",
	    success: function(html){ 
	    	hideLoader();
	    	if(html.status == 'success'){
	    		loadAssessementSumary($('input[name=id]').val());
	    		//$('#landAppraisalAdjustmentFactorsmodal').modal('hide');
	    	}if(html.status == 'verifypsw'){
	    		var factors = html.previousValues;
	    		$('#commonModal').find('input[name=rpa_adjustment_factor_a]').val(factors.factor_a);
	    		$('#commonModal').find('input[name=rpa_adjustment_factor_b]').val(factors.factor_b);
	    		$('#commonModal').find('input[name=rpa_adjustment_factor_c]').val(factors.factor_c);
	    		calculateLandAppAdjustmentPercentValue(false);
                $('#verifyPsw').modal('show');
                $('#verifyPsw').find('.validate-err').html('');
                $('#verifyPsw').find('input[name=verify_psw]').val('');
                
            }if(html.status == 'error'){
	    		Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: 3000
                    })
	    	}
	    },error:function(){
	    	hideLoader();
	    }
	});

	})
function saveAnnotationSpecialPropertyStatus(id) {
	showLoader();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptproperty/anootationspeicalpropertystatus',
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
			//setTimeout(function(){ ; }, 2000);	
				
	    	
	    },error:function(){
	    	hideLoader();
	    }
	});
}

function loadPropertyAnnotations(id = '') {
   	//showLoader();
	var id = $('#saveAnnotationPropertyStatus').find('input[name=property_id]').val();
	$('.loadingGIF').show();
	var filtervars = {
	    id:id,
	}; 
	$.ajax({
	    type: "get",
	    url: DIR+'rptproperty/loadpropertyannotations',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	//hideLoader();
	    	$('.loadingGIF').hide();
	    	$('#listAnnotationshere').html(html);

	    },error:function(){
	    	hideLoader();
	    }
	});
	}
    function saveAnnotationDataToDB() {
		showLoader();
		var url      = DIR+'rptproperty/anootationspeicalpropertystatus';
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
	    url: DIR+'rptproperty/deleteannotaion',
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


   function relatedMachineries()
{
	var dropdown_html=get_page_number('1'); 
	var table = $('#relatedBuildingsAndMachineries').DataTable({ 
        dom:'rtip',
		"bProcessing": true,
		"serverSide": true,
		"bDestroy": true,
		"searching": false,
		"order": [],
		"columnDefs": [{ orderable: false, targets: [0,7] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'rptproperty/relatedBuildingsummary', // json datasource
			type: "GET", 
			"data": {
				"id":$("#id").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
        "columns": [
            { "data": "no" },
        	{ "data": "td_no" },
			{ "data": "taxpayer_name" },
            { "data": "kind" },
        	{ "data": "pin" },
        	{ "data": "market_value" },
        	{ "data": "assessment_level" },
        	{ "data": "assessed_value" },
        	{ "data": "pk_is_active" }
        ],
        drawCallback: function(s){ 
	        
	        
            
	    }
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