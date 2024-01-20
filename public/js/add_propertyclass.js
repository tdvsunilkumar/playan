$(document).ready(function() {
    $(".activeinactive").click(function() {
        var recordid = $(this).attr('id');
        var is_activeinactive = $(this).attr('value');
        ActiveInactiveUpdate(recordid, is_activeinactive);
    });
    $(".activeinactiveActual").click(function() {
        var recordid = $(this).attr('id');
        var is_activeinactive = $(this).attr('value');
        ActiveInactiveUpdateActual(recordid, is_activeinactive);
    });
    datatablefunction();

});


function datatablefunction() {
    // $('#example').DataTable();
}
function ActiveInactiveUpdate(id, is_activeinactive) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You want to Active/Inactive?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: DIR + 'rptPropertysubclassification/ActiveInactive',
                type: "POST",
                data: {
                    "id": id,
                    "is_activeinactive": is_activeinactive,
                    "_token": $("#_csrf_token").val(),
                },
                success: function(response) {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Update Successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Refresh the table data
                       location.reload(true);
                    });
                }
            });
        }
    });
}
function ActiveInactiveUpdateActual(id, is_activeinactive) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You want to Active/Inactive?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: DIR + 'rptpropertyactualuse/ActiveInactive',
                type: "POST",
                data: {
                    "id": id,
                    "is_activeinactive": is_activeinactive,
                    "_token": $("#_csrf_token").val(),
                },
                success: function(response) {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Update Successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Refresh the table data
                       location.reload(true);
                    });
                }
            });
        }
    });
}