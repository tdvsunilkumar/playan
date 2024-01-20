$(document).ready(function(){
    $('form').on('change', '.get-barangay', function(){
        	
        var value = $(this).val();
        var group = $(this).closest('.brgy_group');
        brgyWrite(value, group)
        
    });
});

function brgyWrite(value, group){
    var urlBrgyDetails = DIR +'getBrgyDetails';
    var token = $("#_csrf_token").val();
    $.ajax({
        type: "POST",
        url: urlBrgyDetails,
        data: {
            'brgy_id': value,
            _token: token
        },
        success: function(response){
            var res = JSON.parse(response);
            $.each(res, function(resID,resData){
                group.find('.select_'+resID).val(resData);
            });	
        }
    });
}
