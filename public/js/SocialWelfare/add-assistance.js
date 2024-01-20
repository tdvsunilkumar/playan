$(document).ready(function(){
	var token = $("#_csrf_token").val();	
	var dependentRows = $("#_dependent_count").val();
	var requireRows = $("#_file_count").val();
	var slug = 'social-welfare/assistance';
	citizenRewrite();
	FormNormal(function(){
		$('#commonModal').modal('hide');
	})

	//add new dependent
	$('#dependant-sec').on('click', '.add-dependent', function (e) {
		dependentRows = dependentRows + 1;
		var html = $('#addDependants').html();
		html = html.replace(/changeid/g, dependentRows);
		$('.tbl_dependant').find('tbody').append(html);

		var select = '#dependent-'+dependentRows;
		selectAjax(select); // call dynamic select
		citizenRewrite();
		callToggle();
	});
	
	//get Requirements list
	var urlRequirements = DIR + slug + '/getRequirements';
	$('form.formDtls').on('change', '#wsat_id', function() {
		var value = $(this).val();
		$.ajax({
			type: "POST",
			url: urlRequirements,
			data: {
				'id': value,
				_token: token
			},
			success: function(response){
				$("tr.require-rows").remove();
				var data = JSON.parse(response); 
				$.each(JSON.parse(response), function(index, val) {
					requireRows = requireRows + 1;
					var reqId = val.wsr_id;
					$("#reqirements-contain").append('<tr class="require-rows new-row"><td>'+val.wsr_description+'</td><td><div class="file-upload bg-info btn btn-sm small-button" data-bs-toggle="tooltip" title="Upload File"><label for="require-'+reqId+'"><i class="ti-cloud-up text-white"></i></label><input type="hidden" name="require['+reqId+'r][req_id]" type="text" value="'+reqId+'"><input type="hidden" name="require['+reqId+'r][req_type]" type="text" value="0"><input type="file" class="form-control required-hide" id="require-'+reqId+'" name="require['+reqId+'r][file]"></div></td><td><a href="#req-sec" class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" title="Remove Requirement"><i class="ti-trash"></i></a></td></tr>');
					callToggle();
				});
				uploadFile()

			}
		})
		
	});

	//add new requirement row
	$('#req-sec').on('click', '.add-require', function (e) {
		requireRows = requireRows + 1;

		var html = $('#addRequirements').html();
		html = html.replace(/changeid/g, requireRows);

		appendToRequirementsTable(html,requireRows)
	});

	//add new family composition case study
	$('#family-social-case').on('click', '.add-require', function (e) {
		requireRows = requireRows + 1;

		var html = $('#addSCDependants').html();
		html = html.replace(/changeid/g, requireRows);

		$('#family-social-case').find('tbody').append(html);
		var select = '#dependent-sc-'+requireRows;
		console.log(select)
		// selectNormal('.dependant-select')
		callToggle();
		selectAjax(select); // call dynamic select
		citizenRewrite();
	});

	//add new treatment case study
	$('#treatment-social-case').on('click', '.add-require', function (e) {
		requireRows = requireRows + 1;

		var html = $('#addSCTreatment').html();
		html = html.replace(/changeid/g, requireRows);

		$('#treatment-social-case').find('tbody').append(html);
		callToggle();
		
	});

	//remove row both dependent and requirement
	removeRow(slug);

	// approve
	$('form.formDtls').on('click', '#approveBtn', function (e) {
		button = $(this);
		var id = $('#id').val();
			Swal.fire({
				title: 'Are you sure?',
				text: "This record will be Approve",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Yes',
				cancelButtonText: 'No',
				reverseButtons: true
			}).then((result) => {
					if(result.isConfirmed){
						console.log(id);
						var urlActive = DIR + slug + '/approve';
						$.ajax({
							type: "POST",
							url: urlActive,
							data: {
								'id': id,
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
								
							}
						});
					}
				}
			)
	});

	// social case appear
	$('form').on('keyup', '#wswa_amount', function() {
		amount_limit = $('#_amount_limit').val();
		amount = $(this).val();
		if (parseFloat(amount_limit) <= parseFloat(amount)) {
			console.log('high');
			$('#social-case-sec').removeClass('hidden');			
		} else {
			console.log('low');
			$('#social-case-sec').addClass('hidden');			
		}
	});
	
	// print

	$('.modal-footer').on('click', '#print_assistance', function (e) {
		url = $('#select_print').val();
		id = $('#id').val();
		got_to_link = DIR + 'digital-sign?url=' + slug + '/' + url + id;
		var win = window.open(got_to_link, '_blank');
		if (win) {
			//Browser has allowed it to be opened
			win.focus();
		} else {
			//Browser has blocked it
			alert('Please allow popups for this website');
		}
	});
	

});

