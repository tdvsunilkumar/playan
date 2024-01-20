$(document).ready(function(){
	$("#btn_search").select3({dropdownAutoWidth : false,dropdownParent: $("#nearsearch")});
	
	$("#btn_search").change(function(){
		var year = $(this).val();
 		datatablefunction(year);
 	});	
});


function datatablefunction(year){
  var year = year;
window.location.href= DIR + 'reports-masterlists-noof-business-permit-issued?year=' + year;

}