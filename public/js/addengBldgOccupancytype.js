$(document).ready(function(){
	$("#ebot_is_building").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebot_is_building").val("0");
		}else{
			 $("#ebot_is_building").val("1");
		}
 	});
	
	$("#ebot_is_sanitary").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebot_is_sanitary").val("0");
		}else{
			 $("#ebot_is_sanitary").val("1");
		}
 	});
	
	$("#ebot_is_mechanical").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebot_is_mechanical").val("0");
		}else{
			 $("#ebot_is_mechanical").val("1");
		}
 	});
	
	$("#ebot_is_electrical").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebot_is_electrical").val("0");
		}else{
			 $("#ebot_is_electrical").val("1");
		}
 	});
	
	$("#ebot_is_electronics").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebot_is_electronics").val("0");
		}else{
			 $("#ebot_is_electronics").val("1");
		}
 	});
	
	$("#ebot_is_excavation_and_ground").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebot_is_excavation_and_ground").val("0");
		}else{
			 $("#ebot_is_excavation_and_ground").val("1");
		}
 	});
	
	$("#ebot_is_civil_structural_permit").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebot_is_civil_structural_permit").val("0");
		}else{
			 $("#ebot_is_civil_structural_permit").val("1");
		}
 	});
	
	$("#ebot_is_architectural_permit").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebot_is_architectural_permit").val("0");
		}else{
			 $("#ebot_is_architectural_permit").val("1");
		}
 	});
	
	$("#ebot_is_fencing").change(function(){
 		var ischecked= $(this).is(':checked');
		if(!ischecked){
			 $("#ebot_is_fencing").val("0");
		}else{
			 $("#ebot_is_fencing").val("1");
		}
 	});
});