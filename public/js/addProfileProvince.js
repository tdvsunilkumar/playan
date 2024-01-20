$(document).ready(function(){

	

	$("#reg_no").change(function(){
		var id=$(this).val();
		ProfileProvinceData(id);
	});
	

 
 if($("#reg_no").val()>0){
 	var id = $("#reg_no").val();
 ProfileProvinceData(id);

 }

	
});

function ProfileProvinceData(id){
	$('.loadingGIF').show();
	var filtervars = {
	    id:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'ProfileProvinceData',
	    data: filtervars,
	    dataType: "json",
	    success: function(html){ 
			//alert(html)
	    	$('.loadingGIF').hide();
			$("#reg_no2").val(html.reg_region);
			// $("#reg_no").val(html.reg_no);
	    }
	});
}




