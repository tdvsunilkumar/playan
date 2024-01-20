$(document).ready(function(){
    $(".sendEmailDtls").click(function(){
        sendEmailDetails($("#id").val(),$("#user_email").val());
    })
});
function isEmail(email) {
    var regex =/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

function sendEmailDetails(id,email){
    if(email==''){
        Swal.fire({
            title: "Oops...",
            html: "Email Id not found, Please add email id first.",
            icon: "warning",
            type: "warning",
            showCancelButton: false,
            closeOnConfirm: true,
            confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
        });
        return false;
    }else if (isEmail(email) == false) {
        Swal.fire({
            title: "Oops...",
            html: email+' This is invalid email address',
            icon: "warning",
            type: "warning",
            showCancelButton: false,
            closeOnConfirm: true,
            confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
        });
        return false;
    }else{
        const swalWithBootstrapButtons = Swal.mixin({
           customClass: {
               confirmButton: 'btn btn-success',
               cancelButton: 'btn btn-danger'
           },
           buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
           title:"Are you sure?",
           text: 'Are you sure want to send email.',
           icon: 'warning',
           showCancelButton: true,
           confirmButtonText: 'Yes',
           cancelButtonText: 'No',
           reverseButtons: true
        }).then((result) => {
        if(result.isConfirmed){
            showLoader();
            $.ajax({
                url :DIR+'treasury-business-delinquent/sendEmail', // json datasource
                type: "POST", 
                data: {
                  "id": id, 
                 "_token": $("#_csrf_token").val(),
                },
                success: function(html){
                }
            })

            setTimeout(() => {
                hideLoader();
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Email Send Successfully.',
                    showConfirmButton: false,
                    timer: 1500
                })
            }, 500);
        }
    });
    }
}