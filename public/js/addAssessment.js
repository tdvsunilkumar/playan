var mid=0;
var type='';
$(document).ready(function(){
	$("#btn_addmore_nature").click(function(){
		$('html, body').stop().animate({
      scrollTop: $("#btn_addmore_nature").offset().top
    }, 600);
		addmoreNature();
	});
	getEngneeringFeeDetails();
	$(".btn_cancel_nature").click(function(){
		$(this).closest(".removenaturedata").remove();
	}); 
	$("#ba_business_account_no").change(function(){
		var id=$(this).val();
		if(id){ getprofiledata(id); }
	})
	$('.numeric').numeric();
	if($("#ba_business_account_no").val()>0){
		getprofiledata($("#ba_business_account_no").val());
	}
	$("#barangay_id").change(function(){
		var id=$(this).val();
		getBarangyaDetails(id);
	})
	var totalPopup = $("#popupDetails").find(".modalDiv").length;
	for (var i = 0; i < totalPopup; i++) {
	    getAllFeeDetailsonload(i); 
	    var currid = $('#myModal'+i).find(".tax_type_code").val();
		getClassificationsonload(currid,i);
		var currid = $('#myModal'+i).find(".classification_id").val();
		getActivityDrodownonload(currid,i);

		if(i>=0){
			 $('#myModal'+i).find(".bussiness_application_code").attr('id','bussiness_application_code'+i);
			 $('#myModal'+i).find(".bussiness_application_code").addClass('bussiness_application_code'+i);
			 $('#myModal'+i).find(".tax_type_code").attr('id','tax_type_code'+i);
			 $('#myModal'+i).find(".tax_type_code").addClass('tax_type_code'+i);
			 

		}
	}
	$(".showLess").shorten({
            "showChars" : 30,
            "moreText"	: "More",
            "lessText"	: "Less",
        });

	commonFunction();
});

function commonFunction(){
	$(".btnPopupOpen").unbind("click");
	$(".btnPopupOpen").click(function(){
		type = $(this).attr('type');
		if(type=='edit'){
			mid = $(this).attr('mid');
			$('#myModal'+mid).modal('show');
		}else{
			var totalPopup = $("#popupDetails").find(".modalDiv").length;
			mid = totalPopup;
			
			$("#hidenPopupHtml").find(".bussiness-model").attr("id","myModal"+mid);
			$("#hidenPopupHtml").find(".closeModel").attr("mid",mid);
			var modelHtml = $("#hidenPopupHtml").html();
			$("#popupDetails").append(modelHtml);
			$('#myModal'+mid).modal('show');
			$('#myModal'+mid).find(".bussiness_application_code").attr('id','bussiness_application_code'+mid);
			$('#myModal'+mid).find(".bussiness_application_code").addClass('bussiness_application_code'+mid);
			$('#myModal'+mid).find(".tax_type_code").attr('id','tax_type_code'+mid);
			$('#myModal'+mid).find(".tax_type_code").addClass('tax_type_code'+mid);

			$('#myModal'+mid).find(".classification_code").attr('id','classification_code'+mid);
			$('#myModal'+mid).find(".classification_code").addClass('classification_code'+mid);

			$('#myModal'+mid).find(".activity_code").attr('id','activity_code'+mid);
			$('#myModal'+mid).find(".activity_code").addClass('activity_code'+mid);


			if ($(".bussiness_application_code"+mid).length > 0) {
				$(".bussiness_application_code"+mid).select3({dropdownAutoWidth : false,dropdownParent: $("#myModal"+mid)});
	        }
	       	if ($(".tax_type_code"+mid).length > 0) {
	       		
	       		$(".tax_type_code"+mid).select3({dropdownAutoWidth : false,dropdownParent: $("#myModal"+mid)});
	        }
	        if ($(".classification_code"+mid).length > 0) {
	       		$(".classification_code"+mid).select3({dropdownAutoWidth : false,dropdownParent: $("#myModal"+mid)});
	        }
	        if ($(".activity_code"+mid).length > 0) {
	       		$(".activity_code"+mid).select3({dropdownAutoWidth : false,dropdownParent: $("#myModal"+mid)});
	        }

		}
		
		$(".savebusinessDetails").unbind("click");
		$(".savebusinessDetails").click(function(){
			var inputFields=['bussiness_application_code','bussiness_application_desc','tax_type_code','tax_type_desc','classification_code','classification_desc','activity_code','activity_desc','essential_commodities','no_of_perdays','mayrol_permit_description','mayrol_permit_code','garbage_description','garbage_code','sanitary_description','sanitary_code','capitalization'];
			for (let i = 0; i < inputFields.length; i++) {
				var inputValue = $('#myModal'+mid).find("."+inputFields[i]).val();
				if(inputValue==""){
					$('#myModal'+mid).find("#err_"+inputFields[i]).html("Please fill this field");
					return false;
				}else{
					$('#myModal'+mid).find("#err_"+inputFields[i]).html("");
				}
			}
			// if($(this).prop("checked") == true) {
   //              alert("Checkbox is checked.");
   //            }
			var bussiness_application_desc = $('#myModal'+mid).find(".bussiness_application_desc").val();
			var activity_desc = $('#myModal'+mid).find(".activity_desc").val();
			var capitalization = $('#myModal'+mid).find(".capitalization").val();
			var gross_sale = $('#myModal'+mid).find(".gross_sale").val();

			$("#hidenPopupListHtml").find(".app_qurtr").html('2nd - 2nd');
			$("#hidenPopupListHtml").find(".nature_of_bussiness").html(bussiness_application_desc);
			$("#hidenPopupListHtml").find(".activity").html(activity_desc);
			$("#hidenPopupListHtml").find(".capitalization").html(capitalization);
			$("#hidenPopupListHtml").find(".gross_sale").html(gross_sale);
			$("#hidenPopupListHtml").find(".btnPopupOpen").attr('mid',mid);
			
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

		$(".bussiness_application_code").change(function(){
			var id=$(this).val();
			if(id){ getBussinessData(id); } 
			else{ 
				$('#myModal'+mid).find(".bussiness_application_desc").val('');
				$('#myModal'+mid).find(".bussiness_application_id").val('');
			}
		})
		$(".tax_type_code").change(function(){
			
			var id=$(this).val();
			if(id){ 
				getTasktypesData(id); 
				getClassifications(id);
			}else{
				$('#myModal'+mid).find(".tax_type_desc").val('');
				$('#myModal'+mid).find(".tax_type_id").val('');
			}
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

function getClassifications(id){
    var tax_class_id =0;
    var tax_type_id =id;
    var pre_classification_id =0;
    $.ajax({
        url :DIR+'getClassificationByType', // json datasource
        type: "POST", 
        data: {
          "tax_type_id":tax_type_id,
          "pre_classification_id":pre_classification_id,
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
			$('#myModal'+mid).find(".classification_code").html(html);
        }
    })
}

function getClassificationsonload(currid,i){
    var tax_class_id =0;
    var tax_type_id =currid;
    var pre_classification_id = $('#myModal'+i).find(".classification_id").val();
    $.ajax({
        url :DIR+'getClassificationByType', // json datasource
        type: "POST", 
        data: {
          "tax_type_id":tax_type_id,
          "pre_classification_id":pre_classification_id,
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
			$('#myModal'+i).find(".classification_code").html(html);
			$('#myModal'+i).find(".classification_code").select3({dropdownAutoWidth : false,dropdownParent: $("#myModal"+i)});
        }
    })
}

function getActivityDrodown(id){
	var pre_activityid =0;
    $.ajax({
        url :DIR+'getActivitybyClass', // json datasource
        type: "POST", 
        data: {
          "class_id":id,
          "pre_activityid":pre_activityid,
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
			$('#myModal'+mid).find(".activity_code").html(html);
        }
    })
}

function getActivityDrodownonload(currid,i){
	var pre_activityid =$('#myModal'+i).find(".activity_id").val();
    $.ajax({
        url :DIR+'getActivitybyClass', // json datasource
        type: "POST", 
        data: {
          "class_id":currid,
          "pre_activityid":pre_activityid,
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
			$('#myModal'+i).find(".activity_code").html(html);
        }
    })
}

function applyInputpicker(data,description,code,amount){
	$('#myModal'+mid).find('.'+description).inputpicker({
		data:data,
		fields:[
			{name:'code',text:'Code'},
			{name:'amount',text:'Amount'},
			{name:'description',text:'Business Category'}
		],
		headShow: true,
		fieldText : 'description',
		fieldValue: 'description',
	  	filterOpen: true
	});
	$('#myModal'+mid).find('.'+description).change(function(){
		let index = data.findIndex(x => x.description === $(this).val());
		if(index>=0){
			$('#myModal'+mid).find('.'+code).val(data[index].code);
			$('#myModal'+mid).find('.'+amount).val(data[index].amount);
		}
	})
}

function applyInputpickeronload(data,description,code,amount,i){
	$('#myModal'+i).find('.'+description).inputpicker({
		data:data,
		fields:[
			{name:'code',text:'Code'},
			{name:'amount',text:'Amount'},
			{name:'description',text:'Business Category'}
		],
		headShow: true,
		fieldText : 'description',
		fieldValue: 'description',
	  	filterOpen: true
	});
	$('#myModal'+i).find('.'+description).change(function(){
		let index = data.findIndex(x => x.description === $(this).val());
		if(index>=0){
			$('#myModal'+i).find('.'+code).val(data[index].code);
			$('#myModal'+i).find('.'+amount).val(data[index].amount);
		}
	})
}

function getAllFeeDetails(id){
	var tasktype = $('#myModal'+mid).find(".tax_type_id").val();
	var classificationid = $('#myModal'+mid).find(".classification_code").val();
	var activity = $('#myModal'+mid).find(".activity_code").val();
	var areaused = $("#ba_building_total_area_occupied").val();
	var noofworker = $("#occupationaltax").val();
	var capitaliztion = $('#myModal'+mid).find(".capitalization").val();
    $.ajax({
        url :DIR+'getAllFeeDetails', // json datasource
        type: "POST", 
		dataType:"json",
        data: {
          "tasktypeid":tasktype,
          "classificationid":classificationid,
          "activityid":activity,
          "areaused":areaused,
          "noofworker":noofworker,
          "capitaliztion":capitaliztion,
          "_token": $("#_csrf_token").val(),
        },
        success: function(data){
			console.log("data",data)
			if(!data.ESTATUS){
				applyInputpicker(data.arrPermits,'mayrol_permit_description','mayrol_permit_code','permit_amount');
				applyInputpicker(data.arrGarbage,'garbage_description','garbage_code','garbage_amount');
				applyInputpicker(data.arrSanitary,'sanitary_description','sanitary_code','sanitary_amount');
			}else{
				 $('#myModal'+mid).find(".mayrol_permit_description").val('');
				 $('#myModal'+mid).find(".garbage_description").val('');
				 $('#myModal'+mid).find(".sanitary_description").val('');
			}
			
        }
    })
}

function getEngneeringFeeDetails(){
    $.ajax({
        url :DIR+'getEngneeringFeeDetails', // json datasource
        type: "GET", 
		dataType:"json",
        data: {
          "_token": $("#_csrf_token").val(),
        },
        success: function(data){
			console.log("data",data)
			if(!data.ESTATUS){
				applyInputpickerengfee(data.arrEngnneringfee,'engneeringfee_description','engneering_code','engneering_amount');
			}
        }
    })
}

function applyInputpickerengfee(data,description,code,amount){
	$('#engneeringfee_description').inputpicker({
		data:data,
		fields:[
			{name:'code',text:'Code'},
			{name:'amount',text:'Amount'},
			{name:'description',text:'Engineering Fees'}
		],
		headShow: true,
		fieldText : 'description',
		fieldValue: 'description',
	  	filterOpen: true
	});
	$('#engneeringfee_description').change(function(){
		let index = data.findIndex(x => x.description === $(this).val());
		if(index>=0){
			$('#engneering_code').val(data[index].code);
			$('#engneering_amount').val(data[index].amount);
			$('#engneering_feeid').val(data[index].id);
		}
	})
}

function getAllFeeDetailsonload(i){
 
	var tasktype = $('#myModal'+i).find(".tax_type_id").val();
	var classificationid = $('#myModal'+i).find(".classification_code").val();
	var activity = $('#myModal'+i).find(".activity_code").val();
	var areaused = $("#ba_building_total_area_occupied").val();
	var noofworker = $("#occupationaltax").val();
	var capitaliztion = $('#myModal'+mid).find(".capitalization").val();
    $.ajax({
        url :DIR+'getAllFeeDetails', // json datasource
        type: "POST", 
		dataType:"json",
        data: {
          "tasktypeid":tasktype,
          "classificationid":classificationid,
          "activityid":activity,
          "areaused":areaused,
          "noofworker":noofworker,
          "capitaliztion":capitaliztion,
          "_token": $("#_csrf_token").val(),
        },
        success: function(data){
			console.log("data",data)
			if(!data.ESTATUS){
				applyInputpickeronload(data.arrPermits,'mayrol_permit_description','mayrol_permit_code','permit_amount',i);
				applyInputpickeronload(data.arrGarbage,'garbage_description','garbage_code','garbage_amount',i);
				applyInputpickeronload(data.arrSanitary,'sanitary_description','sanitary_code','sanitary_amount',i);
			}
			
        }
    })
}

function getBussinessData(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getBussinessData',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
			//alert(html)
	    	$('.loadingGIF').hide();
			$('#myModal'+mid).find(".bussiness_application_desc").val(html.subclass_description);
			$('#myModal'+mid).find(".bussiness_application_id").val(html.id);
	    }
	});
}
function getClasificationDesc(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getClasificationDesc',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
			$('#myModal'+mid).find(".classification_desc").val(html.bbc_classification_desc);
			$('#myModal'+mid).find(".classification_id").val(html.id);
	    }
	});
}
function getActivityDesc(id){
  $('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getActivityDesc',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
			$('#myModal'+mid).find(".activity_desc").val(html.bba_desc);
			$('#myModal'+mid).find(".activity_id").val(html.id);
	    }
	});
}
function getTasktypesData(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getTasktypesData',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
			$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
			$('#myModal'+mid).find(".tax_type_id").val(html.id);
	    }
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

function getprofiledata(id){
	$('.loadingGIF').show();
	var filtervars = {
	    pid:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getprofilesasses',
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
	    		$("#ba_telephone_no2").val(arr[0]['p_telephone_no']);
	    		$("#ba_p_address").val(arr[0]['p_address_street_name']+','+arr[0]['p_address_subdivision']);
	    		$("#ba_business_name").val(arr[0]['ba_business_name']);
	    		$("#ba_address_house_lot_no").val(arr[0]['ba_address_house_lot_no']);
	    		$("#ba_address_street_name").val(arr[0]['ba_address_street_name']);
	    		$('#barangay_id>option:eq('+arr[0]['barangay_id']+')').prop('selected', true);
	    		$("#brgy_name").val(arr[0]['brgy_name']);
	    		$("#ba_building_total_area_occupied").val(arr[0]['ba_building_total_area_occupied']);
	    		$("#p_tin_no").val(arr[0]['p_tin_no']);
	    		if(arr[0]['ba_building_is_owned'] =='1'){ $("#Owned").prop("checked", true);              }
	    		else{ $("#Rented").prop("checked", true);  }	
	    		$("#profile_id").val(arr[0]['profile_id']);
	    		$("#application_id").val(arr[0]['id']);
	    		$("#ba_cover_year").val(arr[0]['ba_cover_year']);
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
	    		$("#applicationdate").val(arr[0]['applicationdate']);
	    		$("#app_type").val(arr[0]['app_type_id']);
	    		$("#ba_taxable_owned_truck_wheeler_10above").val(arr[0]['ba_taxable_owned_truck_wheeler_10above']);
	    		$("#ba_taxable_owned_truck_wheeler_6above").val(arr[0]['ba_taxable_owned_truck_wheeler_6above']);
	    		$("#ba_taxable_owned_truck_wheeler_4above").val(arr[0]['ba_taxable_owned_truck_wheeler_4above']);
	    	}
	    }
	});
}

