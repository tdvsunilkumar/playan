$(document).ready(function(){
	$("#barangay_id").change(function(){
		var id=$(this).val();
		getBarangyaDetails(id);
	})
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






