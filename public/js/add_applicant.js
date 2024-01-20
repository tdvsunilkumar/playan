$(document).ready(function(){
	$("#btn_addmore_activity").click(function(){
		$('html, body').stop().animate({
      scrollTop: $("#btn_addmore_activity").offset().top
    }, 600);
		addmoreNature();
	});
	$('.numeric').numeric();
	$(".btn_cancel_activity").click(function(){
		 $(this).closest(".removeactivitydata").remove();
	});
	$("#profile").change(function(){
		var id=$(this).val();
		if(id){ getprofiledata(id); }

	})
	if($("#profile").val()>0){
		getprofiledata($("#profile").val());
	}
   
});

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



function addmoreNature(){
	var html = $("#hidenactivityHtml").html();
	$(".activity-details").append(html);
	$(".btn_cancel_activity").click(function(){
		$(this).closest(".removeactivitydata").remove();
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
	    pid:id,
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'business-permit/application/getprofilesallapplicant',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	arr = $.parseJSON(html);
	    	if(arr.length > 0){
	    		console.log(arr[0]['id']);
	    		$("#owner_postalcode").val(arr[0]['mun_zip_code']);
	    		$("#owner_email").val(arr[0]['p_email_address']);
	    		$("#owner_telephone").val(arr[0]['p_telephone_no']);
	    		$("#owner_mobile").val(arr[0]['p_mobile_no']);
	    		$("#owneraddress").val(arr[0]['p_address_house_lot_no']+','+arr[0]['p_address_street_name']+','+arr[0]['p_address_subdivision']+','+arr[0]['brgy_name']+','+arr[0]['mun_desc']+','+arr[0]['prov_desc']+','+arr[0]['reg_description']);
	    	}
	    }
	});
}

