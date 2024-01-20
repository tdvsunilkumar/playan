$(document).ready(function(){
	$(".deleterow").click(function(){
		var id=$(this).attr('id');
		var name=$(this).attr('name');
		DeleteRecord(id,name);
 	});	
});

function DeleteRecord(id,name){
	// alert(id);
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "This action can not be undone. Do you want to continue?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if(result.isConfirmed){
           $.ajax({
		        url :DIR+'CtoPaymentExtensionBasis/deleteAttchment', // json datasource
		        type: "POST", 
		        data: {
		          "id": id, 
		          "name":name,
		          "_token": $("#_csrf_token").val(),
		        },
		        success: function(html){
		        	Swal.fire({
    				  position: 'center',
    				  icon: 'success',
    				  title: 'File Deleted Successfully.',
    				  showConfirmButton: false,
    				  timer: 1500
    				})
		           $(".attacmentDtls").html("");
		        }
		    })
        }
    })
}