$(document).ready(function(){
	$("#ebs_is_building").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebs_is_building").val("0");
		}else{
			 $("#ebs_is_building").val("1");
		}
 	});
	
	$("#ebs_is_sanitary").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebs_is_sanitary").val("0");
		}else{
			 $("#ebs_is_sanitary").val("1");
		}
 	});
	
	$("#ebs_is_mechanical").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebs_is_mechanical").val("0");
		}else{
			 $("#ebs_is_mechanical").val("1");
		}
 	});
	
	$("#ebs_is_electrical").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebs_is_electrical").val("0");
		}else{
			 $("#ebs_is_electrical").val("1");
		}
 	});
	
	$("#ebs_is_electronics").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebs_is_electronics").val("0");
		}else{
			 $("#ebs_is_electronics").val("1");
		}
 	});
	
	$("#ebs_is_excavation_and_ground").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebs_is_excavation_and_ground").val("0");
		}else{
			 $("#ebs_is_excavation_and_ground").val("1");
		}
 	});
	
	$("#ebs_is_civil_structural_permit").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebs_is_civil_structural_permit").val("0");
		}else{
			 $("#ebs_is_civil_structural_permit").val("1");
		}
 	});
	
	$("#ebs_is_architectural_permit").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebs_is_architectural_permit").val("0");
		}else{
			 $("#ebs_is_architectural_permit").val("1");
		}
 	});
	
	$("#ebs_is_fencing").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebs_is_fencing").val("0");
		}else{
			 $("#ebs_is_fencing").val("1");
		}
 	});
	
	$("#ebs_is_sign").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebs_is_sign").val("0");
		}else{
			 $("#ebs_is_sign").val("1");
		}
 	});
	
	$("#ebs_is_demolition").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebs_is_demolition").val("0");
		}else{
			 $("#ebs_is_demolition").val("1");
		}
 	});
});