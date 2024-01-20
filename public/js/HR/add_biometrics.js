$(document).ready(function(){
	var token = $("#_csrf_token").val();	
	$('.modal-footer').on('click', '#bioconnect', function (e) {
		button = $(this);
        var ip = $('#bio_ip').val();
        var prox = $('#bio_proxy').val();
        console.log(prox);
        $.ajax({
            type: "POST",
            url: DIR+'hr-biometrics/connect',
            data: {
                'ip': ip,
                'prox': prox,
                _token: token
            },
            success: function(response){
                var data = JSON.parse(response); 
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Approved Successfully.',
                    showConfirmButton: false,
                    timer: 1500
                });
                button.prop('disabled', true);
                $('#bio_model').val(data.data.model)
            }
        });
    });
});
