$(document).ready(function(){	
	$('#status').select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
    dashboardSearch()
 	$("#status").change(function(){
        dashboardSearch();
 	});
	 $(".add_health_cert").click(function(){
	console.log("zdcdsxxxxxxxxx");
	});	
	 
});
function dashboardSearch()
{
    var selectedOption = $('#status').val();
    // Make an AJAX request to load the selected view
    $('.loadingGIF').show();
    $.ajax({
        url: DIR+'load-dashboard',
        method: "GET",
        data: { selectedOption: selectedOption },
        success: function (data) {
            $('.loadingGIF').hide();
            // Replace the content of the view container with the loaded view
            $('#viewContainer').html(data);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

