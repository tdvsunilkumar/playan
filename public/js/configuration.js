$(document).ready(function(){
	$(".submit").click(function(){
		updateSystemSetup();
	}); 
});



function updateSystemSetup(){
	var id =$('#id').val();
    var configuration_value =$("#configuration_value").val();
    var oldconfiguration_value =$("#oldconfiguration_value").val();
    $.ajax({
        url :DIR+'payment-system/side-menu/system-setup/updateconfiguration', // json datasource
        type: "POST", 
        data: {
          "id": id, 
          "configuration_value":configuration_value,
          "oldconfiguration_value":oldconfiguration_value,
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
        	if(html=='Success'){
        		Swal.fire({
				  position: 'center',
				  icon: 'success',
				  title: 'System Setup Updated Successfully.',
				  showConfirmButton: false,
				  timer: 1500
				})
        	}
        }
    })
}