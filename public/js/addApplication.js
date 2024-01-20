$(document).ready(function(){
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
			  getTradedopdown(id);}

	})
	if($("#profile").val()>0){
		getprofiledata($("#profile").val());
	}
	$("#barangay_id").change(function(){
		var id=$(this).val();
		getBarangyaDetails(id);
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
    $("#bplo_code_abbreviationdefault").select3({dropdownAutoWidth : false,dropdownParent: $("#defaultrequirements")});
});

function setIscompletedname(){
	$('.bariscompleted').each(function(index, value){
		$(this).attr("name",index+"_bar_is_complied");
    })
}

function getNatureofRequirements(id){
	          var ids=''; var prereq ="";
			   $('.natureofbussiness').each(function () {
					if($(this).val()!='')
						ids +=$(this).val()+',';
				});
			   ids = ids.replace(/,\s*$/, '');
              var id = ids;
              $('.codeabbrevation').each(function () {
					if($(this).val()!='')
						prereq +=$(this).val()+',';
				});
			   prereq = prereq.replace(/,\s*$/, '');
             
              $.ajax({
                url: DIR+'getrequirementofnature',
                type: 'POST',
                data: {
                    "id": id,"prereq": prereq,"_token": $("#_csrf_token").val(),
                },
                success: function (data) {
                   var url = data;
                   console.log(url);
                   $("#dynamicrequirements").html(data);
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
                   setIscompletedname();
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
	    	$("#brgy_name").val(html.brgy_name)
	    }
	});
}

function addmoreRequirements(){
	var html = $("#hidenrequirementHtml").html();
	var prevLength = $(".requirementDetails").find(".removerequirementdata").length;
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
	
	var classid = $(".requirementDetails").find(".removerequirementdata").length;
	$("#bplo_code_abbreviation"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#requirementDetails")});
	$("#hidenrequirementHtml").find('select').attr('id','bplo_code_abbreviation'+classid);
}

function addmoreNature(){
	var html = $("#hidennatureHtml").html();
	var prevLength = $(".natureDetails").find(".removenaturedata").length;
	$(".natureDetails").append(html);
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
		
		var cnt = $(".natureDetails").find(".removenaturedata").length;
		$("#hidennatureHtml").find('select').attr('id','psic_subclass_id'+cnt);
	});
	var classid = $(".natureDetails").find(".removenaturedata").length;
	$("#psic_subclass_id"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#natureDetails")});

    $("#hidennatureHtml").find('select').attr('id','psic_subclass_id'+classid);
    $(".natureofbussiness").change(function(){
		var id=$(this).val();
		if(id){ getNatureofRequirements(id); }
	})
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

function getprofiledata(id){
	$('.loadingGIF').show();
	var filtervars = {
	    pid:id,
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getprofiles',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	arr = $.parseJSON(html);
	    	if(arr.length > 0){
	    		console.log(arr[0]['id']);
	    		$("#ba_p_first_name").val(arr[0]['p_first_name']);
	    		$("#ba_p_middle_name").val(arr[0]['p_middle_name']);
	    		$("#ba_p_last_name").val(arr[0]['p_family_name']);
	    		$("#ba_telephone_no").val(arr[0]['p_telephone_no']);
	    		$("#ba_fax_no").val(arr[0]['p_fax_no']);
	    		$("#ba_tin_no").val(arr[0]['p_tin_no']);
	    		$("#ba_p_address").val(arr[0]['p_address_house_lot_no']+','+arr[0]['p_address_street_name']+','+arr[0]['brgy_name']+','+arr[0]['mun_desc']+','+arr[0]['prov_desc']+','+arr[0]['reg_region']);
	    	}
	    }
	});
}

