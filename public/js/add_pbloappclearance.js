$(document).ready(function(){
	
	
	$("#ba_code").change(function(){
		var id=$(this).val();
		if(id){ getAccounrnumber(id); }
	})
	if($("#ba_code").val()>0){
		getAccounrnumber($("#ba_code").val());
		$("#banldetail").removeClass('hide');
	}
});


function formatNumber(nStr){
 nStr += '';
 var x = nStr.split('.');
 var x1 = x[0];
 var x2 = x.length > 1 ? '.' + x[1] : '';
 var rgx = /(\d+)(\d{3})/;
 while (rgx.test(x1)) {
  x1 = x1.replace(rgx, '$1' + ',' + '$2');
 }
 return x1 + x2;
}

function getAccounrnumber(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	};
	$.ajax({
	    type: "GET",
	    url: DIR+'getpdoPbloClearancedetails',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	$("#ba_business_account_no").val(html.ba_business_account_no);
	    	$("#p_code").val(html.profile_id);
	    	$("#brgy_code").val(html.barangay_id);
	    }
	}); 
}

