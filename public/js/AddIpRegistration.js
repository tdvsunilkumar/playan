$(document).ready(function () {
    $("#ip_detect").click(function(){
        ipDetect();
    });
    var ipAddressField = $('#ip_address');
    if (ipAddressField.val() === '') {
        ipDetect();
    }
    function ipDetect()
    {
        // If the "ip_address" field is blank, make an AJAX request to fetch the IP address
        $.ajax({
            url: DIR+'get_current_ip_address', // Replace with your server-side endpoint
            method: 'GET',
            data: {
                "_token": $("#_csrf_token").val(),
              },
            success: function (data) {
                // Update the "ip_address" field with the retrieved IP address
                ipAddressField.val(data.ip_address);
            },
            error: function () {
                console.error('Failed to fetch IP address');
            }
        });
    }
});