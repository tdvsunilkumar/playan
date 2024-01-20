$(document).ready(function(){
	//$("#btn_search").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
	var yearpickerInput = $('input[name="year"]').val();
$('.yearpicker').yearpicker();
$('.yearpicker').val(yearpickerInput).trigger('change');
$('input[name="year"]').change(function(){
    var selectedYear = $(this).val();
    datatablefunction(selectedYear);
  
});
});


function datatablefunction(year){
  var year = year;
window.location.href= DIR + 'reports-masterlists-percentage-ofbusiness-owned-bysex?year=' + year;

}