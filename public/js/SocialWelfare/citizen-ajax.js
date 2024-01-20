$(document).ready(function(){
	citizenRewrite();

	// open citizen modal
	$('#commonModal').on('click', '.btn_open_second_modal', function (e) {
		var url = $(this).data('url');
		var title = $(this).data('title');
		if(url != '#'){
			$('#secondModal').modal({backdrop: 'static', keyboard: false});
			$('#secondModal').modal('show');
			showLoader();
			$('.loadingGIF').show();
			$.ajax({
				type: "get",
				url: url,
				dataType: "html",
				success: function(html){ 
					hideLoader();
					$('#2ndModal .body').html(html);
					$('#2ndModal #2ndModal-form-url').val(url);
					if (title) {
						$('#2ndModalTitle').html(title);
					}
					$('#2ndModal .select3').select3();
				},
				error: function(){
				hideLoader();
				}
			});
		}
	});

	// clear modal
	$('#secondModal').on('hidden.bs.modal', function () {
        $('#secondModal .body').empty();
    });
});

function citizenRewrite() {
	$('.citizen_group').on('change', '.get-citizen', function (e) {
		var group = $(this).closest('.citizen_group');
		var value = $(this).val();
		citizenWrite(group, value);
		
	});
}
function citizenWrite(group, value,rewrite={}){
	var urlCitizen = DIR + 'citizens/getCitizen';
	var token = $("#_csrf_token").val();	
	var from = group.find('.ajax-select').attr('id');
	$.ajax({
		type: "POST",
		url: urlCitizen,
		data: {
			'id': value,
			_token: token
		},
		success: function(response){
			var data = JSON.parse(response); 
			
			$.each(data, function(id,value){
				if (value) {
					group.find('.check_'+id).prop('checked', true);
					$('.check-'+from+'_'+id).prop('checked', true);
				} else {
					group.find('.check_'+id).prop('checked', false);
					$('.check-'+from+'_'+id).prop('checked', false);
				}
				group.find('.select_'+id).val(value);
				group.find('.select_'+id).not('select').text(value);
				$('.select-'+from+'_'+id).val(value);
				$('.select-'+from+'_'+id).not('select').text(value);
				switch (id) {
					case 'ccs_id':
						$('.select-'+from+'_'+id).next('span.select3-container').find('.select3-selection__rendered').text(data.status);
						group.find('.select_'+id).next('span.select3-container').find('.select3-selection__rendered').text(data.status);
						break;
					case 'cit_gender':
						$('.select-'+from+'_'+id).next('span.select3-container').find('.select3-selection__rendered').text(data.gender);
						group.find('.select_'+id).next('span.select3-container').find('.select3-selection__rendered').text(data.gender);
						break;
					case 'cea_id':
						$('.select-'+from+'_'+id).next('span.select3-container').find('.select3-selection__rendered').text(data.educ);
						group.find('.select_'+id).next('span.select3-container').find('.select3-selection__rendered').text(data.educ);
						break;
					case 'brgy_id':
							$('.select-'+from+'_'+id).next('span.select3-container').find('.select3-selection__rendered').text(data.brgy_name);
							group.find('.select_'+id).next('span.select3-container').find('.select3-selection__rendered').text(data.brgy_name);
							if (group.find('.select_'+id).hasClass('ajax-select')) {
								group.find('select.ajax-select.select_brgy_id').empty()
								group.find('select.ajax-select.select_brgy_id').append('<option checked value="'+value+'">'+data.brgy_name+', '+data.municipality+', '+data.province+'</option>');
							}
							break;
					case 'id':
						$('.select-'+from+'_'+id).next('span.select3-container').find('.select3-selection__rendered').text(data.cit_fullname);
						group.find('.select_'+id).next('span.select3-container').find('.select3-selection__rendered').text(data.cit_fullname);
						group.find('select.get-citizen.select_'+id).empty()
						group.find('select.get-citizen.select_'+id).append('<option checked value="'+value+'">'+data.cit_fullname+'</option>');
						break;
					default:
						break;
				}
				$.each(rewrite, function(ele,data){
					$(ele).val(data);
					$(ele).text(data);
				});
			});			
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

function callToggle(){
	$('[data-bs-toggle="tooltip"]').tooltip({
		trigger : 'hover'
	});
}