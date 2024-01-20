$(document).ready(function(){
	var slug = 'social-welfare/travel-clearance-minor';
	var token = $("#_csrf_token").val();	
	var requireRows = 1;
	var minorRows = 1;
	citizenRewrite();
	//remove row both dependent and requirement
	removeRow(slug);
	//add new requirement row
	$('#file-requirements').on('click', '.add-require', function (e) {
		requireRows = requireRows + 1;

		var html = $('#addRequirements').html();
		html = html.replace(/changeid/g, requireRows+'n');
		html = html.replace(/changename/g, '');

		appendToRequirementsTable(html,requireRows)
	});
	//add new minor
	$('#minors-list').on('click', '.add-require', function (e) {
		minorRows = minorRows + 1;
		
		var html = $('#addMinors').html();
		html = html.replace(/changeid/g, minorRows+'n');
		$('#minors-list').find('tbody').append(html);
		var select = '#minors-'+minorRows+'n';
		selectAjax(select);
		citizenRewrite();
		selectNormal('#minors-civil-'+minorRows+'n')
		selectNormal('#minors-gender-'+minorRows+'n')
		callToggle();
	});
	//add new Destination
	$('#destination-list').on('click', '.add-require', function (e) {
		minorRows = minorRows + 1;
		
		var html = $('#addDestination').html();
		html = html.replace(/changeid/g, minorRows+'n');
		$('#destination-list').find('tbody').append(html);
		callToggle();
	});
	// approve
	$('body').on('click', '#approveBtn', function (e) {
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
								$('#approve').val(data.approve_by);
							}
						});
					}
				}
			)
	});

	$('body').on('change', '#wtcm_cashier_id', function(){
		var url = DIR + slug +'/getTransactionDetails';
		var token = $("#_csrf_token").val();	
		var id = $(this).val();
		$.ajax({
			type: "POST",
			url: url,
			data: {
				'id': id,
				_token: token
			},
			success: function(response){
				var res = JSON.parse(response);
				$.each(res, function(resID,resData){
					$('.select_'+resID).val(resData);
				});	
			}
		});
	});
});
