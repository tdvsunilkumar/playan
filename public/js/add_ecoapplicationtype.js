$(document).ready(function () {
  $("#barangay_id").select3({dropdownAutoWidth : false,dropdownParent: $("#barangay_idparrent")});
  $(".AdditionalProcess").hide();
	$(".est_addtional_infos").click(function() {
		if($(this).is(":checked")) {
			$(".AdditionalProcess").show();
		} else {
			$(".AdditionalProcess").hide();
		}
	});
	valueChanged();
})

function valueChanged()
{
if($('.est_addtional_infos').is(":checked"))   
	$(".AdditionalProcess").show();
else
	$(".AdditionalProcess").hide();
}
