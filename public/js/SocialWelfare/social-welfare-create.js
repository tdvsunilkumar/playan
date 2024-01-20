$(document).ready(function(){
    uploadFile()
});
function appendToRequirementsTable(html,requireRows) {
	$('#file-requirements').find('tbody').append(html);
	var select = '#require-newrow-'+requireRows+'n';
	selectAjax(select); // call dynamic select
	callToggle();
	uploadFile()
    console.log('add req');
	// write id for selected req
	$('.select_req').on('change', select, function (e) {
		var id = $(this).val();
		console.log(id);
		$(this).closest('.select_req').find('.select_req_id').val(id);
	});
}
function uploadFile() {
    // for upload file
$('input[type="file"]').change(function(e){
    console.log(file);
    var file = $(this);
    if(file[0].files.length != 0){
        file.closest('.file-upload').find('label').find('i').addClass('ti-check');
        file.closest('.file-upload').find('label').find('i').removeClass('ti-cloud-up');
    
        if (objectURL) {
            // revoke the old object url to avoid using more memory than needed
            URL.revokeObjectURL(objectURL);  
        }
        const upload = file[0].files[0];
        var objectURL = URL.createObjectURL(e.target.files[0]);
        file.closest('.file-upload').next('a').remove();
        file.closest('.file-upload').after('<a href="'+objectURL+'" download="'+upload.name+'" class="btn btn-sm bg-primary small-button" data-bs-toggle="tooltip" title="Download File"><i class="ti-download text-white"></i></a>');
        callToggle();
    }
});
}
function removeRow(slug){
	var token = $("#_csrf_token").val();	
	$('body').on('click', '.remove-row', function (e) {
		button = $(this);
		$(this).closest('.new-row').remove();

		rm = button.data('remove');
		id = button.data('id');
		active = button.data('active');
		var msg = active==1?'restored':'removed';
		if (rm) {
			Swal.fire({
				title: 'Are you sure?',
				text: "This record will be "+msg,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Yes',
				cancelButtonText: 'No',
				reverseButtons: true
			}).then((result) => {
					if(result.isConfirmed){
						var urlActive = DIR + slug + '/active';
						$.ajax({
							type: "POST",
							url: urlActive,
							data: {
								'id': id,
								'type': rm,
								'status': active,
								_token: token
							},
							success: function(response){
								var data = JSON.parse(response); 
								Swal.fire({
									position: 'center',
									icon: 'success',
									title: 'Update Successfully.',
									showConfirmButton: false,
									timer: 1500
								});
								if (active == 0) {
									button.removeClass('btn-danger')
									button.addClass('btn-info');
									button.find('i').removeClass('ti-trash')
									button.find('i').addClass('ti-reload');
									button.data('active',1);
								} else {
									button.removeClass('btn-info')
									button.addClass('btn-danger');
									button.find('i').removeClass('ti-reload')
									button.find('i').addClass('ti-trash');
									button.data('active',0);
								}
								// location.reload();
							}
						});
					}
				}
			)
		}
	});
}