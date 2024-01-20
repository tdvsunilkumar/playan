$(document).ready(function(){
    $(".datetimepicker").datetimepicker({
        datepicker:false,
        format:'H:i'
    }); 
    $("#commonModal").find('.body').css({overflow: 'unset'})
    $("#schedule_type").select3({dropdownAutoWidth : false,dropdownParent: $("#schedule_type_parrent")});

    $('#schedule_type').on('change', function() {
        $('#schedule_val').val("");
        loadScheduleValue();
        
    });
    loadScheduleValue();
});

function loadScheduleValue() {
    var filtervars = {
        schedule_type_id:$("#schedule_type").val(),
        schedule_val:$("#schedule_val").val(),
        h_hours:$("#h_hours").val(),
        h_day:$("#h_day").val(),
       "_token": $("#_csrf_token").val()
    }; 
    $.ajax({
       type: "POST",
       url: DIR+'cron-job/getScheduleVal',
       data: filtervars,
       dataType: "html",
       success: function(data){
            $("#divSchduleValue").html(data);
            $(".timepicker").datetimepicker({
                datepicker:false,
                format:'H:i'
            }); 
            $("#day").select3({dropdownAutoWidth : false,dropdownParent: $("#divSchduleValue")});
            $("#schedule_value").select3({dropdownAutoWidth : false,dropdownParent: $("#divSchduleValue")});
            
       }
    });
}

